<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Tickets extends Admin_controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Ticket_model');
        $this->load->model('Ticketstatus_model');
        $this->load->model('Note_model');
        $this->load->model('File_model');
        $this->load->model('Customer_model');
    }

    /* List all tickets */
    public function index()
    {
        if(!$GLOBALS['ticket_permission']['view'] && !$GLOBALS['ticket_permission']['view_own']){
            access_denied('ticket');
        }

        //******************** Initialise ********************/
        //Responsibles (Users of Customer)
        $data['filter_responsible'] = $this->Customer_model->get('',"tblusers.userid, CONCAT(tblusers.name,' ',tblusers.surname) as name",
            array('tblusers'=>'tblusers.userid=tblcustomers.responsible')
        );
        $data['filter_responsible'] = dropdown($data['filter_responsible'],'userid','name');

        //Ticketstatus
        $data['filter_ticketstatus'] = $this->Ticketstatus_model->get();
        $data['filter_ticketstatus'] = dropdown($data['filter_ticketstatus'],'id','name');
        //******************** End Initialise ********************/

        $data['title'] = lang('page_tickets');
        $this->load->view('admin/tickets/manage', $data);
    }

    /* List all tickets by ajax */
    public function ajax($filter_responsible='',$filter_ticketstatus='')
    {
        //Filter By responsible, ticketstatus, ticketproduct
        $params = array('filter_responsible'=>$filter_responsible,'filter_ticketstatus'=>$filter_ticketstatus);
        $this->app->get_table_data('tickets',$params);
    }

    /* List all documents by ajax */
    public function ajaxdocument($ticketnr)
    {
        $params['ticketid'] = $ticketnr;
        $this->app->get_table_data('ticketdocuments',$params);
    }

    /* Add/Edit Ticket */
    public function ticket($id='')
    {
        if(!$GLOBALS['ticket_permission']['create'] && !$GLOBALS['ticket_permission']['edit']){
            access_denied('ticket');
        }

        //******************** Initialise ********************/
        if($id>0){
            //Ticket
            $data['ticket'] = (array) $this->Ticket_model->get($id,"tbltickets.*, CONCAT(tblcustomers.name,' ',tblcustomers.surname) as customername",array("tblcustomers"=>"tblcustomers.customernr=tbltickets.customer"));
        }
        //******************** End Initialise ********************/

        //Submit Page
        if ($this->input->post()) {
            $post = $this->input->post();

            if(isset($data['ticket']['ticketnr'])){
                $response = $this->Ticket_model->update($post, $data['ticket']['ticketnr']);
                if (is_numeric($response) && $response>0) {

                    //History
                    $Action_data = array('actionname'=>'ticket', 'actionid'=>$response, 'actiontitle'=>'ticket_updated');
                    do_action_history($Action_data);

                    set_alert('success', sprintf(lang('updated_successfully'),lang('page_ticket')));
                    redirect(site_url('admin/tickets/detail/' . $data['ticket']['ticketnr']));
                }
                else{
                    set_alert('danger', $response);
                }
            }
            else{
                $response = $this->Ticket_model->add($post);
                if (is_numeric($response) && $response>0) {

                    //History
                    $Action_data = array('actionname'=>'ticket', 'actionid'=>$response, 'actiontitle'=>'ticket_added');
                    do_action_history($Action_data);

                    //Send Reminder to Responsible and Teamwork
                    $this->Ticket_model->sendReminder($response);

                    set_alert('success', sprintf(lang('added_successfully'),lang('page_ticket')));
                    redirect(site_url('admin/tickets/detail/' . $response));
                }
                else{
                    set_alert('danger', $response);
                }
            }

            //Initialise
            $ticketnr = '';
            if(isset($data['ticket'])){
                $ticketnr = $data['ticket']['ticketnr'];
            }
            $data['ticket'] = $post;
            $data['ticket']['ticketnr'] = $ticketnr;
        }


        //******************** Initialise ********************/
        //Customers
        //$data['customers'] = $this->Customer_model->get('',"tblcustomers.customernr, CONCAT(tblcustomers.name,' ',tblcustomers.surname) as name");
        $data['customers'] = $this->Customer_model->get('',"tblcustomers.customernr, tblcustomers.company");
        $data['customers'] = dropdown($data['customers'],'customernr','company');


        $where = '';
        if (get_user_role()=='customer') {
            // $where = 'tblcustomers.customernr = ' . get_user_id();
             $where = 'tblcustomers.customernr = ' . get_user_id() .' OR tblcustomers.customernr = '. $GLOBALS['current_user']->parent_customer_id;
        }
        $data['responsibles'] = $this->Customer_model->get('', "tblusers.userid, CONCAT(tblusers.name, ' ', tblusers.surname) AS name",
            array('tblusers' => 'tblusers.userid=tblcustomers.responsible')
            , $where
        );
        $data['responsibles'] = dropdown($data['responsibles'], 'userid', 'name');

        //Ticketstatus
        // $data['ticketstatus'] = $this->Ticketstatus_model->get((get_user_role()=='customer'?5:'')); // 5 = Erstellt
        $data['ticketstatus'] = $this->Ticketstatus_model->get(); // 5 = Erstellt
        // if (get_user_role()=='customer') {
        //     $data['ticketstatus'] = array((array) $data['ticketstatus']);
        // }
        $data['ticketstatus'] = dropdown($data['ticketstatus'],'id','name');

        //Teamwork (Multiple selection)
        $data['teamworks'] = $this->User_model->get('',"userid, CONCAT(name,' ',surname) as name"," userrole NOT IN(4,6) ");
        //******************** End Initialise ********************/


        //Page Title
        if(isset($data['ticket']['ticketnr']) && $data['ticket']['ticketnr']>0){
            $data['title'] = lang('page_edit_ticket');

            //- On the Dashboard he should only see Tickets which belongs to the User who is logged in. (Customer and Salesman and Supporter and POS)
            if((
                (get_user_role()=='customer' && $data['ticket']['userid']!=get_user_id() && $data['ticket']['customer']!=get_user_id()) ||
                (isset($GLOBALS['current_user']) && ($GLOBALS['current_user']->userrole==3 || $GLOBALS['current_user']->userrole==5 || $GLOBALS['current_user']->userrole==6) && $data['ticket']['userid']!=get_user_id() && $data['ticket']['responsible']!=get_user_id())
            )){
                redirect(site_url('admin/tickets'));
            }

        }
        else{
            $data['title'] = lang('page_create_ticket');
        }


        $this->load->view('admin/tickets/ticket', $data);
    }

    /* Detail Ticket */
    public function detail($id='')
    {
        if(!$GLOBALS['ticket_permission']['view'] && !$GLOBALS['ticket_permission']['view_own']){
            access_denied('ticket');
        }

        //******************** Initialise ********************/
        if($id>0){
            //Ticket
            $data['ticket'] = (array) $this->Ticket_model->get($id,"tbltickets.*, CONCAT(customer.name,' ',customer.surname) as customer, tbltickets.customer as customer_id, CONCAT(responsible.name,' ',responsible.surname) as responsible, tbltickets.responsible as responsible_id, customer.company as customer_company, "
                    . " tbltickets.ticketstatus,"
                    . " tblticketstatus.name as ticketstatusname, "
                    . " CONCAT(createduser.name,' ',createduser.surname) as created_by , "
                    . " (SELECT GROUP_CONCAT(CONCAT(`name`,' ',surname)) FROM tblusers WHERE FIND_IN_SET(userid, tbltickets.teamwork)) as teamwork ",

                    array('tblusers as responsible'=>'responsible.userid=tbltickets.responsible',
                    'tblcustomers as customer'=>'customer.customernr=tbltickets.customer',
                    'tblticketstatus'=>'tblticketstatus.id=tbltickets.ticketstatus',
                    'tblusers as createduser'=>'createduser.userid=tbltickets.userid')
            );
        }

        if(empty($data['ticket']['ticketnr'])){
            redirect(site_url('admin/tickets'));
        }

        //- On the Dashboard he should only see Tickets which belongs to the User who is logged in. (Customer and Salesman and Supporter and POS)
        if((
                (get_user_role()=='customer' && $data['ticket']['userid']!=get_user_id() && $data['ticket']['customer_id']!=get_user_id()) ||
                (isset($GLOBALS['current_user']) && ($GLOBALS['current_user']->userrole==3 || $GLOBALS['current_user']->userrole==5 || $GLOBALS['current_user']->userrole==6) && $data['ticket']['userid']!=get_user_id() && $data['ticket']['responsible_id']!=get_user_id())
            )){
            redirect(site_url('admin/tickets'));
        }
        //******************** End Initialise ********************/


        //******************** Initialise ********************/
        //Comments
        $data['comments'] = $this->Note_model->get("","tblnotes.*, CONCAT(tblusers.name,' ',tblusers.surname) as fullname",array('tblusers'=>'tblusers.userid=tblnotes.addedfrom')," tblnotes.rel_id='".$data['ticket']['ticketnr']."' AND tblnotes.rel_type='ticket' ","","tblnotes.id desc");
        //******************** End Initialise ********************/


        //Page Title
        $data['title'] = lang('page_detail_ticket');
        $this->load->view('admin/tickets/detail', $data);
    }

    /* Delete ticket */
    public function delete()
    {
        if(!$GLOBALS['ticket_permission']['delete'] || !$this->input->post('id')){
            access_denied('ticket');
        }

        $response = $this->Ticket_model->delete($this->input->post('id'));
        if ($response==1) {

            //History
            $Action_data = array('actionname'=>'ticket', 'actionid'=>$this->input->post('id'), 'actiontitle'=>'ticket_deleted');
            do_action_history($Action_data);

            set_alert('success', sprintf(lang('deleted_successfully'),lang('page_ticket')));
        }else{
            set_alert('danger', $response);
        }
        redirect(site_url('admin/tickets/'));
    }

    /* Upload Dropzone file by Ajax */
    public function uploadDocuments($id){
        handle_ticket_attachments($id);
        exit;
    }

    /* Get Uploaded Documents by Ajax */
    public function getDocuments($id){
        //******************** Initialise ********************/
        //Ticket
        $data['ticket'] = (array) $this->Ticket_model->get($id);
        //******************** End Initialise ********************/

        if(count($data['ticket']['attachments']) > 0) {
            $this->load->view('admin/tickets/tickets_attachments_template', array('attachments'=>$data['ticket']['attachments']));
        }
    }

    /* Delete Document by Ajax */
    public function deleteDocument(){
        if($this->input->post('id')){
            $response = $this->Ticket_model->delete_ticket_attachment($this->input->post('id'));
            if ($response==1) {
                echo json_encode(array('response'=>'success','message'=>sprintf(lang('deleted_successfully'),lang('page_ticketattachment'))));
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
        $path = get_upload_path_by_type('ticket') . $attachment->rel_id . '/' . $attachment->file_name;
        force_download($path, null);
    }

    /* Add a Comment by Ajax */
    public function addComment(){
        if ($this->input->post()) {
            $post = $this->input->post();
            $response = $this->Note_model->add($post);
            if (is_numeric($response) && $response>0) {

                //History
                $Action_data = array('actionname'=>'ticket', 'actionid'=>$post['rel_id'], 'actionsubid'=>$response, 'actiontitle'=>'ticket_comment_added');
                do_action_history($Action_data);

                //Reminder of Comment to Responsible, Teamwork
                $this->Ticket_model->sendReminder($response,'comment');

                echo json_encode(array('response'=>'success','message'=>sprintf(lang('added_successfully'),lang('page_ticketcomment'))));
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
                $Action_data = array('actionname'=>'ticket', 'actionid'=>$post['rel_id'], 'actionsubid'=>$id, 'actiontitle'=>'ticket_comment_updated');
                do_action_history($Action_data);

                echo json_encode(array('response'=>'success','message'=>sprintf(lang('updated_successfully'),lang('page_ticketcomment'))));
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
                $Action_data = array('actionname'=>'ticket', 'actionid'=>$this->input->post('parentid'), 'actionsubid'=>$this->input->post('id'), 'actiontitle'=>'ticket_comment_deleted');
                do_action_history($Action_data);

                echo json_encode(array('response'=>'success','message'=>sprintf(lang('deleted_successfully'),lang('page_ticketcomment'))));
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
        $data['comments'] = $this->Note_model->get("","tblnotes.*, CONCAT(tblusers.name,' ',tblusers.surname) as fullname",array('tblusers'=>'tblusers.userid=tblnotes.addedfrom')," tblnotes.rel_id='".$id."' AND tblnotes.rel_type='ticket' ","","tblnotes.id desc");
        //******************** End Initialise ********************/

        if(count($data['comments']) > 0) {
            $this->load->view('admin/tickets/tickets_comments_template', $data);
        }
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
}