<?php     
    /* 
     * Paging
     */
    $aColumns = array(        
        'discounttitle',
        'discountvalue',
        'cardnumber',        
        'created',    
        'discountnr'
    );
    $sIndexColumn  = "discountnr";
    $sTable        = 'tbldiscountlevels';
    $join          = array();    
    $where = do_action('discountlevels_table_sql_where', array());    
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
        
        if($GLOBALS['discountlevel_permission']['edit']){
            $aRow['action']='<a href="'.base_url('admin/discountlevels/discount/'.$aRow['discountnr']).'" class="btn btn-sm btn-default btn-circle btn-editable blue"><i class="fa fa-pencil"></i></a>';  
        }    
        
        if($GLOBALS['discountlevel_permission']['delete']){            
            if(!$GLOBALS['discountlevel_permission']['edit']){ $aRow['action'] = ''; }
            $aRow['action'].='<a href="javascript:void(0);" class="btn btn-sm btn-default btn-circle btn-editable red" onclick="deleteConfirmation('
                    . '\''.base_url("admin/discountlevels/delete").'\','
                    . '\''.$aRow["discountnr"].'\','
                    . '\''.lang("page_lb_delete_discountlevel").'\','
                    . '\''.lang("page_lb_delete_discountlevel_info").'\');">'
                    . '<i class="fa fa-remove"></i></a>';        
        }    
        
        if(!$GLOBALS['discountlevel_permission']['edit'] && !$GLOBALS['discountlevel_permission']['delete']){
            $aRow['action'] = lang('access_denied');
        }
       
        $records["data"][] = array(            
            $aRow['discounttitle'],            
            $aRow['discountvalue'],            
            $aRow['cardnumber'],
            _d($aRow['created']),
            $aRow['action'],
            $aRow['discountnr']
        );        
    }

    //Don't remove
    $records["draw"] = $sEcho;
    $records["recordsTotal"] = $iTotalRecords;
    $records["recordsFiltered"] = $iTotalRecords;  

    //echo json_encode($records);  
    $output = $records;
?>