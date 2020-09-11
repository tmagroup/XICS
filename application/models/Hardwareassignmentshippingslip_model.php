<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Hardwareassignmentshippingslip_model extends CI_Model
{
    var $table = 'tblhardwareassignmentshippingslips';
    var $aid = 'shippingslipnr';
    
    public function __construct()
    {
        parent::__construct();                
    }
    
    /**
     * Check if Hardware assignment
     * @return mixed
     */
    public function get($id='', $field='', $join=array(), $where="", $groupby="")
    {        
        //Select Fields
        if($field!=""){
            $this->db->select($field);
        }
        
        //Join
        if(count($join)>0){
            foreach ($join as $key=>$value){
                $this->db->join($key, $value, 'left');
            }
        }
        
        //Group By
        if($groupby!=""){
            $this->db->group_by($groupby);
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
    
    /**
     * Add new Hardware assignment shipingslip 
     * @param array $data Hardware assignment shipingslip $_POST data
     */
    public function add($data)
    {  
        //Database data
        $data['created'] = date('Y-m-d H:i:s');
        $this->db->insert($this->table, $data);
        $id = $this->db->insert_id();
        
        if($id>0){   
            //Add ID Prefix
            $dataId = array();
            $dataId['shippingslipnr_prefix'] = idprefix('shippingslip',$id);            
            $this->db->where($this->aid, $id);
            $this->db->update($this->table, $dataId);
            
            //Get Shippingslipnr
            $rowfield = $this->get($id,'shippingslipnr_prefix');        
            //Log Activity
            logActivity('New Hardware Assignment Shipping Slip Added [ID: ' . $id . ', ' . $rowfield->shippingslipnr_prefix . ']');
        }
        
        return $id;
    }
    
    /**
     * Update Hardware assignment shipingslip
     * @param  array $data Hardware assignment shipingslip
     * @param  mixed $id   Hardware assignment shipingslip id
     * @return boolean
     */
    public function update($data, $id)
    {    
        //Database data 
        $this->db->where($this->aid, $id);     
        $this->db->update($this->table, $data);
        $updateRow = $this->db->affected_rows();
        
        if ($updateRow > 0) {
            $this->db->query("UPDATE ".$this->table." SET `updated`='".date('Y-m-d H:i:s')."' WHERE ".$this->aid."='".$id."' ");
            
            //Get Shippingslipnr
            $rowfield = $this->get($id,'shippingslipnr_prefix');        
            //Log Activity
            logActivity('Hardware Assignment Shipping Slip Updated [ID: ' . $id . ', ' . $rowfield->shippingslipnr_prefix . ']');
        } 
        
        return $id;
    } 
    
    /**
     * Delete deliverynote
     * @param  array $data deliverynote
     * @param  mixed $id   deliverynote id
     * @return boolean
     */
    public function delete($id)
    {
        //Get Shippingslipnr
        $rowfield = $this->get($id,'shippingslipnr_prefix');        
        $this->db->where($this->aid, $id);
        $this->db->delete($this->table);
        
        //Log Activity
        logActivity('Hardware Assignment Shipping Slip Deleted [ID: ' . $id . ', ' . $rowfield->shippingslipnr_prefix . ']');
        
        //Delete Hardwareassignment Shipping Slip Products
        $hardwareassignmentshippingslipproducts = $this->Hardwareassignmentshippingslipproduct_model->get('', 'id', array(), " shippingslipnr='".$id."' ");
        if(isset($hardwareassignmentshippingslipproducts) && count($hardwareassignmentshippingslipproducts)>0){
            foreach($hardwareassignmentshippingslipproducts as $hardwareassignmentshippingslipproduct){
               $this->Hardwareassignmentshippingslipproduct_model->delete($hardwareassignmentshippingslipproduct['id']);
            }                
        }
        
        return 1;
    }  
}
