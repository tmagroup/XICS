<?php
defined('BASEPATH') or exit('No direct script access allowed');
include_once(APPPATH . 'libraries/tcpdf/tcpdf.php');

//Class Customize Header and Footer
class pdf extends TCPDF
{
	private $pdfname = '';
	private $data = array();
	protected $last_page_flag = false;

	public function __construct($pdfname='', $data=array(),$orientation='P', $unit='mm', $format='A4', $unicode=true, $encoding='UTF-8', $diskcache=false, $pdfa=false)
	{
		parent::__construct($orientation, $unit, $format, $unicode, $encoding, $diskcache, $pdfa);
		$this->pdfname = $pdfname;
		$this->data = $data;

	}

	public function Close() {
		$this->last_page_flag = true;
		parent::Close();
	}

	public function Header()
	{
		switch($this->pdfname){
			case 'printdeliverynote2':

				//Red Header
				$image_file = 'assets/pages/img/header.jpg';
				$this->Image($image_file, 0, 8, 210, '', 'JPG', '', 'T', false, 500, '', false, false, 0, false, false, false);

				//Red Header Text
				$this->SetXY(26.5, 28.5);
				$this->SetTextColor(255, 255, 255);
				if($this->data['shippingslip']['shippingslip_type']==2){
					$this->writeHTML('<b>'.lang('page_hardware_assignment_pdf_deliverynote2').'</b>', true, false, true, false, '');
				}
				else{
					$this->writeHTML('<b>'.lang('page_hardware_assignment_pdf_deliverynote').'</b>', true, false, true, false, '');
				}

				//Company Logo
				$image_file = 'uploads/company/logo-big.png';
				$this->Image($image_file, 150, 8, 60, '', 'PNG', '', 'T', false, 500, '', false, false, 0, false, false, false);

			break;

			case 'printhardwareinvoice':

				//Red Header
				if ($this->data['hardwareassignment']['provider'] == 'Telekom') {
					$image_file = 'assets/pages/img/hardwarequotation-logo-telekom.png';
					$this->Image($image_file, 0, 25, 210, '', 'PNG', '', 'T', false, 500, '', false, false, 0, false, false, false);

				} else if ($this->data['hardwareassignment']['provider'] == 'o2Business') {
					$image_file = 'assets/pages/img/hardwarequotation-logo-o2.png';
					$this->Image($image_file, 0, 25, 210, '', 'PNG', '', 'T', false, 500, '', false, false, 0, false, false, false);

				} else {
					$image_file = 'assets/pages/img/header.jpg';
					$this->Image($image_file, 0, 8, 210, '', 'JPG', '', 'T', false, 500, '', false, false, 0, false, false, false);
				}

				//Red Header Text
				$this->SetXY(26.5, 28.5);
				$this->SetTextColor(255, 255, 255);
				$this->writeHTML('<b>'.lang('page_hardware_assignment_pdf_hardwareinvoice').'</b>', true, false, true, false, '');

				//Company Logo
				if ($this->data['hardwareassignment']['provider'] == 'Telekom') {
					$image_file = 'assets/pages/img/telekom-business-serviceslogo.png';
					$this->Image($image_file, 15, 5, 30, '', 'PNG', '', 'T', false, 500, '', false, false, 0, false, false, false);

					$image_file = 'assets/pages/img/deutschland-logo.png';
					$this->Image($image_file, 140, 8, 60, '', 'PNG', '', 'T', false, 500, '', false, false, 0, false, false, false);

				} else if ($this->data['hardwareassignment']['provider'] == 'o2Business') {
					$image_file = 'assets/pages/img/o2-business-serviceslogo.png';
					$this->Image($image_file, 15, 4, 18, '', 'PNG', '', 'T', false, 500, '', false, false, 0, false, false, false);

					$image_file = 'assets/pages/img/deutschland-logo-blue.png';
					$this->Image($image_file, 140, 8, 60, '', 'PNG', '', 'T', false, 500, '', false, false, 0, false, false, false);

				} else {
					//Company Logo
					$image_file = 'uploads/company/logo-big.png';
					$this->Image($image_file, 150, 8, 60, '', 'PNG', '', 'T', false, 500, '', false, false, 0, false, false, false);
				}

			break;

			case 'printdeliverynote':

				//Red Header
				if ($this->data['hardwareassignment']['provider'] == 'Telekom') {
					$image_file = 'assets/pages/img/hardwarequotation-logo-telekom.png';
					$this->Image($image_file, 0, 25, 210, '', 'PNG', '', 'T', false, 500, '', false, false, 0, false, false, false);

				} else if ($this->data['hardwareassignment']['provider'] == 'o2Business') {
					$image_file = 'assets/pages/img/hardwarequotation-logo-o2.png';
					$this->Image($image_file, 0, 25, 210, '', 'PNG', '', 'T', false, 500, '', false, false, 0, false, false, false);

				} else {
					$image_file = 'assets/pages/img/header.jpg';
					$this->Image($image_file, 0, 8, 210, '', 'JPG', '', 'T', false, 500, '', false, false, 0, false, false, false);
				}

				//Red Header Text
				$this->SetXY(26.5, 28.5);
				$this->SetTextColor(255, 255, 255);
				if($this->data['shippingslip_type']==2){
					$this->writeHTML('<b>'.lang('page_hardware_assignment_pdf_deliverynote2').'</b>', true, false, true, false, '');
				}
				else{
					$this->writeHTML('<b>'.lang('page_hardware_assignment_pdf_deliverynote').'</b>', true, false, true, false, '');
				}

				//Company Logo
				if ($this->data['hardwareassignment']['provider'] == 'Telekom') {
					$image_file = 'assets/pages/img/telekom-business-serviceslogo.png';
					$this->Image($image_file, 15, 5, 30, '', 'PNG', '', 'T', false, 500, '', false, false, 0, false, false, false);

					$image_file = 'assets/pages/img/deutschland-logo.png';
					$this->Image($image_file, 140, 8, 60, '', 'PNG', '', 'T', false, 500, '', false, false, 0, false, false, false);

				} else if ($this->data['hardwareassignment']['provider'] == 'o2Business') {
					$image_file = 'assets/pages/img/o2-business-serviceslogo.png';
					$this->Image($image_file, 15, 4, 18, '', 'PNG', '', 'T', false, 500, '', false, false, 0, false, false, false);

					$image_file = 'assets/pages/img/deutschland-logo-blue.png';
					$this->Image($image_file, 140, 8, 60, '', 'PNG', '', 'T', false, 500, '', false, false, 0, false, false, false);

				} else {
					//Company Logo
					$image_file = 'uploads/company/logo-big.png';
					$this->Image($image_file, 150, 8, 60, '', 'PNG', '', 'T', false, 500, '', false, false, 0, false, false, false);
				}

			break;

			case 'printconsultationprotocol':

				//Red Header
				if ($this->data['quotation']['provider'] == 'Telekom') {
					$image_file = 'assets/pages/img/hardwarequotation-logo-telekom.png';
					$this->Image($image_file, 0, 25, 210, '', 'PNG', '', 'T', false, 500, '', false, false, 0, false, false, false);

				} else if ($this->data['quotation']['provider'] == 'o2Business') {
					$image_file = 'assets/pages/img/hardwarequotation-logo-o2.png';
					$this->Image($image_file, 0, 25, 210, '', 'PNG', '', 'T', false, 500, '', false, false, 0, false, false, false);

				} else {
					$image_file = 'assets/pages/img/header.jpg';
					$this->Image($image_file, 0, 8, 210, '', 'JPG', '', 'T', false, 500, '', false, false, 0, false, false, false);
				}


				// $image_file = 'assets/pages/img/header.jpg';
				// $this->Image($image_file, 0, 8, 210, '', 'JPG', '', 'T', false, 500, '', false, false, 0, false, false, false);

				//Red Header Text
				$this->SetTextColor(255, 255, 255);
				if ($this->data['quotation']['provider'] == 'Telekom' || $this->data['quotation']['provider'] == 'o2Business') {
					$this->SetXY(18, 28.5);

				} else {
					$this->SetXY(26.5, 28.5);
				}
				$this->writeHTML('<b>'.lang('page_consultation_quotation_pdf_consultationprotocol').'</b>', true, false, true, false, '');

				//Company Logo
				$image_logo_file = 'uploads/company/logo-big.png';
				if ($this->data['quotation']['provider'] == 'Telekom') {
					$image_file = 'assets/pages/img/telekom-business-serviceslogo.png';
					$this->Image($image_file, 15, 5, 30, '', 'PNG', '', 'T', false, 500, '', false, false, 0, false, false, false);

					$image_logo_file = 'assets/pages/img/deutschland-logo.png';

				} else if ($this->data['quotation']['provider'] == 'o2Business') {
					$image_file = 'assets/pages/img/o2-business-serviceslogo.png';
					$this->Image($image_file, 15, 4, 18, '', 'PNG', '', 'T', false, 500, '', false, false, 0, false, false, false);

					$image_logo_file = 'assets/pages/img/deutschland-logo-blue.png';
				}
				$this->Image($image_logo_file, 140, 8, 60, '', 'PNG', '', 'T', false, 500, '', false, false, 0, false, false, false);


				// $image_file = 'uploads/company/logo-big.png';
				// $this->Image($image_file, 150, 8, 60, '', 'PNG', '', 'T', false, 500, '', false, false, 0, false, false, false);

			break;

			case 'printconsultationprotocollead':

				//Red Header
				if ($this->data['quotation']['provider'] == 'Telekom') {
					$image_file = 'assets/pages/img/hardwarequotation-logo-telekom.png';
					$this->Image($image_file, 0, 25, 210, '', 'PNG', '', 'T', false, 500, '', false, false, 0, false, false, false);

				} else if ($this->data['quotation']['provider'] == 'o2Business') {
					$image_file = 'assets/pages/img/hardwarequotation-logo-o2.png';
					$this->Image($image_file, 0, 25, 210, '', 'PNG', '', 'T', false, 500, '', false, false, 0, false, false, false);

				} else {
					$image_file = 'assets/pages/img/header.jpg';
					$this->Image($image_file, 0, 8, 210, '', 'JPG', '', 'T', false, 500, '', false, false, 0, false, false, false);
				}


				// $image_file = 'assets/pages/img/header.jpg';
				// $this->Image($image_file, 0, 8, 210, '', 'JPG', '', 'T', false, 500, '', false, false, 0, false, false, false);

				//Red Header Text
				$this->SetTextColor(255, 255, 255);
				if ($this->data['quotation']['provider'] == 'Telekom' || $this->data['quotation']['provider'] == 'o2Business') {
					$this->SetXY(18, 28.5);

				} else {
					$this->SetXY(26.5, 28.5);
				}
				$this->writeHTML('<b>'.lang('page_consultation_quotation_pdf_consultationprotocol').'</b>', true, false, true, false, '');

				//Company Logo
				if ($this->data['quotation']['provider'] == 'Telekom') {
					$image_file = 'assets/pages/img/telekom-business-serviceslogo.png';
					$this->Image($image_file, 15, 5, 30, '', 'PNG', '', 'T', false, 500, '', false, false, 0, false, false, false);

					$image_file = 'assets/pages/img/deutschland-logo.png';
					$this->Image($image_file, 140, 8, 60, '', 'PNG', '', 'T', false, 500, '', false, false, 0, false, false, false);

				} else if ($this->data['quotation']['provider'] == 'o2Business') {
					$image_file = 'assets/pages/img/o2-business-serviceslogo.png';
					$this->Image($image_file, 15, 4, 18, '', 'PNG', '', 'T', false, 500, '', false, false, 0, false, false, false);

					$image_file = 'assets/pages/img/deutschland-logo-blue.png';
					$this->Image($image_file, 140, 8, 60, '', 'PNG', '', 'T', false, 500, '', false, false, 0, false, false, false);

				} else {
					//Company Logo
					$image_file = 'uploads/company/logo-big.png';
					$this->Image($image_file, 150, 8, 60, '', 'PNG', '', 'T', false, 500, '', false, false, 0, false, false, false);
				}


				// $image_file = 'uploads/company/logo-big.png';
				// $this->Image($image_file, 150, 8, 60, '', 'PNG', '', 'T', false, 500, '', false, false, 0, false, false, false);

			break;

			case 'printmonitoringinvoice':

				//Red Header
				if ($this->data['provider'] == 'Telekom') {
					$image_file = 'assets/pages/img/hardwarequotation-logo-telekom.png';
					$this->Image($image_file, 0, 25, 210, '', 'PNG', '', 'T', false, 500, '', false, false, 0, false, false, false);

				} else if ($this->data['provider'] == 'o2Business') {
					$image_file = 'assets/pages/img/hardwarequotation-logo-o2.png';
					$this->Image($image_file, 0, 25, 210, '', 'PNG', '', 'T', false, 500, '', false, false, 0, false, false, false);

				} else {
					$image_file = 'assets/pages/img/header.jpg';
					$this->Image($image_file, 0, 8, 210, '', 'JPG', '', 'T', false, 500, '', false, false, 0, false, false, false);
				}


				// $image_file = 'assets/pages/img/header.jpg';
				// $this->Image($image_file, 0, 8, 210, '', 'JPG', '', 'T', false, 500, '', false, false, 0, false, false, false);

				//Red Header Text
				$this->SetTextColor(255, 255, 255);
				if ($this->data['provider'] == 'Telekom' || $this->data['provider'] == 'o2Business') {
					$this->SetXY(18, 28.5);

				} else {
					$this->SetXY(26.5, 28.5);
				}
				$this->writeHTML('<b>'.lang('page_monitorinng_consulting_title').'</b>', true, false, true, false, '');

				//Company Logo
				if ($this->data['provider'] == 'Telekom') {
					$image_file = 'assets/pages/img/telekom-business-serviceslogo.png';
					$this->Image($image_file, 15, 5, 30, '', 'PNG', '', 'T', false, 500, '', false, false, 0, false, false, false);

					$image_file = 'assets/pages/img/deutschland-logo.png';
					$this->Image($image_file, 140, 8, 60, '', 'PNG', '', 'T', false, 500, '', false, false, 0, false, false, false);

				} else if ($this->data['provider'] == 'o2Business') {
					$image_file = 'assets/pages/img/o2-business-serviceslogo.png';
					$this->Image($image_file, 15, 4, 18, '', 'PNG', '', 'T', false, 500, '', false, false, 0, false, false, false);

					$image_file = 'assets/pages/img/deutschland-logo-blue.png';
					$this->Image($image_file, 140, 8, 60, '', 'PNG', '', 'T', false, 500, '', false, false, 0, false, false, false);

				} else {
					//Company Logo
					$image_file = 'uploads/company/logo-big.png';
					$this->Image($image_file, 150, 8, 60, '', 'PNG', '', 'T', false, 500, '', false, false, 0, false, false, false);
				}


				// $image_file = 'uploads/company/logo-big.png';
				// $this->Image($image_file, 150, 8, 60, '', 'PNG', '', 'T', false, 500, '', false, false, 0, false, false, false);

			break;
			case 'printhardwarequotation':

				//Red Header
				if ($this->data['quotation']['provider'] == 'Telekom') {
					$image_file = 'assets/pages/img/hardwarequotation-logo-telekom.png';
					$this->Image($image_file, 0, 25, 210, '', 'PNG', '', 'T', false, 500, '', false, false, 0, false, false, false);

				} else if ($this->data['quotation']['provider'] == 'o2Business') {
					$image_file = 'assets/pages/img/hardwarequotation-logo-o2.png';
					$this->Image($image_file, 0, 25, 210, '', 'PNG', '', 'T', false, 500, '', false, false, 0, false, false, false);

				} else {
					$image_file = 'assets/pages/img/header.jpg';
					$this->Image($image_file, 0, 8, 210, '', 'JPG', '', 'T', false, 500, '', false, false, 0, false, false, false);
				}

				//Red Header Text
				$this->SetXY(26.5, 28.5);
				$this->SetTextColor(255, 255, 255);
				$this->writeHTML('<b>'.lang('page_hardware_quotation_pdf_hardwareoffer').'</b>', true, false, true, false, '');

				if ($this->data['quotation']['provider'] == 'Telekom') {
					$image_file = 'assets/pages/img/telekom-business-serviceslogo.png';
					$this->Image($image_file, 15, 5, 30, '', 'PNG', '', 'T', false, 500, '', false, false, 0, false, false, false);

					$image_file = 'assets/pages/img/deutschland-logo.png';
					$this->Image($image_file, 140, 8, 60, '', 'PNG', '', 'T', false, 500, '', false, false, 0, false, false, false);

				} else if ($this->data['quotation']['provider'] == 'o2Business') {
					$image_file = 'assets/pages/img/o2-business-serviceslogo.png';
					$this->Image($image_file, 15, 4, 18, '', 'PNG', '', 'T', false, 500, '', false, false, 0, false, false, false);

					$image_file = 'assets/pages/img/deutschland-logo-blue.png';
					$this->Image($image_file, 140, 8, 60, '', 'PNG', '', 'T', false, 500, '', false, false, 0, false, false, false);

				} else {
					//Company Logo
					$image_file = 'uploads/company/logo-big.png';
					$this->Image($image_file, 150, 8, 60, '', 'PNG', '', 'T', false, 500, '', false, false, 0, false, false, false);
				}

			break;

			case 'printhardwareleadquotation':

				//Red Header
				if ($this->data['quotation']['provider'] == 'Telekom') {
					$image_file = 'assets/pages/img/hardwarequotation-logo-telekom.png';
					$this->Image($image_file, 0, 25, 210, '', 'PNG', '', 'T', false, 500, '', false, false, 0, false, false, false);

				} else if ($this->data['quotation']['provider'] == 'o2Business') {
					$image_file = 'assets/pages/img/hardwarequotation-logo-o2.png';
					$this->Image($image_file, 0, 25, 210, '', 'PNG', '', 'T', false, 500, '', false, false, 0, false, false, false);

				} else {
					$image_file = 'assets/pages/img/header.jpg';
					$this->Image($image_file, 0, 8, 210, '', 'JPG', '', 'T', false, 500, '', false, false, 0, false, false, false);
				}

				//Red Header Text
				$this->SetXY(26.5, 28.5);
				$this->SetTextColor(255, 255, 255);
				$this->writeHTML('<b>'.lang('page_hardware_quotation_pdf_hardwareoffer').'</b>', true, false, true, false, '');

				if ($this->data['quotation']['provider'] == 'Telekom') {
					$image_file = 'assets/pages/img/telekom-business-serviceslogo.png';
					$this->Image($image_file, 15, 5, 30, '', 'PNG', '', 'T', false, 500, '', false, false, 0, false, false, false);

					$image_file = 'assets/pages/img/deutschland-logo.png';
					$this->Image($image_file, 140, 8, 60, '', 'PNG', '', 'T', false, 500, '', false, false, 0, false, false, false);

				} else if ($this->data['quotation']['provider'] == 'o2Business') {
					$image_file = 'assets/pages/img/o2-business-serviceslogo.png';
					$this->Image($image_file, 15, 4, 18, '', 'PNG', '', 'T', false, 500, '', false, false, 0, false, false, false);

					$image_file = 'assets/pages/img/deutschland-logo-blue.png';
					$this->Image($image_file, 140, 8, 60, '', 'PNG', '', 'T', false, 500, '', false, false, 0, false, false, false);

				} else {
					//Company Logo
					$image_file = 'uploads/company/logo-big.png';
					$this->Image($image_file, 150, 8, 60, '', 'PNG', '', 'T', false, 500, '', false, false, 0, false, false, false);
				}

			break;


			case 'printmonitoringprotocol':

				//Red Header
				$image_file = 'assets/pages/img/header.jpg';
				$this->Image($image_file, 0, 8, 210, '', 'JPG', '', 'T', false, 500, '', false, false, 0, false, false, false);

				//Red Header Text
				$this->SetXY(26.5, 28.5);
				$this->SetTextColor(255, 255, 255);
				$this->writeHTML('<b>'.lang('page_monitoring_pdf_protocol').'</b>', true, false, true, false, '');

				//Company Logo
				$image_file = 'uploads/company/logo-big.png';
				$this->Image($image_file, 150, 8, 60, '', 'PNG', '', 'T', false, 500, '', false, false, 0, false, false, false);

			break;

			case 'printquotation':

				//Current Provider and Simcard function
				$simcard_function_datacards = 0;
				$simcard_function_voicecards = 0;
				foreach($this->data['quotationproducts'] as $quotationproduct){
					if($quotationproduct['simcard_function_id']==1){
						$simcard_function_datacards = $simcard_function_datacards + $quotationproduct['simcard_function_qty'];
					}
					if($quotationproduct['simcard_function_id']==2){
						$simcard_function_voicecards = $simcard_function_voicecards + $quotationproduct['simcard_function_qty'];
					}
				}
				$this->SetY(28);
				$bgcolor = 'background-color:#e60000;';
				if ($this->data['quotation']['provider'] == 'Telekom') {
					$bgcolor = 'background-color:#808080;';

				} else if ($this->data['quotation']['provider'] == 'o2Business') {
					$bgcolor = 'background-color:#404040;';
				}

				$html = '<table border="0" width="25%" cellspacing="0" cellpadding="1" style="color:#fff; font-size:6px; font-weight:bold; '.$bgcolor.'">
				<tr><td>'.lang('page_lb_currentprovider').'</td><td align="center">'.QUOTATION_PROVIDER.'</td></tr>
				<tr><td>'.lang('page_fl_fqty2').'</td><td align="center">'.$simcard_function_voicecards.'</td></tr>
				<tr><td>'.lang('page_fl_fqty1').'</td><td align="center">'.$simcard_function_datacards.'</td></tr>
				<tr><td></td><td align="center"></td></tr>
				</table>';
				$this->writeHTML($html, true, false, true, false, '');

				if ($this->data['quotation']['provider'] == 'Telekom') {
					$image_file = 'assets/pages/img/telekom-business-serviceslogo.png';
					$this->Image($image_file, 87, 22, 40, '', 'PNG', '', 'T', false, 500, '', false, false, 0, false, false, false);
					$this->SetXY(126, 28);

				} else if ($this->data['quotation']['provider'] == 'o2Business') {
					$image_file = 'assets/pages/img/o2-business-serviceslogo.png';
					$this->Image($image_file, 90, 25, 20, '', 'PNG', '', 'T', false, 500, '', false, false, 0, false, false, false);
					$this->SetXY(112, 28);

				} else {
					$image_file = 'assets/pages/img/vodafone-business-serviceslogo2.png';
					$this->Image($image_file, 87, 28, 29, '', 'PNG', '', 'T', false, 500, '', false, false, 0, false, false, false);
					$this->SetXY(100, 28);
				}

				//Discount Level-1,2
				// $this->SetXY(100, 31.5);
				$html = '<table border="0" width="60%" cellspacing="0" cellpadding="1" style="color:#fff; font-size:6px; font-weight:bold; '.$bgcolor.' ">
				<tr><td width="30%"></td><td width="30%">'.lang('page_fl_currentdiscountlevel').'</td><td width="27%">'.$this->data['quotation']['currentdiscountlevel'].'</td></tr>
				<tr><td></td><td>'.lang('page_fl_newdiscountlevel').'</td><td>'.$this->data['quotation']['newdiscountlevel'].'</td></tr>
				<tr><td></td><td align="center"></td></tr>
				<tr><td></td><td></td><td align="center"></td></tr>
				</table>';
				$this->writeHTML($html, true, false, true, false, '');

				$image_file = 'assets/pages/img/header-table-corner.png';
				if ($this->data['quotation']['provider'] == 'Telekom') {
					$image_file = 'assets/pages/img/header-table-corner-telekom.png';

				} else if ($this->data['quotation']['provider'] == 'o2Business') {
					$image_file = 'assets/pages/img/header-table-corner-o2.png';
				}
				$this->Image($image_file, 202.9, 28, 75, '', 'PNG', '', 'T', false, 500, '', false, false, 0, false, false, false);

				$this->SetXY(203, 28);
				$html = '<table border="0" width="70%" cellspacing="0" cellpadding="5" style="font-size:6px; font-weight:bold;"><tr><td>
					<table border="0" width="100%" cellspacing="0" cellpadding="1">
						<tr><th width="30%">'.lang('page_fl_company').':</th><td>'.$this->data['quotation']['customer_company'].'</td></tr>
						<tr><th>'.lang('page_fl_street').':</th><td>'.$this->data['quotation']['customer_street'].'</td></tr>
						<tr><th>'.lang('page_fl_zipcode').'/'.lang('page_fl_city').':</th><td>'.$this->data['quotation']['customer_zipcode'].' '.$this->data['quotation']['customer_city'].'</td></tr>
						<tr><th></th><td></td></tr>
					</table>
				</td></tr></table>';
				$this->writeHTML($html, true, false, true, false, '');

			break;

			case 'printleadquotation':

				//Current Provider and Simcard function
				$simcard_function_datacards = 0;
				$simcard_function_voicecards = 0;
				foreach($this->data['quotationproducts'] as $quotationproduct){
					if($quotationproduct['simcard_function_id']==1){
						$simcard_function_datacards = $simcard_function_datacards + $quotationproduct['simcard_function_qty'];
					}
					if($quotationproduct['simcard_function_id']==2){
						$simcard_function_voicecards = $simcard_function_voicecards + $quotationproduct['simcard_function_qty'];
					}
				}
				$this->SetY(28);
				$bgcolor = 'background-color:#e60000;';
				if ($this->data['quotation']['provider'] == 'Telekom') {
					$bgcolor = 'background-color:#808080;';

				} else if ($this->data['quotation']['provider'] == 'o2Business') {
					$bgcolor = 'background-color:#404040;';
				}

				$html = '<table border="0" width="25%" cellspacing="0" cellpadding="1" style="color:#fff; font-size:6px; font-weight:bold; '.$bgcolor.'">
				<tr><td>'.lang('page_lb_currentprovider').'</td><td align="center">'.QUOTATION_PROVIDER.'</td></tr>
				<tr><td>'.lang('page_fl_fqty2').'</td><td align="center">'.$simcard_function_voicecards.'</td></tr>
				<tr><td>'.lang('page_fl_fqty1').'</td><td align="center">'.$simcard_function_datacards.'</td></tr>
				<tr><td></td><td align="center"></td></tr>
				</table>';
				$this->writeHTML($html, true, false, true, false, '');

				if ($this->data['quotation']['provider'] == 'Telekom') {
					$image_file = 'assets/pages/img/telekom-business-serviceslogo.png';
					$this->Image($image_file, 87, 22, 40, '', 'PNG', '', 'T', false, 500, '', false, false, 0, false, false, false);
					$this->SetXY(126, 28);

				} else if ($this->data['quotation']['provider'] == 'o2Business') {
					$image_file = 'assets/pages/img/o2-business-serviceslogo.png';
					$this->Image($image_file, 90, 25, 20, '', 'PNG', '', 'T', false, 500, '', false, false, 0, false, false, false);
					$this->SetXY(112, 28);

				} else {
					$image_file = 'assets/pages/img/vodafone-business-serviceslogo2.png';
					$this->Image($image_file, 87, 28, 29, '', 'PNG', '', 'T', false, 500, '', false, false, 0, false, false, false);
					$this->SetXY(100, 28);
				}

				//Discount Level-1,2
				// $this->SetXY(100, 31.5);
				$html = '<table border="0" width="60%" cellspacing="0" cellpadding="1" style="color:#fff; font-size:6px; font-weight:bold; '.$bgcolor.' ">
				<tr><td width="30%"></td><td width="30%">'.lang('page_fl_currentdiscountlevel').'</td><td width="27%">'.$this->data['quotation']['currentdiscountlevel'].'</td></tr>
				<tr><td></td><td>'.lang('page_fl_newdiscountlevel').'</td><td>'.$this->data['quotation']['newdiscountlevel'].'</td></tr>
				<tr><td></td><td align="center"></td></tr>
				<tr><td></td><td></td><td align="center"></td></tr>
				</table>';
				$this->writeHTML($html, true, false, true, false, '');

				$image_file = 'assets/pages/img/header-table-corner.png';
				if ($this->data['quotation']['provider'] == 'Telekom') {
					$image_file = 'assets/pages/img/header-table-corner-telekom.png';

				} else if ($this->data['quotation']['provider'] == 'o2Business') {
					$image_file = 'assets/pages/img/header-table-corner-o2.png';
				}
				$this->Image($image_file, 202.9, 28, 75, '', 'PNG', '', 'T', false, 500, '', false, false, 0, false, false, false);

				$this->SetXY(203, 28);
				$html = '<table border="0" width="70%" cellspacing="0" cellpadding="5" style="font-size:6px; font-weight:bold;"><tr><td>
					<table border="0" width="100%" cellspacing="0" cellpadding="1">
						<tr><th width="30%">'.lang('page_fl_company').':</th><td>'.$this->data['quotation']['responsible'].'</td></tr>
						<tr><th>'.lang('page_fl_street').':</th><td>'.$this->data['quotation']['responsible_street'].'</td></tr>
						<tr><th>'.lang('page_fl_zipcode').'/'.lang('page_fl_city').':</th><td>'.$this->data['quotation']['responsible_zipcode'].' '.$this->data['quotation']['responsible_city'].'</td></tr>
						<tr><th></th><td></td></tr>
					</table>
				</td></tr></table>';
				$this->writeHTML($html, true, false, true, false, '');

			break;

			case 'employeecommissionslip':

				//Red Header
				$image_file = 'assets/pages/img/header.jpg';
				$this->Image($image_file, 0, 8, 210, '', 'JPG', '', 'T', false, 500, '', false, false, 0, false, false, false);

				//Red Header Text
				$this->SetXY(26.5, 28.5);
				$this->SetTextColor(255, 255, 255);
				$this->writeHTML('<b>'.lang('page_lb_commission_settlement').'</b>', true, false, true, false, '');

				//Company Logo
				$image_file = 'uploads/company/logo-big.png';
				$this->Image($image_file, 150, 8, 60, '', 'PNG', '', 'T', false, 500, '', false, false, 0, false, false, false);

			break;
		}
	}

