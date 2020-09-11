<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Hardwarebudget_model extends CI_Model
{
    var $table = 'tblhardwarebudget';
    var $aid = 'hardwarebudget_id';

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Add new Hardware assignment
     * @param array $data Hardware assignment $_POST data
     */
    public function add($data)
    {
        //Database data
        $data['created_by_role_id'] = $GLOBALS['current_user']->userrole;
        $data['created_by_id'] = get_user_id();
        $data['created_at'] = date('Y-m-d H:i:s');
        $this->db->insert($this->table, $data);
        $id = $this->db->insert_id();

        return $id;
    }

    public function get_total_total_excluding_vat() {
        $total_excluding_vat = $this->db->select_sum('total_excluding_vat')->get($this->table)->row()->total_excluding_vat;

        return $total_excluding_vat;
    }
}
