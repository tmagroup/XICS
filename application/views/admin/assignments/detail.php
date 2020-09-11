<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<?php $this->load->view('admin/header.php'); ?>
<style>
.divcenter{
    bottom: 0;
    margin: auto;
    /*position: absolute;*/
    left: 0;
    right: 0;
}
.divcenter2{
    text-align: left;
    bottom: 0;
    margin: auto;
    position: absolute;
    left: 0;
    right: 0;
}
</style>
<script src="<?php echo base_url('assets/global/plugins/jquery.min.js'); ?>" type="text/javascript"></script>
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
                                <a href="<?php echo base_url('admin/assignments');?>"><?php echo lang('page_assignments');?></a>
                                <i class="fa fa-circle"></i>
                            </li>

                            <li>
                                <span>
                                    <?php
                                    echo lang('page_detail_assignment');
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
                                        <i class="fa fa-file"></i>
                                        <?php
                                        echo lang('page_detail_assignment');
                                        ?>
                                    </div>

                                    <div class="actions">


                                            <?php
                                            if($GLOBALS['a_contractorder_permission']['create']){
                                                ?>
                                                <a href="javascript:void(0);" onclick="FormTicketContractOrderAjax('<?php echo base_url('admin/assignments/generateTicket/'.$assignment['assignmentnr']);?>','<?php echo $assignment['assignmentnr'];?>','contractorder','<?php echo lang('page_ticket')." - ".lang('page_lb_contractorder');?>');" class="btn sbold green btn-sm"><i class="fa fa-life-ring"></i> <?php echo lang('page_lb_contractorder');?></a>
                                                <?php
                                            }
                                            ?>


                                            <?php
                                            if(isset($GLOBALS['current_user']->userrole) && $GLOBALS['current_user']->userrole!=6){
                                                ?>

                                                        <a href="javascript:void(0);" onclick="FormTicketAjax('<?php echo base_url('admin/assignments/generateTicket/'.$assignment['assignmentnr']);?>','<?php echo $assignment['assignmentnr'];?>','abolock','<?php echo lang('page_ticket')." - ".lang('page_lb_abolock');?>','<?php echo lang('page_lb_abolock_popup_ask');?>','');" class="btn sbold green btn-sm"> <i class="fa fa-life-ring"></i> <?php echo lang('page_lb_abolock');?></a>
                                                        <a href="javascript:void(0);" onclick="FormTicketAjax('<?php echo base_url('admin/assignments/generateTicket/'.$assignment['assignmentnr']);?>','<?php echo $assignment['assignmentnr'];?>','internationaltelephonylock','<?php echo lang('page_ticket')." - ".lang('page_lb_internationaltelephonylock');?>','<?php echo lang('page_lb_internationaltelephonylock_popup_ask');?>','');" class="btn sbold green btn-sm"> <i class="fa fa-life-ring"></i> <?php echo lang('page_lb_internationaltelephonylock');?></a>
                                                        <a href="javascript:void(0);" onclick="FormTicketAjax('<?php echo base_url('admin/assignments/generateTicket/'.$assignment['assignmentnr']);?>','<?php echo $assignment['assignmentnr'];?>','roaminglock','<?php echo lang('page_ticket')." - ".lang('page_lb_roaminglock');?>','<?php echo lang('page_lb_roaminglock_popup_ask');?>','');" class="btn sbold green btn-sm"> <i class="fa fa-life-ring"></i> <?php echo lang('page_lb_roaminglock');?></a>

                                                <?php
                                            }
                                            ?>

                                            <?php
                                            if($GLOBALS['assignment_permission']['edit']){
                                                ?>
                                                <a href="<?php echo base_url('admin/assignments/assignment/'.$assignment['assignmentnr']);?>" class="btn sbold blue btn-sm"><i class="fa fa-pencil"></i> <?php echo lang('page_edit_assignment');?></a>
                                                <?php
                                            }
                                            ?>

                                    </div>

                                </div>
                            </div>
                            <!-- END EXAMPLE TABLE PORTLET-->


                        <?php
                        //Only Editable
                        $tab_document = '';
                        $tab_reminder = '';
                        $tab_legitimation = '';
                        $tab_hardwareassignment = '';

                        if(empty($assignment['assignmentnr'])){
                           $tab_document = 'none';
                           $tab_reminder = 'none';
                           $tab_legitimation = 'none';
                        }

                        if(get_user_role()=='customer'){
                            $tab_reminder = 'none';
                            $tab_hardwareassignment = '';
                        }

                        //POS - When he go to Assignment and click on the Detailview of one assignment he should not see the Tab "Legitimation", "Dokumente", "Erinnerung hinzufügen"
                        if($GLOBALS['current_user']->userrole==6){
                            $tab_document = 'none';
                            $tab_reminder = 'none';
                            $tab_legitimation = 'none';
                        }

                        //Hardwarebestand Right for this has admin, customer, saleman, salesmanager.
                        $tab_hardwareinventory = 'none';
                        $tab_invoice = 'none';

                        //if(get_user_role()=='customer' || $GLOBALS['current_user']->userrole==1 || $GLOBALS['current_user']->userrole==2 || $GLOBALS['current_user']->userrole==3){
                            //$tab_hardwareinventory = 'none';
                            //$tab_invoice = '';
                        //}

                        if($GLOBALS['a_hardwareinventory_permission']['view']){
                            $tab_hardwareinventory = '';
                        }
                        if($GLOBALS['a_invoice_permission']['view']){
                            $tab_invoice = '';
                        }
                        ?>


                        <?php //echo form_open($this->uri->uri_string(), array('enctype' => "multipart/form-data", 'id' => 'form_assignment') );?>


                        <ul class="nav nav-tabs">
                            <li class="active">
                                <a href="#tab_profile" data-toggle="tab"><?php echo lang('page_lb_profile');?></a>
                            </li>
                            <li style="display:<?php echo $tab_legitimation;?>">
                                <a href="#tab_legitimation" data-toggle="tab"><?php echo lang('page_lb_legitimation');?></a>
                            </li>
                            <li style="display:<?php echo $tab_document;?>">
                                <a href="#tab_document" data-toggle="tab"><?php echo lang('page_lb_document');?></a>
                            </li>
                            <li style="display:<?php echo $tab_reminder;?>">
                                <a href="#tab_reminder" data-toggle="tab"><?php echo lang('page_lb_reminder');?></a>
                            </li>
                            <li style="display:<?php echo $tab_hardwareassignment;?>">
                                <a href="#tab_hardwareassignment" data-toggle="tab"><?php echo lang('page_hardwareassignment');?></a>
                            </li>
                            <li style="display:<?php echo $tab_hardwareinventory;?>">
                                <a href="#tab_hardwareinventory" data-toggle="tab"><?php echo lang('page_lb_hardwareinventory');?></a>
                            </li>
                            <li style="display:<?php echo $tab_invoice;?>">
                                <a href="#tab_invoice" data-toggle="tab"><?php echo lang('page_lb_invoice');?></a>
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
                                                    <label><?php echo lang('page_fl_assignment_company');?>:</label>
                                                    <?php echo $assignment['customer_company'];?>
                                                </div>

                                                <div class="form-group">
                                                    <label><?php echo lang('page_fl_assignmentdate');?>:</label>
                                                    <?php echo _d($assignment['assignmentdate']);?>
                                                </div>

                                                <div class="form-group">
                                                    <label><?php echo lang('page_fl_assignmentstatus');?>:</label>
                                                    <?php echo $assignment['assignmentstatus'];?>
                                                </div>

                                                <div class="form-group">
                                                    <label><?php echo lang('page_fl_assignmentprovider');?>:</label>
                                                    <?php echo $assignment['providercompanynr'];?>
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

                                                <!--<div class="form-group">
                                                    <label><?php //echo lang('page_fl_customer');?>:</label>
                                                    <?php //echo $assignment['customer'];?>
                                                </div>-->
                                                 <?php if(isset($assignment['provider']) && $assignment['provider'] != '') { ?>
                                                    <div class="form-group">
                                                        <!-- <label><?php //echo lang('page_fl_provider_logo');?>:</label><br/> -->
                                                        <img src="<?php echo base_url().$assignment['provider'];?>" alt="not logo" width="120px">
                                                    </div>
                                                <?php } ?>

                                                <div class="form-group">
                                                    <label><?php echo lang('page_fl_responsible');?>:</label>
                                                    <?php echo $assignment['responsible'];?>
                                                </div>

                                                <div class="form-group">
                                                    <label><?php echo lang('page_fl_recommend');?>:</label>
                                                    <?php echo $assignment['recommend'];?>
                                                </div>

                                            </div>

                                        </div>
                                    </div>
                                    <!-- END SAMPLE FORM PORTLET-->

                                </div>



                                <div class="clearfix"></div>
                                <div class="col-md-12">

                                    <!-- BEGIN SAMPLE FORM PORTLET-->
                                    <div class="portlet light bordered">
                                        <div class="portlet-body form">


                                            <div class="row">
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label><?php echo lang('page_fl_newdiscountlevel');?>:</label>
                                                        <?php echo $assignment['newdiscountlevel'];?>
                                                    </div>
                                                </div>
                                            </div>


                                            <div class="portlet-title">
                                                <div class="caption font-dark">
                                                    <span class="caption-subject font-green-sharp sbold"><?php echo lang('page_lb_assignmentproducts');?></span>
                                                    <?php if (get_user_role()!='customer'): ?>
                                                        <a href="<?= base_url('admin/assignments/export_product_csv/'.$assignment['assignmentnr'])?>" class="btn sbold green pull-right" ><?php echo lang('page_exportcsv');?></a>
                                                    <?php endif ?>
                                                    <div class="clearfix"></div>
                                                </div>
                                            </div>

                                            <div class="form-body">
                                            <?php echo form_open(base_url('admin/assignments/saveEmployees'), array('enctype' => "multipart/form-data", 'id' => 'form_assignment_employee') );?>
                                                <div class="form-group">
                                                    <!--<div class="table-responsive no-dt">-->
                                                        <!-- <table class="table table-striped table-bordered table-hover dt-responsive" width="100%" id="assignment_detail_datatable"> -->
                                                        <table class="table table-striped table-bordered table-hover dt-responsive" width="100%" id="assignment_detail_datatable_ajax">
                                                            <thead>
                                                                <tr role="row" class="heading">
                                                                    <th class="text-nowrap text-center"><?php echo lang('page_fl_positionnr');?>.</th>
                                                                    <th class="text-nowrap text-center"><?php echo lang('page_fl_simnr');?></th>
                                                                    <th class="text-nowrap text-center"><?php echo lang('page_fl_mobilenr');?></th>
                                                                    <th class="text-nowrap text-center"><?php echo lang('page_fl_employee');?></th>
                                                                    <th class="text-nowrap text-center"><?php echo lang('page_fl_vvl_neu');?></th>
                                                                    <th class="text-nowrap text-center"><?php echo lang('page_fl_ratetitle');?></th>
                                                                    <th class="text-nowrap text-center"><?php echo lang('page_fl_value');?></th>
                                                                    <th class=""><?php echo lang('page_fl_extemtedterm');?></th>
                                                                    <th class=""><?php echo lang('page_fl_subscriptionlock');?></th>
                                                                    <th class="text-nowrap text-center"><?php echo lang('page_fl_optiontitle');?></th>
                                                                    <th class="text-nowrap text-center"><?php echo lang('page_fl_value');?></th>
                                                                    <th class="text-nowrap text-center"><?php echo lang('page_fl_hardware');?></th>
                                                                    <th class=""><?php echo lang('page_fl_cardstatus');?></th>
                                                                    <th class="text-center"><?php echo lang('page_fl_endofcontract');?></th>
                                                                    <?php if (get_user_role()!='customer'): ?>
                                                                        <th class=""><?php echo lang('page_fl_finished');?></th>
                                                                    <?php endif ?>

                                                                    <?php
                                                                    if(isset($GLOBALS['current_user']->userrole) && $GLOBALS['current_user']->userrole!=6){
                                                                        ?>
                                                                        <th></th>
                                                                        <?php
                                                                    }
                                                                    ?>
                                                                </tr>
                                                            </thead>
                                                            <tbody id="assignmentproduct_inputbox">
                                                            </tbody>
                                                        </table>


                                                        <?php
                                                        if(get_user_role()=='customer'){
                                                            ?>
                                                            <button id="save_employees" type="button" class="btn blue"><?php echo lang('save_all');?></button>
                                                            <?php
                                                        }
                                                        ?>

                                                    <!--</div>-->
                                                </div>
                                            <?php echo form_close();?>
                                            </div>

                                        </div>
                                    </div>
                                    <!-- END SAMPLE FORM PORTLET-->

                                </div>

                            </div>

                            <div class="tab-pane" id="tab_legitimation" style="display:<?php echo $tab_document;?>">

                                <?php
                                $this->load->view('admin/assignments/tab-legitimation-detail', array('assignment'=>$assignment));
                                ?>

                            </div>

                            <div class="tab-pane" id="tab_document" style="display:<?php echo $tab_document;?>">

                                <?php
                                $this->load->view('admin/assignments/tab-document', array('assignment'=>$assignment,'categories'=>$categories));
                                ?>

                            </div>

                            <div class="tab-pane" id="tab_reminder" style="display:<?php echo $tab_reminder;?>">

                                <?php
                                $this->load->view('admin/assignments/tab-reminder', array('assignment'=>$assignment));
                                ?>

                            </div>

                            <div class="tab-pane" id="tab_hardwareassignment" style="display:<?php echo $tab_hardwareassignment;?>">

                                <?php
                                $this->load->view('admin/assignments/tab-hardwareassignment', array('hardwareassignment'=>$hardwareassignment, 'hardwareassignmentproducts'=>$hardwareassignmentproducts));
                                ?>

                            </div>

                            <div class="tab-pane" id="tab_hardwareinventory" style="display:<?php echo $tab_hardwareinventory;?>">

                                <?php
                                $this->load->view('admin/assignments/tab-hardwareinventory', array('hardwareassignment'=>$hardwareassignment, 'hardwareassignmentproducts'=>$hardwareassignmentproducts));
                                ?>

                            </div>

                            <div class="tab-pane" id="tab_invoice" style="display:<?php echo $tab_invoice;?>">

                                <?php
                                $this->load->view('admin/assignments/tab-invoice', array('assignment'=>$assignment));
                                ?>

                            </div>
                        </div>

                        <div class="clearfix"></div>
                        <div class="col-md-6">
                            <!-- BEGIN SAMPLE FORM PORTLET-->
                            <div class="portlet light">
                                <div class="portlet-body">
                                    <div class="form-body">
                                        <div class="form-actions">
                                            <!--<button type="submit" class="btn blue"><?php echo lang('save');?></button>-->
                                            <a href="<?php echo base_url('admin/assignments')?>"><button type="button" class="btn default"><?php echo lang('close');?></button></a>
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



