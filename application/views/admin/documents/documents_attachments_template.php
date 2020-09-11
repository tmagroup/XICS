<style>
.docuploadedby{
    padding: 4px 9px 5px 4px;
    text-align: right;
    font-style: italic;
    color: #999;
    font-size: 12px;
}
</style>

<?php
$data = '<div class="row">';
foreach($attachments as $attachment) {
    $attachment_url = base_url('admin/documents/downloadDocument/'.$attachment['id']);
    if(!empty($attachment['external'])){
        $attachment_url = $attachment['external_link'];
    }
    $data .= '<div class="display-block document-attachment-wrapper">';
    $data .= '<div class="col-md-7">';
    $data .= '<div class="pull-left"><i class="'.get_mime_class($attachment['filetype']).'"></i></div>';
    $data .= '<a href="'.$attachment_url.'">'.$attachment['file_name'].' ('.$attachment["categoryname"].')</a>';
    $data .= '<p class="text-muted">'.$attachment["filetype"].'</p>';
    $data .= '</div>';
    $data .= '<div class="col-md-5 text-right">';
    
    //Admin and Salesmanager should be see a Menu "Dokumente" too. Where you see all uploaded files from each User. It must be able to delete files too from Admin and Salesmanager.
    if(($GLOBALS['current_user']->userrole==1 || $GLOBALS['current_user']->userrole==2) && $GLOBALS['document_permission']['delete']){        
        $data .= '<span class="docuploadedby">'.lang('page_lb_uploaded_by').': '.$attachment["uploaded_by"].'</span>';
    }
    
    if($GLOBALS['document_permission']['delete']){
        $data .= '<a href="javascript:void(0);" class="text-danger" onclick="deleteConfirmation(\''.base_url("admin/documents/deleteDocument").'\',\''.$attachment['id'].'\',\''.lang("page_lb_delete_document").'\',\''.lang("page_lb_delete_document_info").'\',\'true\');"><i class="fa fa fa-times"></i></a>';
    }
    /*else if($attachment['userid'] == get_user_id()){        
        $data .= '<a href="javascript:void(0);" class="text-danger" onclick="deleteConfirmation(\''.base_url("admin/documents/deleteDocument").'\',\''.$attachment['id'].'\',\''.lang("page_lb_delete_document").'\',\''.lang("page_lb_delete_document_info").'\',\'true\');"><i class="fa fa fa-times"></i></a>';    
    }*/
    
    $data .= '</div>';
    $data .= '<div class="clearfix"></div>';
    $data .= '</div>';
}
$data .= '</div>';
echo $data;