<!-- BEGIN SAMPLE FORM PORTLET-->
<div class="portlet light portlet-fit portlet-datatable bordered">                                
    <div class="portlet-body form">
        <div class="clearfix">&nbsp;</div>
        <div class="col-md-12">
            <div class="table-container">

                <div class="table-actions-wrapper"></div>

                <table class="table table-striped table-bordered table-hover dt-responsive" width="100%" id="quotation_datatable_ajax">    
                    <thead>
                        <tr role="row" class="heading">                                                                                                        
                            <th width="22%" class="all"> <?php echo lang('page_dt_quotationnr');?></th>                                                    
                            <th width="1%"> <?php echo lang('page_dt_quotationnr');?></th>
                            <th width="22%"> <?php echo lang('page_dt_company');?></th>
                            <th width="22%"> <?php echo lang('page_dt_responsibleuser');?></th>                                                    
                            <th width="22%"> <?php echo lang('page_dt_quotationstatus');?></th>                            
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
    var admin_url_2 = '<?php echo base_url('admin/customers/ajaxQuotation/'.$customer['customernr']);?>';    
    var func_TableDatatablesAjax_2 = 'TableCustomDatatablesAjax_2';
    var datatable_id_2 = 'quotation_datatable_ajax';
    var datatable_pagelength_2 = '<?php echo get_option('tables_pagination_limit');?>';      
    var datatable_columnDefs_2 = 1;
    var datatable_columnDefs2_2 = 1;
    var datatable_sortColumn_2 = 0;
    var datatable_sortColumnBy_2 = 'asc';
    var datatable_hide_columns_2 = 1;
</script>