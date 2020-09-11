<!-- BEGIN SAMPLE FORM PORTLET-->
<div class="portlet light bordered">                                
    <div class="portlet-body form">

        <div class="form-body">
            
            <div class="col-md-12">
                
                <?php
                //if(get_user_role()=='user' && ($GLOBALS['assignment_permission']['create'] || $GLOBALS['assignment_permission']['edit']) && $assignment['userid'] == get_user_id()){
                
                if(get_user_role()=='customer' || (get_user_role()=='user' && ($GLOBALS['current_user']->userrole==1 || $GLOBALS['current_user']->userrole==2 || $GLOBALS['current_user']->userrole==3) )){
                    ?>
                    <form action="<?php echo base_url('admin/assignments/uploadLegitimations/'.$assignment['assignmentnr']);?>" class="dropzone dropzone-file-area dz-clickable" id="my-dropzone" style="padding-top:40px;"></form>
                    <div class="clearfix">&nbsp;</div>
                    <?php
                }
                ?>
                
                <div id="assignment_legitimations">
                <?php if(count($assignment['legitimations']) > 0) { ?>
                    <?php $this->load->view('admin/assignments/assignments_legitimations_template', array('legitimations'=>$assignment['legitimations'])); ?>
                <?php } ?>
                </div>
                
            </div>
            
        </div>
        
    </div>
    <div class="clearfix"></div>                                                    
</div>
<!-- END SAMPLE FORM PORTLET-->