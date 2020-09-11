<?php     
    /* 
     * Paging
     */
    $aColumns = array(                
        'hardwareinputnr_prefix',
        " CONCAT(tblsuppliers.name,' ',tblsuppliers.surname) as suppliername",
        "hardwareinputdate",
        
        "IF(
        (SELECT COUNT(*) FROM tblhardwareinputproducts WHERE hardwareinputnr=tblhardwareinputs.hardwareinputnr)=(SELECT COUNT(*) FROM tblhardwareinputproducts WHERE hardwareinputnr=tblhardwareinputs.hardwareinputnr AND quantity=1),'green',	
            IF((SELECT COUNT(*) FROM tblhardwareinputproducts WHERE hardwareinputnr=tblhardwareinputs.hardwareinputnr)=(SELECT COUNT(*) FROM tblhardwareinputproducts WHERE hardwareinputnr=tblhardwareinputs.hardwareinputnr AND quantity=0),'red','yellow')	
        ) as lampSymbol",
        
        'hardwareinputnr'        
    );
    $sIndexColumn  = "hardwareinputnr";
    $sTable        = 'tblhardwareinputs';
    //$join          = array();    
    $join = array('LEFT JOIN tblsuppliers ON tblsuppliers.suppliernr=tblhardwareinputs.supplier');    
    //$where = do_action('hardwareinputs_table_sql_where', array());    
    
        $where = array();
	if ($filter_lampsymbol!='' && $filter_lampsymbol!='undefined') {
            array_push($where, "AND IF(
            (SELECT COUNT(*) FROM tblhardwareinputproducts WHERE hardwareinputnr=tblhardwareinputs.hardwareinputnr)=(SELECT COUNT(*) FROM tblhardwareinputproducts WHERE hardwareinputnr=tblhardwareinputs.hardwareinputnr AND quantity=1),'green',	
                IF((SELECT COUNT(*) FROM tblhardwareinputproducts WHERE hardwareinputnr=tblhardwareinputs.hardwareinputnr)=(SELECT COUNT(*) FROM tblhardwareinputproducts WHERE hardwareinputnr=tblhardwareinputs.hardwareinputnr AND quantity=0),'red','yellow')	
            )='".$filter_lampsymbol."'");             
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
        
        if($GLOBALS['hardwareinput_permission']['edit']){
            $aRow['action']='<a href="'.base_url('admin/hardwareinputs/hardwareinput/'.$aRow['hardwareinputnr']).'" class="btn btn-sm btn-default btn-circle btn-editable blue"><i class="fa fa-pencil"></i></a>';  
        }    
        
        if($GLOBALS['hardwareinput_permission']['delete']){            
            if(!$GLOBALS['hardwareinput_permission']['edit']){ $aRow['action'] = ''; }
            $aRow['action'].='<a href="javascript:void(0);" class="btn btn-sm btn-default btn-circle btn-editable red" onclick="deleteConfirmation('
                    . '\''.base_url("admin/hardwareinputs/delete").'\','
                    . '\''.$aRow["hardwareinputnr"].'\','
                    . '\''.lang("page_lb_delete_hardwareinput").'\','
                    . '\''.lang("page_lb_delete_hardwareinput_info").'\');">'
                    . '<i class="fa fa-remove"></i></a>';        
        }  
        if(!$GLOBALS['hardwareinput_permission']['edit'] && !$GLOBALS['hardwareinput_permission']['delete']){
            $aRow['action'] = lang('access_denied');
        }
        
        //Hardwareinput Detail
        $aRow['hardwareinputnr_prefix'] = '<a href="'.base_url('admin/hardwareinputs/detail/'.$aRow['hardwareinputnr']).'">'.$aRow['hardwareinputnr_prefix'].'</a>';
        
        //Lamp Symbol
        $lampSymbol = "<img src='".base_url('assets/pages/img/'.$aRow['lampSymbol'].'.png')."' width='24' />";
        
        $records["data"][] = array(
            $aRow['hardwareinputnr_prefix'],                        
            $aRow['suppliername'],
            _d($aRow['hardwareinputdate']),
            $lampSymbol,
            $aRow['action'],   
            $aRow['hardwareinputnr']
        );  
    }

    //Don't remove
    $records["draw"] = $sEcho;
    $records["recordsTotal"] = $iTotalRecords;
    $records["recordsFiltered"] = $iTotalRecords;  

    //echo json_encode($records);  
    $output = $records;
?>