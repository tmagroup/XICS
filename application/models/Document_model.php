<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Document_model extends CI_Model
{   
    public function __construct()
    {
        parent::__construct();        
    }
    
    /**
     * Check if Document
     * @return mixed
     */
    public function get($id='')
    {        
        $document['attachments'] = $this->get_document_attachments($id);
        return $document;
    }
    
    /**
     * Get document attachments
     * @since Version 1.0.4
     * @param  mixed $id document id
     * @return array
     */
    public function get_document_attachments($id = '', $attachment_id = '')
    {       
        if(get_user_role()=='customer'){
            $roledoc = 'customerdocument';
        }
        else{
            $roledoc = 'userdocument';
        }
        
        if (is_numeric($attachment_id)) {
            $this->db->where('id', $attachment_id);

            return $this->db->get('tblfiles')->row();
        }
        
        
        //Admin and Salesmanager should be see a Menu "Dokumente" too. Where you see all uploaded files from each User. It must be able to delete files too from Admin and Salesmanager.
        if($GLOBALS['current_user']->userrole==1 || $GLOBALS['current_user']->userrole==2){
            
            $this->db->select("tblfiles.*, category.categoryname, IF(rel_type='customerdocument',(SELECT CONCAT(name,' ',surname) FROM tblcustomers WHERE customernr=tblfiles.rel_id),(SELECT CONCAT(name,' ',surname) FROM tblusers WHERE userid=tblfiles.rel_id)) as uploaded_by ");
            
            $this->db->where("rel_type IN('userdocument')");
        }
        else{
            $this->db->where('rel_id', $id);
            $this->db->where('rel_type', $roledoc);
        }
        
        
        $this->db->order_by('created', 'DESC');
        
        $this->db->join('tbldocumentsettings as category', 'category.categoryid=tblfiles.categoryid', 'left');

        return $this->db->get('tblfiles')->result_array();
    }
    
    //Add Attachment 
    public function add_attachment_to_database($rel_id, $attachment, $external = false, $form_activity = false)
    {
        if(get_user_role()=='customer'){
            $roledoc = 'customerdocument';
            $rolename = 'Customer';
        }
        else{
            $roledoc = 'userdocument';
            $rolename = 'User';
        }
        
        $post = $this->input->post();         
        $data['categoryid'] = $post['categoryid'];
        
        $data['created'] = date('Y-m-d H:i:s');
        $data['rel_id'] = get_user_id();
        $data['userid'] = get_user_id();
        $data['rel_type'] = $roledoc;        
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
            logActivity('Document Attachment Added ['.$rolename.'ID: ' . $rel_id . ', File Name: '.$data['file_name'].']');
            
            //History 
            $Action_data = array('actionname'=>'userdocument', 'actionid'=>$rel_id, 'actionsubid'=>$insert_id, 'actiontitle'=>'userdocument_added');
            do_action_history($Action_data);
        }
        
        return $insert_id;
    }
    
    /**
     * Delete document attachment
     * @param  mixed $id attachment id
     * @return boolean
     */
    public function delete_document_attachment($id)
    {
        $attachment = $this->get_document_attachments('', $id);
        $deleted    = false;
        
        if(get_user_role()=='customer'){
            $roledoc = 'customerdocument';
            $rolename = 'Customer';
        }
        else{
            $roledoc = 'userdocument';
            $rolename = 'User';
        }
        
        if ($attachment) {
            if (empty($attachment->external)) {
                unlink(get_upload_path_by_type($roledoc) . $attachment->rel_id . '/' . $attachment->file_name);
            }
            $this->db->where('id', $attachment->id);
            $this->db->delete('tblfiles');
            if ($this->db->affected_rows() > 0) {
                $deleted = true;
                logActivity('Document Attachment Deleted ['.$rolename.'ID: ' . $attachment->rel_id . ', File Name: '.$attachment->file_name.']');
                
                //History 
                $Action_data = array('actionname'=>'userdocument', 'actionid'=>$attachment->rel_id, 'actionsubid'=>$attachment->id, 'actiontitle'=>'userdocument_deleted');
                do_action_history($Action_data);                
            }

            if (is_dir(get_upload_path_by_type($roledoc) . $attachment->rel_id)) {
                // Check if no attachments left, so we can delete the folder also
                $other_attachments = list_files(get_upload_path_by_type($roledoc) . $attachment->rel_id);
                if (count($other_attachments) == 0) {
                    // okey only index.html so we can delete the folder also
                    delete_dir(get_upload_path_by_type($roledoc) . $attachment->rel_id);
                }
            }
        }

        return $deleted;
    }
    
}
