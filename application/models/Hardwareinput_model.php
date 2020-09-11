<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Hardwareinput_model extends CI_Model
{
    var $table = 'tblhardwareinputs';
    var $aid = 'hardwareinputnr';
	
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Hardwareinputproduct_model');        
    }

    /**
     * Check if hardwareinput
     * @param  mixed $hardwareinputnr 
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
     * Add new hardwareinput
     * @param array $data hardwareinput $_POST data
     */
    public function add($data)
    {  
        //Database data
        $data['created'] = date('Y-m-d H:i:s');
        $data['userid'] = get_user_id();
        $data['hardwareinputdate'] = to_sql_date($data['hardwareinputdate'], false); 
        $data1 = $data;
        
        foreach($data['seriesnr'] as $fk=>$fd){                 
            if(isset($data['new_quantity_'.$fk])){
                unset($data['new_quantity_'.$fk]);  
            }
        }
        unset($data['count_hardwareinputproduct']); 
        unset($data['seriesnr']);        
        unset($data['hardware']);
        
        $this->db->insert($this->table, $data);
        $id = $this->db->insert_id();
        $data = $data1;
        
        if($id>0){   
            
            //Add ID Prefix
            $dataId = array();
            $dataId['hardwareinputnr_prefix'] = idprefix('hardwareinput',$id);
            $this->db->where($this->aid, $id);
            $this->db->update($this->table, $dataId);
            
            //Hardwareinput Products
            foreach($data['seriesnr'] as $fk=>$fd){
                $dataproduct = array('hardwareinputnr'=>$id,
                    'seriesnr'=>$fd,
                    'hardware'=>$data['hardware'][$fk]                    
                );    
                if(isset($data['hardwareinputproductid'][$fk]) && $data['hardwareinputproductid'][$fk]>0){
                    if(trim($fd)!=""){
                        $dataproduct['quantity'] = $data['old_quantity_'.$fk];
                        $this->Hardwareinputproduct_model->update($dataproduct, $data['hardwareinputproductid'][$fk]);
                    }else{
                        $this->Hardwareinputproduct_model->delete($data['hardwareinputproductid'][$fk]);
                    }
                }else{
                    if(trim($fd)!=""){ 
                        $dataproduct['quantity'] = $data['new_quantity_'.$fk];
                        $hardwareinputproductid = $this->Hardwareinputproduct_model->add($dataproduct);
                    }
                }
            }
            
            //Get Hardwareinputnr
            $rowfield = $this->get($id,'hardwareinputnr_prefix');
            //Log Activity
            logActivity('New Hardwareinput Added [ID: ' . $id . ', ' . $rowfield->hardwareinputnr_prefix . ']');         
        }
        
        return $id;
    }
    
    /**
     * Update hardwareinput
     * @param  array $data hardwareinput
     * @param  mixed $id   hardwareinput id
     * @return boolean
     */
    public function update($data, $id)
    {    
        //Database data
        $data['updated'] = date('Y-m-d H:i:s');
        $data['hardwareinputdate'] = to_sql_date($data['hardwareinputdate'], false);
        $data1 = $data;
        foreach($data['seriesnr'] as $fk=>$fd){                 
            if(isset($data['new_quantity_'.$fk])){
                unset($data['new_quantity_'.$fk]);  
            }
            if(isset($data['old_quantity_'.$fk])){
                unset($data['old_quantity_'.$fk]);  
            }
        }
        unset($data['count_hardwareinputproduct']);
        unset($data['hardwareinputproductid']);        
        unset($data['seriesnr']);        
        unset($data['hardware']);
        
        $this->db->where($this->aid, $id);
        $this->db->update($this->table, $data);
        $data = $data1;
        
        //Hardwareinput Products
        if(isset($data['seriesnr'])){
            //Hardwareinput Products
            foreach($data['seriesnr'] as $fk=>$fd){
                $dataproduct = array('hardwareinputnr'=>$id,
                    'seriesnr'=>$fd,                    
                    'hardware'=>$data['hardware'][$fk]                    
                );                
                if(isset($data['hardwareinputproductid'][$fk]) && $data['hardwareinputproductid'][$fk]>0){
                    if(trim($fd)!=""){  
                        $dataproduct['quantity'] = $data['old_quantity_'.$fk];
                        $this->Hardwareinputproduct_model->update($dataproduct, $data['hardwareinputproductid'][$fk]);
                    }else{
                        $this->Hardwareinputproduct_model->delete($data['hardwareinputproductid'][$fk]);
                    }
                }else{
                    if(trim($fd)!=""){
                        $dataproduct['quantity'] = $data['new_quantity_'.$fk];
                        $hardwareinputproductid = $this->Hardwareinputproduct_model->add($dataproduct);
                    }
                }
                             
            }
        }
        
        if ($this->db->affected_rows() > 0) {            
            //Get Hardwareinputnr
            $rowfield = $this->get($id,'hardwareinputnr_prefix');        
            //Log Activity
            logActivity('Hardwareinput Updated [ID: ' . $id . ', ' . $rowfield->hardwareinputnr_prefix . ']');
        } 
        
        return $id;
    }    
    
    /**
     * Delete hardwareinput
     * @param  array $data hardwareinput
     * @param  mixed $id   hardwareinput id
     * @return boolean
     */
    public function delete($id)
    {
        //Get Hardwareinputnr
        $rowfield = $this->get($id,'hardwareinputnr_prefix');        
        $this->db->where($this->aid, $id);
        $this->db->delete($this->table);
        
        //Log Activity
        logActivity('Hardwareinput Deleted [ID: ' . $id . ', ' . $rowfield->hardwareinputnr_prefix . ']');
        
        //Delete Hardwareinput Products
        $hardwareinputproducts = $this->Hardwareinputproduct_model->get('', 'id', array(), " hardwareinputnr='".$id."' ");
        if(isset($hardwareinputproducts) && count($hardwareinputproducts)>0){
            foreach($hardwareinputproducts as $hardwareinputproduct){
               $this->Hardwareinputproduct_model->delete($hardwareinputproduct['id']);
            }                
        }
        
        return 1;
    }    
    
}