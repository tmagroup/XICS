<?php
//$obj_pdf = new TCPDF('P', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
extract($data);

$hardware_calculate_total = 0;
foreach($quotationproducts as $quotationproduct){
    if(!$quotationproduct['hardware']){  continue; }

    $hardware_calculate_value = 0;
    $commission_value = 0;

    /**************************************************************************************/
    /*** Calculate Hardware Price */
    /**************************************************************************************/
    //Extra Fields of RateMobile
    $extrafields = $this->Field_model->get('ratemobile',$quotationproduct['newratenr']);
    foreach($extrafields as $fkey=>$extrafield){
        foreach($extrafield as $fkey2=>$fvalue2){
            $extrafields[$fkey][trim($fkey2)] = trim($fvalue2);
        }
    }

    //There we choose from where we Select “Mobile Rate 2” in each added Product in Quotation.
    if(strtolower(trim($quotationproduct['vvlneu']))=='neu' && strtolower(trim($quotationproduct['subname']))=='nein'){
        //Commision = Value of PV190000SO
        $array_column = array_column($extrafields, 'field_name');
        $fkey = array_search('PV'.$quotation['newdiscounttitle'].'SO', $array_column);
        $commission_value = $extrafields[$fkey]['field_value'];
    }
    else if(strtolower(trim($quotationproduct['vvlneu']))=='vvl' && strtolower(trim($quotationproduct['subname']))=='nein'){
        //Commision = Value of PV190000VVL
        $array_column = array_column($extrafields, 'field_name');
        $fkey = array_search('PV'.$quotation['newdiscounttitle'].'VVL', $array_column);
        $commission_value = $extrafields[$fkey]['field_value'];
    }
    else if(strtolower(trim($quotationproduct['vvlneu']))=='vvl' && strtolower(trim($quotationproduct['subname']))=='ja'){
        //Commision = Value of PV190000VVL
        $array_column = array_column($extrafields, 'field_name');
        $fkey = array_search('PV'.$quotation['newdiscounttitle'].'VVL', $array_column);
        $commission_value = $extrafields[$fkey]['field_value'];
    }
    else if(strtolower(trim($quotationproduct['vvlneu']))=='neu' && strtolower(trim($quotationproduct['subname']))=='ja'){
        //Commision = Value of PV190000SUB
        $array_column = array_column($extrafields, 'field_name');
        $fkey = array_search('PV'.$quotation['newdiscounttitle'].'SUB', $array_column);
        $commission_value = $extrafields[$fkey]['field_value'];
    }

    if(($commission_value - $quotationproduct['hardwareprice'])>=99){
        //Value 6 = 1,00 €
        $hardware_calculate_value = 1;
    }
    else if(($commission_value - $quotationproduct['hardwareprice'])<99){
        //Value 6 = (Commision-Hardwareprice) *(-1) + 99,00 €
        $hardware_calculate_value = (($commission_value - $quotationproduct['hardwareprice'])*(-1)) + 99;
    }

    //Total
    $hardware_calculate_total = $hardware_calculate_total + $hardware_calculate_value;
    /**************************************************************************************/
}
$data['hardware_calculate_total'] = $hardware_calculate_total;
$obj_pdf = new pdf('printhardwarequotation',$data);

$title = lang('page_lb_print_hardware_quotation');
$obj_pdf->SetTitle($title);
$obj_pdf->SetFont('helvetica', '', 9);
$obj_pdf->SetMargins(18, 58, 0);
$obj_pdf->SetAutoPageBreak(TRUE, 60);
$obj_pdf->AddPage();

ob_start();
// we can have any view part here like HTML, PHP etc
//$content = ob_get_contents();

