<?php
defined('BASEPATH') or exit('No direct script access allowed');
/**
 * Handles uploads error with translation texts
 * @param  mixed $error type of error
 * @return mixed
 */
function _perfex_upload_error($error)
{
    $phpFileUploadErrors = array(
        0 => lang('file_uploaded_success'),
        1 => lang('file_exceeds_max_filesize'),
        2 => lang('file_exceeds_maxfile_size_in_form'),
        3 => lang('file_uploaded_partially'),
        4 => lang('file_not_uploaded'),
        6 => lang('file_missing_temporary_folder'),
        7 => lang('file_failed_to_write_to_disk'),
        8 => lang('file_php_extension_blocked'),
    );

    if (isset($phpFileUploadErrors[$error]) && $error != 0) {
        return $phpFileUploadErrors[$error];
    }

    return false;
}
/**
 * Newsfeed post attachments
 * @param  mixed $postid Post ID to add attachments
 * @return array  - Result values
 */
function handle_newsfeed_post_attachments($postid)
{
    if (isset($_FILES['file']) && _perfex_upload_error($_FILES['file']['error'])) {
        header('HTTP/1.0 400 Bad error');
        echo _perfex_upload_error($_FILES['file']['error']);
        die;
    }
    $path = get_upload_path_by_type('newsfeed') . $postid . '/';
    $CI =& get_instance();
    if (isset($_FILES['file']['name'])) {
        do_action('before_upload_newsfeed_attachment', $postid);
        $uploaded_files = false;
        // Get the temp file path
        $tmpFilePath    = $_FILES['file']['tmp_name'];
        // Make sure we have a filepath
        if (!empty($tmpFilePath) && $tmpFilePath != '') {
            _maybe_create_upload_path($path);
            $filename    = unique_filename($path, $_FILES["file"]["name"]);
            $newFilePath = $path . $filename;
            // Upload the file into the temp dir
            if (move_uploaded_file($tmpFilePath, $newFilePath)) {
                $file_uploaded = true;
                $attachment = array();
                $attachment[] = array(
                    'file_name' => $filename,
                    'filetype' => $_FILES["file"]["type"],
                    );
                $CI->misc_model->add_attachment_to_database($postid, 'newsfeed_post', $attachment);
            }
        }
        if ($file_uploaded == true) {
            echo json_encode(array(
                'success' => true,
                'postid' => $postid
            ));
        } else {
            echo json_encode(array(
                'success' => false,
                'postid' => $postid
            ));
        }
    }
}
/**
 * Handles upload for project files
 * @param  mixed $project_id project id
 * @return boolean
 */
function handle_project_file_uploads($project_id)
{
    $filesIDS = array();
    $errors = array();

    if (isset($_FILES['file']['name'])
        && ($_FILES['file']['name'] != '' || is_array($_FILES['file']['name']) && count($_FILES['file']['name']) > 0)) {
        do_action('before_upload_project_attachment', $project_id);

        if (!is_array($_FILES['file']['name'])) {
            $_FILES['file']['name'] = array($_FILES['file']['name']);
            $_FILES['file']['type'] = array($_FILES['file']['type']);
            $_FILES['file']['tmp_name'] = array($_FILES['file']['tmp_name']);
            $_FILES['file']['error'] = array($_FILES['file']['error']);
            $_FILES['file']['size'] = array($_FILES['file']['size']);
        }

        $path        = get_upload_path_by_type('project') . $project_id . '/';

        for ($i = 0; $i < count($_FILES['file']['name']); $i++) {
            if (_perfex_upload_error($_FILES['file']['error'][$i])) {
                $errors[$_FILES['file']['name'][$i]] = _perfex_upload_error($_FILES['file']['error'][$i]);
                continue;
            }

            // Get the temp file path
            $tmpFilePath = $_FILES['file']['tmp_name'][$i];
            // Make sure we have a filepath
            if (!empty($tmpFilePath) && $tmpFilePath != '') {
                _maybe_create_upload_path($path);
                $filename    = unique_filename($path, $_FILES["file"]["name"][$i]);
                $newFilePath = $path . $filename;
                // Upload the file into the company uploads dir
                if (move_uploaded_file($tmpFilePath, $newFilePath)) {
                    $CI =& get_instance();
                    if (is_client_logged_in()) {
                        $contact_id = get_contact_user_id();
                        $staffid = 0;
                    } else {
                        $staffid = get_staff_user_id();
                        $contact_id = 0;
                    }
                        $data = array(
                            'project_id' => $project_id,
                            'file_name' => $filename,
                            'filetype' => $_FILES["file"]["type"][$i],
                            'dateadded' => date('Y-m-d H:i:s'),
                            'staffid' => $staffid,
                            'contact_id' => $contact_id,
                            'subject' => $filename,
                        );
                    if (is_client_logged_in()) {
                        $data['visible_to_customer'] = 1;
                    } else {
                        $data['visible_to_customer'] = ($CI->input->post('visible_to_customer') == 'true' ? 1 : 0);
                    }
                    $CI->db->insert('tblprojectfiles', $data);

                    $insert_id = $CI->db->insert_id();
                    if ($insert_id) {
                        if (is_image($newFilePath)) {
                            create_img_thumb($path, $filename);
                        }
                        array_push($filesIDS, $insert_id);
                    } else {
                        unlink($newFilePath);

                        return false;
                    }
                }
            }
        }
    }

    if (count($filesIDS) > 0) {
        $CI->load->model('projects_model');
        end($filesIDS);
        $lastFileID = key($filesIDS);
        $CI->projects_model->new_project_file_notification($filesIDS[$lastFileID], $project_id);
    }

    if(count($errors) > 0){
        $message = '';
        foreach($errors as $filename => $error_message){
            $message .= $filename . ' - ' . $error_message .'<br />';
        }
        header('HTTP/1.0 400 Bad error');
        echo $message;
        die;
    }

    if(count($filesIDS) > 0){
        return true;
    }

    return false;
}
/**
 * Handle contract attachments if any
 * @param  mixed $contractid
 * @return boolean
 */
function handle_contract_attachment($id)
{
    if (isset($_FILES['file']) && _perfex_upload_error($_FILES['file']['error'])) {
        header('HTTP/1.0 400 Bad error');
        echo _perfex_upload_error($_FILES['file']['error']);
        die;
    }
    if (isset($_FILES['file']['name']) && $_FILES['file']['name'] != '') {
        do_action('before_upload_contract_attachment', $id);
        $path        = get_upload_path_by_type('contract') . $id . '/';
        // Get the temp file path
        $tmpFilePath = $_FILES['file']['tmp_name'];
        // Make sure we have a filepath
        if (!empty($tmpFilePath) && $tmpFilePath != '') {
            _maybe_create_upload_path($path);
            $filename    = unique_filename($path, $_FILES["file"]["name"]);
            $newFilePath = $path . $filename;
            // Upload the file into the company uploads dir
            if (move_uploaded_file($tmpFilePath, $newFilePath)) {
                $CI =& get_instance();
                $attachment = array();
                $attachment[] = array(
                    'file_name'=>$filename,
                    'filetype'=>$_FILES["file"]["type"],
                    );
                $CI->misc_model->add_attachment_to_database($id, 'contract', $attachment);

                return true;
            }
        }
    }

    return false;
}
/**
 * Handle lead attachments if any
 * @param  mixed $leadid
 * @return boolean
 */
function handle_lead_attachments($leadid, $index_name = 'file', $form_activity = false)
{
    if (isset($_FILES[$index_name]) && empty($_FILES[$index_name]['name']) && $form_activity) {
        return;
    }

    if (isset($_FILES[$index_name]) && _perfex_upload_error($_FILES[$index_name]['error'])) {
        header('HTTP/1.0 400 Bad error');
        echo _perfex_upload_error($_FILES[$index_name]['error']);
        die;
    }

    $CI =& get_instance();
    if (isset($_FILES[$index_name]['name']) && $_FILES[$index_name]['name'] != '') {
        do_action('before_upload_lead_attachment', $leadid);
        $path        = get_upload_path_by_type('lead') . $leadid . '/';
        // Get the temp file path
        $tmpFilePath = $_FILES[$index_name]['tmp_name'];
        // Make sure we have a filepath
        if (!empty($tmpFilePath) && $tmpFilePath != '') {
            if (!_upload_extension_allowed($_FILES[$index_name]["name"])) {
                return false;
            }

            _maybe_create_upload_path($path);

            $filename    = unique_filename($path, $_FILES[$index_name]["name"]);
            $newFilePath = $path . $filename;
            // Upload the file into the company uploads dir
            if (move_uploaded_file($tmpFilePath, $newFilePath)) {
                $CI =& get_instance();
                $CI->load->model('Lead_model');
                $data = array();
                $data[] = array(
                    'file_name' => $filename,
                    'filetype' => $_FILES[$index_name]["type"],
                    );
                $CI->Lead_model->add_attachment_to_database($leadid, $data, false, $form_activity);

                return true;
            }
        }
    }

    return false;
}

