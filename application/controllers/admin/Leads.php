<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Leads extends Admin_controller
{
    public function __construct()
    {
        parent::__construct();
        if(get_user_role()!='user'){
            redirect(site_url('admin/permission/denied/'));
        }
        $this->load->model('Lead_model');
        $this->load->model('User_model');
        $this->load->model('Leadstatus_model');
        $this->load->model('Leadsource_model');
        $this->load->model('Leadprovidercompany_model');
        $this->load->model('Companysize_model');
        $this->load->model('Salutation_model');
        $this->load->model('Leadproduct_model');
        $this->load->model('Documentsetting_model');
        $this->load->model('Remindersubject_model');
        $this->load->model('Note_model');
        $this->load->model('File_model');
        $this->load->model('Customer_model');
        $this->load->model('Reminder_model');
        $this->load->model('Leadquotation_model');
        $this->load->model('Leadquotationproduct_model');
        $this->load->model('Leadquotationreminder_model');
        $this->load->model('Quotation_model');
    }

    /* List all leads */
    public function index()
    {
        //Lead Reminder
        /*$lead_reminder = (array) $this->Reminder_model->get(2,"reminddate");
        echo $d3 = $lead_reminder['reminddate'];
        echo '<br >';
        echo $d1 = $GLOBALS['current_datetime'];
        echo '<br >';
        echo $d2 = date('Y-m-d H:i:s',strtotime($GLOBALS['current_datetime']."1 day 30 minutes"));
        if($d3<=$d2){ echo 'done'; }
        exit;*/

        if(!$GLOBALS['lead_permission']['view'] && !$GLOBALS['lead_permission']['view_own']){
            access_denied('lead');
        }

        //******************** Initialise ********************/
        //Responsibles (Salesmanager,Salesman or Admin)
        $data['filter_responsible'] = $this->User_model->get('',"userid, CONCAT(name,' ',surname) as name"," userrole IN(1,2,3) ");
        $data['filter_responsible'] = dropdown($data['filter_responsible'],'userid','name');

        //Leadstatus
        $data['filter_leadstatus'] = $this->Leadstatus_model->get();
        $data['filter_leadstatus'] = dropdown($data['filter_leadstatus'],'id','name');

        //Products
        $data['filter_product'] = $this->Leadproduct_model->get();
        $data['filter_product'] = dropdown($data['filter_product'],'id','name');
        //******************** End Initialise ********************/

        $data['title'] = lang('page_leads');
        $this->load->view('admin/leads/manage', $data);
    }

    /* List all leads by ajax */
    public function ajax($filter_responsible='',$filter_leadstatus='', $filter_leadproduct='')
    {
        //Filter By responsible, leadstatus, leadproduct
        $params = array('filter_responsible'=>$filter_responsible,'filter_leadstatus'=>$filter_leadstatus,'filter_leadproduct'=>$filter_leadproduct);
        $this->app->get_table_data('leads',$params);
    }

    /* List all documents by ajax */
    public function ajaxdocument($leadnr)
    {
        $params['leadid'] = $leadnr;
        $this->app->get_table_data('leaddocuments',$params);
    }

    /* Add/Edit Lead */
    public function lead($id='')
    {
        if(!$GLOBALS['lead_permission']['create'] && !$GLOBALS['lead_permission']['edit']){
            access_denied('lead');
        }

        //******************** Initialise ********************/
        if($id>0){
            //Lead
            $data['lead'] = (array) $this->Lead_model->get($id);
        }
        //******************** End Initialise ********************/

        //Submit Page
        if ($this->input->post()) {
            $post = $this->input->post();

            //POS cant set Responsible
            if($GLOBALS['current_user']->userrole==6){
                if(isset($post['responsible'])){
                    unset($post['responsible']);
                }
            }

            if(isset($data['lead']['leadnr'])){
                $response = $this->Lead_model->update($post, $data['lead']['leadnr']);
                if (is_numeric($response) && $response>0) {

                    //History
                    $Action_data = array('actionname'=>'lead', 'actionid'=>$response, 'actiontitle'=>'lead_updated');
                    do_action_history($Action_data);

                    set_alert('success', sprintf(lang('updated_successfully'),lang('page_lead')));
                    redirect(site_url('admin/leads/detail/' . $data['lead']['leadnr']));
                }
                else{
                    set_alert('danger', $response);
                }
            }
            else{
                $response = $this->Lead_model->add($post);
                if (is_numeric($response) && $response>0) {

                    //History
                    $Action_data = array('actionname'=>'lead', 'actionid'=>$response, 'actiontitle'=>'lead_added');
                    do_action_history($Action_data);

                    set_alert('success', sprintf(lang('added_successfully'),lang('page_lead')));
                    redirect(site_url('admin/leads/detail/' . $response));
                }
                else{
                    set_alert('danger', $response);
                }
            }

            //Initialise
            $leadnr = '';
            if(isset($data['lead'])){
                $leadnr = $data['lead']['leadnr'];
            }
            $data['lead'] = $post;
            $data['lead']['leadnr'] = $leadnr;
        }


        //******************** Initialise ********************/
        //Responsibles (Salesmanager,Salesman or Admin)
        $data['responsibles'] = $this->User_model->get('',"userid, CONCAT(name,' ',surname) as name"," userrole IN(1,2,3) ");
        $data['responsibles'] = dropdown($data['responsibles'],'userid','name');

        //Recommends (POS)
        $data['recommends'] = $this->User_model->get('',"userid, CONCAT(name,' ',surname) as name"," userrole IN(6) ");
        $data['recommends'] = dropdown($data['recommends'],'userid','name');

        //Leadstatus
        $data['leadstatus'] = $this->Leadstatus_model->get();
        $data['leadstatus'] = dropdown($data['leadstatus'],'id','name');

        //Leadsources
        $data['leadsources'] = $this->Leadsource_model->get();
        $data['leadsources'] = dropdown($data['leadsources'],'id','name');

        //Leadprovidercompanies
        $leadnr = isset($data['lead']['leadnr'])?$data['lead']['leadnr']:'';
        $data['leadprovidercompanies'] = $this->Leadprovidercompany_model->get('',''," leadnr='".$leadnr."' ");

        //Companysizes
        $data['companysizes'] = $this->Companysize_model->get();
        $data['companysizes'] = dropdown($data['companysizes'],'id','name');

        //Salutations (Titles)
        $data['salutations'] = $this->Salutation_model->get();
        $data['salutations'] = dropdown($data['salutations'],'salutationid','name');

        //Products
        $data['products'] = $this->Leadproduct_model->get();
        $data['products'] = dropdown($data['products'],'id','name');

        //Remindersubjects
        $data['remindersubjects'] = $this->Remindersubject_model->get();
        $data['remindersubjects'] = dropdown($data['remindersubjects'],'id','name');

        //Categories
        $data['categories'] = $this->Documentsetting_model->get('',''," active=1 ");
        $data['categories'] = dropdown($data['categories'],'categoryid','categoryname');

        //Teamwork (Multiple selection)
        $data['teamworks'] = $this->User_model->get('',"userid, CONCAT(name,' ',surname) as name"," userrole NOT IN(4,6) ");
        //******************** End Initialise ********************/


        //Page Title
        if(isset($data['lead']['leadnr']) && $data['lead']['leadnr']>0){
            $data['title'] = lang('page_edit_lead');

            //Check If POS User They have access only own records
            //- On the Dashboard he should only see Leads which belongs to the User who is logged in. (Salesman and Supporter and POS)
            if(get_user_role()=='user' && isset($GLOBALS['current_user']->userrole) && ($GLOBALS['current_user']->userrole==3 || $GLOBALS['current_user']->userrole==6) && $data['lead']['userid']!=get_user_id() && $data['lead']['responsible']!=get_user_id()){
                redirect(site_url('admin/leads'));
            }
        }
        else{
            $data['title'] = lang('page_create_lead');
        }


        $this->load->view('admin/leads/lead', $data);
    }

    /* Detail Lead */
    public function detail($id='')
    {

        if(!$GLOBALS['lead_permission']['view'] && !$GLOBALS['lead_permission']['view_own']){
            access_denied('lead');
        }

        //******************** Initialise ********************/
        if($id>0){
            //Lead
            $data['lead'] = (array) $this->Lead_model->get($id,"tblleads.*, CONCAT(responsible.name,' ',responsible.surname) as responsible, tblleads.responsible as responsible_id, "
                    . " CONCAT(recommend.name,' ',recommend.surname) as recommend, "
                    . " tblleads.leadstatus,"
                    . " tblleadstatus.name as leadstatusname, "
                    . " tblleadsources.name as leadsource,"
                    . " tblcompanysizes.name as companysize, "
                    . " tblsalutations.name as salutation, "
                    . " tblleadproducts.name as product,"
                    . " GROUP_CONCAT(tblleadprovidercompanies.providernr) as leadprovidercompanies, "
                    . " (SELECT GROUP_CONCAT(CONCAT(`name`,' ',surname)) FROM tblusers WHERE FIND_IN_SET(userid, tblleads.teamwork)) as teamwork ",

                    array('tblusers as responsible'=>'responsible.userid=tblleads.responsible',
                    'tblusers as recommend'=>'recommend.userid=tblleads.recommend',
                    'tblleadstatus'=>'tblleadstatus.id=tblleads.leadstatus',
                    'tblleadsources'=>'tblleadsources.id=tblleads.leadsource',
                    'tblcompanysizes'=>'tblcompanysizes.id=tblleads.companysize',
                    'tblsalutations'=>'tblsalutations.salutationid=tblleads.salutation',
                    'tblleadproducts'=>'tblleadproducts.id=tblleads.product',
                    'tblleadprovidercompanies'=>'tblleadprovidercompanies.leadnr=tblleads.leadnr',
                    )
            );
        }



        if(empty($data['lead']['leadnr'])){
            redirect(site_url('admin/leads'));
        }


        //Check If POS User They have access only own records
        // || $GLOBALS['current_user']->userrole==5 for supporter
        if(get_user_role()=='user' && isset($GLOBALS['current_user']->userrole) && ($GLOBALS['current_user']->userrole==3 || $GLOBALS['current_user']->userrole==6) && $data['lead']['userid']!=get_user_id() && $data['lead']['responsible_id']!=get_user_id()){
        //- On the Dashboard he should only see Leads which belongs to the User who is logged in. (Salesman and Supporter and POS)
            redirect(site_url('admin/leads'));
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
        $data['comments'] = $this->Note_model->get("","tblnotes.*, CONCAT(tblusers.name,' ',tblusers.surname) as fullname",array('tblusers'=>'tblusers.userid=tblnotes.addedfrom')," tblnotes.rel_id='".$data['lead']['leadnr']."' AND tblnotes.rel_type='lead' ","","tblnotes.id desc");
        //******************** End Initialise ********************/


        //Page Title
        $data['title'] = lang('page_detail_lead');
        $this->load->view('admin/leads/detail', $data);
    }

    /* Delete lead */
    public function delete()
    {
        if(!$GLOBALS['lead_permission']['delete'] || !$this->input->post('id')){
            access_denied('lead');
        }

        $response = $this->Lead_model->delete($this->input->post('id'));
        if ($response==1) {

            //History
            $Action_data = array('actionname'=>'lead', 'actionid'=>$this->input->post('id'), 'actiontitle'=>'lead_deleted');
            do_action_history($Action_data);

            set_alert('success', sprintf(lang('deleted_successfully'),lang('page_lead')));
        }else{
            set_alert('danger', $response);
        }
        redirect(site_url('admin/leads/'));
    }

    /* Delete Lead Provider Company Number by Ajax */
    public function deleteProviderCompany($id){
        $this->Leadprovidercompany_model->delete($id);
        exit;
    }

    /* Upload Dropzone file by Ajax */
    public function uploadDocuments($id){
        handle_lead_attachments($id);
        exit;
    }

    /* Get Uploaded Documents by Ajax */
    public function getDocuments($id){
        //******************** Initialise ********************/
        //Lead
        $data['lead'] = (array) $this->Lead_model->get($id);
        //******************** End Initialise ********************/

        if(count($data['lead']['attachments']) > 0) {
            $this->load->view('admin/leads/leads_attachments_template', array('attachments'=>$data['lead']['attachments']));
        }
    }

    /* Delete Document by Ajax */
    public function deleteDocument(){
        if($this->input->post('id')){
            $response = $this->Lead_model->delete_lead_attachment($this->input->post('id'));
            if ($response==1) {
                echo json_encode(array('response'=>'success','message'=>sprintf(lang('deleted_successfully'),lang('page_leaddocument'))));
            }else{
                echo json_encode(array('response'=>'error','message'=>$response));
            }
        }
        exit;
    }

    /* Update Document Category by Ajax */
    public function updateDocumentCategory($id, $categoryid){
        if(isset($id)){
            $response = $this->Lead_model->update_lead_attachmentcategory($id, $categoryid);
            if ($response==1) {
                echo json_encode(array('response'=>'success','message'=>sprintf(lang('updated_successfully'),lang('page_leaddocument'))));
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
        $path = get_upload_path_by_type('lead') . $attachment->rel_id . '/' . $attachment->file_name;
        force_download($path, null);
    }

    /* Add a Comment by Ajax */
    public function addComment(){
        if ($this->input->post()) {
            $post = $this->input->post();
            $response = $this->Note_model->add($post);
            if (is_numeric($response) && $response>0) {

                //History
                $Action_data = array('actionname'=>'lead', 'actionid'=>$post['rel_id'], 'actionsubid'=>$response, 'actiontitle'=>'lead_comment_added');
                do_action_history($Action_data);

                echo json_encode(array('response'=>'success','message'=>sprintf(lang('added_successfully'),lang('page_leadcomment'))));
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
                $Action_data = array('actionname'=>'lead', 'actionid'=>$post['rel_id'], 'actionsubid'=>$id, 'actiontitle'=>'lead_comment_updated');
                do_action_history($Action_data);

                echo json_encode(array('response'=>'success','message'=>sprintf(lang('updated_successfully'),lang('page_leadcomment'))));
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
                $Action_data = array('actionname'=>'lead', 'actionid'=>$this->input->post('parentid'), 'actionsubid'=>$this->input->post('id'), 'actiontitle'=>'lead_comment_deleted');
                do_action_history($Action_data);

                echo json_encode(array('response'=>'success','message'=>sprintf(lang('deleted_successfully'),lang('page_leadcomment'))));
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
        $data['comments'] = $this->Note_model->get("","tblnotes.*, CONCAT(tblusers.name,' ',tblusers.surname) as fullname",array('tblusers'=>'tblusers.userid=tblnotes.addedfrom')," tblnotes.rel_id='".$id."' AND tblnotes.rel_type='lead' ","","tblnotes.id desc");
        //******************** End Initialise ********************/

        if(count($data['comments']) > 0) {
            $this->load->view('admin/leads/leads_comments_template', $data);
        }
    }

    /* Get Lead by Ajax */
    public function getLead($id){

        if(!$GLOBALS['leadtocustomer_permission']['create']){
            access_denied('leadtocustomer');
        }

        //******************** Initialise ********************/
        if($id>0){
            //Lead
            $data['lead'] = (array) $this->Lead_model->get($id);
        }
        //******************** End Initialise ********************/

        if(empty($data['lead']['leadnr'])){
            //redirect(site_url('admin/leads'));
            return false;
        }

        //******************** Initialise ********************/
        //Responsibles (Salesmanager,Salesman or Admin)
        $data['responsibles'] = $this->User_model->get('',"userid, CONCAT(name,' ',surname) as name"," userrole IN(1,2,3) ");
        $data['responsibles'] = dropdown($data['responsibles'],'userid','name');

        //Recommends (POS)
        $data['recommends'] = $this->User_model->get('',"userid, CONCAT(name,' ',surname) as name"," userrole IN(6) ");
        $data['recommends'] = dropdown($data['recommends'],'userid','name');

        //Leadstatus
        $data['leadstatus'] = $this->Leadstatus_model->get();
        $data['leadstatus'] = dropdown($data['leadstatus'],'id','name');

        //Leadsources
        $data['leadsources'] = $this->Leadsource_model->get();
        $data['leadsources'] = dropdown($data['leadsources'],'id','name');

        //Leadprovidercompanies
        $leadnr = isset($data['lead']['leadnr'])?$data['lead']['leadnr']:'';
        $data['leadprovidercompanies'] = $this->Leadprovidercompany_model->get('',''," leadnr='".$leadnr."' ");

        //Companysizes
        $data['companysizes'] = $this->Companysize_model->get();
        $data['companysizes'] = dropdown($data['companysizes'],'id','name');

        //Salutations (Titles)
        $data['salutations'] = $this->Salutation_model->get();
        $data['salutations'] = dropdown($data['salutations'],'salutationid','name');

        //Products
        $data['products'] = $this->Leadproduct_model->get();
        $data['products'] = dropdown($data['products'],'id','name');

        //Remindersubjects
        $data['remindersubjects'] = $this->Remindersubject_model->get();
        $data['remindersubjects'] = dropdown($data['remindersubjects'],'id','name');

        //Categories
        $data['categories'] = $this->Documentsetting_model->get('',''," active=1 ");
        $data['categories'] = dropdown($data['categories'],'categoryid','categoryname');

        //Remindersubjects
        $data['remindersubjects'] = $this->Remindersubject_model->get();
        $data['remindersubjects'] = dropdown($data['remindersubjects'],'id','name');
        //******************** End Initialise ********************/

        if(count($data['lead']) > 0) {
            $this->load->view('admin/leads/addcustomer', $data);
        }
    }

    /* Add New Customer */
    public function addCustomer($id){

        if(!$GLOBALS['leadtocustomer_permission']['create']){
            access_denied('leadtocustomer');
        }

        //Submit Page
        if ($this->input->post()) {
            $post = $this->input->post();
            $post['leadid'] = $id; //for Converted Reference from Lead
            $response = $this->Customer_model->add($post);
            if (is_numeric($response) && $response>0) {

                //History
                $Action_data = array('actionname'=>'customer', 'actionid'=>$response, 'actiontitle'=>'customer_added');
                do_action_history($Action_data);

                //Change Status to Closed(Geschlossen)
                $data = array();
                $data['leadstatus'] = 5;
                $this->Lead_model->update($data, $id);

                //Copy Documents
                $this->copy_documents_leadtocustomer($id, $response);
                //Copy Comments
                $this->copy_comments_leadtocustomer($id, $response);

                // copy lead quotation to quotation
                $leadquotations = $this->Leadquotation_model->get('','',array(),"leadnr = $id");
                foreach ($leadquotations as $key => $value) {
                    $quotation = array();
                    foreach ($value as $s_key => $s_value) {
                        $quotation[$s_key] = $s_value;
                    }
                    $quotation['customer'] = $response;
                    $quotation['quotationdate'] = $quotation['leadquotationdate'];
                    $quotation['quotationstatus'] = $quotation['leadquotationstatus'];
                    $quotation['created'] = date('Y-m-d H:i:s');

                    $leadquotationnr = $quotation['leadquotationnr'];
                    $unset_arr = array('leadquotationnr','leadquotationnr_prefix','created','updated','leadquotationdate','leadquotationstatus');
                    foreach ($unset_arr as $u_key => $u_value) { unset($quotation[$u_value]); }

                    $this->db->insert('tblquotations', $quotation);
                    $new_quotataion_id = $this->db->insert_id();

                    $temp = idprefix('quotation',$new_quotataion_id);
                    $this->db->where('quotationnr', $new_quotataion_id);
                    $this->db->update('tblquotations', array('quotationnr_prefix' => $temp));

                    $products = $this->Leadquotationproduct_model->get('','',array(),"leadquotationnr = $leadquotationnr");
                    foreach ($products as $p_key => $p_value) {
                        $product = array();
                        foreach ($p_value as $s_key => $s_value) {
                            $product[$s_key] = $s_value;
                        }
                        $product['quotationnr'] = $new_quotataion_id;
                        $unset_arr = array('id','leadquotationnr');
                        foreach ($unset_arr as $u_key => $u_value) { unset($product[$u_value]); }
                        $this->db->insert('tblquotationproducts', $product);
                    }

                    $reminders = $this->Leadquotationreminder_model->get('','',array(),"rel_id = $leadquotationnr AND rel_type ='leadquotation'");

                    foreach ($reminders as $r_key => $r_value) {
                        $reminder = array();
                        foreach ($r_value as $s_key => $s_value) {
                            $reminder[$s_key] = $s_value;
                        }
                        $reminder['rel_id'] = $new_quotataion_id;
                        $unset_arr = array('remindernr','remindernr_prefix');
                        foreach ($unset_arr as $u_key => $u_value) { unset($reminder[$u_value]); }
                        $this->db->insert('tblquotationreminders', $reminder);
                        $new_reminder_id = $this->db->insert_id();

                        $temp = idprefix('quotationreminder',$new_reminder_id);
                        $this->db->where('remindernr', $new_reminder_id);
                        $this->db->update('tblquotationreminders', array('remindernr_prefix' => $temp));
                    }

                    $documents = $this->File_model->get('',''," rel_id='".$leadquotationnr."' AND rel_type='leadquotation' ");
                    foreach ($documents as $d_key => $d_value) {
                        //Destination with create new folder
                        $quotationpath = get_upload_path_by_type('quotation') . $new_quotataion_id . '/';
                        _maybe_create_upload_path($quotationpath);
                        $filename    = $d_value['file_name'];
                        $newFilePath = $quotationpath . $filename;

                        //Source
                        $leadquotationpath = get_upload_path_by_type('leadquotation') . $leadquotationnr . '/';
                        $tmpFilePath = $leadquotationpath . $filename;

                        // Upload the file into the company uploads dir
                        if (copy($tmpFilePath, $newFilePath)) {
                            $document = array();
                            foreach ($d_value as $s_key => $s_value) {
                                $document[$s_key] = $s_value;
                            }
                            $unset_arr = array('id');
                            foreach ($unset_arr as $u_key => $u_value) { unset($document[$u_value]); }
                            $document['created'] = date('Y-m-d H:i:s');
                            $document['rel_id'] = $new_quotataion_id;
                            $document['userid'] = get_user_id();
                            $document['rel_type'] = 'quotation';
                            $document['attachment_key'] = app_generate_hash();
                            $this->db->insert('tblfiles', $document);
                        }
                    }
                }

                //Update Customer Belongs to Quotation
                //$this->Lead_model->updateCustomerQuotation($id, $response, $post['responsible']);

                set_alert('success', sprintf(lang('created_successfully'),lang('page_customer')));
                echo json_encode(array('response'=>'success','message'=>sprintf(lang('created_successfully'),lang('page_customer')),'redirect'=>base_url('admin/leads/')));
            }else{
                echo json_encode(array('response'=>'error','message'=>$response,'redirect'=>base_url('admin/leads/')));
            }
        }
        exit;
    }

    //Copy Documents
    public function copy_documents_leadtocustomer($id, $custid){
        $documents = $this->File_model->get('',''," rel_id='".$id."' AND rel_type='lead' ");
        if(count($documents)>0){
            foreach($documents as $document){
                do_action('before_upload_customer_attachment', $document['rel_id']);

                //Destination with create new folder
                $customerpath = get_upload_path_by_type('customer') . $custid . '/';
                _maybe_create_upload_path($customerpath);
                //$filename    = unique_filename($customerpath, $document['file_name']);
                $filename    = $document['file_name'];
                $newFilePath = $customerpath . $filename;

                //Source
                $leadpath = get_upload_path_by_type('lead') . $document['rel_id'] . '/';
                $tmpFilePath = $leadpath . $filename;

                // Upload the file into the company uploads dir
                if (copy($tmpFilePath, $newFilePath)) {
                    $data = array();
                    $data[] = array(
                        'file_name' => $filename,
                        'filetype' => $document['filetype'],
                        'categoryid' => $document['categoryid']
                        );
                    $this->Customer_model->add_attachment_to_database($custid, $data, false, false);
                }
            }
        }
    }

    //Copy Comments
    public function copy_comments_leadtocustomer($id, $custid){
        $comments = $this->Note_model->get('','',array()," rel_id='".$id."' AND rel_type='lead' ");
        if(count($comments)>0){
            foreach($comments as $comment){
                $data = $comment;
                unset($data['id']);
                $data['rel_id'] = $custid;
                $data['rel_type'] = 'customer';
                $this->Note_model->add($data);
            }
        }
    }

     /* Import Lead */
    public function import(){
        if(!$GLOBALS['lead_permission']['import']){
            access_denied('lead');
        }

        //Initialize
        $data['file_name'] = 'tblleads';
        $data['db_fields'] = $this->db->list_fields($data['file_name']);
        $data['not_importable'] = array('leadnr_prefix','userid','created','updated');
        $data['sample_data'] = array(idprefix('lead',1),'admin','admin',
            'Nicht kontaktiert',
            'Kalt','BVTcom GmbH', 'Amtsgericht Hamm','59192','Bergkamen','Germany','+ 49 (0) 800 200 70 99 ','+ 49 (0) 800 200 70 997',
            'vertrieb@bvtcom.de','Klein','www.bvtcom.de','Business Name','Herr','BVT', 'BVT','CEO','0800 200 70 99',
            'Vodafone','Festnetz');

        //Submit for Import
        if ($this->input->post()) {
            if($this->input->post('download_sample') === 'true'){
                //Download Sample CSV
                downloadsamplecsv($data);
            }
            else{
                //Import CSV
                $response = $this->Lead_model->importcsv($data);
                if ($response['status']==1) {

                    //History
                    $Action_data = array('actionname'=>'lead', 'actiontitle'=>'lead_imported');
                    do_action_history($Action_data);

                    set_alert('success', $response['message']);
                    redirect(site_url('admin/leads/'));
                }else{
                    set_alert('danger', $response['message']);
                }
            }
        }

        $data['title'] = lang('page_import_lead');
        $this->load->view('admin/leads/import', $data);
    }

    //Generate Reminder by Cronjob (Notification Email 1 Day before he set the date)
    public function generatereminder($id='')
    {
        //Reminder to Responsible, Teamwork
        $this->Lead_model->sendReminder($id);
    }

    //Delete All Lead
    public function deleteAll(){
        if(!$GLOBALS['lead_permission']['delete'] || !$this->input->post('id')){
            access_denied('lead');
        }
        $response = 0;
        $ids = explode(",",$this->input->post('id'));
        foreach($ids as $id){
            $response = $response + $this->Lead_model->delete($id);
        }

        //History
        $Action_data = array('actionname'=>'lead', 'actiongroupid'=>$this->input->post('id'), 'actiontitle'=>'lead_deleted_all');
        do_action_history($Action_data);

        if ($response>0) {
            set_alert('success', sprintf(lang('deleted_successfully'),lang('page_lead')));
        }else{
            set_alert('danger', $response);
        }
        redirect(site_url('admin/leads/'));
    }
}