<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Hardwarecategory_model extends CI_Model
{
    var $table = 'tblhardwarecategories';
    var $aid = 'id';
    
    public function __construct()
    {
        parent::__construct();
    }

    public function get($id = '', $field='', $where='')
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
}
