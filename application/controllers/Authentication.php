<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Authentication extends CI_Controller {
	
	public function __construct()
    {
        parent::__construct();    
        $this->load->model('Authentication_model');
    }
	
    public function index()
    {
		if (is_logged_in()) {
            redirect(site_url('admin/dashboard'));
        }
		
		$this->form_validation->set_rules('username', lang('admin_auth_login_username'), 'trim|required');
		$this->form_validation->set_rules('password', lang('admin_auth_login_password'), 'required');
        
		if ($this->input->post()) {
			if ($this->form_validation->run() !== false) {
				
				$username = $this->input->post('username');
                $password = $this->input->post('password',false);
                $remember = $this->input->post('remember');				
				$data = $this->Authentication_model->login($username, $password, $remember);
				
				if (is_array($data) && isset($data['memberinactive'])) {
                    set_alert('danger', lang('admin_auth_inactive_account'));
                    redirect(site_url());
                } else if(!$data){
					set_alert('danger', lang('admin_auth_invalid_email_or_password'));
					redirect(site_url());
				}
				else{
					redirect(site_url('admin/dashboard'));
				}
			}
		}
		
        $this->load->view('authentication/login');
    }
	
	public function logout()
    {
        $this->Authentication_model->logout();   
		do_action('after_user_logout');    
        redirect(site_url());
    }
}
