<?php
    /*
     * Paging
     */
    $provider = '';
    if (get_user_role() == 'customer') {
        $ci =& get_instance();
        $new_array = array();
        if ($GLOBALS['current_user']->provider != '') {
            $new_array[] = $GLOBALS['current_user']->provider;
        }
        $query  = ' SELECT provider FROM tblquotations WHERE provider != "" AND customer = ' . get_user_id();
        $query .= ' UNION ';
        $query .= ' SELECT provider FROM tblassignments WHERE provider != "" AND customer = ' . get_user_id();
        $quotations = $ci->db->query($query)->result_array();
        if (count($quotations)) {
            $quotations = array_reduce($quotations, 'array_merge_recursive', array())['provider'];
            if (count($quotations) == 1) {
                $quotations = array($quotations);
            }
            $new_array = array_merge($new_array, $quotations);
        }
        if (count($new_array)) {
            $provider = '("'.implode('","', $new_array).'")';
        }

    }

    $aColumns = array(
        'tblinfodocuments.documenttitle as documenttitle',
        'tblinfodocuments.provider as provider',
        'tblinfodocuments.created as created',
        'tblinfodocuments.documentfile as documentfile',
        'tblinfodocuments.documentnr as documentnr'
    );
    $sIndexColumn  = "documentnr";
    $sTable        = 'tblinfodocuments';
    $join = array();

    //$where = do_action('reminders_table_sql_where', array());
    $where = array();
    if (get_user_role() == 'customer') {
        if ($provider == '') {
            $where[] = " AND tblinfodocuments.provider = '' ";
        } else {
            $where[] = " AND tblinfodocuments.provider IN ".$provider;
        }
    }
    //array_push($where, "AND tblbills.assignmentnr='".$assignmentid."' ");

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

        $aRow['action'] ='<a href="'.base_url('uploads/infodocuments/'.$aRow['documentnr'].'/'.$aRow['documentfile']).'" class="btn btn-sm btn-default red" target="_blank">'
            . '<i class="fa fa-file-pdf-o"></i> '.lang('page_dt_infodocumentfile').'</a>';

        if($GLOBALS['infodocument_permission']['edit']){
            $aRow['action'].='<a href="javascript:void(0);" class="btn btn-sm btn-default btn-circle btn-editable blue" onclick="addeditInfodocumentAjax('
            .'\''.base_url('admin/infodocuments/addInfodocument').'\','
            .'\''.$aRow["documentnr"].'\','
            .'\''.sprintf(lang('page_edit_infodocument'),lang('page_document')).'\',$(this))" >'
            .'<i class="fa fa-pencil"></i></a>';
        }


        if($GLOBALS['infodocument_permission']['delete']){
            $aRow['action'].='<a href="javascript:void(0);" class="btn btn-sm btn-default btn-circle btn-editable red" onclick="deleteConfirmation('
                . '\''.base_url("admin/infodocuments/deleteInfodocument").'\','
                . '\''.$aRow["documentnr"].'\','
                . '\''.lang("page_lb_delete_infodocument").'\','
                . '\''.lang("page_lb_delete_infodocument_info").'\',\'true\');">'
                . '<i class="fa fa-remove"></i></a>';
        }

        $records["data"][] = array(
        $aRow['documenttitle'],
        $aRow['provider'],
            _dt($aRow['created']),
            $aRow['action'],
            $aRow['documentnr']
        );
    }

    //Don't remove
    $records["draw"] = $sEcho;
    $records["recordsTotal"] = $iTotalRecords;
    $records["recordsFiltered"] = $iTotalRecords;

    //echo json_encode($records);
    $output = $records;
?>