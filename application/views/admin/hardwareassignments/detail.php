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
                                <a href="<?php echo base_url('admin/hardwareassignments');?>"><?php echo lang('page_hardwareassignments');?></a>
                                <i class="fa fa-circle"></i>
                            </li> 
                            
                            <li>
                                <span>
                                    <?php
                                    echo lang('page_detail_hardwareassignment'); 
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
                                    <i class="icon-settings"></i>
                                    <?php
                                    echo lang('page_detail_hardwareassignment');
                                    ?>
                                </div>  
                                
                                <div class="actions">
                                    <?php
                                    if($GLOBALS['hardwareassignment_permission']['edit']){
                                        ?>
                                        <a href="<?php echo base_url('admin/hardwareassignments/hardwareassignment/'.$hardwareassignment['hardwareassignmentnr']);?>" class="btn sbold blue btn-sm"><i class="fa fa-pencil"></i> <?php echo lang('page_edit_hardwareassignment');?></a>                                
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
                        if(empty($hardwareassignment['hardwareassignmentnr'])){
                           $tab_document = 'none';
                           $tab_reminder = 'none';
                        }	
                        
                        if(get_user_role()=='customer'){
                            $tab_reminder = 'none';
                        }
                        ?>
                            
                        <ul class="nav nav-tabs">
                            <li class="active">
                                <a href="#tab_profile" data-toggle="tab"><?php echo lang('page_lb_profile');?></a>
                            </li>
                            <li style="display:<?php echo $tab_document;?>">
                                <a href="#tab_document" data-toggle="tab"><?php echo lang('page_lb_document');?></a>
                            </li>
                            <li style="display:<?php echo $tab_reminder;?>">
                                <a href="#tab_reminder" data-toggle="tab"><?php echo lang('page_lb_reminder');?></a>
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
                                                    <label><?php echo lang('page_fl_company');?>:</label>
                                                    <?php echo $hardwareassignment['company'];?>
                                                </div>
                                                
                                                <div class="form-group">
                                                    <label><?php echo lang('page_fl_hardwareassignmentstatus');?>:</label>
                                                    <?php echo $hardwareassignment['hardwareassignmentstatus'];?>
                                                </div>
                                                
                                                <div class="form-group">
                                                    <label><?php echo lang('page_dt_created');?>:</label>
                                                    <?php echo _dt($hardwareassignment['created']);?>
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
                                                    <label><?php echo lang('page_fl_customer');?>:</label>
                                                    <?php echo $hardwareassignment['customer'];?>
                                                </div>
                                                
                                                <div class="form-group">
                                                    <label><?php echo lang('page_fl_responsible');?>:</label>
                                                    <?php echo $hardwareassignment['responsible'];?>
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
                                            
                                            <div class="form-body">
                                                
                                                <div class="form-group">
                                                    <div class="table-responsive no-dt">
                                                        <table class="table table-striped table-bordered table-hover dt-responsive" width="100%">    
                                                            <thead>
                                                                <tr role="row" class="heading">                          
                                                                    <th class="text-nowrap" width="10%"><?php echo lang('page_fl_mobilenr');?></th>
                                                                    <th class="text-nowrap" width="10%"><?php echo lang('page_fl_simnr');?></th>
                                                                    <th class="text-nowrap" width="20%"><?php echo lang('page_fl_ratetitle');?></th>
                                                                    <th class="text-nowrap" width="20%"><?php echo lang('page_fl_choosenhardware');?></th>
                                                                    <th class="text-nowrap" width="20%"><?php echo lang('page_fl_stockhardware');?></th>
                                                                    <th class="text-nowrap" width="10%"><?php echo lang('page_fl_seriesnr');?></th>
                                                                    <th class="text-nowrap" width="10%"><?php echo lang('page_fl_shippingnr');?></th>
                                                                    <th class="text-nowrap" width="10%"><?php echo lang('page_fl_hardwarevalue');?></th>
                                                                </tr> 
                                                            </thead>   
                                                            <tbody id="hardwareassignment_inputbox">
                                                                
                                                                <?php
                                                                if(isset($hardwareassignmentproducts) && count($hardwareassignmentproducts)>0){
                                                                    $data_hidden = array('type'=>'hidden', 'name'=>'count_hardwareassignmentproduct', 'id'=>'count_hardwareassignmentproduct', 'value'=>isset($hardwareassignmentproducts)?count($hardwareassignmentproducts):1);  
                                                                    echo form_input($data_hidden);

                                                                    foreach($hardwareassignmentproducts as $pkey=>$hardwareassignmentproduct){                                                                
                                                                        echo form_hidden('hardwareassignmentproductid['.$pkey.']', $hardwareassignmentproduct['id']);
                                                                        ?>
                                                                        <!-- ROW -->
                                                                        <tr id="row1_old_hardwareassignment_<?php echo $hardwareassignmentproduct['id'];?>">
                                                                            <td><?php echo $hardwareassignmentproduct['mobilenr'];?></td>
                                                                            <td><?php echo $hardwareassignmentproduct['simnr'];?></td>
                                                                            <td><?php echo $hardwareassignmentproduct['newratemobile'];?></td>
                                                                            <td><?php echo $hardwareassignmentproduct['hardware'];?></td>
                                                                            <td><?php echo $hardwareassignmentproduct['stockhardwaretitle'];?></td>
                                                                            <td><?php echo $hardwareassignmentproduct['seriesnr'];?></td>
                                                                            <td><?php echo $hardwareassignmentproduct['shippingnr'];?></td>
                                                                            <td><?php echo format_money($hardwareassignmentproduct['hardwarevalue'],"&nbsp;".$GLOBALS['currency_data']['currency_symbol']);?></td>
                                                                        </tr>
                                                                        <!-- END ROW -->   
                                                                        <?php
                                                                    }
                                                                }
                                                                ?>                                                            
                                                            </tbody>                                                                
                                                        </table>    
                                                    </div>
                                                </div>
                                                
                                            </div>
                                            
                                        </div>
                                    </div>
                                    <!-- END SAMPLE FORM PORTLET-->
                                    
                                </div>
                        
                            </div>
                            
                            <div class="tab-pane" id="tab_document" style="display:<?php echo $tab_document;?>">
                                
                                <?php 
                                if(isset($hardwareassignment['hardwareassignmentnr']) && $hardwareassignment['hardwareassignmentnr']>0){
                                    $this->load->view('admin/hardwareassignments/tab-document', array('hardwareassignment'=>$hardwareassignment,'categories'=>$categories)); 
                                }
                                ?>
                                
                            </div>
                            
                            <div class="tab-pane" id="tab_reminder" style="display:<?php echo $tab_reminder;?>">
                                
                                <?php 
                                if(isset($hardwareassignment['hardwareassignmentnr']) && $hardwareassignment['hardwareassignmentnr']>0){ 
                                    $this->load->view('admin/hardwareassignments/tab-reminder', array('hardwareassignment'=>$hardwareassignment)); 
                                }
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
                                            <a href="<?php echo base_url('admin/hardwareassignments')?>"><button type="button" class="btn default"><?php echo lang('close');?></button></a>
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
<?php $this->load->view('admin/footer.php'); ?>        
<?php $this->load->view('admin/hardwareassignments/hardwareassignmentjs',array('hardwareassignment'=>$hardwareassignment, 'remindersubjects'=>$remindersubjects));?>