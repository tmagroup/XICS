<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Deliverynotes extends Admin_controller
{
    public function __construct()
    {
        parent::__construct();
        // if(get_user_role()!='user'){
        //     redirect(site_url('admin/permission/denied/'));
        // }
        $this->load->model('Hardwareassignmentshippingslip_model');
        $this->load->model('Hardwareassignmentshippingslipproduct_model');
        $this->load->model('Pdf_model');
    }

    /* List all deliverynotes */
    public function index()
    {
        if(!$GLOBALS['deliverynote_permission']['view']){
            access_denied('deliverynote');
        }

        $data['title'] = lang('page_deliverynotes');
        $this->load->view('admin/deliverynotes/manage', $data);
    }

    /* List all deliverynotes by ajax */
    public function ajax()
    {
        $this->app->get_table_data('deliverynotes');
    }

    /* Delete deliverynote */
    public function delete()
    {
        if(!$GLOBALS['deliverynote_permission']['delete'] || !$this->input->post('id')){
            access_denied('deliverynote');
        }

        $response = $this->Hardwareassignmentshippingslip_model->delete($this->input->post('id'));
        if ($response==1) {

            //History
            $Action_data = array('actionname'=>'deliverynote', 'actionid'=>$this->input->post('id'), 'actiontitle'=>'deliverynote_deleted');
            do_action_history($Action_data);

            set_alert('success', sprintf(lang('deleted_successfully'),lang('page_deliverynote')));
        }else{
            set_alert('danger', $response);
        }
        redirect(site_url('admin/deliverynotes/'));
        exit;
    }

    /* Print Delivery Note */
    public function printdeliverynote($id){
        $rowfield = (array)$this->Hardwareassignmentshippingslip_model->get($id,"*,DATE_FORMAT(created,'%Y-%m-%d') as created");

        if(isset($rowfield['shippingslipnr'])){
            $data['shippingslip'] = $rowfield;
            $data['shippingslipproducts'] = $this->Hardwareassignmentshippingslipproduct_model->get('','',array()," shippingslipnr='".$rowfield['shippingslipnr']."' ");

            //Footer Text
            $data = array_merge($data, $GLOBALS['currency_data'], $GLOBALS['company_data']);
            $data['data'] = $data;

            $this->Pdf_model->pdf_printdeliverynote2($data);
        }
        else{
            set_alert('error', sprintf(lang('failed'), lang('page_lb_print_deliverynote')));
            redirect(site_url('admin/deliverynotes/'));
        }
    }

    /* Get Shippingnr for Tracking by Ajax */
    public function getShippingnr($id){
        //Shipping Products
        $data['shippingslipproducts'] = $this->Hardwareassignmentshippingslipproduct_model->get('','', array()," shippingslipnr='".$id."' ");
        //******************** End Initialise ********************/
        $this->load->view('admin/deliverynotes/tracking_shippingnr', $data);
    }
}