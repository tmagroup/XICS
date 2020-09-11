<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Optionslandline extends Admin_controller
{
    public function __construct()
    {
        parent::__construct();
        if(get_user_role()!='user'){
            redirect(site_url('admin/permission/denied/'));
        }
        $this->load->model('Optionlandline_model');
        $this->load->model('Ratelandline_model');
    }

    /* List all optionslandline */
    public function index()
    {
        if(!$GLOBALS['optionlandline_permission']['view']){
            access_denied('optionlandline');
        }

        $data['title'] = lang('page_optionslandline');
        $this->load->view('admin/optionslandline/manage', $data);
    }

    /* List all optionslandline by ajax */
    public function ajax()
    {
        $this->app->get_table_data('optionslandline');
    }

    /* Add/Edit Optionlandline */
    public function option($id='')
    {
        if(!$GLOBALS['optionlandline_permission']['create'] && !$GLOBALS['optionlandline_permission']['edit']){
            access_denied('optionlandline');
        }

        //******************** Initialise ********************/
        if($id>0){
            //Optionlandline
            $data['optionlandline'] = (array) $this->Optionlandline_model->get($id);

            if(empty($data['optionlandline']['optionnr'])){
                redirect(site_url('admin/optionslandline'));
            }
        }
        //******************** End Initialise ********************/

        //Submit Page
        if ($this->input->post()) {
            $post = $this->input->post();
            if(isset($data['optionlandline']['optionnr'])){
                $response = $this->Optionlandline_model->update($post, $data['optionlandline']['optionnr']);
                if (is_numeric($response) && $response>0) {

                    //History
                    $Action_data = array('actionname'=>'optionlandline', 'actionid'=>$response, 'actiontitle'=>'optionlandline_updated');
                    do_action_history($Action_data);

                    set_alert('success', sprintf(lang('updated_successfully'),lang('page_optionlandline')));
                    redirect(site_url('admin/optionslandline/'));
                }
                else{
                    set_alert('danger', $response);
                }
            }
            else{
                $response = $this->Optionlandline_model->add($post);
                if (is_numeric($response) && $response>0) {

                    //History
                    $Action_data = array('actionname'=>'optionlandline', 'actionid'=>$response, 'actiontitle'=>'optionlandline_added');
                    do_action_history($Action_data);

                    set_alert('success', sprintf(lang('added_successfully'),lang('page_optionlandline')));
                    redirect(site_url('admin/optionslandline/'));
                }
                else{
                    set_alert('danger', $response);
                }
            }

            //Initialise
            $optionnr = '';
            if(isset($data['optionlandline'])){
                $optionnr = $data['optionlandline']['optionnr'];
            }
            $data['optionlandline'] = $post;
            $data['optionlandline']['optionnr'] = $optionnr;
        }

        //******************** Initialise ********************/
        //Rates Landline
        $data['rateslandline'] = $this->Ratelandline_model->get();
        $data['rateslandline'] = dropdown($data['rateslandline'],'ratenr','ratetitle');
        //******************** End Initialise ********************/

        //Page Title
        if(isset($data['optionlandline']['optionnr']) && $data['optionlandline']['optionnr']>0){
            $data['title'] = lang('page_edit_optionlandline');
        }
        else{
            $data['title'] = lang('page_create_optionlandline');
        }


        $this->load->view('admin/optionslandline/option', $data);
    }

    /* Delete option landline */
    public function delete()
    {
        if(!$GLOBALS['optionlandline_permission']['delete'] || !$this->input->post('id')){
            access_denied('optionlandline');
        }

        $response = $this->Optionlandline_model->delete($this->input->post('id'));
        if ($response==1) {

            //History
            $Action_data = array('actionname'=>'optionlandline', 'actionid'=>$this->input->post('id'), 'actiontitle'=>'optionlandline_deleted');
            do_action_history($Action_data);

            set_alert('success', sprintf(lang('deleted_successfully'),lang('page_optionlandline')));
        }else{
            set_alert('danger', $response);
        }
        redirect(site_url('admin/optionslandline/'));
    }

    /* Import Optionlandline */
    public function import(){
        if(!$GLOBALS['optionlandline_permission']['import']){
            access_denied('optionlandline');
        }

        //Initialize
        $data['file_name'] = 'tbloptionslandline';
        $data['db_fields'] = $this->db->list_fields($data['file_name']);
        $data['not_importable'] = array('optionnr_prefix','created','updated');
        $data['sample_data'] = array(idprefix('optionlandline',1),'Rates Landline Title','Sample Title','100');

        //Submit for Import
        if ($this->input->post()) {
            if($this->input->post('download_sample') === 'true'){
                //Download Sample CSV
                downloadsamplecsv($data);
            }
            else{
                //Import CSV
                $response = $this->Optionlandline_model->importcsv($data);
                if ($response['status']==1) {

                    //History
                    $Action_data = array('actionname'=>'optionlandline', 'actiontitle'=>'optionlandline_imported');
                    do_action_history($Action_data);

                    set_alert('success', $response['message']);
                    redirect(site_url('admin/optionslandline/'));
                }else{
                    set_alert('danger', $response['message']);
                }
            }
        }

        $data['title'] = lang('page_import_optionlandline');
        $this->load->view('admin/optionslandline/import', $data);
    }

    public function export_excel() {
        $data = $this->Optionlandline_model->get('','optionnr_prefix,optiontitle,price');
        $header = array(lang('page_dt_optionnr'),lang('page_dt_optiontitle'),lang('page_dt_price'));

        $filename = lang('page_manage_optionlandline').'_'.date('dmY').'.csv';
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
