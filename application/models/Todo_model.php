<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Todo_model extends CI_Model
{
    var $table = 'tbltodos';
    var $aid = 'todonr';

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Note_model');
        $this->load->model('Email_model');
    }

    /**
     * Check if todo
     * @param  mixed $todonr
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
     * Add new todo
     * @param array $data todo $_POST data
     */
    public function add($data)
    {
        //Database data
        $data['created'] = date('Y-m-d H:i:s');
        $data['userid'] = get_user_id();

        if(isset($data['startdate'])){
            $data['startdate'] = to_sql_date($data['startdate'], false);
        }
        if(isset($data['reminderdate'])){
            $data['reminderdate'] = to_sql_date($data['reminderdate'], false);
        }
        if(isset($data['tododesc'])){
            $data['tododesc'] = htmlentities($post['tododesc']);
        }
        $data['reminderway'] = isset($data['reminderway'])?1:0;
        $data['teamwork'] = isset($data['teamwork'])?implode(",",$data['teamwork']):'';

        $this->db->insert($this->table, $data);
        $id = $this->db->insert_id();

        if($id>0){
            //Add ID Prefix
            $dataId = array();
            $dataId['todonr_prefix'] = idprefix('todo',$id);
            $this->db->where($this->aid, $id);
            $this->db->update($this->table, $dataId);

            //Get Todonr
            $rowfield = $this->get($id,'todonr_prefix');
            //Log Activity
            logActivity('New Todo Added [ID: ' . $id . ', ' . $rowfield->todonr_prefix . ']');
        }

        return $id;
    }

    /**
     * Update todo
     * @param  array $data todo
     * @param  mixed $id   todo id
     * @return boolean
     */
    public function update($data, $id, $popup=false)
    {
        //Database data
        $data['updated'] = date('Y-m-d H:i:s');

        if(isset($data['startdate'])){
            $data['startdate'] = to_sql_date($data['startdate'], false);
        }

        if(isset($data['reminderdate'])){
            $data['reminderdate'] = to_sql_date($data['reminderdate'], false);
        }

        if(!$popup){
            $data['reminderway'] = isset($data['reminderway'])?1:0;
        }

        if(isset($data['teamwork'])){
            $data['teamwork'] = isset($data['teamwork'])?implode(",",$data['teamwork']):'';
        }

        if(isset($data['tododesc'])){
            $data['tododesc'] = htmlentities($post['tododesc']);
        }

        $this->db->where($this->aid, $id);
        $this->db->update($this->table, $data);

        if ($this->db->affected_rows() > 0) {
            //Get Todonr
            $rowfield = $this->get($id,'todonr_prefix');
            //Log Activity
            logActivity('Todo Updated [ID: ' . $id . ', ' . $rowfield->todonr_prefix . ']');
        }

        return $id;
    }

    /**
     * Delete todo
     * @param  array $data todo
     * @param  mixed $id   todo id
     * @return boolean
     */
    public function delete($id)
    {
        //Get Todonr
        $rowfield = $this->get($id,'todonr_prefix');
        $this->db->where($this->aid, $id);
        $this->db->delete($this->table);

        //Log Activity
        logActivity('Todo Deleted [ID: ' . $id . ', ' . $rowfield->todonr_prefix . ']');

        //Delete Comments
        $comments = $this->Note_model->get('', 'id', array(), " rel_id='".$id."' AND rel_type='todo' ");
        if(isset($comments) && count($comments)>0){
            foreach($comments as $comment){
               $this->Note_model->delete($comment['id']);
            }
        }
        return 1;
    }

    //Generate Reminder by Cronjob (Notification Email 1 Day before he set the date)
    public function sendReminder($id='', $submit_type=''){
        if($submit_type=='comment'){
            //Reminder Todo creating by comment
            $data = (array) $this->Note_model->get($id,"tbltodos.*, "
                    . "  tblsalutations.name as salutation, responsible.name as name, responsible.surname as surname, responsible.email as email, "
                    . " (SELECT GROUP_CONCAT(name) FROM tblusers WHERE FIND_IN_SET(userid, tbltodos.teamwork)) as teamwork_name, "
                    . " (SELECT GROUP_CONCAT(surname) FROM tblusers WHERE FIND_IN_SET(userid, tbltodos.teamwork)) as teamwork_surname, "
                    . " (SELECT GROUP_CONCAT(email) FROM tblusers WHERE FIND_IN_SET(userid, tbltodos.teamwork)) as teamwork_email ",
                    array('tbltodos'=>'tbltodos.todonr=tblnotes.rel_id',
                        'tblusers as responsible'=>'responsible.userid=tbltodos.responsible',
                        'tblsalutations'=>'tblsalutations.salutationid=responsible.salutation')
            );
        }
        else if($submit_type=='single'){
            //Reminder Todo creating by user
            $data = (array) $this->Todo_model->get($id,"tbltodos.*, "
                    . "  tblsalutations.name as salutation, responsible.name as name, responsible.surname as surname, responsible.email as email, "
                    . " (SELECT GROUP_CONCAT(name) FROM tblusers WHERE FIND_IN_SET(userid, tbltodos.teamwork)) as teamwork_name, "
                    . " (SELECT GROUP_CONCAT(surname) FROM tblusers WHERE FIND_IN_SET(userid, tbltodos.teamwork)) as teamwork_surname, "
                    . " (SELECT GROUP_CONCAT(email) FROM tblusers WHERE FIND_IN_SET(userid, tbltodos.teamwork)) as teamwork_email ",
                    array('tblusers as responsible'=>'responsible.userid=tbltodos.responsible',
                        'tblsalutations'=>'tblsalutations.salutationid=responsible.salutation'),
                    " tbltodos.reminderway=0 AND (tbltodos.email_sent=0 OR ISNULL(tbltodos.email_sent) OR tbltodos.email_sent='') "
            );
        }
        else{
            //Reminder Todo creating by user
            //// $data = (array) $this->Todo_model->get($id,"tbltodos.*, "
            ////         . "  tblsalutations.name as salutation, responsible.name as name, responsible.surname as surname, responsible.email as email, "
            ////         . " (SELECT GROUP_CONCAT(name) FROM tblusers WHERE FIND_IN_SET(userid, tbltodos.teamwork)) as teamwork_name, "
            ////         . " (SELECT GROUP_CONCAT(surname) FROM tblusers WHERE FIND_IN_SET(userid, tbltodos.teamwork)) as teamwork_surname, "
            ////         . " (SELECT GROUP_CONCAT(email) FROM tblusers WHERE FIND_IN_SET(userid, tbltodos.teamwork)) as teamwork_email ",
            ////         array('tblusers as responsible'=>'responsible.userid=tbltodos.responsible',
            ////             'tblsalutations'=>'tblsalutations.salutationid=responsible.salutation'),
            ////         " tbltodos.reminderway=0 AND (tbltodos.email_sent=0 OR ISNULL(tbltodos.email_sent) OR tbltodos.email_sent='') AND tbltodos.reminderdate<='".$GLOBALS['current_datetime']."' "
            //// );


            //
            $t_where = " tbltodos.reminderway=0 AND (tbltodos.email_sent=0 OR ISNULL(tbltodos.email_sent) OR tbltodos.email_sent='') AND tbltodos.reminderdate<='".$GLOBALS['current_datetime']."' ";
            //Reminder Todo creating by user
            $data = (array) $this->Todo_model->get($id,"tbltodos.*, "
                    . "  tblsalutations.name as salutation, responsible.name as name, responsible.surname as surname, responsible.email as email, "
                    . " (SELECT GROUP_CONCAT(name) FROM tblusers WHERE FIND_IN_SET(userid, tbltodos.teamwork)) as teamwork_name, "
                    . " (SELECT GROUP_CONCAT(surname) FROM tblusers WHERE FIND_IN_SET(userid, tbltodos.teamwork)) as teamwork_surname, "
                    . " (SELECT GROUP_CONCAT(email) FROM tblusers WHERE FIND_IN_SET(userid, tbltodos.teamwork)) as teamwork_email "
                    // . ", (SELECT company FROM tblcustomers WHERE tblcustomers.customernr=tbltodos.customer) AS todocompany ",
                    . " , (SELECT (SELECT IF(COUNT(tbltodos.customer) = 0 OR tbltodos.customer IS NULL OR tbltodos.customer = 0, '(NO COMPANY)', tblcustomers.company) FROM tblcustomers, tbltodos WHERE tblcustomers.customernr=tbltodos.customer AND $t_where)) AS todocompany ",
                    array(
                        'tblusers as responsible' => 'responsible.userid=tbltodos.responsible',
                        'tblsalutations' => 'tblsalutations.salutationid=responsible.salutation'
                    ),
                    $t_where
            );
        }

        // echo '<pre>';
        // print_r($this->db->last_query());
        // print_r($data);
        // exit(0);

        if(isset($data['todonr'])){
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
        $data['linktoreminder'] = '<a href="'.base_url('admin/todos/detail/'. $data['todonr']).'" target="_blank">'.lang('click_here').'</a>';
        //All Comments
        $data['comments'] = '';
        $comments = (array) $this->Note_model->get('',"tblnotes.*, CONCAT(tblusers.name,' ',tblusers.surname) as fullname ",array('tblusers'=>'tblusers.userid=tblnotes.addedfrom')," rel_id='".$data['todonr']."' AND rel_type='todo' ","","tblnotes.id desc");
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
        $merge_fields = array_merge($merge_fields, get_todoreminder_merge_fields($data));

        //Everywhere when a user make a comment and he is responsive user he should not become a Notification Email. This should work everywhere in the same way (Ticket, Todo and so on)
        if($GLOBALS['current_user']->email==$data['email'] && $submit_type=='comment'){
        }else{
            $sent = $this->Email_model->send_email_template('todo-reminder', $data['email'], $merge_fields,'','',$data['todonr']);
            // $sent = $this->Email_model->send_email_template('todo-reminder', 'test.usertm1@mailinator.com', $merge_fields);

            if ($sent) {
                $post = array('email_sent'=>1);
                $this->update($post, $data['todonr']);

                do_action('todoreminder_sent', $data['todonr']);
            }

            sleep(1);
        }

        $responsible_email = $data['email'];

        //Send to Teamwork of Todo
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

                $merge_fields = array_merge($merge_fields, get_todoreminder_merge_fields($data));
                $sent = $this->Email_model->send_email_template('todo-reminder', $data['email'], $merge_fields,'','',$data['todonr']);
                // $sent = $this->Email_model->send_email_template('todo-reminder', 'test.usertm2@mailinator.com', $merge_fields);

                if ($sent) {
                    $post = array('email_sent'=>1);
                    $this->update($post, $data['todonr']);

                    do_action('todoreminder_sent', $data['todonr']);
                }

                sleep(1);
            }
        }

        /*if ($sent) {
            $post = array('email_sent'=>1);
            $this->update($post, $data['todonr']);
            echo '<pre>';
            print_r($this->db->last_query());

            do_action('todoreminder_sent', $data['todonr']);
            return 1;
        }
        else{
            return 0;
        }*/
    }
}
