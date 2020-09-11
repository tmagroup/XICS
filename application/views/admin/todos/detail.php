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
                                <a href="<?php echo base_url('admin/todos');?>"><?php echo lang('page_todos');?></a>
                                <i class="fa fa-circle"></i>
                            </li>

                            <li>
                                <span>
                                    <?php
                                    echo lang('page_detail_todo');
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

                        <?php //echo form_open($this->uri->uri_string(), array('enctype' => "multipart/form-data", 'id' => 'form_todo') );?>


                        <!-- BEGIN EXAMPLE TABLE PORTLET-->
                        <div class="portlet light"  style="border:0px; margin-bottom: 0px; padding-bottom: 0px;">
                            <div class="portlet-title" style="border:0px; margin-bottom: 0px; padding-bottom: 0px;">
                                <div class="caption">
                                    <i class="fa fa-comment"></i>
                                    <?php
                                    echo lang('page_detail_todo');
                                    ?>
                                </div>

                                <?php
                                if($GLOBALS['todo_permission']['edit']){
                                    ?>
                                    <div class="actions">
                                        <a href="<?php echo base_url('admin/todos/todo/'.$todo['todonr']);?>" class="btn sbold blue btn-sm"><i class="fa fa-pencil"></i> <?php echo lang('page_edit_todo');?></a>
                                    </div>
                                    <?php
                                }
                                ?>
                            </div>
                        </div>
                        <!-- END EXAMPLE TABLE PORTLET-->


                        <div class="col-md-6">


                            <!-- BEGIN SAMPLE FORM PORTLET-->
                            <div class="portlet light bordered">
                                <div class="portlet-body form">

                                    <div class="form-body">

                                        <div class="form-group">
                                            <label><?php echo lang('page_fl_todotitle');?>:</label>
                                            <?php echo $todo['todotitle'];?>
                                        </div>

                                        <!--<div class="form-group">
                                            <label><?php echo lang('page_fl_company');?>:</label>
                                            <?php echo $todo['company'];?>
                                        </div>-->

                                        <div class="form-group">
                                            <label><?php echo lang('page_fl_startdate');?>:</label>
                                            <?php echo _d($todo['startdate']);?>
                                        </div>

                                        <div class="form-group">
                                            <label><?php echo lang('page_fl_todostatus');?>:</label>
                                            <?php echo $todo['todostatus'];?>
                                        </div>

                                        <div class="form-group">
                                            <label><?php echo lang('page_fl_customer');?>:</label>
                                            <?php echo $todo['customer'];?>
                                        </div>

                                        <div class="form-group">
                                            <label><?php echo lang('page_fl_responsible');?>:</label>
                                            <?php echo $todo['responsible'];?>
                                        </div>

                                        <div class="form-group">
                                            <label><?php echo lang('page_fl_created_by');?>:</label>
                                            <?php echo $todo['created_by'];?>
                                        </div>

                                        <div class="form-group">
                                            <label><?php echo lang('page_fl_teamwork');?>:</label>
                                            <?php echo $todo['teamwork'];?>
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
                                            <label><?php echo lang('page_fl_tododesc');?>:</label>
                                            <?php echo html_entity_decode($todo['tododesc']);?>
                                        </div>

                                        <div class="form-group">
                                            <label><?php echo lang('page_fl_reminderdate');?>:</label>
                                            <?php echo _d($todo['reminderdate']);?>
                                        </div>

                                        <div class="form-group">
                                            <label><?php echo lang('page_fl_reminderway');?>:</label>
                                            <?php $lbcheck = ($todo['reminderway']==1)?'yes':'no'; echo lang('page_lb_'.$lbcheck); ?>
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
                                <?php
                                $this->load->view('admin/todos/tab-comment', array('todo'=>$todo));
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
                                            <a href="<?php echo base_url('admin/todos')?>"><button type="button" class="btn default"><?php echo lang('close');?></button></a>
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
<?php $this->load->view('admin/todos/todojs',array('todo'=>$todo));?>