<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<?php $this->load->view('admin/header.php'); ?>


<style>
.form-body i{
    color:<?php echo $event['color']?$event['color']:'#3a87ad';?>;
}
</style>

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
                                <a href="<?php echo base_url('admin/calendars');?>"><?php echo lang('page_calendar');?></a>
                                <i class="fa fa-circle"></i>
                            </li> 
                            
                            <li>
                                <span>
                                    <?php
                                    echo lang('page_lb_detail_event'); 
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
                        
                       
                        
                        
                        <div class="modal-header" style="background-color: <?php echo $event['color']?$event['color']:'#3a87ad';?>;color:#ffffff;">

                            <?php echo '&nbsp;&nbsp;'.$event['title'];?>

                        </div>


                        <div class="portlet light">                                
                            <div class="portlet-body form">
                                <div class="form-body">

                                    <div class="form-group">
                                        <i class="icon-clock"></i>
                                        <?php echo _dt($event['start']);?> - <?php echo _dt($event['end']);?>
                                    </div>
                                    
                                    <?php
                                    if(trim($event['event_company'])!=""){
                                        ?>
                                        <div class="form-group">
                                            <i class="fa fa-building"></i>
                                            <?php echo $event['event_company'];?>
                                        </div>
                                        <?php
                                    }
                                    ?>

                                    <?php
                                    if(trim($event['description'])!=""){
                                        ?>
                                        <div class="form-group">
                                            <i class="fa fa-bars"></i>
                                            <?php echo $event['description'];?>
                                        </div>
                                        <?php
                                    }
                                    ?>

                                    <?php
                                    if(trim($event['event_startaddress'])!="" || trim($event['event_address'])!=""){
                                        ?>
                                        <div class="form-group">
                                            <i class="fa fa-map"></i>
                                            <?php if(isset($event['event_startaddress']) && $event['event_startaddress']!=""){ echo lang('from').': '.$event['event_startaddress'];}?><div class="clearfix"></div>
                                            <?php if(isset($event['event_address']) && $event['event_address']!=""){ echo lang('to').': '.$event['event_address'];}?>
                                        </div>
                                        <?php
                                    }
                                    ?>

                                    <div class="form-group">
                                        <i class="fa fa-user"></i>
                                        <?php
                                        if($event['event_type']=='CRM_EVENT'){
                                            echo $event['full_name'];
                                        }
                                        else{
                                            ?>
                                            <a href="<?php echo $event['google_htmllink'];?>" target="_blank"><img src="<?php echo base_url('assets/pages/img/google_calendar.jpg')?>" width="100" /></a> (<?php echo $event['googleCalendarName'];?>)
                                            <?php
                                        }
                                        ?>
                                    </div>

                                    <?php
                                    if(isset($event['distance']) && $event['distance']!="" && $event['eventstatus']==2){
                                        ?>
                                        <div class="form-group">
                                            <i class="fa fa-road"></i>
                                            <?php echo $event['distance'];?>
                                        </div>
                                        <?php
                                    }
                                    ?>

                                </div>    
                            </div>
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