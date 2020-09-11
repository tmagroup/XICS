<script>   
function datapicker(){
    jQuery(".form_date").datepicker({
        autoclose: true,
        isRTL: App.isRTL(),
        format: "dd.mm.yyyy",         
        pickerPosition: (App.isRTL() ? "bottom-right" : "bottom-left")
    });
}
jQuery(document).ready(function() {
    //Date Picker Initialize
    datapicker();
});
</script>