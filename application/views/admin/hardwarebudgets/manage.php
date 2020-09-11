<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<?php $this->load->view('admin/header.php'); ?>

<body class="page-header-fixed page-sidebar-closed-hide-logo page-content-white">

	<?php $this->load->view('admin/topnavigation.php'); ?>

	<!-- BEGIN HEADER & CONTENT DIVIDER -->
	<div class="clearfix"></div>
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
							<span>Hardware-Budget</span>
						</li>
					</ul>

					<div class="page-toolbar">
						<div class="btn-group btn-group-devided pull-right">
							<?php if ( $GLOBALS['current_user']->userrole == 1 || $GLOBALS['current_user']->userrole == 2 || $GLOBALS['current_user']->userrole == 4 && ( !(isset($GLOBALS['current_user']->customer_role) && !empty($GLOBALS['current_user']->customer_role) && ($GLOBALS['current_user']->customer_role !== 1)) ) ) { ?>
								<button id="button_add_hardware_budget" name="button_add_hardware_budget" class="btn sbold blue btn-sm" onclick="onclick_button_add_hardware_budget(this);">Hardware-Budget hinzufügen</button>
								<div id="modal_add_hardware_budget" class="modal fade" tabindex="-1" role="dialog">
									<div class="modal-dialog" role="document">
										<?php echo form_open_multipart($this->uri->uri_string() .'/hardwarebudget', array('id' => 'form_add_hardware_budget', 'name' => 'form_add_hardware_budget') ); ?>
											<div class="modal-content">
												<div class="modal-header">
													<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
													<h4 class="modal-title"></h4>
												</div>

												<div class="modal-body">
													<!-- BEGIN PAGE MESSAGE-->
													<?php $this->load->view('admin/alerts_modal');?>
													<!-- BEGIN PAGE MESSAGE-->

													<div class="form-group">
														<label>Hardware-Budget von<span class="required"> * </span></label>
														<?php
															echo form_dropdown('provider', provider_values(), '', 'class="form-control"');
														?>
													</div>

													<div class="form-group">
														<label>Summe exkl. MwSt.<span class="required"> * </span></label>
														<?php
															echo form_input(array(
																'type' => 'number',
																'id' => 'total_excluding_vat',
																'name' => 'total_excluding_vat',
																'class' => 'form-control',
																'value' => '',
																'autofocus' => 'autofocus',
																'onkeypress' => 'return only_number(event);'
															));
														?>
													</div>

													<div class="form-group">
														<label>Gültig bis<span class="required"> * </span></label>
														<div class="input-group date form_datetime">
															<?php
																echo form_input(array(
																	'id' => 'date_of_expiry',
																	'name' => 'date_of_expiry',
																	'class' => 'form-control',
																	'value' => '',
																	'readonly' => true,
																	'size' => 16,
																	'autofocus' => 'autofocus',
																));
															?>
															<span class="input-group-btn">
																<button type="button" class="btn default date-set"><i class="fa fa-calendar"></i></button>
															</span>
														</div>
													</div>

													<div class="form-group">
														<label>Dokument</label>
														<div class="clearfix"></div>
														<div class="fileinput fileinput-new" data-provides="fileinput">
															<div class="fileinput-new thumbnail" style="width: 160px; height: 160px;"></div>
															<div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 160px; max-height: 160px;"></div>
															<div>
																<span class="btn default btn-file">
																	<span class="fileinput-new"> Datei </span>
																	<span class="fileinput-exists"> <?php echo lang('page_lb_change');?> </span>
																	<?php echo form_upload('budget_document'); ?>
																</span>
																<a href="javascript:void(0);" class="btn default fileinput-exists" data-dismiss="fileinput"> <?php echo lang('page_lb_remove');?> </a>
															</div>
														</div>
														<!-- <div class="clearfix margin-top-10">
															<span class="label label-danger"><?php echo lang('page_lb_note');?> </span>
															<span>&nbsp;<?php echo lang('page_lb_selectimage_note_text');?></span>
														</div> -->
													</div>
												</div>

												<div class="modal-footer">
													<button type="submit" class="btn btn-default blue">Speichern</button>
													<button type="button" class="btn btn-default" data-dismiss="modal">Abbrechen</button>
												</div>
											</div>
										</form>
									</div>
								</div>
							<?php } ?>
							<?php if ( $GLOBALS['current_user']->userrole == 1 || $GLOBALS['current_user']->userrole == 2 || $GLOBALS['current_user']->userrole == 4 || ( isset($GLOBALS['current_user']->customer_role) && !empty($GLOBALS['current_user']->customer_role) && ($GLOBALS['current_user']->customer_role !== 1) ) ) { ?>
								<button id="button_use_hardware_budget" name="button_use_hardware_budget" class="btn sbold blue btn-sm" onclick="onclick_button_use_hardware_budget(this);">Budget benutzen</button>
								<div id="modal_use_hardware_budget" class="modal fade" tabindex="-1" role="dialog">
									<div class="modal-dialog" role="document">
										<?php echo form_open_multipart($this->uri->uri_string() .'/hardwarebudgetuse', array('id' => 'form_use_hardware_budget', 'name' => 'form_use_hardware_budget') ); ?>
											<div class="modal-content">
												<div class="modal-header">
													<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
													<h4 class="modal-title"></h4>
												</div>

												<div class="modal-body">
													<!-- BEGIN PAGE MESSAGE-->
													<?php $this->load->view('admin/alerts_modal');?>
													<!-- BEGIN PAGE MESSAGE-->

													<div class="form-group">
														<label>Wofür nutzen Sie das Budget?<span class="required"> * </span></label>
														<?php
															echo form_dropdown('budget_for', array('' => lang('page_option_select'), 'Hardware Einkauf' => 'Hardware Einkauf', 'Gutschrift aufs Konto' => 'Gutschrift aufs Konto', 'Gutschrift zum Anbieter' => 'Gutschrift zum Anbieter'), '', 'class="form-control"');
														?>
													</div>

													<div class="form-group">
														<label>Summe exkl. MwSt.<span class="required"> * </span></label>
														<?php
															echo form_input(array(
																'type' => 'number',
																'id' => 'total_excluding_vat_use',
																'name' => 'total_excluding_vat_use',
																'class' => 'form-control',
																'value' => '',
																'autofocus' => 'autofocus',
																'onkeypress' => 'return only_number(event);'
															));
														?>
													</div>

													<div class="form-group">
														<label>Beschreibung</label>
														<?php
															echo form_textarea(array(
																'id' => 'use_description',
																'name' => 'use_description',
																'class' => 'form-control',
																'value' => '',
																'autofocus' => 'autofocus',
																'style' => 'resize: vertical;',
															));
														?>
													</div>

													<div class="form-group">
														<label>Wann haben Sie das Budget benutzt?<span class="required"> * </span></label>
														<div class="input-group date form_datetime">
															<?php
																echo form_input(array(
																	'id' => 'date_of_use',
																	'name' => 'date_of_use',
																	'class' => 'form-control',
																	'value' => '',
																	'readonly' => true,
																	'size' => 16,
																	'autofocus' => 'autofocus',
																));
															?>
															<span class="input-group-btn">
																<button type="button" class="btn default date-set"><i class="fa fa-calendar"></i></button>
															</span>
														</div>
													</div>

													<div class="form-group">
														<label>Dokument</label>
														<div class="clearfix"></div>
														<div class="fileinput fileinput-new" data-provides="fileinput">
															<div class="fileinput-new thumbnail" style="width: 160px; height: 160px;"></div>
															<div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 160px; max-height: 160px;"></div>
															<div>
																<span class="btn default btn-file">
																	<span class="fileinput-new"> Datei </span>
																	<span class="fileinput-exists"> <?php echo lang('page_lb_change');?> </span>
																	<?php echo form_upload('budget_use_document'); ?>
																</span>
																<a href="javascript:void(0);" class="btn default fileinput-exists" data-dismiss="fileinput"> <?php echo lang('page_lb_remove');?> </a>
															</div>
														</div>
														<!-- <div class="clearfix margin-top-10">
															<span class="label label-danger"><?php echo lang('page_lb_note');?> </span>
															<span>&nbsp;<?php echo lang('page_lb_selectimage_note_text');?></span>
														</div> -->
													</div>
												</div>

												<div class="modal-footer">
													<button type="submit" class="btn btn-default blue">Speichern</button>
													<button type="button" class="btn btn-default" data-dismiss="modal">Abbrechen</button>
												</div>
											</div>
										</form>
									</div>
								</div>
							<?php } ?>
						</div>
					</div>
				</div>
				<!-- END PAGE BAR -->



				<!-- BEGIN PAGE MESSAGE-->
				<?php $this->load->view('admin/alerts'); ?>
				<!-- BEGIN PAGE MESSAGE-->



				<!-- BEGIN PAGE TITLE-->
				<h3 class="page-title"><i class="icon-settings"></i> Verwalten Hardware Budget</h3>
				<!-- END PAGE TITLE-->


				<div class="row widget-row">
					<div class="col-md-4">
						<!-- BEGIN WIDGET THUMB -->
						<div class="widget-thumb widget-bg-color-white text-uppercase margin-bottom-20 bordered">
							<h4 class="widget-thumb-heading">Aktuelles Hardware-Budget</h4>
							<div class="widget-thumb-wrap">
								<i class="widget-thumb-icon bg-green icon-settings"></i>
								<div class="widget-thumb-body">
									<span class="widget-thumb-subtitle"></span>
									<span class="widget-thumb-body-stat" style="display: inline-block;" data-counter="counterup" data-value="<?php echo format_money($latest_hardware_budget); ?>">0</span> <span style="display: inline-block; font-size: 30px; font-weight: 600; color: #3E4F5E;">&euro;</span>
								</div>
							</div>
						</div>
						<!-- END WIDGET THUMB -->
					</div>
				</div>


				<!-- END PAGE HEADER-->
				<div class="row">
					<div class="col-md-12">

						<!-- Begin: life time stats -->
						<div class="portlet light portlet-fit portlet-datatable bordered">
							<!-- <div class="portlet-title filterby"></div> -->

							<div class="portlet-body">
								<div>
									<h1>Budget</h1>
									<table id="datatable_ajax_hardwarebudget" class="table table-striped table-bordered table-hover dt-responsive" width="100%">
										<thead>
											<tr role="row" class="heading">
												<th></th>
												<th width="25%">Hardware-Budget von</th>
												<th width="25%">Dokument</th>
												<th width="25%">Summe exkl. MwSt.</th>
												<th width="25%">Gültig bis</th>
											</tr>
										</thead>
										<tbody></tbody>
									</table>
								</div>

								<hr />

								<div>
									<h1>Nutzen</h1>
									<table id="datatable_ajax_hardwarebudgetuse" class="table table-striped table-bordered table-hover dt-responsive" width="100%">
										<thead>
											<tr role="row" class="heading">
												<th></th>
												<th width="20%">Wofür nutzen Sie das Budget?</th>
												<th width="20%">Dokument</th>
												<th width="20%">Summe exkl. MwSt.</th>
												<th width="20%">Beschreibung</th>
												<th width="20%">Wann haben Sie das Budget benutzt?</th>
											</tr>
										</thead>
										<tbody></tbody>
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
<script type="text/javascript">
	var admin_url = '<?php echo base_url('admin/hardwarebudgets/ajax/1'); ?>';
	var func_TableDatatablesAjax = 'TableCustomDatatablesAjax';
	var datatable_id = 'datatable_ajax_hardwarebudget';
	var datatable_pagelength = '<?php echo get_option('tables_pagination_limit'); ?>';
	var datatable_columnDefs = 0;
	var datatable_columnDefs2 = 0;
	var datatable_sortColumn = 4;
	var datatable_sortColumnBy = 'asc';
	var datatable_hide_columns = 0;

	var admin_url_2 = '<?php echo base_url('admin/hardwarebudgets/ajax/2'); ?>';
	var func_TableDatatablesAjax_2 = 'TableCustomDatatablesAjax_2';
	var datatable_id_2 = 'datatable_ajax_hardwarebudgetuse';
	var datatable_pagelength_2 = '<?php echo get_option('tables_pagination_limit'); ?>';
	var datatable_columnDefs_2 = 0;
	var datatable_columnDefs2_2 = 0;
	var datatable_sortColumn_2 = 5;
	var datatable_sortColumnBy_2 = 'asc';
	var datatable_hide_columns_2 = 0;
