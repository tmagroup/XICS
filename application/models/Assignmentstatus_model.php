<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Assignmentstatus_model extends CI_Model
{
    var $table = 'tblassignmentstatus';
    var $aid = 'id';
	
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Check if assignment status
     * @param  mixed $id
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
}
