<?php
//$obj_pdf = new TCPDF('P', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
$obj_pdf = new pdf('printquotation',$data);
/*$obj_pdf->SetCreator(PDF_CREATOR);
$title = "PDF Report";
$obj_pdf->SetTitle($title);
$obj_pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, $title, PDF_HEADER_STRING);
$obj_pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$obj_pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
$obj_pdf->SetDefaultMonospacedFont('helvetica');
$obj_pdf->SetHeaderMargin(0);
$obj_pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
$obj_pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$obj_pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
$obj_pdf->SetFont('helvetica', '', 9);
$obj_pdf->setFontSubsetting(false);*/

$title = lang('page_lb_print_quotation');
$obj_pdf->SetTitle($title);
$obj_pdf->SetFont('helvetica', '', 9);
$obj_pdf->SetMargins(18, 50, 0);
$obj_pdf->SetAutoPageBreak(TRUE, 55);
$obj_pdf->AddPage('L', 'A4');

ob_start();
// we can have any view part here like HTML, PHP etc
//$content = ob_get_contents();

$total_price1 = 0;
$total_price2 = 0;

$content= '<style>.first-td-color { background-color:#e60000; } .second-td-color, .activationdate { background-color:#a6a6a6; } .providerdescription-td { background-color:#a6a6a6; } .total-td { background-color:#a6a6a6; } .final-total-td { background-color:#a6a6a6; color:#008037; }</style>';
if ($data['quotation']['provider'] == 'Telekom') {
    $content= '<style> .first-td-color { background-color:#808080; } .second-td-color { background-color:#e26ac5; } .providerdescription-td { background-color:#808080; } .total-td { background-color:#808080; } .final-total-td { background-color:#808080; color:#FFFFFF; }</style>';

} else if ($data['quotation']['provider'] == 'o2Business') {
    $content= '<style> .first-td-color { background-color:#bfbfbf; } .second-td-color { background-color:#7030a0; color: #FFFFFF; } .providerdescription-td { background-color:#bfbfbf; } .total-td { background-color:#bfbfbf; } .final-total-td { background-color:#002060; color:#FFFFFF; }</style>';
}

