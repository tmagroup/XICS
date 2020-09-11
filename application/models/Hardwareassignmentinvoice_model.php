<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Hardwareassignmentinvoice_model extends CI_Model
{
    var $table = 'tblhardwareassignmentinvoices';
    var $aid = 'invoicenr';
    
    public function __construct()
    {
        parent::__construct();       
        $this->load->model('Hardwareassignmentinvoiceproduct_model');
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
     * Add new Hardware assignment invoice 
     * @param array $data Hardware assignment invoice $_POST data
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
            $dataId['invoicenr_prefix'] = idprefix('hardwareinvoice',$id);            
            $this->db->where($this->aid, $id);
            $this->db->update($this->table, $dataId);
            
            //Get Hardwareassignmentnr
            $rowfield = $this->get($id,'invoicenr_prefix');        
            //Log Activity
            logActivity('New Hardware Assignment Invoice Added [ID: ' . $id . ', ' . $rowfield->invoicenr_prefix . ']');
        }
        
        return $id;
    }
    
    /**
     * Update Hardware assignment
     * @param  array $data Hardware assignment
     * @param  mixed $id   Hardware assignment id
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
            
            //Get Hardwareassignmentnr
            $rowfield = $this->get($id,'invoicenr_prefix');        
            //Log Activity
            logActivity('Hardware Assignment Invoice Updated [ID: ' . $id . ', ' . $rowfield->invoicenr_prefix . ']');
        } 
        
        return $id;
    } 
    
    /**
     * Delete hardwareinvoice
     * @param  array $data hardwareinvoice
     * @param  mixed $id   hardwareinvoice id
     * @return boolean
     */
    public function delete($id)
    {
        //Get Invoicenr
        $rowfield = $this->get($id,'invoicenr_prefix');        
        $this->db->where($this->aid, $id);
        $this->db->delete($this->table);
        
        //Log Activity
        logActivity('Hardware Assignment Invoice Deleted [ID: ' . $id . ', ' . $rowfield->invoicenr_prefix . ']');
        
        //Delete Hardwareassignment Invoice Products
        $hardwareassignmentinvoiceproducts = $this->Hardwareassignmentinvoiceproduct_model->get('', 'id', array(), " invoicenr='".$id."' ");
        if(isset($hardwareassignmentinvoiceproducts) && count($hardwareassignmentinvoiceproducts)>0){
            foreach($hardwareassignmentinvoiceproducts as $hardwareassignmentinvoiceproduct){
               $this->Hardwareassignmentinvoiceproduct_model->delete($hardwareassignmentinvoiceproduct['id']);
            }                
        }
        
        return 1;
    }  
    
    /* Send Email Hardware Invoice */
    function sendEmail($data){                
        $merge_fields = array();     
        $merge_fields = array_merge($merge_fields, get_hardwareinvoice_merge_fields($data));
        
        //PDF Attachment        
        $file = FCPATH.'uploads/hardware_assignment_invoices/HardwareInvoice-'.$data['invoicenr_prefix'].'.pdf';     
        $this->Email_model->add_attachment(array('attachment' => $file));    
        
        $sent = $this->Email_model->send_email_template('hardwareinvoice-reminder', $data['customer_email'], $merge_fields);
        if ($sent) {
            do_action('hardwareinvoicereminder_sent', $data['invoicenr']);
            return 1;
        }     
        else{ 
            return 0;
        }
    } 
}
