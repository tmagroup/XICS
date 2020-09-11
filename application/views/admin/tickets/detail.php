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
                                <a href="<?php echo base_url('admin/tickets');?>"><?php echo lang('page_tickets');?></a>
                                <i class="fa fa-circle"></i>
                            </li> 
                            
                            <li>
                                <span>
                                    <?php
                                    echo lang('page_detail_ticket'); 
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
                                        <i class="fa fa-ticket"></i>
                                        <?php
                                        echo lang('page_detail_ticket');
                                        ?>
                                    </div>
                                    
                                    <?php
                                    if($GLOBALS['ticket_permission']['edit']){
                                        ?>
                                        <div class="actions">
                                            <a href="<?php echo base_url('admin/tickets/ticket/'.$ticket['ticketnr']);?>" class="btn sbold blue btn-sm"><i class="fa fa-pencil"></i> <?php echo lang('page_edit_ticket');?></a>
                                        </div>
                                        <?php
                                    }
                                    ?>
                                </div>                            
                            </div>
                            <!-- END EXAMPLE TABLE PORTLET-->
                   
                    
                        <?php
                        //Only Editable 
                        $tab_document = '';
                        if(empty($ticket['ticketnr'])){
                           $tab_document = 'none';
                        }					
                        ?>

                    
                    
                        <?php //echo form_open($this->uri->uri_string(), array('enctype' => "multipart/form-data", 'id' => 'form_ticket') );?>
                        
                        
                        <ul class="nav nav-tabs">
                            <li class="active">
                                <a href="#tab_profile" data-toggle="tab"><?php echo lang('page_lb_profile');?></a>
                            </li>
                            <li style="display:<?php echo $tab_document;?>">
                                <a href="#tab_document" data-toggle="tab"><?php echo lang('page_lb_attachment');?></a>
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
                                                    <label><?php echo lang('page_fl_tickettitle');?>:</label>
                                                    <?php echo $ticket['tickettitle'];?>
                                                </div>

                                                <div class="form-group">
                                                    <label><?php echo lang('page_fl_company');?>:</label>
                                                    <?php echo $ticket['customer_company'];?>
                                                </div>

                                                <div class="form-group">
                                                    <label><?php echo lang('page_fl_ticketstatus');?>:</label>
                                                    <?php echo $ticket['ticketstatusname'];?>
                                                </div>
                                                
                                                <div class="form-group">
                                                    <label><?php echo lang('page_fl_ticketdesc');?>:</label>
                                                    <?php echo $ticket['ticketdesc'];?>
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
                                                
                                                <div class="form-group" style="display:<?php if(get_user_role()=='customer'){ echo 'none'; }?>">
                                                    <label><?php echo lang('page_fl_customer');?>:</label>
                                                    <?php echo $ticket['customer'];?>
                                                </div>

                                                <div class="form-group">
                                                    <label><?php echo lang('page_fl_responsible');?>:</label>
                                                    <?php echo $ticket['responsible'];?>
                                                </div>
                                                
                                                <div class="form-group">
                                                    <label><?php echo lang('page_fl_created_by');?>:</label>
                                                    <?php echo $ticket['created_by'];?>
                                                </div>
                                        
                                                <div class="form-group" style="display:<?php if(get_user_role()=='customer'){ echo 'none'; }?>">
                                                    <label><?php echo lang('page_fl_teamwork');?>:</label>
                                                    <?php echo $ticket['teamwork'];?>
                                                </div>
                                                
                                            </div>    
                                            
                                        </div>
                                    </div>
                                    <!-- END SAMPLE FORM PORTLET-->
                                    
                                </div>
                        
                            </div>
                            
                            <div class="tab-pane" id="tab_document" style="display:<?php echo $tab_document;?>">
                                
                                <?php 
                                $this->load->view('admin/tickets/tab-document', array('ticket'=>$ticket)); 
                                ?>
                                
                            </div>
                            
                        </div>        
                        
                        
                        <div class="clearfix"></div>
                        <div class="col-md-12">
                            <!-- BEGIN SAMPLE FORM PORTLET-->
                            <div class="portlet light bordered"> 
                                <?php
                                $this->load->view('admin/tickets/tab-comment', array('ticket'=>$ticket));
                                ?>
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
                                            <!--<button type="submit" class="btn blue"><?php echo lang('save');?></button>-->
                                            <a href="<?php echo base_url('admin/tickets')?>"><button type="button" class="btn default"><?php echo lang('close');?></button></a>
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
<?php $this->load->view('admin/tickets/ticketjs',array('ticket'=>$ticket));?>