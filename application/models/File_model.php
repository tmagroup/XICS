<?php
defined('BASEPATH') or exit('No direct script access allowed');
class File_model extends CI_Model
{
    var $table = 'tblfiles';
    var $aid = 'id';
	
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Check if file
     * @param  mixed $id
     * @return mixed
     */
    public function get($id='', $field='', $where="")
    {        
        //Select Fields
        if($field!=""){
            $this->db->select($field);
        }
       
        //Where 
        if($where!=""){           
            $this->db->where($where);           
        }
        
        if (is_numeric($id)) {
            $this->db->where($this->table.".".$this->aid, $id);
            return $this->db->get($this->table)->row();
        }

        return $this->db->get($this->table)->result_array();
    }
}