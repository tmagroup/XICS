<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Salutation_model extends CI_Model
{
    var $table = 'tblsalutations';
    var $aid = 'salutationid';
    
    public function __construct()
    {
        parent::__construct();
    }

    public function get($id = '')
    {
        if (is_numeric($id)) {
            $this->db->where($this->aid, $id);
            return $this->db->get($this->table)->row();
        }

        return $this->db->get($this->table)->result_array();
    }
}
