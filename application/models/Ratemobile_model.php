<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Ratemobile_model extends CI_Model
{
    var $table = 'tblratesmobile';
    var $aid = 'ratenr';
	
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Simcardfunction_model');    
        $this->load->model('Sub_model');    
        $this->load->model('Mobileflaterate_model');    
        $this->load->model('Landlineflaterate_model');    
        $this->load->model('Vodafoneflaterate_model');    
        $this->load->model('Euroaming_model');    
        $this->load->model('Smsflaterate_model');  
        $this->load->model('Field_model'); 
        $this->load->model('Fieldvalue_model'); 
    }

    /**
     * Check if rate mobile
     * @param  mixed $ratenr 
     * @return mixed
     */
    public function get($id='', $field='', $join=array(), $where="")
    {        
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
        
        if (is_numeric($id)) {
            $this->db->where($this->aid, $id);
            
            return $this->db->get($this->table)->row();
        }

        return $this->db->get($this->table)->result_array();
    }
    
    /**
     * Add new rate mobile
     * @param array $data rate mobile $_POST data
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
        $data1 = $data;
        
        //Unset Not for related table
        $extrafields = $this->Field_model->get('ratemobile');
        if(isset($extrafields) && count($extrafields)>0){
            foreach($extrafields as $extrafield){
                unset($data['field_'.$extrafield['field_id']]);
            }
        }
        
        $this->db->insert($this->table, $data);
        $id = $this->db->insert_id();
        $data = $data1;
        
        if($id>0){   
            
            //Extra Fields
            if(isset($extrafields) && count($extrafields)>0){
                foreach($extrafields as $extrafield){
                    $field_value = $this->Fieldvalue_model->get($extrafield['field_id'], $id, 'ratemobile');
                    if(isset($field_value->field_value_id) && $field_value->field_value_id>0){
                        $datafield = array('field_value'=>$data['field_'.$extrafield['field_id']]);                        
                        $this->Fieldvalue_model->update($datafield, $extrafield['field_id'], $id, 'ratemobile');
                    }else{
                        $datafield = array('field_value'=>$data['field_'.$extrafield['field_id']], 'field_id'=>$extrafield['field_id'], 'rel_id'=>$id, 'rel_type'=>'ratemobile');                        
                        $this->Fieldvalue_model->add($datafield);
                    }
                }
            }
            
            //Log Activity
            logActivity('New Ratemobile '.$logMessage.' [ID: ' . $id . ', ' . $data['ratetitle'] . ']');
            
            //if($logMessage=='Added'){
                //Add ID Prefix
                $dataId = array();
                $dataId['ratenr_prefix'] = idprefix('ratemobile',$id);
                $this->db->where('ratenr', $id);
                $this->db->update($this->table, $dataId);
            //}
        }
        
        return $id;
    }
    
    /**
     * Update rate mobile
     * @param  array $data rate mobile
     * @param  mixed $id   rate mobile id
     * @return boolean
     */
    public function update($data, $id, $logMessage='')
    { 
        if(empty($logMessage)){ $logMessage='Updated'; }
        
        //Check Ratetitle 
        if($logMessage==''){
            $this->db->where($this->aid.'!=', $id);
            $this->db->where('ratetitle', trim($data['ratetitle']));
            $ratetitle = $this->db->get($this->table)->row();
            if ($ratetitle) {            
                return lang('page_form_validation_ratetitle_already_exists');
            }
        }
        
        //Database data
        $data['updated'] = date('Y-m-d H:i:s');
        $data1 = $data;
        
        //Unset Not for related table
        $extrafields = $this->Field_model->get('ratemobile');
        if(isset($extrafields) && count($extrafields)>0){
            foreach($extrafields as $extrafield){
                unset($data['field_'.$extrafield['field_id']]);
            }
        }
        
        $this->db->where($this->aid, $id);
        $this->db->update($this->table, $data);
        $data = $data1;
        
        if ($this->db->affected_rows() > 0 || $logMessage=='Imported') {
            
            //If Import then we update from title
            if($logMessage=='Imported'){
                $field = $this->get('', 'ratenr', array(), $where="LCASE(ratetitle)='".strtolower(trim($id))."'");
                $id = $field[0]['ratenr'];
            }    
            
            //Extra Fields
            if(isset($extrafields) && count($extrafields)>0){
                foreach($extrafields as $extrafield){
                    $field_value = $this->Fieldvalue_model->get($extrafield['field_id'], $id, 'ratemobile');
                    if(isset($field_value->field_value_id) && $field_value->field_value_id>0){
                        $datafield = array('field_value'=>$data['field_'.$extrafield['field_id']]);                        
                        $this->Fieldvalue_model->update($datafield, $extrafield['field_id'], $id, 'ratemobile');
                    }else{
                        $datafield = array('field_value'=>$data['field_'.$extrafield['field_id']], 'field_id'=>$extrafield['field_id'], 'rel_id'=>$id, 'rel_type'=>'ratemobile');                        
                        $this->Fieldvalue_model->add($datafield);
                    }
                }
            }
            
            //Log Activity
            logActivity('Ratemobile '.$logMessage.' [ID: ' . $id . ', ' . $data['ratetitle'] . ']');
        } 
        
        return $id;
    }    
    
    /**
     * Delete rate mobile
     * @param  array $data rate mobile
     * @param  mixed $id   rate mobile id
     * @return boolean
     */
    public function delete($id)
    {
        //Get Title
        $rowfield = $this->get($id,'ratetitle');
        
        $this->db->where($this->aid, $id);
        $this->db->delete($this->table);
        
        //Delete Extra Field Value 
        $this->Fieldvalue_model->delete($id, 'ratemobile');
        
        //Log Activity
        logActivity('Ratemobile Deleted [ID: ' . $id . ', ' . $rowfield->ratetitle . ']');
        
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
                    //$this->db->where("ratenr_prefix",trim($line[0]));
                    //$this->db->or_where("ratetitle",trim($line[1]));
                    
                    $this->db->where("LCASE(ratetitle)",strtolower(trim($line[0])));
                    $result = $this->db->get($this->table)->result();
                    
                    
                    $post = array();     
                    $fkey = 0;
                    foreach($data['db_fields'] as $field){
                        if(in_array($field,$data['not_importable'])){continue;} 

                        //Get Id from Values
                        switch($field){

                            /*case 'ratenr':
                                $field = 'ratenr_prefix';
                            break;*/

                            case 'simcard_function':
                                $line[$fkey] = @$this->Simcardfunction_model->get('','id'," LCASE(name)='".strtolower(trim($line[$fkey]))."' ")[0]['id'];
                            break;

                            case 'subn':
                                $line[$fkey] = @$this->Sub_model->get('','id'," LCASE(name)='".strtolower(trim($line[$fkey]))."' ")[0]['id'];
                            break;

                            case 'mobileflaterate':
                                $line[$fkey] = @$this->Mobileflaterate_model->get('','id'," LCASE(name)='".strtolower(trim($line[$fkey]))."' ")[0]['id'];
                            break;

                            case 'landingflaterate':
                                $line[$fkey] = @$this->Landlineflaterate_model->get('','id'," LCASE(name)='".strtolower(trim($line[$fkey]))."' ")[0]['id'];
                            break;

                            case 'vodafoneflaterate':
                                $line[$fkey] = @$this->Vodafoneflaterate_model->get('','id'," LCASE(name)='".strtolower(trim($line[$fkey]))."' ")[0]['id'];
                            break;

                            case 'eu_roaming':
                                $line[$fkey] = @$this->Euroaming_model->get('','id'," LCASE(name)='".strtolower(trim($line[$fkey]))."' ")[0]['id'];
                            break;

                            case 'smsflaterate':
                                $line[$fkey] = @$this->Smsflaterate_model->get('','id'," LCASE(name)='".strtolower(trim($line[$fkey]))."' ")[0]['id'];
                            break;
							
							/*NEW CODE 18 Apr 2018 */
							case 'ultracard':
								if(strtolower(trim($line[$fkey]))=='ja'){
	                                $line[$fkey] = 1;
								}else{
									$line[$fkey] = 2;
								}
                            break;
							/*End NEW CODE 18 Apr 2018 */
                        }
                        
                        //Extra fields
						
						
                        /*if($fkey>10){                           
                            $post = array_merge($post,array('field_'.($fkey-10)=>trim($line[$fkey])));
                        }
                        else{
                            $post = array_merge($post,array($field=>trim($line[$fkey])));                            
                        }*/
						
						
						/*NEW CODE 18 Apr 2018 */
						if($fkey>11){                           
                            $post = array_merge($post,array('field_'.($fkey-11)=>trim($line[$fkey])));
                        }
                        else{
                            $post = array_merge($post,array($field=>trim($line[$fkey])));                            
                        }
						/*End NEW CODE 18 Apr 2018 */
                        
                        $fkey++;
                    }

                    
                    //Duplicate rows wont be imported
                    if(!count($result)){                        
                        //print_r($post);exit;
                        $insertid = $this->add($post); 
                        $imported_records++;
                        /*$insertid = $this->add($post,'Imported');                        
                        if (is_numeric($insertid) && $insertid>0) {   
                            $temp = explode("-",$post['ratenr_prefix']);
                            $newid = end($temp);
                            $this->update(array('ratenr'=>$newid),$insertid);
                            $imported_records++;
                        }*/
                    }
                    else{
                        $this->aid = 'LCASE(ratetitle)';
                        $this->update($post,strtolower(trim($line[0])),'Imported');
                        $imported_records++;
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
