<div class="modal fade" id="deleteConfirmation" tabindex="-1" role="dialog">
	<div class="modal-dialog" role="document">
		<?php echo form_open("",array("id"=>"deleteModal")); ?>
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title"></h4>
			</div>
			<div class="modal-body">
				<div class="delete_id">
                                    <?php echo form_hidden('id'); ?>
				</div>
                            <p class="modal-text"></p>
			</div>
			<div class="modal-footer">
				<button type="submit" class="btn btn-danger red"><?php echo lang('page_lb_confirm'); ?></button>
                                <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo lang('page_lb_close'); ?></button>
			</div>
		</div><!-- /.modal-content -->
		<?php echo form_close(); ?>
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->



<!-- BEGIN FOOTER -->
        <div class="page-footer">
            <div class="page-footer-inner text-center"> 2018 © Optimus V 2.0</div>
            <div class="scroll-to-top">
                <i class="icon-arrow-up"></i>
            </div>
        </div>
        
        
        
        <!-- END FOOTER -->
        <!--[if lt IE 9]>
<script src="../assets/global/plugins/respond.min.js"></script>
<script src="../assets/global/plugins/excanvas.min.js"></script> 
<![endif]-->
        <!-- BEGIN CORE PLUGINS -->
        <script src="<?php echo base_url('assets/global/plugins/jquery.min.js'); ?>" type="text/javascript"></script>
        <script src="<?php echo base_url('assets/global/plugins/bootstrap/js/bootstrap.min.js'); ?>" type="text/javascript"></script>
        <script src="<?php echo base_url('assets/global/plugins/js.cookie.min.js'); ?>" type="text/javascript"></script>
        <script src="<?php echo base_url('assets/global/plugins/bootstrap-hover-dropdown/bootstrap-hover-dropdown.min.js'); ?>" type="text/javascript"></script>
        <script src="<?php echo base_url('assets/global/plugins/jquery-slimscroll/jquery.slimscroll.min.js'); ?>" type="text/javascript"></script>
        <script src="<?php echo base_url('assets/global/plugins/jquery.blockui.min.js'); ?>" type="text/javascript"></script>
        <script src="<?php echo base_url('assets/global/plugins/uniform/jquery.uniform.min.js'); ?>" type="text/javascript"></script>
        <script src="<?php echo base_url('assets/global/plugins/bootstrap-switch/js/bootstrap-switch.min.js'); ?>" type="text/javascript"></script>
        <!-- END CORE PLUGINS -->
        
        
        <!-- BEGIN THEME GLOBAL SCRIPTS -->
        <script src="<?php echo base_url('assets/global/scripts/app.min.js'); ?>" type="text/javascript"></script>
        <!-- END THEME GLOBAL SCRIPTS -->
        
        
        <!-- BEGIN PAGE LEVEL PLUGINS -->                
        <?php        
        switch(current_url())
        { 
            //Dashboard
            case base_url('admin/dashboard'):        
            ?>
            <script src="<?php echo base_url('assets/global/plugins/moment.min.js'); ?>" type="text/javascript"></script>
            <script src="<?php echo base_url('assets/global/plugins/bootstrap-daterangepicker/daterangepicker.min.js'); ?>" type="text/javascript"></script>
            <script src="<?php echo base_url('assets/global/plugins/morris/morris.min.js'); ?>" type="text/javascript"></script>
            <script src="<?php echo base_url('assets/global/plugins/morris/raphael-min.js'); ?>" type="text/javascript"></script>
            <script src="<?php echo base_url('assets/global/plugins/counterup/jquery.waypoints.min.js'); ?>" type="text/javascript"></script>
            <script src="<?php echo base_url('assets/global/plugins/counterup/jquery.counterup.min.js'); ?>" type="text/javascript"></script>
            <script src="<?php echo base_url('assets/global/plugins/amcharts/amcharts/amcharts.js'); ?>" type="text/javascript"></script>
            <script src="<?php echo base_url('assets/global/plugins/amcharts/amcharts/serial.js'); ?>" type="text/javascript"></script>
            <script src="<?php echo base_url('assets/global/plugins/amcharts/amcharts/pie.js'); ?>" type="text/javascript"></script>
            <script src="<?php echo base_url('assets/global/plugins/amcharts/amcharts/radar.js'); ?>" type="text/javascript"></script>
            <script src="<?php echo base_url('assets/global/plugins/amcharts/amcharts/themes/light.js'); ?>" type="text/javascript"></script>
            <script src="<?php echo base_url('assets/global/plugins/amcharts/amcharts/themes/patterns.js'); ?>" type="text/javascript"></script>
            <script src="<?php echo base_url('assets/global/plugins/amcharts/amcharts/themes/chalk.js'); ?>" type="text/javascript"></script>
            <script src="<?php echo base_url('assets/global/plugins/amcharts/ammap/ammap.js'); ?>" type="text/javascript"></script>
            <script src="<?php echo base_url('assets/global/plugins/amcharts/ammap/maps/js/worldLow.js'); ?>" type="text/javascript"></script>
            <script src="<?php echo base_url('assets/global/plugins/amcharts/amstockcharts/amstock.js'); ?>" type="text/javascript"></script>
            <script src="<?php echo base_url('assets/global/plugins/fullcalendar/fullcalendar.min.js'); ?>" type="text/javascript"></script>
            <script src="<?php echo base_url('assets/global/plugins/flot/jquery.flot.min.js'); ?>" type="text/javascript"></script>
            <script src="<?php echo base_url('assets/global/plugins/flot/jquery.flot.resize.min.js'); ?>" type="text/javascript"></script>
            <script src="<?php echo base_url('assets/global/plugins/flot/jquery.flot.categories.min.js'); ?>" type="text/javascript"></script>
            <script src="<?php echo base_url('assets/global/plugins/jquery-easypiechart/jquery.easypiechart.min.js'); ?>" type="text/javascript"></script>
            <script src="<?php echo base_url('assets/global/plugins/jquery.sparkline.min.js'); ?>" type="text/javascript"></script>
            <script src="<?php echo base_url('assets/global/plugins/jqvmap/jqvmap/jquery.vmap.js'); ?>" type="text/javascript"></script>
            <script src="<?php echo base_url('assets/global/plugins/jqvmap/jqvmap/maps/jquery.vmap.russia.js'); ?>" type="text/javascript"></script>
            <script src="<?php echo base_url('assets/global/plugins/jqvmap/jqvmap/maps/jquery.vmap.world.js'); ?>" type="text/javascript"></script>
            <script src="<?php echo base_url('assets/global/plugins/jqvmap/jqvmap/maps/jquery.vmap.europe.js'); ?>" type="text/javascript"></script>
            <script src="<?php echo base_url('assets/global/plugins/jqvmap/jqvmap/maps/jquery.vmap.germany.js'); ?>" type="text/javascript"></script>
            <script src="<?php echo base_url('assets/global/plugins/jqvmap/jqvmap/maps/jquery.vmap.usa.js'); ?>" type="text/javascript"></script>
            <script src="<?php echo base_url('assets/global/plugins/jqvmap/jqvmap/data/jquery.vmap.sampledata.js'); ?>" type="text/javascript"></script>
            
            
            <script src="<?php echo base_url('assets/pages/scripts/dashboard.min.js'); ?>" type="text/javascript"></script>
            <?php
            break;
        
        
        
        
            /****************************************************************************************************/
            //List :: User / Rate Mobile / Rate Landline
            /****************************************************************************************************/
            case base_url('admin/users'):
            case base_url('admin/ratesmobile'):
            case base_url('admin/rateslandline'):
            case base_url('admin/optionsmobile'):
            case base_url('admin/optionslandline'):
            ?>   
            <script src="<?php echo base_url('assets/global/scripts/datatable.js'); ?>" type="text/javascript"></script>
            <script src="<?php echo base_url('assets/global/plugins/datatables/datatables.min.js'); ?>" type="text/javascript"></script>
            <script src="<?php echo base_url('assets/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.js'); ?>" type="text/javascript"></script>
            <script src="<?php echo base_url('assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js'); ?>" type="text/javascript"></script>            
            <script src="<?php echo base_url('assets/global/plugins/jquery-ui/jquery-ui.min.js'); ?>" type="text/javascript"></script>
            <script src="<?php echo base_url('assets/pages/scripts/ui-modals.js'); ?>" type="text/javascript"></script>
            
            <?php            
            if(current_url()==base_url('admin/users')){
                ?>
                <script>
                    var admin_url = '<?php echo base_url('admin/users/ajax');?>';
                    var datatable_id = 'user_datatable_ajax';
                    var datatable_pagelength = 10;
                    var datatable_style = 2;
                </script>
                <script src="<?php echo base_url('assets/pages/scripts/table-datatables-ajax.js'); ?>" type="text/javascript"></script>            
                <script>
                    jQuery(document).ready(function() {
                        if(datatable_style==1){
                            TableUserDatatablesAjax.init();
                        }
                        else{
                            TableUserDatatablesAjax2.init();
                        }
                    });
                </script>    
                <?php    
            }
            else if(current_url()==base_url('admin/ratesmobile')){
                ?>
                <script>
                    var admin_url = '<?php echo base_url('admin/ratesmobile/ajax');?>';
                    var datatable_id = 'ratemobile_datatable_ajax';
                    var datatable_pagelength = 10;                    
                </script>
                <script src="<?php echo base_url('assets/pages/scripts/table-datatables-ajax.js'); ?>" type="text/javascript"></script>            
                <script>
                    jQuery(document).ready(function() {
                        TableRateMobileDatatablesAjax.init();
                    });
                </script>    
                <?php   
            }
            else if(current_url()==base_url('admin/rateslandline')){
                ?>
                <script>
                    var admin_url = '<?php echo base_url('admin/rateslandline/ajax');?>';
                    var datatable_id = 'ratelandline_datatable_ajax';
                    var datatable_pagelength = 10;                    
                </script>
                <script src="<?php echo base_url('assets/pages/scripts/table-datatables-ajax.js'); ?>" type="text/javascript"></script>            
                <script>
                    jQuery(document).ready(function() {
                        TableRateLandlineDatatablesAjax.init();
                    });
                </script>    
                <?php   
            }    
            else if(current_url()==base_url('admin/optionsmobile')){
                ?>
                <script>
                    var admin_url = '<?php echo base_url('admin/optionsmobile/ajax');?>';
                    var datatable_id = 'optionmobile_datatable_ajax';
                    var datatable_pagelength = 10;                    
                </script>
                <script src="<?php echo base_url('assets/pages/scripts/table-datatables-ajax.js'); ?>" type="text/javascript"></script>            
                <script>
                    jQuery(document).ready(function() {
                        TableOptionMobileDatatablesAjax.init();
                    });
                </script>    
                <?php   
            }
            else if(current_url()==base_url('admin/optionslandline')){
                ?>
                <script>
                    var admin_url = '<?php echo base_url('admin/optionslandline/ajax');?>';
                    var datatable_id = 'optionlandline_datatable_ajax';
                    var datatable_pagelength = 10;                    
                </script>
                <script src="<?php echo base_url('assets/pages/scripts/table-datatables-ajax.js'); ?>" type="text/javascript"></script>            
                <script>
                    jQuery(document).ready(function() {
                        TableOptionLandlineDatatablesAjax.init();
                    });
                </script>    
                <?php   
            }  
            break;
        
            
            
            /****************************************************************************************************/
            //Add/Edit :: User or Rate Mobile or Rate Landline 
            /****************************************************************************************************/
            case base_url('admin/users/user'):
            case base_url('admin/users/user/'.$this->uri->segment(4)):                
                
            case base_url('admin/ratesmobile/rate'):
            case base_url('admin/ratesmobile/rate/'.$this->uri->segment(4)):                
            case base_url('admin/rateslandline/rate'):
            case base_url('admin/rateslandline/rate/'.$this->uri->segment(4)):
                
            case base_url('admin/optionsmobile/option'):
            case base_url('admin/optionsmobile/option/'.$this->uri->segment(4)):                
            case base_url('admin/optionslandline/option'):
            case base_url('admin/optionslandline/option/'.$this->uri->segment(4)):
            ?>   
            <script src="<?php echo base_url('assets/global/plugins/bootstrap-fileinput/bootstrap-fileinput.js'); ?>" type="text/javascript"></script>
            <script src="<?php echo base_url('assets/global/plugins/jquery.sparkline.min.js'); ?>" type="text/javascript"></script>
            <script src="<?php echo base_url('assets/global/plugins/jquery-validation/js/jquery.validate.min.js'); ?>" type="text/javascript"></script>
            <script src="<?php echo base_url('assets/global/plugins/jquery-validation/js/additional-methods.min.js'); ?>" type="text/javascript"></script>
            <script src="<?php echo base_url('assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js'); ?>" type="text/javascript"></script>
            
            <?php
            if(current_url()==base_url('admin/users/user') || current_url()==base_url('admin/users/user/'.$this->uri->segment(4))){
                ?>
                <script>
                    var form_id = 'form_user';    
                    var edit_id = '<?php echo isset($userid)?$userid:''?>';
                </script>
                <script src="<?php echo base_url('assets/pages/scripts/form-validation.js'); ?>" type="text/javascript"></script>
                <script>
                    jQuery(document).ready(function() {
                        FormUserValidation.init();
                    });
                </script>
                <?php
            }
            else if(current_url()==base_url('admin/ratesmobile/rate') || current_url()==base_url('admin/ratesmobile/rate/'.$this->uri->segment(4))){
                ?>
                <script>
                    var form_id = 'form_ratemobile';    
                    var edit_id = '<?php echo isset($ratenr)?$ratenr:''?>';
                </script>
                <script src="<?php echo base_url('assets/pages/scripts/form-validation.js'); ?>" type="text/javascript"></script>
                <script>
                    jQuery(document).ready(function() {
                        FormRateMobileValidation.init();
                    });
                </script>
                <?php
            }
            else if(current_url()==base_url('admin/rateslandline/rate') || current_url()==base_url('admin/rateslandline/rate/'.$this->uri->segment(4))){
                ?>
                <script>
                    var form_id = 'form_ratelandline';    
                    var edit_id = '<?php echo isset($ratenr)?$ratenr:''?>';
                </script>
                <script src="<?php echo base_url('assets/pages/scripts/form-validation.js'); ?>" type="text/javascript"></script>
                <script>
                    jQuery(document).ready(function() {
                        FormRateLandlineValidation.init();
                    });
                </script>
                <?php
            }
            else if(current_url()==base_url('admin/optionsmobile/option') || current_url()==base_url('admin/optionsmobile/option/'.$this->uri->segment(4))){
                ?>
                <script>
                    var form_id = 'form_optionmobile';    
                    var edit_id = '<?php echo isset($optionnr)?$optionnr:''?>';
                </script>
                <script src="<?php echo base_url('assets/pages/scripts/form-validation.js'); ?>" type="text/javascript"></script>
                <script>
                    jQuery(document).ready(function() {
                        FormOptionMobileValidation.init();
                    });
                </script>
                <?php
            }
            else if(current_url()==base_url('admin/optionslandline/option') || current_url()==base_url('admin/optionslandline/option/'.$this->uri->segment(4))){
                ?>
                <script>
                    var form_id = 'form_optionlandline';    
                    var edit_id = '<?php echo isset($optionnr)?$optionnr:''?>';
                </script>
                <script src="<?php echo base_url('assets/pages/scripts/form-validation.js'); ?>" type="text/javascript"></script>
                <script>
                    jQuery(document).ready(function() {
                        FormOptionLandlineValidation.init();
                    });
                </script>
                <?php
            }
            
            break;
        }
        ?>            
        <!-- END PAGE LEVEL PLUGINS -->
        
        
        
        <!-- BEGIN THEME LAYOUT SCRIPTS -->
        <script src="<?php echo base_url('assets/layouts/layout/scripts/layout.min.js'); ?>" type="text/javascript"></script>
        <script src="<?php echo base_url('assets/layouts/layout/scripts/demo.min.js'); ?>" type="text/javascript"></script>
        <script src="<?php echo base_url('assets/layouts/global/scripts/quick-sidebar.min.js'); ?>" type="text/javascript"></script>
        <!-- END THEME LAYOUT SCRIPTS -->
    </body>

</html>