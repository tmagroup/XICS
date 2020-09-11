<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Customers extends Admin_controller
{
    public function __construct()
    {
        parent::__construct();
        if(get_user_role()!='user' && current_url()!=base_url('admin/customers/profile')){
            redirect(site_url('admin/permission/denied/'));
        }
        $this->load->model('Customer_model');
        $this->load->model('User_model');
        $this->load->model('Customerprovidercompany_model');
        $this->load->model('Companysize_model');
        $this->load->model('Salutation_model');
        $this->load->model('Documentsetting_model');
        $this->load->model('Remindersubject_model');
        $this->load->model('Note_model');
        $this->load->model('File_model');
        $this->load->model('Reminder_model');

        $this->load->model('Quotation_model');
        $this->load->model('Quotationproduct_model');
        $this->load->model('Assignment_model');
        $this->load->model('Assignmentproduct_model');
        $this->load->model('Ticket_model');
        $this->load->model('Hardwareassignment_model');
        $this->load->model('Hardwareassignmentproduct_model');

        $this->load->model('Monitoringvalue_model');
    }

    /* List all customers */
    public function index()
    {
        if(!$GLOBALS['customer_permission']['view']){
            access_denied('customer');
        }

        //******************** Initialise ********************/
        //Responsibles (Salesmanager,Salesman or Admin)
        $data['filter_responsible'] = $this->User_model->get('',"userid, CONCAT(name,' ',surname) as name"," userrole IN(1,2,3) ");
        $data['filter_responsible'] = dropdown($data['filter_responsible'],'userid','name');
        //******************** End Initialise ********************/

        $data['title'] = lang('page_customers');
        $this->load->view('admin/customers/manage', $data);
    }

    /* List all customers by ajax */
    public function ajax($filter_responsible='')
    {
        //Filter By responsible
        $params = array('filter_responsible'=>$filter_responsible);
        $this->app->get_table_data('customers',$params);
    }

    /* List all documents by ajax */
    public function ajaxdocument($customernr)
    {
        $params['customerid'] = $customernr;
        $this->app->get_table_data('customerdocuments',$params);
    }

    /* List all documents by ajax */
    public function ajaxinternaldocument($customernr)
    {
        $params['customerid'] = $customernr;
        $this->app->get_table_data('customerinternaldocuments',$params);
    }

    /* Change Status */
    public function change_active($id='', $status=''){
        if($status==1){
            //History
            $Action_data = array('actionname'=>'customer', 'actionid'=>$id, 'actiontitle'=>'customer_activated');
            do_action_history($Action_data);
        }else{
            //History
            $Action_data = array('actionname'=>'customer', 'actionid'=>$id, 'actiontitle'=>'customer_deactivated');
            do_action_history($Action_data);
        }
        $this->db->query("UPDATE `tblcustomers` SET `active`='".$status."' WHERE customernr='".$id."'");
        exit;
    }

    /* List all quotations by ajax */
    public function ajaxQuotation($customer_id, $filter_responsible='', $filter_quotationstatus='')
    {
        //Filter By responsible, quotationstatus
        $params = array('customer_id'=>$customer_id, 'filter_responsible'=>$filter_responsible,'filter_quotationstatus'=>$filter_quotationstatus);
        $this->app->get_table_data('customer_quotations',$params);
    }

    /* List all assignments by ajax */
    public function ajaxAssignment($customer_id, $filter_responsible='', $filter_assignmentstatus='')
    {
        //Filter By responsible, assignmentstatus
        $params = array('customer_id'=>$customer_id, 'filter_responsible'=>$filter_responsible,'filter_assignmentstatus'=>$filter_assignmentstatus);
        $this->app->get_table_data('customer_assignments',$params);
    }

    /* List all tickets by ajax */
    public function ajaxTicket($customer_id, $filter_responsible='', $filter_ticketstatus='')
    {
        //Filter By responsible, ticketstatus
        $params = array('customer_id'=>$customer_id, 'filter_responsible'=>$filter_responsible,'filter_ticketstatus'=>$filter_ticketstatus);
        $this->app->get_table_data('customer_tickets',$params);
    }

    /* List all hardware assignments by ajax */
    public function ajaxHardwareassignment($customer_id, $filter_hardwareassignmentstatus='')
    {
        //Filter By hardwareassignmentstatus
        $params = array('customer_id'=>$customer_id, 'filter_hardwareassignmentstatus'=>$filter_hardwareassignmentstatus);
        $this->app->get_table_data('customer_hardwareassignments',$params);
    }

    /* Add/Edit Customer */
    public function customer($id='')
    {
        if(!$GLOBALS['customer_permission']['create'] && !$GLOBALS['customer_permission']['edit']){
            access_denied('customer');
        }

        //******************** Initialise ********************/
        if($id>0){
            //Customer
            $data['customer'] = (array) $this->Customer_model->get($id);
        }
        //******************** End Initialise ********************/

        //Submit Page
        if ($this->input->post()) {
            $post = $this->input->post();

            //Admin and Salesmanager can set Responsible
            if($GLOBALS['current_user']->userrole==1 || $GLOBALS['current_user']->userrole==2){
            }
            else{
                if(isset($post['responsible'])){
                    unset($post['responsible']);
                }
            }

            if(isset($data['customer']['customernr'])){
                $response = $this->Customer_model->update($post, $data['customer']['customernr']);
                if (is_numeric($response) && $response>0) {

                    //History
                    $Action_data = array('actionname'=>'customer', 'actionid'=>$response, 'actiontitle'=>'customer_updated');
                    do_action_history($Action_data);

                    handle_customer_profile_image_upload($data['customer']['customernr']);
                    set_alert('success', sprintf(lang('updated_successfully'),lang('page_customer')));
                    redirect(site_url('admin/customers/detail/' . $data['customer']['customernr']));
                }
                else{
                    set_alert('danger', $response);
                }
            }
            else{
                $response = $this->Customer_model->add($post);
                if (is_numeric($response) && $response>0) {

                    //History
                    $Action_data = array('actionname'=>'customer', 'actionid'=>$response, 'actiontitle'=>'customer_added');
                    do_action_history($Action_data);

                    handle_customer_profile_image_upload($response);
                    set_alert('success', sprintf(lang('added_successfully'),lang('page_customer')));
                    redirect(site_url('admin/customers/detail/' . $response));
                }
                else{
                    set_alert('danger', $response);
                }
            }

            //Initialise
            $customernr = '';
            if(isset($data['customer'])){
                $customernr = $data['customer']['customernr'];
            }
            $data['customer'] = $post;
            $data['customer']['customernr'] = $customernr;
        }


        //******************** Initialise ********************/
        //Responsibles (Salesmanager,Salesman or Admin)
        $data['responsibles'] = $this->User_model->get('',"userid, CONCAT(name,' ',surname) as name"," userrole IN(1,2,3) ");
        $data['responsibles'] = dropdown($data['responsibles'],'userid','name');

        //Recommends (POS)
        $data['recommends'] = $this->User_model->get('',"userid, CONCAT(name,' ',surname) as name"," userrole IN(6) ");
        $data['recommends'] = dropdown($data['recommends'],'userid','name');

        //Customerprovidercompanies
        $customernr = isset($data['customer']['customernr'])?$data['customer']['customernr']:'';
        $data['customerprovidercompanies'] = $this->Customerprovidercompany_model->get('',''," customernr='".$customernr."' ");

        //Companysizes
        $data['companysizes'] = $this->Companysize_model->get();
        $data['companysizes'] = dropdown($data['companysizes'],'id','name');

        //Salutations (Titles)
        $data['salutations'] = $this->Salutation_model->get();
        $data['salutations'] = dropdown($data['salutations'],'salutationid','name');

        //Remindersubjects
        $data['remindersubjects'] = $this->Remindersubject_model->get();
        $data['remindersubjects'] = dropdown($data['remindersubjects'],'id','name');

        //Categories
        $data['categories'] = $this->Documentsetting_model->get('',''," active=1 ");
        $data['categories'] = dropdown($data['categories'],'categoryid','categoryname');

        //Monitoringvalues (This selectboxvalues I need later.)
        $data['monitoringvalues'] = $this->Monitoringvalue_model->get('',"gval,CONCAT(gval,'%') as gval2");
        $data['monitoringvalues'] = dropdown($data['monitoringvalues'],'gval','gval2');
        //******************** End Initialise ********************/


        //Page Title
        if(isset($data['customer']['customernr']) && $data['customer']['customernr']>0){
            $data['title'] = lang('page_edit_customer');
        }
        else{
            $data['title'] = lang('page_create_customer');
        }


        $this->load->view('admin/customers/customer', $data);
    }

    /* Add/Edit Customer */
    public function profile()
    {
        //Get (User/Customer)
        $user_role = get_user_role();
        if($user_role!='customer'){
            redirect(site_url('admin/settings/profile/'));
        }else{
            redirect(site_url('admin/settings/profile/'));
        }

        $id = get_user_id();
        //******************** Initialise ********************/
        if($id>0){
            //Customer
            $data['customer'] = (array) $this->Customer_model->get($id);
        }
        //******************** End Initialise ********************/

        //Submit Page
        if ($this->input->post()) {
            $post = $this->input->post();

            $response = $this->Customer_model->update($post, $data['customer']['customernr']);
            if (is_numeric($response) && $response>0) {
                handle_customer_profile_image_upload($data['customer']['customernr']);
                set_alert('success', sprintf(lang('updated_successfully'),lang('page_profile_setting')));
                redirect(site_url('admin/customers/profile/'));
            }
            else{
                set_alert('danger', $response);
            }

            //Initialise
            $customernr = '';
            if(isset($data['customer'])){
                $customernr = $data['customer']['customernr'];
            }
            $data['customer'] = $post;
            $data['customer']['customernr'] = $customernr;
        }

        //Page Title
        $data['title'] = lang('page_profile_setting');
        $this->load->view('admin/customers/profile', $data);
    }

    /* Detail Customer */
    public function detail($id='')
    {

        if(!$GLOBALS['customer_permission']['view']){
            access_denied('customer');
        }

        //******************** Initialise ********************/
        if($id>0){
            //Customer
            $data['customer'] = (array) $this->Customer_model->get($id,"tblcustomers.*, CONCAT(responsible.name,' ',responsible.surname) as responsible, tblcustomers.responsible as responsible_id, "
                    . " CONCAT(recommend.name,' ',recommend.surname) as recommend, "
                    . " tblcompanysizes.name as companysize, "
                    . " tblsalutations.name as salutation, "
                    . " GROUP_CONCAT(tblcustomerprovidercompanies.providernr) as customerprovidercompanies ",

                    array('tblusers as responsible'=>'responsible.userid=tblcustomers.responsible',
                    'tblusers as recommend'=>'recommend.userid=tblcustomers.recommend',
                    'tblcompanysizes'=>'tblcompanysizes.id=tblcustomers.companysize',
                    'tblsalutations'=>'tblsalutations.salutationid=tblcustomers.salutation',
                    'tblcustomerprovidercompanies'=>'tblcustomerprovidercompanies.customernr=tblcustomers.customernr',
                    )
            );
        }

        if(empty($data['customer']['customernr'])){
            redirect(site_url('admin/customers'));
        }
        //******************** End Initialise ********************/


        //******************** Initialise ********************/
        //Categories
        $data['categories'] = $this->Documentsetting_model->get('',''," active=1 ");
        $data['categories'] = dropdown($data['categories'],'categoryid','categoryname');

        //Remindersubjects
        $data['remindersubjects'] = $this->Remindersubject_model->get();
        $data['remindersubjects'] = dropdown($data['remindersubjects'],'id','name');

        //Comments
        $data['comments'] = $this->Note_model->get("","tblnotes.*, CONCAT(tblusers.name,' ',tblusers.surname) as fullname",array('tblusers'=>'tblusers.userid=tblnotes.addedfrom')," tblnotes.rel_id='".$data['customer']['customernr']."' AND tblnotes.rel_type='customer' ","","tblnotes.id desc");
        //******************** End Initialise ********************/


        //Page Title
        $data['title'] = lang('page_detail_customer');
        $this->load->view('admin/customers/detail', $data);
    }

    /* Delete customer */
    public function delete()
    {
        if(!$GLOBALS['customer_permission']['delete'] || !$this->input->post('id')){
            access_denied('customer');
        }

        $response = $this->Customer_model->delete($this->input->post('id'));
        if ($response==1) {

            //History
            $Action_data = array('actionname'=>'customer', 'actionid'=>$this->input->post('id'), 'actiontitle'=>'customer_deleted');
            do_action_history($Action_data);

            set_alert('success', sprintf(lang('deleted_successfully'),lang('page_customer')));
        }else{
            set_alert('danger', $response);
        }
        redirect(site_url('admin/customers/'));
    }

    /* Delete Customer Provider Company Number by Ajax */
    public function deleteProviderCompany($id){
        $this->Customerprovidercompany_model->delete($id);
        exit;
    }

    /* Upload Dropzone file by Ajax */
    public function uploadDocuments($id){
        handle_customer_attachments($id);
        exit;
    }

    /* Upload Dropzone internal document file by Ajax */
    public function uploadInternalDocuments($id) {
        handle_customer_internal_attachments($id);
        exit;
    }

    /* Get Uploaded Documents by Ajax */
    public function getDocuments($id){
        //******************** Initialise ********************/
        //Customer
        $data['customer'] = (array) $this->Customer_model->get($id);
        //******************** End Initialise ********************/

        if(count($data['customer']['attachments']) > 0) {
            $this->load->view('admin/customers/customers_attachments_template', array('attachments'=>$data['customer']['attachments']));
        }
    }

    /* Delete Document by Ajax */
    public function deleteDocument(){
        if($this->input->post('id')){
            $response = $this->Customer_model->delete_customer_attachment($this->input->post('id'));
            if ($response==1) {
                echo json_encode(array('response'=>'success','message'=>sprintf(lang('deleted_successfully'),lang('page_customerdocument'))));
            }else{
                echo json_encode(array('response'=>'error','message'=>$response));
            }
        }
        exit;
    }

    /* Delete Internal Document by Ajax */
    public function deleteInternalDocument(){
        if($this->input->post('id')){
            $response = $this->Customer_model->delete_customer_internal_attachment($this->input->post('id'));
            if ($response==1) {
                echo json_encode(array('response'=>'success','message'=>sprintf(lang('deleted_successfully'),lang('page_customerinternaldocument'))));
            }else{
                echo json_encode(array('response'=>'error','message'=>$response));
            }
        }
        exit;
    }

    /* Download Document */
    public function downloadInternalDocument($attachmentid){
        $this->db->where('id', $attachmentid);
        $attachment = $this->db->get('tblfiles')->row();
        if (!$attachment) {
            die('No attachment found in database');
        }
        $path = get_upload_path_by_type('customerinternaldocument') . $attachment->rel_id . '/' . $attachment->file_name;
        force_download($path, null);
    }

    /* Update Document Category by Ajax */
    public function updateDocumentCategory($id, $categoryid){
        if(isset($id)){
            $response = $this->Customer_model->update_customer_attachmentcategory($id, $categoryid);
            if ($response==1) {
                echo json_encode(array('response'=>'success','message'=>sprintf(lang('updated_successfully'),lang('page_customerdocument'))));
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
        $path = get_upload_path_by_type('customer') . $attachment->rel_id . '/' . $attachment->file_name;
        force_download($path, null);
    }

    /* Add a Comment by Ajax */
    public function addComment(){
        if ($this->input->post()) {
            $post = $this->input->post();
            $response = $this->Note_model->add($post);
            if (is_numeric($response) && $response>0) {

                //History
                $Action_data = array('actionname'=>'customer', 'actionid'=>$post['rel_id'], 'actionsubid'=>$response, 'actiontitle'=>'customer_comment_added');
                do_action_history($Action_data);

                echo json_encode(array('response'=>'success','message'=>sprintf(lang('added_successfully'),lang('page_customercomment'))));
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
                $Action_data = array('actionname'=>'customer', 'actionid'=>$post['rel_id'], 'actionsubid'=>$id, 'actiontitle'=>'customer_comment_updated');
                do_action_history($Action_data);

                echo json_encode(array('response'=>'success','message'=>sprintf(lang('updated_successfully'),lang('page_customercomment'))));
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
                $Action_data = array('actionname'=>'customer', 'actionid'=>$this->input->post('parentid'), 'actionsubid'=>$this->input->post('id'), 'actiontitle'=>'customer_comment_deleted');
                do_action_history($Action_data);

                echo json_encode(array('response'=>'success','message'=>sprintf(lang('deleted_successfully'),lang('page_customercomment'))));
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
        $data['comments'] = $this->Note_model->get("","tblnotes.*, CONCAT(tblusers.name,' ',tblusers.surname) as fullname",array('tblusers'=>'tblusers.userid=tblnotes.addedfrom')," tblnotes.rel_id='".$id."' AND tblnotes.rel_type='customer' ","","tblnotes.id desc");
        //******************** End Initialise ********************/

        if(count($data['comments']) > 0) {
            $this->load->view('admin/customers/customers_comments_template', $data);
        }
    }

    //Generate Reminder by Cronjob (Notification Email 1 Day before he set the date)
    public function generatereminder($id='')
    {
        //Reminder to Responsible, Teamwork
        $this->Customer_model->sendReminder($id);
    }

    //Get Quotation Detail
    public function getQuotationDetail($id){
        //******************** Initialise ********************/
        //Quotation
        $data['quotation'] = (array) $this->Quotation_model->get($id,"tblquotations.*, CONCAT(customer.name,' ',customer.surname) as customer, CONCAT(responsible.name,' ',responsible.surname) as responsible, "
                . " CONCAT(recommend.name,' ',recommend.surname) as recommend, "
                . " tblquotations.quotationstatus,"
                . " tblquotationstatus.name as quotationstatusname, "
                . " currentdiscountlevel.discounttitle as currentdiscountlevel, "
                . " newdiscountlevel.discounttitle as newdiscountlevel ",

                array('tblusers as responsible'=>'responsible.userid=tblquotations.responsible',
                'tblcustomers as customer'=>'customer.customernr=tblquotations.customer',
                'tblusers as recommend'=>'recommend.userid=tblquotations.recommend',
                'tblquotationstatus'=>'tblquotationstatus.id=tblquotations.quotationstatus',
                'tbldiscountlevels as currentdiscountlevel'=>'currentdiscountlevel.discountnr=tblquotations.currentdiscountlevel',
                'tbldiscountlevels as newdiscountlevel'=>'newdiscountlevel.discountnr=tblquotations.newdiscountlevel')
        );
        //Quotation Products
        $data['quotationproducts'] = $this->Quotationproduct_model->get('','tblquotationproducts.*, tblvvlneu.name as vvlneu, '
                . " IF(tblquotationproducts.formula='A', currentratemobile.ratetitle, tblquotationproducts.currentratemobile) as currentratemobile,"
                . " IF(tblquotationproducts.formula='A', newratemobile.ratetitle, tblquotationproducts.newratemobile) as newratemobile,"
                . " IF(tblquotationproducts.formula='A', currentoptionmobile.optiontitle, tblquotationproducts.currentoptionmobile) as currentoptionmobile,"
                . " IF(tblquotationproducts.formula='A', newoptionmobile.optiontitle, tblquotationproducts.newoptionmobile) as newoptionmobile,"
                . 'tblhardwares.hardwaretitle as hardware',

                array('tblvvlneu'=>'tblvvlneu.id=tblquotationproducts.vvlneu',
                    'tblratesmobile as currentratemobile'=>'currentratemobile.ratenr=tblquotationproducts.currentratemobile',
                    'tblratesmobile as newratemobile'=>'newratemobile.ratenr=tblquotationproducts.newratemobile',
                    'tbloptionsmobile as currentoptionmobile'=>'currentoptionmobile.optionnr=tblquotationproducts.currentoptionmobile',
                    'tbloptionsmobile as newoptionmobile'=>'newoptionmobile.optionnr=tblquotationproducts.newoptionmobile',
                    'tblhardwares'=>'tblhardwares.hardwarenr=tblquotationproducts.hardware'
                ),

            " tblquotationproducts.quotationnr='".$data['quotation']['quotationnr']."' "
        );
        //******************** End Initialise ********************/
        $this->load->view('admin/customers/quotation_detail', $data);
    }

    //Get Assignment Detail
    public function getAssignmentDetail($id){
        //******************** Initialise ********************/
        //Assignment
        $data['assignment'] = (array) $this->Assignment_model->get($id,"tblassignments.*, CONCAT(customer.name,' ',customer.surname) as customer, CONCAT(responsible.name,' ',responsible.surname) as responsible, "
                . " CONCAT(recommend.name,' ',recommend.surname) as recommend, "
                . " tblassignmentstatus.name as assignmentstatus, "
                . " newdiscountlevel.discounttitle as newdiscountlevel ",

                array('tblusers as responsible'=>'responsible.userid=tblassignments.responsible',
                'tblcustomers as customer'=>'customer.customernr=tblassignments.customer',
                'tblusers as recommend'=>'recommend.userid=tblassignments.recommend',
                'tblassignmentstatus'=>'tblassignmentstatus.id=tblassignments.assignmentstatus',
                'tbldiscountlevels as newdiscountlevel'=>'newdiscountlevel.discountnr=tblassignments.newdiscountlevel')
        );
        //Assignment Products
        $data['assignmentproducts'] = $this->Assignmentproduct_model->get('','tblassignmentproducts.*, tblvvlneu.name as vvlneu, '
                . " newratemobile.ratetitle as newratemobile,"
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
        $this->load->view('admin/customers/assignment_detail', $data);
    }

    //Get Ticket Detail
    public function getTicketDetail($id){
        //Ticket
        $data['ticket'] = (array) $this->Ticket_model->get($id,"tbltickets.*, CONCAT(customer.name,' ',customer.surname) as customer, CONCAT(responsible.name,' ',responsible.surname) as responsible, "
                . " tbltickets.ticketstatus,"
                . " tblticketstatus.name as ticketstatusname, "
                . " (SELECT GROUP_CONCAT(CONCAT(`name`,' ',surname)) FROM tblusers WHERE FIND_IN_SET(userid, tbltickets.teamwork)) as teamwork ",

                array('tblusers as responsible'=>'responsible.userid=tbltickets.responsible',
                'tblcustomers as customer'=>'customer.customernr=tbltickets.customer',
                'tblticketstatus'=>'tblticketstatus.id=tbltickets.ticketstatus',
                )
        );
        $this->load->view('admin/customers/ticket_detail', $data);
    }

    //Get Hardware Assignment Detail
    public function getHardwareAssignmentDetail($id){
        //Hardwareassignment
        $data['hardwareassignment'] = (array) $this->Hardwareassignment_model->get($id,"tblhardwareassignments.*, CONCAT(customer.name,' ',customer.surname) as customer, CONCAT(responsible.name,' ',responsible.surname) as responsible, "
                . " tblhardwareassignmentstatus.name as hardwareassignmentstatus, ",

                array('tblusers as responsible'=>'responsible.userid=tblhardwareassignments.responsible',
                'tblcustomers as customer'=>'customer.customernr=tblhardwareassignments.customer',
                'tblhardwareassignmentstatus'=>'tblhardwareassignmentstatus.id=tblhardwareassignments.hardwareassignmentstatus')
        );
        //Hardware Assignment Products
        $data['hardwareassignmentproducts'] = $this->Hardwareassignmentproduct_model->get('','tblhardwareassignmentproducts.*, '
                . " newratemobile.ratetitle as newratemobile,"
                . ' tblhardwares.hardwaretitle as hardware,'
                . " stockhardware.hardwaretitle as stockhardwaretitle",

                array('tblratesmobile as newratemobile'=>'newratemobile.ratenr=tblhardwareassignmentproducts.newratemobile',
                    'tblhardwares'=>'tblhardwares.hardwarenr=tblhardwareassignmentproducts.hardware',
                    'tblhardwareinputproducts'=>'tblhardwareinputproducts.id=tblhardwareassignmentproducts.stockhardware',
                    'tblhardwares as stockhardware'=>'stockhardware.hardwarenr=tblhardwareinputproducts.hardware'
                ),

            " tblhardwareassignmentproducts.hardwareassignmentnr='".$data['hardwareassignment']['hardwareassignmentnr']."' "
        );
        $this->load->view('admin/customers/hardwareassignment_detail', $data);
    }
}