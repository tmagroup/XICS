<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Quotationproduct_model extends CI_Model
{
    var $table = 'tblquotationproducts';
    var $aid = 'id';

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Check if quotation product
     * @param  mixed $id
     * @return mixed
     */
    public function get($id='', $field='', $join=array(), $where="")
    {
        //Field
        if($field!=""){
            $this->db->select($field);
        }

        //Join
        if(count($join)>0){
            foreach ($join as $key=>$value){
                $this->db->join($key, $value, 'left');
            }
        }

        //Where
	if($where!=""){
            $this->db->where($where);
        }

        //Order by
        $this->db->order_by($this->table.".".$this->aid, "asc");

        if (is_numeric($id)) {
            $this->db->where($this->aid, $id);

            return $this->db->get($this->table)->row();
        }

        return $this->db->get($this->table)->result_array();
    }

    /**
     * Add new quotation product
     * @param array $data quotation product $_POST data
     */
    public function add($data)
    {
        //Database data
        if(isset($data['endofcontract']) && $data['endofcontract']!=""){
            $data['endofcontract'] = to_sql_date($data['endofcontract'], false);
        }
        $data['activationdate'] = (isset($data['activationdate']) && $data['activationdate']!="")?to_sql_date($data['activationdate'], false):'';
        $this->db->insert($this->table, $data);
        $id = $this->db->insert_id();

        if($id>0){
            //Log Activity
            logActivity('New Quotation Product Added [ID: ' . $id . ', ' . $data['mobilenr'] . ']');
        }

        return $id;
    }

    /**
     * Update quotation product
     * @param  array $data quotation product
     * @param  mixed $id   quotation product id
     * @return boolean
     */
    public function update($data, $id)
    {
        //Database data
        if(isset($data['endofcontract']) && $data['endofcontract']!=""){
            $data['endofcontract'] = to_sql_date($data['endofcontract'], false);
        }
        $data['activationdate'] = (isset($data['activationdate']) && $data['activationdate']!="")?to_sql_date($data['activationdate'], false):'';
        $this->db->where($this->aid, $id);
        $this->db->update($this->table, $data);
        if ($this->db->affected_rows() > 0) {
            //Log Activity
            logActivity('Quotation Product Updated [ID: ' . $id . ', ' . $data['mobilenr'] . ']');
        }

        return $id;
    }

    /**
     * Delete quotation product
     * @param  array $data quotation product
     * @param  mixed $id   quotation product id
     * @return boolean
     */
    public function delete($id)
    {
        //Get Customernr
        $rowfield = $this->get($id,'mobilenr');

        $this->db->where($this->aid, $id);
        $this->db->delete($this->table);

        //Log Activity
        logActivity('Quotation Product Deleted [ID: ' . $id . ', ' . (isset($rowfield) && $rowfield->mobilenr ? $rowfield->mobilenr : '') . ']');

        return 1;
    }
}
