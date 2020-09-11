<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Field_model extends CI_Model
{
    var $table = 'tblfields';
    var $aid = 'field_id';

    public function __construct()
    {
        parent::__construct();
    }

    public function get($rel_type, $rel_id = '')
    {
        $fields = $this->table.".field_id, ".$this->table.".field_name, ".$this->table.".field_type, ".$this->table.".provider";
        if($rel_id>0){ $fields.=", tblfieldvalues.field_value "; }
        $this->db->select($fields);

        $where = $this->table.".rel_type='".$rel_type."' ";
        //if($rel_id>0){ $where.=" AND tblfieldvalues.rel_id='".$rel_id."' "; }
        $this->db->where($where);

        if($rel_id>0){
            $join = array('tblfieldvalues'=>"tblfieldvalues.field_id=".$this->table.".field_id AND tblfieldvalues.rel_id='".$rel_id."' ");
            if(count($join)>0){
                foreach ($join as $key=>$value){
                    $this->db->join($key, $value, 'left');
                }
            }
        }

        return $this->db->get($this->table)->result_array();
    }

    /**
     * Add new discount level
     * @param array $data discount level $_POST data
     */
    public function add($data, $logMessage='')
    {
        if(empty($logMessage)){ $logMessage='Added'; }

        $fields = array('SO','NEUSUB','VVL');
        foreach ($fields as $key => $value) {
            $insert_data = array();
            $insert_data['field_name'] = 'PV'.$data['discounttitle'].$value;
            $insert_data['field_type'] = 'number';
            $insert_data['rel_type'] = 'ratemobile';
            $insert_data['provider'] = $data['provider'];
            $insert_data['discountnr'] = $data['discountnr'];

            $this->db->where('field_name', trim($insert_data['field_name']));
            $field_name = $this->db->get($this->table)->row();

            if (!$field_name) {
                $this->db->insert($this->table, $insert_data);
                $id = $this->db->insert_id();

                if($id>0){
                    //Log Activity
                    logActivity('New Field '.$logMessage.' [ID: ' . $id . ', ' . $insert_data['field_name'] . ']');
                }
            }
        }
        return true;
    }
}
