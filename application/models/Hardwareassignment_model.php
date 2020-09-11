<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Hardwareassignment_model extends CI_Model
{
    var $table = 'tblhardwareassignments';
    var $aid = 'hardwareassignmentnr';

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Hardwareassignmentproduct_model');
        $this->load->model('Qualitycheck_model');
        $this->load->model('Assignment_model');
        $this->load->model('Hardwareassignmentreminder_model');
        $this->load->model('Hardware_model');
    }

    /**
     * Check if Hardware assignment
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
            $hardwareassignment = $this->db->get($this->table)->row();
            if ($hardwareassignment) {
                $hardwareassignment->attachments = $this->get_hardwareassignment_attachments($id);
            }
            return $hardwareassignment;
        }

        return $this->db->get($this->table)->result_array();
    }

    /**
     * Add new Hardware assignment
     * @param array $data Hardware assignment $_POST data
     */
    public function add($data)
    {
        //Database data
        $data['created'] = date('Y-m-d H:i:s');
        $this->db->insert($this->table, $data);
        $id = $this->db->insert_id();

        if($id>0){
            //Add ID Prefix
            $dataId = array();
            $dataId['hardwareassignmentnr_prefix'] = idprefix('hardwareassignment',$id);
            $this->db->where($this->aid, $id);
            $this->db->update($this->table, $dataId);

            //Get Hardwareassignmentnr
            $rowfield = $this->get($id,'hardwareassignmentnr_prefix');
            //Log Activity
            logActivity('New Hardware Assignment Added [ID: ' . $id . ', ' . $rowfield->hardwareassignmentnr_prefix . ']');
        }

        return $id;
    }


    /**
     * Update Hardware assignment
     * @param  array $data Hardware assignment
     * @param  mixed $id   Hardware assignment id
     * @return boolean
     */
    public function update($data, $id)
    {
        //Database data
        $data1 = $data;

        unset($data['count_hardwareassignmentproduct']);
        unset($data['hardwareassignmentproductid']);
        unset($data['mobilenr']);
        unset($data['simnr']);
        unset($data['newratemobile']);
        unset($data['hardware']);
        unset($data['stockhardware']);
        unset($data['seriesnr']);
        unset($data['shippingnr']);
        unset($data['hardwarevalue']);

        $this->db->where($this->aid, $id);
        $this->db->update($this->table, $data);

        $updateRow = $this->db->affected_rows();
        $data = $data1;

        //Hardware Assignment Products
        $updateProductRow = 0;
        $updateProductIDs = array();
        if(isset($data['hardwareassignmentproductid'])){
            foreach($data['hardwareassignmentproductid'] as $fk=>$fd){
                $dataproduct = array('stockhardware'=>$data['stockhardware'][$fk],
                    'seriesnr'=>$data['seriesnr'][$fk],
                    'shippingnr'=>$data['shippingnr'][$fk]
                );
                if(isset($data['hardwareassignmentproductid'][$fk]) && $data['hardwareassignmentproductid'][$fk]>0){
                    if(trim($fd)!=""){
                        $hardwareassignmentproductid = $data['hardwareassignmentproductid'][$fk];

                        //$updateProductRow = $updateProductRow + $this->Hardwareassignmentproduct_model->update($dataproduct, $data['hardwareassignmentproductid'][$fk]);

                        $updateProductRow1 = $this->Hardwareassignmentproduct_model->update($dataproduct, $data['hardwareassignmentproductid'][$fk]);
                        if($updateProductRow1>0){
                            $updateProductRow = $updateProductRow + $updateProductRow1;
                            $updateProductIDs[] = $data['hardwareassignmentproductid'][$fk];
                        }

                        /*
                        After I choose StockHardware and saved the Hardwareassignment for all Position
                        where StockHardware was choosen should changed in the Database “Quantity”= 0
                        */
                        if($data['stockhardware'][$fk]!=""){
                            $dataHardwareinput = array(
                                'quantity' => 0 //Red
                            );
                            $this->Hardwareinputproduct_model->update($dataHardwareinput, $data['stockhardware'][$fk]);



                            //Save saveQualitycheck (version 2)
                            /* A Qualitycheck was generated after I open the Hardware-Assignment and choose Hardware to "Hardware-Bestand" and saved.
                            But there is a small logical issue. A Qualitycheck should be generated only when in one Position "Sendungsnr" was entered.
                            And in this Qualitycheck should only be listed the Positions where "Sendungsnr" entered.
                            When I open a Hardware-Assignment and as example there are three Positions. And we choose first only for one
                            postion "Hardware-Bestand" and enter "Sendungsnr" and saved so generate a Qualitycheck only with this Hardware Listed.
                            Please input in the Qualitycheck the Hardwareinfo, Sendungsnr.
                            So when I open the Hardware-Assignment again and choose for the other Position "Hardware-Bestand" and enter "Sendungsnr" and save
                            so generate a Qualitycheck again now only for this Hardwares. */



                            //Get Assignmentnr
                            /*$hardwareassignmentnr = $id;
                            $rowHardwareAssignment = (array) $this->get($hardwareassignmentnr,"assignmentnr");
                            $assignmentnr = $rowHardwareAssignment['assignmentnr'];


                            //Get Detail of Assignment
                            $rowAssignment = (array) $this->Assignment_model->get($assignmentnr);
                            $dataAss = $rowAssignment;


                            //Date of generating Assignmentdate+5 Days
                            $qualitycheckstart = date('Y-m-d', strtotime('+5 days', strtotime($dataAss['assignmentdate'])));
                            if(isset($assignmentnr) && $assignmentnr>0){

                                //Get Hardware Title
                                $rowHardware = (array) $this->Hardwareinputproduct_model->get($data['stockhardware'][$fk],"tblhardwares.hardwaretitle",array(
                                    'tblhardwares'=>'tblhardwares.hardwarenr=tblhardwareinputproducts.hardware'
                                ));
                                $qualitycheckdesc = isset($rowHardware['hardwaretitle'])?'<b>'.lang('page_hardware').':</b> '.$rowHardware['hardwaretitle']:'';
                                $qualitycheckdesc.= isset($data['seriesnr'][$fk])?', <br /><b>'.lang('page_fl_seriesnr').':</b> '.$data['seriesnr'][$fk]:'';
                                $qualitycheckdesc.= isset($data['shippingnr'][$fk])?', <br /><b>'.lang('page_fl_shippingnr').':</b> '.$data['shippingnr'][$fk]:'';

                                //Quality Check
                                $em = $this->Qualitycheck_model->get('','qualitychecknr',array()," rel_id='".$hardwareassignmentnr."' AND rel_type='hardwareassignment' AND stockhardware='".$data['stockhardware'][$fk]."' AND seriesnr='".$data['seriesnr'][$fk]."' AND shippingnr='".$data['shippingnr'][$fk]."' ");
                                if(isset($em) && count($em)>0){
                                }else{
                                    $data_qualitycheck = array(
                                        'qualityissue' => 'Hardwarecheck',
                                        'rel_id' => $hardwareassignmentnr,
                                        'rel_type' => 'hardwareassignment',
                                        'qualitycheckstart' => $qualitycheckstart,
                                        'company' => $dataAss['company'],
                                        'responsible' => $dataAss['responsible'],
                                        'qualitycheckstatus' => 1, //Often
                                        'question1' => 3,
                                        'qualitycheckdesc' => $qualitycheckdesc,
                                        'stockhardware' => $data['stockhardware'][$fk],
                                        'seriesnr' => $data['seriesnr'][$fk],
                                        'shippingnr' => $data['shippingnr'][$fk]
                                    );
                                    $this->Qualitycheck_model->add($data_qualitycheck,'hardwarequalitycheck');
                                }
                            }*/


                        }
                    }
                }
            }
        }

        //When we edit the Assignment and changed and fillout more field and save it again Qualitycheck generate again too. (version 1)
        if($updateProductRow > 0){
            $this->saveQualitycheck($id, $updateProductIDs);
        }

        if ($updateRow > 0) {
            $this->db->query("UPDATE ".$this->table." SET `updated`='".date('Y-m-d H:i:s')."' WHERE ".$this->aid."='".$id."' ");
            //$this->db->where($this->aid, $id);
            //$this->db->update($this->table, $data);
            //Get Hardwareassignmentnr
            $rowfield = $this->get($id,'hardwareassignmentnr_prefix');
            //Log Activity
            logActivity('Hardware Assignment Updated [ID: ' . $id . ', ' . $rowfield->hardwareassignmentnr_prefix . ']');
        }

        return $id;
    }

    function saveQualitycheck($hardwareassignmentnr, $updateProductIDs=array()){

        //After finish a Hardware-Assignment a Qualitycheck will be gerate. In the Qualitycheck somewhere should be listed which
        //Hardwares are in the Hardware-Assignment.
        /*$hardwares = (array) $this->Hardwareassignmentproduct_model->get("","GROUP_CONCAT(tblhardwares.hardwaretitle SEPARATOR ', ') as hardwaretitles",
            array('tblhardwares'=>'tblhardwares.hardwarenr=tblhardwareassignmentproducts.hardware'),
            "hardwareassignmentnr='".$hardwareassignmentnr."'"
        );
        $qualitycheckdesc = isset($hardwares[0]['hardwaretitles'])?'Hardware: '.$hardwares[0]['hardwaretitles']:'';*/

        if(count($updateProductIDs)<=0){
            return;
        }

        $qualitycheckdesc = '';
        $HardwareInfos = array();
        foreach($updateProductIDs as $updateProductID){
            $hardwares = (array) $this->Hardwareassignmentproduct_model->get($updateProductID,"tblhardwares.hardwaretitle, tblhardwareassignmentproducts.seriesnr, tblhardwareassignmentproducts.shippingnr",
                    array('tblhardwares'=>'tblhardwares.hardwarenr=tblhardwareassignmentproducts.hardware'),
                    " tblhardwareassignmentproducts.stockhardware!='' AND tblhardwareassignmentproducts.shippingnr!='' "
            );
            if($hardwares){
                $HardwareInfos[] = array(lang('page_hardware')=>$hardwares['hardwaretitle'], lang('page_fl_seriesnr')=>$hardwares['seriesnr'], lang('page_fl_shippingnr')=>$hardwares['shippingnr']);
            }
        }

         if(count($HardwareInfos)>0){
            $qualitycheckdesc2  = json_encode($HardwareInfos);


            //Get Assignmentnr
            $rowHardwareAssignment = (array) $this->get($hardwareassignmentnr,"assignmentnr");
            $assignmentnr = $rowHardwareAssignment['assignmentnr'];

            //Get Detail of Assignment
            $rowAssignment = (array) $this->Assignment_model->get($assignmentnr);
            $data = $rowAssignment;

            //Date of generating Assignmentdate+5 Days
            $qualitycheckstart = date('Y-m-d', strtotime('+5 days', strtotime($data['assignmentdate'])));
            if(isset($assignmentnr) && $assignmentnr>0){
                //Quality Check
                $em = $this->Qualitycheck_model->get('','qualitychecknr',array()," rel_id='".$hardwareassignmentnr."' ");
                if(isset($em) && count($em)>0){
                    /*$qualitychecknr = $em[0]['qualitychecknr'];
                    $data_qualitycheck = array(
                        'assignmentnr' => $assignmentnr,
                        'qualitycheckstart' => $qualitycheckstart,
                        'company' => $data['company'],
                        'responsible' => $data['responsible'],
                        'qualitycheckstatus' => 1, //Often
                    );
                    //$this->Qualitycheck_model->update($data_qualitycheck, $qualitychecknr);*/
                    $data_qualitycheck = array(
                        'qualityissue' => 'Hardwarecheck',
                        'rel_id' => $hardwareassignmentnr,
                        'rel_type' => 'hardwareassignment',
                        'qualitycheckstart' => $qualitycheckstart,
                        'company' => $data['company'],
                        'responsible' => $data['responsible'],
                        'qualitycheckstatus' => 1, //Often
                        'question1' => 3,
                        'qualitycheckdesc' => $qualitycheckdesc,
                        'qualitycheckdesc2' => $qualitycheckdesc2
                    );
                    $qualitychecknr = $this->Qualitycheck_model->add($data_qualitycheck,'hardwarequalitycheck');
                }
                else{
                    $data_qualitycheck = array(
                        'qualityissue' => 'Hardwarecheck',
                        'rel_id' => $hardwareassignmentnr,
                        'rel_type' => 'hardwareassignment',
                        'qualitycheckstart' => $qualitycheckstart,
                        'company' => $data['company'],
                        'responsible' => $data['responsible'],
                        'qualitycheckstatus' => 1, //Often
                        'question1' => 3,
                        'qualitycheckdesc' => $qualitycheckdesc,
                        'qualitycheckdesc2' => $qualitycheckdesc2
                    );
                    $qualitychecknr = $this->Qualitycheck_model->add($data_qualitycheck,'hardwarequalitycheck');
                }
            }
        }
    }

    /**
     * Delete hardwareassignment
     * @param  array $data hardwareassignment
     * @param  mixed $id   hardwareassignment id
     * @return boolean
     */
    public function delete($id)
    {
        //Get Hardwareassignmentnr
        $rowfield = $this->get($id,'hardwareassignmentnr_prefix');
        $this->db->where($this->aid, $id);
        $this->db->delete($this->table);

        //Log Activity
        logActivity('Hardware Assignment Deleted [ID: ' . $id . ', ' . $rowfield->hardwareassignmentnr_prefix . ']');

        //Delete Hardwareassignment Products
        $hardwareassignmentproducts = $this->Hardwareassignmentproduct_model->get('', 'id', array(), " hardwareassignmentnr='".$id."' ");
        if(isset($hardwareassignmentproducts) && count($hardwareassignmentproducts)>0){
            foreach($hardwareassignmentproducts as $hardwareassignmentproduct){
               $this->Hardwareassignmentproduct_model->delete($hardwareassignmentproduct['id']);
            }
        }

        //Delete Document
        $documents = $this->get_hardwareassignment_attachments($id);
        if(isset($documents) && count($documents)>0){
            foreach($documents as $document){
                $this->delete_hardwareassignment_attachment($document['id']);
            }
        }

        //Delete Reminder
        $reminders = $this->Hardwareassignmentreminder_model->get('', '', array(), " rel_id='".$id."' AND rel_type='hardwareassignment' ");
        if(isset($reminders) && count($reminders)>0){
            foreach($reminders as $reminder){
               $this->Hardwareassignmentreminder_model->delete($reminder['remindernr']);
            }
        }

        return 1;
    }

    /**
     * Get hardwareassignment attachments
     * @since Version 1.0.4
     * @param  mixed $id hardwareassignment id
     * @return array
     */
    public function get_hardwareassignment_attachments($id = '', $attachment_id = '')
    {
        if (is_numeric($attachment_id)) {
            $this->db->where('id', $attachment_id);

            return $this->db->get('tblfiles')->row();
        }
        $this->db->where('rel_id', $id);
        $this->db->where('rel_type', 'hardwareassignment');
        $this->db->order_by('created', 'DESC');

        $this->db->join('tbldocumentsettings as category', 'category.categoryid=tblfiles.categoryid', 'left');

        return $this->db->get('tblfiles')->result_array();
    }

    //Add Attachment
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
        $data['rel_type'] = 'hardwareassignment';
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
            $hardwareassignment = $this->get($rel_id,'hardwareassignmentnr_prefix');
            logActivity('Hardware Assignment Attachment Added [Hardware AssignmentID: ' . $rel_id . ', '.$hardwareassignment->hardwareassignmentnr_prefix.']');

            //History
            $Action_data = array('actionname'=>'hardwareassignment', 'actionid'=>$rel_id, 'actionsubid'=>$insert_id, 'actiontitle'=>'hardwareassignment_document_added');
            do_action_history($Action_data);
        }

        return $insert_id;
    }

    /**
     * Delete hardwareassignment attachment
     * @param  mixed $id attachment id
     * @return boolean
     */
    public function delete_hardwareassignment_attachment($id)
    {
        $attachment = $this->get_hardwareassignment_attachments('', $id);
        $hardwareassignment = $this->get($attachment->rel_id,'hardwareassignmentnr_prefix');
        $deleted    = false;

        if ($attachment) {
            if (empty($attachment->external)) {
                unlink(get_upload_path_by_type('hardwareassignment') . $attachment->rel_id . '/' . $attachment->file_name);
            }
            $this->db->where('id', $attachment->id);
            $this->db->delete('tblfiles');
            if ($this->db->affected_rows() > 0) {
                $deleted = true;
                logActivity('Hardware Assignment Attachment Deleted [Hardware AssignmentID: ' . $attachment->rel_id . ', '.$hardwareassignment->hardwareassignmentnr_prefix.']');

                //History
                $Action_data = array('actionname'=>'hardwareassignment', 'actionid'=>$attachment->rel_id, 'actionsubid'=>$attachment->id, 'actiontitle'=>'hardwareassignment_document_deleted');
                do_action_history($Action_data);
            }

            if (is_dir(get_upload_path_by_type('hardwareassignment') . $attachment->rel_id)) {
                // Check if no attachments left, so we can delete the folder also
                $other_attachments = list_files(get_upload_path_by_type('hardwareassignment') . $attachment->rel_id);
                if (count($other_attachments) == 0) {
                    // okey only index.html so we can delete the folder also
                    delete_dir(get_upload_path_by_type('hardwareassignment') . $attachment->rel_id);
                }
            }
        }

        return $deleted;
    }

    /* Update attachment category */
    public function update_hardwareassignment_attachmentcategory($rel_id, $categoryid){
        $data = array('categoryid'=>$categoryid);
        $this->db->update('tblfiles', $data, array('rel_id' => $rel_id, 'rel_type'=>'hardwareassignment'));
        if ($this->db->affected_rows() > 0) {
            $hardwareassignment = $this->get($rel_id,'hardwareassignmentnr_prefix');
            logActivity('Hardware Assignment Attachment Updated [Hardware AssignmentID: ' . $rel_id . ', '.$hardwareassignment->hardwareassignmentnr_prefix.']');
            return 1;
        }
    }


    //Generate Reminder by Cronjob (Notification Email 1 Day before he set the date)
    public function sendReminder($reminderid='', $submit_type=''){
        if($submit_type=='single'){
            //Reminder Hardwareassignment
            $data = (array) $this->Hardwareassignmentreminder_model->get($reminderid,'tblhardwareassignmentreminders.remindernr, tblremindersubjects.name as remindersubject, '
                . 'tblsalutations.name as salutation, '
                . 'responsible.email, '
                . 'responsible.name, '
                . 'responsible.surname, '
                . 'tblhardwareassignments.hardwareassignmentnr, '
                . 'tblhardwareassignments.hardwareassignmentnr_prefix, '
                . 'tblhardwareassignments.company, '
                . 'tblhardwareassignmentreminders.notice,'
                . 'tblhardwareassignmentreminders.reminddate ',
                array('tblremindersubjects'=>'tblremindersubjects.id=tblhardwareassignmentreminders.remindersubject',
                'tblhardwareassignments'=>'tblhardwareassignments.hardwareassignmentnr=tblhardwareassignmentreminders.rel_id',
                'tblusers as responsible'=>'responsible.userid=tblhardwareassignments.responsible',
                'tblsalutations'=>'tblsalutations.salutationid=responsible.salutation'
                ),
                "tblhardwareassignmentreminders.rel_type='hardwareassignment'"
            );
        }
        else{
            //Reminder Hardwareassignment
            $data = (array) $this->Hardwareassignmentreminder_model->get($reminderid,'tblhardwareassignmentreminders.remindernr, tblremindersubjects.name as remindersubject, '
                . 'tblsalutations.name as salutation, '
                . 'responsible.email, '
                . 'responsible.name, '
                . 'responsible.surname, '
                . 'tblhardwareassignments.hardwareassignmentnr, '
                . 'tblhardwareassignments.hardwareassignmentnr_prefix, '
                . 'tblhardwareassignments.company, '
                . 'tblhardwareassignmentreminders.notice,'
                . 'tblhardwareassignmentreminders.reminddate '

                . " , (SELECT IF( (tblcustomers.company IS NULL) , '(NO COMPANY)', tblcustomers.company ) ) AS company ",

                array('tblremindersubjects'=>'tblremindersubjects.id=tblhardwareassignmentreminders.remindersubject',
                'tblhardwareassignments'=>'tblhardwareassignments.hardwareassignmentnr=tblhardwareassignmentreminders.rel_id',
                'tblusers as responsible'=>'responsible.userid=tblhardwareassignments.responsible',
                'tblsalutations'=>'tblsalutations.salutationid=responsible.salutation',

                    'tblcustomers'=>'tblcustomers.customernr=tblhardwareassignments.customer',

                ),
                "tblhardwareassignmentreminders.rel_type='hardwareassignment' AND tblhardwareassignmentreminders.reminderway=0 AND (tblhardwareassignmentreminders.email_sent=0 OR ISNULL(tblhardwareassignmentreminders.email_sent) OR tblhardwareassignmentreminders.email_sent='') AND tblhardwareassignmentreminders.reminddate<='".date('Y-m-d H:i:s',strtotime($GLOBALS['current_datetime']."1 day 30 minutes"))."' "
            );
        }


        // print_r($this->db->last_query());
        // echo '<pre>';
        // print_r($data);
        // exit(0);


        if(isset($data['hardwareassignmentnr'])){
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
        $data['linktoreminder'] = '<a href="'.base_url('admin/hardwareassignments/detail/'. $data['hardwareassignmentnr']).'" target="_blank">'.lang('click_here').'</a>';
        $merge_fields = array();
        $merge_fields = array_merge($merge_fields, get_hardwareassignmentreminder_merge_fields($data));

        $sent = $this->Email_model->send_email_template('hardwareassignment-reminder', $data['email'], $merge_fields);
        if ($sent) {
            // Set to status sent
            $post = array('email_sent'=>1);
            $this->Hardwareassignmentreminder_model->update($post,$data['remindernr']);
            do_action('hardwareassignmentreminder_sent', $data['remindernr']);
            return 1;
        }
        else{
            return 0;
        }
    }
}