/**
 * Handle customer attachments if any
 * @param  mixed $customerid
 * @return boolean
 */
function handle_customer_attachments($customerid, $index_name = 'file', $form_activity = false)
{
    if (isset($_FILES[$index_name]) && empty($_FILES[$index_name]['name']) && $form_activity) {
        return;
    }

    if (isset($_FILES[$index_name]) && _perfex_upload_error($_FILES[$index_name]['error'])) {
        header('HTTP/1.0 400 Bad error');
        echo _perfex_upload_error($_FILES[$index_name]['error']);
        die;
    }

    $CI =& get_instance();
    if (isset($_FILES[$index_name]['name']) && $_FILES[$index_name]['name'] != '') {
        do_action('before_upload_customer_attachment', $customerid);
        $path        = get_upload_path_by_type('customer') . $customerid . '/';
        // Get the temp file path
        $tmpFilePath = $_FILES[$index_name]['tmp_name'];
        // Make sure we have a filepath
        if (!empty($tmpFilePath) && $tmpFilePath != '') {
            if (!_upload_extension_allowed($_FILES[$index_name]["name"])) {
                return false;
            }

            _maybe_create_upload_path($path);

            $filename    = unique_filename($path, $_FILES[$index_name]["name"]);
            $newFilePath = $path . $filename;
            // Upload the file into the company uploads dir
            if (move_uploaded_file($tmpFilePath, $newFilePath)) {
                $CI =& get_instance();
                $CI->load->model('Customer_model');
                $data = array();
                $data[] = array(
                    'file_name' => $filename,
                    'filetype' => $_FILES[$index_name]["type"],
                    );
                $CI->Customer_model->add_attachment_to_database($customerid, $data, false, $form_activity);

                return true;
            }
        }
    }

    return false;
}

/**
 * Handle customer internal attachments if any
 * @param  mixed $customerid
 * @return boolean
 */
function handle_customer_internal_attachments($customerid, $index_name = 'file', $form_activity = false)
{
    if (isset($_FILES[$index_name]) && empty($_FILES[$index_name]['name']) && $form_activity) {
        return;
    }

    if (isset($_FILES[$index_name]) && _perfex_upload_error($_FILES[$index_name]['error'])) {
        header('HTTP/1.0 400 Bad error');
        echo _perfex_upload_error($_FILES[$index_name]['error']);
        die;
    }

    $CI =& get_instance();
    if (isset($_FILES[$index_name]['name']) && $_FILES[$index_name]['name'] != '') {
        do_action('before_upload_customer_attachment', $customerid);
        $path        = get_upload_path_by_type('customerinternaldocument') . $customerid . '/';
        // Get the temp file path
        $tmpFilePath = $_FILES[$index_name]['tmp_name'];
        // Make sure we have a filepath
        if (!empty($tmpFilePath) && $tmpFilePath != '') {
            if (!_upload_extension_allowed($_FILES[$index_name]["name"])) {
                return false;
            }

            _maybe_create_upload_path($path);

            $filename    = unique_filename($path, $_FILES[$index_name]["name"]);
            $newFilePath = $path . $filename;
            // Upload the file into the company uploads dir
            if (move_uploaded_file($tmpFilePath, $newFilePath)) {
                $CI =& get_instance();
                $CI->load->model('Customer_model');
                $data = array();
                $data[] = array(
                    'file_name' => $filename,
                    'filetype' => $_FILES[$index_name]["type"],
                    );
                $CI->Customer_model->add_internal_doc_attachment_to_database($customerid, $data, false, $form_activity);
                return true;
            }
        }
    }

    return false;
}

/**
 * Handle lead attachments if any
 * @param  mixed $leadid
 * @return boolean
 */
function handle_quotation_attachments($quotationid, $index_name = 'file', $form_activity = false)
{
    if (isset($_FILES[$index_name]) && empty($_FILES[$index_name]['name']) && $form_activity) {
        return;
    }

    if (isset($_FILES[$index_name]) && _perfex_upload_error($_FILES[$index_name]['error'])) {
        header('HTTP/1.0 400 Bad error');
        echo _perfex_upload_error($_FILES[$index_name]['error']);
        die;
    }

    $CI =& get_instance();
    if (isset($_FILES[$index_name]['name']) && $_FILES[$index_name]['name'] != '') {
        do_action('before_upload_quotation_attachment', $quotationid);
        $path        = get_upload_path_by_type('quotation') . $quotationid . '/';
        // Get the temp file path
        $tmpFilePath = $_FILES[$index_name]['tmp_name'];
        // Make sure we have a filepath
        if (!empty($tmpFilePath) && $tmpFilePath != '') {
            if (!_upload_extension_allowed($_FILES[$index_name]["name"])) {
                return false;
            }

            _maybe_create_upload_path($path);

            $filename    = unique_filename($path, $_FILES[$index_name]["name"]);
            $newFilePath = $path . $filename;
            // Upload the file into the company uploads dir
            if (move_uploaded_file($tmpFilePath, $newFilePath)) {
                $CI =& get_instance();
                $CI->load->model('Quotation_model');
                $data = array();
                $data[] = array(
                    'file_name' => $filename,
                    'filetype' => $_FILES[$index_name]["type"],
                    );
                $CI->Quotation_model->add_attachment_to_database($quotationid, $data, false, $form_activity);

                return true;
            }
        }
    }

    return false;
}

/**
 * Handle lead attachments if any
 * @param  mixed $leadid
 * @return boolean
 */
function handle_leadquotation_attachments($quotationid, $index_name = 'file', $form_activity = false)
{
    if (isset($_FILES[$index_name]) && empty($_FILES[$index_name]['name']) && $form_activity) {
        return;
    }

    if (isset($_FILES[$index_name]) && _perfex_upload_error($_FILES[$index_name]['error'])) {
        header('HTTP/1.0 400 Bad error');
        echo _perfex_upload_error($_FILES[$index_name]['error']);
        die;
    }

    $CI =& get_instance();
    if (isset($_FILES[$index_name]['name']) && $_FILES[$index_name]['name'] != '') {
        do_action('before_upload_leadquotation_attachment', $quotationid);
        $path        = get_upload_path_by_type('leadquotation') . $quotationid . '/';
        // Get the temp file path
        $tmpFilePath = $_FILES[$index_name]['tmp_name'];
        // Make sure we have a filepath
        if (!empty($tmpFilePath) && $tmpFilePath != '') {
            if (!_upload_extension_allowed($_FILES[$index_name]["name"])) {
                return false;
            }

            _maybe_create_upload_path($path);

            $filename    = unique_filename($path, $_FILES[$index_name]["name"]);
            $newFilePath = $path . $filename;
            // Upload the file into the company uploads dir
            if (move_uploaded_file($tmpFilePath, $newFilePath)) {
                $CI =& get_instance();
                $CI->load->model('Leadquotation_model');
                $data = array();
                $data[] = array(
                    'file_name' => $filename,
                    'filetype' => $_FILES[$index_name]["type"],
                    );
                $CI->Leadquotation_model->add_attachment_to_database($quotationid, $data, false, $form_activity);

                return true;
            }
        }
    }

    return false;
}

/**
 * Handle lead attachments if any
 * @param  mixed $leadid
 * @return boolean
 */
function handle_assignment_attachments($assignmentid, $index_name = 'file', $form_activity = false)
{
    if (isset($_FILES[$index_name]) && empty($_FILES[$index_name]['name']) && $form_activity) {
        return;
    }

    if (isset($_FILES[$index_name]) && _perfex_upload_error($_FILES[$index_name]['error'])) {
        header('HTTP/1.0 400 Bad error');
        echo _perfex_upload_error($_FILES[$index_name]['error']);
        die;
    }

    $CI =& get_instance();
    if (isset($_FILES[$index_name]['name']) && $_FILES[$index_name]['name'] != '') {
        do_action('before_upload_assignment_attachment', $assignmentid);
        $path        = get_upload_path_by_type('assignment') . $assignmentid . '/';
        // Get the temp file path
        $tmpFilePath = $_FILES[$index_name]['tmp_name'];
        // Make sure we have a filepath
        if (!empty($tmpFilePath) && $tmpFilePath != '') {
            if (!_upload_extension_allowed($_FILES[$index_name]["name"])) {
                return false;
            }

            _maybe_create_upload_path($path);

            $filename    = unique_filename($path, $_FILES[$index_name]["name"]);
            $newFilePath = $path . $filename;
            // Upload the file into the company uploads dir
            if (move_uploaded_file($tmpFilePath, $newFilePath)) {
                $CI =& get_instance();
                $CI->load->model('Assignment_model');
                $data = array();
                $data[] = array(
                    'file_name' => $filename,
                    'filetype' => $_FILES[$index_name]["type"],
                    );
                $CI->Assignment_model->add_attachment_to_database($assignmentid, $data, false, $form_activity);

                return true;
            }
        }
    }

    return false;
}

