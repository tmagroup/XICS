<?php     
    /* 
     * Paging
     */
    $aColumns = array(               
        'categoryname',
        'active',             
        'categoryid',
    );
    $sIndexColumn  = "categoryid";
    $sTable        = 'tbldocumentsettings';
    $join          = array();    
    $where = do_action('documentsettings_table_sql_where', array());    
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
        
        if($GLOBALS['documentsetting_permission']['edit']){
            $aRow['action']='<a href="'.base_url('admin/documentsettings/category/'.$aRow['categoryid']).'" class="btn btn-sm btn-default btn-circle btn-editable blue"><i class="fa fa-pencil"></i></a>';  
        }    
        
        if($GLOBALS['documentsetting_permission']['delete']){            
            if(!$GLOBALS['documentsetting_permission']['edit']){ $aRow['action'] = ''; }
            $aRow['action'].='<a href="javascript:void(0);" class="btn btn-sm btn-default btn-circle btn-editable red" onclick="deleteConfirmation('
                    . '\''.base_url("admin/documentsettings/delete").'\','
                    . '\''.$aRow["categoryid"].'\','
                    . '\''.lang("page_lb_delete_category").'\','
                    . '\''.lang("page_lb_delete_category_info").'\');">'
                    . '<i class="fa fa-remove"></i></a>';        
        }    
        
        if(!$GLOBALS['documentsetting_permission']['edit'] && !$GLOBALS['documentsetting_permission']['delete']){
            $aRow['action'] = lang('access_denied');
        }

        $checked = ($aRow['active']==1)?' checked':'';         
        $aRow['active'] = '<div class="onoffswitch" data-toggle="tooltip" data-title=""><input '.$checked.' type="checkbox" data-switch-url="'.base_url('admin/documentsettings/change_active').'" data-id="'.$aRow['categoryid'].'" class="make-switch" data-on-text="'.lang('page_lb_yes').'" data-off-text="'.lang('page_lb_no').'" data-on-color="primary" data-off-color="danger" data-size="small"></div>';
                
        $records["data"][] = array(            
            $aRow['categoryname'],             
            $aRow['active'],            
            $aRow['action'],
            $aRow['categoryid']
        );        
    }

    //Don't remove
    $records["draw"] = $sEcho;
    $records["recordsTotal"] = $iTotalRecords;
    $records["recordsFiltered"] = $iTotalRecords;  

    //echo json_encode($records);  
    $output = $records;
?>