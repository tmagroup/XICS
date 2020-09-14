<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<?php $this->load->view('admin/header.php'); ?>

<body class="page-header-fixed page-sidebar-closed-hide-logo page-content-white">

        <?php $this->load->view('admin/topnavigation.php'); ?>

        <!-- BEGIN HEADER & CONTENT DIVIDER -->
        <div class="clearfix"> </div>
        <!-- END HEADER & CONTENT DIVIDER -->
        <!-- BEGIN CONTAINER -->
        <div class="page-container">

            <?php $this->load->view('admin/sidebar.php'); ?>


            <!-- BEGIN CONTENT -->
            <div class="page-content-wrapper">
                <!-- BEGIN CONTENT BODY -->
                <div class="page-content">
                    <!-- BEGIN PAGE HEADER-->

                    <!-- BEGIN PAGE BAR -->
                    <div class="page-bar">
                        <ul class="page-breadcrumb">
                            <li>
                                <a href="<?php echo base_url('admin/dashboard');?>"><?php echo lang('bread_home');?></a>
                                <i class="fa fa-circle"></i>
                            </li>
                            <li>
                                <span><?php echo lang('page_monitorings');?></span>
                            </li>
                        </ul>
                         <div class="page-toolbar">
                            <div class="btn-group btn-group-devided pull-right">
                                <a href="<?php echo base_url().'Cronjobs/monitorings_generatemonitoringjob/YES';?>" class="btn sbold blue btn-sm add-monitoring"><i class="fa fa-refresh"></i> <?php echo lang('page_refresh_monitoring');?></a>
                            </div>
                        </div>
                    </div>
                    <!-- END PAGE BAR -->



                    <!-- BEGIN PAGE MESSAGE-->
                    <?php $this->load->view('admin/alerts'); ?>
                    <!-- BEGIN PAGE MESSAGE-->



                    <!-- BEGIN PAGE TITLE-->
                    <h3 class="page-title"><i class="fa fa-eye"></i> <?php echo lang('page_manage_monitoring');?></h3>
                    <!-- END PAGE TITLE-->


                    <!-- END PAGE HEADER-->
                    <div class="row">
                        <div class="col-md-12">

                            <!-- Begin: life time stats -->
                            <div class="portlet light portlet-fit portlet-datatable bordered">

                                <div class="portlet-title filterby">
                                    <div class="form-group">

                                        <label><?php echo lang('filter_by');?> </label>

                                        <div class="col-md-3 col-sm-3">
                                        <?php echo form_dropdown('filter_responsible', $filter_responsible, '', 'class="form-control select2" id="filter_responsible" ');?>
                                        </div>

                                        <div class="col-md-3 col-sm-3">
                                        <?php echo form_dropdown('filter_monitoringstatus', $filter_monitoringstatus, '', 'class="form-control select2" id="filter_monitoringstatus" ');?>
                                        </div>

                                    </div>
                                </div>

                                <div class="portlet-body">
                                    <div class="table-container">

                                        <div class="table-actions-wrapper"></div>

                                        <table class="table table-striped table-bordered table-hover dt-responsive" width="100%" id="monitoring_datatable_ajax">
                                            <thead>
                                                <tr role="row" class="heading">
                                                    <th width="15%" class="all"> <?php echo lang('page_dt_monitoringnr');?></th>
                                                    <th> <?php echo lang('page_fl_date');?></th>
                                                    <th> <?php echo lang('page_dt_company');?></th>
                                                    <th> <?php echo lang('page_dt_monitoringstatus');?></th>
                                                    <th> <?php echo lang('page_dt_assignmentnr');?></th>

                                                    <?php
                                                    if(get_user_role()!='customer'){
                                                        ?>
                                                        <th class="none"> <?php echo lang('page_fl_monitoringlink');?></th>
                                                        <th class="none"> <?php echo lang('page_fl_monitoringuser');?></th>
                                                        <th class="none"> <?php echo lang('page_fl_monitoringpass');?></th>
                                                        <?php
                                                    }
                                                    ?>

                                                    <th width="20%" class="desktop"> <?php echo lang('page_dt_action');?></th>
                                                    <th width="1%"> <?php echo lang('page_dt_monitoringnr');?></th>
                                                </tr>
                                            </thead>
                                            <tbody> </tbody>
                                        </table>

                                    </div>
                                </div>
                            </div>
                            <!-- End: life time stats -->
                        </div>
                    </div>
                </div>
                <!-- END CONTENT BODY -->
            </div>
            <!-- END CONTENT -->
        </div>
        <!-- END CONTAINER -->

<script>
    var admin_url = '<?php echo base_url('admin/monitorings/ajax');?>';
    var func_TableDatatablesAjax = 'TableCustomDatatablesAjax';
    var datatable_id = 'monitoring_datatable_ajax';
    var datatable_pagelength = '<?php echo get_option('tables_pagination_limit');?>';

    <?php
    if(get_user_role()=='customer'){
        ?>
        var datatable_columnDefs = 5;
        var datatable_columnDefs2 = 5;
        <?php
    }else{
        ?>
        var datatable_columnDefs = 8;
        var datatable_columnDefs2 = 8;
        <?php
    }
    ?>

    var datatable_sortColumn = 0;
    var datatable_sortColumnBy = 'asc';

    <?php
    if(get_user_role()=='customer'){
        ?>
        var datatable_hide_columns = 6;
        <?php
    }else{
        ?>
        var datatable_hide_columns = 9;
        <?php
    }
    ?>
</script>

<?php $this->load->view('admin/footer.php'); ?>
<?php $this->load->view('admin/monitorings/monitoringjs');?>

<script>
//Change Filter By Company of Customer
jQuery("#filter_responsible").select2({
	placeholder: "<?php echo lang('page_lb_select_a_company');?>",
	allowClear: true
});

jQuery("#filter_monitoringstatus").select2({
	placeholder: "<?php echo lang('page_lb_select_a_status');?>",
	allowClear: true
});

jQuery('#filter_responsible,#filter_monitoringstatus').change( function(){
    var admin_url = '<?php echo base_url('admin/monitorings/ajax');?>';

    var filter_responsible = jQuery('#filter_responsible').val();
    var filter_responsible = filter_responsible.replace(/ /g, "_space_");

    var filter_monitoringstatus = jQuery('#filter_monitoringstatus').val();
    var admin_url = admin_url + '/'+ filter_responsible + '/' + eval(filter_monitoringstatus);

    if (typeof func_TableDatatablesAjax !== 'undefined') {
        $('#'+datatable_id).DataTable().destroy();
        eval(func_TableDatatablesAjax + "('"+admin_url+"')");
    }
});
</script>