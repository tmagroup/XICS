<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Hardwareassignments extends Admin_controller
{
    public function __construct()
    {
        parent::__construct();
        /*if(get_user_role()!='user'){
            redirect(site_url('admin/permission/denied/'));
        }*/
        $this->load->model('Hardwareassignment_model');
        $this->load->model('Hardwareassignmentproduct_model');
        $this->load->model('Hardwareassignmentstatus_model');
        $this->load->model('Customer_model');
        $this->load->model('Documentsetting_model');
        $this->load->model('Remindersubject_model');
        $this->load->model('Hardwareinput_model');
        $this->load->model('Hardwareinputproduct_model');
        $this->load->model('Ratemobile_model');
        $this->load->model('Hardware_model');
        $this->load->model('Pdf_model');
        $this->load->model('Hardwareassignmentshippingslip_model');
        $this->load->model('Hardwareassignmentshippingslipproduct_model');
        $this->load->model('Hardwareassignmentinvoice_model');
        $this->load->model('Hardwareassignmentinvoiceproduct_model');
    }

    /* List all hardwareassignments */
    public function index()
    {
        if(!$GLOBALS['hardwareassignment_permission']['view'] && !$GLOBALS['hardwareassignment_permission']['view_own']){
            access_denied('hardwareassignment');
        }

        //******************** Initialise ********************/
        //Hardwareassignmentstatus
        $data['filter_hardwareassignmentstatus'] = $this->Hardwareassignmentstatus_model->get();
        $data['filter_hardwareassignmentstatus'] = dropdown($data['filter_hardwareassignmentstatus'],'id','name');
        //******************** End Initialise ********************/

        $data['title'] = lang('page_hardwareassignments');
        $this->load->view('admin/hardwareassignments/manage', $data);
    }

    /* List all hardwareassignments by ajax */
    public function ajax($filter_hardwareassignmentstatus='')
    {
        //Filter By Hardwareassignmentstatus
        $params = array('filter_hardwareassignmentstatus'=>$filter_hardwareassignmentstatus);
        $this->app->get_table_data('hardwareassignments',$params);
    }

    /* List all documents by ajax */
    public function ajaxdocument($hardwareassignmentnr)
    {
        $params['hardwareassignmentid'] = $hardwareassignmentnr;
        $this->app->get_table_data('hardwareassignmentdocuments',$params);
    }

    /* Add/Edit Hardwareassignment */
    public function hardwareassignment($id)
    {
        if(!$GLOBALS['hardwareassignment_permission']['edit']){
            access_denied('hardwareassignment');
        }

        //******************** Initialise ********************/
        if($id>0){
            //Hardwareassignment
            $join = array('tblcustomers'=>'tblcustomers.customernr=tblhardwareassignments.company');
            $data['hardwareassignment'] = (array) $this->Hardwareassignment_model->get($id, 'tblhardwareassignments.*, tblcustomers.company', $join);
        }

        if(empty($data['hardwareassignment']['hardwareassignmentnr'])){
            redirect(site_url('admin/hardwareassignments'));
        }
        //******************** End Initialise ********************/

        //Submit Page
        if ($this->input->post()) {
            $post = $this->input->post();

            /*Notice: Only all fields are filled out completty in one Hardware-Assignment
             * HardwareassignmentStatus and the row “Shipped” is set=1 in all positions of the
             * HardwareAssignment could be set to  HardwareAssignmentStatus = “Erledigt”*/
            //$post['shipped'] = 1;
            //$post['hardwareassignmentstatus'] = 3; //Erledigt

            $response = $this->Hardwareassignment_model->update($post, $data['hardwareassignment']['hardwareassignmentnr']);
            if (is_numeric($response) && $response>0) {

                //History
                $Action_data = array('actionname'=>'hardwareassignment', 'actionid'=>$response, 'actiontitle'=>'hardwareassignment_updated');
                do_action_history($Action_data);

                set_alert('success', sprintf(lang('updated_successfully'),lang('page_hardwareassignment')));
                redirect(site_url('admin/hardwareassignments/'));
                exit;
            }
            else{
                set_alert('danger', $response);
            }

            //Initialise
            $hardwareassignmentnr = '';
            if(isset($data['hardwareassignment'])){
                $hardwareassignmentnr = $data['hardwareassignment']['hardwareassignmentnr'];
            }
            $data['hardwareassignment'] = $post;
            $data['hardwareassignment']['hardwareassignmentnr'] = $hardwareassignmentnr;
        }


        //******************** Initialise ********************/
        //Customers
        $data['customers'] = $this->Customer_model->get('',"tblcustomers.customernr, CONCAT(tblcustomers.name,' ',tblcustomers.surname) as name");
        $data['customers'] = dropdown($data['customers'],'customernr','name');

        //Hardwareassignmentstatus
        $data['hardwareassignmentstatus'] = $this->Hardwareassignmentstatus_model->get();
        $data['hardwareassignmentstatus'] = dropdown($data['hardwareassignmentstatus'],'id','name');

        //Mobile Rate
        $data['mobilerates'] = $this->Ratemobile_model->get();
        $data['mobilerates'] = dropdown($data['mobilerates'],'ratenr','ratetitle');

        //Hardware
        $data['hardwares'] = $this->Hardware_model->get();
        $data['hardwares'] = dropdown($data['hardwares'],'hardwarenr','hardwaretitle');

        //Remindersubjects
        $data['remindersubjects'] = $this->Remindersubject_model->get();
        $data['remindersubjects'] = dropdown($data['remindersubjects'],'id','name');

        //Categories
        $data['categories'] = $this->Documentsetting_model->get('',''," active=1 ");
        $data['categories'] = dropdown($data['categories'],'categoryid','categoryname');

        //Assignment Products
        $hardwareassignmentnr = isset($data['hardwareassignment']['hardwareassignmentnr'])?$data['hardwareassignment']['hardwareassignmentnr']:'';
        $data['hardwareassignmentproducts'] = $this->Hardwareassignmentproduct_model->get('','tblhardwareassignmentproducts.*', array()," hardwareassignmentnr='".$hardwareassignmentnr."' ");
        //******************** End Initialise ********************/

        //Page Title
        $data['title'] = lang('page_edit_hardwareassignment');
        $this->load->view('admin/hardwareassignments/hardwareassignment', $data);
    }

    /* Detail Hardwareassignment */
    public function detail($id='')
    {
        if(!$GLOBALS['hardwareassignment_permission']['view'] && !$GLOBALS['hardwareassignment_permission']['view_own']){
            access_denied('hardwareassignment');
        }

        //******************** Initialise ********************/
        if($id>0){
            //Hardwareassignment
            /*$data['hardwareassignment'] = (array) $this->Hardwareassignment_model->get($id,"tblhardwareassignments.*, CONCAT(customer.name,' ',customer.surname) as customer, CONCAT(responsible.name,' ',responsible.surname) as responsible,  "
                    . " hardware.hardwaretitle,"
                    . " newratemobile.ratetitle as newratemobile,"
                    . " stockhardware.hardwaretitle as stockhardwaretitle,"
                    . " tblhardwareassignmentstatus.name as hardwareassignmentstatus ",

                    array('tblhardwares as hardware'=>'hardware.hardwarenr=tblhardwareassignments.hardware',
                    'tblratesmobile as newratemobile'=>'newratemobile.ratenr=tblhardwareassignments.newratemobile',
                    'tblhardwareinputproducts'=>'tblhardwareinputproducts.id=tblhardwareassignments.stockhardware',
                    'tblhardwares as hardware'=>'hardware.hardwarenr=tblhardwareassignments.hardware',
                    'tblhardwares as stockhardware'=>'stockhardware.hardwarenr=tblhardwareinputproducts.hardware',
                    'tblhardwareassignmentstatus'=>'tblhardwareassignmentstatus.id=tblhardwareassignments.hardwareassignmentstatus')
            );*/

            //Hardwareassignment
            $data['hardwareassignment'] = (array) $this->Hardwareassignment_model->get($id,"tblhardwareassignments.*, CONCAT(customer.name,' ',customer.surname) as customer, tblhardwareassignments.customer as customerid, CONCAT(responsible.name,' ',responsible.surname) as responsible, "
                    . " tblhardwareassignmentstatus.name as hardwareassignmentstatus, customer.company as company, ",

                    array('tblusers as responsible'=>'responsible.userid=tblhardwareassignments.responsible',
                    'tblcustomers as customer'=>'customer.customernr=tblhardwareassignments.customer',
                    'tblhardwareassignmentstatus'=>'tblhardwareassignmentstatus.id=tblhardwareassignments.hardwareassignmentstatus')
            );
        }

        if(empty($data['hardwareassignment']['hardwareassignmentnr'])){
            redirect(site_url('admin/hardwareassignments'));
        }

        if(get_user_role()=='customer' && $data['hardwareassignment']['customerid']!=get_user_id()){
            redirect(site_url('admin/assignments'));
        }
        //******************** End Initialise ********************/


        //******************** Initialise ********************/
        //Categories
        $data['categories'] = $this->Documentsetting_model->get('',''," active=1 ");
        $data['categories'] = dropdown($data['categories'],'categoryid','categoryname');

        //Remindersubjects
        $data['remindersubjects'] = $this->Remindersubject_model->get();
        $data['remindersubjects'] = dropdown($data['remindersubjects'],'id','name');

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
        //******************** End Initialise ********************/


        //Page Title
        $data['title'] = lang('page_detail_hardwareassignment');
        $this->load->view('admin/hardwareassignments/detail', $data);
    }

    /* Delete hardwareassignment */
    public function delete()
    {
        if(!$GLOBALS['hardwareassignment_permission']['delete'] || !$this->input->post('id')){
            access_denied('hardwareassignment');
        }

        $response = $this->Hardwareassignment_model->delete($this->input->post('id'));
        if ($response==1) {

            //History
            $Action_data = array('actionname'=>'hardwareassignment', 'actionid'=>$this->input->post('id'), 'actiontitle'=>'hardwareassignment_deleted');
            do_action_history($Action_data);

            set_alert('success', sprintf(lang('deleted_successfully'),lang('page_hardwareassignment')));
        }else{
            set_alert('danger', $response);
        }
        redirect(site_url('admin/hardwareassignments/'));
        exit;
    }

    	/* Upload Dropzone file by Ajax */
    public function uploadDocuments($id){
        handle_hardwareassignment_attachments($id);
        exit;
    }

    /* Get Uploaded Documents by Ajax */
    public function getDocuments($id){
        //******************** Initialise ********************/
        //Hardwareassignment
        $data['hardwareassignment'] = (array) $this->Hardwareassignment_model->get($id);
        //******************** End Initialise ********************/

        if(count($data['hardwareassignment']['attachments']) > 0) {
            $this->load->view('admin/hardwareassignments/hardwareassignments_attachments_template', array('attachments'=>$data['hardwareassignment']['attachments']));
        }
    }

    /* Delete Document by Ajax */
    public function deleteDocument(){
        if($this->input->post('id')){
            $response = $this->Hardwareassignment_model->delete_hardwareassignment_attachment($this->input->post('id'));
            if ($response==1) {
                echo json_encode(array('response'=>'success','message'=>sprintf(lang('deleted_successfully'),lang('page_hardwareassignmentdocument'))));
            }else{
                echo json_encode(array('response'=>'error','message'=>$response));
            }
        }
        exit;
    }

    /* Update Document Category by Ajax */
    public function updateDocumentCategory($id, $categoryid){
        if(isset($id)){
            $response = $this->Hardwareassignment_model->update_hardwareassignment_attachmentcategory($id, $categoryid);
            if ($response==1) {
                echo json_encode(array('response'=>'success','message'=>sprintf(lang('updated_successfully'),lang('page_hardwareassignmentdocument'))));
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
        $path = get_upload_path_by_type('hardwareassignment') . $attachment->rel_id . '/' . $attachment->file_name;
        force_download($path, null);
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

    //Generate Reminder by Cronjob (Notification Email 1 Day before he set the date)
    public function generatereminder($id=''){
        //Reminder to Responsible
        $this->Hardwareassignment_model->sendReminder($id);
    }

    /* Get Stock Hardware Input by Ajax */
    public function getStockHardwares($hardware, $hardwareassignmentnr){
        $data['hardwareinput'] = $this->Hardwareinputproduct_model->get('',"tblhardwareinputproducts.id as id, CONCAT(tblhardwares.hardwaretitle,' (',tblhardwareinputproducts.seriesnr,')') as name ",
                array('tblhardwareinputs'=>'tblhardwareinputs.hardwareinputnr=tblhardwareinputproducts.hardwareinputnr',
                'tblhardwares'=>'tblhardwares.hardwarenr=tblhardwareinputproducts.hardware'),
                "tblhardwareinputproducts.hardware='".$hardware."' "
                . " AND tblhardwareinputproducts.id NOT IN(SELECT stockhardware FROM tblhardwareassignmentproducts as t1 JOIN tblhardwareinputproducts as t2 ON t1.stockhardware = t2.id WHERE t1.hardwareassignmentnr!='".$hardwareassignmentnr."') "
        );

        $data_array = dropdown($data['hardwareinput'],'id','name');
        echo json_encode($data_array);
        exit;
    }

    /* Get Stock Hardware Input Series No by Ajax */
    public function getStockHardwareSeriesnr($stockhardware){
        $data['hardwareinput'] = $this->Hardwareinputproduct_model->get($stockhardware,"seriesnr");
        if(isset($data['hardwareinput']->seriesnr)){
            echo $data['hardwareinput']->seriesnr;
        }else{
            echo '';
        }
        exit;
    }

    /* Print Delivery Note */
    public function printdeliverynote($id){
        //Hardware Assignment
        $data['hardwareassignment'] = (array) $this->Hardwareassignment_model->get($id,"tblhardwareassignments.*, "
                . " customer.salutation as customer_salutation, "
                . " customer.name as customer_name, "
                . " customer.surname as customer_surname, "
                . " customer.company as customer_company, "
                . " customer.customernr_prefix, "
                . " customer.street as customer_street, "
                . " customer.zipcode as customer_zipcode, "
                . " customer.city as customer_city, "
                . " customer.email as customer_email, "

                . " CONCAT(responsible.name,' ',responsible.surname) as responsible, "
                . " responsible.email as responsible_email ",

                array('tblusers as responsible'=>'responsible.userid=tblhardwareassignments.responsible',
                'tblcustomers as customer'=>'customer.customernr=tblhardwareassignments.customer')
        );
        if(empty($data['hardwareassignment']['hardwareassignmentnr'])){
            redirect(site_url('admin/hardwareassignments'));
            exit;
        }

        //Hardware Assignment Products
        $data['hardwareassignmentproducts'] = $this->Hardwareassignmentproduct_model->get('','tblhardwareassignmentproducts.*, '
               . " newratemobile.ratetitle as newratemobile,"
                . ' tblhardwares.hardwaretitle as hardware',

                array('tblratesmobile as newratemobile'=>'newratemobile.ratenr=tblhardwareassignmentproducts.newratemobile',
                    'tblhardwares'=>'tblhardwares.hardwarenr=tblhardwareassignmentproducts.hardware'
                ),

            " tblhardwareassignmentproducts.shipped=0 AND tblhardwareassignmentproducts.shippingnr!='' AND tblhardwareassignmentproducts.hardwareassignmentnr='".$data['hardwareassignment']['hardwareassignmentnr']."' "
        );
        $hardwareassignmentnr = $data['hardwareassignment']['hardwareassignmentnr'];



        //Hardware Assignment Products3
        $data['hardwareassignmentproducts3'] = $this->Hardwareassignmentproduct_model->get('','tblhardwareassignmentproducts.*, '
               . " newratemobile.ratetitle as newratemobile,"
                . ' tblhardwares.hardwaretitle as hardware',

                array('tblratesmobile as newratemobile'=>'newratemobile.ratenr=tblhardwareassignmentproducts.newratemobile',
                    'tblhardwares'=>'tblhardwares.hardwarenr=tblhardwareassignmentproducts.hardware'
                ),

            " (tblhardwareassignmentproducts.shippingnr='' OR ISNULL(tblhardwareassignmentproducts.shippingnr)) AND tblhardwareassignmentproducts.hardwareassignmentnr='".$data['hardwareassignment']['hardwareassignmentnr']."' "
        );


        /***************************************************************************************/
        //Hardware Assignment Products2
        $data['hardwareassignmentproducts2'] = $this->Hardwareassignmentproduct_model->get('','tblhardwareassignmentproducts.*, '
                . " newratemobile.ratetitle as newratemobile,"
                . ' tblhardwares.hardwaretitle as hardware',

                array('tblratesmobile as newratemobile'=>'newratemobile.ratenr=tblhardwareassignmentproducts.newratemobile',
                    'tblhardwares'=>'tblhardwares.hardwarenr=tblhardwareassignmentproducts.hardware'
                ),

            " tblhardwareassignmentproducts.shipped=0 AND tblhardwareassignmentproducts.hardwareassignmentnr='".$data['hardwareassignment']['hardwareassignmentnr']."' "
        );
        /***************************************************************************************/

        //Check If all All product shipped
        if($data['hardwareassignmentproducts3']){
            set_alert('info', lang('page_lb_delivery_hardware_notfound'));
            redirect(site_url('admin/hardwareassignments/'));
        }

        if(!$data['hardwareassignmentproducts']){
            set_alert('info', lang('page_lb_delivery_already_shipped'));
            redirect(site_url('admin/hardwareassignments/'));
        }


        //Generate Hardware Assignment Shipping Slip No
        $dataShipping = array('hardwareassignmentnr' => $data['hardwareassignment']['hardwareassignmentnr'],
            'customer_salutation' => $data['hardwareassignment']['customer_salutation'],
            'customer_name' => $data['hardwareassignment']['customer_name'],
            'customer_surname' => $data['hardwareassignment']['customer_surname'],
            'customer_company' => $data['hardwareassignment']['customer_company'],
            'customernr_prefix' => $data['hardwareassignment']['customernr_prefix'],
            'customer_street' => $data['hardwareassignment']['customer_street'],
            'customer_zipcode' => $data['hardwareassignment']['customer_zipcode'],
            'customer_city' => $data['hardwareassignment']['customer_city'],
            'customer_email' => $data['hardwareassignment']['customer_email'],
            'responsible' => $data['hardwareassignment']['responsible'],
            'responsible_email' => $data['hardwareassignment']['responsible_email']
        );
        $shippingslipnr = $this->Hardwareassignmentshippingslip_model->add($dataShipping);
        $rowfield = (array)$this->Hardwareassignmentshippingslip_model->get($shippingslipnr,"shippingslipnr,shippingslipnr_prefix,DATE_FORMAT(created,'%Y-%m-%d') as created");

        if(isset($rowfield['shippingslipnr'])){
            $data['shippingslip'] = $rowfield;

            //History
            $Action_data = array('actionname'=>'deliverynote', 'actionid'=>$rowfield['shippingslipnr'], 'actiontitle'=>'deliverynote_added');
            do_action_history($Action_data);

            //Check If any field is not fill out so Title could be change of PDF
            $title2 = false;
            foreach($data['hardwareassignmentproducts2'] as $hardwareassignmentproduct){
                if(!$hardwareassignmentproduct['stockhardware'] || !$hardwareassignmentproduct['seriesnr'] || !$hardwareassignmentproduct['shippingnr']){
                    $title2 = true;
                }
                if($hardwareassignmentproduct['shippingnr']!=""){
                    //During generating this PDF it should set Shipped = 1 by the Positions which will taken into the PDF.
                    $dataHardwareAssignmentProduct = array('shipped'=>1);
                    $this->Hardwareassignmentproduct_model->update($dataHardwareAssignmentProduct,$hardwareassignmentproduct['id']);

                    //Should save all shipping info in generate table
                    $dataShippingProduct = array(
                        'productpositionid' => $hardwareassignmentproduct['id'],
                        'shippingslipnr' => $rowfield['shippingslipnr'],
                        'simnr' => $hardwareassignmentproduct['simnr'],
                        'mobilenr' => $hardwareassignmentproduct['mobilenr'],
                        'newratemobile' => $hardwareassignmentproduct['newratemobile'],
                        'hardware' => $hardwareassignmentproduct['hardware'],
                        'seriesnr' => $hardwareassignmentproduct['seriesnr'],
                        'shippingnr' => $hardwareassignmentproduct['shippingnr'],
                        'hardwarevalue' => $hardwareassignmentproduct['hardwarevalue']
                    );
                    $shipnewid = $this->Hardwareassignmentshippingslipproduct_model->add($dataShippingProduct);

                }
            }
            $data['shippingslip_type'] = $title2?2:1;

            //Update Shipping Type
            $dataShipping = array('shippingslip_type'=>$data['shippingslip_type']);
            $this->Hardwareassignmentshippingslip_model->update($dataShipping, $rowfield['shippingslipnr']);

            //Footer Text
            $data = array_merge($data, $GLOBALS['currency_data'], $GLOBALS['company_data']);
            $data['data'] = $data;

            $this->Pdf_model->pdf_printdeliverynote($data);
        }
        else{
            set_alert('error', sprintf(lang('failed'), lang('page_lb_print_deliverynote')));
            redirect(site_url('admin/hardwareassignments/'));
        }
    }

    /* Print Hardware Invoice */
    public function printhardwareinvoice($id){
        //Hardwareassignment
        $data['hardwareassignment'] = (array) $this->Hardwareassignment_model->get($id,"tblhardwareassignments.*, "
                . " customer.customernr as customer_id, "
                . " customer.salutation as customer_salutation, "
                . " customer.name as customer_name, "
                . " customer.surname as customer_surname, "
                . " customer.company as customer_company, "
                . " customer.customernr_prefix, "
                . " customer.street as customer_street, "
                . " customer.zipcode as customer_zipcode, "
                . " customer.city as customer_city, "
                . " customer.email as customer_email, "

                . " CONCAT(responsible.name,' ',responsible.surname) as responsible, "
                . " responsible.email as responsible_email ",

                array('tblusers as responsible'=>'responsible.userid=tblhardwareassignments.responsible',
                'tblcustomers as customer'=>'customer.customernr=tblhardwareassignments.customer')
        );
        if(empty($data['hardwareassignment']['hardwareassignmentnr'])){
            redirect(site_url('admin/hardwareassignments'));
            exit;
        }

        //Hardwareassignment Products
        $data['hardwareassignmentproducts'] = $this->Hardwareassignmentproduct_model->get('','tblhardwareassignmentproducts.*, '
                . " newratemobile.ratetitle as newratemobile,"
                . ' tblhardwares.hardwaretitle as hardware,'
                . ' tblhardwareassignmentproducts.hardwarevalue',

                array('tblratesmobile as newratemobile'=>'newratemobile.ratenr=tblhardwareassignmentproducts.newratemobile',
                    'tblhardwares'=>'tblhardwares.hardwarenr=tblhardwareassignmentproducts.hardware'
                ),

            " tblhardwareassignmentproducts.hardwareassignmentnr='".$data['hardwareassignment']['hardwareassignmentnr']."' "
        );


        //Generate Hardware Assignment Invoice
        $dataInvoice = array('hardwareassignmentnr' => $data['hardwareassignment']['hardwareassignmentnr'],
            'customer_id' => $data['hardwareassignment']['customer_id'],
            'customer_salutation' => $data['hardwareassignment']['customer_salutation'],
            'customer_name' => $data['hardwareassignment']['customer_name'],
            'customer_surname' => $data['hardwareassignment']['customer_surname'],
            'customer_company' => $data['hardwareassignment']['customer_company'],
            'customernr_prefix' => $data['hardwareassignment']['customernr_prefix'],
            'customer_street' => $data['hardwareassignment']['customer_street'],
            'customer_zipcode' => $data['hardwareassignment']['customer_zipcode'],
            'customer_city' => $data['hardwareassignment']['customer_city'],
            'customer_email' => $data['hardwareassignment']['customer_email'],
            'responsible' => $data['hardwareassignment']['responsible'],
            'responsible_email' => $data['hardwareassignment']['responsible_email']
        );
        $invoicenr = $this->Hardwareassignmentinvoice_model->add($dataInvoice);
        //Get Invoice Detail
        $rowfield = (array)$this->Hardwareassignmentinvoice_model->get($invoicenr,"invoicenr");

        if(isset($rowfield['invoicenr'])){

            //History
            $Action_data = array('actionname'=>'hardwareinvoice', 'actionid'=>$rowfield['invoicenr'], 'actiontitle'=>'hardwareinvoice_added');
            do_action_history($Action_data);

            /*
            Value 12 is added all Hardwarevalues…
            Value 13 = (Value 12)-(Value 12 / 1.19)
            Value 14 = Value 12 * 1.19
            */

            //Generate Hardware Assignment Invoice Products
            $hardware_calculate_total = 0;
            foreach($data['hardwareassignmentproducts'] as $hardwareassignmentproduct){
                $dataInvoiceProduct = array(
                    'productpositionid' => $hardwareassignmentproduct['id'],
                    'invoicenr' => $rowfield['invoicenr'],
                    'simnr' => $hardwareassignmentproduct['simnr'],
                    'mobilenr' => $hardwareassignmentproduct['mobilenr'],
                    'newratemobile' => $hardwareassignmentproduct['newratemobile'],
                    'hardware' => $hardwareassignmentproduct['hardware'],
                    'seriesnr' => $hardwareassignmentproduct['seriesnr'],
                    'shippingnr' => $hardwareassignmentproduct['shippingnr'],
                    'hardwarevalue' => $hardwareassignmentproduct['hardwarevalue']
                );
                $hardware_calculate_total = $hardware_calculate_total + $hardwareassignmentproduct['hardwarevalue'];
                $this->Hardwareassignmentinvoiceproduct_model->add($dataInvoiceProduct);
            }
            //VAT
            $vat = round((($hardware_calculate_total * COMPANY_VAT)/100),2);
            $grand_total = round(($hardware_calculate_total + $vat),2);

            //Update Invoice Grand Total
            $updateInvoiceData = array('hardware_total'=>$hardware_calculate_total,'company_vat'=>COMPANY_VAT, 'company_vat_total'=>$vat,'grand_total'=>$grand_total);
            $this->Hardwareassignmentinvoice_model->update($updateInvoiceData,$rowfield['invoicenr']);

            //Get Invoice Detail
            $rowfield = (array)$this->Hardwareassignmentinvoice_model->get($rowfield['invoicenr'],"*,DATE_FORMAT(created,'%Y-%m-%d') as created");
            $data['invoice'] = $rowfield;
            $rowfieldProducts = (array)$this->Hardwareassignmentinvoiceproduct_model->get('','',array()," invoicenr='".$rowfield['invoicenr']."' ");
            $data['invoiceproducts'] = $rowfieldProducts;

            //Footer Text
            $data = array_merge($data, $GLOBALS['currency_data'], $GLOBALS['company_data']);
            $data['data'] = $data;

            $this->Pdf_model->pdf_printhardwareinvoice($data);
        }
        else{
            set_alert('error', sprintf(lang('failed'), lang('page_lb_print_invoice')));
            redirect(site_url('admin/hardwareassignments/'));
        }
    }
}