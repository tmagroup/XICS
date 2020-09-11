<?php
//HARDWARE INVOICES
if(isset($dashboard_hardwareinvoices)){
?>                        

	<div class="portlet portlet-sortable light bordered" data-widget-id="hardwareinvoices">
		<div class="portlet-title ui-sortable-handle">
			<div class="caption">
				<i class="fa fa-file font-blue"></i>
				<span class="caption-subject font-blue bold uppercase"><?php echo lang('page_hardwareinvoices')?></span>
			</div>
		</div>
		<div class="portlet-body">
			<div class="scroller" style="max-height: 170px;" data-always-visible="1" data-rail-visible="0">
				<ul class="feeds">
					<?php
					if(count($dashboard_hardwareinvoices)>0){
						foreach($dashboard_hardwareinvoices as $dashboard_hardwareinvoice){      
							?>
							<li>
                            	<a href="<?php echo base_url('admin/hardwareinvoices/printhardwareinvoice/'.$dashboard_hardwareinvoice['invoicenr']);?>" target="_blank">
								<div class="col1">
									<div class="cont">
										<div class="cont-col1">
											<div class="label label-sm label-info">
												<i class="fa fa-file"></i>
											</div>
										</div>
										<div class="cont-col2">
											<div class="desc">
												<?php echo $dashboard_hardwareinvoice['customer_company'];?>
												<small>(<?php echo $dashboard_hardwareinvoice['invoicenr_prefix'];?>)</small>
                                                <br /><div class="status"><?php if($dashboard_hardwareinvoice['status']==1){ echo lang('page_lb_paid'); }else{ echo lang('page_lb_notpaid'); }?></div>
											</div>
										</div>
									</div>
								</div>
								<div class="col2">
									<div class="date"><?php echo _d($dashboard_hardwareinvoice['created']);?></div>
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