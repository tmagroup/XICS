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
                                <a href="<?php echo base_url('admin/customers');?>"><?php echo lang('page_customers');?></a>
                                <i class="fa fa-circle"></i>
                            </li>

                            <li>
                                <span>
                                    <?php
                                    echo lang('page_detail_customer');
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
                                        <i class="fa fa-user"></i>
                                        <?php
                                        echo lang('page_detail_customer');
                                        ?>
                                    </div>

                                    <div class="actions">

                                            <?php
                                            if($GLOBALS['customer_permission']['edit']){
                                                ?>
                                                <a href="<?php echo base_url('admin/customers/customer/'.$customer['customernr']);?>" class="btn sbold blue btn-sm"><i class="fa fa-pencil"></i> <?php echo lang('page_edit_customer');?></a>
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
                        $tab_internal_document = '';
                        $tab_reminder = '';
                        $tab_quotation = '';
                        $tab_assignment = '';
                        $tab_ticket = '';
                        $tab_hardwareassignment = '';
                        if(empty($customer['customernr'])){
                            $tab_document = 'none';
                            $tab_internal_document = 'none';
                            $tab_reminder = 'none';
                            $tab_quotation = 'none';
                            $tab_assignment = 'none';
                            $tab_ticket = 'none';
                            $tab_hardwareassignment = 'none';
                        }
                        ?>



                        <?php //echo form_open($this->uri->uri_string(), array('enctype' => "multipart/form-data", 'id' => 'form_customer') );?>


                        <ul class="nav nav-tabs">
                            <li class="active">
                                <a href="#tab_profile" data-toggle="tab"><?php echo lang('page_lb_profile');?></a>
                            </li>
                            <li style="display:<?php echo $tab_document;?>">
                                <a href="#tab_document" data-toggle="tab"><?php echo lang('page_lb_document');?></a>
                            </li>
                            <?php if (get_user_role()!='customer'): ?>
                                <li style="display:<?php echo $tab_internal_document;?>">
                                    <a href="#tab_internal_document" data-toggle="tab"><?php echo lang('page_lb_internal_document');?></a>
                                </li>
                            <?php endif ?>
                            <li style="display:<?php echo $tab_reminder;?>">
                                <a href="#tab_reminder" data-toggle="tab"><?php echo lang('page_lb_reminder');?></a>
                            </li>
                            <li style="display:<?php echo $tab_quotation;?>">
                                <a href="#tab_quotation" data-toggle="tab"><?php echo lang('page_lb_quotations');?></a>
                            </li>
                            <li style="display:<?php echo $tab_assignment;?>">
                                <a href="#tab_assignment" data-toggle="tab"><?php echo lang('page_lb_assignments');?></a>
                            </li>
                            <li style="display:<?php echo $tab_ticket;?>">
                                <a href="#tab_ticket" data-toggle="tab"><?php echo lang('page_lb_tickets');?></a>
                            </li>
                            <li style="display:<?php echo $tab_hardwareassignment;?>">
                                <a href="#tab_hardwareassignment" data-toggle="tab"><?php echo lang('page_lb_hardwareassignments');?></a>
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
                                                    <label><?php echo lang('page_fl_responsible');?>:</label>
                                                    <?php echo $customer['responsible'];?>
                                                </div>

                                                <div class="form-group">
                                                    <label><?php echo lang('page_fl_recommend');?>:</label>
                                                    <?php echo $customer['recommend'];?>
                                                </div>

                                                <div class="form-group">
                                                    <label><?php echo lang('page_fl_customerprovider');?>:</label>
                                                    <?php echo $customer['customerprovidercompanies'];?>
                                                </div>

                                                <div class="form-group">
                                                    <label><?php echo lang('page_fl_framecontno');?>:</label>
                                                    <?php echo $customer['framecontno'];?>
                                                </div>

                                                <div class="form-group">
                                                    <label><?php echo lang('page_fl_company');?>:</label>
                                                    <?php echo $customer['company'];?>
                                                </div>

                                                <div class="form-group">
                                                    <label><?php echo lang('page_fl_salutation');?>:</label>
                                                    <?php echo $customer['salutation'];?>
                                                </div>

                                                <div class="form-group">
                                                    <label><?php echo lang('page_fl_surname');?>:</label>
                                                    <?php echo $customer['surname'];?>
                                                </div>

                                                <div class="form-group">
                                                    <label><?php echo lang('page_fl_name');?>:</label>
                                                    <?php echo $customer['name'];?>
                                                </div>

                                                <div class="form-group">
                                                    <label><?php echo lang('page_fl_contactperson');?>:</label>
                                                    <?php echo $customer['contactperson'];?>
                                                </div>

                                                <div class="form-group">
                                                    <label><?php echo lang('page_fl_position');?>:</label>
                                                    <?php echo $customer['position'];?>
                                                </div>

                                                <div class="form-group">
                                                    <label><?php echo lang('page_fl_email');?>:</label>
                                                    <?php echo $customer['email'];?>
                                                </div>

                                                <div class="form-group">
                                                    <label><?php echo lang('page_fl_phonenumber');?>:</label>
                                                    <?php echo $customer['phone'];?>
                                                </div>

                                                <div class="form-group">
                                                    <label><?php echo lang('page_fl_mobilnr');?>:</label>
                                                    <?php echo $customer['mobilnr'];?>
                                                </div>

                                                <div class="form-group">
                                                    <label><?php echo lang('page_fl_directdialing');?>:</label>
                                                    <?php echo $customer['directdialing'];?>
                                                </div>

                                                <div class="form-group">
                                                    <label><?php echo lang('page_fl_faxnr');?>:</label>
                                                    <?php echo $customer['faxnr'];?>
                                                </div>

                                                <div class="form-group">
                                                    <label><?php echo lang('page_fl_street');?>:</label>
                                                    <?php echo $customer['street'];?>
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
                                                    <label><?php echo lang('page_fl_customerthumb');?> <!--<span class="required"> * </span>--></label>
                                                    <div class="clearfix"></div>
                                                    <div class="fileinput fileinput-new" data-provides="fileinput">
                                                        <div class="fileinput-new thumbnail" style="width: 160px; height: 160px;">

                                                            <?php
                                                            $customernr = isset($customer['customernr'])?$customer['customernr']:'';
                                                            echo customer_profile_image($customernr,array('customer-profile-image'),'thumb');
                                                            ?>

                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <label><?php echo lang('page_fl_zipcode');?>:</label>
                                                    <?php echo $customer['zipcode'];?>
                                                </div>

                                                <div class="form-group">
                                                    <label><?php echo lang('page_fl_city');?>:</label>
                                                    <?php echo $customer['city'];?>
                                                </div>

                                                <div class="form-group">
                                                    <label><?php echo lang('page_fl_registernr');?>:</label>
                                                    <?php echo $customer['registernr'];?>
                                                </div>

                                                <div class="form-group">
                                                    <label><?php echo lang('page_fl_districtcourt');?>:</label>
                                                    <?php echo $customer['districtcourt'];?>
                                                </div>

                                                <div class="form-group">
                                                    <label><?php echo lang('page_fl_monitoring');?>:</label>
                                                    <?php $lbcheck = ($customer['monitoring']==1)?'yes':'no'; echo lang('page_lb_'.$lbcheck); ?>
                                                </div>

                                                <div class="form-group">
                                                    <label><?php echo lang('page_fl_monitoringvalue');?>:</label>
                                                    <?php if($customer['monitoringvalue']){ echo $customer['monitoringvalue'].'%'; }?>
                                                </div>

                                                <?php
                                                if($customer['monitoring']==1){
                                                    ?>
                                                    <div class="form-group">
                                                        <label><?php echo lang('page_fl_monitoringlink');?>:</label>
                                                        <?php echo $customer['monitoringlink'];?>
                                                    </div>

                                                    <div class="form-group">
                                                        <label><?php echo lang('page_fl_monitoringuser');?>:</label>
                                                        <?php echo $customer['monitoringuser'];?>
                                                    </div>

                                                    <div class="form-group">
                                                        <label><?php echo lang('page_fl_monitoringpass');?>:</label>
                                                        <?php echo $customer['monitoringpass'];?>
                                                    </div>
                                                    <?php
                                                }
                                                ?>

                                                <div class="form-group">
                                                    <label><?php echo lang('page_fl_companysize');?>:</label>
                                                    <?php echo $customer['companysize'];?>
                                                </div>

                                                <div class="form-group">
                                                    <label><?php echo lang('page_fl_website');?>:</label>
                                                    <?php echo $customer['website'];?>
                                                </div>

                                                <div class="form-group">
                                                    <label><?php echo lang('page_fl_business');?>:</label>
                                                    <?php echo $customer['business'];?>
                                                </div>

                                            </div>

                                        </div>
                                    </div>
                                    <!-- END SAMPLE FORM PORTLET-->

                                </div>

                            </div>

                            <div class="tab-pane" id="tab_document" style="display:<?php echo $tab_document;?>">

                                <?php
                                $this->load->view('admin/customers/tab-document', array('customer'=>$customer,'categories'=>$categories));
                                ?>

                            </div>

                            <?php if (get_user_role()!='customer'): ?>
                                <div class="tab-pane" id="tab_internal_document" style="display:<?php echo $tab_internal_document;?>">
                                    <?php if(isset($customer['customernr']) && $customer['customernr']>0){
                                        $this->load->view('admin/customers/tab-internal-document', array('customer'=>$customer,'categories'=>$categories));
                                    } ?>
                                </div>
                            <?php endif ?>

                            <div class="tab-pane" id="tab_reminder" style="display:<?php echo $tab_reminder;?>">

                                <?php
                                $this->load->view('admin/customers/tab-reminder', array('customer'=>$customer));
                                ?>

                            </div>

                            <div class="tab-pane" id="tab_quotation" style="display:<?php echo $tab_quotation;?>">

                                <?php
                                $this->load->view('admin/customers/tab-quotation', array('customer'=>$customer));
                                ?>

                            </div>

                            <div class="tab-pane" id="tab_assignment" style="display:<?php echo $tab_assignment;?>">

                                <?php
                                $this->load->view('admin/customers/tab-assignment', array('customer'=>$customer));
                                ?>

                            </div>

                            <div class="tab-pane" id="tab_ticket" style="display:<?php echo $tab_ticket;?>">

                                <?php
                                $this->load->view('admin/customers/tab-ticket', array('customer'=>$customer));
                                ?>

                            </div>

                            <div class="tab-pane" id="tab_hardwareassignment" style="display:<?php echo $tab_hardwareassignment;?>">

                                <?php
                                $this->load->view('admin/customers/tab-hardwareassignment', array('customer'=>$customer));
                                ?>

                            </div>
                        </div>


                        <div class="clearfix"></div>
                        <div class="col-md-12">
                            <!-- BEGIN SAMPLE FORM PORTLET-->
                            <div class="portlet light bordered">
                                <?php
                                $this->load->view('admin/customers/tab-comment', array('customer'=>$customer));
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
                                            <a href="<?php echo base_url('admin/customers')?>"><button type="button" class="btn default"><?php echo lang('close');?></button></a>
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
<?php $this->load->view('admin/customers/customerjs',array('customer'=>$customer, 'remindersubjects'=>$remindersubjects));?>