/**
 * Handle lead attachments if any
 * @param  mixed $leadid
 * @return boolean
 */
function handle_hardwareassignmentposition_documents($hardwareassignmentproductid, $index_name = 'file', $form_activity = false)
{
    if (isset($_FILES[$index_name]) && empty($_FILES[$index_name]['name']) && $form_activity) {
        return;
    }

    if (isset($_FILES[$index_name]) && _perfex_upload_error($_FILES[$index_name]['error'])) {
        header('HTTP/1.0 400 Bad error');
        echo _perfex_upload_error($_FILES[$index_name]['error']);
        die;
    }

    $CI =& get_instance();
    if (isset($_FILES[$index_name]['name']) && $_FILES[$index_name]['name'] != '') {
        do_action('before_upload_hardwareassignmentpositiondocument_attachment', $hardwareassignmentproductid);
        $path        = get_upload_path_by_type('hardwareassignmentpositiondocument') . $hardwareassignmentproductid . '/';
        // Get the temp file path
        $tmpFilePath = $_FILES[$index_name]['tmp_name'];
        // Make sure we have a filepath
        if (!empty($tmpFilePath) && $tmpFilePath != '') {
            if (!_upload_extension_allowed($_FILES[$index_name]["name"])) {
                return false;
            }

            _maybe_create_upload_path($path);

            $filename    = unique_filename($path, $_FILES[$index_name]["name"]);
            $newFilePath = $path . $filename;
            // Upload the file into the company uploads dir
            if (move_uploaded_file($tmpFilePath, $newFilePath)) {
                $CI =& get_instance();
                $CI->load->model('Hardwareassignmentproduct_model');
                $data = array();
                $data[] = array(
                    'file_name' => $filename,
                    'filetype' => $_FILES[$index_name]["type"],
                    );
                $CI->Hardwareassignmentproduct_model->add_attachment_to_database($hardwareassignmentproductid, $data, false, $form_activity);

                return true;
            }
        }
    }

    return false;
}

/**
 * Handle lead legitimations if any
 * @param  mixed $leadid
 * @return boolean
 */
function handle_assignment_legitimations($assignmentid, $index_name = 'file', $form_activity = false)
{
    if (isset($_FILES[$index_name]) && empty($_FILES[$index_name]['name']) && $form_activity) {
        return;
    }

    if (isset($_FILES[$index_name]) && _perfex_upload_error($_FILES[$index_name]['error'])) {
        header('HTTP/1.0 400 Bad error');
        echo _perfex_upload_error($_FILES[$index_name]['error']);
        die;
    }

    $CI =& get_instance();
    if (isset($_FILES[$index_name]['name']) && $_FILES[$index_name]['name'] != '') {
        do_action('before_upload_assignment_legitimation', $assignmentid);
        $path        = get_upload_path_by_type('legitimation') . $assignmentid . '/';
        // Get the temp file path
        $tmpFilePath = $_FILES[$index_name]['tmp_name'];
        // Make sure we have a filepath
        if (!empty($tmpFilePath) && $tmpFilePath != '') {
            if (!_upload_extension_allowed($_FILES[$index_name]["name"])) {
                return false;
            }

            _maybe_create_upload_path($path);

            $filename    = unique_filename($path, $_FILES[$index_name]["name"]);
            $newFilePath = $path . $filename;
            // Upload the file into the company uploads dir
            if (move_uploaded_file($tmpFilePath, $newFilePath)) {
                $CI =& get_instance();
                $CI->load->model('Assignment_model');
                $data = array();
                $data[] = array(
                    'file_name' => $filename,
                    'filetype' => $_FILES[$index_name]["type"],
                    );
                $CI->Assignment_model->add_legitimation_to_database($assignmentid, $data, false, $form_activity);

                return true;
            }
        }
    }

    return false;
}

/**
 * Handle lead attachments if any
 * @param  mixed $leadid
 * @return boolean
 */
function handle_hardwareassignment_attachments($hardwareassignmentid, $index_name = 'file', $form_activity = false)
{
    if (isset($_FILES[$index_name]) && empty($_FILES[$index_name]['name']) && $form_activity) {
        return;
    }

    if (isset($_FILES[$index_name]) && _perfex_upload_error($_FILES[$index_name]['error'])) {
        header('HTTP/1.0 400 Bad error');
        echo _perfex_upload_error($_FILES[$index_name]['error']);
        die;
    }

    $CI =& get_instance();
    if (isset($_FILES[$index_name]['name']) && $_FILES[$index_name]['name'] != '') {
        do_action('before_upload_hardwareassignment_attachment', $hardwareassignmentid);
        $path        = get_upload_path_by_type('hardwareassignment') . $hardwareassignmentid . '/';
        // Get the temp file path
        $tmpFilePath = $_FILES[$index_name]['tmp_name'];
        // Make sure we have a filepath
        if (!empty($tmpFilePath) && $tmpFilePath != '') {
            if (!_upload_extension_allowed($_FILES[$index_name]["name"])) {
                return false;
            }

            _maybe_create_upload_path($path);

            $filename    = unique_filename($path, $_FILES[$index_name]["name"]);
            $newFilePath = $path . $filename;
            // Upload the file into the company uploads dir
            if (move_uploaded_file($tmpFilePath, $newFilePath)) {
                $CI =& get_instance();
                $CI->load->model('Hardwareassignment_model');
                $data = array();
                $data[] = array(
                    'file_name' => $filename,
                    'filetype' => $_FILES[$index_name]["type"],
                    );
                $CI->Hardwareassignment_model->add_attachment_to_database($hardwareassignmentid, $data, false, $form_activity);

                return true;
            }
        }
    }

    return false;
}

/**
 * Task attachments upload array
 * Multiple task attachments can be upload if input type is array or dropzone plugin is used
 * @param  mixed $taskid     task id
 * @param  string $index_name attachments index, in different forms different index name is used
 * @return mixed
 */
function handle_task_attachments_array($taskid, $index_name = 'attachments')
{
    $uploaded_files = array();
    $path           = get_upload_path_by_type('task') . $taskid . '/';
    $CI = &get_instance();

    if (isset($_FILES[$index_name]['name'])
        && ($_FILES[$index_name]['name'] != '' || is_array($_FILES[$index_name]['name']) && count($_FILES[$index_name]['name']) > 0)) {

        if (!is_array($_FILES[$index_name]['name'])) {
            $_FILES[$index_name]['name'] = array($_FILES[$index_name]['name']);
            $_FILES[$index_name]['type'] = array($_FILES[$index_name]['type']);
            $_FILES[$index_name]['tmp_name'] = array($_FILES[$index_name]['tmp_name']);
            $_FILES[$index_name]['error'] = array($_FILES[$index_name]['error']);
            $_FILES[$index_name]['size'] = array($_FILES[$index_name]['size']);
        }

        _file_attachments_index_fix($index_name);
        for ($i = 0; $i < count($_FILES[$index_name]['name']); $i++) {
            // Get the temp file path
            $tmpFilePath = $_FILES[$index_name]['tmp_name'][$i];

            // Make sure we have a filepath
            if (!empty($tmpFilePath) && $tmpFilePath != '') {

                if (_perfex_upload_error($_FILES[$index_name]['error'][$i])
                    || !_upload_extension_allowed($_FILES[$index_name]["name"][$i])) {
                    continue;
                }

                _maybe_create_upload_path($path);
                $filename    = unique_filename($path, $_FILES[$index_name]["name"][$i]);
                $newFilePath = $path . $filename;

                // Upload the file into the temp dir
                if (move_uploaded_file($tmpFilePath, $newFilePath)) {
                    array_push($uploaded_files, array(
                    'file_name' => $filename,
                    'filetype' => $_FILES[$index_name]["type"][$i]
                    ));
                    if (is_image($newFilePath)) {
                        create_img_thumb($path, $filename);
                    }
                }
            }
        }
    }

    if (count($uploaded_files) > 0) {
        return $uploaded_files;
    }

    return false;
}

