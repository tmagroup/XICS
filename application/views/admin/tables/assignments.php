<?php
// $subcustomer_where = '';
// if ( $GLOBALS['current_user']->parent_customer_id > 0 ) {
// 	// $subcustomer_where = ' tblassignments.customer = '. $GLOBALS['current_user']->parent_customer_id .' OR tblassignments.responsible = '. $GLOBALS['current_user']->parent_customer_id .' ';
// 	$subcustomer_where = ' tblassignments.customer = '. $GLOBALS['current_user']->parent_customer_id .' ';
// }

	/*
	 * Paging
	 */
	$aColumns = array(
		'assignmentnr_prefix',
		'tblprovider.image as provider',
		'tblcustomers.company as company',
		"CONCAT(tblusers.name,' ',tblusers.surname) as fullname",
		'tblassignmentstatus.name as assignmentstatus',
		"tblassignments.created as created",
		'assignmentnr'
	);

	$sIndexColumn  = "assignmentnr";
	$sTable        = 'tblassignments';
	//$join          = array();
	$join = array('LEFT JOIN tblusers ON tblusers.userid=tblassignments.responsible',
	'LEFT JOIN tblassignmentstatus ON tblassignmentstatus.id=tblassignments.assignmentstatus',
	'LEFT JOIN tblprovider ON tblprovider.name=tblassignments.provider',
	'LEFT JOIN tblcustomers ON tblcustomers.customernr=tblassignments.customer');


	//$where = do_action('assignments_table_sql_where', array());
		$where = array();
		if(get_user_role()=='customer'){
			$subcustomer_where = '';
			if ( $GLOBALS['current_user']->parent_customer_id > 0 ) {
				// $subcustomer_where = ' tblassignments.customer = '. $GLOBALS['current_user']->parent_customer_id .' OR tblassignments.responsible = '. $GLOBALS['current_user']->parent_customer_id .' ';
				$subcustomer_where = ' tblassignments.customer = '. $GLOBALS['current_user']->parent_customer_id .' OR tblassignments.userid = '. $GLOBALS['current_user']->parent_customer_id .' OR tblassignments.userid = '. get_user_id() .' ';
			}

			if ( empty($subcustomer_where) ) {
				array_push($where, "AND tblassignments.customer='".get_user_id()."'");
			} else {
				array_push($where, "AND tblassignments.customer='".get_user_id()."' OR ( ". $subcustomer_where ." )");
			}
		}
		//- On the Dashboard he should only see Assignments which belongs to the User who is logged in. (Salesman)
		else if(get_user_role()=='user' && isset($GLOBALS['current_user']->userrole) && $GLOBALS['current_user']->userrole==3){
			array_push($where,"AND (tblassignments.userid='".get_user_id()."' OR tblassignments.responsible='".get_user_id()."') ");
		}
		//- He can see only Assignment where the POS was choosen.
		else if(get_user_role()=='user' && isset($GLOBALS['current_user']->userrole) && $GLOBALS['current_user']->userrole==6){
			array_push($where,"AND tblassignments.recommend='".get_user_id()."' ");
		}

	if ($filter_responsible!='' && $filter_responsible!='undefined') {
			array_push($where, "AND tblassignments.responsible='".$filter_responsible."'");
	}
		if ($filter_assignmentstatus!='' && $filter_assignmentstatus!='undefined') {
			array_push($where, "AND tblassignments.assignmentstatus='".$filter_assignmentstatus."'");
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

		if($GLOBALS['assignment_permission']['edit']){
			$aRow['action']='<a href="'.base_url('admin/assignments/assignment/'.$aRow['assignmentnr']).'" class="btn btn-sm btn-default btn-circle btn-editable blue"><i class="fa fa-pencil"></i></a>';
		}

		if($GLOBALS['assignment_permission']['delete']){
			if(!$GLOBALS['assignment_permission']['edit']){ $aRow['action'] = ''; }
			$aRow['action'].='<a href="javascript:void(0);" class="btn btn-sm btn-default btn-circle btn-editable red" onclick="deleteConfirmation('
					. '\''.base_url("admin/assignments/delete").'\','
					. '\''.$aRow["assignmentnr"].'\','
					. '\''.lang("page_lb_delete_assignment").'\','
					. '\''.lang("page_lb_delete_assignment_info").'\');">'
					. '<i class="fa fa-remove"></i></a>';
		}
		if(!$GLOBALS['assignment_permission']['edit'] && !$GLOBALS['assignment_permission']['delete']){
			$aRow['action'] = lang('access_denied');
		}

		//Assignment Detail
		$aRow['assignmentnr_prefix'] = '<a href="'.base_url('admin/assignments/detail/'.$aRow['assignmentnr']).'">'.$aRow['assignmentnr_prefix'].'</a>';

		if($aRow['provider'] != '') {
			$aRow['provider_logo'] = '<img class="img-thumbnail" src="'.base_url().$aRow['provider'].'" style="height:40px !important;border:none !important;">';
		} else {
			$aRow['provider_logo'] = '';
		}

		$records["data"][] = array(
			$aRow['assignmentnr_prefix'],
			$aRow['provider_logo'],
			$aRow['company'],
			$aRow['fullname'],
			$aRow['assignmentstatus'],
			_dt($aRow['created']),
			$aRow['action'],
			$aRow['assignmentnr']
		);
	}

	//Don't remove
	$records["draw"] = $sEcho;
	$records["recordsTotal"] = $iTotalRecords;
	$records["recordsFiltered"] = $iTotalRecords;

	//echo json_encode($records);
	$output = $records;
?>
