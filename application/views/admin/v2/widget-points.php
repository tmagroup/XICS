<?php
//LEADS
if(isset($dashboard_points)){
?>                        
<div class="col-md-12 col-sm-12">
	<div class="portlet light bordered">
		<div class="portlet-title">
			<div class="caption">
				<i class="icon-bar-chart font-yellow"></i>
				<span class="caption-subject font-yellow bold uppercase"><?php echo lang('page_dt_points')?></span>
			</div>
		</div>
		<div class="portlet-body">
			<div class="scroller" style="max-height: 170px;" data-always-visible="1" data-rail-visible="0">
				<ul class="feeds">
					<?php
					if(count($dashboard_points)>0){
						foreach($dashboard_points as $dashboard_point){      
							?>
							<li>
                            	<a href="<?php echo base_url('admin/employeecommissions/downloadslip/'.$dashboard_point['slipnr']);?>">
								<div class="col1">
									<div class="cont">
										<div class="cont-col1">
											<div class="label label-sm label-warning">
												<i class="icon-bar-chart"></i>
											</div>
										</div>
										<div class="cont-col2">
											<div class="desc">
												<?php echo date('M-Y',strtotime($dashboard_point['period']."-1"));?>
												<small>(<?php echo $dashboard_point['slipnr_prefix'];?>)</small>
											</div>
										</div>
									</div>
								</div>
								<div class="col2">
									<div class="date"><?php echo $dashboard_point['pointsvalue'];?></div>
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
</div>
<?php
}
?>	