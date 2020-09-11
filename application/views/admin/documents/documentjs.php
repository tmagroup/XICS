<script>
jQuery("#documentcategory").find("option").eq(0).remove();

jQuery(document).ready(function() { 
    
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
                formData.append("categoryid", jQuery('#documentcategory').val()); // Append all the additional input data of your form here!
            });
            
            this.on('error', function(file, response) {
                //Showtoast Messagebox
                showtoast('error','',response);                
            });
            
            this.on('success', function(file, response) {
                this.removeFile(file);
                
                //Showtoast Messagebox
                showtoast('success','','<?php echo lang('page_lb_file_uploaded_success');?>');  
                
                //Get New Refresh User Document List
                /*jQuery.ajax({url: '<?php echo base_url('admin/documents/getDocuments/'.get_user_id());?>', success: function(result){        
                    jQuery('#document_attachments').html(result);
                }});*/
                
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
                    
                    if(url.indexOf('deleteDocument') != -1){
                    
                        //Get New Refresh User Document List                        
                        /*jQuery.ajax({url: '<?php echo base_url('admin/documents/getDocuments/'.get_user_id());?>', success: function(result){        
                            jQuery('#document_attachments').html(result);
                        }});*/
                            
                        var table = jQuery('#'+datatable_id).DataTable();
                        table.ajax.reload();
                         
                        jQuery('#deleteConfirmationAjax').modal('hide');
                    
                    }                    
               }
        });
        });
        e.preventDefault(); // avoid to execute the actual submit of the form.    
    }); 
   
});
</script>