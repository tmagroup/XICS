<!-- BEGIN SAMPLE FORM PORTLET-->
<div class="portlet light bordered">                                
    <div class="portlet-body form">

        <div class="form-body">
            <?php
            if(get_user_role()=='user' && isset($GLOBALS['current_user']->userrole) && $GLOBALS['current_user']->userrole!=6){
                ?>
                <div class="col-md-6">
                    <div class="form-group">
                        <label><?php echo lang('page_fl_category');?> <span class="required"> * </span></label>
                        <?php echo form_dropdown('category', $categories, isset($assignment['attachments'][0]['categoryid'])?$assignment['attachments'][0]['categoryid']:'', 'class="form-control" id="documentcategory" ');?>
                    </div>                                                   
                </div>
                <div class="clearfix"></div>
                <?php
            }
            ?>
            <div class="col-md-12">
                <?php
                if(get_user_role()=='user' && isset($GLOBALS['current_user']->userrole) && $GLOBALS['current_user']->userrole!=6){
                    ?>
                    <form action="<?php echo base_url('admin/assignments/uploadDocuments/'.$assignment['assignmentnr']);?>" class="dropzone dropzone-file-area dz-clickable" id="my-dropzone" style="padding-top:40px;"></form>
                    <div class="clearfix">&nbsp;</div>
                    <?php
                }
                ?>
                <div id="assignment_attachments">
                <?php if(count($assignment['attachments']) > 0) { ?>
                    <?php $this->load->view('admin/assignments/assignments_attachments_template', array('attachments'=>$assignment['attachments'])); ?>
                <?php } ?>
                </div>
                
            </div>
            
        </div>
        
    </div>
    <div class="clearfix"></div>                                                    
</div>
<!-- END SAMPLE FORM PORTLET-->