<!-- Convert to Customer Modal -->
<div class="modal fade bs-modal-lg in" id="FormAjax" tabindex="-1" role="dialog">
	<div class="modal-dialog modal-lg" role="document">
		<?php echo form_open("",array("id"=>"FormModalAjax")); ?>
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title"></h4>
			</div>
			<div class="modal-body">



			</div>
			<div class="modal-footer">
				<button type="submit" class="btn btn-default blue"><?php echo lang('save'); ?></button>
                                <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo lang('page_lb_close'); ?></button>
			</div>
		</div><!-- /.modal-content -->
		<?php echo form_close(); ?>
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<!-- End Convert to Customer Modal -->


<!-- Add/Edit Reminder Modal -->
<div class="modal fade" id="addeditReminderAjax" tabindex="-1" role="dialog">
	<div class="modal-dialog" role="document">
		<?php echo form_open("",array("id"=>"addeditReminderModalAjax")); ?>
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title"></h4>
			</div>
			<div class="modal-body">

                            <!-- BEGIN PAGE MESSAGE-->
                            <?php $this->load->view('admin/alerts_modal'); ?>
                            <!-- BEGIN PAGE MESSAGE-->

                            <div class="id">
                                <?php echo form_hidden('rel_id', $lead['leadnr']); ?>
                                <?php echo form_hidden('rel_type', 'lead'); ?>
                            </div>

                            <!-- Form -->
                            <div class="form-group">
                                <label><?php echo lang('page_fl_reminddate');?> <span class="required"> * </span></label>

                                <div class="input-group date form_datetime">
                                    <?php $dd = array('name'=>'reminddate', 'class'=>'form-control', 'readonly'=>true, 'size'=>16);
                                    echo form_input($dd);?>

                                    <span class="input-group-btn">
                                        <button class="btn default date-set" type="button">
                                            <i class="fa fa-calendar"></i>
                                        </button>
                                    </span>
                                </div>

                            </div>

                            <div class="form-group">
                                <label><?php echo lang('page_fl_remindersubject');?> <span class="required"> * </span></label>
                                <?php echo form_dropdown('remindersubject', $remindersubjects, '', 'class="form-control"');?>
                            </div>

                            <div class="form-group">
                                <label><?php echo lang('page_fl_notice');?> <span class="required"> * </span></label>
                                <?php echo form_textarea('notice', '', 'class="form-control"');?>
                            </div>

                            <div class="form-group">
                                <label>
                                    <?php $dc = array('name'=>'reminderway','class'=>'form-control');
                                    echo form_checkbox($dc);?>
                                    <?php echo lang('page_fl_reminderway');?>
                                </label>
                            </div>
                            <!-- End Form -->

			</div>
			<div class="modal-footer">
				<button type="submit" class="btn btn-default blue"><?php echo lang('save'); ?></button>
                                <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo lang('page_lb_close'); ?></button>
			</div>
		</div><!-- /.modal-content -->
		<?php echo form_close(); ?>
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<!-- End Add/Edit Reminder Modal -->


