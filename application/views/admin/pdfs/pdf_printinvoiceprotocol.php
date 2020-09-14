<?php
//$obj_pdf = new TCPDF('P', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
$obj_pdf = new pdf('printinvoiceprotocol',$data);
extract($data);

$title = lang('page_lb_print_invoice_protocol');
$obj_pdf->SetTitle($title);
$obj_pdf->SetFont('helvetica', '', 9);
$obj_pdf->SetMargins(18, 10, 0);
$obj_pdf->SetAutoPageBreak(TRUE, 0);
$obj_pdf->AddPage();

ob_start();
// we can have any view part here like HTML, PHP etc
//$content = ob_get_contents();


//Page-1
$image_file = 'assets/pages/img/RechnungsAnalyseTool-1.jpg';
$obj_pdf->Image($image_file, 0, 0, 0, '', 'JPG', '', 'T', false, 0, '', false, false, 0, false, false, false);
$customer_company_email = str_replace(" ","",$quotation['customer_company']);

$content = '<table border="0" width="100%" cellspacing="0" cellpadding="0" style="color:#2b2b2a">';

    $content.='<tr><td>';
        $content.='<table border="0" width="100%" cellspacing="0" cellpadding="0">';
            $content.='<tr>';
                $content.='<td width="100%">';
                    $content.='<table border="0" width="100%" cellspacing="0" cellpadding="3" style="font-size:8px;">';
                        $content.='<tr><td colspan="2">'.$quotation['customer_company'].'</td></tr>';
                        $content.='<tr><td colspan="2">'.$quotation['customer_street'].'</td></tr>';
                        $content.='<tr><td width="16.5%">'.$quotation['customer_zipcode'].'</td><td>'.$quotation['customer_city'].'</td></tr>';
                        $content.='<tr><td width="5.5%"></td><td>'.$quotation['customer_contact_person'].'</td></tr>';
                        $content.='<tr><td width="14%"></td><td>'.$quotation['customer_phone'].'</td></tr>';
                        $content.='<tr><td colspan="2">'.$customer_company_email.'@dk-deutschland.de</td></tr>';
                        $content.='<tr><td width="14%"></td><td>'.$quotation['customer_registernr'].' / '.$quotation['customer_districtcourt'].'</td></tr>';
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
$obj_pdf->SetXY(35,40);
$obj_pdf->writeHTML($html, true, false, true, false, '');

//Page-2
$obj_pdf->AddPage();
$image_file = 'assets/pages/img/RechnungsAnalyseTool-2.jpg';
$obj_pdf->Image($image_file, 0, 0, 0, '', 'JPG', '', 'T', false, 0, '', false, false, 0, false, false, false);


//Close and output PDF document
$obj_pdf->Output('QuotationInvoiceProtocol-'.$data['quotation']['quotationnr'].'.pdf', 'I');
?>
