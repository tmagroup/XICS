<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Leadprovidercompany_model extends CI_Model
{
    var $table = 'tblleadprovidercompanies';
    var $aid = 'id';
	
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Check if lead provider company
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
     * Add new lead
     * @param array $data lead $_POST data
     */
    public function add($data)
    {    
        //Database data
        $this->db->insert($this->table, $data);
        $id = $this->db->insert_id();
        
        if($id>0){            
            //Log Activity
            logActivity('New Lead Provider Company Number Added [ID: ' . $id . ', ' . $data['providernr'] . ']');         
        }
        
        return $id;
    }
    
    /**
     * Update lead
     * @param  array $data lead
     * @param  mixed $id   lead id
     * @return boolean
     */
    public function update($data, $id)
    {        
        //Database data
        $this->db->where($this->aid, $id);
        $this->db->update($this->table, $data);
        if ($this->db->affected_rows() > 0) {
            //Log Activity
            logActivity('Lead Provider Company Number Updated [ID: ' . $id . ', ' . $data['providernr'] . ']');
        } 
        
        return $id;
    }    
    
    /**
     * Delete lead
     * @param  array $data lead
     * @param  mixed $id   lead id
     * @return boolean
     */
    public function delete($id)
    {
        //Get Leadnr
        $rowfield = $this->get($id,'providernr');
        
        $this->db->where($this->aid, $id);
        $this->db->delete($this->table);
        
        //Log Activity
        logActivity('Lead Provider Company Number Deleted [ID: ' . $id . ', ' . $rowfield->providernr . ']');
        
        return 1;
    }    
}
