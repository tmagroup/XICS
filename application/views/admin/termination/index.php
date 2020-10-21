<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<?php $this->load->view('admin/header.php'); ?>
<style type="text/css">
    .clrwhite{
        background-color:#fff !important;
    }
    .clrGreen{
        background-color:#c6ddae !important;
    }
    .clrRed{
        background-color:#f6d1d1 !important;
    }
</style>
<body class="page-header-fixed page-sidebar-closed-hide-logo page-content-white">
        <?php $this->load->view('admin/topnavigation.php'); ?>

        <!-- BEGIN HEADER & CONTENT DIVIDER -->
        <div class="clearfix"> </div>
        <!-- END HEADER & CONTENT DIVIDER -->
        <!-- BEGIN CONTAINER -->
        <div class="page-container">

            <?php $this->load->view('admin/sidebar.php'); ?>


            <!-- BEGIN CONTENT -->
            <div class="page-content-wrapper">
                <!-- BEGIN CONTENT BODY -->
                <div class="page-content">
                    <!-- BEGIN PAGE HEADER-->

                    <!-- BEGIN PAGE BAR -->
                    <div class="page-bar">
                        <ul class="page-breadcrumb">
                            <li>
                                <a href="<?php echo base_url('admin/dashboard');?>"><?php echo lang('bread_home');?></a>
                                <i class="fa fa-circle"></i>
                            </li>
                            <li>
                                <span><?php echo lang('page_termination');?></span>
                            </li>
                        </ul>
                        <?php
                        if($GLOBALS['termination_permission']['create'] || $GLOBALS['termination_permission']['import']){
                        ?>
                        <div class="page-toolbar">
                            <div class="btn-group btn-group-devided pull-right">
                                <?php
                                    if($GLOBALS['termination_permission']['import']){
                                        ?>
                                        <a href="<?php echo base_url('admin/termination/import');?>" class="btn sbold green btn-sm"><i class="fa fa-file-excel-o"></i> <?php echo lang('page_import_termination');?></a>
                                        <?php
                                    }
                                ?>
                                <?php
                                    if($GLOBALS['termination_permission']['create']){
                                        ?>
                                        <a href="<?php echo base_url('admin/termination/setup');?>" class="btn sbold blue btn-sm"><i class="fa fa-plus"></i> <?php echo lang('page_create_termination');?></a>
                                        <?php
                                    }
                                ?>
                            </div>
                        </div>
                        <?php
                        }
                        ?>
                    </div>
                    <!-- END PAGE BAR -->
                    <!-- BEGIN PAGE MESSAGE-->
                    <?php $this->load->view('admin/alerts'); ?>
                    <!-- BEGIN PAGE MESSAGE-->
                    <!-- BEGIN PAGE TITLE-->
                    <h3 class="page-title"><i class="fa fa-tty"></i> <?php echo lang('page_manage_termination');?></h3>
                    <!-- END PAGE TITLE-->

                    <!-- END PAGE HEADER-->
                    <div class="row">
                        <div class="col-md-12">

                            <!-- Begin: life time stats -->
                            <div class="portlet light portlet-fit portlet-datatable bordered">
                                <div class="portlet-body">
                                    <div class="table-container">
                                    <form method="post" action="<?php echo base_url().'admin/termination/export_excel';?>">
                                        <div class="col-md-2 col-sm-2">
                                            <div class="form-group">
                                                <label><b>Filter Export Excel</b></label>
                                            </div>
                                        </div>
                                        <div class="col-md-2 col-sm-2">
                                            <div class="form-group">
                                                <select name="filter_month" class="form-control">
                                                    <?php
                                                    $months = array(1 => 'Jan.', 2 => 'Feb.', 3 => 'Mar.', 4 => 'Apr.', 5 => 'May', 6 => 'Jun.', 7 => 'Jul.', 8 => 'Aug.', 9 => 'Sep.', 10 => 'Oct.', 11 => 'Nov.', 12 => 'Dec.');
                                                    ?>
                                                    <option value="" selected="selected">Select Month</option>
                                                    <?php foreach ($months as $key => $month) { ?>
                                                        <option value="<?php echo $key;?>"><?php echo $month;?></option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-2 col-sm-2">
                                            <div class="form-group">
                                                <select name="filter_year" class="form-control">
                                                    <option value="" selected="selected">Select Year</option>
                                                    <?php if(!empty($yearData)){ ?>
                                                        <?php foreach($yearData as $year) { ?>
                                                            <option value="<?php echo date('Y',strtotime($year['date']));?>"><?php echo date('Y',strtotime($year['date']));?></option>
                                                        <?php } ?>
                                                    <?php } ?>

                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-3 col-sm-3">
                                            <div class="form-group">
                                                <select name="filter_leadStatus" class="form-control">
                                                    <option value="" selected="selected">Select Lead Status</option>
                                                    <?php if(!empty($leadStatusData)){ ?>
                                                        <?php foreach($leadStatusData as $lead_status) { ?>
                                                            <option value="<?php echo $lead_status['id'];?>"><?php echo $lead_status['name'];?></option>
                                                        <?php } ?>
                                                    <?php } ?>

                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-2 col-sm-2">
                                            <div class="form-group">
                                                <button type="submit" class="btn sbold blue btn-sm">Export Excel</button>
                                            </div>
                                        </div>
                                    </form>
                                        <div class="table-actions-wrapper"></div>
                                        <table class="table table-striped table-bordered table-hover dt-responsive" width="100%" id="termination_datatable_ajax">
                                            <thead>
                                                <tr role="row" class="heading">
                                                    <th> No. </th>
                                                    <th> Firma </th>
                                                    <th> Leadstatus </th>
                                                    <th> Provider </th>
                                                    <th> Cards </th>
                                                    <th> Responsive User </th>
                                                    <th> <?php echo lang('page_dt_action');?></th>
                                                </tr>
                                            </thead>
                                            <tbody> </tbody>
                                        </table>

                                    </div>
                                </div>
                            </div>
                            <!-- End: life time stats -->
                        </div>
                    </div>
                </div>
                <!-- END CONTENT BODY -->
            </div>
            <!-- END CONTENT -->
        </div>
        <!-- END CONTAINER -->

<?php $this->load->view('admin/footer.php'); ?>

<script>

$(document).on('click', '.delete-termination', function(event) {
    event.preventDefault();
    var $this = $(this);
    var id = $this.attr('data-id');

    if(confirm("Are you sure you want to delete this?")){
        $.ajax({
            url: "<?php echo base_url().'admin/termination/delete/';?>",
            type:"POST",
            dataType:'json',
            data:{id:id},
            success: function(data){
                if(data.status) {
                    $this.closest('tr').remove();
                    showtoast(data.response,'',data.message);
                    show_datatable();
                }
            }
        });
    } else {
        return false;
    }
});

$(document).on('click', '.sendmail-term', function(event) {
    event.preventDefault();
    var $this = $(this);
    var id = $this.attr('data-id');

    if(confirm("Are you sure you want to send mail?")){
        if($this.hasClass('fresh')) {
            $this.removeClass('fresh');
            $this.text('');
            $this.html('<i class="fa fa-spinner fa-spin" style="font-size:24px"></i>');
            $.ajax({
                url: "<?php echo base_url().'admin/termination/appointmentConfirmation/';?>",
                type:"POST",
                dataType:'json',
                data:{id:id},
                success: function(data){
                    if(data.status) {
                        $this.addClass('fresh');
                        $this.text('Appointment Confirmation');
                        showtoast(data.response,'',data.message);
                        show_datatable();
                    }
                }
            });
        }
    } else {
        return false;
    }
});

show_datatable();
function show_datatable() {
    $('#termination_datatable_ajax').DataTable({
    "processing": true,
    "pageLength" : 10,
    "serverSide": true,
    "bDestroy": true,
    "ajax":{
         "url": "<?php echo base_url('admin/termination/index') ?>",
         "dataType": "json",
         "type": "POST",
    },
    createdRow: function ( row, data, index ) {
    switch (data.status) {
        case "0":
            $(row).addClass("clrwhite");
            break;
        case "1":
            $(row).addClass("clrRed");
            break;
        case "2":
            $(row).addClass("clrGreen");
            break;
    };
    },
  "columns": [
      { "data": "id"},
      { "data": "company_name"},
      { "data": "leadStatus"},
      { "data": "providerName"},
      { "data": "cards"},
      { "data": "responsiUser"},
      { "data": "action"},
   ]
    });
}
</script>