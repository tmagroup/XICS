<div class="portlet-title">
    <div class="caption font-dark">
        <i class="icon-bubble font-green-sharp"></i>
        <span class="caption-subject font-green-sharp sbold"><?php echo lang('page_comments');?></span>
    </div>
    <?php
    if($GLOBALS['customercomment_permission']['create']){
        ?>
        <div class="actions">
            <div class="btn-group btn-group-devided" data-toggle="buttons">
                <a href="javascript:void(0);" id="addcommentbtn" class="btn sbold green btn-sm btn-circle"><?php echo lang('page_create_comment');?> <i class="fa fa-angle-down"></i></a>
            </div>
        </div>
        <?php
    }
    ?>
</div>
    
<?php
if($GLOBALS['customercomment_permission']['create']){
    ?>
    <?php echo form_open(base_url('admin/customers/addComment'),array("id"=>"addCommentAjax")); ?>        
        <div id="addcommentbox">
            <?php echo form_hidden('rel_id',$customer['customernr']);?>
            <?php echo form_hidden('rel_type','customer');?>
            <?php echo form_hidden('addedto',$customer['responsible_id']);?>
            
            <div class="form-group">
                <?php $cdinput = array('name'=>'description','rows'=>2);  echo form_textarea($cdinput, '', 'class="form-control"');?>
            </div>
            <div class="form-actions pull-right">
                <button type="submit" class="btn blue"><?php echo lang('save');?></button>
            </div>
        </div>   
    <?php echo form_close(); ?>    
    <?php
}
?>

<div class="portlet-body" id="chats">
    <div class="scroller" style="max-height: 525px;" data-always-visible="1" data-rail-visible1="1">
        <ul class="chats">
            
            <?php
            foreach($comments as $comment){
                ?>
                <li class="<?php if($comment['addedfrom']==get_user_id()){ echo "out"; }else{ echo "in"; }?>">

                    <?php
                    echo user_profile_image($comment['addedfrom'],array('user-profile-image-small img-circle avatar'),'thumb');
                    ?>
                    
                    <div class="message">
                        <span class="arrow"> </span>
                        <a href="javascript:;" class="name"> <?php echo $comment['fullname'];?> </a>
                        <span class="datetime"> at <?php echo _dt($comment['created']);?> </span>
                        <span class="body"> <?php echo $comment['description'];?> 
                            
                            <?php
                            if($GLOBALS['customercomment_permission']['delete']){
                                ?>
                                <div class="text-<?php if($comment['addedfrom']==get_user_id()){ echo "right"; }else{ echo "left"; }?>">
                                    <a href="javascript:void(0);" class="text-danger" onclick="deleteConfirmation('<?php echo base_url("admin/customers/deleteComment");?>','<?php echo $comment['id'];?>','<?php echo lang("page_lb_delete_customercomment");?>','<?php echo lang("page_lb_delete_customercomment_info");?>','true','<?php echo $comment['rel_id'];?>');"><i class="fa fa fa-times"></i></a>
                                </div>
                                <?php
                            }
                            ?>
                            
                        </span>
                    </div>
                </li>
                <?php
            }
            ?>
            
        </ul>
    </div>
</div>


<script>
    var form_id3 = 'addCommentAjax'; 
    var func_FormValidation3 = 'FormCustomValidation3';
    	
    function after_func_FormValidation3(form1, error1, success1){
   
        form1.validate({
            errorElement: 'span', //default input error message container
            errorClass: 'help-block help-block-error', // default input error message class
            focusInvalid: false, // do not focus the last invalid input
            ignore: "",  // validate all fields including form hidden input

            rules: { 
                description: {    
                    maxlength: 255,
                    required: true
                },
            },

            invalidHandler: function (event, validator) { //display error alert on form submit              
                    //success1.hide();
                    //error1.show();
                    //App.scrollTo(error1, -200);
            },

            highlight: function (element) { // hightlight error inputs
                    $(element)
                            .closest('.form-group').addClass('has-error'); // set error class to the control group
            },

            unhighlight: function (element) { // revert the change done by hightlight
                    $(element)
                            .closest('.form-group').removeClass('has-error'); // set error class to the control group
            },

            success: function (label) {
                    label
                            .closest('.form-group').removeClass('has-error'); // set success class to the control group
            },

            submitHandler: function (form) {
                    //success1.show();
                    error1.hide();
                    App.scrollTo(error1, -200);
                    return true;
            }
	});
    }
</script>