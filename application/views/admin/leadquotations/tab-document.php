<!-- BEGIN SAMPLE FORM PORTLET-->
<div class="portlet light bordered">
    <div class="portlet-body form">

        <div class="form-body">

            <div class="col-md-6">
                <div class="form-group">
                    <label><?php echo lang('page_fl_category');?> <span class="required"> * </span></label>
                    <?php echo form_dropdown('category', $categories, isset($quotation['attachments'][0]['categoryid'])?$quotation['attachments'][0]['categoryid']:'', 'class="form-control" id="documentcategory" ');?>
                </div>
            </div>
            <div class="clearfix"></div>

            <div class="col-md-12">

                <form action="<?php echo base_url('admin/leadquotations/uploadDocuments/'.$quotation['leadquotationnr']);?>" class="dropzone dropzone-file-area" id="my-dropzone" style="padding-top:40px;"></form>
                <div class="clearfix">&nbsp;</div>

                <div id="quotation_attachments">
                <?php /*if(count($quotation['attachments']) > 0) { ?>
                    <?php $this->load->view('admin/leadquotations/quotations_attachments_template', array('attachments'=>$quotation['attachments'])); ?>
                <?php }*/ ?>

                    <div class="portlet-body">
                        <div class="table-container">
                            <div class="table-actions-wrapper"></div>
                            <table class="table table-striped table-bordered table-hover dt-responsive" width="100%" id="document_datatable_ajax">
                                <thead>
                                    <tr role="row" class="heading">
                                        <th width="22%" class="all"> <?php echo lang('page_dt_category');?></th>
                                        <th width="22%"> <?php echo lang('page_dt_document');?></th>
                                        <th width="22%"> <?php echo lang('page_dt_uploaded_by');?></th>
                                        <th width="15%"> <?php echo lang('page_fl_date');?></th>
                                        <th width="15%" class="desktop"> <?php echo lang('page_dt_action');?></th>
                                        <th width="1%"> <?php echo lang('page_dt_documentid');?></th>
                                    </tr>
                                </thead>
                                <tbody> </tbody>
                            </table>
                        </div>
                    </div>

                </div>

            </div>

        </div>

    </div>
    <div class="clearfix"></div>
</div>
<!-- END SAMPLE FORM PORTLET-->

<script>
    var admin_url_2 = '<?php echo base_url('admin/leadquotations/ajaxdocument/'.$quotation['leadquotationnr']);?>';
    var func_TableDatatablesAjax_2 = 'TableCustomDatatablesAjax_2';
    var datatable_id_2 = 'document_datatable_ajax';
    var datatable_pagelength_2 = '<?php echo get_option('tables_pagination_limit');?>';
    var datatable_columnDefs_2 = 4;
    var datatable_columnDefs2_2 = 4;
    var datatable_sortColumn_2 = 0;
    var datatable_sortColumnBy_2 = 'asc';
    var datatable_hide_columns_2 = 5;
</script>