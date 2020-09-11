<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Hardwareinputproduct_model extends CI_Model
{
    var $table = 'tblhardwareinputproducts';
    var $aid = 'id';
	
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Check if hardwareinput product
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
     * Add new hardwareinput product
     * @param array $data hardwareinput product $_POST data
     */
    public function add($data)
    {    
        $this->db->insert($this->table, $data);
        $id = $this->db->insert_id();
        
        if($id>0){            
            //Log Activity
            logActivity('New Hardwareinput Product Added [ID: ' . $id . ', ' . $data['seriesnr'] . ']');         
        }
        
        return $id;
    }
    
    /**
     * Update hardwareinput product
     * @param  array $data hardwareinput product
     * @param  mixed $id   hardwareinput product id
     * @return boolean
     */
    public function update($data, $id, $img='')
    {              
        $this->db->where($this->aid, $id);
        $this->db->update($this->table, $data);
        if ($this->db->affected_rows() > 0) {
            //Log Activity
            logActivity('Hardwareinput Product Updated [ID: ' . $id . ', ' . $data['seriesnr'] . ']');
        } 
        
        return $id;
    }    
    
    /**
     * Delete hardwareinput product
     * @param  array $data hardwareinput product
     * @param  mixed $id   hardwareinput product id
     * @return boolean
     */
    public function delete($id)
    {
        //Get Seriesnr
        $rowfield = $this->get($id,'seriesnr');
        
        $this->db->where($this->aid, $id);
        $this->db->delete($this->table);
        
        //Log Activity
        logActivity('Hardwareinput Product Deleted [ID: ' . $id . ', ' . $rowfield->seriesnr . ']');
        
        return 1;
    }    
}
