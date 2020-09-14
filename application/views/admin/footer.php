<!-- Delete Confirmation -->
<div id="pageloaddiv" style="display: none;"></div>

<div class="modal fade" id="deleteConfirmationAjax" tabindex="-1" role="dialog" style="z-index:10051;">
	<div class="modal-dialog" role="document">
		<?php echo form_open("",array("id"=>"deleteModalAjax")); ?>
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title"></h4>
			</div>
			<div class="modal-body">
				<div class="delete_id">
                                    <?php echo form_hidden('id'); ?>
				</div>
                                <div class="parent_id">
                                    <?php echo form_hidden('parentid'); ?>
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
                                <div class="parent_id">
                                    <?php echo form_hidden('parentid'); ?>
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
            <div class="page-footer-inner text-center"> <?php echo date('Y');?> &copy; <?php echo get_option('company_name');?></div>
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
        <script src="<?php echo base_url('assets/global/plugins/counterup/jquery.waypoints.min.js'); ?>" type="text/javascript"></script>
        <script src="<?php echo base_url('assets/global/plugins/counterup/jquery.counterup.min.js'); ?>" type="text/javascript"></script>
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
            <!--<script src="<?php echo base_url('assets/global/plugins/moment.min.js'); ?>" type="text/javascript"></script>
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
            <script src="<?php echo base_url('assets/global/plugins/jqvmap/jqvmap/data/jquery.vmap.sampledata.js'); ?>" type="text/javascript"></script>            <script src="<?php echo base_url('assets/pages/scripts/dashboard.min.js'); ?>" type="text/javascript"></script>-->

            <script src="<?php echo base_url('assets/global/plugins/bootstrap-toastr/toastr.js'); ?>" type="text/javascript"></script>

            <script src="<?php echo base_url('assets/global/plugins/jquery-ui/jquery-ui.min.js'); ?>" type="text/javascript"></script>
            <script src="<?php echo base_url('assets/pages/scripts/portlet-draggable.js'); ?>" type="text/javascript"></script>
            <?php
            break;

            /****************************************************************************************************/
            //List and Form
            /****************************************************************************************************/
            default:
            ?>
            <!-- List -->
            <script src="<?php echo base_url('assets/global/plugins/select2/js/select2.full.min.js'); ?>" type="text/javascript"></script>
            <script src="<?php echo base_url('assets/global/scripts/datatable.js'); ?>" type="text/javascript"></script>
            <script src="<?php echo base_url('assets/global/plugins/datatables/datatables.min.js'); ?>" type="text/javascript"></script>
            <script src="<?php echo base_url('assets/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.js'); ?>" type="text/javascript"></script>
            <script src="<?php echo base_url('assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js'); ?>" type="text/javascript"></script>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/2.5.0/jszip.min.js" type="text/javascript"></script>
            <!--<script src="<?php echo base_url('assets/global/plugins/jquery-ui/jquery-ui.min.js'); ?>" type="text/javascript"></script>-->
            <script src="<?php echo base_url('assets/pages/scripts/modals.js?v=3'); ?>" type="text/javascript"></script>
            <script src="<?php echo base_url('assets/pages/scripts/table-datatables-ajax.js?v=5'); ?>" type="text/javascript"></script>
            <script>
                if (typeof func_TableDatatablesAjax !== 'undefined') {
                    jQuery(document).ready(function() {
                        eval(func_TableDatatablesAjax + "('"+admin_url+"')");
                    });
                }
				if (typeof func_TableDatatablesAjax_2 !== 'undefined') {
                    jQuery(document).ready(function() {
                        eval(func_TableDatatablesAjax_2 + "('"+admin_url_2+"')");
                    });
                }
				if (typeof func_TableDatatablesAjax_3 !== 'undefined') {
                    jQuery(document).ready(function() {
                        eval(func_TableDatatablesAjax_3 + "('"+admin_url_3+"')");
                    });
                }
				if (typeof func_TableDatatablesAjax_4 !== 'undefined') {
                    jQuery(document).ready(function() {
                        eval(func_TableDatatablesAjax_4 + "('"+admin_url_4+"')");
                    });
                }
				if (typeof func_TableDatatablesAjax_5 !== 'undefined') {
                    jQuery(document).ready(function() {
                        eval(func_TableDatatablesAjax_5 + "('"+admin_url_5+"')");
                    });
                }
				if (typeof func_TableDatatablesAjax_6 !== 'undefined') {
                    jQuery(document).ready(function() {
                        eval(func_TableDatatablesAjax_6 + "('"+admin_url_6+"')");
                    });
                }
                if (typeof func_TableDatatablesAjax_7 !== 'undefined') {
                    jQuery(document).ready(function() {
                        eval(func_TableDatatablesAjax_7 + "('"+admin_url_7+"')");
                    });
                }
            </script>

            <!-- Add/Edit Form -->
            <script src="<?php echo base_url('assets/global/plugins/bootstrap-fileinput/bootstrap-fileinput.js'); ?>" type="text/javascript"></script>
            <script src="<?php echo base_url('assets/global/plugins/jquery.sparkline.min.js'); ?>" type="text/javascript"></script>
            <script src="<?php echo base_url('assets/global/plugins/jquery-validation/js/jquery.validate.min.js'); ?>" type="text/javascript"></script>
            <script src="<?php echo base_url('assets/global/plugins/jquery-validation/js/additional-methods.min.js'); ?>" type="text/javascript"></script>
            <script src="<?php echo base_url('assets/pages/scripts/form-validation.js'); ?>" type="text/javascript"></script>
            <script src="<?php echo base_url('assets/global/plugins/dropzone/dropzone.min.js'); ?>" type="text/javascript"></script>
            <script src="<?php echo base_url('assets/global/plugins/bootstrap-toastr/toastr.js'); ?>" type="text/javascript"></script>
            <script src="<?php echo base_url('assets/global/plugins/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js'); ?>" type="text/javascript"></script>

            <!-- Calendar -->
            <script src="<?php echo base_url('assets/global/plugins/moment.min.js'); ?>" type="text/javascript"></script>

        	<script src="<?php echo base_url('assets/global/plugins/fullcalendar/fullcalendar.min.js'); ?>" type="text/javascript"></script>
	        <!-- End Calendar -->

            <script>
				jQuery(document).ready(function() {
					if (typeof func_FormValidation !== 'undefined') {
						eval(func_FormValidation + "()");
					}

					if (typeof func_FormValidation2 !== 'undefined') {
						if(typeof inner_msg_id2 !== 'undefined'){
							eval(func_FormValidation2 + "('"+inner_msg_id2+"')");
						}
						else{
							eval(func_FormValidation2 + "()");
						}
					}

					if (typeof func_FormValidation3 !== 'undefined') {
						if(typeof inner_msg_id3 !== 'undefined'){
							eval(func_FormValidation3 + "('"+inner_msg_id3+"')");
						}
						else{
							eval(func_FormValidation3 + "()");
						}
					}
				});
            </script>
            <?php
            break;
        }
        ?>
        <!-- END PAGE LEVEL PLUGINS -->




        <!-- BEGIN THEME LAYOUT SCRIPTS -->
        <audio id="bell" src="<?php echo base_url('assets/global/img/bell.mp3');?>" autostart="false" ></audio>
        <script src="<?php echo base_url('assets/layouts/layout/scripts/layout.min.js'); ?>" type="text/javascript"></script>
        <script src="<?php echo base_url('assets/layouts/layout/scripts/demo.min.js'); ?>" type="text/javascript"></script>
        <script src="<?php echo base_url('assets/layouts/global/scripts/quick-sidebar.min.js'); ?>" type="text/javascript"></script>
        <script src="<?php echo base_url('assets/pages/scripts/custom.js?v=3'); ?>" type="text/javascript"></script>
        <?php $this->load->view('admin/alerts_reminder'); ?>
        <!-- END THEME LAYOUT SCRIPTS -->


        <script>
        /* Not Required Sidebar for Some Large Pages */
        <?php
		if(current_url()==base_url('admin/quotations/quotation')
		|| current_url()==base_url('admin/quotations/quotation/'.$this->uri->segment(4))
		|| current_url()==base_url('admin/quotations/detail/'.$this->uri->segment(4))
		|| current_url()==base_url('admin/assignments/assignment')
		|| current_url()==base_url('admin/assignments/assignment/'.$this->uri->segment(4))
		|| current_url()==base_url('admin/assignments/detail/'.$this->uri->segment(4))
		|| current_url()==base_url('admin/hardwareassignments/hardwareassignment/'.$this->uri->segment(4))
		|| current_url()==base_url('admin/hardwareassignments/detail/'.$this->uri->segment(4))
		){
			?>
			setTimeout(function(){ jQuery('.menu-toggler').click(); },500);
			<?php
		}
		?>
        </script>
    </body>
</html>
