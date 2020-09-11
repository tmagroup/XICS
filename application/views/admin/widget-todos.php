<?php
//TODOS
if(isset($dashboard_todos)){
?>                        

	<div class="portlet portlet-sortable light bordered" data-widget-id="todos">
		<div class="portlet-title ui-sortable-handle">
			<div class="caption">
				<i class="fa fa-comment font-red"></i>
				<span class="caption-subject font-red bold uppercase"><?php echo lang('page_todos')?></span>
			</div>
		</div>
		<div class="portlet-body">
			<div class="scroller" style="max-height: 170px;" data-always-visible="1" data-rail-visible="0">
				<ul class="feeds">
					<?php
					if(count($dashboard_todos)>0){
						foreach($dashboard_todos as $dashboard_todo){      
							?>
							<li>
                            	<a href="<?php echo base_url('admin/todos/detail/'.$dashboard_todo['todonr']);?>">
								<div class="col1">
									<div class="cont">
										<div class="cont-col1">
											<div class="label label-sm label-danger">
												<i class="fa fa-comment"></i>
											</div>
										</div>
										<div class="cont-col2">
											<div class="desc">
												<?php echo $dashboard_todo['todotitle'];?>
												<small>(<?php echo $dashboard_todo['todonr_prefix'];?>)</small>
                                                <br /><div class="status"><?php echo $dashboard_todo['status'];?></div>
											</div>
										</div>
									</div>
								</div>
								<div class="col2">
									<div class="date"><?php echo _d($dashboard_todo['created']);?></div>
								</div>
                                </a>
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