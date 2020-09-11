<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Hardwareassignmentproduct_model extends CI_Model
{
    var $table = 'tblhardwareassignmentproducts';
    var $aid = 'id';
	
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Check if hardware assignment product
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
            $this->db->where($this->table.".".$this->aid, $id);
            
            return $this->db->get($this->table)->row();
        }

        return $this->db->get($this->table)->result_array();
    }
    
    /**
     * Add new hardware assignment product
     * @param array $data hardware assignment product $_POST data
     */
    public function add($data)
    {   
        //Date of the Day what Day the Hardware was send out
        //"Sendungsnr" filled out. This is the Date where Hardware was send out!
        if(isset($data['shippingnr']) && $data['shippingnr']!=""){
            $data['shippingnr_date'] = date('Y-m-d');
        }
        
        $this->db->insert($this->table, $data);
        $id = $this->db->insert_id();
        
        if($id>0){            
            //Log Activity
            logActivity('New Hardware Assignment Product Added [ID: ' . $id . ', ' . $data['mobilenr'] . ']');         
        }
        
        return $id;
    }
    
    /**
     * Update hardware assignment product
     * @param  array $data hardware assignment product
     * @param  mixed $id   hardware assignment product id
     * @return boolean
     */
    public function update($data, $id)
    {   
        //Date of the Day what Day the Hardware was send out
        //"Sendungsnr" filled out. This is the Date where Hardware was send out!
        if(isset($data['shippingnr']) && $data['shippingnr']!=""){
            $data['shippingnr_date'] = date('Y-m-d');
        }
        
        $this->db->where($this->aid, $id);
        $this->db->update($this->table, $data);
        $updateRow = $this->db->affected_rows();
        
        if ($updateRow > 0) {
            //Log Activity
            if(isset($data['mobilenr'])){
                logActivity('Hardware Assignment Product Updated [ID: ' . $id . ', ' . $data['mobilenr'] . ']');
            }
        } 
        
        return $updateRow;
    }    
    
    /**
     * Delete hardware assignment product
     * @param  array $data hardware assignment product
     * @param  mixed $id   hardware assignment product id
     * @return boolean
     */
    public function delete($id)
    {
        //Get Customernr
        $rowfield = $this->get($id,'mobilenr');
        
        $this->db->where($this->aid, $id);
        $this->db->delete($this->table);
        
        //Log Activity
        logActivity('Hardware Assignment Product Deleted [ID: ' . $id . ', ' . $rowfield->mobilenr . ']');
        
        return 1;
    }    
    
    
    /**
     * Get hardware position attachments
     * @since Version 1.0.4
     * @param  mixed $id assignment id
     * @return array
     */
    public function get_hardwareposition_documents($id = '', $attachment_id = '')
    {
        if (is_numeric($attachment_id)) {
            $this->db->where('id', $attachment_id);

            return $this->db->get('tblfiles')->row();
        }
        $this->db->where('rel_id', $id);
        $this->db->where('rel_type', 'hardwareassignmentpositiondocument');
        $this->db->order_by('created', 'DESC');
        
        $this->db->join('tbldocumentsettings as category', 'category.categoryid=tblfiles.categoryid', 'left');
        
        return $this->db->get('tblfiles')->result_array();
    }
    
    //Add Attachment 
    public function add_attachment_to_database($rel_id, $attachment, $external = false, $form_activity = false)
    {
        if($this->input->post()){
            $post = $this->input->post();           
            
            if(isset($post['categoryid'])){
                $data['categoryid'] = $post['categoryid'];
            }
            else{
                $data['categoryid'] = $attachment[0]['categoryid'];
            }
            
        }else{
            $data['categoryid'] = $attachment[0]['categoryid'];
        }
        
        $data['created'] = date('Y-m-d H:i:s');
        $data['rel_id'] = $rel_id;
        $data['userid'] = get_user_id();
        $data['rel_type'] = 'hardwareassignmentpositiondocument';        
        $data['attachment_key'] = app_generate_hash();

        if ($external == false) {
            $data['file_name'] = $attachment[0]['file_name'];
            $data['filetype']  = $attachment[0]['filetype'];
        } else {
            $path_parts            = pathinfo($attachment[0]['name']);
            $data['file_name']     = $attachment[0]['name'];
            $data['external_link'] = $attachment[0]['link'];
            $data['filetype']      = get_mime_by_extension('.' . $path_parts['extension']);
            $data['external']      = $external;
            if (isset($attachment[0]['thumbnailLink'])) {
                $data['thumbnail_link'] = $attachment[0]['thumbnailLink'];
            }
        }
        
        $db = $this->db;
        $db->insert('tblfiles', $data);
        $insert_id = $db->insert_id();
        
        if($insert_id>0){
            //$assignment = $this->get($rel_id,'id');
            
            //Get Assignment Id
            $assignment = $this->get($rel_id,"tblassignmentproducts.assignmentnr",array("tblassignmentproducts"=>"tblassignmentproducts.id=tblhardwareassignmentproducts.productpositionid"));        
            logActivity('Hardware Assignment Position Document Added [HardwareAssignmentProductID: ' . $rel_id . ', '.$insert_id.']');
            
            //History 
            $Action_data = array('actionname'=>'assignment', 'actionid'=>$assignment->assignmentnr, 'actionsubid'=>$insert_id, 'actiontitle'=>'hardwareassignmentposition_document_added');
            do_action_history($Action_data);
        }
        
        return $insert_id;
    }
    
    /**
     * Delete hardware assignment attachment
     * @param  mixed $id attachment id
     * @return boolean
     */
    public function delete_hardwareassignmentposition_document($id)
    {
        $attachment = $this->get_hardwareposition_documents('', $id);
        //$assignment = $this->get($attachment->rel_id,'id');
        
        //Get Assignment Id
        $assignment = $this->get($attachment->rel_id,"tblassignmentproducts.assignmentnr",array("tblassignmentproducts"=>"tblassignmentproducts.id=tblhardwareassignmentproducts.productpositionid"));        
        $deleted    = false;

        if ($attachment) {
            if (empty($attachment->external)) {
                unlink(get_upload_path_by_type('hardwareassignmentpositiondocument') . $attachment->rel_id . '/' . $attachment->file_name);
            }
            $this->db->where('id', $attachment->id);
            $this->db->delete('tblfiles');
            if ($this->db->affected_rows() > 0) {
                $deleted = true;
                logActivity('Hardware Assignment Position Document Deleted [HardwareAssignmentProductID: ' . $attachment->rel_id . ', '.$id.']');
                
                //History 
                $Action_data = array('actionname'=>'assignment', 'actionid'=>$assignment->assignmentnr, 'actionsubid'=>$id, 'actiontitle'=>'hardwareassignmentposition_document_deleted');
                do_action_history($Action_data);
            }

            if (is_dir(get_upload_path_by_type('hardwareassignmentpositiondocument') . $attachment->rel_id)) {
                // Check if no attachments left, so we can delete the folder also
                $other_attachments = list_files(get_upload_path_by_type('hardwareassignmentpositiondocument') . $attachment->rel_id);
                if (count($other_attachments) == 0) {
                    // okey only index.html so we can delete the folder also
                    delete_dir(get_upload_path_by_type('hardwareassignmentpositiondocument') . $attachment->rel_id);
                }
            }
        }

        return $deleted;
    }
}
