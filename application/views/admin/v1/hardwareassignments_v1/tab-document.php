<!-- BEGIN SAMPLE FORM PORTLET-->
<div class="portlet light bordered">                                
    <div class="portlet-body form">

        <div class="form-body">
            <?php
            if(get_user_role()=='user'){
                ?>
                <div class="col-md-6">
                    <div class="form-group">
                        <label><?php echo lang('page_fl_category');?> <span class="required"> * </span></label>
                        <?php echo form_dropdown('category', $categories, isset($hardwareassignment['attachments'][0]['categoryid'])?$hardwareassignment['attachments'][0]['categoryid']:'', 'class="form-control" id="documentcategory" ');?>
                    </div>                                                   
                </div>
                <div class="clearfix"></div>
                <?php
            }
            ?>
            <div class="col-md-12">
                <?php
                if(get_user_role()=='user'){
                    ?>
                    <form action="<?php echo base_url('admin/hardwareassignments/uploadDocuments/'.$hardwareassignment['hardwareassignmentnr']);?>" class="dropzone dropzone-file-area dz-clickable" id="my-dropzone" style="padding-top:40px;"></form>
                    <div class="clearfix">&nbsp;</div>
                    <?php
                }
                ?>
                <div id="hardwareassignment_attachments">
                <?php if(count($hardwareassignment['attachments']) > 0) { ?>
                    <?php $this->load->view('admin/hardwareassignments/hardwareassignments_attachments_template', array('attachments'=>$hardwareassignment['attachments'])); ?>
                <?php } ?>
                </div>
                
            </div>
            
        </div>
        
    </div>
    <div class="clearfix"></div>                                                    
</div>
<!-- END SAMPLE FORM PORTLET-->