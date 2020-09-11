<?php
//$obj_pdf = new TCPDF('P', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
extract($data);
$obj_pdf = new pdf('printdeliverynote2',$data);

$title = lang('page_lb_print_deliverynote');
$obj_pdf->SetTitle($title);
$obj_pdf->SetFont('helvetica', '', 9);
$obj_pdf->SetMargins(18, 58, 0);
$obj_pdf->SetAutoPageBreak(TRUE, 80);
$obj_pdf->AddPage();

ob_start();
// we can have any view part here like HTML, PHP etc
//$content = ob_get_contents();

$content = '<table border="0" width="100%" cellspacing="0" cellpadding="0" style="color:#2b2b2a">';

    //Small Heading of Page
    $content.='<tr><td>';
        $content.='<table border="0" width="40%" cellspacing="0" cellpadding="0" style="font-size:6px;"><tr><td style="border-bottom:0px solid #2b2b2a;">';
        $content.=$company_business_partner.' '.$company_name.' - '.$company_address.' - '.$company_zipcode.' '.$company_city;
        $content.='</td></tr></table>';
    $content.='</td></tr>';        
    
    $content.='<tr><td>';  
        $content.='<table border="0" width="100%" cellspacing="0" cellpadding="15">';
            $content.='<tr>';            
                $content.='<td width="40%">'; //Left
                    $content.='<table border="0" cellspacing="0" cellpadding="0" style="font-size:12px;">';
                        $content.='<tr><td>'.$shippingslip['customer_company'].'</td></tr>';
                        $content.='<tr><td>'.$shippingslip['customer_street'].'</td></tr>';
                        $content.='<tr><td>'.$shippingslip['customer_zipcode'].' '.$shippingslip['customer_city'].'</td></tr>';
                    $content.='</table>';
                $content.='</td>';                
                $content.='<td width="60%">'; // Right
                    $content.='<table border="0" cellspacing="0" cellpadding="0" style="font-size:10px;">';
                        $content.='<tr><td align="right">'.lang('page_dt_customernr').': &nbsp;</td><td>'.$shippingslip['customernr_prefix'].'</td></tr>';
                        $content.='<tr><td align="right">'.lang('page_hardware_assignment_pdf_responsible').': &nbsp;</td><td>'.$shippingslip['responsible'].'</td></tr>';
                        $content.='<tr><td align="right">'.lang('page_fl_email').': &nbsp;</td><td>'.$shippingslip['responsible_email'].'</td></tr>';
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
                        $content.='<tr><th align="right"><b>'.lang('page_hardware_assignment_pdf_shippingslipnr').'.:</b> &nbsp;</th><th><b>'.$shippingslip['shippingslipnr_prefix'].'</b></th></tr>';
                        $content.='<tr><th align="right"><b>'.lang('page_fl_date').':</b> &nbsp;</th><th><b>'._d($shippingslip['created']).'</b></th></tr>';
                    $content.='</table>';
                $content.='</td>';                
            $content.='</tr>';
        $content.='</table>';  
    $content.='</td></tr>';
    
    //Row
    $content.='<tr><td style="font-size:10px;">';  
        $content.='<table border="0" width="100%" cellspacing="0" cellpadding="3" class="table-bordered">';   
            $content.='<tr><th style="background-color:#e42225; color:#ffffff;" class="none_border" width="33.33%">&nbsp;&nbsp;<b>'.lang('page_fl_hardware').'</b></th><th style="background-color:#e42225; color:#ffffff;" class="none_border" width="33.33%"><b>'.lang('page_hardware_assignment_pdf_mobilenr').'.</b></th><th style="background-color:#e42225; color:#ffffff;" class="none_border" width="33.33%"><b>'.lang('page_hardware_assignment_pdf_shippingnr').'.</b></th></tr>';            
            foreach($shippingslipproducts as $shippingslipproduct){
                if($shippingslipproduct['shippingnr']!=""){
                    if($shippingslipproduct['seriesnr']!=""){
                        $shippingslipproduct['seriesnr'] = '<br /> <small>&nbsp;&nbsp;&nbsp;'.lang('page_hardware_assignment_pdf_seriesnr').': '.$shippingslipproduct['seriesnr'].'</small>';
                    }
                    $content.='<tr><td class="none_border" style="border-bottom:0px solid #000;" width="33.33%">&nbsp;&nbsp;'.$shippingslipproduct['hardware'].' '.$shippingslipproduct['seriesnr'].'</td><td class="none_border" style="border-bottom:0px solid #000;" width="33.33%">'.$shippingslipproduct['mobilenr'].'</td><td class="none_border" style="border-bottom:0px solid #000;" width="15%">'.$shippingslipproduct['shippingnr'].'</td></tr>';                                    
                }    
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
$obj_pdf->Output('DeliveryNote-'.$shippingslip['shippingslipnr_prefix'].'.pdf', 'I'); 
?>