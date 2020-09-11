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
                                <span><?php echo lang('page_leads');?></span>
                            </li>
                        </ul>
                        <?php
                        if($GLOBALS['lead_permission']['create'] || $GLOBALS['lead_permission']['import']){
                        ?>
                        <div class="page-toolbar">
                            <div class="btn-group btn-group-devided pull-right">                                
                                
                                <?php
                                if($GLOBALS['lead_permission']['import']){
                                    ?>
                                    <a href="<?php echo base_url('admin/leads/import');?>" class="btn sbold green btn-sm"><i class="fa fa-file-excel-o"></i> <?php echo lang('page_import_lead');?></a>                                
                                    <?php
                                }
                                ?>
                                
                                <?php
                                if($GLOBALS['lead_permission']['create']){
                                    ?>
                                    <a href="<?php echo base_url('admin/leads/lead');?>" class="btn sbold blue btn-sm"><i class="fa fa-plus"></i> <?php echo lang('page_create_lead');?></a>                                
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
                    <h3 class="page-title"><i class="fa fa-tty"></i> <?php echo lang('page_manage_lead');?></h3>
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
                                        <?php echo form_dropdown('filter_leadstatus', $filter_leadstatus, '', 'class="form-control select2" id="filter_leadstatus" ');?>
                                        </div>
                                        
                                        <div class="col-md-3 col-sm-3">
                                        <?php echo form_dropdown('filter_product', $filter_product, '', 'class="form-control select2" id="filter_product" ');?>
                                        </div>
                                        
                                        <?php
                                        if($GLOBALS['lead_permission']['delete']){    
                                            ?>
                                            <div id="btn_delete_all" style="display: none">
                                                <a href="javascript:void(0);" class="btn_delete_all_lead btn sbold red btn-sm" onclick="deleteAllConfirmation('<?php echo base_url("admin/leads/deleteAll");?>','lead','<?php echo lang("page_lb_delete_all_lead");?>','<?php echo lang("page_lb_delete_all_lead_info");?>','','<?php echo lang('not_selected_rows')?>')" ><i class="fa fa-trash"></i> <?php echo lang('page_lb_delete_all_lead');?></a>                                
                                            </div>
                                            <?php
                                        }
                                        ?>
                                        
                                    </div>                                    
                                </div>
                                
                                <div class="portlet-body">
                                    <div class="table-container">
                                        
                                        <div class="table-actions-wrapper"></div>
                                        
                                        <table class="table table-striped table-bordered table-hover dt-responsive" width="100%" id="lead_datatable_ajax">    
                                            <thead>
                                                <tr role="row" class="heading">
                                                    
                                                    <?php
                                                    if($GLOBALS['lead_permission']['delete']){    
                                                        ?>
                                                        <th width="1%"><input type="checkbox" class="group-checkable" data-set="#lead_datatable_ajax .checkboxes" /></th>
                                                        <?php
                                                    }
                                                    ?>
                                                        
                                                    <th width="7%" class="all"> <?php echo lang('page_dt_leadnr');?></th>
                                                    <th width="1%" class="none"> <?php echo lang('page_dt_leadstatus');?></th>
                                                    <th width="1%" class="none"> <?php echo lang('page_dt_leadstatuscolor');?></th>
                                                    <th width="7%" class="all"> <?php echo lang('page_dt_responsibleuser');?></th>
                                                    <th width="7%" class="all"> <?php echo lang('page_dt_leadsource');?></th>
                                                    <th width="7%" class="all"> <?php echo lang('page_dt_company');?></th>
                                                    <th width="7%" class="min-tablet"> <?php echo lang('page_dt_city');?></th>
                                                    <th width="7%" class="min-tablet"> <?php echo lang('page_dt_name');?></th>
                                                    <th width="7%" class="none"> <?php echo lang('page_dt_surname');?></th>
                                                    <th width="7%" class="none"> <?php echo lang('page_dt_phone');?></th>
                                                    <th width="7%" class="none"> <?php echo lang('page_dt_mobile');?></th>
                                                    <th width="7%" class="none"> <?php echo lang('page_dt_provider');?></th>
                                                    <th width="7%" class="none"> <?php echo lang('page_dt_product');?></th>
                                                    <th width="10%" class="desktop"> <?php echo lang('page_dt_action');?></th>
                                                    <th width="1%"> <?php echo lang('page_dt_leadnr');?></th>
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
    var admin_url = '<?php echo base_url('admin/leads/ajax');?>';    
    var func_TableDatatablesAjax = 'TableCustomDatatablesAjax';
    var datatable_id = 'lead_datatable_ajax';
    var datatable_pagelength = '<?php echo get_option('tables_pagination_limit');?>';      
    
    <?php
    if($GLOBALS['lead_permission']['delete']){    
        ?>
        var datatable_columnDefs = 0;
        var datatable_columnDefs2 = 14;
        var datatable_sortColumn = 1;
        var datatable_sortColumnBy = 'asc';
        var datatable_hide_columns = 15;
        <?php
    }
    else{
        ?>
        var datatable_columnDefs = 13;
        var datatable_columnDefs2 = 13;
        var datatable_sortColumn = 0;
        var datatable_sortColumnBy = 'asc';
        var datatable_hide_columns = 14;
        <?php
    }
    ?>
</script>

<?php $this->load->view('admin/footer.php'); ?>        


<script>
//Change Filter By User
jQuery("#filter_responsible").select2({
	placeholder: "<?php echo lang('page_lb_select_a_user');?>",
	allowClear: true
});

jQuery("#filter_leadstatus").select2({
	placeholder: "<?php echo lang('page_lb_select_a_status');?>",
	allowClear: true
});

jQuery("#filter_product").select2({
	placeholder: "<?php echo lang('page_lb_select_a_product');?>",
	allowClear: true
});
		
jQuery('#filter_responsible,#filter_leadstatus,#filter_product').change( function(){   
    var admin_url = '<?php echo base_url('admin/leads/ajax');?>'; 
    
    var filter_responsible = jQuery('#filter_responsible').val();
    var filter_leadstatus = jQuery('#filter_leadstatus').val();
    var filter_product = jQuery('#filter_product').val();
    var admin_url = admin_url + '/'+ eval(filter_responsible) + '/' + eval(filter_leadstatus) + '/' + eval(filter_product);    
      
    if (typeof func_TableDatatablesAjax !== 'undefined') {             
        $('#'+datatable_id).DataTable().destroy();
        eval(func_TableDatatablesAjax + "('"+admin_url+"')");
    }
});
</script>