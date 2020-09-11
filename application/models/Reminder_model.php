<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Reminder_model extends CI_Model
{
    var $table = 'tblreminders';
    var $aid = 'remindernr';
	
    public function __construct()
    {
        parent::__construct();  
        $this->load->model('Remindersubject_model');
    }

    /**
     * Check if reminder
     * @param  mixed $remindernr 
     * @return mixed
     */
    public function get($id='', $field='', $join=array(), $where="", $groupby="")
    {        
        //Select Fields
        if($field!=""){
            $this->db->select($field);
        }
        
        //Join
        if(count($join)>0){
            foreach ($join as $key=>$value){
                $this->db->join($key, $value, 'left');
            }
        }
        
        //Group By
        if($groupby!=""){
            $this->db->group_by($groupby);
        }
        
        //Where 
        if($where!=""){           
            $this->db->where($where);           
        }
        
        if (is_numeric($id)) {
            $this->db->where($this->table.".".$this->aid, $id);
            return $this->db->get($this->table)->row();
        }

        return $this->db->get($this->table)->result_array();
    }
    
    /**
     * Add new reminder
     * @param array $data reminder $_POST data
     */
    public function add($data)
    {    
        //Database data
        $data['created'] = date('Y-m-d H:i:s');
        $data['userid'] = get_user_id();        
        $data['reminddate'] = to_sql_date($data['reminddate'], true); 
        $data['reminderway'] = isset($data['reminderway'])?1:0;
        $this->db->insert($this->table, $data);
        $id = $this->db->insert_id();
        
        if($id>0){            
            //Add ID Prefix
            $dataId = array();
            $dataId['remindernr_prefix'] = idprefix('reminder',$id);
            $this->db->where($this->aid, $id);
            $this->db->update($this->table, $dataId);
            
            $rowfield = $this->get($id,'remindersubject');
            $rowfield = $this->Remindersubject_model->get($rowfield->remindersubject,'name');
            //Log Activity
			$name = isset($rowfield->name)?$rowfield->name:'';
            logActivity('New Lead Reminder Added [ID: ' . $id . ', Subject: ' . $name . ']');         
        }
        
        return $id;
    }
    
    /**
     * Update reminder
     * @param  array $data reminder
     * @param  mixed $id   reminder id
     * @return boolean
     */
    public function update($data, $id, $popup=false)
    {    
        //Database data
        $data['updated'] = date('Y-m-d H:i:s');
        if(isset($data['reminddate'])){
            $data['reminddate'] = to_sql_date($data['reminddate'], true);   
        } 
        if(!$popup){
            $data['reminderway'] = isset($data['reminderway'])?1:0;
        }
        $this->db->where($this->aid, $id);
        $this->db->update($this->table, $data);
        
        if ($this->db->affected_rows() > 0) {            
            $rowfield = $this->get($id,'remindersubject');
            $rowfield = $this->Remindersubject_model->get($rowfield->remindersubject,'name');       
            //Log Activity
            $name = isset($rowfield->name)?$rowfield->name:'';
            logActivity('Lead Reminder Updated [ID: ' . $id . ', Subject: ' . $name . ']');
        } 
        
        return $id;
    }    
    
    /**
     * Delete reminder
     * @param  array $data reminder
     * @param  mixed $id   reminder id
     * @return boolean
     */
    public function delete($id)
    {
        //Get Leadnr
        $rowfield = $this->get($id,'remindersubject');
        $rowfield = $this->Remindersubject_model->get($rowfield->remindersubject,'name');
        
        $this->db->where($this->aid, $id);
        $this->db->delete($this->table);
        
        //Log Activity
		$name = isset($rowfield->name)?$rowfield->name:'';
        logActivity('Lead Reminder Deleted [ID: ' . $id . ', Subject: ' . $name . ']');
        
        return 1;
    }
    
    /* Get Reminders of User or Customer */
    public function get_user_reminders(){        
        $this->load->model('Lead_model');
        $this->load->model('Customer_model');
        $this->load->model('Quotation_model');
        $this->load->model('Assignment_model');
        $this->load->model('Reminder_model');
        $this->load->model('Customerreminder_model'); 
        $this->load->model('Quotationreminder_model'); 
        $this->load->model('Assignmentreminder_model'); 
        $this->load->model('Hardwareassignmentreminder_model'); 
        $this->load->model('Todo_model');
        
        $data['customerreminder'] = array();
        $data['leadreminder'] = array();
        $data['todoreminder'] = array();
        $data['quotationreminder'] = array();
        $data['assignmentreminder'] = array();
        $data['hardwareassignmentreminder'] = array();
        //******************** Initialise ********************/          
        if(get_user_role()=='customer'){
            //Customer Reminder
            /*$data['customerreminder'] = (array) $this->Customerreminder_model->get('',"tblcustomerreminders.remindernr as id, tblcustomerreminders.rel_id, tblcustomerreminders.rel_type, '".lang('page_customer')."' as type, tblremindersubjects.name as subject, tblcustomerreminders.notice as message, CONCAT(sender.name,' ',sender.surname) as fromname, DATE_FORMAT(tblcustomerreminders.reminddate,'%d.%m.%Y') as reminddate",
                array('tblcustomers'=>'tblcustomers.customernr=tblcustomerreminders.rel_id',
                'tblremindersubjects'=>'tblremindersubjects.id=tblcustomerreminders.remindersubject',
                'tblusers as responsible'=>'responsible.userid=tblcustomers.responsible',
                'tblusers as sender'=>'sender.userid=tblcustomerreminders.userid'),
                "tblcustomerreminders.rel_type='customer' AND tblcustomerreminders.isopen=0 AND tblcustomerreminders.isread=0 AND tblcustomerreminders.reminddate<=NOW() AND tblcustomers.customernr='".get_user_id()."' "    
            );*/
            
            $query = $this->db->query("SELECT * FROM (

                SELECT `tblcustomerreminders`.`remindernr` as `id`, `tblcustomerreminders`.`rel_id`, `tblcustomerreminders`.`rel_type`, '".lang('page_customer')."' as type, `tblremindersubjects`.`name` as `subject`, `tblcustomerreminders`.`notice` as `message`, CONCAT(sender.name, ' ', sender.surname) as fromname, DATE_FORMAT(tblcustomerreminders.reminddate, '%d.%m.%Y') as reminddate 
                FROM `tblcustomerreminders` LEFT JOIN `tblcustomers` ON `tblcustomers`.`customernr`=`tblcustomerreminders`.`rel_id` LEFT JOIN `tblremindersubjects` ON `tblremindersubjects`.`id`=`tblcustomerreminders`.`remindersubject` LEFT JOIN `tblusers` as `responsible` ON `responsible`.`userid`=`tblcustomers`.`responsible` LEFT JOIN `tblusers` as `sender` ON `sender`.`userid`=`tblcustomerreminders`.`userid` 
                WHERE `tblcustomerreminders`.`rel_type` = 'customer' AND `tblcustomerreminders`.`isopen`=0 AND `tblcustomerreminders`.`isread`=0 AND `tblcustomerreminders`.`reminddate` <= NOW() AND `tblcustomers`.`customernr` = '".get_user_id()."' 

            )  as reminders 
            
            ORDER BY reminddate DESC ");  
        }
        else{        
            //Lead Reminder
            /*$data['leadreminder'] = (array) $this->Reminder_model->get('',"tblreminders.remindernr as id, tblreminders.rel_id, tblreminders.rel_type, '".lang('page_lead')."' as type, tblremindersubjects.name as subject, tblreminders.notice as message, CONCAT(sender.name,' ',sender.surname) as fromname, DATE_FORMAT(tblreminders.reminddate,'%d.%m.%Y') as reminddate",
                array('tblleads'=>'tblleads.leadnr=tblreminders.rel_id',
                'tblremindersubjects'=>'tblremindersubjects.id=tblreminders.remindersubject',
                'tblusers as responsible'=>'responsible.userid=tblleads.responsible',
                'tblusers as sender'=>'sender.userid=tblreminders.userid'),
                "tblreminders.rel_type='lead' AND tblreminders.isopen=0 AND tblreminders.isread=0 AND tblreminders.reminddate<=NOW() AND tblleads.responsible='".get_user_id()."' "    
            );*/   
            //Customer Reminder
            /*$data['customerreminder'] = (array) $this->Customerreminder_model->get('',"tblcustomerreminders.remindernr as id, tblcustomerreminders.rel_id, tblcustomerreminders.rel_type, '".lang('page_customer')."' as type, tblremindersubjects.name as subject, tblcustomerreminders.notice as message, CONCAT(sender.name,' ',sender.surname) as fromname, DATE_FORMAT(tblcustomerreminders.reminddate,'%d.%m.%Y') as reminddate",
                array('tblcustomers'=>'tblcustomers.customernr=tblcustomerreminders.rel_id',
                'tblremindersubjects'=>'tblremindersubjects.id=tblcustomerreminders.remindersubject',
                'tblusers as responsible'=>'responsible.userid=tblcustomers.responsible',
                'tblusers as sender'=>'sender.userid=tblcustomerreminders.userid'),
                "tblcustomerreminders.rel_type='customer' AND tblcustomerreminders.isopen=0 AND tblcustomerreminders.isread=0 AND tblcustomerreminders.reminddate<=NOW() AND tblcustomers.responsible='".get_user_id()."' "    
            );*/
            //Todo Reminder
            /*$data['todoreminder'] = (array) $this->Todo_model->get('',"tbltodos.todonr as id, tbltodos.todonr as rel_id, 'todo' as rel_type, '".lang('page_todo')."' as type, tbltodos.todotitle as subject, tbltodos.tododesc as message, CONCAT(sender.name,' ',sender.surname) as fromname, DATE_FORMAT(tbltodos.reminderdate,'%d.%m.%Y') as reminddate",
                array('tblusers as responsible'=>'responsible.userid=tbltodos.responsible',
                'tblusers as sender'=>'sender.userid=tbltodos.userid'),
                "tbltodos.isopen=0 AND tbltodos.isread=0 AND tbltodos.reminderdate<=NOW() AND tbltodos.responsible='".get_user_id()."' "    
            );  
            //Quotation Reminder
            $data['quotationreminder'] = (array) $this->Quotationreminder_model->get('',"tblquotationreminders.remindernr as id, tblquotationreminders.rel_id, tblquotationreminders.rel_type, '".lang('page_quotation')."' as type, tblremindersubjects.name as subject, tblquotationreminders.notice as message, CONCAT(sender.name,' ',sender.surname) as fromname, DATE_FORMAT(tblquotationreminders.reminddate,'%d.%m.%Y') as reminddate",
                array('tblquotations'=>'tblquotations.quotationnr=tblquotationreminders.rel_id',
                'tblremindersubjects'=>'tblremindersubjects.id=tblquotationreminders.remindersubject',
                'tblusers as responsible'=>'responsible.userid=tblquotations.responsible',
                'tblusers as sender'=>'sender.userid=tblquotationreminders.userid'),
                "tblquotationreminders.rel_type='quotation' AND tblquotationreminders.isopen=0 AND tblquotationreminders.isread=0 AND tblquotationreminders.reminddate<=NOW() AND tblquotations.responsible='".get_user_id()."' "    
            );
            //Assignment Reminder
            $data['assignmentreminder'] = (array) $this->Assignmentreminder_model->get('',"tblassignmentreminders.remindernr as id, tblassignmentreminders.rel_id, tblassignmentreminders.rel_type, '".lang('page_assignment')."' as type, tblremindersubjects.name as subject, tblassignmentreminders.notice as message, CONCAT(sender.name,' ',sender.surname) as fromname, DATE_FORMAT(tblassignmentreminders.reminddate,'%d.%m.%Y') as reminddate",
                array('tblassignments'=>'tblassignments.assignmentnr=tblassignmentreminders.rel_id',
                'tblremindersubjects'=>'tblremindersubjects.id=tblassignmentreminders.remindersubject',
                'tblusers as responsible'=>'responsible.userid=tblassignments.responsible',
                'tblusers as sender'=>'sender.userid=tblassignmentreminders.userid'),
                "tblassignmentreminders.rel_type='assignment' AND tblassignmentreminders.isopen=0 AND tblassignmentreminders.isread=0 AND tblassignmentreminders.reminddate<=NOW() AND tblassignments.responsible='".get_user_id()."' "    
            );
            //Hardware Assignment Reminder
            $data['hardwareassignmentreminder'] = (array) $this->Hardwareassignmentreminder_model->get('',"tblhardwareassignmentreminders.remindernr as id, tblhardwareassignmentreminders.rel_id, tblhardwareassignmentreminders.rel_type, '".lang('page_hardwareassignment')."' as type, tblremindersubjects.name as subject, tblhardwareassignmentreminders.notice as message, CONCAT(sender.name,' ',sender.surname) as fromname, DATE_FORMAT(tblhardwareassignmentreminders.reminddate,'%d.%m.%Y') as reminddate",
                array('tblhardwareassignments'=>'tblhardwareassignments.hardwareassignmentnr=tblhardwareassignmentreminders.rel_id',
                'tblremindersubjects'=>'tblremindersubjects.id=tblhardwareassignmentreminders.remindersubject',
                'tblusers as responsible'=>'responsible.userid=tblhardwareassignments.responsible',
                'tblusers as sender'=>'sender.userid=tblhardwareassignmentreminders.userid'),
                "tblhardwareassignmentreminders.rel_type='hardwareassignment' AND tblhardwareassignmentreminders.isopen=0 AND tblhardwareassignmentreminders.isread=0 AND tblhardwareassignmentreminders.reminddate<=NOW() AND tblhardwareassignments.responsible='".get_user_id()."' "    
            );*/
            
            $query = $this->db->query("SELECT *, DATE_FORMAT(STR_TO_DATE(reminddate, '%d.%m.%Y'), '%Y-%m-%d') as orderdate FROM (

            (SELECT `tblreminders`.`rel_type` as sub_rel_type, `tblreminders`.`remindernr` as `id`, `tblreminders`.`rel_id`, `tblreminders`.`rel_type`, '".lang('page_lead')."' as type, `tblremindersubjects`.`name` as `subject`, `tblreminders`.`notice` as `message`, CONCAT(sender.name, ' ', sender.surname) as fromname, DATE_FORMAT(tblreminders.reminddate, '%d.%m.%Y') as reminddate 
            FROM `tblreminders` LEFT JOIN `tblleads` ON `tblleads`.`leadnr`=`tblreminders`.`rel_id` LEFT JOIN `tblremindersubjects` ON `tblremindersubjects`.`id`=`tblreminders`.`remindersubject` LEFT JOIN `tblusers` as `responsible` ON `responsible`.`userid`=`tblleads`.`responsible` LEFT JOIN `tblusers` as `sender` ON `sender`.`userid`=`tblreminders`.`userid` 
            WHERE `tblreminders`.`rel_type` = 'lead' AND `tblreminders`.`isopen`=0 AND `tblreminders`.`isread`=0 AND `tblreminders`.`reminddate` <= NOW() AND `tblleads`.`responsible` = '".get_user_id()."')

            UNION ALL

            (SELECT 'todo' as sub_rel_type, `tbltodos`.`todonr` as `id`, `tbltodos`.`todonr` as `rel_id`, 'todo' as rel_type, '".lang('page_todo')."' as type, `tbltodos`.`todotitle` as `subject`, `tbltodos`.`tododesc` as `message`, CONCAT(sender.name, ' ', sender.surname) as fromname, DATE_FORMAT(tbltodos.reminderdate, '%d.%m.%Y') as reminddate 
            FROM `tbltodos` LEFT JOIN `tblusers` as `responsible` ON `responsible`.`userid`=`tbltodos`.`responsible` LEFT JOIN `tblusers` as `sender` ON `sender`.`userid`=`tbltodos`.`userid` 
            WHERE `tbltodos`.`isopen`=0 AND `tbltodos`.`isread`=0 AND `tbltodos`.`reminderdate` <= NOW() AND `tbltodos`.`responsible` = '".get_user_id()."')

            UNION ALL

            (SELECT `tblquotationreminders`.`rel_type` as sub_rel_type, `tblquotationreminders`.`remindernr` as `id`, `tblquotationreminders`.`rel_id`, `tblquotationreminders`.`rel_type`, '".lang('page_quotation')."' as type, `tblremindersubjects`.`name` as `subject`, `tblquotationreminders`.`notice` as `message`, CONCAT(sender.name, ' ', sender.surname) as fromname, DATE_FORMAT(tblquotationreminders.reminddate, '%d.%m.%Y') as reminddate 
            FROM `tblquotationreminders` LEFT JOIN `tblquotations` ON `tblquotations`.`quotationnr`=`tblquotationreminders`.`rel_id` LEFT JOIN `tblremindersubjects` ON `tblremindersubjects`.`id`=`tblquotationreminders`.`remindersubject` LEFT JOIN `tblusers` as `responsible` ON `responsible`.`userid`=`tblquotations`.`responsible` LEFT JOIN `tblusers` as `sender` ON `sender`.`userid`=`tblquotationreminders`.`userid` 
            WHERE `tblquotationreminders`.`rel_type` = 'quotation' AND `tblquotationreminders`.`isopen`=0 AND `tblquotationreminders`.`isread`=0 AND `tblquotationreminders`.`reminddate` <= NOW() AND `tblquotations`.`responsible` = '".get_user_id()."')

            UNION ALL

            (SELECT `tblassignmentreminders`.`rel_type` as sub_rel_type, `tblassignmentreminders`.`remindernr` as `id`, `tblassignmentreminders`.`rel_id`, `tblassignmentreminders`.`rel_type`, '".lang('page_assignment')."' as type, `tblremindersubjects`.`name` as `subject`, `tblassignmentreminders`.`notice` as `message`, CONCAT(sender.name, ' ', sender.surname) as fromname, DATE_FORMAT(tblassignmentreminders.reminddate, '%d.%m.%Y') as reminddate 
            FROM `tblassignmentreminders` LEFT JOIN `tblassignments` ON `tblassignments`.`assignmentnr`=`tblassignmentreminders`.`rel_id` LEFT JOIN `tblremindersubjects` ON `tblremindersubjects`.`id`=`tblassignmentreminders`.`remindersubject` LEFT JOIN `tblusers` as `responsible` ON `responsible`.`userid`=`tblassignments`.`responsible` LEFT JOIN `tblusers` as `sender` ON `sender`.`userid`=`tblassignmentreminders`.`userid` 
            WHERE `tblassignmentreminders`.`rel_type` = 'assignment' AND `tblassignmentreminders`.`isopen`=0 AND `tblassignmentreminders`.`isread`=0 AND `tblassignmentreminders`.`reminddate` <= NOW() AND `tblassignments`.`responsible` = '".get_user_id()."')

            UNION ALL

            (SELECT `tblhardwareassignmentreminders`.`rel_type` as sub_rel_type, `tblhardwareassignmentreminders`.`remindernr` as `id`, `tblhardwareassignmentreminders`.`rel_id`, `tblhardwareassignmentreminders`.`rel_type`, '".lang('page_hardwareassignment')."' as type, `tblremindersubjects`.`name` as `subject`, `tblhardwareassignmentreminders`.`notice` as `message`, CONCAT(sender.name, ' ', sender.surname) as fromname, DATE_FORMAT(tblhardwareassignmentreminders.reminddate, '%d.%m.%Y') as reminddate 
            FROM `tblhardwareassignmentreminders` LEFT JOIN `tblhardwareassignments` ON `tblhardwareassignments`.`hardwareassignmentnr`=`tblhardwareassignmentreminders`.`rel_id` LEFT JOIN `tblremindersubjects` ON `tblremindersubjects`.`id`=`tblhardwareassignmentreminders`.`remindersubject` LEFT JOIN `tblusers` as `responsible` ON `responsible`.`userid`=`tblhardwareassignments`.`responsible` LEFT JOIN `tblusers` as `sender` ON `sender`.`userid`=`tblhardwareassignmentreminders`.`userid` 
            WHERE `tblhardwareassignmentreminders`.`rel_type` = 'hardwareassignment' AND `tblhardwareassignmentreminders`.`isopen`=0 AND `tblhardwareassignmentreminders`.`isread`=0 AND `tblhardwareassignmentreminders`.`reminddate` <= NOW() AND `tblhardwareassignments`.`responsible` = '".get_user_id()."')

            UNION ALL
            
            (SELECT 'ticket' as sub_rel_type, `tbltickets`.`ticketnr` as `id`, `tbltickets`.`ticketnr` as `rel_id`, 'ticket' as `rel_type`, '".lang('page_ticket')."' as type, `tbltickets`.`tickettitle` as `subject`, `tbltickets`.`ticketdesc` as `message`, CONCAT(sender.name, ' ', sender.surname) as fromname, DATE_FORMAT(tbltickets.created, '%d.%m.%Y') as reminddate FROM `tbltickets` JOIN `tblusers` as `sender` ON `sender`.`userid`=`tbltickets`.`userid` WHERE `tbltickets`.`isopen`=0 AND `tbltickets`.`isread`=0 AND `tbltickets`.`responsible` = '".get_user_id()."')
            
            UNION ALL
            
            (SELECT 'comment' as sub_rel_type, `tblnotes`.`id` as `id`, `tblnotes`.`rel_id` as `rel_id`, `tblnotes`.`rel_type` as `rel_type`, '".lang('comment')."' as type, `tblnotes`.`rel_type` as `subject`, `tblnotes`.`description` as `message`, CONCAT(sender.name, ' ', sender.surname) as fromname, DATE_FORMAT(tblnotes.created, '%d.%m.%Y') as reminddate FROM `tblnotes` JOIN `tblusers` as `sender` ON `sender`.`userid`=`tblnotes`.`addedfrom` WHERE `tblnotes`.`isopen`=0 AND `tblnotes`.`isread`=0 AND `tblnotes`.`addedfrom`!=`tblnotes`.`addedto` AND  `tblnotes`.`addedto` = '".get_user_id()."')            

            )  as reminders 
            
            ORDER BY orderdate DESC "); 
        }
        
        /*$data['reminder'] = array_merge($data['leadreminder'],$data['customerreminder'],$data['todoreminder'],$data['quotationreminder'],$data['assignmentreminder'],$data['hardwareassignmentreminder']);
        return $data['reminder'];*/
        
        return $query->result_array();
    }
}