	public function Footer()
	{
		switch($this->pdfname){
			case 'printdeliverynote2':

				//Footer Text-1
				$this->SetXY(22,230);

								//<tr><td style="font-size:12px; font-weight:normal;">'.lang('page_hardware_assignment_pdf_footerline1').'</td></tr>

				$html = '<table border="0" width="100%" cellspacing="0" cellpadding="2" style="color:#000000; font-weight:normal;">
				<tr><td style="font-size:8px; font-weight:normal; color:#6a6a6a;">'.lang('page_hardware_assignment_pdf_footerline2').'</td></tr>
				</table>';
				$this->writeHTML($html, true, false, true, false, '');

				//Red Footer Line
				$linestyle = array('width' => 6, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(228, 34, 37));
				$this->Line(20, 245, 210, 245, $linestyle);

				//Footer Text-2
				$this->SetXY(0,268.5);
				$html = '<table border="0" width="100%" cellspacing="0" cellpadding="0" style="color:#6a6a6a; font-size:8px; font-weight:normal;">
				<tr><td></td></tr><tr><td align="center">'.$this->data['company_business_partner_name'].' - '.$this->data['company_name'].' - '.$this->data['company_address'].
				' - '.$this->data['company_zipcode'].' '.$this->data['company_city'].'</td></tr>
				<tr><td align="center">'.$this->data['company_address2'].'</td></tr>
				<tr><td align="center">'.$this->data['company_address3'].'</td></tr>
				<tr><td align="center">'.lang('page_lb_tel').'.: '.$this->data['company_tel'].' '.lang('page_lb_fax').': '.$this->data['company_fax'].' - '.$this->data['company_website'].' - '.$this->data['company_email'].'</td></tr>
				</table>';
				$this->writeHTML($html, true, false, true, false, '');

			break;

			case 'printhardwareinvoice':

				//Footer Text-1
				$this->SetXY(22,230);
				$html = '<table border="0" width="100%" cellspacing="0" cellpadding="2" style="color:#000000; font-weight:normal;">
				<tr><td style="font-size:8px; font-weight:normal; color:#6a6a6a;">'.lang('page_hardware_assignment_pdf_footerline3').'</td></tr>
				</table>';
				$this->writeHTML($html, true, false, true, false, '');

				//Red Footer Line
				if ($this->data['hardwareassignment']['provider'] == 'Telekom') {
					$linestyle = array('width' => 6, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(95, 105, 106));

				} else if ($this->data['hardwareassignment']['provider'] == 'o2Business') {
					$linestyle = array('width' => 6, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(20, 48, 102));

				} else {
					$linestyle = array('width' => 6, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(228, 34, 37));
				}
				$this->Line(20, 245, 210, 245, $linestyle);

				//Footer Total Hardware
				$this->SetY(250);
				$html = '<table border="0" width="100%" cellspacing="0" cellpadding="0" style="color:#2b2b2a"><tr><td align="right"><table border="0"><tr style="font-size:10px;"><td align="right" width="72%"><b>'.lang('page_hardware_quotation_pdf_total').':</b></td><td width="3%">&nbsp;</td><td align="left" width="25%"><b>'.format_money($this->data['invoice']['hardware_total'],"&nbsp;".$this->data['currency_name']).'</b></td></tr><tr style="font-size:8px;"><td align="right" width="72%"><b>'.$this->data['invoice']['company_vat'].'% '.lang('page_hardware_quotation_pdf_vat').':</b></td><td width="3%">&nbsp;</td><td align="left" width="25%"><b>'.format_money($this->data['invoice']['company_vat_total'],"&nbsp;".$this->data['currency_name']).'</b></td></tr><tr><td align="right" width="72%"><b>'.lang('page_lb_totalpayment').':</b></td><td width="3%">&nbsp;</td><td align="left" width="25%"><b>'.format_money($this->data['invoice']['grand_total'],"&nbsp;".$this->data['currency_name']).'</b></td></tr></table></td></tr></table>';
				$this->writeHTML($html, true, false, true, false, '');

				$company_business_partner = $this->data['company_business_partner_name'].' - '.$this->data['company_name'];
				if ($this->data['hardwareassignment']['provider'] == 'Telekom') {
					$company_business_partner = $this->data['company_name'].' - '.$this->data['company_business_partner_name_telekom'];

				} else if ($this->data['hardwareassignment']['provider'] == 'o2Business') {
					$company_business_partner = $this->data['company_name'].' - '.$this->data['company_business_partner_name_o2'];
				}

				//Footer Text-2
				$this->SetX(0);
				$html = '<table border="0" width="100%" cellspacing="0" cellpadding="0" style="color:#6a6a6a; font-size:8px; font-weight:normal;">
				<tr><td></td></tr><tr><td align="center">'.$company_business_partner.' - '.$this->data['company_address'].
				' - '.$this->data['company_zipcode'].' '.$this->data['company_city'].'</td></tr>
				<tr><td align="center">'.$this->data['company_address2'].'</td></tr>
				<tr><td align="center">'.$this->data['company_address3'].'</td></tr>
				<tr><td align="center">'.lang('page_lb_tel').'.: '.$this->data['company_tel'].' '.lang('page_lb_fax').': '.$this->data['company_fax'].' - '.$this->data['company_website'].' - '.$this->data['company_email'].'</td></tr>
				</table>';
				$this->writeHTML($html, true, false, true, false, '');

			break;

			case 'printdeliverynote':

				//Footer Text-1
				$this->SetXY(22,225);
				$html = '<table border="0" width="100%" cellspacing="0" cellpadding="2" style="color:#000000; font-weight:normal;">
				<tr><td style="font-size:12px; font-weight:normal;">'.lang('page_hardware_assignment_pdf_footerline1').'</td></tr>
				<tr><td style="font-size:8px; font-weight:normal; color:#6a6a6a;">'.lang('page_hardware_assignment_pdf_footerline2').'</td></tr>
				</table>';
				$this->writeHTML($html, true, false, true, false, '');

				//Red Footer Line
				if ($this->data['hardwareassignment']['provider'] == 'Telekom') {
					$linestyle = array('width' => 6, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(95, 105, 106));

				} else if ($this->data['hardwareassignment']['provider'] == 'o2Business') {
					$linestyle = array('width' => 6, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(20, 48, 102));

				} else {
					$linestyle = array('width' => 6, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(228, 34, 37));
				}
				$this->Line(20, 245, 210, 245, $linestyle);

				//Footer Text-2
				$company_business_partner = $this->data['company_business_partner_name'].' - '.$this->data['company_name'];
				if ($this->data['hardwareassignment']['provider'] == 'Telekom') {
					$company_business_partner = $this->data['company_name'].' - '.$this->data['company_business_partner_name_telekom'];

				} else if ($this->data['hardwareassignment']['provider'] == 'o2Business') {
					$company_business_partner = $this->data['company_name'].' - '.$this->data['company_business_partner_name_o2'];
				}

				$this->SetXY(0,268.5);
				$html = '<table border="0" width="100%" cellspacing="0" cellpadding="0" style="color:#6a6a6a; font-size:8px; font-weight:normal;">
				<tr><td></td></tr><tr><td align="center">'.$company_business_partner.' - '.$this->data['company_address'].
				' - '.$this->data['company_zipcode'].' '.$this->data['company_city'].'</td></tr>
				<tr><td align="center">'.$this->data['company_address2'].'</td></tr>
				<tr><td align="center">'.$this->data['company_address3'].'</td></tr>
				<tr><td align="center">'.lang('page_lb_tel').'.: '.$this->data['company_tel'].' '.lang('page_lb_fax').': '.$this->data['company_fax'].' - '.$this->data['company_website'].' - '.$this->data['company_email'].'</td></tr>
				</table>';
				$this->writeHTML($html, true, false, true, false, '');

			break;


			case 'printconsultationprotocol':

				//Footer Text
				$company_business_partner = $this->data['company_business_partner_name'].' - '.$this->data['company_name'];
				if ($this->data['quotation']['provider'] == 'Telekom') {
					$company_business_partner = $this->data['company_name'].' - '.$this->data['company_business_partner_name_telekom'];

				} else if ($this->data['quotation']['provider'] == 'o2Business') {
					$company_business_partner = $this->data['company_name'].' - '.$this->data['company_business_partner_name_o2'];
				}

				$this->SetXY(0,268.5);
				$html = '<table border="0" width="100%" cellspacing="0" cellpadding="0" style="color:#6a6a6a; font-size:8px; font-weight:normal;">
				<tr><td></td></tr><tr><td align="center">'.$company_business_partner.' - '.$this->data['company_address'].
				' - '.$this->data['company_zipcode'].' '.$this->data['company_city'].'</td></tr>
				<tr><td align="center">'.$this->data['company_address2'].'</td></tr>
				<tr><td align="center">'.$this->data['company_address3'].'</td></tr>
				<tr><td align="center">'.lang('page_lb_tel').'.: '.$this->data['company_tel'].' '.lang('page_lb_fax').': '.$this->data['company_fax'].' - '.$this->data['company_website'].' - '.$this->data['company_email'].'</td></tr>
				</table>';
				$this->writeHTML($html, true, false, true, false, '');

			break;

			case 'printconsultationprotocollead':

				//Footer Text
				$company_business_partner = $this->data['company_business_partner_name'].' - '.$this->data['company_name'];
				if ($this->data['quotation']['provider'] == 'Telekom') {
					$company_business_partner = $this->data['company_name'].' - '.$this->data['company_business_partner_name_telekom'];

				} else if ($this->data['quotation']['provider'] == 'o2Business') {
					$company_business_partner = $this->data['company_name'].' - '.$this->data['company_business_partner_name_o2'];
				}

				$this->SetXY(0,268.5);
				$html = '<table border="0" width="100%" cellspacing="0" cellpadding="0" style="color:#6a6a6a; font-size:8px; font-weight:normal;">
				<tr><td></td></tr><tr><td align="center">'.$company_business_partner.' - '.$this->data['company_address'].
				' - '.$this->data['company_zipcode'].' '.$this->data['company_city'].'</td></tr>
				<tr><td align="center">'.$this->data['company_address2'].'</td></tr>
				<tr><td align="center">'.$this->data['company_address3'].'</td></tr>
				<tr><td align="center">'.lang('page_lb_tel').'.: '.$this->data['company_tel'].' '.lang('page_lb_fax').': '.$this->data['company_fax'].' - '.$this->data['company_website'].' - '.$this->data['company_email'].'</td></tr>
				</table>';
				$this->writeHTML($html, true, false, true, false, '');

			break;

			case 'printhardwarequotation':

				//VAT
				$hardware_calculate_total = $this->data['hardware_calculate_total'];
				$vat = round((($hardware_calculate_total * COMPANY_VAT)/100),2);
				$grand_total = round(($hardware_calculate_total + $vat),2);

				//Red Footer Line
				if ($this->data['quotation']['provider'] == 'Telekom') {
					$linestyle = array('width' => 6, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(95, 105, 106));

				} else if ($this->data['quotation']['provider'] == 'o2Business') {
					$linestyle = array('width' => 6, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(20, 48, 102));

				} else {
					$linestyle = array('width' => 6, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(228, 34, 37));
				}
				$this->Line(20, 245, 210, 245, $linestyle);

				//Footer Total Hardware
				$this->SetY(250);
				$html = '<table border="0" width="100%" cellspacing="0" cellpadding="0" style="color:#2b2b2a"><tr><td align="right"><table border="0"><tr style="font-size:10px;"><td align="right" width="72%"><b>'.lang('page_hardware_quotation_pdf_total').':</b></td><td width="3%">&nbsp;</td><td align="left" width="25%"><b>'.format_money($hardware_calculate_total,"&nbsp;".$this->data['currency_name']).'</b></td></tr><tr style="font-size:8px;"><td align="right" width="72%"><b>'.COMPANY_VAT.'% '.lang('page_hardware_quotation_pdf_vat').':</b></td><td width="3%">&nbsp;</td><td align="left" width="25%"><b>'.format_money($vat,"&nbsp;".$this->data['currency_name']).'</b></td></tr><tr><td align="right" width="72%"><b>'.lang('page_lb_totalpayment').':</b></td><td width="3%">&nbsp;</td><td align="left" width="25%"><b>'.format_money($grand_total,"&nbsp;".$this->data['currency_name']).'</b></td></tr></table></td></tr></table>';
				$this->writeHTML($html, true, false, true, false, '');

				$company_business_partner = $this->data['company_business_partner_name'].' - '.$this->data['company_name'];
				if ($this->data['quotation']['provider'] == 'Telekom') {
				    $company_business_partner = $this->data['company_name'].' - '.$this->data['company_business_partner_name_telekom'];

				} else if ($this->data['quotation']['provider'] == 'o2Business') {
				    $company_business_partner = $this->data['company_name'].' - '.$this->data['company_business_partner_name_o2'];
				}

				//Footer Text
				$this->SetX(0);
				$html = '<table border="0" width="100%" cellspacing="0" cellpadding="0" style="color:#6a6a6a; font-size:8px; font-weight:normal;">
				<tr><td></td></tr><tr><td align="center">'.$company_business_partner.' - '.$this->data['company_address'].
				' - '.$this->data['company_zipcode'].' '.$this->data['company_city'].'</td></tr>
				<tr><td align="center">'.$this->data['company_address2'].'</td></tr>
				<tr><td align="center">'.$this->data['company_address3'].'</td></tr>
				<tr><td align="center">'.lang('page_lb_tel').'.: '.$this->data['company_tel'].' '.lang('page_lb_fax').': '.$this->data['company_fax'].' - '.$this->data['company_website'].' - '.$this->data['company_email'].'</td></tr>
				</table>';
				$this->writeHTML($html, true, false, true, false, '');

			break;

			case 'printhardwareleadquotation':

				//VAT
				$hardware_calculate_total = $this->data['hardware_calculate_total'];
				$vat = round((($hardware_calculate_total * COMPANY_VAT)/100),2);
				$grand_total = round(($hardware_calculate_total + $vat),2);

				//Red Footer Line
				if ($this->data['quotation']['provider'] == 'Telekom') {
					$linestyle = array('width' => 6, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(95, 105, 106));

				} else if ($this->data['quotation']['provider'] == 'o2Business') {
					$linestyle = array('width' => 6, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(20, 48, 102));

				} else {
					$linestyle = array('width' => 6, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(228, 34, 37));
				}
				$this->Line(20, 245, 210, 245, $linestyle);

				//Footer Total Hardware
				$this->SetY(250);
				$html = '<table border="0" width="100%" cellspacing="0" cellpadding="0" style="color:#2b2b2a"><tr><td align="right"><table border="0"><tr style="font-size:10px;"><td align="right" width="72%"><b>'.lang('page_hardware_quotation_pdf_total').':</b></td><td width="3%">&nbsp;</td><td align="left" width="25%"><b>'.format_money($hardware_calculate_total,"&nbsp;".$this->data['currency_name']).'</b></td></tr><tr style="font-size:8px;"><td align="right" width="72%"><b>'.COMPANY_VAT.'% '.lang('page_hardware_quotation_pdf_vat').':</b></td><td width="3%">&nbsp;</td><td align="left" width="25%"><b>'.format_money($vat,"&nbsp;".$this->data['currency_name']).'</b></td></tr><tr><td align="right" width="72%"><b>'.lang('page_lb_totalpayment').':</b></td><td width="3%">&nbsp;</td><td align="left" width="25%"><b>'.format_money($grand_total,"&nbsp;".$this->data['currency_name']).'</b></td></tr></table></td></tr></table>';
				$this->writeHTML($html, true, false, true, false, '');

				$company_business_partner = $this->data['company_business_partner_name'].' - '.$this->data['company_name'];
				if ($this->data['quotation']['provider'] == 'Telekom') {
				    $company_business_partner = $this->data['company_name'].' - '.$this->data['company_business_partner_name_telekom'];

				} else if ($this->data['quotation']['provider'] == 'o2Business') {
				    $company_business_partner = $this->data['company_name'].' - '.$this->data['company_business_partner_name_o2'];
				}

				//Footer Text
				$this->SetX(0);
				$html = '<table border="0" width="100%" cellspacing="0" cellpadding="0" style="color:#6a6a6a; font-size:8px; font-weight:normal;">
				<tr><td></td></tr><tr><td align="center">'.$company_business_partner.' - '.$this->data['company_address'].
				' - '.$this->data['company_zipcode'].' '.$this->data['company_city'].'</td></tr>
				<tr><td align="center">'.$this->data['company_address2'].'</td></tr>
				<tr><td align="center">'.$this->data['company_address3'].'</td></tr>
				<tr><td align="center">'.lang('page_lb_tel').'.: '.$this->data['company_tel'].' '.lang('page_lb_fax').': '.$this->data['company_fax'].' - '.$this->data['company_website'].' - '.$this->data['company_email'].'</td></tr>
				</table>';
				$this->writeHTML($html, true, false, true, false, '');

			break;


			case 'printmonitoringprotocol':

				if ($this->last_page_flag) {
					//Red Footer Line
					$linestyle = array('width' => 6, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(228, 34, 37));
					$this->Line(20, 220, 210, 220, $linestyle);

					//Footer
					$additional_extracost = $this->data['monitoring']['additional_extracost'];

					$this->SetY(218);
					$html = '<table border="0" width="100%" cellspacing="0" cellpadding="0" style="color:#2b2b2a"><tr><td width="2%"></td><td align="left" style="font-size:10px;color:#fff;"><b>'.lang('page_monitoring_pdf_otherremark').'</b></td></tr><tr><td width="2%"></td><td align="left" width="98%"><table border="0" width="100%"><tr style="font-size:10px;"><td>'.$additional_extracost.'</td></tr></table></td></tr></table>';
					$this->writeHTML($html, true, false, true, false, '');

					//Footer Text
					$this->SetX(0);
					$this->SetY(270);
					$html = '<table border="0" width="100%" cellspacing="0" cellpadding="0" style="color:#6a6a6a; font-size:8px; font-weight:normal;">
					<tr><td></td></tr><tr><td align="center">'.$this->data['company_business_partner_name'].' - '.$this->data['company_name'].' - '.$this->data['company_address'].
					' - '.$this->data['company_zipcode'].' '.$this->data['company_city'].'</td></tr>
					<tr><td align="center">'.$this->data['company_address2'].'</td></tr>
					<tr><td align="center">'.$this->data['company_address3'].'</td></tr>
					<tr><td align="center">'.lang('page_lb_tel').'.: '.$this->data['company_tel'].' '.lang('page_lb_fax').': '.$this->data['company_fax'].' - '.$this->data['company_website'].' - '.$this->data['company_email'].'</td></tr>
					</table>';
					$this->writeHTML($html, true, false, true, false, '');
				}

			break;

			case 'printquotation':

				if ($this->data['quotation']['provider'] == 'Telekom') {
					$image_file = 'assets/pages/img/footer-table-corner-telekom.png';
					$this->Image($image_file, 17, 161, 259, '', 'PNG', '', 'T', false, 500, '', false, false, 0, false, false, false);

				} else if ($this->data['quotation']['provider'] == 'o2Business') {
					$image_file = 'assets/pages/img/footer-table-corner-o2.png';
					$this->Image($image_file, 17, 161, 259, '', 'PNG', '', 'T', false, 500, '', false, false, 0, false, false, false);

				} else {
					$image_file = 'assets/pages/img/footer-table-corner.png';
					$this->Image($image_file, 17, 160, 259, '', 'PNG', '', 'T', false, 500, '', false, false, 0, false, false, false);
				}

				$company_business_partner = $this->data['company_business_partner_name'].' - '.$this->data['company_name'];
				if ($this->data['quotation']['provider'] == 'Telekom') {
				    $company_business_partner = $this->data['company_name'].' - '.$this->data['company_business_partner_name_telekom'];

				} else if ($this->data['quotation']['provider'] == 'o2Business') {
				    $company_business_partner = $this->data['company_name'].' - '.$this->data['company_business_partner_name_o2'];
				}

				//Footer Text
				$this->SetY(160);
				$html = '<table border="0" width="100%" cellspacing="0" cellpadding="0" style="font-size:6px; font-weight:normal;">
				<tr><td></td></tr><tr><td align="center">'.$company_business_partner.' - '.$this->data['company_address'].
				' - '.$this->data['company_zipcode'].' '.$this->data['company_city'].'</td></tr>
				<tr><td align="center">'.lang('page_lb_tel').'.: '.$this->data['company_tel'].' '.lang('page_lb_fax').': '.$this->data['company_fax'].' - '.$this->data['company_website'].' - '.$this->data['company_email'].'</td></tr>
				</table>';
				$this->writeHTML($html, true, false, true, false, '');

			break;

			case 'printleadquotation':

				if ($this->data['quotation']['provider'] == 'Telekom') {
					$image_file = 'assets/pages/img/footer-table-corner-telekom.png';
					$this->Image($image_file, 17, 161, 259, '', 'PNG', '', 'T', false, 500, '', false, false, 0, false, false, false);

				} else if ($this->data['quotation']['provider'] == 'o2Business') {
					$image_file = 'assets/pages/img/footer-table-corner-o2.png';
					$this->Image($image_file, 17, 161, 259, '', 'PNG', '', 'T', false, 500, '', false, false, 0, false, false, false);

				} else {
					$image_file = 'assets/pages/img/footer-table-corner.png';
					$this->Image($image_file, 17, 160, 259, '', 'PNG', '', 'T', false, 500, '', false, false, 0, false, false, false);
				}

				$company_business_partner = $this->data['company_business_partner_name'].' - '.$this->data['company_name'];
				if ($this->data['quotation']['provider'] == 'Telekom') {
				    $company_business_partner = $this->data['company_name'].' - '.$this->data['company_business_partner_name_telekom'];

				} else if ($this->data['quotation']['provider'] == 'o2Business') {
				    $company_business_partner = $this->data['company_name'].' - '.$this->data['company_business_partner_name_o2'];
				}

				//Footer Text
				$this->SetY(160);
				$html = '<table border="0" width="100%" cellspacing="0" cellpadding="0" style="font-size:6px; font-weight:normal;">
				<tr><td></td></tr><tr><td align="center">'.$company_business_partner.' - '.$this->data['company_address'].
				' - '.$this->data['company_zipcode'].' '.$this->data['company_city'].'</td></tr>
				<tr><td align="center">'.lang('page_lb_tel').'.: '.$this->data['company_tel'].' '.lang('page_lb_fax').': '.$this->data['company_fax'].' - '.$this->data['company_website'].' - '.$this->data['company_email'].'</td></tr>
				</table>';
				$this->writeHTML($html, true, false, true, false, '');

			break;

			case 'employeecommissionslip':

				//Red Footer Line
				$linestyle = array('width' => 6, 'cap' => 'butt', 'join' => 'miter', 'dash' => '', 'phase' => 0, 'color' => array(228, 34, 37));
				$this->Line(20, 250, 210, 250, $linestyle);

				//Footer Total Payment
				$this->SetY(260);
				$html = '<table border="0" width="100%" cellspacing="0" cellpadding="0" style="color:#2b2b2a"><tr><td align="right"><table border="0"><tr><td align="right" width="72%"><b>'.lang('page_lb_totalpayment').':</b></td><td width="3%">&nbsp;</td><td align="left" width="25%"><b>'.format_money($this->data['withdrawvalue'],"&nbsp;".$this->data['currency_name']).'</b></td></tr></table></td></tr></table>';
				$this->writeHTML($html, true, false, true, false, '');

				//Footer Text
				$this->SetX(0);
				$html = '<table border="0" width="100%" cellspacing="0" cellpadding="0" style="color:#6a6a6a; font-size:8px; font-weight:normal;">
				<tr><td></td></tr><tr><td align="center">'.$this->data['company_business_partner_name'].' - '.$this->data['company_name'].' - '.$this->data['company_address'].
				' - '.$this->data['company_zipcode'].' '.$this->data['company_city'].'</td></tr>
				<tr><td align="center">'.$this->data['company_address2'].'</td></tr>
				<tr><td align="center">'.$this->data['company_address3'].'</td></tr>
				<tr><td align="center">'.lang('page_lb_tel').'.: '.$this->data['company_tel'].' '.lang('page_lb_fax').': '.$this->data['company_fax'].' - '.$this->data['company_website'].' - '.$this->data['company_email'].'</td></tr>
				</table>';
				$this->writeHTML($html, true, false, true, false, '');

			break;
		}
	}
}