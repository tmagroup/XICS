<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<?php $this->load->view('admin/header.php'); ?>

<style>
/*.row {
display: flex;
flex-wrap: wrap;
padding: 0 4px;
}
.column {
flex: 33.33%;
padding: 0 4px;
}*/
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
								<span><?php echo lang('page_dashboard');?></span>
							</li>
						</ul>
					</div>
					<!-- END PAGE BAR -->
					<!-- BEGIN PAGE TITLE-->
					<h3 class="page-title"><?php echo lang('welcome_to')?> <?php echo ($GLOBALS['current_user']->name.' '.$GLOBALS['current_user']->surname);?></h3>
					<!-- END PAGE TITLE-->
					<!-- END PAGE HEADER-->


					<!-- BEGIN DASHBOARD STATS 1-->
					<div class="row" id="dashboard_sortable_portlets">

						<?php
						//1) When Salesman login he see on his Dashboard:
						if($GLOBALS['current_user']->userrole==3){
							//Saved Order
							if(!empty($GLOBALS['current_user']->dashboard_widgets_order)){
								$widget_data = explode(',',$GLOBALS['current_user']->dashboard_widgets_order);
								$widget_group = array();
								foreach($widget_data as $widget_col){
									list($widget_colid,$widget_name) = explode(":",$widget_col);
									$widget_group[$widget_colid][] = $widget_name;
								}

								$w_col = array(1,2,3);
								$w_col_in = array();
								foreach($widget_group as $widget_key=>$widget_rows){
									$w_col_in[] = $widget_key;
									?>
									<div class="col-md-4 column sortable" data-role="user" data-colid="<?php echo $widget_key;?>">
										<?php
										foreach($widget_rows as $widget_val){
											$this->load->view('admin/widget-'.$widget_val, array('dashboard_'.$widget_val=>$widget['dashboard_'.$widget_val]));
										}
										?>

										<!-- empty sortable porlet required for each columns! -->
										<div class="portlet portlet-sortable-empty"> </div>
									</div>
									<?php
								}
								foreach($w_col as $coln){
									if(!in_array($coln,$w_col_in)){
										?>
										<div class="col-md-4 column sortable" data-role="user" data-colid="<?php echo $coln;?>">

											<!-- empty sortable porlet required for each columns! -->
											<div class="portlet portlet-sortable-empty"> </div>
										</div>
										<?php
									}
								}
							}
							else{
								//Default Order
								?>
								<div class="col-md-4 column sortable" data-role="user" data-colid="1">
									<?php
									$this->load->view('admin/widget-points', array('dashboard_points'=>$widget['dashboard_points']));
									$this->load->view('admin/widget-todos', array('dashboard_todos'=>$widget['dashboard_todos']));
									$this->load->view('admin/widget-events', array('dashboard_events'=>$widget['dashboard_events']));
									?>
									<!-- empty sortable porlet required for each columns! -->
									<div class="portlet portlet-sortable-empty"> </div>
								</div>
								<div class="col-md-4 column sortable" data-role="user" data-colid="2">
									<?php
									$this->load->view('admin/widget-leads', array('dashboard_leads'=>$widget['dashboard_leads']));
									$this->load->view('admin/widget-assignments', array('dashboard_assignments'=>$widget['dashboard_assignments']));
									$this->load->view('admin/widget-tickets', array('dashboard_tickets'=>$widget['dashboard_tickets']));
									?>
									<!-- empty sortable porlet required for each columns! -->
									<div class="portlet portlet-sortable-empty"> </div>
								</div>
								<div class="col-md-4 column sortable" data-role="user" data-colid="3">
									<?php
									$this->load->view('admin/widget-quotations', array('dashboard_quotations'=>$widget['dashboard_quotations']));
									$this->load->view('admin/widget-notifications', array('dashboard_notifications'=>$widget['dashboard_notifications']));
									?>
									<!-- empty sortable porlet required for each columns! -->
									<div class="portlet portlet-sortable-empty"> </div>
								</div>
								<?php
							}
						}
						//2) When Salesmanager login he see on his Dashboard:
						//3) When Admin login he see on his Dashboard:
						else if($GLOBALS['current_user']->userrole==1 || $GLOBALS['current_user']->userrole==2){
							// pdebug('$GLOBALS[current_user]->dashboard_widgets_order');
							// pdebug($GLOBALS['current_user']->dashboard_widgets_order);
							// pdebug($GLOBALS['current_user']->userrole);

							//Saved Order
							if(!empty($GLOBALS['current_user']->dashboard_widgets_order)){
								$widget_data = explode(',',$GLOBALS['current_user']->dashboard_widgets_order);
								$widget_group = array();
								foreach($widget_data as $widget_col){
									list($widget_colid,$widget_name) = explode(":",$widget_col);
									$widget_group[$widget_colid][] = $widget_name;
								}

								$w_col = array(1,2,3);
								$w_col_in = array();
								foreach($widget_group as $widget_key=>$widget_rows){
									$w_col_in[] = $widget_key;
									?>
									<div class="col-md-4 column sortable" data-role="user" data-colid="<?php echo $widget_key;?>">
										<?php
										foreach($widget_rows as $widget_val){
											$this->load->view('admin/widget-'.$widget_val, array('dashboard_'.$widget_val=>$widget['dashboard_'.$widget_val]));
										}
										?>

										<!-- empty sortable porlet required for each columns! -->
										<div class="portlet portlet-sortable-empty"> </div>
									</div>
									<?php
								}
								foreach($w_col as $coln){
									if(!in_array($coln,$w_col_in)){
										?>
										<div class="col-md-4 column sortable" data-role="user" data-colid="<?php echo $coln;?>">

											<!-- empty sortable porlet required for each columns! -->
											<div class="portlet portlet-sortable-empty"> </div>
										</div>
										<?php
									}
								}
							}
							else{
								//Default Order
								?>
								<div class="col-md-4 column sortable" data-role="user" data-colid="1">
									<?php
									$this->load->view('admin/widget-leads', array('dashboard_leads'=>$widget['dashboard_leads']));
									$this->load->view('admin/widget-assignments', array('dashboard_assignments'=>$widget['dashboard_assignments']));
									?>
									<!-- empty sortable porlet required for each columns! -->
									<div class="portlet portlet-sortable-empty"> </div>
								</div>
								<div class="col-md-4 column sortable" data-role="user" data-colid="2">
									<?php
									$this->load->view('admin/widget-tickets', array('dashboard_tickets'=>$widget['dashboard_tickets']));
									$this->load->view('admin/widget-quotations', array('dashboard_quotations'=>$widget['dashboard_quotations']));
									?>
									<!-- empty sortable porlet required for each columns! -->
									<div class="portlet portlet-sortable-empty"> </div>
								</div>
								<div class="col-md-4 column sortable" data-role="user" data-colid="3">
									<?php
									$this->load->view('admin/widget-todos', array('dashboard_todos'=>$widget['dashboard_todos']));                                 	$this->load->view('admin/widget-notifications', array('dashboard_notifications'=>$widget['dashboard_notifications']));
									?>
									<!-- empty sortable porlet required for each columns! -->
									<div class="portlet portlet-sortable-empty"> </div>
								</div>
								<?php
							}

						}
						//4) When Accounting login he see on his Dashboard:
						else if($GLOBALS['current_user']->userrole==7){
							//Saved Order
							if(!empty($GLOBALS['current_user']->dashboard_widgets_order)){
								$widget_data = explode(',',$GLOBALS['current_user']->dashboard_widgets_order);
								$widget_group = array();
								foreach($widget_data as $widget_col){
									list($widget_colid,$widget_name) = explode(":",$widget_col);
									$widget_group[$widget_colid][] = $widget_name;
								}

								$w_col = array(1,2,3);
								$w_col_in = array();
								foreach($widget_group as $widget_key=>$widget_rows){
									$w_col_in[] = $widget_key;
									?>
									<div class="col-md-4 column sortable" data-role="user" data-colid="<?php echo $widget_key;?>">
										<?php
										foreach($widget_rows as $widget_val){
											$this->load->view('admin/widget-'.$widget_val, array('dashboard_'.$widget_val=>$widget['dashboard_'.$widget_val]));
										}
										?>

										<!-- empty sortable porlet required for each columns! -->
										<div class="portlet portlet-sortable-empty"> </div>
									</div>
									<?php
								}
								foreach($w_col as $coln){
									if(!in_array($coln,$w_col_in)){
										?>
										<div class="col-md-4 column sortable" data-role="user" data-colid="<?php echo $coln;?>">

											<!-- empty sortable porlet required for each columns! -->
											<div class="portlet portlet-sortable-empty"> </div>
										</div>
										<?php
									}
								}
							}
							else{
								//Default Order
								?>
								<div class="col-md-4 column sortable" data-role="user" data-colid="1">
									<?php
									$this->load->view('admin/widget-tickets', array('dashboard_tickets'=>$widget['dashboard_tickets']));
									?>
									<!-- empty sortable porlet required for each columns! -->
									<div class="portlet portlet-sortable-empty"> </div>
								</div>
								<div class="col-md-4 column sortable" data-role="user" data-colid="2">
									<?php
									$this->load->view('admin/widget-todos', array('dashboard_todos'=>$widget['dashboard_todos']));
									?>
								</div>
								<div class="col-md-4 column sortable" data-role="user" data-colid="3">
									<?php
									$this->load->view('admin/widget-notifications', array('dashboard_notifications'=>$widget['dashboard_notifications']));
									?>
								</div>
								<?php
							}
						}
						//5) When POS login he see on his Dashboard:
						else if($GLOBALS['current_user']->userrole==6){
							//Saved Order
							if(!empty($GLOBALS['current_user']->dashboard_widgets_order)){
								$widget_data = explode(',',$GLOBALS['current_user']->dashboard_widgets_order);
								$widget_group = array();
								foreach($widget_data as $widget_col){
									list($widget_colid,$widget_name) = explode(":",$widget_col);
									$widget_group[$widget_colid][] = $widget_name;
								}

								$w_col = array(1,2,3);
								$w_col_in = array();
								foreach($widget_group as $widget_key=>$widget_rows){
									$w_col_in[] = $widget_key;
									?>
									<div class="col-md-4 column sortable" data-role="user" data-colid="<?php echo $widget_key;?>">
										<?php
										foreach($widget_rows as $widget_val){
											$this->load->view('admin/widget-'.$widget_val, array('dashboard_'.$widget_val=>$widget['dashboard_'.$widget_val]));
										}
										?>

										<!-- empty sortable porlet required for each columns! -->
										<div class="portlet portlet-sortable-empty"> </div>
									</div>
									<?php
								}
								foreach($w_col as $coln){
									if(!in_array($coln,$w_col_in)){
										?>
										<div class="col-md-4 column sortable" data-role="user" data-colid="<?php echo $coln;?>">

											<!-- empty sortable porlet required for each columns! -->
											<div class="portlet portlet-sortable-empty"> </div>
										</div>
										<?php
									}
								}
							}
							else{
								//Default Order
								?>
								<div class="col-md-4 column sortable" data-role="user" data-colid="1">
									<?php
									$this->load->view('admin/widget-points', array('dashboard_points'=>$widget['dashboard_points']));
									$this->load->view('admin/widget-assignments', array('dashboard_assignments'=>$widget['dashboard_assignments']));
									?>
									<!-- empty sortable porlet required for each columns! -->
									<div class="portlet portlet-sortable-empty"> </div>
								</div>
								<div class="col-md-4 column sortable" data-role="user" data-colid="2">
									<?php
									$this->load->view('admin/widget-leads', array('dashboard_leads'=>$widget['dashboard_leads']));
									$this->load->view('admin/widget-tickets', array('dashboard_tickets'=>$widget['dashboard_tickets']));
									?>
									<!-- empty sortable porlet required for each columns! -->
									<div class="portlet portlet-sortable-empty"> </div>
								</div>
								<div class="col-md-4 column sortable" data-role="user" data-colid="3">
									<?php
									$this->load->view('admin/widget-notifications', array('dashboard_notifications'=>$widget['dashboard_notifications']));
									?>
								</div>
								<?php
							}
						}
						//6) When Supporter login he see on his Dashboard:
						else if($GLOBALS['current_user']->userrole==5){
							//Saved Order
							if(!empty($GLOBALS['current_user']->dashboard_widgets_order)){
								$widget_data = explode(',',$GLOBALS['current_user']->dashboard_widgets_order);
								$widget_group = array();
								foreach($widget_data as $widget_col){
									list($widget_colid,$widget_name) = explode(":",$widget_col);
									$widget_group[$widget_colid][] = $widget_name;
								}

								$w_col = array(1,2,3);
								$w_col_in = array();
								foreach($widget_group as $widget_key=>$widget_rows){
									$w_col_in[] = $widget_key;
									?>
									<div class="col-md-4 column sortable" data-role="user" data-colid="<?php echo $widget_key;?>">
										<?php
										foreach($widget_rows as $widget_val){
											$this->load->view('admin/widget-'.$widget_val, array('dashboard_'.$widget_val=>$widget['dashboard_'.$widget_val]));
										}
										?>

										<!-- empty sortable porlet required for each columns! -->
										<div class="portlet portlet-sortable-empty"> </div>
									</div>
									<?php
								}
								foreach($w_col as $coln){
									if(!in_array($coln,$w_col_in)){
										?>
										<div class="col-md-4 column sortable" data-role="user" data-colid="<?php echo $coln;?>">

											<!-- empty sortable porlet required for each columns! -->
											<div class="portlet portlet-sortable-empty"> </div>
										</div>
										<?php
									}
								}
							}
							else{
								//Default Order
								?>
								<div class="col-md-4 column sortable" data-role="user" data-colid="1">
									<?php
									$this->load->view('admin/widget-leads', array('dashboard_leads'=>$widget['dashboard_leads']));
									$this->load->view('admin/widget-qualitychecks', array('dashboard_qualitychecks'=>$widget['dashboard_qualitychecks']));
									?>
									<!-- empty sortable porlet required for each columns! -->
									<div class="portlet portlet-sortable-empty"> </div>
								</div>
								<div class="col-md-4 column sortable" data-role="user" data-colid="2">
									<?php
									$this->load->view('admin/widget-tickets', array('dashboard_tickets'=>$widget['dashboard_tickets']));
									$this->load->view('admin/widget-todos', array('dashboard_todos'=>$widget['dashboard_todos']));
									?>
									<!-- empty sortable porlet required for each columns! -->
									<div class="portlet portlet-sortable-empty"> </div>
								</div>
								<div class="col-md-4 column sortable" data-role="user" data-colid="3">
									<?php
									$this->load->view('admin/widget-notifications', array('dashboard_notifications'=>$widget['dashboard_notifications']));
									?>
								</div>
								<?php
							}
						}
						//7) When Customer login he see on his Dashboard:
						else if(get_user_role()=='customer'){
							//Saved Order
							if(!empty($GLOBALS['current_user']->dashboard_widgets_order)){
								$widget_data = explode(',',$GLOBALS['current_user']->dashboard_widgets_order);
								$widget_group = array();
								foreach($widget_data as $widget_col){
									list($widget_colid,$widget_name) = explode(":",$widget_col);
									$widget_group[$widget_colid][] = $widget_name;
								}

								$w_col = array(1,2,3);
								$w_col_in = array();
								foreach($widget_group as $widget_key=>$widget_rows){
									$w_col_in[] = $widget_key;
									?>
									<div class="col-md-4 column sortable" data-role="user" data-colid="<?php echo $widget_key;?>">
										<?php
										foreach($widget_rows as $widget_val){
											$this->load->view('admin/widget-'.$widget_val, array('dashboard_'.$widget_val=>$widget['dashboard_'.$widget_val]));
										}
										?>

										<!-- empty sortable porlet required for each columns! -->
										<div class="portlet portlet-sortable-empty"> </div>
									</div>
									<?php
								}
								foreach($w_col as $coln){
									if(!in_array($coln,$w_col_in)){
										?>
										<div class="col-md-4 column sortable" data-role="user" data-colid="<?php echo $coln;?>">

											<!-- empty sortable porlet required for each columns! -->
											<div class="portlet portlet-sortable-empty"> </div>
										</div>
										<?php
									}
								}
							}
							else{
								//Default Order
								?>
								<div class="col-md-4 column sortable" data-role="customer" data-colid="1">
									<?php
									$this->load->view('admin/widget-assignments', array('dashboard_assignments'=>$widget['dashboard_assignments']));
									$this->load->view('admin/widget-hardwareinvoices', array('dashboard_hardwareinvoices'=>$widget['dashboard_hardwareinvoices']));
									?>
									<!-- empty sortable porlet required for each columns! -->
									<div class="portlet portlet-sortable-empty"> </div>
								</div>
								<div class="col-md-4 column sortable" data-role="customer" data-colid="2">
									<?php
									$this->load->view('admin/widget-hardwareassignments', array('dashboard_hardwareassignments'=>$widget['dashboard_hardwareassignments']));
									$this->load->view('admin/widget-monitorings', array('dashboard_monitorings'=>$widget['dashboard_monitorings']));
									?>
									<!-- empty sortable porlet required for each columns! -->
									<div class="portlet portlet-sortable-empty"> </div>
								</div>
								<div class="col-md-4 column sortable" data-role="customer" data-colid="3">
									<?php
									$this->load->view('admin/widget-tickets', array('dashboard_tickets'=>$widget['dashboard_tickets']));
									$this->load->view('admin/widget-notifications', array('dashboard_notifications'=>$widget['dashboard_notifications']));
									?>
								</div>
								<?php
							}
						}
						?>

					</div>
					<!-- END DASHBOARD STATS 1-->


				</div>
				<!-- END CONTENT BODY -->
			</div>
			<!-- END CONTENT -->


		</div>
		<!-- END CONTAINER -->

<?php $this->load->view('admin/footer.php'); ?>
