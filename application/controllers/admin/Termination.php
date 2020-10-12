<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Termination extends Admin_controller
{
    var $table = 'tbltermination';
    var $aid = 'id';

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Email_model');
        $this->load->model('Event_model');
    }

    /* List all monitorings */
    public function index()
    {
        if(!$GLOBALS['termination_permission']['view']){
            access_denied('termination');
        }

        // if(get_user_role()=='customer'){
        //     if(!$GLOBALS['current_user']->monitoring){
        //         access_denied('termination');
        //     }
        // }


        $data['title'] = lang('page_termination');
        if ($this->input->server('REQUEST_METHOD') == 'POST') {
            $columns = array(
                0 => 'id',
                1 => 'company_name',
                2 => 'leadStatus',
                3 => 'salutation',
                4 => 'id',
            );

            $limit = $this->input->post('length');
            $start = $this->input->post('start');
            $order = $columns[$this->input->post('order')[0]['column']];
            $dir = $this->input->post('order')[0]['dir'];

            $totalFiltered = $this->countAllRecord($this->table);

            $param  = array();

            $field = 'tbltermination.*,ls.name as leadStatus,color';
            $param['limit'] = array($limit,$start);
            // print_r($param['limit']); die();
            $param['order_by'] = array($order,$dir);
            $param['join'] = array(
                            'tblleadstatus ls'=>'ls.id=tbltermination.lead_status'
                            );

            if(empty($this->input->post('search')['value']))
            {
                $posts = $this->select_record($this->table,$field,$param);

            } else {
                $search = $this->input->post('search')['value'];

                if($search != '') {
                    $param['like'] = array('tbltermination.company_name',$search);
                }
                $posts = $this->select_record($this->table,$field,$param);

                $totalFiltered = count($posts);
            }

            $data = array();
            if(!empty($posts))
            {
                $no = $start;
                foreach ($posts as $key => $post)
                {
                    $nestedData['id'] = $no+1;
                    $nestedData['company_name'] = $post['company_name'];
                    $nestedData['leadStatus'] = $post['leadStatus'];
                    $nestedData['status'] = $post['status'];
                    $nestedData['salutation'] = $post['salutation'] == '0' ? 'HERR' :'FRAU';
                    $nestedData['action'] = '<button class="btn btn-danger delete-termination" data-id="'.$post['id'].'">Delete</button>&nbsp;&nbsp;<a href="'.base_url().'admin/termination/setup/'.$post['id'].'" class="btn btn-success">Edit</a>&nbsp;&nbsp;<a href="javascript:void(0);" class="btn btn-default yellow sendmail-term fresh" data-id="'.$post['id'].'">Appointment Confirmation</a>';

                    $data[] = $nestedData;
                    $no++;
                }
            }

            $json_data = array(
                "draw"            => intval($this->input->post('draw')),
                "recordsTotal"    => intval($totalData),
                "recordsFiltered" => intval($totalFiltered),
                "data"            => $data
            );
            echo json_encode($json_data);die();
        }
        $data = array();
        $data['yearData'] = $this->db
                                ->select('date')
                                ->from($this->table)
                                ->group_by('YEAR(date)')
                                ->get()
                                ->result_array();
        $data['leadStatusData'] = $this->select_record('tblleadstatus',$field='*');
        // echo "<pre>";
        // print_r($data['yearData']);
        // die();
        $this->load->view('admin/termination/index', $data);
    }

    public function setup($id=0)
    {
        if(!$GLOBALS['termination_permission']['create'] && !$GLOBALS['termination_permission']['edit']){
            access_denied('termination');
        }

        $data = array();
        $data['leadStatusData'] = $this->select_record('tblleadstatus',$field='*');
        $data['providerData'] = $this->select_record('tblprovider',$field='*');
        $data['responsiveUserData'] = $this->select_record('tblusers',$field='*');

        if($id > 0) {
            $data['terminationData'] = $this->get_single_record('tbltermination',array('id' => $id));
        }

        $this->load->view('admin/termination/form', $data);
    }

    public function commit($id=0)
    {
        if($this->input->server('REQUEST_METHOD') == 'POST') {
            $post = $this->input->post();

            $post['date'] = to_sql_date($post['date'], false);
            if($id > 0) {
                $post['updated_at'] = date('Y-m-d H:i:s');

                $this->update_record($this->table,array('id'=>$id),$post);
                set_alert('success', sprintf(lang('updated_successfully'),lang('page_termination')));
                redirect(site_url('admin/termination/'));
            } else {
                $post['created_at'] = date('Y-m-d H:i:s');
                $post['updated_at'] = date('Y-m-d H:i:s');

                $insert_id = $this->insert($this->table,$post);
                if($insert_id > 0) {
                    set_alert('success', sprintf(lang('added_successfully'),lang('page_termination')));
                    redirect(site_url('admin/termination/'));
                }
            }
        }
    }

    public function delete()
    {
        if(!$GLOBALS['termination_permission']['delete'] || !$this->input->post('id')){
            access_denied('termination');
        }

        $return = array();
        if ($this->input->post()) {
            $id = $this->input->post('id');

            $this->delete_record($this->table,array('id' => $id));
            $return['status'] = TRUE;
            $return['response'] = 'success';
            $return['message'] = 'Delete Successfully';
        } else {
            $return['response'] = 'error';
            $return['status'] = FALSE;
        }
        echo json_encode($return); die();
    }

    public function appointmentConfirmation()
    {
        $return = array();
        if ($this->input->post()) {
            $id = $this->input->post('id');

            $data = $this->db
                         ->select('tt.*')
                         ->from('tbltermination as tt')
                         ->where('tt.id',$id)
                         ->get()
                         ->row_array();
            // $billData = $this->Assignmentbill_model->get($bill_id);
            // $file = FCPATH.'uploads/assignments/'.$post['assignmentnr'].'/bills/'.$billData->invoicefilecsv;
            // $this->Email_model->add_attachment(array('attachment' => $file));
            $mer_data = array();
            if($data['provider'] == '2') {
                $emailtemplate = 'terminationtelekon';
                $logo_image_url = base_url().'assets/telekon.png';
            } else if($data['provider'] == '3') {
                $emailtemplate = 'terminationO2Business';
                $logo_image_url = base_url().'assets/o2.png';
            } else {
                $emailtemplate = 'terminationvodafone';
                $logo_image_url = base_url().'assets/Vodafone.jpg';
            }
            $mer_data['customer_surname'] = $data['surname'];
            $mer_data['customer_name'] = $data['name'];
            $mer_data['logo_image_url'] = $logo_image_url;
            $mer_data['data_type'] = date('d-m-Y',strtotime($data['date']));
            $mer_data['appoiment_date'] = date('d-m-Y',strtotime($data['date']));
            $mer_data['accept_url'] = base_url().'admin/termination/terminationAcceptCancel/'.$id.'/accept/';
            $mer_data['cancel_url'] = base_url().'admin/termination/terminationAcceptCancel/'.$id.'/cancel/';

            $merge_fields = array();
            $merge_fields = array_merge($merge_fields, get_customertermination_merge_fields($mer_data));

            // echo "<pre>";
             // $sent = $this->Email_model->send_email_template('invoicecsvemail', $customerData->email, $merge_fields);
            // $sent = $this->Email_model->send_email_template($emailtemplate, 'akshaysorathiya555@gmail.com', $merge_fields);
            $sent = $this->Email_model->send_email_template($emailtemplate, $data['email'], $merge_fields);

            if($sent) {
                $current_datetime = date('Y-m-d H:i:s');
                $this->update_record($this->table,array('id'=>$id),array('status' => '0','mail_send_time'=> $current_datetime));

                $return['status'] = TRUE;
                $return['response'] = 'success';
                $return['message'] = 'Send Mail Successfully';
            }
        } else {
            $return['response'] = 'error';
            $return['status'] = FALSE;
        }
        echo json_encode($return); die();
    }

    public function terminationAcceptCancel($id,$status)
    {
        if($id > 0){
            if($status == 'accept') {
                $post['lead_status'] = '8';
                $post['status'] = '2';
                $this->addCalendarsEvent($id);
            } else {
                $post['lead_status'] = '9';
                $post['status'] = '1';
            }

            $this->update_record($this->table,array('id'=>$id),$post);
        }
        redirect(site_url('admin/termination/'));
    }

    public function addCalendarsEvent($id)
    {
        $data = $this->db
                     ->select('*')
                     ->from($this->table)
                     ->where('id',$id)
                     ->get()
                     ->row_array();

        $startDate = $data['date'].' 00:01:00';
        $endDate = $data['date'].' 00:23:59';

        $dataEvent['created'] = date('Y-m-d H:i:s');
        $dataEvent['userid']  = get_user_id();
        $dataEvent['start']   = $startDate;
        $dataEvent['end']     = $endDate;
        $dataEvent['public']  = 0;

        $this->db->insert('tblevents', $dataEvent);
        $event_id = $this->db->insert_id();

        if($event_id > 0) {
            $dataNew  = array(
                'calendarId' => 'bvtcomgmbh@gmail.com',
                'start' => $startDate,
                'end' => $endDate,
                'title' => $data['name'],
                'google_color_id' => '2',
                'event_address' => $data['street'].','.$data['city'],
                'description' => $data['notice'],
            );

            $this->Event_model->addGoogleEvent($dataNew,$event_id);
        }
    }

    public function export_excel()
    {
        if($this->input->server('REQUEST_METHOD') == 'POST') {
            $post = $this->input->post();

            $this->db->select('ls.name as leadStatus,tbltermination.*,p.name as providerName,u.username as responsiveUser');
            $this->db->from($this->table);
            if($post['filter_month'] != '') {
                $this->db->where('MONTH(tbltermination.date)' , $post['filter_month']);
            }
            if($post['filter_year'] != '') {
                $this->db->where('YEAR(tbltermination.date)' , $post['filter_year']);
            }
            if($post['filter_leadStatus'] != '') {
                $this->db->where('tbltermination.lead_status' , $post['filter_leadStatus']);
            }
            $this->db->join('tblleadstatus ls', 'ls.id=tbltermination.lead_status', 'left');
            $this->db->join('tblprovider p', 'p.id = tbltermination.provider', 'left');
            $this->db->join('tblusers u', 'u.userid = tbltermination.responsive_user', 'left');
            $this->db->group_by('tbltermination.id');
            $query =  $this->db->get();

            $data = $query->result_array();

            $header = array(
                lang('page_fl_leadstatus'),
                lang('page_fl_salutation'),
                lang('page_fl_company'),
                lang('page_fl_name'),
                lang('page_fl_position'),
                lang('page_fl_street'),
                lang('page_fl_zipcode'),
                lang('page_fl_city'),
                lang('page_fl_phone'),
                lang('page_fl_email'),
                lang('page_fl_employment'),
                lang('page_fl_provider'),
                lang('page_fl_cards'),
                lang('page_fl_date'),
                lang('page_fl_responsive_user')
            );

            $filename = lang('page_termination').'_'.date('dmY').'.csv';
            header('Content-Type: application/csv');
            header('Content-Disposition: attachment; filename="'.$filename.'";');
            header("Content-Transfer-Encoding: UTF-8");
            // header("Pragma: no-cache");
            // header("Expires: 0");

            $f = fopen('php://output', 'w');
            fputcsv($f, $header);
            foreach ($data as $key => $value) {
                $newvalue = array(
                    'leadStatus' => $value['leadStatus'],
                    'salutation' => $value['salutation'] == '0' ? 'Herr' :'Frau',
                    'company_name' => $value['company_name'],
                    'Name' => $value['name'],
                    'position' => $value['position'],
                    'street' => $value['street'],
                    'zipcode' => $value['zipcode'],
                    'city' => $value['city'],
                    'phone_number' => $value['phone_number'],
                    'email' => $value['email'],
                    'employment' => $value['employment'],
                    'provider' => $value['providerName'],
                    'cards' => $value['cards'],
                    'date' => date('d-m-Y',strtotime($value['date'])),
                    'responsive_user' => $value['responsiveUser']
                );
                fputcsv($f, $newvalue);
            }
        } else {
            redirect(base_url().'admin/termination','refresh');
        }
    }
}
