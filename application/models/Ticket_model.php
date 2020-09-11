<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Ticket_model extends CI_Model
{
    var $table = 'tbltickets';
    var $aid = 'ticketnr';
    
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Email_model');
        $this->load->model('Note_model');          
    }

    /**
     * Check if Ticket
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
            $ticket = $this->db->get($this->table)->row();
            if ($ticket) {
                $ticket->attachments = $this->get_ticket_attachments($id);
            }
            return $ticket;
        }
        
        return $this->db->get($this->table)->result_array();
    }
    
    /**
     * Add new Ticket
     * @param array $data Ticket $_POST data
     */
    public function add($data)
    {  
        //Database data
        $data['created'] = date('Y-m-d H:i:s');
        $data['userid'] = get_user_id();
        $data['userrole'] = get_user_role();
        $data['teamwork'] = isset($data['teamwork'])?implode(",",$data['teamwork']):'';
        $this->db->insert($this->table, $data);
        $id = $this->db->insert_id();
        
        if($id>0){   
            //Add ID Prefix
            $dataId = array();
            $dataId['ticketnr_prefix'] = idprefix('ticket',$id);            
            $this->db->where($this->aid, $id);
            $this->db->update($this->table, $dataId);
            
            //Get Qualitychecknr
            $rowfield = $this->get($id,'ticketnr_prefix');        
            //Log Activity
            logActivity('New Ticket Added [ID: ' . $id . ', ' . $rowfield->ticketnr_prefix . ']');            
        }
        
        return $id;
    }
    
    /**
     * Update Ticket
     * @param  array $data Ticket
     * @param  mixed $id   Ticket id
     * @return boolean
     */
    public function update($data, $id)
    {    
        //Database data
        $data['updated'] = date('Y-m-d H:i:s');
        if(isset($data['teamwork'])){
            $data['teamwork'] = isset($data['teamwork'])?implode(",",$data['teamwork']):'';
        }
        $this->db->where($this->aid, $id);     
        $this->db->update($this->table, $data);
        
        if ($this->db->affected_rows() > 0) {
            //Get Qualitychecknr
            $rowfield = $this->get($id,'ticketnr_prefix');        
            //Log Activity
            logActivity('Ticket Updated [ID: ' . $id . ', ' . $rowfield->ticketnr_prefix . ']');            
        } 
        
        return $id;
    }  
    
    /**
     * Delete ticket
     * @param  array $data ticket
     * @param  mixed $id   ticket id
     * @return boolean
     */
    public function delete($id)
    {
        //Get Ticketnr
        $rowfield = $this->get($id,'ticketnr_prefix');        
        $this->db->where($this->aid, $id);
        $this->db->delete($this->table);
        
        //Log Activity
        logActivity('Ticket Deleted [ID: ' . $id . ', ' . $rowfield->ticketnr_prefix . ']');
        
        //Delete Comments
        $comments = $this->Note_model->get('', 'id', array(), " rel_id='".$id."' AND rel_type='ticket' ");
        if(isset($comments) && count($comments)>0){
            foreach($comments as $comment){
               $this->Note_model->delete($comment['id']);
            }                
        }
        
        //Delete Document       
        $documents = $this->get_ticket_attachments($id);
        if(isset($documents) && count($documents)>0){
            foreach($documents as $document){
                $this->delete_ticket_attachment($document['id']);
            }
        }
        
        return 1;
    } 
    
    /**
     * Get ticket attachments
     * @since Version 1.0.4
     * @param  mixed $id ticket id
     * @return array
     */
    public function get_ticket_attachments($id = '', $attachment_id = '')
    {
        if (is_numeric($attachment_id)) {
            $this->db->where('id', $attachment_id);

            return $this->db->get('tblfiles')->row();
        }
        $this->db->where('rel_id', $id);
        $this->db->where('rel_type', 'ticket');
        $this->db->order_by('created', 'DESC');

        return $this->db->get('tblfiles')->result_array();
    }
    
    //Add Attachment 
    public function add_attachment_to_database($rel_id, $attachment, $external = false, $form_activity = false)
    {
        $post = $this->input->post();           
        $data['created'] = date('Y-m-d H:i:s');
        $data['rel_id'] = $rel_id;
        $data['userid'] = get_user_id();
        $data['rel_type'] = 'ticket';        
        $data['attachment_key'] = app_generate_hash();

        if ($external == false) {
            $data['file_name'] = $attachment[0]['file_name'];
            $data['filetype']  = $attachment[0]['filetype'];
        } else {
            $path_parts            = pathinfo($attachment[0]['name']);
            $data['file_name']     = $attachment[0]['name'];
            $data['external_link'] = $attachment[0]['link'];
            $data['filetype']      = get_mime_by_extension('.' . $path_parts['extension']);
            $data['external']      = $external;
            if (isset($attachment[0]['thumbnailLink'])) {
                $data['thumbnail_link'] = $attachment[0]['thumbnailLink'];
            }
        }
        
        $db = $this->db;
        $db->insert('tblfiles', $data);
        $insert_id = $db->insert_id();
        
        if($insert_id>0){
            $ticket = $this->get($rel_id,'ticketnr_prefix');
            logActivity('Ticket Attachment Added [TicketID: ' . $rel_id . ', '.$ticket->ticketnr_prefix.']');
            
            //History 
            $Action_data = array('actionname'=>'ticket', 'actionid'=>$rel_id, 'actionsubid'=>$insert_id, 'actiontitle'=>'ticket_attachment_added');
            do_action_history($Action_data);
        }
        
        return $insert_id;
    }
    
    /**
     * Delete ticket attachment
     * @param  mixed $id attachment id
     * @return boolean
     */
    public function delete_ticket_attachment($id)
    {
        $attachment = $this->get_ticket_attachments('', $id);
        $ticket = $this->get($attachment->rel_id,'ticketnr_prefix');
        $deleted    = false;

        if ($attachment) {
            if (empty($attachment->external)) {
                unlink(get_upload_path_by_type('ticket') . $attachment->rel_id . '/' . $attachment->file_name);
            }
            $this->db->where('id', $attachment->id);
            $this->db->delete('tblfiles');
            if ($this->db->affected_rows() > 0) {
                $deleted = true;
                logActivity('Ticket Attachment Deleted [TicketID: ' . $attachment->rel_id . ', '.$ticket->ticketnr_prefix.']');    
                
                //History 
                $Action_data = array('actionname'=>'ticket', 'actionid'=>$attachment->rel_id, 'actionsubid'=>$attachment->id, 'actiontitle'=>'ticket_attachment_deleted');
                do_action_history($Action_data);
            }

            if (is_dir(get_upload_path_by_type('ticket') . $attachment->rel_id)) {
                // Check if no attachments left, so we can delete the folder also
                $other_attachments = list_files(get_upload_path_by_type('ticket') . $attachment->rel_id);
                if (count($other_attachments) == 0) {
                    // okey only index.html so we can delete the folder also
                    delete_dir(get_upload_path_by_type('ticket') . $attachment->rel_id);
                }
            }
        }

        return $deleted;
    }
    
    //Generate Reminder by Cronjob (Notification Email 1 Day before he set the date)
    public function sendReminder($id='', $submit_type=''){        
        if($submit_type=='comment'){
            //Reminder Ticket creating by comment
            $data = (array) $this->Note_model->get($id,"tbltickets.*, "
                    . "  tblsalutations.name as salutation, responsible.name as name, responsible.surname as surname, responsible.email as email, customer.company as customer_company, "
                    . " (SELECT GROUP_CONCAT(name) FROM tblusers WHERE FIND_IN_SET(userid, tbltickets.teamwork)) as teamwork_name, "
                    . " (SELECT GROUP_CONCAT(surname) FROM tblusers WHERE FIND_IN_SET(userid, tbltickets.teamwork)) as teamwork_surname, "
                    . " (SELECT GROUP_CONCAT(email) FROM tblusers WHERE FIND_IN_SET(userid, tbltickets.teamwork)) as teamwork_email ",
                    array('tbltickets'=>'tbltickets.ticketnr=tblnotes.rel_id',
                        'tblusers as responsible'=>'responsible.userid=tbltickets.responsible',
                        'tblsalutations'=>'tblsalutations.salutationid=responsible.salutation',
                        'tblcustomers as customer'=>'customer.customernr=tbltickets.customer')
            );         
        }
        else{        
            //Reminder Ticket creating by user
            $data = (array) $this->Ticket_model->get($id,"tbltickets.*, "
                    . "  tblsalutations.name as salutation, responsible.name as name, responsible.surname as surname, responsible.email as email, customer.company as customer_company, "
                    . " (SELECT GROUP_CONCAT(name) FROM tblusers WHERE FIND_IN_SET(userid, tbltickets.teamwork)) as teamwork_name, "
                    . " (SELECT GROUP_CONCAT(surname) FROM tblusers WHERE FIND_IN_SET(userid, tbltickets.teamwork)) as teamwork_surname, "
                    . " (SELECT GROUP_CONCAT(email) FROM tblusers WHERE FIND_IN_SET(userid, tbltickets.teamwork)) as teamwork_email ",
                    array('tblusers as responsible'=>'responsible.userid=tbltickets.responsible',
                        'tblsalutations'=>'tblsalutations.salutationid=responsible.salutation',
                        'tblcustomers as customer'=>'customer.customernr=tbltickets.customer')
            );            
        }
        
        if(isset($data['ticketnr'])){
            return $this->sendMail($data, $submit_type);
        }
        else{
            //Loop
            $data1 = $data;
            if(isset($data1) && count($data1)>0){
                foreach($data1 as $data){
                    $this->sendMail($data, $submit_type);
                }                
            }
        }
    }
    
    function sendMail($data, $submit_type=''){
        //print_r($data);exit;        
        $data['linktoticket'] = '<a href="'.base_url('admin/tickets/detail/'. $data['ticketnr']).'" target="_blank">'.lang('click_here').'</a>';
        //All Comments
        $data['comments'] = '';
        $comments = (array) $this->Note_model->get('',"tblnotes.*, CONCAT(tblusers.name,' ',tblusers.surname) as fullname ",array('tblusers'=>'tblusers.userid=tblnotes.addedfrom')," rel_id='".$data['ticketnr']."' AND rel_type='ticket' ","","tblnotes.id desc");            
        if(isset($comments) && count($comments)>0){ 
            $data['comments'].='<table>';                                                
            $iRow = 1;
            foreach($comments as $comment){

                $rowcolor = '';
                if($iRow%2==0){
                   $rowcolor = '#DDE3EC'; 
                }

                $data['comments'].="<tr style='background-color:".$rowcolor."'>";                    
                    $data['comments'].='<td>';

                        $data['comments'].='<table>';
                                $data['comments'].='<tr><td>'.$comment['fullname'].' at '._dt($comment['created']).'</td></tr>';                                    
                                $data['comments'].='<tr><td><b>'.lang('page_dt_comment').'</b>: '.$comment['description'].'</td></tr>';
                        $data['comments'].='</table>';

                    $data['comments'].='</td>';                    
                $data['comments'].='</tr>';

                $iRow++;
            }

            $data['comments'].='</table>';
        }

        //Send to Responsible User of Customer
        $merge_fields = array();     
        $merge_fields = array_merge($merge_fields, get_ticketreminder_merge_fields($data));
        
        //Everywhere when a user make a comment and he is responsive user he should not become a Notification Email. This should work everywhere in the same way (Ticket, Todo and so on)
        if($GLOBALS['current_user']->email==$data['email'] && $submit_type=='comment'){           
        }else{
            $sent = $this->Email_model->send_email_template('ticket-reminder', $data['email'], $merge_fields);
            sleep(1);
        }
        
        $responsible_email = $data['email'];

        //Send to Teamwork of Ticket
        $teamwork_name_array = explode(",",$data['teamwork_name']);
        $teamwork_surname_array = explode(",",$data['teamwork_surname']);
        $teamwork_email_array = explode(",",$data['teamwork_email']);    
        if(count($teamwork_name_array)>0){
            foreach($teamwork_name_array as $tkey=>$teamwork){
                $merge_fields = array();                     
                $data['name'] = $teamwork_name_array[$tkey];
                $data['surname'] = $teamwork_surname_array[$tkey];
                $data['email'] = $teamwork_email_array[$tkey]; 

                //Should not send twice      
                if($responsible_email==$data['email']){ continue; }                

                $merge_fields = array_merge($merge_fields, get_ticketreminder_merge_fields($data));
                $sent = $this->Email_model->send_email_template('ticket-reminder', $data['email'], $merge_fields);
                sleep(1);
            }
        }

        if ($sent) {
            $post = array('email_sent'=>1);
            $this->update($post, $data['ticketnr']);
            do_action('ticketreminder_sent', $data['ticketnr']);
            return 1;
        }     
        else{
            return 0;
        }               
    } 
}
