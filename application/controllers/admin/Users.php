<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Users extends Admin_controller
{
    public function __construct()
    {
        parent::__construct();
        if(get_user_role()!='user' && current_url()!=base_url('admin/users/profile')){
            redirect(site_url('admin/permission/denied/'));
        }
        $this->load->model('User_model');
        $this->load->model('Role_model');
        $this->load->model('Salutation_model');
        $this->load->model('Event_model');           
    }

    /* List all users */
    public function index()
    {
        if(!$GLOBALS['user_permission']['view']){
            access_denied('user');
        }
        
        /*SELF PAGE AJAX CALL
        if ($this->input->is_ajax_request()) {
            $this->app->get_table_data('users');
        }*/        
        $data['title'] = lang('page_users');
        $this->load->view('admin/users/manage', $data);
    }
    
    /* List all users by ajax */
    public function ajax()
    {
        /*if($name!=""){
            switch($name){
                case 'change_active':
                    $post['active'] = $status;
                    $this->User_model->update($post, $id);            
                break;
            }            
            exit;
        }*/
        
        $this->app->get_table_data('users');        
    }
    
    /* Change Status */
    public function change_active($id='', $status=''){
        if($status==1){
            //History 
            $Action_data = array('actionname'=>'user', 'actionid'=>$id, 'actiontitle'=>'user_activated');
            do_action_history($Action_data);
        }else{
            //History 
            $Action_data = array('actionname'=>'user', 'actionid'=>$id, 'actiontitle'=>'user_deactivated');
            do_action_history($Action_data);
        }
        $this->db->query("UPDATE `tblusers` SET `active`='".$status."' WHERE userid='".$id."'");
        exit;
    }
    
    /* Add/Edit User */
    public function user($id='')
    {
        if(!$GLOBALS['user_permission']['create'] && !$GLOBALS['user_permission']['edit']){
            access_denied('user');
        }
        
        //******************** Initialise ********************/
        if($id>0){
            //User
            $data['user'] = (array) $this->User_model->get($id);            
            //echo time_ago($data['user']['last_login']);exit;
            
            if(empty($data['user']['userid'])){
                redirect(site_url('admin/users'));
            }
        }
        //******************** End Initialise ********************/
         
        //Submit Page
        if ($this->input->post()) {
            $post = $this->input->post();            
            //$data['password'] = $this->input->post('password', false);
            if(isset($data['user']['userid'])){
                handle_user_profile_image_upload($data['user']['userid']);
                $response = $this->User_model->update($post, $data['user']['userid']);  
                if (is_numeric($response) && $response>0) {
                    
                    //History 
                    $Action_data = array('actionname'=>'user', 'actionid'=>$response, 'actiontitle'=>'user_updated');
                    do_action_history($Action_data);
                    
                    set_alert('success', sprintf(lang('updated_successfully'),lang('page_user')));
                    //redirect(site_url('admin/users/user' . $data['user']['userid']));
                    redirect(site_url('admin/users/'));
                }
                else{
                    set_alert('danger', $response);
                }
            }
            else{
                $response = $this->User_model->add($post);
                if (is_numeric($response) && $response>0) {
                    
                    //History 
                    $Action_data = array('actionname'=>'user', 'actionid'=>$response, 'actiontitle'=>'user_added');
                    do_action_history($Action_data);
                    
                    handle_user_profile_image_upload($response);
                    set_alert('success', sprintf(lang('added_successfully'),lang('page_user')));
                    //redirect(site_url('admin/users/user' . $response));
                    redirect(site_url('admin/users/'));
                }
                else{
                    set_alert('danger', $response);
                }
            }
            
            //Initialise    
            $thumbimage = '';
            $userid = '';
            if(isset($data['user'])){
                $userid = $data['user']['userid'];
                $thumbimage = $data['user']['userthumb'];                
            }
            $data['user'] = $post;
            $data['user']['userid'] = $userid;
            $data['user']['userthumb'] = $thumbimage;           
        }
        
        
        //******************** Initialise ********************/
        //Roles
        $data['roles'] = $this->Role_model->get('',"(roleid!=4)");
        $data['roles'] = dropdown($data['roles'],'roleid','name');
        //Salutations
        $data['salutations'] = $this->Salutation_model->get();
        $data['salutations'] = dropdown($data['salutations'],'salutationid','name');  
        //Permissions
        $data['permissions'] = $this->Role_model->get_permissions();
        //Get Google Calendar IDs
        $data['googlecalendars'] = $this->Event_model->getGoogleCalendarList();
        //Get Google System Calendar Color
        $data['getSystemCalendarColor'] = $this->Event_model->getSystemCalendarColor();
        //print_r($data['googlecalendars']);exit;
        
        //******************** End Initialise ********************/
        
        
        //Page Title
        if(isset($data['user']['userid']) && $data['user']['userid']>0){
            $data['title'] = lang('page_edit_user');            
        }
        else{
            $data['title'] = lang('page_create_user');
        }            
        
       
        $this->load->view('admin/users/user', $data);
    }
    
    /* Profile setting */
    public function profile()
    {
        //Get (User/Customer)
        $user_role = get_user_role();        
        if($user_role=='customer'){        
            redirect(site_url('admin/settings/profile/'));
        }else{
            redirect(site_url('admin/settings/profile/'));
        }
        
        $id = get_user_id();
        //******************** Initialise ********************/
        if($id>0){
            //User
            $data['user'] = (array) $this->User_model->get($id);            
            //echo time_ago($data['user']['last_login']);exit;
        }
        //******************** End Initialise ********************/
         
        //Submit Page
        if ($this->input->post()) {
            $post = $this->input->post();            
            
            handle_user_profile_image_upload($data['user']['userid']);
            $response = $this->User_model->update($post, $data['user']['userid']);  
            if (is_numeric($response) && $response>0) {
                set_alert('success', sprintf(lang('updated_successfully'),lang('page_profile_setting')));
                //redirect(site_url('admin/users/user' . $data['user']['userid']));
                redirect(site_url('admin/users/profile/'));
            }
            else{
                set_alert('danger', $response);
            }
            
            
            //Initialise    
            $thumbimage = '';
            $userid = '';
            if(isset($data['user'])){
                $userid = $data['user']['userid'];
                $thumbimage = $data['user']['userthumb'];                
            }
            $data['user'] = $post;
            $data['user']['userid'] = $userid;
            $data['user']['userthumb'] = $thumbimage;           
        }
        
        //Page Title
        $data['title'] = lang('page_profile_setting');
        $this->load->view('admin/users/profile', $data);
    }
    
    /* Delete user */
    public function delete()
    {
        if(!$GLOBALS['user_permission']['delete'] || !$this->input->post('id')){
            access_denied('user');
        }
        
        $response = $this->User_model->delete($this->input->post('id'));
        if ($response==1) {
            
            //History 
            $Action_data = array('actionname'=>'user', 'actionid'=>$this->input->post('id'), 'actiontitle'=>'user_deleted');
            do_action_history($Action_data);
            
            set_alert('success', sprintf(lang('deleted_successfully'),lang('page_user')));
        }else{
            set_alert('danger', $response);
        }            
        redirect(site_url('admin/users/'));
    }
}
