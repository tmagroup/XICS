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
								<span><?php echo lang('page_customers');?></span>
							</li>
						</ul>
						<div class="page-toolbar">
							<div class="btn-group pull-right">
								<a href="<?php echo base_url('admin/subcustomers/customer');?>" class="btn sbold blue btn-sm"><i class="fa fa-plus"></i> Unterbenutzer</a>
							</div>
						</div>
					</div>
					<!-- END PAGE BAR -->



					<!-- BEGIN PAGE MESSAGE-->
					<?php $this->load->view('admin/alerts'); ?>
					<!-- BEGIN PAGE MESSAGE-->



					<!-- BEGIN PAGE TITLE-->
					<h3 class="page-title"><i class="fa fa-user"></i> <?php echo lang('page_manage_customer');?></h3>
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

									</div>
								</div>

								<div class="portlet-body">
									<div class="table-container">

										<div class="table-actions-wrapper"></div>

										<table class="table table-striped table-bordered table-hover dt-responsive" width="100%" id="customer_datatable_ajax">
											<thead>
												<tr role="row" class="heading">
													<th width="5%" class="all"> <?php echo lang('page_dt_customerthumb');?></th>
													<th width="7%" class="all"> <?php echo lang('page_dt_customernr');?></th>
													<th width="7%" class="all"> <?php echo lang('page_dt_responsibleuser');?></th>
													<th width="7%" class="all"> <?php echo lang('page_dt_company');?></th>
													<th width="7%" class="min-tablet"> <?php echo lang('page_dt_city');?></th>
													<th width="7%" class="min-tablet"> <?php echo lang('page_dt_name');?></th>
													<th width="7%" class="none"> <?php echo lang('page_dt_surname');?></th>
													<th width="7%" class="none"> <?php echo lang('page_dt_phone');?></th>
													<th width="7%" class="none"> <?php echo lang('page_dt_mobile');?></th>
													<th width="10%" class="all"> <?php echo lang('page_dt_active');?></th>
													<th width="10%" class="desktop"> <?php echo lang('page_dt_action');?></th>
													<th width="1%"> <?php echo lang('page_dt_customernr');?></th>
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
	var admin_url = '<?php echo base_url('admin/subcustomers/ajax');?>';
	var func_TableDatatablesAjax = 'TableCustomDatatablesAjax';
	var datatable_id = 'customer_datatable_ajax';
	var datatable_pagelength = '<?php echo get_option('tables_pagination_limit');?>';
	var datatable_columnDefs = 10; // 10;
	var datatable_columnDefs2 = 10; // 10;
	var datatable_sortColumn = 1;
	var datatable_sortColumnBy = 'desc';
	var datatable_hide_columns = 11; // 11;
</script>

<?php $this->load->view('admin/footer.php'); ?>


<script>
//Change Filter By User
jQuery("#filter_responsible").select2({
	placeholder: "<?php echo lang('page_lb_select_a_user');?>",
	allowClear: true
});

jQuery('#filter_responsible,#filter_customerstatus,#filter_product').change( function(){
	var admin_url = '<?php echo base_url('admin/subcustomers/ajax');?>';
	var filter_responsible = jQuery('#filter_responsible').val();
	var admin_url = admin_url + '/'+ eval(filter_responsible);
	if (typeof func_TableDatatablesAjax !== 'undefined') {
		$('#'+datatable_id).DataTable().destroy();
		eval(func_TableDatatablesAjax + "('"+admin_url+"')");
	}
});
</script>
