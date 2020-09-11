<?php
$data = '<div class="row">';
foreach($attachments as $attachment) {
    $attachment_url = base_url('admin/quotations/downloadDocument/'.$attachment['id']);
    if(!empty($attachment['external'])){
        $attachment_url = $attachment['external_link'];
    }
    $data .= '<div class="display-block quotation-attachment-wrapper">';
    $data .= '<div class="col-md-10">';
    $data .= '<div class="pull-left"><i class="'.get_mime_class($attachment['filetype']).'"></i></div>';
    $data .= '<a href="'.$attachment_url.'">'.$attachment['file_name'].' ('.$attachment["categoryname"].')</a>';
    $data .= '<p class="text-muted">'.$attachment["filetype"].'</p>';
    $data .= '</div>';
    $data .= '<div class="col-md-2 text-right">';
    if($attachment['userid'] == get_user_id()){
    $data .= '<a href="javascript:void(0);" class="text-danger" onclick="deleteConfirmation(\''.base_url("admin/quotations/deleteDocument").'\',\''.$attachment['id'].'\',\''.lang("page_lb_delete_quotationdocument").'\',\''.lang("page_lb_delete_quotationdocument_info").'\',\'true\');"><i class="fa fa fa-times"></i></a>';
    }
    $data .= '</div>';
    $data .= '<div class="clearfix"></div>';
    $data .= '</div>';
}
$data .= '</div>';
echo $data;
