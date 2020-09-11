<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Hardwarebudgetuse_model extends CI_Model
{
    var $table = 'tblhardwarebudgetnutzen';
    var $aid = 'hardwarebudgetuse_id';

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

    public function get_total_total_excluding_vat_use() {
        $total_excluding_vat_use = $this->db->select_sum('total_excluding_vat_use')->get($this->table)->row()->total_excluding_vat_use;

        return $total_excluding_vat_use;
    }
}
