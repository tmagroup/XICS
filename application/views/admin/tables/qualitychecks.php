<?php
    /*
     * Paging
     */
    $aColumns = array(
        'qualitychecknr_prefix',
        'qualityissue',
        'IF(rel_type="event", tblevents.calendarId, "") AS calendarId',
        // 'tblcustomers.company as company',
        'IF(rel_type="event",
            (SELECT CONCAT(eventuser.name," ",eventuser.surname) FROM tblusers AS eventuser WHERE eventuser.userid=tblevents.userid),
            (CONCAT(responsible.name," ",responsible.surname))
        ) as responsible_name',
        'IF(rel_type="event",
            IF(tblevents.assignmentnr>0,
            (SELECT company FROM tblassignments WHERE assignmentnr=tblevents.assignmentnr),
            IF(tblevents.leadnr>0,(SELECT company FROM tblleads WHERE leadnr=tblevents.leadnr),"")),
            tblcustomers.company
        ) as company',
        'tblqualitycheckstatus.name as qualitycheckstatus',
        // "CONCAT(responsible.name,' ',responsible.surname) as responsible_name",
        //"CONCAT(proofuser.name,' ',proofuser.surname) as proofuser_name",

        "(CASE rel_type

            WHEN 'assignment' THEN (SELECT assignmentnr_prefix FROM tblassignments WHERE assignmentnr=tblqualitychecks.rel_id)

            WHEN 'hardwareassignment' THEN (SELECT hardwareassignmentnr_prefix FROM tblhardwareassignments WHERE hardwareassignmentnr=tblqualitychecks.rel_id)

            WHEN 'event' THEN (SELECT title FROM tblevents WHERE eventid=tblqualitychecks.rel_id)

            ELSE ''

        END) as rel_name",

        'rel_id',
        'rel_type',
        'qualitychecknr',
    );
    $sIndexColumn  = "qualitychecknr";
    $sTable        = 'tblqualitychecks';
    //$join          = array();
    $join = array('LEFT JOIN tblusers as responsible ON responsible.userid=tblqualitychecks.responsible',
    'LEFT JOIN tblusers as proofuser ON proofuser.userid=tblqualitychecks.proofuser',
    'LEFT JOIN tblqualitycheckstatus ON tblqualitycheckstatus.id=tblqualitychecks.qualitycheckstatus',
    'LEFT JOIN tblevents ON tblevents.eventid=tblqualitychecks.rel_id',
    'LEFT JOIN tblcustomers ON tblcustomers.customernr=tblqualitychecks.company');

    //$where = do_action('qualitychecks_table_sql_where', array());

        $where = array();
        if ($filter_responsible!='' && $filter_responsible!='undefined') {
            array_push($where, "AND tblqualitychecks.responsible='".$filter_responsible."'");
    }
    if ($filter_qualitycheckstatus!='' && $filter_qualitycheckstatus!='undefined') {
            array_push($where, "AND tblqualitychecks.qualitycheckstatus='".$filter_qualitycheckstatus."'");
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

    $ci =& get_instance();
    $ci->load->model('Event_model');
    $googlecalendars = $ci->Event_model->getGoogleCalendarList();
    $new_arr = array();
    foreach ($googlecalendars as $key => $value) {
        $new_arr[$value['id']] = $value['summary'];
    }
    $googlecalendars = $new_arr;

    //Data Loop
    foreach ($rResult as $aRow) {

        $aRow['action'] = '';
        if($GLOBALS['qualitycheck_permission']['edit']){
            $aRow['action']='<a href="'.base_url('admin/qualitychecks/qualitycheck/'.$aRow['qualitychecknr']).'" class="btn btn-sm btn-default btn-circle btn-editable blue"><i class="fa fa-pencil"></i></a>';
        }

        if($GLOBALS['qualitycheck_permission']['delete']){
            if(!$GLOBALS['qualitycheck_permission']['edit']){ $aRow['action'] = ''; }
            $aRow['action'].='<a href="javascript:void(0);" class="btn btn-sm btn-default btn-circle btn-editable red" onclick="deleteConfirmation('
                    . '\''.base_url("admin/qualitychecks/delete").'\','
                    . '\''.$aRow["qualitychecknr"].'\','
                    . '\''.lang("page_lb_delete_qualitycheck").'\','
                    . '\''.lang("page_lb_delete_qualitycheck_info").'\');">'
                    . '<i class="fa fa-remove"></i></a>';
        }

        //Hardwareinput Detail
        $aRow['qualitychecknr_prefix'] = '<a href="'.base_url('admin/qualitychecks/detail/'.$aRow['qualitychecknr']).'">'.$aRow['qualitychecknr_prefix'].'</a>';


        if($aRow['rel_type']=='assignment'){
            $aRow['rel_link'] = "<a href='".base_url('admin/assignments/detail/'.$aRow['rel_id'])."' target='_blank'>".$aRow['rel_name']."</a>";
        }
        elseif($aRow['rel_type']=='hardwareassignment'){
            $aRow['rel_link'] = "<a href='".base_url('admin/hardwareassignments/detail/'.$aRow['rel_id'])."' target='_blank'>".$aRow['rel_name']."</a>";
        }
        elseif($aRow['rel_type']=='event'){
            $aRow['rel_link'] = "<a href='".base_url('admin/calendars/detail/'.$aRow['rel_id'])."' target='_blank'>".$aRow['rel_name']."</a>";
        }

        $aRow['responsible_name'] = $aRow['responsible_name'];
        if ($aRow['rel_type']=='event' && isset($googlecalendars[$aRow['calendarId']]) && strtolower($googlecalendars[$aRow['calendarId']]) != 'primary') {
            $aRow['responsible_name'] = $googlecalendars[$aRow['calendarId']];
        }

        $records["data"][] = array(
            $aRow['qualitychecknr_prefix'],
            $aRow['qualityissue'],
            $aRow['company'],
            $aRow['qualitycheckstatus'],
            $aRow['responsible_name'],
            //$aRow['proofuser_name'],
            "<span style='white-space:nowrap;'>".$aRow['rel_link']."</span>",
            $aRow['action'],
            $aRow['qualitychecknr']
        );
    }

    //Don't remove
    $records["draw"] = $sEcho;
    $records["recordsTotal"] = $iTotalRecords;
    $records["recordsFiltered"] = $iTotalRecords;

    //echo json_encode($records);
    $output = $records;
?>
