<?php
    /*
     * Paging
     */
    $aColumns = array(
        'tblleadstatus.name as leadstatus',
        'tblleadstatus.color as color',
        'leadnr_prefix',
        "CONCAT(tblusers.name,' ',tblusers.surname) as fullname",
        'tblleadsources.name as leadsource',
        'tblleads.company as company',
        'tblleads.city as city',
        'tblleads.name as name',
        'tblleads.surname as surname',
        'tblleads.phone as phone',
        'tblleads.mobilnr as mobilnr',
        'tblleads.provider as provider',
        'tblleadproducts.name as product',
        'leadnr',
    );
    $sIndexColumn  = "leadnr";
    $sTable        = 'tblleads';
    //$join          = array();
    $join = array('LEFT JOIN tblusers ON tblusers.userid=tblleads.responsible',
    'LEFT JOIN tblleadstatus ON tblleadstatus.id=tblleads.leadstatus',
    'LEFT JOIN tblleadsources ON tblleadsources.id=tblleads.leadsource',
    'LEFT JOIN tblleadproducts ON tblleadproducts.id=tblleads.product',
    );

    //$where = do_action('leads_table_sql_where', array());
        $where = array();

        //Check If POS User They have access only own records
        //- On the Dashboard he should only see Leads which belongs to the User who is logged in. (Salesman and Supporter and POS) || $GLOBALS['current_user']->userrole==5
        if(get_user_role()=='user' && isset($GLOBALS['current_user']->userrole) && ($GLOBALS['current_user']->userrole==3 || $GLOBALS['current_user']->userrole==6)){
            array_push($where, "AND (tblleads.userid='".get_user_id()."' OR tblleads.responsible='".get_user_id()."') ");
        }

	if ($filter_responsible!='' && $filter_responsible!='undefined') {
            array_push($where, "AND tblleads.responsible='".$filter_responsible."' ");
	}
        if ($filter_leadstatus!='' && $filter_leadstatus!='undefined') {
            array_push($where, "AND tblleads.leadstatus='".$filter_leadstatus."' ");
	}
        if ($filter_leadproduct!='' && $filter_leadproduct!='undefined') {
            array_push($where, "AND tblleads.product='".$filter_leadproduct."' ");
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

        if($GLOBALS['lead_permission']['edit']){
            $aRow['action']='<a href="'.base_url('admin/leads/lead/'.$aRow['leadnr']).'" class="btn btn-sm btn-default btn-circle btn-editable blue"><i class="fa fa-pencil"></i></a>';
        }

        if($GLOBALS['lead_permission']['delete']){
            if(!$GLOBALS['lead_permission']['edit']){ $aRow['action'] = ''; }
            $aRow['action'].='<a href="javascript:void(0);" class="btn btn-sm btn-default btn-circle btn-editable red" onclick="deleteConfirmation('
                    . '\''.base_url("admin/leads/delete").'\','
                    . '\''.$aRow["leadnr"].'\','
                    . '\''.lang("page_lb_delete_lead").'\','
                    . '\''.lang("page_lb_delete_lead_info").'\');">'
                    . '<i class="fa fa-remove"></i></a>';
        }

        if(!$GLOBALS['lead_permission']['edit'] && !$GLOBALS['lead_permission']['delete']){
            $aRow['action'] = lang('access_denied');
        }

        //Lead Detail
        $aRow['leadnr_prefix'] = '<a href="'.base_url('admin/leads/detail/'.$aRow['leadnr']).'">'.$aRow['leadnr_prefix'].'</a>';

        if($GLOBALS['lead_permission']['delete']){
            $records["data"][] = array(
                '<input type="checkbox" name="delete_leadnr[]" class="checkboxes" value="'.$aRow['leadnr'].'" />',
                $aRow['leadnr_prefix'],
                $aRow['leadstatus'],
                $aRow['color'],
                $aRow['fullname'],
                $aRow['leadsource'],
                $aRow['company'],
                $aRow['city'],
                $aRow['name'],
                $aRow['surname'],
                $aRow['phone'],
                $aRow['mobilnr'],
                $aRow['provider'],
                $aRow['product'],
                $aRow['action'],
                $aRow['leadnr']
            );
        }else{
            $records["data"][] = array(
                $aRow['leadnr_prefix'],
                $aRow['leadstatus'],
                $aRow['color'],
                $aRow['fullname'],
                $aRow['leadsource'],
                $aRow['company'],
                $aRow['city'],
                $aRow['name'],
                $aRow['surname'],
                $aRow['phone'],
                $aRow['mobilnr'],
                $aRow['provider'],
                $aRow['product'],
                $aRow['action'],
                $aRow['leadnr'],
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