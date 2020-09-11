<?php
	/*
	 * Paging
	 */
	$aColumns = array(
		'tblassignmentproducts.id as id',
		'tblassignmentproducts.simnr as simnr',
		'tblassignmentproducts.mobilenr as mobilenr',
		'tblassignmentproducts.employee as employee',
		'tblassignmentproducts.puk as puk',
		'tblassignmentproducts.ultracard1 as ultracard1',
		'tblassignmentproducts.ultracard2 as ultracard2',
		'tblassignmentproducts.value2 as value2',
		'tblassignmentproducts.extemtedterm as extemtedterm',
		'tblassignmentproducts.subscriptionlock as subscriptionlock',
		'tblassignmentproducts.value4 as value4',
		'tblassignmentproducts.cardstatus as cardstatus',
		'tblassignmentproducts.endofcontract as endofcontract',
		'tblassignmentproducts.finished as finished',
		'tblassignmentproducts.is_paused as is_paused',
		'tblassignmentproducts.cardbreak as cardbreak',
		'tblassignmentproducts.hardwareassignmentnr as hardwareassignmentnr',
		'tblassignmentproducts.formula as formula',
		'tblassignmentproducts.pin as pin',
		'tblvvlneu.name as vvlneu',
		'tblratesmobile.ratetitle as newratemobile',
		'tblassignmentproducts.newratemobile as newratemobile_id',
		'tbloptionsmobile.optiontitle as newoptionmobile',
		'tblhardwares.hardwaretitle as hardware'
	);
	$sIndexColumn  = "id";
	$sTable        = 'tblassignmentproducts';

	$join = array(' LEFT JOIN tblvvlneu ON tblvvlneu.id=tblassignmentproducts.vvlneu ',
	' LEFT JOIN tblratesmobile ON tblratesmobile.ratenr=tblassignmentproducts.newratemobile ',
	' LEFT JOIN tbloptionsmobile ON tbloptionsmobile.optionnr=tblassignmentproducts.newoptionmobile ',
	' LEFT JOIN tblhardwares ON tblhardwares.hardwarenr=tblassignmentproducts.hardware ');

	//$where = do_action('reminders_table_sql_where', array());
	$where = array();
	array_push($where, "AND tblassignmentproducts.assignmentnr='".$assignmentid."' ");

	// $customOrderBy = ' tblbills.monthyear DESC ';
	$result  = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where, $additionalSelect = array(), $sGroupBy = '', $customOrderBy);

	$output  = $result['output'];
	$rResult = $result['rResult'];

	$iTotalRecords = $output['iTotalRecords']; //count($rResult);
	$iDisplayLength = (isset($_REQUEST['length']))?intval($_REQUEST['length']):0;
	$iDisplayLength = $iDisplayLength < 0 ? $iTotalRecords : $iDisplayLength;
	$iDisplayStart = (isset($_REQUEST['start']))?intval($_REQUEST['start']):0;
	$sEcho = (isset($_REQUEST['draw']))?intval($_REQUEST['draw']):0;
	$records = array();
	$records["data"] = array();

	//Don't remove
	$end = $iDisplayStart + $iDisplayLength;
	$end = $end > $iTotalRecords ? $iTotalRecords : $end;

	$CI =& get_instance();
	//Data Loop
	$idx = $iDisplayStart+1;
	foreach ($rResult as $aRow) {
		ob_start();
		?>
			<?php if(get_user_role()=='customer'){ ?>
				<td class="text-center">
					<label style="display: none;"><?= $aRow['employee']?></label>
					<input type="text" name="employee[<?php echo $aRow['id'];?>]" value="<?php echo $aRow['employee'];?>" class="form-control noerror" />

					<?php if($GLOBALS['a_pin_puk_permission']['create']){ ?>
						<div style="margin-top: 10px;">
							<?php echo lang('page_fl_pin');?>:<br>
							<?php echo form_input('pin['.$aRow['id'].']', $aRow['pin'], 'class="form-control noerror"');?>
						</div>
					<?php }

			} else {
				echo $aRow['employee'];
				if($aRow['pin']!="" && $GLOBALS['a_pin_puk_permission']['view']){ ?>
					<div style="margin-top: 10px;">
						<?php echo lang('page_fl_pin');?>:
						<?php echo $aRow['pin'];?>
					</div>
				<?php } ?>
			<?php } ?>
		<?php
		$pin = ob_get_contents();
		ob_end_clean();

		ob_start();
		if(get_user_role()=='customer') {
			echo $aRow['vvlneu'];

			if($GLOBALS['a_pin_puk_permission']['create']){ ?>
				<br>
				<div style="margin-top: 10px;">
					<?php echo lang('page_fl_puk');?>:<br>
					<?php echo form_input('puk['.$aRow['id'].']', $aRow['puk'], 'class="form-control noerror" style="width: auto;"');?>
				</div>
			<?php }

		} else {
			echo $aRow['vvlneu'];

			if($aRow['puk']!="" && $GLOBALS['a_pin_puk_permission']['view']){ ?>
				<div style="margin-top: 10px;">
					<?php echo lang('page_fl_puk');?>:
					<?php echo $aRow['puk'];?>
				</div>
			<?php }
		}
		$puk = ob_get_contents();
		ob_end_clean();

		ob_start();
		echo $aRow['newratemobile'];?><br><br>
		<div id="ultracard_<?php echo $aRow['id'];?>" class="divcenter" style="display:none;white-space:nowrap;">
			<label>
				<?php echo $aRow['ultracard1']=='1'?'<i class="fa fa-check"></i>':'<i class="fa fa-close"></i>';?>
				<?php echo lang('page_fl_ultracard1');?>
			</label><br>
			<label>
				<?php echo $aRow['ultracard2']=='1'?'<i class="fa fa-check"></i>':'<i class="fa fa-close"></i>';?>
				<?php echo lang('page_fl_ultracard2');?>
			</label>

		</div>

		<?php
		$newratemobile = ob_get_contents();
		ob_end_clean();

		ob_start();
		echo $aRow['newoptionmobile'];
		if($GLOBALS['a_moreoptionmobile_permission']['view']){
			$rowMoreOptionMobiles = $CI->Assignmentproductmoreoptionmobile_model->get("","",array(),"assignmentnr='".$assignmentid."' AND assignmentproductid='".$aRow['id']."'");
			if(isset($rowMoreOptionMobiles) && count($rowMoreOptionMobiles)>0){
				foreach($rowMoreOptionMobiles as $kOpt=>$rowMoreOptionMobile){
					$newoptionmobile = isset($rowMoreOptionMobile['newoptionmobile'])?$rowMoreOptionMobile['newoptionmobile']:'';
					foreach($mobileoptions_2 as $mobileoption2){
						if($mobileoption2['optionnr']==$newoptionmobile){
							echo '<div class="clearfix"></div>';
							echo '<div class="text-nowrap">'.$mobileoption2['optiontitle'].'</div>';
						}
					}
				}
			}
		}
		$newoptionmobile = ob_get_contents();
		ob_end_clean();

		ob_start();
		echo $aRow['value4']?$aRow['value4']:'&nbsp;';
		if($GLOBALS['a_moreoptionmobile_permission']['view']){
			$rowMoreOptionMobiles = $CI->Assignmentproductmoreoptionmobile_model->get("","",array(),"assignmentnr='".$assignmentid."' AND assignmentproductid='".$aRow['id']."'");
			if(isset($rowMoreOptionMobiles) && count($rowMoreOptionMobiles)>0){
				foreach($rowMoreOptionMobiles as $kOpt=>$rowMoreOptionMobile){
					echo isset($rowMoreOptionMobile['value4'])?'<div class="clearfix"></div><div class="text-nowrap">'.$rowMoreOptionMobile['value4']:'</div><div class="clearfix"></div>&nbsp;';
				}
			}
		}

		$value4 = ob_get_contents();
		ob_end_clean();

		$buttons = '';
		if (isset($GLOBALS['current_user']->userrole) && $GLOBALS['current_user']->userrole!=6) {
			ob_start(); ?>
			<a href="javascript:void(0);" onclick="FormTicketAjax('<?php echo base_url('admin/assignments/generateTicket/'.$assignmentid);?>','<?php echo $assignmentid;?>','cardlock','<?php echo lang('page_ticket')." - ".lang('page_lb_cardlock');?>','<?php echo lang('page_lb_cardlock_popup_ask');?>','<?php echo $aRow['id'];?>');" class="btn sbold green btn-sm"> <i class="fa fa-life-ring"></i> <?php echo lang('page_lb_cardlock');?></a>

			<?php if($aRow['subscriptionlock']==1){
				if($GLOBALS['a_subscriptionlock2_permission']['create']){ ?>
					<a href="javascript:void(0);" onclick="FormTicketAjax('<?php echo base_url('admin/assignments/generateTicket/'.$assignmentid);?>','<?php echo $assignmentid;?>','subscriptionlock2','<?php echo lang('page_ticket')." - ".lang('page_lb_subscriptionlock2');?>','<?php echo lang('page_lb_subscriptionlock2_popup_ask');?>','<?php echo $aRow['id'];?>');" class="btn sbold green-jungle btn-sm"> <?php echo lang('page_lb_subscriptionlock2');?></a>
				<?php }

			} else {
				if($GLOBALS['a_subscriptionlock_permission']['create']){ ?>
					<a href="javascript:void(0);" onclick="FormTicketAjax('<?php echo base_url('admin/assignments/generateTicket/'.$assignmentid);?>','<?php echo $assignmentid;?>','subscriptionlock','<?php echo lang('page_ticket')." - ".lang('page_lb_subscriptionlock');?>','<?php echo lang('page_lb_subscriptionlock_popup_ask');?>','<?php echo $aRow['id'];?>');" class="btn sbold red btn-sm"> <?php echo lang('page_lb_subscriptionlock');?></a>
				<?php }
			} ?>

			<?php if($GLOBALS['a_optionbook_permission']['create']) { ?>
				<a href="javascript:void(0);" onclick="FormTicketMobileOptionAjax('<?php echo base_url('admin/assignments/generateTicket/'.$assignmentid);?>','<?php echo $assignmentid;?>','optionbook','<?php echo lang('page_ticket')." - ".lang('page_lb_optionbook');?>','<?php echo $aRow['id'];?>');" class="btn sbold red btn-sm"> <?php echo lang('page_lb_optionbook');?></a>
			<?php } ?>

			<?php if($GLOBALS['a_hardwareorder_permission']['create']) {
				if(empty($aRow['hardware'])) {
					?>
					<a href="javascript:void(0);" onclick="FormTicketHardwareOrderAjax('<?php echo base_url('admin/assignments/generateTicket/'.$assignmentid);?>','<?php echo $assignmentid;?>','hardwareorder','<?php echo lang('page_ticket')." - ".lang('page_lb_hardwareorder');?>','<?php echo $aRow['id'];?>');" class="btn sbold red btn-sm"> <?php echo lang('page_lb_hardwareorder');?></a>
				<?php }
			} ?>

			<?php if($GLOBALS['a_ultracardorder_permission']['create']){
				//Ultracard Order Button for Gray
				$btn_ultracardorder = '';
				if($aRow['ultracard1']=='1' && $aRow['ultracard2']=='1') {
					$btn_ultracardorder = '1';
				} ?>
				<a id="ultracardorder_<?php echo $aRow['id'];?>" href="javascript:void(0);" onclick="FormTicketCardOrderAjax('<?php echo base_url('admin/assignments/generateTicket/'.$assignmentid);?>','<?php echo $assignmentid;?>','ultracardorder','<?php echo lang('page_ticket')." - ".lang('page_lb_ultracardorder');?>','<?php echo $aRow['id'];?>');" class="btn sbold <?php if($btn_ultracardorder==1){ echo 'grey disabled'; }else{ echo 'red'; }?> btn-sm"><?php if($btn_ultracardorder==1){ echo lang('page_lb_ultracardorder2'); }else{ echo lang('page_lb_ultracardorder'); }?></a>
			<?php } ?>

			<?php if($GLOBALS['a_cardpause_permission']['create']) {
				if($aRow['is_paused']==1){ ?>
					<a href="javascript:void(0);" onclick="FormTicketAjax('<?php echo base_url('admin/assignments/generateTicket/'.$assignmentid);?>','<?php echo $assignmentid;?>','cardpause2','<?php echo lang('page_ticket')." - ".lang('page_lb_cardpause2');?>','<?php echo lang('page_lb_cardpause2_popup_ask');?>','<?php echo $aRow['id'];?>');" class="btn sbold red btn-sm"> <?php echo lang('page_lb_cardpause2');?></a>
				<?php } else { ?>
					<a id="cardpause_<?php echo $aRow['id'];?>" href="javascript:void(0);" onclick="FormTicketCardPauseAjax('<?php echo base_url('admin/assignments/generateTicket/'.$assignmentid);?>','<?php echo $assignmentid;?>','cardpause','<?php echo lang('page_ticket')." - ".lang('page_lb_cardpause');?>','<?php echo $aRow['id'];?>','<?php echo $aRow['is_paused'];?>');" class="btn sbold red btn-sm"> <?php echo lang('page_lb_cardpause');?></a>
				<?php }
			} ?>

			<?php if ($aRow['hardwareassignmentnr'] == 0): ?>
				<a href="javascript:void(0);" onclick="FormExternalHardware('<?php echo $assignmentid;?>', '<?php echo $aRow['id'];?>');" class="btn sbold green btn-sm">Externe Hardware hinzuf&uuml;gen</a>
			<?php endif ?>

			<?php $formula = ($aRow['formula']=='M')?'M':'A';
			if($formula=='A'){ ?>
				<script>
					jQuery.ajax({url: '<?php echo base_url('admin/ratesmobile/getInputUltraCard/'.$aRow['newratemobile_id'].'/');?>', success: function(result){
						if(result==1){
							$('#ultracard_<?php echo $aRow['id'];?>').show();
							$('#ultracardorder_<?php echo $aRow['id'];?>').show();
						}else{
							$('#ultracard_<?php echo $aRow['id'];?>').hide();
							$('#ultracardorder_<?php echo $aRow['id'];?>').hide();
						}
					}});
				</script>
			<?php } ?>

			<?php
			$buttons = ob_get_contents();
			ob_end_clean();
		}

		$records["data"][] = array(
			$idx++,
			$aRow['simnr'],
			$aRow['mobilenr'],
			$pin,
			$puk,
			$newratemobile,
			$aRow['value2'],
			$aRow['extemtedterm']=='1'?'<i class="fa fa-check"></i>':'<i class="fa fa-close"></i>',
			$aRow['subscriptionlock']=='1'?'<i class="fa fa-check"></i>':'<i class="fa fa-close"></i>',
			$newoptionmobile,
			$value4,
			$aRow['hardware'],
			$aRow['cardstatus']=='1'?'<i class="fa fa-check"></i>':'<i class="fa fa-close"></i>',
			_d($aRow['endofcontract'])
		);
		if (get_user_role()!='customer') {
			$records['data'][count($records['data'])-1][] = $aRow['finished']=='1'?'<i class="fa fa-check"></i>':'<i class="fa fa-close"></i>';
		}
		if (isset($GLOBALS['current_user']->userrole) && $GLOBALS['current_user']->userrole!=6) {
			$records['data'][count($records['data'])-1][] = $buttons;
		}
	}

	//Don't remove
	$records["draw"] = $sEcho;
	$records["recordsTotal"] = $iTotalRecords;
	$records["recordsFiltered"] = $iTotalRecords;

	//echo json_encode($records);
	$output = $records;
?>