<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Documentsettings extends Admin_controller
{
    public function __construct()
    {
        parent::__construct();
        if(get_user_role()!='user'){
            redirect(site_url('admin/permission/denied/'));
        }
        $this->load->model('Documentsetting_model');            
    }

    /* List all documentsettings */
    public function index()
    {
        if(!$GLOBALS['documentsetting_permission']['view']){
            access_denied('documentsetting');
        }
             
        $data['title'] = lang('page_documentsettings');
        $this->load->view('admin/documentsettings/manage', $data);
    }
    
    /* List all documentsettings by ajax */
    public function ajax()
    {
        /*if($name!=""){
            switch($name){
                case 'change_active':
                    $post['active'] = $status;
                    $this->Documentsetting_model->update($post, $id);            
                break;
            }            
            exit;
        }*/
        
        $this->app->get_table_data('documentsettings');        
    }
    
    /* Change Status */
    public function change_active($id='', $status=''){
        if($status==1){
            //History 
            $Action_data = array('actionname'=>'documentsetting', 'actionid'=>$id, 'actiontitle'=>'documentsetting_activated');
            do_action_history($Action_data);
        }else{
            //History 
            $Action_data = array('actionname'=>'documentsetting', 'actionid'=>$id, 'actiontitle'=>'documentsetting_deactivated');
            do_action_history($Action_data);
        }
        $this->db->query("UPDATE `tbldocumentsettings` SET `active`='".$status."' WHERE categoryid='".$id."'");
        exit;
    }
    
    /* Add/Edit Category */
    public function category($id='')
    {
        if(!$GLOBALS['documentsetting_permission']['create'] && !$GLOBALS['documentsetting_permission']['edit']){
            access_denied('documentsetting');
        }
        
        //******************** Initialise ********************/             
        if($id>0){
            //Documentsetting
            $data['documentsetting'] = (array) $this->Documentsetting_model->get($id);  
            
            if(empty($data['documentsetting']['categoryid'])){
                redirect(site_url('admin/documentsettings'));
            }
        }
        //******************** End Initialise ********************/
        
        //Submit Page
        if ($this->input->post()) {
            $post = $this->input->post();                       
            if(isset($data['documentsetting']['categoryid'])){
                $response = $this->Documentsetting_model->update($post, $data['documentsetting']['categoryid']);  
                if (is_numeric($response) && $response>0) {
                    
                    //History 
                    $Action_data = array('actionname'=>'documentsetting', 'actionid'=>$response, 'actiontitle'=>'documentsetting_updated');
                    do_action_history($Action_data);
                    
                    set_alert('success', sprintf(lang('updated_successfully'),lang('page_documentsetting')));                
                    redirect(site_url('admin/documentsettings/'));
                }
                else{
                    set_alert('danger', $response);
                }
            }
            else{
                $response = $this->Documentsetting_model->add($post);
                if (is_numeric($response) && $response>0) {  
                    
                    //History 
                    $Action_data = array('actionname'=>'documentsetting', 'actionid'=>$response, 'actiontitle'=>'documentsetting_added');
                    do_action_history($Action_data);
                    
                    set_alert('success', sprintf(lang('added_successfully'),lang('page_documentsetting')));
                    redirect(site_url('admin/documentsettings/'));
                }
                else{
                    set_alert('danger', $response);
                }
            }
            
            //Initialise                
            $categoryid = '';
            if(isset($data['documentsetting'])){
                $categoryid = $data['documentsetting']['categoryid'];
            }
            $data['documentsetting'] = $post;
            $data['documentsetting']['categoryid'] = $categoryid;              
        }
        
        
        //Page Title
        if(isset($data['documentsetting']['categoryid']) && $data['documentsetting']['categoryid']>0){
            $data['title'] = lang('page_edit_documentsetting');            
        }
        else{
            $data['title'] = lang('page_create_documentsetting');
        }            
        
       
        $this->load->view('admin/documentsettings/category', $data);
    }
    
    /* Delete documentsetting */
    public function delete()
    {
        if(!$GLOBALS['documentsetting_permission']['delete'] || !$this->input->post('id')){
            access_denied('documentsetting');
        }
        
        $response = $this->Documentsetting_model->delete($this->input->post('id'));
        if ($response==1) {
            
            //History 
            $Action_data = array('actionname'=>'documentsetting', 'actionid'=>$this->input->post('id'), 'actiontitle'=>'documentsetting_deleted');
            do_action_history($Action_data);
            
            set_alert('success', sprintf(lang('deleted_successfully'),lang('page_documentsetting')));
        }else{
            set_alert('danger', $response);
        }            
        redirect(site_url('admin/documentsettings/'));
    }
}