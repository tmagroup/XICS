<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<?php $this->load->view('admin/header.php'); ?>
<style>
.divcenter{
	bottom: 0;
	margin: auto;
	/*position: absolute;*/
	left: 0;
	right: 0;
}
</style>
<script src="<?php echo base_url('assets/global/plugins/jquery.min.js'); ?>" type="text/javascript"></script>
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
								<a href="<?php echo base_url('admin/leadquotations');?>"><?php echo lang('page_leadquotations');?></a>
								<i class="fa fa-circle"></i>
							</li>

							<li>
								<span>
									<?php
									echo lang('page_detail_leadquotation');
									?>
								</span>
							</li>

						</ul>

					</div>
					<!-- END PAGE BAR -->


					<!-- BEGIN PAGE MESSAGE-->
					<?php $this->load->view('admin/alerts'); ?>
					<!-- BEGIN PAGE MESSAGE-->


					<div class="row">


							<!-- BEGIN EXAMPLE TABLE PORTLET-->
							<div class="portlet light"  style="border:0px; margin-bottom: 0px; padding-bottom: 0px;">
								<div class="portlet-title" style="border:0px; margin-bottom: 0px; padding-bottom: 0px;">
									<div class="caption">
										<i class="fa fa-file"></i>
										<?php
										echo lang('page_detail_leadquotation');
										?>
									</div>


									<div class="actions">
										<a href="<?php echo base_url('admin/leadquotations/printquotation/'.$quotation['leadquotationnr']);?>" target="_blank" class="btn sbold yellow btn-sm"><i class="fa fa-print"></i> <?php echo lang('page_lb_print_leadquotation');?></a>

										<a href="<?php echo base_url('admin/leadquotations/printhardwarequotation/'.$quotation['leadquotationnr']);?>" target="_blank" class="btn sbold yellow btn-sm"><i class="fa fa-print"></i> <?php echo lang('page_lb_print_hardware_leadquotation');?></a>

										<a href="<?php echo base_url('admin/leadquotations/printconsultationprotocol/'.$quotation['leadquotationnr']);?>" target="_blank" class="btn sbold yellow btn-sm"><i class="fa fa-print"></i> <?php echo lang('page_lb_print_consultation_protocol');?></a>

										<a href="<?php echo base_url('admin/leadquotations/printinvoiceprotocol/'.$quotation['leadquotationnr']);?>" target="_blank" class="btn sbold yellow btn-sm"><i class="fa fa-print"></i> <?php echo lang('page_lb_print_invoice_protocol');?></a>

										<?php
										if (total_rows('tblassignments', array('quotationid' => $quotation['leadquotationnr']))) {
										}
										else if($GLOBALS['quotationtoassignment_permission']['create']){
											/* ?>
											<div class="btn-group btn-group-devided" data-toggle="buttons">
												<a href="javascript:void(0);" onclick="FormAjax('<?php echo base_url('admin/leadquotations/addAssignment/'.$quotation['leadquotationnr']);?>','<?php echo base_url('admin/leadquotations/getQuotation/'.$quotation['leadquotationnr']);?>','<?php echo lang('page_lb_create_a_assignment');?>','assignment');" class="btn sbold green btn-sm"> <i class="fa fa-plus"></i> <?php echo lang('page_lb_create_a_assignment');?></a>
											</div>
											<?php */
										}
										?>

										<?php
										if($GLOBALS['leadquotation_permission']['edit']){
											?>
											<a href="<?php echo base_url('admin/leadquotations/quotation/'.$quotation['leadquotationnr']);?>" class="btn sbold blue btn-sm"><i class="fa fa-pencil"></i> <?php echo lang('page_edit_leadquotation');?></a>
											<?php
										}
										?>
									</div>

								</div>
							</div>
							<!-- END EXAMPLE TABLE PORTLET-->


						<?php
						//Only Editable
						$tab_document = '';
						$tab_reminder = '';
						if(empty($quotation['leadquotationnr'])){
						   $tab_document = 'none';
						   $tab_reminder = 'none';
						}
						?>



						<?php //echo form_open($this->uri->uri_string(), array('enctype' => "multipart/form-data", 'id' => 'form_quotation') );?>


						<ul class="nav nav-tabs">
							<li class="active">
								<a href="#tab_profile" data-toggle="tab"><?php echo lang('page_lb_profile');?></a>
							</li>
							<li style="display:<?php echo $tab_document;?>">
								<a href="#tab_document" data-toggle="tab"><?php echo lang('page_lb_document');?></a>
							</li>
							<li style="display:<?php echo $tab_reminder;?>">
								<a href="#tab_reminder" data-toggle="tab"><?php echo lang('page_lb_reminder');?></a>
							</li>
						</ul>


						<div class="tab-content">

							<div class="tab-pane active" id="tab_profile">

								<div class="col-md-6">


									<!-- BEGIN SAMPLE FORM PORTLET-->
									<div class="portlet light bordered">
										<div class="portlet-body form">

											<div class="form-body">

												<!--<div class="form-group">
													<label><?php echo lang('page_fl_company');?>:</label>
													<?php echo $quotation['company'];?>
												</div>-->

												<div class="form-group">
													<label><?php echo lang('page_fl_quotationdate');?>:</label>
													<?php echo _d($quotation['leadquotationdate']);?>
												</div>

												<div class="form-group">
													<label><?php echo lang('page_fl_quotationstatus');?>:</label>
													<?php echo $quotation['quotationstatusname'];?>
												</div>

												<div class="form-group">
													<label><?php echo lang('page_fl_quotationprovider');?>:</label>
													<?php echo $quotation['providercompanynr'];?>
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
													<label><?php echo lang('page_lead');?>:</label>
													<?php echo $quotation['lead'];?>
												</div>

												<div class="form-group">
													<label><?php echo lang('page_fl_responsible');?>:</label>
													<?php echo $quotation['responsible'];?>
												</div>

												<div class="form-group">
													<label><?php echo lang('page_fl_recommend');?>:</label>
													<?php echo $quotation['recommend'];?>
												</div>

											</div>

										</div>
									</div>
									<!-- END SAMPLE FORM PORTLET-->

								</div>



								<div class="clearfix"></div>
								<div class="col-md-12">

									<!-- BEGIN SAMPLE FORM PORTLET-->
									<div class="portlet light bordered">
										<div class="portlet-body form">


											<div class="row">
												<div class="col-md-4">
													<div class="form-group">
														<label><?php echo lang('page_fl_currentdiscountlevel');?>:</label>
														<?php echo $quotation['currentdiscountlevel'];?>
													</div>
												</div>
												<div class="col-md-4">
													<div class="form-group">
														<label><?php echo lang('page_fl_newdiscountlevel');?>:</label>
														<?php echo $quotation['newdiscountlevel'];?>
													</div>
												</div>
											</div>


											<div class="portlet-title">
												<div class="caption font-dark">
													<span class="caption-subject font-green-sharp sbold"><?php echo lang('page_lb_quotationproducts');?></span>
												</div>
											</div>

											<div class="form-body">

												<div class="form-group">

													<table class="table table-striped table-bordered table-hover dt-responsive" width="100%" id="quotation_detail_datatable">
														<thead>
															<tr role="row" class="heading">
																<!--<th class="text-nowrap text-center"><?php echo lang('page_fl_productenterform');?></th>-->
																<th class="text-nowrap text-center"><?php echo lang('page_fl_positionnr');?>.</th>
																<th class="text-nowrap text-center"><?php echo lang('page_fl_mobilenr');?></th>
																<th class="text-nowrap text-center"><?php echo lang('page_fl_vvl_neu');?></th>
																<th class="text-nowrap text-center"><?php echo lang('page_fl_currentratemobile');?></th>
																<th class="text-nowrap text-center"><?php echo lang('page_fl_value');?></th>
																<th class="text-nowrap text-center"><?php echo lang('page_fl_use');?></th>
																<th class="text-nowrap text-center"><?php echo lang('page_fl_newratemobile');?></th>
																<th class="text-nowrap text-center"><?php echo lang('page_fl_value');?></th>
																<th class="text-nowrap text-center"><?php echo lang('page_fl_endofcontract');?></th>
																<th class="text-nowrap text-center"><?php echo lang('page_fl_hardware');?></th>
															</tr>
														</thead>
														<tbody id="quotationproduct_inputbox">
														<?php
														if(isset($quotationproducts) && count($quotationproducts)>0){
															foreach($quotationproducts as $pkey=>$quotationproduct){
																?>
																<!-- ROW -->
																<tr id="row1_old_quotationproduct_<?php echo $quotationproduct['id'];?>">
																	<!--<td class="text-nowrap text-center"><?php echo $quotationproduct['formula']=='M'?lang('page_lb_manual'):lang('page_lb_auto');?></td>-->
																	<td class="text-nowrap text-center"><?php echo $quotationproduct['id'];?></td>
																	<td class="text-center"><?php echo $quotationproduct['mobilenr'];?></td>
																	<td class="text-center"><?php echo $quotationproduct['vvlneu'];?></td>
																	<td class="text-center"><?php echo $quotationproduct['currentratemobile'];?><br><br><div class="divcenter"><?php echo $quotationproduct['currentoptionmobile']?lang('page_fl_optiontitle').":".$quotationproduct['currentoptionmobile']:'';?></div></td>
																	<td class="text-center"><?php echo $quotationproduct['value1']?format_money($quotationproduct['value1'],"&nbsp;".$GLOBALS['currency_data']['currency_symbol']):'';?><br><br><div class="divcenter"><?php echo $quotationproduct['value3']?format_money($quotationproduct['value3'],"&nbsp;".$GLOBALS['currency_data']['currency_symbol']):'';?></div></td>
																	<td class="text-center"><?php echo $quotationproduct['use']?format_money($quotationproduct['use'],"&nbsp;".$GLOBALS['currency_data']['currency_symbol']):'';?></td>
																	<td class="text-center"><?php echo $quotationproduct['newratemobile'];?><br><br><div class="divcenter"><?php echo $quotationproduct['newoptionmobile']?lang('page_fl_optiontitle').":".$quotationproduct['newoptionmobile']:'';?><br><br>



																		<div id="ultracard_<?php echo $quotationproduct['id'];?>" class="divcenter" style="display:none;white-space:nowrap;">

																			<label>
																				<?php echo $quotationproduct['ultracard1']=='1'?'<i class="fa fa-check"></i>':'<i class="fa fa-close"></i>';?>
																				<?php echo lang('page_fl_ultracard1');?>
																			</label><br>
																			<label>
																				<?php echo $quotationproduct['ultracard2']=='1'?'<i class="fa fa-check"></i>':'<i class="fa fa-close"></i>';?>
																				<?php echo lang('page_fl_ultracard2');?>
																			</label>

																		</div>


																		<?php
																		$formula = ($quotationproduct['formula']=='M')?'M':'A';
																		if($formula=='A'){
																			?>
																			<script>
																			jQuery.ajax({url: '<?php echo base_url('admin/ratesmobile/getInputUltraCard/'.$quotationproduct['newratemobile_id'].'/');?>', success: function(result){
																				if(result==1){
																					$('#ultracard_<?php echo $quotationproduct['id'];?>').show();
																				}else{
																					$('#ultracard_<?php echo $quotationproduct['id'];?>').hide();
																				}
																			}});
																			</script>
																			<?php
																		}
																		?>

																	</td>
																	<td class="text-center"><?php echo $quotationproduct['value2']?format_money($quotationproduct['value2'],"&nbsp;".$GLOBALS['currency_data']['currency_symbol']):'';?><br><br><div class="divcenter"><?php echo $quotationproduct['value4']?format_money($quotationproduct['value4'],"&nbsp;".$GLOBALS['currency_data']['currency_symbol']):'';?></div></td>
																	<td class="text-center"><?php echo _d($quotationproduct['endofcontract']);?>

																		<?php
																		if($quotationproduct['activationdate']!="" && $quotationproduct['activationdate']!="0000-00-00"){
																			?><div class="clearfix"></div>
																			<div class="divcenter" style="position:relative">


																				<table>
																					<tr>
																						<td><label><?php echo lang('page_fl_activationdate');?>: </label>
																						<?php echo _d($quotationproduct['activationdate']);?></td>
																					</tr>
																				</table>


																				<div style="display:none"><?php if($quotationproduct['formula']=='A'){ echo lang('page_fl_fqty'.$quotationproduct['simcard_function_id']).': '.$quotationproduct['simcard_function_qty']; }?></div>
																			</div>

																		 <?php
																		}
																		?>



																	</td>
																	<td class="text-center"><?php echo $quotationproduct['hardware'];?></td>
																</tr>
																<!-- END ROW -->
																<?php

															}
														}
														?>
														</tbody>
													</table>

												</div>

											</div>

										</div>
									</div>
									<!-- END SAMPLE FORM PORTLET-->

								</div>

							</div>

							<div class="tab-pane" id="tab_document" style="display:<?php echo $tab_document;?>">

								<?php
								$this->load->view('admin/leadquotations/tab-document', array('quotation'=>$quotation,'categories'=>$categories));
								?>

							</div>

							<div class="tab-pane" id="tab_reminder" style="display:<?php echo $tab_reminder;?>">

								<?php
								$this->load->view('admin/leadquotations/tab-reminder', array('quotation'=>$quotation));
								?>

							</div>
						</div>

						<div class="clearfix"></div>
						<div class="col-md-12">
							<!-- BEGIN SAMPLE FORM PORTLET-->
							<div class="portlet light bordered">
								<?php
								$this->load->view('admin/leadquotations/tab-comment', array('quotation'=>$quotation));
								?>
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
											<!--<button type="submit" class="btn blue"><?php echo lang('save');?></button>-->
											<a href="<?php echo base_url('admin/leadquotations')?>"><button type="button" class="btn default"><?php echo lang('close');?></button></a>
										</div>
									</div>
								</div>
							</div>
							<!-- END SAMPLE FORM PORTLET-->
						</div>


						<?php //echo form_close();?>
					</div>


				</div>
				<!-- END CONTENT BODY -->
			</div>
			<!-- END CONTENT -->


		</div>
		<!-- END CONTAINER -->