<script>
function datetimepicker(){
    jQuery("#teamwork").find("option").eq(0).remove();
    jQuery("#teamwork").select2({
        placeholder: "<?php echo lang('page_lb_select_teamwork');?>",
        /*allowClear: true,*/
        width: null
    });
    //Date & Time Picker Initialize
    jQuery(".form_datetime").datetimepicker({
        autoclose: true,
        isRTL: App.isRTL(),

        <?php
        $time_format = get_option('time_format');
        if($time_format == 24){
            ?>
            format: "dd.mm.yyyy"+" hh:ii:ss",
            <?php
        }
        else{
            ?>
            format: "dd.mm.yyyy"+" HH:ii:ss P",
            showMeridian: true,
            <?php
        }
        ?>

        pickerPosition: (App.isRTL() ? "bottom-right" : "bottom-left")
    });
}
jQuery(document).ready(function() {
    jQuery("#documentcategory").find("option").eq(0).remove();

    //Date & Time Picker Initialize
    datetimepicker();

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

                //Get New Refresh Lead Document List
                <?php
                /*if(isset($lead['leadnr'])){
                    ?>
                    jQuery.ajax({url: '<?php echo base_url('admin/leads/getDocuments/'.$lead['leadnr']);?>', success: function(result){
                        jQuery('#lead_attachments').html(result);
                    }});
                    <?php
                }*/
                ?>

                var table = jQuery('#'+datatable_id_2).DataTable();
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

    //Update Document Category by Ajax
    /*jQuery("#documentcategory").change( function(e){
        var categoryid = jQuery(this).val();
        Pace.track(function(){
            Pace.restart();
            <?php
            if(isset($lead['leadnr'])){
                ?>
                jQuery.ajax({dataType: "JSON", url: '<?php echo base_url('admin/leads/updateDocumentCategory/'.$lead['leadnr']);?>/'+categoryid, success: function(data){
                    //Showtoast Messagebox
                    if(data.response=='success'){
                        showtoast(data.response,'',data.message);
                    }
                }});
                <?php
            }
            ?>
        });
    });*/

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

                    if(url.indexOf('deleteComment') != -1){

                        //Get New Refresh Lead Comment List
                        <?php
                        if(isset($lead['leadnr'])){
                            ?>
                            jQuery.ajax({url: '<?php echo base_url('admin/leads/getComments/'.$lead['leadnr']);?>', success: function(result){
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

                        //Get New Refresh Lead Document List
                        <?php
                        /*if(isset($lead['leadnr'])){
                            ?>
                            jQuery.ajax({url: '<?php echo base_url('admin/leads/getDocuments/'.$lead['leadnr']);?>', success: function(result){
                                jQuery('#lead_attachments').html(result);
                            }});
                            <?php
                        }*/
                        ?>

                        var table = jQuery('#'+datatable_id_2).DataTable();
                        table.ajax.reload();

                        jQuery('#deleteConfirmationAjax').modal('hide');

                    }
                    else if(url.indexOf('deleteReminder') != -1){

                        var table = jQuery('#'+datatable_id).DataTable();
                        table.ajax.reload();

                        jQuery('#deleteConfirmationAjax').modal('hide');

                    }
               }
        });
        });
        e.preventDefault(); // avoid to execute the actual submit of the form.
    });

    //Add/Edit Reminder Submit by Ajax
    jQuery("#addeditReminderModalAjax").submit(function(e) {
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

                    var table = jQuery('#'+datatable_id).DataTable();
                    table.ajax.reload();

                    jQuery('#addeditReminderAjax').modal('hide');
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
                    if(isset($lead['leadnr'])){
                        ?>
                        jQuery.ajax({url: '<?php echo base_url('admin/leads/getComments/'.$lead['leadnr']);?>', success: function(result){
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


    //Add Customer Submit by Ajax
    jQuery("#FormModalAjax").submit(function(e) {

        var form = jQuery(this);
        var url = form.attr('action');
        var error1 = jQuery('#FormAjax #alert_modal .alert-danger');

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
                    if(data.response=='success'){
                        showtoast(data.response,'',data.message);
                        jQuery('#FormAjax').modal('hide');
                        window.location = data.redirect;
                    }
                    else{
                        error1.html(data.message);
                        error1.show();
                        $('#FormAjax').animate({ scrollTop: 0 }, 'fast');
                    }
               }
        });
        });
        e.preventDefault(); // avoid to execute the actual submit of the form.
    });
});

