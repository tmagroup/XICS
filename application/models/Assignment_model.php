<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Assignment_model extends CI_Model
{
    var $table = 'tblassignments';
    var $aid = 'assignmentnr';

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Assignmentproduct_model');
        $this->load->model('Assignmentproductmoreoptionmobile_model');
        $this->load->model('Assignmentreminder_model');
        $this->load->model('Email_model');
        $this->load->model('User_model');
        $this->load->model('Assignmentstatus_model');
        $this->load->model('Ratemobile_model');
        $this->load->model('Optionmobile_model');
        $this->load->model('Discountlevel_model');
        $this->load->model('Employeecommission_model');
        $this->load->model('Hardwareassignment_model');
        $this->load->model('Hardwareassignmentproduct_model');
        $this->load->model('Qualitycheck_model');
        $this->load->model('Todo_model');
        $this->load->model('Ticket_model');
        $this->load->model('Field_model');
        $this->load->model('Monitoring_model');
    }

    /**
     * Check if assignment
     * @param  mixed $assignmentnr
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
            $assignment = $this->db->get($this->table)->row();
            if ($assignment) {
                $assignment->attachments = $this->get_assignment_attachments($id);
                $assignment->legitimations = $this->get_assignment_legitimations($id);
            }
            return $assignment;
        }

        return $this->db->get($this->table)->result_array();
    }

    /**
     * Add new assignment
     * @param array $data assignment $_POST data
     */
    public function add($data)
    {
        //Database data
        $data['created'] = date('Y-m-d H:i:s');
        $data['userid'] = get_user_id();
        $data['assignmentdate'] = to_sql_date($data['assignmentdate'], false);
        $data1 = $data;

        foreach($data['mobilenr'] as $fk=>$fd){
            if(isset($data['new_formula_'.$fk])){
                unset($data['new_formula_'.$fk]);
            }
        }
        if(isset($data['count_assignmentproduct'])){
            unset($data['count_assignmentproduct']);
        }

        unset($data['mobilenr']);
        unset($data['vvlneu']);
        unset($data['newratemobile']);
        unset($data['value2']);
        unset($data['endofcontract']);
        unset($data['hardware']);
        unset($data['newoptionmobile']);
        unset($data['value4']);

        if(isset($data['more_newoptionmobile'])){
            unset($data['more_newoptionmobile']);
        }
        if(isset($data['more_value4'])){
            unset($data['more_value4']);
        }

        if(isset($data['simnr'])){
            unset($data['simnr']);
        }
        if(isset($data['employee'])){
            unset($data['employee']);
        }
        if(isset($data['extemtedterm'])){
            unset($data['extemtedterm']);
        }
        if(isset($data['subscriptionlock'])){
            unset($data['subscriptionlock']);
        }
        if(isset($data['cardstatus'])){
            unset($data['cardstatus']);
        }
        if(isset($data['finished'])){
            unset($data['finished']);
        }
        if(isset($data['simcard_function_id'])){
            unset($data['simcard_function_id']);
        }
        if(isset($data['simcard_function_nm'])){
            unset($data['simcard_function_nm']);
        }
        if(isset($data['simcard_function_qty'])){
            unset($data['simcard_function_qty']);
        }
        if(isset($data['ultracard1'])){
            unset($data['ultracard1']);
        }
        if(isset($data['ultracard2'])){
            unset($data['ultracard2']);
        }
        if(isset($data['pin'])){
            unset($data['pin']);
        }
        if(isset($data['puk'])){
            unset($data['puk']);
        }

        $this->db->insert($this->table, $data);
        $id = $this->db->insert_id();
        $data = $data1;

        //echo '<pre>';
        //print_r($data);
        //exit;

        if($id>0){

            //Add ID Prefix
            $dataId = array();
            $dataId['assignmentnr_prefix'] = idprefix('assignment',$id);
            $this->db->where($this->aid, $id);
            $this->db->update($this->table, $dataId);

            //Assignment Products
            $updateProductRow = 0;
            $updateProductIDs = array();

            foreach($data['mobilenr'] as $fk=>$fd){
                $extemtedterm = isset($data['extemtedterm'][$fk])?$data['extemtedterm'][$fk]:0;
                $subscriptionlock = isset($data['subscriptionlock'][$fk])?$data['subscriptionlock'][$fk]:0;
                $cardstatus = isset($data['cardstatus'][$fk])?$data['cardstatus'][$fk]:0;
                $finished = isset($data['finished'][$fk])?$data['finished'][$fk]:0;
                $ultracard1 = isset($data['ultracard1'][$fk])?$data['ultracard1'][$fk]:0;
                $ultracard2 = isset($data['ultracard2'][$fk])?$data['ultracard2'][$fk]:0;

                $pin = isset($data['pin'][$fk])?$data['pin'][$fk]:'';
                $puk = isset($data['puk'][$fk])?$data['puk'][$fk]:'';

                /* When I choose by Selectbox “VVL/Neu”>> Neu&
                Datefield must be empty */
                if($data['vvlneu'][$fk]==2){ $data['endofcontract'][$fk]=''; }
                $endofcontract = $data['endofcontract'][$fk];
                $newratemobile = $data['newratemobile'][$fk];

                $dataproduct = array('assignmentnr'=>$id,
                    'mobilenr'=>$fd,
                    'simnr'=>isset($data['simnr'][$fk])?$data['simnr'][$fk]:'',
                    'employee'=>isset($data['employee'][$fk])?$data['employee'][$fk]:'',
                    'vvlneu'=>$data['vvlneu'][$fk],
                    'newratemobile'=>$newratemobile,
                    'value2'=>$data['value2'][$fk],
                    'endofcontract'=>$endofcontract,
                    'hardware'=>$data['hardware'][$fk],
                    'newoptionmobile'=>$data['newoptionmobile'][$fk],
                    'value4'=>$data['value4'][$fk],
                    'extemtedterm'=>$extemtedterm,
                    'subscriptionlock'=>$subscriptionlock,
                    'cardstatus'=>$cardstatus,
                    'finished'=>$finished,
                    'ultracard1'=>$ultracard1,
                    'ultracard2'=>$ultracard2,
                    'pin'=>$pin,
                    'puk'=>$puk
                );

                if(isset($data['assignmentproductid'][$fk]) && $data['assignmentproductid'][$fk]>0){
                    $assignmentproductid = $data['assignmentproductid'][$fk];
                    $old_product_data = $this->Assignmentproduct_model->get($data['assignmentproductid'][$fk],'finished');

                    if(trim($fd)!=""){
                        $dataproduct['formula'] = $data['old_formula_'.$fk];
                        $updateProductRow1 = $this->Assignmentproduct_model->update($dataproduct, $data['assignmentproductid'][$fk]);

                        if($updateProductRow1>0){
                            $updateProductIDs[] = $data['assignmentproductid'][$fk];
                            $updateProductRow++;
                        }
                    }else{
                        $this->Assignmentproduct_model->delete($data['assignmentproductid'][$fk]);
                    }

                    //Add Assignmentproduct More Option Mobile
                    $this->saveProductMoreOptionMobile($id, $fk, $assignmentproductid, $data);
                }else{
                    $old_product_data->finished = '';
                    //$old_product_data = '';

                    if(trim($fd)!=""){
                        $dataproduct['formula'] = $data['new_formula_'.$fk];
                        $assignmentproductid = $this->Assignmentproduct_model->add($dataproduct);

                        if($assignmentproductid>0){
                            $updateProductIDs[] = $assignmentproductid;
                            $updateProductRow++;

                            //Add Assignmentproduct More Option Mobile
                            $this->saveProductMoreOptionMobile($id, $fk, $assignmentproductid, $data);
                        }
                    }
                }

                /******************************************************************************************
                - There where we checked the checkbox “Finished” we done this steps in the database..
                ******************************************************************************************/
                /* 1 :: The Date we choose will changed and saved in the DB as this date  */
                $this->updateDateExtemtedTerm($assignmentproductid, $old_product_data, $newratemobile, $endofcontract, $finished, $extemtedterm);
                /* 3 :: In a new Table “Employeecommision” should saved */
                $this->saveEmployeeCommision($assignmentproductid, $old_product_data, $finished, $data);
                /******************************************************************************************
                - End There where we checked the checkbox “Finished” we done this steps in the database..
                ******************************************************************************************/

            }

            /*****************************************************************************************************/
            /* 2 :: In the Database for assignment should be a row “Provicheck” (Standardvalue is 0) should set =1 */
            /*$finished = 0;
            foreach($data['mobilenr'] as $fk=>$fd){
                $finished = $finished + (isset($data['finished'][$fk])?$data['finished'][$fk]:0);
            }
            if($finished>1){
                $data = array('provicheck'=>1);
                $this->db->where($this->aid, $id);
                $this->db->update($this->table, $data);
            }*/

            /* 4 :: Hardware-Assignment */
            if($updateProductRow > 0){
                $this->saveHardwareAssignment($id, $data1, $updateProductIDs);
            }
            /* 5 :: Qualitycheck  */
            $this->saveQualitycheck($id, $data1);

            /* 6 :: Generate Monitoring If Not Exists in db */
            $this->generateMonitoring($id);
            /*****************************************************************************************************/

            //Get Assignmentnr
            $rowfield = $this->get($id,'assignmentnr_prefix');
            //Log Activity
            logActivity('New Assignment Added [ID: ' . $id . ', ' . $rowfield->assignmentnr_prefix . ']');
        }

        return $id;
    }

    /**
     * Update assignment
     * @param  array $data assignment
     * @param  mixed $id   assignment id
     * @return boolean
     */
    public function update($data, $id)
    {
        //Database data
        $data['updated'] = date('Y-m-d H:i:s');
        if(isset($data['assignmentdate'])){
            $data['assignmentdate'] = to_sql_date($data['assignmentdate'], false);
        }
        $data1 = $data;

        if(isset($data['mobilenr'])){
            foreach($data['mobilenr'] as $fk=>$fd){
                if(isset($data['new_formula_'.$fk])){
                    unset($data['new_formula_'.$fk]);
                }
                if(isset($data['old_formula_'.$fk])){
                    unset($data['old_formula_'.$fk]);
                }
            }
        }

        unset($data['count_assignmentproduct']);
        unset($data['assignmentproductid']);
        unset($data['mobilenr']);
        unset($data['vvlneu']);
        unset($data['newratemobile']);
        unset($data['value2']);
        unset($data['endofcontract']);
        unset($data['hardware']);
        unset($data['newoptionmobile']);
        unset($data['value4']);

        if(isset($data['more_newoptionmobile'])){
            unset($data['more_newoptionmobile']);
        }
        if(isset($data['more_value4'])){
            unset($data['more_value4']);
        }

        if(isset($data['simnr'])){
            unset($data['simnr']);
        }
        if(isset($data['employee'])){
            unset($data['employee']);
        }
        if(isset($data['extemtedterm'])){
            unset($data['extemtedterm']);
        }
        if(isset($data['subscriptionlock'])){
            unset($data['subscriptionlock']);
        }
        if(isset($data['cardstatus'])){
            unset($data['cardstatus']);
        }
        if(isset($data['finished'])){
            unset($data['finished']);
        }
        if(isset($data['simcard_function_id'])){
            unset($data['simcard_function_id']);
        }
        if(isset($data['simcard_function_nm'])){
            unset($data['simcard_function_nm']);
        }
        if(isset($data['simcard_function_qty'])){
            unset($data['simcard_function_qty']);
        }
        if(isset($data['ultracard1'])){
            unset($data['ultracard1']);
        }
        if(isset($data['ultracard2'])){
            unset($data['ultracard2']);
        }
        if(isset($data['pin'])){
            unset($data['pin']);
        }
        if(isset($data['puk'])){
            unset($data['puk']);
        }

        $this->db->where($this->aid, $id);
        $this->db->update($this->table, $data);
        $data = $data1;

        //echo '<pre>';
        //print_r($data);
        //exit;

        //Assignment Products
        $updateProductRow = 0;
        $updateProductIDs = array();

        if(isset($data['mobilenr'])){
            //Assignment Products
            foreach($data['mobilenr'] as $fk=>$fd){
                $extemtedterm = isset($data['extemtedterm'][$fk])?$data['extemtedterm'][$fk]:0;
                $subscriptionlock = isset($data['subscriptionlock'][$fk])?$data['subscriptionlock'][$fk]:0;
                $cardstatus = isset($data['cardstatus'][$fk])?$data['cardstatus'][$fk]:0;
                $finished = isset($data['finished'][$fk])?$data['finished'][$fk]:0;
                $ultracard1 = isset($data['ultracard1'][$fk])?$data['ultracard1'][$fk]:0;
                $ultracard2 = isset($data['ultracard2'][$fk])?$data['ultracard2'][$fk]:0;

                $pin = isset($data['pin'][$fk])?$data['pin'][$fk]:'';
                $puk = isset($data['puk'][$fk])?$data['puk'][$fk]:'';

                /* When I choose by Selectbox “VVL/Neu”>> Neu&
                Datefield must be empty */
                if($data['vvlneu'][$fk]==2){ $data['endofcontract'][$fk]=''; }
                $endofcontract = $data['endofcontract'][$fk];
                $newratemobile = $data['newratemobile'][$fk];

                $dataproduct = array('assignmentnr'=>$id,
                    'mobilenr'=>$fd,
                    'simnr'=>isset($data['simnr'][$fk])?$data['simnr'][$fk]:'',
                    'employee'=>isset($data['employee'][$fk])?$data['employee'][$fk]:'',
                    'vvlneu'=>$data['vvlneu'][$fk],
                    'newratemobile'=>$newratemobile,
                    'value2'=>$data['value2'][$fk],
                    'endofcontract'=>$endofcontract,
                    'hardware'=>$data['hardware'][$fk],
                    'newoptionmobile'=>$data['newoptionmobile'][$fk],
                    'value4'=>$data['value4'][$fk],
                    'extemtedterm'=>$extemtedterm,
                    'subscriptionlock'=>$subscriptionlock,
                    'cardstatus'=>$cardstatus,
                    'finished'=>$finished,
                    'ultracard1'=>$ultracard1,
                    'ultracard2'=>$ultracard2,
                    'pin'=>$pin,
                    'puk'=>$puk
                );
                if(isset($data['assignmentproductid'][$fk]) && $data['assignmentproductid'][$fk]>0){
                    $assignmentproductid = $data['assignmentproductid'][$fk];
                    $old_product_data = $this->Assignmentproduct_model->get($data['assignmentproductid'][$fk],'finished');

                    if(trim($fd)!=""){
                        $dataproduct['formula'] = $data['old_formula_'.$fk];
                        $updateProductRow1 = $this->Assignmentproduct_model->update($dataproduct, $data['assignmentproductid'][$fk]);

                        if($updateProductRow1>0){
                            $updateProductIDs[] = $data['assignmentproductid'][$fk];
                            $updateProductRow++;
                        }
                    }else{
                        $this->Assignmentproduct_model->delete($data['assignmentproductid'][$fk]);
                    }

                    //Add Assignmentproduct More Option Mobile
                    $this->saveProductMoreOptionMobile($id, $fk, $assignmentproductid, $data);
                }else{
                    $old_product_data->finished = '';

                    if(trim($fd)!=""){
                        $dataproduct['formula'] = $data['new_formula_'.$fk];
                        $assignmentproductid = $this->Assignmentproduct_model->add($dataproduct);

                        if($assignmentproductid>0){
                            $updateProductIDs[] = $assignmentproductid;
                            $updateProductRow++;

                            //Add Assignmentproduct More Option Mobile
                            $this->saveProductMoreOptionMobile($id, $fk, $assignmentproductid, $data);
                        }
                    }
                }

                /******************************************************************************************
                - There where we checked the checkbox “Finished” we done this steps in the database..
                ******************************************************************************************/
                /* 1 :: The Date we choose will changed and saved in the DB as this date */
                $this->updateDateExtemtedTerm($assignmentproductid, $old_product_data, $newratemobile, $endofcontract, $finished, $extemtedterm);
                /* 3 :: In a new Table “Employeecommision” should saved */
                $this->saveEmployeeCommision($assignmentproductid, $old_product_data, $finished, $data);
                /******************************************************************************************
                - End There where we checked the checkbox “Finished” we done this steps in the database..
                ******************************************************************************************/
            }


            /*****************************************************************************************************/
            /* 2 :: In the Database for assignment should be a row “Provicheck” (Standardvalue is 0) should set =1 */
            /*$finished = 0;
            foreach($data['mobilenr'] as $fk=>$fd){
                $finished = $finished + (isset($data['finished'][$fk])?$data['finished'][$fk]:0);
            }
            if($finished>1){
                $data = array('provicheck'=>1);
                $this->db->where($this->aid, $id);
                $this->db->update($this->table, $data);
            }*/

            /* 4 :: Hardware-Assignment */
            if($updateProductRow > 0){
                $this->saveHardwareAssignment($id, $data1, $updateProductIDs);
            }
            /* 5 :: Qualitycheck  */
            $this->saveQualitycheck($id, $data1);
            /*****************************************************************************************************/
        }

        /* 6 :: Generate Monitoring If Not Exists in db */
        $this->generateMonitoring($id);

        if ($this->db->affected_rows() > 0) {
            //Get Assignmentnr
            $rowfield = $this->get($id,'assignmentnr_prefix');
            //Log Activity
            logActivity('Assignment Updated [ID: ' . $id . ', ' . $rowfield->assignmentnr_prefix . ']');
        }

        return $id;
    }

    function updateDateExtemtedTerm($assignmentproductid, $old_product_data, $newratemobile, $endofcontract, $finished, $extemtedterm){

        if($endofcontract==""){
            $endofcontract = date('Y-m-d');
        }

        if(isset($assignmentproductid) && $assignmentproductid>0 && isset($endofcontract) && $endofcontract!=""){
            if((isset($old_product_data->finished) && $old_product_data->finished!=$finished && $finished==1)){
                if($extemtedterm==1){
                    //- When checkbox ExtendedTerm is checked >>
                    //Datetoday + 2 Years + ExtemtedTerm from database Mobile Rate of each Position
                    $ratemobile = $this->Ratemobile_model->get($newratemobile,'extemptedterm');
                    if(isset($ratemobile)){
                        $extemptedterm = $ratemobile->extemptedterm;
                    }
                    if(isset($endofcontract) && $endofcontract!=""){
                        //$endofcontract = to_sql_date($endofcontract, false);
                        //$endofcontract = date('Y-m-d', strtotime('+2 year +'.$extemptedterm.' days', strtotime($endofcontract)));
                        $endofcontract = date('Y-m-d', strtotime('+2 year +'.$extemptedterm.' months', strtotime($endofcontract)));
                        $dataproduct['endofcontract'] = _d($endofcontract);
                        $this->Assignmentproduct_model->update($dataproduct, $assignmentproductid);
                    }
                }
                else{
                    //- When checkbox ExtemtedTerm is not checked>>
                    //Datetoday + 2 Years
                    if(isset($endofcontract) && $endofcontract!=""){
                        //$endofcontract = to_sql_date($endofcontract, false);
                        $endofcontract = date('Y-m-d', strtotime('+2 year', strtotime($endofcontract)));
                        $dataproduct['endofcontract'] = _d($endofcontract);
                        $this->Assignmentproduct_model->update($dataproduct, $assignmentproductid, 'img');
                    }
                }
            }
        }
    }

    function saveEmployeeCommision($assignmentproductid, $old_product_data, $finished, $data){
        if(isset($assignmentproductid) && $assignmentproductid>0){
            if((isset($old_product_data->finished) && $old_product_data->finished!=$finished && $finished==1)){
                //Assignment Products
                $assignmentproduct = (array) $this->Assignmentproduct_model->get($assignmentproductid,'tblassignmentproducts.*, tblvvlneu.name as vvlneu, '
                        . " newratemobile.ratenr as newratenr, "
                        . ' tblsubs.name as subname',
                        array('tblvvlneu'=>'tblvvlneu.id=tblassignmentproducts.vvlneu',
                            'tblratesmobile as newratemobile'=>'newratemobile.ratenr=tblassignmentproducts.newratemobile',
                            'tblsubs'=>'tblsubs.id=newratemobile.subn',
                        )
                );
                $assignment = (array) $this->Discountlevel_model->get($data['newdiscountlevel'], 'discounttitle as newdiscounttitle');

                /**************************************************************************************/
                /*** Calculate ECommision */
                /**************************************************************************************/
                //Extra Fields of RateMobile
                $commission_value = 0;

                $extrafields = $this->Field_model->get('ratemobile',$assignmentproduct['newratenr']);
                foreach($extrafields as $fkey=>$extrafield){
                    foreach($extrafield as $fkey2=>$fvalue2){
                        $extrafields[$fkey][trim($fkey2)] = trim($fvalue2);
                    }
                }

                //There we choose from where we Select “Mobile Rate 2” in each added Product in Assignment.
                if(strtolower(trim($assignmentproduct['vvlneu']))=='neu' && strtolower(trim($assignmentproduct['subname']))=='nein'){
                    //Commision = Value of PV190000SO
                    $array_column = array_column($extrafields, 'field_name');
                    $fkey = array_search('PV'.$assignment['newdiscounttitle'].'SO', $array_column);
                    $commission_value = $extrafields[$fkey]['field_value'];
                }
                else if(strtolower(trim($assignmentproduct['vvlneu']))=='neu' && strtolower(trim($assignmentproduct['subname']))=='ja'){
                    //Commision = Value of PV190000SUB
                    $array_column = array_column($extrafields, 'field_name');
                    $fkey = array_search('PV'.$assignment['newdiscounttitle'].'SUB', $array_column);
                    $commission_value = $extrafields[$fkey]['field_value'];
                }
                else if(strtolower(trim($assignmentproduct['vvlneu']))=='vvl' && strtolower(trim($assignmentproduct['subname']))=='nein'){
                    //Commision = Value of PV190000SO
                    $array_column = array_column($extrafields, 'field_name');
                    $fkey = array_search('PV'.$assignment['newdiscounttitle'].'SO', $array_column);
                    $commission_value = $extrafields[$fkey]['field_value'];
                }
                else if(strtolower(trim($assignmentproduct['vvlneu']))=='vvl' && strtolower(trim($assignmentproduct['subname']))=='ja'){
                    //Commision = Value of PV190000VVL
                    $array_column = array_column($extrafields, 'field_name');
                    $fkey = array_search('PV'.$assignment['newdiscounttitle'].'VVL', $array_column);
                    $commission_value = $extrafields[$fkey]['field_value'];
                }
                /**************************************************************************************/

                /*If VVL/Neu=Neu
                Ecommision = Read Out Value / 10
                If VVL/Neu=VVL
                Ecommision = (Read Out Value / 10) / 2 */

                $ecommision = 0;

                if(strtolower(trim($assignmentproduct['vvlneu']))=='neu'){
                    $ecommision = $commission_value/10;
                }
                else if(strtolower(trim($assignmentproduct['vvlneu']))=='vvl'){
                    $ecommision = ($commission_value/10) / 2;
                }

                //Issue4 I forgot a small thing. 4 lines.
                /*!!!!!!!!!!!!!!!!!!!!!!!
                If “Hardware” is choosen in Position
                Ecommision = 10
                !!!!!!!!!!!!!!!!!!!!!!!*/
                if(isset($assignmentproduct['hardware']) && $assignmentproduct['hardware']>0){
                    $ecommision = 10;
                }

                //Ecommision for Responsible
                if($data['responsible']!=""){
                    $em = $this->Employeecommission_model->get('','',array()," userid='".$data['responsible']."' AND productpositionid='".$assignmentproductid."' ");
                    if(isset($em) && count($em)>0){
                        $data_ecommission = array(
                            'userid' => $data['responsible'],
                            'posid' => 0,
                            'productpositionid' => $assignmentproductid,
                            'ecommision' => $ecommision,
                            'withdraw' => 0
                        );
                        $this->Employeecommission_model->update($data_ecommission, " userid='".$data['responsible']."' AND productpositionid='".$assignmentproductid."' ");
                    }
                    else{
                        $data_ecommission = array(
                            'userid' => $data['responsible'],
                            'posid' => 0,
                            'date' => date('Y-m-d'),
                            'productpositionid' => $assignmentproductid,
                            'ecommision' => $ecommision,
                            'withdraw' => 0
                        );
                        $this->Employeecommission_model->add($data_ecommission);
                    }
                }

                //Ecommision for Pos
                if($data['recommend']!=""){
                    $em = $this->Employeecommission_model->get('','',array()," posid='".$data['recommend']."' AND productpositionid='".$assignmentproductid."' ");
                    if(isset($em) && count($em)>0){
                        $data_ecommission = array(
                            'userid' => 0,
                            'posid' => $data['recommend'],
                            'productpositionid' => $assignmentproductid,
                            'ecommision' => $ecommision,
                            'withdraw' => 0
                        );
                        $this->Employeecommission_model->update($data_ecommission, " posid='".$data['recommend']."' AND productpositionid='".$assignmentproductid."' ");
                    }
                    else{
                        $data_ecommission = array(
                            'userid' => 0,
                            'posid' => $data['recommend'],
                            'date' => date('Y-m-d'),
                            'productpositionid' => $assignmentproductid,
                            'ecommision' => $ecommision,
                            'withdraw' => 0
                        );
                        $this->Employeecommission_model->add($data_ecommission);
                    }
                }
            }
        }
    }

    function saveHardwareAssignment($assignmentnr, $data, $updateProductIDs=array()){

        if(count($updateProductIDs)<=0){
            return;
        }

        //Get Assignment Products
        $is_Hardware = false;
        /*$assignmentproducts = (array) $this->Assignmentproduct_model->get("",'tblassignmentproducts.*, tblvvlneu.name as vvlneu, '
                . " newratemobile.ratenr as newratenr, "
                . " tblhardwares.hardwareprice, "
                . " tblhardwares.hardwarenr as hardwarenr, "
                . ' tblsubs.name as subname',
                array('tblvvlneu'=>'tblvvlneu.id=tblassignmentproducts.vvlneu',
                    'tblratesmobile as newratemobile'=>'newratemobile.ratenr=tblassignmentproducts.newratemobile',
                    'tblhardwares'=>'tblhardwares.hardwarenr=tblassignmentproducts.hardware',
                    'tblsubs'=>'tblsubs.id=newratemobile.subn',
                ),
                " assignmentnr='".$assignmentnr."' "
        );*/
        $assignmentproducts = (array) $this->Assignmentproduct_model->get("",'tblassignmentproducts.*, tblvvlneu.name as vvlneu, '
                . " newratemobile.ratenr as newratenr, "
                . " tblhardwares.hardwareprice, "
                . " tblhardwares.hardwarenr as hardwarenr, "
                . ' tblsubs.name as subname',
                array('tblvvlneu'=>'tblvvlneu.id=tblassignmentproducts.vvlneu',
                    'tblratesmobile as newratemobile'=>'newratemobile.ratenr=tblassignmentproducts.newratemobile',
                    'tblhardwares'=>'tblhardwares.hardwarenr=tblassignmentproducts.hardware',
                    'tblsubs'=>'tblsubs.id=newratemobile.subn',
                ),
                " tblassignmentproducts.id IN('".implode("','",$updateProductIDs)."')  "
        );

        //Check New Hardware Assignment
        if(isset($assignmentproducts) && count($assignmentproducts)>0){
            foreach($assignmentproducts as $assignmentproduct){
                $assignmentproductid = $assignmentproduct['id'];
                if(isset($assignmentproductid) && $assignmentproductid>0){
                    //if($assignmentproduct['hardwarenr']!="" && $assignmentproduct['hardwarecheck']==0){
                    if($assignmentproduct['hardwarenr']!=""){
                        $is_Hardware = true;
                    }
                }
            }
        }

        if(isset($assignmentnr) && $assignmentnr>0 && $is_Hardware){

            //Check Hardware Assignment Exists
            //$rowAssignment = (array) $this->Hardwareassignment_model->get('','hardwareassignmentnr',array()," assignmentnr='".$assignmentnr."' ");
            //if(!$rowAssignment){
                $data_hardwareassignment = array(
                    'assignmentnr' => $assignmentnr,
                    'company' => $data['company'],
                    'customer' => $data['customer'],
                    'responsible' => $data['responsible'],
                    'provider' => $data['provider'],
                    'hardwareassignmentstatus' => 1, //Offen
                );
                $hardwareassignmentnr = $this->Hardwareassignment_model->add($data_hardwareassignment);
            /*}
            else{
                $data_hardwareassignment = array(
                    'company' => $data['company'],
                    'customer' => $data['customer'],
                    'responsible' => $data['responsible'],
                );
                $hardwareassignmentnr = $this->Hardwareassignment_model->update($data_hardwareassignment, $rowAssignment[0]['hardwareassignmentnr']);
            }*/

            /*echo '<br /><br />';
            echo rand(0,100)."-".$hardwareassignmentnr;
            echo '<br /><br />';*/

            //Get Discount Title
            $discountLevel = (array) $this->Discountlevel_model->get($data['newdiscountlevel'], 'discounttitle as newdiscounttitle');


            if(isset($hardwareassignmentnr) && $hardwareassignmentnr>0){
                //History
                $Action_data = array('actionname'=>'hardwareassignment', 'actionid'=>$hardwareassignmentnr, 'actiontitle'=>'hardwareassignment_added');
                do_action_history($Action_data);
            }

            if(isset($assignmentproducts) && count($assignmentproducts)>0 && isset($hardwareassignmentnr) && $hardwareassignmentnr>0){

                foreach($assignmentproducts as $assignmentproduct){
                    $assignmentproductid = $assignmentproduct['id'];

                    if(isset($assignmentproductid) && $assignmentproductid>0){
                        //if((isset($old_product_data->finished) && $old_product_data->finished!=$finished && $finished==1)){

                            /**************************************************************************************/
                            /*** Calculate ECommision */
                            /**************************************************************************************/
                            //if($assignmentproduct['hardwarenr']!="" && $assignmentproduct['hardwarecheck']==0){
                            if($assignmentproduct['hardwarenr']!=""){
                                //Extra Fields of RateMobile
                                $hardware_calculate_value = 0;
                                $commission_value = 0;

                                $extrafields = $this->Field_model->get('ratemobile',$assignmentproduct['newratenr']);
                                foreach($extrafields as $fkey=>$extrafield){
                                    foreach($extrafield as $fkey2=>$fvalue2){
                                        $extrafields[$fkey][trim($fkey2)] = trim($fvalue2);
                                    }
                                }

                                //There we choose from where we Select “Mobile Rate 2” in each added Product in Assignment.
                                if(strtolower(trim($assignmentproduct['vvlneu']))=='neu' && strtolower(trim($assignmentproduct['subname']))=='nein'){
                                    //Commision = Value of PV190000SO
                                    $array_column = array_column($extrafields, 'field_name');
                                    $fkey = array_search('PV'.$discountLevel['newdiscounttitle'].'SO', $array_column);
                                    $commission_value = $extrafields[$fkey]['field_value'];
                                }
                                else if(strtolower(trim($assignmentproduct['vvlneu']))=='vvl' && strtolower(trim($assignmentproduct['subname']))=='nein'){
                                    //Commision = Value of PV190000VVL
                                    $array_column = array_column($extrafields, 'field_name');
                                    $fkey = array_search('PV'.$discountLevel['newdiscounttitle'].'VVL', $array_column);
                                    $commission_value = $extrafields[$fkey]['field_value'];
                                }
                                else if(strtolower(trim($assignmentproduct['vvlneu']))=='vvl' && strtolower(trim($assignmentproduct['subname']))=='ja'){
                                    //Commision = Value of PV190000VVL
                                    $array_column = array_column($extrafields, 'field_name');
                                    $fkey = array_search('PV'.$discountLevel['newdiscounttitle'].'VVL', $array_column);
                                    $commission_value = $extrafields[$fkey]['field_value'];
                                }
                                else if(strtolower(trim($assignmentproduct['vvlneu']))=='neu' && strtolower(trim($assignmentproduct['subname']))=='ja'){
                                    //Commision = Value of PV190000SUB
                                    $array_column = array_column($extrafields, 'field_name');
                                    $fkey = array_search('PV'.$discountLevel['newdiscounttitle'].'SUB', $array_column);
                                    $commission_value = $extrafields[$fkey]['field_value'];
                                }
                                /**************************************************************************************/

                                /*Hardwarevalue will calculate in this way >>
                                if (Commision – Hardwareprice>=99)
                                Hardwarevalue = 1,00 €
                                if (Commision – Hardwareprice<99)
                                Hardwarevalue = (Commision-Hardwareprice) *(-1) + 99,00 €
                                */
                                if(($commission_value - $assignmentproduct['hardwareprice'])>=99){
                                    //Hardwarevalue = 1,00 €
                                    $hardware_calculate_value = 1;
                                }
                                else if(($commission_value - $assignmentproduct['hardwareprice'])<99){
                                    //Hardwarevalue = (Commision-Hardwareprice) *(-1) + 99,00 €
                                    $hardware_calculate_value = (($commission_value - $assignmentproduct['hardwareprice'])*(-1)) + 99;
                                }

                                //Hardware-Assignment
                                /*$em = $this->Hardwareassignmentproduct_model->get('','hardwareassignmentnr',array()," productpositionid='".$assignmentproductid."' AND hardware='".$assignmentproduct['hardware']."' ");
                                if(isset($em) && count($em)>0){
                                    $hardwareassignmentproductid = $em[0]['id'];
                                    $data_hardwareassignmentproduct = array(*/
                                        /*'productpositionid' => $assignmentproductid,*/
                                        //'hardwareassignmentnr' => $hardwareassignmentnr,
                                        /*'company' => $data['company'],
                                        'customer' => $data['customer'],
                                        'responsible' => $data['responsible'],
                                        'hardwareassignmentstatus' => 1, //Offen*/
                                        /*'simnr' => $assignmentproduct['simnr'],
                                        'mobilenr' => $assignmentproduct['mobilenr'],
                                        'newratemobile' => $assignmentproduct['newratemobile'],
                                        'hardware' => $assignmentproduct['hardware'],
                                        'hardwarevalue' => $hardware_calculate_value
                                    );
                                    $this->Hardwareassignmentproduct_model->update($data_hardwareassignmentproduct, $hardwareassignmentproductid);
                                }
                                else{*/
                                    $data_hardwareassignmentproduct = array(
                                        'productpositionid' => $assignmentproductid,
                                        'hardwareassignmentnr' => $hardwareassignmentnr,
                                        /*'company' => $data['company'],
                                        'customer' => $data['customer'],
                                        'responsible' => $data['responsible'],
                                        'hardwareassignmentstatus' => 1, //Offen*/
                                        'simnr' => $assignmentproduct['simnr'],
                                        'mobilenr' => $assignmentproduct['mobilenr'],
                                        'newratemobile' => $assignmentproduct['newratemobile'],
                                        'hardware' => $assignmentproduct['hardware'],
                                        'hardwarevalue' => $hardware_calculate_value
                                    );

                                    //print_r($data_hardwareassignmentproduct);exit;

                                    $hardwareassignmentproductid = $this->Hardwareassignmentproduct_model->add($data_hardwareassignmentproduct);
                                //}

                                //During Saving Assignment Hardwarecheck should set=1 in the Positions where Hardware was choosen through Selectbox.
                                if($hardwareassignmentproductid!=""){
                                    $data_assignmentproduct = array('hardwarecheck'=>1);
                                    $this->Assignmentproduct_model->update($data_assignmentproduct, $assignmentproductid);
                                }
                            }
                        //}
                    }
                }
            }
        }
    }

    function saveQualitycheck($assignmentnr, $data){
        //Date of generating Assignmentdate+3 Days
        $qualitycheckstart = date('Y-m-d', strtotime('+3 days', strtotime($data['assignmentdate'])));
        if(isset($assignmentnr) && $assignmentnr>0){
            //Quality Check
            $em = $this->Qualitycheck_model->get('','qualitychecknr',array()," rel_id='".$assignmentnr."' ");
            if(isset($em) && count($em)>0){
                $qualitychecknr = $em[0]['qualitychecknr'];
                $data_qualitycheck = array(
                    'qualityissue' => 'Auftragcheck',
                    'rel_id' => $assignmentnr,
                    'rel_type' => 'assignment',
                    'qualitycheckstart' => $qualitycheckstart,
                    'company' => $data['company'],
                    'responsible' => $data['responsible'],
                    'qualitycheckstatus' => 1, //Often
                    'question1' => 1,
                    'question2' => 2
                );
                //$this->Qualitycheck_model->update($data_qualitycheck, $qualitychecknr);
            }
            else{
                $data_qualitycheck = array(
                    'qualityissue' => 'Auftragcheck',
                    'rel_id' => $assignmentnr,
                    'rel_type' => 'assignment',
                    'qualitycheckstart' => $qualitycheckstart,
                    'company' => $data['company'],
                    'responsible' => $data['responsible'],
                    'qualitycheckstatus' => 1, //Often
                    'question1' => 1,
                    'question2' => 2
                );
                $qualitychecknr = $this->Qualitycheck_model->add($data_qualitycheck,'qualitycheck');
            }
        }
    }

    function saveProductMoreOptionMobile($id, $fk, $assignmentproductid, $data){
        //Add Assignmentproduct More Option Mobile
        if($GLOBALS['a_moreoptionmobile_permission']['create']){
            $this->Assignmentproductmoreoptionmobile_model->delete("","assignmentnr='".$id."' AND assignmentproductid='".$assignmentproductid."'");
        }
        if(isset($data['more_newoptionmobile'][$fk])){
            foreach($data['more_newoptionmobile'][$fk] as $ofk=>$ofd){
                $more_newoptionmobile = isset($data['more_newoptionmobile'][$fk][$ofk])?$data['more_newoptionmobile'][$fk][$ofk]:'';
                $more_value4 = isset($data['more_value4'][$fk][$ofk])?$data['more_value4'][$fk][$ofk]:'';

                if($more_newoptionmobile>0){
                    $dataMoreOptionMobile = array(
                        'assignmentnr'=> $id,
                        'assignmentproductid' => $assignmentproductid,
                        'newoptionmobile' => $more_newoptionmobile,
                        'value4' => $more_value4
                    );
                    $this->Assignmentproductmoreoptionmobile_model->add($dataMoreOptionMobile);
                }
            }
        }
    }

    function generateMonitoring($id){
        if(isset($id) and $id>0){
            $dataCustomers = $this->Customer_model->get('','tblcustomers.*, tblassignments.assignmentnr',array('tblassignments'=>'tblassignments.customer=tblcustomers.customernr')," tblcustomers.monitoring=1 AND assignmentnr='".$id."' ");
            if(isset($dataCustomers) && count($dataCustomers)>0){
                foreach($dataCustomers as $dataCustomer){
                    $dataMonitoring = $this->Monitoring_model->get("","",array()," assignmentnr='".$dataCustomer['assignmentnr']."' ");
                    if(!$dataMonitoring){
                        $post = array(
                            'customer' => $dataCustomer['customernr'],
                            'responsible' => $dataCustomer['responsible'],
                            'date' => date("Y-m-d"),
                            'monitoringstatus' => 1, //Erstellt
                            'company' => $dataCustomer['company'],
                            'monitoringlink' => $dataCustomer['monitoringlink'],
                            'monitoringuser' => $dataCustomer['monitoringuser'],
                            'monitoringpass' => $dataCustomer['monitoringpass'],
                            'monitoringvalue' => $dataCustomer['monitoringvalue'],
                            'assignmentnr' => $dataCustomer['assignmentnr']
                        );
                        $this->Monitoring_model->add($post);
                    }
                }
            }
        }
    }

    /**
     * Delete assignment
     * @param  array $data assignment
     * @param  mixed $id   assignment id
     * @return boolean
     */
    public function delete($id)
    {
        //Get Assignmentnr
        $rowfield = $this->get($id,'assignmentnr_prefix');
        $this->db->where($this->aid, $id);
        $this->db->delete($this->table);

        //Log Activity
        logActivity('Assignment Deleted [ID: ' . $id . ', ' . $rowfield->assignmentnr_prefix . ']');

        //Delete Assignment Products
        $assignmentproducts = $this->Assignmentproduct_model->get('', 'id', array(), " assignmentnr='".$id."' ");
        if(isset($assignmentproducts) && count($assignmentproducts)>0){
            foreach($assignmentproducts as $assignmentproduct){
               $this->Assignmentproduct_model->delete($assignmentproduct['id']);
            }
        }

        //Delete Legitimation
        $legitimations = $this->get_assignment_legitimations($id);
        if(isset($legitimations) && count($legitimations)>0){
            foreach($legitimations as $legitimation){
                $this->delete_assignment_legitimation($legitimation['id']);
            }
        }

        //Delete Document
        $documents = $this->get_assignment_attachments($id);
        if(isset($documents) && count($documents)>0){
            foreach($documents as $document){
                $this->delete_assignment_attachment($document['id']);
            }
        }

        //Delete Reminder
        $reminders = $this->Assignmentreminder_model->get('', '', array(), " rel_id='".$id."' AND rel_type='assignment' ");
        if(isset($reminders) && count($reminders)>0){
            foreach($reminders as $reminder){
               $this->Assignmentreminder_model->delete($reminder['remindernr']);
            }
        }

        return 1;
    }

    /**
     * Get assignment attachments
     * @since Version 1.0.4
     * @param  mixed $id assignment id
     * @return array
     */
    public function get_assignment_attachments($id = '', $attachment_id = '')
    {
        if (is_numeric($attachment_id)) {
            $this->db->where('id', $attachment_id);

            return $this->db->get('tblfiles')->row();
        }
        $this->db->where('rel_id', $id);
        $this->db->where('rel_type', 'assignment');
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
        $data['rel_type'] = 'assignment';
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
            $assignment = $this->get($rel_id,'assignmentnr_prefix');
            logActivity('Assignment Attachment Added [AssignmentID: ' . $rel_id . ', '.$assignment->assignmentnr_prefix.']');

            //History
            $Action_data = array('actionname'=>'assignment', 'actionid'=>$rel_id, 'actionsubid'=>$insert_id, 'actiontitle'=>'assignment_document_added');
            do_action_history($Action_data);
        }

        return $insert_id;
    }

    /**
     * Delete assignment attachment
     * @param  mixed $id attachment id
     * @return boolean
     */
    public function delete_assignment_attachment($id)
    {
        $attachment = $this->get_assignment_attachments('', $id);
        $assignment = $this->get($attachment->rel_id,'assignmentnr_prefix');
        $deleted    = false;

        if ($attachment) {
            if (empty($attachment->external)) {
                unlink(get_upload_path_by_type('assignment') . $attachment->rel_id . '/' . $attachment->file_name);
            }
            $this->db->where('id', $attachment->id);
            $this->db->delete('tblfiles');
            if ($this->db->affected_rows() > 0) {
                $deleted = true;
                logActivity('Assignment Attachment Deleted [AssignmentID: ' . $attachment->rel_id . ', '.$assignment->assignmentnr_prefix.']');

                //History
                $Action_data = array('actionname'=>'assignment', 'actionid'=>$attachment->rel_id, 'actionsubid'=>$attachment->id, 'actiontitle'=>'assignment_document_deleted');
                do_action_history($Action_data);
            }

            if (is_dir(get_upload_path_by_type('assignment') . $attachment->rel_id)) {
                // Check if no attachments left, so we can delete the folder also
                $other_attachments = list_files(get_upload_path_by_type('assignment') . $attachment->rel_id);
                if (count($other_attachments) == 0) {
                    // okey only index.html so we can delete the folder also
                    delete_dir(get_upload_path_by_type('assignment') . $attachment->rel_id);
                }
            }
        }

        return $deleted;
    }

    /* Update attachment category */
    public function update_assignment_attachmentcategory($rel_id, $categoryid){
        $data = array('categoryid'=>$categoryid);
        $this->db->update('tblfiles', $data, array('rel_id' => $rel_id, 'rel_type'=>'assignment'));
        if ($this->db->affected_rows() > 0) {
            $assignment = $this->get($rel_id,'assignmentnr_prefix');
            logActivity('Assignment Attachment Updated [AssignmentID: ' . $rel_id . ', '.$assignment->assignmentnr_prefix.']');
            return 1;
        }
    }



    /**
    * Get assignment legitimations
    * @since Version 1.0.4
    * @param  mixed $id legitimation id
    * @return array
    */
    public function get_assignment_legitimations($id = '', $legitimation_id = '')
    {
           if (is_numeric($legitimation_id)) {
                   $this->db->where('id', $legitimation_id);

                   return $this->db->get('tblfiles')->row();
           }
           $this->db->where('rel_id', $id);
           $this->db->where('rel_type', 'legitimation');
           $this->db->order_by('created', 'DESC');

           return $this->db->get('tblfiles')->result_array();
    }

    //Add Legitimation
    public function add_legitimation_to_database($rel_id, $legitimation, $external = false, $form_activity = false)
    {
           if($this->input->post()){
                   $post = $this->input->post();
           }

           $data['created'] = date('Y-m-d H:i:s');
           $data['rel_id'] = $rel_id;
           $data['userid'] = get_user_id();
           $data['rel_type'] = 'legitimation';
           $data['attachment_key'] = app_generate_hash();

           if ($external == false) {
                   $data['file_name'] = $legitimation[0]['file_name'];
                   $data['filetype']  = $legitimation[0]['filetype'];
           } else {
                   $path_parts            = pathinfo($legitimation[0]['name']);
                   $data['file_name']     = $legitimation[0]['name'];
                   $data['external_link'] = $legitimation[0]['link'];
                   $data['filetype']      = get_mime_by_extension('.' . $path_parts['extension']);
                   $data['external']      = $external;
                   if (isset($legitimation[0]['thumbnailLink'])) {
                           $data['thumbnail_link'] = $legitimation[0]['thumbnailLink'];
                   }
           }

           $db = $this->db;
           $db->insert('tblfiles', $data);
           $insert_id = $db->insert_id();

           if($insert_id>0){
                $assignment = $this->get($rel_id,'assignmentnr_prefix');
                logActivity('Assignment Legitimation Added [AssignmentID: ' . $rel_id . ', '.$assignment->assignmentnr_prefix.']');

                //History
                $Action_data = array('actionname'=>'assignment', 'actionid'=>$rel_id, 'actionsubid'=>$insert_id, 'actiontitle'=>'assignment_legitimation_added');
                do_action_history($Action_data);
           }

           return $insert_id;
    }

    /**
    * Delete assignment legitimation
    * @param  mixed $id legitimation id
    * @return boolean
    */
    public function delete_assignment_legitimation($id)
    {
           $legitimation = $this->get_assignment_legitimations('', $id);
           $assignment = $this->get($legitimation->rel_id,'assignmentnr_prefix');
           $deleted    = false;

           if ($legitimation) {
                   if (empty($legitimation->external)) {
                           unlink(get_upload_path_by_type('legitimation') . $legitimation->rel_id . '/' . $legitimation->file_name);
                   }
                   $this->db->where('id', $legitimation->id);
                   $this->db->delete('tblfiles');
                   if ($this->db->affected_rows() > 0) {
                           $deleted = true;
                           logActivity('Assignment Legitimation Deleted [AssignmentID: ' . $legitimation->rel_id . ', '.$assignment->assignmentnr_prefix.']');

                        //History
                        $Action_data = array('actionname'=>'assignment', 'actionid'=>$legitimation->rel_id, 'actionsubid'=>$legitimation->id, 'actiontitle'=>'assignment_legitimation_deleted');
                        do_action_history($Action_data);
                   }

                   if (is_dir(get_upload_path_by_type('legitimation') . $legitimation->rel_id)) {
                           // Check if no legitimations left, so we can delete the folder also
                           $other_legitimations = list_files(get_upload_path_by_type('legitimation') . $legitimation->rel_id);
                           if (count($other_legitimations) == 0) {
                                   // okey only index.html so we can delete the folder also
                                   delete_dir(get_upload_path_by_type('legitimation') . $legitimation->rel_id);
                           }
                   }
           }

           return $deleted;
    }


    //Generate Reminder by Cronjob (Notification Email 1 Day before he set the date)
    public function sendReminder($reminderid='', $submit_type=''){
        if($submit_type=='single'){
            //Reminder Assignment
            $data = (array) $this->Assignmentreminder_model->get($reminderid,'tblassignmentreminders.remindernr, tblremindersubjects.name as remindersubject, '
                . 'tblsalutations.name as salutation, '
                . 'responsible.email, '
                . 'responsible.name, '
                . 'responsible.surname, '
                . 'tblassignments.assignmentnr, '
                . 'tblassignments.assignmentnr_prefix, '
                . 'tblassignments.company, '
                . 'tblassignmentreminders.notice,'
                . 'tblassignmentreminders.reminddate ',
                array('tblremindersubjects'=>'tblremindersubjects.id=tblassignmentreminders.remindersubject',
                'tblassignments'=>'tblassignments.assignmentnr=tblassignmentreminders.rel_id',
                'tblusers as responsible'=>'responsible.userid=tblassignments.responsible',
                'tblsalutations'=>'tblsalutations.salutationid=responsible.salutation'
                ),
                "tblassignmentreminders.rel_type='assignment'"
            );
        }
        else{
            //Reminder Assignment
            //// $data = (array) $this->Assignmentreminder_model->get($reminderid,'tblassignmentreminders.remindernr, tblremindersubjects.name as remindersubject, '
            ////     . 'tblsalutations.name as salutation, '
            ////     . 'responsible.email, '
            ////     . 'responsible.name, '
            ////     . 'responsible.surname, '
            ////     . 'tblassignments.assignmentnr, '
            ////     . 'tblassignments.assignmentnr_prefix, '
            ////     . 'tblassignments.company, '
            ////     . 'tblassignmentreminders.notice,'
            ////     . 'tblassignmentreminders.reminddate ',
            ////     array('tblremindersubjects'=>'tblremindersubjects.id=tblassignmentreminders.remindersubject',
            ////     'tblassignments'=>'tblassignments.assignmentnr=tblassignmentreminders.rel_id',
            ////     'tblusers as responsible'=>'responsible.userid=tblassignments.responsible',
            ////     'tblsalutations'=>'tblsalutations.salutationid=responsible.salutation'
            ////     ),
            ////     "tblassignmentreminders.rel_type='assignment' AND tblassignmentreminders.reminderway=0 AND (tblassignmentreminders.email_sent=0 OR ISNULL(tblassignmentreminders.email_sent) OR tblassignmentreminders.email_sent='') AND tblassignmentreminders.reminddate<='".date('Y-m-d H:i:s',strtotime($GLOBALS['current_datetime']."1 day 30 minutes"))."' "
            //// );

            $data = (array) $this->Assignmentreminder_model->get($reminderid,
                "tblassignmentreminders.remindernr, "
                . "tblremindersubjects.name as remindersubject, "
                . "tblsalutations.name as salutation, "
                . "responsible.email, "
                . "responsible.name, "
                . "responsible.surname, "
                . "tblassignments.assignmentnr, "
                . "tblassignments.assignmentnr_prefix, "
                . "tblassignments.company, "
                . "tblassignmentreminders.notice,"
                . "tblassignmentreminders.reminddate, "
                . "tblcustomers.customernr, "
                . "tblcustomers.customernr_prefix, "
                . " (SELECT IF( (tblcustomers.company IS NULL) , '(NO COMPANY)', tblcustomers.company ) ) AS company ",

                array('tblremindersubjects'=>'tblremindersubjects.id=tblassignmentreminders.remindersubject',
                    'tblassignments'=>'tblassignments.assignmentnr=tblassignmentreminders.rel_id',
                    'tblusers as responsible'=>'responsible.userid=tblassignments.responsible',
                    'tblsalutations'=>'tblsalutations.salutationid=responsible.salutation',
                    'tblcustomers'=>'tblcustomers.customernr=tblassignmentreminders.rel_id'
                ),
                "tblassignmentreminders.rel_type='assignment' AND tblassignmentreminders.reminderway=0 AND (tblassignmentreminders.email_sent=0 OR ISNULL(tblassignmentreminders.email_sent) OR tblassignmentreminders.email_sent='') AND tblassignmentreminders.reminddate<='".date('Y-m-d H:i:s',strtotime($GLOBALS['current_datetime']."1 day 30 minutes"))."' "
            );
        }

        // print_r($this->db->last_query());
        // echo '<pre>';
        // print_r($data);
        // exit(0);

        if(isset($data['assignmentnr'])){
            return $this->sendMail($data);
        }else{
            //Loop
            $data1 = $data;
            if(isset($data1) && count($data1)>0){
                foreach($data1 as $data){
                    $this->sendMail($data);
                }
            }
        }
    }

    function sendMail($data){
        $data['linktoreminder'] = '<a href="'.base_url('admin/assignments/detail/'. $data['assignmentnr']).'" target="_blank">'.lang('click_here').'</a>';
        $merge_fields = array();
        $merge_fields = array_merge($merge_fields, get_assignmentreminder_merge_fields($data));

        $sent = $this->Email_model->send_email_template('assignment-reminder', $data['email'], $merge_fields);
        if ($sent) {
            // Set to status sent
            $post = array('email_sent'=>1);
            $this->Assignmentreminder_model->update($post,$data['remindernr']);
            do_action('assignmentreminder_sent', $data['remindernr']);
            return 1;
        }
        else{
            return 0;
        }
    }


    //Generate Reminder by Cronjob of assignment
    //When Between Datetoday and saved Date is not more than 3 Month the row “Finished”
    public function sendReminder_assignment($assignmentnr='', $submit_type=''){
        if($submit_type=='single'){
            //Reminder Assignment
            $data = (array) $this->Assignment_model->get($assignmentnr,' '
                . 'tblsalutations.name as salutation, '
                . 'responsible.email, '
                . 'responsible.name, '
                . 'responsible.surname, '
                . 'tblassignments.assignmentnr, '
                . 'tblassignments.assignmentnr_prefix, '
                . 'tblassignments.company,'
                . 'tblassignments.customer,'
                . 'tblassignments.assignmentdate,'
                . 'tblassignments.responsible ',

                array('tblusers as responsible'=>'responsible.userid=tblassignments.responsible',
                'tblsalutations'=>'tblsalutations.salutationid=responsible.salutation'
                )
            );
        }
        else{
            //Reminder Assignment
            $data = (array) $this->Assignment_model->get($assignmentnr,' '
                . 'tblsalutations.name as salutation, '
                . 'responsible.email, '
                . 'responsible.name, '
                . 'responsible.surname, '
                . 'tblassignments.assignmentnr, '
                . 'tblassignments.assignmentnr_prefix, '
                . 'tblassignments.company,'
                . 'tblassignments.customer,'
                . 'tblassignments.assignmentdate, '
                . 'tblassignments.responsible '

                . " , (SELECT IF( (tblcustomers.company IS NULL) , '(NO COMPANY)', tblcustomers.company ) ) AS company ",

                array('tblusers as responsible'=>'responsible.userid=tblassignments.responsible',
                'tblsalutations'=>'tblsalutations.salutationid=responsible.salutation',

                    'tblcustomers'=>'tblcustomers.customernr=tblassignments.customer',
                ),

                " tblassignments.assignmentnr IN(SELECT assignmentnr FROM tblassignmentproducts WHERE tblassignmentproducts.provicheck=1 AND "
                . " (datediff(tblassignmentproducts.endofcontract,NOW())>=1 AND datediff(tblassignmentproducts.endofcontract,NOW())<=90)) "

            );
        }

        // print_r($this->db->last_query());
        // echo '<pre>';
        // print_r($data);
        // exit(0);

        if(isset($data['assignmentnr'])){
            return $this->sendMail_assignment($data);
        }else{
            //Loop
            $data1 = $data;
            if(isset($data1) && count($data1)>0){
                foreach($data1 as $data){
                    $this->sendMail_assignment($data);
                }
            }
        }
    }

    function sendMail_assignment($data){
        $data['linktoassignment'] = '<a href="'.base_url('admin/assignments/detail/'. $data['assignmentnr']).'" target="_blank">'.lang('click_here').'</a>';


        //Get Assignment Products
        $dataProducts = (array) $this->Assignmentproduct_model->get('','tblassignmentproducts.id, '
            . 'tblassignmentproducts.simnr, '
            . 'tblassignmentproducts.mobilenr,'
            . 'newratemobile.ratetitle as newratemobile ',
            array('tblratesmobile as newratemobile'=>'newratemobile.ratenr=tblassignmentproducts.newratemobile'),

            " tblassignmentproducts.assignmentnr='".$data['assignmentnr']."' AND tblassignmentproducts.provicheck=1 AND "
            . " (datediff(tblassignmentproducts.endofcontract,NOW())>=1 AND datediff(tblassignmentproducts.endofcontract,NOW())<=90) "
        );
        $data['assignmentproducts'] = '';
        if(isset($dataProducts) && count($dataProducts)>0){
            $data['assignmentproducts'].='<table>';
            $data['assignmentproducts'].='<tr><th align="left">'.lang('page_fl_simnr').'</th><th align="left">'.lang('page_fl_mobilenr').'</th><th align="left">'.lang('page_fl_ratetitle').'</th></tr>';

            $iRow = 1;
            foreach($dataProducts as $dataProduct){
                $rowcolor = '';
                if($iRow%2==0){
                   $rowcolor = '#DDE3EC';
                }
                $data['assignmentproducts'].="<tr style='background-color:".$rowcolor."'>";
                    $data['assignmentproducts'].='<td>'.$dataProduct['simnr'].'</td><td>'.$dataProduct['mobilenr'].'</td><td>'.$dataProduct['newratemobile'].'</td>';
                $data['assignmentproducts'].="</tr>";
                $iRow++;
            }
            $data['assignmentproducts'].='</table>';
        }

        $merge_fields = array();
        $merge_fields = array_merge($merge_fields, get_assignmentreminder_assignment_merge_fields($data));

        $sent = $this->Email_model->send_email_template('assignment-reminder2', $data['email'], $merge_fields);
        if ($sent) {

            // Set Provicheck
            $tododesc = '';
            if(isset($dataProducts) && count($dataProducts)>0){
                foreach($dataProducts as $dataProduct){
                    $tododesc .= $dataProduct['simnr'].",".$dataProduct['mobilenr'].",".$dataProduct['newratemobile']."\n";
                    $post = array('provicheck'=>0);
                    $this->Assignmentproduct_model->update($post,$dataProduct['id']);
                }
            }

            //Todo Generate
            $dataTodo = array(
                // 'todotitle' => lang('page_lb_task').": ".$data['assignmentnr']." ".$data['company'],
                'todotitle' => $data['assignmentnr']." ".$data['company'],
                'responsible' => $data['responsible'],
                /*'company' => $data['company'],*/
                'customer' => $data['customer'],
                'todostatus' => 1, //Erstellt
                'tododesc' => $tododesc
            );
            $this->Todo_model->add($dataTodo);

            do_action('assignmentreminderposition_sent', $data['assignmentnr']);

            return 1;
        }
        else{
            return 0;
        }
    }


    //Get Value for Mobile Rate 1 or Mobile Rate 2 Auto Calculation
    public function getMobileRateValue($id, $discountlevel, $formula){
        if($id!="" && $id!="none"){
            $mobilerate = $this->Ratemobile_model->get($id,'price, ultracard');
            $discountlevel = $this->Discountlevel_model->get($discountlevel,'discountvalue');

            //A is for Auto and M is for Manual
            if($formula=='A'){
                if(isset($discountlevel->discountvalue)){
                    return round($mobilerate->price/(1+($discountlevel->discountvalue/100)),2).'[=]'.$mobilerate->ultracard;
                }
                else{
                    return $mobilerate->price.'[=]'.$mobilerate->ultracard;
                }
            }else{
                return $mobilerate->price.'[=]'.$mobilerate->ultracard;
            }
        }else{
            return '';
        }
    }

    //Get Value for Mobile Option 1 or Mobile Option 2 Price
    public function getMobileOptionValue($id){
        if($id!="" && $id!="none"){
            $mobileoption = $this->Optionmobile_model->get($id,'price');
            return $mobileoption->price;
        }else{
            echo '';
        }
    }

    //Generate Tickets
    public function generateTicket(){

        $assignmentId = $this->input->post('assignmentId');
        $ticketType = $this->input->post('ticketType');
        $emailSend = $this->input->post('emailSend');
        $assignmentProductId = $this->input->post('assignmentProductId');
        $mobileoption = @$this->input->post('mobileoption');
        $hardware = @$this->input->post('hardware');
        $card_month = @$this->input->post('card_month');
        $card_reason = @$this->input->post('card_reason');
        $cardbreak = @$this->input->post('cardbreak');
        $is_paused = @$this->input->post('is_paused');
        $ratemobile = @$this->input->post('ratemobile');
        $quantity = @$this->input->post('quantity');

        if($assignmentId!=""){

            //Get Assignment
            if($ticketType=='cardlock'
                    || $ticketType=='subscriptionlock'
                    || $ticketType=='subscriptionlock2'
                    || $ticketType=='optionbook'
                    || $ticketType=='hardwareorder'
                    || $ticketType=='ultracardorder'
                    || $ticketType=='cardpause'
                    || $ticketType=='cardpause2'){

                $data = (array) $this->Assignmentproduct_model->get($assignmentProductId,' '
                    . 'tblsalutations.name as salutation, '
                    . 'responsible.email, '
                    . 'responsible.name, '
                    . 'responsible.surname, '
                    . 'tblassignments.assignmentnr, '
                    . 'tblassignments.assignmentnr_prefix, '
                    . 'tblassignments.company as customer_company,'
                    . 'tblassignments.assignmentdate,'
                    . 'tblassignments.customer,'
                    . 'tblassignments.responsible,'
                    . 'tblassignmentproducts.simnr,'
                    . 'tblassignmentproducts.mobilenr ',

                    array('tblassignments' => 'tblassignments.assignmentnr=tblassignmentproducts.assignmentnr',
                    'tblusers as responsible'=>'responsible.userid=tblassignments.responsible',
                    'tblsalutations'=>'tblsalutations.salutationid=responsible.salutation',
                    )
                );
            }
            else if($ticketType=='repairorder'
                    || $ticketType=='rebuyorder'
                    || $ticketType=='bookinsurance'){

                $data = (array) $this->Hardwareassignmentproduct_model->get($assignmentProductId,' '
                    . 'tblsalutations.name as salutation, '
                    . 'responsible.email, '
                    . 'responsible.name, '
                    . 'responsible.surname, '
                    . 'tblassignments.assignmentnr, '
                    . 'tblassignments.assignmentnr_prefix, '
                    . 'tblassignments.company as customer_company,'
                    . 'tblassignments.assignmentdate,'
                    . 'tblassignments.customer,'
                    . 'tblassignments.responsible,'
                    . 'tblhardwareassignmentproducts.simnr,'
                    . 'tblhardwareassignmentproducts.mobilenr ',

                    array('tblhardwareassignments' => 'tblhardwareassignments.hardwareassignmentnr=tblhardwareassignmentproducts.hardwareassignmentnr',
                    'tblassignmentproducts'=>'tblassignmentproducts.id=tblhardwareassignmentproducts.productpositionid',
                    'tblassignments'=>'tblassignments.assignmentnr=tblassignmentproducts.assignmentnr',
                    'tblusers as responsible'=>'responsible.userid=tblassignments.responsible',
                    'tblsalutations'=>'tblsalutations.salutationid=responsible.salutation',
                    )
                );
            }
            else{
                $data = (array) $this->Assignment_model->get($assignmentId,' '
                    . 'tblsalutations.name as salutation, '
                    . 'responsible.email, '
                    . 'responsible.name, '
                    . 'responsible.surname, '
                    . 'tblassignments.assignmentnr, '
                    . 'tblassignments.assignmentnr_prefix, '
                    . 'tblassignments.company as customer_company,'
                    . 'tblassignments.assignmentdate,'
                    . 'tblassignments.customer,'
                    . 'tblassignments.responsible ',

                    array('tblusers as responsible'=>'responsible.userid=tblassignments.responsible',
                    'tblsalutations'=>'tblsalutations.salutationid=responsible.salutation'
                    )
                );
            }

            if(isset($data['assignmentnr'])){
                //Create Ticket
                if($ticketType=='subscriptionlock'){
                   $dataTicket = array(
                        'tickettitle' => lang('page_lb_'.$ticketType).': '.$data['assignmentnr_prefix'].' '.$data['customer_company'],
                        'ticketstatus' => 1, //Offen
                        'customer' => $data['customer'],
                        'responsible' => $data['responsible'],
                        'company' => $data['customer_company'],
                        'ticketdesc' => sprintf(lang('page_lb_'.$ticketType.'_ticketdesc'), $data['customer_company'], @$data['mobilenr'])
                    );
                }
                else if($ticketType=='subscriptionlock2'){
                   $dataTicket = array(
                        'tickettitle' => lang('page_lb_'.$ticketType).': '.$data['assignmentnr_prefix'].' '.$data['customer_company'],
                        'ticketstatus' => 1, //Offen
                        'customer' => $data['customer'],
                        'responsible' => $data['responsible'],
                        'company' => $data['customer_company'],
                        'ticketdesc' => sprintf(lang('page_lb_'.$ticketType.'_ticketdesc'), $data['customer_company'], @$data['mobilenr'])
                    );
                }
                else if($ticketType=='optionbook'){
                   $mobileoption = explode('=',$mobileoption);
                   $mobileoption = $mobileoption[1];

                   $dataTicket = array(
                        'tickettitle' => lang('page_lb_'.$ticketType).': '.$data['assignmentnr_prefix'].' '.$data['customer_company'],
                        'ticketstatus' => 1, //Offen
                        'customer' => $data['customer'],
                        'responsible' => $data['responsible'],
                        'company' => $data['customer_company'],
                        'ticketdesc' => sprintf(lang('page_lb_'.$ticketType.'_ticketdesc'), $data['customer_company'], @$mobileoption, @$data['mobilenr'])
                    );
                }
                else if($ticketType=='hardwareorder'){
                   $hardware = explode('=',$hardware);
                   $hardware = $hardware[1];

                   $dataTicket = array(
                        'tickettitle' => lang('page_lb_'.$ticketType).': '.$data['assignmentnr_prefix'].' '.$data['customer_company'],
                        'ticketstatus' => 1, //Offen
                        'customer' => $data['customer'],
                        'responsible' => $data['responsible'],
                        'company' => $data['customer_company'],
                        'ticketdesc' => sprintf(lang('page_lb_'.$ticketType.'_ticketdesc'), $data['customer_company'], @$hardware, @$data['mobilenr'])
                    );
                }
                else if($ticketType=='ultracardorder'){
                   $dataTicket = array(
                        'tickettitle' => lang('page_lb_'.$ticketType).': '.$data['assignmentnr_prefix'].' '.$data['customer_company'],
                        'ticketstatus' => 1, //Offen
                        'customer' => $data['customer'],
                        'responsible' => $data['responsible'],
                        'company' => $data['customer_company'],
                        'ticketdesc' => sprintf(lang('page_lb_'.$ticketType.'_ticketdesc'), $data['customer_company'], @$data['mobilenr'])
                    );
                }
                else if($ticketType=='cardpause'){
                   $dataTicket = array(
                        'tickettitle' => lang('page_lb_'.$ticketType).': '.$data['assignmentnr_prefix'].' '.$data['customer_company'],
                        'ticketstatus' => 1, //Offen
                        'customer' => $data['customer'],
                        'responsible' => $data['responsible'],
                        'company' => $data['customer_company'],
                        'ticketdesc' => sprintf(lang('page_lb_'.$ticketType.'_ticketdesc'), $data['customer_company'], @$card_month, @$data['mobilenr'], @$card_reason)
                    );

                    //Card Paused
                    if(isset($is_paused) && $is_paused==1){
                        $this->db->query("UPDATE tblassignmentproducts SET is_paused='1' WHERE id='".$assignmentProductId."'");
                    }

                    if(isset($cardbreak) && $cardbreak==1){
                        $this->db->query("UPDATE tblassignmentproducts SET cardbreak='1' WHERE id='".$assignmentProductId."'");

                        //When you click than on "Pause legen" the choosen Month must be added to Vertragsende
                        if(isset($card_month)){
                            $card_month = (int)$card_month;
                            $this->db->query("UPDATE tblassignmentproducts SET endofcontract=DATE_FORMAT(IF(endofcontract='0000-00-00','0000-00-00', DATE_ADD(endofcontract, INTERVAL ".$card_month." MONTH)),'%Y-%m-%d') WHERE id='".$assignmentProductId."'");
                        }
                    }
                }
                else if($ticketType=='cardpause2'){
                   $dataTicket = array(
                        'tickettitle' => lang('page_lb_'.$ticketType).': '.$data['assignmentnr_prefix'].' '.$data['customer_company'],
                        'ticketstatus' => 1, //Offen
                        'customer' => $data['customer'],
                        'responsible' => $data['responsible'],
                        'company' => $data['customer_company'],
                        'ticketdesc' => sprintf(lang('page_lb_'.$ticketType.'_ticketdesc'), $data['customer_company'], @$data['mobilenr'])
                    );
                    $this->db->query("UPDATE tblassignmentproducts SET is_paused='0' WHERE id='".$assignmentProductId."'");
                }
                else if($ticketType=='contractorder'){
                   $ratemobile = explode('=',$ratemobile);
                   $ratemobile = $ratemobile[1];

                   $dataTicket = array(
                        'tickettitle' => lang('page_lb_'.$ticketType).': '.$data['assignmentnr_prefix'].' '.$data['customer_company'],
                        'ticketstatus' => 1, //Offen
                        'customer' => $data['customer'],
                        'responsible' => $data['responsible'],
                        'company' => $data['customer_company'],
                        'ticketdesc' => sprintf(lang('page_lb_'.$ticketType.'_ticketdesc'), $data['customer_company'], $quantity, @$ratemobile, @$data['assignmentnr_prefix'])
                    );
                }
                else if($ticketType=='repairorder'){
                   $dataTicket = array(
                        'tickettitle' => lang('page_lb_'.$ticketType).': '.$data['assignmentnr_prefix'].' '.$data['customer_company'],
                        'ticketstatus' => 1, //Offen
                        'customer' => $data['customer'],
                        'responsible' => $data['responsible'],
                        'company' => $data['customer_company'],
                        'ticketdesc' => sprintf(lang('page_lb_'.$ticketType.'_ticketdesc'), $data['customer_company'], @$data['mobilenr'])
                    );
                }
                else if($ticketType=='rebuyorder'){
                   $dataTicket = array(
                        'tickettitle' => lang('page_lb_'.$ticketType).': '.$data['assignmentnr_prefix'].' '.$data['customer_company'],
                        'ticketstatus' => 1, //Offen
                        'customer' => $data['customer'],
                        'responsible' => $data['responsible'],
                        'company' => $data['customer_company'],
                        'ticketdesc' => sprintf(lang('page_lb_'.$ticketType.'_ticketdesc'), $data['customer_company'], @$data['mobilenr'])
                    );
                }
                else if($ticketType=='bookinsurance'){
                   $dataTicket = array(
                        'tickettitle' => lang('page_lb_'.$ticketType).': '.$data['assignmentnr_prefix'].' '.$data['customer_company'],
                        'ticketstatus' => 1, //Offen
                        'customer' => $data['customer'],
                        'responsible' => $data['responsible'],
                        'company' => $data['customer_company'],
                        'ticketdesc' => sprintf(lang('page_lb_'.$ticketType.'_ticketdesc'), $data['customer_company'], @$data['mobilenr'])
                    );
                }
                else{
                    $dataTicket = array(
                        'tickettitle' => lang('page_lb_'.$ticketType).': '.$data['assignmentnr_prefix'].' '.$data['customer_company'],
                        'ticketstatus' => 1, //Offen
                        'customer' => $data['customer'],
                        'responsible' => $data['responsible'],
                        'company' => $data['customer_company'],
                        'ticketdesc' => sprintf(lang('page_lb_'.$ticketType.'_ticketdesc'), $data['customer_company'], @$data['simnr'])
                    );
                }

                $ticketnr = $this->Ticket_model->add($dataTicket);
                if($ticketnr>0){

                    //History
                    $Action_data = array('actionname'=>'ticket', 'actionid'=>$ticketnr, 'actiontitle'=>'ticket_added');
                    do_action_history($Action_data);

                    //Send Mail
                    if($emailSend==1){

                        $dataTicket = (array) $this->Ticket_model->get($ticketnr,' '
                            . 'tblsalutations.name as salutation, '
                            . 'responsible.email, '
                            . 'responsible.name, '
                            . 'responsible.surname,'
                            . 'tbltickets.tickettitle, '
                            . 'tbltickets.ticketnr, '
                            . 'tbltickets.ticketnr_prefix, '
                            . 'tbltickets.company as customer_company,'
                            . 'tbltickets.ticketdesc ',

                            array('tblusers as responsible'=>'responsible.userid=tbltickets.responsible',
                            'tblsalutations'=>'tblsalutations.salutationid=responsible.salutation'
                            )
                        );

                        $this->sendMail_ticket($dataTicket);
                    }

                    return 1;
                }
                else{
                    return sprintf(lang('failed'),lang('page_'.$ticketType));
                }
            }
            else{
                return sprintf(lang('failed'),lang('page_'.$ticketType));
            }
        }
        return sprintf(lang('failed'),lang('page_'.$ticketType));
    }

    function sendMail_ticket($data){
        $data['linktoticket'] = '<a href="'.base_url('admin/tickets/detail/'. $data['ticketnr']).'" target="_blank">'.lang('click_here').'</a>';
        $data['comments'] = '';

        $merge_fields = array();
        $merge_fields = array_merge($merge_fields, get_ticketreminder_merge_fields($data));

        $sent = $this->Email_model->send_email_template('ticket-reminder', $data['email'], $merge_fields);
        if ($sent) {
            return 1;
        }
        else{
            return 0;
        }
    }
}
