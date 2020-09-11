<?php
	/*
	 * Paging
	 */
	$aColumns = array(
		'leadquotationnr_prefix',
		'tblleads.leadnr_prefix as lead',
		"CONCAT(tblusers.name,' ',tblusers.surname) as fullname",
		'tblquotationstatus.name as leadquotationstatus',
		"tblleadquotations.created as created",
		'leadquotationnr'
	);
	$sIndexColumn  = "leadquotationnr";
	$sTable        = 'tblleadquotations';
	//$join          = array();
	$join = array('LEFT JOIN tblusers ON tblusers.userid=tblleadquotations.responsible',
		'LEFT JOIN tblquotationstatus ON tblquotationstatus.id=tblleadquotations.leadquotationstatus',
		'LEFT JOIN tblleads ON tblleads.leadnr=tblleadquotations.leadnr');

	//$where = do_action('quotations_table_sql_where', array());
	$where = array();

	if(get_user_role()=='customer'){
		array_push($where, "AND tblleadquotations.customer='".get_user_id()."'");
	}
		//- On the Dashboard he should only see Quotations which belongs to the User who is logged in. (Salesman)
	else if(get_user_role()=='user' && isset($GLOBALS['current_user']->userrole) && $GLOBALS['current_user']->userrole==3){
		array_push($where,"AND (tblleadquotations.userid='".get_user_id()."' OR tblleadquotations.responsible='".get_user_id()."') ");
	}

	if ($filter_responsible!='' && $filter_responsible!='undefined') {
		array_push($where, "AND tblleadquotations.responsible='".$filter_responsible."'");
	}
	if ($filter_leadquotationstatus!='' && $filter_leadquotationstatus!='undefined') {
		array_push($where, "AND tblleadquotations.leadquotationstatus='".$filter_leadquotationstatus."'");
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

		if($GLOBALS['leadquotation_permission']['edit']){
			$aRow['action']='<a href="'.base_url('admin/leadquotations/quotation/'.$aRow['leadquotationnr']).'" class="btn btn-sm btn-default btn-circle btn-editable blue"><i class="fa fa-pencil"></i></a>';
		}

		if($GLOBALS['leadquotation_permission']['delete']){
			if(!$GLOBALS['leadquotation_permission']['edit']){ $aRow['action'] = ''; }
			$aRow['action'].='<a href="javascript:void(0);" class="btn btn-sm btn-default btn-circle btn-editable red" onclick="deleteConfirmation('
			. '\''.base_url("admin/leadquotations/delete").'\','
			. '\''.$aRow["leadquotationnr"].'\','
			. '\''.lang("page_lb_delete_quotation").'\','
			. '\''.lang("page_lb_delete_quotation_info").'\');">'
			. '<i class="fa fa-remove"></i></a>';
		}

		if(!$GLOBALS['leadquotation_permission']['edit'] && !$GLOBALS['leadquotation_permission']['delete']){
			$aRow['action'] = lang('access_denied');
		}

		$aRow['action2']='<a href="'.base_url('admin/leadquotations/printquotation/'.$aRow['leadquotationnr']).'" target="_blank" class="btn btn-sm btn-default btn-editable yellow"><i class="fa fa-print"></i> '.lang('page_lb_print_leadquotation').'</a>';
		$aRow['action2'].='<a href="'.base_url('admin/leadquotations/printhardwarequotation/'.$aRow['leadquotationnr']).'" target="_blank" class="btn btn-sm btn-default btn-editable yellow"><i class="fa fa-print"></i> '.lang('page_lb_print_hardware_leadquotation').'</a>';
		$aRow['action2'].='<a href="'.base_url('admin/leadquotations/printconsultationprotocol/'.$aRow['leadquotationnr']).'" target="_blank" class="btn btn-sm btn-default btn-editable yellow"><i class="fa fa-print"></i> '.lang('page_lb_print_consultation_protocol').'</a>';
		$aRow['action2'].='<a href="'.base_url('admin/leadquotations/printinvoiceprotocol/'.$aRow['leadquotationnr']).'" target="_blank" class="btn btn-sm btn-default btn-editable yellow"><i class="fa fa-print"></i> '.lang('page_lb_print_invoice_protocol').'</a>';

		//Quotation Detail
		$aRow['leadquotationnr_prefix'] = '<a href="'.base_url('admin/leadquotations/detail/'.$aRow['leadquotationnr']).'">'.$aRow['leadquotationnr_prefix'].'</a>';

		$records["data"][] = array(
			$aRow['leadquotationnr_prefix'],
			$aRow['lead'],
			$aRow['fullname'],
			$aRow['leadquotationstatus'],
			_dt($aRow['created']),
			$aRow['action'],
			$aRow['leadquotationnr']
		);
		if($aRow['action2']!=""){
			$records["data"][] = array(
				$aRow['action2'],
				'COLSPAN',
				'',
				'',
				'',
				'',
				'',
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