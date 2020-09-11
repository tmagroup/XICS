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
                                <span><?php echo lang('page_tickets');?></span>
                            </li>
                        </ul>
                        <?php
                        if($GLOBALS['ticket_permission']['create']){
                        ?>
                        <div class="page-toolbar">
                            <div class="btn-group btn-group-devided pull-right">

                                <?php
                                if($GLOBALS['ticket_permission']['create']){
                                    ?>
                                    <a href="<?php echo base_url('admin/tickets/ticket');?>" class="btn sbold blue btn-sm"><i class="fa fa-plus"></i> <?php echo lang('page_create_ticket');?></a>
                                    <?php
                                }
                                ?>

                            </div>
                        </div>
                        <?php
                        }
                        ?>
                    </div>
                    <!-- END PAGE BAR -->



                    <!-- BEGIN PAGE MESSAGE-->
                    <?php $this->load->view('admin/alerts'); ?>
                    <!-- BEGIN PAGE MESSAGE-->



                    <!-- BEGIN PAGE TITLE-->
                    <h3 class="page-title"><i class="fa fa-ticket"></i> <?php echo lang('page_manage_ticket');?></h3>
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
                                        <?php echo form_dropdown('filter_ticketstatus', $filter_ticketstatus, '', 'class="form-control select2" id="filter_ticketstatus" ');?>
                                        </div>

                                    </div>
                                </div>

                                <div class="portlet-body">
                                    <div class="table-container">

                                        <div class="table-actions-wrapper"></div>

                                        <table class="table table-striped table-bordered table-hover dt-responsive" width="100%" id="ticket_datatable_ajax">
                                            <thead>
                                                <tr role="row" class="heading">
                                                    <th width="15%" class="all"> <?php echo lang('page_dt_ticketnr');?></th>
                                                    <th> <?php echo lang('page_dt_tickettitle');?></th>
                                                    <th> <?php echo lang('page_dt_company');?></th>
                                                    <th> <?php echo lang('page_dt_ticketstatus');?></th>
                                                    <th> <?php echo lang('page_dt_responsibleuser');?></th>
                                                    <th> <?php echo lang('page_fl_date');?></th>
                                                    <th width="15%" class="desktop"> <?php echo lang('page_dt_action');?></th>
                                                    <th width="1%"> <?php echo lang('page_dt_ticketnr');?></th>
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
    var admin_url = '<?php echo base_url('admin/tickets/ajax');?>';
    var func_TableDatatablesAjax = 'TableCustomDatatablesAjax';
    var datatable_id = 'ticket_datatable_ajax';
    var datatable_pagelength = '<?php echo get_option('tables_pagination_limit');?>';
    var datatable_columnDefs = 6;
    var datatable_columnDefs2 = 6;
    var datatable_sortColumn = 0;
    var datatable_sortColumnBy = 'desc';
    var datatable_hide_columns = 7;
</script>

<?php $this->load->view('admin/footer.php'); ?>


<script>
//Change Filter By User
jQuery("#filter_responsible").select2({
	placeholder: "<?php echo lang('page_lb_select_a_user');?>",
	allowClear: true
});

jQuery("#filter_ticketstatus").select2({
	placeholder: "<?php echo lang('page_lb_select_a_status');?>",
	allowClear: true
});

jQuery('#filter_responsible,#filter_ticketstatus').change( function(){
    var admin_url = '<?php echo base_url('admin/tickets/ajax');?>';

    var filter_responsible = jQuery('#filter_responsible').val();
    var filter_ticketstatus = jQuery('#filter_ticketstatus').val();
    var admin_url = admin_url + '/'+ eval(filter_responsible) + '/' + eval(filter_ticketstatus);

    if (typeof func_TableDatatablesAjax !== 'undefined') {
        $('#'+datatable_id).DataTable().destroy();
        eval(func_TableDatatablesAjax + "('"+admin_url+"')");
    }
});
</script>