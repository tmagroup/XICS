<?php
    /*
     * Paging
     */
    $aColumns = array(
        'hardwareassignmentnr_prefix',
        "tblcustomers.company as company",
        "tblhardwareassignmentstatus.name as hardwareassignmentstatus",
        'hardwareassignmentnr'
    );
    $sIndexColumn  = "hardwareassignmentnr";
    $sTable        = 'tblhardwareassignments';
    //$join          = array();
    $join = array('LEFT JOIN tblhardwareassignmentstatus ON tblhardwareassignmentstatus.id=tblhardwareassignments.hardwareassignmentstatus',
    'LEFT JOIN tblcustomers ON tblcustomers.customernr=tblhardwareassignments.customer');

    //$where = do_action('hardwareassignments_table_sql_where', array());

        $where = array();
        if(get_user_role()=='customer'){
            array_push($where, "AND tblhardwareassignments.customer='".get_user_id()."'");
        }
	if ($filter_hardwareassignmentstatus!='' && $filter_hardwareassignmentstatus!='undefined') {
            array_push($where, "AND tblhardwareassignments.hardwareassignmentstatus='".$filter_hardwareassignmentstatus."'");
	}

    $result  = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where);

    $output  = $result['output'];
    $rResult = $result['rResult'];

    $iTotalRecords = $output['iTotalRecords']; //count($rResult);
    $iDisplayLength = (isset($_REQUEST['length']))?intval($_REQUEST['length']):0;
    $iDisplayLength = $iDisplayLength < 0 ? $iTotalRecords : $iDisplayLength;
    $iDisplayStart = (isset($_REQUEST['start']))?intval($_REQUEST['start']):0;
    $sEcho = (isset($_REQUEST['draw']))?intval($_REQUEST['draw']):0;
    $records = array();
    $records["data"] = array();

    //Don't remove
    $end = $iDisplayStart + $iDisplayLength;
    $end = $end > $iTotalRecords ? $iTotalRecords : $end;

    //Data Loop
    foreach ($rResult as $aRow) {

        $aRow['action'] = '';
        if($GLOBALS['hardwareassignment_permission']['edit']){
            $aRow['action']='<a href="'.base_url('admin/hardwareassignments/hardwareassignment/'.$aRow['hardwareassignmentnr']).'" class="btn btn-sm btn-default btn-circle btn-editable blue"><i class="fa fa-pencil"></i></a>';
        }

        if($GLOBALS['hardwareassignment_permission']['delete']){
            if(!$GLOBALS['hardwareassignment_permission']['edit']){ $aRow['action'] = ''; }
            $aRow['action'].='<a href="javascript:void(0);" class="btn btn-sm btn-default btn-circle btn-editable red" onclick="deleteConfirmation('
                    . '\''.base_url("admin/hardwareassignments/delete").'\','
                    . '\''.$aRow["hardwareassignmentnr"].'\','
                    . '\''.lang("page_lb_delete_hardwareassignment").'\','
                    . '\''.lang("page_lb_delete_hardwareassignment_info").'\');">'
                    . '<i class="fa fa-remove"></i></a>';
        }

        //if($aRow['shippingnr']!="" && $aRow['shippingnr']!="0"){
        $aRow['action'].='<a href="'.base_url('admin/hardwareassignments/printdeliverynote/'.$aRow['hardwareassignmentnr']).'" target="_blank" class="btn btn-sm btn-default btn-editable yellow"><i class="fa fa-print"></i> '.lang('page_lb_print_deliverynote').'</a>';
        $aRow['action'].='<a href="'.base_url('admin/hardwareassignments/printhardwareinvoice/'.$aRow['hardwareassignmentnr']).'" target="_blank" class="btn btn-sm btn-default btn-editable yellow"><i class="fa fa-print"></i> '.lang('page_lb_print_invoice').'</a>';
        //}

        //Hardwareinput Detail
        $aRow['hardwareassignmentnr_prefix'] = '<a href="'.base_url('admin/hardwareassignments/detail/'.$aRow['hardwareassignmentnr']).'">'.$aRow['hardwareassignmentnr_prefix'].'</a>';

        $records["data"][] = array(
            $aRow['hardwareassignmentnr_prefix'],
            $aRow['company'],
            $aRow['hardwareassignmentstatus'],
            $aRow['action'],
            $aRow['hardwareassignmentnr']
        );
    }

    //Don't remove
    $records["draw"] = $sEcho;
    $records["recordsTotal"] = $iTotalRecords;
    $records["recordsFiltered"] = $iTotalRecords;

    //echo json_encode($records);
    $output = $records;
?>