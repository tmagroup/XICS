<?php
//QUALITYCHECKS
if(isset($dashboard_qualitychecks)){
?>                        
<div class="col-md-12 col-sm-12">
	<div class="portlet light bordered">
		<div class="portlet-title">
			<div class="caption">
				<i class="fa fa-shield"></i>
				<span class="caption-subject bold uppercase"><?php echo lang('page_qualitychecks')?></span>
			</div>
		</div>
		<div class="portlet-body">
			<div class="scroller" style="max-height: 170px;" data-always-visible="1" data-rail-visible="0">
				<ul class="feeds">
					<?php
					if(count($dashboard_qualitychecks)>0){
						foreach($dashboard_qualitychecks as $dashboard_qualitycheck){      
							?>
							<li>
                            	<a href="<?php echo base_url('admin/qualitychecks/detail/'.$dashboard_qualitycheck['qualitychecknr']);?>">
								<div class="col1">
									<div class="cont">
										<div class="cont-col1">
											<div class="label label-sm label-default">
												<i class="fa fa-shield"></i>
											</div>
										</div>
										<div class="cont-col2">
											<div class="desc">
												<?php echo $dashboard_qualitycheck['company'];?>
												<small>(<?php echo $dashboard_qualitycheck['qualitychecknr_prefix'];?>)</small>
                                                <br /><div class="status"><?php echo $dashboard_qualitycheck['status'];?></div>
											</div>
										</div>
									</div>
								</div>
								<div class="col2">
									<div class="date"><?php echo _d($dashboard_qualitycheck['created']);?></div>
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