<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Termination extends Admin_controller
{
	public $table = 'tbltermination';
	public $aid = 'id';

	public function __construct()
	{
		parent::__construct();
		$this->load->model('Email_model');
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

			$field = 'tbltermination.*,ls.name as leadStatus,color,p.name as providerName,CONCAT(u.surname," ", u.name) as responsiUser';
			$param['limit'] = array($limit,$start);
			// print_r($param['limit']); die();
			$param['order_by'] = array($order,$dir);
			$param['join'] = array(
							'tblleadstatus ls'=>'ls.id=tbltermination.lead_status',
							'tblprovider p'=>'p.id=tbltermination.provider',
							'tblusers u'=>'u.userid=tbltermination.responsive_user'
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
					$nestedData['status'] = $post['status'];
					$nestedData['company_name'] = $post['company_name'];
					$nestedData['leadStatus'] = $post['leadStatus'];
					$nestedData['leadStatus_id'] = $post['lead_status'];
					$nestedData['providerName'] = $post['providerName'];
					$nestedData['cards'] = $post['cards'];
					$nestedData['responsiUser'] = $post['responsiUser'];
					$nestedData['action'] = '<button class="btn btn-danger delete-termination" title="Delete" data-id="'.$post['id'].'"><i class="fa fa-trash"></i></button>&nbsp;<a href="'.base_url().'admin/termination/setup/'.$post['id'].'"  title="Edit" class="btn btn-success"><i class="fa fa-pencil"></i></a>&nbsp;<a href="javascript:void(0);" class="btn btn-default yellow sendmail-term fresh" title="Appointment Confirmation" data-id="'.$post['id'].'"><i class="fa fa-envelope-square"></i></a>&nbsp;<a href="'.base_url().'admin/termination/show/'.$post['id'].'" class="btn btn-primary" title="View Details" data-id="'.$post['id'].'"><i class="fa fa-eye"></i></a>';

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
		$data['appointmentTypeData'] = $this->select_record('tblappointmenttype',$field='*');

		if($id > 0) {
			$data['terminationData'] = $this->get_single_record('tbltermination',array('id' => $id));
		}

		$this->load->view('admin/termination/form', $data);
	}

	public function commit($id=0)
	{
		if($this->input->server('REQUEST_METHOD') == 'POST') {
			$post = $this->input->post();

			$post['date'] = to_sql_date($post['date'], true);
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

	public function show($id)
	{
		if($id > 0) {
			$data = array();
			$data['data'] = $this->db
								->select('t.*,ls.name as leadName,p.name as providerName,CONCAT(u.surname," ", u.name) as responsiUser,a.name as appointmentName')
								->from('tbltermination t')
								->where('t.id',$id)
								->join('tblleadstatus ls','ls.id=t.lead_status','left')
								->join('tblprovider p','p.id=t.provider','left')
								->join('tblusers u','u.userid=t.responsive_user','left')
								->join('tblappointmenttype a','a.id=t.appointment_type','left')
								->get()
								->row_array();

			$this->load->view('admin/termination/view', $data);
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
			$return['message'] = lang('deleted_successfully');
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
						 ->select('tt.*,a.name as eventType')
						 ->from('tbltermination as tt')
						 ->join('tblappointmenttype a','a.id=tt.appointment_type','left')
						 ->where('tt.id',$id)
						 ->get()
						 ->row_array();
			// $billData = $this->Assignmentbill_model->get($bill_id);
			// $file = FCPATH.'uploads/assignments/'.$post['assignmentnr'].'/bills/'.$billData->invoicefilecsv;
			// $this->Email_model->add_attachment(array('attachment' => $file));
			$mer_data = array();
			// $data_type = date('d.m.Y',strtotime($data['date']));
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
			$mer_data['data_type'] = $data['eventType'];
			$mer_data['appoiment_date'] = date('d.m.Y',strtotime($data['date']));
			$mer_data['accept_url'] = base_url().'cronjobs/terminationAcceptCancel/'.md5($id).'/accept/';
			$mer_data['cancel_url'] = base_url().'cronjobs/terminationAcceptCancel/'.md5($id).'/cancel/';

			$merge_fields = array();
			$merge_fields = array_merge($merge_fields, get_customertermination_merge_fields($mer_data));

			// $sent = $this->Email_model->send_email_template('invoicecsvemail', $customerData->email, $merge_fields);
			$sent = $this->Email_model->send_email_template($emailtemplate, 'connectusdemo12@gmail.com', $merge_fields);

			if($sent) {
				$current_datetime = date('Y-m-d H:i:s');
				$this->update_record($this->table,array('id'=>$id),array('status' => '0','lead_status' => 10,'mail_send_time'=> $current_datetime));

				$return['status'] = TRUE;
				$return['response'] = 'success';
				$return['message'] = lang('page_fl_appointment_mail_message');
			} else {
				$return['status'] = TRUE;
				$return['response'] = 'success';
				$return['message'] = 'Mail not send';
			}
		} else {
			$return['response'] = 'error';
			$return['status'] = FALSE;
		}
		echo json_encode($return); die();
	}
	public function createLead()
	{
		$return = array();
		if ($this->input->post()) {
			$id = $this->input->post('id');

		   $data = $this->db
						->select('t.*,ls.name as leadName,p.name as providerName,CONCAT(u.surname," ", u.name) as responsiUser,a.name as appointmentName')
						->from('tbltermination t')
						->where('t.id',$id)
						->join('tblleadstatus ls','ls.id=t.lead_status','left')
						->join('tblprovider p','p.id=t.provider','left')
						->join('tblusers u','u.userid=t.responsive_user','left')
						->join('tblappointmenttype a','a.id=t.appointment_type','left')
						->get()
						->row_array();

			if(!empty($data)) {
				$current_datetime = date('Y-m-d H:i:s');
				$dataNew = array(
					'responsible' => $data['responsive_user'],
					'leadstatus' => $data['lead_status'],
					'company' => $data['company_name'],
					'street' => $data['street'],
					'zipcode' => $data['zipcode'],
					'city' => $data['city'],
					'phone' => $data['phone_number'],
					'email' => $data['email'],
					'salutation' => $data['salutation'],
					'provider' => $data['providerName'],
					'surname' => $data['surname'],
					'name' => $data['name'],
					'position' => $data['position'],
					'created' => $current_datetime
				);

				$lead_id = $this->insert('tblleads',$dataNew);

				if($lead_id > 0) {

					$this->db->where('leadnr', $lead_id);
					$this->db->update('tblleads', array('leadnr_prefix' => 'DKD-L-'.date('Y').'-'.$lead_id));

					$mer_data['leadstatus'] = $data['leadName'];
					$mer_data['appointment_type'] = $data['appointmentName'];
					$mer_data['provider'] = $data['providerName'];
					$mer_data['salution'] = $data['salutation'] == '1' ? 'Herr' : 'Frau';
					$mer_data['surname'] = $data['surname'];
					$mer_data['name'] = $data['name'];
					$mer_data['company'] = $data['company_name'];
					$mer_data['phone'] = $data['phone_number'];
					$mer_data['email'] = $data['email'];
					$mer_data['card'] = $data['cards'];
					$mer_data['employment'] = $data['employment'];
					$mer_data['street'] = $data['street'];
					$mer_data['zipcode'] = $data['zipcode'];
					$mer_data['city'] = $data['city'];
					$mer_data['notice'] = $data['notice'];

					$merge_fields = array();
					$merge_fields = array_merge($merge_fields, get_getterminationlead_merge_fields($mer_data));
					// echo "<pre>";
					 // $sent = $this->Email_model->send_email_template('invoicecsvemail', $customerData->email, $merge_fields);
					//$sent = $this->Email_model->send_email_template('terminationleademail', 'lead@dk-deutschland.de', $merge_fields);

					$return['status'] = TRUE;
					$return['response'] = 'success';
					$return['message'] = lang('page_fl_lead_add_message');;
				}
			} else {
				$return['status'] = TRUE;
				$return['response'] = 'success';
				$return['message'] = 'Mail not send';
			}
		} else {
			$return['response'] = 'error';
			$return['status'] = FALSE;
		}
		echo json_encode($return); die();
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