/**
 * Invoice attachments
 * @param  mixed $invoiceid invoice ID to add attachments
 * @return array  - Result values
 */
function handle_sales_attachments($rel_id, $rel_type)
{
    if (isset($_FILES['file']) && _perfex_upload_error($_FILES['file']['error'])) {
        header('HTTP/1.0 400 Bad error');
        echo _perfex_upload_error($_FILES['file']['error']);
        die;
    }

    $path = get_upload_path_by_type($rel_type) . $rel_id . '/';

    $CI =& get_instance();
    if (isset($_FILES['file']['name'])) {
        $uploaded_files = false;
        // Get the temp file path
        $tmpFilePath    = $_FILES['file']['tmp_name'];
        // Make sure we have a filepath
        if (!empty($tmpFilePath) && $tmpFilePath != '') {
            // Getting file extension
            $type        = $_FILES["file"]["type"];
            _maybe_create_upload_path($path);
            $filename    = unique_filename($path, $_FILES["file"]["name"]);
            $newFilePath = $path . $filename;
            // Upload the file into the temp dir
            if (move_uploaded_file($tmpFilePath, $newFilePath)) {
                $file_uploaded = true;
                $attachment = array();
                $attachment[] = array(
                    'file_name' => $filename,
                    'filetype' => $type,
                    );
                $insert_id = $CI->misc_model->add_attachment_to_database($rel_id, $rel_type, $attachment);
                // Get the key so we can return to ajax request and show download link
                $CI->db->where('id', $insert_id);
                $_attachment = $CI->db->get('tblfiles')->row();
                $key = $_attachment->attachment_key;

                if ($rel_type == 'invoice') {
                    $CI->load->model('invoices_model');
                    $CI->invoices_model->log_invoice_activity($rel_id, 'invoice_activity_added_attachment');
                } elseif ($rel_type == 'estimate') {
                    $CI->load->model('estimates_model');
                    $CI->estimates_model->log_estimate_activity($rel_id, 'estimate_activity_added_attachment');
                }
            }
        }
        if ($file_uploaded == true) {
            echo json_encode(array(
                'success' => true,
                'attachment_id' => $insert_id,
                'filetype' => $type,
                'rel_id'=>$rel_id,
                'file_name' => $filename,
                'key' => $key,
            ));
        } else {
            echo json_encode(array(
                'success' => false,
                'rel_id' => $rel_id,
                'file_name' => $filename
            ));
        }
    }
}
/**
 * Client attachments
 * @param  mixed $clientid Client ID to add attachments
 * @return array  - Result values
 */
function handle_client_attachments_upload($id, $customer_upload = false)
{
    $path = get_upload_path_by_type('customer') . $id . '/';
    $CI =& get_instance();
    if (isset($_FILES['file']['name'])) {
        do_action('before_upload_client_attachment', $id);
        // Get the temp file path
        $tmpFilePath = $_FILES['file']['tmp_name'];
        // Make sure we have a filepath
        if (!empty($tmpFilePath) && $tmpFilePath != '') {
            _maybe_create_upload_path($path);
            $filename    = unique_filename($path, $_FILES['file']['name']);
            $newFilePath = $path . $filename;
            // Upload the file into the temp dir
            if (move_uploaded_file($tmpFilePath, $newFilePath)) {
                $attachment = array();
                $attachment[]= array(
                    'file_name'=>$filename,
                    'filetype'=>$_FILES["file"]["type"],
                    );
                if (is_image($newFilePath)) {
                    create_img_thumb($newFilePath, $filename);
                }

                if ($customer_upload == true) {
                    $attachment[0]['staffid'] = 0;
                    $attachment[0]['contact_id'] = get_contact_user_id();
                    $attachment['visible_to_customer'] = 1;
                }

                $CI->misc_model->add_attachment_to_database($id, 'customer', $attachment);
            }
        }
    }
}
/**
 * Handles upload for expenses receipt
 * @param  mixed $id expense id
 * @return void
 */
function handle_expense_attachments($id)
{
    if (isset($_FILES['file']) && _perfex_upload_error($_FILES['file']['error'])) {
        header('HTTP/1.0 400 Bad error');
        echo _perfex_upload_error($_FILES['file']['error']);
        die;
    }
    $path = get_upload_path_by_type('expense') . $id . '/';
    $CI =& get_instance();

    if (isset($_FILES['file']['name'])) {
        do_action('before_upload_expense_attachment', $id);
        // Get the temp file path
        $tmpFilePath = $_FILES['file']['tmp_name'];
        // Make sure we have a filepath
        if (!empty($tmpFilePath) && $tmpFilePath != '') {
            _maybe_create_upload_path($path);
            $filename    = $_FILES["file"]["name"];
            $newFilePath = $path . $filename;
            // Upload the file into the temp dir
            if (move_uploaded_file($tmpFilePath, $newFilePath)) {
                $attachment = array();
                $attachment[]= array(
                    'file_name'=>$filename,
                    'filetype'=>$_FILES["file"]["type"],
                    );

                $CI->misc_model->add_attachment_to_database($id, 'expense', $attachment);
            }
        }
    }
}

/**
 * Handle ticket attachments if any
 * @param  mixed $ticketid
 * @return boolean
 */
function handle_ticket_attachments($ticketid, $index_name = 'file', $form_activity = false)
{
    if (isset($_FILES[$index_name]) && empty($_FILES[$index_name]['name']) && $form_activity) {
        return;
    }

    if (isset($_FILES[$index_name]) && _perfex_upload_error($_FILES[$index_name]['error'])) {
        header('HTTP/1.0 400 Bad error');
        echo _perfex_upload_error($_FILES[$index_name]['error']);
        die;
    }

    $CI =& get_instance();
    if (isset($_FILES[$index_name]['name']) && $_FILES[$index_name]['name'] != '') {
        do_action('before_upload_ticket_attachment', $ticketid);
        $path        = get_upload_path_by_type('ticket') . $ticketid . '/';
        // Get the temp file path
        $tmpFilePath = $_FILES[$index_name]['tmp_name'];
        // Make sure we have a filepath
        if (!empty($tmpFilePath) && $tmpFilePath != '') {
            if (!_upload_extension_allowed($_FILES[$index_name]["name"])) {
                return false;
            }

            _maybe_create_upload_path($path);

            $filename    = unique_filename($path, $_FILES[$index_name]["name"]);
            $newFilePath = $path . $filename;
            // Upload the file into the company uploads dir
            if (move_uploaded_file($tmpFilePath, $newFilePath)) {
                $CI =& get_instance();
                $CI->load->model('Ticket_model');
                $data = array();
                $data[] = array(
                    'file_name' => $filename,
                    'filetype' => $_FILES[$index_name]["type"],
                    );
                $CI->Ticket_model->add_attachment_to_database($ticketid, $data, false, $form_activity);

                return true;
            }
        }
    }

    return false;
}


/**
 * Handle document attachments if any
 * @param  mixed $documentid (userid)
 * @return boolean
 */
function handle_userdocument_attachments($documentid, $index_name = 'file', $form_activity = false)
{
    if (isset($_FILES[$index_name]) && empty($_FILES[$index_name]['name']) && $form_activity) {
        return;
    }

    if (isset($_FILES[$index_name]) && _perfex_upload_error($_FILES[$index_name]['error'])) {
        header('HTTP/1.0 400 Bad error');
        echo _perfex_upload_error($_FILES[$index_name]['error']);
        die;
    }

    $CI =& get_instance();
    if (isset($_FILES[$index_name]['name']) && $_FILES[$index_name]['name'] != '') {
        do_action('before_upload_document_attachment', $documentid);
        $path        = get_upload_path_by_type('userdocument') . $documentid . '/';
        // Get the temp file path
        $tmpFilePath = $_FILES[$index_name]['tmp_name'];
        // Make sure we have a filepath
        if (!empty($tmpFilePath) && $tmpFilePath != '') {
            if (!_upload_extension_allowed($_FILES[$index_name]["name"])) {
                return false;
            }

            _maybe_create_upload_path($path);

            $filename    = unique_filename($path, $_FILES[$index_name]["name"]);
            $newFilePath = $path . $filename;
            // Upload the file into the company uploads dir
            if (move_uploaded_file($tmpFilePath, $newFilePath)) {
                $CI =& get_instance();
                $CI->load->model('Document_model');
                $data = array();
                $data[] = array(
                    'file_name' => $filename,
                    'filetype' => $_FILES[$index_name]["type"],
                    );
                $CI->Document_model->add_attachment_to_database($documentid, $data, false, $form_activity);

                return true;
            }
        }
    }

    return false;
}


