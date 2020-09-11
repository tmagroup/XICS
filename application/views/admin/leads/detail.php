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
                                <a href="<?php echo base_url('admin/leads');?>"><?php echo lang('page_leads');?></a>
                                <i class="fa fa-circle"></i>
                            </li>

                            <li>
                                <span>
                                    <?php
                                    echo lang('page_detail_lead');
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
                                        <i class="fa fa-tty"></i>
                                        <?php
                                        echo lang('page_detail_lead');
                                        ?>
                                    </div>

                                    <?php
                                    /*if (total_rows('tblcustomers', array('leadid' => $lead['leadnr'])) || $lead['leadstatus']==5) {
                                        ?>
                                        <div class="actions">
                                            <div class="ribbon"><?php //echo lang('page_customer');?></div>
                                        </div>
                                        <?php
                                    }
                                    else{*/
                                        if($GLOBALS['leadtocustomer_permission']['create']){
                                            ?>
                                            <div class="actions">
                                                <div class="btn-group btn-group-devided">
                                                    <?php if($GLOBALS['lead_permission']['edit']){ ?>
                                                        <a href="<?php echo base_url('admin/leads/lead/'.$lead['leadnr']); ?>" class="btn sbold green btn-sm"> <i class="fa fa-pencil"></i> <?php echo lang('page_edit_lead');?></a>
                                                    <?php } ?>
                                                    <a href="javascript:void(0);" onclick="FormAjax('<?php echo base_url('admin/leads/addCustomer/'.$lead['leadnr']);?>','<?php echo base_url('admin/leads/getLead/'.$lead['leadnr']);?>','<?php echo lang('page_lb_create_a_customer');?>','customer');" class="btn sbold green btn-sm"> <i class="fa fa-plus"></i> <?php echo lang('page_lb_create_a_customer');?></a>
                                                </div>
                                            </div>
                                            <?php
                                        }
                                    // }
                                    ?>

                                </div>
                            </div>
                            <!-- END EXAMPLE TABLE PORTLET-->


                        <?php
                        //Only Editable
                        $tab_document = '';
                        $tab_reminder = '';
                        if(empty($lead['leadnr'])){
                           $tab_document = 'none';
                           $tab_reminder = 'none';
                        }
                        ?>



                        <?php //echo form_open($this->uri->uri_string(), array('enctype' => "multipart/form-data", 'id' => 'form_lead') );?>


                        <ul class="nav nav-tabs">
                            <li class="active">
                                <a href="#tab_profile" data-toggle="tab"><?php echo lang('page_lb_profile');?></a>
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
                                                    <label><?php echo lang('page_fl_responsible');?>:</label>
                                                    <?php echo $lead['responsible'];?>
                                                </div>

                                                <div class="form-group">
                                                    <label><?php echo lang('page_fl_recommend');?>:</label>
                                                    <?php echo $lead['recommend'];?>
                                                </div>

                                                <div class="form-group">
                                                    <label><?php echo lang('page_fl_leadstatus');?>:</label>
                                                    <?php echo $lead['leadstatusname'];?>
                                                </div>

                                                <div class="form-group">
                                                    <label><?php echo lang('page_fl_leadsource');?>:</label>
                                                    <?php echo $lead['leadsource'];?>
                                                </div>

                                                <div class="form-group">
                                                    <label><?php echo lang('page_fl_leadprovider');?>:</label>
                                                    <?php echo $lead['leadprovidercompanies'];?>
                                                </div>

                                                <div class="form-group">
                                                    <label><?php echo lang('page_fl_custpassword');?>:</label>
                                                    <?php echo $lead['custpassword'];?>
                                                </div>

                                                <div class="form-group">
                                                    <label><?php echo lang('page_fl_framecontno');?>:</label>
                                                    <?php echo $lead['framecontno'];?>
                                                </div>

                                                <div class="form-group">
                                                    <label><?php echo lang('page_fl_company');?>:</label>
                                                    <?php echo $lead['company'];?>
                                                </div>

                                                <div class="form-group">
                                                    <label><?php echo lang('page_fl_street');?>:</label>
                                                    <?php echo $lead['street'];?>
                                                </div>

                                                <div class="form-group">
                                                    <label><?php echo lang('page_fl_zipcode');?>:</label>
                                                    <?php echo $lead['zipcode'];?>
                                                </div>

                                                <div class="form-group">
                                                    <label><?php echo lang('page_fl_city');?>:</label>
                                                    <?php echo $lead['city'];?>
                                                </div>

                                                <div class="form-group">
                                                    <label><?php echo lang('page_fl_country');?>:</label>
                                                    <?php echo $lead['country'];?>
                                                </div>

                                                <div class="form-group">
                                                    <label><?php echo lang('page_fl_phonenumber');?>:</label>
                                                    <?php echo $lead['phone'];?>
                                                </div>

                                                <div class="form-group">
                                                    <label><?php echo lang('page_fl_faxnr');?>:</label>
                                                    <?php echo $lead['faxnr'];?>
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
                                                    <label><?php echo lang('page_fl_email');?>:</label>
                                                    <?php echo $lead['email'];?>
                                                </div>

                                                <div class="form-group">
                                                    <label><?php echo lang('page_fl_companysize');?>:</label>
                                                    <?php echo $lead['companysize'];?>
                                                </div>

                                                <div class="form-group">
                                                    <label><?php echo lang('page_fl_website');?>:</label>
                                                    <?php echo $lead['website'];?>
                                                </div>

                                                <div class="form-group">
                                                    <label><?php echo lang('page_fl_business');?>:</label>
                                                    <?php echo $lead['business'];?>
                                                </div>

                                                <div class="form-group">
                                                    <label><?php echo lang('page_fl_title');?>:</label>
                                                    <?php echo $lead['salutation'];?>
                                                </div>

                                                <div class="form-group">
                                                    <label><?php echo lang('page_fl_surname');?>:</label>
                                                    <?php echo $lead['surname'];?>
                                                </div>

                                                <div class="form-group">
                                                    <label><?php echo lang('page_fl_name');?>:</label>
                                                    <?php echo $lead['name'];?>
                                                </div>

                                                <div class="form-group">
                                                    <label><?php echo lang('page_fl_position');?>:</label>
                                                    <?php echo $lead['position'];?>
                                                </div>

                                                <div class="form-group">
                                                    <label><?php echo lang('page_fl_mobilnr');?>:</label>
                                                    <?php echo $lead['mobilnr'];?>
                                                </div>

                                                <div class="form-group">
                                                    <label><?php echo lang('page_fl_provider');?>:</label>
                                                    <?php echo $lead['provider'];?>
                                                </div>

                                                <div class="form-group">
                                                    <label><?php echo lang('page_fl_product');?>:</label>
                                                    <?php echo $lead['product'];?>
                                                </div>

                                                <div class="form-group" style="display:<?php if(get_user_role()=='customer'){ echo 'none'; }?>">
                                                    <label><?php echo lang('page_fl_teamwork');?>:</label>
                                                    <?php echo $lead['teamwork'];?>
                                                </div>

                                            </div>

                                        </div>
                                    </div>
                                    <!-- END SAMPLE FORM PORTLET-->

                                </div>

                            </div>

                            <div class="tab-pane" id="tab_document" style="display:<?php echo $tab_document;?>">

                                <?php
                                $this->load->view('admin/leads/tab-document', array('lead'=>$lead,'categories'=>$categories));
                                ?>

                            </div>

                            <div class="tab-pane" id="tab_reminder" style="display:<?php echo $tab_reminder;?>">

                                <?php
                                $this->load->view('admin/leads/tab-reminder', array('lead'=>$lead));
                                ?>

                            </div>
                        </div>


                        <div class="clearfix"></div>
                        <div class="col-md-12">
                            <!-- BEGIN SAMPLE FORM PORTLET-->
                            <div class="portlet light bordered">
                                <?php
                                $this->load->view('admin/leads/tab-comment', array('lead'=>$lead));
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
                                            <a href="<?php echo base_url('admin/leads')?>"><button type="button" class="btn default"><?php echo lang('close');?></button></a>
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
<?php $this->load->view('admin/leads/leadjs',array('lead'=>$lead, 'remindersubjects'=>$remindersubjects));?>