//Reminder Modal
function addeditReminderAjax(url, id, title){
    $('#addeditReminderAjax').modal('show');
    $('#addeditReminderAjax #addeditReminderModalAjax').attr('action',url+'/'+id);
    $('#addeditReminderAjax .modal-title').html('<i class="fa fa-bell-o"></i> '+title);

    /* Initialise for Edit */
    $('#addeditReminderModalAjax')[0].reset();
    $.uniform.update('#addeditReminderModalAjax input[name=reminderway]');

    //Clear validation
    $("span.help-block-error").hide();
    $(".has-error").removeClass("has-error");
    $("#"+inner_msg_id2+" .alert").hide();

    if(id>0){
        Pace.track(function(){
            Pace.restart();
            jQuery.ajax({dataType: "JSON", url: '<?php echo base_url('admin/reminders/getReminder/');?>'+id, success: function(data){

            $('#addeditReminderModalAjax input[name=reminddate]').val(data.reminddate);

            if(data.reminderway==1){
                $('#addeditReminderModalAjax input[name=reminderway]').prop('checked', true);
            }
            else{
                $('#addeditReminderModalAjax input[name=reminderway]').prop('checked', false);
            }
            $.uniform.update('#addeditReminderModalAjax input[name=reminderway]');

            $('#addeditReminderModalAjax select[name=remindersubject]').val(data.remindersubject);
            $('#addeditReminderModalAjax textarea[name=notice]').val(data.notice);
        }});
        });
    }
}

//General Modal
function FormAjax(url, infourl, title, validfunc){
    $('#FormAjax').modal('show');
    $('#FormAjax #FormModalAjax').attr('action',url);
    $('#FormModalAjax .modal-title').html('<i class="fa fa-plus"></i> '+title);
    $('#FormModalAjax .modal-body').html("<div class='text-center'><img src='<?php echo base_url('assets/global/img/loading-spinner-blue.gif');?>' /></div>");

    /* Initialise for Edit */
    $('#FormModalAjax')[0].reset();

    //Clear validation
    $("span.help-block-error").hide();
    $(".has-error").removeClass("has-error");
    var error1 = jQuery('#FormAjax #alert_modal .alert-danger');
    $(error1).hide();

    Pace.track(function(){
        Pace.restart();
        jQuery.ajax({url: infourl, success: function(result){
        $('#FormModalAjax .modal-body').html(result);

        //Validation Form
        if (typeof validfunc !== 'undefined') {
            eval(validfunc + "FormValidation()");
        }
    }});
    });
}

//Validation Function for Add Customer
function customerFormValidation(){

    var form1 = $('#FormModalAjax');
    var error1 = $('#FormAjax #alert_modal .alert-danger');
    var success1 = $('#FormAjax #alert_modal .alert-success');

    form1.validate({
        errorElement: 'span', //default input error message container
        errorClass: 'help-block help-block-error', // default input error message class
        focusInvalid: false, // do not focus the last invalid input
        ignore: "",  // validate all fields including form hidden input

        rules: {
            /*recommend: {
                required: true
            },
            framecontno: {
                required: true
            },
            position: {
                required: true
            },
            website: {
                required: true
            },
            business: {
                required: true
            },
            faxnr: {
                required: true
            },
            mobilnr: {
                required: true
            },*/
            responsible: {
                required: true
            },
            username: {
                minlength: 2,
                required: true
            },
            password: {
                minlength: 5,
                required: true
            },
            cpassword: {
                minlength: 5,
                required: true,
                equalTo: "#submit_form_password"
            },
            company: {
                minlength: 2,
                required: true
            },
            street: {
                required: true
            },
            zipcode: {
                required: true
            },
            city: {
                required: true
            },
            phone: {
                required: true
            },
            email: {
                required: true,
                email: true
            },
            companysize: {
                required: true
            },
            salutation: {
                required: true
            },
            surname: {
                minlength: 2,
                required: true
            },
            name: {
                minlength: 2,
                required: true
            },
        },

        invalidHandler: function (event, validator) { //display error alert on form submit
                success1.hide();
                error1.show();
                App.scrollTo(error1, -200);
                $('#FormAjax').animate({ scrollTop: 0 }, 'fast');
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
                $('#FormAjax').animate({ scrollTop: 0 }, 'fast');
                return true;
        }
    });
}
</script>