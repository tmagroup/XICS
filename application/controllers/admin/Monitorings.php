<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Monitorings extends Admin_controller
{
    public function __construct()
    {
        parent::__construct();
        /*if(get_user_role()!='user'){
            redirect(site_url('admin/permission/denied/'));
        }*/
        $this->load->model('Monitoring_model');
        $this->load->model('Monitoringstatus_model');
        $this->load->model('Monitoringassignmentstatus_model');
        $this->load->model('Note_model');
        $this->load->model('Customer_model');
        $this->load->model('Pdf_model');
    }

    /* List all monitorings */
    public function index()
    {
        if(!$GLOBALS['monitoring_permission']['view'] && !$GLOBALS['monitoring_permission']['view_own']){
            access_denied('monitoring');
        }

        if(get_user_role()=='customer'){
            if(!$GLOBALS['current_user']->monitoring){
                access_denied('monitoring');
            }
        }

        //******************** Initialise ********************/
        //Responsibles (Company of Customer)
        $data['filter_responsible'] = $this->Customer_model->get('',"company, company");
        $data['filter_responsible'] = dropdown($data['filter_responsible'],'company','company');

        //Monitoringstatus
        $data['filter_monitoringstatus'] = $this->Monitoringstatus_model->get();
        $data['filter_monitoringstatus'] = dropdown($data['filter_monitoringstatus'],'id','name');
        //******************** End Initialise ********************/

        $data['title'] = lang('page_monitorings');
        $this->load->view('admin/monitorings/manage', $data);
    }

    /* List all monitorings by ajax */
    public function ajax($filter_responsible='',$filter_monitoringstatus='')
    {
        //Filter By responsible, monitoringstatus
        $params = array('filter_responsible'=>$filter_responsible,'filter_monitoringstatus'=>$filter_monitoringstatus);
        $this->app->get_table_data('monitorings',$params);
    }

    /* Add/Edit Monitoring */
    public function monitoring($id='')
    {
        if(!$GLOBALS['monitoring_permission']['edit']){
            access_denied('monitoring');
        }

        if(get_user_role()=='customer'){
            if(!$GLOBALS['current_user']->monitoring){
                access_denied('monitoring');
            }
        }

        //******************** Initialise ********************/
        if($id>0){
            //Monitoring
            $data['monitoring'] = (array) $this->Monitoring_model->get($id,"tblmonitorings.*, CONCAT(customer.name,' ',customer.surname) as customer, CONCAT(responsible.name,' ',responsible.surname) as responsible, assignment.assignmentnr_prefix",
                    array('tblusers as responsible'=>'responsible.userid=tblmonitorings.responsible',
                    'tblcustomers as customer'=>'customer.customernr=tblmonitorings.customer',
                    'tblmonitoringstatus'=>'tblmonitoringstatus.id=tblmonitorings.monitoringstatus',
                    'tblassignments as assignment'=>'assignment.assignmentnr=tblmonitorings.assignmentnr',
                    )
            );

            // echo '<pre>';
            // print_r($data['monitoring']);
            // die();
        }

        if(empty($data['monitoring']['monitoringnr'])){
            redirect(site_url('admin/monitoring'));
        }
        //******************** End Initialise ********************/

        //Submit Page
        if ($this->input->post()) {
            $post = $this->input->post();

            $response = $this->Monitoring_model->update($post, $data['monitoring']['monitoringnr']);
            if (is_numeric($response) && $response>0) {

                //History
                $Action_data = array('actionname'=>'monitoring', 'actionid'=>$response, 'actiontitle'=>'monitoring_updated');
                do_action_history($Action_data);

                set_alert('success', sprintf(lang('updated_successfully'),lang('page_monitoring')));
                redirect(site_url('admin/monitorings/detail/' . $data['monitoring']['monitoringnr']));
            }
            else{
                set_alert('danger', $response);
            }

            //Initialise
            $monitoringnr = '';
            if(isset($data['monitoring'])){
                $monitoringnr = $data['monitoring']['monitoringnr'];
            }
            $data['monitoring'] = $post;
            $data['monitoring']['monitoringnr'] = $monitoringnr;
        }


        //******************** Initialise ********************/
        //Monitoringstatus
        $data['monitoringstatus'] = $this->Monitoringstatus_model->get();
        $data['monitoringstatus'] = dropdown($data['monitoringstatus'],'id','name');

        //Monitoringassignmentstatus
        $data['monitoringassignmentstatus'] = $this->Monitoringassignmentstatus_model->get();
        $data['monitoringassignmentstatus'] = dropdown($data['monitoringassignmentstatus'],'id','name', lang('page_option_choose'));
        //******************** End Initialise ********************/


        //Page Title
        $data['title'] = lang('page_edit_monitoring');
        $this->load->view('admin/monitorings/monitoring', $data);
    }

    /* Detail Monitoring */
    public function detail($id='')
    {
        if(!$GLOBALS['monitoring_permission']['view'] && !$GLOBALS['monitoring_permission']['view_own']){
            access_denied('monitoring');
        }

        if(get_user_role()=='customer'){
            if(!$GLOBALS['current_user']->monitoring){
                access_denied('monitoring');
            }
        }

        //******************** Initialise ********************/
        if($id>0){
            //Monitoring
            $data['monitoring'] = (array) $this->Monitoring_model->get($id,"tblmonitorings.*, CONCAT(customer.name,' ',customer.surname) as customer, CONCAT(responsible.name,' ',responsible.surname) as responsible, tblmonitorings.responsible as responsible_id, "
                    . " tblmonitorings.monitoringstatus,"
                    . " tblmonitoringstatus.name as monitoringstatusname,"
                    . " assignment.assignmentnr_prefix ",

                    array('tblusers as responsible'=>'responsible.userid=tblmonitorings.responsible',
                    'tblcustomers as customer'=>'customer.customernr=tblmonitorings.customer',
                    'tblmonitoringstatus'=>'tblmonitoringstatus.id=tblmonitorings.monitoringstatus',
                    'tblassignments as assignment'=>'assignment.assignmentnr=tblmonitorings.assignmentnr',
                    )
            );
        }

        if(empty($data['monitoring']['monitoringnr'])){
            redirect(site_url('admin/monitorings'));
        }
        //******************** End Initialise ********************/


        //******************** Initialise ********************/
        //Comments
        $data['comments'] = $this->Note_model->get("","tblnotes.*, CONCAT(tblusers.name,' ',tblusers.surname) as fullname",array('tblusers'=>'tblusers.userid=tblnotes.addedfrom')," tblnotes.rel_id='".$data['monitoring']['monitoringnr']."' AND tblnotes.rel_type='monitoring' ","","tblnotes.id desc");
        //******************** End Initialise ********************/


        //Page Title
        $data['title'] = lang('page_detail_monitoring');
        $this->load->view('admin/monitorings/detail', $data);
    }

    /* Delete monitoring */
    public function delete()
    {
        if(!$GLOBALS['monitoring_permission']['delete'] || !$this->input->post('id')){
            access_denied('monitoring');
        }

        if(get_user_role()=='customer'){
            if(!$GLOBALS['current_user']->monitoring){
                access_denied('monitoring');
            }
        }

        $response = $this->Monitoring_model->delete($this->input->post('id'));
        if ($response==1) {

            //History
            $Action_data = array('actionname'=>'monitoring', 'actionid'=>$this->input->post('id'), 'actiontitle'=>'monitoring_deleted');
            do_action_history($Action_data);

            set_alert('success', sprintf(lang('deleted_successfully'),lang('page_monitoring')));
        }else{
            set_alert('danger', $response);
        }
        redirect(site_url('admin/monitorings/'));
    }

    /* Add a Comment by Ajax */
    public function addComment(){
        if ($this->input->post()) {
            $post = $this->input->post();
            $response = $this->Note_model->add($post);
            if (is_numeric($response) && $response>0) {

                //History
                $Action_data = array('actionname'=>'monitoring', 'actionid'=>$post['rel_id'], 'actionsubid'=>$response, 'actiontitle'=>'monitoring_comment_added');
                do_action_history($Action_data);

                echo json_encode(array('response'=>'success','message'=>sprintf(lang('added_successfully'),lang('page_monitoringcomment'))));
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
                $Action_data = array('actionname'=>'monitoring', 'actionid'=>$post['rel_id'], 'actionsubid'=>$id, 'actiontitle'=>'monitoring_comment_updated');
                do_action_history($Action_data);

                echo json_encode(array('response'=>'success','message'=>sprintf(lang('updated_successfully'),lang('page_monitoringcomment'))));
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
                $Action_data = array('actionname'=>'monitoring', 'actionid'=>$this->input->post('parentid'), 'actionsubid'=>$this->input->post('id'), 'actiontitle'=>'monitoring_comment_deleted');
                do_action_history($Action_data);

                echo json_encode(array('response'=>'success','message'=>sprintf(lang('deleted_successfully'),lang('page_monitoringcomment'))));
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
        $data['comments'] = $this->Note_model->get("","tblnotes.*, CONCAT(tblusers.name,' ',tblusers.surname) as fullname",array('tblusers'=>'tblusers.userid=tblnotes.addedfrom')," tblnotes.rel_id='".$id."' AND tblnotes.rel_type='monitoring' ","","tblnotes.id desc");
        //******************** End Initialise ********************/

        if(count($data['comments']) > 0) {
            $this->load->view('admin/monitorings/monitorings_comments_template', $data);
        }
    }

    //Generate Monitoring Job
    public function generatemonitoringjob(){
        /*if(date('d')=='10'){
            $dataCustomers = $this->Customer_model->get('','',array()," monitoring=1 ");
            if(isset($dataCustomers) && count($dataCustomers)>0){
                foreach($dataCustomers as $dataCustomer){
                    $dataMonitoring = $this->Monitoring_model->get("","",array()," customer='".$dataCustomer['customernr']."' ");

                    if(!$dataMonitoring){
                        $post = array(
                            'customer' => $dataCustomer['customernr'],
                            'responsible' => $dataCustomer['responsible'],
                            'date' => _d(date("Y-m-d")),
                            'monitoringstatus' => 1, //Erstellt
                            'company' => $dataCustomer['company'],
                            'monitoringlink' => $dataCustomer['monitoringlink'],
                            'monitoringuser' => $dataCustomer['monitoringuser'],
                            'monitoringpass' => $dataCustomer['monitoringpass']
                        );
                        $this->Monitoring_model->add($post);
                    }
                }
            }
        }*/

        if(date('d')=='10'){
            $dataCustomers = $this->Customer_model->get('','tblcustomers.*, tblassignments.assignmentnr',array('tblassignments'=>'tblassignments.customer=tblcustomers.customernr')," tblcustomers.monitoring=1 ");
            if(isset($dataCustomers) && count($dataCustomers)>0){
                foreach($dataCustomers as $dataCustomer){
                    $dataMonitoring = $this->Monitoring_model->get("","",array()," assignmentnr='".$dataCustomer['assignmentnr']."' ");
                    if(!$dataMonitoring){
                        $post = array(
                            'customer' => $dataCustomer['customernr'],
                            'responsible' => $dataCustomer['responsible'],
                            'date' => date("Y-m-d"),
                            'monitoringstatus' => 1, //Erstellt
                            'company' => $dataCustomer['company'],
                            'monitoringlink' => $dataCustomer['monitoringlink'],
                            'monitoringuser' => $dataCustomer['monitoringuser'],
                            'monitoringpass' => $dataCustomer['monitoringpass'],
                            'monitoringvalue' => $dataCustomer['monitoringvalue'],
                            'assignmentnr' => $dataCustomer['assignmentnr']
                        );
                        $this->Monitoring_model->add($post);
                    }
                }
            }
        }
    }


    /* Import Assignment Positions by Ajax */
    public function import(){
        if(!$GLOBALS['monitoring_permission']['edit']){
            access_denied('monitoring');
        }

        //Submit for Import
        if ($this->input->post()) {
            $post = $this->input->post();

            //Import CSV
            $response = $this->Monitoring_model->importcsv($post);
            if ($response['status']==1) {

                //History
                $Action_data = array('actionname'=>'monitoring', 'actionid'=>$post['monitoringId'], 'actiontitle'=>'monitoring_imported');
                do_action_history($Action_data);

                echo json_encode(array('response'=>'success','message'=>$response['message']));
            }else{
                echo json_encode(array('response'=>'error','message'=>$response['message']));
            }
        }

        exit;
    }

    /* Change Monitoring Position Status */
    public function changeMonitoringAssignmentStatus(){
        if(!$GLOBALS['monitoring_permission']['edit']){
            access_denied('monitoring');
        }

        if ($this->input->post()) {
            $post = $this->input->post();
            $mData['costincurredby'] = $post['costincurredby'];
            $mData['updated'] = date('Y-m-d H:i:s');
            $this->db->where('id', $post['positionid']);
            $this->db->update('tblmonitoringassignments', $mData);

            //History
            $Action_data = array('actionname'=>'monitoring', 'actionid'=>$post['monitoringId'], 'actiontitle'=>'monitoring_positioncostupdated');
            do_action_history($Action_data);

            echo json_encode(array('response'=>'success','message'=>sprintf(lang('updated_successfully'),lang('page_lb_cost_incurred_by'))));
        }else{
            echo json_encode(array('response'=>'error','message'=>sprintf(lang('failed'),lang('page_lb_cost_incurred_by'))));
        }

        exit;
    }


    //Print Monitoring Protocol
    public function printmonitoringprotocol($id){
        /*if(!$GLOBALS['monitoring_permission']['edit']){
            access_denied('monitoring');
        }*/

        //Monitoring
        $data['monitoring'] = (array) $this->Monitoring_model->get($id,"tblmonitorings.*, "

                . " customer.company as customer_company, "
                . " customer.customernr_prefix, "
                . " customer.street as customer_street, "
                . " customer.zipcode as customer_zipcode, "
                . " customer.city as customer_city,"

                . " CONCAT(responsible.name,' ',responsible.surname) as responsible, "
                . " responsible.email as responsible_email, "

                . " (SELECT COUNT(*) FROM tblmonitoringassignments WHERE monitoringnr=tblmonitorings.monitoringnr) as compare1, "
                . " (SELECT COUNT(*) FROM tblmonitoringassignments WHERE monitoringnr=tblmonitorings.monitoringnr and (!ISNULL(costincurredby) OR costincurredby!=0)) as compare2 ",

                array('tblusers as responsible'=>'responsible.userid=tblmonitorings.responsible',
                'tblcustomers as customer'=>'customer.customernr=tblmonitorings.customer',
                'tblmonitoringstatus'=>'tblmonitoringstatus.id=tblmonitorings.monitoringstatus',
                'tblassignments as assignment'=>'assignment.assignmentnr=tblmonitorings.assignmentnr',
                )
        );

        if(empty($data['monitoring']['monitoringnr'])){
            redirect(site_url('admin/monitorings'));
            exit;
        }

        //This button should be seen after all Selectboxes are unequel "Auswählen" and status was changed to "Erledigt"
        if($data['monitoring']['compare1']==$data['monitoring']['compare2'] && $data['monitoring']['compare1']>0 && $data['monitoring']['compare2']>0 && $data['monitoring']['monitoringstatus']==3){
        }else{
            redirect(site_url('admin/monitorings'));
            exit;
        }

        //Footer Text
        $data = array_merge($data, $GLOBALS['currency_data'], $GLOBALS['company_data']);
        $data['data'] = $data;
        $this->Pdf_model->pdf_printmonitoringprotocol($data);
    }

     /* Import Second Csv Assignment Positions by Ajax */
    public function importsecond()
    {
        if(!$GLOBALS['monitoring_permission']['edit']){
            access_denied('monitoring');
        }

        //Submit for Import
        if ($this->input->post()) {
            $post = $this->input->post();

            // echo "hhel"; die();
            //Import CSV
            $response = $this->Monitoring_model->importcsvSecond($post);

            if ($response['status']==1) {
                $Action_data = array('actionname'=>'monitoring', 'actionid'=>$post['monitoringId'], 'actiontitle'=>'monitoring_imported');
                do_action_history($Action_data);

                echo json_encode(array('response'=>'success','message'=>$response['message']));
            } else {
                echo json_encode(array('response'=>'error','message'=>$response['message']));
            }
        }

        exit;
    }

    public function csvMonitoringData()
    {
        if ($this->input->server('REQUEST_METHOD') == 'POST') {
            $columns = array(
                0 => 'id',
                1 => 'monitoringnr',
                2 => 'mobilenr',
                3 => 'simnr',
            );

            $id = $this->input->post('id');
            $limit = $this->input->post('length');
            $start = $this->input->post('start');
            $order = $columns[$this->input->post('order')[0]['column']];
            $dir = $this->input->post('order')[0]['dir'];

            $param  = array();

            $field = 'pc.*,tb.monitoringnr_prefix';
            $param['where'] = array('pc.monitoringnr'=>$id);
            // print_r($param['limit']); die();
            $param['order_by'] = array($order,$dir);
            $param['join'] = array(
                            'tblmonitorings tb'=>'tb.monitoringnr=pc.monitoringnr'
                            );
            $totalFiltered = count($this->select_record('tblassignmentproducts_csv as pc',$field,$param));
            $param['limit'] = array($limit,$start);
            if(empty($this->input->post('search')['value']))
            {
                $posts = $this->select_record('tblassignmentproducts_csv as pc',$field,$param);

            } else {
                $search = trim($this->input->post('search')['value']);

                if($search != '') {
                    $param['like'] = array('pc.connection',$search);
                }
                $posts = $this->select_record('tblassignmentproducts_csv as pc',$field,$param);

                $totalFiltered = count($posts);
            }

            $data = array();
            if(!empty($posts))
            {
                $no = $start;
                foreach ($posts as $key => $post)
                {
                    $nestedData['id'] = $no+1;
                    $nestedData['monitoringnr'] = $post['monitoringnr_prefix'];
                    $nestedData['mobilenr'] = $post['mobilenr'];
                    $nestedData['simnr'] = $post['simnr'];

                    $data[] = $nestedData;
                    $no++;
                }
            }

            $json_data = array(
                "draw"            => intval($this->input->post('draw')),
                "recordsTotal"    => intval($totalFiltered),
                "recordsFiltered" => intval($totalFiltered),
                "data"            => $data
            );
            echo json_encode($json_data);die();
        }
    }

    public function exportPdf($id)
    {
        if($id > 0) {
            $data = array();
           $data['data'] = (array) $this->Monitoring_model->get($id,"tblmonitorings.*, CONCAT(customer.name,' ',customer.surname) as customer, CONCAT(responsible.name,' ',responsible.surname) as responsible, assignment.assignmentnr_prefix,customer.street,customer.zipcode as customer_zipcode,customer.city as customer_city,registernr,customer.email as customer_email,customer.phone as customer_phone,assignment.provider,responsible.name as responsible_user",
                    array('tblusers as responsible'=>'responsible.userid=tblmonitorings.responsible',
                    'tblcustomers as customer'=>'customer.customernr=tblmonitorings.customer',
                    'tblmonitoringstatus'=>'tblmonitoringstatus.id=tblmonitorings.monitoringstatus',
                    'tblassignments as assignment'=>'assignment.assignmentnr=tblmonitorings.assignmentnr',
                    )
            );

             $data['monitoringcsvData'] = $this->db
                                              ->select('*')
                                              ->from('tblassignmentproducts_csv tc')
                                              ->where('tc.monitoringnr',$id)
                                              ->get()
                                              ->result_array();


            // echo "<pre>";
            // print_r($data['data']);
            // die();

            $this->Pdf_model->pdf_printmonitoringprovider($data);
        }
    }
}