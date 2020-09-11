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
                                    if(isset($monitoring['monitoringnr']) && $monitoring['monitoringnr']>0){
                                        echo lang('page_edit_monitoring');
                                    }
                                    else
                                    {
                                        echo lang('page_create_monitoring');                                
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
                        if(isset($monitoring['monitoringnr']) && $monitoring['monitoringnr']>0){
                            ?>
                            <i class="fa fa-pencil"></i>
                            <?php
                            echo lang('page_edit_monitoring');
                        }
                        else
                        {
                            ?>
                            <i class="fa fa-plus"></i>
                            <?php
                            echo lang('page_create_monitoring');                                
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
                                <?php echo form_open($this->uri->uri_string(), array('enctype' => "multipart/form-data", 'id' => 'form_monitoring') );?>        
                                <div class="col-md-6">
                            
                            
                                    <!-- BEGIN SAMPLE FORM PORTLET-->
                                    <div class="portlet light bordered">                                
                                        <div class="portlet-body form">
                                    
                                            <div class="form-body">
                                                
                                                <div class="form-group">
                                                    <label><?php echo lang('page_fl_monitoringstatus');?> <span class="required"> * </span></label>
                                                    <?php echo form_dropdown('monitoringstatus', $monitoringstatus, isset($monitoring['monitoringstatus'])?$monitoring['monitoringstatus']:'', 'class="form-control"');?>
                                                </div>
                                                
                                                <div class="form-group" id="fld_monitoringlink">
                                                    <label><?php echo lang('page_fl_monitoringlink');?> <span class="required"> * </span></label>
                                                    <?php echo form_input('monitoringlink', isset($monitoring['monitoringlink'])?$monitoring['monitoringlink']:'', 'class="form-control" id="monitoringlink" ');?>
                                                </div>
                                                
                                                <div class="form-group" id="fld_monitoringuser">
                                                    <label><?php echo lang('page_fl_monitoringuser');?> <span class="required"> * </span></label>
                                                    <?php echo form_input('monitoringuser', isset($monitoring['monitoringuser'])?$monitoring['monitoringuser']:'', 'class="form-control" id="monitoringuser" ');?>
                                                </div>
                                                
                                                <div class="form-group" id="fld_monitoringpass">
                                                    <label><?php echo lang('page_fl_monitoringpass');?> <span class="required"> * </span></label>
                                                    <?php echo form_input('monitoringpass', isset($monitoring['monitoringpass'])?$monitoring['monitoringpass']:'', 'class="form-control" id="monitoringpass" ');?>
                                                </div>
                                                
                                                <div class="form-group" id="fld_monitoringpass" style="display: none;">
                                                    <label><?php echo lang('page_fl_monitoringextracost');?> <span class="required"> * </span></label>
                                                    <?php echo form_input(array('name'=>'extracost','type'=>'number'), isset($monitoring['extracost'])?$monitoring['extracost']:'', 'class="form-control" ');?>
                                                </div>
                                                
                                                <div class="form-group" id="fld_monitoringpass">
                                                    <label><?php echo lang('page_fl_monitoringadditionalextracost');?> <span class="required"> * </span></label>
                                                    <?php //echo form_input('additional_extracost', isset($monitoring['additional_extracost'])?$monitoring['additional_extracost']:'', 'class="form-control" ');?>
                                                    <?php echo form_textarea(array('name'=>'additional_extracost','rows'=>3), isset($monitoring['additional_extracost'])?$monitoring['additional_extracost']:'', 'class="form-control"');?>
                                                </div>
                                                
                                                <div class="form-group" id="fld_monitoringpass">
                                                    <label><?php echo lang('page_fl_monitoringratestatus');?> <span class="required"> * </span></label>
                                                    <?php $ratestatus = (isset($monitoring['ratestatus']) && ($monitoring['ratestatus']==0 || !$monitoring['ratestatus']))?'':' checked';?>
                                                    <div class="onoffswitch" data-toggle="tooltip" data-title=""><input name="ratestatus" <?php echo $ratestatus;?> type="checkbox" class="make-switch" data-on-text="<?php echo lang('page_lb_current')?>" data-off-text="<?php echo lang('page_lb_outdated')?>" data-on-color="primary" data-off-color="danger" data-size="small"></div>
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
                                                    <label><?php echo lang('page_fl_company');?> <span class="required"> * </span></label>
                                                    <?php echo form_input('company', isset($monitoring['company'])?$monitoring['company']:'', 'class="form-control" ');?>
                                                </div>
                                                
                                                <div class="form-group">
                                                    <label><?php echo lang('page_fl_customer');?>:</label><br />
                                                    <?php echo isset($monitoring['customer'])?$monitoring['customer']:'' ?>
                                                </div>

                                                <div class="form-group">
                                                    <label><?php echo lang('page_fl_responsible');?>:</label><br />
                                                    <?php echo isset($monitoring['responsible'])?$monitoring['responsible']:'' ?>
                                                </div>
                                                
                                                <div class="form-group">
                                                    <label><?php echo lang('page_dt_assignmentnr');?>:</label><br />
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
                                                            <td width="25%" align="center" style="vertical-align: middle"><?php echo $additional_cost['invoiceitem'];?></td>
                                                            <td width="25%" align="center" class="text-danger" style="vertical-align: middle"><?php echo format_money($additional_cost['invoicetotal'],$GLOBALS['currency_data']['currency_symbol']);?></td>                                                    
                                                            <td width="50%">
                                                                <?php echo form_dropdown(array('id'=>'costincurredby'), $monitoringassignmentstatus, isset($additional_cost['costincurredby'])?$additional_cost['costincurredby']:'', 'class="form-control monitoringassignmentstatus" dataid="'.$additional_cost['id'].'" dataid="'.$additional_cost['id'].'" monitoringid="'.$additional_cost['monitoringnr'].'" ');?>
                                                            </td>
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
                                
                                
                                                
                                <div class="clearfix"></div>
                                <div class="col-md-6">
                                    <!-- BEGIN SAMPLE FORM PORTLET-->
                                    <div class="portlet light">                                
                                        <div class="portlet-body">
                                            <div class="form-body">
                                                <div class="form-actions">
                                                    <button type="submit" class="btn blue"><?php echo lang('save');?></button>
                                                    <a href="<?php echo base_url('admin/monitorings')?>"><button type="button" class="btn default"><?php echo lang('cancel');?></button></a>
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
    var form_id = 'form_monitoring'; 
    var func_FormValidation = 'FormCustomValidation';
    
    function after_func_FormValidation(form1, error1, success1){
      
        form1.validate({
            errorElement: 'span', //default input error message container
            errorClass: 'help-block help-block-error', // default input error message class
            focusInvalid: false, // do not focus the last invalid input
            ignore: "",  // validate all fields including form hidden input

            rules: { 
                monitoringstatus: {                        
                    required: true
                },
                monitoringlink: {                        
                    required: true
                },
                monitoringuser: {                        
                    required: true
                },
                monitoringpass: {                        
                    required: true
                },
                company: {
                    minlength: 2,
                    required: true
                },
                /*extracost: {
                    required: true
                },*/
                additional_extracost: {
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
<?php $this->load->view('admin/monitorings/monitoringjs',array('monitoring'=>isset($monitoring)?$monitoring:''));?>