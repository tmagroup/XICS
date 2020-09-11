<?php
//$obj_pdf = new TCPDF('P', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
$obj_pdf = new pdf('printmonitoringinvoice',$data);
extract($data);

$title = lang('page_monitorinng_consulting_title');
$obj_pdf->SetTitle($title);
$obj_pdf->SetFont('helvetica', '', 9);
$obj_pdf->SetMargins(18, 45, 0);
$obj_pdf->SetAutoPageBreak(TRUE, 0);
$obj_pdf->AddPage();

ob_start();
// we can have any view part here like HTML, PHP etc
//$content = ob_get_contents();

$background_color_th = 'background-color:#e42225';
if ($data['provider'] == 'Telekom') {
    $background_color_th = 'background-color:#5f696a';

} else if ($data['provider'] == 'o2Business') {
    $background_color_th = 'background-color:#143066';
}

$content = '<table border="0" width="100%" cellspacing="0" cellpadding="0" style="color:#2b2b2a">';
    $content.='<tr><td>';
        $content.='<table border="0" width="100%" cellspacing="0" cellpadding="0">';
            $content.='<tr>';
                $content.='<td width="70%">';
                    $content.='<table border="0" width="80%" cellspacing="0" cellpadding="0" style="font-size:12px;">';
                        $content.='<tr><td style="font-size:8px;">'.lang('page_monitorinng_info').'</td></tr>';
                    $content.='</table>';
                    $content.='<hr/>';
                $content.='</td>';
            $content.='</tr>';
        $content.='</table>';
    $content.='</td></tr>';

    $content.='<tr><td>';
        $content.='<table border="0" width="100%" cellspacing="0" cellpadding="0">';
            $content.='<tr>';
                $content.='<td width="50%">'; //Left
                    $content.='<table border="0" width="80%" cellspacing="0" cellpadding="0" style="font-size:12px;">';
                        $content.='<tr><td style="font-size:10px;">'.$data['customer'].'</td></tr>';
                        $content.='<tr><td style="font-size:10px;">'.$data['company'].'</td></tr>';
                        $content.='<tr><td style="font-size:10px;">'.$data['customer_zipcode'].' '.$data['customer_city'].'</td></tr>';
                    $content.='</table>';
                $content.='</td>';
                $content.='<td width="50%">'; // Right
                    $content.='<table border="0" width="100%" cellspacing="0" cellpadding="0" style="font-size:10px;">';
                        $content.='<tr><td>'.lang('page_dt_customernr').': &nbsp;'.$data['registernr'].'</td></tr>';
                        $content.='<tr><td>'.lang('page_dt_consultant').': &nbsp;'.$data['responsible_user'].'</td></tr>';
                        $content.='<tr><td>'.lang('page_fl_email').': &nbsp;'.$data['customer_email'].'</td></tr>';
                        $content.='<tr><td>'.lang('page_fl_hotline').': &nbsp;'.$data['customer_email'].'</td></tr>';
                    $content.='</table>';
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
                            $content.='<tr><td></td></tr>';
                            $content.='<tr><td></td></tr>';
                            $content.='<tr><td></td></tr>';
                        $content.='</table>';
                    $content.='</td>';
                    $content.='<td width="50%">'; // Right
                        $content.='<table border="0" width="100%" cellspacing="0" cellpadding="0" style="font-size:10px;">';
                            $content.='<tr><td><h3>'.lang('page_dt_monitorjobnr').':'.$data['monitoringnr_prefix'].'</h3></td></tr>';
                            $content.='<tr><td><h3>'.lang('page_dt_date').':'.date('d-m-Y').'</h3></td></tr>';
                        $content.='</table>';
                    $content.='</td>';
                $content.='</tr>';
            $content.='</table>';
    $content.='</td></tr>';

    $content.='<tr><td><h4>'.lang('page_dt_following_addition').'</h4></td></tr>';

    $content.='<tr><td></td></tr>';


    $content.='<tr><td>';
        $content.='<table width="100%" cellspacing="0" cellpadding="2">';
            $content.='<tr>';
                $content.='<td width="100%" style="'.$background_color_th.'">';
                     $content.='<table border="0" width="80%" cellspacing="0" cellpadding="0" style="font-size:12px;">';
                        $content.='<tr>';
                            $content.= '<th style="color:#fff;">'.lang('page_dt_call_number').'</th>';
                            $content.= '<th style="color:#fff;">'.lang('page_dt_amount').'</th>';
                            $content.= '<th style="color:#fff;">'.lang('page_dt_additional_cost').'</th>';
                        $content.= '</tr>';
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
                    $content.='<table border="0" width="80%" cellspacing="0" cellpadding="2" style="font-size:12px;">';
                        if(isset($data['additional_costs']) && !empty($data['additional_costs'])){
                            foreach ($data['additional_costs'] as $key => $val) {
                                $content.='<tr>';
                                    $content.='<td style="border-bottom:1px solid #9E9E9E;">'.$val['invoiceitem'].'</td>';
                                    $content.='<td style="border-bottom:1px solid #9E9E9E;">'.$val['costincurredbyname'].'</td>';
                                    $content.='<td style="border-bottom:1px solid #9E9E9E;">'.$val['invoicetotal'].'</td>';
                                $content.='</tr>';
                            }
                        }
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
                   $content.='<table border="0" width="80%" cellspacing="0" cellpadding="0" style="font-size:12px;">';
                        $content.='<tr>';
                            $content.= '<th style="color:#fff;">'.lang('page_dt_unused_participants').'</th>';
                        $content.='</tr>';
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
                    $content.='<table border="0" width="100%" cellspacing="0" cellpadding="2" style="font-size:12px;">';
                    if(isset($monitoringcsvData) && !empty($monitoringcsvData)) {
                        foreach ($monitoringcsvData as $key => $val) {
                            $content.='<tr><td style="border-bottom:1px solid #9E9E9E"><b>'.$val['mobilenr'].'</b></td></tr>';
                        }
                    }
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
                    $content.='<table border="0" width="80%" cellspacing="0" cellpadding="0" style="font-size:12px;">';
                        $content.='<tr>';
                            $content.= '<th style="color:#fff;">'.lang('page_dt_other_remark').'</th>';
                        $content.='</tr>';
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
                    $content.='<table border="0" width="100%" cellspacing="0" cellpadding="2" style="font-size:12px;">';
                        $content.='<tr><td><p style="font-size:8px;">'.$data['additional_extracost'].'</p></td></tr>';
                    $content.='</table>';
                $content.='</td>';
            $content.='</tr>';
        $content.='</table>';
    $content.='</td></tr>';


    $content.='<tr><td></td></tr>';

    // $content.='<tr><td>';
    //     $content.='<table width="100%" cellspacing="0" cellpadding="2">';
    //         $content.='<tr>';
    //             $content.='<td width="100%" style="'.$background_color_th.'">';
    //             $content.='</td>';
    //         $content.='</tr>';
    //     $content.='</table>';
    // $content.='</td></tr>';

    // $content.='<tr><td></td></tr>';

    $content.='<tr><td>';
        $content.='<table border="0" width="95%" cellspacing="0" cellpadding="0">';
            $content.='<tr>';
                $content.='<td width="100%">'; //Left
                    $content.='<table border="0" width="100%" cellspacing="0" cellpadding="0" style="font-size:8px;">';
                        $content.='<tr><td width="100%" style="text-align:center;">';
                            $content.='Zertifzierter Business Service Partner der Vodafone GmbH - DK Deutschland GmbH - Industriestr. 10 - 59192 Bergkamen<br>
                            Amtsgericht Hamm - HRB 9693 - Geschaftsfuhrer Ozan Arac - St.Nr. 322 / 5729 / 6294 - Finanzamt Hamm<br>
                            Bankdaten: Deutsche Bank AG - IBAN DE55 4407 0024 0213 8378 00 - BIC DEUTDEDB440<br>
                            Tel.: + 49 (0) 800 200 70 999 - Fax: + 49 (0) 800 200 70 997 - www.dk-deutschland.de - kontakt@dk-deutschland.de';
                        $content .='</td></tr>';
                    $content.='</table>';
                $content.='</td>';
            $content.='</tr>';
        $content.='</table>';
    $content.='</td></tr>';

    $content.='<tr><td></td></tr>';
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
$obj_pdf->Output('QuotationConsultationProtocol-'.$data['monitoringnr'].'.pdf', 'I');
?>