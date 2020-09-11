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
                                <a href="<?php echo base_url('admin/ratesmobile');?>"><?php echo lang('page_ratesmobile');?></a>
                                <i class="fa fa-circle"></i>
                            </li>

                            <li>
                                <span>
                                    <?php
                                    if(isset($ratemobile['ratenr']) && $ratemobile['ratenr']>0){
                                        echo lang('page_edit_ratemobile');
                                    }
                                    else
                                    {
                                        echo lang('page_create_ratemobile');
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
                        if(isset($ratemobile['ratenr']) && $ratemobile['ratenr']>0){
                            ?>
                            <i class="fa fa-pencil"></i>
                            <?php
                            echo lang('page_edit_ratemobile');
                        }
                        else
                        {
                            ?>
                            <i class="fa fa-plus"></i>
                            <?php
                            echo lang('page_create_ratemobile');
                        }
                        ?>

                    </h3>
                    <!-- END PAGE TITLE-->
                    <!-- END PAGE HEADER-->



                    <div class="row">
                        <?php echo form_open($this->uri->uri_string(), array('enctype' => "multipart/form-data", 'id' => 'form_ratemobile') );?>

                        <div class="col-md-6">
                            <!-- BEGIN SAMPLE FORM PORTLET-->
                            <div class="portlet light bordered">
                                <div class="portlet-body form">

                                    <div class="form-body">

                                        <div class="form-group">
                                            <label><?php echo lang('page_fl_ratetitle');?> <span class="required"> * </span></label>
                                            <?php echo form_input('ratetitle', isset($ratemobile['ratetitle'])?$ratemobile['ratetitle']:'', 'class="form-control"');?>
                                        </div>

                                        <div class="form-group">
                                            <label><?php echo lang('page_fl_price');?> <span class="required"> * </span></label>
                                            <?php echo form_input('price', isset($ratemobile['price'])?$ratemobile['price']:'', 'class="form-control"');?>
                                        </div>

                                        <div class="form-group">
                                            <label><?php echo lang('page_fl_provider');?> </label>
                                            <?php echo form_dropdown('provider', provider_values(), isset($ratemobile['provider'])?$ratemobile['provider']:'', 'class="form-control" id="provider"');?>
                                        </div>

                                        <div class="form-group">
                                            <label><?php echo lang('page_fl_simcard_function');?> <span class="required"> * </span></label>
                                            <?php echo form_dropdown('simcard_function', $simcardfunctions, isset($ratemobile['simcard_function'])?$ratemobile['simcard_function']:'', 'class="form-control"');?>
                                        </div>

                                        <div class="form-group">
                                            <label><?php echo lang('page_fl_subn');?> <span class="required"> * </span></label>
                                            <?php echo form_dropdown('subn', $subs, isset($ratemobile['subn'])?$ratemobile['subn']:'', 'class="form-control"');?>
                                        </div>

                                        <div class="form-group">
                                            <label><?php echo lang('page_fl_mobileflaterate');?> <span class="required"> * </span></label>
                                            <?php echo form_dropdown('mobileflaterate', $mobileflaterates, isset($ratemobile['mobileflaterate'])?$ratemobile['mobileflaterate']:'', 'class="form-control"');?>
                                        </div>

                                        <?php /*<div class="form-group">
                                            <label><?php echo lang('page_fl_vodafoneflaterate');?> <span class="required"> * </span></label>
                                            <?php echo form_dropdown('vodafoneflaterate', $vodafoneflaterates, isset($ratemobile['vodafoneflaterate'])?$ratemobile['vodafoneflaterate']:'', 'class="form-control"');?>
                                        </div>*/ ?>
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
                                            <label><?php echo lang('page_fl_landingflaterate');?> <span class="required"> * </span></label>
                                            <?php echo form_dropdown('landingflaterate', $landlineflaterates, isset($ratemobile['landingflaterate'])?$ratemobile['landingflaterate']:'', 'class="form-control"');?>
                                        </div>

                                        <div class="form-group">
                                            <label><?php echo lang('page_fl_datavolume');?> <span class="required"> * </span></label>
                                            <?php
                                            $inputd = array(
                                                'type' => 'number',
                                                'name' => 'datavolume',
                                                'value' => isset($ratemobile['datavolume'])?$ratemobile['datavolume']:'',
                                                'class' => 'form-control'
                                            );
                                            echo form_input($inputd);?>
                                        </div>

                                        <div class="form-group">
                                            <label><?php echo lang('page_fl_eu_roaming');?> <span class="required"> * </span></label>
                                            <?php echo form_dropdown('eu_roaming', $euroamings, isset($ratemobile['eu_roaming'])?$ratemobile['eu_roaming']:'', 'class="form-control"');?>
                                        </div>

                                        <div class="form-group">
                                            <label><?php echo lang('page_fl_smsflaterate');?> <span class="required"> * </span></label>
                                            <?php echo form_dropdown('smsflaterate', $smsflaterates, isset($ratemobile['smsflaterate'])?$ratemobile['smsflaterate']:'', 'class="form-control"');?>
                                        </div>

                                        <div class="form-group">
                                            <label><?php echo lang('page_fl_extemptedterm');?> <span class="required"> * </span></label>
                                            <?php
                                            $inputd = array(
                                                'type' => 'number',
                                                'name' => 'extemptedterm',
                                                'value' => isset($ratemobile['extemptedterm'])?$ratemobile['extemptedterm']:'',
                                                'class' => 'form-control'
                                            );
                                            echo form_input($inputd);?>
                                        </div>

                                        <div class="form-group">
                                            <label><?php echo lang('page_fl_ultracard');?> </label>
                                            <?php echo form_dropdown('ultracard', $ultracards, isset($ratemobile['ultracard'])?$ratemobile['ultracard']:'', 'class="form-control"');?>
                                        </div>

                                    </div>
                                </div>
                            </div>
                            <!-- END SAMPLE FORM PORTLET-->
                        </div>

                        <div class="clearfix"></div>
                        <div class="col-md-12">

                            <div class="portlet light bordered">
                                <div class="portlet-body form extrafields">

                                    <div class="row">
                                        <?php
                                        $rc=6;
                                        if(isset($extrafields) && count($extrafields)>0){
                                            foreach($extrafields as $extrafield){
                                                if(($rc%6)==0){
                                                    // echo '<div class="row">';
                                                }
                                                ?>
                                                <div class="col-md-2 col-sm-3 col-xs-6" style="padding-bottom: 10px;" data-provider="<?= $extrafield['provider']?>">
                                                    <label><?php echo $extrafield['field_name'];?></label>

                                                    <?php
                                                    $inputd = array(
                                                        'type' => $extrafield['field_type'],
                                                        'name' => 'field_'.$extrafield['field_id'],
                                                        'value' => isset($extrafield['field_value'])?$extrafield['field_value']:'',
                                                        'placeholder' => 0,
                                                        'class' => 'form-control'
                                                    );
                                                    echo form_input($inputd);?>

                                                </div>
                                                <?php
                                                $rc++;

                                                if(($rc%6)==0){
                                                    // echo '</div>';
                                                }

                                            }
                                        }
                                        ?>
                                    </div>

                                </div>
                            </div>

                        </div>

                        <div class="clearfix"></div>
                        <div class="col-md-6">
                            <!-- BEGIN SAMPLE FORM PORTLET-->
                            <div class="portlet light">
                                <div class="portlet-body">
                                    <div class="form-body">
                                        <div class="form-actions">
                                            <button type="submit" class="btn blue"><?php echo lang('save');?></button>
                                            <a href="<?php echo base_url('admin/ratesmobile')?>"><button type="button" class="btn default"><?php echo lang('cancel');?></button></a>
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

<?php $this->load->view('admin/footer.php'); ?>

<script>
    var form_id = 'form_ratemobile';
    var func_FormValidation = 'FormCustomValidation';

    function after_func_FormValidation(form1, error1, success1){

        form1.validate({
                errorElement: 'span', //default input error message container
                errorClass: 'help-block help-block-error', // default input error message class
                focusInvalid: false, // do not focus the last invalid input
                ignore: "",  // validate all fields including form hidden input
                rules: {
                    ratetitle: {
                        minlength: 2,
                        required: true
                    },
                    price: {
                        required: true
                    },
                    simcard_function: {
                        required: true
                    },
                    subn: {
                        required: true
                    },
                    mobileflaterate: {
                        required: true
                    },
                    landingflaterate: {
                        required: true
                    },
                    vodafoneflaterate: {
                        // required: true
                    },
                    datavolume: {
                        number: true,
                        required: true
                    },
                    eu_roaming: {
                        required: true
                    },
                    smsflaterate: {
                        required: true
                    },
                    extemptedterm: {
                        digits: true,
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

    if ($('#provider').val()) {
        change_provider(1);
    }

    function change_provider(first_call) {
        if (!first_call) {
            $('.extrafields').find('[type="number"]').val('');
        }
        $('.extrafields').find('[data-provider]').hide();
        $('.extrafields').find('[data-provider="'+$('#provider').val()+'"]').show();
    }

    $('#provider').change(function(event) {
        change_provider();
    });
</script>