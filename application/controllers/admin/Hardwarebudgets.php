<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Hardwarebudgets extends Admin_controller
{
    public function __construct()
    {
        parent::__construct();

        $this->load->model('Hardwarebudget_model');
        $this->load->model('Hardwarebudgetuse_model');
    }

    private function is_loggedin_valid_user() {
        if ( !($GLOBALS['hardwarebudget_permission']['view'] || $GLOBALS['hardwarebudget_permission']['view_own'] || $GLOBALS['hardwarebudget_permission']['create'] || $GLOBALS['hardwarebudget_permission']['edit'] || $GLOBALS['hardwarebudget_permission']['delete']) ) {
            redirect(site_url('admin/dashboard')); exit(0);
        }
    }

    /* List all hardwareassignments */
    public function index()
    {
        $this->is_loggedin_valid_user();
        $data = array();
        $data['title'] = 'Hardware-Budget';
        $total_excluding_vat = $this->Hardwarebudget_model->get_total_total_excluding_vat();
        $total_excluding_vat_use = $this->Hardwarebudgetuse_model->get_total_total_excluding_vat_use();
        $data['latest_hardware_budget'] = $total_excluding_vat - $total_excluding_vat_use;
        $this->load->view('admin/hardwarebudgets/manage', $data);
    }

    /* List all assignments by ajax */
    public function ajax($table)
    {
        if ( $table == 1 ) { // tblhardwarebudget
            $this->app->get_table_data('hardwarebudget');
        } else if ( $table == 2 ) { // tblhardwarebudgetnutzen
            $this->app->get_table_data('hardwarebudgetuse');
        }
    }

    public function hardwarebudget($id = '')
    {
        $this->is_loggedin_valid_user();
        // print_r($this->input->post());
        // print_r($_FILES);

        //******************** Initialise ********************/
        if ( $id > 0 ) {
            $data['hardwarebudget'] = (array) $this->Hardwarebudget_model->get($id);
        }
        //******************** End Initialise ********************/

        //Submit Page
        if ($this->input->post()) {
            $post = $this->input->post();

            if ( isset($data['hardwarebudget']['hardwarebudget_id']) ) {
                $post['date_of_expiry'] = date('Y-m-d H:i:s', strtotime($post['date_of_expiry']));
                $response = $this->Hardwarebudget_model->update($post, $data['hardwarebudget']['hardwarebudget_id']);
                if (is_numeric($response) && $response>0) {

                    //History
                    $Action_data = array('actionname'=>'hardwarebudget', 'actionid'=>$response, 'actiontitle'=>'hardwarebudget_updated');
                    do_action_history($Action_data);

                    handle_image_upload_hardwarebudget($data['hardwarebudget']['hardwarebudget_id']);
                    set_alert('success', sprintf(lang('updated_successfully'), lang('page_hardwarebudget')));
                    redirect(site_url('admin/hardwarebudgets'));
                } else {
                    set_alert('danger', $response);
                }
            } else {
                $post['date_of_expiry'] = date('Y-m-d H:i:s', strtotime($post['date_of_expiry']));
                $response = $this->Hardwarebudget_model->add($post);
                if (is_numeric($response) && $response>0) {

                    //History
                    $Action_data = array('actionname'=>'hardwarebudget', 'actionid'=>$response, 'actiontitle'=>'hardwarebudget_added');
                    do_action_history($Action_data);

                    handle_image_upload_hardwarebudget($response);
                    set_alert('success', sprintf(lang('added_successfully'), lang('page_hardwarebudget')));
                    redirect(site_url('admin/hardwarebudgets'));
                } else {
                    set_alert('danger', $response);
                }
            }
        }

        redirect(site_url('admin/hardwarebudgets'));
    }

    public function hardwarebudgetuse($id = '')
    {
        $this->is_loggedin_valid_user();
        // print_r($this->input->post());
        // print_r($_FILES);

        //******************** Initialise ********************/
        if ( $id > 0 ) {
            $data['hardwarebudgetuse'] = (array) $this->Hardwarebudgetuse_model->get($id);
        }
        //******************** End Initialise ********************/

        //Submit Page
        if ($this->input->post()) {
            $post = $this->input->post();

            if ( isset($data['hardwarebudgetuse']['hardwarebudgetuse_id']) ) {
                $post['date_of_use'] = date('Y-m-d H:i:s', strtotime($post['date_of_use']));
                $response = $this->Hardwarebudgetuse_model->update($post, $data['hardwarebudgetuse']['hardwarebudgetuse_id']);
                if (is_numeric($response) && $response>0) {

                    //History
                    $Action_data = array('actionname'=>'hardwarebudgetuse', 'actionid'=>$response, 'actiontitle'=>'hardwarebudgetuse_updated');
                    do_action_history($Action_data);

                    handle_image_upload_hardwarebudgetuse($data['hardwarebudgetuse']['hardwarebudgetuse_id']);
                    set_alert('success', sprintf(lang('updated_successfully'), lang('page_hardwarebudgetuse')));
                    redirect(site_url('admin/hardwarebudgets'));
                } else {
                    set_alert('danger', $response);
                }
            } else {
                $post['date_of_use'] = date('Y-m-d H:i:s', strtotime($post['date_of_use']));
                $response = $this->Hardwarebudgetuse_model->add($post);
                if (is_numeric($response) && $response>0) {

                    //History
                    $Action_data = array('actionname'=>'hardwarebudgetuse', 'actionid'=>$response, 'actiontitle'=>'hardwarebudgetuse_added');
                    do_action_history($Action_data);

                    handle_image_upload_hardwarebudgetuse($response);
                    set_alert('success', sprintf(lang('added_successfully'), lang('page_hardwarebudgetuse')));
                    redirect(site_url('admin/hardwarebudgets'));
                } else {
                    set_alert('danger', $response);
                }
            }
        }

        redirect(site_url('admin/hardwarebudgets'));
    }
}
