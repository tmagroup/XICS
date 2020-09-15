<?php
$subcustomer_where = '';
if ( $GLOBALS['current_user']->parent_customer_id > 0 ) {
	$subcustomer_where = ' tblfiles.rel_id = '. $GLOBALS['current_user']->parent_customer_id .' ';
}

	/*
	 * Paging
	 */
	$aColumns = array(
		'tbldocumentsettings.categoryname as categoryname',
		"file_name",
		"IF(rel_type='customerdocument',(SELECT CONCAT(name,' ',surname) FROM tblcustomers WHERE customernr=tblfiles.rel_id),(SELECT CONCAT(name,' ',surname) FROM tblusers WHERE userid=tblfiles.rel_id)) as uploaded_by",
		"created",
		'filetype',
		'id'
	);
	$sIndexColumn  = "id";
	$sTable        = 'tblfiles';
	//$join          = array();
	$join = array('LEFT JOIN tbldocumentsettings ON tbldocumentsettings.categoryid=tblfiles.categoryid');

	//$where = do_action('assignments_table_sql_where', array());
	$where = array();
	if(get_user_role()=='customer'){
		array_push($where, "AND tblfiles.rel_id='".get_user_id()."'");
		array_push($where, "AND tblfiles.rel_type='customerdocument'");

		if ( !empty($subcustomer_where) ) {
			array_push($where, "OR ( ". $subcustomer_where ." )");
		}
	}else{
		if($GLOBALS['current_user']->userrole==1 || $GLOBALS['current_user']->userrole==2){
			array_push($where, "AND tblfiles.rel_type='userdocument'");
		}else{
			array_push($where, "AND tblfiles.rel_id='".get_user_id()."'");
			array_push($where, "AND tblfiles.rel_type='userdocument'");
		}
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

		if($GLOBALS['document_permission']['delete']){
			$aRow['action'].='<a href="javascript:void(0);" class="btn btn-sm btn-default btn-circle btn-editable red" onclick="deleteConfirmation('
					. '\''.base_url("admin/documents/deleteDocument").'\','
					. '\''.$aRow["id"].'\','
					. '\''.lang("page_lb_delete_document").'\','
					. '\''.lang("page_lb_delete_document_info").'\',\'true\');">'
					. '<i class="fa fa-remove"></i></a>';
		}
		/*if(!$GLOBALS['document_permission']['delete']){
			$aRow['action'] = lang('access_denied');
		}*/

		$attachment_url = base_url('admin/documents/downloadDocument/'.$aRow['id']);
		$aRow['file_name'] = '<a href="'.$attachment_url.'"><div class="pull-left"><i class="'.get_mime_class($aRow['filetype']).'"></i></div> '.$aRow['file_name'].'</a>';

		if($GLOBALS['document_permission']['delete']){
			$records["data"][] = array(
				$aRow['categoryname'],
				$aRow['file_name'],
				$aRow['uploaded_by'],
				_dt($aRow['created']),
				$aRow['action'],
				$aRow['id']
			);
		}else{
			$records["data"][] = array(
				$aRow['categoryname'],
				$aRow['file_name'],
				$aRow['uploaded_by'],
				_dt($aRow['created']),
				$aRow['id']
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
