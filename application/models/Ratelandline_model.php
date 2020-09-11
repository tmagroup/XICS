<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Ratelandline_model extends CI_Model
{
    var $table = 'tblrateslandline';
    var $aid = 'ratenr';
	
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Check if rate land line
     * @param  mixed $ratenr 
     * @return mixed
     */
    public function get($id='', $field='')
    {        
        if($field!=""){
            $this->db->select($field);
        }
        
        if (is_numeric($id)) {
            $this->db->where($this->aid, $id);
            
            return $this->db->get($this->table)->row();
        }

        return $this->db->get($this->table)->result_array();
    }
    
    /**
     * Add new rate land line
     * @param array $data rate land line $_POST data
     */
    public function add($data, $logMessage='')
    {        
        if(empty($logMessage)){ $logMessage='Added'; }
        
        //Check Ratetitle 
        $this->db->where('ratetitle', trim($data['ratetitle']));
        $ratetitle = $this->db->get($this->table)->row();
        if ($ratetitle) {            
            return lang('page_form_validation_ratetitle_already_exists');
        }
        
        //Database data
        $data['created'] = date('Y-m-d H:i:s');
        $this->db->insert($this->table, $data);
        $id = $this->db->insert_id();
        
        if($id>0){       
            //Log Activity
            logActivity('New Ratelandline '.$logMessage.' [ID: ' . $id . ', ' . $data['ratetitle'] . ']');
            
            if($logMessage=='Added'){
                //Add ID Prefix
                $dataId = array();
                $dataId['ratenr_prefix'] = idprefix('ratelandline',$id);
                $this->db->where($this->aid, $id);
                $this->db->update($this->table, $dataId);
            }    
        }
        
        return $id;
    }
    
    /**
     * Update rate land line
     * @param  array $data rate land line
     * @param  mixed $id   rate land line id
     * @return boolean
     */
    public function update($data, $id)
    {
        //Check Ratetitle 
        $this->db->where($this->aid.'!=', $id);
        $this->db->where('ratetitle', trim($data['ratetitle']));
        $ratetitle = $this->db->get($this->table)->row();
        if ($ratetitle) {            
            return lang('page_form_validation_ratetitle_already_exists');
        }
        
        //Database data
        $data['updated'] = date('Y-m-d H:i:s');
        $this->db->where($this->aid, $id);
        $this->db->update($this->table, $data);
        if ($this->db->affected_rows() > 0) {
            //Log Activity
            logActivity('Ratelandline Updated [ID: ' . $id . ', ' . $data['ratetitle'] . ']');
        } 
        
        return $id;
    }    
    
    /**
     * Delete rate land line
     * @param  array $data rate land line
     * @param  mixed $id   rate land line id
     * @return boolean
     */
    public function delete($id)
    {
        //Get Title
        $rowfield = $this->get($id,'ratetitle');
        
        $this->db->where($this->aid, $id);
        $this->db->delete($this->table);
        
        //Log Activity
        logActivity('Ratelandline Deleted [ID: ' . $id . ', ' . $rowfield->ratetitle . ']');
        
        return 1;
    }    
    
   
    /* Import CSV
     */
    public function importcsv($data){        
        $csvMimes = array('application/vnd.ms-excel','text/plain','text/csv','text/tsv');        
        if(!empty($_FILES['file_csv']['name']) && in_array($_FILES['file_csv']['type'],$csvMimes)){
        
            if(is_uploaded_file($_FILES['file_csv']['tmp_name'])){
                                
                //open uploaded csv file with read only mode
	        $csvFile = fopen($_FILES['file_csv']['tmp_name'], 'r');
                                
                // skip first line
                // if your csv file have no heading, just comment the next line
                fgetcsv($csvFile);
                                
                //parse data from csv file line by line
                $imported_records = 0;
                while(($line = fgetcsv($csvFile)) !== FALSE){
                    
                    //check whether member already exists in database with same ratenr
                    $this->db->where("ratenr_prefix",trim($line[0]));
                    $this->db->or_where("ratetitle",trim($line[1]));
                    $result = $this->db->get($this->table)->result();
                    
                    //Duplicate rows wont be imported
                    if(!count($result)){
                        $post = array();     
                        $fkey = 0;
                        foreach($data['db_fields'] as $field){
                            if(in_array($field,$data['not_importable'])){continue;} 
                            
                            //Get Id from Values
                            switch($field){
                                case 'ratenr':
                                    $field = 'ratenr_prefix';
                                break;                                    
                            }
                            
                            $post = array_merge($post,array($field=>$line[$fkey]));                            
                            $fkey++;
                        }
                        
                        //print_r($post);exit;
                        
                        $insertid = $this->add($post,'Imported');                        
                        if (is_numeric($insertid) && $insertid>0) {   
                            $temp = explode("-",$post['ratenr_prefix']);
                            $newid = end($temp);
                            $this->update(array('ratenr'=>$newid),$insertid);
                            $imported_records++;
                        }
                    }
                }

                //close opened csv file
                fclose($csvFile);
                
                if($imported_records>0){
                    return array('status'=>1,'message'=>sprintf(lang('import_total_imported'),$imported_records));               
                }
                else{
                    return array('status'=>0,'message'=>sprintf(lang('import_total_imported'),$imported_records));               
                }
                
            }else{
                return array('status'=>0,'message'=>lang('import_upload_failed'));
            }            
        }else{
            return array('status'=>0,'message'=>lang('import_upload_failed'));
        }    
    }
}
