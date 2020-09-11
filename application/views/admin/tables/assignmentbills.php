<?php
    /*
     * Paging
     */
    $aColumns = array(
        "DATE_FORMAT(CONCAT(tblbills.monthyear,'-01'),'%b-%Y') as monthyear",
        'tblbills.invoicenr as invoicenr',
        'tblbills.description as description',
        'tblbills.netamount as netamount',
        'tblbills.invoicefile as invoicefile',
        'tblbills.assignmentnr as assignmentnr',
        'tblbills.billnr as billnr'
    );
    $sIndexColumn  = "billnr";
    $sTable        = 'tblbills';
    $join = array('LEFT JOIN tblassignments ON tblassignments.assignmentnr=tblbills.assignmentnr');

    //$where = do_action('reminders_table_sql_where', array());
    $where = array();
    array_push($where, "AND tblbills.assignmentnr='".$assignmentid."' ");
    if ($filter_invoice_year!='' && $filter_invoice_year!='undefined') {
        array_push($where, "AND DATE_FORMAT(CONCAT(tblbills.monthyear,'-01'),'%Y')='".$filter_invoice_year."'");
    }

    $customOrderBy = ' tblbills.monthyear DESC ';
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

        $aRow['action'].='<a href="'.base_url('uploads/assignments/'.$aRow['assignmentnr'].'/bills/'.$aRow['invoicefile']).'" class="btn btn-sm btn-default red" target="_blank">'
            . '<i class="fa fa-file-pdf-o"></i> '.lang('page_lb_invoice').'</a>';

        if($GLOBALS['a_invoice_permission']['delete']){
            $aRow['action'].='<a href="javascript:void(0);" class="btn btn-sm btn-default btn-circle btn-editable red" onclick="deleteConfirmation('
                . '\''.base_url("admin/assignments/deleteInvoice").'\','
                . '\''.$aRow["billnr"].'\','
                . '\''.lang("page_lb_delete_invoice").'\','
                . '\''.lang("page_lb_delete_invoice_info").'\',\'true\',\''.$aRow["assignmentnr"].'\');">'
                . '<i class="fa fa-remove"></i></a>';
        }

        $records["data"][] = array(
	    $aRow['monthyear'],
            $aRow['invoicenr'],
            $aRow['description'],
            format_money($aRow['netamount'], "&nbsp;".$GLOBALS['currency_data']['currency_symbol']),
            $aRow['action'],
            $aRow['billnr']
        );
    }

    //Don't remove
    $records["draw"] = $sEcho;
    $records["recordsTotal"] = $iTotalRecords;
    $records["recordsFiltered"] = $iTotalRecords;

    //echo json_encode($records);
    $output = $records;
?>