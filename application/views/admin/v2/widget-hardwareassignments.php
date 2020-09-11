<?php
//HARDWARE ASSIGNMENTS
if(isset($dashboard_hardwareassignments)){
?>                        
<div class="col-md-12 col-sm-12">
	<div class="portlet light bordered">
		<div class="portlet-title">
			<div class="caption">
				<i class="icon-settings font-green"></i>
				<span class="caption-subject font-green bold uppercase"><?php echo lang('page_hardwareassignments')?></span>
			</div>
		</div>
		<div class="portlet-body">
			<div class="scroller" style="max-height: 170px;" data-always-visible="1" data-rail-visible="0">
				<ul class="feeds">
					<?php
					if(count($dashboard_hardwareassignments)>0){
						foreach($dashboard_hardwareassignments as $dashboard_hardwareassignment){      
							?>
							<li>
                            	<a href="<?php echo base_url('admin/hardwareassignments/detail/'.$dashboard_hardwareassignment['hardwareassignmentnr']);?>">
								<div class="col1">
									<div class="cont">
										<div class="cont-col1">
											<div class="label label-sm label-success">
												<i class="icon-settings"></i>
											</div>
										</div>
										<div class="cont-col2">
											<div class="desc">
												<?php echo $dashboard_hardwareassignment['company'];?>
												<small>(<?php echo $dashboard_hardwareassignment['hardwareassignmentnr_prefix'];?>)</small>
                                                <br /><div class="status"><?php echo $dashboard_hardwareassignment['status'];?></div>
											</div>
										</div>
									</div>
								</div>
								<div class="col2">
									<div class="date"><?php echo _d($dashboard_hardwareassignment['created']);?></div>
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