/**
 * Handle customer document attachments if any
 * @param  mixed $documentid (customerid)
 * @return boolean
 */
function handle_customerdocument_attachments($documentid, $index_name = 'file', $form_activity = false)
{
    if (isset($_FILES[$index_name]) && empty($_FILES[$index_name]['name']) && $form_activity) {
        return;
    }

    if (isset($_FILES[$index_name]) && _perfex_upload_error($_FILES[$index_name]['error'])) {
        header('HTTP/1.0 400 Bad error');
        echo _perfex_upload_error($_FILES[$index_name]['error']);
        die;
    }

    $CI =& get_instance();
    if (isset($_FILES[$index_name]['name']) && $_FILES[$index_name]['name'] != '') {
        do_action('before_upload_customerdocument_attachment', $documentid);
        $path        = get_upload_path_by_type('customerdocument') . $documentid . '/';
        // Get the temp file path
        $tmpFilePath = $_FILES[$index_name]['tmp_name'];
        // Make sure we have a filepath
        if (!empty($tmpFilePath) && $tmpFilePath != '') {
            if (!_upload_extension_allowed($_FILES[$index_name]["name"])) {
                return false;
            }

            _maybe_create_upload_path($path);

            $filename    = unique_filename($path, $_FILES[$index_name]["name"]);
            $newFilePath = $path . $filename;
            // Upload the file into the company uploads dir
            if (move_uploaded_file($tmpFilePath, $newFilePath)) {
                $CI =& get_instance();
                $CI->load->model('Document_model');
                $data = array();
                $data[] = array(
                    'file_name' => $filename,
                    'filetype' => $_FILES[$index_name]["type"],
                    );
                $CI->Document_model->add_attachment_to_database($documentid, $data, false, $form_activity);

                return true;
            }
        }
    }

    return false;
}

/**
 * Check for company logo upload
 * @return boolean
 */
function handle_company_logo_upload()
{
    if (isset($_FILES['company_logo']) && _perfex_upload_error($_FILES['company_logo']['error'])) {
        set_alert('warning', _perfex_upload_error($_FILES['company_logo']['error']));

        return false;
    }
    if (isset($_FILES['company_logo']['name']) && $_FILES['company_logo']['name'] != '') {
        do_action('before_upload_company_logo_attachment');
        $path        = get_upload_path_by_type('company');
        // Get the temp file path
        $tmpFilePath = $_FILES['company_logo']['tmp_name'];
        // Make sure we have a filepath
        if (!empty($tmpFilePath) && $tmpFilePath != '') {
            // Getting file extension
            $path_parts         = pathinfo($_FILES["company_logo"]["name"]);
            $extension          = $path_parts['extension'];
            $extension = strtolower($extension);
            $allowed_extensions = array(
                'jpg',
                'jpeg',
                'png',
                'gif',
            );

            if (!in_array($extension, $allowed_extensions)) {
                set_alert('warning', 'Image extension not allowed.');

                return false;
            }

            // Setup our new file path
            $filename    = 'logo' . '.' . $extension;
            $newFilePath = $path . $filename;
            _maybe_create_upload_path($path);
            // Upload the file into the company uploads dir
            if (move_uploaded_file($tmpFilePath, $newFilePath)) {
                update_option('company_logo', $filename);

                return true;
            }
        }
    }

    return false;
}
/**
 * Check for company logo upload
 * @return boolean
 */
function handle_company_signature_upload()
{
    if (isset($_FILES['signature_image']) && _perfex_upload_error($_FILES['signature_image']['error'])) {
        set_alert('warning', _perfex_upload_error($_FILES['signature_image']['error']));

        return false;
    }
    if (isset($_FILES['signature_image']['name']) && $_FILES['signature_image']['name'] != '') {
        do_action('before_upload_signature_image_attachment');
        $path        = get_upload_path_by_type('company');
        // Get the temp file path
        $tmpFilePath = $_FILES['signature_image']['tmp_name'];
        // Make sure we have a filepath
        if (!empty($tmpFilePath) && $tmpFilePath != '') {
            // Getting file extension
            $path_parts         = pathinfo($_FILES["signature_image"]["name"]);
            $extension          = $path_parts['extension'];
            $extension = strtolower($extension);

            $allowed_extensions = array(
                'jpg',
                'jpeg',
                'png',
            );
            if (!in_array($extension, $allowed_extensions)) {
                set_alert('warning', 'Image extension not allowed.');

                return false;
            }
            // Setup our new file path
            $filename    = 'signature' . '.' . $extension;
            $newFilePath = $path . $filename;
            _maybe_create_upload_path($path);
            // Upload the file into the company uploads dir
            if (move_uploaded_file($tmpFilePath, $newFilePath)) {
                update_option('signature_image', $filename);

                return true;
            }
        }
    }

    return false;
}
/**
 * Handle company favicon upload
 * @return boolean
 */
function handle_favicon_upload()
{
    if (isset($_FILES['favicon']['name']) && $_FILES['favicon']['name'] != '') {
        do_action('before_upload_favicon_attachment');
        $path        = get_upload_path_by_type('company');
        // Get the temp file path
        $tmpFilePath = $_FILES['favicon']['tmp_name'];
        // Make sure we have a filepath
        if (!empty($tmpFilePath) && $tmpFilePath != '') {
            // Getting file extension
            $path_parts  = pathinfo($_FILES["favicon"]["name"]);
            $extension   = $path_parts['extension'];
            $extension = strtolower($extension);
            // Setup our new file path
            $filename    = 'favicon' . '.' . $extension;
            $newFilePath = $path . $filename;
            _maybe_create_upload_path($path);
            // Upload the file into the company uploads dir
            if (move_uploaded_file($tmpFilePath, $newFilePath)) {
                update_option('favicon', $filename);

                return true;
            }
        }
    }

    return false;
}

/**
 * Maybe upload user profile image
 * @param  string $user_id user_id or current logged in user id will be used if not passed
 * @return boolean
 */
function handle_user_profile_image_upload($user_id = '')
{
    /*if (!is_numeric($user_id)) {
        $user_id = get_user_id();
    }*/

    if (isset($_FILES['userthumb']['name']) && $_FILES['userthumb']['name'] != '') {
        do_action('before_upload_user_userthumb');
        $path        = get_upload_path_by_type('user') . $user_id . '/';
        // Get the temp file path
        $tmpFilePath = $_FILES['userthumb']['tmp_name'];
        // Make sure we have a filepath
        if (!empty($tmpFilePath) && $tmpFilePath != '') {
            // Getting file extension
            $path_parts         = pathinfo($_FILES["userthumb"]["name"]);
            $extension          = $path_parts['extension'];
            $extension = strtolower($extension);
            $allowed_extensions = array(
                'jpg',
                'jpeg',
                'png'
            );
            if (!in_array($extension, $allowed_extensions)) {
                set_alert('warning', lang('page_form_validation_file_php_extension_blocked'));

                return false;
            }
            _maybe_create_upload_path($path);
            $filename    = unique_filename($path, $_FILES["userthumb"]["name"]);
            $newFilePath = $path . '/' . $filename;
            // Upload the file into the company uploads dir
            if (move_uploaded_file($tmpFilePath, $newFilePath)) {
                $CI =& get_instance();
                $config                   = array();
                $config['image_library']  = 'gd2';
                $config['source_image']   = $newFilePath;
                $config['new_image']      = 'thumb_' . $filename;
                $config['maintain_ratio'] = true;
                $config['width']          = 160;
                $config['height']         = 160;
                $CI->image_lib->initialize($config);
                $CI->image_lib->resize();
                $CI->image_lib->clear();
                $config['image_library']  = 'gd2';
                $config['source_image']   = $newFilePath;
                $config['new_image']      = 'small_' . $filename;
                $config['maintain_ratio'] = true;
                $config['width']          = 32;
                $config['height']         = 32;
                $CI->image_lib->initialize($config);
                $CI->image_lib->resize();
                $CI->db->where('userid', $user_id);
                $CI->db->update('tblusers', array(
                    'userthumb' => $filename
                ));
                // Remove original image
                unlink($newFilePath);

                return true;
            }
        }
    }

    return false;
}


/**
 * Delete User Profile Image
*/
function handle_user_profile_image_delete($user_id,$dirname=''){
    do_action('before_delete_user_userthumb');

    if($dirname==""){
        $dirname = get_upload_path_by_type('user') . $user_id . '/';
    }

    if(is_dir($dirname))
         $dir_handle = opendir($dirname);
    if (!$dir_handle)
        return false;
    while($file = readdir($dir_handle)) {
        if ($file != "." && $file != "..") {
            if (!is_dir($dirname."/".$file))
            {
                 unlink($dirname."/".$file);
            }
            else
            {
                 delete_directory($user_id, $dirname.'/'.$file);
            }
        }
    }
    closedir($dir_handle);
    rmdir($dirname);
    return true;
}

