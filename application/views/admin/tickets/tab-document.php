<!-- BEGIN SAMPLE FORM PORTLET-->
<div class="portlet light bordered">                                
    <div class="portlet-body form">

        <div class="form-body">
            
            <div class="col-md-12">
                
                <form action="<?php echo base_url('admin/tickets/uploadDocuments/'.$ticket['ticketnr']);?>" class="dropzone dropzone-file-area" id="my-dropzone" style="padding-top:40px;"></form>
                <div class="clearfix">&nbsp;</div>
                
                <div id="ticket_attachments">
                <?php /*if(count($ticket['attachments']) > 0) { ?>
                    <?php $this->load->view('admin/tickets/tickets_attachments_template', array('attachments'=>$ticket['attachments'])); ?>
                <?php }*/ ?>
                    
                    
                    <div class="portlet-body">
                        <div class="table-container">
                            <div class="table-actions-wrapper"></div>
                            <table class="table table-striped table-bordered table-hover dt-responsive" width="100%" id="document_datatable_ajax">    
                                <thead>
                                    <tr role="row" class="heading">                                                                                                        
                                        <!--<th width="22%" class="all"> <?php echo lang('page_dt_category');?></th>-->                                                                                                        
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
    var admin_url = '<?php echo base_url('admin/tickets/ajaxdocument/'.$ticket['ticketnr']);?>';    
    var func_TableDatatablesAjax = 'TableCustomDatatablesAjax';
    var datatable_id = 'document_datatable_ajax';
    var datatable_pagelength = '<?php echo get_option('tables_pagination_limit');?>';      
    var datatable_columnDefs = 3;
    var datatable_columnDefs2 = 3;
    var datatable_sortColumn = 0;
    var datatable_sortColumnBy = 'asc';
    var datatable_hide_columns = 4;
</script>