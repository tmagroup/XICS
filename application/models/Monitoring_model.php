<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Monitoring_model extends CI_Model
{
    var $table = 'tblmonitorings';
    var $aid = 'monitoringnr';

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Customer_model');
        $this->load->model('Assignmentproduct_model');
    }

    /**
     * Check if Monitoring
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
            $monitoring = $this->db->get($this->table)->row();
            if ($monitoring) {
                $monitoring->additional_costs = $this->get_additional_costs($id, array('tblmonitoringassignmentstatus'=>'tblmonitoringassignmentstatus.id=tblmonitoringassignments.costincurredby'));
            }
            return $monitoring;
        }

        return $this->db->get($this->table)->result_array();
    }

    /**
     * Get additional costs
     * @since Version 1.0.4
     * @param  mixed $id lead id
     * @return array
     */
    public function get_additional_costs($id, $join=array())
    {
        //Join
        if(count($join)>0){
            foreach ($join as $key=>$value){
                $this->db->join($key, $value, 'left');
            }
            $this->db->select("tblmonitoringassignments.*, tblmonitoringassignmentstatus.name as costincurredbyname ");
        }
        $this->db->where('monitoringnr', $id);
        $this->db->order_by('created', 'DESC');
        return $this->db->get('tblmonitoringassignments')->result_array();
    }


    /**
     * Add new Monitoring
     * @param array $data Monitoring $_POST data
     */
    public function add($data)
    {
        //Database data
        $data['created'] = date('Y-m-d H:i:s');
        $data['ratestatus'] = 1;
        $this->db->insert($this->table, $data);
        $id = $this->db->insert_id();

        if($id>0){
            //Add ID Prefix
            $dataId = array();
            $dataId['monitoringnr_prefix'] = idprefix('monitoring',$id);
            $this->db->where($this->aid, $id);
            $this->db->update($this->table, $dataId);

            //Get Monitoringnr
            $rowfield = $this->get($id,'monitoringnr_prefix');
            //Log Activity
            logActivity('New Monitoring Added [ID: ' . $id . ', ' . $rowfield->monitoringnr_prefix . ']');
        }

        return $id;
    }

    /**
     * Update Monitoring
     * @param  array $data Monitoring
     * @param  mixed $id   Monitoring id
     * @return boolean
     */
    public function update($data, $id)
    {
        //Get Monitoring Status
        $rowfield = $this->get($id,'monitoringstatus');

        if(isset($data['ratestatus'])){
            $data['ratestatus'] = 1;
        }else{
            $data['ratestatus'] = 0;
        }

        //Database data
        $this->db->where($this->aid, $id);
        $this->db->update($this->table, $data);

        if ($this->db->affected_rows() > 0) {
            $this->db->query("UPDATE ".$this->table." SET `updated`='".date('Y-m-d H:i:s')."' WHERE ".$this->aid."='".$id."' ");

            //-When a Supporter change status = “Handlungsbedarf”
            if($GLOBALS['current_user']->userrole==5){
                //Send Email
                if($rowfield->monitoringstatus!=$data['monitoringstatus'] && $data['monitoringstatus']==2){

                    //Reminder Status change by supporter
                    $dataEmail = (array) $this->get($id,"tblmonitorings.*, "
                            . "  tblsalutations.name as salutation, responsible.name as name, responsible.surname as surname, responsible.email as email",

                            array('tblusers as responsible'=>'responsible.userid=tblmonitorings.responsible',
                                'tblsalutations'=>'tblsalutations.salutationid=responsible.salutation')
                    );

                    $this->sendMail($dataEmail);
                }
            }

            //Get Monitoringnr
            $rowfield = $this->get($id,'monitoringnr_prefix');
            //Log Activity
            logActivity('Monitoring Updated [ID: ' . $id . ', ' . $rowfield->monitoringnr_prefix . ']');
        }

        return $id;
    }

    function sendMail($data){
        //print_r($data);exit;
        $data['linktomonitoring'] = '<a href="'.base_url('admin/monitorings/detail/'. $data['monitoringnr']).'" target="_blank">'.lang('click_here').'</a>';
        //All Comments
        $data['comments'] = '';
        $comments = (array) $this->Note_model->get('',"tblnotes.*, CONCAT(tblusers.name,' ',tblusers.surname) as fullname ",array('tblusers'=>'tblusers.userid=tblnotes.addedfrom')," rel_id='".$data['monitoringnr']."' AND rel_type='monitoring' ","","tblnotes.id desc");
        if(isset($comments) && count($comments)>0){
            $data['comments'].='<table>';
            $iRow = 1;
            foreach($comments as $comment){

                $rowcolor = '';
                if($iRow%2==0){
                   $rowcolor = '#DDE3EC';
                }

                $data['comments'].="<tr style='background-color:".$rowcolor."'>";
                    $data['comments'].='<td>';

                        $data['comments'].='<table>';
                                $data['comments'].='<tr><td>'.$comment['fullname'].' at '._dt($comment['created']).'</td></tr>';
                                $data['comments'].='<tr><td><b>'.lang('page_dt_comment').'</b>: '.$comment['description'].'</td></tr>';
                        $data['comments'].='</table>';

                    $data['comments'].='</td>';
                $data['comments'].='</tr>';

                $iRow++;
            }

            $data['comments'].='</table>';
        }

        //Send to Responsible User of Customer
        $merge_fields = array();
        $merge_fields = array_merge($merge_fields, get_monitoringreminder_merge_fields($data));
        $sent = $this->Email_model->send_email_template('monitoring', $data['email'], $merge_fields);

        if ($sent) {
            return 1;
        }
        else{
            return 0;
        }
    }

    /**
     * Delete Monitoring
     * @param  array $data Monitoring
     * @param  mixed $id   Monitoring id
     * @return boolean
     */
    public function delete($id)
    {
        //Get Monitoringnr
        $rowfield = $this->get($id,'monitoringnr_prefix');

        $this->db->where($this->aid, $id);
        $this->db->delete($this->table);

        //Log Activity
        logActivity('Monitoring Deleted [ID: ' . $id . ', ' . $rowfield->monitoringnr_prefix . ']');

        return 1;
    }


    /* Import CSV
     */
    public function importcsv($data){

        //Monitoring
        $rowMonitoring = (array) $this->get($data['monitoringId'],"tblmonitorings.monitoringnr,tblmonitorings.monitoringvalue,assignment.assignmentnr,assignment.provider",
                array('tblusers as responsible'=>'responsible.userid=tblmonitorings.responsible',
                'tblcustomers as customer'=>'customer.customernr=tblmonitorings.customer',
                'tblmonitoringstatus'=>'tblmonitoringstatus.id=tblmonitorings.monitoringstatus',
                'tblassignments as assignment'=>'assignment.assignmentnr=tblmonitorings.assignmentnr',
                )
        );

        //Assignment Products Mobile Rate Price + Option Price
        $rowAssignmentProducts = $this->Assignmentproduct_model->get('','tblassignmentproducts.mobilenr, tblassignmentproducts.value2, '
                . '(tblassignmentproducts.value4 + COALESCE((SELECT SUM(value4) FROM tblassignmentproduct_moreoptionmobiles WHERE assignmentproductid=tblassignmentproducts.id),0)) as value4 ',

                array('tblvvlneu'=>'tblvvlneu.id=tblassignmentproducts.vvlneu',
                    'tblratesmobile as newratemobile'=>'newratemobile.ratenr=tblassignmentproducts.newratemobile',
                    'tbloptionsmobile as newoptionmobile'=>'newoptionmobile.optionnr=tblassignmentproducts.newoptionmobile',
                    'tblhardwares'=>'tblhardwares.hardwarenr=tblassignmentproducts.hardware'
                ),

            " tblassignmentproducts.assignmentnr='".$rowMonitoring['assignmentnr']."' "
        );

        if(isset($rowMonitoring['monitoringnr']) && $rowMonitoring['monitoringnr']>0){

            $csvMimes = array('application/vnd.ms-excel','text/plain','text/csv','text/tsv');
            if(!empty($_FILES['file_csv']['name']) && in_array($_FILES['file_csv']['type'],$csvMimes)){

                if(is_uploaded_file($_FILES['file_csv']['tmp_name'])){
                    $imported_records = 0;

                    if ( $rowMonitoring['provider'] === 'Telekom' ) { // FOR TELEKOM ONLY
                        $csv_file = fopen($_FILES['file_csv']['tmp_name'], 'r');
                        $csv_data = array();
                        while ( !feof($csv_file) ) {
                            $tmp = fgetcsv($csv_file, null, ';');
                            if ($tmp) { $csv_data[] = $tmp; }
                        }
                        fclose($csv_file);

                        foreach ($csv_data as $data) {
                            if ( isset($data[9]) && (trim($data[9]) === '880101011348') ) {
                                if ( isset($data[5]) ) {
                                    foreach ( $rowAssignmentProducts as $rowAssignmentProduct ) {
                                        if ( trim($data[5]) === trim($rowAssignmentProduct['mobilenr']) ) {
                                            //Mobile Rate Price+all Option Prices=ValueA from the Assignment
                                            $ValueA = $rowAssignmentProduct['value2'] + $rowAssignmentProduct['value4'];

                                            //Gesamtbetrag = ValueB from the new imported table
                                            $ValueB = str_replace(',', '.', $data[18]);

                                            //Selectboxvalue "Kulanzwert" from Customer Settings = ValueC
                                            $ValueC  = $rowMonitoring['monitoringvalue'];

                                            //Listed all Position where this is:
                                            //ValueA + (ValueA/100*ValueC) <= ValueB
                                            //Import only if codition true
                                            if ( ($ValueA + (($ValueA / 100) * $ValueC)) <= $ValueB ) {
                                                $mData['monitoringnr'] = $rowMonitoring['monitoringnr'];
                                                $mData['invoiceitem'] = trim($data[5]);
                                                $mData['invoicetotal'] = trim($ValueB);
                                                $mData['created'] = date('Y-m-d H:i:s');
                                                $this->db->insert('tblmonitoringassignments', $mData);

                                                $imported_records++;
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    } else { // FOR VODAFONE AND O2BUSINESS
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
                        // while(($line = fgetcsv($csvFile)) !== FALSE){
                        foreach ($csv_data as $key => $line) {

                            /*
                            Compare:
                            */

                            if(isset($line[6])){
                                foreach($rowAssignmentProducts as $rowAssignmentProduct){
                                    if(trim($line[6])==trim($rowAssignmentProduct['mobilenr'])){
                                        $line[12] = str_replace(',', '.',$line[12]);

                                        //Mobile Rate Price+all Option Prices=ValueA from the Assignment
                                        $ValueA = $rowAssignmentProduct['value2'] + $rowAssignmentProduct['value4'];

                                        //Gesamtbetrag = ValueB from the new imported table
                                        $ValueB = str_replace(',', '.', $line[12]);

                                        //Selectboxvalue "Kulanzwert" from Customer Settings = ValueC
                                        $ValueC  = $rowMonitoring['monitoringvalue'];

                                        //Listed all Position where this is:
                                        //ValueA + (ValueA/100*ValueC) <= ValueB
                                        //Import only if codition true
                                        if($ValueA + (($ValueA/100)*$ValueC) <= $ValueB){

                                            $mData['monitoringnr'] = $rowMonitoring['monitoringnr'];
                                            $mData['invoiceitem'] = trim($line[6]);
                                            $mData['invoicetotal'] = trim($line[12]);
                                            $mData['created'] = date('Y-m-d H:i:s');
                                            $this->db->insert('tblmonitoringassignments', $mData);

                                            $imported_records++;
                                        }
                                    }
                                }
                            }
                        }

                        //close opened csv file
                        fclose($csvFile);
                    }

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

    public function importcsvSecond($data)
    {
        $csv = array();
        $batchsize = 10; //split huge CSV file by 1,000, you can modify this based on your needs

        ini_set('memory_limit', '-1');

        if($_FILES['file_csv_second']['error'] == 0)
        {
            $name = $_FILES['file_csv_second']['name'];
            $ext = strtolower(end(explode('.', $_FILES['file_csv_second']['name'])));
            $tmpName = $_FILES['file_csv_second']['tmp_name'];

            if($ext === 'csv'){ //check if uploaded file is of CSV format
                $csv_data_in = array();
                $csv_data = array();
                if(($csv_file = fopen($tmpName, 'r')) !== FALSE) {

                    $dataAssigment = $this->db
                                    ->select('DISTINCT TRIM(leading "0" FROM mobilenr) AS mobilenr,simnr')
                                    ->from('tblassignmentproducts')
                                    ->where('mobilenr != ""')
                                    ->where('assignmentnr',$data['assignmentnr'])
                                    ->get()->result_array();

                    fgetcsv($csv_file);
                    $csv_mobile_number = array();
                    $new_uniq =  array();
                    while ( !feof($csv_file) ) {
                        $tmp = fgetcsv($csv_file);
                        if($tmp[0] != '') {
                            $csv_mobile_number[] = trim(ltrim($tmp[0], 0));
                        }
                    }

                    if(!empty($dataAssigment)) {
                        foreach ($dataAssigment as $key => $mobilenr) {
                            if (!in_array($mobilenr['mobilenr'],$csv_mobile_number)) {
                                $csv_data[$key]['monitoringnr'] = $data['monitoringId'];
                                $csv_data[$key]['mobilenr'] = $mobilenr['mobilenr'];
                                $csv_data[$key]['simnr'] = $mobilenr['simnr'];
                            }
                        }

                        $this->db->insert_batch('tblassignmentproducts_csv', $csv_data);
                    }

                    if(!empty($csv_data)) {
                        return array('status'=>1,'message'=>'import data successfully');
                    } else if(empty($csv_data)) {
                        return array('status'=>1,'message'=>'import data Not Found');
                    } else {
                        return array('status'=>0,'message'=>'import data Filed');
                    }
                    fclose($csv_file);
                }
            } else {
                return array('status'=>0,'message'=>lang('file choose type not csv only select csv file'));
            }
        }
    }
}
