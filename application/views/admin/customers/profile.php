<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<?php $this->load->view('admin/header.php'); ?>
<style>
.control-label input.required, .form-group input.required{
    color: #4d6b8a;
    padding: 6px 12px;
    font-size: 14px;
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
                                <span>
                                    <?php
                                    echo lang('nav_my_profile');
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
                        <i class="fa fa-user"></i>
                        <?php
                        echo lang('page_profile_setting');
                        ?>
                    </h3>
                    <!-- END PAGE TITLE-->
                    <!-- END PAGE HEADER-->
                    
                   
                    
                    <div class="row">
                      
                        <?php echo form_open($this->uri->uri_string(), array('enctype' => "multipart/form-data", 'id' => 'form_customer') );?>        

                        <div class="col-md-6">

                            <!-- BEGIN SAMPLE FORM PORTLET-->
                            <div class="portlet light bordered">                                
                                <div class="portlet-body form">

                                    <div class="form-body">

                                        <div class="form-group">
                                            <label><?php echo lang('page_fl_customerthumb');?> <!--<span class="required"> * </span>--></label>
                                            <div class="clearfix"></div>
                                            <div class="fileinput fileinput-new" data-provides="fileinput">
                                                <div class="fileinput-new thumbnail" style="width: 160px; height: 160px;">

                                                    <!--<img src="<?php echo base_url('assets/pages/img/avatars/user-placeholder.jpg');?>" alt="" />-->
                                                    <?php
                                                    $customernr = isset($customer['customernr'])?$customer['customernr']:'';
                                                    echo customer_profile_image($customernr,array('customer-profile-image'),'thumb');
                                                    ?>

                                                </div>
                                                <div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 160px; max-height: 160px;"> </div>
                                                <div>
                                                    <span class="btn default btn-file">
                                                        <span class="fileinput-new"> <?php echo lang('page_lb_selectimage');?> </span>
                                                        <span class="fileinput-exists"> <?php echo lang('page_lb_change');?> </span>
                                                        <!--<input type="file" name="...">-->                                                             
                                                        <?php
                                                        echo form_upload('customerthumb');
                                                        ?>
                                                    </span>
                                                    <a href="javascript:;" class="btn default fileinput-exists" data-dismiss="fileinput"> <?php echo lang('page_lb_remove');?> </a>
                                                </div>
                                            </div>
                                            <div class="clearfix margin-top-10">
                                                <span class="label label-danger"><?php echo lang('page_lb_note');?> </span>
                                                <span>&nbsp;<?php echo lang('page_lb_selectimage_note_text');?></span>
                                            </div>
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
                                            <label><?php echo lang('page_fl_changepassword');?> <span class="required"> * </span></label>
                                            <?php echo form_password('password', "", 'class="form-control" id="submit_form_password"');?>
                                        </div>

                                        <div class="form-group">
                                            <label><?php echo lang('page_fl_cpassword');?> <span class="required"> * </span></label>
                                            <?php echo form_password('cpassword', "", 'class="form-control"');?>
                                        </div>
                                        
                                        <?php 
                                        $monitoring = (isset($customer['monitoring']) && $customer['monitoring']==1)?true:false;
                                        $data_hidden = array('type'=>'hidden', 'name'=>'monitoring', 'value'=>$monitoring);  
                                        echo form_input($data_hidden);?>

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
    var form_id = 'form_customer'; 
    var func_FormValidation = 'FormCustomValidation';
    
    function after_func_FormValidation(form1, error1, success1){
      
        form1.validate({
            errorElement: 'span', //default input error message container
            errorClass: 'help-block help-block-error', // default input error message class
            focusInvalid: false, // do not focus the last invalid input
            ignore: "",  // validate all fields including form hidden input

            rules: { 
                password: {
                    minlength: 5,
                    required: <?php echo isset($customer['customernr'])?'false':'true'?>
                },
                cpassword: {
                    minlength: 5,
                    required: <?php echo isset($customer['customernr'])?'false':'true'?>,
                    equalTo: "#submit_form_password"
                },
                customerthumb: {					  
                  extension: "jpg|jpeg|png"
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