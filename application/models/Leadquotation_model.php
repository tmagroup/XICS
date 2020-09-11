<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Leadquotation_model extends CI_Model
{
    var $table = 'tblleadquotations';
    var $aid = 'leadquotationnr';

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Leadquotationproduct_model');
        $this->load->model('Leadquotationreminder_model');
        $this->load->model('Quotationreminderopen_model');
        $this->load->model('Email_model');
        $this->load->model('User_model');
        $this->load->model('Quotationstatus_model');
        $this->load->model('Ratemobile_model');
        $this->load->model('Optionmobile_model');
        $this->load->model('Discountlevel_model');
    }

    /**
     * Check if quotation
     * @param  mixed $leadquotationnr
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
            $leadquotation = $this->db->get($this->table)->row();
            if ($leadquotation) {
                $leadquotation->attachments = $this->get_quotation_attachments($id);
            }
            return $leadquotation;
        }

        return $this->db->get($this->table)->result_array();
    }

    /**
     * Add new quotation
     * @param array $data quotation $_POST data
     */
    public function add($data)
    {
        //Database data
        $data['created'] = date('Y-m-d H:i:s');
        $data['userid'] = get_user_id();
        $data['leadquotationdate'] = to_sql_date($data['leadquotationdate'], false);

        $data1 = $data;

        unset($data['count_quotationproduct']);
        foreach($data['mobilenr'] as $fk=>$fd){
            if(isset($data['new_formula_'.$fk])){
                unset($data['new_formula_'.$fk]);
            }
        }

        unset($data['mobilenr']);
        unset($data['vvlneu']);
        unset($data['currentratemobile']);
        unset($data['value1']);
        unset($data['use']);
        unset($data['newratemobile']);
        unset($data['value2']);
        unset($data['endofcontract']);
        unset($data['hardware']);
        unset($data['currentoptionmobile']);
        unset($data['value3']);
        unset($data['newoptionmobile']);
        unset($data['value4']);
        unset($data['simcard_function_id']);
        unset($data['simcard_function_nm']);
        unset($data['simcard_function_qty']);
        unset($data['activationdate']);
        unset($data['ultracard1']);
        unset($data['ultracard2']);

        $this->db->insert($this->table, $data);
        $id = $this->db->insert_id();
        $data = $data1;

        if($id>0){

            //Add ID Prefix
            $dataId = array();
            $dataId['leadquotationnr_prefix'] = idprefix('leadquotation',$id);
            $this->db->where($this->aid, $id);
            $this->db->update($this->table, $dataId);

            //Quotation Products
            if(isset($data['mobilenr'])){
                foreach($data['mobilenr'] as $fk=>$fd){
                    $ultracard1 = isset($data['ultracard1'][$fk])?$data['ultracard1'][$fk]:0;
                    $ultracard2 = isset($data['ultracard2'][$fk])?$data['ultracard2'][$fk]:0;

                    $dataproduct = array('leadquotationnr'=>$id,'mobilenr'=>$fd,
                        'vvlneu'=>$data['vvlneu'][$fk],
                        'currentratemobile'=>$data['currentratemobile'][$fk],
                        'value1'=>$data['value1'][$fk],
                        'use'=>$data['use'][$fk],
                        'newratemobile'=>$data['newratemobile'][$fk],
                        'value2'=>$data['value2'][$fk],
                        'endofcontract'=>$data['endofcontract'][$fk],
                        'hardware'=>$data['hardware'][$fk],
                        'currentoptionmobile'=>$data['currentoptionmobile'][$fk],
                        'value3'=>$data['value3'][$fk],
                        'newoptionmobile'=>$data['newoptionmobile'][$fk],
                        'value4'=>$data['value4'][$fk],
                        'simcard_function_id'=>$data['simcard_function_id'][$fk],
                        'simcard_function_nm'=>$data['simcard_function_nm'][$fk],
                        'simcard_function_qty'=>$data['simcard_function_qty'][$fk],
                        'activationdate'=>$data['activationdate'][$fk],
                        'ultracard1'=>$data['ultracard1'][$fk],
                        'ultracard2'=>$data['ultracard2'][$fk],
                    );
                    if(isset($data['quotationproductid'][$fk]) && $data['quotationproductid'][$fk]>0){
                        //if(trim($fd)!=""){
                            //$dataproduct['formula'] = $data['old_formula_'.$data['quotationproductid'][$fk]];
                            $dataproduct['formula'] = $data['old_formula_'.$fk];
                            $this->Leadquotationproduct_model->update($dataproduct, $data['quotationproductid'][$fk]);
                        /*}else{
                            $this->Leadquotationproduct_model->delete($data['quotationproductid'][$fk]);
                        }*/
                    }else{
                        //if(trim($fd)!=""){
                            $dataproduct['formula'] = $data['new_formula_'.$fk];
                            $this->Leadquotationproduct_model->add($dataproduct);
                        //}
                    }
                }
            }

            //Get Quotationnr
            $rowfield = $this->get($id,'leadquotationnr_prefix');
            //Log Activity
            logActivity('New Lead Quotation Added [ID: ' . $id . ', ' . $rowfield->leadquotationnr_prefix . ']');
        }

        return $id;
    }

    /**
     * Update quotation
     * @param  array $data quotation
     * @param  mixed $id   quotation id
     * @return boolean
     */
    public function update($data, $id)
    {
        //Database data
        $data['updated'] = date('Y-m-d H:i:s');
        if(isset($data['leadquotationdate'])){
            $data['leadquotationdate'] = to_sql_date($data['leadquotationdate'], false);
        }
        $data1 = $data;

        unset($data['count_quotationproduct']);
        if(isset($data['mobilenr'])){
            foreach($data['mobilenr'] as $fk=>$fd){
                if(isset($data['new_formula_'.$fk])){
                    unset($data['new_formula_'.$fk]);
                }
                if(isset($data['old_formula_'.$fk])){
                    unset($data['old_formula_'.$fk]);
                }
                /*if(isset($data['quotationproductid'][$fk]) && $data['quotationproductid'][$fk]>0){
                    unset($data['old_formula_'.$data['quotationproductid'][$fk]]);
                }*/
            }
        }
        unset($data['quotationproductid']);
        unset($data['mobilenr']);
        unset($data['vvlneu']);
        unset($data['currentratemobile']);
        unset($data['value1']);
        unset($data['use']);
        unset($data['newratemobile']);
        unset($data['value2']);
        unset($data['endofcontract']);
        unset($data['hardware']);
        unset($data['currentoptionmobile']);
        unset($data['value3']);
        unset($data['newoptionmobile']);
        unset($data['value4']);
        unset($data['simcard_function_id']);
        unset($data['simcard_function_nm']);
        unset($data['simcard_function_qty']);
        unset($data['activationdate']);
        unset($data['ultracard1']);
        unset($data['ultracard2']);

        $this->db->where($this->aid, $id);
        $this->db->update($this->table, $data);
        $data = $data1;

        //Quotation Products
        if(isset($data['mobilenr'])){
            //Quotation Products
            foreach($data['mobilenr'] as $fk=>$fd){
                $ultracard1 = isset($data['ultracard1'][$fk])?$data['ultracard1'][$fk]:0;
                $ultracard2 = isset($data['ultracard2'][$fk])?$data['ultracard2'][$fk]:0;

                $dataproduct = array('leadquotationnr'=>$id,'mobilenr'=>$fd,
                    'vvlneu'=>$data['vvlneu'][$fk],
                    'currentratemobile'=>$data['currentratemobile'][$fk],
                    'value1'=>$data['value1'][$fk],
                    'use'=>$data['use'][$fk],
                    'newratemobile'=>$data['newratemobile'][$fk],
                    'value2'=>$data['value2'][$fk],
                    'endofcontract'=>$data['endofcontract'][$fk],
                    'hardware'=>$data['hardware'][$fk],
                    'currentoptionmobile'=>$data['currentoptionmobile'][$fk],
                    'value3'=>$data['value3'][$fk],
                    'newoptionmobile'=>$data['newoptionmobile'][$fk],
                    'value4'=>$data['value4'][$fk],
                    'simcard_function_id'=>$data['simcard_function_id'][$fk],
                    'simcard_function_nm'=>$data['simcard_function_nm'][$fk],
                    'simcard_function_qty'=>$data['simcard_function_qty'][$fk],
                    'activationdate'=>$data['activationdate'][$fk],
                    'ultracard1'=>$data['ultracard1'][$fk],
                    'ultracard2'=>$data['ultracard2'][$fk],
                );
                if(isset($data['quotationproductid'][$fk]) && $data['quotationproductid'][$fk]>0){
                    //if(trim($fd)!=""){
                        //$dataproduct['formula'] = $data['old_formula_'.$data['quotationproductid'][$fk]];
                        $dataproduct['formula'] = $data['old_formula_'.$fk];
                        $this->Leadquotationproduct_model->update($dataproduct, $data['quotationproductid'][$fk]);
                    /*}else{
                        $this->Leadquotationproduct_model->delete($data['quotationproductid'][$fk]);
                    }*/
                }else{
                    //if(trim($fd)!=""){
                        $dataproduct['formula'] = $data['new_formula_'.$fk];
                        $this->Leadquotationproduct_model->add($dataproduct);
                    //}
                }
            }
        }

        if ($this->db->affected_rows() > 0) {
            //Get Quotationnr
            $rowfield = $this->get($id,'leadquotationnr_prefix');
            //Log Activity
            logActivity('Lead Quotation Updated [ID: ' . $id . ', ' . $rowfield->leadquotationnr_prefix . ']');
        }

        return $id;
    }

    /**
     * Delete quotation
     * @param  array $data quotation
     * @param  mixed $id   quotation id
     * @return boolean
     */
    public function delete($id)
    {
        //Get Quotationnr
        $rowfield = $this->get($id,'leadquotationnr_prefix');
        $this->db->where($this->aid, $id);
        $this->db->delete($this->table);

        //Log Activity
        logActivity('Lead Quotation Deleted [ID: ' . $id . ', ' . $rowfield->leadquotationnr_prefix . ']');

        //Delete Quotation Products
        $quotationproducts = $this->Leadquotationproduct_model->get('', 'id', array(), " leadquotationnr='".$id."' ");
        if(isset($quotationproducts) && count($quotationproducts)>0){
            foreach($quotationproducts as $quotationproduct){
               $this->Leadquotationproduct_model->delete($quotationproduct['id']);
            }
        }

        //Delete Document
        $documents = $this->get_quotation_attachments($id);
        if(isset($documents) && count($documents)>0){
            foreach($documents as $document){
                $this->delete_quotation_attachment($document['id']);
            }
        }

        //Delete Reminder
        $reminders = $this->Leadquotationreminder_model->get('', 'remindernr', array(), " rel_id='".$id."' AND rel_type='leadquotation' ");
        if(isset($reminders) && count($reminders)>0){
            foreach($reminders as $reminder){
               $this->Leadquotationreminder_model->delete($reminder['remindernr']);
            }
        }

        return 1;
    }

    /**
     * Get lead quotation attachments
     * @since Version 1.0.4
     * @param  mixed $id quotation id
     * @return array
     */
    public function get_quotation_attachments($id = '', $attachment_id = '')
    {
        if (is_numeric($attachment_id)) {
            $this->db->where('id', $attachment_id);

            return $this->db->get('tblfiles')->row();
        }
        $this->db->where('rel_id', $id);
        $this->db->where('rel_type', 'leadquotation');
        $this->db->order_by('created', 'DESC');

        $this->db->join('tbldocumentsettings as category', 'category.categoryid=tblfiles.categoryid', 'left');

        return $this->db->get('tblfiles')->result_array();
    }

    //Add Attachment
    public function add_attachment_to_database($rel_id, $attachment, $external = false, $form_activity = false)
    {
        $post = $this->input->post();
        $data['categoryid'] = $post['categoryid'];

        $data['created'] = date('Y-m-d H:i:s');
        $data['rel_id'] = $rel_id;
        $data['userid'] = get_user_id();
        $data['rel_type'] = 'leadquotation';
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
            $quotation = $this->get($rel_id,'leadquotationnr_prefix');
            logActivity('Lead Quotation Attachment Added [QuotationID: ' . $rel_id . ', '.$quotation->leadquotationnr_prefix.']');

            //History
            $Action_data = array('actionname'=>'leadquotation', 'actionid'=>$rel_id, 'actionsubid'=>$insert_id, 'actiontitle'=>'leadquotation_document_added');
            do_action_history($Action_data);
        }

        return $insert_id;
    }

    /**
     * Delete quotation attachment
     * @param  mixed $id attachment id
     * @return boolean
     */
    public function delete_quotation_attachment($id)
    {
        $attachment = $this->get_quotation_attachments('', $id);
        $quotation = $this->get($attachment->rel_id,'leadquotationnr_prefix');
        $deleted    = false;

        if ($attachment) {
            if (empty($attachment->external)) {
                unlink(get_upload_path_by_type('leadquotation') . $attachment->rel_id . '/' . $attachment->file_name);
            }
            $this->db->where('id', $attachment->id);
            $this->db->delete('tblfiles');
            if ($this->db->affected_rows() > 0) {
                $deleted = true;
                logActivity('Lead Quotation Attachment Deleted [QuotationID: ' . $attachment->rel_id . ', '.$quotation->leadquotationnr_prefix.']');

                //History
                $Action_data = array('actionname'=>'leadquotation', 'actionid'=>$attachment->rel_id, 'actionsubid'=>$attachment->id, 'actiontitle'=>'leadquotation_document_deleted');
                do_action_history($Action_data);
            }

            if (is_dir(get_upload_path_by_type('leadquotation') . $attachment->rel_id)) {
                // Check if no attachments left, so we can delete the folder also
                $other_attachments = list_files(get_upload_path_by_type('leadquotation') . $attachment->rel_id);
                if (count($other_attachments) == 0) {
                    // okey only index.html so we can delete the folder also
                    delete_dir(get_upload_path_by_type('leadquotation') . $attachment->rel_id);
                }
            }
        }

        return $deleted;
    }

    /* Update attachment category */
    public function update_quotation_attachmentcategory($rel_id, $categoryid){
        $data = array('categoryid'=>$categoryid);
        $this->db->update('tblfiles', $data, array('rel_id' => $rel_id, 'rel_type'=>'leadquotation'));
        if ($this->db->affected_rows() > 0) {
            $quotation = $this->get($rel_id,'leadquotationnr_prefix');
            logActivity('Lead Quotation Attachment Updated [QuotationID: ' . $rel_id . ', '.$quotation->leadquotationnr_prefix.']');
            return 1;
        }
    }

    //Generate Reminder by Cronjob (Notification Email 1 Day before he set the date)
    public function sendReminder($reminderid='', $submit_type=''){
        return;
        if($submit_type=='single'){
            //Reminder Quotation
            $data = (array) $this->Leadquotationreminder_model->get($reminderid,'tblquotationreminders.remindernr, tblremindersubjects.name as remindersubject, '
                . 'tblsalutations.name as salutation, '
                . 'responsible.email, '
                . 'responsible.name, '
                . 'responsible.surname, '
                . 'tblleadquotations.leadquotationnr, '
                . 'tblleadquotations.leadquotationnr_prefix, '
                . 'tblleadquotations.company, '
                . 'tblquotationreminders.notice,'
                . 'tblquotationreminders.reminddate ',
                array('tblremindersubjects'=>'tblremindersubjects.id=tblquotationreminders.remindersubject',
                'tblleadquotations'=>'tblleadquotations.leadquotationnr=tblquotationreminders.rel_id',
                'tblusers as responsible'=>'responsible.userid=tblleadquotations.responsible',
                'tblsalutations'=>'tblsalutations.salutationid=responsible.salutation'
                ),
                "tblquotationreminders.rel_type='quotation'"
            );
        }
        else{
            //Reminder Quotation
            $data = (array) $this->Leadquotationreminder_model->get($reminderid,'tblquotationreminders.remindernr, tblremindersubjects.name as remindersubject, '
                . 'tblsalutations.name as salutation, '
                . 'responsible.email, '
                . 'responsible.name, '
                . 'responsible.surname, '
                . 'tblleadquotations.leadquotationnr, '
                . 'tblleadquotations.leadquotationnr_prefix, '
                . 'tblleadquotations.company, '
                . 'tblquotationreminders.notice,'
                . 'tblquotationreminders.reminddate '

                . " , (SELECT IF( (tblcustomers.company IS NULL) , '(NO COMPANY)', tblcustomers.company ) ) AS company ",
                array('tblremindersubjects'=>'tblremindersubjects.id=tblquotationreminders.remindersubject',
                'tblleadquotations'=>'tblleadquotations.leadquotationnr=tblquotationreminders.rel_id',
                'tblusers as responsible'=>'responsible.userid=tblleadquotations.responsible',
                'tblsalutations'=>'tblsalutations.salutationid=responsible.salutation',

                    'tblcustomers'=>'tblcustomers.customernr=tblleadquotations.customer',
                ),
                "tblquotationreminders.rel_type='quotation' AND tblquotationreminders.reminderway=0 AND (tblquotationreminders.email_sent=0 OR ISNULL(tblquotationreminders.email_sent) OR tblquotationreminders.email_sent='') AND tblquotationreminders.reminddate<='".date('Y-m-d H:i:s',strtotime($GLOBALS['current_datetime']."1 day 30 minutes"))."' "
            );
        }

        // print_r($this->db->last_query());
        // echo '<pre>';
        // print_r($data);
        // exit(0);

        if(isset($data['leadquotationnr'])){
            return $this->sendMail($data);
        }else{
            //Loop
            $data1 = $data;
            if(isset($data1) && count($data1)>0){
                foreach($data1 as $data){
                    $this->sendMail($data);
                }
            }
        }
    }

    function sendMail($data){
        $data['linktoreminder'] = '<a href="'.base_url('admin/leadquotations/detail/'. $data['leadquotationnr']).'" target="_blank">'.lang('click_here').'</a>';
        $merge_fields = array();
        $merge_fields = array_merge($merge_fields, get_quotationreminder_merge_fields($data));

        $sent = $this->Email_model->send_email_template('quotation-reminder', $data['email'], $merge_fields);
        $sent = true;
        if ($sent) {
            // Set to status sent
            $post = array('email_sent'=>1);
            $this->Leadquotationreminder_model->update($post,$data['remindernr']);
            do_action('quotationreminder_sent', $data['remindernr']);
            return 1;
        }
        else{
            return 0;
        }
    }


    //Generate Reminder by Cronjob of quotation three times (Status=Erstellt)
    public function sendReminder_quotation($leadquotationnr='', $submit_type=''){
        return;

        if($submit_type=='single'){
            //Reminder Quotation
            $data = (array) $this->Quotation_model->get($leadquotationnr,' '
                . 'tblsalutations.name as salutation, '
                . 'responsible.email, '
                . 'responsible.name, '
                . 'responsible.surname, '
                . 'tblleadquotations.leadquotationnr, '
                . 'tblleadquotations.leadquotationnr_prefix, '
                . 'tblleadquotations.company,'
                . 'tblleadquotations.quotationdate,'
                . 'tblleadquotations.responsible ',

                array('tblusers as responsible'=>'responsible.userid=tblleadquotations.responsible',
                'tblsalutations'=>'tblsalutations.salutationid=responsible.salutation'
                ),

                "tblleadquotations.leadquotationstatus='1'"
            );
        }
        else{
            //Reminder Quotation
            $data = (array) $this->Quotation_model->get($leadquotationnr,' '
                . 'tblsalutations.name as salutation, '
                . 'responsible.email, '
                . 'responsible.name, '
                . 'responsible.surname, '
                . 'tblleadquotations.leadquotationnr, '
                . 'tblleadquotations.leadquotationnr_prefix, '
                . 'tblleadquotations.company,'
                . 'tblleadquotations.quotationdate, '
                . 'tblleadquotations.responsible '

                . " , (SELECT IF( (tblcustomers.company IS NULL) , '(NO COMPANY)', tblcustomers.company ) ) AS company ",

                array('tblusers as responsible'=>'responsible.userid=tblleadquotations.responsible',
                'tblsalutations'=>'tblsalutations.salutationid=responsible.salutation',

                    'tblcustomers'=>'tblcustomers.customernr=tblleadquotations.customer',

                ),

               "tblleadquotations.leadquotationstatus='1'"
            );
        }

        // print_r($this->db->last_query());
        // echo '<pre>';
        // print_r($data);
        // exit(0);

        if(isset($data['leadquotationnr'])){

            /*******************************************************************/
            //Logic of Send Quotation Reminder Open every 10 days three times
            /*******************************************************************/
            $rowSend = $this->Quotationreminderopen_model->get('', '', array()," leadquotationnr='".$data['leadquotationnr']."' ","sendno", "1");
            if(isset($rowSend) && count($rowSend)>0){
                $dateQuotation=date_create(date('Y-m-d',strtotime($rowSend[0]['senddate'])));//PHP Internal Function
                $dateToday=date_create(date('Y-m-d')); //PHP Internal Function
                $diff=date_diff($dateQuotation,$dateToday); //PHP Internal Function

                if($diff->d==4 && $rowSend[0]['sendno']==1){
                    return $this->sendMail_quotation($data,2);
                }
                if($diff->d==5 && $rowSend[0]['sendno']==2){
                    return $this->sendMail_quotation($data,3);
                }
            }
            else{
                $dateQuotation=date_create($data['quotationdate']);//PHP Internal Function
                $dateToday=date_create(date('Y-m-d')); //PHP Internal Function
                $diff=date_diff($dateQuotation,$dateToday); //PHP Internal Function
                if(strtotime(date('Y-m-d'))>strtotime($data['quotationdate'])){
                    if($diff->d>10){
                        return $this->sendMail_quotation($data,1);
                    }
                }
            }
            /*******************************************************************/
            //End Logic of Send Quotation Reminder Open every 10 days three times
            /*******************************************************************/

        }else{
            //Loop
            $data1 = $data;
            if(isset($data1) && count($data1)>0){
                foreach($data1 as $data){

                    /*******************************************************************/
                    //Logic of Send Quotation Reminder Open every 10 days three times
                    /*******************************************************************/
                    $rowSend = $this->Quotationreminderopen_model->get('', '', array()," leadquotationnr='".$data['leadquotationnr']."' ","sendno", "1");
                    if(isset($rowSend) && count($rowSend)>0){
                        $dateQuotation=date_create(date('Y-m-d',strtotime($rowSend[0]['senddate'])));//PHP Internal Function
                        $dateToday=date_create(date('Y-m-d')); //PHP Internal Function
                        $diff=date_diff($dateQuotation,$dateToday); //PHP Internal Function

                        if($diff->d==4 && $rowSend[0]['sendno']==1){
                            $this->sendMail_quotation($data,2);
                        }
                        if($diff->d==5 && $rowSend[0]['sendno']==2){
                            $this->sendMail_quotation($data,3);
                        }
                    }
                    else{
                        $dateQuotation=date_create($data['quotationdate']);//PHP Internal Function
                        $dateToday=date_create(date('Y-m-d')); //PHP Internal Function
                        $diff=date_diff($dateQuotation,$dateToday); //PHP Internal Function
                        if(strtotime(date('Y-m-d'))>strtotime($data['quotationdate'])){
                            if($diff->d>10){
                                $this->sendMail_quotation($data,1);
                            }
                        }
                    }
                    /*******************************************************************/
                    //End Logic of Send Quotation Reminder Open every 10 days three times
                    /*******************************************************************/

                }
            }
        }
    }

    function sendMail_quotation($data,$no){
        $data['linktoquotation'] = '<a href="'.base_url('admin/leadquotations/detail/'. $data['leadquotationnr']).'" target="_blank">'.lang('click_here').'</a>';
        $merge_fields = array();
        $merge_fields = array_merge($merge_fields, get_quotationreminder_quotation_merge_fields($data));

        $sent = $this->Email_model->send_email_template('quotation-reminder-open'.$no, $data['email'], $merge_fields);
        $sent = true;
        if ($sent) {
            $post = array('leadquotationnr'=>$data['leadquotationnr'],'responsible'=>$data['responsible'],'sendno'=>$no);
            $this->Quotationreminderopen_model->add($post);
            do_action('quotationreminderopen_sent', $data['leadquotationnr']);
            return 1;
        }
        else{
            return 0;
        }
    }


    //Get Value for Mobile Rate 1 or Mobile Rate 2 Auto Calculation
    public function getMobileRateValue($id, $discountlevel, $formula){
        if($id!="" && $id!="none"){
            $mobilerate = $this->Ratemobile_model->get($id,'price, ultracard');
            $discountlevel = $this->Discountlevel_model->get($discountlevel,'discountvalue');

            //A is for Auto and M is for Manual
            if($formula=='A'){
                if(isset($discountlevel->discountvalue)){
                    return round($mobilerate->price/(1+($discountlevel->discountvalue/100)),2).'[=]'.$mobilerate->ultracard;
                }
                else{
                    return $mobilerate->price.'[=]'.$mobilerate->ultracard;
                }
            }else{
                return $mobilerate->price.'[=]'.$mobilerate->ultracard;
            }
        }else{
            return '';
        }
    }

    //Get Value for Mobile Option 1 or Mobile Option 2 Price
    public function getMobileOptionValue($id){
        if($id!="" && $id!="none"){
            $mobileoption = $this->Optionmobile_model->get($id,'price');
            return $mobileoption->price;
        }else{
            echo '';
        }
    }
}
