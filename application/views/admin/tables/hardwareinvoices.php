<?php     
    /* 
     * Paging
     */
    $aColumns = array(
        'invoicenr_prefix',
        'tblhardwareassignmentinvoices.customer_company as company',
        'is_paid',
        'invoicenr'
    );
    $sIndexColumn  = "invoicenr";
    $sTable        = 'tblhardwareassignmentinvoices';
    $join          = array();        
    //$where = do_action('hardwareassignmentinvoices_table_sql_where', array());            
    $where = array();
    if(get_user_role()=='customer'){
        array_push($where, "AND tblhardwareassignmentinvoices.customernr_prefix='".$GLOBALS['current_user']->customernr_prefix."'");
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
        if($GLOBALS['hardwareinvoice_permission']['delete']){                   
            $aRow['action'].='<a href="javascript:void(0);" class="btn btn-sm btn-default btn-circle btn-editable red" onclick="deleteConfirmation('
                    . '\''.base_url("admin/hardwareinvoices/delete").'\','
                    . '\''.$aRow["invoicenr"].'\','
                    . '\''.lang("page_lb_delete_hardwareinvoice").'\','
                    . '\''.lang("page_lb_delete_hardwareinvoice_info").'\');">'
                    . '<i class="fa fa-remove"></i></a>';        
        }  
        
        //if($aRow['shippingnr']!="" && $aRow['shippingnr']!="0"){
        //This is Fix Role for POS User has not access
        $rel_type = '';
        if($GLOBALS['current_user']->userrole!=6){
            $aRow['action'].='<a href="javascript:void(0);" class="btn btn-sm btn-default btn-editable yellow" onclick="sendReminder('
            . '\''.base_url("admin/hardwareinvoices/sendEmail").'\','
            . '\''.$aRow["invoicenr"].'\',\''.$rel_type.'\',\'true\');">'
            . '<i class="fa fa-send"></i> '.lang('page_lb_sendemail').'</a>';     
        }
        $aRow['action'].='<a href="'.base_url('admin/hardwareinvoices/printhardwareinvoice/'.$aRow['invoicenr']).'" target="_blank" class="btn btn-sm btn-default btn-editable yellow"><i class="fa fa-print"></i> '.lang('page_lb_print').'</a>';          
        //}
        
        if(get_user_role()=='user'){
            $readonly = '';
            $langtext = lang('page_hardwareinvoice_paid_unpaid_help');
            $checked = ($aRow['is_paid']==1)?' checked':'';         
            $aRow['is_paid'] = '<div class="onoffswitch" data-toggle="tooltip" data-title="'.$langtext.'"><input '.$readonly.' '.$checked.' type="checkbox" data-switch-url="'.base_url('admin/hardwareinvoices/change_paid').'" data-id="'.$aRow['invoicenr'].'" class="make-switch" data-on-text="'.lang('page_lb_yes').'" data-off-text="'.lang('page_lb_no').'" data-on-color="success" data-off-color="danger" data-size="small"></div>';
        }
        else{
            $is_paid = ($aRow['is_paid']==1)?"<div class='text-success'>".lang('page_lb_yes').'</div>':"<div class='text-danger'>".lang('page_lb_no').'</div>';
            $aRow['is_paid'] = $is_paid;
        }
        
        $records["data"][] = array(
            $aRow['invoicenr_prefix'],                        
            $aRow['company'],
            $aRow['is_paid'],
            $aRow['action'],   
            $aRow['invoicenr']
        );  
    }

    //Don't remove
    $records["draw"] = $sEcho;
    $records["recordsTotal"] = $iTotalRecords;
    $records["recordsFiltered"] = $iTotalRecords;  

    //echo json_encode($records);  
    $output = $records;
?>