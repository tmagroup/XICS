<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Subcustomers extends Admin_controller
{
	public function __construct()
	{
		parent::__construct();
		if ( get_user_role() != 'customer' || (get_user_role() === 'customer' && $GLOBALS['current_user']->parent_customer_id > 0)) {
			redirect(site_url('admin/permission/denied/'));
		}
		$this->load->model('Customer_model');
		$this->load->model('User_model');
		$this->load->model('Customerprovidercompany_model');
		$this->load->model('Companysize_model');
		$this->load->model('Salutation_model');
		$this->load->model('Documentsetting_model');
		$this->load->model('Remindersubject_model');
		$this->load->model('Note_model');
		$this->load->model('Monitoringvalue_model');
	}

	/* List all customers */
	public function index()
	{
		//******************** Initialise ********************/
		$data['filter_responsible'] = $this->User_model->get('',"userid, CONCAT(name,' ',surname) as name"," userrole IN(1,2,3) ");
		$data['filter_responsible'] = dropdown($data['filter_responsible'],'userid','name');
		//******************** End Initialise ********************/

		$data['title'] = lang('page_customers');
		$this->load->view('admin/subcustomers/manage', $data);
	}

	/* List all customers by ajax */
	public function ajax($filter_responsible='')
	{
		//Filter By responsible
		$params = array('filter_responsible'=>$filter_responsible);
		$this->app->get_table_data('subcustomers',$params);
	}



	/* Change Status */
	public function change_active($id='', $status=''){
		if($status==1){
			//History
			$Action_data = array('actionname'=>'customer', 'actionid'=>$id, 'actiontitle'=>'customer_activated');
			do_action_history($Action_data);
		}else{
			//History
			$Action_data = array('actionname'=>'customer', 'actionid'=>$id, 'actiontitle'=>'customer_deactivated');
			do_action_history($Action_data);
		}
		$this->db->query("UPDATE `tblcustomers` SET `active`='".$status."' WHERE customernr='".$id."'");
		exit;
	}


	/* Add/Edit Customer */
	public function customer($id='')
	{
		//******************** Initialise ********************/
		if($id>0){
			//Customer
			$data['customer'] = (array) $this->Customer_model->get($id);
		}
		//******************** End Initialise ********************/

		//Submit Page
		if ($this->input->post()) {
			$post = $this->input->post();

			//Admin and Salesmanager can set Responsible
			if($GLOBALS['current_user']->userrole==1 || $GLOBALS['current_user']->userrole==2){
			}
			else{
				if(isset($post['responsible'])){
					unset($post['responsible']);
				}
			}

			if(isset($data['customer']['customernr'])){
				$response = $this->Customer_model->update($post, $data['customer']['customernr']);
				if (is_numeric($response) && $response>0) {

					//History
					$Action_data = array('actionname'=>'customer', 'actionid'=>$response, 'actiontitle'=>'customer_updated');
					do_action_history($Action_data);

					handle_customer_profile_image_upload($data['customer']['customernr']);
					set_alert('success', sprintf(lang('updated_successfully'),lang('page_customer')));
					redirect(site_url('admin/subcustomers/detail/' . $data['customer']['customernr']));
				}
				else{
					set_alert('danger', $response);
				}
			}
			else{
				$post['parent_customer_id'] = $GLOBALS['current_user']->customernr;
				$response = $this->Customer_model->add($post);
				if (is_numeric($response) && $response>0) {

					//History
					$Action_data = array('actionname'=>'customer', 'actionid'=>$response, 'actiontitle'=>'customer_added');
					do_action_history($Action_data);

					handle_customer_profile_image_upload($response);
					set_alert('success', sprintf(lang('added_successfully'),lang('page_customer')));
					redirect(site_url('admin/subcustomers/detail/' . $response));
				}
				else{
					set_alert('danger', $response);
				}
			}

			//Initialise
			$customernr = '';
			if(isset($data['customer'])){
				$customernr = $data['customer']['customernr'];
			}
			$data['customer'] = $post;
			$data['customer']['customernr'] = $customernr;
		}


		//******************** Initialise ********************/
		//Responsibles (Salesmanager,Salesman or Admin)
		$data['responsibles'] = $this->User_model->get('',"userid, CONCAT(name,' ',surname) as name"," userrole IN(1,2,3) ");
		$data['responsibles'] = dropdown($data['responsibles'],'userid','name');

		//Recommends (POS)
		$data['recommends'] = $this->User_model->get('',"userid, CONCAT(name,' ',surname) as name"," userrole IN(6) ");
		$data['recommends'] = dropdown($data['recommends'],'userid','name');

		//Customerprovidercompanies
		$customernr = isset($data['customer']['customernr'])?$data['customer']['customernr']:'';
		$data['customerprovidercompanies'] = $this->Customerprovidercompany_model->get('',''," customernr='".$customernr."' ");

		//Companysizes
		$data['companysizes'] = $this->Companysize_model->get();
		$data['companysizes'] = dropdown($data['companysizes'],'id','name');

		//Salutations (Titles)
		$data['salutations'] = $this->Salutation_model->get();
		$data['salutations'] = dropdown($data['salutations'],'salutationid','name');

		//Remindersubjects
		$data['remindersubjects'] = $this->Remindersubject_model->get();
		$data['remindersubjects'] = dropdown($data['remindersubjects'],'id','name');

		//Categories
		$data['categories'] = $this->Documentsetting_model->get('',''," active=1 ");
		$data['categories'] = dropdown($data['categories'],'categoryid','categoryname');

		//Monitoringvalues (This selectboxvalues I need later.)
		$data['monitoringvalues'] = $this->Monitoringvalue_model->get('',"gval,CONCAT(gval,'%') as gval2");
		$data['monitoringvalues'] = dropdown($data['monitoringvalues'],'gval','gval2');
		//******************** End Initialise ********************/


		//Page Title
		if(isset($data['customer']['customernr']) && $data['customer']['customernr']>0){
			$data['title'] = lang('page_edit_customer');
		}
		else{
			$data['title'] = lang('page_create_customer');
		}


		$this->load->view('admin/subcustomers/customer', $data);
	}

	/* Add/Edit Customer */
	public function profile()
	{
		//Get (User/Customer)
		$user_role = get_user_role();
		if($user_role!='customer'){
			redirect(site_url('admin/settings/profile/'));
		}else{
			redirect(site_url('admin/settings/profile/'));
		}

		$id = get_user_id();
		//******************** Initialise ********************/
		if($id>0){
			//Customer
			$data['customer'] = (array) $this->Customer_model->get($id);
		}
		//******************** End Initialise ********************/

		//Submit Page
		if ($this->input->post()) {
			$post = $this->input->post();

			$response = $this->Customer_model->update($post, $data['customer']['customernr']);
			if (is_numeric($response) && $response>0) {
				handle_customer_profile_image_upload($data['customer']['customernr']);
				set_alert('success', sprintf(lang('updated_successfully'),lang('page_profile_setting')));
				redirect(site_url('admin/subcustomers/profile/'));
			}
			else{
				set_alert('danger', $response);
			}

			//Initialise
			$customernr = '';
			if(isset($data['customer'])){
				$customernr = $data['customer']['customernr'];
			}
			$data['customer'] = $post;
			$data['customer']['customernr'] = $customernr;
		}

		//Page Title
		$data['title'] = lang('page_profile_setting');
		$this->load->view('admin/subcustomers/profile', $data);
	}

	/* Detail Customer */
	public function detail($id='')
	{

		if ( get_user_role() != 'customer' || (get_user_role() === 'customer' && $GLOBALS['current_user']->parent_customer_id > 0)) {
			redirect(site_url('admin/permission/denied/'));
		}

		//******************** Initialise ********************/
		if($id>0){
			//Customer
			$data['customer'] = (array) $this->Customer_model->get($id,"tblcustomers.*, CONCAT(responsible.name,' ',responsible.surname) as responsible, tblcustomers.responsible as responsible_id, "
					. " CONCAT(recommend.name,' ',recommend.surname) as recommend, "
					. " tblcompanysizes.name as companysize, "
					. " tblsalutations.name as salutation, "
					. " GROUP_CONCAT(tblcustomerprovidercompanies.providernr) as customerprovidercompanies ",

					array('tblusers as responsible'=>'responsible.userid=tblcustomers.responsible',
					'tblusers as recommend'=>'recommend.userid=tblcustomers.recommend',
					'tblcompanysizes'=>'tblcompanysizes.id=tblcustomers.companysize',
					'tblsalutations'=>'tblsalutations.salutationid=tblcustomers.salutation',
					'tblcustomerprovidercompanies'=>'tblcustomerprovidercompanies.customernr=tblcustomers.customernr',
					)
			);
		}

		if(empty($data['customer']['customernr'])){
			redirect(site_url('admin/customers'));
		}
		//******************** End Initialise ********************/


		//******************** Initialise ********************/
		//Categories
		$data['categories'] = $this->Documentsetting_model->get('',''," active=1 ");
		$data['categories'] = dropdown($data['categories'],'categoryid','categoryname');

		//Remindersubjects
		$data['remindersubjects'] = $this->Remindersubject_model->get();
		$data['remindersubjects'] = dropdown($data['remindersubjects'],'id','name');

		//Comments
		$data['comments'] = $this->Note_model->get("","tblnotes.*, CONCAT(tblusers.name,' ',tblusers.surname) as fullname",array('tblusers'=>'tblusers.userid=tblnotes.addedfrom')," tblnotes.rel_id='".$data['customer']['customernr']."' AND tblnotes.rel_type='customer' ","","tblnotes.id desc");
		//******************** End Initialise ********************/


		//Page Title
		$data['title'] = lang('page_detail_customer');
		$this->load->view('admin/subcustomers/detail', $data);
	}

	/* Delete customer */
	public function delete()
	{
		if ( get_user_role() != 'customer' || (get_user_role() === 'customer' && $GLOBALS['current_user']->parent_customer_id > 0)) {
			redirect(site_url('admin/permission/denied/'));
		}

		$response = $this->Customer_model->delete($this->input->post('id'));
		if ($response==1) {

			//History
			$Action_data = array('actionname'=>'customer', 'actionid'=>$this->input->post('id'), 'actiontitle'=>'customer_deleted');
			do_action_history($Action_data);

			set_alert('success', sprintf(lang('deleted_successfully'),lang('page_customer')));
		}else{
			set_alert('danger', $response);
		}
		redirect(site_url('admin/subcustomers/'));
	}
}
