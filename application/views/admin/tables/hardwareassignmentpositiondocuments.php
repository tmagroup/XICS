<?php     
    /* 
     * Paging
     */
    $aColumns = array(        
        'tbldocumentsettings.categoryname as categoryname', 
        "file_name",
        "(SELECT CONCAT(name,' ',surname) FROM tblusers WHERE userid=tblfiles.userid) as uploaded_by",        
        "created",
        'filetype',        
        'id'
    );
    $sIndexColumn  = "id";
    $sTable        = 'tblfiles';
    //$join          = array();    
    $join = array('LEFT JOIN tbldocumentsettings ON tbldocumentsettings.categoryid=tblfiles.categoryid');
    
    //$where = do_action('assignments_table_sql_where', array());    
        $where = array(); 
        array_push($where, "AND tblfiles.rel_id='".$hardwareassignmentproductid."'");
        array_push($where, "AND tblfiles.rel_type='hardwareassignmentpositiondocument'");
        
        
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
                
        $aRow['action'].='<a href="javascript:void(0);" class="btn btn-sm btn-default btn-circle btn-editable red" onclick="deleteConfirmation('
                . '\''.base_url("admin/assignments/deleteHardwarePositionDocument").'\','
                . '\''.$aRow["id"].'\','
                . '\''.lang("page_lb_delete_document").'\','
                . '\''.lang("page_lb_delete_document_info").'\',\'true\');">'
                . '<i class="fa fa-remove"></i></a>';        

        $attachment_url = base_url('admin/assignments/downloadHardwarePositionDocument/'.$aRow['id']);
        $aRow['file_name'] = '<a href="'.$attachment_url.'"><div class="pull-left"><i class="'.get_mime_class($aRow['filetype']).'"></i></div> '.$aRow['file_name'].'</a>';
        
        $records["data"][] = array(
            /*$aRow['categoryname'],*/                       
            $aRow['file_name'],
            $aRow['uploaded_by'],            
            _dt($aRow['created']),
            $aRow['action'],   
            $aRow['id']
        );  
    }

    //Don't remove
    $records["draw"] = $sEcho;
    $records["recordsTotal"] = $iTotalRecords;
    $records["recordsFiltered"] = $iTotalRecords;  

    //echo json_encode($records);  
    $output = $records;
?>