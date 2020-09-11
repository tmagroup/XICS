<!-- BEGIN SAMPLE FORM PORTLET-->
<div class="portlet light portlet-fit portlet-datatable bordered">                                
    <div class="portlet-body form">
        <div class="clearfix">&nbsp;</div>
        <div class="col-md-12">
            <div class="table-container">

                <div class="table-actions-wrapper"></div>

                <table class="table table-striped table-bordered table-hover dt-responsive" width="100%" id="hardwareassignment_datatable_ajax">    
                    <thead>
                        <tr role="row" class="heading">                                                                                                        
                            <th width="15%" class="all"> <?php echo lang('page_dt_hardwareassignmentnr');?></th>                                                    
                            <th width="1%"> <?php echo lang('page_dt_hardwareassignmentnr');?></th>
                            <th width="15%"> <?php echo lang('page_dt_company');?></th>
                            <th width="15%"> <?php echo lang('page_dt_hardwareassignmentstatus');?></th>
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
    var admin_url_5 = '<?php echo base_url('admin/customers/ajaxHardwareassignment/'.$customer['customernr']);?>';    
    var func_TableDatatablesAjax_5 = 'TableCustomDatatablesAjax_5';
    var datatable_id_5 = 'hardwareassignment_datatable_ajax';
    var datatable_pagelength_5 = '<?php echo get_option('tables_pagination_limit');?>';      
    var datatable_columnDefs_5 = 1;
    var datatable_columnDefs2_5 = 1;
    var datatable_sortColumn_5 = 0;
    var datatable_sortColumnBy_5 = 'asc';
    var datatable_hide_columns_5 = 1;
</script>