<!-- BEGIN SAMPLE FORM PORTLET-->
<div class="portlet light portlet-fit portlet-datatable bordered">                                
    <div class="portlet-body form">
        <div class="clearfix">&nbsp;</div>
        <div class="col-md-12">
            <div class="table-container">

                <div class="table-actions-wrapper"></div>

                <table class="table table-striped table-bordered table-hover dt-responsive" width="100%" id="assignment_datatable_ajax">    
                    <thead>
                        <tr role="row" class="heading">                                                                                                        
                            <th width="22%" class="all"> <?php echo lang('page_dt_assignmentnr');?></th>                                                    
                            <th width="1%"> <?php echo lang('page_dt_assignmentnr');?></th>
                            <th width="22%"> <?php echo lang('page_dt_company');?></th>
                            <th width="22%"> <?php echo lang('page_dt_responsibleuser');?></th>                                                    
                            <th width="22%"> <?php echo lang('page_dt_assignmentstatus');?></th>                           
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
    var admin_url_3 = '<?php echo base_url('admin/customers/ajaxAssignment/'.$customer['customernr']);?>';    
    var func_TableDatatablesAjax_3 = 'TableCustomDatatablesAjax_3';
    var datatable_id_3 = 'assignment_datatable_ajax';
    var datatable_pagelength_3 = '<?php echo get_option('tables_pagination_limit');?>';      
    var datatable_columnDefs_3 = 1;
    var datatable_columnDefs2_3 = 1;
    var datatable_sortColumn_3 = 0;
    var datatable_sortColumnBy_3 = 'asc';
    var datatable_hide_columns_3 = 1;
</script>