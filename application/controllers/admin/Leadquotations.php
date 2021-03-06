<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Leadquotations extends Admin_controller
{
    public function __construct()
    {
        parent::__construct();
        /*if(get_user_role()!='user'){
            redirect(site_url('admin/permission/denied/'));
        }*/
        $this->load->model('Leadquotation_model');
        $this->load->model('Leadquotationproduct_model');
        $this->load->model('User_model');
        $this->load->model('Customer_model');
        $this->load->model('Quotationstatus_model');
        $this->load->model('Documentsetting_model');
        $this->load->model('Remindersubject_model');
        $this->load->model('File_model');
        $this->load->model('Assignment_model');
        $this->load->model('Leadquotationreminder_model');
        $this->load->model('Discountlevel_model');
        $this->load->model('Vvlneu_model');
        $this->load->model('Ratemobile_model');
        $this->load->model('Hardware_model');
        $this->load->model('Optionmobile_model');
        $this->load->model('Assignmentstatus_model');
        $this->load->model('Pdf_model');
        $this->load->model('Note_model');
        $this->load->model('Lead_model');
    }

    /* List all leadquotations */
    public function index()
    {
        if(!$GLOBALS['leadquotation_permission']['view'] && !$GLOBALS['leadquotation_permission']['view_own']){
            access_denied('quotation');
        }

        //******************** Initialise ********************/
        //Responsibles (Users of Customer)
        $data['filter_responsible'] = $this->Customer_model->get('',"tblusers.userid, CONCAT(tblusers.name,' ',tblusers.surname) as name",
            array('tblusers'=>'tblusers.userid=tblcustomers.responsible')
        );
        $data['filter_responsible'] = dropdown($data['filter_responsible'],'userid','name');

        //Quotationstatus
        $data['filter_leadquotationstatus'] = $this->Quotationstatus_model->get();
        $data['filter_leadquotationstatus'] = dropdown($data['filter_leadquotationstatus'],'id','name');
        //******************** End Initialise ********************/

        $data['title'] = lang('page_leadquotations');
        $this->load->view('admin/leadquotations/manage', $data);
    }

    /* List all lead quotations by ajax */
    public function ajax($filter_responsible='',$filter_leadquotationstatus='')
    {
        //Filter By responsible, quotationstatus
        $params = array('filter_responsible'=>$filter_responsible,'filter_leadquotationstatus'=>$filter_leadquotationstatus);
        $this->app->get_table_data('leadquotations',$params);
    }

    /* List all documents by ajax */
    public function ajaxdocument($leadquotationnr)
    {
        $params['quotationid'] = $leadquotationnr;
        $this->app->get_table_data('leadquotationdocuments',$params);
    }

    /* Add/Edit Quotation */
    public function quotation($id='')
    {
        if(!$GLOBALS['leadquotation_permission']['create'] && !$GLOBALS['leadquotation_permission']['edit']){
            access_denied('quotation');
        }

        //******************** Initialise ********************/
        if($id>0){
            //Quotation
            $data['quotation'] = (array) $this->Leadquotation_model->get($id);
        }
        //******************** End Initialise ********************/

        //Submit Page
        if ($this->input->post()) {
            $post = $this->input->post();

            if(isset($data['quotation']['leadquotationnr'])){
                $response = $this->Leadquotation_model->update($post, $data['quotation']['leadquotationnr']);
                if (is_numeric($response) && $response>0) {

                    //History
                    $Action_data = array('actionname'=>'quotation', 'actionid'=>$response, 'actiontitle'=>'quotation_updated');
                    do_action_history($Action_data);

                    set_alert('success', sprintf(lang('updated_successfully'),lang('page_leadquotation')));
                    redirect(site_url('admin/leadquotations/detail/' . $data['quotation']['leadquotationnr']));
                    exit;
                }
                else{
                    set_alert('danger', $response);
                }
            }
            else{
                $response = $this->Leadquotation_model->add($post);
                if (is_numeric($response) && $response>0) {

                    //History
                    $Action_data = array('actionname'=>'quotation', 'actionid'=>$response, 'actiontitle'=>'quotation_added');
                    do_action_history($Action_data);

                    set_alert('success', sprintf(lang('added_successfully'),lang('page_leadquotation')));
                    redirect(site_url('admin/leadquotations/detail/' . $response));
                    exit;
                }
                else{
                    set_alert('danger', $response);
                }
            }

            //Initialise
            $leadquotationnr = '';
            if(isset($data['quotation'])){
                $leadquotationnr = $data['quotation']['leadquotationnr'];
            }
            $data['quotation'] = $post;
            $data['quotation']['leadquotationnr'] = $leadquotationnr;
        }


        //******************** Initialise ********************/
        //Quotationstatus
        $data['quotationstatus'] = $this->Quotationstatus_model->get();
        $data['quotationstatus'] = dropdown($data['quotationstatus'],'id','name');

        //Responsibles (Users of Customer)
        /*$data['responsibles'] = $this->Customer_model->get('',"tblusers.userid, CONCAT(tblusers.name,' ',tblusers.surname) as name",
            array('tblusers'=>'tblusers.userid=tblcustomers.responsible')
        );
        $data['responsibles'] = dropdown($data['responsibles'],'userid','name');*/

        //Customers
        //$data['customers'] = $this->Customer_model->get('',"tblcustomers.customernr, CONCAT(tblcustomers.name,' ',tblcustomers.surname) as name");
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

        //Quotation Products
        $leadquotationnr = isset($data['quotation']['leadquotationnr'])?$data['quotation']['leadquotationnr']:'';
        $data['quotationproducts'] = $this->Leadquotationproduct_model->get('','', array()," leadquotationnr='".$leadquotationnr."' ");
        //******************** End Initialise ********************/


        //Page Title
        if(isset($data['quotation']['leadquotationnr']) && $data['quotation']['leadquotationnr']>0){
            $data['title'] = lang('page_edit_quotation');

            if(get_user_role()=='customer' && $data['quotation']['customerid']!=get_user_id()){
                redirect(leadsite_url('admin/quotations'));
            }
            //- On the Dashboard he should only see Quotations which belongs to the User who is logged in. (Salesman)
            else if(get_user_role()=='user' && isset($GLOBALS['current_user']->userrole) && $GLOBALS['current_user']->userrole==3 && $data['quotation']['userid']!=get_user_id() && $data['quotation']['responsible']!=get_user_id()){
                redirect(site_url('admin/leadquotations'));
            }
        }
        else{
            $data['title'] = lang('page_create_leadquotation');
        }

        $data['leads'] = $this->Lead_model->get_lead_responsible();

        $this->load->view('admin/leadquotations/leadquotation', $data);
    }

    /* Detail Quotation */
    public function detail($id='')
    {
        if(!$GLOBALS['leadquotation_permission']['view'] && !$GLOBALS['leadquotation_permission']['view_own']){
            access_denied('quotation');
        }

        //******************** Initialise ********************/
        if($id>0){
            //Quotation
            $data['quotation'] = (array) $this->Leadquotation_model->get($id,"tblleadquotations.*, leads.leadnr_prefix as lead, CONCAT(responsible.name,' ',responsible.surname) as responsible, tblleadquotations.responsible as responsible_id, "
                    . " CONCAT(recommend.name,' ',recommend.surname) as recommend, "
                    . " tblleadquotations.leadquotationstatus,"
                    . " tblquotationstatus.name as quotationstatusname, "
                    . " currentdiscountlevel.discounttitle as currentdiscountlevel, "
                    . " newdiscountlevel.discounttitle as newdiscountlevel ",

                    array('tblusers as responsible'=>'responsible.userid=tblleadquotations.responsible',
                    'tblleads as leads'=>'leads.leadnr=tblleadquotations.leadnr',
                    'tblusers as recommend'=>'recommend.userid=tblleadquotations.recommend',
                    'tblquotationstatus'=>'tblquotationstatus.id=tblleadquotations.leadquotationstatus',
                    'tbldiscountlevels as currentdiscountlevel'=>'currentdiscountlevel.discountnr=tblleadquotations.currentdiscountlevel',
                    'tbldiscountlevels as newdiscountlevel'=>'newdiscountlevel.discountnr=tblleadquotations.newdiscountlevel')
            );
        }

        if(empty($data['quotation']['leadquotationnr'])){
            redirect(site_url('admin/leadquotations'));
        }

        if(get_user_role()=='customer' && $data['quotation']['customerid']!=get_user_id()){
            redirect(site_url('admin/leadquotations'));
        }
        //- On the Dashboard he should only see Quotations which belongs to the User who is logged in. (Salesman)
        else if(get_user_role()=='user' && isset($GLOBALS['current_user']->userrole) && $GLOBALS['current_user']->userrole==3 && $data['quotation']['userid']!=get_user_id() && $data['quotation']['responsible_id']!=get_user_id()){
            redirect(site_url('admin/leadquotations'));
        }
        //******************** End Initialise ********************/


        //******************** Initialise ********************/
        //Categories
        $data['categories'] = $this->Documentsetting_model->get('',''," active=1 ");
        $data['categories'] = dropdown($data['categories'],'categoryid','categoryname');

        //Remindersubjects
        $data['remindersubjects'] = $this->Remindersubject_model->get();
        $data['remindersubjects'] = dropdown($data['remindersubjects'],'id','name');

        //Quotation Products
        $data['quotationproducts'] = $this->Leadquotationproduct_model->get('','tblleadquotationproducts.*, tblvvlneu.name as vvlneu, '
                . " IF(tblleadquotationproducts.formula='A', currentratemobile.ratetitle, tblleadquotationproducts.currentratemobile) as currentratemobile,"
                . " IF(tblleadquotationproducts.formula='A', newratemobile.ratetitle, tblleadquotationproducts.newratemobile) as newratemobile, tblleadquotationproducts.newratemobile as newratemobile_id, "
                . " IF(tblleadquotationproducts.formula='A', currentoptionmobile.optiontitle, tblleadquotationproducts.currentoptionmobile) as currentoptionmobile,"
                . " IF(tblleadquotationproducts.formula='A', newoptionmobile.optiontitle, tblleadquotationproducts.newoptionmobile) as newoptionmobile,"
                . 'tblhardwares.hardwaretitle as hardware',

                array('tblvvlneu'=>'tblvvlneu.id=tblleadquotationproducts.vvlneu',
                    'tblratesmobile as currentratemobile'=>'currentratemobile.ratenr=tblleadquotationproducts.currentratemobile',
                    'tblratesmobile as newratemobile'=>'newratemobile.ratenr=tblleadquotationproducts.newratemobile',
                    'tbloptionsmobile as currentoptionmobile'=>'currentoptionmobile.optionnr=tblleadquotationproducts.currentoptionmobile',
                    'tbloptionsmobile as newoptionmobile'=>'newoptionmobile.optionnr=tblleadquotationproducts.newoptionmobile',
                    'tblhardwares'=>'tblhardwares.hardwarenr=tblleadquotationproducts.hardware'
                ),

            " tblleadquotationproducts.leadquotationnr='".$data['quotation']['leadquotationnr']."' "
        );
        //******************** End Initialise ********************/


        //******************** Initialise ********************/
        //Comments
        $data['comments'] = $this->Note_model->get("","tblnotes.*, CONCAT(tblusers.name,' ',tblusers.surname) as fullname",array('tblusers'=>'tblusers.userid=tblnotes.addedfrom')," tblnotes.rel_id='".$data['quotation']['leadquotationnr']."' AND tblnotes.rel_type='leadquotation' ","","tblnotes.id desc");
        //******************** End Initialise ********************/

        //Page Title
        $data['title'] = lang('page_detail_leadquotation');
        $this->load->view('admin/leadquotations/detail', $data);
    }

    /* Delete quotation */
    public function delete()
    {
        if(!$GLOBALS['leadquotation_permission']['delete'] || !$this->input->post('id')){
            access_denied('quotation');
        }

        $response = $this->Leadquotation_model->delete($this->input->post('id'));
        if ($response==1) {

            //History
            $Action_data = array('actionname'=>'leadquotation', 'actionid'=>$this->input->post('id'), 'actiontitle'=>'leadquotation_deleted');
            do_action_history($Action_data);

            set_alert('success', sprintf(lang('deleted_successfully'),lang('page_leadquotation')));
        }else{
            set_alert('danger', $response);
        }
        redirect(site_url('admin/leadquotations/'));
        exit;
    }

    /* Add a Comment by Ajax */
    public function addComment(){
        if ($this->input->post()) {
            $post = $this->input->post();
            $response = $this->Note_model->add($post);
            if (is_numeric($response) && $response>0) {

                //History
                $Action_data = array('actionname'=>'leadquotation', 'actionid'=>$post['rel_id'], 'actionsubid'=>$response, 'actiontitle'=>'quotation_comment_added');
                do_action_history($Action_data);

                echo json_encode(array('response'=>'success','message'=>sprintf(lang('added_successfully'),lang('page_quotationcomment'))));
            }else{
                echo json_encode(array('response'=>'error','message'=>$response));
            }
        }
    }

    /* Update a Comment by Ajax */
    public function editComment($id){
        if ($this->input->post()) {
            $post = $this->input->post();
            $response = $this->Note_model->update($post, $id);
            if (is_numeric($response) && $response>0) {

                //History
                $Action_data = array('actionname'=>'leadquotation', 'actionid'=>$post['rel_id'], 'actionsubid'=>$id, 'actiontitle'=>'leadquotation_comment_updated');
                do_action_history($Action_data);

                echo json_encode(array('response'=>'success','message'=>sprintf(lang('updated_successfully'),lang('page_quotationcomment'))));
            }else{
                echo json_encode(array('response'=>'error','message'=>$response));
            }
        }
        exit;
    }

    /* Delete a Comment by Ajax */
    public function deleteComment(){
        if($this->input->post('id')){
            $response = $this->Note_model->delete($this->input->post('id'));
            if ($response==1) {

                //History
                $Action_data = array('actionname'=>'leadquotation', 'actionid'=>$this->input->post('parentid'), 'actionsubid'=>$this->input->post('id'), 'actiontitle'=>'leadquotation_comment_deleted');
                do_action_history($Action_data);

                echo json_encode(array('response'=>'success','message'=>sprintf(lang('deleted_successfully'),lang('page_quotationcomment'))));
            }else{
                echo json_encode(array('response'=>'error','message'=>$response));
            }
        }
        exit;
    }

    /* Get Comments by Ajax */
    public function getComments($id){
        //******************** Initialise ********************/
        //Comments
        $data['comments'] = $this->Note_model->get("","tblnotes.*, CONCAT(tblusers.name,' ',tblusers.surname) as fullname",array('tblusers'=>'tblusers.userid=tblnotes.addedfrom')," tblnotes.rel_id='".$id."' AND tblnotes.rel_type='leadquotation' ","","tblnotes.id desc");
        //******************** End Initialise ********************/

        if(count($data['comments']) > 0) {
            $this->load->view('admin/leadquotations/quotations_comments_template', $data);
        }
    }

    /* Upload Dropzone file by Ajax */
    public function uploadDocuments($id){
        handle_leadquotation_attachments($id);
        exit;
    }

    /* Get Uploaded Documents by Ajax */
    public function getDocuments($id){
        //******************** Initialise ********************/
        //Quotation
        $data['quotation'] = (array) $this->Leadquotation_model->get($id);
        //******************** End Initialise ********************/

        if(count($data['quotation']['attachments']) > 0) {
            $this->load->view('admin/leadquotations/quotations_attachments_template', array('attachments'=>$data['quotation']['attachments']));
        }
    }

    /* Delete Document by Ajax */
    public function deleteDocument(){
        if($this->input->post('id')){
            $response = $this->Leadquotation_model->delete_quotation_attachment($this->input->post('id'));
            if ($response==1) {
                echo json_encode(array('response'=>'success','message'=>sprintf(lang('deleted_successfully'),lang('page_quotationdocument'))));
            }else{
                echo json_encode(array('response'=>'error','message'=>$response));
            }
        }
        exit;
    }

    /* Update Document Category by Ajax */
    public function updateDocumentCategory($id, $categoryid){
        if(isset($id)){
            $response = $this->Leadquotation_model->update_quotation_attachmentcategory($id, $categoryid);
            if ($response==1) {
                echo json_encode(array('response'=>'success','message'=>sprintf(lang('updated_successfully'),lang('page_quotationdocument'))));
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
        $path = get_upload_path_by_type('leadquotation') . $attachment->rel_id . '/' . $attachment->file_name;
        force_download($path, null);
    }

    /* Get Quotation by Ajax */
    public function getQuotation($id){

        if(!$GLOBALS['quotationtoassignment_permission']['create']){
            access_denied('quotationtoassignment');
        }

        //******************** Initialise ********************/
        if($id>0){
            //Quotation
            $data['quotation'] = (array) $this->Leadquotation_model->get($id);
        }
        //******************** End Initialise ********************/

        if(empty($data['quotation']['leadquotationnr'])){
            //redirect(site_url('admin/leadquotations'));
            return false;
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
        $data['customers'] = $this->Customer_model->get('',"tblcustomers.customernr, tblcustomers.company");
        $data['customers'] = dropdown($data['customers'],'customernr','company');

        //Recommends (POS)
        $data['recommends'] = $this->User_model->get('',"userid, CONCAT(name,' ',surname) as name"," userrole IN(6) ");
        $data['recommends'] = dropdown($data['recommends'],'userid','name');

        //Discount Level
        $data['discountlevels'] = $this->Discountlevel_model->get();
        $data['discountlevels'] = dropdown($data['discountlevels'],'discountnr','discounttitle');

        //VVL Neu
        $data['vvlneu'] = $this->Vvlneu_model->get();
        $data['vvlneu'] = dropdown($data['vvlneu'],'id','name');

        //Mobile Rate
        $data['mobilerates'] = $this->Ratemobile_model->get();
        $data['mobilerates'] = dropdown($data['mobilerates'],'ratenr','ratetitle');

        //Hardware
        $data['hardwares'] = $this->Hardware_model->get();
        $data['hardwares'] = dropdown($data['hardwares'],'hardwarenr','hardwaretitle');

        //Mobile Option
        $data['mobileoptions'] = $this->Optionmobile_model->get();
        $data['mobileoptions'] = dropdown($data['mobileoptions'],'optionnr','optiontitle');

        //Quotation Products
        $leadquotationnr = isset($data['quotation']['leadquotationnr'])?$data['quotation']['leadquotationnr']:'';
        $data['quotationproducts'] = $this->Leadquotationproduct_model->get('','', array()," leadquotationnr='".$leadquotationnr."' ");
        //******************** End Initialise ********************/

        if(count($data['quotation']) > 0) {
            $this->load->view('admin/leadquotations/addassignment', $data);
        }
    }

    /* Add New Assignment */
    public function addAssignment($id){

        if(!$GLOBALS['quotationtoassignment_permission']['create']){
            access_denied('quotationtoassignment');
        }

        //Submit Page
        if ($this->input->post()) {
            $post = $this->input->post();
            $post['quotationid'] = $id; //for Converted Reference from Quotation

            //Get Company Name from Customer Selected
            if(isset($post['customer'])){
                $rowC = (array) $this->Customer_model->get($post['customer'],'company');
                $post['company'] = $rowC['company'];
            }

            $response = $this->Assignment_model->add($post);
            if (is_numeric($response) && $response>0) {

                //History
                $Action_data = array('actionname'=>'assignment', 'actionid'=>$response, 'actiontitle'=>'assignment_updated');
                do_action_history($Action_data);

                //Change Status to Closed(Geschlossen)
                $data = array();
                $data['quotationstatus'] = 4;
                $this->Leadquotation_model->update($data, $id);

                //Copy Documents
                $this->copy_documents_quotationtoassignment($id, $response);

                set_alert('success', sprintf(lang('created_successfully'),lang('page_assignment')));
                echo json_encode(array('response'=>'success','message'=>sprintf(lang('created_successfully'),lang('page_assignment')),'redirect'=>base_url('admin/leadquotations/')));
            }else{
                echo json_encode(array('response'=>'error','message'=>$response,'redirect'=>base_url('admin/leadquotations/')));
            }
        }
        exit;
    }

    //Copy Documents
    public function copy_documents_quotationtoassignment($id, $quotid){
        $documents = $this->File_model->get('',''," rel_id='".$id."' AND rel_type='quotation' ");
        if(count($documents)>0){
            foreach($documents as $document){
                do_action('before_upload_assignment_attachment', $document['rel_id']);

                //Destination with create new folder
                $assignmentpath = get_upload_path_by_type('assignment') . $quotid . '/';
                _maybe_create_upload_path($assignmentpath);
                //$filename    = unique_filename($assignmentpath, $document['file_name']);
                $filename    = $document['file_name'];
                $newFilePath = $assignmentpath . $filename;

                //Source
                $quotationpath = get_upload_path_by_type('quotation') . $document['rel_id'] . '/';
                $tmpFilePath = $quotationpath . $filename;

                // Upload the file into the company uploads dir
                if (copy($tmpFilePath, $newFilePath)) {
                    $data = array();
                    $data[] = array(
                        'file_name' => $filename,
                        'filetype' => $document['filetype'],
                        'categoryid' => $document['categoryid']
                        );
                    $this->Assignment_model->add_attachment_to_database($quotid, $data, false, false);
                }
            }
        }
    }

    //Generate Reminder by Cronjob (Notification Email 1 Day before he set the date)
    public function generatereminder($id=''){
        //Reminder to Responsible
        $this->Leadquotation_model->sendReminder($id);
    }

    //Get Value for Mobile Rate 1 or Mobile Rate 2 Auto Calculation
    public function getMobileRateValue($id='', $discountlevel='', $formula=''){
        echo $this->Leadquotation_model->getMobileRateValue($id, $discountlevel, $formula);
        exit;
    }

    //Get Value for Mobile Option 1 or Mobile Option 2 Price
    public function getMobileOptionValue($id=''){
        echo $this->Leadquotation_model->getMobileOptionValue($id);
        exit;
    }

    //Print Quotation
    public function printquotation($id){

        //Quotation
        $data['quotation'] = (array) $this->Leadquotation_model->get($id,"tblleadquotations.*, CONCAT(responsible.name,' ',responsible.surname) as responsible, "
                . " CONCAT(recommend.name,' ',recommend.surname) as recommend, "
                . " responsible.street as responsible_street, "
                . " responsible.zipcode as responsible_zipcode, "
                . " responsible.city as responsible_city, "
                . " tblquotationstatus.name as quotationstatus, "
                . " currentdiscountlevel.discounttitle as currentdiscountlevel, "
                . " newdiscountlevel.discounttitle as newdiscountlevel ",

                array('tblusers as responsible'=>'responsible.userid=tblleadquotations.responsible',
                'tblusers as recommend'=>'recommend.userid=tblleadquotations.recommend',
                'tblquotationstatus'=>'tblquotationstatus.id=tblleadquotations.leadquotationstatus',
                'tbldiscountlevels as currentdiscountlevel'=>'currentdiscountlevel.discountnr=tblleadquotations.currentdiscountlevel',
                'tbldiscountlevels as newdiscountlevel'=>'newdiscountlevel.discountnr=tblleadquotations.newdiscountlevel')
        );
        if(empty($data['quotation']['leadquotationnr'])){
            redirect(site_url('admin/leadquotations'));
        }

        //Quotation Products
        $data['quotationproducts'] = $this->Leadquotationproduct_model->get('','tblleadquotationproducts.*, tblvvlneu.name as vvlneu, '
                . " IF(tblleadquotationproducts.formula='A', currentratemobile.ratetitle, tblleadquotationproducts.currentratemobile) as currentratemobile,"
                . " IF(tblleadquotationproducts.formula='A', newratemobile.ratetitle, tblleadquotationproducts.newratemobile) as newratemobile,"
                . " IF(tblleadquotationproducts.formula='A', currentoptionmobile.optiontitle, tblleadquotationproducts.currentoptionmobile) as currentoptionmobile,"
                . " IF(tblleadquotationproducts.formula='A', newoptionmobile.optiontitle, tblleadquotationproducts.newoptionmobile) as newoptionmobile,"
                . 'tblhardwares.hardwaretitle as hardware',

                array('tblvvlneu'=>'tblvvlneu.id=tblleadquotationproducts.vvlneu',
                    'tblratesmobile as currentratemobile'=>'currentratemobile.ratenr=tblleadquotationproducts.currentratemobile',
                    'tblratesmobile as newratemobile'=>'newratemobile.ratenr=tblleadquotationproducts.newratemobile',
                    'tbloptionsmobile as currentoptionmobile'=>'currentoptionmobile.optionnr=tblleadquotationproducts.currentoptionmobile',
                    'tbloptionsmobile as newoptionmobile'=>'newoptionmobile.optionnr=tblleadquotationproducts.newoptionmobile',
                    'tblhardwares'=>'tblhardwares.hardwarenr=tblleadquotationproducts.hardware'
                ),

            " tblleadquotationproducts.leadquotationnr='".$data['quotation']['leadquotationnr']."' "
        );

        //Footer Text
        $data = array_merge($data, $GLOBALS['currency_data'], $GLOBALS['company_data']);

        $data['data'] = $data;
        $this->Pdf_model->pdf_printleadquotation($data);
    }

    //Print Hardware Quotation
    public function printhardwarequotation($id){

        //Quotation
        $data['quotation'] = (array) $this->Leadquotation_model->get($id,"tblleadquotations.*, discountlevel.discounttitle as newdiscounttitle, "
                . " customer.company as customer_company, "
                . " customer.leadnr_prefix, "
                . " customer.street as customer_street, "
                . " customer.zipcode as customer_zipcode, "
                . " customer.city as customer_city, "

                . " CONCAT(responsible.name,' ',responsible.surname) as responsible, "
                . " responsible.email as responsible_email ",

                array('tblusers as responsible'=>'responsible.userid=tblleadquotations.responsible',
                'tblleads as customer'=>'customer.leadnr=tblleadquotations.leadnr',
                'tbldiscountlevels as discountlevel'=>'discountlevel.discountnr=tblleadquotations.newdiscountlevel')
        );
        if(empty($data['quotation']['leadquotationnr'])){
            redirect(site_url('admin/leadquotations'));
            exit;
        }

        //Quotation Products
        $data['quotationproducts'] = $this->Leadquotationproduct_model->get('','tblleadquotationproducts.*, tblvvlneu.name as vvlneu, '
                . " IF(tblleadquotationproducts.formula='A', currentratemobile.ratetitle, tblleadquotationproducts.currentratemobile) as currentratemobile,"
                . " IF(tblleadquotationproducts.formula='A', newratemobile.ratetitle, tblleadquotationproducts.newratemobile) as newratemobile,"
                . " IF(tblleadquotationproducts.formula='A', currentoptionmobile.optiontitle, tblleadquotationproducts.currentoptionmobile) as currentoptionmobile,"
                . " IF(tblleadquotationproducts.formula='A', newoptionmobile.optiontitle, tblleadquotationproducts.newoptionmobile) as newoptionmobile,"
                . " newratemobile.ratenr as newratenr, "
                . ' tblhardwares.hardwaretitle as hardware,'
                . ' tblhardwares.hardwareprice, '
                . ' tblsubs.name as subname',

                array('tblvvlneu'=>'tblvvlneu.id=tblleadquotationproducts.vvlneu',
                    'tblratesmobile as currentratemobile'=>'currentratemobile.ratenr=tblleadquotationproducts.currentratemobile',
                    'tblratesmobile as newratemobile'=>'newratemobile.ratenr=tblleadquotationproducts.newratemobile',
                    'tbloptionsmobile as currentoptionmobile'=>'currentoptionmobile.optionnr=tblleadquotationproducts.currentoptionmobile',
                    'tbloptionsmobile as newoptionmobile'=>'newoptionmobile.optionnr=tblleadquotationproducts.newoptionmobile',
                    'tblhardwares'=>'tblhardwares.hardwarenr=tblleadquotationproducts.hardware',
                    'tblsubs'=>'tblsubs.id=newratemobile.subn',
                ),

            " tblleadquotationproducts.formula='A' AND tblleadquotationproducts.leadquotationnr='".$data['quotation']['leadquotationnr']."' "
        );

        //Footer Text
        $data = array_merge($data, $GLOBALS['currency_data'], $GLOBALS['company_data']);

        $data['data'] = $data;
        $this->Pdf_model->pdf_printhardwareleadquotation($data);
    }

    //Print Consultation Protocol
    public function printconsultationprotocol($id){

        //Quotation
        $data['quotation'] = (array) $this->Leadquotation_model->get($id,"tblleadquotations.provider,tblleadquotations.leadquotationnr, customer.leadnr_prefix, "
                . " tblleadquotations.customerrequirements as customerrequirements,"
                . " customer.city as customer_city,"
                . " customer.company as customer_company, "
                // . " customer.contactperson as customer_contact_person, "
                . " customer.street as customer_street, "
                . " customer.zipcode as customer_zipcode, "
                . " customer.position as customer_position, "
                . " CONCAT(customer.surname,' ',customer.name) as customer_name, "

                . " CONCAT(responsible.surname,' ',responsible.name) as responsible, "
                . " responsible.email as responsible_email ",

                array('tblusers as responsible'=>'responsible.userid=tblleadquotations.responsible',
                'tblleads as customer'=>'customer.leadnr=tblleadquotations.leadnr')
        );
        if(empty($data['quotation']['leadquotationnr'])){
            redirect(site_url('admin/leadquotations'));
            exit;
        }

        //Quotation Products
        $data['quotationproducts'] = $this->Leadquotationproduct_model->get('','tblvvlneu.name as vvlneu',
                array('tblvvlneu'=>'tblvvlneu.id=tblleadquotationproducts.vvlneu'),
            " tblleadquotationproducts.leadquotationnr='".$data['quotation']['leadquotationnr']."' "
        );

        //Footer Text
        $data = array_merge($data, $GLOBALS['currency_data'], $GLOBALS['company_data']);

        $data['data'] = $data;
        $this->Pdf_model->pdf_printconsultationprotocollead($data);
    }

    //Print Invoice Protocol
    public function printinvoiceprotocol($id){

        //Quotation
        $data['quotation'] = (array) $this->Leadquotation_model->get($id,"tblleadquotations.leadquotationnr, customer.company as customer_company, "
                . " customer.street as customer_street, "
                . " customer.zipcode as customer_zipcode, "
                . " customer.city as customer_city, "
                // . " customer.contactperson as customer_contact_person, "
                . " customer.phone as customer_phone, "
                . " customer.leadnr as customer_registernr, ",
                // . " customer.districtcourt as customer_districtcourt ",

                array('tblleads as customer'=>'customer.leadnr=tblleadquotations.leadnr')
        );
        if(empty($data['quotation']['leadquotationnr'])){
            redirect(site_url('admin/leadquotations'));
            exit;
        }

        //Footer Text
        $data = array_merge($data, $GLOBALS['currency_data'], $GLOBALS['company_data']);

        $data['data'] = $data;
        $this->Pdf_model->pdf_printinvoiceprotocollead($data);
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

    //Generate Reminder by Cronjob of quotation three times (Status=Erstellt)
    public function generatereminder_quotation($id=''){
        //Reminder to Responsible
        $this->Leadquotation_model->sendReminder_quotation($id);
    }

    //Delete Quotation Product By Ajax
    public function deleteQuotationProduct(){
        $response = $this->Leadquotationproduct_model->delete($this->input->post('id'));
        if ($response==1) {
            //echo json_encode(array('response'=>'success','message'=>sprintf(lang('deleted_successfully'),lang('page_quotation_product')),'dataid'=>$this->input->post('id')));
            echo json_encode(array('response'=>'success','message'=>sprintf(lang('deleted_successfully'),lang('page_quotation_product')),'dataid'=>$this->input->post('parentid')));
        }else{
            //echo json_encode(array('response'=>'error','message'=>$response,'dataid'=>$this->input->post('id')));
            echo json_encode(array('response'=>'error','message'=>$response,'dataid'=>$this->input->post('parentid')));
        }
        exit;
    }

    public function download_product_csv(){

        //Initialize
        $data = array();
        $data['file_name'] = 'tblleadquotationproducts';
        $data['db_fields'] = $this->db->list_fields($data['file_name']);

        $data['not_importable'] = array('id','leadquotationnr','ultracard1','ultracard2','simcard_function_id','simcard_function_nm','simcard_function_qty');
        $data['sample_data'][] = array('Auto',rand(0,100),'VVL','Red Business XS','','','Red Business XS','',date('d.m.Y'),'iPhone 11 Pro Max 64GB','World Data','','World Data','',date('d.m.Y')); //NEW CODE 18 Apr 2018
        $data['sample_data'][] = array('Manual',rand(0,100),'Neu','Sample name',rand(0,100),'','Sample name',rand(0,100),date('d.m.Y'),'iPhone 11 Pro Max 64GB','Sample current option mobile',rand(0,100),'Sample new option mobile',rand(0,100),date('d.m.Y')); //NEW CODE 18 Apr 2018

        header("Pragma: public");
        header("Expires: 0");
        header('Content-Type: application/csv');
        header("Content-Disposition: attachment; filename=\"".$data['file_name']."_sample_import_file.csv\";");
        header("Content-Transfer-Encoding: binary");

        $_total_sample_fields = 0;
        $_data = '';
        foreach($data['db_fields'] as $field){
            if(in_array($field,$data['not_importable'])){continue;}
            $_data.=ucfirst($field).';';
            $_total_sample_fields++;
        }

        foreach ($data['sample_data'] as $key => $value) {
            $_data.="\n";
            for($f = 0;$f<$_total_sample_fields;$f++){
                $_data.=''.$value[$f].';';
            }
        }
        $_data.="\n";
        echo $_data;
        exit;
    }

    public function import_product_csv(){
        $responseData = array();
        $csvMimes = array('application/vnd.ms-excel','text/plain','text/csv','text/tsv');

        $data['file_name'] = 'tblleadquotationproducts';
        $data['db_fields'] = $this->db->list_fields($data['file_name']);
        $data['not_importable'] = array('id','leadquotationnr','ultracard1','ultracard2','simcard_function_id','simcard_function_nm','simcard_function_qty');

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
                    foreach($data['db_fields'] as $field){
                        if(in_array($field,$data['not_importable'])){continue;}
                        $line[$fkey] = trim($line[$fkey]);
                        switch ($field) {
                            case 'formula':
                                $add_data[$idx][$field] = (strtolower($line[$fkey])=='manual')?'M':'A';
                            break;

                            case 'vvlneu':
                                $add_data[$idx][$field] = '';
                                if ($line[$fkey]!='') {
                                    $temp = $this->Vvlneu_model->get('','id', array('name'=>$line[$fkey]));
                                    if (count($temp)) {
                                        $add_data[$idx][$field] = $temp[0]['id'];
                                    }
                                }
                            break;

                            case 'currentratemobile':
                            case 'newratemobile':
                                $add_data[$idx][$field] = $line[$fkey];
                                if ($add_data[$idx]['formula'] == 'A' && $line[$fkey] != '') {
                                    $temp = $this->Ratemobile_model->get('','ratenr,price','', array('ratetitle'=>$line[$fkey]));
                                    if (count($temp)) {
                                        $add_data[$idx][$field] = $temp[0]['ratenr'];
                                        $add_data[$idx][$field=='currentratemobile'?'value1':'value2'] = $temp[0]['price'];
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

                            case 'currentoptionmobile':
                            case 'newoptionmobile':
                                $data['mobileoptions'] = $this->Optionmobile_model->get();
                                $add_data[$idx][$field] = $line[$fkey];
                                if ($line[$fkey] != '') {
                                    $temp = $this->Optionmobile_model->get('','optionnr,price', array('optiontitle'=>$line[$fkey]));
                                    if (count($temp)) {
                                        $add_data[$idx][$field] = $temp[0]['optionnr'];
                                        $add_data[$idx][$field=='currentoptionmobile'?'value3':'value4'] = $temp[0]['price'];
                                    }
                                }
                            break;

                            default:
                                if($add_data[$idx]['formula'] == 'A' && in_array($field,array('value1','value2','value3','value4'))){
                                    $add_data[$idx][$field] = $add_data[$idx][$field];

                                } else {
                                    $add_data[$idx][$field] = $line[$fkey];
                                }
                                break;
                        }
                        $fkey++;
                    }
                    $idx++;
                }

                /*while(($line = fgetcsv($csvFile)) !== FALSE){
                    $fkey = 0;
                    foreach($data['db_fields'] as $field){
                        if(in_array($field,$data['not_importable'])){continue;}
                        $line[$fkey] = trim($line[$fkey]);
                        switch ($field) {
                            case 'formula':
                                $add_data[$idx][$field] = (strtolower($line[$fkey])=='manual')?'M':'A';
                            break;

                            case 'vvlneu':
                                $add_data[$idx][$field] = '';
                                if ($line[$fkey]!='') {
                                    $temp = $this->Vvlneu_model->get('','id', array('name'=>$line[$fkey]));
                                    if (count($temp)) {
                                        $add_data[$idx][$field] = $temp[0]['id'];
                                    }
                                }
                            break;

                            case 'currentratemobile':
                            case 'newratemobile':
                                $add_data[$idx][$field] = $line[$fkey];
                                if ($add_data[$idx]['formula'] == 'A' && $line[$fkey] != '') {
                                    $temp = $this->Ratemobile_model->get('','ratenr,price','', array('ratetitle'=>$line[$fkey]));
                                    if (count($temp)) {
                                        $add_data[$idx][$field] = $temp[0]['ratenr'];
                                        $add_data[$idx][$field=='currentratemobile'?'value1':'value2'] = $temp[0]['price'];
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

                            case 'currentoptionmobile':
                            case 'newoptionmobile':
                                $data['mobileoptions'] = $this->Optionmobile_model->get();
                                $add_data[$idx][$field] = $line[$fkey];
                                if ($line[$fkey] != '') {
                                    $temp = $this->Optionmobile_model->get('','optionnr,price', array('optiontitle'=>$line[$fkey]));
                                    if (count($temp)) {
                                        $add_data[$idx][$field] = $temp[0]['optionnr'];
                                        $add_data[$idx][$field=='currentoptionmobile'?'value3':'value4'] = $temp[0]['price'];
                                    }
                                }
                            break;

                            default:
                                if($add_data[$idx]['formula'] == 'A' && in_array($field,array('value1','value2','value3','value4'))){
                                    $add_data[$idx][$field] = $add_data[$idx][$field];

                                } else {
                                    $add_data[$idx][$field] = $line[$fkey];
                                }
                                break;
                        }
                        $fkey++;
                    }
                    $idx++;
                }*/
                $responseData = array('status'=>1,'add_data'=>$add_data);

            } else {
                $responseData = array('status'=>0,'message'=>lang('import_upload_failed'));
            }

        } else{
            $responseData = array('status'=>0,'message'=>lang('import_upload_failed'));
        }

        echo json_encode($responseData);
    }
}