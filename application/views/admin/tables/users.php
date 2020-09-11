<?php     
    /* 
     * Paging
     */
    $aColumns = array(        
        'userthumb',
        'userid_prefix',        
        'username',        
        '(SELECT name FROM tblroles WHERE roleid=tblusers.userrole) as userrole',        
        'email',
        'last_login',
        'active',
        'userid'
    );
    $sIndexColumn  = "userid";
    $sTable        = 'tblusers';
    $join          = array();    
    $where = do_action('user_table_sql_where', array());    
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
    /*for($i = $iDisplayStart; $i < $end; $i++) {
        $records["data"][] = array('1','2','3','4','5');
    }*/
       
    foreach ($rResult as $aRow) {
        
        $aRow['action'] = lang('access_denied');
        if($GLOBALS['user_permission']['edit']){
            $aRow['action']='<a href="'.base_url('admin/users/user/'.$aRow['userid']).'" class="btn btn-sm btn-default btn-circle btn-editable blue"><i class="fa fa-pencil"></i></a>';  
        }    
        
        //Self user can't delete account
        if(get_user_id()!=$aRow['userid'] && $GLOBALS['user_permission']['delete']){            
            if(!$GLOBALS['user_permission']['edit']){ $aRow['action'] = ''; }
            $aRow['action'].='<a href="javascript:void(0);" class="btn btn-sm btn-default btn-circle btn-editable red" onclick="deleteConfirmation('
                    . '\''.base_url("admin/users/delete").'\','
                    . '\''.$aRow["userid"].'\','
                    . '\''.lang("page_lb_delete_user").'\','
                    . '\''.lang("page_lb_delete_user_info").'\');">'
                    . '<i class="fa fa-remove"></i></a>';        			
        }    
        else{
            if(get_user_id()==$aRow['userid'] && !$GLOBALS['user_permission']['edit']){
                $aRow['action'] = '';
            }
        }
        
        //Last Login
        if ($aRow['last_login']!=null) {
            $last_login = '<span class="text-has-action" data-toggle="tooltip" data-title="'._dt($aRow['last_login']).'">'.time_ago($aRow['last_login']).'</span>';
        } else {
            $last_login = 'Never';
        }
        
        //Master Admin Can't Inactive
        $readonly = ($aRow['userid']==1)?' readonly':'';     
        if($aRow['userid']==1){
            $langtext = lang('master_admin_can_never_inactive');
        }else{
            $langtext = lang('page_user_active_inactive_help');
        }
        
        $checked = ($aRow['active']==1)?' checked':'';         
        $aRow['active'] = '<div class="onoffswitch" data-toggle="tooltip" data-title="'.$langtext.'"><input '.$readonly.' '.$checked.' type="checkbox" data-switch-url="'.base_url('admin/users/change_active').'" data-id="'.$aRow['userid'].'" class="make-switch" data-on-text="'.lang('page_lb_yes').'" data-off-text="'.lang('page_lb_no').'" data-on-color="primary" data-off-color="danger" data-size="small"></div>';
                
        $records["data"][] = array(	    
            user_profile_image($aRow['userid'],array('user-profile-image-small img-circle'),'small'),            
            $aRow['userid_prefix'],
            $aRow['username'],
            $aRow['userrole'],            
            $aRow['email'],
            $last_login,
            $aRow['active'],
            $aRow['action'],
            $aRow['userid']
        );        
    }

    //Don't remove
    $records["draw"] = $sEcho;
    $records["recordsTotal"] = $iTotalRecords;
    $records["recordsFiltered"] = $iTotalRecords;  

    //echo json_encode($records);  
    $output = $records;
?>