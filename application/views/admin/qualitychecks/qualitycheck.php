<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<?php $this->load->view('admin/header.php'); ?>

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
                                <a href="<?php echo base_url('admin/qualitychecks');?>"><?php echo lang('page_qualitychecks');?></a>
                                <i class="fa fa-circle"></i>
                            </li> 
                            
                            <li>
                                <span>
                                    <?php
                                    if(isset($qualitycheck['qualitychecknr'])){
                                        echo lang('page_edit_qualitycheck');
                                    }
                                    else
                                    {
                                        echo lang('page_create_qualitycheck');                                
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
                        if(isset($qualitycheck['qualitychecknr'])){
                            ?>
                            <i class="fa fa-pencil"></i>
                            <?php
                            echo lang('page_edit_qualitycheck');
                        }
                        else
                        {
                            ?>
                            <i class="fa fa-plus"></i>
                            <?php
                            echo lang('page_create_qualitycheck');                                
                        }    
                        ?>
                        
                    </h3>
                    <!-- END PAGE TITLE-->
                    <!-- END PAGE HEADER-->
                   
                    
                    <div class="row">
                        
                        <ul class="nav nav-tabs">
                            <li class="active">
                                <a href="#tab_profile" data-toggle="tab"><?php echo lang('page_lb_profile');?></a>
                            </li>                            
                        </ul>
                        
                        <div class="tab-content">
                            
                            <div class="tab-pane active" id="tab_profile">
                                <?php echo form_open($this->uri->uri_string(), array('enctype' => "multipart/form-data", 'id' => 'form_qualitycheck') );?>        
                                <div class="col-md-6">
                                                        
                                    <!-- BEGIN SAMPLE FORM PORTLET-->
                                    <div class="portlet light bordered">                                
                                        <div class="portlet-body form">
                                    
                                            <div class="form-body">
                                                
                                                <div class="form-group">
                                                    <label><?php echo lang('page_fl_qualityissue');?> <span class="required"> * </span></label>
                                                    <?php echo form_input('qualityissue', isset($qualitycheck['qualityissue'])?$qualitycheck['qualityissue']:'', 'class="form-control" ');?>
                                                </div>
                                                
                                                <div class="form-group">
                                                    <label><?php echo lang('page_fl_qualitycheckstart');?> <span class="required"> * </span></label>

                                                    <div class="input-group date form_date">
                                                        <?php $dd = array('name'=>'qualitycheckstart', 'class'=>'form-control', 'readonly'=>true, 'size'=>16, 'value'=> isset($qualitycheck['qualitycheckstart'])?_d($qualitycheck['qualitycheckstart']):date('d.m.Y'));
                                                        echo form_input($dd);?>  

                                                        <span class="input-group-btn">
                                                            <button class="btn default date-set" type="button">
                                                                <i class="fa fa-calendar"></i>
                                                            </button>
                                                        </span>
                                                    </div>

                                                </div>
                                                
                                                <div class="form-group">
                                                    <label><?php echo lang('page_fl_company');?> <span class="required"> * </span></label>
                                                    <?php echo form_input('company', isset($qualitycheck['company'])?$qualitycheck['company']:'', 'class="form-control" ');?>
                                                </div>
         
                                                <div class="form-group">
                                                    <label><?php echo lang('page_fl_qualitycheckstatus');?> <span class="required"> * </span></label>
                                                    <?php echo form_dropdown('qualitycheckstatus', $qualitycheckstatus, isset($qualitycheck['qualitycheckstatus'])?$qualitycheck['qualitycheckstatus']:'', 'class="form-control" ');?>
                                                </div>
                                                
                                                <div class="form-group">
                                                    <label><?php echo lang('page_fl_quality_question'.$qualitycheck['question1']);?> <span class="required"> * </span></label>
                                                    <?php 
                                                    $question1_answer = array(''=>lang('page_option_select'),'yes'=>lang('page_lb_yes'),'no'=>lang('page_lb_no'));
                                                    echo form_dropdown('question1_answer', $question1_answer, isset($qualitycheck['question1_answer'])?$qualitycheck['question1_answer']:'', 'class="form-control" ');?>
                                                </div>
                                                
                                                <?php
                                                if($qualitycheck['question2']>0){
                                                    ?>
                                                    <div class="form-group">
                                                        <label><?php echo lang('page_fl_quality_question'.$qualitycheck['question2']);?> <span class="required"> * </span></label>
                                                        <?php 
                                                        $question2_answer = array(''=>lang('page_option_select'),'yes'=>lang('page_lb_yes'),'no'=>lang('page_lb_no'));
                                                        echo form_dropdown('question2_answer', $question2_answer, isset($qualitycheck['question2_answer'])?$qualitycheck['question2_answer']:'', 'class="form-control" ');?>
                                                    </div>
                                                    <?php
                                                }
                                                ?>
                                                
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
                                                    <label><?php echo lang('page_fl_responsible');?>:</label>
                                                    <?php echo $qualitycheck['responsible'];?>
                                                </div>
                                                
                                                <div class="form-group">
                                                    <label><?php echo lang('page_fl_proofuser');?> <span class="required"> * </span></label>
                                                    <?php echo form_dropdown('proofuser', $proofusers, isset($qualitycheck['proofuser'])?$qualitycheck['proofuser']:'', 'class="form-control" ');?>
                                                </div>
                                                
                                                <div class="form-group">
                                                    <label><?php echo lang('page_fl_qualitycheckdesc');?> <span class="required"> * </span></label>
                                                    <?php echo form_textarea('qualitycheckdesc', isset($qualitycheck['qualitycheckdesc'])?strip_tags($qualitycheck['qualitycheckdesc']):'', 'class="form-control"');?>
                                                </div>
                                                
                                                <?php 
                                                if($qualitycheck['rel_type']=='hardwareassignment'){?>
                                                
                                                    <?php
                                                    $qualitycheckdesc2 = json_decode($qualitycheck['qualitycheckdesc2']);
                                                    if(count($qualitycheckdesc2)>0){                                                        
                                                        echo '<div class="form-group"><label>'.lang('page_hardware').'</label><br />';                                                        
                                                        echo '<table class="table table-striped table-bordered table-hover dt-responsive">';
                                                        foreach($qualitycheckdesc2 as $qrow=>$qvals){

                                                            echo '<tr>';
                                                            foreach($qvals as $qkey=>$qval){
                                                                if($qrow==0){
                                                                    echo '<th>'.$qkey.'</th>'; 
                                                                }
                                                            }
                                                            echo '</tr>';

                                                            echo '<tr>';
                                                            foreach($qvals as $qkey=>$qval){
                                                                echo '<td>'.$qval.'</td>';                                                                     
                                                            }
                                                            echo '</tr>';                                                                
                                                        }
                                                        echo '</table></div>';
                                                    }
                                                } 
                                                ?>
                                                
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
                                                    <a href="<?php echo base_url('admin/qualitychecks')?>"><button type="button" class="btn default"><?php echo lang('cancel');?></button></a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- END SAMPLE FORM PORTLET-->
                                </div>    

                                <?php echo form_close();?>
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
    var form_id = 'form_qualitycheck'; 
    var func_FormValidation = 'FormCustomValidation';
    
    function after_func_FormValidation(form1, error1, success1){
      
        form1.validate({
            errorElement: 'span', //default input error message container
            errorClass: 'help-block help-block-error', // default input error message class
            focusInvalid: false, // do not focus the last invalid input
            ignore: "",  // validate all fields including form hidden input

            rules: { 
                qualityissue: {
                    required: true
                },
                qualitycheckstart: {
                    required: true
                },
                company: {
                    required: true
                },
                qualitycheckstatus: {                    
                    required: true
                },
                question1_answer: {                    
                    required: true
                },
                question2_answer: {                    
                    required: true
                },
                proofuser: {                    
                    required: true
                },
                qualitycheckdesc: {                    
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
                if(extraFieldsValidate()){
                    App.scrollTo(error1, -200);
                    return true;
                }else{
                    //return false;
                    return true;
                }
            }
	});
    }
</script>

<?php $this->load->view('admin/footer.php'); ?>        
<?php $this->load->view('admin/qualitychecks/qualitycheckjs',array('qualitycheck'=>isset($qualitycheck)?$qualitycheck:''));?>