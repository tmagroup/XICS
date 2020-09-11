<!-- BEGIN SAMPLE FORM PORTLET-->
<div class="portlet light portlet-fit portlet-datatable bordered">                                
    <div class="portlet-body form">
        <div class="clearfix">&nbsp;</div>
        <div class="col-md-12">
            <div class="table-container">

                <div class="table-actions-wrapper"></div>

                <table class="table table-striped table-bordered table-hover dt-responsive" width="100%" id="ticket_datatable_ajax">    
                    <thead>
                        <tr role="row" class="heading">                                                                                                        
                            <th width="22%" class="all"> <?php echo lang('page_dt_ticketnr');?></th>                                                    
                            <th width="1%"> <?php echo lang('page_dt_ticketnr');?></th>
                            <th width="15%"> <?php echo lang('page_dt_tickettitle');?></th>
                            <th width="15%"> <?php echo lang('page_dt_company');?></th>
                            <th width="15%"> <?php echo lang('page_dt_ticketstatus');?></th>
                            <th width="15%"> <?php echo lang('page_dt_responsibleuser');?></th>
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
    var admin_url_4 = '<?php echo base_url('admin/customers/ajaxTicket/'.$customer['customernr']);?>';    
    var func_TableDatatablesAjax_4 = 'TableCustomDatatablesAjax_4';
    var datatable_id_4 = 'ticket_datatable_ajax';
    var datatable_pagelength_4 = '<?php echo get_option('tables_pagination_limit');?>';      
    var datatable_columnDefs_4 = 1;
    var datatable_columnDefs2_4 = 1;
    var datatable_sortColumn_4 = 0;
    var datatable_sortColumnBy_4 = 'asc';
    var datatable_hide_columns_4 = 1;
</script>