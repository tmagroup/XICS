<script>
jQuery(document).ready(function() { 
    jQuery("#teamwork").find("option").eq(0).remove();
    jQuery("#teamwork").select2({
        placeholder: "<?php echo lang('page_lb_select_teamwork');?>",
        /*allowClear: true,*/
        width: null
    });

    //Date Picker Initialize
    jQuery(".form_date").datepicker({
        autoclose: true,
        isRTL: App.isRTL(),
        format: "dd.mm.yyyy", 
        pickerPosition: (App.isRTL() ? "bottom-right" : "bottom-left")
    });
    
    //Delete Comment Submit by Ajax
    jQuery("#deleteModalAjax").submit(function(e) {    
        var form = jQuery(this);
        var url = form.attr('action');
        Pace.track(function(){
            Pace.restart(); 
            jQuery.ajax({
               type: "POST",
               url: url,
               data: form.serialize(), // serializes the form's elements.
               dataType: "JSON",
               success: function(data){
                    //show response from the php script.
                    //Showtoast Messagebox
                    showtoast(data.response,'',data.message);  
                    
                    if(url.indexOf('deleteComment') != -1){
                    
                        //Get New Refresh Lead Comment List
                        <?php
                        if(isset($todo['todonr'])){
                            ?>
                            jQuery.ajax({url: '<?php echo base_url('admin/todos/getComments/'.$todo['todonr']);?>', success: function(result){                           
                                jQuery('#chats').html(result);                        
                                jQuery('html, body').animate({scrollTop: jQuery('#chats').offset().top-200}, 500);
                                jQuery('.scroller').slimScroll({
                                        maxheight: '525px'
                                });
                            }});
                            <?php
                        }
                        ?>
                        
                        jQuery('#deleteConfirmationAjax').modal('hide');
                    }
                        
               }
        });
        });
        e.preventDefault(); // avoid to execute the actual submit of the form.    
    }); 
    
    //Add/Edit Comment Submit by Ajax
    jQuery("#addCommentAjax,#editCommentAjax").submit(function(e) {
    
        var form = jQuery(this);
        var url = form.attr('action');
        
        // check if the input is valid
        if(!form.valid()){  
            return false;
        }
        Pace.track(function(){
            Pace.restart();
            jQuery.ajax({
               type: "POST",
               url: url,
               data: form.serialize(), // serializes the form's elements.
               dataType: "JSON",
               success: function(data){
                    //show response from the php script.
                    //Showtoast Messagebox
                    showtoast(data.response,'',data.message); 
                    form[0].reset();
                    jQuery("#addcommentbox").slideUp();
                    jQuery("#addcommentbtn i").addClass('fa-angle-up');  
                    jQuery("#addcommentbtn i").removeClass('fa-angle-down');  
                    
                    <?php
                    if(isset($todo['todonr'])){
                        ?>
                        jQuery.ajax({url: '<?php echo base_url('admin/todos/getComments/'.$todo['todonr']);?>', success: function(result){                           
                            jQuery('#chats').html(result);                        
                            jQuery('html, body').animate({scrollTop: jQuery('#chats').offset().top-200}, 500);
                            jQuery('.scroller').slimScroll({
                                    maxheight: '525px'
                            });
                        }});
                        <?php
                    }
                    ?>
               }
        });
        });
        e.preventDefault(); // avoid to execute the actual submit of the form.    
    });
    
    jQuery("#addcommentbtn").click( function(){        
        if(jQuery('#addcommentbox').css('display') == 'none'){
            jQuery("#addcommentbox").slideDown();
            jQuery("#addcommentbtn i").addClass('fa-angle-down');  
            jQuery("#addcommentbtn i").removeClass('fa-angle-up');
        }else{
            jQuery("#addcommentbox").slideUp();
            jQuery("#addcommentbtn i").addClass('fa-angle-up');  
            jQuery("#addcommentbtn i").removeClass('fa-angle-down');
        }
    });
    
    //Responsibles (Users of Customer) by Ajax
    var selected_responsible = '<?php echo isset($todo['responsible'])?$todo['responsible']:'';?>'
    /*jQuery("#customer").change( function(){        
        var custid = jQuery(this).val();
        jQuery("#responsible").html("<option value=''><?php echo lang('page_option_wait');?></option>");
        jQuery.ajax({dataType: "JSON", url: '<?php echo base_url('admin/todos/getResponsibleOfCustomer/');?>'+custid, success: function(data){            
            var optionhtml = '';
            jQuery("#responsible").html("<option value=''><?php echo lang('page_option_select');?></option>");        
            jQuery.each( data, function( key, value ) { 
                if(key!=""){
                    var selected = '';
                    if(selected_responsible==key){ var selected=' selected';  }
                    optionhtml = optionhtml+"<option value='"+key+"' "+selected+">"+value+"</option>";
                }
            });
            jQuery("#responsible").append(optionhtml);
        }});
    });*/
    
    <?php
    if(isset($todo['todonr'])){
        ?>
        jQuery("#customer").change();
        <?php
    }
    ?>
});
</script>