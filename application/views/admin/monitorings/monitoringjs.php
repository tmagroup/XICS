<!-- Import CSV Modal -->
<div class="modal fade bs-modal-lg in" id="FormImportCSVAjax" tabindex="-1" role="dialog">
	<div class="modal-dialog" role="document">
		<?php echo form_open("",array('enctype' => "multipart/form-data", "id"=>"FormImportCSVModalAjax")); ?>
		<div class="modal-content">
			<div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title"></h4>
			</div>
			<div class="modal-body">

                            <!-- BEGIN PAGE MESSAGE-->
                            <?php $this->load->view('admin/alerts_modal'); ?>
                            <!-- BEGIN PAGE MESSAGE-->

                            <?php
                            $data_hidden = array('type'=>'hidden', 'name'=>'monitoringId', 'id'=>'monitoringId', 'value'=>'');
                            echo form_input($data_hidden);
                            ?>

                            <div class="form-group">
                                <label><b><?php echo lang('page_dt_assignmentnr');?>:</b> <span id="assignmentId"></span></label>
                            </div>

                            <div class="form-group">
                                <label><?php echo lang('choose_csv_file');?> <span class="required"> * </span></label>
                                <input type="file" name="file_csv" class="form-control" accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel">
                            </div>

			</div>
			<div class="modal-footer">
                            <button type="submit" class="btn btn-default blue"><?php echo lang('submit'); ?></button>
                            <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo lang('page_lb_close'); ?></button>
			</div>
		</div><!-- /.modal-content -->
		<?php echo form_close(); ?>
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<!-- End Import CSV Modal -->

<!-- Import Second CSV Modal -->
<div class="modal fade bs-modal-lg in" id="FormImportCSVSecondModalAjax" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <?php echo form_open("",array('enctype' => "multipart/form-data", "id"=>"FormImportCSVSecondeAjax")); ?>
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"></h4>
            </div>
            <div class="modal-body">
                    <!-- BEGIN PAGE MESSAGE-->
                    <?php $this->load->view('admin/alerts_modal'); ?>
                    <!-- BEGIN PAGE MESSAGE-->

                    <?php
                    $data_hidden = array('type'=>'hidden', 'name'=>'monitoringId', 'id'=>'monitoringId', 'value'=>'');
                    echo form_input($data_hidden);
                    ?>
                    <input type="hidden" name="assignmentnr" id="assignmentnr">
                    <div class="form-group">
                        <label><b><?php echo lang('page_dt_assignmentnr');?>:</b> <span id="assignmentId"></span></label>
                    </div>

                    <div class="form-group">
                        <label><?php echo lang('choose_csv_file');?> <span class="required"> * </span></label>
                        <input type="file" name="file_csv_second" class="form-control" accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel">
                        <?php //echo form_upload('file_csv_second', '', 'class="form-control"');?>
                    </div>

            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-default blue"><?php echo lang('submit'); ?></button>
                <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo lang('page_lb_close'); ?></button>
            </div>
        </div><!-- /.modal-content -->
        <?php echo form_close(); ?>
    </div><!-- /.modal-dialog -->
</div>
<!-- End Import CSV Modal -->

