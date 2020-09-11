<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Hardwareinvoices extends Admin_controller
{
    public function __construct()
    {
        parent::__construct();
        /*if(get_user_role()!='user'){
            redirect(site_url('admin/permission/denied/'));
        }*/
        $this->load->model('Hardwareassignmentinvoice_model');       
        $this->load->model('Hardwareassignmentinvoiceproduct_model');
        $this->load->model('Pdf_model');   
        $this->load->model('Email_model');   
    }

    /* List all hardwareinvoices */
    public function index()
    {        
        if(!$GLOBALS['hardwareinvoice_permission']['view'] && !$GLOBALS['hardwareinvoice_permission']['view_own']){
            access_denied('hardwareinvoice');
        }
        
        $data['title'] = lang('page_hardwareinvoices');
        $this->load->view('admin/hardwareinvoices/manage', $data);
    }
    
    /* List all hardwareinvoices by ajax */
    public function ajax()
    {
        /*if($name!=""){
            switch($name){
                case 'change_paid':
                    $post['is_paid'] = $status;
                    $this->Hardwareassignmentinvoice_model->update($post, $id);            
                break;
            }            
            exit;
        }*/    
        
        $this->app->get_table_data('hardwareinvoices');        
    }
    
    /* Change is Paid Status */
    public function change_paid($id='', $status=''){
        if($status==1){
            //History 
            $Action_data = array('actionname'=>'hardwareinvoice', 'actionid'=>$id, 'actiontitle'=>'hardwareinvoice_paid');
            do_action_history($Action_data);
        }else{
            //History 
            $Action_data = array('actionname'=>'hardwareinvoice', 'actionid'=>$id, 'actiontitle'=>'hardwareinvoice_unpaid');
            do_action_history($Action_data);
        }
        $this->db->query("UPDATE `tblhardwareassignmentinvoices` SET `is_paid`='".$status."' WHERE invoicenr='".$id."'");
        exit;
    }
    
    /* Delete hardwareinvoice */
    public function delete()
    {
        if(!$GLOBALS['hardwareinvoice_permission']['delete'] || !$this->input->post('id')){
            access_denied('hardwareinvoice');
        }
        
        $response = $this->Hardwareassignmentinvoice_model->delete($this->input->post('id'));
        if ($response==1) {
            
            //History 
            $Action_data = array('actionname'=>'hardwareinvoice', 'actionid'=>$this->input->post('id'), 'actiontitle'=>'hardwareinvoice_deleted');
            do_action_history($Action_data);
            
            set_alert('success', sprintf(lang('deleted_successfully'),lang('page_hardwareinvoice')));
        }else{
            set_alert('danger', $response);
        }            
        redirect(site_url('admin/hardwareinvoices/'));
        exit;
    }
    
    /* Print Hardware Invoice */
    public function printhardwareinvoice($id){
        $rowfield = (array)$this->Hardwareassignmentinvoice_model->get($id,"*,DATE_FORMAT(created,'%Y-%m-%d') as created");
        
        if(isset($rowfield['invoicenr'])){
            $data['invoice'] = $rowfield;
            $data['invoiceproducts'] = $this->Hardwareassignmentinvoiceproduct_model->get('','',array()," invoicenr='".$rowfield['invoicenr']."' ");
            
            //Footer Text
            $data = array_merge($data, $GLOBALS['currency_data'], $GLOBALS['company_data']);               
            $data['data'] = $data;        

            $this->Pdf_model->pdf_printhardwareinvoice($data);        
        }
        else{
            set_alert('error', sprintf(lang('failed'), lang('page_lb_print_invoice')));                    
            redirect(site_url('admin/hardwareinvoices/'));
        }
    }
    
    /* Send Email Hardware Invoice */
    public function sendEmail($id){
        //Invoice PDF send to Customer
        $data = (array)$this->Hardwareassignmentinvoice_model->get($id,"*, tblsalutations.name as salutation",array('tblsalutations'=>'tblsalutations.salutationid=tblhardwareassignmentinvoices.customer_salutation'));        
        $response = $this->Hardwareassignmentinvoice_model->sendEmail($data);
        if ($response==1) {
            
            //History 
            $Action_data = array('actionname'=>'hardwareinvoice', 'actionid'=>$id, 'actiontitle'=>'hardwareinvoice_sent');
            do_action_history($Action_data);
            
            echo json_encode(array('response'=>'success','message'=>sprintf(lang('sent_successfully'),lang('page_hardwareinvoice'))));
        }else{
            echo json_encode(array('response'=>'error','message'=>sprintf(lang('failed'),lang('page_hardwareinvoice'))));
        }
        exit;
    }
}