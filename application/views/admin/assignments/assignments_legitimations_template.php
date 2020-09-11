<?php
$data = '<div class="row">';
foreach($legitimations as $legitimation) {
    $legitimation_url = base_url('admin/assignments/downloadLegitimation/'.$legitimation['id']);
    if(!empty($legitimation['external'])){
        $legitimation_url = $legitimation['external_link'];
    }
    $data .= '<div class="display-block assignment-legitimation-wrapper">';
    $data .= '<div class="col-md-10">';
    $data .= '<div class="pull-left"><i class="'.get_mime_class($legitimation['filetype']).'"></i></div>';
    $data .= '<a href="'.$legitimation_url.'">'.$legitimation['file_name'].'</a>';
    $data .= '<p class="text-muted">'.$legitimation["filetype"].'</p>';
    $data .= '</div>';
    $data .= '<div class="col-md-2 text-right">';
    if($legitimation['userid'] == get_user_id() && get_user_role()=='user'){
    $data .= '<a href="javascript:void(0);" class="text-danger" onclick="deleteConfirmation(\''.base_url("admin/assignments/deleteLegitimation").'\',\''.$legitimation['id'].'\',\''.lang("page_lb_delete_assignmentlegitimation").'\',\''.lang("page_lb_delete_assignmentlegitimation_info").'\',\'true\',\''.$legitimation['rel_id'].'\');"><i class="fa fa fa-times"></i></a>';
    }
    $data .= '</div>';
    $data .= '<div class="clearfix"></div>';
    $data .= '</div>';
}
$data .= '</div>';
echo $data;
