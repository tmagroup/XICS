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
                                <a href="<?php echo base_url('admin/tickets');?>"><?php echo lang('page_tickets');?></a>
                                <i class="fa fa-circle"></i>
                            </li> 
                            
                            <li>
                                <span>
                                    <?php
                                    if(isset($ticket['ticketnr']) && $ticket['ticketnr']>0){
                                        echo lang('page_edit_ticket');
                                    }
                                    else
                                    {
                                        echo lang('page_create_ticket');                                
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
                        if(isset($ticket['ticketnr']) && $ticket['ticketnr']>0){
                            ?>
                            <i class="fa fa-pencil"></i>
                            <?php
                            echo lang('page_edit_ticket');
                        }
                        else
                        {
                            ?>
                            <i class="fa fa-plus"></i>
                            <?php
                            echo lang('page_create_ticket');                                
                        }    
                        ?>
                        
                    </h3>
                    <!-- END PAGE TITLE-->
                    <!-- END PAGE HEADER-->
                    
                    
                    <?php
                    //Only Editable 
                    $tab_document = '';
                    if(empty($ticket['ticketnr'])){
                       $tab_document = 'none';
                    }					
                    ?>
                    
                    
                    <div class="row">
                        
                        
                        
                        <ul class="nav nav-tabs">
                            <li class="active">
                                <a href="#tab_profile" data-toggle="tab"><?php echo lang('page_lb_profile');?></a>
                            </li>
                            <li style="display:<?php echo $tab_document;?>">
                                <a href="#tab_document" data-toggle="tab"><?php echo lang('page_lb_document');?></a>
                            </li>
                        </ul>
                        
                        
                        <div class="tab-content">
                            
                            <div class="tab-pane active" id="tab_profile">
                                <?php echo form_open($this->uri->uri_string(), array('enctype' => "multipart/form-data", 'id' => 'form_ticket') );?>        
                                <div class="col-md-6">
                            
                            
                                    <!-- BEGIN SAMPLE FORM PORTLET-->
                                    <div class="portlet light bordered">                                
                                        <div class="portlet-body form">
                                    
                                            <div class="form-body">
                                                
                                                <div class="form-group">
                                                    <label><?php echo lang('page_fl_tickettitle');?> <span class="required"> * </span></label>
                                                    <?php echo form_input('tickettitle', isset($ticket['tickettitle'])?$ticket['tickettitle']:'', 'class="form-control"');?>
                                                </div>
                                                
                                                <!--<div class="form-group">
                                                    <label><?php echo lang('page_fl_company');?> <span class="required"> * </span></label>
                                                    <?php echo form_input('company', isset($ticket['company'])?$ticket['company']:'', 'class="form-control"');?>
                                                </div>-->
                                                
                                                <div class="form-group">
                                                    <label><?php echo lang('page_fl_ticketstatus');?> <span class="required"> * </span></label>
                                                    <?php echo form_dropdown('ticketstatus', $ticketstatus, isset($ticket['ticketstatus'])?$ticket['ticketstatus']:'', 'class="form-control"');?>
                                                </div>
                                                
                                                <div class="form-group">
                                                    <label><?php echo lang('page_fl_ticketdesc');?> <span class="required"> * </span></label>
                                                    <?php echo form_textarea('ticketdesc', isset($ticket['ticketdesc'])?$ticket['ticketdesc']:'', 'class="form-control"');?>
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
                                                
                                                <?php
                                                if(get_user_role()=='customer'){
                                                    ?>
                                                    <input type="hidden" name="customer" id="customer" value="<?php echo get_user_id();?>">
                                                    <?php
                                                }
                                                else{
                                                    if(isset($ticket['userrole']) && $ticket['userrole']=='customer'){
                                                        ?>
                                                        <div class="form-group">                                            
                                                            <label><?php echo lang('page_fl_customer');?>: </label>                                                            
                                                            <?php echo isset($ticket['customername'])?$ticket['customername']:'';?>
                                                            <input type="hidden" name="customer" id="customer" value="<?php echo get_user_id();?>">
                                                        </div>
                                                        <?php
                                                    }
                                                    else{
                                                        ?>
                                                        <div class="form-group">                                            
                                                            <label><?php echo lang('page_fl_customer');?></label>
                                                            <?php echo form_dropdown('customer', $customers, isset($ticket['customer'])?$ticket['customer']:'', 'class="form-control" id="customer" ');?>
                                                        </div>
                                                        <?php
                                                    }
                                                }
                                                ?>

                                                <div class="form-group">
                                                    <label><?php echo lang('page_fl_responsible');?> <span class="required"> * </span></label>
                                                    <?php /* echo form_dropdown('responsible', array(''=>lang('page_option_select')) ,'', 'class="form-control" id="responsible" '); */?>
                                                    <?php echo form_dropdown('responsible', $responsibles, isset($ticket['responsible']) ? $ticket['responsible'] : '', 'class="form-control" id="responsible"'); ?>
                                                </div>

                                                <div class="form-group" style="display:<?php if(get_user_role()=='customer'){ echo 'none'; }?>">
                                                    <label><?php echo lang('page_fl_teamwork');?> </label>
                                                    <?php //echo form_dropdown('teamwork[]', $teamworks, isset($ticket['teamwork'])?$ticket['teamwork']:'', 'class="form-control select2-multiple" id="teamwork" multiple');?>

                                                    <?php
                                                    $selected_teamwork = isset($ticket['teamwork'])?$ticket['teamwork']:'';
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
                                
                                
                                                
                                <div class="clearfix"></div>
                                <div class="col-md-6">
                                    <!-- BEGIN SAMPLE FORM PORTLET-->
                                    <div class="portlet light">                                
                                        <div class="portlet-body">
                                            <div class="form-body">
                                                <div class="form-actions">
                                                    <button type="submit" class="btn blue"><?php echo lang('save');?></button>
                                                    <a href="<?php echo base_url('admin/tickets')?>"><button type="button" class="btn default"><?php echo lang('cancel');?></button></a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- END SAMPLE FORM PORTLET-->
                                </div>    
                                
                                <?php echo form_close();?>
                            </div>
                            
                            <div class="tab-pane" id="tab_document" style="display:<?php echo $tab_document;?>">
                                
                                <?php 
                                if(isset($ticket['ticketnr']) && $ticket['ticketnr']>0){
                                    $this->load->view('admin/tickets/tab-document', array('ticket'=>$ticket)); 
                                }
                                ?>
                                
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
    var form_id = 'form_ticket'; 
    var func_FormValidation = 'FormCustomValidation';
    
    function after_func_FormValidation(form1, error1, success1){
      
        form1.validate({
            errorElement: 'span', //default input error message container
            errorClass: 'help-block help-block-error', // default input error message class
            focusInvalid: false, // do not focus the last invalid input
            ignore: "",  // validate all fields including form hidden input

            rules: { 
                tickettitle: {  
                    minlength: 2,
                    required: true
                },
                company: {
                    minlength: 2,
                    required: true
                },
                ticketstatus: {                        
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
                ticketdesc: {
                    maxlength: 255,
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
<?php $this->load->view('admin/tickets/ticketjs',array('ticket'=>isset($ticket)?$ticket:''));?>