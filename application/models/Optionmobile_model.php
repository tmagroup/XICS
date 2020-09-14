<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Optionmobile_model extends CI_Model
{
    var $table = 'tbloptionsmobile';
    var $aid = 'optionnr';

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Check if option mobile
     * @param  mixed $optionnr
     * @return mixed
     */
    public function get($id='', $field='',$where='')
    {
        if($field!=""){
            $this->db->select($field);
        }

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
     * Add new option mobile
     * @param array $data option mobile $_POST data
     */
    public function add($data, $logMessage='')
    {
        if(empty($logMessage)){ $logMessage='Added'; }

        //Check Optiontitle
        $this->db->where('optiontitle', trim($data['optiontitle']));
        $optiontitle = $this->db->get($this->table)->row();
        if ($optiontitle) {
            return lang('page_form_validation_optiontitle_already_exists');
        }

        //Database data
        $data['created'] = date('Y-m-d H:i:s');
        $this->db->insert($this->table, $data);
        $id = $this->db->insert_id();

        if($id>0){
            //Log Activity
            logActivity('New Optionmobile '.$logMessage.' [ID: ' . $id . ', ' . $data['optiontitle'] . ']');

            if($logMessage=='Added'){
                //Add ID Prefix
                $dataId = array();
                $dataId['optionnr_prefix'] = idprefix('optionmobile',$id);
                $this->db->where($this->aid, $id);
                $this->db->update($this->table, $dataId);
            }
        }

        return $id;
    }

    /**
     * Update option mobile
     * @param  array $data option mobile
     * @param  mixed $id   option mobile id
     * @return boolean
     */
    public function update($data, $id)
    {
        //Check Optiontitle
        $this->db->where($this->aid.'!=', $id);
        $this->db->where('optiontitle', trim($data['optiontitle']));
        $optiontitle = $this->db->get($this->table)->row();
        if ($optiontitle) {
            return lang('page_form_validation_optiontitle_already_exists');
        }

        //Database data
        $data['updated'] = date('Y-m-d H:i:s');
        $this->db->where($this->aid, $id);
        $this->db->update($this->table, $data);
        if ($this->db->affected_rows() > 0) {
            //Log Activity
            logActivity('Optionmobile Updated [ID: ' . $id . ', ' . $data['optiontitle'] . ']');
        }

        return $id;
    }

    /**
     * Delete option mobile
     * @param  array $data option mobile
     * @param  mixed $id   option mobile id
     * @return boolean
     */
    public function delete($id)
    {
        //Get Title
        $rowfield = $this->get($id,'optiontitle');

        $this->db->where($this->aid, $id);
        $this->db->delete($this->table);

        //Log Activity
        logActivity('Optionmobile Deleted [ID: ' . $id . ', ' . $rowfield->optiontitle . ']');

        return 1;
    }

    /* Import CSV
     */
    public function importcsv($data){
        $csvMimes = array('application/vnd.ms-excel','text/plain','text/csv','text/tsv','application/octet-stream');
        if(!empty($_FILES['file_csv']['name']) && in_array($_FILES['file_csv']['type'],$csvMimes)){

            if(is_uploaded_file($_FILES['file_csv']['tmp_name'])){

                //open uploaded csv file with read only mode
	            $csvFile = fopen($_FILES['file_csv']['tmp_name'], 'r');

                fgetcsv($csvFile);
                $csv_data = array();
                while (!feof($csvFile)) {
                    $tmp = fgetcsv($csvFile, null, ';');
                    if ($tmp) { $csv_data[] = $tmp; }
                }
                fclose($csvFile);

                // skip first line
                // if your csv file have no heading, just comment the next line
                // fgetcsv($csvFile);

                //parse data from csv file line by line
                $imported_records = 0;
                // while(($line = fgetcsv($csvFile)) !== FALSE){
                foreach ($csv_data as $key => $line) {

                    //check whether member already exists in database with same optionnr
                    if (trim($line[0]) != '') {
                        $this->db->where("optionnr_prefix",trim($line[0]));
                    }
                    $this->db->or_where("optiontitle",trim($line[1]));
                    $result = $this->db->get($this->table)->result();

                    //Duplicate rows wont be imported
                    if(!count($result)){
                        $post = array();
                        $fkey = 0;
                        foreach($data['db_fields'] as $field){
                            if(in_array($field,$data['not_importable'])){continue;}

                            //Get Id from Values
                            switch($field){
                                case 'optionnr':
                                    $field = 'optionnr_prefix';
                                break;
                            }

                            $post = array_merge($post,array($field=>$line[$fkey]));
                            $fkey++;
                        }

                        //print_r($post);exit;

                        $insertid = $this->add($post,'Imported');
                        if (is_numeric($insertid) && $insertid>0) {
                            $dataId = array();
                            $dataId['optionnr_prefix'] = idprefix('optionmobile',$insertid);
                            $this->db->where($this->aid, $insertid);
                            $this->db->update($this->table, $dataId);

                            $temp = explode("-",$post['optionnr_prefix']);
                            $newid = end($temp);
                            // $this->update(array('optionnr'=>$newid),$insertid);
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
