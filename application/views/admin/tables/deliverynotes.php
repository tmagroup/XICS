<?php     
    /* 
     * Paging
     */
    $aColumns = array(        
        'shippingslipnr_prefix',
        'tblhardwareassignmentshippingslips.customer_company as company',
        'shippingslipnr'
    );
    $sIndexColumn  = "shippingslipnr";
    $sTable        = 'tblhardwareassignmentshippingslips';
    $join          = array();        
    $where = do_action('hardwareassignmentshippingslips_table_sql_where', array());            
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
        if($GLOBALS['deliverynote_permission']['delete']){                   
            $aRow['action'].='<a href="javascript:void(0);" class="btn btn-sm btn-default btn-circle btn-editable red" onclick="deleteConfirmation('
                    . '\''.base_url("admin/deliverynotes/delete").'\','
                    . '\''.$aRow["shippingslipnr"].'\','
                    . '\''.lang("page_lb_delete_deliverynote").'\','
                    . '\''.lang("page_lb_delete_deliverynote_info").'\');">'
                    . '<i class="fa fa-remove"></i></a>';        
        }  
        
        //if($aRow['shippingnr']!="" && $aRow['shippingnr']!="0"){
        $aRow['action'].='<a href="'.base_url('admin/deliverynotes/printdeliverynote/'.$aRow['shippingslipnr']).'" target="_blank" class="btn btn-sm btn-default btn-editable yellow"><i class="fa fa-print"></i> '.lang('page_lb_print').'</a>';  
        //$aRow['action'].='<a href="'.base_url('admin/deliverynotes/tracking/'.$aRow['shippingslipnr']).'" target="_blank" class="btn btn-sm btn-default btn-editable yellow"><i class="icon-graph"></i> '.lang('page_lb_tracking').'</a>';  
        $aRow['action'].='<a href="javascript:void(0);" class="btn btn-sm btn-default btn-editable yellow" onclick="FormAjax('
                    . '\''.base_url("admin/deliverynotes/tracking").'\','
                    . '\''.base_url("admin/deliverynotes/getShippingnr/".$aRow['shippingslipnr']).'\','
                    . '\''.lang("page_lb_tracking").'\','
                    . '\'\');"><i class="icon-graph"></i> '.lang('page_lb_tracking').'</a>';  
        
        //}
       
        $records["data"][] = array(
            $aRow['shippingslipnr_prefix'],                        
            $aRow['company'],
            $aRow['action'],   
            $aRow['shippingslipnr']
        );  
    }

    //Don't remove
    $records["draw"] = $sEcho;
    $records["recordsTotal"] = $iTotalRecords;
    $records["recordsFiltered"] = $iTotalRecords;  

    //echo json_encode($records);  
    $output = $records;
?>