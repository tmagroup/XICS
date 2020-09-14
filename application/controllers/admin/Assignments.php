<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Assignments extends Admin_controller
{
    public function __construct()
    {
        parent::__construct();
        /*if(get_user_role()!='user'){
            redirect(site_url('admin/permission/denied/'));
        }*/
        $this->load->model('Assignment_model');
        $this->load->model('Assignmentproduct_model');
        $this->load->model('Assignmentproductmoreoptionmobile_model');
        $this->load->model('Hardwareassignmentproduct_model');
        $this->load->model('User_model');
        $this->load->model('Customer_model');
        $this->load->model('Assignmentstatus_model');
        $this->load->model('Documentsetting_model');
        $this->load->model('Remindersubject_model');
        $this->load->model('File_model');
        $this->load->model('Assignmentreminder_model');
        $this->load->model('Discountlevel_model');
        $this->load->model('Vvlneu_model');
        $this->load->model('Ratemobile_model');
        $this->load->model('Hardware_model');
        $this->load->model('Optionmobile_model');
        $this->load->model('Pdf_model');
        $this->load->model('Field_model');
        $this->load->model('Assignmentbill_model');
        $this->load->model('Hardwarecategory_model');
    }

    /* List all assignments */
    public function index()
    {
        if(!$GLOBALS['assignment_permission']['view'] && !$GLOBALS['assignment_permission']['view_own']){
            access_denied('assignment');
        }

        //******************** Initialise ********************/
        //Responsibles (Users of Customer)
        $data['filter_responsible'] = $this->Customer_model->get('',"tblusers.userid, CONCAT(tblusers.name,' ',tblusers.surname) as name",
            array('tblusers'=>'tblusers.userid=tblcustomers.responsible')
        );
        $data['filter_responsible'] = dropdown($data['filter_responsible'],'userid','name');

        //Assignmentstatus
        $data['filter_assignmentstatus'] = $this->Assignmentstatus_model->get();
        $data['filter_assignmentstatus'] = dropdown($data['filter_assignmentstatus'],'id','name');
        //******************** End Initialise ********************/

        $data['title'] = lang('page_assignments');
        $this->load->view('admin/assignments/manage', $data);
    }

    /* List all assignments by ajax */
    public function ajax($filter_responsible='',$filter_assignmentstatus='')
    {
        //Filter By responsible, assignmentstatus
        $params = array('filter_responsible'=>$filter_responsible,'filter_assignmentstatus'=>$filter_assignmentstatus);
        $this->app->get_table_data('assignments',$params);
    }

    /* Change Status */
    public function change_hardwareassignment_insurance($id='', $status='', $parentid=''){
        /*if($status==1){
            //History
            $Action_data = array('actionname'=>'hardwareassignment', 'actionid'=>$parentid, 'actiontitle'=>'insurance_activated');
            do_action_history($Action_data);
        }else{
            //History
            $Action_data = array('actionname'=>'hardwareassignment', 'actionid'=>$parentid, 'actiontitle'=>'insurance_deactivated');
            do_action_history($Action_data);
        }*/
        $this->db->query("UPDATE `tblhardwareassignmentproducts` SET `is_insurance`='".$status."' WHERE id='".$id."'");
        exit;
    }

    /* Change Status */
    public function change_hardwareassignment_mdm($id='', $status='', $parentid=''){
        /*if($status==1){
            //History
            $Action_data = array('actionname'=>'hardwareassignment', 'actionid'=>$parentid, 'actiontitle'=>'mdm_activated');
            do_action_history($Action_data);
        }else{
            //History
            $Action_data = array('actionname'=>'hardwareassignment', 'actionid'=>$parentid, 'actiontitle'=>'mdm_deactivated');
            do_action_history($Action_data);
        }*/
        $this->db->query("UPDATE `tblhardwareassignmentproducts` SET `is_mdm`='".$status."' WHERE id='".$id."'");
        exit;
    }

    /* List all documents by ajax */
    public function ajaxdocument($assignmentnr)
    {
        $params['assignmentid'] = $assignmentnr;
        $this->app->get_table_data('assignmentdocuments',$params);
    }

    /* List all documents by ajax */
    public function ajaxhardwareassignmentpositiondocument($hardwareassignmentproductid)
    {
        $params['hardwareassignmentproductid'] = $hardwareassignmentproductid;
        $this->app->get_table_data('hardwareassignmentpositiondocuments',$params);
    }

    /* List all invoices by ajax */
    public function ajaxinvoice($assignmentnr, $filter_invoice_year='')
    {
        $params['assignmentid'] = $assignmentnr;
        $params['filter_invoice_year'] = $filter_invoice_year;
        $this->app->get_table_data('assignmentbills',$params);
    }


    /* List all invoices by ajax */
    public function ajaxproduct($assignmentnr, $filter_invoice_year='')
    {
        $params['assignmentid'] = $assignmentnr;
        // $params['filter_invoice_year'] = $filter_invoice_year;
        $this->app->get_table_data('assignmentproducts',$params);
    }


    /* Add/Edit Assignment */
    public function assignment($id='')
    {
        if(!$GLOBALS['assignment_permission']['create'] && !$GLOBALS['assignment_permission']['edit']){
            access_denied('assignment');
        }

        //******************** Initialise ********************/
        if($id>0){
            //Assignment
            $data['assignment'] = (array) $this->Assignment_model->get($id);
        }
        //******************** End Initialise ********************/
// echo "<pre>";
// print_r($assignment);
// die();
        //Submit Page
        if ($this->input->post()) {
            $post = $this->input->post();

            $post['company'] = $post['customer'];
            if(isset($data['assignment']['assignmentnr'])){

                $response = $this->Assignment_model->update($post, $data['assignment']['assignmentnr']);
                if (is_numeric($response) && $response>0) {
                    //History
                    $Action_data = array('actionname'=>'assignment', 'actionid'=>$response, 'actiontitle'=>'assignment_updated');
                    do_action_history($Action_data);

                    set_alert('success', sprintf(lang('updated_successfully'),lang('page_assignment')));
                    redirect(site_url('admin/assignments/detail/' . $data['assignment']['assignmentnr']));
                    exit;
                }
                else{
                    set_alert('danger', $response);
                }
            }
            else{
                $response = $this->Assignment_model->add($post);
                if (is_numeric($response) && $response>0) {
                    //History
                    $Action_data = array('actionname'=>'assignment', 'actionid'=>$response, 'actiontitle'=>'assignment_added');
                    do_action_history($Action_data);

                    set_alert('success', sprintf(lang('added_successfully'),lang('page_assignment')));
                    redirect(site_url('admin/assignments/detail/' . $response));
                    exit;
                }
                else{
                    set_alert('danger', $response);
                }
            }

            //Initialise
            $assignmentnr = '';
            if(isset($data['assignment'])){
                $assignmentnr = $data['assignment']['assignmentnr'];
            }
            $data['assignment'] = $post;
            $data['assignment']['assignmentnr'] = $assignmentnr;
        }


        //******************** Initialise ********************/
        //Assignmentstatus
        $data['assignmentstatus'] = $this->Assignmentstatus_model->get();
        $data['assignmentstatus'] = dropdown($data['assignmentstatus'],'id','name');

        //Responsibles (Users of Customer)
        /*$data['responsibles'] = $this->Customer_model->get('',"tblusers.userid, CONCAT(tblusers.name,' ',tblusers.surname) as name",
            array('tblusers'=>'tblusers.userid=tblcustomers.responsible')
        );
        $data['responsibles'] = dropdown($data['responsibles'],'userid','name');*/

        //Customers
        //$data['customers'] = $this->Customer_model->get('',"tblcustomers.customernr, CONCAT(tblcustomers.name,' ',tblcustomers.surname) as name");
        //$data['customers'] = dropdown($data['customers'],'customernr','name');
        $data['customers'] = $this->Customer_model->get('',"tblcustomers.customernr, tblcustomers.company");
        $data['customers'] = dropdown($data['customers'],'customernr','company');

        //Recommends (POS)
        $data['recommends'] = $this->User_model->get('',"userid, CONCAT(name,' ',surname) as name"," userrole IN(6) ");
        $data['recommends'] = dropdown($data['recommends'],'userid','name');

        //Discount Level
        $data['discountlevels'] = $this->Discountlevel_model->get();
        // $data['discountlevels'] = dropdown($data['discountlevels'],'discountnr','discounttitle');

        //VVL Neu
        $data['vvlneu'] = $this->Vvlneu_model->get();
        $data['vvlneu'] = dropdown($data['vvlneu'],'id','name');

        //Mobile Rate
        $data['mobilerates'] = $this->Ratemobile_model->get();
        // $data['mobilerates'] = dropdown($data['mobilerates'],'ratenr','ratetitle');

        //Hardware
        $data['hardwares'] = $this->Hardware_model->get();
        $data['hardwares'] = dropdown($data['hardwares'],'hardwarenr','hardwaretitle');

        //Mobile Option
        $data['mobileoptions'] = $this->Optionmobile_model->get();
        // $data['mobileoptions'] = dropdown($data['mobileoptions'],'optionnr','optiontitle');

        //Remindersubjects
        $data['remindersubjects'] = $this->Remindersubject_model->get();
        $data['remindersubjects'] = dropdown($data['remindersubjects'],'id','name');

        //Categories
        $data['categories'] = $this->Documentsetting_model->get('',''," active=1 ");
        $data['categories'] = dropdown($data['categories'],'categoryid','categoryname');

        //Assignment Products
        $assignmentnr = isset($data['assignment']['assignmentnr'])?$data['assignment']['assignmentnr']:'';
        $data['assignmentproducts'] = $this->Assignmentproduct_model->get('','tblassignmentproducts.*, tblvvlneu.name as vvlneuname', array('tblvvlneu'=>'tblvvlneu.id=tblassignmentproducts.vvlneu')," assignmentnr='".$assignmentnr."' ");
        //******************** End Initialise ********************/


        //Page Title
        if(isset($data['assignment']['assignmentnr']) && $data['assignment']['assignmentnr']>0){
            $data['title'] = lang('page_edit_assignment');

            if(get_user_role()=='customer' && $data['assignment']['customerid']!=get_user_id()){
                redirect(site_url('admin/assignments'));
            }
            //- On the Dashboard he should only see Assignments which belongs to the User who is logged in. (Salesman)
            else if(get_user_role()=='user' && isset($GLOBALS['current_user']->userrole) && $GLOBALS['current_user']->userrole==3 && $data['assignment']['userid']!=get_user_id() && $data['assignment']['responsible']!=get_user_id()){
                redirect(site_url('admin/assignments'));
            }
            //- He can see only Assignment where the POS was choosen.
            else if(get_user_role()=='user' && isset($GLOBALS['current_user']->userrole) && $GLOBALS['current_user']->userrole==6 && $data['assignment']['recommend']!=get_user_id()){
                redirect(site_url('admin/assignments'));
            }
        }
        else{
            $data['title'] = lang('page_create_assignment');
        }
        $data['providerData'] = $this->select_record('tblprovider');

        $this->load->view('admin/assignments/assignment', $data);
    }

    /* Detail Assignment */
    public function detail($id='')
    {
        if(!$GLOBALS['assignment_permission']['view'] && !$GLOBALS['assignment_permission']['view_own']){
            access_denied('assignment');
        }

        //******************** Initialise ********************/
        if($id>0){
            //Assignment
            $data['assignment'] = (array) $this->Assignment_model->get($id,"tblassignments.*,tblprovider.image as provider,CONCAT(customer.name,' ',customer.surname) as customer, tblassignments.customer as customerid , CONCAT(responsible.name,' ',responsible.surname) as responsible, tblassignments.responsible as responsible_id, "
                    . " CONCAT(recommend.name,' ',recommend.surname) as recommend, "
                    . " tblassignments.recommend as recommendid, "
                    . " tblassignmentstatus.name as assignmentstatus, "
                    . " newdiscountlevel.discounttitle as newdiscountlevel,"
                    . " tblassignments.newdiscountlevel as newdiscountlevel_id, "
                    . " customer.company as customer_company ",

                    array('tblusers as responsible'=>'responsible.userid=tblassignments.responsible',
                    'tblcustomers as customer'=>'customer.customernr=tblassignments.customer',
                    'tblusers as recommend'=>'recommend.userid=tblassignments.recommend',
                    'tblassignmentstatus'=>'tblassignmentstatus.id=tblassignments.assignmentstatus',
                    'tblprovider'=>'tblprovider.name=tblassignments.provider',
                    'tbldiscountlevels as newdiscountlevel'=>'newdiscountlevel.discountnr=tblassignments.newdiscountlevel')
            );
        }
        if(empty($data['assignment']['assignmentnr'])){
            redirect(site_url('admin/assignments'));
        }

        if(get_user_role()=='customer' && $data['assignment']['customerid']!= get_user_id() && $GLOBALS['current_user']->parent_customer_id == 0 ){
            redirect(site_url('admin/assignments'));
        }
        //- On the Dashboard he should only see Assignments which belongs to the User who is logged in. (Salesman)
        else if(get_user_role()=='user' && isset($GLOBALS['current_user']->userrole) && $GLOBALS['current_user']->userrole==3 && $data['assignment']['userid']!=get_user_id() && $data['assignment']['responsible_id']!=get_user_id()){
            redirect(site_url('admin/assignments'));
        }
        //- He can see only Assignment where the POS was choosen.
        else if(get_user_role()=='user' && isset($GLOBALS['current_user']->userrole) && $GLOBALS['current_user']->userrole==6 && $data['assignment']['recommendid']!=get_user_id()){
            redirect(site_url('admin/assignments'));
        }
        //******************** End Initialise ********************/


        //******************** Initialise ********************/
        //Mobile Option
        //$data['mobileoptions'] = $this->Optionmobile_model->get("","CONCAT(optionnr,'=',optiontitle,'=',runningtime) as optionnr, optiontitle");
        if (get_user_role() == 'customer') {
            $data['mobileoptions'] = $this->Optionmobile_model->get("","CONCAT(optionnr,'=',optiontitle,'=',runningtime) as optionnr, optiontitle","provider = '".$data['assignment']['provider']."'");
        } else {
            $data['mobileoptions'] = $this->Optionmobile_model->get("","CONCAT(optionnr,'=',optiontitle,'=',runningtime) as optionnr, optiontitle");
        }
        $data['mobileoptions'] = dropdown($data['mobileoptions'],'optionnr','optiontitle');
        //Mobile Option 2
        $data['mobileoptions_2'] = $this->Optionmobile_model->get("","optionnr, optiontitle");
        //$data['mobileoptions_2'] = dropdown($data['mobileoptions_2'],'optionnr','optiontitle');

        //Hardware
        $data['hardwares'] = $this->Hardware_model->get("","CONCAT(hardwarenr,'=',hardwaretitle) as hardwarenr, hardwaretitle");
        $data['hardwares'] = dropdown($data['hardwares'],'hardwarenr','hardwaretitle');

        //Categories
        $data['categories'] = $this->Documentsetting_model->get('',''," active=1 ");
        $data['categories'] = dropdown($data['categories'],'categoryid','categoryname');

        //Remindersubjects
        $data['remindersubjects'] = $this->Remindersubject_model->get();
        $data['remindersubjects'] = dropdown($data['remindersubjects'],'id','name');

        //Mobile Rate
        if (get_user_role() == 'customer') {
            $data['mobilerates'] = $this->Ratemobile_model->get("","CONCAT(ratenr,'=',ratetitle) as ratenr, ratetitle",array(),"shop=1 AND provider = '".$data['assignment']['provider']."'");
        } else {
            $data['mobilerates'] = $this->Ratemobile_model->get("","CONCAT(ratenr,'=',ratetitle) as ratenr, ratetitle",array(),"shop=1");
        }
        $data['mobilerates'] = dropdown($data['mobilerates'],'ratenr','ratetitle');

        //Assignment Products
        $data['assignmentproducts'] = $this->Assignmentproduct_model->get('','tblassignmentproducts.*, tblvvlneu.name as vvlneu, '
                . " newratemobile.ratetitle as newratemobile, tblassignmentproducts.newratemobile as newratemobile_id, "
                . " newoptionmobile.optiontitle as newoptionmobile,"
                . 'tblhardwares.hardwaretitle as hardware',

                array('tblvvlneu'=>'tblvvlneu.id=tblassignmentproducts.vvlneu',
                    'tblratesmobile as newratemobile'=>'newratemobile.ratenr=tblassignmentproducts.newratemobile',
                    'tbloptionsmobile as newoptionmobile'=>'newoptionmobile.optionnr=tblassignmentproducts.newoptionmobile',
                    'tblhardwares'=>'tblhardwares.hardwarenr=tblassignmentproducts.hardware'
                ),

            " tblassignmentproducts.assignmentnr='".$data['assignment']['assignmentnr']."' "
        );
        //******************** End Initialise ********************/



        /************************************************************************************************************************************/
        /* - When I click on a Assignment there are three Tabs. Add there the Tab "Hardware Aufträge" (Hardware-Assignment wich belogs to this
Assignment) */
        /************************************************************************************************************************************/

        //Hardwareassignment
        $hardwareassignment = (array) $this->Hardwareassignment_model->get("","tblhardwareassignments.*, CONCAT(customer.name,' ',customer.surname) as customer, tblhardwareassignments.customer as customerid, CONCAT(responsible.name,' ',responsible.surname) as responsible, "
                . " tblhardwareassignmentstatus.name as hardwareassignmentstatus, ",

                array('tblusers as responsible'=>'responsible.userid=tblhardwareassignments.responsible',
                'tblcustomers as customer'=>'customer.customernr=tblhardwareassignments.customer',
                'tblhardwareassignmentstatus'=>'tblhardwareassignmentstatus.id=tblhardwareassignments.hardwareassignmentstatus'),

                "assignmentnr='".$id."'"
        );
        $data['hardwareassignment'] = isset($hardwareassignment[0]) ? $hardwareassignment[0] : array();

        //Get Hardware Assignment IDs
        $hardwareAssignmentIDs = array();
        foreach($hardwareassignment as $row_hardwareassignment){
            $hardwareAssignmentIDs[] = $row_hardwareassignment['hardwareassignmentnr'];
        }
        $hardwareAssignmentIDs = implode(",",$hardwareAssignmentIDs);
        if(empty($hardwareAssignmentIDs)){ $hardwareAssignmentIDs=0; }


        //Hardware Assignment Products
        $data['hardwareassignmentproducts'] = $this->Hardwareassignmentproduct_model->get('','tblhardwareassignmentproducts.*, '
                . " newratemobile.ratetitle as newratemobile,"
                . ' tblhardwares.hardwaretitle as hardware,'
                . ' assignmentproduct.employee as employee,'
                . ' assignmentproduct.hardwareassignmentnr as extrenalhardwareassignmentnr,'
                . ' tblhardwarecategories.name as hardwarecategory,'
                . " stockhardware.hardwaretitle as stockhardwaretitle",

                array('tblratesmobile as newratemobile'=>'newratemobile.ratenr=tblhardwareassignmentproducts.newratemobile',
                    'tblhardwares'=>'tblhardwares.hardwarenr=tblhardwareassignmentproducts.hardware',
                    'tblhardwareinputproducts'=>'tblhardwareinputproducts.id=tblhardwareassignmentproducts.stockhardware',
                    'tblhardwares as stockhardware'=>'stockhardware.hardwarenr=tblhardwareinputproducts.hardware',
                    'tblhardwarecategories'=>'tblhardwarecategories.id=tblhardwares.hardwarecategory',
                    'tblassignmentproducts as assignmentproduct'=>'assignmentproduct.id=tblhardwareassignmentproducts.productpositionid'
                ),

            " tblhardwareassignmentproducts.hardwareassignmentnr IN($hardwareAssignmentIDs) "
        );
        /************************************************************************************************************************************/
        //print_r($data['hardwareassignmentproducts']);exit;

        $data['hardware_data'] = $data['hardwares'] = $this->Hardware_model->get();
        $data['hardwares'] = dropdown($data['hardwares'],'hardwarenr','hardwaretitle');

        //Hardwarecategories
        $data['hardwarecategories'] = $this->Hardwarecategory_model->get();
        $data['hardwarecategories'] = dropdown($data['hardwarecategories'],'id','name');

        //Page Title
        $data['title'] = lang('page_detail_assignment');
        $this->load->view('admin/assignments/detail', $data);
    }

    public function addExternalHardware() {
        $response = '';
        if ($this->input->post()) {
            $assignment_data = $this->Assignment_model->get($this->input->post('assignmentnr'));
            $hardwareassignments = array();
            $hardwareassignments['assignmentnr'] = $this->input->post('assignmentnr');
            $hardwareassignments['company'] = $assignment_data->company;
            $hardwareassignments['customer'] = $assignment_data->customer;
            $hardwareassignments['provider'] = $assignment_data->provider;
            $hardwareassignments['responsible'] = $assignment_data->responsible;
            $hardwareassignments['hardwareassignmentstatus'] = 1;
            $hardwareassignmentnr = $this->Hardwareassignment_model->add($hardwareassignments);

            $newratemobile = $this->Assignmentproduct_model->get($this->input->post('id'));
            $hardwareassignmentproducts = array();
            $hardwareassignmentproducts['hardwareassignmentnr'] = $hardwareassignmentnr;
            $hardwareassignmentproducts['productpositionid'] = $this->input->post('id');
            $hardwareassignmentproducts['seriesnr'] = $this->input->post('seriesnr');
            $hardwareassignmentproducts['hardware'] = $this->input->post('hardware');
            $hardwareassignmentproducts['newratemobile'] = $newratemobile->newratemobile;
            $hardwareassignmentproducts['mobilenr'] = $newratemobile->mobilenr;
            $hardwareassignmentproducts['simnr'] = $newratemobile->simnr;
            $hardwareassignmentproducts['notice'] = 'Externe Hardware';
            $this->Hardwareassignmentproduct_model->add($hardwareassignmentproducts);

            $this->Assignmentproduct_model->update(array('hardwareassignmentnr' => $hardwareassignmentnr), $this->input->post('id'));

            echo json_encode(array('response'=>'success','message'=>sprintf(lang('added_successfully'),lang('page_lb_hardwareinventory'))));
        } else {
            echo json_encode(array('response'=>'error','message'=>$response));
        }
        exit;
    }

    public function deleteHardware() {

        if(/*!$GLOBALS['assignment_permission']['delete'] ||*/ !$this->input->post('id')){
            access_denied('assignment');
        }

        $temp = $this->Hardwareassignment_model->get($this->input->post('id'));
        $response = $this->Hardwareassignment_model->delete($this->input->post('id'));
        if ($response==1) {
            //History
            $this->Assignmentproduct_model->update(array('hardwareassignmentnr' => 0), $this->input->post('parentid'));
            $Action_data = array('actionname'=>'assignment', 'actionid'=>$this->input->post('id'), 'actiontitle'=>'assignment_hardware_deleted');
            do_action_history($Action_data);

            set_alert('success', sprintf(lang('deleted_successfully'),lang('page_hardwareassignment')));
            redirect(site_url('admin/assignments/detail/'.$temp->assignmentnr));
        }else{
            set_alert('danger', $response);
            redirect(site_url('admin/assignments/'));
        }

    }

    /* Delete assignment */
    public function delete()
    {
        if(!$GLOBALS['assignment_permission']['delete'] || !$this->input->post('id')){
            access_denied('assignment');
        }

        $response = $this->Assignment_model->delete($this->input->post('id'));
        if ($response==1) {
            //History
            $Action_data = array('actionname'=>'assignment', 'actionid'=>$this->input->post('id'), 'actiontitle'=>'assignment_deleted');
            do_action_history($Action_data);

            set_alert('success', sprintf(lang('deleted_successfully'),lang('page_assignment')));
        }else{
            set_alert('danger', $response);
        }
        redirect(site_url('admin/assignments/'));
        exit;
    }

    /* Upload Dropzone file by Ajax */
    public function uploadDocuments($id){
        handle_assignment_attachments($id);
        exit;
    }

    /* Get Uploaded Documents by Ajax */
    public function getDocuments($id){
        //******************** Initialise ********************/
        //Assignment
        $data['assignment'] = (array) $this->Assignment_model->get($id);
        //******************** End Initialise ********************/

        if(count($data['assignment']['attachments']) > 0) {
            $this->load->view('admin/assignments/assignments_attachments_template', array('attachments'=>$data['assignment']['attachments']));
        }
    }

    /* Delete Document by Ajax */
    public function deleteDocument(){
        if($this->input->post('id')){
            $response = $this->Assignment_model->delete_assignment_attachment($this->input->post('id'));
            if ($response==1) {
                echo json_encode(array('response'=>'success','message'=>sprintf(lang('deleted_successfully'),lang('page_assignmentdocument'))));
            }else{
                echo json_encode(array('response'=>'error','message'=>$response));
            }
        }
        exit;
    }

    /* Update Document Category by Ajax */
    public function updateDocumentCategory($id, $categoryid){
        if(isset($id)){
            $response = $this->Assignment_model->update_assignment_attachmentcategory($id, $categoryid);
            if ($response==1) {
                echo json_encode(array('response'=>'success','message'=>sprintf(lang('updated_successfully'),lang('page_assignmentdocument'))));
            }else{
                echo json_encode(array('response'=>'error','message'=>$response));
            }
        }
        exit;
    }

    /* Download Document */
    public function downloadDocument($attachmentid){
        $this->db->where('id', $attachmentid);
        $attachment = $this->db->get('tblfiles')->row();
        if (!$attachment) {
            die('No attachment found in database');
        }
        $path = get_upload_path_by_type('assignment') . $attachment->rel_id . '/' . $attachment->file_name;
        force_download($path, null);
    }


    /* Upload Dropzone file by Ajax */
    public function uploadLegitimations($id){
            handle_assignment_legitimations($id);
            exit;
    }

    /* Get Uploaded Legitimations by Ajax */
    public function getLegitimations($id){
            //******************** Initialise ********************/
            //Assignment
            $data['assignment'] = (array) $this->Assignment_model->get($id);
            //******************** End Initialise ********************/

            if(count($data['assignment']['legitimations']) > 0) {
                    $this->load->view('admin/assignments/assignments_legitimations_template', array('legitimations'=>$data['assignment']['legitimations']));
            }
    }

    /* Delete Legitimation by Ajax */
    public function deleteLegitimation(){
            if($this->input->post('id')){
                    $response = $this->Assignment_model->delete_assignment_legitimation($this->input->post('id'));
                    if ($response==1) {
                            echo json_encode(array('response'=>'success','message'=>sprintf(lang('deleted_successfully'),lang('page_lb_legitimation'))));
                    }else{
                            echo json_encode(array('response'=>'error','message'=>$response));
                    }
            }
            exit;
    }

    /* Download Legitimation */
    public function downloadLegitimation($legitimationid){
            $this->db->where('id', $legitimationid);
            $legitimation = $this->db->get('tblfiles')->row();
            if (!$legitimation) {
                    die('No legitimation found in database');
            }
            $path = get_upload_path_by_type('assignment') . $legitimation->rel_id . '/' . $legitimation->file_name;
            force_download($path, null);
    }

    //Generate Reminder by Cronjob (Notification Email 1 Day before he set the date)
    public function generatereminder($id=''){
        //Reminder to Responsible
        $this->Assignment_model->sendReminder($id);
    }

    //Get Value for Mobile Rate 1 or Mobile Rate 2 Auto Calculation
    public function getMobileRateValue($id='', $discountlevel='', $formula=''){
        echo $this->Assignment_model->getMobileRateValue($id, $discountlevel, $formula);
        exit;
    }

    //Get Value for Mobile Option 1 or Mobile Option 2 Price
    public function getMobileOptionValue($id=''){
        echo $this->Assignment_model->getMobileOptionValue($id);
        exit;
    }

    //Responsibles (Users of Customer) by Ajax
    public function getResponsibleOfCustomer($custid){
        $data['responsibles'] = $this->Customer_model->get('',"tblusers.userid, CONCAT(tblusers.name,' ',tblusers.surname) as name",
            array('tblusers'=>'tblusers.userid=tblcustomers.responsible'),
            "tblcustomers.customernr='".$custid."'"
        );
        $data_array = dropdown($data['responsibles'],'userid','name');
        echo json_encode($data_array);
        exit;
    }

    //Delete Assignment Product By Ajax
    public function deleteAssignmentProduct(){
        $response = $this->Assignmentproduct_model->delete($this->input->post('id'));
        if ($response==1) {
            //echo json_encode(array('response'=>'success','message'=>sprintf(lang('deleted_successfully'),lang('page_assignment_product')),'dataid'=>$this->input->post('id')));
            echo json_encode(array('response'=>'success','message'=>sprintf(lang('deleted_successfully'),lang('page_assignment_product')),'dataid'=>$this->input->post('parentid')));
        }else{
            //echo json_encode(array('response'=>'error','message'=>$response,'dataid'=>$this->input->post('id')));
            echo json_encode(array('response'=>'error','message'=>$response,'dataid'=>$this->input->post('parentid')));
        }
        exit;
    }

    //Generate Reminder by Cronjob of assignment
    //When Between Datetoday and saved Date is not more than 3 Month the row “Finished”
    public function generatereminder_assignment($id=''){
        //Reminder to Responsible
        $this->Assignment_model->sendReminder_assignment($id);
    }

    //Generate Tickets
    public function generateTicket($id=''){
        //$response = $this->Assignment_model->generateTicket(7, 'cardlock', 1, 5);
        //$response = $this->Assignment_model->generateTicket($this->input->post('assignmentId'), $this->input->post('ticketType'), $this->input->post('emailSend'), $this->input->post('assignmentProductId'), @$this->input->post('mobileoption'), @$this->input->post('hardware'));
        $response = $this->Assignment_model->generateTicket();
        if ($response==1) {
            echo json_encode(array('response'=>'success','message'=>sprintf(lang('cronjob_generated_successfully'),lang('page_lb_'.$this->input->post('ticketType')))));
        }else{
            echo json_encode(array('response'=>'error','message'=>$response));
        }
        exit;
    }

    //Save Employees by Ajax
    public function saveEmployees(){
        if ($this->input->post()) {
            $post = $this->input->post();
            if(isset($post['employee']) && count($post['employee'])>0){
                foreach($post['employee'] as $key=>$employee){

                    $pin = $post['pin'][$key];
                    $puk = $post['puk'][$key];

                    $data = array('employee'=>$employee,'pin'=>$pin,'puk'=>$puk);

                    $this->Assignmentproduct_model->update($data, $key);
                }
                echo json_encode(array('response'=>'success','message'=>sprintf(lang('updated_successfully'),lang('page_assignment_employee'))));
            }
        }
        else{
            echo json_encode(array('response'=>'error','message'=>$response));
        }
        exit;
    }

    //Get Hardware Assignment Value of Calculation by Ajax
    public function getHardwareAssignmentValue($hardwareid, $assignmentproductid, $newdiscountlevel){
        $hardware_calculate_value = 0;
        $commission_value = 0;
        $hardwareprice = 0;

        if($hardwareid!="" && $hardwareid>0){
            //Get Discount Title
            $discountLevel = (array) $this->Discountlevel_model->get($newdiscountlevel, 'discounttitle as newdiscounttitle');

            //Get Hardare
            $rowHardware = (array) $this->Hardware_model->get($hardwareid,'hardwareprice');
            $hardwareprice = $rowHardware['hardwareprice'];

            //Get Assignment Product
            $assignmentproduct = (array) $this->Assignmentproduct_model->get($assignmentproductid,'tblassignmentproducts.*, tblvvlneu.name as vvlneu, '
                    . " newratemobile.ratenr as newratenr, "
                    . " tblhardwares.hardwareprice, "
                    . " tblhardwares.hardwarenr as hardwarenr, "
                    . ' tblsubs.name as subname',
                    array('tblvvlneu'=>'tblvvlneu.id=tblassignmentproducts.vvlneu',
                        'tblratesmobile as newratemobile'=>'newratemobile.ratenr=tblassignmentproducts.newratemobile',
                        'tblhardwares'=>'tblhardwares.hardwarenr=tblassignmentproducts.hardware',
                        'tblsubs'=>'tblsubs.id=newratemobile.subn',
                    )
            );

            $extrafields = $this->Field_model->get('ratemobile',$assignmentproduct['newratenr']);
            foreach($extrafields as $fkey=>$extrafield){
                foreach($extrafield as $fkey2=>$fvalue2){
                    $extrafields[$fkey][trim($fkey2)] = trim($fvalue2);
                }
            }

            //There we choose from where we Select “Mobile Rate 2” in each added Product in Assignment.
            if(strtolower(trim($assignmentproduct['vvlneu']))=='neu' && strtolower(trim($assignmentproduct['subname']))=='nein'){
                //Commision = Value of PV190000SO
                $array_column = array_column($extrafields, 'field_name');
                $fkey = array_search('PV'.$discountLevel['newdiscounttitle'].'SO', $array_column);
                $commission_value = $extrafields[$fkey]['field_value'];
            }
            else if(strtolower(trim($assignmentproduct['vvlneu']))=='vvl' && strtolower(trim($assignmentproduct['subname']))=='nein'){
                //Commision = Value of PV190000VVL
                $array_column = array_column($extrafields, 'field_name');
                $fkey = array_search('PV'.$discountLevel['newdiscounttitle'].'VVL', $array_column);
                $commission_value = $extrafields[$fkey]['field_value'];
            }
            else if(strtolower(trim($assignmentproduct['vvlneu']))=='vvl' && strtolower(trim($assignmentproduct['subname']))=='ja'){
                //Commision = Value of PV190000VVL
                $array_column = array_column($extrafields, 'field_name');
                $fkey = array_search('PV'.$discountLevel['newdiscounttitle'].'VVL', $array_column);
                $commission_value = $extrafields[$fkey]['field_value'];
            }
            else if(strtolower(trim($assignmentproduct['vvlneu']))=='neu' && strtolower(trim($assignmentproduct['subname']))=='ja'){
                //Commision = Value of PV190000SUB
                $array_column = array_column($extrafields, 'field_name');
                $fkey = array_search('PV'.$discountLevel['newdiscounttitle'].'SUB', $array_column);
                $commission_value = $extrafields[$fkey]['field_value'];
            }
            /**************************************************************************************/

            /*Hardwarevalue will calculate in this way >>
            if (Commision – Hardwareprice>=99)
            Hardwarevalue = 1,00 €
            if (Commision – Hardwareprice<99)
            Hardwarevalue = (Commision-Hardwareprice) *(-1) + 99,00 €
            */
            if(($commission_value - $hardwareprice)>=99){
                //Hardwarevalue = 1,00 €
                $hardware_calculate_value = 1;
            }
            else if(($commission_value - $hardwareprice)<99){
                //Hardwarevalue = (Commision-Hardwareprice) *(-1) + 99,00 €
                $hardware_calculate_value = (($commission_value - $hardwareprice)*(-1)) + 99;
            }
        }

        echo format_money($hardware_calculate_value, "&nbsp;".$GLOBALS['currency_data']['currency_symbol']);
        exit;
    }

    //Get Hardware Assignment Value of Calculation by Ajax
    public function getHardwareMobileOptionValue($id){
        $hardware_calculate_value = 0;
        if($id!="" && $id>0){
            $hardware_calculate_value = $this->Assignment_model->getMobileOptionValue($id);
        }
        echo format_money($hardware_calculate_value, "&nbsp;".$GLOBALS['currency_data']['currency_symbol']);
        exit;
    }

    //Get Contract Assignment Value of Calculation by Ajax
    public function getContractAssignmentValue($ratemobileid, $newdiscountlevel){
        $temp = $this->Assignment_model->getMobileRateValue($ratemobileid, $newdiscountlevel, 'A');
        $temp = explode('[=]',$temp);
        $ratemobile_calculate_value = $temp[0];
        echo format_money($ratemobile_calculate_value, "&nbsp;".$GLOBALS['currency_data']['currency_symbol']);
        exit;
    }

    /* Add Invoice by Ajax */
    public function addInvoice(){

        //Submit Page
        if ($this->input->post()) {
            $post = $this->input->post();

            $bill_id = $this->Assignmentbill_model->add($post);
            if (is_numeric($bill_id) && $bill_id > 0) {
                $assignmentData = $this->Assignment_model->get($post['assignmentnr']);

                //History
                $Action_data = array('actionname'=>'assignment', 'actionid'=>$post['assignmentnr'], 'actionsubid'=>$bill_id, 'actiontitle'=>'assignment_invoice_added');
                do_action_history($Action_data);

                $resu = handle_assignment_invoicefile_upload($post['assignmentnr'], $bill_id);
                handle_assignment_invoicefilecsv_upload($post['assignmentnr'], $bill_id);
                //echo $resu; die();
                if($resu) {
                    if(!empty($assignmentData))  {
                        $customerData = $this->Customer_model->get($assignmentData->customer);

                        if(!empty($customerData)) {
                            if($customerData->invoice_email == '0') {
                                 //CSV Attachment
                                $mer_data = array();
                                $billData = $this->Assignmentbill_model->get($bill_id);
                                if(!empty($billData) && $billData->invoicefilecsv != '') {
                                    $file = FCPATH.'uploads/assignments/'.$post['assignmentnr'].'/bills/'.$billData->invoicefilecsv;
                                    $this->Email_model->add_attachment(array('attachment' => $file));
                                    $mer_data['assignment_link_csv'] = base_url().'uploads/assignments/'.$post['assignmentnr'].'/bills/'.$billData->invoicefilecsv;
                                }

                                if(!empty($billData) && $billData->invoicefile != '') {
                                    $file = FCPATH.'uploads/assignments/'.$post['assignmentnr'].'/bills/'.$billData->invoicefile;
                                    $this->Email_model->add_attachment(array('attachment' => $file));
                                    $mer_data['assignment_link'] = base_url().'uploads/assignments/'.$post['assignmentnr'].'/bills/'.$billData->invoicefile;
                                }

                                $mer_data['customer_surname'] = $customerData->surname;
                                $mer_data['customer_name'] = $customerData->name;
                                $mer_data['customernr'] = $customerData->customernr;

                                $merge_fields = array();
                                $merge_fields = array_merge($merge_fields, get_customerinvoice_merge_fields($mer_data));

                                $invoiceCustomer = $this->Customer_model->get($customerData->invoice_cus);
                                // $sent = $this->Email_model->send_email_template('invoicecsvemail', $invoiceCustomer->email, $merge_fields);
                                $sent = $this->Email_model->send_email_template('invoicecsvemail', 'connectusdemo12@gmail.com', $merge_fields);

                            }
                        }
                    }
                    echo json_encode(array('response'=>'success','message'=>sprintf(lang('added_successfully'),lang('page_lb_invoice'))));
                } else{
                    echo json_encode(array('response'=>'error','message'=>$response));
                }
            }
        }
        exit;
    }

    /* Delete Invoice by Ajax */
    public function deleteInvoice(){
        $response = $this->Assignmentbill_model->delete($this->input->post('id'));
        if ($response==1) {

            //History
            $Action_data = array('actionname'=>'assignment', 'actionid'=>$this->input->post('parentid'), 'actionsubid'=>$response, 'actiontitle'=>'assignment_invoice_deleted');
            do_action_history($Action_data);

            echo json_encode(array('response'=>'success','message'=>sprintf(lang('deleted_successfully'),lang('page_lb_invoice'))));
        }else{
            echo json_encode(array('response'=>'error','message'=>$response));
        }
        exit;
    }

    /* Get Hardware Assignment Position Document */
    public function getHardwareAssignmentPositionDocument($hardwareassignmentproductid){
        //Categories
        $data['categories'] = $this->Documentsetting_model->get('',''," active=1 ");
        $data['categories'] = dropdown($data['categories'],'categoryid','categoryname');
        $data['hardwareassignmentproduct'] = (array) $this->Hardwareassignmentproduct_model->get($hardwareassignmentproductid);
        //$data['documents'] = $this->Hardwareassignmentproduct_model->get_hardwareposition_documents($hardwareassignmentproductid);
        $this->load->view('admin/assignments/tab-hardwareassignmentpositiondocument', $data);
    }

    /* Upload Hardare Position Document by Ajax */
    public function uploadHardwareAssignmentPositionDocuments($hardwareassignmentproductid){
        handle_hardwareassignmentposition_documents($hardwareassignmentproductid);
        exit;
    }

    /* Delete Hardare Position Document by Ajax */
    public function deleteHardwarePositionDocument(){
        if($this->input->post('id')){
            $response = $this->Hardwareassignmentproduct_model->delete_hardwareassignmentposition_document($this->input->post('id'));
            if ($response==1) {
                echo json_encode(array('response'=>'success','message'=>sprintf(lang('deleted_successfully'),lang('page_hardwareassignmentpositiondocument'))));
            }else{
                echo json_encode(array('response'=>'error','message'=>$response));
            }
        }
        exit;
    }

    public function getAssignmentproduct($assignmentnr = '', $limit = 5, $start = 0) {
        $data['assignmentproducts'] = $this->Assignmentproduct_model->get('','tblassignmentproducts.*, tblvvlneu.name as vvlneuname', array('tblvvlneu'=>'tblvvlneu.id=tblassignmentproducts.vvlneu')," assignmentnr='".$assignmentnr."' ");
        $data['mobilerates'] = $this->Ratemobile_model->get();
        $data['mobileoptions'] = $this->Optionmobile_model->get();
        $data = $this->load->view('admin/assignments/assignment-product', $data, true);
        echo $data;
    }

    public function export_product_csv($assignmentnr){

        if (get_user_role()=='customer') {
            access_denied('assignments');
        }

        //Assignment Products
        $assignmentproducts = $this->Assignmentproduct_model->get('','tblassignmentproducts.*, tblvvlneu.name as vvlneu, '
                . " newratemobile.ratetitle as newratemobile, tblassignmentproducts.newratemobile as newratemobile_id, "
                . " newoptionmobile.optiontitle as newoptionmobile,"
                . 'tblhardwares.hardwaretitle as hardware',

                array('tblvvlneu'=>'tblvvlneu.id=tblassignmentproducts.vvlneu',
                    'tblratesmobile as newratemobile'=>'newratemobile.ratenr=tblassignmentproducts.newratemobile',
                    'tbloptionsmobile as newoptionmobile'=>'newoptionmobile.optionnr=tblassignmentproducts.newoptionmobile',
                    'tblhardwares'=>'tblhardwares.hardwarenr=tblassignmentproducts.hardware'
                ),

            " tblassignmentproducts.assignmentnr='".$assignmentnr."' "
        );

        $idx = 0;
        $data[$idx] = array(lang('page_fl_simnr'), lang('page_fl_mobilenr'), lang('page_fl_employee'), lang('page_fl_vvl_neu'), lang('page_fl_ratetitle'), lang('page_fl_value'), lang('page_fl_extemtedterm'), lang('page_fl_subscriptionlock'), lang('page_fl_optiontitle'), lang('page_fl_value'), lang('page_fl_hardware'), lang('page_fl_cardstatus'), lang('page_fl_endofcontract'), lang('page_fl_finished'));
        foreach ($assignmentproducts as $key => $value) {
            $idx++;
            $data[$idx][] = $value['simnr'];
            $data[$idx][] = $value['mobilenr'];
            $data[$idx][] = $value['employee'];
            $data[$idx][] = $value['vvlneu'];
            $data[$idx][] = $value['newratemobile'];
            $data[$idx][] = $value['value2'];
            $data[$idx][] = $value['extemtedterm'];
            $data[$idx][] = $value['subscriptionlock'];
            $data[$idx][] = $value['newoptionmobile'];
            $data[$idx][] = $value['value4'];
            $data[$idx][] = $value['hardware'];
            $data[$idx][] = $value['cardstatus'];
            $data[$idx][] = $value['endofcontract'];
            $data[$idx][] = $value['finished'];
        }

        $filename = 'assignments_'.date('dmY').'.csv';
        header('Content-Type: application/csv');
        header('Content-Disposition: attachment; filename="'.$filename.'";');

        $f = fopen('php://output', 'w');
        foreach ($data as $key => $value) {
            fputcsv($f, $value, ';');
        }
        exit;
    }

    public function download_product_csv(){

        //Initialize
        $data = array();
        $data['file_name'] = 'tblassignmentproducts';
        $data['db_fields'] = $this->db->list_fields($data['file_name']);

        $data['not_importable'] = array('id', 'assignmentnr', 'formula', 'currentratemobile', 'value1', 'use', 'hardwarecheck', 'currentoptionmobile', 'value3', 'simcard_function_id', 'simcard_function_nm', 'simcard_function_qty', 'provicheck', 'ultracard1', 'ultracard2', 'cardbreak', 'is_paused', 'hardwareassignmentnr');
        $data['sample_data'][] = array(rand(0,100),rand(0,100),'Smaple Employye','VVL','Red Business XS','',1,1,'World Data','','iPhone 11 Pro Max 256 GB',1,date('d.m.Y'),1,rand(0,100),rand(0,100));

        header("Pragma: public");
        header("Expires: 0");
        header('Content-Type: application/csv');
        header("Content-Disposition: attachment; filename=\"".$data['file_name']."_sample_import_file.csv\";");
        header("Content-Transfer-Encoding: binary");

        $_total_sample_fields = 0;
        $_data = '';
        $data['importable'] = array('simnr', 'mobilenr', 'employee', 'vvlneu', 'newratemobile', 'value2', 'extemtedterm', 'subscriptionlock', 'newoptionmobile', 'value4', 'hardware', 'cardstatus', 'endofcontract', 'finished', 'pin', 'puk');
        foreach($data['importable'] as $field){
            if(in_array($field,$data['not_importable'])){continue;}
            $_data.=ucfirst($field).';';
            $_total_sample_fields++;
        }

        foreach ($data['sample_data'] as $key => $value) {
            $_data.="\n";
            for($f = 0;$f<$_total_sample_fields;$f++){
                $_data.=$value[$f].';';
            }
        }
        $_data.="\n";
        echo $_data;
        exit;
    }

     public function import_product_csv(){
        $responseData = array();
        $csvMimes = array('application/vnd.ms-excel','text/plain','text/csv','text/tsv');

        $data['importable'] = array('simnr', 'mobilenr', 'employee', 'vvlneu', 'newratemobile', 'value2', 'extemtedterm', 'subscriptionlock', 'newoptionmobile', 'value4', 'hardware', 'cardstatus', 'endofcontract', 'finished', 'pin', 'puk');

        if(!empty($_FILES['file_csv']['name']) && in_array($_FILES['file_csv']['type'],$csvMimes)){

            if(is_uploaded_file($_FILES['file_csv']['tmp_name'])){
                $csvFile = fopen($_FILES['file_csv']['tmp_name'], 'r');
                fgetcsv($csvFile);
                $csv_data = array();
                while (!feof($csvFile)) {
                    $tmp = fgetcsv($csvFile, null, ';');
                    if ($tmp) { $csv_data[] = $tmp; }
                }
                fclose($csvFile);

                $add_data = array();
                $idx = 0;
                foreach ($csv_data as $m_key => $line) {
                    $fkey = 0;
                    foreach($data['importable'] as $field){
                        $line[$fkey] = trim($line[$fkey]);
                        switch ($field) {
                            case 'vvlneu':
                                $add_data[$idx][$field] = '';
                                if ($line[$fkey]!='') {
                                    $temp = $this->Vvlneu_model->get('','id', array('name'=>$line[$fkey]));
                                    if (count($temp)) {
                                        $add_data[$idx][$field] = $temp[0]['id'];
                                    }
                                }
                            break;

                            case 'newratemobile':
                                $add_data[$idx][$field] = $line[$fkey];
                                if ($line[$fkey] != '') {
                                    $temp = $this->Ratemobile_model->get('','ratenr,price','', array('ratetitle'=>$line[$fkey]));
                                    if (count($temp)) {
                                        $add_data[$idx][$field] = $temp[0]['ratenr'];
                                        $add_data[$idx]['value2'] = $temp[0]['price'];
                                    }
                                }
                            break;

                            case 'hardware':
                                $add_data[$idx][$field] = $line[$fkey];
                                if ($line[$fkey] != '') {
                                    $temp = $this->Hardware_model->get('','hardwarenr', array('hardwaretitle'=>$line[$fkey]));
                                    if (count($temp)) {
                                        $add_data[$idx][$field] = $temp[0]['hardwarenr'];
                                    }
                                }
                            break;

                            case 'newoptionmobile':
                                $data['mobileoptions'] = $this->Optionmobile_model->get();
                                $add_data[$idx][$field] = $line[$fkey];
                                if ($line[$fkey] != '') {
                                    $temp = $this->Optionmobile_model->get('','optionnr,price', array('optiontitle'=>$line[$fkey]));
                                    if (count($temp)) {
                                        $add_data[$idx][$field] = $temp[0]['optionnr'];
                                        $add_data[$idx]['value4'] = $temp[0]['price'];
                                    }
                                }
                            break;

                            case 'simnr':
                            case 'mobilenr':
                                if (strpos($line[$fkey], '+') !== false) {
                                    $temp = filter_var($line[$fkey], FILTER_SANITIZE_NUMBER_INT);
                                    $add_data[$idx][$field] = (int) $temp;
                                    $add_data[$idx][$field] = str_pad($add_data[$idx][$field], (strlen($add_data[$idx][$field])+explode('+', $temp)[1]), '0');
                                } else {
                                    $add_data[$idx][$field] = $line[$fkey];
                                }
                            break;

                            default:
                                if(in_array($field,array('value2','value4'))){
                                    if ($field == 'value2' && $line[$fkey] != '') {
                                        $add_data[$idx][$field] = $line[$fkey];

                                    } else {
                                        $add_data[$idx][$field] = $add_data[$idx][$field];
                                    }

                                } else {
                                    $add_data[$idx][$field] = $line[$fkey];
                                }
                                break;
                        }
                        $fkey++;
                    }
                    $idx++;
                }

                $new_data['assignmentproducts'] = $add_data;
                $new_data['mobilerates'] = $this->Ratemobile_model->get();
                $new_data['mobileoptions'] = $this->Optionmobile_model->get();
                $new_data['vvlneu'] = $this->Vvlneu_model->get();
                $new_data['vvlneu'] = dropdown($new_data['vvlneu'],'id','name');
                $new_data['hardwares'] = $this->Hardware_model->get();
                $new_data['hardwares'] = dropdown($new_data['hardwares'],'hardwarenr','hardwaretitle');
                $html_data = $this->load->view('admin/assignments/assignment-product', $new_data, true);

                $responseData = array('status'=>1,'add_data'=>$add_data,'html_data' => $html_data);

            } else {
                $responseData = array('status'=>0,'message'=>lang('import_upload_failed'));
            }

        } else{
            $responseData = array('status'=>0,'message'=>lang('import_upload_failed'));
        }

        echo json_encode($responseData);
    }

}
