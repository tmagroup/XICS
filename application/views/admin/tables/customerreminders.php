<?php     
    /* 
     * Paging
     */
    $aColumns = array(
        'remindernr',
        'remindernr_prefix',
        'remindernr_prefix',
        'reminddate',
        "IF(reminderway=1,'".lang('page_lb_reminder_is_notified_boolean_yes')."','".lang('page_lb_reminder_is_notified_boolean_no')."') as reminderway",        
        '(tblremindersubjects.name) as remindersubject', 
        'rel_id',
    );
    $sIndexColumn  = "remindernr";
    $sTable        = 'tblcustomerreminders';
    $join = array('LEFT JOIN tblremindersubjects ON tblremindersubjects.id=tblcustomerreminders.remindersubject');
    
    //$where = do_action('reminders_table_sql_where', array());    
    $where = array(); 
    array_push($where, "AND tblcustomerreminders.rel_id='".$rel_id."' AND tblcustomerreminders.rel_type='".$rel_type."'");
    
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
        
        $aRow['action']='<a href="javascript:void(0);" class="btn btn-sm btn-default btn-circle btn-editable blue" onclick="addeditReminderAjax('
        . '\''.base_url("admin/customerreminders/editReminder").'\','
        . '\''.$aRow["remindernr"].'\','       
        . '\''.sprintf(lang('page_edit_reminder'),lang('page_lead')).'\');">'
        . '<i class="fa fa-pencil"></i></a>';  
        
        $aRow['action'].='<a href="javascript:void(0);" class="btn btn-sm btn-default btn-circle btn-editable red" onclick="deleteConfirmation('
        . '\''.base_url("admin/customerreminders/deleteReminder").'\','
        . '\''.$aRow["remindernr"].'\','
        . '\''.lang("page_lb_delete_reminder").'\','
        . '\''.lang("page_lb_delete_reminder_info").'\',\'true\',\''.$aRow["rel_id"].'\');">'
        . '<i class="fa fa-remove"></i></a>';     
        
        //This is Fix Role for POS User has not access
        if($GLOBALS['current_user']->userrole!=6){
            $aRow['action'].='<a href="javascript:void(0);" class="btn btn-sm btn-default btn-circle btn-editable red" onclick="sendReminder('
            . '\''.base_url("admin/reminders/sendReminder").'\','
            . '\''.$aRow["remindernr"].'\',\''.$rel_type.'\',\'true\',\''.$aRow["rel_id"].'\');">'
            . '<i class="fa fa-send"></i> '.lang('page_lb_request_sublist').'</a>';     
        }
        
        $records["data"][] = array(            
	    $aRow['remindernr_prefix'],
            $aRow['remindernr'],
            _dt($aRow['reminddate']), 
            $aRow['reminderway'],   
            $aRow['remindersubject'],   
            $aRow['action']
        );        
    }

    //Don't remove
    $records["draw"] = $sEcho;
    $records["recordsTotal"] = $iTotalRecords;
    $records["recordsFiltered"] = $iTotalRecords;  

    //echo json_encode($records);  
    $output = $records;
?>