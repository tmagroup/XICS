<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<?php $this->load->view('admin/header.php'); ?>
<style>
.control-label input.required, .form-group input.required{
	color: #4d6b8a;
	padding: 6px 12px;
	font-size: 14px;
}
</style>
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
								<a href="<?php echo base_url('admin/subcustomers');?>"><?php echo lang('page_customers');?></a>
								<i class="fa fa-circle"></i>
							</li>

							<li>
								<span>
									<?php
									if(isset($customer['customernr']) && $customer['customernr']>0){
										echo lang('page_edit_customer');
									}
									else
									{
										echo lang('page_create_customer');
									}
									?>
								</span>
							</li>

						</ul>

					</div>
					<!-- END PAGE BAR -->

					<!-- BEGIN PAGE MESSAGE-->
					<?php $this->load->view('admin/alerts'); ?>
					<!-- BEGIN PAGE MESSAGE-->

					<!-- BEGIN PAGE TITLE-->
					<h3 class="page-title">

						<?php
						if(isset($customer['customernr']) && $customer['customernr']>0){
							?>
							<i class="fa fa-pencil"></i>
							<?php
							echo lang('page_edit_customer');
						}
						else
						{
							?>
							<i class="fa fa-user-plus"></i>
							<?php
							echo lang('page_create_customer');
						}
						?>

					</h3>
					<!-- END PAGE TITLE-->
					<!-- END PAGE HEADER-->

					<div class="row">



						<ul class="nav nav-tabs">
							<li class="active">
								<a href="#tab_profile" data-toggle="tab"><?php echo lang('page_lb_profile');?></a>
							</li>
						</ul>


						<div class="tab-content">

							<div class="tab-pane active" id="tab_profile">
								<?php echo form_open($this->uri->uri_string(), array('enctype' => "multipart/form-data", 'id' => 'form_subcustomer') );?>
								<div class="col-md-6">


									<!-- BEGIN SAMPLE FORM PORTLET-->
									<div class="portlet light bordered">
										<div class="portlet-body form">

											<div class="form-body">

												<div class="form-group">
													<label><?php echo lang('page_fl_username');?> <span class="required"> * </span></label>
													<?php $readonly = (isset($customer['customernr'])) ? 'readonly' : ''; ?>
													<?php echo form_input('username', isset($customer['username'])?$customer['username']:'', 'class="form-control" '.$readonly);?>
												</div>

												<div class="form-group">
													<label><?php echo lang('page_fl_userpassword');?> <?php if (!isset($customer['customernr'])): echo '<span class="required"> * </span>'; endif ?></label>
													<?php echo form_password('password', "", 'class="form-control" id="submit_form_password"');?>
												</div>

												<div class="form-group">
													<label><?php echo lang('page_fl_cpassword');?> <?php if (!isset($customer['customernr'])): echo '<span class="required"> * </span>'; endif ?></label>
													<?php echo form_password('cpassword', "", 'class="form-control"');?>
												</div>

												<div class="form-group">
													<label><?php echo lang('page_fl_salutation');?> <span class="required"> * </span></label>
													<?php echo form_dropdown('salutation', $salutations, isset($customer['salutation'])?$customer['salutation']:'', 'class="form-control"');?>
												</div>

												<div class="form-group">
													<label><?php echo lang('page_fl_surname');?> <span class="required"> * </span></label>
													<?php echo form_input('surname', isset($customer['surname'])?$customer['surname']:'', 'class="form-control"');?>
												</div>

												<div class="form-group">
													<label><?php echo lang('page_fl_name');?> <span class="required"> * </span></label>
													<?php echo form_input('name', isset($customer['name'])?$customer['name']:'', 'class="form-control"');?>
												</div>

												<div class="form-group">
													<label><?php echo lang('page_fl_position');?> </label>
													<?php echo form_input('position', isset($customer['position'])?$customer['position']:'', 'class="form-control"');?>
												</div>

												<div class="form-group">
													<label><?php echo lang('page_fl_email');?> <span class="required"> * </span></label>
													<?php echo form_input(array('type'=>'email','name'=>'email'), isset($customer['email'])?$customer['email']:'', 'class="form-control"');?>
												</div>

												<div class="form-group">
													<label><?php echo lang('page_fl_phonenumber');?> <span class="required"> * </span></label>
													<?php echo form_input('phone', isset($customer['phone'])?$customer['phone']:'', 'class="form-control"');?>
												</div>

												<div class="form-group">
													<label><?php echo lang('page_fl_mobilnr');?></label>
													<?php echo form_input('mobilnr', isset($customer['mobilnr'])?$customer['mobilnr']:'', 'class="form-control"');?>
												</div>

												<div class="form-group">
													<label><?php echo lang('page_fl_directdialing');?> </label>
													<?php echo form_input('directdialing', isset($customer['directdialing'])?$customer['directdialing']:'', 'class="form-control"');?>
												</div>

												<div class="form-group">
													<label><?php echo lang('page_fl_faxnr');?> </label>
													<?php echo form_input('faxnr', isset($customer['faxnr'])?$customer['faxnr']:'', 'class="form-control"');?>
												</div>
											</div>

										</div>
									</div>
									<!-- END SAMPLE FORM PORTLET-->

								</div>


								<div class="col-md-6">

									<!-- BEGIN SAMPLE FORM PORTLET-->
									<div class="portlet light bordered">
										<div class="portlet-body form">

											<div class="form-body">

												<div class="form-group">
													<label><?php echo lang('page_fl_customerthumb');?> <!--<span class="required"> * </span>--></label>
													<div class="clearfix"></div>
													<div class="fileinput fileinput-new" data-provides="fileinput">
														<div class="fileinput-new thumbnail" style="width: 160px; height: 160px;">

															<!--<img src="<?php echo base_url('assets/pages/img/avatars/user-placeholder.jpg');?>" alt="" />-->
															<?php
															$customernr = isset($customer['customernr'])?$customer['customernr']:'';
															echo customer_profile_image($customernr,array('customer-profile-image'),'thumb');
															?>

														</div>
														<div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 160px; max-height: 160px;"> </div>
														<div>
															<span class="btn default btn-file">
																<span class="fileinput-new"> <?php echo lang('page_lb_selectimage');?> </span>
																<span class="fileinput-exists"> <?php echo lang('page_lb_change');?> </span>
																<!--<input type="file" name="...">-->
																<?php
																echo form_upload('customerthumb');
																?>
															</span>
															<a href="javascript:;" class="btn default fileinput-exists" data-dismiss="fileinput"> <?php echo lang('page_lb_remove');?> </a>
														</div>
													</div>
													<div class="clearfix margin-top-10">
														<span class="label label-danger"><?php echo lang('page_lb_note');?> </span>
														<span>&nbsp;<?php echo lang('page_lb_selectimage_note_text');?></span>
													</div>
												</div>

												<div class="form-group">
													<label><?php echo lang('page_fl_lastcontact');?> </label>

													<div class="input-group date form_datetime">
														<?php $dd = array('name'=>'lastcontact', 'class'=>'form-control', 'readonly'=>true, 'size'=>16, 'value'=> isset($customer['lastcontact'])?_dt($customer['lastcontact']):_dt(date('Y-m-d H:i:s')));
														echo form_input($dd);?>

														<span class="input-group-btn">
															<button class="btn default date-set" type="button">
																<i class="fa fa-calendar"></i>
															</button>
														</span>
													</div>
												</div>

												<div class="form-group">
													<label>Benutzer-Rolle<span class="required"> * </span></label>
													<?php echo form_dropdown('customer_role', array('' => 'Wählen', '1' => 'Mitarbeiter', '2' => 'Buchhaltung', '3' => 'Controlling'), isset($customer['customer_role']) ? $customer['customer_role'] : '', 'class="form-control"'); ?>
												</div>
											</div>
										</div>
									</div>
									<!-- END SAMPLE FORM PORTLET-->

								</div>

								<div class="clearfix"></div>
								<div class="col-md-6">
									<!-- BEGIN SAMPLE FORM PORTLET-->
									<div class="portlet light">
										<div class="portlet-body">
											<div class="form-body">
												<div class="form-actions">
													<button type="submit" class="btn blue"><?php echo lang('save');?></button>
													<a href="<?php echo base_url('admin/subcustomers')?>"><button type="button" class="btn default"><?php echo lang('cancel');?></button></a>
												</div>
											</div>
										</div>
									</div>
									<!-- END SAMPLE FORM PORTLET-->
								</div>

								<?php echo form_close();?>
							</div>
						</div>
					</div>
				</div>
				<!-- END CONTENT BODY -->
			</div>
			<!-- END CONTENT -->
		</div>
		<!-- END CONTAINER -->
