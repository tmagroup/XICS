<!-- BEGIN SAMPLE FORM PORTLET-->
<div class="portlet light portlet-fit portlet-datatable bordered">
    <div class="portlet-body form">

        <?php
        if($GLOBALS['a_invoice_permission']['create']){
            ?>
            <div class="clearfix">&nbsp;</div>
            <div class="col-md-12">
                <a href="javascript:void(0);" onclick="addeditInvoiceAjax('<?php echo base_url('admin/assignments/addInvoice');?>','','<?php echo sprintf(lang('page_create_invoice'),lang('page_lb_invoice'));?>');" class="btn sbold blue btn-sm"><i class="fa fa-file-pdf-o"></i> <?php echo sprintf(lang('page_create_invoice'),lang('page_lb_invoice'));?></a>
            </div>
            <?php
        }
        ?>



        <div class="clearfix">&nbsp;</div>
        <div class="col-md-12">
            <div class="row">
                <div class="portlet-title filterby pull-right">
                    <div class="form-group">
                        <label class="control-label"><?php echo lang('filter_by');?> </label>
                        <div class="col-md-3 col-sm-3">
                        <?php
                        $filter_invoice_years[''] = '';
                        foreach(range(date('Y')-10,(date('Y')+50)) as $yname){
                            $filter_invoice_years[$yname] = $yname;
                        }
                        echo form_dropdown('filter_invoice_year', $filter_invoice_years, '', 'class="form-control select2" id="filter_invoice_year" ');?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="table-container">

                <div class="table-actions-wrapper"></div>

                <table class="table table-striped table-bordered table-hover dt-responsive" width="100%" id="invoice_datatable_ajax">
                    <thead>
                        <tr role="row" class="heading">
                            <th width="20%"> <?php echo lang('page_dt_assignment_monthyear');?></th>
                            <th width="20%"> <?php echo lang('page_dt_assignment_invoicenr');?></th>
                            <th width="25%"> <?php echo lang('page_dt_assignment_description');?></th>
                            <th width="20%"> <?php echo lang('page_dt_assignment_netamount');?></th>
                            <th width="15%"> <?php echo lang('page_dt_action');?></th>
                            <th width="1%"> <?php echo lang('page_dt_assignment_billnr');?></th>
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
    var admin_url_3 = '<?php echo base_url('admin/assignments/ajaxinvoice/'.$assignment['assignmentnr']);?>';
    var func_TableDatatablesAjax_3 = 'TableCustomDatatablesAjax_3';
    var datatable_id_3 = 'invoice_datatable_ajax';
    var datatable_pagelength_3 = '<?php echo get_option('tables_pagination_limit');?>';
    var datatable_columnDefs_3 = 4;
    var datatable_columnDefs2_3 = 5;
    var datatable_sortColumn_3 = 0;
    var datatable_sortColumnBy_3 = 'asc';
    var datatable_hide_columns_3 = 5;
</script>

<script>
    var form_id3 = 'addeditInvoiceModalAjax';
    var func_FormValidation3 = 'FormCustomValidation3';
    var inner_msg_id3 = 'addeditInvoiceModalAjax #alert_modal';

    function after_func_FormValidation3(form1, error1, success1){
        form1.validate({
            errorElement: 'span', //default input error message container
            errorClass: 'help-block help-block-error', // default input error message class
            focusInvalid: false, // do not focus the last invalid input
            ignore: "",  // validate all fields including form hidden input

            rules: {
                m_monthyear: {
                    required: true
                },
                y_monthyear: {
                    required: true
                },
                invoicenr: {
                    required: true
                },
                description: {
                    maxlength: 255,
                    required: true
                },
                netamount: {
                    required: true
                },
                invoicefile: {
                    required: true,
                    extension: "pdf"
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