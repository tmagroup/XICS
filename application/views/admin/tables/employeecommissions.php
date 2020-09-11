<?php  
	/* 
     * Paging
     */
    $aColumns = array(
        'slipnr_prefix',
        'period',
        'pointsvalue',
        'withdrawvalue',        
        "CONCAT(tblusers.name,' ',tblusers.surname) as fullname",  
        'slipnr'
    );
    $sIndexColumn  = "slipnr";
    $sTable        = 'tblcommisionslips';    
    $join = array('JOIN tblusers ON tblusers.userid=tblcommisionslips.userid');
    //$where = do_action('employeecommissions_table_sql_where', array()); 
	
	$where = array(); 
        
        if($GLOBALS['current_user']->userrole==6){
            array_push($where,"AND tblcommisionslips.userid='".get_user_id()."'");
        }
    
	if ($filter_user!='') {
		array_push($where, 'AND tblcommisionslips.userid='.$filter_user);
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
        
        $aRow['action']='<a href="'.base_url('admin/employeecommissions/downloadslip/'.$aRow['slipnr']).'" class="btn btn-sm btn-default btn-editable red"><i class="fa fa-download"></i> '.ucwords(strtolower(lang('page_lb_commission_settlement'))).'</a>';  
       
        $records["data"][] = array(            
            $aRow['slipnr_prefix'],            
            date('M-Y',strtotime($aRow['period']."-1")),   
            $aRow['pointsvalue'],            
            format_money($aRow['withdrawvalue'], "&nbsp;".$GLOBALS['currency_data']['currency_name']), 
            $aRow['fullname'], 
            $aRow['action'],
            $aRow['slipnr']
        );        
    }

    //Don't remove
    $records["draw"] = $sEcho;
    $records["recordsTotal"] = $iTotalRecords;
    $records["recordsFiltered"] = $iTotalRecords;  

    //echo json_encode($records);  
    $output = $records;
?>