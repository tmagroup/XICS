<?php     
    /* 
     * Paging
     */
    $aColumns = array(
        'assignmentnr',
        'assignmentnr_prefix', 
        'assignmentnr_prefix', 
        'tblassignments.company as company',
        "CONCAT(tblusers.name,' ',tblusers.surname) as fullname",
        'tblassignmentstatus.name as assignmentstatus',        
    );
    $sIndexColumn  = "assignmentnr";
    $sTable        = 'tblassignments';
    //$join          = array();    
    $join = array('LEFT JOIN tblusers ON tblusers.userid=tblassignments.responsible',
    'LEFT JOIN tblassignmentstatus ON tblassignmentstatus.id=tblassignments.assignmentstatus');
    
    //$where = do_action('assignments_table_sql_where', array());    
        $where = array(); 
        array_push($where, "AND tblassignments.customer='".$customer_id."'");
        
	if ($filter_responsible!='' && $filter_responsible!='undefined') {
            array_push($where, "AND tblassignments.responsible='".$filter_responsible."'");
	}
        if ($filter_assignmentstatus!='' && $filter_assignmentstatus!='undefined') {
            array_push($where, "AND tblassignments.assignmentstatus='".$filter_assignmentstatus."'");
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
        
        if($GLOBALS['assignment_permission']['edit']){
            $aRow['action']='<a href="'.base_url('admin/assignments/assignment/'.$aRow['assignmentnr']).'" class="btn btn-sm btn-default btn-circle btn-editable blue"><i class="fa fa-pencil"></i></a>';  
        }    
        
        if($GLOBALS['assignment_permission']['delete']){            
            if(!$GLOBALS['assignment_permission']['edit']){ $aRow['action'] = ''; }
            $aRow['action'].='<a href="javascript:void(0);" class="btn btn-sm btn-default btn-circle btn-editable red" onclick="deleteConfirmation('
                    . '\''.base_url("admin/assignments/delete").'\','
                    . '\''.$aRow["assignmentnr"].'\','
                    . '\''.lang("page_lb_delete_assignment").'\','
                    . '\''.lang("page_lb_delete_assignment_info").'\');">'
                    . '<i class="fa fa-remove"></i></a>';        
        }  
        if(!$GLOBALS['assignment_permission']['edit'] && !$GLOBALS['assignment_permission']['delete']){
            $aRow['action'] = lang('access_denied');
        }
        
        //Assignment Detail
        //$aRow['assignmentnr_prefix'] = '<a href="'.base_url('admin/assignments/detail/'.$aRow['assignmentnr']).'">'.$aRow['assignmentnr_prefix'].'</a>';
        $aRow['assignmentnr_prefix'] = '<a href="javascript:void(0);" onclick="FormAjax(\''.base_url('admin/customers/getAssignmentDetail/'.$aRow['assignmentnr']).'\',\''.lang('page_detail_assignment').'\');">'.$aRow['assignmentnr_prefix'].'</a>';
        
        $records["data"][] = array(
            $aRow['assignmentnr_prefix'],            
            $aRow['assignmentnr'],
            $aRow['company'],
            $aRow['fullname'],            
            $aRow['assignmentstatus']
        );  
    }

    //Don't remove
    $records["draw"] = $sEcho;
    $records["recordsTotal"] = $iTotalRecords;
    $records["recordsFiltered"] = $iTotalRecords;  

    //echo json_encode($records);  
    $output = $records;
?>