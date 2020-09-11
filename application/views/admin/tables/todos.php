<?php
    /*
     * Paging
     */
    $aColumns = array(
        'todonr_prefix',
        'todotitle',
        /*'tbltodos.company as company',*/
        'tblcustomers.company as company',
        'tbltodostatus.name as todostatus',
        "CONCAT(tblusers.name,' ',tblusers.surname) as fullname",
        'todonr'
    );
    $sIndexColumn  = "todonr";
    $sTable        = 'tbltodos';
    //$join          = array();
    $join = array('LEFT JOIN tblusers ON tblusers.userid=tbltodos.responsible',
    'LEFT JOIN tbltodostatus ON tbltodostatus.id=tbltodos.todostatus',
    'LEFT JOIN tblcustomers ON tblcustomers.customernr=tbltodos.customer');

    //$where = do_action('todos_table_sql_where', array());
    $where = array();

    //Check If POS User They have access only own records
    //- On the Dashboard he should only see TODOs which belongs to the User who is logged in. (Salesman and Supporter)
    //I have add a new Todo from admin account. And choose responsive user "Koc Mansur". Now Im logged in as mansurkoc, but I cant see the Todo under Menu Todo and not on the Dashboard.
    if ($GLOBALS['current_user']->userrole != 1) {
        array_push($where, "AND (FIND_IN_SET(".get_user_id().", tbltodos.teamwork) != 0 OR tbltodos.responsible='".get_user_id()."') ");
    }
    /*if(get_user_role()=='user' && isset($GLOBALS['current_user']->userrole) && ($GLOBALS['current_user']->userrole==3 || $GLOBALS['current_user']->userrole==5)){
        array_push($where, "AND (tbltodos.userid='".get_user_id()."' OR tbltodos.responsible='".get_user_id()."') ");
    }*/

	if ($filter_responsible!='' && $filter_responsible!='undefined') {
            array_push($where, "AND tbltodos.responsible='".$filter_responsible."'");
	}
        if ($filter_todostatus!='' && $filter_todostatus!='undefined') {
            array_push($where, "AND tbltodos.todostatus='".$filter_todostatus."'");
	}

    // 1 = Erstellt, 2 = Begonnen, 3 = Erledigt
    $customOrderBy = ' tbltodos.todonr DESC, tbltodos.todostatus = 1 DESC, tbltodos.todostatus = 2 DESC, tbltodos.todostatus = 3 DESC ';
    $result  = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where, $additionalSelect = array(), $sGroupBy = '', $customOrderBy);

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

        if($GLOBALS['todo_permission']['edit']){
            $aRow['action']='<a href="'.base_url('admin/todos/todo/'.$aRow['todonr']).'" class="btn btn-sm btn-default btn-circle btn-editable blue"><i class="fa fa-pencil"></i></a>';
        }

        if($GLOBALS['todo_permission']['delete']){
            if(!$GLOBALS['todo_permission']['edit']){ $aRow['action'] = ''; }
            $aRow['action'].='<a href="javascript:void(0);" class="btn btn-sm btn-default btn-circle btn-editable red" onclick="deleteConfirmation('
                    . '\''.base_url("admin/todos/delete").'\','
                    . '\''.$aRow["todonr"].'\','
                    . '\''.lang("page_lb_delete_todo").'\','
                    . '\''.lang("page_lb_delete_todo_info").'\');">'
                    . '<i class="fa fa-remove"></i></a>';
        }

        if(!$GLOBALS['todo_permission']['edit'] && !$GLOBALS['todo_permission']['delete']){
            $aRow['action'] = lang('access_denied');
        }

        //Lead Detail
        $aRow['todonr_prefix'] = '<a href="'.base_url('admin/todos/detail/'.$aRow['todonr']).'">'.$aRow['todonr_prefix'].'</a>';

        $records["data"][] = array(
            $aRow['todonr_prefix'],
            $aRow['todotitle'],
            $aRow['company'],
            $aRow['todostatus'],
            $aRow['fullname'],
            $aRow['action'],
            $aRow['todonr']
        );
    }

    //Don't remove
    $records["draw"] = $sEcho;
    $records["recordsTotal"] = $iTotalRecords;
    $records["recordsFiltered"] = $iTotalRecords;

    //echo json_encode($records);
    $output = $records;
?>