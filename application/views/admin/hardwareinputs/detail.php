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
                                <a href="<?php echo base_url('admin/hardwareinputs');?>"><?php echo lang('page_hardwareinputs');?></a>
                                <i class="fa fa-circle"></i>
                            </li> 
                            
                            <li>
                                <span>
                                    <?php
                                    echo lang('page_detail_hardwareinput'); 
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
                                    echo lang('page_detail_hardwareinput');
                                    ?>
                                </div>                        
                            </div>                            
                        </div>
                        <!-- END EXAMPLE TABLE PORTLET-->

                            
                        <ul class="nav nav-tabs">
                            <li class="active">
                                <a href="#tab_profile" data-toggle="tab"><?php echo lang('page_lb_profile');?></a>
                            </li>
                        </ul>
                        
                        
                        <div class="tab-content">
                            
                            <div class="tab-pane active" id="tab_profile">
                                        
                                <div class="col-md-12">
                            
                            
                                    <!-- BEGIN SAMPLE FORM PORTLET-->
                                    <div class="portlet light bordered">                                
                                        <div class="portlet-body form">                                            
                                            <div class="row">
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label><?php echo lang('page_fl_supplier');?>:</label>
                                                        <?php echo $hardwareinput['suppliername'];?>
                                                    </div>
                                                </div>
                                                
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label><?php echo lang('page_fl_hardwareinputdate');?>:</label>
                                                        <?php echo _d($hardwareinput['hardwareinputdate']);?>
                                                    </div>
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
                                            
                                            <div class="portlet-title">
                                                <div class="caption font-dark">
                                                    <span class="caption-subject font-green-sharp sbold"><?php echo lang('page_lb_hardwareinputproducts');?></span>
                                                </div>
                                            </div>
                                            
                                            <div class="form-body">
                                                
                                                <div class="form-group">
                                                    <div class="table-responsive no-dt">
                                                        <table class="table table-striped table-bordered table-hover dt-responsive" width="100%">    
                                                            <thead>
                                                                <tr role="row" class="heading">                          
                                                                    <th class="text-nowrap text-center"><?php echo lang('page_fl_hardware');?></th>
                                                                    <th class="text-nowrap text-center"><?php echo lang('page_fl_seriesnr');?></th>
                                                                    <th class="text-nowrap text-center"><?php echo lang('page_fl_lampsymbol');?></th>
                                                                </tr>                                                
                                                            </thead>   
                                                            <tbody id="hardwareinputproduct_inputbox">
                                                            <?php
                                                            if(isset($hardwareinputproducts) && count($hardwareinputproducts)>0){
                                                                foreach($hardwareinputproducts as $pkey=>$hardwareinputproduct){
                                                                    ?>
                                                                    <!-- ROW -->
                                                                    <tr id="row1_old_hardwareinputproduct_<?php echo $hardwareinputproduct['id'];?>">
                                                                        <td class="text-center"><?php echo $hardwareinputproduct['hardware'];?></td>                                                                        
                                                                        <td class="text-center"><?php echo $hardwareinputproduct['seriesnr'];?></td>                                                                        
                                                                        <td class="text-center"><?php if($hardwareinputproduct['quantity']==1){ echo "<img src='".base_url('assets/pages/img/green.png')."' width='24' />"; }else{ echo "<img src='".base_url('assets/pages/img/red.png')."' width='24' />"; }?></td>                                                                        
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
                            
                        </div>        
                        
                        <div class="clearfix"></div>
                        <div class="col-md-6">
                            <!-- BEGIN SAMPLE FORM PORTLET-->
                            <div class="portlet light">                                
                                <div class="portlet-body">
                                    <div class="form-body">
                                        <div class="form-actions">
                                            <!--<button type="submit" class="btn blue"><?php echo lang('save');?></button>-->
                                            <a href="<?php echo base_url('admin/hardwareinputs')?>"><button type="button" class="btn default"><?php echo lang('close');?></button></a>
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
<?php $this->load->view('admin/hardwareinputs/hardwareinputjs',array('hardwareinput'=>$hardwareinput));?>