<script type="text/javascript">
$(document).ready(function() {
    var datatable_id = 'assignment_detail_datatable';
    var datatable_pagelength = <?php echo (int)get_option('tables_pagination_limit'); ?>;
    var datatable_sortcolumn = 0;
    var datatable_sortcolumn_by = 'asc';
    var datatable_language_url = '//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/German.json';

    setTimeout(function() {
        $('#'+ datatable_id).DataTable({
            pageLength: datatable_pagelength,
            language: {url: datatable_language_url},
            order: [[datatable_sortcolumn, datatable_sortcolumn_by]],
            responsive: {details: {}},
            filter: true,
            pagingType: 'bootstrap_full_number',
            lengthMenu: [[10, 20, 50, 100, 150], [10, 20, 50, 100, 150]],
            columns: [{
                searchable: false,
            }, {
                searchable: false,
            }, {
                searchable: true,
            }, {
                searchable: true,
            }, {
                searchable: false,
            }, {
                searchable: false,
            }, {
                searchable: false,
            }, {
                searchable: false,
            }, {
                searchable: false,
            }, {
                searchable: false,
            }, {
                searchable: false,
            }, {
                searchable: false,
            }, {
                searchable: false,
            }, {
                searchable: false,
            }, {
                searchable: false,
            }
            <?php if (get_user_role()!='customer'){ ?>
                , {
                    searchable: false,
                }
            <?php } ?>
            ],
        });

        setTimeout(function() {
            $('#'+ datatable_id +'_wrapper').removeClass('dataTables_extended_wrapper');
            $('#'+ datatable_id +'_wrapper .dataTables_filter input').attr('placeholder', '<?php echo lang('page_fl_mobilenr'); ?>');
        }, 1000);
    }, 1000);
});
</script>