$background_color_th = '#e42225';
if ($data['quotation']['provider'] == 'Telekom') {
    $company_business_partner = $company_name.' '.$company_business_partner_telekom;
    $background_color_th = '#5f696a';

} else if ($data['quotation']['provider'] == 'o2Business') {
    $company_business_partner = $company_name.' '.$company_business_partner_o2;
    $background_color_th = '#143066';

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
                        $content.='<tr><td>'.$quotation['customer_company'].'</td></tr>';
                        $content.='<tr><td>'.$quotation['customer_street'].'</td></tr>';
                        $content.='<tr><td>'.$quotation['customer_zipcode'].' '.$quotation['customer_city'].'</td></tr>';
                    $content.='</table>';
                $content.='</td>';
                $content.='<td width="60%">'; // Right
                    $content.='<table border="0" cellspacing="0" cellpadding="0" style="font-size:10px;">';
                        $content.='<tr><td align="right">'.lang('page_dt_customernr').': &nbsp;</td><td>'.$quotation['customernr_prefix'].'</td></tr>';
                        $content.='<tr><td align="right">'.lang('page_hardware_quotation_pdf_responsible').': &nbsp;</td><td>'.$quotation['responsible'].'</td></tr>';
                        $content.='<tr><td align="right">'.lang('page_fl_email').': &nbsp;</td><td>'.$quotation['responsible_email'].'</td></tr>';
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
                        $content.='<tr><th align="right"><b>'.lang('page_dt_quotationnr').'.:</b> &nbsp;</th><th><b>'.$quotation['quotationnr_prefix'].'</b></th></tr>';
                        $content.='<tr><th align="right"><b>'.lang('page_fl_date').':</b> &nbsp;</th><th><b>'._d($quotation['quotationdate']).'</b></th></tr>';
                    $content.='</table>';
                $content.='</td>';
            $content.='</tr>';
        $content.='</table>';
    $content.='</td></tr>';

    //Row
    $content.='<tr><td style="font-size:10px;">';
        $content.='<table border="0" width="100%" cellspacing="0" cellpadding="3" class="table-bordered">';
            $content.='<tr><th style="background-color:'.$background_color_th.'; color:#ffffff;" class="none_border" width="33.33%">&nbsp;&nbsp;<b>'.lang('page_fl_hardware').'</b></th><th style="background-color:'.$background_color_th.'; color:#ffffff;" class="none_border" width="33.33%"><b>'.lang('page_quotation_pdf_mobilenr').'.</b></th><th style="background-color:'.$background_color_th.'; color:#ffffff;" class="none_border" width="33.33%"><b>'.lang('page_hardware_quotation_pdf_payment').'</b></th></tr>';

            $hardware_calculate_total = 0;
            foreach($quotationproducts as $quotationproduct){
                if(!$quotationproduct['hardware']){  continue; }

                $hardware_calculate_value = 0;
                $commission_value = 0;

                //Now Calculate of Hardware Price
                /*
                Value 6 will calculate in this way >>
                if (Commision – Hardwareprice>=99)
                Value 6 = 1,00 €
                if (Commision – Hardwareprice<99)
                Value 6 = (Commision-Hardwareprice) *(-1) + 99,00 €

                There we choose from where we Select “Mobile Rate 2” in each added Product in Quotation.
                When in this Product is “VVL/Neu” is Selected >> “Neu” and Sub is >> “Nein” than >>
                Commision = Value of PV190000SO
                When in this Product is “VVL/Neu” is Selected >> “VVL” and Sub is >> “Nein” than >>
                Commision = Value of PV190000VVL
                When in this Product is “VVL/Neu” is Selected >> “VVL” and Sub is >> “JA” than >>
                Commision = Value of PV190000VVL
                When in this Product is “VVL/Neu” is Selected >> “NEU” and Sub is >> “JA” than >>
                Commision = Value of PV190000SUB
                 */

                /**************************************************************************************/
                /*** Calculate Hardware Price */
                /**************************************************************************************/
                //Extra Fields of RateMobile
                $extrafields = $this->Field_model->get('ratemobile',$quotationproduct['newratenr']);
                foreach($extrafields as $fkey=>$extrafield){
                    foreach($extrafield as $fkey2=>$fvalue2){
                        $extrafields[$fkey][trim($fkey2)] = trim($fvalue2);
                    }
                }

                //There we choose from where we Select “Mobile Rate 2” in each added Product in Quotation.
                if(strtolower(trim($quotationproduct['vvlneu']))=='neu' && strtolower(trim($quotationproduct['subname']))=='nein'){
                    //Commision = Value of PV190000SO
                    $array_column = array_column($extrafields, 'field_name');
                    $fkey = array_search('PV'.$quotation['newdiscounttitle'].'SO', $array_column);
                    $commission_value = $extrafields[$fkey]['field_value'];
                }
                else if(strtolower(trim($quotationproduct['vvlneu']))=='vvl' && strtolower(trim($quotationproduct['subname']))=='nein'){
                    //Commision = Value of PV190000VVL
                    $array_column = array_column($extrafields, 'field_name');
                    $fkey = array_search('PV'.$quotation['newdiscounttitle'].'VVL', $array_column);
                    $commission_value = $extrafields[$fkey]['field_value'];
                }
                else if(strtolower(trim($quotationproduct['vvlneu']))=='vvl' && strtolower(trim($quotationproduct['subname']))=='ja'){
                    //Commision = Value of PV190000VVL
                    $array_column = array_column($extrafields, 'field_name');
                    $fkey = array_search('PV'.$quotation['newdiscounttitle'].'VVL', $array_column);
                    $commission_value = $extrafields[$fkey]['field_value'];
                }
                else if(strtolower(trim($quotationproduct['vvlneu']))=='neu' && strtolower(trim($quotationproduct['subname']))=='ja'){
                    //Commision = Value of PV190000SUB
                    $array_column = array_column($extrafields, 'field_name');
                    $fkey = array_search('PV'.$quotation['newdiscounttitle'].'SUB', $array_column);
                    $commission_value = $extrafields[$fkey]['field_value'];
                }

                if(($commission_value - $quotationproduct['hardwareprice'])>=99){
                    //Value 6 = 1,00 €
                    $hardware_calculate_value = 1;
                }
                else if(($commission_value - $quotationproduct['hardwareprice'])<99){
                    // Value 6 = (Commision-Hardwareprice) *(-1) + 99,00 €
                    $hardware_calculate_value = (($commission_value - $quotationproduct['hardwareprice'])*(-1)) + 99;
                }

                //Total
                $hardware_calculate_total = $hardware_calculate_total + $hardware_calculate_value;
                /**************************************************************************************/

                $content.='<tr><td class="none_border" style="border-bottom:0px solid #000;" width="33.33%">&nbsp;&nbsp;'.$quotationproduct['hardware'].'</td><td class="none_border" style="border-bottom:0px solid #000;" width="33.33%">'.$quotationproduct['mobilenr'].'</td><td class="none_border" style="border-bottom:0px solid #000;" width="15%">'.format_money($hardware_calculate_value,"&nbsp;".$currency_name).'</td></tr>';
            }

            $content.='<tr><td colspan="3" style="font-size:8px;" class="none_border">&nbsp;&nbsp;'.lang('page_hardware_quotation_pdf_offerline').'</td></tr>';

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
$obj_pdf->Output('HardwareQuotation-'.$data['quotation']['quotationnr'].'.pdf', 'I');
?>