</script>
<?php $this->load->view('admin/footer.php'); ?>
<script type="text/javascript">
	jQuery(document).ready(function() {
		// Date Picker Initialize
		jQuery('.form_datetime').datepicker({
			autoclose: true,
			isRTL: App.isRTL(),
			pickerPosition: (App.isRTL() ? 'bottom-right' : 'bottom-left'),
			format: 'dd.mm.yyyy',
		});
	});

	function onclick_button_add_hardware_budget( button_p ) {
		jQuery('#modal_add_hardware_budget').modal('show');
	}

	function onclick_button_use_hardware_budget( button_p ) {
		jQuery('#modal_use_hardware_budget').modal('show');
	}
</script>
<script type="text/javascript">
	var form_id = 'form_add_hardware_budget';
	var func_FormValidation = 'FormCustomValidation';

	function after_func_FormValidation(form1, error1, success1){
		form1.validate({
			errorElement: 'span', //default input error message container
			errorClass: 'help-block help-block-error', // default input error message class
			focusInvalid: false, // do not focus the last invalid input
			ignore: '', // validate all fields including form hidden input
			rules: {
				provider: {
					required: true,
				},
				total_excluding_vat: {
					required: true,
				},
				date_of_expiry: {
					required: true,
				},
				document: {
					extension: 'jpg|jpeg|png|pdf',
				},
			},
			invalidHandler: function (event, validator) { //display error alert on form submit
					success1.hide();
					error1.show();
					App.scrollTo(error1, -200);
			},
			highlight: function (element) { // hightlight error inputs
					$(element)
							.closest('.form-group').addClass('has-error'); // set error class to the control group
			},
			unhighlight: function (element) { // revert the change done by hightlight
					$(element)
							.closest('.form-group').removeClass('has-error'); // set error class to the control group
			},
			success: function (label) {
					label
							.closest('.form-group').removeClass('has-error'); // set success class to the control group
			},
			submitHandler: function (form) {
					//success1.show();
					error1.hide();
					App.scrollTo(error1, -200);
					return true;
			}
		});
	}

	jQuery('#form_use_hardware_budget').validate({
		errorElement: 'span', //default input error message container
		errorClass: 'help-block help-block-error', // default input error message class
		focusInvalid: false, // do not focus the last invalid input
		ignore: '', // validate all fields including form hidden input
		rules: {
			budget_for: {
				required: true,
			},
			total_excluding_vat_use: {
				required: true,
			},
			date_of_use: {
				required: true,
			},
			budget_use_document: {
				extension: 'jpg|jpeg|png|pdf',
			},
		},
		invalidHandler: function (event, validator) { //display error alert on form submit
			jQuery('.alert-danger').show();
			jQuery('.alert-success').hide();
			App.scrollTo(jQuery('.alert-danger'), -200);
		},
		highlight: function (element) { // hightlight error inputs
			jQuery(element).closest('.form-group').addClass('has-error'); // set error class to the control group
		},
		unhighlight: function (element) { // revert the change done by hightlight
			jQuery(element).closest('.form-group').removeClass('has-error'); // set error class to the control group
		},
		success: function (label) {
			label.closest('.form-group').removeClass('has-error'); // set success class to the control group
		},
		submitHandler: function (form) {
			jQuery('.alert-danger').hide();
			App.scrollTo(jQuery('.alert-danger'), -200);
			return true;
		}
	});

	function only_number( event_p ) {
		if ( isNaN(String.fromCharCode(event.keyCode)) ) {
			if ( String.fromCharCode(event.keyCode) === '.' ) {
				return true;
			}
			return false;
		}
	}
</script>
