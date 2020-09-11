<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Hardwares extends Admin_controller
{
    public function __construct()
    {
        parent::__construct();
        if(get_user_role()!='user'){
            redirect(site_url('admin/permission/denied/'));
        }
        $this->load->model('Hardware_model');
        $this->load->model('Hardwarecategory_model');
    }

    /* List all hardwares */
    public function index()
    {
        if(!$GLOBALS['hardware_permission']['view']){
            access_denied('hardware');
        }

        $data['title'] = lang('page_hardwares');
        $this->load->view('admin/hardwares/manage', $data);
    }

    /* List all hardwares by ajax */
    public function ajax()
    {
        $this->app->get_table_data('hardwares');
    }

    /* Add/Edit Hardware */
    public function hardware($id='')
    {
        if(!$GLOBALS['hardware_permission']['create'] && !$GLOBALS['hardware_permission']['edit']){
            access_denied('hardware');
        }

        //******************** Initialise ********************/
        if($id>0){
            //Hardware
            $data['hardware'] = (array) $this->Hardware_model->get($id);

            $data['hardware']['hardwareprice'] = str_replace('.', ',', $data['hardware']['hardwareprice']);

            if(empty($data['hardware']['hardwarenr'])){
                redirect(site_url('admin/hardwares'));
            }
        }
        //******************** End Initialise ********************/

        //Submit Page
        if ($this->input->post()) {
            $post = $this->input->post();
            $post['hardwareprice'] = str_replace(',', '.', $post['hardwareprice']);
            if(isset($data['hardware']['hardwarenr'])){
                $response = $this->Hardware_model->update($post, $data['hardware']['hardwarenr']);
                if (is_numeric($response) && $response>0) {

                    //History
                    $Action_data = array('actionname'=>'hardware', 'actionid'=>$response, 'actiontitle'=>'hardware_updated');
                    do_action_history($Action_data);

                    set_alert('success', sprintf(lang('updated_successfully'),lang('page_hardware')));
                    redirect(site_url('admin/hardwares/'));
                }
                else{
                    set_alert('danger', $response);
                }
            }
            else{
                $response = $this->Hardware_model->add($post);
                if (is_numeric($response) && $response>0) {

                    //History
                    $Action_data = array('actionname'=>'hardware', 'actionid'=>$response, 'actiontitle'=>'hardware_added');
                    do_action_history($Action_data);

                    set_alert('success', sprintf(lang('added_successfully'),lang('page_hardware')));
                    redirect(site_url('admin/hardwares/'));
                }
                else{
                    set_alert('danger', $response);
                }
            }

            //Initialise
            $hardwarenr = '';
            if(isset($data['hardware'])){
                $hardwarenr = $data['hardware']['hardwarenr'];
            }
            $data['hardware'] = $post;
            $data['hardware']['hardwarenr'] = $hardwarenr;
        }

        //******************** Initialise ********************/
        //Hardwarecategories
        $data['hardwarecategories'] = $this->Hardwarecategory_model->get();
        $data['hardwarecategories'] = dropdown($data['hardwarecategories'],'id','name');
        //******************** End Initialise ********************/

        //Page Title
        if(isset($data['hardware']['hardwarenr']) && $data['hardware']['hardwarenr']>0){
            $data['title'] = lang('page_edit_hardware');
        }
        else{
            $data['title'] = lang('page_create_hardware');
        }


        $this->load->view('admin/hardwares/hardware', $data);
    }

    /* Delete hardware */
    public function delete()
    {
        if(!$GLOBALS['hardware_permission']['delete'] || !$this->input->post('id')){
            access_denied('hardware');
        }

        $response = $this->Hardware_model->delete($this->input->post('id'));
        if ($response==1) {

            //History
            $Action_data = array('actionname'=>'hardware', 'actionid'=>$this->input->post('id'), 'actiontitle'=>'hardware_deleted');
            do_action_history($Action_data);

            set_alert('success', sprintf(lang('deleted_successfully'),lang('page_hardware')));
        }else{
            set_alert('danger', $response);
        }
        redirect(site_url('admin/hardwares/'));
    }

    /* Import Hardware */
    public function import(){
        if(!$GLOBALS['hardware_permission']['import']){
            access_denied('hardware');
        }

        //Initialize
        $data['file_name'] = 'tblhardwares';
        $data['db_fields'] = $this->db->list_fields($data['file_name']);
        $data['not_importable'] = array('hardwarenr_prefix','created','updated');
        $data['sample_data'] = array(idprefix('hardware',1),'Sample Title','100');

        //Submit for Import
        if ($this->input->post()) {
            if($this->input->post('download_sample') === 'true'){
                //Download Sample CSV
                downloadsamplecsv($data);
            }
            else{
                //Import CSV
                $response = $this->Hardware_model->importcsv($data);
                if ($response['status']==1) {

                    //History
                    $Action_data = array('actionname'=>'hardware', 'actiontitle'=>'hardware_imported');
                    do_action_history($Action_data);

                    set_alert('success', $response['message']);
                    redirect(site_url('admin/hardwares/'));
                }else{
                    set_alert('danger', $response['message']);
                }
            }
        }

        $data['title'] = lang('page_import_hardware');
        $this->load->view('admin/hardwares/import', $data);
    }

    public function export_excel() {
        $data = $this->Hardware_model->get('','hardwarenr_prefix,hardwaretitle,hardwareprice');
        $header = array(lang('page_dt_hardwarenr'),lang('page_dt_hardwaretitle'),lang('page_dt_hardwareprice'));

        $filename = lang('page_manage_hardware').'_'.date('dmY').'.csv';
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
