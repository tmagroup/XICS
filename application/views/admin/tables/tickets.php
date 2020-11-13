<?php
// $subcustomer_where = '';
// if ( $GLOBALS['current_user']->parent_customer_id > 0 ) {
// 	$subcustomer_where = ' tbltickets.userid = '. $GLOBALS['current_user']->parent_customer_id .' OR tbltickets.responsible = '. $GLOBALS['current_user']->parent_customer_id .' ';
// }

	/*
	 * Paging
	 */
	$aColumns = array(
		'ticketnr_prefix',
		'tickettitle',
		/*'tbltickets.company as company',*/
		'tblcustomers.company as company',
		'tblticketstatus.name as ticketstatus',
		"CONCAT(tblusers.name,' ',tblusers.surname) as fullname",
		"tbltickets.created as created",
		'ticketnr',
	);
	$sIndexColumn  = "ticketnr";
	$sTable        = 'tbltickets';
	//$join          = array();
	$join = array('LEFT JOIN tblusers ON tblusers.userid=tbltickets.responsible',
	'LEFT JOIN tblticketstatus ON tblticketstatus.id=tbltickets.ticketstatus',
	'LEFT JOIN tblcustomers ON tblcustomers.customernr=tbltickets.customer');

	//$where = do_action('tickets_table_sql_where', array());
		$where = array();
		if(get_user_role()=='customer'){
			$subcustomer_where = '';
			if ( $GLOBALS['current_user']->parent_customer_id > 0 ) {
				// $subcustomer_where = ' tbltickets.userid = '. $GLOBALS['current_user']->parent_customer_id .' OR tbltickets.responsible = '. $GLOBALS['current_user']->parent_customer_id .' ';
				$subcustomer_where = ' tbltickets.customer = '. $GLOBALS['current_user']->parent_customer_id .' OR tbltickets.userid = '. $GLOBALS['current_user']->parent_customer_id .' OR tbltickets.userid = '. get_user_id() .' '.' OR tbltickets.responsible  = '. $GLOBALS['current_user']->parent_customer_id .' ';
			}
			if ( empty($subcustomer_where) ) {
				array_push($where,"AND (tbltickets.userid='".get_user_id()."' AND tbltickets.userrole='customer') OR (tbltickets.customer='".get_user_id()."' AND tbltickets.userrole='user') ");
			} else {
				array_push($where,"AND (tbltickets.userid='".get_user_id()."' AND tbltickets.userrole='customer') OR (tbltickets.customer='".get_user_id()."' AND tbltickets.userrole='user') OR (( ". $subcustomer_where ." ) AND tbltickets.userrole = 'customer') ");
			}
		}
		//- On the Dashboard he should only see Tickets which belongs to the User who is logged in. (Salesman and Supporter and POS)
		else if(get_user_role()=='user' && isset($GLOBALS['current_user']->userrole) && ($GLOBALS['current_user']->userrole==3 || $GLOBALS['current_user']->userrole==5 || $GLOBALS['current_user']->userrole==6 || $GLOBALS['current_user']->userrole==8)){
			array_push($where,"AND ((tbltickets.userid='".get_user_id()."' OR tbltickets.responsible='".get_user_id()."') AND tbltickets.userrole='user') OR ((tbltickets.userid='".get_user_id()."' OR tbltickets.responsible='".get_user_id()."') AND tbltickets.userrole='customer') ");
		}

	if ($filter_responsible!='' && $filter_responsible!='undefined') {
			array_push($where, "AND tbltickets.responsible='".$filter_responsible."' ");
	}
	if ($filter_ticketstatus!='' && $filter_ticketstatus!='undefined') {
			array_push($where, "AND tbltickets.ticketstatus='".$filter_ticketstatus."' ");
	}
	if ($filter_ticketstatus!='4') {
		array_push($where, "AND tbltickets.ticketstatus!=4");
	}

	// 1 = Offen, 2 = Bearbeitung, 3 = Warte auf Zuarbeit
	$customOrderBy = ' tbltickets.responsible = '.get_user_id().' DESC, tbltickets.ticketstatus = 1 DESC, tbltickets.ticketstatus = 2 DESC, tbltickets.ticketstatus = 3 DESC ';
	// data_tables_init($aColumns, $sIndexColumn, $sTable, $join = array(), $where = array(), $additionalSelect = array(), $sGroupBy = '')
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

		if($GLOBALS['ticket_permission']['edit']){
			$aRow['action']='<a href="'.base_url('admin/tickets/ticket/'.$aRow['ticketnr']).'" class="btn btn-sm btn-default btn-circle btn-editable blue"><i class="fa fa-pencil"></i></a>';
		}

		if($GLOBALS['ticket_permission']['delete']){
			if(!$GLOBALS['ticket_permission']['edit']){ $aRow['action'] = ''; }
			$aRow['action'].='<a href="javascript:void(0);" class="btn btn-sm btn-default btn-circle btn-editable red" onclick="deleteConfirmation('
					. '\''.base_url("admin/tickets/delete").'\','
					. '\''.$aRow["ticketnr"].'\','
					. '\''.lang("page_lb_delete_ticket").'\','
					. '\''.lang("page_lb_delete_ticket_info").'\');">'
					. '<i class="fa fa-remove"></i></a>';
		}

		if(!$GLOBALS['ticket_permission']['edit'] && !$GLOBALS['ticket_permission']['delete']){
			$aRow['action'] = lang('access_denied');
		}

		//Lead Detail
		$aRow['ticketnr_prefix'] = '<a href="'.base_url('admin/tickets/detail/'.$aRow['ticketnr']).'">'.$aRow['ticketnr_prefix'].'</a>';

		$records["data"][] = array(
			$aRow['ticketnr_prefix'],
			$aRow['tickettitle'],
			$aRow['company'],
			$aRow['ticketstatus'],
			$aRow['fullname'],
			_dt($aRow['created']),
			$aRow['action'],
			$aRow['ticketnr']
		);
	}

	//Don't remove
	$records["draw"] = $sEcho;
	$records["recordsTotal"] = $iTotalRecords;
	$records["recordsFiltered"] = $iTotalRecords;

	//echo json_encode($records);
	$output = $records;
?>