/**
 * Maybe upload customer profile image
 * @param  string $customer_id user_id or current logged in user id will be used if not passed
 * @return boolean
 */
function handle_customer_profile_image_upload($customer_id = '')
{
    /*if (!is_numeric($customer_id)) {
        $customer_id = get_user_id();
    }*/

    if (isset($_FILES['customerthumb']['name']) && $_FILES['customerthumb']['name'] != '') {
        do_action('before_upload_customer_customerthumb');
        $path        = get_upload_path_by_type('customerp') . $customer_id . '/';
        // Get the temp file path
        $tmpFilePath = $_FILES['customerthumb']['tmp_name'];
        // Make sure we have a filepath
        if (!empty($tmpFilePath) && $tmpFilePath != '') {
            // Getting file extension
            $path_parts         = pathinfo($_FILES["customerthumb"]["name"]);
            $extension          = $path_parts['extension'];
            $extension = strtolower($extension);
            $allowed_extensions = array(
                'jpg',
                'jpeg',
                'png'
            );
            if (!in_array($extension, $allowed_extensions)) {
                set_alert('warning', lang('page_form_validation_file_php_extension_blocked'));

                return false;
            }

            _maybe_create_upload_path($path);

            $filename    = unique_filename($path, $_FILES["customerthumb"]["name"]);
            $newFilePath = $path . '/' . $filename;
            // Upload the file into the company uploads dir
            if (move_uploaded_file($tmpFilePath, $newFilePath)) {
                $CI =& get_instance();
                $config                   = array();
                $config['image_library']  = 'gd2';
                $config['source_image']   = $newFilePath;
                $config['new_image']      = 'thumb_' . $filename;
                $config['maintain_ratio'] = true;
                $config['width']          = 160;
                $config['height']         = 160;
                $CI->image_lib->initialize($config);
                $CI->image_lib->resize();
                $CI->image_lib->clear();
                $config['image_library']  = 'gd2';
                $config['source_image']   = $newFilePath;
                $config['new_image']      = 'small_' . $filename;
                $config['maintain_ratio'] = true;
                $config['width']          = 32;
                $config['height']         = 32;
                $CI->image_lib->initialize($config);
                $CI->image_lib->resize();
                $CI->db->where('customernr', $customer_id);
                $CI->db->update('tblcustomers', array(
                    'customerthumb' => $filename
                ));
                // Remove original image
                unlink($newFilePath);

                return true;
            }
        }
    }

    return false;
}


/**
 * Delete Customer Profile Image
*/
function handle_customer_profile_image_delete($customer_id,$dirname=''){
    do_action('before_delete_customer_customerthumb');

    if($dirname==""){
        $dirname = get_upload_path_by_type('customerp') . $customer_id . '/';
    }

    if(is_dir($dirname))
         $dir_handle = opendir($dirname);
    if (!$dir_handle)
        return false;
    while($file = readdir($dir_handle)) {
        if ($file != "." && $file != "..") {
            if (!is_dir($dirname."/".$file))
            {
                 unlink($dirname."/".$file);
            }
            else
            {
                 delete_directory($customer_id, $dirname.'/'.$file);
            }
        }
    }
    closedir($dir_handle);
    rmdir($dirname);
    return true;
}


/**
 * ASSIGNMENT PROVIDER LOGO UPLOAD
 * @param  string $customer_id user_id or current logged in user id will be used if not passed
 * @return boolean
 */
function handle_assignment_provider_logo_upload($assignment_id = '')
{
    /*if (!is_numeric($customer_id)) {
        $customer_id = get_user_id();
    }*/
  // echo "<pre>";
  //   print_r($_FILES);
  //   die();

    if (isset($_FILES['provider_logo']['name']) && $_FILES['provider_logo']['name'] != '') {
        // do_action('before_upload_customer_customerthumb');
        $path        = get_upload_path_by_type('assignment_provider') . $assignment_id . '/';
        // Get the temp file path
        $tmpFilePath = $_FILES['provider_logo']['tmp_name'];

        // Make sure we have a filepath
        $CI =& get_instance();
        if (!empty($tmpFilePath) && $tmpFilePath != '') {
            // Getting file extension
                $path_parts         = pathinfo($_FILES["provider_logo"]["name"]);
                $extension          = $path_parts['extension'];
                $extension = strtolower($extension);
                $allowed_extensions = array(
                    'jpg',
                    'jpeg',
                    'png'
                );
                if (!in_array($extension, $allowed_extensions)) {
                    set_alert('warning', lang('page_form_validation_file_php_extension_blocked'));

                    return false;
                }

                // _maybe_create_upload_path($path);

                $filename    = unique_filename($path, $_FILES["provider_logo"]["name"]);
                $newFilePath = $path . '/' . $filename;
                $upPath = $path;

                if(!file_exists($upPath))  {
                    mkdir($upPath, 0777, true);
                }

                $config = array(
                    'upload_path' => $upPath,
                    'allowed_types' => "gif|jpg|png|jpeg",
                    'overwrite' => TRUE,
                );

                $CI->load->library('upload', $config);
                $CI->load->initialize($config);
                if(!$CI->upload->do_upload('provider_logo')) {
                    $data['imageError'] =  $CI->upload->display_errors();
                } else {
                    $imageDetailArray = $CI->upload->data();
                    $image =  $imageDetailArray['file_name'];

                    $CI->db->where('assignmentnr', $assignment_id);
                    $CI->db->update('tblassignments', array(
                        'provider_logo' => $image
                    ));
                }
                return true;
        }
    }

    return false;
}


/**
 * ASSIGNMENT PROVIDER LOGO DELETE
*/
function handle_assignment_provider_logo_delete($assignment_id,$dirname=''){
    // do_action('before_delete_customer_customerthumb');

    if($dirname==""){
        $dirname = get_upload_path_by_type('assignment_provider') . $assignment_id . '/';
    }

    if(is_dir($dirname))
         $dir_handle = opendir($dirname);
    if (!$dir_handle)
        return false;
    while($file = readdir($dir_handle)) {
        if ($file != "." && $file != "..") {
            if (!is_dir($dirname."/".$file))
            {
                 unlink($dirname."/".$file);
            }
            else
            {
                 delete_directory($assignment_id, $dirname.'/'.$file);
            }
        }
    }
    closedir($dir_handle);
    rmdir($dirname);
    return true;
}
/**
 * Maybe upload contact profile image
 * @param  string $contact_id contact_id or current logged in contact id will be used if not passed
 * @return boolean
 */
function handle_contact_profile_image_upload($contact_id = '')
{
    if (isset($_FILES['profile_image']['name']) && $_FILES['profile_image']['name'] != '') {
        do_action('before_upload_contact_profile_image');
        if ($contact_id == '') {
            $contact_id = get_contact_user_id();
        }
        $path        = get_upload_path_by_type('contact_profile_images') . $contact_id . '/';
        // Get the temp file path
        $tmpFilePath = $_FILES['profile_image']['tmp_name'];
        // Make sure we have a filepath
        if (!empty($tmpFilePath) && $tmpFilePath != '') {
            // Getting file extension
            $path_parts         = pathinfo($_FILES["profile_image"]["name"]);
            $extension          = $path_parts['extension'];
            $extension = strtolower($extension);
            $allowed_extensions = array(
                'jpg',
                'jpeg',
                'png'
            );
            if (!in_array($extension, $allowed_extensions)) {
                set_alert('warning', _l('file_php_extension_blocked'));

                return false;
            }
            _maybe_create_upload_path($path);
            $filename    = unique_filename($path, $_FILES["profile_image"]["name"]);
            $newFilePath = $path . $filename;
            // Upload the file into the company uploads dir
            if (move_uploaded_file($tmpFilePath, $newFilePath)) {
                $CI =& get_instance();
                $config                   = array();
                $config['image_library']  = 'gd2';
                $config['source_image']   = $newFilePath;
                $config['new_image']      = 'thumb_' . $filename;
                $config['maintain_ratio'] = true;
                $config['width']          = 160;
                $config['height']         = 160;
                $CI->image_lib->initialize($config);
                $CI->image_lib->resize();
                $CI->image_lib->clear();
                $config['image_library']  = 'gd2';
                $config['source_image']   = $newFilePath;
                $config['new_image']      = 'small_' . $filename;
                $config['maintain_ratio'] = true;
                $config['width']          = 32;
                $config['height']         = 32;
                $CI->image_lib->initialize($config);
                $CI->image_lib->resize();

                $CI->db->where('id', $contact_id);
                $CI->db->update('tblcontacts', array(
                    'profile_image' => $filename
                ));
                // Remove original image
                unlink($newFilePath);

                return true;
            }
        }
    }

    return false;
}
/**
 * Handle upload for project discussions comment
 * Function for jquery-comment plugin
 * @param  mixed $discussion_id discussion id
 * @param  mixed $post_data     additional post data from the comment
 * @param  array $insert_data   insert data to be parsed if needed
 * @return arrray
 */
