<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<?php $this->load->view('admin/header.php'); ?>

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
                                <a href="<?php echo base_url('admin/monitorings');?>"><?php echo lang('page_monitorings');?></a>
                                <i class="fa fa-circle"></i>
                            </li>

                            <li>
                                <span>
                                    <?php
                                    echo lang('page_detail_monitoring');
                                    ?>
                                </span>
                            </li>

                        </ul>

                    </div>
                    <!-- END PAGE BAR -->


                    <!-- BEGIN PAGE MESSAGE-->
                    <?php $this->load->view('admin/alerts'); ?>
                    <!-- BEGIN PAGE MESSAGE-->


                    <div class="row">


                            <!-- BEGIN EXAMPLE TABLE PORTLET-->
                            <div class="portlet light"  style="border:0px; margin-bottom: 0px; padding-bottom: 0px;">
                                <div class="portlet-title" style="border:0px; margin-bottom: 0px; padding-bottom: 0px;">
                                    <div class="caption">
                                        <i class="fa fa-eye"></i>
                                        <?php
                                        echo lang('page_detail_monitoring');
                                        ?>
                                    </div>

                                    <div class="actions">

                                            <?php
                                            if($GLOBALS['monitoring_permission']['edit']){
                                                ?>
                                                <a href="<?php echo base_url('admin/monitorings/monitoring/'.$monitoring['monitoringnr']);?>" class="btn sbold blue btn-sm"><i class="fa fa-pencil"></i> <?php echo lang('page_edit_monitoring');?></a>
                                                <?php
                                            }
                                            ?>

                                    </div>

                                </div>
                            </div>
                            <!-- END EXAMPLE TABLE PORTLET-->


                        <?php //echo form_open($this->uri->uri_string(), array('enctype' => "multipart/form-data", 'id' => 'form_monitoring') );?>


                        <ul class="nav nav-tabs">
                            <li class="active">
                                <a href="#tab_profile" data-toggle="tab"><?php echo lang('page_lb_profile');?></a>
                            </li>
                        </ul>


                        <div class="tab-content">

                            <div class="tab-pane active" id="tab_profile">

                                <div class="col-md-6">


                                    <!-- BEGIN SAMPLE FORM PORTLET-->
                                    <div class="portlet light bordered">
                                        <div class="portlet-body form">

                                            <div class="form-body">

                                                <div class="form-group">
                                                    <label><?php echo lang('page_fl_company');?>:</label>
                                                    <?php echo $monitoring['company'];?>
                                                </div>

                                                <div class="form-group">
                                                    <label><?php echo lang('page_fl_monitoringstatus');?>:</label>
                                                    <?php echo $monitoring['monitoringstatusname'];?>
                                                </div>

                                                <?php
                                                if(get_user_role()!='customer'){
                                                    ?>
                                                    <div class="form-group">
                                                        <label><?php echo lang('page_fl_monitoringlink');?>:</label>
                                                        <?php echo $monitoring['monitoringlink'];?>
                                                    </div>

                                                    <div class="form-group">
                                                        <label><?php echo lang('page_fl_monitoringuser');?>:</label>
                                                        <?php echo $monitoring['monitoringuser'];?>
                                                    </div>

                                                    <div class="form-group">
                                                        <label><?php echo lang('page_fl_monitoringpass');?>:</label>
                                                        <?php echo $monitoring['monitoringpass'];?>
                                                    </div>
                                                    <?php
                                                }
                                                ?>

                                                <div class="form-group" style="display:none;">
                                                    <label><?php echo lang('page_fl_monitoringextracost');?>:</label>
                                                    <?php echo $monitoring['extracost'];?>
                                                </div>

                                                <div class="form-group">
                                                    <label><?php echo lang('page_fl_monitoringadditionalextracost');?>:</label>
                                                    <?php echo $monitoring['additional_extracost'];?>
                                                </div>

                                                <div class="form-group">
                                                    <label><?php echo lang('page_fl_monitoringratestatus');?>:</label>
                                                    <?php echo ($monitoring['ratestatus']==1)?lang('page_lb_current'):lang('page_lb_outdated');?>
                                                </div>

                                            </div>

                                        </div>
                                    </div>
                                    <!-- END SAMPLE FORM PORTLET-->

                                </div>


                                <div class="col-md-6">

                                    <!-- BEGIN SAMPLE FORM PORTLET-->
                                    <div class="portlet light bordered">
                                        <div class="portlet-body form">

                                            <div class="form-body">

                                                <div class="form-group">
                                                    <label><?php echo lang('page_fl_customer');?>:</label>
                                                    <?php echo $monitoring['customer'];?>
                                                </div>

                                                <div class="form-group">
                                                    <label><?php echo lang('page_fl_responsible');?>:</label>
                                                    <?php echo $monitoring['responsible'];?>
                                                </div>

                                                <div class="form-group">
                                                    <label><?php echo lang('page_dt_assignmentnr');?>:</label>
                                                    <a href="<?php echo base_url('admin/assignments/detail/'.$monitoring['assignmentnr']);?>"><?php echo isset($monitoring['assignmentnr_prefix'])?$monitoring['assignmentnr_prefix']:'' ?></a>
                                                </div>


                                            </div>

                                        </div>
                                    </div>
                                    <!-- END SAMPLE FORM PORTLET-->


                                    <?php
                                    if(isset($monitoring['additional_costs']) && count($monitoring['additional_costs'])>0){
                                        ?>
                                        <!-- BEGIN SAMPLE FORM PORTLET-->
                                        <div class="portlet light bordered">
                                            <div class="portlet-body form">

                                                <label><b><?php echo lang('page_lb_following_additional_cost');?>:</b></label>
                                                <table class="table table-bordered" width="100%">
                                                    <?php
                                                    foreach($monitoring['additional_costs'] as $additional_cost){
                                                        ?>
                                                        <tr>
                                                            <td width="50%"><?php echo $additional_cost['invoiceitem'];?></td><td class="text-danger"><?php echo format_money($additional_cost['invoicetotal'],$GLOBALS['currency_data']['currency_symbol']);?></td>
                                                        </tr>
                                                        <?php
                                                    }
                                                    ?>
                                                </table>

                                            </div>
                                        </div>
                                        <!-- END SAMPLE FORM PORTLET-->
                                        <?php
                                    }
                                    ?>
                                </div>

                                <div class="col-md-12">
                                    <h3 class="page-title"><i class="fa fa-eye"></i> Nicht genutzte Teilnehmer</h3>
                                    <table class="table table-bordered" id="tbl-assignment-csv">
                                        <thead>
                                            <tr>
                                                <th>No.</th>
                                                <th>Monitoringnr</th>
                                                <th><?php echo lang('page_mn_mobilenr')?></th>
                                                <th><?php echo lang('page_mn_simnr')?></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                    </table>
                                </div>

                            </div>

                        </div>


                        <div class="clearfix"></div>
                        <div class="col-md-12">
                            <!-- BEGIN SAMPLE FORM PORTLET-->
                            <div class="portlet light bordered">
                                <?php
                                $this->load->view('admin/monitorings/tab-comment', array('monitoring'=>$monitoring));
                                ?>
                            </div>
                            <!-- END SAMPLE FORM PORTLET-->
                        </div>


                        <div class="clearfix"></div>
                        <div class="col-md-6">
                            <!-- BEGIN SAMPLE FORM PORTLET-->
                            <div class="portlet light">
                                <div class="portlet-body">
                                    <div class="form-body">
                                        <div class="form-actions">
                                            <!--<button type="submit" class="btn blue"><?php echo lang('save');?></button>-->
                                            <a href="<?php echo base_url('admin/monitorings')?>"><button type="button" class="btn default"><?php echo lang('close');?></button></a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- END SAMPLE FORM PORTLET-->
                        </div>


                        <?php //echo form_close();?>
                    </div>


                </div>
                <!-- END CONTENT BODY -->
            </div>
            <!-- END CONTENT -->


        </div>
        <!-- END CONTAINER -->
<?php $this->load->view('admin/footer.php'); ?>
<?php $this->load->view('admin/monitorings/monitoringjs',array('monitoring'=>$monitoring));?>

<script type="text/javascript">
    show_datatable();
    function show_datatable() {
        $('#tbl-assignment-csv').DataTable({
        "processing": true,
        "pageLength" : 10,
        "serverSide": true,
        "bDestroy": true,
        "ajax":{
             "url": "<?php echo base_url('admin/monitorings/csvMonitoringData/') ?>",
             "dataType": "json",
             "type": "POST",
             "data": {
                "id": "<?php echo $this->uri->segment(4);?>"
            }
        },
      "columns": [
          { "data": "id" },
          { "data": "monitoringnr" },
          { "data": "mobilenr" },
          { "data": "simnr" }
        ]
        });
    }
</script>