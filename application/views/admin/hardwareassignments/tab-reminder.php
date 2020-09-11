<!-- BEGIN SAMPLE FORM PORTLET-->
<div class="portlet light portlet-fit portlet-datatable bordered">                                
    <div class="portlet-body form">
        
        
        <div class="clearfix">&nbsp;</div>
        <div class="col-md-12">
            <a href="javascript:void(0);" onclick="addeditReminderAjax('<?php echo base_url('admin/hardwareassignmentreminders/addReminder');?>','','<?php echo sprintf(lang('page_create_reminder'),lang('page_hardwareassignment'));?>');" class="btn sbold blue btn-sm"><i class="fa fa-bell-o"></i> <?php echo sprintf(lang('page_create_reminder'),lang('page_hardwareassignment'));?></a>
        </div>    
        
        
        <div class="clearfix">&nbsp;</div>        
        <div class="col-md-12">
            <div class="table-container">

                <div class="table-actions-wrapper"></div>

                <table class="table table-striped table-bordered table-hover dt-responsive" width="100%" id="reminder_datatable_ajax">    
                    <thead>
                        <tr role="row" class="heading">                        
                            <th width="21%"> <?php echo lang('page_dt_remindernr');?></th>                        
                            <th width="1%"> <?php echo lang('page_dt_remindernr');?></th>
                            <th width="21%"> <?php echo lang('page_dt_reminddate');?></th>
                            <th width="21%"> <?php echo lang('page_dt_reminderway');?></th>
                            <th width="21%"> <?php echo lang('page_dt_remindersubject');?></th>
                            <th width="15%"> <?php echo lang('page_dt_action');?></th>
                        </tr>                                                
                    </thead>
                    <tbody> </tbody>
                </table>

            </div>
        </div>
        
        
        <div class="clearfix"></div>        
    </div>
</div>    


<script>
    var admin_url = '<?php echo base_url('admin/hardwareassignmentreminders/ajax/'.$hardwareassignment['hardwareassignmentnr'].'/hardwareassignment');?>';    
    var func_TableDatatablesAjax = 'TableCustomDatatablesAjax';
    var datatable_id = 'reminder_datatable_ajax';
    var datatable_pagelength = '<?php echo get_option('tables_pagination_limit');?>';      
    var datatable_columnDefs = 5;
    var datatable_columnDefs2 = 5;
    var datatable_sortColumn = 0;
    var datatable_sortColumnBy = 'asc';
    var datatable_hide_columns = 1;
</script>

<script>
    var form_id2 = 'addeditReminderModalAjax'; 
    var func_FormValidation2 = 'FormCustomValidation2';
    var inner_msg_id2 = 'alert_modal';
    
    function after_func_FormValidation2(form1, error1, success1){     
        form1.validate({
            errorElement: 'span', //default input error message container
            errorClass: 'help-block help-block-error', // default input error message class
            focusInvalid: false, // do not focus the last invalid input
            ignore: "",  // validate all fields including form hidden input

            rules: { 
                reminddate: {                        
                    required: true
                },
                remindersubject: {                        
                    required: true
                },
                notice: {
                    maxlength: 255,
                    required: true
                },            			
            },

            invalidHandler: function (event, validator) { //display error alert on form submit              
                    success1.hide();
                    error1.show();
                    App.scrollTo(error1, -200);
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