<script>
    var func_TableDatatables_c = 'TableCustomDatatables';
    var datatable_id_c = 'assignment_detail_datatable';
    var datatable_pagelength_c = '<?php echo get_option('tables_pagination_limit');?>';
    var datatable_sortColumn_c = 0;
    var datatable_sortColumnBy_c = 'asc';
</script>

<?php $this->load->view('admin/footer.php'); ?>
<?php $this->load->view('admin/assignments/assignmentjs',array('assignment'=>$assignment, 'remindersubjects'=>$remindersubjects, 'mobileoptions'=>$mobileoptions, 'hardwares'=>$hardwares, 'mobilerates'=>$mobilerates));?>

<script>
/* Manage without Ajax */
function TableCustomDatatables(){

    /*if(typeof datatable_hide_columns == 'undefined'){
            var datatable_hide_columns=0;
    }*/
    var language_url = '//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/German.json';

    var table = $('#'+datatable_id_c);
    table.DataTable( {

        "pageLength": datatable_pagelength_c,
        "pagingType": "bootstrap_full_number",
        "filter": true,

        // setup responsive extension: http://datatables.net/extensions/responsive/
        responsive: {
                details: {
                        /*type: 'column',
                        target: 'tr'*/
                }
        },

        "language": {
                "url": language_url
        },

        "lengthMenu": [
                [10, 20, 50, 100, 150],
                [10, 20, 50, 100, 150] // change per page values here
        ],

        "columnDefs": [
            {
                "targets": 0,
                "searchable": false,
            },
            {
                "targets": 2,
                "searchable": false,
            },
            {
                "targets": 3,
                "searchable": false,
            },
            {
                "targets": 4,
                "searchable": false,
            },
            {
                "targets": 5,
                "searchable": false,
            },
            {
                "targets": 6,
                "searchable": false,
            },
            {
                "targets": 7,
                "searchable": false,
            },
            {
                "targets": 8,
                "searchable": false,
            },
            {
                "targets": 9,
                "searchable": false,
            },
            {
                "targets": 10,
                "searchable": false,
            },
            {
                "targets": 11,
                "searchable": false,
            },
            {
                "targets": 12,
                "searchable": false,
            },

            <?php
            if(isset($GLOBALS['current_user']->userrole) && $GLOBALS['current_user']->userrole!=6){
                ?>
                {
                    "targets": 13,
                    "searchable": false,
                }
                <?php
            }
            ?>
        ],

        "order": [
                [datatable_sortColumn_c, datatable_sortColumnBy_c]
        ], // set first column as a default sort by asc

    });

    var tableWrapper = jQuery('#'+datatable_id_c+'_wrapper');
    //jQuery(tableWrapper).removeClass('dataTables_extended_wrapper');
    setInterval(function (){ jQuery('#'+datatable_id_c+'_wrapper').removeClass('dataTables_extended_wrapper');
        $('#'+datatable_id_c+'_wrapper .dataTables_filter input').attr("placeholder", "<?php echo lang('page_fl_mobilenr');?>");
    },100);
}
// TableCustomDatatables();
</script>