function handle_project_discussion_comment_attachments($discussion_id, $post_data, $insert_data)
{
    if (isset($_FILES['file']['name']) && _perfex_upload_error($_FILES['file']['error'])) {
        header('HTTP/1.0 400 Bad error');
        echo json_encode(array('message'=>_perfex_upload_error($_FILES['file']['error'])));
        die;
    }

    if (isset($_FILES['file']['name'])) {
        do_action('before_upload_project_discussion_comment_attachment');
        $path = PROJECT_DISCUSSION_ATTACHMENT_FOLDER .$discussion_id . '/';
        // Check for all cases if this extension is allowed
        if (!_upload_extension_allowed($_FILES["file"]["name"])) {
            header('HTTP/1.0 400 Bad error');
            echo json_encode(array('message'=>_l('file_php_extension_blocked')));
            die;
        }

        // Get the temp file path
        $tmpFilePath = $_FILES['file']['tmp_name'];
        // Make sure we have a filepath
        if (!empty($tmpFilePath) && $tmpFilePath != '') {
            _maybe_create_upload_path($path);
            $filename    = unique_filename($path, $_FILES['file']['name']);
            $newFilePath = $path . $filename;
            // Upload the file into the temp dir
            if (move_uploaded_file($tmpFilePath, $newFilePath)) {
                $insert_data['file_name'] = $filename;

                if (isset($_FILES['file']['type'])) {
                    $insert_data['file_mime_type'] = $_FILES['file']['type'];
                } else {
                    $insert_data['file_mime_type'] = get_mime_by_extension($filename);
                }
            }
        }
    }

    return $insert_data;
}

/**
 * Maybe upload Product image
 * @param  string $product_id product_id or current logged in user id will be used if not passed
 * @return boolean
 */
function handle_assignment_invoicefile_upload($assignment_id, $bill_id = '')
{
    /*if (!is_numeric($bill_id)) {
        $bill_id = get_bill_id();
    }*/
    if (isset($_FILES['invoicefile']['name']) && $_FILES['invoicefile']['name'] != '') {
        do_action('before_upload_product_invoicefile');

        _maybe_create_upload_path(get_upload_path_by_type('assignment') . $assignment_id.'/');
        $path = get_upload_path_by_type('assignment') . $assignment_id . '/bills/';

        // Get the temp file path
        $tmpFilePath = $_FILES['invoicefile']['tmp_name'];
        // Make sure we have a filepath
        if (!empty($tmpFilePath) && $tmpFilePath != '') {
            // Getting file extension
            $path_parts         = pathinfo($_FILES["invoicefile"]["name"]);
            $extension          = $path_parts['extension'];
            $extension = strtolower($extension);
            $allowed_extensions = array(
                'pdf'
            );
            if (!in_array($extension, $allowed_extensions)) {
                set_alert('warning', lang('page_form_validation_file_php_extension_blocked'));

                return false;
            }
            _maybe_create_upload_path($path);
            $filename    = unique_filename($path, $_FILES["invoicefile"]["name"]);
            $newFilePath = $path . '/' . $filename;

            // Upload the file into the company uploads dir
            if (move_uploaded_file($tmpFilePath, $newFilePath)) {
                $CI =& get_instance();
                $CI->db->where('billnr', $bill_id);
                $CI->db->update('tblbills', array(
                    'invoicefile' => $filename,
                    'invoicefiletype' => $_FILES["invoicefile"]["type"],
                ));
                return true;
            }
        }
    }

    return false;
}

/**
 * Maybe upload Product image
 * @param  string $product_id product_id or current logged in user id will be used if not passed
 * @return boolean
 */

function handle_assignment_invoicefilecsv_upload($assignment_id, $bill_id = '')
{
    /*if (!is_numeric($bill_id)) {
        $bill_id = get_bill_id();
    }*/

    if (isset($_FILES['invoicefilecsv']['name']) && $_FILES['invoicefilecsv']['name'] != '') {
        do_action('before_upload_product_invoicefile');

        _maybe_create_upload_path(get_upload_path_by_type('assignment') . $assignment_id.'/');
        $path = get_upload_path_by_type('assignment') . $assignment_id . '/bills/';

        // Get the temp file path
        $tmpFilePath = $_FILES['invoicefilecsv']['tmp_name'];
        // Make sure we have a filepath
        if (!empty($tmpFilePath) && $tmpFilePath != '') {
            // Getting file extension
            $path_parts         = pathinfo($_FILES["invoicefilecsv"]["name"]);
            $extension          = $path_parts['extension'];
            $extension = strtolower($extension);
            $allowed_extensions = array(
                'csv'
            );
            if (!in_array($extension, $allowed_extensions)) {
                set_alert('warning', lang('page_form_validation_file_php_extension_blocked'));

                return false;
            }
            _maybe_create_upload_path($path);
            $filename    = unique_filename($path, $_FILES["invoicefilecsv"]["name"]);
            $newFilePath = $path . '/' . $filename;

            // Upload the file into the company uploads dir
            if (move_uploaded_file($tmpFilePath, $newFilePath)) {
                $CI =& get_instance();
                $CI->db->where('billnr', $bill_id);
                $CI->db->update('tblbills', array(
                    'invoicefilecsv' => $filename,
                    'invoicefilecsvtype' => $_FILES["invoicefilecsv"]["type"],
                ));
                return true;
            }
        }
    }

    return false;
}


function handle_infodocument_attachments($documentid)
{
    /*if (!is_numeric($bill_id)) {
        $bill_id = get_bill_id();
    }*/

    if (isset($_FILES['documentfile']['name']) && $_FILES['documentfile']['name'] != '') {
        do_action('before_upload_infodocumentfile');
        $CI =& get_instance();

        $path = get_upload_path_by_type('infodocument') . $documentid . '/';

        // Get the temp file path
        $tmpFilePath = $_FILES['documentfile']['tmp_name'];
        // Make sure we have a filepath
        if (!empty($tmpFilePath) && $tmpFilePath != '') {
            // Getting file extension
            $path_parts         = pathinfo($_FILES["documentfile"]["name"]);
            $extension          = $path_parts['extension'];
            $extension = strtolower($extension);
            $allowed_extensions = array(
                'pdf'
            );
            if (!in_array($extension, $allowed_extensions)) {
                set_alert('warning', lang('page_form_validation_file_php_extension_blocked'));

                return false;
            }
            _maybe_create_upload_path($path);
            $filename    = unique_filename($path, $_FILES["documentfile"]["name"]);
            $newFilePath = $path . '/' . $filename;

            // Upload the file into the company uploads dir
            if (move_uploaded_file($tmpFilePath, $newFilePath)) {
                $CI->db->where('documentnr', $documentid);
                $_attachment = $CI->db->get('tblinfodocuments')->row();
                if ($_attachment->documentfile != '') {
                    unlink($path.'/'.$_attachment->documentfile);
                }
                $CI->db->where('documentnr', $documentid);
                $CI->db->update('tblinfodocuments', array(
                    'documentfile' => $filename,
                    'documentfiletype' => $_FILES["documentfile"]["type"],
                ));
                return true;
            }
        }
    }

    return false;
}
/**
 * Create thumbnail from image
 * @param  string  $path     imat path
 * @param  string  $filename filename to store
 * @param  integer $width    width of thumb
 * @param  integer $height   height of thumb
 * @return null
 */
function create_img_thumb($path, $filename, $width = 300, $height = 300)
{
    $CI = &get_instance();

    $source_path = rtrim($path, '/') . '/' . $filename;
    $target_path = $path;
    $config_manip = array(
        'image_library' => 'gd2',
        'source_image' => $source_path,
        'new_image' => $target_path,
        'maintain_ratio' => true,
        'create_thumb' => true,
        'thumb_marker' => '_thumb',
        'width' => $width,
        'height' => $height
    );

    $CI->image_lib->initialize($config_manip);
    $CI->image_lib->resize();
    $CI->image_lib->clear();
}

/**
 * Check if extension is allowed for upload
 * @param  string $filename filename
 * @return boolean
 */
