<?php
//ASSIGNMENTS
if(isset($dashboard_assignments)){
?>                        
<!--<div class="col-md-12 col-sm-12">-->
	<div class="portlet portlet-sortable light bordered">
		<div class="portlet-title">
			<div class="caption">
				<i class="fa fa-file"></i>
				<span class="caption-subject bold uppercase"><?php echo lang('page_assignments')?></span>
			</div>
		</div>
		<div class="portlet-body">
			<div class="scroller" style="max-height: 170px;" data-always-visible="1" data-rail-visible="1">
				<ul class="feeds">
					<?php
					if(count($dashboard_assignments)>0){
						foreach($dashboard_assignments as $dashboard_assignment){      
							?>
							<li>
                            	<a href="<?php echo base_url('admin/assignments/detail/'.$dashboard_assignment['assignmentnr']);?>">
								<div class="col1">
									<div class="cont">
										<div class="cont-col1">
											<div class="label label-sm label-default">
												<i class="fa fa-file"></i>
											</div>
										</div>
										<div class="cont-col2">
											<div class="desc">
												<?php echo $dashboard_assignment['company'];?>
												<small>(<?php echo $dashboard_assignment['assignmentnr_prefix'];?>)</small>
                                                <br /><div class="status"><?php echo $dashboard_assignment['status'];?></div>
											</div>
										</div>
									</div>
								</div>
								<div class="col2">
									<div class="date"><?php echo _d($dashboard_assignment['created']);?></div>
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
<!--</div>-->
<?php
}
?>	