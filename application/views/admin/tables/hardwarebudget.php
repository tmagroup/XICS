<?php
	$aColumns = array(
		'hardwarebudget_id',
		'provider',
		'budget_document',
		'total_excluding_vat',
		'date_of_expiry'
	);
	$sIndexColumn = 'hardwarebudget_id';
	$sTable = 'tblhardwarebudget';
	$join = array();
	$where = array();
	$result = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where);

	// $CI =& get_instance();
	// print_r($CI->db->last_query()); exit(0);

	$output = $result['output'];
	$rResult = $result['rResult'];

	$iTotalRecords = $output['iTotalRecords'];
	$iDisplayLength = (isset($_REQUEST['length'])) ? intval($_REQUEST['length']) : 0;
	$iDisplayLength = ($iDisplayLength < 0) ? $iTotalRecords : $iDisplayLength;
	$iDisplayStart = (isset($_REQUEST['start'])) ? intval($_REQUEST['start']) : 0;
	$sEcho = (isset($_REQUEST['draw'])) ? intval($_REQUEST['draw']) : 0;
	$records = array();
	$records['data'] = array();

	// Don't remove
	$end = $iDisplayStart + $iDisplayLength;
	$end = $end > $iTotalRecords ? $iTotalRecords : $end;

	// Data Loop
	foreach ($rResult as $aRow) {
		$bufget_arr = explode('.', $aRow['budget_document']);
		if($aRow['budget_document'] != '') {
			if(isset($bufget_arr) && !empty($bufget_arr) && $bufget_arr[1] =='pdf' || $bufget_arr[1] =='PDF') {
				$aRow['budget_document'] = '<a href="'.base_url().'uploads/hardware_budget_document/'.$aRow['hardwarebudget_id'].'/'.$aRow['budget_document'].'" target="_blank"><img src="'.base_url().'assets/pdf.png" height="35px"></a>';
			} else {
				$aRow['budget_document'] = '<a href="'.base_url().'uploads/hardware_budget_document/'.$aRow['hardwarebudget_id'].'/'.$aRow['budget_document'].'" target="_blank"><img src="'.base_url().'uploads/hardware_budget_document/'.$aRow['hardwarebudget_id'].'/'.$aRow['budget_document'].'" height="35px"></a>';
			}
		} else {
			$aRow['budget_document'] = '';
		}

		$records['data'][] = array(
			$aRow['hardwarebudget_id'],
			$aRow['provider'],
			$aRow['budget_document'],
			format_money($aRow['total_excluding_vat']) .' &euro;',
			date('d.m.Y', strtotime($aRow['date_of_expiry'])),
		);
	}

	// Don't remove
	$records['draw'] = $sEcho;
	$records['recordsTotal'] = $iTotalRecords;
	$records['recordsFiltered'] = $iTotalRecords;

	$output = $records;