function _upload_extension_allowed($filename)
{
    $path_parts         = pathinfo($filename);
    $extension          = $path_parts['extension'];
    $extension = strtolower($extension);
    $allowed_extensions = explode(',', get_option('allowed_files'));
    $allowed_extensions = array_map('trim', $allowed_extensions);
    // Check for all cases if this extension is allowed
    if (!in_array('.'.$extension, $allowed_extensions)) {
        return false;
    }

    return true;
}

/**
 * Performs fixes when $_FILES is array and the index is messed up
 * Eq user click on + then remove the file and then added new file
 * In this case the indexes will be 0,2 - 1 is missing because it's removed but they should be 0,1
 * @param  string $index_name $_FILES index name
 * @return null
 */
function _file_attachments_index_fix($index_name)
{
    if (isset($_FILES[$index_name]['name']) && is_array($_FILES[$index_name]['name'])) {
        $_FILES[$index_name]['name'] = array_values($_FILES[$index_name]['name']);
    }

    if (isset($_FILES[$index_name]['type']) && is_array($_FILES[$index_name]['type'])) {
        $_FILES[$index_name]['type'] = array_values($_FILES[$index_name]['type']);
    }

    if (isset($_FILES[$index_name]['tmp_name']) && is_array($_FILES[$index_name]['tmp_name'])) {
        $_FILES[$index_name]['tmp_name'] = array_values($_FILES[$index_name]['tmp_name']);
    }

    if (isset($_FILES[$index_name]['error']) && is_array($_FILES[$index_name]['error'])) {
        $_FILES[$index_name]['error'] = array_values($_FILES[$index_name]['error']);
    }

    if (isset($_FILES[$index_name]['size']) && is_array($_FILES[$index_name]['size'])) {
        $_FILES[$index_name]['size'] = array_values($_FILES[$index_name]['size']);
    }
}

/**
 * Check if path exists if not exists will create one
 * This is used when uploading files
 * @param  string $path path to check
 * @return null
 */
function _maybe_create_upload_path($path)
{
    if (!file_exists($path)) {
        mkdir($path);
        fopen($path . 'index.html', 'w');
    }
}

/**
 * Function that return full path for upload based on passed type
 * @param  string $type
 * @return string
 */
function get_upload_path_by_type($type)
{
    switch ($type) {
        case 'lead':
            return LEAD_ATTACHMENTS_FOLDER;
        break;
        case 'expense':
            return EXPENSE_ATTACHMENTS_FOLDER;
        break;
        case 'project':
            return PROJECT_ATTACHMENTS_FOLDER;
        break;
        case 'proposal':
            return PROPOSAL_ATTACHMENTS_FOLDER;
        break;
        case 'estimate':
            return ESTIMATE_ATTACHMENTS_FOLDER;
        break;
        case 'invoice':
            return INVOICE_ATTACHMENTS_FOLDER;
        break;
        case 'credit_note':
            return CREDIT_NOTES_ATTACHMENTS_FOLDER;
        break;
        case 'task':
            return TASKS_ATTACHMENTS_FOLDER;
        break;
        case 'contract':
            return CONTRACTS_UPLOADS_FOLDER;
        break;
        case 'customer':
            return CUSTOMER_ATTACHMENTS_FOLDER;
        break;
        case 'customerp':
            return CUSTOMER_PROFILE_IMAGES_FOLDER;
        break;
        case 'user':
        return USER_PROFILE_IMAGES_FOLDER;
        break;
        case 'company':
        return COMPANY_FILES_FOLDER;
        break;
        case 'ticket':
        return TICKET_ATTACHMENTS_FOLDER;
        break;
        case 'contact_profile_images':
        return CONTACT_PROFILE_IMAGES_FOLDER;
        break;
        case 'newsfeed':
        return NEWSFEED_FOLDER;
        break;
        case 'quotation':
            return QUOTATION_ATTACHMENTS_FOLDER;
        break;
        case 'assignment':
            return ASSIGNMENT_ATTACHMENTS_FOLDER;
        break;
        case 'assignment_provider':
            return ASSIGNMENT_PROVIDER_ATTACHMENTS_FOLDER;
        break;
        case 'hardwareassignment':
            return HARDWARE_ASSIGNMENT_ATTACHMENTS_FOLDER;
        break;
        case 'legitimation':
            return ASSIGNMENT_LEGITIMATIONS_FOLDER;
        break;
        case 'userdocument':
            return USERDOCUMENT_ATTACHMENTS_FOLDER;
        break;
        case 'customerdocument':
            return CUSTOMERDOCUMENT_ATTACHMENTS_FOLDER;
        break;
        case 'customerinternaldocument':
            return CUSTOMERINTERNALDOCUMENT_ATTACHMENTS_FOLDER;
        break;
        case 'infodocument':
            return INFODOCUMENT_ATTACHMENTS_FOLDER;
        break;
        case 'hardwareassignmentpositiondocument':
            return HARDWARE_ASSIGNMENT_POSITION_DOCUMENTS_FOLDER;
        case 'hardwarebudgetdocument':
            return HARDWARE_BUDGET_DOCUMENT_FOLDER;
        case 'hardwarebudgetdocumentuse':
            return HARDWARE_BUDGET_DOCUMENT_USE_FOLDER;

        case 'leadquotation':
            return LEADQUOTATION_ATTACHMENTS_FOLDER;
        default:
        return false;
    }
}

/**
 * Maybe upload hardwarebudget document
 * @param  string $hardwarebudget_id hardwarebudget_id
 * @return boolean
 */
function handle_image_upload_hardwarebudget( $hardwarebudget_id = '' ) {
    if ( isset($_FILES['budget_document']['name']) && $_FILES['budget_document']['name'] != '' ) {
        $path = get_upload_path_by_type('hardwarebudgetdocument') . $hardwarebudget_id;
        // Get the temp file path
        $tmpFilePath = $_FILES['budget_document']['tmp_name'];
        // Make sure we have a filepath
        if ( !empty($tmpFilePath) && $tmpFilePath != '' ) {
            // Getting file extension
            $path_parts = pathinfo($_FILES['budget_document']['name']);
            $extension = $path_parts['extension'];
            $extension = strtolower($extension);
            $allowed_extensions = array(
                'jpg',
                'jpeg',
                'png',
                'pdf'
            );
            if ( !in_array($extension, $allowed_extensions) ) {
                set_alert('warning', lang('page_form_validation_file_php_extension_blocked'));

                return false;
            }

            my_mkdir($path);

            $filename = unique_filename($path, $_FILES['budget_document']['name']);
            $newFilePath = $path .'/'. $filename;
            // Upload the file into the uploads dir
            if ( move_uploaded_file($tmpFilePath, $newFilePath) ) {
                $CI =& get_instance();
                $CI->db->where('hardwarebudget_id', $hardwarebudget_id);
                $CI->db->update('tblhardwarebudget', array(
                    'budget_document' => $filename
                ));

                return true;
            }
        }
    }

    return false;
}

/**
 * Maybe upload hardwarebudgetuse budget_use_document
 * @param  string $hardwarebudgetuse_id hardwarebudgetuse_id
 * @return boolean
 */
function handle_image_upload_hardwarebudgetuse( $hardwarebudgetuse_id = '' ) {
    if ( isset($_FILES['budget_use_document']['name']) && $_FILES['budget_use_document']['name'] != '' ) {
        $path = get_upload_path_by_type('hardwarebudgetdocumentuse') . $hardwarebudgetuse_id;
        // Get the temp file path
        $tmpFilePath = $_FILES['budget_use_document']['tmp_name'];
        // Make sure we have a filepath
        if ( !empty($tmpFilePath) && $tmpFilePath != '' ) {
            // Getting file extension
            $path_parts = pathinfo($_FILES['budget_use_document']['name']);
            $extension = $path_parts['extension'];
            $extension = strtolower($extension);
            $allowed_extensions = array(
                'jpg',
                'jpeg',
                'png',
                'pdf'
            );
            if ( !in_array($extension, $allowed_extensions) ) {
                set_alert('warning', lang('page_form_validation_file_php_extension_blocked'));

                return false;
            }

            my_mkdir($path);

            $filename = unique_filename($path, $_FILES['budget_use_document']['name']);
            $newFilePath = $path .'/'. $filename;
            // Upload the file into the uploads dir
            if ( move_uploaded_file($tmpFilePath, $newFilePath) ) {
                $CI =& get_instance();
                $CI->db->where('hardwarebudgetuse_id', $hardwarebudgetuse_id);
                $CI->db->update('tblhardwarebudgetnutzen', array(
                    'budget_use_document' => $filename
                ));

                return true;
            }
        }
    }

    return false;
}
