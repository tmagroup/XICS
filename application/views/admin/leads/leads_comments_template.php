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
                                    if($GLOBALS['leadcomment_permission']['delete']){
                                        ?>
                                        <div class="text-<?php if($comment['addedfrom']==get_user_id()){ echo "right"; }else{ echo "left"; }?>">
                                            <a href="javascript:void(0);" class="text-danger" onclick="deleteConfirmation('<?php echo base_url("admin/leads/deleteComment");?>','<?php echo $comment['id'];?>','<?php echo lang("page_lb_delete_leadcomment");?>','<?php echo lang("page_lb_delete_leadcomment_info");?>','true','<?php echo $comment['rel_id'];?>');"><i class="fa fa fa-times"></i></a>
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
<?php
exit;