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
                                <span><?php echo lang('page_calendar');?></span>
                            </li>
                        </ul>                           
                    </div>
                    <!-- END PAGE BAR -->
                    
                    
                    
                    <!-- BEGIN PAGE MESSAGE-->
                    <?php $this->load->view('admin/alerts'); ?>
                    <!-- BEGIN PAGE MESSAGE-->
                    
                    
                    <!-- END PAGE HEADER-->
                    <div class="row">
                        <div class="col-md-12">
                            
                            <!-- START CALENDAR -->
                            
                                <div class="portlet-title ">
                                    <div class="caption">
                                        <i class="fa fa-calendar font-green-sharp"></i>
                                        <span class="caption-subject font-green-sharp bold uppercase"> <?php echo lang('page_manage_calendar');?></span>
                                    </div>
                                </div>
                                
                                
                                    <div class="portlet-title filterby">                                	
                                        <div class="form-group">
                                            <?php echo form_open(base_url('admin/calendars/getEvents'), array('enctype' => "multipart/form-data", 'id' => 'form_calendar_filter') );?>
                                            
                                            <?php
                                            if(is_array($calendarIds) && count($calendarIds)>1){
                                                ?>
                                                <label><?php echo lang('filter_by');?> </label>
                                                <?php
                                            }
                                            ?>
                                                
                                            <ul class="icheck-colors">
                                                
                                                <!--<label>
                                                    <span><input type="checkbox" name="filter_googleCalendarIDs[]" id="cal_all" value="all" class="vchecker" data_key="all" checked></span> <?php echo lang('all')?>                                                               
                                                </label>-->
                                                
                                                <?php
                                                if(is_array($calendarIds) && count($calendarIds)>1){
                                                    foreach($googlecalendars as $ckey=>$googlecalendar){                                                
                                                        if(in_array($googlecalendar['id'],$calendarIds)){
                                                            ?>
                                                            <li style="background-color:<?php echo $getSystemCalendarColor[$googlecalendar['colorId']];?>"></li>

                                                            <label>
                                                            <?php 
                                                            $ch = array(
                                                                'name' => 'filter_googleCalendarIDs[]',
                                                                'id' => 'cal_'.$ckey,                                                            
                                                                'value' => $googlecalendar['id'],
                                                                'class' => 'vchecker',
                                                                'data_key' => $ckey
                                                            );                                                               
                                                            echo form_checkbox($ch);
                                                            echo $googlecalendar['summary'];?>                                                                
                                                            </label>
                                                            <?php
                                                        }
                                                    }
                                                }
                                                else{
                                                    ?>
                                                    <input type="hidden" name="filter_googleCalendarIDs[]" value="<?php echo $calendarIds[0];?>" />        
                                                    <?php        
                                                }
                                                ?>
                                                        
                                             
                                            </ul>  
                                            
                                            <?php echo form_close();?>           
                                        </div>                                    
                                    </div>
                                    <div class="clearfix"></div>
                                    
                            
                                <div class="portlet-body">
                                    <div id="calendar"> </div>
                                </div>
                            
                            <!-- END CALENDAR -->
                            
                        </div>
                    </div>
                </div>
                <!-- END CONTENT BODY -->
            </div>
            <!-- END CONTENT -->
        </div>
        <!-- END CONTAINER -->

<?php $this->load->view('admin/footer.php'); ?>        
<?php $this->load->view('admin/calendars/calendarjs');?>        