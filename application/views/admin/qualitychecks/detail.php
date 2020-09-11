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
                                    echo lang('page_detail_qualitycheck'); 
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
                                    <i class="fa fa-shield"></i>
                                    <?php
                                    echo lang('page_detail_qualitycheck');
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
                                
                                
                                <div class="col-md-6">
                            
                            
                                    <!-- BEGIN SAMPLE FORM PORTLET-->
                                    <div class="portlet light bordered">                                
                                        <div class="portlet-body form">
                                    
                                            <div class="form-body">
                                                
                                                <div class="form-group">
                                                    <label><?php echo lang('page_fl_qualityissue');?>:</label>
                                                    <?php echo $qualitycheck['qualityissue'];?>
                                                </div>
                                                
                                                <div class="form-group">
                                                    <label><?php echo lang('page_fl_qualitycheckstart');?>:</label>
                                                    <?php echo _d($qualitycheck['qualitycheckstart']);?>
                                                </div>
                                                
                                                <div class="form-group">
                                                    <label><?php echo lang('page_fl_company');?>:</label>
                                                    <?php echo $qualitycheck['company'];?>
                                                </div>
                                                
                                                <div class="form-group">
                                                    <label><?php echo lang('page_fl_qualitycheckstatus');?>:</label>
                                                    <?php echo $qualitycheck['qualitycheckstatus'];?>
                                                </div>
                                                
                                                <div class="form-group">
                                                    <label><?php echo lang('page_fl_quality_question'.$qualitycheck['question1']);?>:</label>
                                                    <?php echo lang('page_lb_'.$qualitycheck['question1_answer']);?>
                                                </div>
                                                
                                                <?php
                                                if($qualitycheck['question2']>0){
                                                    ?>
                                                    <div class="form-group">
                                                        <label><?php echo lang('page_fl_quality_question'.$qualitycheck['question2']);?>:</label>
                                                        <?php echo lang('page_lb_'.$qualitycheck['question2_answer']);?>
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
                                                    <label><?php echo lang('page_fl_proofuser');?>:</label>
                                                    <?php echo $qualitycheck['proofuser'];?>
                                                </div>
                                                
                                                <div class="form-group">
                                                    <label><?php echo lang('page_fl_qualitycheckdesc');?>:</label><br />
                                                    <?php echo nl2br($qualitycheck['qualitycheckdesc']);?>
                                                </div>
                                                
                                                <?php 
                                                if($qualitycheck['rel_type']=='hardwareassignment'){?>
                                                
                                                    <?php
                                                    $qualitycheckdesc2 = json_decode($qualitycheck['qualitycheckdesc2']);
                                                    if(count($qualitycheckdesc2)>0){                                                        
                                                        echo '<div class="form-group"><label>'.lang('page_hardware').':</label><br />';                                                        
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
                                                
                                                
                                                <div class="form-group">
                                                    <label><?php echo lang('page_dt_created');?>:</label>
                                                    <?php echo _dt($qualitycheck['created']);?>
                                                </div>
                                                
                                                <div class="form-group">
                                                    <label><?php echo lang('from');?>:</label>
                                                    
                                                    <?php
                                                    if($qualitycheck['rel_type']=='assignment'){
                                                        $qualitycheck['rel_link'] = "<a href='".base_url('admin/assignments/detail/'.$qualitycheck['rel_id'])."' target='_blank'>".$qualitycheck['rel_name']."</a>";
                                                    }
                                                    elseif($qualitycheck['rel_type']=='hardwareassignment'){
                                                        $qualitycheck['rel_link'] = "<a href='".base_url('admin/hardwareassignments/detail/'.$qualitycheck['rel_id'])."' target='_blank'>".$qualitycheck['rel_name']."</a>";
                                                    }
                                                    elseif($qualitycheck['rel_type']=='event'){
                                                        $qualitycheck['rel_link'] = "<a href='".base_url('admin/calendars/detail/'.$qualitycheck['rel_id'])."' target='_blank'>".$qualitycheck['rel_name']."</a>";
                                                    }
                                                    
                                                    echo $qualitycheck['rel_link'];
                                                    ?>
                                                    
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
                                            <a href="<?php echo base_url('admin/qualitychecks')?>"><button type="button" class="btn default"><?php echo lang('close');?></button></a>
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
<?php $this->load->view('admin/qualitychecks/qualitycheckjs',array('qualitycheck'=>$qualitycheck));?>