<script>
	var func_TableDatatables_c = 'TableCustomDatatables';
	var datatable_id_c = 'quotation_detail_datatable';
	var datatable_pagelength_c = '<?php echo get_option('tables_pagination_limit');?>';
	var datatable_sortColumn_c = 0;
	var datatable_sortColumnBy_c = 'asc';
</script>

<?php $this->load->view('admin/footer.php'); ?>
<?php $this->load->view('admin/leadquotations/leadquotationjs',array('quotation'=>$quotation, 'remindersubjects'=>$remindersubjects));?>

<script>
/* Manage without Ajax */
function TableCustomDatatables(){

	/*if(typeof datatable_hide_columns == 'undefined'){
			var datatable_hide_columns=0;
	}*/
	var language_url = '//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/German.json';

	var table = $('#'+datatable_id_c);
	table.DataTable( {

		"pageLength": datatable_pagelength_c,
		"pagingType": "bootstrap_full_number",
		"filter": true,

		// setup responsive extension: http://datatables.net/extensions/responsive/
		responsive: {
				details: {
						/*type: 'column',
						target: 'tr'*/
				}
		},

		"language": {
				"url": language_url
		},

		"lengthMenu": [
				[10, 20, 50, 100, 150],
				[10, 20, 50, 100, 150] // change per page values here
		],

		"columnDefs": [
			{
				"targets": 0,
				"searchable": false,
			},
			{
				"targets": 2,
				"searchable": false,
			},
			{
				"targets": 3,
				"searchable": false,
			},
			{
				"targets": 4,
				"searchable": false,
			},
			{
				"targets": 5,
				"searchable": false,
			},
			{
				"targets": 6,
				"searchable": false,
			},
			{
				"targets": 7,
				"searchable": false,
			},
			{
				"targets": 8,
				"searchable": false,
			},
			{
				"targets": 9,
				"searchable": false,
			}
		],

		"order": [
				[datatable_sortColumn_c, datatable_sortColumnBy_c]
		], // set first column as a default sort by asc

	});

	var tableWrapper = jQuery('#'+datatable_id_c+'_wrapper');
	//jQuery(tableWrapper).removeClass('dataTables_extended_wrapper');
	setInterval(function (){ jQuery('#'+datatable_id_c+'_wrapper').removeClass('dataTables_extended_wrapper');
		$('#'+datatable_id_c+'_wrapper .dataTables_filter input').attr("placeholder", "<?php echo lang('page_fl_mobilenr');?>");
	},100);
}
TableCustomDatatables();
</script>