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
				<img class="img-responsive" src="<?php echo base_url('assets/DKDLogo.png');?>"><br/>
			</div>
		</div>
		<div class="row">
			<div class="col-md-12">
				<p class="text-center" style="font-size: 18px;color: #000000;font-weight: bold;font-family: Arial;">Sie haben den Termin abgesagt.</p>
			</div>
			<div class="col-md-12">
				<p class="text-center" style="font-size: 12px;color: #000000;font-family: Arial;margin-bottom: 2px !important;">Wir bedauern sehr das Sie den Termin abgesagt haben. Wir hätten Sie gerne unverbindlich beraten</p>
				<p class="text-center" style="font-size: 12px;color: #000000;font-family: Arial;margin-bottom: 2px !important;">und Ihnen dargestellt welches Potential an Leistungssteigern und Ersparnisse möglich wären.</p><br>
				<p class="text-center" style="font-size: 12px;color: #000000;font-family: Arial;margin-bottom: 2px !important;">Vielleicht wollen Sie den Termin eventuell doch wahrnehmen?</p><br>
			</div>
			<div class="col-md-12">
				<p class="text-center"><a href="<?php echo base_url().'cronjobs/terminationAcceptCancel/'.md5($terminationData['id']).'/accept/';?>" class="btn btn-lg- btn-default" style="width: 185px;height: 33px;background-color: #e30613;border: 1px solid #111111;font-size: 18px;color: #ffffff;font-weight: bold;font-family:Arial">Zusagen</a></p>
			</div>
			<div class="col-md-12">
				<p class="text-center" style="font-size: 18px;color: #000000;font-weight: bold;font-family: Arial;">TerminTermin</p>
			</div>
			<div class="col-md-12">
				<p class="text-center" style="font-size: 14px;color: #000000;font-family: Arial;margin-bottom: 2px;">Firma: <?php echo $terminationData['company_name'];?></p>
				<p class="text-center" style="font-size: 14px;color: #000000;font-family: Arial;margin-bottom: 2px;">Ansprechpartner: <?php echo $terminationData['surname'].' '.$terminationData['name'];?></p>
				<p class="text-center" style="font-size: 14px;color: #000000;font-family: Arial;margin-bottom: 2px;">Strasse: <?php echo $terminationData['street'];?></p>
				<p class="text-center" style="font-size: 14px;color: #000000;font-family: Arial;margin-bottom: 2px;">PLZ/Ort: <?php echo $terminationData['zipcode'].','.$terminationData['city'];?>, City</p><br>
				<p class="text-center" style="font-size: 14px;color: #000000;font-family: Arial;margin-bottom: 2px;">Datum: <?php echo date('d.m.Y',strtotime($terminationData['date']));?></p>
				<p class="text-center" style="font-size: 14px;color: #000000;font-family: Arial;margin-bottom: 2px;">Uhrzeit: <?php echo date('H:i:s',strtotime($terminationData['date']));?></p><br/><br/>
			</div>

			<div class="col-md-12">
				<p class="text-center" style="font-size: 12px;color: #000000;font-family: Arial;margin-bottom: 2px;">DK Deutschland GmbH - Industriestr.10 - 59192 Bergkamen</p>
				<p class="text-center" style="font-size: 12px;color: #000000;font-family: Arial;margin-bottom: 2px;">Tel.: +49 (0) 2307 - 71 99 590 - Email: kontakt@dk-deutschland.de</p>
			</div>
		<div>
	</div>
</body>
</html>