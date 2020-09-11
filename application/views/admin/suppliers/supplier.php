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
                                <a href="<?php echo base_url('admin/suppliers');?>"><?php echo lang('page_suppliers');?></a>
                                <i class="fa fa-circle"></i>
                            </li> 
                            
                            <li>
                                <span>
                                    <?php
                                    if(isset($supplier['suppliernr']) && $supplier['suppliernr']>0){
                                        echo lang('page_edit_supplier');
                                    }
                                    else
                                    {
                                        echo lang('page_create_supplier');                                
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
                        if(isset($supplier['suppliernr']) && $supplier['suppliernr']>0){
                            ?>
                            <i class="fa fa-pencil"></i>
                            <?php
                            echo lang('page_edit_supplier');
                        }
                        else
                        {
                            ?>
                            <i class="fa fa-plus"></i>
                            <?php
                            echo lang('page_create_supplier');                                
                        }    
                        ?>
                        
                    </h3>
                    <!-- END PAGE TITLE-->
                    <!-- END PAGE HEADER-->
                    
                    
                    
                    <div class="row">
                        <?php echo form_open($this->uri->uri_string(), array('enctype' => "multipart/form-data", 'id' => 'form_supplier') );?>
                     
                        <div class="col-md-6">
                            <!-- BEGIN SAMPLE FORM PORTLET-->
                            <div class="portlet light bordered">                                
                                <div class="portlet-body form">

                                    <div class="form-body">

                                        <div class="form-group">
                                            <label><?php echo lang('page_fl_company');?> <span class="required"> * </span></label>
                                            <?php echo form_input('companyname', isset($supplier['companyname'])?$supplier['companyname']:'', 'class="form-control"');?>
                                        </div>

                                        <div class="form-group">
                                            <label><?php echo lang('page_fl_street');?> <span class="required"> * </span></label>
                                            <?php echo form_textarea(array('name'=>'street','rows'=>3), isset($supplier['street'])?$supplier['street']:'', 'class="form-control"');?>
                                        </div>

                                        <div class="form-group">
                                            <label><?php echo lang('page_fl_zipcode');?> <span class="required"> * </span></label>
                                            <?php echo form_input('zipcode', isset($supplier['zipcode'])?$supplier['zipcode']:'', 'class="form-control"');?>
                                        </div>

                                        <div class="form-group">
                                            <label><?php echo lang('page_fl_city');?> <span class="required"> * </span></label>
                                            <?php echo form_input('city', isset($supplier['city'])?$supplier['city']:'', 'class="form-control"');?>
                                        </div>

                                        <div class="form-group">
                                            <label><?php echo lang('page_fl_country');?> </label>
                                            <?php echo form_input('country', isset($supplier['country'])?$supplier['country']:'', 'class="form-control"');?>
                                        </div>

                                        <div class="form-group">
                                            <label><?php echo lang('page_fl_phonenumber');?> <span class="required"> * </span></label>
                                            <?php echo form_input('phone', isset($supplier['phone'])?$supplier['phone']:'', 'class="form-control"');?>
                                        </div>

                                        <div class="form-group">
                                            <label><?php echo lang('page_fl_faxnr');?> </label>
                                            <?php echo form_input('faxnr', isset($supplier['faxnr'])?$supplier['faxnr']:'', 'class="form-control"');?>
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
                                            <label><?php echo lang('page_fl_email');?> <span class="required"> * </span></label>
                                            <?php echo form_input(array('type'=>'email','name'=>'email'), isset($supplier['email'])?$supplier['email']:'', 'class="form-control"');?>
                                        </div>
                                        
                                        <div class="form-group">
                                            <label><?php echo lang('page_fl_website');?> </label>
                                            <?php echo form_input('website', isset($supplier['website'])?$supplier['website']:'', 'class="form-control"');?>
                                        </div>

                                        <div class="form-group">
                                            <label><?php echo lang('page_fl_business');?> </label>
                                            <?php echo form_input('business', isset($supplier['business'])?$supplier['business']:'', 'class="form-control"');?>
                                        </div>

                                        <div class="form-group">
                                            <label><?php echo lang('page_fl_title');?> <span class="required"> * </span></label>                                                
                                            <?php echo form_dropdown('title', $salutations, isset($supplier['title'])?$supplier['title']:'', 'class="form-control"');?>
                                        </div>

                                        <div class="form-group">
                                            <label><?php echo lang('page_fl_surname');?> <span class="required"> * </span></label>
                                            <?php echo form_input('surname', isset($supplier['surname'])?$supplier['surname']:'', 'class="form-control"');?>
                                        </div>

                                        <div class="form-group">
                                            <label><?php echo lang('page_fl_name');?> <span class="required"> * </span></label>
                                            <?php echo form_input('name', isset($supplier['name'])?$supplier['name']:'', 'class="form-control"');?>
                                        </div>

                                        <div class="form-group">
                                            <label><?php echo lang('page_fl_position');?> </label>
                                            <?php echo form_input('position', isset($supplier['position'])?$supplier['position']:'', 'class="form-control"');?>
                                        </div>

                                        <div class="form-group">
                                            <label><?php echo lang('page_fl_mobilnr');?> </label>
                                            <?php echo form_input('mobilnr', isset($supplier['mobilnr'])?$supplier['mobilnr']:'', 'class="form-control"');?>
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
                                            <a href="<?php echo base_url('admin/suppliers')?>"><button type="button" class="btn default"><?php echo lang('cancel');?></button></a>
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
    var form_id = 'form_supplier'; 
    var func_FormValidation = 'FormCustomValidation';
    
    function after_func_FormValidation(form1, error1, success1){
      
        form1.validate({
            errorElement: 'span', //default input error message container
            errorClass: 'help-block help-block-error', // default input error message class
            focusInvalid: false, // do not focus the last invalid input
            ignore: "",  // validate all fields including form hidden input
            rules: {
                companyname: {
                    minlength: 2,
                    required: true
                },
                street: {
                    required: true
                },
                zipcode: {
                    required: true
                },
                city: {
                    required: true
                },
                /*country: {
                    required: true
                },*/
                phone: {
                    required: true
                },
                /*faxnr: {
                    required: true
                },*/
                email: {
                    required: true,
                    email: true
                },
                /*website: {
                    required: true
                },
                business: {
                    required: true
                },*/
                title: {
                    required: true
                },
                surname: {       
                    minlength: 2,                 
                    required: true
                },
                name: {          
                    minlength: 2,              
                    required: true
                },
                /*position: {
                    required: true
                },
                mobilnr: {
                    required: true
                },*/
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