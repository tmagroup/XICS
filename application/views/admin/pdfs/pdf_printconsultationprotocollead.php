<?php
//$obj_pdf = new TCPDF('P', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
$obj_pdf = new pdf('printconsultationprotocollead',$data);
extract($data);

$title = lang('page_lb_print_consultation_protocol');
$obj_pdf->SetTitle($title);
$obj_pdf->SetFont('helvetica', '', 9);
$obj_pdf->SetMargins(18, 45, 0);
$obj_pdf->SetAutoPageBreak(TRUE, 0);
$obj_pdf->AddPage();

ob_start();
// we can have any view part here like HTML, PHP etc
//$content = ob_get_contents();

$background_color_th = 'background-color:#e42225';
if ($data['quotation']['provider'] == 'Telekom') {
    $background_color_th = 'background-color:#5f696a';

} else if ($data['quotation']['provider'] == 'o2Business') {
    $background_color_th = 'background-color:#143066';
}

$content = '<table border="0" width="100%" cellspacing="0" cellpadding="0" style="color:#2b2b2a">';

    $content.='<tr><td>';
        $content.='<table border="0" width="100%" cellspacing="0" cellpadding="0">';
            $content.='<tr>';
                $content.='<td width="50%">'; //Left
                    $content.='<table border="0" width="80%" cellspacing="0" cellpadding="0" style="font-size:12px;">';
                        $content.='<tr><td><b>'.lang('page_consultation_quotation_pdf_consultantinfo').'</b></td></tr>';
                        $content.='<tr><td></td></tr>';
                        $content.='<tr><td style="font-size:10px;">'.lang('page_consultation_quotation_pdf_consultantname').'</td></tr>';
                        $content.='<tr><td style="font-size:10px; border-bottom:0px solid #000; color:#ff0000">'.$quotation['responsible'].'</td></tr>';
                    $content.='</table>';
                $content.='</td>';
                $content.='<td width="50%">'; // Right
                    $content.='<table border="0" width="100%" cellspacing="0" cellpadding="0" style="font-size:10px;">';
                        $content.='<tr><td align="right" width="30%">'.lang('page_dt_customernr').': &nbsp;</td><td width="70%">'.$quotation['leadnr_prefix'].'</td></tr>';
                        $content.='<tr><td align="right">'.lang('page_fl_email').': &nbsp;</td><td>'.$quotation['responsible_email'].'</td></tr>';
                        $content.='<tr><td align="right">'.lang('page_fl_hotline').': &nbsp;</td><td>'.get_option('company_hotline').'</td></tr>';
                    $content.='</table>';
                $content.='</td>';
            $content.='</tr>';
        $content.='</table>';
    $content.='</td></tr>';


    $content.='<tr><td></td></tr>';


    $content.='<tr><td>';
        $content.='<table width="100%" cellspacing="0" cellpadding="2">';
            $content.='<tr>';
                $content.='<td width="100%" style="'.$background_color_th.'">';
                $content.='</td>';
            $content.='</tr>';
        $content.='</table>';
    $content.='</td></tr>';


    $content.='<tr><td></td></tr>';


    $content.='<tr><td>';
        $content.='<table border="0" width="100%" cellspacing="0" cellpadding="0">';
            $content.='<tr>';
                $content.='<td width="50%">'; //Left
                    $content.='<table border="0" width="80%" cellspacing="0" cellpadding="0" style="font-size:12px;">';
                        $content.='<tr><td><b>'.lang('page_consultation_quotation_pdf_generaladvice').'</b></td></tr>';
                        $content.='<tr><td style="border-bottom:0px solid #000; font-size:10px;"><br /><br />'.lang('page_fl_date').': </td></tr>';
                        $content.='<tr><td style="border-bottom:0px solid #000; font-size:10px;"><br /><br />'.lang('page_consultation_quotation_pdf_companycity').': <span style="color:#ff0000;">'.$quotation['customer_city'].'</span></td></tr>';
                    $content.='</table>';
                $content.='</td>';
                $content.='<td width="50%">'; // Right
                    $content.='<table border="0" width="80%" cellspacing="0" cellpadding="0" style="font-size:12px;">';
                        $content.='<tr><td><b>'.lang('page_consultation_quotation_pdf_otherpersonpresent').'</b></td></tr>';
                        $content.='<tr><td style="border-bottom:0px solid #000; font-size:10px;"><br /><br /></td></tr>';
                        $content.='<tr><td style="border-bottom:0px solid #000; font-size:10px;"><br /><br /></td></tr>';
                    $content.='</table>';
                $content.='</td>';
            $content.='</tr>';
        $content.='</table>';
    $content.='</td></tr>';


    $content.='<tr><td></td></tr>';


    $content.='<tr><td>';
        $content.='<table width="100%" cellspacing="0" cellpadding="2">';
            $content.='<tr>';
                $content.='<td width="100%" style="'.$background_color_th.'">';
                $content.='</td>';
            $content.='</tr>';
        $content.='</table>';
    $content.='</td></tr>';


    $content.='<tr><td></td></tr>';


    $content.='<tr><td>';
        $content.='<table border="0" width="100%" cellspacing="0" cellpadding="0">';
            $content.='<tr>';
                $content.='<td width="100%">'; //Left
                    $content.='<table border="0" width="100%" cellspacing="0" cellpadding="0" style="font-size:12px;">';
                        $content.='<tr><td width="30%"><b>'.lang('page_consultation_quotation_pdf_customerinfo').'</b></td><td style="font-size:8px; line-height: 250%;">'.lang('page_consultation_quotation_pdf_consultantfillinfo').'</td></tr>';
                    $content.='</table>';
                $content.='</td>';
            $content.='</tr>';
        $content.='</table>';
    $content.='</td></tr>';

    $content.='<tr><td>';
        $content.='<table border="0" width="100%" cellspacing="0" cellpadding="0">';
            $content.='<tr>';
                $content.='<td width="40%">'; //Left
                    $content.='<table border="0" width="95%" cellspacing="0" cellpadding="0" style="font-size:12px;">';
                        $content.='<tr><td style="border-bottom:0px solid #000; font-size:10px;"><br /><br />'.lang('page_fl_company').': <span style="color:#ff0000;">'.$quotation['customer_company'].'</span></td></tr>';
                        $content.='<tr><td style="border-bottom:0px solid #000; font-size:10px;"><br /><br />'.lang('page_fl_street').': <span style="color:#ff0000;">'.$quotation['customer_street'].'</span></td></tr>';
                        $content.='<tr><td style="border-bottom:0px solid #000; font-size:10px;"><br /><br />'.lang('page_fl_position').': <span style="color:#ff0000;">'.$quotation['customer_position'].'</span></td></tr>';
                    $content.='</table>';
                $content.='</td>';
                $content.='<td width="60%">'; // Right
                    $content.='<table border="0" width="84%" cellspacing="0" cellpadding="0" style="font-size:12px;">';
                        // $content.='<tr><td style="border-bottom:0px solid #000; font-size:10px;"><br /><br />'.lang('page_fl_contactperson').': <span style="color:#ff0000;">'.$quotation['customer_contact_person'].'</span></td></tr>';
                        $content.='<tr><td style="border-bottom:0px solid #000; font-size:10px;"><br /><br />'.lang('page_fl_zipcode').'/'.lang('page_fl_city').': <span style="color:#ff0000;">'.$quotation['customer_zipcode'].' '.$quotation['customer_city'].'</span></td></tr>';
                        $content.='<tr><td style="border-bottom:0px solid #000; font-size:10px;"><br /><br />'.lang('page_consultation_quotation_pdf_customerdirector').': <span style="color:#ff0000;">'.$quotation['customer_name'].'</span></td></tr>';
                    $content.='</table>';
                $content.='</td>';
            $content.='</tr>';
        $content.='</table>';
    $content.='</td></tr>';

    $content.='<tr><td></td></tr>';


    $content.='<tr><td>';
        $content.='<table width="100%" cellspacing="0" cellpadding="2">';
            $content.='<tr>';
                $content.='<td width="100%" style="'.$background_color_th.'">';
                $content.='</td>';
            $content.='</tr>';
        $content.='</table>';
    $content.='</td></tr>';


    $content.='<tr><td></td></tr>';


    $content.='<tr><td>';
        $content.='<table border="0" width="100%" cellspacing="0" cellpadding="0">';
            $content.='<tr>';
                $content.='<td width="100%">'; //Left
                    $content.='<table border="0" width="100%" cellspacing="0" cellpadding="0" style="font-size:12px;">';
                        $content.='<tr><td width="100%"><b>'.lang('page_consultation_quotation_pdf_consultantdate').'</b></td></tr>';
                    $content.='</table>';
                $content.='</td>';
            $content.='</tr>';
        $content.='</table>';
    $content.='</td></tr>';


    $content.='<tr><td></td></tr>';


    /*
    When In the Quotation in any Product is Selected by “VVL/Neu” = VVL than “Vertragsverlängerung” and “Tarifoptimierung” should be checked.
    When In the Quotation in any Product is Selected by “VVL/Neu” = Neu than “Neuvertrag” should be checked.
    When In the Quotation in any Product is Selected by “VVL/Neu” = VVL and Neu than “Vertragsverlängerung”, “Neuvertrag” and “Tarifoptimierung” should be checked.
    */

    $contractextension = '';
    $contractextension_table='border="0" width="100%" cellspacing="0" cellpadding="0" style="font-size:0px;"';
    $tariffoptimization = '';
    $tariffoptimization_table='border="0" width="100%" cellspacing="0" cellpadding="0" style="font-size:0px;"';
    $newcontract = '';
    $newcontract_table='border="0" width="100%" cellspacing="0" cellpadding="0" style="font-size:0px;"';


    foreach($quotationproducts as $quotationproduct){
        if(strtolower(trim($quotationproduct['vvlneu']))=='vvl'){
            $contractextension = '<img src="assets/pages/img/checkmark.png" />';
        }
        if(strtolower(trim($quotationproduct['vvlneu']))=='vvl'){
            $tariffoptimization = '<img src="assets/pages/img/checkmark.png" />';
        }
        if(strtolower(trim($quotationproduct['vvlneu']))=='neu'){
            $newcontract = '<img src="assets/pages/img/checkmark.png" />';
        }
    }

    if($contractextension==""){
        $newcontract_table = "";
    }
    if($tariffoptimization==""){
        $tariffoptimization_table = "";
    }
    if($newcontract==""){
        $newcontract_table = "";
    }

    $content.='<tr><td>';
        $content.='<table border="0" width="100%" cellspacing="0" cellpadding="0">';
            $content.='<tr>';
                $content.='<td width="25%">';
                    $content.='<table border="0" width="100%" cellspacing="0" cellpadding="2" style="font-size:10px;">';
                        $content.='<tr><td width="13%"><table '.$contractextension_table.'><tr><td style="border:2px solid #000;">'.$contractextension.'</td></tr></table></td><td width="85%">'.lang('page_consultation_quotation_pdf_contractextension').'<br /><span style="font-size:8px;">'.lang('page_consultation_quotation_pdf_detail_appendix').'</span></td></tr>';
                    $content.='</table>';
                $content.='</td>';
                $content.='<td width="25%">';
                    $content.='<table border="0" width="100%" cellspacing="0" cellpadding="2" style="font-size:10px;">';
                        $content.='<tr><td width="13%"><table '.$newcontract_table.'><tr><td style="border:2px solid #000;">'.$newcontract.'</td></tr></table></td><td width="85%">'.lang('page_consultation_quotation_pdf_newcontract').'<br /><span style="font-size:8px;">'.lang('page_consultation_quotation_pdf_detail_appendix').'</span></td></tr>';
                    $content.='</table>';
                $content.='</td>';
                $content.='<td width="25%">';
                    $content.='<table border="0" width="100%" cellspacing="0" cellpadding="2" style="font-size:10px;">';
                        $content.='<tr><td width="13%"><table '.$tariffoptimization_table.'><tr><td style="border:2px solid #000;">'.$tariffoptimization.'</td></tr></table></td><td width="85%">'.lang('page_consultation_quotation_pdf_tariffoptimization').'<br /><span style="font-size:8px;">'.lang('page_consultation_quotation_pdf_detail_appendix').'</span></td></tr>';
                    $content.='</table>';
                $content.='</td>';
                $content.='<td width="25%">';
                    $content.='<table border="0" width="100%" cellspacing="0" cellpadding="2" style="font-size:10px;">';
                        $content.='<tr><td width="13%"><table><tr><td style="border:2px solid #000;"></td></tr></table></td><td width="85%">'.lang('page_consultation_quotation_pdf_support').'</td></tr>';
                    $content.='</table>';
                $content.='</td>';
            $content.='</tr>';
        $content.='</table>';
    $content.='</td></tr>';


    $content.='<tr><td></td></tr>';


    $content.='<tr><td>';
        $content.='<table border="0" width="100%" cellspacing="0" cellpadding="0">';
            $content.='<tr>';
                $content.='<td width="100%">'; //Left
                    $content.='<table border="0" width="90%" cellspacing="0" cellpadding="0" style="font-size:12px;">';
                        $content.='<tr><td width="100%"><b>'.lang('page_consultation_quotation_pdf_customerrequirement').'</b></td></tr>';
                        $content.='<tr><td width="100%" style="font-size:8px;">'.nl2br($data['quotation']['customerrequirements']).'</td></tr>';
                        $content.='<tr><td width="100%" style="border-bottom:0px solid #000;"><br /><br /></td></tr>';
                        $content.='<tr><td width="100%" style="border-bottom:0px solid #000;"><br /><br /></td></tr>';

                    $content.='</table>';
                $content.='</td>';
            $content.='</tr>';
        $content.='</table>';
    $content.='</td></tr>';

    $content.='<tr><td></td></tr>';

    $content.='<tr><td>';
        $content.='<table width="100%" cellspacing="0" cellpadding="2">';
            $content.='<tr>';
                $content.='<td width="100%" style="'.$background_color_th.'">';
                $content.='</td>';
            $content.='</tr>';
        $content.='</table>';
    $content.='</td></tr>';

    $content.='<tr><td></td></tr>';

    $content.='<tr><td>';
        $content.='<table border="0" width="95%" cellspacing="0" cellpadding="0">';
            $content.='<tr>';
                $content.='<td width="100%">'; //Left
                    $content.='<table border="0" width="100%" cellspacing="0" cellpadding="0" style="font-size:8px;">';
                        $content.='<tr><td width="100%">'.lang('page_consultation_quotation_pdf_footerline').'</td></tr>';
                    $content.='</table>';
                $content.='</td>';
            $content.='</tr>';
        $content.='</table>';
    $content.='</td></tr>';

    $content.='<tr><td></td></tr>';

    $content.='<tr><td>';
        $content.='<table border="0" width="95%" cellspacing="0" cellpadding="0">';
            $content.='<tr>';
                $content.='<td width="50%">'; //Left
                    $content.='<table border="0" width="90%" cellspacing="0" cellpadding="2" style="font-size:8px; border:0px solid #000;">';
                        $content.='<tr><td height="50">'.lang('page_consultation_quotation_pdf_consultantsign').'</td></tr>';
                    $content.='</table>';
                $content.='</td>';
                $content.='<td width="50%">'; // Right
                    $content.='<table border="0" width="90%" cellspacing="0" cellpadding="2" style="font-size:8px; border:0px solid #000;">';
                        $content.='<tr><td height="50">'.lang('page_consultation_quotation_pdf_customersign').'</td></tr>';
                    $content.='</table>';
                $content.='</td>';
            $content.='</tr>';
        $content.='</table>';
    $content.='</td></tr>';

$content.= '</table>';


// Get the proposals css
$css = file_get_contents(FCPATH.'assets/layouts/layout/css/pdf.css');
// Theese lines should aways at the end of the document left side. Dont indent these lines
$html = <<<EOF
<style>
    $css
</style>
$content
EOF;

ob_end_clean();
$obj_pdf->writeHTML($html, true, false, true, false, '');

//Close and output PDF document
$obj_pdf->Output('QuotationConsultationProtocol-'.$data['quotation']['leadquotationnr'].'.pdf', 'I');
?>