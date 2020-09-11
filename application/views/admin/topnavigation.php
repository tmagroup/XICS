<!-- BEGIN HEADER -->
<?php
$user_reminders = get_user_reminders();
//print_r($user_reminders);exit;
?>

<div class="page-header navbar navbar-fixed-top">
    <!-- BEGIN HEADER INNER -->
    <div class="page-header-inner ">
        
        <!-- BEGIN LOGO -->
        <div class="page-logo">
            <a href="<?php echo base_url('admin/dashboard');?>">
                <!--<img src="<?php echo base_url('assets/pages/img/logo.png'); ?>" alt="logo" class="logo-default" />-->
                <!--<img src="<?php echo base_url('uploads/company/logo.png'); ?>" alt="" />-->
                <img src="<?php echo base_url('uploads/company/xics-big.png'); ?>" alt="" width="100" />
            </a>
            <div class="menu-toggler sidebar-toggler"> </div>
        </div>
        <!-- END LOGO -->	
        
        <!-- BEGIN RESPONSIVE MENU TOGGLER -->
        <a href="javascript:;" class="menu-toggler responsive-toggler" data-toggle="collapse" data-target=".navbar-collapse"> </a>
        <!-- END RESPONSIVE MENU TOGGLER -->
        <!-- BEGIN TOP NAVIGATION MENU -->
        <div class="top-menu">
            <ul class="nav navbar-nav pull-right">
                <!-- BEGIN NOTIFICATION DROPDOWN -->
                <!-- DOC: Apply "dropdown-dark" class after below "dropdown-extended" to change the dropdown styte -->
                <input type="hidden" id="currentNotificationCount" value="<?php echo count($user_reminders);?>" />
                <li class="dropdown dropdown-extended dropdown-notification" id="header_notification_bar">
                    <!--<a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="true">-->
                    <a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown" data-close-others="true">    
                        <i class="icon-bell"></i>
                        <span class="badge badge-default"> <?php echo count($user_reminders);?> </span>
                    </a>
                    <ul class="dropdown-menu">
                        <li class="external">
                            <h3>
                                <span class="bold"><?php echo count($user_reminders);?> <?php echo lang('nav_pending');?></span> <?php echo lang('nav_notifications');?></h3>
                            <!--<a href="page_user_profile_1.html">view all</a>-->
                        </li>
                        <li>
                            <ul class="dropdown-menu-list" style="height: 250px; overflow-y: scroll;" data-handle-color="#637283">
                                <?php
                                if(count($user_reminders)>0){
                                    foreach($user_reminders as $user_reminder){
                                        ?>
                                        <li>                                        
                                            <a href="javascript:;">
                                            <span class="time"><?php echo $user_reminder['reminddate'];?></span>
                                            <span class="details">
                                            <span class="label label-sm label-icon label-success">
                                                <i class="fa fa-bell-o"></i>
                                            </span><b><?php echo $user_reminder['type'];?></b> - <?php echo $user_reminder['subject'];?>  <?php echo $user_reminder['message'];?> <small><?php echo lang('from');?>: <?php echo $user_reminder['fromname'];?></small></span>
                                            </a>
                                        </li>    
                                        <?php
                                    }
                                }
                                ?>                                
                            </ul>
                        </li>
                    </ul>
                </li>
                <!-- END NOTIFICATION DROPDOWN -->
                <!-- BEGIN INBOX DROPDOWN -->
                <!-- DOC: Apply "dropdown-dark" class after below "dropdown-extended" to change the dropdown styte -->
                
                <!-- END INBOX DROPDOWN -->
                <!-- BEGIN TODO DROPDOWN -->
                <!-- DOC: Apply "dropdown-dark" class after below "dropdown-extended" to change the dropdown styte -->
                
                <!-- END TODO DROPDOWN -->
                <!-- BEGIN USER LOGIN DROPDOWN -->
                <!-- DOC: Apply "dropdown-dark" class after below "dropdown-extended" to change the dropdown styte -->
                <li class="dropdown dropdown-user">
                    <a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="true">
                        <!--<img alt="" class="img-circle" src="../assets/layouts/layout/img/avatar3_small.jpg" />-->                        
                        <?php 
                        if(get_user_role()=='customer'){
                            echo customer_profile_image($current_user->userid,array('img-circle')); 
                        }
                        else{
                            echo user_profile_image($current_user->userid,array('img-circle')); 
                        }
                        ?>
                        <span class="username username-hide-on-mobile"> <?php echo $current_user->name;?> </span>
                        <i class="fa fa-angle-down"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-default">
                        <li>
                            <a href="<?php echo base_url('admin/settings/profile')?>">
                                <i class="icon-user"></i> <?php echo lang('page_settings'); ?> </a>
                        </li>
                        <li>
                            <a href="<?php echo base_url('authentication/logout')?>">
                                <i class="icon-key"></i> <?php echo lang('nav_logout'); ?> </a>
                        </li>
                    </ul>
                </li>
                <!-- END USER LOGIN DROPDOWN -->
                
            </ul>
        </div>
        <!-- END TOP NAVIGATION MENU -->
    </div>
    <!-- END HEADER INNER -->
</div>
<!-- END HEADER -->