<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Roles extends Admin_controller
{
    public function __construct()
    {
        parent::__construct();
        if(get_user_role()!='user' && get_user_id()==1){
            redirect(site_url('admin/permission/denied/'));
        }
        $this->load->model('Role_model');        
    }

    /* Permissions Set by Role */
    public function index()
    {
        if(!$GLOBALS['role_permission']['view']){
            access_denied('role');
        }
        
        //Submit Page
        if ($this->input->post()) {
            $post = $this->input->post();         
			$roleid = $post['userrole'];	
			$response = $this->Role_model->updatePermission($post, $roleid); 
			if (is_numeric($response) && $response>0) {
				set_alert('success', sprintf(lang('updated_successfully'),lang('page_role')));				
				//redirect(site_url('admin/roles/'));
			}
			else{
				set_alert('danger', $response);
			}	
			
			$data['role'] = $post;               
        }
        
        
        //******************** Initialise ********************/
        //Roles
        $data['roles'] = $this->Role_model->get('');
        $data['roles'] = dropdown($data['roles'],'roleid','name');        
        //******************** End Initialise ********************/
        
        
        $data['title'] = lang('page_roles');
        $this->load->view('admin/roles/index', $data);
    }   
    
    public function ajax($id){
        
        //******************** Initialise ********************/
        //Role
        $data['role'] = (array) $this->Role_model->get($id);
        
        //Permissions
        $data['permissions'] = $this->Role_model->get_permissions();
        //******************** End Initialise ********************/
        
        $this->load->view('admin/roles/tab-permissions', $data);
    }
}
