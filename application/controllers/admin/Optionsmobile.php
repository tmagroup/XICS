<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Optionsmobile extends Admin_controller
{
    public function __construct()
    {
        parent::__construct();
        if(get_user_role()!='user'){
            redirect(site_url('admin/permission/denied/'));
        }
        $this->load->model('Optionmobile_model');
    }

    /* List all optionsmobile */
    public function index()
    {
        if(!$GLOBALS['optionmobile_permission']['view']){
            access_denied('optionmobile');
        }

        $data['title'] = lang('page_optionsmobile');
        $this->load->view('admin/optionsmobile/manage', $data);
    }

    /* List all optionsmobile by ajax */
    public function ajax()
    {
        $this->app->get_table_data('optionsmobile');
    }

    /* Add/Edit Optionmobile */
    public function option($id='')
    {
        if(!$GLOBALS['optionmobile_permission']['create'] && !$GLOBALS['optionmobile_permission']['edit']){
            access_denied('optionmobile');
        }

        //******************** Initialise ********************/
        if($id>0){
            //Optionmobile
            $data['optionmobile'] = (array) $this->Optionmobile_model->get($id);

            if(empty($data['optionmobile']['optionnr'])){
                redirect(site_url('admin/optionsmobile'));
            }
        }
        //******************** End Initialise ********************/

        //Submit Page
        if ($this->input->post()) {
            $post = $this->input->post();
            if(isset($data['optionmobile']['optionnr'])){
                $response = $this->Optionmobile_model->update($post, $data['optionmobile']['optionnr']);
                if (is_numeric($response) && $response>0) {

                    //History
                    $Action_data = array('actionname'=>'optionmobile', 'actionid'=>$response, 'actiontitle'=>'optionmobile_updated');
                    do_action_history($Action_data);

                    set_alert('success', sprintf(lang('updated_successfully'),lang('page_optionmobile')));
                    redirect(site_url('admin/optionsmobile/'));
                }
                else{
                    set_alert('danger', $response);
                }
            }
            else{
                $response = $this->Optionmobile_model->add($post);
                if (is_numeric($response) && $response>0) {

                    //History
                    $Action_data = array('actionname'=>'optionmobile', 'actionid'=>$response, 'actiontitle'=>'optionmobile_added');
                    do_action_history($Action_data);

                    set_alert('success', sprintf(lang('added_successfully'),lang('page_optionmobile')));
                    redirect(site_url('admin/optionsmobile/'));
                }
                else{
                    set_alert('danger', $response);
                }
            }

            //Initialise
            $optionnr = '';
            if(isset($data['optionmobile'])){
                $optionnr = $data['optionmobile']['optionnr'];
            }
            $data['optionmobile'] = $post;
            $data['optionmobile']['optionnr'] = $optionnr;
        }


        //Page Title
        if(isset($data['optionmobile']['optionnr']) && $data['optionmobile']['optionnr']>0){
            $data['title'] = lang('page_edit_optionmobile');
        }
        else{
            $data['title'] = lang('page_create_optionmobile');
        }


        $this->load->view('admin/optionsmobile/option', $data);
    }

    /* Delete option mobile */
    public function delete()
    {
        if(!$GLOBALS['optionmobile_permission']['delete'] || !$this->input->post('id')){
            access_denied('optionmobile');
        }

        $response = $this->Optionmobile_model->delete($this->input->post('id'));
        if ($response==1) {

            //History
            $Action_data = array('actionname'=>'optionmobile', 'actionid'=>$this->input->post('id'), 'actiontitle'=>'optionmobile_deleted');
            do_action_history($Action_data);

            set_alert('success', sprintf(lang('deleted_successfully'),lang('page_optionmobile')));
        }else{
            set_alert('danger', $response);
        }
        redirect(site_url('admin/optionsmobile/'));
    }

    /* Import Optionmobile */
    public function import(){
        if(!$GLOBALS['optionmobile_permission']['import']){
            access_denied('optionmobile');
        }

        //Initialize
        $data['file_name'] = 'tbloptionsmobile';
        $data['db_fields'] = $this->db->list_fields($data['file_name']);
        $data['not_importable'] = array('optionnr_prefix','created','updated');
        $data['sample_data'] = array(idprefix('optionmobile',1),'Sample Title','100');

        //Submit for Import
        if ($this->input->post()) {
            if($this->input->post('download_sample') === 'true'){
                //Download Sample CSV
                downloadsamplecsv($data, ';');
            }
            else{
                //Import CSV
                $response = $this->Optionmobile_model->importcsv($data);
                if ($response['status']==1) {

                    //History
                    $Action_data = array('actionname'=>'optionmobile', 'actiontitle'=>'optionmobile_imported');
                    do_action_history($Action_data);

                    set_alert('success', $response['message']);
                    redirect(site_url('admin/optionsmobile/'));
                }else{
                    set_alert('danger', $response['message']);
                }
            }
        }

        $data['title'] = lang('page_import_optionmobile');
        $this->load->view('admin/optionsmobile/import', $data);
    }

    public function export_excel() {
        $data = $this->Optionmobile_model->get('','optionnr_prefix,optiontitle,provider,price');
        $header = array(lang('page_dt_optionnr'),lang('page_dt_optiontitle'),lang('page_dt_provider'),lang('page_dt_price'));

        $filename = lang('page_manage_optionmobile').'_'.date('dmY').'.csv';
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
