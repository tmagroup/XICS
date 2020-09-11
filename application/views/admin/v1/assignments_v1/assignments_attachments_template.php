<?php
$data = '<div class="row">';
foreach($attachments as $attachment) {
    $attachment_url = base_url('admin/assignments/downloadDocument/'.$attachment['id']);
    if(!empty($attachment['external'])){
        $attachment_url = $attachment['external_link'];
    }
    $data .= '<div class="display-block assignment-attachment-wrapper">';
    $data .= '<div class="col-md-10">';
    $data .= '<div class="pull-left"><i class="'.get_mime_class($attachment['filetype']).'"></i></div>';
    $data .= '<a href="'.$attachment_url.'">'.$attachment['file_name'].' ('.$attachment["categoryname"].')</a>';
    $data .= '<p class="text-muted">'.$attachment["filetype"].'</p>';
    $data .= '</div>';
    $data .= '<div class="col-md-2 text-right">';
    if($attachment['userid'] == get_user_id() && get_user_role()=='user'){
    $data .= '<a href="javascript:void(0);" class="text-danger" onclick="deleteConfirmation(\''.base_url("admin/assignments/deleteDocument").'\',\''.$attachment['id'].'\',\''.lang("page_lb_delete_assignmentdocument").'\',\''.lang("page_lb_delete_assignmentdocument_info").'\',\'true\');"><i class="fa fa fa-times"></i></a>';
    }
    $data .= '</div>';
    $data .= '<div class="clearfix"></div>';
    $data .= '</div>';
}
$data .= '</div>';
echo $data;
