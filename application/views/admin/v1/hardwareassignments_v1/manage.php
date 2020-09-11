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
                                <span><?php echo lang('page_hardwareassignments');?></span>
                            </li>
                        </ul>                           
                    </div>
                    <!-- END PAGE BAR -->
                    
                    
                    
                    <!-- BEGIN PAGE MESSAGE-->
                    <?php $this->load->view('admin/alerts'); ?>
                    <!-- BEGIN PAGE MESSAGE-->
                    
                    
                    
                    <!-- BEGIN PAGE TITLE-->
                    <h3 class="page-title"><i class="icon-settings"></i> <?php echo lang('page_manage_hardwareassignment');?></h3>
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
                                        <?php echo form_dropdown('filter_hardwareassignmentstatus', $filter_hardwareassignmentstatus, '', 'class="form-control select2" id="filter_hardwareassignmentstatus" ');?>
                                        </div>
                                        
                                    </div>                                    
                                </div>
                                
                                <div class="portlet-body">
                                    <div class="table-container">
                                        
                                        <div class="table-actions-wrapper"></div>
                                        
                                        <table class="table table-striped table-bordered table-hover dt-responsive" width="100%" id="hardwareassignment_datatable_ajax">    
                                            <thead>
                                                <tr role="row" class="heading">                                                                                                        
                                                    <th width="15%" class="all"> <?php echo lang('page_dt_hardwareassignmentnr');?></th>                                                    
                                                    <th width="15%"> <?php echo lang('page_dt_company');?></th>
                                                    <th width="15%"> <?php echo lang('page_dt_hardwareassignmentstatus');?></th>
                                                    <th width="30%" class="desktop"> <?php echo lang('page_dt_action');?></th>
                                                    <th width="1%"> <?php echo lang('page_dt_hardwareassignmentnr');?></th>
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
    var admin_url = '<?php echo base_url('admin/hardwareassignments/ajax');?>';    
    var func_TableDatatablesAjax = 'TableCustomDatatablesAjax';
    var datatable_id = 'hardwareassignment_datatable_ajax';
    var datatable_pagelength = '<?php echo get_option('tables_pagination_limit');?>';      
    var datatable_columnDefs = 3;
    var datatable_columnDefs2 = 3;
    var datatable_sortColumn = 0;
    var datatable_sortColumnBy = 'asc';
    var datatable_hide_columns = 4;
</script>

<?php $this->load->view('admin/footer.php'); ?>        


<script>
//Change Filter By Lamp Symbol
jQuery("#filter_hardwareassignmentstatus").select2({
	placeholder: "<?php echo lang('page_lb_select_a_status');?>",
	allowClear: true
});

jQuery('#filter_hardwareassignmentstatus').change( function(){   
    var admin_url = '<?php echo base_url('admin/hardwareassignments/ajax');?>';     
    var filter_hardwareassignmentstatus = jQuery('#filter_hardwareassignmentstatus').val();
    var admin_url = admin_url + '/'+ filter_hardwareassignmentstatus;    
      
    if (typeof func_TableDatatablesAjax !== 'undefined') {             
        $('#'+datatable_id).DataTable().destroy();
        eval(func_TableDatatablesAjax + "('"+admin_url+"')");
    }
});
</script>