<script>
jQuery(document).ready(function() {

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
                        if(isset($monitoring['monitoringnr'])){
                            ?>
                            jQuery.ajax({url: '<?php echo base_url('admin/monitorings/getComments/'.$monitoring['monitoringnr']);?>', success: function(result){
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
                    if(isset($monitoring['monitoringnr'])){
                        ?>
                        jQuery.ajax({url: '<?php echo base_url('admin/monitorings/getComments/'.$monitoring['monitoringnr']);?>', success: function(result){
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

    //Generate Ticket Hardware Assignment Submit by Ajax
    jQuery("#FormImportCSVModalAjax").submit(function(e) {

        //jQuery('#FormImportCSVAjax').modal('hide');
        var form = jQuery(this);
        var url = form.attr('action');
        var error1 = jQuery('#FormImportCSVAjax #alert_modal .alert-danger');

        // check if the input is valid
        if(!form.valid()){
            return false;
        }

        var formData = new FormData(jQuery(this)[0]);
        Pace.track(function(){
            Pace.restart();
            jQuery.ajax({
               type: "POST",
               url: url,
               data: formData, // serializes the form's elements.
               dataType: "JSON",

               cache: false,
               contentType: false,
               processData: false,

               beforeSend:function(){
                    $('#pageloaddiv').show();
               },
               success: function(data){
                    $('#pageloaddiv').hide();

                    //show response from the php script.
                    //Showtoast Messagebox
                    jQuery('#FormImportCSVAjax').modal('hide');
                    showtoast(data.response,'',data.message);
               }
            });
        });

        e.preventDefault(); // avoid to execute the actual submit of the form.
    });

    //Get Running Time of Mobile Option
    jQuery('.monitoringassignmentstatus').change(function() {
        var positionid = jQuery(this).attr('dataid');
        var monitoringid = jQuery(this).attr('monitoringid');
        var costincurredby = jQuery(this).val();
        var url = '<?php echo base_url('admin/monitorings/changeMonitoringAssignmentStatus/')?>';

        Pace.track(function(){
            Pace.restart();
            jQuery.ajax({
               type: "POST",
               url: url,
               data:{
                    costincurredby: costincurredby,
                    positionid: positionid,
                    monitoringId: monitoringid
               },
               dataType: "JSON",
               beforeSend:function(){
                    $('#pageloaddiv').show();
               },
               success: function(data){
                    $('#pageloaddiv').hide();
                    showtoast(data.response,'',data.message);
               }
            });
        });
    });
});

//Import CSV Validation
function ImportCSVFormValidation(){

    var form1 = $('#FormImportCSVModalAjax');
    var error1 = $('#FormImportCSVAjax #alert_modal .alert-danger');
    var success1 = $('#FormImportCSVAjax #alert_modal .alert-success');

    form1.validate({
        errorElement: 'span', //default input error message container
        errorClass: 'help-block help-block-error', // default input error message class
        focusInvalid: false, // do not focus the last invalid input
        ignore: "",  // validate all fields including form hidden input

        rules: {
            file_csv: {
                required: true,
                extension: "csv"
            },
        },

        invalidHandler: function (event, validator) { //display error alert on form submit
                success1.hide();
                error1.show();
                App.scrollTo(error1, -200);
                $('#FormImportCSVAjax').animate({ scrollTop: 0 }, 'fast');
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
            $('#FormImportCSVAjax').animate({ scrollTop: 0 }, 'fast');
            return true;
        }
    });
}

//Import CSV Modal
function importCSVAjax(url,id,title,aid){
    $('#FormImportCSVAjax').modal('show');
    $('#FormImportCSVAjax #FormImportCSVModalAjax').attr('action',url);
    $('#FormImportCSVAjax #monitoringId').val(id);
    $('#FormImportCSVAjax #assignmentId').html(aid);
    $('#FormImportCSVAjax .modal-title').html('<i class="fa fa-file-excel-o"></i> '+title);
    $('#FormImportCSVModalAjax')[0].reset();

    //Clear validation
    $("span.help-block-error").hide();
    $(".has-error").removeClass("has-error");
    var error1 = jQuery('#FormImportCSVAjax #alert_modal .alert-danger');
    $(error1).hide();

    //Validation Form
    var validfunc = 'ImportCSV';
    if (typeof validfunc !== 'undefined') {
        eval(validfunc + "FormValidation()");
    }
}

$('#FormImportCSVSecondeAjax').validate({
    ignore: "",

    rules: {
        file_csv_second: {
            required: true,
            extension: "csv"
        },
    },
    messages : {
        file_csv_second : {
            required : 'Please choose csv file',
            extension : 'Please choose only csv file',
        }
    }
});

//Import Second CSV Modal
function importCSVAjaxSecond(url,id,title,aid,assignmentnr)
{
    $('#FormImportCSVSecondModalAjax').modal('show');
    $('#FormImportCSVSecondModalAjax #FormImportCSVSecondeAjax').attr('action',url);
    $('#FormImportCSVSecondModalAjax #monitoringId').val(id);
    $('#FormImportCSVSecondModalAjax #assignmentId').html(aid);
    $('#FormImportCSVSecondModalAjax #assignmentnr').val(assignmentnr);
    $('#FormImportCSVSecondModalAjax .modal-title').html('<i class="fa fa-file-excel-o"></i> '+title);
    $('#FormImportCSVSecondeAjax')[0].reset();
}

//Generate Ticket Hardware Assignment Submit by Ajax second CSV FILES
jQuery("#FormImportCSVSecondeAjax").submit(function(e) {
    e.preventDefault();
    //jQuery('#FormImportCSVAjax').modal('hide');
    var form = $(this);
    var url = form.attr('action');

    var formData = new FormData(jQuery(this)[0]);
        Pace.track(function(){
        Pace.restart();

        if(form.valid()){
            $.ajax({
               type: "POST",
               url: url,
               data: formData, // serializes the form's elements.
               dataType: "JSON",

               cache: false,
               contentType: false,
               processData: false,

               beforeSend:function(){
                    $('#pageloaddiv').show();
               },
               success: function(data){
                    $('#pageloaddiv').hide();
                    jQuery('#FormImportCSVSecondModalAjax').modal('hide');
                    showtoast(data.response,'',data.message);
               }
            });
        }
    });

    e.preventDefault(); // avoid to execute the actual submit of the form.
});
</script>