<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Lead_model extends CI_Model
{
    var $table = 'tblleads';
    var $aid = 'leadnr';

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Leadprovidercompany_model');
        $this->load->model('Reminder_model');
        $this->load->model('Email_model');
        $this->load->model('Note_model');

        $this->load->model('User_model');
        $this->load->model('Leadstatus_model');
        $this->load->model('Leadsource_model');
        $this->load->model('Companysize_model');
        $this->load->model('Salutation_model');
        $this->load->model('Leadproduct_model');
        $this->load->model('Quotation_model');
    }

    /**
     * Check if lead
     * @param  mixed $leadnr
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
            $lead = $this->db->get($this->table)->row();
            if ($lead) {
                $lead->attachments = $this->get_lead_attachments($id);
            }
            return $lead;
        }

        return $this->db->get($this->table)->result_array();
    }

    /**
     * Add new lead
     * @param array $data lead $_POST data
     */
    public function add($data, $logMessage='')
    {
        if(empty($logMessage)){ $logMessage='Added'; }

        //Check Email
        $this->db->where('email', trim($data['email']));
        $email = $this->db->get($this->table)->row();
        if ($email) {
            return lang('page_form_validation_email_already_exists');
        }

        //Database data
        $data['created'] = date('Y-m-d H:i:s');
        $data['userid'] = get_user_id();
        $data['teamwork'] = isset($data['teamwork'])?implode(",",$data['teamwork']):'';
        $data1 = $data;
        unset($data['providernr']);
        unset($data['count_leadprovidercompany']);
        $this->db->insert($this->table, $data);
        $id = $this->db->insert_id();
        $data = $data1;

        if($id>0){
            //Company Provider Numbers
            foreach($data['providernr'] as $fk=>$fd){
                if(isset($data['leadprovidercompanyid'][$fk]) && $data['leadprovidercompanyid'][$fk]>0){
                    if(trim($fd)!=""){
                        $dataprovider = array('providernr'=>$fd);
                        $this->Leadprovidercompany_model->update($dataprovider, $data['leadprovidercompanyid'][$fk]);
                    }else{
                        $this->Leadprovidercompany_model->delete($data['leadprovidercompanyid'][$fk]);
                    }
                }else{
                    if(trim($fd)!=""){
                        $dataprovider = array('leadnr'=>$id,'providernr'=>$fd);
                        $this->Leadprovidercompany_model->add($dataprovider);
                    }
                }
            }

            if($logMessage=='Added'){
                //Add ID Prefix
                $dataId = array();
                $dataId['leadnr_prefix'] = idprefix('lead',$id);
                $this->db->where($this->aid, $id);
                $this->db->update($this->table, $dataId);
            }

            //Get Leadnr
            $rowfield = $this->get($id,'leadnr_prefix');
            //Log Activity
            logActivity('New Lead '.$logMessage.' [ID: ' . $id . ', ' . $rowfield->leadnr_prefix . ']');

            //Generate Quotation
            //$this->generateQuotation($id);
        }

        return $id;
    }

    /*
    It must be able to make a quotation for a Lead! This is very important. After I add a Lead
    i must be able to generate a quotation under Menu "Quotation". That means when I click on
    "+Angebot anlegen" i must be able to choose Customer or Lead.
    And when I generate a Customer later from Lead the quotation should belongs to the
    customer.
    */

    public function generateQuotation($id){
        $rowfield = (array) $this->get($id,'leadnr,recommend');

        $data['leadnr'] = $rowfield['leadnr'];
        $data['recommend'] = $rowfield['recommend'];
        $data['quotationdate'] = date('d.m.Y');
        $data['quotationstatus'] = 1;

        $rowleadprodernr =  $this->Leadprovidercompany_model->get('','providernr',"leadnr='".$id."'");
        $data['providercompanynr'] = $rowleadprodernr[0]['providernr'];

        $this->Quotation_model->add($data);
    }

    public function updateCustomerQuotation($id,$custid,$responsible){
        $data['customer'] = $custid;
        $data['responsible'] = $responsible;
        $this->db->where('leadnr', $id);
        $this->db->update('tblquotations', $data);
    }


    /**
     * Update lead
     * @param  array $data lead
     * @param  mixed $id   lead id
     * @return boolean
     */
    public function update($data, $id)
    {
        //Check Email
        if(isset($data['email'])){
            $this->db->where($this->aid.'!=', $id);
            $this->db->where('email', trim($data['email']));
            $email = $this->db->get($this->table)->row();
            if ($email) {
                return lang('page_form_validation_email_already_exists');
            }
        }

        //Database data
        $data['updated'] = date('Y-m-d H:i:s');
        if(isset($data['teamwork'])){
            $data['teamwork'] = isset($data['teamwork'])?implode(",",$data['teamwork']):'';
        }
        $data1 = $data;
        unset($data['leadprovidercompanyid']);
        unset($data['providernr']);
        unset($data['count_leadprovidercompany']);
        $this->db->where($this->aid, $id);
        $this->db->update($this->table, $data);
        $data = $data1;

        //Company Provider Numbers
        if(isset($data['providernr'])){
            foreach($data['providernr'] as $fk=>$fd){
                if(isset($data['leadprovidercompanyid'][$fk]) && $data['leadprovidercompanyid'][$fk]>0){
                    if(trim($fd)!=""){
                        $dataprovider = array('providernr'=>$fd);
                        $this->Leadprovidercompany_model->update($dataprovider, $data['leadprovidercompanyid'][$fk]);
                    }
                    else{
                        $this->Leadprovidercompany_model->delete($data['leadprovidercompanyid'][$fk]);
                    }
                }else{
                    if(trim($fd)!=""){
                        $dataprovider = array('leadnr'=>$id,'providernr'=>$fd);
                        $this->Leadprovidercompany_model->add($dataprovider);
                    }
                }
            }
        }

        if ($this->db->affected_rows() > 0) {

            //Get Leadnr
            $rowfield = $this->get($id,'leadnr_prefix');
            //Log Activity
            logActivity('Lead Updated [ID: ' . $id . ', ' . $rowfield->leadnr_prefix . ']');
        }

        return $id;
    }

    /**
     * Delete lead
     * @param  array $data lead
     * @param  mixed $id   lead id
     * @return boolean
     */
    public function delete($id)
    {
        //Get Leadnr
        $rowfield = $this->get($id,'leadnr_prefix');
        $this->db->where($this->aid, $id);
        $this->db->delete($this->table);

        //Log Activity
        logActivity('Lead Deleted [ID: ' . $id . ', ' . $rowfield->leadnr_prefix . ']');

        //Delete Provider Company
        $providers = $this->Leadprovidercompany_model->get('', 'id', " leadnr='".$id."' ");
        if(isset($providers) && count($providers)>0){
            foreach($providers as $provider){
               $this->Leadprovidercompany_model->delete($provider['id']);
            }
        }

        //Delete Comments
        $comments = $this->Note_model->get('', 'id', array(), " rel_id='".$id."' AND rel_type='lead' ");
        if(isset($comments) && count($comments)>0){
            foreach($comments as $comment){
               $this->Note_model->delete($comment['id']);
            }
        }

        //Delete Document
        $documents = $this->get_lead_attachments($id);
        if(isset($documents) && count($documents)>0){
            foreach($documents as $document){
                $this->delete_lead_attachment($document['id']);
            }
        }

        //Delete Reminder
        $reminders = $this->Reminder_model->get('', 'remindernr', array(), " rel_id='".$id."' AND rel_type='lead' ");
        if(isset($reminders) && count($reminders)>0){
            foreach($reminders as $reminder){
               $this->Reminder_model->delete($reminder['remindernr']);
            }
        }

        return 1;
    }

    /**
     * Get lead attachments
     * @since Version 1.0.4
     * @param  mixed $id lead id
     * @return array
     */
    public function get_lead_attachments($id = '', $attachment_id = '')
    {
        if (is_numeric($attachment_id)) {
            $this->db->where('id', $attachment_id);

            return $this->db->get('tblfiles')->row();
        }
        $this->db->where('rel_id', $id);
        $this->db->where('rel_type', 'lead');
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
        $data['rel_type'] = 'lead';
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
            $lead = $this->get($rel_id,'leadnr_prefix');
            logActivity('Lead Attachment Added [LeadID: ' . $rel_id . ', '.$lead->leadnr_prefix.']');

            //History
            $Action_data = array('actionname'=>'lead', 'actionid'=>$rel_id, 'actionsubid'=>$insert_id, 'actiontitle'=>'lead_document_added');
            do_action_history($Action_data);
        }

        return $insert_id;
    }

    /**
     * Delete lead attachment
     * @param  mixed $id attachment id
     * @return boolean
     */
    public function delete_lead_attachment($id)
    {
        $attachment = $this->get_lead_attachments('', $id);
        $lead = $this->get($attachment->rel_id,'leadnr_prefix');
        $deleted    = false;

        if ($attachment) {
            if (empty($attachment->external)) {
                unlink(get_upload_path_by_type('lead') . $attachment->rel_id . '/' . $attachment->file_name);
            }
            $this->db->where('id', $attachment->id);
            $this->db->delete('tblfiles');
            if ($this->db->affected_rows() > 0) {
                $deleted = true;
                logActivity('Lead Attachment Deleted [LeadID: ' . $attachment->rel_id . ', '.$lead->leadnr_prefix.']');

                //History
                $Action_data = array('actionname'=>'lead', 'actionid'=>$attachment->rel_id, 'actionsubid'=>$attachment->id, 'actiontitle'=>'lead_document_deleted');
                do_action_history($Action_data);
            }

            if (is_dir(get_upload_path_by_type('lead') . $attachment->rel_id)) {
                // Check if no attachments left, so we can delete the folder also
                $other_attachments = list_files(get_upload_path_by_type('lead') . $attachment->rel_id);
                if (count($other_attachments) == 0) {
                    // okey only index.html so we can delete the folder also
                    delete_dir(get_upload_path_by_type('lead') . $attachment->rel_id);
                }
            }
        }

        return $deleted;
    }

    /* Update attachment category */
    public function update_lead_attachmentcategory($rel_id, $categoryid){
        $data = array('categoryid'=>$categoryid);
        $this->db->update('tblfiles', $data, array('rel_id' => $rel_id, 'rel_type'=>'lead'));
        if ($this->db->affected_rows() > 0) {
            $lead = $this->get($rel_id,'leadnr_prefix');
            logActivity('Lead Attachment Updated [LeadID: ' . $rel_id . ', '.$lead->leadnr_prefix.']');
            return 1;
        }
    }

    public function sendReminder($reminderid='', $submit_type=''){

        /*$to = "pramodranpariya@gmail.com";
        $subject = "My subject";
        $txt = "Hello world!";
        $headers = "From: support@girirajjewellers.co.in";
        mail($to,$subject,$txt,$headers);
        exit;*/

        /*$this->email->clear(true);
        $this->email->from('support@girirajjewellers.co.in');
        $this->email->subject('Testing');
        $this->email->message('Hello World!');
        $this->email->to('pramodranpariya@gmail.com');
        $this->email->send();
        exit;*/

        if($submit_type=='single'){
            //Reminder Lead
            $data = (array) $this->Reminder_model->get($reminderid,'tblreminders.remindernr, tblremindersubjects.name as remindersubject, '
                . 'tblsalutations.name as salutation, '
                . 'responsible.email, '
                . 'responsible.name, '
                . 'responsible.surname, '
                . 'tblleads.leadnr, '
                . 'tblleads.leadnr_prefix, '
                . 'tblleads.company, '
                . 'tblreminders.notice ',
                array('tblremindersubjects'=>'tblremindersubjects.id=tblreminders.remindersubject',
                'tblleads'=>'tblleads.leadnr=tblreminders.rel_id',
                'tblusers as responsible'=>'responsible.userid=tblleads.responsible',
                'tblsalutations'=>'tblsalutations.salutationid=responsible.salutation'
                ),
                "tblreminders.rel_type='lead'"
            );
        }
        else{
            //Reminder Lead
            $data = (array) $this->Reminder_model->get($reminderid,'tblreminders.remindernr, tblremindersubjects.name as remindersubject, '
                . 'tblsalutations.name as salutation, '
                . 'responsible.email, '
                . 'responsible.name, '
                . 'responsible.surname, '
                . 'tblleads.leadnr, '
                . 'tblleads.leadnr_prefix, '
                . 'tblleads.company, '
                . 'tblreminders.notice ',
                array('tblremindersubjects'=>'tblremindersubjects.id=tblreminders.remindersubject',
                'tblleads'=>'tblleads.leadnr=tblreminders.rel_id',
                'tblusers as responsible'=>'responsible.userid=tblleads.responsible',
                'tblsalutations'=>'tblsalutations.salutationid=responsible.salutation'
                ),
                "tblreminders.rel_type='lead' AND tblreminders.reminderway=0 AND (tblreminders.email_sent=0 OR ISNULL(tblreminders.email_sent) OR tblreminders.email_sent='') AND tblreminders.reminddate<='".date('Y-m-d H:i:s',strtotime($GLOBALS['current_datetime']."1 day 30 minutes"))."' "
            );
        }

        // print_r($this->db->last_query());
        // echo '<pre>';
        // print_r($data);
        // exit(0);

        if(isset($data['leadnr'])){
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
        $data['linktoreminder'] = '<a href="'.base_url('admin/leads/detail/'. $data['leadnr']).'" target="_blank">'.lang('click_here').'</a>';
        $merge_fields = array();
        $merge_fields = array_merge($merge_fields, get_leadreminder_merge_fields($data));

        $sent = $this->Email_model->send_email_template('lead-reminder', $data['email'], $merge_fields);
        if ($sent) {
            // Set to status sent
            $post = array('email_sent'=>1);
            $this->Reminder_model->update($post,$data['remindernr']);
            do_action('leadreminder_sent', $data['remindernr']);
            return 1;
        }
        else{
            return 0;
        }
    }

    /* Import CSV
     */
    public function importcsv($data){
        $csvMimes = array('application/vnd.ms-excel','text/plain','text/csv','text/tsv');
        if(!empty($_FILES['file_csv']['name']) && in_array($_FILES['file_csv']['type'],$csvMimes)){

            if(is_uploaded_file($_FILES['file_csv']['tmp_name'])){

                //open uploaded csv file with read only mode
	        $csvFile = fopen($_FILES['file_csv']['tmp_name'], 'r');

                // skip first line
                // if your csv file have no heading, just comment the next line
                fgetcsv($csvFile);

                //parse data from csv file line by line
                $imported_records = 0;
                while(($line = fgetcsv($csvFile)) !== FALSE){

                    //check whether member already exists in database with same leadnr
                    $this->db->where("leadnr_prefix",trim($line[0]));
                    $this->db->or_where("email",trim($line[12]));
                    $result = $this->db->get($this->table)->result();

                    //Duplicate rows wont be imported
                    if(!count($result)){
                        $post = array();
                        $fkey = 0;
                        foreach($data['db_fields'] as $field){
                            if(in_array($field,$data['not_importable'])){continue;}

                            //Get Id from Values
                            switch($field){
                                case 'leadnr':
                                    $field = 'leadnr_prefix';
                                break;
                                case 'responsible':
                                    $line[$fkey] = $this->User_model->get('','userid'," username='".trim($line[$fkey])."' ")[0]['userid'];
                                break;

                                case 'recommend':
                                    $line[$fkey] = $this->User_model->get('','userid'," username='".trim($line[$fkey])."' ")[0]['userid'];
                                break;

                                case 'leadstatus':
                                    $line[$fkey] = $this->Leadstatus_model->get('','id'," name='".trim($line[$fkey])."' ")[0]['id'];
                                break;

                                case 'leadsource':
                                    $line[$fkey] = $this->Leadsource_model->get('','id'," name='".trim($line[$fkey])."' ")[0]['id'];
                                break;

                                case 'companysize':
                                    $line[$fkey] = $this->Companysize_model->get('','id'," name='".trim($line[$fkey])."' ")[0]['id'];
                                break;

                                case 'salutation':
                                    $line[$fkey] = $this->Salutation_model->get('','salutationid'," name='".trim($line[$fkey])."' ")[0]['salutationid'];
                                break;

                                case 'product':
                                    $line[$fkey] = $this->Leadproduct_model->get('','id'," name='".trim($line[$fkey])."' ")[0]['id'];
                                break;
                            }

                            $post = array_merge($post,array($field=>$line[$fkey]));
                            $fkey++;
                        }

                        //print_r($post);exit;

                        $insertid = $this->add($post,'Imported');
                        if (is_numeric($insertid) && $insertid>0) {
                            $temp = explode("-",$post['leadnr_prefix']);
                            $newid = end($temp);

                            $post['leadnr_prefix'] = str_replace('-'.$newid,'-'.$insertid,$post['leadnr_prefix']);
                            $this->update(array('leadnr_prefix'=>$post['leadnr_prefix']),$insertid);

                            //@$this->update(array('leadnr'=>$newid),$insertid);
                            $imported_records++;
                        }
                    }
                }

                //close opened csv file
                fclose($csvFile);

                if($imported_records>0){
                    return array('status'=>1,'message'=>sprintf(lang('import_total_imported'),$imported_records));
                }
                else{
                    return array('status'=>0,'message'=>sprintf(lang('import_total_imported'),$imported_records));
                }

            }else{
                return array('status'=>0,'message'=>lang('import_upload_failed'));
            }
        }else{
            return array('status'=>0,'message'=>lang('import_upload_failed'));
        }
    }

    public function get_lead_responsible() {
        $this->db->select('tblleads.*, tblusers.userid, CONCAT(tblusers.name," ",tblusers.surname) as responsible_name');
        $this->db->from('tblleads');
        $this->db->join('tblusers', 'tblusers.userid=tblleads.responsible', 'left');
        $this->db->order_by('leadnr_prefix', 'DESC');
        return $this->db->get()->result_array();
    }
}
