<!-- Add/Edit Infodocument Modal -->
<div class="modal fade" id="addeditInfodocumentAjax" tabindex="-1" role="dialog">
	<div class="modal-dialog" role="document">
		<?php echo form_open("",array("id"=>"addeditInfodocumentModalAjax")); ?>
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title"></h4>
			</div>
			<div class="modal-body">

                <!-- BEGIN PAGE MESSAGE-->
                <?php $this->load->view('admin/alerts_modal'); ?>
                <!-- BEGIN PAGE MESSAGE-->

                <!-- Form -->
                <input type="hidden" id="documentnr">
                <div class="form-group">
                    <label><?php echo lang('page_dt_infodocumenttitle');?> <span class="required"> * </span></label>
                    <?php echo form_input('documenttitle', '', 'class="form-control"');?>
                </div>

                <div class="form-group">
                    <label><?php echo lang('page_fl_provider');?> </label>
                    <?php echo form_dropdown('provider', provider_values(), isset($ratemobile['provider'])?$ratemobile['provider']:'', 'class="form-control"');?>
                </div>

                <div class="form-group"><label><?php echo lang('page_dt_infodocumentfile');?> <span class="required"> * </span></label>
                    <div class="clearfix"></div>
                    <div class="fileinput fileinput-new" data-provides="fileinput">
                        <div>
                            <span class="btn btn-sm btn-default m-t-n-xs btn-file">
                                <span class="fileinput-new"> Select </span>
                                <span class="fileinput-exists"> <?php echo lang('page_lb_change');?> </span>
                                <?php
                                echo form_upload('documentfile');
                                ?>
                            </span>
                            <a href="javascript:;" class="btn btn-sm btn-primary m-t-n-xs fileinput-exists" data-dismiss="fileinput"> <?php echo lang('page_lb_remove');?> </a>
                        </div>
                        <div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 160px; max-height: 160px;"></div>
                    </div>
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
<!-- End Add/Edit Infodocument Modal -->

<script>
    jQuery(document).ready(function() {

    //Add/Edit Infodocument Submit by Ajax
    jQuery("#addeditInfodocumentModalAjax").submit(function(e) {
        var form = jQuery(this);
        var url = form.attr('action');

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

               success: function(data){
                    //show response from the php script.
                    //Showtoast Messagebox
                    showtoast(data.response,'',data.message);

                    var table = jQuery('#'+datatable_id).DataTable();
                    table.ajax.reload();

                    jQuery('#addeditInfodocumentAjax').modal('hide');
                }
            });
        });
        e.preventDefault(); // avoid to execute the actual submit of the form.
    });

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

                    if(url.indexOf('deleteInfodocument') != -1){

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


//Infodocument Modal
function addeditInfodocumentAjax(url, id, title, _this){
    $('#addeditInfodocumentAjax').modal('show');
    $('#addeditInfodocumentAjax #addeditInfodocumentModalAjax').attr('action',url+'/'+id);
    $('#addeditInfodocumentAjax .modal-title').html('<i class="fa fa-file-pdf-o"></i> '+title);

    /* Initialise for Edit */
    $('#addeditInfodocumentModalAjax')[0].reset();

    $('#addeditInfodocumentAjax #documentnr').val(id);
    $('#addeditInfodocumentAjax [name="documentfile"]').attr('required', true);
    if (id) {
        $('#addeditInfodocumentAjax [name="documentfile"]').attr('required', false);
        $('#addeditInfodocumentAjax [name="documenttitle"]').val(_this.closest('tr').find('td:eq(0)').text());
        $('#addeditInfodocumentAjax [name="provider"]').val(_this.closest('tr').find('td:eq(1)').text());
    }

    //Clear validation
    $("span.help-block-error").hide();
    $(".has-error").removeClass("has-error");
    $("#"+inner_msg_id+" .alert").hide();
}
</script>