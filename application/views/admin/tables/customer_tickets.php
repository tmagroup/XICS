<?php     
    /* 
     * Paging
     */
    $aColumns = array(
        'ticketnr',
        'ticketnr_prefix',
        'ticketnr_prefix',
        'tickettitle',
        'tbltickets.company as company',
        'tblticketstatus.name as ticketstatus',
        "CONCAT(tblusers.name,' ',tblusers.surname) as fullname"
    );
    $sIndexColumn  = "ticketnr";
    $sTable        = 'tbltickets';
    //$join          = array();    
    $join = array('LEFT JOIN tblusers ON tblusers.userid=tbltickets.responsible',
    'LEFT JOIN tblticketstatus ON tblticketstatus.id=tbltickets.ticketstatus'
    );
    
    //$where = do_action('tickets_table_sql_where', array());    
        $where = array(); 
        array_push($where, "AND tbltickets.customer='".$customer_id."'");
        
	if ($filter_responsible!='' && $filter_responsible!='undefined') {
            array_push($where, "AND tbltickets.responsible='".$filter_responsible."' ");
	}
        if ($filter_ticketstatus!='' && $filter_ticketstatus!='undefined') {
            array_push($where, "AND tbltickets.ticketstatus='".$filter_ticketstatus."' ");
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
        
        if($GLOBALS['ticket_permission']['edit']){
            $aRow['action']='<a href="'.base_url('admin/tickets/ticket/'.$aRow['ticketnr']).'" class="btn btn-sm btn-default btn-circle btn-editable blue"><i class="fa fa-pencil"></i></a>';  
        }    
        
        if($GLOBALS['ticket_permission']['delete']){            
            if(!$GLOBALS['ticket_permission']['edit']){ $aRow['action'] = ''; }
            $aRow['action'].='<a href="javascript:void(0);" class="btn btn-sm btn-default btn-circle btn-editable red" onclick="deleteConfirmation('
                    . '\''.base_url("admin/tickets/delete").'\','
                    . '\''.$aRow["ticketnr"].'\','
                    . '\''.lang("page_lb_delete_ticket").'\','
                    . '\''.lang("page_lb_delete_ticket_info").'\');">'
                    . '<i class="fa fa-remove"></i></a>';        
        }  
        
        if(!$GLOBALS['ticket_permission']['edit'] && !$GLOBALS['ticket_permission']['delete']){
            $aRow['action'] = lang('access_denied');
        }
        
        //Lead Detail
        //$aRow['ticketnr_prefix'] = '<a href="'.base_url('admin/tickets/detail/'.$aRow['ticketnr']).'">'.$aRow['ticketnr_prefix'].'</a>';
        $aRow['ticketnr_prefix'] = '<a href="javascript:void(0);" onclick="FormAjax(\''.base_url('admin/customers/getTicketDetail/'.$aRow['ticketnr']).'\',\''.lang('page_detail_ticket').'\');">'.$aRow['ticketnr_prefix'].'</a>';
        
        $records["data"][] = array(
            $aRow['ticketnr_prefix'],
            $aRow['ticketnr'],
            $aRow['tickettitle'],
            $aRow['company'],
            $aRow['ticketstatus'],
            $aRow['fullname']
        );        
    }

    //Don't remove
    $records["draw"] = $sEcho;
    $records["recordsTotal"] = $iTotalRecords;
    $records["recordsFiltered"] = $iTotalRecords;  

    //echo json_encode($records);  
    $output = $records;
?>