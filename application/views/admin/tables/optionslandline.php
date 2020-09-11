<?php     
    /* 
     * Paging
     */
    $aColumns = array(
        'optionnr_prefix',
        'optiontitle',
        'price',        
        'created',    
        'optionnr'
    );
    $sIndexColumn  = "optionnr";
    $sTable        = 'tbloptionslandline';
    $join          = array();    
    $where = do_action('optionslandline_table_sql_where', array());    
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
        
        if($GLOBALS['optionlandline_permission']['edit']){
            $aRow['action']='<a href="'.base_url('admin/optionslandline/option/'.$aRow['optionnr']).'" class="btn btn-sm btn-default btn-circle btn-editable blue"><i class="fa fa-pencil"></i></a>';  
        }    
        
        if($GLOBALS['optionlandline_permission']['delete']){            
            if(!$GLOBALS['optionlandline_permission']['edit']){ $aRow['action'] = ''; }
            $aRow['action'].='<a href="javascript:void(0);" class="btn btn-sm btn-default btn-circle btn-editable red" onclick="deleteConfirmation('
                    . '\''.base_url("admin/optionslandline/delete").'\','
                    . '\''.$aRow["optionnr"].'\','
                    . '\''.lang("page_lb_delete_optionlandline").'\','
                    . '\''.lang("page_lb_delete_optionlandline_info").'\');">'
                    . '<i class="fa fa-remove"></i></a>';        
        }    
        
        if(!$GLOBALS['optionlandline_permission']['edit'] && !$GLOBALS['optionlandline_permission']['delete']){
            $aRow['action'] = lang('access_denied');
        }
            
        $records["data"][] = array(            
	    $aRow['optionnr_prefix'],            
            $aRow['optiontitle'],            
            format_money($aRow['price'], "&nbsp;".$GLOBALS['currency_data']['currency_name']),
            _d($aRow['created']),
            $aRow['action'],
            $aRow['optionnr']
        );        
    }

    //Don't remove
    $records["draw"] = $sEcho;
    $records["recordsTotal"] = $iTotalRecords;
    $records["recordsFiltered"] = $iTotalRecords;  

    //echo json_encode($records);  
    $output = $records;
?>