<?php     
    /* 
     * Paging
     */


    /*$aColumns = array(
        'tblhistories.historienr_prefix as historienr_prefix',
        "CONCAT(tblusers.name,' ',tblusers.surname) as fullname",
        '(SELECT name FROM tblroles WHERE roleid=tblusers.userrole) as userrole',   
        'tblhistories.created as created',
        'tblhistories.actiontitle as actiontitle',
        'tblhistories.historienr as historienr',
        'tblhistories.actionid as actionid',
        'tblhistories.actionname as actionname',
    );
    $sIndexColumn  = "historienr";
    $sTable        = 'tblhistories';
    //$join          = array();    
    $join = array('JOIN tblusers ON tblusers.userid=tblhistories.userid');*/
    

    $aColumns = array(
        'tblhistories.historienr_prefix as historienr_prefix',
        "IF(tblhistories.usertype='customer', (SELECT CONCAT(tblcustomers.name,' ',tblcustomers.surname) FROM tblcustomers WHERE tblcustomers.customernr=tblhistories.userid LIMIT 1), (SELECT CONCAT(tblusers.name,' ',tblusers.surname) FROM tblusers WHERE tblusers.userid=tblhistories.userid LIMIT 1)) as fullname",
        "IF(tblhistories.usertype='customer', 'Customer', (SELECT tblroles.name FROM tblroles JOIN tblusers ON tblroles.roleid=tblusers.userrole WHERE tblusers.userid=tblhistories.userid LIMIT 1)) as userrole",        
        'tblhistories.created as created',
        'tblhistories.actiontitle as actiontitle',
        'tblhistories.historienr as historienr',
        'tblhistories.actionid as actionid',
        'tblhistories.actionname as actionname',
    );
    $sIndexColumn  = "historienr";
    $sTable        = 'tblhistories';
    $join          = array();    
    //$join = array('JOIN tblusers ON tblusers.userid=tblhistories.userid');
    
    
    //$where = do_action('tickets_table_sql_where', array());    
        $where = array(); 
        array_push($where, "AND (tblhistories.userid IN(SELECT userid FROM tblusers) OR tblhistories.userid IN(SELECT customernr FROM tblcustomers)) ");
        
        if ($filter_user!='' && $filter_user!='undefined' && $filter_user>0 && $filter_customer!='' && $filter_customer!='undefined' && $filter_customer>0) {
            array_push($where, "AND ((tblhistories.userid='".$filter_user."' AND tblhistories.usertype='user') OR (tblhistories.userid='".$filter_customer."' AND tblhistories.usertype='customer')) ");
        }
        else if ($filter_user!='' && $filter_user!='undefined' && $filter_user>0) {
            array_push($where, "AND tblhistories.userid='".$filter_user."' AND tblhistories.usertype='user' ");
	}
        else if ($filter_customer!='' && $filter_customer!='undefined' && $filter_customer>0) {
            array_push($where, "AND tblhistories.userid='".$filter_customer."' AND tblhistories.usertype='customer' ");
	}
        
        
        if ($filter_action!='' && $filter_action!='undefined') {
            array_push($where, "AND tblhistories.actionname='".$filter_action."' ");
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
        
        $actionlink = '';
        switch($aRow['actionname'])
        {
            case 'ticket':
                $actionlink = base_url('admin/tickets/detail/'.$aRow['actionid']);
            break; 
            case 'event':
                $actionlink = base_url('admin/calendars/detail/'.$aRow['actionid']);
            break; 
            case 'qualitycheck':
                $actionlink = base_url('admin/qualitychecks/detail/'.$aRow['actionid']);
            break; 
            case 'todo':
                $actionlink = base_url('admin/todos/detail/'.$aRow['actionid']);
            break; 
            case 'lead':
                $actionlink = base_url('admin/leads/detail/'.$aRow['actionid']);
            break; 
            case 'customer':
                $actionlink = base_url('admin/customers/detail/'.$aRow['actionid']);
            break; 
            case 'quotation':
                $actionlink = base_url('admin/quotations/detail/'.$aRow['actionid']);
            break; 
            case 'assignment':
                $actionlink = base_url('admin/assignments/detail/'.$aRow['actionid']);
            break; 
            case 'assignment':
                $actionlink = base_url('admin/assignments/detail/'.$aRow['actionid']);
            break; 
            case 'hardwareassignment':
                $actionlink = base_url('admin/hardwareassignments/detail/'.$aRow['actionid']);
            break; 
            case 'deliverynote':
                $actionlink = base_url('admin/deliverynotes/printdeliverynote/'.$aRow['actionid']);
            break; 
            case 'hardwareinvoice':
                $actionlink = base_url('admin/hardwareinvoices/printhardwareinvoice/'.$aRow['actionid']);
            break;
            case 'hardwareinput':
                $actionlink = base_url('admin/hardwareinputs/detail/'.$aRow['actionid']);
            break;
            case 'monitoring':
                $actionlink = base_url('admin/monitorings/detail/'.$aRow['actionid']);
            break;
            case 'userdocument':
                $actionlink = base_url('admin/documents');
            break;
            case 'infodocument':
                $actionlink = base_url('admin/infodocuments');
            break;
        
            case 'user':
                $actionlink = base_url('admin/users/user/'.$aRow['actionid']);
            break; 
        
            case 'ratemobile':
                $actionlink = base_url('admin/ratesmobile/rate/'.$aRow['actionid']);
            break; 
        
            case 'ratelandline':
                $actionlink = base_url('admin/rateslandline/rate/'.$aRow['actionid']);
            break; 
        
            case 'optionmobile':
                $actionlink = base_url('admin/optionsmobile/option/'.$aRow['actionid']);
            break; 
        
            case 'optionlandline':
                $actionlink = base_url('admin/optionslandline/option/'.$aRow['actionid']);
            break; 
            
            case 'discountlevel':
                $actionlink = base_url('admin/discountlevels/discount/'.$aRow['actionid']);
            break; 
        
            case 'hardware':
                $actionlink = base_url('admin/hardwares/hardware/'.$aRow['actionid']);
            break; 
        
            case 'supplier':
                $actionlink = base_url('admin/suppliers/supplier/'.$aRow['actionid']);
            break; 
        
            case 'documentsetting':
                $actionlink = base_url('admin/documentsettings/category/'.$aRow['actionid']);
            break; 
        }
        
        $aRow['action']='';  
        if(stristr($aRow['actiontitle'],'ticket_comment_deleted') //This Condition for Child Information
           || stristr($aRow['actiontitle'],'ticket_attachment_deleted')
                   
           || stristr($aRow['actiontitle'],'todo_comment_deleted') 
           || stristr($aRow['actiontitle'],'todo_document_deleted')
                   
           || stristr($aRow['actiontitle'],'lead_comment_deleted') 
           || stristr($aRow['actiontitle'],'lead_document_deleted')
           || stristr($aRow['actiontitle'],'lead_reminder_deleted')
                   
           || stristr($aRow['actiontitle'],'customer_comment_deleted') 
           || stristr($aRow['actiontitle'],'customer_document_deleted')
           || stristr($aRow['actiontitle'],'customer_reminder_deleted')
                   
           || stristr($aRow['actiontitle'],'quotation_comment_deleted') 
           || stristr($aRow['actiontitle'],'quotation_document_deleted')
           || stristr($aRow['actiontitle'],'quotation_reminder_deleted')
                   
           || stristr($aRow['actiontitle'],'assignment_comment_deleted') 
           || stristr($aRow['actiontitle'],'assignment_legitimation_deleted')
           || stristr($aRow['actiontitle'],'assignment_document_deleted')
           || stristr($aRow['actiontitle'],'assignment_reminder_deleted')
           || stristr($aRow['actiontitle'],'assignment_invoice_deleted')
                   
           || stristr($aRow['actiontitle'],'hardwareassignment_document_deleted')
           || stristr($aRow['actiontitle'],'hardwareassignment_reminder_deleted')
                   
           || stristr($aRow['actiontitle'],'monitoring_comment_deleted')  
           
           || stristr($aRow['actiontitle'],'userdocument_deleted')  
           || stristr($aRow['actiontitle'],'infodocument_deleted')  
           || stristr($aRow['actiontitle'],'hardwareassignmentposition_document_deleted')          
        ){
            $aRow['action']='<a href="'.$actionlink.'" class="btn btn-sm btn-default btn-circle btn-editable blue" target="_blank"><i class="fa fa-link"></i></a>';  
        }
        else if(stristr($aRow['actiontitle'],'monitoring_imported')){
            $aRow['action']='<a href="'.$actionlink.'" class="btn btn-sm btn-default btn-circle btn-editable blue" target="_blank"><i class="fa fa-link"></i></a>';  
        }
        else if(stristr($aRow['actiontitle'],'deleted') || stristr($aRow['actiontitle'],'imported')){
			
			if($aRow['actionname']=='ratemobile'){
				$actionlink = base_url('admin/ratesmobile/');
			}else if($aRow['actionname']=='ratelandline'){
				$actionlink = base_url('admin/rateslandline/');
			}else{			
	            $actionlink = base_url('admin/'.$aRow['actionname'].'s/');
			}
			
            $aRow['action']='<a href="'.$actionlink.'" class="btn btn-sm btn-default btn-circle btn-editable blue" target="_blank"><i class="fa fa-link"></i></a>';  
        }
        else{
            $aRow['action']='<a href="'.$actionlink.'" class="btn btn-sm btn-default btn-circle btn-editable blue" target="_blank"><i class="fa fa-link"></i></a>';  
        }
        
        $records["data"][] = array(
            $aRow['historienr_prefix'],
            $aRow['fullname'],
            $aRow['userrole'],
            _dt($aRow['created']),
            lang($aRow['actiontitle']),          
            $aRow['action'],
            $aRow['historienr']
        );        
    }

    //Don't remove
    $records["draw"] = $sEcho;
    $records["recordsTotal"] = $iTotalRecords;
    $records["recordsFiltered"] = $iTotalRecords;  

    //echo json_encode($records);  
    $output = $records;
?>