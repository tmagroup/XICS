<?php
//$obj_pdf = new TCPDF('P', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
extract($data);
$obj_pdf = new pdf('printhardwareinvoice',$data);

$title = lang('page_lb_print_hardware_invoice');
$obj_pdf->SetTitle($title);
$obj_pdf->SetFont('helvetica', '', 9);
$obj_pdf->SetMargins(18, 58, 0);
$obj_pdf->SetAutoPageBreak(TRUE, 60);
$obj_pdf->AddPage();

ob_start();
// we can have any view part here like HTML, PHP etc
//$content = ob_get_contents();

$background_color_th = 'background-color:#e42225;';
if ($data['hardwareassignment']['provider'] == 'Telekom') {
    $company_business_partner = $company_name.' '.$company_business_partner_telekom;
    $background_color_th = 'background-color:#5f696a;';

} else if ($data['hardwareassignment']['provider'] == 'o2Business') {
    $company_business_partner = $company_name.' '.$company_business_partner_o2;
    $background_color_th = 'background-color:#143066;';

} else {
    $company_business_partner = $company_business_partner.' '.$company_name;
}

$content = '<table border="0" width="100%" cellspacing="0" cellpadding="0" style="color:#2b2b2a">';

    //Small Heading of Page
    $content.='<tr><td>';
        $content.='<table border="0" width="45%" cellspacing="0" cellpadding="0" style="font-size:6px;"><tr><td style="border-bottom:0px solid #2b2b2a;">';
        $content.=$company_business_partner.' - '.$company_address.' - '.$company_zipcode.' '.$company_city;
        $content.='</td></tr></table>';
    $content.='</td></tr>';

    $content.='<tr><td>';
        $content.='<table border="0" width="100%" cellspacing="0" cellpadding="15">';
            $content.='<tr>';
                $content.='<td width="40%">'; //Left
                    $content.='<table border="0" cellspacing="0" cellpadding="0" style="font-size:12px;">';
                        $content.='<tr><td>'.$invoice['customer_company'].'</td></tr>';
                        $content.='<tr><td>'.$invoice['customer_street'].'</td></tr>';
                        $content.='<tr><td>'.$invoice['customer_zipcode'].' '.$invoice['customer_city'].'</td></tr>';
                    $content.='</table>';
                $content.='</td>';
                $content.='<td width="60%">'; // Right
                    $content.='<table border="0" cellspacing="0" cellpadding="0" style="font-size:10px;">';
                        $content.='<tr><td align="right">'.lang('page_dt_customernr').': &nbsp;</td><td>'.$invoice['customernr_prefix'].'</td></tr>';
                        $content.='<tr><td align="right">'.lang('page_hardware_assignment_pdf_responsible').': &nbsp;</td><td>'.$invoice['responsible'].'</td></tr>';
                        $content.='<tr><td align="right">'.lang('page_fl_email').': &nbsp;</td><td>'.$invoice['responsible_email'].'</td></tr>';
                        $content.='<tr><td align="right">'.lang('page_fl_hotline').': &nbsp;</td><td>'.get_option('company_hotline').'</td></tr>';
                    $content.='</table>';
                $content.='</td>';
            $content.='</tr>';
        $content.='</table>';
    $content.='</td></tr>';

    $content.='<tr><td>';
        $content.='<table border="0" width="100%" cellspacing="0" cellpadding="15">';
            $content.='<tr>';
                $content.='<td width="40%"></td>'; //Left
                $content.='<td width="60%">'; //Right
                    $content.='<table border="0" cellspacing="0" cellpadding="0" style="font-size:12px;">';
                        $content.='<tr><th align="right"><b>'.lang('page_dt_invoicenr').'.:</b> &nbsp;</th><th><b>'.$invoice['invoicenr_prefix'].'</b></th></tr>';
                        $content.='<tr><th align="right"><b>'.lang('page_fl_date').':</b> &nbsp;</th><th><b>'._d($invoice['created']).'</b></th></tr>';
                    $content.='</table>';
                $content.='</td>';
            $content.='</tr>';
        $content.='</table>';
    $content.='</td></tr>';

    //Row
    $content.='<tr><td style="font-size:10px;">';
        $content.='<table border="0" width="100%" cellspacing="0" cellpadding="3" class="table-bordered">';
            $content.='<tr><th style="'.$background_color_th.' color:#ffffff;" class="none_border" width="33.33%">&nbsp;&nbsp;<b>'.lang('page_fl_hardware').'</b></th><th style="'.$background_color_th.' color:#ffffff;" class="none_border" width="33.33%"><b>'.lang('page_quotation_pdf_mobilenr').'.</b></th><th style="'.$background_color_th.' color:#ffffff;" class="none_border" width="33.33%"><b>'.lang('page_hardware_quotation_pdf_payment').'</b></th></tr>';

            foreach($invoiceproducts as $invoiceproduct){
                $content.='<tr><td class="none_border" style="border-bottom:0px solid #000;" width="33.33%">&nbsp;&nbsp;'.$invoiceproduct['hardware'].'</td><td class="none_border" style="border-bottom:0px solid #000;" width="33.33%">'.$invoiceproduct['mobilenr'].'</td><td class="none_border" style="border-bottom:0px solid #000;" width="15%">'.format_money($invoiceproduct['hardwarevalue'],"&nbsp;".$currency_name).'</td></tr>';
            }

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
$obj_pdf->Output('HardwareInvoice-'.$invoice['invoicenr_prefix'].'.pdf', 'I');

//Create File in Folder
if(is_dir(FCPATH.'uploads/hardware_assignment_invoices/')){
    $obj_pdf->Output(FCPATH.'uploads/hardware_assignment_invoices/HardwareInvoice-'.$invoice['invoicenr_prefix'].'.pdf', 'F');
}
?>