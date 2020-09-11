<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Customerreminders extends Admin_controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Customerreminder_model');
        $this->load->model('Customer_model');
    }

    /* List all reminders by ajax */
    public function ajax($rel_id,$rel_type)
    {
        $data['rel_id'] = $rel_id;
        $data['rel_type'] = $rel_type;
        $this->app->get_table_data('customerreminders', $data);        
    }
    
    /* Add Reminder by Ajax */
    public function addReminder(){
        //Submit Page
        if ($this->input->post()) {
            $post = $this->input->post();
            $response = $this->Customerreminder_model->add($post);
            if (is_numeric($response) && $response>0) {  
                
                //History 
                $Action_data = array('actionname'=>'customer', 'actionid'=>$post['rel_id'], 'actionsubid'=>$response, 'actiontitle'=>'customer_reminder_added');
                do_action_history($Action_data);
                
                echo json_encode(array('response'=>'success','message'=>sprintf(lang('added_successfully'),lang('page_lb_reminder'))));
            }
            else{
                echo json_encode(array('response'=>'error','message'=>$response));
            }
        }
        exit;
    }
    
    /* Get Reminder by Ajax */
    public function getReminder($id=''){
        //******************** Initialise ********************/                     
        //Reminder
        $data['reminder'] = (array) $this->Customerreminder_model->get($id); 
        
        //Date Format
        if(isset($data['reminder'])){
            $data['reminder']['reminddate'] = _dt($data['reminder']['reminddate']);
        }
        echo json_encode($data['reminder']);
        exit;
        //******************** End Initialise ********************/        
    }
    
    /* Edit Reminder by Ajax */
    public function editReminder($id=''){
        //Submit Page
        if ($this->input->post()) {
            $post = $this->input->post();
            $response = $this->Customerreminder_model->update($post, $id);  
            if (is_numeric($response) && $response>0) { 
                
                //History 
                $Action_data = array('actionname'=>'customer', 'actionid'=>$post['rel_id'], 'actionsubid'=>$response, 'actiontitle'=>'customer_reminder_updated');
                do_action_history($Action_data);
                
                echo json_encode(array('response'=>'success','message'=>sprintf(lang('updated_successfully'),lang('page_lb_reminder'))));
            }
            else{
                echo json_encode(array('response'=>'error','message'=>$response));
            }
        }
        exit;
    }
    
    /* Delete Reminder by Ajax */
    public function deleteReminder(){
        $response = $this->Customerreminder_model->delete($this->input->post('id'));
        if ($response==1) {
            
            //History 
            $Action_data = array('actionname'=>'customer', 'actionid'=>$this->input->post('parentid'), 'actionsubid'=>$this->input->post('id'), 'actiontitle'=>'customer_reminder_deleted');
            do_action_history($Action_data);
            
            echo json_encode(array('response'=>'success','message'=>sprintf(lang('deleted_successfully'),lang('page_lb_reminder'))));
        }else{
            echo json_encode(array('response'=>'error','message'=>$response));
        } 
        exit;
    }
    
    //Send Request 
    public function sendReminder($id,$reltype,$parentid=''){
        if($id){
            $response = $this->Customer_model->sendReminder($id);         
            if ($response==1) {
                
                //History 
                $Action_data = array('actionname'=>$reltype, 'actionid'=>$parentid, 'actionsubid'=>$id, 'actiontitle'=>$reltype.'_reminder_sent');
                do_action_history($Action_data);
                
                echo json_encode(array('response'=>'success','message'=>sprintf(lang('sent_successfully'),lang('page_lb_reminder'))));
            }else{
                echo json_encode(array('response'=>'error','message'=>sprintf(lang('failed'),lang('page_lb_reminder'))));
            }
        }    
        exit;
    }
}
