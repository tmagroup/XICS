<?php     
    /* 
     * Paging
     */
    $aColumns = array(        
        'quotationnr_prefix', 
        /*'tblquotations.company as company',*/        
        'tblcustomers.company as company',        
        "CONCAT(tblusers.name,' ',tblusers.surname) as fullname",
        'tblquotationstatus.name as quotationstatus', 
        "tblquotations.created as created",
        'quotationnr'
    );
    $sIndexColumn  = "quotationnr";
    $sTable        = 'tblquotations';
    //$join          = array();    
    $join = array('LEFT JOIN tblusers ON tblusers.userid=tblquotations.responsible',
    'LEFT JOIN tblquotationstatus ON tblquotationstatus.id=tblquotations.quotationstatus',
    'LEFT JOIN tblcustomers ON tblcustomers.customernr=tblquotations.customer');
    
    //$where = do_action('quotations_table_sql_where', array());    
        $where = array(); 
        
        if(get_user_role()=='customer'){
            array_push($where, "AND tblquotations.customer='".get_user_id()."'");
        }
        //- On the Dashboard he should only see Quotations which belongs to the User who is logged in. (Salesman)
        else if(get_user_role()=='user' && isset($GLOBALS['current_user']->userrole) && $GLOBALS['current_user']->userrole==3){
            array_push($where,"AND (tblquotations.userid='".get_user_id()."' OR tblquotations.responsible='".get_user_id()."') ");
        }
        
	if ($filter_responsible!='' && $filter_responsible!='undefined') {
            array_push($where, "AND tblquotations.responsible='".$filter_responsible."'");
	}
        if ($filter_quotationstatus!='' && $filter_quotationstatus!='undefined') {
            array_push($where, "AND tblquotations.quotationstatus='".$filter_quotationstatus."'");
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
        
        if($GLOBALS['quotation_permission']['edit']){
            $aRow['action']='<a href="'.base_url('admin/quotations/quotation/'.$aRow['quotationnr']).'" class="btn btn-sm btn-default btn-circle btn-editable blue"><i class="fa fa-pencil"></i></a>';  
        }    
        
        if($GLOBALS['quotation_permission']['delete']){            
            if(!$GLOBALS['quotation_permission']['edit']){ $aRow['action'] = ''; }
            $aRow['action'].='<a href="javascript:void(0);" class="btn btn-sm btn-default btn-circle btn-editable red" onclick="deleteConfirmation('
                    . '\''.base_url("admin/quotations/delete").'\','
                    . '\''.$aRow["quotationnr"].'\','
                    . '\''.lang("page_lb_delete_quotation").'\','
                    . '\''.lang("page_lb_delete_quotation_info").'\');">'
                    . '<i class="fa fa-remove"></i></a>';        
        }  
        
        if(!$GLOBALS['quotation_permission']['edit'] && !$GLOBALS['quotation_permission']['delete']){
            $aRow['action'] = lang('access_denied');
        }
        
        $aRow['action2']='<a href="'.base_url('admin/quotations/printquotation/'.$aRow['quotationnr']).'" target="_blank" class="btn btn-sm btn-default btn-editable yellow"><i class="fa fa-print"></i> '.lang('page_lb_print_quotation').'</a>';  
        $aRow['action2'].='<a href="'.base_url('admin/quotations/printhardwarequotation/'.$aRow['quotationnr']).'" target="_blank" class="btn btn-sm btn-default btn-editable yellow"><i class="fa fa-print"></i> '.lang('page_lb_print_hardware_quotation').'</a>';  
        $aRow['action2'].='<a href="'.base_url('admin/quotations/printconsultationprotocol/'.$aRow['quotationnr']).'" target="_blank" class="btn btn-sm btn-default btn-editable yellow"><i class="fa fa-print"></i> '.lang('page_lb_print_consultation_protocol').'</a>';  
        $aRow['action2'].='<a href="'.base_url('admin/quotations/printinvoiceprotocol/'.$aRow['quotationnr']).'" target="_blank" class="btn btn-sm btn-default btn-editable yellow"><i class="fa fa-print"></i> '.lang('page_lb_print_invoice_protocol').'</a>';  
        
        //Quotation Detail
        $aRow['quotationnr_prefix'] = '<a href="'.base_url('admin/quotations/detail/'.$aRow['quotationnr']).'">'.$aRow['quotationnr_prefix'].'</a>';
        
        $records["data"][] = array(
            $aRow['quotationnr_prefix'],                        
            $aRow['company'],
            $aRow['fullname'],            
            $aRow['quotationstatus'],
            _dt($aRow['created']),   
            $aRow['action'],   
            $aRow['quotationnr']
        );  
        if($aRow['action2']!=""){
            $records["data"][] = array(
                $aRow['action2'],            
                'COLSPAN', 
                '', 
                '',         
                '', 
                '', 
                '', 
            );  
        }    
    }

    //Don't remove
    $records["draw"] = $sEcho;
    $records["recordsTotal"] = $iTotalRecords;
    $records["recordsFiltered"] = $iTotalRecords;  

    //echo json_encode($records);  
    $output = $records;
?>