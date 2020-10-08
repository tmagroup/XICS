<!-- BEGIN SIDEBAR -->
<div class="page-sidebar-wrapper">
	<!-- BEGIN SIDEBAR -->
	<!-- DOC: Set data-auto-scroll="false" to disable the sidebar from auto scrolling/focusing -->
	<!-- DOC: Change data-auto-speed="200" to adjust the sub menu slide up/down speed -->
	<div class="page-sidebar navbar-collapse collapse">
		<!-- BEGIN SIDEBAR MENU -->
		<!-- DOC: Apply "page-sidebar-menu-light" class right after "page-sidebar-menu" to enable light sidebar menu style(without borders) -->
		<!-- DOC: Apply "page-sidebar-menu-hover-submenu" class right after "page-sidebar-menu" to enable hoverable(hover vs accordion) sub menu mode -->
		<!-- DOC: Apply "page-sidebar-menu-closed" class right after "page-sidebar-menu" to collapse("page-sidebar-closed" class must be applied to the body element) the sidebar sub menu mode -->
		<!-- DOC: Set data-auto-scroll="false" to disable the sidebar from auto scrolling/focusing -->
		<!-- DOC: Set data-keep-expand="true" to keep the submenues expanded -->
		<!-- DOC: Set data-auto-speed="200" to adjust the sub menu slide up/down speed -->

		<!--<ul class="page-sidebar-menu  page-header-fixed " data-keep-expanded="false" data-auto-scroll="true" data-slide-speed="200" style="padding-top: 20px">-->
		<ul class="page-sidebar-menu  page-header-fixed " data-keep-expanded="false" data-auto-scroll="true" data-slide-speed="200" style="padding-top: 30px">
			<!-- DOC: To remove the sidebar toggler from the sidebar you just need to completely remove the below "sidebar-toggler-wrapper" LI element -->
			<li class="sidebar-toggler-wrapper hide">
				<!-- BEGIN SIDEBAR TOGGLER BUTTON -->
				<div class="sidebar-toggler"> </div>
				<!-- END SIDEBAR TOGGLER BUTTON -->
			</li>
			<!-- DOC: To remove the search box from the sidebar you just need to completely remove the below "sidebar-search-wrapper" LI element -->

			<li class="nav-item start <?php if(current_url()==base_url('admin/dashboard')){ ?>active open<?php }?>">
				<a href="<?php echo base_url('admin/dashboard');?>" class="nav-link nav-toggle">
					<i class="icon-home"></i>
					<span class="title"><?php echo lang('page_dashboard');?></span>
					<span class="selected"></span>
					<span class="open"></span>
				</a>
			</li>


			<!-- TICEKTS -->
			<?php
			if($ticket_permission['view'] || $ticket_permission['create'] || $ticket_permission['edit'] || $ticket_permission['delete']){
				?>
				<li class="nav-item  <?php if(current_url()==base_url('admin/tickets')
					|| current_url()==base_url('admin/tickets/ticket')
					|| current_url()==base_url('admin/tickets/ticket/'.$this->uri->segment(4))
					|| current_url()==base_url('admin/tickets/detail/'.$this->uri->segment(4))
				){ ?>active open<?php }?>">
					<a href="<?php echo base_url('admin/tickets');?>" class="nav-link nav-toggle">
						<i class="fa fa-ticket"></i>
						<span class="title"><?php echo lang('page_tickets');?></span>
					</a>
				</li>
				<?php
			}
			?>
			<!-- END TICEKTS -->


			<!-- CALENDAR -->
			<?php
			if(get_user_role()=='user' && ($calendar_permission['view'] || $calendar_permission['create'] || $calendar_permission['edit'] || $calendar_permission['delete'])){
				?>
				<li class="nav-item  <?php if(current_url()==base_url('admin/calendars')
				){ ?>active open<?php }?>">
					<a href="<?php echo base_url('admin/calendars');?>" class="nav-link nav-toggle">
						<i class="fa fa-calendar"></i>
						<span class="title"><?php echo lang('page_calendar');?></span>
					</a>
				</li>
				<?php
			}
			?>
			<!-- END CALENDAR -->


			<!-- TODOS -->
			<?php
			if(get_user_role()=='user' && ($todo_permission['view'] || $todo_permission['create'] || $todo_permission['edit'] || $todo_permission['delete'])){
				?>
				<li class="nav-item  <?php if(current_url()==base_url('admin/todos')
					|| current_url()==base_url('admin/todos/todo')
					|| current_url()==base_url('admin/todos/todo/'.$this->uri->segment(4))
					|| current_url()==base_url('admin/todos/detail/'.$this->uri->segment(4))
				){ ?>active open<?php }?>">
					<a href="<?php echo base_url('admin/todos');?>" class="nav-link nav-toggle">
						<i class="fa fa-comment"></i>
						<span class="title"><?php echo lang('page_todos');?></span>
					</a>
				</li>
				<?php
			}
			?>
			<!-- END TODOS -->


			<!-- LEADS -->
			<?php
			if(get_user_role()=='user' && ($lead_permission['view'] || $lead_permission['view_own'] || $lead_permission['create'] || $lead_permission['edit'] || $lead_permission['delete'] || $lead_permission['import'])){

				?>
				<li class="nav-item  <?php if(current_url()==base_url('admin/leads')
					|| current_url()==base_url('admin/leads/lead')
					|| current_url()==base_url('admin/leads/lead/'.$this->uri->segment(4))
					|| current_url()==base_url('admin/leads/detail/'.$this->uri->segment(4))
					|| current_url()==base_url('admin/leads/import')
				){ ?>active open<?php }?>">
					<a href="<?php echo base_url('admin/leads');?>" class="nav-link nav-toggle">
						<i class="fa fa-tty"></i>
						<span class="title"><?php echo lang('page_leads');?></span>
					</a>
				</li>
				<?php
			}
			?>
			<!-- END LEADS -->


			<!-- LEAD QUOTATIONS -->
			<?php
			if($leadquotation_permission['view'] || $leadquotation_permission['view_own'] || $leadquotation_permission['create'] || $leadquotation_permission['edit'] || $leadquotation_permission['delete']){
				?>
				<li class="nav-item  <?php if(current_url()==base_url('admin/leadquotations')
					|| current_url()==base_url('admin/leadquotations/quotation')
					|| current_url()==base_url('admin/leadquotations/quotation/'.$this->uri->segment(4))
					|| current_url()==base_url('admin/leadquotations/detail/'.$this->uri->segment(4))
				){ ?>active open<?php }?>">
					<a href="<?php echo base_url('admin/leadquotations');?>" class="nav-link nav-toggle">
						<i class="fa fa-file"></i>
						<span class="title"><?php echo lang('page_leadquotations');?></span>
					</a>
				</li>
				<?php
			}
			?>
			<!-- END LEAD QUOTATIONS -->

			<!-- CUSTOMERS -->
			<?php
			if(get_user_role()=='user' && ($customer_permission['view'] || $customer_permission['create'] || $customer_permission['edit'] || $customer_permission['delete'])){
				?>
				<li class="nav-item  <?php if(current_url()==base_url('admin/customers')
					|| current_url()==base_url('admin/customers/customer')
					|| current_url()==base_url('admin/customers/customer/'.$this->uri->segment(4))
					|| current_url()==base_url('admin/customers/detail/'.$this->uri->segment(4))
				){ ?>active open<?php }?>">
					<a href="<?php echo base_url('admin/customers');?>" class="nav-link nav-toggle">
						<i class="fa fa-user"></i>
						<span class="title"><?php echo lang('page_customers');?></span>
					</a>
				</li>
				<?php
			}
			?>
			<!-- END CUSTOMERS -->


			<!-- QUOTATIONS -->
			<?php
			if($quotation_permission['view'] || $quotation_permission['view_own'] || $quotation_permission['create'] || $quotation_permission['edit'] || $quotation_permission['delete']){
				?>
				<li class="nav-item  <?php if(current_url()==base_url('admin/quotations')
					|| current_url()==base_url('admin/quotations/quotation')
					|| current_url()==base_url('admin/quotations/quotation/'.$this->uri->segment(4))
					|| current_url()==base_url('admin/quotations/detail/'.$this->uri->segment(4))
				){ ?>active open<?php }?>">
					<a href="<?php echo base_url('admin/quotations');?>" class="nav-link nav-toggle">
						<i class="fa fa-file"></i>
						<span class="title"><?php echo lang('page_quotations');?></span>
					</a>
				</li>
				<?php
			}
			?>
			<!-- END QUOTATIONS -->


			<!-- ASSIGNMENTS -->
			<?php
			if($assignment_permission['view'] || $assignment_permission['view_own'] || $assignment_permission['create'] || $assignment_permission['edit'] || $assignment_permission['delete']){
				?>
				<li class="nav-item  <?php if(current_url()==base_url('admin/assignments')
					|| current_url()==base_url('admin/assignments/assignment')
					|| current_url()==base_url('admin/assignments/assignment/'.$this->uri->segment(4))
					|| current_url()==base_url('admin/assignments/detail/'.$this->uri->segment(4))
				){ ?>active open<?php }?>">
					<a href="<?php echo base_url('admin/assignments');?>" class="nav-link nav-toggle">
						<i class="fa fa-file"></i>
						<span class="title"><?php echo lang('page_assignments');?></span>
					</a>
				</li>
				<?php
			}
			?>
			<!-- END ASSIGNMENTS -->
			<!-- HARDWARE ASSIGNMENT -->
			<?php
			if($hardwareinput_permission['view'] || $hardwareinput_permission['create'] || $hardwareinput_permission['edit'] || $hardwareinput_permission['delete']
			|| $hardwareassignment_permission['view'] || $hardwareassignment_permission['view_own'] || $hardwareassignment_permission['create'] || $hardwareassignment_permission['edit'] || $hardwareassignment_permission['delete']

			|| $deliverynote_permission['view'] || $deliverynote_permission['create'] || $deliverynote_permission['edit'] || $deliverynote_permission['delete']
			|| $hardwareinvoice_permission['view'] || $hardwareinvoice_permission['create'] || $hardwareinvoice_permission['edit'] || $hardwareinvoice_permission['delete']

			|| $hardwarebudget_permission['view'] || $hardwarebudget_permission['view_own'] || $hardwarebudget_permission['create'] || $hardwarebudget_permission['edit'] || $hardwarebudget_permission['delete']

			){

				?>
				<li class="nav-item  <?php if(current_url()==base_url('admin/hardwareinputs')
						|| current_url()==base_url('admin/hardwareinputs/hardwareinput')
						|| current_url()==base_url('admin/hardwareinputs/hardwareinput/'.$this->uri->segment(4))
						|| current_url()==base_url('admin/hardwareinputs/detail/'.$this->uri->segment(4))

						|| current_url()==base_url('admin/hardwareassignments')
						|| current_url()==base_url('admin/hardwareassignments/hardwareassignment')
						|| current_url()==base_url('admin/hardwareassignments/hardwareassignment/'.$this->uri->segment(4))
						|| current_url()==base_url('admin/hardwareassignments/detail/'.$this->uri->segment(4))

						|| current_url()==base_url('admin/hardwarebudgets')
						|| current_url()==base_url('admin/hardwarebudgets/hardwarebudget')
						|| current_url()==base_url('admin/hardwarebudgets/hardwarebudget/'.$this->uri->segment(4))
						|| current_url()==base_url('admin/hardwarebudgets/detail/'.$this->uri->segment(4))

						|| current_url()==base_url('admin/deliverynotes')
						|| current_url()==base_url('admin/hardwareinvoices')

						){ ?>active open<?php }?>">
						<a href="javascript:;" class="nav-link nav-toggle">
							<i class="icon-settings"></i>
							<span class="title"><?php echo lang('page_hardware');?></span>
							<span class="arrow"></span>
						</a>
						<ul class="sub-menu">

							<!-- Hardware Budget Manage -->
							<?php
								$sep_border = true;
								if ( $hardwarebudget_permission['view'] || $hardwarebudget_permission['view_own'] || $hardwarebudget_permission['create'] || $hardwarebudget_permission['edit'] || $hardwarebudget_permission['delete'] ) {
							?>
								<li class="nav-item
									<?php
										if (
											current_url() == base_url('admin/hardwarebudgets')
											|| current_url() == base_url('admin/hardwarebudgets/hardwarebudget')
											|| current_url() == base_url('admin/hardwarebudgets/hardwarebudget/'. $this->uri->segment(4))
											|| current_url() == base_url('admin/hardwarebudgets/detail/'. $this->uri->segment(4))
										) {
									?> active open <?php } ?>">
									<a href="<?php echo base_url('admin/hardwarebudgets'); ?>" class="nav-link ">
										<i class="icon-settings"></i> <span class="title">Hardware-Budget</span>
									</a>
								</li>
							<?php
								$sep_border = false;
							} ?>


							<!-- Hardware Assignment Manage -->
							<?php
							$sep_border = true;
							if($hardwareassignment_permission['view'] || $hardwareassignment_permission['view_own'] || $hardwareassignment_permission['create'] || $hardwareassignment_permission['edit'] || $hardwareassignment_permission['delete']){
								?>
								<li class="nav-item <?php if(current_url()==base_url('admin/hardwareassignments')
									|| current_url()==base_url('admin/hardwareassignments/hardwareassignment')
									|| current_url()==base_url('admin/hardwareassignments/hardwareassignment/'.$this->uri->segment(4))
									|| current_url()==base_url('admin/hardwareassignments/detail/'.$this->uri->segment(4))
									){ ?>active open<?php }?>">
									<a href="<?php echo base_url('admin/hardwareassignments');?>" class="nav-link ">
									   <i class="icon-settings"></i> <span class="title"><?php echo lang('page_hardwareassignments');?></span>
									</a>
								</li>
								<?php
								$sep_border = false;
							}
							?>


							<!-- Delivery Note Manage -->
							<?php
							$sep_border = true;
							if($deliverynote_permission['view'] || $deliverynote_permission['create'] || $deliverynote_permission['edit'] || $deliverynote_permission['delete']){
								?>
								<li class="nav-item <?php if(current_url()==base_url('admin/deliverynotes')
									|| current_url()==base_url('admin/deliverynotes/deliverynote')
									|| current_url()==base_url('admin/deliverynotes/deliverynote/'.$this->uri->segment(4))
									|| current_url()==base_url('admin/deliverynotes/detail/'.$this->uri->segment(4))
									){ ?>active open<?php }?>">
									<a href="<?php echo base_url('admin/deliverynotes');?>" class="nav-link ">
									   <i class="icon-graph"></i> <span class="title"><?php echo lang('page_deliverynotes');?></span>
									</a>
								</li>
								<?php
								$sep_border = false;
							}
							?>

							<!-- Hardware Invoice Manage -->
							<?php
							$sep_border = true;
							if($hardwareinvoice_permission['view'] || $hardwareinvoice_permission['view_own'] || $hardwareinvoice_permission['create'] || $hardwareinvoice_permission['edit'] || $hardwareinvoice_permission['delete']){
								?>
								<li class="nav-item <?php if(current_url()==base_url('admin/hardwareinvoices')
									|| current_url()==base_url('admin/hardwareinvoices/hardwareinvoice')
									|| current_url()==base_url('admin/hardwareinvoices/hardwareinvoice/'.$this->uri->segment(4))
									|| current_url()==base_url('admin/hardwareinvoices/detail/'.$this->uri->segment(4))
									){ ?>active open<?php }?>">
									<a href="<?php echo base_url('admin/hardwareinvoices');?>" class="nav-link ">
									   <i class="icon-graph"></i> <span class="title"><?php echo lang('page_hardwareinvoices');?></span>
									</a>
								</li>
								<?php
								$sep_border = false;
							}
							?>

							<!-- Hardware Input Manage -->
							<?php
							$sep_border = true;
							if($hardwareinput_permission['view'] || $hardwareinput_permission['create'] || $hardwareinput_permission['edit'] || $hardwareinput_permission['delete']){
								?>
								<li class="nav-item <?php if(current_url()==base_url('admin/hardwareinputs')
									|| current_url()==base_url('admin/hardwareinputs/hardwareinput')
									|| current_url()==base_url('admin/hardwareinputs/hardwareinput/'.$this->uri->segment(4))
									|| current_url()==base_url('admin/hardwareinputs/detail/'.$this->uri->segment(4))
									){ ?>active open<?php }?>">
									<a href="<?php echo base_url('admin/hardwareinputs');?>" class="nav-link ">
									   <i class="icon-settings"></i> <span class="title"><?php echo lang('page_hardwareinputs');?></span>
									</a>
								</li>
								<?php
								$sep_border = false;
							}
							?>
						</ul>
				</li>
				<?php
			}
			?>
			<!-- END HARDWARE ASSIGNMENT -->


			<!-- QUALITY CHECKS -->
			<?php
			if(get_user_role()=='user' && ($qualitycheck_permission['view'] || $qualitycheck_permission['create'] || $qualitycheck_permission['edit'] || $qualitycheck_permission['delete'])){
				?>
				<li class="nav-item  <?php if(current_url()==base_url('admin/qualitychecks')
					|| current_url()==base_url('admin/qualitychecks/qualitycheck')
					|| current_url()==base_url('admin/qualitychecks/qualitycheck/'.$this->uri->segment(4))
					|| current_url()==base_url('admin/qualitychecks/detail/'.$this->uri->segment(4))
				){ ?>active open<?php }?>">
					<a href="<?php echo base_url('admin/qualitychecks');?>" class="nav-link nav-toggle">
						<i class="fa fa-shield"></i>
						<span class="title"><?php echo lang('page_qualitychecks');?></span>
					</a>
				</li>
				<?php
			}
			?>
			<!-- END QUALITY CHECKS -->


			<!-- MONITORINGS -->
			<?php
			if($monitoring_permission['view'] || $monitoring_permission['view_own'] || $monitoring_permission['create'] || $monitoring_permission['edit'] || $monitoring_permission['delete']){

				//2. The Menu Monitoring should only to see to customers when checkbox "Monitoring" was checked in his Profile.
				$monitoring_show = true;
				if(get_user_role()=='customer'){
					if($GLOBALS['current_user']->monitoring==1){
						$monitoring_show = true;
					}else{
						$monitoring_show = false;
					}
				}

								if($monitoring_show){
									?>
									<li class="nav-item  <?php if(current_url()==base_url('admin/monitorings')
											|| current_url()==base_url('admin/monitorings/monitoring')
											|| current_url()==base_url('admin/monitorings/monitoring/'.$this->uri->segment(4))
											|| current_url()==base_url('admin/monitorings/detail/'.$this->uri->segment(4))
									){ ?>active open<?php }?>">
											<a href="<?php echo base_url('admin/monitorings');?>" class="nav-link nav-toggle">
													<i class="fa fa-eye"></i>
													<span class="title"><?php echo lang('page_monitorings');?></span>
											</a>
									</li>
									<?php
								}
			}
			?>
			<!-- END MONITORINGS -->

			<!-- SETTINGS -->
			<?php
			if($GLOBALS['current_user']->userrole==1 && get_user_role()=='user' && ($role_permission['view']

			|| $user_permission['view'] || $user_permission['create'] || $user_permission['edit'] || $user_permission['delete']

			|| $ratemobile_permission['view'] || $ratemobile_permission['create'] || $ratemobile_permission['edit'] || $ratemobile_permission['delete'] || $ratemobile_permission['import']
			|| $ratelandline_permission['view'] || $ratelandline_permission['create'] || $ratelandline_permission['edit'] || $ratelandline_permission['delete'] || $ratelandline_permission['import']

			|| $optionmobile_permission['view'] || $optionmobile_permission['create'] || $optionmobile_permission['edit'] || $optionmobile_permission['delete'] || $optionmobile_permission['import']
			|| $optionlandline_permission['view'] || $optionlandline_permission['create'] || $optionlandline_permission['edit'] || $optionlandline_permission['delete'] || $optionlandline_permission['import']

			|| $discountlevel_permission['view'] || $discountlevel_permission['create'] || $discountlevel_permission['edit'] || $discountlevel_permission['delete'] || $discountlevel_permission['import']

			|| $hardware_permission['view'] || $hardware_permission['create'] || $hardware_permission['edit'] || $hardware_permission['delete'] || $hardware_permission['import']

			|| $supplier_permission['view'] || $supplier_permission['create'] || $supplier_permission['edit'] || $supplier_permission['delete']

			|| $documentsetting_permission['view'] || $documentsetting_permission['create'] || $documentsetting_permission['edit'] || $documentsetting_permission['delete']

			|| $employeecommission_permission['view'])
			){
			?>
			<li class="nav-item  <?php if(current_url()==base_url('admin/roles')

					|| current_url()==base_url('admin/users')
					|| current_url()==base_url('admin/users/user')
					|| current_url()==base_url('admin/users/user/'.$this->uri->segment(4))

					|| current_url()==base_url('admin/ratesmobile')
					|| current_url()==base_url('admin/ratesmobile/rate')
					|| current_url()==base_url('admin/ratesmobile/import')
					|| current_url()==base_url('admin/ratesmobile/rate/'.$this->uri->segment(4))

					|| current_url()==base_url('admin/rateslandline')
					|| current_url()==base_url('admin/rateslandline/rate')
					|| current_url()==base_url('admin/rateslandline/import')
					|| current_url()==base_url('admin/rateslandline/rate/'.$this->uri->segment(4))

					|| current_url()==base_url('admin/optionsmobile')
					|| current_url()==base_url('admin/optionsmobile/option')
					|| current_url()==base_url('admin/optionsmobile/import')
					|| current_url()==base_url('admin/optionsmobile/option/'.$this->uri->segment(4))

					|| current_url()==base_url('admin/optionslandline')
					|| current_url()==base_url('admin/optionslandline/option')
					|| current_url()==base_url('admin/optionslandline/import')
					|| current_url()==base_url('admin/optionslandline/option/'.$this->uri->segment(4))

					|| current_url()==base_url('admin/discountlevels')
					|| current_url()==base_url('admin/discountlevels/discount')
					|| current_url()==base_url('admin/discountlevels/import')
					|| current_url()==base_url('admin/discountlevels/discount/'.$this->uri->segment(4))

					|| current_url()==base_url('admin/hardwares')
					|| current_url()==base_url('admin/hardwares/hardware')
					|| current_url()==base_url('admin/hardwares/import')
					|| current_url()==base_url('admin/hardwares/hardware/'.$this->uri->segment(4))

					|| current_url()==base_url('admin/suppliers')
					|| current_url()==base_url('admin/suppliers/supplier')
					|| current_url()==base_url('admin/suppliers/supplier/'.$this->uri->segment(4))

					|| current_url()==base_url('admin/documentsettings')
					|| current_url()==base_url('admin/documentsettings/category')
					|| current_url()==base_url('admin/documentsettings/category/'.$this->uri->segment(4))

					|| current_url()==base_url('admin/employeecommissions')

					|| current_url()==base_url('admin/history')
					){ ?>active open<?php }?>">
				<a href="javascript:;" class="nav-link nav-toggle">
					<i class="icon-settings"></i>
					<span class="title"><?php echo lang('page_settings');?></span>
					<span class="arrow"></span>
				</a>
				<ul class="sub-menu">

					<!-- Role Manage Only Access for Master Admin (id:1) -->
					<?php
					if($role_permission['view']){
						?>
						<!--<li class="nav-item <?php if(current_url()==base_url('admin/roles')){ ?>active open<?php }?>">
							<a href="<?php echo base_url('admin/roles');?>" class="nav-link ">
							   <i class="fa fa-briefcase"></i> <span class="title"><?php echo lang('page_roles');?></span>
							</a>
						</li>-->
						<?php
					}
					?>

					<!-- User Manage -->
					<?php
					if($user_permission['view'] || $user_permission['edit'] || $user_permission['delete']){
						?>
						<li class="nav-item <?php if(current_url()==base_url('admin/users')
						|| current_url()==base_url('admin/users/user/'.$this->uri->segment(4))){ ?>active open<?php }?> menu_separate_border">
							<a href="<?php echo base_url('admin/users');?>" class="nav-link ">
							   <i class="fa fa-users"></i> <span class="title"><?php echo lang('page_users');?></span>
							</a>
						</li>
						<?php
					}
					?>

					<!-- Rates Mobile Manage -->
					<?php
					$sep_border = true;
					if($ratemobile_permission['view'] || $ratemobile_permission['edit'] || $ratemobile_permission['delete'] || $ratemobile_permission['import']){
						?>
						<li class="nav-item <?php if(current_url()==base_url('admin/ratesmobile')
								|| current_url()==base_url('admin/ratesmobile/rate/'.$this->uri->segment(4))
								|| current_url()==base_url('admin/ratesmobile/import')){ ?>active open<?php }?> menu_separate_border">
							<a href="<?php echo base_url('admin/ratesmobile');?>" class="nav-link ">
							   <i class="fa fa-tag"></i> <span class="title"><?php echo lang('page_ratesmobile');?></span>
							</a>
						</li>
						<?php
						$sep_border = false;
					}
					?>

					<!-- Rates Land line Manage -->
					<?php
					$sep_border = true;
					if($ratelandline_permission['view'] || $ratelandline_permission['edit'] || $ratelandline_permission['delete'] || $ratelandline_permission['import']){
						?>
						<li class="nav-item <?php if(current_url()==base_url('admin/rateslandline')
								|| current_url()==base_url('admin/rateslandline/rate/'.$this->uri->segment(4))
								|| current_url()==base_url('admin/rateslandline/import')){ ?>active open<?php }?> menu_separate_border">
							<a href="<?php echo base_url('admin/rateslandline');?>" class="nav-link ">
							   <i class="fa fa-tag"></i> <span class="title"><?php echo lang('page_rateslandline');?></span>
							</a>
						</li>
						<?php
						$sep_border = false;
					}
					?>

					<!-- Options Mobile Manage -->
					<?php
					$sep_border = true;
					if($optionmobile_permission['view'] || $optionmobile_permission['edit'] || $optionmobile_permission['delete'] || $optionmobile_permission['import']){
						?>
						<li class="nav-item <?php if(current_url()==base_url('admin/optionsmobile')
								|| current_url()==base_url('admin/optionsmobile/option/'.$this->uri->segment(4))
								|| current_url()==base_url('admin/optionsmobile/import')){ ?>active open<?php }?> menu_separate_border">
							<a href="<?php echo base_url('admin/optionsmobile');?>" class="nav-link ">
							   <i class="fa fa-tag"></i> <span class="title"><?php echo lang('page_optionsmobile');?></span>
							</a>
						</li>
						<?php
						$sep_border = false;
					}
					?>

					<!-- Options Land line Manage -->
					<?php
					$sep_border = true;
					if($optionlandline_permission['view'] || $optionlandline_permission['edit'] || $optionlandline_permission['delete'] || $optionlandline_permission['import']){
						?>
						<li class="nav-item <?php if(current_url()==base_url('admin/optionslandline')
								|| current_url()==base_url('admin/optionslandline/option/'.$this->uri->segment(4))
								|| current_url()==base_url('admin/optionslandline/import')){ ?>active open<?php }?> menu_separate_border">
							<a href="<?php echo base_url('admin/optionslandline');?>" class="nav-link ">
							   <i class="fa fa-tag"></i> <span class="title"><?php echo lang('page_optionslandline');?></span>
							</a>
						</li>
						<?php
						$sep_border = false;
					}
					?>


					<!-- Discount Level Manage -->
					<?php
					$sep_border = true;
					if($discountlevel_permission['view'] || $discountlevel_permission['edit'] || $discountlevel_permission['delete'] || $discountlevel_permission['import']){
						?>
						<li class="nav-item <?php if(current_url()==base_url('admin/discountlevels')
								|| current_url()==base_url('admin/discountlevels/discount/'.$this->uri->segment(4))
								|| current_url()==base_url('admin/discountlevels/import')){ ?>active open<?php }?> menu_separate_border">
							<a href="<?php echo base_url('admin/discountlevels');?>" class="nav-link ">
							   <i class="fa fa-tag"></i> <span class="title"><?php echo lang('page_discountlevels');?></span>
							</a>
						</li>
						<?php
						$sep_border = false;
					}
					?>


					<!-- Hardware Manage -->
					<?php
						$sep_border = true;
						if($hardware_permission['view'] || $hardware_permission['edit'] || $hardware_permission['delete'] || $hardware_permission['import']){
							?>
							<li class="nav-item <?php if(current_url()==base_url('admin/hardwares')
									|| current_url()==base_url('admin/hardwares/hardware/'.$this->uri->segment(4))
									|| current_url()==base_url('admin/hardwares/import')){ ?>active open<?php }?> menu_separate_border">
								<a href="<?php echo base_url('admin/hardwares');?>" class="nav-link ">
								   <i class="fa fa-tag"></i> <span class="title"><?php echo lang('page_hardware');?></span>
								</a>
							</li>
							<?php
							$sep_border = false;
						}
					?>

					<!-- Supplier Manage -->
					<?php
					$sep_border = true;
					if($supplier_permission['view'] || $supplier_permission['edit'] || $supplier_permission['delete']){
						?>
						<li class="nav-item <?php if(current_url()==base_url('admin/suppliers')
							|| current_url()==base_url('admin/suppliers/supplier/'.$this->uri->segment(4))
						){ ?>active open<?php }?> menu_separate_border">
							<a href="<?php echo base_url('admin/suppliers');?>" class="nav-link ">
							   <i class="fa fa-tag"></i> <span class="title"><?php echo lang('page_suppliers');?></span>
							</a>
						</li>
						<?php
						$sep_border = false;
					}
					?>


					<!-- Document Setting Manage -->
					<?php
					$sep_border = true;
					if($documentsetting_permission['view'] || $documentsetting_permission['edit'] || $documentsetting_permission['delete']){
						?>
						<li class="nav-item <?php if(current_url()==base_url('admin/documentsettings')
							|| current_url()==base_url('admin/documentsettings/category/'.$this->uri->segment(4))
						){ ?>active open<?php }?> menu_separate_border">
							<a href="<?php echo base_url('admin/documentsettings');?>" class="nav-link ">
							   <i class="fa fa-tag"></i> <span class="title"><?php echo lang('page_documentsettings');?></span>
							</a>
						</li>
						<?php
						$sep_border = false;
					}
					?>


					<!-- Employee Commission Slip Manage -->
					<?php
					$sep_border = true;
					if($employeecommission_permission['view']){
						?>
						<li class="nav-item <?php if(current_url()==base_url('admin/employeecommissions')){ ?>active open<?php }?> menu_separate_border">
							<a href="<?php echo base_url('admin/employeecommissions');?>" class="nav-link ">
							   <i class="fa fa-tag"></i> <span class="title"><?php echo lang('page_employeecommissions');?></span>
							</a>
						</li>
						<?php
						$sep_border = false;
					}
					?>


					<?php
					//$sep_border = true;
					if($history_permission['view']){
						?>
						<li class="nav-item <?php if(current_url()==base_url('admin/history')){ ?>active open<?php }?> menu_separate_border">
							<a href="<?php echo base_url('admin/history');?>" class="nav-link ">
							   <i class="fa fa-history"></i> <span class="title"><?php echo lang('page_history');?></span>
							</a>
						</li>
						<?php
						//$sep_border = false;
					}
					?>

				</ul>
			</li>
			<?php
			}
			?>
			<!-- END SETTINGS -->



			<!-- FOR POS USER AND CUSTOMER USER -->
			<?php
			/*if(get_user_role()=='customer' || (get_user_role()=='user' && isset($GLOBALS['current_user']->userrole) && $GLOBALS['current_user']->userrole==6)){
				if($profile_permission['edit']){
					?>
					<li class="nav-item <?php if(current_url()==base_url('admin/settings/profile')){ ?>active open<?php }?> menu_separate_border">
						<a href="<?php echo base_url('admin/settings/profile');?>" class="nav-link ">
						   <i class="icon-settings"></i> <span class="title"><?php echo lang('page_settings');?></span>
						</a>
					</li>
					<?php
				}
			}*/
			?>


			<!-- FOR POS USER -->
			<?php
			if(get_user_role()=='user' && isset($GLOBALS['current_user']->userrole) && $GLOBALS['current_user']->userrole==6){
				if($employeecommission_permission['view_own']){
					?>
					<li class="nav-item <?php if(current_url()==base_url('admin/employeecommissions')){ ?>active open<?php }?> menu_separate_border">
						<a href="<?php echo base_url('admin/employeecommissions');?>" class="nav-link ">
						   <i class="fa fa-tag"></i> <span class="title"><?php echo lang('page_employeecommissions');?></span>
						</a>
					</li>
					<?php
				}
			}
			?>


			<!-- FOR POS USER AND CUSTOMER USER -->
			<?php
			/*if(get_user_role()=='customer' || (get_user_role()=='user' && isset($GLOBALS['current_user']->userrole)
			&& $GLOBALS['current_user']->userrole==6)){*/
			if($document_permission['view'] || $document_permission['create'] || $document_permission['edit'] || $document_permission['delete']){
				?>
				<li class="nav-item <?php if(current_url()==base_url('admin/documents')){ ?>active open<?php }?> menu_separate_border">
					<a href="<?php echo base_url('admin/documents');?>" class="nav-link ">
					   <i class="fa fa-file"></i> <span class="title"><?php echo lang('page_documents');?></span>
					</a>
				</li>
				<?php
			}
			?>

			<?php
			if($infodocument_permission['view'] || $infodocument_permission['create'] || $infodocument_permission['edit'] || $infodocument_permission['delete']){
					?>
					<li class="nav-item <?php if(current_url()==base_url('admin/infodocuments')){ ?>active open<?php }?> menu_separate_border">
							<a href="<?php echo base_url('admin/infodocuments');?>" class="nav-link ">
							   <i class="fa fa-file"></i> <span class="title"><?php echo lang('page_infodocuments');?></span>
							</a>
					</li>
					<?php
			}
			?>

			<?php // print_r($GLOBALS['current_user']); ?>
			<?php if ( get_user_role() === 'customer' ) { ?>
				<!-- Hardware Manage -->
				<?php /* if ( $hardware_permission['view'] || $hardware_permission['edit'] || $hardware_permission['delete'] || $hardware_permission['import'] ) { ?>
					<li class="nav-item
						<?php
							if (
								current_url() == base_url('admin/hardwares')
								|| current_url() == base_url('admin/hardwares/hardware/'. $this->uri->segment(4))
								|| current_url() == base_url('admin/hardwares/import')
							) {
						?> active open <?php } ?> menu_separate_border">
						<a href="<?php echo base_url('admin/hardwares'); ?>" class="nav-link">
							<i class="fa fa-tag"></i> <span class="title"><?php echo lang('page_hardware'); ?></span>
						</a>
					</li>
				<?php } */ ?>

				<?php if ( $GLOBALS['current_user']->parent_customer_id == 0 ) { ?>
					<li class="nav-item
						<?php
							if (
								current_url() == base_url('admin/subcustomers')
								|| current_url() == base_url('admin/subcustomers/customer')
								|| current_url() == base_url('admin/subcustomers/customer/'. $this->uri->segment(4))
								|| current_url() == base_url('admin/subcustomers/detail/'. $this->uri->segment(4))
							) {
						?> active open <?php } ?>">
						<a href="<?php echo base_url('admin/subcustomers');?>" class="nav-link">
							<i class="fa fa-file"></i> <span class="title">Unterbenutzer</span>
						</a>
					</li>
				<?php } ?>
			<?php } ?>

			<?php if($termination_permission['view'] || $termination_permission['create'] || $termination_permission['edit'] || $termination_permission['delete']){ ?>
				<li class="nav-item
					<?php
						if (
							current_url() == base_url('admin/termination')
							|| current_url() == base_url('admin/termination/customer')
							|| current_url() == base_url('admin/termination/customer/'. $this->uri->segment(4))
							|| current_url() == base_url('admin/termination/detail/'. $this->uri->segment(4))
						) {
					?> active open <?php } ?>">
					<a href="<?php echo base_url('admin/termination');?>" class="nav-link">
						<i class="fa fa-file"></i> <span class="title">Terminierung</span>
					</a>
				</li>
			<?php } ?>
		</ul>
		<!-- END SIDEBAR MENU -->
	</div>
	<!-- END SIDEBAR -->
</div>
<!-- END SIDEBAR -->
