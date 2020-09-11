<?php
	$aColumns = array(
		'hardwarebudgetuse_id',
		'budget_for',
		'budget_use_document',
		'total_excluding_vat_use',
		'use_description',
		'date_of_use'
	);
	$sIndexColumn = 'hardwarebudgetuse_id';
	$sTable = 'tblhardwarebudgetnutzen';
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
		if($aRow['budget_use_document'] != '') {
			$bufget_arr = explode('.', $aRow['budget_use_document']);
			if(isset($bufget_arr) && !empty($bufget_arr) && $bufget_arr[1] =='pdf' || $bufget_arr[1] =='PDF') {
				$aRow['budget_use_document'] = '<a href="'.base_url().'uploads/hardware_budget_document_use/'.$aRow['hardwarebudgetuse_id'].'/'.$aRow['budget_use_document'].'" target="_blank"><img src="'.base_url().'assets/pdf.png"   height="35px"></a>';
			} else {
				$aRow['budget_use_document'] = '<a href="'.base_url().'uploads/hardware_budget_document_use/'.$aRow['hardwarebudgetuse_id'].'/'.$aRow['budget_use_document'].'" target="_blank"><img src="'.base_url().'uploads/hardware_budget_document_use/'.$aRow['hardwarebudgetuse_id'].'/'.$aRow['budget_use_document'].'" height="35px"></a>';
			}
		} else {
			$aRow['budget_use_document'] = '';
		}

		$records['data'][] = array(
			$aRow['hardwarebudgetuse_id'],
			$aRow['budget_for'],
			$aRow['budget_use_document'],
			format_money($aRow['total_excluding_vat_use']) .' &euro;',
			$aRow['use_description'],
			date('d.m.Y', strtotime($aRow['date_of_use'])),
		);
	}

	// Don't remove
	$records['draw'] = $sEcho;
	$records['recordsTotal'] = $iTotalRecords;
	$records['recordsFiltered'] = $iTotalRecords;

	$output = $records;
