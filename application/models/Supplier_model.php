<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Supplier_model extends CI_Model
{
    var $table = 'tblsuppliers';
    var $aid = 'suppliernr';
	
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Check if supplier
     * @param  mixed $suppliernr 
     * @return mixed
     */
    public function get($id='', $field='')
    {        
        if($field!=""){
            $this->db->select($field);
        }
        
        if (is_numeric($id)) {
            $this->db->where($this->aid, $id);
            
            return $this->db->get($this->table)->row();
        }

        return $this->db->get($this->table)->result_array();
    }
    
    /**
     * Add new supplier
     * @param array $data supplier $_POST data
     */
    public function add($data)
    {        
        //Check Companyname 
        $this->db->where('companyname', trim($data['companyname']));
        $companyname = $this->db->get($this->table)->row();
        if ($companyname) {            
            return lang('page_form_validation_companyname_already_exists');
        }
        
        //Database data
        $data['created'] = date('Y-m-d H:i:s');
        $this->db->insert($this->table, $data);
        $id = $this->db->insert_id();
        
        if($id>0){            
            //Log Activity
            logActivity('New Supplier Added [ID: ' . $id . ', ' . $data['companyname'] . ']');
            
            //Add ID Prefix
            $dataId = array();
            $dataId['suppliernr_prefix'] = idprefix('supplier',$id);
            $this->db->where($this->aid, $id);
            $this->db->update($this->table, $dataId);
        }
        
        return $id;
    }
    
    /**
     * Update supplier
     * @param  array $data supplier
     * @param  mixed $id   supplier id
     * @return boolean
     */
    public function update($data, $id)
    {
        //Check Companyname 
        $this->db->where($this->aid.'!=', $id);
        $this->db->where('companyname', trim($data['companyname']));
        $companyname = $this->db->get($this->table)->row();
        if ($companyname) {            
            return lang('page_form_validation_companyname_already_exists');
        }
        
        //Database data
        $data['updated'] = date('Y-m-d H:i:s');
        $this->db->where($this->aid, $id);
        $this->db->update($this->table, $data);
        if ($this->db->affected_rows() > 0) {
            //Log Activity
            logActivity('Supplier Updated [ID: ' . $id . ', ' . $data['companyname'] . ']');
        } 
        
        return $id;
    }    
    
    /**
     * Delete supplier
     * @param  array $data supplier
     * @param  mixed $id   supplier id
     * @return boolean
     */
    public function delete($id)
    {
        //Get Title
        $rowfield = $this->get($id,'companyname');
        
        $this->db->where($this->aid, $id);
        $this->db->delete($this->table);
        
        //Log Activity
        logActivity('Supplier Deleted [ID: ' . $id . ', ' . $rowfield->companyname . ']');
        
        return 1;
    }    
}
