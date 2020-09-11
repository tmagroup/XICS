<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Hardwareassignmentshippingslipproduct_model extends CI_Model
{
    var $table = 'tblhardwareassignmentshippingslipproducts';
    var $aid = 'id';
	
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Check if hardware assignment product
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
     * Add new hardware assignment product
     * @param array $data hardware assignment product $_POST data
     */
    public function add($data)
    {   
        $this->db->insert($this->table, $data);
        $id = $this->db->insert_id();
        
        if($id>0){            
            //Log Activity
            logActivity('New Hardware Assignment Shipping Slip Product Added [ID: ' . $id . ', ' . $data['shippingnr'] . ']');         
        }
        
        return $id;
    }
    
    /**
     * Update hardware assignment product
     * @param  array $data hardware assignment product
     * @param  mixed $id   hardware assignment product id
     * @return boolean
     */
    public function update($data, $id)
    {   
        $this->db->where($this->aid, $id);
        $this->db->update($this->table, $data);
        if ($this->db->affected_rows() > 0) {
            //Log Activity
            if(isset($data['shippingnr'])){
                logActivity('Hardware Assignment Shipping Slip Product Updated [ID: ' . $id . ', ' . $data['shippingnr'] . ']');
            }
        } 
        
        return $id;
    }    
    
    /**
     * Delete hardware assignment product
     * @param  array $data hardware assignment product
     * @param  mixed $id   hardware assignment product id
     * @return boolean
     */
    public function delete($id)
    {
        //Get Customernr
        $rowfield = $this->get($id,'shippingnr');
        
        $this->db->where($this->aid, $id);
        $this->db->delete($this->table);
        
        //Log Activity
        logActivity('Hardware Assignment Shipping Slip Product Deleted [ID: ' . $id . ', ' . $rowfield->shippingnr . ']');
        
        return 1;
    }    
}
