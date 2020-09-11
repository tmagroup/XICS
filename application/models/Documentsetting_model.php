<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Documentsetting_model extends CI_Model
{
    var $table = 'tbldocumentsettings';
    var $aid = 'categoryid';
	
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Check if document setting
     * @param  mixed $categoryid 
     * @return mixed
     */
    public function get($id='', $field='', $where='')
    {      
        //Select
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
     * Add new document setting
     * @param array $data document setting $_POST data
     */
    public function add($data)
    {        
        //Check Categoryname 
        $this->db->where('categoryname', trim($data['categoryname']));
        $categoryname = $this->db->get($this->table)->row();
        if ($categoryname) {            
            return lang('page_form_validation_categoryname_already_exists');
        }
        
        //Database data
        $this->db->insert($this->table, $data);
        $id = $this->db->insert_id();
        
        if($id>0){            
            //Log Activity
            logActivity('New Documentsetting Added [ID: ' . $id . ', ' . $data['categoryname'] . ']');            
        }
        
        return $id;
    }
    
    /**
     * Update document setting
     * @param  array $data document setting
     * @param  mixed $id   document setting id
     * @return boolean
     */
    public function update($data, $id)
    {
        //Check Categoryname 
        $this->db->where($this->aid.'!=', $id);
        $this->db->where('categoryname', trim($data['categoryname']));
        $categoryname = $this->db->get($this->table)->row();
        if ($categoryname) {            
            return lang('page_form_validation_categoryname_already_exists');
        }
        
        //Database data
        $this->db->where($this->aid, $id);
        $this->db->update($this->table, $data);
        if ($this->db->affected_rows() > 0) {
            //Log Activity
            logActivity('Documentsetting Updated [ID: ' . $id . ', ' . $data['categoryname'] . ']');
        } 
        
        return $id;
    }    
    
    /**
     * Delete document setting
     * @param  array $data document setting
     * @param  mixed $id   document setting id
     * @return boolean
     */
    public function delete($id)
    {
        //Get Title
        $rowfield = $this->get($id,'categoryname');
        
        $this->db->where($this->aid, $id);
        $this->db->delete($this->table);
        
        //Log Activity
        logActivity('Documentsetting Deleted [ID: ' . $id . ', ' . $rowfield->categoryname . ']');
        
        return 1;
    }    
}
