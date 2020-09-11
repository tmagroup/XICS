<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Customerprovidercompany_model extends CI_Model
{
    var $table = 'tblcustomerprovidercompanies';
    var $aid = 'id';
	
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Check if customer provider company
     * @param  mixed $id
     * @return mixed
     */
    public function get($id='', $field='', $where="")
    {   
        //Field
        if($field!=""){
            $this->db->select($field);
        }
        
        //Where
	if($where!=""){
            $this->db->where($where);
        }
        
        if (is_numeric($id)) {
            $this->db->where($this->aid, $id);
            
            return $this->db->get($this->table)->row();
        }

        return $this->db->get($this->table)->result_array();
    }
    
    /**
     * Add new customer
     * @param array $data customer $_POST data
     */
    public function add($data)
    {    
        //Database data
        $this->db->insert($this->table, $data);
        $id = $this->db->insert_id();
        
        if($id>0){            
            //Log Activity
            logActivity('New Customer Provider Company Number Added [ID: ' . $id . ', ' . $data['providernr'] . ']');         
        }
        
        return $id;
    }
    
    /**
     * Update customer
     * @param  array $data customer
     * @param  mixed $id   customer id
     * @return boolean
     */
    public function update($data, $id)
    {        
        //Database data
        $this->db->where($this->aid, $id);
        $this->db->update($this->table, $data);
        if ($this->db->affected_rows() > 0) {
            //Log Activity
            logActivity('Customer Provider Company Number Updated [ID: ' . $id . ', ' . $data['providernr'] . ']');
        } 
        
        return $id;
    }    
    
    /**
     * Delete customer
     * @param  array $data customer
     * @param  mixed $id   customer id
     * @return boolean
     */
    public function delete($id)
    {
        //Get Customernr
        $rowfield = $this->get($id,'providernr');
        
        $this->db->where($this->aid, $id);
        $this->db->delete($this->table);
        
        //Log Activity
        logActivity('Customer Provider Company Number Deleted [ID: ' . $id . ', ' . $rowfield->providernr . ']');
        
        return 1;
    }    
}
