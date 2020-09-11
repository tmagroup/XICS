<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Leadquotationreminder_model extends CI_Model
{
	var $table = 'tblleadquotationreminders';
	var $aid = 'remindernr';

	public function __construct()
	{
		parent::__construct();
		$this->load->model('Remindersubject_model');
	}

	/**
	 * Check if reminder
	 * @param  mixed $remindernr
	 * @return mixed
	 */
	public function get($id='', $field='', $join=array(), $where="", $groupby="")
	{
		//Select Fields
		if($field!=""){
			$this->db->select($field);
		}

		//Join
		if(count($join)>0){
			foreach ($join as $key=>$value){
				$this->db->join($key, $value, 'left');
			}
		}

		//Group By
		if($groupby!=""){
			$this->db->group_by($groupby);
		}

		//Where
		if($where!=""){
			$this->db->where($where);
		}

		if (is_numeric($id)) {
			$this->db->where($this->table.".".$this->aid, $id);
			return $this->db->get($this->table)->row();
		}

		return $this->db->get($this->table)->result_array();
	}

	/**
	 * Add new reminder
	 * @param array $data reminder $_POST data
	 */
	public function add($data)
	{
		//Database data
		$data['created'] = date('Y-m-d H:i:s');
		$data['userid'] = get_user_id();
		$data['reminddate'] = to_sql_date($data['reminddate'], true);
		$data['reminderway'] = isset($data['reminderway'])?1:0;
		$this->db->insert($this->table, $data);
		$id = $this->db->insert_id();

		if($id>0){
			//Add ID Prefix
			$dataId = array();
			$dataId['remindernr_prefix'] = idprefix('leadquotationreminder',$id);
			$this->db->where($this->aid, $id);
			$this->db->update($this->table, $dataId);

			$rowfield = $this->get($id,'remindersubject');
			$rowfield = $this->Remindersubject_model->get($rowfield->remindersubject,'name');
			//Log Activity
			$name = isset($rowfield->name)?$rowfield->name:'';
			logActivity('New Lead Quotation Reminder Added [ID: ' . $id . ', Subject: ' . $name . ']');
		}

		return $id;
	}

	/**
	 * Update reminder
	 * @param  array $data reminder
	 * @param  mixed $id   reminder id
	 * @return boolean
	 */
	public function update($data, $id, $popup=false)
	{
		//Database data
		$data['updated'] = date('Y-m-d H:i:s');
		if(isset($data['reminddate'])){
			$data['reminddate'] = to_sql_date($data['reminddate'], true);
		}
		if(!$popup){
			$data['reminderway'] = isset($data['reminderway'])?1:0;
		}
		$this->db->where($this->aid, $id);
		$this->db->update($this->table, $data);

		if ($this->db->affected_rows() > 0) {
			$rowfield = $this->get($id,'remindersubject');
			$rowfield = $this->Remindersubject_model->get($rowfield->remindersubject,'name');
			//Log Activity
			$name = isset($rowfield->name)?$rowfield->name:'';
			logActivity('Lead Quotation Reminder Updated [ID: ' . $id . ', Subject: ' . $name . ']');
		}

		return $id;
	}

	/**
	 * Delete reminder
	 * @param  array $data reminder
	 * @param  mixed $id   reminder id
	 * @return boolean
	 */
	public function delete($id)
	{
		//Get Leadnr
		$rowfield = $this->get($id,'remindersubject');
		$rowfield = $this->Remindersubject_model->get($rowfield->remindersubject,'name');

		$this->db->where($this->aid, $id);
		$this->db->delete($this->table);

		//Log Activity
		$name = isset($rowfield->name)?$rowfield->name:'';
		logActivity('Lead Quotation Reminder Deleted [ID: ' . $id . ', Subject: ' . $name . ']');

		return 1;
	}
}