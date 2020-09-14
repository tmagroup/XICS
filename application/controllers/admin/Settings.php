<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Settings extends Admin_controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('User_model');
        $this->load->model('Customer_model');
        $this->load->model('Setting_model');
    }

    /* Edit Profile */
    public function profile()
    {
        if(!$GLOBALS['profile_permission']['edit']){
            access_denied('profile');
        }

        if(get_user_role()=='customer')
        {
            //******************** Initialise ********************/
            $id = get_user_id();
            $data['customer'] = (array) $this->Customer_model->get($id);
            //******************** End Initialise ********************/

            //Submit Page
            if ($this->input->post()) {
                $post = $this->input->post();
                handle_customer_profile_image_upload($id);
                $response = $this->Setting_model->cupdateProfile($post, $id);
                if (is_numeric($response) && $response>0) {
                    set_alert('success', sprintf(lang('updated_successfully'),lang('page_profile')));
                    redirect(site_url('admin/settings/profile'));
                }
                else{
                    set_alert('danger', $response);
                }

                //Initialise
                $thumbimage = '';
                $customernr = '';
                if(isset($data['customer'])){
                    $customernr = $id;
                    $thumbimage = $data['customer']['customerthumb'];
                    $username= $data['customer']['username'];
                }
                $data['customer'] = $post;
                $data['customer']['customernr'] = $customernr;
                $data['customer']['username'] = $username;
                $data['customer']['customerthumb'] = $thumbimage;
            }

            //Page Title
            $data['title'] = lang('page_edit_profile');
            $this->load->view('admin/settings/cprofile', $data);
        }
        else
        {
            //******************** Initialise ********************/
            $id = get_user_id();
            $data['user'] = (array) $this->User_model->get($id);
            //******************** End Initialise ********************/

            //Submit Page
            if ($this->input->post()) {
                $post = $this->input->post();
                handle_user_profile_image_upload($id);
                $response = $this->Setting_model->updateProfile($post, $id);
                if (is_numeric($response) && $response>0) {
                    set_alert('success', sprintf(lang('updated_successfully'),lang('page_profile')));
                    redirect(site_url('admin/settings/profile'));
                }
                else{
                    set_alert('danger', $response);
                }

                //Initialise
                $thumbimage = '';
                $userid = '';
                if(isset($data['user'])){
                    $userid = $id;
                    $thumbimage = $data['user']['userthumb'];
                    $username= $data['user']['username'];
                }
                $data['user'] = $post;
                $data['user']['userid'] = $userid;
                $data['user']['username'] = $username;
                $data['user']['userthumb'] = $thumbimage;
            }

            //Page Title
            $data['title'] = lang('page_edit_profile');
            $this->load->view('admin/settings/profile', $data);
        }
    }

    // get login user customer
    public function getLoginInvoiceCustomer()
    {
        $return = array();
        if(get_user_role()=='customer'){
            $id = get_user_id();
            $return['customer'] = (array) $this->Customer_model->getLoginInvoiceCustomer($id);
            $return['status'] = TRUE;
        } else {
            $return['status'] = FALSE;
        }

        echo json_encode($return); die();
    }
}
