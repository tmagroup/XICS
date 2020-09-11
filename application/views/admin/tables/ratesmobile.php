<?php
    /*
     * Paging
     */
    $aColumns = array(
        'ratenr_prefix',
        'ratetitle',
        'price',
        'shop',
        'created',
        'ratenr'
    );
    $sIndexColumn  = "ratenr";
    $sTable        = 'tblratesmobile';
    $join          = array();
    $where = do_action('ratesmobile_table_sql_where', array());
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

        if($GLOBALS['ratemobile_permission']['edit']){
            $aRow['action']='<a href="'.base_url('admin/ratesmobile/rate/'.$aRow['ratenr']).'" class="btn btn-sm btn-default btn-circle btn-editable blue"><i class="fa fa-pencil"></i></a>';
        }

        if($GLOBALS['ratemobile_permission']['delete']){
            if(!$GLOBALS['ratemobile_permission']['edit']){ $aRow['action'] = ''; }
            $aRow['action'].='<a href="javascript:void(0);" class="btn btn-sm btn-default btn-circle btn-editable red" onclick="deleteConfirmation('
                    . '\''.base_url("admin/ratesmobile/delete").'\','
                    . '\''.$aRow["ratenr"].'\','
                    . '\''.lang("page_lb_delete_ratemobile").'\','
                    . '\''.lang("page_lb_delete_ratemobile_info").'\');">'
                    . '<i class="fa fa-remove"></i></a>';
        }

        if(!$GLOBALS['ratemobile_permission']['edit'] && !$GLOBALS['ratemobile_permission']['delete']){
            $aRow['action'] = lang('access_denied');
        }


        $langtext = '';
        $shopchecked = ($aRow['shop']==1)?' checked':'';
        $aRow['shop'] = '<div class="onoffswitch" data-toggle="tooltip" data-title="'.$langtext.'"><input '.$shopchecked.' type="checkbox" data-switch-url="'.base_url('admin/ratesmobile/change_shop').'" data-id="'.$aRow['ratenr'].'" class="make-switch" data-on-text="Yes" data-off-text="No" data-on-color="success" data-off-color="danger" data-size="small"></div>';


        $records["data"][] = array(
            $aRow['ratenr_prefix'],
            $aRow['ratetitle'],
            format_money($aRow['price'], "&nbsp;".$GLOBALS['currency_data']['currency_name']),
            _d($aRow['created']),
            $aRow['action'],
            $aRow['shop'],
            $aRow['ratenr']
        );
    }

    //Don't remove
    $records["draw"] = $sEcho;
    $records["recordsTotal"] = $iTotalRecords;
    $records["recordsFiltered"] = $iTotalRecords;

    //echo json_encode($records);
    $output = $records;
?>