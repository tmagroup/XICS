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
                                <span><?php echo lang('page_infodocuments');?></span>
                            </li>

                        </ul>

                    </div>
                    <!-- END PAGE BAR -->


                    <!-- BEGIN PAGE MESSAGE-->
                    <?php $this->load->view('admin/alerts'); ?>
                    <!-- BEGIN PAGE MESSAGE-->


                    <!-- BEGIN PAGE TITLE-->
                    <h3 class="page-title">
                        <i class="fa fa-file"></i>
                        <?php
                        echo lang('page_manage_infodocument');
                        ?>

                    </h3>
                    <!-- END PAGE TITLE-->

                    <div class="row">



                            <!-- BEGIN SAMPLE FORM PORTLET-->
                            <div class="portlet light portlet-fit portlet-datatable bordered">
                                <div class="portlet-body form">

                                    <?php
                                    if($GLOBALS['infodocument_permission']['create']){
                                        ?>
                                        <div class="clearfix">&nbsp;</div>
                                        <div class="col-md-12">
                                            <a href="javascript:void(0);" onclick="addeditInfodocumentAjax('<?php echo base_url('admin/infodocuments/addInfodocument');?>','','<?php echo sprintf(lang('page_create_infodocument'),lang('page_document'));?>');" class="btn sbold blue btn-sm"><i class="fa fa-file-pdf-o"></i> <?php echo sprintf(lang('page_create_infodocument'),lang('page_document'));?></a>
                                        </div>
                                        <?php
                                    }
                                    ?>

                                    <div class="clearfix">&nbsp;</div>
                                    <div class="col-md-12">
                                        <div class="table-container">

                                            <div class="table-actions-wrapper"></div>

                                            <table class="table table-striped table-bordered table-hover dt-responsive" width="100%" id="infodocument_datatable_ajax">
                                                <thead>
                                                    <tr role="row" class="heading">
                                                        <th width="50%"> <?php echo lang('page_dt_infodocumenttitle');?></th>
                                                        <th width="10%"> <?php echo lang('page_fl_provider');?></th>
                                                        <th width="20%"> <?php echo lang('page_fl_date');?></th>
                                                        <th width="20%"> <?php echo lang('page_dt_action');?></th>
                                                        <th width="1%"> <?php echo lang('page_dt_infodocumentid');?></th>
                                                    </tr>
                                                </thead>
                                                <tbody> </tbody>
                                            </table>

                                        </div>
                                    </div>


                                    <div class="clearfix"></div>
                                </div>
                            </div>


                    </div>


                </div>
                <!-- END CONTENT BODY -->
            </div>
            <!-- END CONTENT -->


        </div>
        <!-- END CONTAINER -->

<script>
    var admin_url = '<?php echo base_url('admin/infodocuments/ajax');?>';
    var func_TableDatatablesAjax = 'TableCustomDatatablesAjax';
    var datatable_id = 'infodocument_datatable_ajax';
    var datatable_pagelength = '<?php echo get_option('tables_pagination_limit');?>';
    var datatable_columnDefs = 2;
    var datatable_columnDefs2 = 2;
    var datatable_sortColumn = 0;
    var datatable_sortColumnBy = 'asc';
    var datatable_hide_columns = 4;
</script>

<script>
    var form_id = 'addeditInfodocumentModalAjax';
    var func_FormValidation = 'FormCustomValidation';
    var inner_msg_id = 'addeditInfodocumentModalAjax #alert_modal';

    function after_func_FormValidation(){

        var form1 = $('#addeditInfodocumentModalAjax');
        var error1 = $('#addeditInfodocumentAjax #alert_modal .alert-danger');
        var success1 = $('#addeditInfodocumentAjax #alert_modal .alert-success');

        form1.validate({
            errorElement: 'span', //default input error message container
            errorClass: 'help-block help-block-error', // default input error message class
            focusInvalid: false, // do not focus the last invalid input
            ignore: "",  // validate all fields including form hidden input

            rules: {
                documenttitle: {
                    required: true
                },
                documentfile: {
                    // required: true,
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

<?php $this->load->view('admin/footer.php'); ?>
<?php $this->load->view('admin/infodocuments/infodocumentjs');?>