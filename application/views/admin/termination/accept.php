<!DOCTYPE html>
<html>
<head>
	<title></title>
	<link href="<?php echo base_url('/assets/global/plugins/bootstrap/css/bootstrap.min.css');?>" rel="stylesheet" type="text/css" />
	<script src="<?php echo base_url('assets/global/plugins/bootstrap/js/bootstrap.min.js')?>" type="text/javascript"></script>
</head>
<body>
	<div class="container">
		<div class="row">
			<div class="col-md-3"></div>
			<div class="col-md-9">
				<img class="img-responsive" src="<?php echo base_url('assets/DKDLogo.png');?>" style="margin-top: 25px;"><br/>
			</div>
		</div>
		<div class="row">
			<div class="col-md-12">
				<p class="text-center" style="font-size: 18px;color: #000000;font-weight: bold;font-family: Arial;"><?php echo lang('page_fl_appointment_confirmation');?></p>
			</div>
			<div class="col-md-12">
				<p class="text-center" style="font-size: 12px;color: #000000;font-family: Arial;margin-bottom: 2px !important;"><?php echo lang('page_fl_app_conf_message_line1');?></p>
				<p class="text-center" style="font-size: 12px;color: #000000;font-family: Arial;margin-bottom: 2px !important;"><?php echo lang('page_fl_app_conf_message_line2');?></p><br/>
			</div>
			<div class="col-md-12">
				<p class="text-center" style="font-size: 18px;color: #000000;font-weight: bold;font-family: Arial;"><?php echo lang('meeting');?></p>
			</div>
			<div class="col-md-12">
				<p class="text-center" style="font-size: 14px;color: #000000;font-family: Arial;margin-bottom: 2px;"><?php echo lang('page_fl_company');?>: <?php echo $terminationData['company_name'];?></p>
				<p class="text-center" style="font-size: 14px;color: #000000;font-family: Arial;margin-bottom: 2px;"><?php echo lang('page_fl_surname');?>: <?php echo $terminationData['surname'].' '.$terminationData['name'];?></p>
				<p class="text-center" style="font-size: 14px;color: #000000;font-family: Arial;margin-bottom: 2px;"><?php echo lang('page_fl_street');?>: <?php echo $terminationData['street'];?></p>
				<p class="text-center" style="font-size: 14px;color: #000000;font-family: Arial;margin-bottom: 2px;"><?php echo lang('page_fl_zipcode');?>/<?php echo lang('page_fl_city');?>: <?php echo $terminationData['zipcode'].','.$terminationData['city'];?></p><br>
				<p class="text-center" style="font-size: 14px;color: #000000;font-family: Arial;margin-bottom: 2px;"><?php echo lang('page_fl_date');?>: <?php echo date('d.m.Y',strtotime($terminationData['date']));?></p>
				<p class="text-center" style="font-size: 14px;color: #000000;font-family: Arial;margin-bottom: 2px;"><?php echo lang('page_fl_time');?>: <?php echo date('H:i:s',strtotime($terminationData['date']));?></p><br/><br/>
			</div>

			<div class="col-md-12">
				<p class="text-center" style="font-size: 12px;color: #000000;font-family: Arial;margin-bottom: 2px;">DK Deutschland GmbH - Industriestr.10 - 59192 Bergkamen</p>
				<p class="text-center" style="font-size: 12px;color: #000000;font-family: Arial;margin-bottom: 2px;">Tel.: +49 (0) 2307 - 71 99 590 - Email: kontakt@dk-deutschland.de</p>
			</div>
		<div>
	</div>
</body>
</html>