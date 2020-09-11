<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Customer_model extends CI_Model
{
    var $table = 'tblcustomers';
    var $aid = 'customernr';

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Customerprovidercompany_model');
        $this->load->model('Customerreminder_model');
        $this->load->model('Email_model');
        $this->load->model('Note_model');
        $this->load->model('User_model');
    }

    /**
     * Check if customer
     * @param  mixed $customernr
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
            $customer = $this->db->get($this->table)->row();
            if ($customer) {
                $customer->attachments = $this->get_customer_attachments($id);
            }
            return $customer;
        }

        return $this->db->get($this->table)->result_array();
    }

    /**
     * Add new customer
     * @param array $data customer $_POST data
     */
    public function add($data)
    {
        //Check Customer Username
        if(isset($data['username'])){
            $this->db->where('username', trim($data['username']));
            $username = $this->db->get($this->table)->row();
            if ($username) {
                return lang('page_form_validation_username_already_exists');
            }
        }

        //Check Customer Email
        $this->db->where('email', trim($data['email']));
        $email = $this->db->get($this->table)->row();
        if ($email) {
            return lang('page_form_validation_email_already_exists');
        }

        //Check Staff Username
        if(isset($data['username'])){
            $this->db->where('username', trim($data['username']));
            $s_username = $this->db->get('tblusers')->row();
            if ($s_username) {
                return lang('page_form_validation_username_already_exists');
            }
        }

        //Check Staff Email
        $this->db->where('email', trim($data['email']));
        $s_email = $this->db->get('tblusers')->row();
        if ($s_email) {
            return lang('page_form_validation_email_already_exists');
        }


        //Database data
        $data['created'] = date('Y-m-d H:i:s');
        $data['userid'] = get_user_id();
        if(isset($data['lastcontact'])){
            $data['lastcontact'] = to_sql_date($data['lastcontact'], true);
        }
        $data['monitoring'] = isset($data['monitoring'])?1:0;


        //Password Encrpted
        if(isset($data['password'])){
            $this->load->helper('phpass');
            $original_password = $data['password'];
            $hasher = new PasswordHash(PHPASS_HASH_STRENGTH, PHPASS_HASH_PORTABLE);
            $data['password'] = $hasher->HashPassword($data['password']);
        }

        //Unnecessory data
        if(isset($data['cpassword'])){
            unset($data['cpassword']);
        }


        $data1 = $data;
        unset($data['customerprovidercompanyid']);
        unset($data['providernr']);
        if(isset($data['count_customerprovidercompany'])){
            unset($data['count_customerprovidercompany']);
        }
        $this->db->insert($this->table, $data);
        $id = $this->db->insert_id();
        $data = $data1;

        if($id>0){
            //Company Provider Numbers
            if(isset($data['providernr'])){
                foreach($data['providernr'] as $fk=>$fd){
                    if(isset($data['customerprovidercompanyid'][$fk]) && $data['customerprovidercompanyid'][$fk]>0){
                        if(trim($fd)!=""){
                            $dataprovider = array('providernr'=>$fd);
                            $this->Customerprovidercompany_model->update($dataprovider, $data['customerprovidercompanyid'][$fk]);
                        }else{
                            $this->Customerprovidercompany_model->delete($data['customerprovidercompanyid'][$fk]);
                        }
                    }else{
                        if(trim($fd)!=""){
                            $dataprovider = array('customernr'=>$id,'providernr'=>$fd);
                            $this->Customerprovidercompany_model->add($dataprovider);
                        }
                    }
                }
            }

            //Add ID Prefix
            $dataId = array();
            $dataId['customernr_prefix'] = idprefix('customer',$id);
            $this->db->where($this->aid, $id);
            $this->db->update($this->table, $dataId);
            //Get Customernr
            $rowfield = $this->get($id,'customernr_prefix');
            //Log Activity
            logActivity('New Customer Added [ID: ' . $id . ', ' . $rowfield->customernr_prefix . ']');
        }

        return $id;
    }

    /**
     * Update customer
     * @param  array $data customer
     * @param  mixed $id   customer id
     * @return boolean
     */
    public function update($data, $id)
    {
        //Check Username
        if(isset($data['username'])){
            $this->db->where($this->aid.'!=', $id);
            $this->db->where('username', trim($data['username']));
            $email = $this->db->get($this->table)->row();
            if ($email) {
                return lang('page_form_validation_username_already_exists');
            }
        }

        //Check Email
        if(isset($data['email'])){
            $this->db->where($this->aid.'!=', $id);
            $this->db->where('email', trim($data['email']));
            $email = $this->db->get($this->table)->row();
            if ($email) {
                return lang('page_form_validation_email_already_exists');
            }
        }

        //Check Staff Username
        if(isset($data['username'])){
            $this->db->where('username', trim($data['username']));
            $s_username = $this->db->get('tblusers')->row();
            if ($s_username) {
                return lang('page_form_validation_username_already_exists');
            }
        }

        //Check Staff Email
        if(isset($data['email'])){
            $this->db->where('email', trim($data['email']));
            $s_email = $this->db->get('tblusers')->row();
            if ($s_email) {
                return lang('page_form_validation_email_already_exists');
            }
        }

        //Database data
        $data['updated'] = date('Y-m-d H:i:s');
        if(isset($data['lastcontact'])){
            $data['lastcontact'] = to_sql_date($data['lastcontact'], true);
        }
        $data['monitoring'] = isset($data['monitoring'])?1:0;


        //Password Encrpted
        if(isset($data['password'])){
            if($data['password']!=""){
                $this->load->helper('phpass');
                $original_password = $data['password'];
                $hasher = new PasswordHash(PHPASS_HASH_STRENGTH, PHPASS_HASH_PORTABLE);
                $data['password'] = $hasher->HashPassword($data['password']);
            }else{
                unset($data['password']);
            }
        }

        //Unnecessory data
        if(isset($data['cpassword'])){
            unset($data['cpassword']);
        }


        $data1 = $data;
        unset($data['customerprovidercompanyid']);
        unset($data['providernr']);
        unset($data['count_customerprovidercompany']);
        $this->db->where($this->aid, $id);
        $this->db->update($this->table, $data);
        $data = $data1;

        //Company Provider Numbers
        if(isset($data['providernr'])){
            foreach($data['providernr'] as $fk=>$fd){
                if(isset($data['customerprovidercompanyid'][$fk]) && $data['customerprovidercompanyid'][$fk]>0){
                    if(trim($fd)!=""){
                        $dataprovider = array('providernr'=>$fd);
                        $this->Customerprovidercompany_model->update($dataprovider, $data['customerprovidercompanyid'][$fk]);
                    }
                    else{
                        $this->Customerprovidercompany_model->delete($data['customerprovidercompanyid'][$fk]);
                    }
                }else{
                    if(trim($fd)!=""){
                        $dataprovider = array('customernr'=>$id,'providernr'=>$fd);
                        $this->Customerprovidercompany_model->add($dataprovider);
                    }
                }
            }
        }

        if ($this->db->affected_rows() > 0) {

            //Get Customernr
            $rowfield = $this->get($id,'customernr_prefix');
            //Log Activity
            logActivity('Customer Updated [ID: ' . $id . ', ' . $rowfield->customernr_prefix . ']');
        }

        return $id;
    }

    /**
     * Delete customer
     * @param  array $data customer
     * @param  mixed $id   customer id
     * @return boolean
     */
    public function delete($id)
    {
        //Get Customernr
        $rowfield = $this->get($id,'customernr_prefix');
        $this->db->where($this->aid, $id);
        $this->db->delete($this->table);

        //Log Activity
        logActivity('Customer Deleted [ID: ' . $id . ', ' . $rowfield->customernr_prefix . ']');


        //Delete Customer Profile Image
        handle_customer_profile_image_delete($id);


        //Delete Provider Company
        $providers = $this->Customerprovidercompany_model->get('', 'id', " customernr='".$id."' ");
        if(isset($providers) && count($providers)>0){
            foreach($providers as $provider){
               $this->Customerprovidercompany_model->delete($provider['id']);
            }
        }

        //Delete Comments
        $comments = $this->Note_model->get('', 'id', array(), " rel_id='".$id."' AND rel_type='customer' ");
        if(isset($comments) && count($comments)>0){
            foreach($comments as $comment){
               $this->Note_model->delete($comment['id']);
            }
        }

        //Delete Document
        $documents = $this->get_customer_attachments($id);
        if(isset($documents) && count($documents)>0){
            foreach($documents as $document){
                $this->delete_customer_attachment($document['id']);
            }
        }

        //Delete Reminder
        $reminders = $this->Customerreminder_model->get('', 'remindernr', array(), " rel_id='".$id."' AND rel_type='customer' ");
        if(isset($reminders) && count($reminders)>0){
            foreach($reminders as $reminder){
               $this->Customerreminder_model->delete($reminder['remindernr']);
            }
        }

        return 1;
    }

    /**
     * Get customer attachments
     * @since Version 1.0.4
     * @param  mixed $id customer id
     * @return array
     */
    public function get_customer_attachments($id = '', $attachment_id = '')
    {
        if (is_numeric($attachment_id)) {
            $this->db->where('id', $attachment_id);

            return $this->db->get('tblfiles')->row();
        }
        $this->db->where('rel_id', $id);
        //$this->db->where('rel_type', 'customer');
        $this->db->where('rel_type', 'customerdocument');
        $this->db->order_by('created', 'DESC');

        $this->db->join('tbldocumentsettings as category', 'category.categoryid=tblfiles.categoryid', 'left');

        return $this->db->get('tblfiles')->result_array();
    }

    public function add_attachment_to_database($rel_id, $attachment, $external = false, $form_activity = false)
    {
        if($this->input->post()){
            $post = $this->input->post();

            if(isset($post['categoryid'])){
                $data['categoryid'] = $post['categoryid'];
            }
            else{
                $data['categoryid'] = $attachment[0]['categoryid'];
            }

        }else{
            $data['categoryid'] = $attachment[0]['categoryid'];
        }

        $data['created'] = date('Y-m-d H:i:s');
        $data['rel_id'] = $rel_id;
        $data['userid'] = get_user_id();
        //$data['rel_type'] = 'customer';
        $data['rel_type'] = 'customerdocument';
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
            $customer = $this->get($rel_id,'customernr_prefix');
            @logActivity('Customer Attachment Added [CustomerID: ' . $rel_id . ', '.$customer->customernr_prefix.']');

            //History
            $Action_data = array('actionname'=>'customer', 'actionid'=>$rel_id, 'actionsubid'=>$insert_id, 'actiontitle'=>'customer_document_added');
            do_action_history($Action_data);
        }

        return $insert_id;
    }

    /**
     * Delete customer attachment
     * @param  mixed $id attachment id
     * @return boolean
     */
    public function delete_customer_attachment($id)
    {
        $attachment = $this->get_customer_attachments('', $id);
        $customer = $this->get($attachment->rel_id,'customernr_prefix');
        $deleted    = false;

        if ($attachment) {
            if (empty($attachment->external)) {
                //unlink(get_upload_path_by_type('customer') . $attachment->rel_id . '/' . $attachment->file_name);
                unlink(get_upload_path_by_type('customerdocument') . $attachment->rel_id . '/' . $attachment->file_name);
            }
            $this->db->where('id', $attachment->id);
            $this->db->delete('tblfiles');
            if ($this->db->affected_rows() > 0) {
                $deleted = true;
                logActivity('Customer Attachment Deleted [CustomerID: ' . $attachment->rel_id . ', '.$customer->customernr_prefix.']');

                //History
                $Action_data = array('actionname'=>'customer', 'actionid'=>$attachment->rel_id, 'actionsubid'=>$attachment->id, 'actiontitle'=>'customer_document_deleted');
                do_action_history($Action_data);
            }

            if (is_dir(get_upload_path_by_type('customer') . $attachment->rel_id)) {
                // Check if no attachments left, so we can delete the folder also
                $other_attachments = list_files(get_upload_path_by_type('customer') . $attachment->rel_id);
                if (count($other_attachments) == 0) {
                    // okey only index.html so we can delete the folder also
                    //delete_dir(get_upload_path_by_type('customer') . $attachment->rel_id);
                    delete_dir(get_upload_path_by_type('customerdocument') . $attachment->rel_id);
                }
            }
        }

        return $deleted;
    }


    public function update_customer_attachmentcategory($rel_id, $categoryid){
        $data = array('categoryid'=>$categoryid);
        $this->db->update('tblfiles', $data, array('rel_id' => $rel_id, 'rel_type'=>'customer'));
        if ($this->db->affected_rows() > 0) {
            $customer = $this->get($rel_id,'customernr_prefix');
            logActivity('Customer Attachment Updated [CustomerID: ' . $rel_id . ', '.$customer->customernr_prefix.']');
            return 1;
        }
    }


    /**
     * Get customer attachments
     * @since Version 1.0.4
     * @param  mixed $id customer id
     * @return array
     */
    public function get_customer_internal_attachments($id = '', $attachment_id = '')
    {
        if (is_numeric($attachment_id)) {
            $this->db->where('id', $attachment_id);

            return $this->db->get('tblfiles')->row();
        }
        $this->db->where('rel_id', $id);
        //$this->db->where('rel_type', 'customer');
        $this->db->where('rel_type', 'customerinternaldocument');
        $this->db->order_by('created', 'DESC');

        $this->db->join('tbldocumentsettings as category', 'category.categoryid=tblfiles.categoryid', 'left');

        return $this->db->get('tblfiles')->result_array();
    }

    public function add_internal_doc_attachment_to_database($rel_id, $attachment, $external = false, $form_activity = false)
    {
        if($this->input->post()){
            $post = $this->input->post();

            if(isset($post['categoryid'])){
                $data['categoryid'] = $post['categoryid'];
            }
            else{
                $data['categoryid'] = $attachment[0]['categoryid'];
            }

        }else{
            $data['categoryid'] = $attachment[0]['categoryid'];
        }

        $data['created'] = date('Y-m-d H:i:s');
        $data['rel_id'] = $rel_id;
        $data['userid'] = get_user_id();
        //$data['rel_type'] = 'customer';
        $data['rel_type'] = 'customerinternaldocument';
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
            $customer = $this->get($rel_id,'customernr_prefix');
            @logActivity('Customer Attachment Added [CustomerID: ' . $rel_id . ', '.$customer->customernr_prefix.']');

            //History
            $Action_data = array('actionname'=>'customer', 'actionid'=>$rel_id, 'actionsubid'=>$insert_id, 'actiontitle'=>'customer_internal_document_added');
            do_action_history($Action_data);
        }

        return $insert_id;
    }

    /**
     * Delete customer internal attachment
     * @param  mixed $id attachment id
     * @return boolean
     */
    public function delete_customer_internal_attachment($id)
    {
        $attachment = $this->get_customer_internal_attachments('', $id);
        $customer = $this->get($attachment->rel_id,'customernr_prefix');
        $deleted    = false;

        if ($attachment) {
            if (empty($attachment->external)) {
                //unlink(get_upload_path_by_type('customer') . $attachment->rel_id . '/' . $attachment->file_name);
                unlink(get_upload_path_by_type('customerinternaldocument') . $attachment->rel_id . '/' . $attachment->file_name);
            }
            $this->db->where('id', $attachment->id);
            $this->db->delete('tblfiles');
            if ($this->db->affected_rows() > 0) {
                $deleted = true;
                logActivity('Customer Internal Attachment Deleted [CustomerID: ' . $attachment->rel_id . ', '.$customer->customernr_prefix.']');

                //History
                $Action_data = array('actionname'=>'customer', 'actionid'=>$attachment->rel_id, 'actionsubid'=>$attachment->id, 'actiontitle'=>'customer_internal_document_deleted');
                do_action_history($Action_data);
            }

            if (is_dir(get_upload_path_by_type('customerinternaldocument') . $attachment->rel_id)) {
                // Check if no attachments left, so we can delete the folder also
                $other_attachments = list_files(get_upload_path_by_type('customerinternaldocument') . $attachment->rel_id);
                if (count($other_attachments) == 0) {
                    // okey only index.html so we can delete the folder also
                    //delete_dir(get_upload_path_by_type('customer') . $attachment->rel_id);
                    delete_dir(get_upload_path_by_type('customerinternaldocument') . $attachment->rel_id);
                }
            }
        }

        return $deleted;
    }

    public function sendReminder($reminderid='', $submit_type=''){
        if($submit_type=='single'){
            //Reminder Customer
            $data = (array) $this->Customerreminder_model->get($reminderid,'tblcustomerreminders.remindernr, tblremindersubjects.name as remindersubject, '

                . 'tblsalutations.name as salutation, '
                . 'responsible.email, '
                . 'responsible.name, '
                . 'responsible.surname, '

                . 'c_salutations.name as c_salutation, '
                . 'tblcustomers.email as c_email, '
                . 'tblcustomers.name as c_name, '
                . 'tblcustomers.surname as c_surname, '

                . 'tblcustomers.customernr, '
                . 'tblcustomers.customernr_prefix, '
                . 'tblcustomers.company, '
                . 'tblcustomerreminders.notice ',
                array('tblremindersubjects'=>'tblremindersubjects.id=tblcustomerreminders.remindersubject',
                'tblcustomers'=>'tblcustomers.customernr=tblcustomerreminders.rel_id',
                'tblusers as responsible'=>'responsible.userid=tblcustomers.responsible',
                'tblsalutations'=>'tblsalutations.salutationid=responsible.salutation',
                'tblsalutations as c_salutations'=>'c_salutations.salutationid=tblcustomers.salutation'
                ),
                "tblcustomerreminders.rel_type='customer'"
            );
        }
        else{
            //Reminder Customer
            /*$data = (array) $this->Customerreminder_model->get($reminderid,'tblcustomerreminders.remindernr, tblremindersubjects.name as remindersubject, '

                . 'tblsalutations.name as salutation, '
                . 'responsible.email, '
                . 'responsible.name, '
                . 'responsible.surname, '

                . 'c_salutations.name as c_salutation, '
                . 'tblcustomers.email as c_email, '
                . 'tblcustomers.name as c_name, '
                . 'tblcustomers.surname as c_surname, '

                . 'tblcustomers.customernr, '
                . 'tblcustomers.customernr_prefix, '
                . 'tblcustomers.company, '
                . 'tblcustomerreminders.notice ',
                array('tblremindersubjects'=>'tblremindersubjects.id=tblcustomerreminders.remindersubject',
                'tblcustomers'=>'tblcustomers.customernr=tblcustomerreminders.rel_id',
                'tblusers as responsible'=>'responsible.userid=tblcustomers.responsible',
                'tblsalutations'=>'tblsalutations.salutationid=responsible.salutation',
                'tblsalutations as c_salutations'=>'c_salutations.salutationid=tblcustomers.salutation'
                ),
                "tblcustomerreminders.rel_type='customer' AND tblcustomerreminders.reminderway=0 AND (tblcustomerreminders.email_sent=0 OR ISNULL(tblcustomerreminders.email_sent) OR tblcustomerreminders.email_sent='') AND DATE_FORMAT(tblcustomerreminders.reminddate,'%Y-%m-%d')='".date("Y-m-d",strtotime("1 day"))."' "
            ); */


            $data = (array) $this->Customerreminder_model->get($reminderid,'tblcustomerreminders.remindernr, tblremindersubjects.name as remindersubject, '

                . 'tblsalutations.name as salutation, '
                . 'responsible.email, '
                . 'responsible.name, '
                . 'responsible.surname, '

                . 'c_salutations.name as c_salutation, '
                . 'tblcustomers.email as c_email, '
                . 'tblcustomers.name as c_name, '
                . 'tblcustomers.surname as c_surname, '

                . 'tblcustomers.customernr, '
                . 'tblcustomers.customernr_prefix, '
                . 'tblcustomers.company, '
                . 'tblcustomerreminders.notice ',
                array('tblremindersubjects'=>'tblremindersubjects.id=tblcustomerreminders.remindersubject',
                'tblcustomers'=>'tblcustomers.customernr=tblcustomerreminders.rel_id',
                'tblusers as responsible'=>'responsible.userid=tblcustomers.responsible',
                'tblsalutations'=>'tblsalutations.salutationid=responsible.salutation',
                'tblsalutations as c_salutations'=>'c_salutations.salutationid=tblcustomers.salutation'
                ),
                "tblcustomerreminders.rel_type='customer' AND tblcustomerreminders.reminderway=0 AND (tblcustomerreminders.email_sent=0 OR ISNULL(tblcustomerreminders.email_sent) OR tblcustomerreminders.email_sent='') AND tblcustomerreminders.reminddate<='".date('Y-m-d H:i:s',strtotime($GLOBALS['current_datetime']."1 day 30 minutes"))."' "
            );
        }

        // print_r($this->db->last_query());
        // echo '<pre>';
        // print_r($data);
        // exit(0);

        if(isset($data['customernr'])){
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
        $data['linktoreminder'] = '<a href="'.base_url('admin/customers/detail/'. $data['customernr']).'" target="_blank">'.lang('click_here').'</a>';

        //Responsible
        $merge_fields = array();
        $merge_fields = array_merge($merge_fields, get_customerreminder_merge_fields($data));
        $sent = $this->Email_model->send_email_template('customer-reminder', $data['email'], $merge_fields);
        // $sent = $this->Email_model->send_email_template('customer-reminder', 'test.usertm1@mailinator.com', $merge_fields);

        //Customer
        $merge_fields = array();
        $merge_fields = array_merge($merge_fields, get_customerreminder_merge_fields2($data));
        $sent = $this->Email_model->send_email_template('customer-reminder', $data['c_email'], $merge_fields);
        // $sent = $this->Email_model->send_email_template('customer-reminder', 'test.usertm2@mailinator.com', $merge_fields);

        if ($sent) {
            // Set to status sent
            $post = array('email_sent'=>1);
            $this->Customerreminder_model->update($post,$data['remindernr']);
            do_action('customerreminder_sent', $data['remindernr']);
            return 1;
        }
        else{
            return 0;
        }
    }
}
