<script>
jQuery(document).ready(function() { 
    jQuery("#teamwork").find("option").eq(0).remove();
    jQuery("#teamwork").select2({
        placeholder: "<?php echo lang('page_lb_select_teamwork');?>",
        /*allowClear: true,*/
        width: null
    });
    
    Dropzone.options.myDropzone = {
       
        dictDefaultMessage: '<h3 class="sbold"><?php echo lang('page_lb_drop_files_here_to_upload');?></h3>',
        dictFallbackMessage: '<?php echo lang('page_lb_browser_not_support_drag_and_drop');?>',
        dictFileTooBig: '<?php echo lang('page_lb_file_exceeds_maxfile_size_in_form');?>',
        dictCancelUpload: '<?php echo lang('page_lb_cancel_upload');?>',
        dictRemoveFile: '<?php echo lang('page_lb_remove_file');?>',
        dictMaxFilesExceeded: '<?php lang('page_lb_you_can_not_upload_any_more_files');?>',
        maxFilesize: (<?php echo file_upload_max_size();?> / (1024 * 1024)).toFixed(0),
        acceptedFiles: '<?php echo get_option('allowed_files');?>',
        
        init: function() {
            
            this.on("sending", function(file, xhr, formData) {
                //formData.append("categoryid", jQuery('#documentcategory').val()); // Append all the additional input data of your form here!
            });
            
            this.on('error', function(file, response) {
                //Showtoast Messagebox
                showtoast('error','',response);                
            });
            
            this.on('success', function(file, response) {
                this.removeFile(file);
                
                //Showtoast Messagebox
                showtoast('success','','<?php echo lang('page_lb_file_uploaded_success');?>');  
                
                //Get New Refresh Ticket Document List
                <?php
                /*if(isset($ticket['ticketnr'])){
                    ?>
                    jQuery.ajax({url: '<?php echo base_url('admin/tickets/getDocuments/'.$ticket['ticketnr']);?>', success: function(result){        
                        jQuery('#ticket_attachments').html(result);
                    }});
                    <?php
                }*/
                ?>
                                    
                var table = jQuery('#'+datatable_id).DataTable();
                table.ajax.reload();
                
            });
            
            /*this.on("complete", function(file) { 
                this.removeAllFiles(true); 
            });*/
            
            this.on("addedfile", function(file) {
                // Create the remove button
                var removeButton = Dropzone.createElement("<a href='javascript:;'' class='btn red btn-sm btn-block'><?php echo lang('page_lb_remove');?></a>");

                // Capture the Dropzone instance as closure.
                var _this = this;

                // Listen to the click event
                removeButton.addEventListener("click", function(e) {
                  // Make sure the button click doesn't submit the form:
                  e.preventDefault();
                  e.stopPropagation();

                  // Remove the file preview.
                  _this.removeFile(file);
                  // If you want to the delete the file on the server as well,
                  // you can do the AJAX request here.
                });

                // Add the button to the file preview element.
                file.previewElement.appendChild(removeButton);                
            });
        }            
    }  
    
    //Delete Comment/Docuemnt Submit by Ajax
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
                    
                        //Get New Refresh Ticket Comment List
                        <?php
                        if(isset($ticket['ticketnr'])){
                            ?>
                            jQuery.ajax({url: '<?php echo base_url('admin/tickets/getComments/'.$ticket['ticketnr']);?>', success: function(result){                           
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
                    
                    if(url.indexOf('deleteDocument') != -1){
                    
                        //Get New Refresh Ticket Document List
                        <?php
                        /*if(isset($ticket['ticketnr'])){
                            ?>
                            jQuery.ajax({url: '<?php echo base_url('admin/tickets/getDocuments/'.$ticket['ticketnr']);?>', success: function(result){        
                                jQuery('#ticket_attachments').html(result);
                            }});
                            <?php
                        }*/
                        ?>
                                                    
                        var table = jQuery('#'+datatable_id).DataTable();
                        table.ajax.reload();

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
                    if(isset($ticket['ticketnr'])){
                        ?>
                        jQuery.ajax({url: '<?php echo base_url('admin/tickets/getComments/'.$ticket['ticketnr']);?>', success: function(result){                           
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
    var selected_responsible = '<?php echo isset($ticket['responsible'])?$ticket['responsible']:'';?>'
    /*jQuery("#customer").change( function(){        
        var custid = jQuery(this).val();
        jQuery("#responsible").html("<option value=''><?php echo lang('page_option_wait');?></option>");
        jQuery.ajax({dataType: "JSON", url: '<?php echo base_url('admin/tickets/getResponsibleOfCustomer/');?>'+custid, success: function(data){            
            var optionhtml = '';
            jQuery("#responsible").html("");        
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
    if(isset($ticket['ticketnr'])){
        ?>
        jQuery("#customer").change();
        <?php
    }
    elseif(get_user_role()=='customer'){
        ?>
        jQuery("#customer").change();        
        <?php
    }
    ?>
});
</script>