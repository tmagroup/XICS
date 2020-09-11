<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Reminders extends Admin_controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Lead_model');
        $this->load->model('Customer_model');
        $this->load->model('Quotation_model');
        $this->load->model('Leadquotation_model');
        $this->load->model('Assignment_model');
        $this->load->model('Reminder_model');
        $this->load->model('Customerreminder_model');
        $this->load->model('Quotationreminder_model');
        $this->load->model('Assignmentreminder_model');
        $this->load->model('Hardwareassignmentreminder_model');
        $this->load->model('Todo_model');
    }

    /* List all reminders by ajax */
    public function ajax($rel_id,$rel_type)
    {
        $data['rel_id'] = $rel_id;
        $data['rel_type'] = $rel_type;
        $this->app->get_table_data('reminders', $data);
    }

    /* Add Reminder by Ajax */
    public function addReminder(){
        //Submit Page
        if ($this->input->post()) {
            $post = $this->input->post();
            $response = $this->Reminder_model->add($post);
            if (is_numeric($response) && $response>0) {

                //History
                $Action_data = array('actionname'=>'lead', 'actionid'=>$post['rel_id'], 'actionsubid'=>$response, 'actiontitle'=>'lead_reminder_added');
                do_action_history($Action_data);

                echo json_encode(array('response'=>'success','message'=>sprintf(lang('added_successfully'),lang('page_lb_reminder'))));
            }
            else{
                echo json_encode(array('response'=>'error','message'=>$response));
            }
        }
        exit;
    }


    /* Get Reminders of User or Customer by Ajax */
    public function getUserReminders(){
        $data['reminder'] = $this->Reminder_model->get_user_reminders();
        echo json_encode($data['reminder']);
        exit;
        //******************** End Initialise ********************/
    }

    /* Change Read Status Reminder by Ajax */
    public function changeUserReminderStatus($status,$id,$rel_type){
        $data['is'.$status] = 1;

        switch($rel_type){
            case 'lead':
                $this->Reminder_model->update($data,$id);
            break;
            case 'customer':
                $this->Customerreminder_model->update($data,$id);
            break;
            case 'quotation':
                $this->Quotationreminder_model->update($data,$id);
            break;
            case 'assignment':
                $this->Assignmentreminder_model->update($data,$id);
            break;
            case 'Hardwareassignment':
                $this->Hardwareassignmentreminder_model->update($data,$id);
            break;
            case 'todo':
                $this->Todo_model->update($data,$id);
            break;
        }

        exit;
    }

    /* Get Reminder by Ajax */
    public function getReminder($id=''){
        //******************** Initialise ********************/
        //Reminder
        $data['reminder'] = (array) $this->Reminder_model->get($id);

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
            $response = $this->Reminder_model->update($post, $id);
            if (is_numeric($response) && $response>0) {

                //History
                $Action_data = array('actionname'=>'lead', 'actionid'=>$post['rel_id'], 'actionsubid'=>$response, 'actiontitle'=>'lead_reminder_updated');
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
        $response = $this->Reminder_model->delete($this->input->post('id'));
        if ($response==1) {

            //History
            $Action_data = array('actionname'=>'lead', 'actionid'=>$this->input->post('parentid'), 'actionsubid'=>$this->input->post('id'), 'actiontitle'=>'lead_reminder_deleted');
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

            switch($reltype){
                case 'lead':
                    $response = $this->Lead_model->sendReminder($id,'single');
                break;
                case 'customer':
                    $response = $this->Customer_model->sendReminder($id,'single');
                break;
                case 'quotation':
                    $response = $this->Quotation_model->sendReminder($id,'single');
                break;
                case 'leadquotation':
                    $response = $this->Leadquotation_model->sendReminder($id,'single');
                break;
                case 'assignment':
                    $response = $this->Assignment_model->sendReminder($id,'single');
                break;
                case 'hardwareassignment':
                    $response = $this->Hardwareassignment_model->sendReminder($id,'single');
                break;
            }

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