$content.= '<table border="0" width="100%" cellspacing="0" cellpadding="0" style="font-size:6px; font-weight:bold;">';
    $content.='<tr>';

        //Quotation Products
        $content.='<td width="80%">';
            $content.='<table width="100%" cellspacing="0" cellpadding="1" class="table-bordered">';

                $content.='<thead><tr>';
                    $content.='<th align="center" width="5%">'.lang('page_quotation_pdf_lfdnr').'</th>';
                    $content.='<th align="center" width="10%">'.lang('page_quotation_pdf_mobilenr').'</th>';
                    $content.='<th align="center" width="15%">'.lang('page_quotation_pdf_currentratemobile').'</th>';
                    $content.='<th align="center" width="8%" class="first-td-color">'.lang('page_quotation_pdf_currentratemobilevalue').'</th>';
                    $content.='<th align="center" width="8%" class="first-td-color">'.lang('page_quotation_pdf_currentratemobileusing').'</th>';
                    $content.='<th align="center" width="8%" class="first-td-color">'.lang('page_quotation_pdf_currentoptionmobilevalue').'</th>';
                    $content.='<th align="center" width="14%">'.lang('page_quotation_pdf_newratemobile').'</th>';
                    $content.='<th align="center" width="8%" class="second-td-color">'.lang('page_quotation_pdf_newratemobilevalue').'</th>';
                    $content.='<th align="center" width="7%" class="second-td-color">'.lang('page_quotation_pdf_newoptionmobile').'</th>';
                    $content.='<th align="center" width="8%" class="second-td-color">'.lang('page_quotation_pdf_newoptionmobilevalue').'</th>';
                    $content.='<th align="center" width="9%" class="activationdate">'.lang('page_quotation_pdf_activationdate').'</th>';
                $content.='</tr></thead>';

                $content.='<tbody>';
                    $total_price_1 = $total_price_2 = 0;
                    foreach($data['quotationproducts'] as $r=>$quotationproduct){

                        $total_price_1 = $total_price_1 + ($quotationproduct['value1']+$quotationproduct['use']+$quotationproduct['value3']);
                        $total_price_2 = $total_price_2 + ($quotationproduct['value2']+$quotationproduct['value4']);
                        if($quotationproduct['activationdate']=="" || $quotationproduct['activationdate']=="0000-00-00"){ $activationdate=lang('page_lb_sofort'); }else{ $activationdate = _d($quotationproduct['activationdate']); }

                        $content.='<tr>';
                            $content.='<td align="center" width="5%">'.($r+1).'</td>';
                            $content.='<td align="center" width="10%">'.$quotationproduct['mobilenr'].'</td>';
                            $content.='<td align="left" width="15%">'.$quotationproduct['currentratemobile'].'</td>';
                            $content.='<td align="center" width="8%" class="first-td-color">'.format_money($quotationproduct['value1'],"&nbsp;".$GLOBALS['currency_data']['currency_symbol']).'</td>';
                            $content.='<td align="center" width="8%" class="first-td-color">'.format_money($quotationproduct['use'],"&nbsp;".$GLOBALS['currency_data']['currency_symbol']).'</td>';
                            $content.='<td align="center" width="8%" class="first-td-color">'.format_money($quotationproduct['value3'],"&nbsp;".$GLOBALS['currency_data']['currency_symbol']).'</td>';
                            $content.='<td align="left" width="14%">'.$quotationproduct['newratemobile'].'</td>';
                            $content.='<td align="center" width="8%" class="second-td-color">'.format_money($quotationproduct['value2'],"&nbsp;".$GLOBALS['currency_data']['currency_symbol']).'</td>';
                            $content.='<td align="center" width="7%" class="second-td-color">'.$quotationproduct['newoptionmobile'].'</td>';
                            $content.='<td align="center" width="8%" class="second-td-color">'.format_money($quotationproduct['value4'],"&nbsp;".$GLOBALS['currency_data']['currency_symbol']).'</td>';
                            $content.='<td align="center" width="9%" class="activationdate">'.$activationdate.'</td>';
                        $content.='</tr>';
                    }

                $content.='</tbody>';

            $content.='</table>';


            //Total Price 1, Total Price 2
            $content.='<br /><br /><table width="100%" cellspacing="0" cellpadding="1" class="table-bordered">';

                $content.='<thead><tr>';
                    $content.='<th align="center" width="5%" class="none_border"></th>';
                    $content.='<th align="center" width="10%" class="none_border"></th>';
                    $content.='<th align="center" width="15%" class="first-td-color" style="border-right:none;"></th>';
                    $content.='<th align="left" width="16%" colspan="2" class="none_border topbottomborder first-td-color">'.lang('page_quotation_pdf_currenttotalvalue').':</th>';
                    $content.='<th align="center" width="8%" class="none_border topbottomborder rightborder first-td-color">'.format_money($total_price_1,"&nbsp;".$GLOBALS['currency_data']['currency_symbol']).'</th>';
                    $content.='<th align="center" width="22%" colspan="2" class="none_border topbottomborder rightborder total-td"></th>';
                    if ($data['quotation']['provider'] == 'Telekom' || $data['quotation']['provider'] == 'o2Business') {
                        $content.='<th align="left" width="7%" class="none_border topbottomborder total-td">'.lang('page_quotation_pdf_newtotalvalue').':</th>';
                        $content.='<th align="center" width="8%" class="none_border topbottomborder rightborder total-td">'.format_money($total_price_2,"&nbsp;".$GLOBALS['currency_data']['currency_symbol']).'</th>';
                        $content.='<th align="center" width="9%" class="none_border"></th>';

                    } else {
                        $content.='<th align="left" width="15%" class="none_border topbottomborder total-td">'.lang('page_quotation_pdf_newtotalvalue').':</th>';
                        $content.='<th align="center" width="9%" class="none_border topbottomborder rightborder total-td">'.format_money($total_price_2,"&nbsp;".$GLOBALS['currency_data']['currency_symbol']).'</th>';
                    }

                $content.='</tr></thead>';

            $content.='</table>';



            //Total Price 3 = (Total Price 1 – Total Price 2) * 24
            $total_price_3 = ($total_price_1 - $total_price_2)*QUOTATION_TOTAL_PRICE3_MONTHS;
            $content.='<br /><br /><table width="100%" cellspacing="0" cellpadding="1" class="table-bordered">';

                $content.='<thead><tr>';
                    $content.='<th align="center" width="5%" class="none_border"></th>';
                    $content.='<th align="center" width="10%" class="none_border"></th>';
                    if ($data['quotation']['provider'] != 'Telekom' && $data['quotation']['provider'] != 'o2Business') {
                        $content.='<th width="31%" colspan="4" class="none_border"></th>';
                    } else {
                        $content.='<th width="31%" rowspan="3" colspan="4" class="none_border"><img height="100" src="assets/pages/img/deutschland-logo.png" /></th>';
                    }
                    $content.='<th align="center" width="8%" class="none_border"></th>';

                    $content.='<th align="center" width="22%" colspan="2" class="final-total-td"></th>';
                    if ($data['quotation']['provider'] == 'Telekom' || $data['quotation']['provider'] == 'o2Business') {
                        $content.='<th align="left" width="7%" class="none_border topbottomborder final-total-td">'.lang('page_quotation_pdf_currentnewtotalvalue').':</th>';
                        $content.='<th align="center" width="8%" class="none_border topbottomborder rightborder final-total-td">'.format_money($total_price_3,"&nbsp;".$GLOBALS['currency_data']['currency_symbol']).'</th>';
                        $content.='<th align="center" width="9%" class="none_border"></th>';

                    } else {
                        $content.='<th align="left" width="15%" class="none_border topbottomborder final-total-td">'.lang('page_quotation_pdf_currentnewtotalvalue').':</th>';
                        $content.='<th align="center" width="9%" class="none_border topbottomborder rightborder final-total-td">'.format_money($total_price_3,"&nbsp;".$GLOBALS['currency_data']['currency_symbol']).'</th>';
                    }

                $content.='</tr></thead>';

            $content.='</table>';

            //Date and Signature
            $content.='<br /><br /><br /><br /><br /><br /><table width="100%" cellspacing="0" cellpadding="1" class="table-bordered">';

                $content.='<thead><tr>';
                    $content.='<th align="center" width="5%" class="none_border"></th>';
                    $content.='<th align="center" width="10%" class="none_border"></th>';
                    $content.='<th align="center" width="15%" class="none_border"></th>';
                    $content.='<th align="left" width="16%" colspan="2" class="none_border"></th>';
                    $content.='<th align="center" width="8%" class="none_border"></th>';
                    $content.='<th align="left" width="15%" colspan="2" class="none_border topborder">'.lang('page_quotation_pdf_city').', '.lang('page_quotation_pdf_date').'</th>';
                    $content.='<th align="center" width="8%" class="none_border"></th>';
                    $content.='<th align="left" width="15%" class="none_border topborder">'.lang('page_quotation_pdf_signature').'/'.lang('page_quotation_pdf_stampcustomer').'</th>';
                    $content.='<th align="center" width="8%" class="none_border"></th>';
                $content.='</tr></thead>';

            $content.='</table>';

            if ($data['quotation']['provider'] == 'Telekom' || $data['quotation']['provider'] == 'o2Business') {
                $content.='<br /><br /><br /><br /><table width="100%" cellspacing="0" cellpadding="1">';

                    $content.='<thead><tr>';
                        $content.='<th align="center" width="54%" colspan="2" class="none_border"></th>';
                        $content.='<th align="left" width="46%" colspan="5" class="none_border">Mit meiner Unterschrift erteile ich der DK Deutschland GmbH die Tarifanpassung, den Tarifwechsel, die Vertragsverlängerungen <br />
                        und die Neuaktivierungen wie oben in der Tabelle dargestellt. Dieses Formular ist ein Teil der Auftragserteilung und <br />
                        nur gültig in Verbindung mit dem Beratungsprotokoll. Gleichzeitig akzeptiere ich die AGBs der DK Deutschland GmbH</th>';
                    $content.='</tr></thead>';

                $content.='</table>';
            }

        $content.='</td>';


        //Red Business Logo
        $content.='<td width="13%">';
            $content.='<table border="0" width="100%" cellspacing="0" cellpadding="3" class="table-bordered">';
                $content.='<tr>';
                    $content.='<td style="font-weight:normal; font-style:italic; height:100px;" class="providerdescription-td" >'.lang('page_quotation_pdf_providerdescription').'</td>';
                $content.='</tr>';
                if ($data['quotation']['provider'] != 'Telekom' && $data['quotation']['provider'] != 'o2Business') {
                    $content.='<tr>';
                        $content.='<td align="center" class="none_border"><img style="width:70px;" src="assets/pages/img/red-businesslogo.jpg" /></td>';
                    $content.='</tr>';
                }
            $content.='</table>';
        $content.='</td>';

    $content.='</tr>';
$content.='</table>';

$content = mb_convert_encoding($content, 'UTF-8', 'UTF-8');
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
$obj_pdf->Output('Quotation-'.$data['quotation']['quotationnr'].'.pdf', 'I');
?>