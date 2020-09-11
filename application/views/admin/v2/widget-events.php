<style>
.font-calendar{ color:<?php echo isset($dashboard_events['calendarColorCode'])?$dashboard_events['calendarColorCode']:'';?> !important; }
.label-calendar{ background-color:<?php echo isset($dashboard_events['calendarColorCode'])?$dashboard_events['calendarColorCode']:'';?> !important; }    
</style>    
<?php
$dashboard_events = $dashboard_events['events'];
//EVENTS
if(isset($dashboard_events)){
?>                        
<div class="col-md-12 col-sm-12">
	<div class="portlet light bordered">
		<div class="portlet-title">
			<div class="caption">
				<i class="fa fa-calendar font-calendar"></i>
				<span class="caption-subject font-calendar bold uppercase" style=""><?php echo lang('page_events')?></span>
			</div>
		</div>
		<div class="portlet-body">
			<div class="scroller" style="max-height: 170px;" data-always-visible="1" data-rail-visible="0">
				<ul class="feeds">
					<?php
					if(count($dashboard_events)>0){
						foreach($dashboard_events as $dashboard_event){   
							?>
							<li>
                            	<a href="<?php echo base_url('admin/calendars/detail/'.$dashboard_event['eventid']);?>">
								<div class="col1">
									<div class="cont">
										<div class="cont-col1">
											<div class="label label-sm label-calendar" style="background-color:<?php echo $dashboard_event['color'];?>">
												<i class="fa fa-calendar"></i>
											</div>
										</div>
										<div class="cont-col2">
											<div class="desc">
												<?php echo $dashboard_event['title'];?>
												<small>(<?php echo $dashboard_event['eventstatusname'];?>)</small>
											</div>
										</div>
									</div>
								</div>
								<div class="col2">
									<div class="date"><?php echo _d($dashboard_event['created']);?></div>
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