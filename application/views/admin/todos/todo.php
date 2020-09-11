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
                                <a href="<?php echo base_url('admin/todos');?>"><?php echo lang('page_todos');?></a>
                                <i class="fa fa-circle"></i>
                            </li>

                            <li>
                                <span>
                                    <?php
                                    if(isset($todo['todonr']) && $todo['todonr']>0){
                                        echo lang('page_edit_todo');
                                    }
                                    else
                                    {
                                        echo lang('page_create_todo');
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
                        if(isset($todo['todonr']) && $todo['todonr']>0){
                            ?>
                            <i class="fa fa-pencil"></i>
                            <?php
                            echo lang('page_edit_todo');
                        }
                        else
                        {
                            ?>
                            <i class="fa fa-plus"></i>
                            <?php
                            echo lang('page_create_todo');
                        }
                        ?>

                    </h3>
                    <!-- END PAGE TITLE-->
                    <!-- END PAGE HEADER-->

                    <div class="row">

                        <?php echo form_open($this->uri->uri_string(), array('enctype' => "multipart/form-data", 'id' => 'form_todo') );?>
                        <div class="col-md-6">


                            <!-- BEGIN SAMPLE FORM PORTLET-->
                            <div class="portlet light bordered">
                                <div class="portlet-body form">

                                    <div class="form-body">

                                        <div class="form-group">
                                            <label><?php echo lang('page_fl_todotitle');?> <span class="required"> * </span></label>
                                            <?php echo form_input('todotitle', isset($todo['todotitle'])?$todo['todotitle']:'', 'class="form-control"');?>
                                        </div>

                                        <!--<div class="form-group">
                                            <label><?php echo lang('page_fl_company');?> </label>
                                            <?php echo form_input('company', isset($todo['company'])?$todo['company']:'', 'class="form-control"');?>
                                        </div>-->

                                        <div class="form-group">
                                            <label><?php echo lang('page_fl_startdate');?> <span class="required"> * </span></label>

                                            <div class="input-group date form_date">
                                                <?php $dd = array('name'=>'startdate', 'class'=>'form-control', 'readonly'=>true, 'size'=>16, 'value'=> isset($todo['startdate'])?_d($todo['startdate']):'');
                                                echo form_input($dd);?>

                                                <span class="input-group-btn">
                                                    <button class="btn default date-set" type="button">
                                                        <i class="fa fa-calendar"></i>
                                                    </button>
                                                </span>
                                            </div>

                                        </div>


                                        <div class="form-group">
                                            <label><?php echo lang('page_fl_todostatus');?> <span class="required"> * </span></label>
                                            <?php echo form_dropdown('todostatus', $todostatus, isset($todo['todostatus'])?$todo['todostatus']:'', 'class="form-control"');?>
                                        </div>

                                        <div class="form-group">
                                            <label><?php echo lang('page_fl_customer');?></label>
                                            <?php echo form_dropdown('customer', $customers, isset($todo['customer'])?$todo['customer']:'', 'class="form-control" id="customer" ');?>
                                        </div>

                                        <div class="form-group">
                                            <label><?php echo lang('page_fl_responsible');?> <span class="required"> * </span></label>
                                            <?php /* echo form_dropdown('responsible', array(''=>lang('page_option_select')) ,'', 'class="form-control" id="responsible" '); */?>
                                            <?php echo form_dropdown('responsible', $responsibles, isset($ticket['responsible']) ? $ticket['responsible'] : '', 'class="form-control" id="responsible"'); ?>
                                        </div>

                                        <div class="form-group">
                                            <label><?php echo lang('page_fl_teamwork');?> </label>
                                            <?php //echo form_dropdown('teamwork[]', $teamworks, isset($todo['teamwork'])?$todo['teamwork']:'', 'class="form-control select2-multiple" id="teamwork" multiple');?>

                                            <?php
                                            $selected_teamwork = isset($todo['teamwork'])?$todo['teamwork']:'';
                                            $selected_teamwork = explode(",", $selected_teamwork);
                                            ?>

                                            <select name="teamwork[]" class="form-control select2-multiple" id="teamwork" multiple>
                                            <?php
                                            foreach($teamworks as $teamwork){
                                                $selected = '';
                                                if(in_array($teamwork['userid'],$selected_teamwork)){
                                                    $selected = ' selected';
                                                }
                                                ?>
                                                <option value="<?php echo $teamwork['userid']?>" <?php echo $selected;?>><?php echo $teamwork['name']?></option>
                                                <?php
                                            }
                                            ?>
                                            </select>
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
                                            <label><?php echo lang('page_fl_tododesc');?> <span class="required"> * </span></label>
                                            <?php echo form_textarea('tododesc', isset($todo['tododesc'])?html_entity_decode($todo['tododesc']):'', 'class="form-control" id="tododesc"');?>
                                        </div>

                                        <div class="form-group">
                                            <label><?php echo lang('page_fl_reminderdate');?> <span class="required"> * </span></label>

                                            <div class="input-group date form_date">
                                                <?php $dd = array('name'=>'reminderdate', 'class'=>'form-control', 'readonly'=>true, 'size'=>16, 'value'=> isset($todo['reminderdate'])?_d($todo['reminderdate']):'');
                                                echo form_input($dd);?>

                                                <span class="input-group-btn">
                                                    <button class="btn default date-set" type="button">
                                                        <i class="fa fa-calendar"></i>
                                                    </button>
                                                </span>
                                            </div>

                                        </div>

                                        <div class="form-group">
                                            <label>
                                                <?php echo lang('page_fl_reminderway');?>
                                                <?php
                                                $reminderway = (isset($todo['reminderway']) && $todo['reminderway']==1)?true:false;
                                                $dc = array('name'=>'reminderway','class'=>'form-control','checked'=>$reminderway, 'value'=>1);
                                                echo form_checkbox($dc);?>
                                            </label>
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
                                            <a href="<?php echo base_url('admin/todos')?>"><button type="button" class="btn default"><?php echo lang('cancel');?></button></a>
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

<style>#cke_1_bottom { display: none; }</style>
<script src="https://cdn.ckeditor.com/4.14.0/standard/ckeditor.js"></script>
<script>
    CKEDITOR.replace('tododesc', {
        removeButtons: 'Source,About,Image,Anchor,Scayt,Styles,Maximize,Outdent,Indent,Blockquote'
    });

    var form_id = 'form_todo';
    var func_FormValidation = 'FormCustomValidation';

    function after_func_FormValidation(form1, error1, success1){

        form1.validate({
            errorElement: 'span', //default input error message container
            errorClass: 'help-block help-block-error', // default input error message class
            focusInvalid: false, // do not focus the last invalid input
            ignore: "",  // validate all fields including form hidden input

            rules: {
                todotitle: {
                    minlength: 2,
                    required: true
                },
                /*company: {
                    minlength: 2,
                    required: true
                },*/
                startdate: {
                    required: true
                },
                todostatus: {
                    required: true
                },
                /*customer: {
                    required: true
                },*/
                responsible: {
                    required: true
                },
                /*teamwork: {
                    required: true
                },*/
                tododesc: {
                    // maxlength: 255,
                    required: true
                },
                reminderdate: {
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
<?php $this->load->view('admin/todos/todojs',array('todo'=>isset($todo)?$todo:''));?>