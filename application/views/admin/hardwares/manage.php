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
                                <span><?php echo lang('page_hardwares');?></span>
                            </li>
                        </ul>
                        <?php
                        if($GLOBALS['hardware_permission']['create'] || $GLOBALS['hardware_permission']['import']){
                        ?>
                        <div class="page-toolbar">
                            <div class="btn-group btn-group-devided pull-right">

                                <?php
                                if($GLOBALS['hardware_permission']['import']){
                                    ?>
                                    <a href="<?php echo base_url('admin/hardwares/import');?>" class="btn sbold green btn-sm"><i class="fa fa-file-excel-o"></i> <?php echo lang('page_import_hardware');?></a>
                                    <?php
                                }
                                ?>

                                <?php
                                if($GLOBALS['hardware_permission']['create']){
                                    ?>
                                    <a href="<?php echo base_url('admin/hardwares/hardware');?>" class="btn sbold blue btn-sm"><i class="fa fa-plus"></i> <?php echo lang('page_create_hardware');?></a>
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
                    <h3 class="page-title">
                        <i class="fa fa-tag"></i> <?php echo lang('page_manage_hardware');?>
                        <div class="pull-right">
                            <a href="<?php echo base_url('admin/hardwares/export_excel');?>" class="btn sbold blue btn-sm"><i class="fa fa-file-excel-o"></i> Excel</a>
                        </div>
                    </h3>
                    <!-- END PAGE TITLE-->


                    <!-- END PAGE HEADER-->
                    <div class="row">
                        <div class="col-md-12">

                            <!-- Begin: life time stats -->
                            <div class="portlet light portlet-fit portlet-datatable bordered">

                                <div class="portlet-body">
                                    <div class="table-container">

                                        <div class="table-actions-wrapper"></div>

                                        <table class="table table-striped table-bordered table-hover dt-responsive" id="hardware_datatable_ajax">
                                            <thead>
                                                <tr role="row" class="heading">
                                                    <th width="21%"> <?php echo lang('page_dt_hardwarenr');?></th>
                                                    <th width="21%"> <?php echo lang('page_dt_hardwaretitle');?></th>
                                                    <th width="21%"> <?php echo lang('page_dt_hardwareprice');?></th>
                                                    <th width="21%"> <?php echo lang('page_dt_created');?></th>
                                                    <th width="15%"> <?php echo lang('page_dt_action');?></th>
                                                    <th width="1%"> <?php echo lang('page_dt_hardwarenr');?></th>
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
    var admin_url = '<?php echo base_url('admin/hardwares/ajax');?>';
    var func_TableDatatablesAjax = 'TableCustomDatatablesAjax';
    var datatable_id = 'hardware_datatable_ajax';
    var datatable_pagelength = '<?php echo get_option('tables_pagination_limit');?>';
    var datatable_columnDefs = 4;
    var datatable_columnDefs2 = 4;
    var datatable_sortColumn = 0;
    var datatable_sortColumnBy = 'asc';
    var datatable_hide_columns = 5;
</script>

<?php $this->load->view('admin/footer.php'); ?>