<?php
//$obj_pdf = new TCPDF('P', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
$obj_pdf = new pdf('employeecommissionslip',$data);
extract($data);

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


$obj_pdf->SetFont('helvetica', '', 9);
$obj_pdf->SetMargins(18, 58, 0);
$obj_pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
$obj_pdf->AddPage();


ob_start();
// we can have any view part here like HTML, PHP etc
//$content = ob_get_contents();

$html = '<table border="0" width="100%" cellspacing="0" cellpadding="0" style="color:#2b2b2a">';

    //Small Heading of Page
    $html.='<tr><td>';
        $html.='<table border="0" width="40%" cellspacing="0" cellpadding="0" style="font-size:6px;"><tr><td style="border-bottom:0px solid #2b2b2a;">';
        $html.=$company_business_partner.' '.$company_name.' - '.$company_address.' - '.$company_zipcode.' '.$company_city;
        $html.='</td></tr></table>';
    $html.='</td></tr>';        
    
    $html.='<tr><td>';  
        $html.='<table border="0" width="100%" cellspacing="0" cellpadding="15">';
            $html.='<tr>';            
                $html.='<td width="30%">'; //Left
                    $html.='<table border="0" cellspacing="0" cellpadding="0" style="font-size:12px;">';
                        $html.='<tr><td>'.$name.' '.$surname.'</td></tr>';
                        $html.='<tr><td>'.$street.'</td></tr>';
                        $html.='<tr><td>'.$zipcode.' '.$city.'</td></tr>';
                    $html.='</table>';
                $html.='</td>';                
                $html.='<td width="70%">'; // Right
                    $html.='<table border="0" cellspacing="0" cellpadding="0" style="font-size:10px;">';
                        $html.='<tr><td align="right">'.lang('page_user').': &nbsp;</td><td>'.$username.'</td></tr>';
                        $html.='<tr><td align="right">'.lang('page_fl_email').': &nbsp;</td><td>'.$email.'</td></tr>';
                        $html.='<tr><td align="right">'.lang('page_fl_hotline').': &nbsp;</td><td>'.get_option('company_hotline').'</td></tr>';
                    $html.='</table>';
                $html.='</td>';                
            $html.='</tr>';
        $html.='</table>';  
    $html.='</td></tr>';
    
    $html.='<tr><td>';  
        $html.='<table border="0" width="100%" cellspacing="0" cellpadding="15">';
            $html.='<tr>';            
                $html.='<td width="40%"></td>'; //Left                
                $html.='<td width="60%">'; //Right
                    $html.='<table border="0" cellspacing="0" cellpadding="0" style="font-size:12px;">';
                        $html.='<tr><th align="right"><b>'.lang('page_fl_slipnr').'.:</b> &nbsp;</th><th><b>'.$slipnr_prefix.'</b></th></tr>';
                        $html.='<tr><th align="right"><b>'.lang('page_fl_date').':</b> &nbsp;</th><th><b>'._d($date).'</b></th></tr>';
                    $html.='</table>';
                $html.='</td>';                
            $html.='</tr>';
        $html.='</table>';  
    $html.='</td></tr>';
    
    //Row
    $html.='<tr><td style="font-size:12px;">';  
        $html.='<table border="0" width="100%" cellspacing="0" cellpadding="2">';   
            $html.='<tr><th style="background-color:#e42225;">&nbsp;&nbsp;<b>'.lang('page_fl_period').'</b></th><th style="background-color:#e42225;"><b>'.lang('page_fl_pointsvalue').'</b></th></tr>';            
            $html.='<tr><td>&nbsp;&nbsp;<b>'.date('M-Y',strtotime($period."-1")).'</b></td><td><b>'.$pointsvalue.'</b></td></tr>';                        
        $html.='</table>';  
    $html.='</td></tr>';
        
$html.= '</table>';

ob_end_clean();
$obj_pdf->writeHTML($html, true, false, true, false, '');

//Create Folder Userwise
_maybe_create_upload_path(FCPATH.'uploads/commision_slips/'.$userid.'/');
if(is_dir(FCPATH.'uploads/commision_slips/'.$userid.'/')){
    $period = date('M-Y',strtotime($period."-01"));    
    $obj_pdf->Output(FCPATH.'uploads/commision_slips/'.$userid.'/CommisionSlip-'.$slipnr_prefix.'-'.$period.'.pdf', 'F');
}    
?>