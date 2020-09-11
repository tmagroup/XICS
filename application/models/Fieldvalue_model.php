<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Fieldvalue_model extends CI_Model
{
    var $table = 'tblfieldvalues';
    var $aid = 'field_id';
	
    public function __construct()
    {
        parent::__construct();        
    }

    /**
     * Check if field value
     * @param  mixed $field_value_id 
     * @return mixed
     */
    public function get($field_id, $rel_id, $rel_type)
    {    
        //Where 
        $this->db->where($this->aid."='".$field_id."' AND rel_id='".$rel_id."' AND rel_type='".$rel_type."'");        
        return $this->db->get($this->table)->row();
    }
    
    /**
     * Add new field value
     * @param array $data field value $_POST data
     */
    public function add($data)
    {    
        $this->db->insert($this->table, $data);
        return $this->db->insert_id();        
    }
    
    /**
     * Update field value
     * @param  array $data field value
     * @param  mixed $id   field value id
     * @return boolean
     */
    public function update($data, $field_id, $rel_id, $rel_type)
    {       
        $this->db->where($this->aid."='".$field_id."' AND rel_id='".$rel_id."' AND rel_type='".$rel_type."'");
        $this->db->update($this->table, $data);        
    }    
    
    /**
     * Delete field value
     * @param  array $data field value
     * @param  mixed $id   field value id
     * @return boolean
     */
    public function delete($rel_id, $rel_type)
    {        
        $this->db->where("rel_id='".$rel_id."' AND rel_type='".$rel_type."'");
        $this->db->delete($this->table);        
        return 1;
    } 
}