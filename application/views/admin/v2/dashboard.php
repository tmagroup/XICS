<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<?php $this->load->view('admin/header.php'); ?>

<style>
/*.row {
display: flex;
flex-wrap: wrap;
padding: 0 4px;
}
.column {
flex: 33.33%;
padding: 0 4px;
}*/
</style>

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
                                <span><?php echo lang('page_dashboard');?></span>
                            </li>
                        </ul>
                    </div>
                    <!-- END PAGE BAR -->
                    <!-- BEGIN PAGE TITLE-->
                    <h3 class="page-title"><?php echo lang('welcome_to')?> <?php echo ($GLOBALS['current_user']->name.' '.$GLOBALS['current_user']->surname);?></h3>
                    <!-- END PAGE TITLE-->
                    <!-- END PAGE HEADER-->
                    
                    
                    <!-- BEGIN DASHBOARD STATS 1-->
                    <div class="row" id="sortable_portlets">  
                        
                        <?php
                        //1) When Salesman login he see on his Dashboard:
                        if($GLOBALS['current_user']->userrole==3){
                            ?>                        
                            <div class="col-md-4 column sortable">
                                <?php 
                                $this->load->view('admin/widget-points', array('dashboard_points'=>$dashboard_points)); 
                                $this->load->view('admin/widget-todos', array('dashboard_todos'=>$dashboard_todos)); 
                                $this->load->view('admin/widget-events', array('dashboard_events'=>$dashboard_events)); 
                                ?>  
                            </div> 
                            <div class="col-md-4 column sortable">
                                <?php 
                                $this->load->view('admin/widget-leads', array('dashboard_leads'=>$dashboard_leads)); 
                                $this->load->view('admin/widget-assignments', array('dashboard_assignments'=>$dashboard_assignments)); 
                                ?>
                            </div>    
                            <div class="col-md-4 column sortable">
                                <?php 
                                $this->load->view('admin/widget-tickets', array('dashboard_tickets'=>$dashboard_tickets)); 
                                $this->load->view('admin/widget-quotations', array('dashboard_quotations'=>$dashboard_quotations)); 
                                ?>
                            </div>   
                            <!-- empty sortable porlet required for each columns! -->
                            <div class="portlet portlet-sortable-empty"> </div>
                            <?php
                        }
                        //2) When Salesmanager login he see on his Dashboard:
                        //3) When Admin login he see on his Dashboard:
                        else if($GLOBALS['current_user']->userrole==1 || $GLOBALS['current_user']->userrole==2){
                            ?>                        
                            <div class="col-md-4 column sortable">
                                <?php 
                                $this->load->view('admin/widget-leads', array('dashboard_leads'=>$dashboard_leads)); 
                                $this->load->view('admin/widget-assignments', array('dashboard_assignments'=>$dashboard_assignments));
                                ?>
                            </div> 
                            <div class="col-md-4 column sortable">
                                <?php 
                                $this->load->view('admin/widget-tickets', array('dashboard_tickets'=>$dashboard_tickets)); 
                                $this->load->view('admin/widget-quotations', array('dashboard_quotations'=>$dashboard_quotations));  
                                ?>
                            </div>    
                            <div class="col-md-4 column sortable">
                                <?php 
                                $this->load->view('admin/widget-todos', array('dashboard_todos'=>$dashboard_todos));                                 
                                ?>
                            </div>  
                            <!-- empty sortable porlet required for each columns! -->
                            <div class="portlet portlet-sortable-empty"> </div>
                            <?php
                        }                        
                        //4) When Accounting login he see on his Dashboard:
                        else if($GLOBALS['current_user']->userrole==7){
                            ?>                        
                            <div class="col-md-4 column sortable">
                                <?php 
                                $this->load->view('admin/widget-tickets', array('dashboard_tickets'=>$dashboard_tickets)); 
                                ?>
                            </div> 
                            <div class="col-md-4 column sortable">
                                <?php 
                                $this->load->view('admin/widget-todos', array('dashboard_todos'=>$dashboard_todos)); 
                                ?>
                            </div>
                            <!-- empty sortable porlet required for each columns! -->
                            <div class="portlet portlet-sortable-empty"> </div>
                            <?php
                        }
                        //5) When POS login he see on his Dashboard:
                        else if($GLOBALS['current_user']->userrole==6){
                            ?>                        
                            <div class="col-md-4 column sortable">
                                <?php 
                                $this->load->view('admin/widget-points', array('dashboard_points'=>$dashboard_points)); 
                                $this->load->view('admin/widget-assignments', array('dashboard_assignments'=>$dashboard_assignments)); 
                                ?>
                            </div> 
                            <div class="col-md-4 column sortable">
                                <?php 
                                $this->load->view('admin/widget-leads', array('dashboard_leads'=>$dashboard_leads)); 
                                ?>
                            </div>    
                            <div class="col-md-4 column sortable">
                                <?php 
                                $this->load->view('admin/widget-tickets', array('dashboard_tickets'=>$dashboard_tickets)); 
                                ?>
                            </div>  
                            <!-- empty sortable porlet required for each columns! -->
                            <div class="portlet portlet-sortable-empty"> </div>
                            <?php
                        }
                        //6) When Supporter login he see on his Dashboard:
                        else if($GLOBALS['current_user']->userrole==5){
                            ?>                        
                            <div class="col-md-4 column sortable">
                                <?php 
                                $this->load->view('admin/widget-leads', array('dashboard_leads'=>$dashboard_leads)); 
                                $this->load->view('admin/widget-qualitychecks', array('dashboard_qualitychecks'=>$dashboard_qualitychecks)); 
                                ?>
                            </div> 
                            <div class="col-md-4 column sortable">
                                <?php 
                                $this->load->view('admin/widget-tickets', array('dashboard_tickets'=>$dashboard_tickets)); 
                                ?>
                            </div>    
                            <div class="col-md-4 column sortable">
                                <?php 
                                $this->load->view('admin/widget-todos', array('dashboard_todos'=>$dashboard_todos)); 
                                ?>
                            </div>  
                            <!-- empty sortable porlet required for each columns! -->
                            <div class="portlet portlet-sortable-empty"> </div>
                            <?php
                        }
                        //7) When Customer login he see on his Dashboard:
                        else if(get_user_role()=='customer'){
                            ?>                        
                            <div class="col-md-4 column sortable">
                                <?php 
                                $this->load->view('admin/widget-assignments', array('dashboard_assignments'=>$dashboard_assignments));
                                $this->load->view('admin/widget-hardwareinvoices', array('dashboard_hardwareinvoices'=>$dashboard_hardwareinvoices));  
                                ?>
                            </div> 
                            <div class="col-md-4 column sortable">
                                <?php 
                                $this->load->view('admin/widget-hardwareassignments', array('dashboard_hardwareassignments'=>$dashboard_hardwareassignments));  
                                $this->load->view('admin/widget-monitorings', array('dashboard_monitorings'=>$dashboard_monitorings));  
                                ?>
                            </div>    
                            <div class="col-md-4 column sortable">
                                <?php 
                                $this->load->view('admin/widget-tickets', array('dashboard_tickets'=>$dashboard_tickets)); 
                                ?>
                            </div> 
                            <!-- empty sortable porlet required for each columns! -->
                            <div class="portlet portlet-sortable-empty"> </div>
                            <?php
                        }
                        ?>
                        
                    </div>                       
                    <!-- END DASHBOARD STATS 1-->
                    
                    
                </div>
                <!-- END CONTENT BODY -->
            </div>
            <!-- END CONTENT -->
                        
            
        </div>
        <!-- END CONTAINER -->
        
<?php $this->load->view('admin/footer.php'); ?> 