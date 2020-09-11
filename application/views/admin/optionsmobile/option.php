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
                                <a href="<?php echo base_url('admin/optionsmobile');?>"><?php echo lang('page_optionsmobile');?></a>
                                <i class="fa fa-circle"></i>
                            </li>

                            <li>
                                <span>
                                    <?php
                                    if(isset($optionmobile['optionnr']) && $optionmobile['optionnr']>0){
                                        echo lang('page_edit_optionmobile');
                                    }
                                    else
                                    {
                                        echo lang('page_create_optionmobile');
                                    }
                                    ?>
                                </span>
                            </li>

                        </ul>

                    </div>
                    <!-- END PAGE BAR -->

                    <!-- BEGIN PAGE MESSAGE-->
                    <?php $this->load->view('admin/alerts'); ?>
                    <!-- BEGIN PAGE MESSAGE-->

                    <!-- BEGIN PAGE TITLE-->
                    <h3 class="page-title">

                        <?php
                        if(isset($optionmobile['optionnr']) && $optionmobile['optionnr']>0){
                            ?>
                            <i class="fa fa-pencil"></i>
                            <?php
                            echo lang('page_edit_optionmobile');
                        }
                        else
                        {
                            ?>
                            <i class="fa fa-plus"></i>
                            <?php
                            echo lang('page_create_optionmobile');
                        }
                        ?>

                    </h3>
                    <!-- END PAGE TITLE-->
                    <!-- END PAGE HEADER-->



                    <div class="row">
                        <?php echo form_open($this->uri->uri_string(), array('enctype' => "multipart/form-data", 'id' => 'form_optionmobile') );?>

                        <div class="col-md-6">
                            <!-- BEGIN SAMPLE FORM PORTLET-->
                            <div class="portlet light bordered">
                                <div class="portlet-body form">

                                        <div class="form-body">

                                            <div class="form-group">
                                                <label><?php echo lang('page_fl_optiontitle');?> <span class="required"> * </span></label>
                                                <?php echo form_input('optiontitle', isset($optionmobile['optiontitle'])?$optionmobile['optiontitle']:'', 'class="form-control"');?>
                                            </div>

                                            <div class="form-group">
                                                <label><?php echo lang('page_fl_price');?> <span class="required"> * </span></label>
                                                <?php echo form_input('price', isset($optionmobile['price'])?$optionmobile['price']:'', 'class="form-control"');?>
                                            </div>

                                            <div class="form-group">
                                                <label><?php echo lang('page_fl_runningtime');?> <span class="required"> * </span></label>
                                                <?php echo form_input('runningtime', isset($optionmobile['runningtime'])?$optionmobile['runningtime']:'', 'class="form-control"');?>
                                            </div>

                                            <div class="form-group">
                                                <label><?php echo lang('page_fl_provider');?> </label>
                                                <?php echo form_dropdown('provider', provider_values(), isset($optionmobile['provider'])?$optionmobile['provider']:'', 'class="form-control"');?>
                                            </div>

                                        </div>

                                </div>
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
                                            <button type="submit" class="btn blue"><?php echo lang('save');?></button>
                                            <a href="<?php echo base_url('admin/optionsmobile')?>"><button type="button" class="btn default"><?php echo lang('cancel');?></button></a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- END SAMPLE FORM PORTLET-->
                        </div>

                        <?php echo form_close();?>
                    </div>


                </div>
                <!-- END CONTENT BODY -->
            </div>
            <!-- END CONTENT -->


        </div>
        <!-- END CONTAINER -->

<script>
    var form_id = 'form_optionmobile';
    var func_FormValidation = 'FormCustomValidation';

    function after_func_FormValidation(form1, error1, success1){

        form1.validate({
            errorElement: 'span', //default input error message container
            errorClass: 'help-block help-block-error', // default input error message class
            focusInvalid: false, // do not focus the last invalid input
            ignore: "",  // validate all fields including form hidden input
            rules: {
                optiontitle: {
                    minlength: 2,
                    required: true
                },
                price: {
                    required: true
                },
                runningtime: {
                    required: true
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