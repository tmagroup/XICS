<?php
    /*
     * Paging
     */
    $aColumns = array(
        'monitoringnr_prefix',
        'tblmonitorings.date as date',
        'tblmonitorings.company as company',
        'tblmonitoringstatus.name as monitoringstatus',
        'tblassignments.assignmentnr_prefix as assignmentnr_prefix',
        'tblmonitorings.monitoringlink as monitoringlink',
        'tblmonitorings.monitoringuser as monitoringuser',
        'tblmonitorings.monitoringpass as monitoringpass',
        'tblmonitorings.assignmentnr as assignmentnr',
        'monitoringnr',
        'monitoringstatus as monitoringstatusid',
        '(SELECT COUNT(*) FROM tblmonitoringassignments WHERE monitoringnr=tblmonitorings.monitoringnr) as compare1',
        '(SELECT COUNT(*) FROM tblmonitoringassignments WHERE monitoringnr=tblmonitorings.monitoringnr and (!ISNULL(costincurredby) OR costincurredby!=0)) as compare2',
    );
    $sIndexColumn  = "monitoringnr";
    $sTable        = 'tblmonitorings';
    //$join          = array();
    $join = array('LEFT JOIN tblcustomers ON tblcustomers.customernr=tblmonitorings.customer',
    'LEFT JOIN tblmonitoringstatus ON tblmonitoringstatus.id=tblmonitorings.monitoringstatus',
    'LEFT JOIN tblassignments ON tblassignments.assignmentnr=tblmonitorings.assignmentnr'
    );

    //$where = do_action('monitorings_table_sql_where', array());
        $where = array();

        if(get_user_role()=='customer'){
            array_push($where, "AND tblmonitorings.customer='".get_user_id()."'");
            array_push($where, "AND tblmonitorings.monitoringstatus=3");
        }

	if ($filter_responsible!='' && $filter_responsible!='undefined') {
            $filter_responsible = str_replace("_space_"," ",$filter_responsible);
            array_push($where, "AND tblmonitorings.company='".$filter_responsible."' ");
	}
        if ($filter_monitoringstatus!='' && $filter_monitoringstatus!='undefined') {
            array_push($where, "AND tblmonitorings.monitoringstatus='".$filter_monitoringstatus."' ");
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

        if($GLOBALS['monitoring_permission']['edit']){
            $aRow['action']='<a href="'.base_url('admin/monitorings/monitoring/'.$aRow['monitoringnr']).'" class="btn btn-sm btn-default btn-circle btn-editable blue"><i class="fa fa-pencil"></i></a>';

            $aRow['action'].='<a href="javascript:void(0);" class="btn btn-sm btn-default btn-circle btn-editable green-dark" onClick="importCSVAjax(\''.base_url("admin/monitorings/import").'\',\''.$aRow['monitoringnr'].'\',\''.lang("page_lb_import_monitoring").' '.$aRow['monitoringnr_prefix'].'\',\''.$aRow['assignmentnr_prefix'].'\');"><i class="fa fa-file-excel-o"></i></a>';
            $aRow['action'].='<a href="javascript:void(0);" class="btn btn-sm btn-default btn-circle btn-editable green-dark" onClick="importCSVAjaxSecond(\''.base_url("admin/monitorings/importsecond").'\',\''.$aRow['monitoringnr'].'\',\''.lang("page_lb_import_monitoring").' '.$aRow['monitoringnr_prefix'].'\',\''.$aRow['assignmentnr_prefix'].'\',\''.$aRow['assignmentnr'].'\');"><i class="fa fa-file-excel-o"></i></a>';
            $aRow['action'].='<a href="'.base_url("admin/monitorings/exportPdf").'/'.$aRow['monitoringnr'].'" class="btn btn-sm btn-danger btn-circle btn-editable"><i class="fa fa-file-pdf-o"></i></a>';
        }

        if($GLOBALS['monitoring_permission']['delete']){
            if(!$GLOBALS['monitoring_permission']['edit']){ $aRow['action'] = ''; }
            $aRow['action'].='<a href="javascript:void(0);" class="btn btn-sm btn-default btn-circle btn-editable red" onclick="deleteConfirmation('
                    . '\''.base_url("admin/monitorings/delete").'\','
                    . '\''.$aRow["monitoringnr"].'\','
                    . '\''.lang("page_lb_delete_monitoring").'\','
                    . '\''.lang("page_lb_delete_monitoring_info").'\');">'
                    . '<i class="fa fa-remove"></i></a>';
        }

        if(!$GLOBALS['monitoring_permission']['edit'] && !$GLOBALS['monitoring_permission']['delete']){
            $aRow['action'] = lang('access_denied');
        }


        //For Bericht PDF
        //This button should be seen after all Selectboxes are unequel "Auswählen" and status was changed to "Erledigt"
        //if($GLOBALS['monitoring_permission']['edit']){
            if($aRow['compare1']==$aRow['compare2'] && $aRow['compare1']>0 && $aRow['compare2']>0 && $aRow['monitoringstatusid']==3){
                if(!$GLOBALS['monitoring_permission']['edit'] && !$GLOBALS['monitoring_permission']['delete']){
                    $aRow['action'] = '';
                }else{
                    $aRow['action'].= '<br /><br />';
                }
                $aRow['action'].='<a href="'.base_url('admin/monitorings/printmonitoringprotocol/'.$aRow['monitoringnr']).'" target="_blank" class="btn btn-sm btn-default btn-editable yellow"><i class="fa fa-print"></i> '.lang('page_lb_report_pdf').'</a>';
            }
        //}


        //Lead Detail
        $aRow['monitoringnr_prefix'] = '<a href="'.base_url('admin/monitorings/detail/'.$aRow['monitoringnr']).'">'.$aRow['monitoringnr_prefix'].'</a>';
        $aRow['assignmentnr_prefix'] = '<a href="'.base_url('admin/assignments/detail/'.$aRow['assignmentnr']).'">'.$aRow['assignmentnr_prefix'].'</a>';

        if(get_user_role()=='customer'){
            $records["data"][] = array(
                $aRow['monitoringnr_prefix'],
                _d($aRow['date']),
                $aRow['company'],
                $aRow['monitoringstatus'],
                $aRow['assignmentnr_prefix'],
                $aRow['action'],
                $aRow['monitoringnr']
            );
        }else{
            $records["data"][] = array(
                $aRow['monitoringnr_prefix'],
                _d($aRow['date']),
                $aRow['company'],
                $aRow['monitoringstatus'],
                $aRow['assignmentnr_prefix'],
                $aRow['monitoringlink'],
                $aRow['monitoringuser'],
                $aRow['monitoringpass'],
                $aRow['action'],
                $aRow['monitoringnr']
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