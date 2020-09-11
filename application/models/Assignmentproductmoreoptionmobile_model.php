<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Assignmentproductmoreoptionmobile_model extends CI_Model
{
    var $table = 'tblassignmentproduct_moreoptionmobiles';
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
        $this->db->insert($this->table, $data);
        $id = $this->db->insert_id();

        if($id>0){
            //Log Activity
            logActivity('New Assignment Product Option Mobile Added [ID: ' . $id . ', ' . $data['newoptionmobile'] . ']');
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
        $this->db->where($this->aid, $id);
        $this->db->update($this->table, $data);
        $updateRow = $this->db->affected_rows();

        if ($updateRow > 0) {
            logActivity('Assignment Product Option Mobile Updated [ID: ' . $id . ', ' . $data['newoptionmobile'] . ']');
        }

        return $updateRow;
    }

    /**
     * Delete assignment product
     * @param  array $data assignment product
     * @param  mixed $id   assignment product id
     * @return boolean
     */
    public function delete($id,$where='')
    {
        //Get Customernr
        if($where!=""){
            $this->db->where($where);
        }
        else{
            $rowfield = $this->get($id,'newoptionmobile');
            $this->db->where($this->aid, $id);
        }

        $this->db->delete($this->table);

        //Log Activity
        if ( isset($rowfield->newoptionmobile) ) {
            logActivity('Assignment Product Option Mobile Deleted [ID: ' . $id . ', ' . $rowfield->newoptionmobile . ']');
        } else {
            logActivity('Assignment Product Option Mobile Deleted [ID: ' . $id . ']');
        }

        return 1;
    }
}
