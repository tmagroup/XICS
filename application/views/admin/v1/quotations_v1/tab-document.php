<!-- BEGIN SAMPLE FORM PORTLET-->
<div class="portlet light bordered">                                
    <div class="portlet-body form">

        <div class="form-body">
            
            <div class="col-md-6">
                <div class="form-group">
                    <label><?php echo lang('page_fl_category');?> <span class="required"> * </span></label>
                    <?php echo form_dropdown('category', $categories, isset($quotation['attachments'][0]['categoryid'])?$quotation['attachments'][0]['categoryid']:'', 'class="form-control" id="documentcategory" ');?>
                </div>                                                   
            </div>
            <div class="clearfix"></div>
            
            <div class="col-md-12">
                
                <form action="<?php echo base_url('admin/quotations/uploadDocuments/'.$quotation['quotationnr']);?>" class="dropzone dropzone-file-area" id="my-dropzone" style="padding-top:40px;"></form>
                <div class="clearfix">&nbsp;</div>
                
                <div id="quotation_attachments">
                <?php if(count($quotation['attachments']) > 0) { ?>
                    <?php $this->load->view('admin/quotations/quotations_attachments_template', array('attachments'=>$quotation['attachments'])); ?>
                <?php } ?>
                </div>
                
            </div>
            
        </div>
        
    </div>
    <div class="clearfix"></div>                                                    
</div>
<!-- END SAMPLE FORM PORTLET-->