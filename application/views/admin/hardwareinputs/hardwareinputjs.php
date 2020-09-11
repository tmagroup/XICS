<script>
function datapicker(){
    jQuery(".form_date").datepicker({
        autoclose: true,
        isRTL: App.isRTL(),
        format: "dd.mm.yyyy",         
        pickerPosition: (App.isRTL() ? "bottom-right" : "bottom-left")
    });
}    
function extraFieldsValidate(){
    var isValid = true;
 
    jQuery('#hardwareinputproduct_inputbox select').each(function() {
        if($(this).val() == "" && $(this).val().length < 1) {
            $(this).addClass('field_error');
            isValid = false;
        } else {
            $(this).removeClass('field_error');
        }
    });  

    jQuery('#hardwareinputproduct_inputbox input').each(function() {
        if(jQuery(this).attr('type')!='radio'){
            if(!jQuery(this).hasClass("noerror")){               
                if($(this).val() == "" && $(this).val().length < 1) {
                    $(this).addClass('field_error');
                    isValid = false;
                } else {
                    $(this).removeClass('field_error');
                }                
            }
        }
    }); 
    
    return isValid;
}

jQuery(document).ready(function() {
    //Date Picker Initialize
    datapicker();            
    
    //Delete Reminder/Comment Submit by Ajax
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
                    
                    if(url.indexOf('deleteHardwareinputProduct') != -1){                        
                        if(data.response=='success'){
                            jQuery('#row1_old_hardwareinputproduct_'+data.dataid).remove();                            
                        } 
                        jQuery('#deleteConfirmationAjax').modal('hide');
                    }                    
               }
        });
        });
        e.preventDefault(); // avoid to execute the actual submit of the form.    
    });     
});
</script>