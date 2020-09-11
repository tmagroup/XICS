<?php
//NOTIFICATIONS
if(isset($dashboard_notifications)){
?>                        

	<div id="widget_dashboard_notifications" class="portlet portlet-sortable light bordered" data-widget-id="notifications">
		<div class="portlet-title ui-sortable-handle">
			<div class="caption">
				<i class="fa fa-bell-o font-yellow"></i>
				<span class="caption-subject font-yellow bold uppercase"><?php echo lang('nav_notifications')?></span>
			</div>
		</div>
		<div class="portlet-body">
			<div class="scroller" style="max-height: 170px;" data-always-visible="1" data-rail-visible="0">
				<ul class="feeds">
					<?php
					if(count($dashboard_notifications)>0){
						foreach($dashboard_notifications as $dashboard_notification){ 
							?>
							<li>
                            	<?php
								switch($dashboard_notification['rel_type']){
									case 'lead': //And Comment also
			                            echo '<a href="'.base_url('admin/leads/detail/'.$dashboard_notification['rel_id']).'">';
									break;
									case 'todo': //And Comment also
			                            echo '<a href="'.base_url('admin/todos/detail/'.$dashboard_notification['rel_id']).'">';
									break;
									case 'quotation': //And Comment also
			                            echo '<a href="'.base_url('admin/quotations/detail/'.$dashboard_notification['rel_id']).'">';
									break;
									case 'assignment':
			                            echo '<a href="'.base_url('admin/assignments/detail/'.$dashboard_notification['rel_id']).'">';
									break;
									case 'hardwareassignment':
			                            echo '<a href="'.base_url('admin/hardwareassignments/detail/'.$dashboard_notification['rel_id']).'">';
									break;
									case 'customer': //And Comment also
			                            echo '<a href="'.base_url('admin/customers/detail/'.$dashboard_notification['rel_id']).'">';
									break;
									case 'ticket': //Comment Only
			                            echo '<a href="'.base_url('admin/tickets/detail/'.$dashboard_notification['rel_id']).'">';
									break;	
									case 'monitoring': //Comment Only
			                            echo '<a href="'.base_url('admin/monitorings/detail/'.$dashboard_notification['rel_id']).'">';
									break;	                                                                        
								}
                                ?>
								<div class="col1">
									<div class="cont">
										<div class="cont-col1">
											<div class="label label-sm label-warning">
												<i class="fa fa-bell-o"></i>
											</div>
										</div>
										<div class="cont-col2">
											<div class="desc">
												<?php echo $dashboard_notification['subject'];?><br  />
                                                <?php echo $dashboard_notification['message'];?>
												<small>(<?php echo lang('from');?>: <?php echo $dashboard_notification['fromname'];?>)</small>
                                                <br /><div class="status"><?php echo $dashboard_notification['type'];?></div>
											</div>
										</div>
									</div>
								</div>
								<div class="col2">
									<div class="date"><?php echo $dashboard_notification['reminddate'];?></div>
								</div>
                                <?php echo '</a>';?>
							</li> 
							<?php
						}
					}
					else{
						?>
						<li>
                            <div class="col1">
                                <div class="cont">
                                    <div class="cont-col1">
                                        <div class="desc">
                                            <?php echo lang('not_available');?>
                                        </div>
                                    </div>
                                </div>
                            </div>                            
                        </li> 		
						<?php    
					}
					?>
				</ul>
			</div>
			<!--<div class="scroller-footer">
				<div class="btn-arrow-link pull-right">
					<a href="javascript:;">View More</a>
					<i class="icon-arrow-right"></i>
				</div>
			</div>-->
		</div>
	</div> 

<?php
}
?>  	