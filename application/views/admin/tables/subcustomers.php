<?php
	/*
	 * Paging
	 */
	$aColumns = array(
		'userthumb',
		'tcc.customernr_prefix',
		"CONCAT(tblusers.name,' ',tblusers.surname) as fullname",
		'tblcustomers.company as company',
		'tblcustomers.city as city',
		'tcc.name as name',
		'tcc.surname as surname',
		'tcc.phone as phone',
		'tcc.mobilnr as mobilnr',
		'tcc.active as active',
		'tcc.customernr'
	);
	$sIndexColumn  = "customernr";
	$sTable        = 'tblcustomers';
	//$join          = array();
	$join = array(
		'LEFT JOIN tblusers ON tblusers.userid=tblcustomers.responsible',
		'INNER JOIN tblcustomers AS tcc ON tcc.parent_customer_id = tblcustomers.customernr
			AND tcc.parent_customer_id IS NOT NULL
			AND tcc.parent_customer_id != 0',
	);

	//$where = do_action('customers_table_sql_where', array());
	$where = array();
	if($filter_responsible!='' && $filter_responsible!='undefined') {
			array_push($where, "AND tblcustomers.responsible='".$filter_responsible."'");
	}
	array_push($where, " AND tcc.parent_customer_id = ". $GLOBALS['current_user']->customernr);

	$result  = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where);

	// $CI =& get_instance();
	// print_r($CI->db->last_query()); exit(0);

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
		$aRow['action'] = '';
		if ( get_user_role() != 'customer' || (get_user_role() === 'customer' && $GLOBALS['current_user']->parent_customer_id == 0)) {
			$aRow['action']='<a href="'.base_url('admin/subcustomers/customer/'.$aRow['customernr']).'" class="btn btn-sm btn-default btn-circle btn-editable blue"><i class="fa fa-pencil"></i></a>';
		}

		if ( get_user_role() != 'customer' || (get_user_role() === 'customer' && $GLOBALS['current_user']->parent_customer_id == 0)) {
			$aRow['action'].='<a href="javascript:void(0);" class="btn btn-sm btn-default btn-circle btn-editable red" onclick="deleteConfirmation('
					. '\''.base_url("admin/subcustomers/delete").'\','
					. '\''.$aRow["customernr"].'\','
					. '\''.lang("page_lb_delete_customer").'\','
					. '\''.lang("page_lb_delete_customer_info").'\');">'
					. '<i class="fa fa-remove"></i></a>';
		}

		if ( get_user_role() != 'customer' || (get_user_role() === 'customer' && $GLOBALS['current_user']->parent_customer_id > 0)) {
			$aRow['action'] = lang('access_denied');
		}

		//- Under customer he cant acitive and deactive a user. (Salesman)
		if ( get_user_role() != 'customer' || (get_user_role() === 'customer' && $GLOBALS['current_user']->parent_customer_id > 0)) {
			$aRow['active'] = ($aRow['active']==1)?lang('page_lb_yes'):lang('page_lb_no');
		}
		else{
			$langtext = lang('page_user_active_inactive_help');
			$checked = ($aRow['active']==1)?' checked':'';
			$aRow['active'] = '<div class="onoffswitch" data-toggle="tooltip" data-title="'.$langtext.'"><input '.$checked.' type="checkbox" data-switch-url="'.base_url('admin/subcustomers/change_active').'" data-id="'.$aRow['customernr'].'" class="make-switch" data-on-text="'.lang('page_lb_yes').'" data-off-text="'.lang('page_lb_no').'" data-on-color="primary" data-off-color="danger" data-size="small"></div>';
		}

		//Lead Detail
		$aRow['customernr_prefix'] = '<a href="'.base_url('admin/subcustomers/detail/'.$aRow['customernr']).'">'.$aRow['customernr_prefix'].'</a>';

		$records["data"][] = array(
			customer_profile_image($aRow['customernr'],array('user-profile-image-small img-circle'),'small'),
			$aRow['customernr_prefix'],
			$aRow['fullname'],
			$aRow['company'],
			$aRow['city'],
			$aRow['name'],
			$aRow['surname'],
			$aRow['phone'],
			$aRow['mobilnr'],
			$aRow['active'],
			$aRow['action'],
			$aRow['customernr']
		);
	}

	//Don't remove
	$records["draw"] = $sEcho;
	$records["recordsTotal"] = $iTotalRecords;
	$records["recordsFiltered"] = $iTotalRecords;

	//echo json_encode($records);
	$output = $records;
?>
