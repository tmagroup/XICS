<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * All email user templates slugs used for sending the emails
 * If you create new email template you can and must add the slug here with action hook.
 * Those are used to identify in what language should the email template to be sent
 * @return array
 */
function get_user_email_templates_slugs()
{
    $user_email_templates_slugs = array(
        'user-password-reseted',
        'user-forgot-password',
        'new-user-created',
		'new-employeecommission-slip'
    );

    return do_action('user_email_templates', $user_email_templates_slugs);
}
/**
 * Function that will return in what language the email template should be sent
 * @param  string $template_slug the template slug
 * @param  string $email         email that this template will be sent
 * @return string
 */
function get_email_template_language($template_slug, $email)
{
    $CI =& get_instance();
    $language = get_option('active_language');
	//$language = $CI->session->userdata('site_lang');
	   
    $hook_data['language'] = $language;
    $hook_data['template_slug'] = $template_slug;
    $hook_data['email'] = $email;

    $hook_data = do_action('email_template_language', $hook_data);
    $language = $hook_data['language'];

    return $language;
}

/**
 * Based on the template slug and email the function will fetch a template from database
 * The template will be fetched on the language that should be sent
 * @param  string $template_slug
 * @param  string $email
 * @return object
 */
function get_email_template_for_sending($template_slug, $email)
{
    $CI =& get_instance();

    $language = get_email_template_language($template_slug, $email);

    if (!is_dir(APPPATH . 'language/' . $language)) {
        $language = 'english';
    }

    $CI->db->where('language', $language);
    $CI->db->where('slug', $template_slug);
    $template = $CI->db->get('tblemailtemplates')->row();

    // Template languages not yet inserted
    // Users needs to visit Setup->Email Templates->Any template to initialize all languages
    if (!$template) {
        $CI->db->where('language', 'english');
        $CI->db->where('slug', $template_slug);
        $template = $CI->db->get('tblemailtemplates')->row();
    } else {
        if ($template && $template->message == '') {
            // Template message blank use the active language default template
            $CI->db->where('language', get_option('active_language'));
            $CI->db->where('slug', $template_slug);
            $template = $CI->db->get('tblemailtemplates')->row();

            if ($template->message == '') {
                $CI->db->where('language', 'english');
                $CI->db->where('slug', $template_slug);
                $template = $CI->db->get('tblemailtemplates')->row();
            }
        }
    }

    return $template;
}

/**
 * Parse email template with the merge fields
 * @param  mixed $template     template
 * @param  array  $merge_fields
 * @return object
 */
function parse_email_template($template, $merge_fields = array())
{
    $CI =& get_instance();
    if (!is_object($template) || $CI->input->post('template_name')) {
        $original_template = $template;
        if ($CI->input->post('template_name')) {
            $template = $CI->input->post('template_name');
        }
        $CI->db->where('slug', $template);
        $template = $CI->db->get('tblemailtemplates')->row();

        if ($CI->input->post('email_template_custom')) {
            $template->message = $CI->input->post('email_template_custom', false);
            // Replace the subject too
            $template->subject = $original_template->subject;
        }
    }
    $template = _parse_email_template_merge_fields($template, $merge_fields);


    return do_action('email_template_parsed', $template);
}

/**
 * This function will parse email template merge fields and replace with the corresponding merge fields passed before sending email
 * @param  object $template     template from database
 * @param  array $merge_fields available merge fields
 * @return object
 */
function _parse_email_template_merge_fields($template, $merge_fields)
{
    $merge_fields = array_merge($merge_fields, get_other_merge_fields());
    foreach ($merge_fields as $key => $val) {
        if (stripos($template->message, $key) !== false) {
            $template->message = str_ireplace($key, $val, $template->message);
        } else {
            $template->message = str_ireplace($key, '', $template->message);
        }
        if (stripos($template->fromname, $key) !== false) {
            $template->fromname = str_ireplace($key, $val, $template->fromname);
        } else {
            $template->fromname = str_ireplace($key, '', $template->fromname);
        }
        if (stripos($template->subject, $key) !== false) {
            $template->subject = str_ireplace($key, $val, $template->subject);
        } else {
            $template->subject = str_ireplace($key, '', $template->subject);
        }
    }

    return $template;
}
