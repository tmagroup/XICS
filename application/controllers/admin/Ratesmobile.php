<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Ratesmobile extends Admin_controller
{
    public function __construct()
    {
        parent::__construct();
        if(get_user_role()!='user'){
            if($this->uri->segment(3)!='getInputUltraCard' && $this->uri->segment(3)!='getoldInputSimcardFunction' && $this->uri->segment(3)!='getnewInputSimcardFunction'){
                redirect(site_url('admin/permission/denied/'));
            }
        }
        $this->load->model('Ratemobile_model');
        $this->load->model('Simcardfunction_model');
        $this->load->model('Sub_model');
        $this->load->model('Mobileflaterate_model');
        $this->load->model('Landlineflaterate_model');
        $this->load->model('Vodafoneflaterate_model');
        $this->load->model('Euroaming_model');
        $this->load->model('Smsflaterate_model');
        $this->load->model('Ultracard_model');
        $this->load->model('Field_model');
    }

    /* List all ratesmobile */
    public function index()
    {
        if(!$GLOBALS['ratemobile_permission']['view']){
            access_denied('ratemobile');
        }

        $data['title'] = lang('page_ratesmobile');
        $this->load->view('admin/ratesmobile/manage', $data);
    }

    /* List all ratesmobile by ajax */
    public function ajax()
    {
        $this->app->get_table_data('ratesmobile');
    }

    /* Change is shop Status */
    public function change_shop($id='', $status=''){
        if($status==1){
            //History
            $Action_data = array('actionname'=>'ratemobile', 'actionid'=>$id, 'actiontitle'=>'ratemobile_shop_activated');
            do_action_history($Action_data);
        }else{
            //History
            $Action_data = array('actionname'=>'ratemobile', 'actionid'=>$id, 'actiontitle'=>'ratemobile_shop_deactivated');
            do_action_history($Action_data);
        }
        $this->db->query("UPDATE `tblratesmobile` SET `shop`='".$status."' WHERE ratenr='".$id."'");
        exit;
    }

    /* Add/Edit Ratemobile */
    public function rate($id='')
    {
        if(!$GLOBALS['ratemobile_permission']['create'] && !$GLOBALS['ratemobile_permission']['edit']){
            access_denied('ratemobile');
        }

        //******************** Initialise ********************/
        if($id>0){
            //Ratemobile
            $data['ratemobile'] = (array) $this->Ratemobile_model->get($id);

            if(empty($data['ratemobile']['ratenr'])){
                redirect(site_url('admin/ratesmobile'));
            }
        }
        //******************** End Initialise ********************/

        //Submit Page
        if ($this->input->post()) {
            $post = $this->input->post();
            if(isset($data['ratemobile']['ratenr'])){
                $response = $this->Ratemobile_model->update($post, $data['ratemobile']['ratenr']);
                if (is_numeric($response) && $response>0) {

                    //History
                    $Action_data = array('actionname'=>'ratemobile', 'actionid'=>$response, 'actiontitle'=>'ratemobile_updated');
                    do_action_history($Action_data);

                    set_alert('success', sprintf(lang('updated_successfully'),lang('page_ratemobile')));
                    redirect(site_url('admin/ratesmobile/'));
                }
                else{
                    set_alert('danger', $response);
                }
            }
            else{
                $response = $this->Ratemobile_model->add($post);
                if (is_numeric($response) && $response>0) {

                    //History
                    $Action_data = array('actionname'=>'ratemobile', 'actionid'=>$response, 'actiontitle'=>'ratemobile_added');
                    do_action_history($Action_data);

                    set_alert('success', sprintf(lang('added_successfully'),lang('page_ratemobile')));
                    redirect(site_url('admin/ratesmobile/'));
                }
                else{
                    set_alert('danger', $response);
                }
            }

            //Initialise
            $ratenr = '';
            if(isset($data['ratemobile'])){
                $ratenr = $data['ratemobile']['ratenr'];
            }
            $data['ratemobile'] = $post;
            $data['ratemobile']['ratenr'] = $ratenr;
        }


        //******************** Initialise ********************/
        //Simcard Functions
        $data['simcardfunctions'] = $this->Simcardfunction_model->get();
        $data['simcardfunctions'] = dropdown($data['simcardfunctions'],'id','name');
        //Subs
        $data['subs'] = $this->Sub_model->get();
        $data['subs'] = dropdown($data['subs'],'id','name');
        //Mobile Flate Rates
        $data['mobileflaterates'] = $this->Mobileflaterate_model->get();
        $data['mobileflaterates'] = dropdown($data['mobileflaterates'],'id','name');
        //Landing Flate Rates
        $data['landlineflaterates'] = $this->Landlineflaterate_model->get();
        $data['landlineflaterates'] = dropdown($data['landlineflaterates'],'id','name');
        //Vodafone Flate Rates
        $data['vodafoneflaterates'] = $this->Vodafoneflaterate_model->get();
        $data['vodafoneflaterates'] = dropdown($data['vodafoneflaterates'],'id','name');
        //Eu Roamings
        $data['euroamings'] = $this->Euroaming_model->get();
        $data['euroamings'] = dropdown($data['euroamings'],'id','name');
        //Sms Flate Rates
        $data['smsflaterates'] = $this->Smsflaterate_model->get();
        $data['smsflaterates'] = dropdown($data['smsflaterates'],'id','name');
        //Ultracards
        $data['ultracards'] = $this->Ultracard_model->get();
        $data['ultracards'] = dropdown($data['ultracards'],'id','name');
        //Extra Fields
        $data['extrafields'] = $this->Field_model->get('ratemobile',isset($data['ratemobile']['ratenr'])?$data['ratemobile']['ratenr']:'');
        //******************** End Initialise ********************/


        //Page Title
        if(isset($data['ratemobile']['ratenr']) && $data['ratemobile']['ratenr']>0){
            $data['title'] = lang('page_edit_ratemobile');
        }
        else{
            $data['title'] = lang('page_create_ratemobile');
        }


        $this->load->view('admin/ratesmobile/rate', $data);
    }

    /* Delete rate mobile */
    public function delete()
    {
        if(!$GLOBALS['ratemobile_permission']['delete'] || !$this->input->post('id')){
            access_denied('ratemobile');
        }

        $response = $this->Ratemobile_model->delete($this->input->post('id'));
        if ($response==1) {

            //History
            $Action_data = array('actionname'=>'ratemobile', 'actionid'=>$this->input->post('id'), 'actiontitle'=>'ratemobile_deleted');
            do_action_history($Action_data);

            set_alert('success', sprintf(lang('deleted_successfully'),lang('page_ratemobile')));
        }else{
            set_alert('danger', $response);
        }
        redirect(site_url('admin/ratesmobile/'));
    }

    /* Import Ratemobile */
    public function import(){
        if(!$GLOBALS['ratemobile_permission']['import']){
            access_denied('ratemobile');
        }

        //Initialize
        $data['file_name'] = 'tblratesmobile';
        $data['db_fields'] = $this->db->list_fields($data['file_name']);

		/*NEW CODE 18 Apr 2018 */
		foreach($data['db_fields'] as $dkey=>$db_field){
			if($db_field=='shop'){ unset($data['db_fields'][$dkey]); }
		}
		/*END NEW CODE 18 Apr 2018 */

        $extrafields = $this->Field_model->get('ratemobile');
        if(isset($extrafields) && count($extrafields)>0){
            foreach($extrafields as $extrafield){
                $data['db_fields'][] = trim($extrafield['field_name']);
            }
        }

        //$data['not_importable'] = array('ratenr_prefix','created','updated');
        //$data['sample_data'] = array(idprefix('ratemobile',1),'Sample Title','100','Data','Ja','Nein','Ja','Nein','1000','Ja','Nein','10');
        $data['not_importable'] = array('ratenr','ratenr_prefix','created','updated');
        $data['sample_data'] = array('Sample Title','100','Data','Ja','Nein','Ja','Nein','1000','Ja','Nein','10','ja'); //NEW CODE 18 Apr 2018
        if(isset($extrafields) && count($extrafields)>0){
            foreach($extrafields as $extrafield){
                $data['sample_data'][] = rand(0,100);
            }
        }

        //Submit for Import
        if ($this->input->post()) {
            if($this->input->post('download_sample') === 'true'){
                //Download Sample CSV
                downloadsamplecsv($data);
            }
            else{
                //Import CSV
                $response = $this->Ratemobile_model->importcsv($data);
                if ($response['status']==1) {

                    //History
                    $Action_data = array('actionname'=>'ratemobile', 'actiontitle'=>'ratemobile_imported');
                    do_action_history($Action_data);

                    set_alert('success', $response['message']);
                    redirect(site_url('admin/ratesmobile/'));
                    exit;
                }else{
                    set_alert('danger', $response['message']);
                }
            }
        }

        $data['title'] = lang('page_import_ratemobile');
        $this->load->view('admin/ratesmobile/import', $data);
    }

    //Get Inputbox Quantiy of function call from Quotation by Ajax
    public function getnewInputSimcardFunction($pkey, $id=''){
        $simfunction = $this->Ratemobile_model->get($id,'tblsimcardfunctions.*',array(
                'tblsimcardfunctions'=>'tblsimcardfunctions.id=tblratesmobile.simcard_function'
            )
        );
        if(isset($simfunction->id)){
            echo '<table style="display:none"><tr><td><label>'.lang('page_fl_fqty'.$simfunction->id).': </label></td><td><input type="hidden" name="simcard_function_id['.$pkey.']" value="'.$simfunction->id.'" class="form-control"><input type="hidden" name="simcard_function_nm['.$pkey.']" value="'.$simfunction->name.'" class="form-control"><input type="hidden" name="simcard_function_qty['.$pkey.']" value="1" class="form-control"></td></tr></table>';
        }
        else{
            echo '';
        }
        exit;
    }

    //Get Inputbox Quantiy of function call from Quotation by Ajax
    public function getoldInputSimcardFunction($pkey ,$id='', $nm='', $qty=''){
        if($id!=""){
            echo '<table style="display:none;"><tr><td><label>'.lang('page_fl_fqty'.$id).': </label></td><td><input type="hidden" name="simcard_function_id['.$pkey.']" value="'.$id.'" class="form-control"><input type="hidden" name="simcard_function_nm['.$pkey.']" value="'.$nm.'" class="form-control"><input type="number" name="simcard_function_qty['.$pkey.']" value="1" class="form-control"></td></tr></table>';
        }
        else{
            echo '';
        }
        exit;
    }

    public function getInputUltraCard($id=''){
        $row = $this->Ratemobile_model->get($id,'ultracard');
        if(isset($row->ultracard) && $row->ultracard==1){
            echo '1';
        }else{
            echo '0';
        }
        exit;
    }

    public function export_excel() {
        $data = $this->Ratemobile_model->get('','ratenr_prefix,ratetitle,provider,price');
        $header = array(lang('page_dt_ratenr'),lang('page_dt_ratetitle'),lang('page_dt_provider'),lang('page_dt_price'));

        $filename = lang('page_ratesmobile').'_'.date('dmY').'.csv';
        header('Content-Type: application/csv');
        header('Content-Disposition: attachment; filename="'.$filename.'";');

        $f = fopen('php://output', 'w');
        foreach ($data as $key => $value) {
            if ($key==0) {
                fputcsv($f, array_combine(array_keys($value), $header), ',');
            }
            fputcsv($f, $value, ',');
        }
    }
}
