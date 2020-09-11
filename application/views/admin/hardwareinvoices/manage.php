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
                                <span><?php echo lang('page_hardwareinvoices');?></span>
                            </li>
                        </ul>                           
                    </div>
                    <!-- END PAGE BAR -->
                    
                    
                    
                    <!-- BEGIN PAGE MESSAGE-->
                    <?php $this->load->view('admin/alerts'); ?>
                    <!-- BEGIN PAGE MESSAGE-->
                    
                    
                    
                    <!-- BEGIN PAGE TITLE-->
                    <h3 class="page-title"><i class="icon-graph"></i> <?php echo lang('page_manage_hardwareinvoice');?></h3>
                    <!-- END PAGE TITLE-->
                    
                    
                    <!-- END PAGE HEADER-->
                    <div class="row">
                        <div class="col-md-12">
                            
                            <!-- Begin: life time stats -->
                            <div class="portlet light portlet-fit portlet-datatable bordered">
                               
                                <div class="portlet-body">
                                    <div class="table-container">
                                        
                                        <div class="table-actions-wrapper"></div>
                                        
                                        <table class="table table-striped table-bordered table-hover dt-responsive" width="100%" id="hardwareinvoice_datatable_ajax">    
                                            <thead>
                                                <tr role="row" class="heading">                                                                                                        
                                                    <th class="all"> <?php echo lang('page_dt_invoicenr');?></th>                                                    
                                                    <th><?php echo lang('page_dt_company');?></th>
                                                    <th><?php echo lang('page_dt_paid');?></th>
                                                    <th width="30%" class="desktop"> <?php echo lang('page_dt_action');?></th>
                                                    <th><?php echo lang('page_dt_invoicenr');?></th>
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
    var admin_url = '<?php echo base_url('admin/hardwareinvoices/ajax');?>';    
    var func_TableDatatablesAjax = 'TableCustomDatatablesAjax';
    var datatable_id = 'hardwareinvoice_datatable_ajax';
    var datatable_pagelength = '<?php echo get_option('tables_pagination_limit');?>';      
    var datatable_columnDefs = 3;
    var datatable_columnDefs2 = 3;
    var datatable_sortColumn = 0;
    var datatable_sortColumnBy = 'asc';
    var datatable_hide_columns = 4;
</script>

<?php $this->load->view('admin/footer.php'); ?>