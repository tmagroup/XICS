<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Assignmentproduct_model extends CI_Model
{
    var $table = 'tblassignmentproducts';
    var $aid = 'id';

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Check if assignment product
     * @param  mixed $id
     * @return mixed
     */
    public function get($id='', $field='', $join=array(), $where="")
    {
        //Field
        if($field!=""){
            $this->db->select($field);
        }

        //Join
        if(count($join)>0){
            foreach ($join as $key=>$value){
                $this->db->join($key, $value, 'left');
            }
        }

        //Where
	if($where!=""){
            $this->db->where($where);
        }

        //Order by
        $this->db->order_by($this->table.".".$this->aid, "asc");

        if (is_numeric($id)) {
            $this->db->where($this->table.".".$this->aid, $id);

            return $this->db->get($this->table)->row();
        }

        return $this->db->get($this->table)->result_array();
    }

    /**
     * Add new assignment product
     * @param array $data assignment product $_POST data
     */
    public function add($data)
    {
        //Database data
        if(isset($data['endofcontract']) && $data['endofcontract']!=""){
           $data['endofcontract'] = to_sql_date($data['endofcontract'], false);
        }
        else{
           //$data['endofcontract'] = '';
        }

        $this->db->insert($this->table, $data);
        $id = $this->db->insert_id();

        if($id>0){
            //Log Activity
            logActivity('New Assignment Product Added [ID: ' . $id . ', ' . $data['mobilenr'] . ']');
        }

        return $id;
    }

    /**
     * Update assignment product
     * @param  array $data assignment product
     * @param  mixed $id   assignment product id
     * @return boolean
     */
    public function update($data, $id, $img='')
    {
        //Database data
        if(isset($data['endofcontract']) && $data['endofcontract']!=""){
           $data['endofcontract'] = to_sql_date($data['endofcontract'], false);
        }
        else{
           //$data['endofcontract'] = '';
        }

        //Get Hardware Updated OR New Hardware Added
        $Hardware_updateRow = 0;
        $rowHardwareData = (array) $this->get($id);
        if(isset($rowHardwareData['hardware']) && $rowHardwareData['hardware']!="" && isset($data['hardware']) && $data['hardware']!="" && $rowHardwareData['hardware']!=$data['hardware']){
            $Hardware_updateRow = 1;
        }

        $this->db->where($this->aid, $id);
        $this->db->update($this->table, $data);
        $updateRow = $this->db->affected_rows();

        if ($updateRow > 0) {
            //Log Activity
            if(isset($data['mobilenr'])){
                logActivity('Assignment Product Updated [ID: ' . $id . ', ' . $data['mobilenr'] . ']');
            }
        }

        return $Hardware_updateRow;
    }

    /**
     * Delete assignment product
     * @param  array $data assignment product
     * @param  mixed $id   assignment product id
     * @return boolean
     */
    public function delete($id)
    {
        //Get Customernr
        $rowfield = $this->get($id,'mobilenr');

        $this->db->where($this->aid, $id);
        $this->db->delete($this->table);

        //Log Activity
        if ( isset($rowfield->mobilenr) ) {
            logActivity('Assignment Product Deleted [ID: ' . $id . ', ' . $rowfield->mobilenr . ']');
        } else {
            logActivity('Assignment Product Deleted [ID: ' . $id . ']');
        }

        return 1;
    }
}
