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
                                <a href="<?php echo base_url('admin/assignments');?>"><?php echo lang('page_assignments');?></a>
                                <i class="fa fa-circle"></i>
                            </li> 
                            
                            <li>
                                <span>
                                    <?php
                                    echo lang('page_detail_assignment'); 
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
                                        <i class="fa fa-file"></i>
                                        <?php
                                        echo lang('page_detail_assignment');
                                        ?>
                                    </div>
                                    
                                    <div class="actions">
                                                                     
                                            <?php
                                            if(isset($GLOBALS['current_user']->userrole) && $GLOBALS['current_user']->userrole!=6){
                                                ?>

                                                        <a href="javascript:void(0);" onclick="FormTicketAjax('<?php echo base_url('admin/assignments/generateTicket/'.$assignment['assignmentnr']);?>','<?php echo $assignment['assignmentnr'];?>','abolock','<?php echo lang('page_ticket')." - ".lang('page_lb_abolock');?>','<?php echo lang('page_lb_abolock_popup_ask');?>','');" class="btn sbold green btn-sm"> <i class="fa fa-life-ring"></i> <?php echo lang('page_lb_abolock');?></a>
                                                        <a href="javascript:void(0);" onclick="FormTicketAjax('<?php echo base_url('admin/assignments/generateTicket/'.$assignment['assignmentnr']);?>','<?php echo $assignment['assignmentnr'];?>','internationaltelephonylock','<?php echo lang('page_ticket')." - ".lang('page_lb_internationaltelephonylock');?>','<?php echo lang('page_lb_internationaltelephonylock_popup_ask');?>','');" class="btn sbold green btn-sm"> <i class="fa fa-life-ring"></i> <?php echo lang('page_lb_internationaltelephonylock');?></a>
                                                        <a href="javascript:void(0);" onclick="FormTicketAjax('<?php echo base_url('admin/assignments/generateTicket/'.$assignment['assignmentnr']);?>','<?php echo $assignment['assignmentnr'];?>','roaminglock','<?php echo lang('page_ticket')." - ".lang('page_lb_roaminglock');?>','<?php echo lang('page_lb_roaminglock_popup_ask');?>','');" class="btn sbold green btn-sm"> <i class="fa fa-life-ring"></i> <?php echo lang('page_lb_roaminglock');?></a>

                                                <?php
                                            }
                                            ?>     
                                                        
                                            <?php
                                            if($GLOBALS['assignment_permission']['edit']){
                                                ?>
                                                <a href="<?php echo base_url('admin/assignments/assignment/'.$assignment['assignmentnr']);?>" class="btn sbold blue btn-sm"><i class="fa fa-pencil"></i> <?php echo lang('page_edit_assignment');?></a>                                
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
                        $tab_legitimation = '';
                        if(empty($assignment['assignmentnr'])){
                           $tab_document = 'none';
                           $tab_reminder = 'none';
                           $tab_legitimation = 'none';
                        }	
                        
                        if(get_user_role()=='customer'){
                            $tab_reminder = 'none';
                        }
                        ?>

                    
                    
                        <?php //echo form_open($this->uri->uri_string(), array('enctype' => "multipart/form-data", 'id' => 'form_assignment') );?>
                        
                        
                        <ul class="nav nav-tabs">
                            <li class="active">
                                <a href="#tab_profile" data-toggle="tab"><?php echo lang('page_lb_profile');?></a>
                            </li>
                            <li style="display:<?php echo $tab_legitimation;?>">
                                <a href="#tab_legitimation" data-toggle="tab"><?php echo lang('page_lb_legitimation');?></a>
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
                                                    <?php echo $assignment['company'];?>
                                                </div>
                                                
                                                <div class="form-group">
                                                    <label><?php echo lang('page_fl_assignmentdate');?>:</label>
                                                    <?php echo _d($assignment['assignmentdate']);?>
                                                </div>
                                                
                                                <div class="form-group">
                                                    <label><?php echo lang('page_fl_assignmentstatus');?>:</label>
                                                    <?php echo $assignment['assignmentstatus'];?>
                                                </div>
                                                
                                                <div class="form-group">
                                                    <label><?php echo lang('page_fl_assignmentprovider');?>:</label>
                                                    <?php echo $assignment['providercompanynr'];?>
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
                                                    <?php echo $assignment['customer'];?>
                                                </div>
                                                
                                                <div class="form-group">
                                                    <label><?php echo lang('page_fl_responsible');?>:</label>
                                                    <?php echo $assignment['responsible'];?>
                                                </div>
                                                
                                                <div class="form-group">
                                                    <label><?php echo lang('page_fl_recommend');?>:</label>
                                                    <?php echo $assignment['recommend'];?>
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
                                            
                                            
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label><?php echo lang('page_fl_newdiscountlevel');?>:</label>
                                                        <?php echo $assignment['newdiscountlevel'];?>
                                                    </div>
                                                </div>
                                            </div>
                                                
                                            
                                            <div class="portlet-title">
                                                <div class="caption font-dark">
                                                    <span class="caption-subject font-green-sharp sbold"><?php echo lang('page_lb_assignmentproducts');?></span>
                                                </div>
                                            </div>
                                            
                                            <div class="form-body">
                                            <?php echo form_open(base_url('admin/assignments/saveEmployees'), array('enctype' => "multipart/form-data", 'id' => 'form_assignment_employee') );?>    
                                                <div class="form-group">
                                                    <div class="table-responsive no-dt">
                                                        <table class="table table-striped table-bordered table-hover dt-responsive" width="100%">    
                                                            <thead>
                                                                <tr role="row" class="heading">                          
                                                                    <th class="text-nowrap text-center"><?php echo lang('page_fl_simnr');?></th>           
                                                                    <th class="text-nowrap text-center"><?php echo lang('page_fl_mobilenr');?></th>                                                    
                                                                    <th class="text-nowrap text-center"><?php echo lang('page_fl_employee');?></th>                                                    
                                                                    <th class="text-nowrap text-center"><?php echo lang('page_fl_vvl_neu');?></th>
                                                                    <th class="text-nowrap text-center"><?php echo lang('page_fl_ratetitle');?></th>
                                                                    <th class="text-nowrap text-center"><?php echo lang('page_fl_value');?></th>   
                                                                    <th class=""><?php echo lang('page_fl_extemtedterm');?></th>
                                                                    <th class="text-nowrap text-center"><?php echo lang('page_fl_optiontitle');?></th>
                                                                    <th class="text-nowrap text-center"><?php echo lang('page_fl_value');?></th>                                                    
                                                                    <th class="text-nowrap text-center"><?php echo lang('page_fl_hardware');?></th>
                                                                    <th class=""><?php echo lang('page_fl_cardstatus');?></th>
                                                                    <th class="text-center"><?php echo lang('page_fl_endofcontract');?></th> 
                                                                    <th class=""><?php echo lang('page_fl_finished');?></th>
                                                                    
                                                                    <?php
                                                                    if(isset($GLOBALS['current_user']->userrole) && $GLOBALS['current_user']->userrole!=6){
                                                                        ?>
                                                                        <th></th>
                                                                        <?php
                                                                    }
                                                                    ?>
                                                                </tr>                                                
                                                            </thead>   
                                                            <tbody id="assignmentproduct_inputbox">
                                                            <?php
                                                            if(isset($assignmentproducts) && count($assignmentproducts)>0){
                                                                foreach($assignmentproducts as $pkey=>$assignmentproduct){
                                                                    ?>
                                                                    <!-- ROW -->
                                                                    <tr id="row1_old_assignmentproduct_<?php echo $assignmentproduct['id'];?>">
                                                                        <td class="text-center"><?php echo $assignmentproduct['simnr'];?></td>
                                                                        <td class="text-center"><?php echo $assignmentproduct['mobilenr'];?></td>
                                                                        
                                                                        <?php
                                                                        if(get_user_role()=='customer'){
                                                                            ?>
                                                                            <td class="text-center"><input type="text" name="employee[<?php echo $assignmentproduct['id'];?>]" value="<?php echo $assignmentproduct['employee'];?>" class="form-control" /></td>
                                                                            <?php
                                                                        }
                                                                        else{
                                                                            ?>
                                                                            <td class="text-center"><?php echo $assignmentproduct['employee'];?></td>
                                                                            <?php
                                                                        }    
                                                                        ?>
                                                                        
                                                                        <td class="text-center"><?php echo $assignmentproduct['vvlneu'];?></td>
                                                                        <td class="text-center"><?php echo $assignmentproduct['newratemobile'];?></td>                                                                    
                                                                        <td class="text-center"><?php echo $assignmentproduct['value2'];?></td>
                                                                        <td class="text-center"><?php echo $assignmentproduct['extemtedterm']=='1'?'<i class="fa fa-check"></i>':'<i class="fa fa-close"></i>';?></td>
                                                                        <td class="text-center"><?php echo $assignmentproduct['newoptionmobile'];?></td>
                                                                        <td class="text-center"><?php echo $assignmentproduct['value4'];?></td>                                                                    
                                                                        <td class="text-center"><?php echo $assignmentproduct['hardware'];?></td>
                                                                        <td class="text-center"><?php echo $assignmentproduct['cardstatus']=='1'?'<i class="fa fa-check"></i>':'<i class="fa fa-close"></i>';?></td>
                                                                        <td class="text-center"><?php echo _d($assignmentproduct['endofcontract']);?></td> 
                                                                        <td class="text-center"><?php echo $assignmentproduct['finished']=='1'?'<i class="fa fa-check"></i>':'<i class="fa fa-close"></i>';?></td>
                                                                        
                                                                        <?php
                                                                        if(isset($GLOBALS['current_user']->userrole) && $GLOBALS['current_user']->userrole!=6){
                                                                            ?>
                                                                            <td class="text-center">
                                                                                <a href="javascript:void(0);" onclick="FormTicketAjax('<?php echo base_url('admin/assignments/generateTicket/'.$assignment['assignmentnr']);?>','<?php echo $assignment['assignmentnr'];?>','cardlock','<?php echo lang('page_ticket')." - ".lang('page_lb_cardlock');?>','<?php echo lang('page_lb_cardlock_popup_ask');?>','<?php echo $assignmentproduct['id'];?>');" class="btn sbold green btn-sm"> <i class="fa fa-life-ring"></i> <?php echo lang('page_lb_cardlock');?></a>
                                                                            </td>
                                                                            <?php
                                                                        }
                                                                        ?>
                                                                    </tr>
                                                                    <!-- END ROW -->                                                                                                                                
                                                                    <?php
                                                                }
                                                                
                                                                if(get_user_role()=='customer'){
                                                                    ?>
                                                                    <tr>
                                                                        <td></td>
                                                                        <td></td>
                                                                        <td class="text-center"><button id="save_employees" type="button" class="btn blue"><?php echo lang('save_all');?></button></td>
                                                                        <td></td>
                                                                        <td></td>
                                                                        <td></td>
                                                                        <td></td>
                                                                        <td></td>
                                                                        <td></td>
                                                                        <td></td>
                                                                        <td></td>
                                                                        <td></td>
                                                                        <td></td>
                                                                    </tr>
                                                                    <?php
                                                                }                                                                    
                                                            }
                                                            ?>
                                                            </tbody>                                                                
                                                        </table>    
                                                    </div>
                                                </div>
                                            <?php echo form_close();?>    
                                            </div>
                                            
                                        </div>
                                    </div>
                                    <!-- END SAMPLE FORM PORTLET-->
                                    
                                </div>
                        
                            </div>
                            
                            <div class="tab-pane" id="tab_legitimation" style="display:<?php echo $tab_document;?>">
                                
                                <?php 
                                $this->load->view('admin/assignments/tab-legitimation-detail', array('assignment'=>$assignment)); 
                                ?>
                                
                            </div>
                            
                            <div class="tab-pane" id="tab_document" style="display:<?php echo $tab_document;?>">
                                
                                <?php 
                                $this->load->view('admin/assignments/tab-document', array('assignment'=>$assignment,'categories'=>$categories)); 
                                ?>
                                
                            </div>
                            
                            <div class="tab-pane" id="tab_reminder" style="display:<?php echo $tab_reminder;?>">
                                
                                <?php 
                                $this->load->view('admin/assignments/tab-reminder', array('assignment'=>$assignment)); 
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
                                            <a href="<?php echo base_url('admin/assignments')?>"><button type="button" class="btn default"><?php echo lang('close');?></button></a>
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
<?php $this->load->view('admin/assignments/assignmentjs',array('assignment'=>$assignment, 'remindersubjects'=>$remindersubjects));?>