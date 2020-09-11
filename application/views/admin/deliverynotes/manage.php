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
                                <span><?php echo lang('page_deliverynotes');?></span>
                            </li>
                        </ul>                           
                    </div>
                    <!-- END PAGE BAR -->
                    
                    
                    
                    <!-- BEGIN PAGE MESSAGE-->
                    <?php $this->load->view('admin/alerts'); ?>
                    <!-- BEGIN PAGE MESSAGE-->
                    
                    
                    
                    <!-- BEGIN PAGE TITLE-->
                    <h3 class="page-title"><i class="icon-graph"></i> <?php echo lang('page_manage_deliverynote');?></h3>
                    <!-- END PAGE TITLE-->
                    
                    
                    <!-- END PAGE HEADER-->
                    <div class="row">
                        <div class="col-md-12">
                            
                            <!-- Begin: life time stats -->
                            <div class="portlet light portlet-fit portlet-datatable bordered">
                               
                                <div class="portlet-body">
                                    <div class="table-container">
                                        
                                        <div class="table-actions-wrapper"></div>
                                        
                                        <table class="table table-striped table-bordered table-hover dt-responsive" width="100%" id="deliverynote_datatable_ajax">    
                                            <thead>
                                                <tr role="row" class="heading">                                                                                                        
                                                    <th class="all"> <?php echo lang('page_dt_shippingslipnr');?></th>                                                    
                                                    <th><?php echo lang('page_dt_company');?></th>
                                                    <th width="30%" class="desktop"> <?php echo lang('page_dt_action');?></th>
                                                    <th><?php echo lang('page_dt_shippingslipnr');?></th>
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
    var admin_url = '<?php echo base_url('admin/deliverynotes/ajax');?>';    
    var func_TableDatatablesAjax = 'TableCustomDatatablesAjax';
    var datatable_id = 'deliverynote_datatable_ajax';
    var datatable_pagelength = '<?php echo get_option('tables_pagination_limit');?>';      
    var datatable_columnDefs = 2;
    var datatable_columnDefs2 = 2;
    var datatable_sortColumn = 0;
    var datatable_sortColumnBy = 'asc';
    var datatable_hide_columns = 3;
</script>

<?php $this->load->view('admin/footer.php'); ?>

<!-- Shipping No. Modal -->
<div class="modal fade bs-modal-sm in" id="FormAjax" tabindex="-1" role="dialog">
	<div class="modal-dialog modal-sm" role="document">
		<?php echo form_open("",array("id"=>"FormModalAjax")); ?>
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title"></h4>
			</div>
			<div class="modal-body">
                            
			</div>
			<div class="modal-footer">
				<!--<button type="submit" class="btn btn-default blue"><?php echo lang('save'); ?></button>-->
                                <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo lang('page_lb_close'); ?></button>
			</div>
		</div><!-- /.modal-content -->
		<?php echo form_close(); ?>
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->   
<!-- End Shipping No. Modal -->

<script>    
//General Modal  
function FormAjax(url, infourl, title, validfunc){
    $('#FormAjax').modal('show');	
    $('#FormAjax #FormModalAjax').attr('action',url);
    $('#FormModalAjax .modal-title').html('<i class="icon-graph"></i> '+title);
    $('#FormModalAjax .modal-body').html("<div class='text-center'><img src='<?php echo base_url('assets/global/img/loading-spinner-blue.gif');?>' /></div>");
    
    /* Initialise for Edit */
    $('#FormModalAjax')[0].reset();
    
    //Clear validation
    $("span.help-block-error").hide();
    $(".has-error").removeClass("has-error");
    var error1 = jQuery('#FormAjax #alert_modal .alert-danger');
    $(error1).hide();
    
    Pace.track(function(){
        Pace.restart();
        jQuery.ajax({url: infourl, success: function(result){
        $('#FormModalAjax .modal-body').html(result);  
        //datapicker();
        
        //Validation Form
        if (typeof validfunc !== 'undefined') {
                //eval(validfunc + "FormValidation()");						
            }        
        }});
    });
}
</script>    