<script type="text/javascript">
	var form_id = 'form_subcustomer';
	var func_FormValidation = 'FormCustomValidation';

	function after_func_FormValidation(form1, error1, success1){

		form1.validate({
			errorElement: 'span', //default input error message container
			errorClass: 'help-block help-block-error', // default input error message class
			focusInvalid: false, // do not focus the last invalid input
			ignore: "",  // validate all fields including form hidden input

			rules: {
				username: {
					minlength: 2,
					required: true
				},
				password: {
					minlength: 5,
					required: <?php echo isset($customer['customernr'])?'false':'true'?>
				},
				cpassword: {
					minlength: 5,
					required: <?php echo isset($customer['customernr'])?'false':'true'?>,
					equalTo: "#submit_form_password"
				},
				salutation: {
					required: true
				},
				surname: {
					minlength: 2,
					required: true
				},
				name: {
					minlength: 2,
					required: true
				},
				/*position: {
					required: true
				},*/
				email: {
					required: true,
					email: true
				},
				phone: {
					required: true
				},
				/*mobilnr: {
					required: true
				},
				directdialing: {
					required: true
				},
				faxnr: {
					required: true
				},*/
				customerthumb: {
					extension: "jpg|jpeg|png"
				},
				customer_role: {
					required: true,
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
</script>
<?php $this->load->view('admin/footer.php'); ?>
<?php $this->load->view('admin/subcustomers/customerjs',array('customer'=>isset($customer)?$customer:'', 'remindersubjects'=>$remindersubjects));?>
