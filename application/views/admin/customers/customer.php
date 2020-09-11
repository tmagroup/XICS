<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<?php $this->load->view('admin/header.php'); ?>
<style>
.control-label input.required, .form-group input.required{
    color: #4d6b8a;
    padding: 6px 12px;
    font-size: 14px;
}
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
                                <a href="<?php echo base_url('admin/customers');?>"><?php echo lang('page_customers');?></a>
                                <i class="fa fa-circle"></i>
                            </li>

                            <li>
                                <span>
                                    <?php
                                    if(isset($customer['customernr']) && $customer['customernr']>0){
                                        echo lang('page_edit_customer');
                                    }
                                    else
                                    {
                                        echo lang('page_create_customer');
                                    }
                                    ?>
                                </span>
                            </li>

                        </ul>

                    </div>
                    <!-- END PAGE BAR -->

                    <!-- BEGIN PAGE MESSAGE-->
                    <?php $this->load->view('admin/alerts'); ?>
                    <!-- BEGIN PAGE MESSAGE-->

                    <!-- BEGIN PAGE TITLE-->
                    <h3 class="page-title">

                        <?php
                        if(isset($customer['customernr']) && $customer['customernr']>0){
                            ?>
                            <i class="fa fa-pencil"></i>
                            <?php
                            echo lang('page_edit_customer');
                        }
                        else
                        {
                            ?>
                            <i class="fa fa-user-plus"></i>
                            <?php
                            echo lang('page_create_customer');
                        }
                        ?>

                    </h3>
                    <!-- END PAGE TITLE-->
                    <!-- END PAGE HEADER-->


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


                    <div class="row">



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
                                <?php echo form_open($this->uri->uri_string(), array('enctype' => "multipart/form-data", 'id' => 'form_customer') );?>
                                <div class="col-md-6">


                                    <!-- BEGIN SAMPLE FORM PORTLET-->
                                    <div class="portlet light bordered">
                                        <div class="portlet-body form">

                                            <div class="form-body">

                                                <div class="form-group">
                                                    <label><?php echo lang('page_fl_username');?> <span class="required"> * </span></label>
                                                    <?php $readonly = (isset($customer['customernr'])) ? 'readonly' : ''; ?>
                                                    <?php echo form_input('username', isset($customer['username'])?$customer['username']:'', 'class="form-control" '.$readonly);?>
                                                </div>


                                                <!-- Admin and Salesmanager can set Responsible  -->
                                                <?php
                                                if($GLOBALS['current_user']->userrole==1 || $GLOBALS['current_user']->userrole==2){
                                                    ?>
                                                    <div class="form-group">
                                                        <label><?php echo lang('page_fl_responsible');?> <span class="required"> * </span></label>
                                                        <?php echo form_dropdown('responsible', $responsibles, isset($customer['responsible'])?$customer['responsible']:'', 'class="form-control"');?>
                                                    </div>
                                                    <?php
                                                }
                                                else{
                                                    if(isset($customer['customernr'])){
                                                        ?>
                                                        <div class="form-group">
                                                            <label><?php echo lang('page_fl_responsible');?></label>
                                                            <?php echo form_dropdown('responsible', $responsibles, isset($customer['responsible'])?$customer['responsible']:'', 'class="form-control" disabled ');?>
                                                        </div>
                                                        <?php
                                                    }
                                                }
                                                ?>


                                                <div class="form-group">
                                                    <label><?php echo lang('page_fl_recommend');?> <!--<span class="required"> * </span>--></label>
                                                    <?php echo form_dropdown('recommend', $recommends, isset($customer['recommend'])?$customer['recommend']:'', 'class="form-control"');?>
                                                </div>

                                                <div class="form-group">
                                                    <label><?php echo lang('page_fl_customerprovider');?> </label>

                                                    <div id="customerprovider_inputbox">

                                                        <?php
                                                        if(isset($customerprovidercompanies) && count($customerprovidercompanies)>0){
                                                            foreach($customerprovidercompanies as $lkey=>$customerprovidercompany){
                                                                ?>
                                                                <div class="form-group" id="old_customerprovider_<?php echo $customerprovidercompany['id'];?>">

                                                                    <div class="input-group">
                                                                        <?php echo form_hidden('customerprovidercompanyid[]', $customerprovidercompany['id']);?>


                                                                        <?php
                                                                        if($lkey==0){
                                                                            ?>
                                                                            <?php echo form_input('providernr[]', $customerprovidercompany['providernr'], 'class="form-control"  ');?>
                                                                            <span class="input-group-btn"><button type="button" class="btn default blue pull-right addcustomerprovidercompany" data-toggle="tooltip" data-title="<?php echo lang('page_lb_add_more_provider_company_no');?>">+</button>
                                                                            <?php $data_hidden = array('type'=>'hidden', 'name'=>'count_customerprovidercompany', 'id'=>'count_customerprovidercompany', 'value'=>count($customerprovidercompanies));
                                                                            echo form_input($data_hidden);?></span>
                                                                            <?php
                                                                        }
                                                                        else
                                                                        {
                                                                            ?>
                                                                            <?php echo form_input('providernr[]', $customerprovidercompany['providernr'], 'class="form-control" ');?>
                                                                            <span class="input-group-btn"><button type="button" class="btn default red pull-right deletecustomerprovidercompany" onclick="javascript:deletecustomerprovidercompany('<?php echo $customerprovidercompany['id'];?>', 'old');" data-toggle="tooltip" data-title="<?php echo lang('page_lb_delete_provider_company_no');?>">x</button></span>
                                                                            <?php
                                                                        }
                                                                        ?>
                                                                    </div>

                                                                </div>
                                                                <?php
                                                            }
                                                        }
                                                        else{
                                                            ?>
                                                            <div class="form-group">

                                                                <div class="input-group">
                                                                    <?php echo form_input('providernr[]', isset($customer['providernr'][0])?$customer['providernr'][0]:'', 'class="form-control" ');?>
                                                                    <span class="input-group-btn"><button type="button" class="btn default blue addcustomerprovidercompany" data-toggle="tooltip" data-title="<?php echo lang('page_lb_add_more_provider_company_no');?>">+</button>
                                                                    <?php $data_hidden = array('type'=>'hidden', 'name'=>'count_customerprovidercompany', 'id'=>'count_customerprovidercompany', 'value'=>isset($customer['providernr'])?count($customer['providernr']):1);
                                                                    echo form_input($data_hidden);?></span>
                                                                </div>

                                                            </div>
                                                            <?php

                                                            if(isset($customer['providernr']) && count($customer['providernr'])>0){
                                                                foreach($customer['providernr'] as $lkey=>$customerprovidercompany){
                                                                    if($lkey==0){ continue; }
                                                                    ?>
                                                                    <div class="form-group" id="new_customerprovider_<?php echo ($lkey);?>">

                                                                        <div class="input-group">
                                                                            <?php echo form_input('providernr[]', $customerprovidercompany, 'class="form-control" ');?>
                                                                            <span class="input-group-btn"><button type="button" class="btn default red pull-right deletecustomerprovidercompany" onclick="javascript:deletecustomerprovidercompany('<?php echo ($lkey);?>', 'new');" data-toggle="tooltip" data-title="<?php echo lang('page_lb_delete_provider_company_no');?>">x</button></span>
                                                                        </div>

                                                                    </div>
                                                                    <?php
                                                                }
                                                            }
                                                        }
                                                        ?>

                                                    </div>

                                                </div>

                                                <div class="form-group">
                                                    <label><?php echo lang('page_fl_custpassword');?></label>
                                                    <?php echo form_input('userpassword', isset($customer['userpassword'])?$customer['userpassword']:'', 'class="form-control"');?>
                                                </div>

                                                <div class="form-group">
                                                    <label><?php echo lang('page_fl_userpassword');?> <?php if (!isset($customer['customernr'])): echo '<span class="required"> * </span>'; endif ?></label>
                                                    <?php echo form_password('password', "", 'class="form-control" id="submit_form_password"');?>
                                                </div>

                                                <div class="form-group">
                                                    <label><?php echo lang('page_fl_cpassword');?> <?php if (!isset($customer['customernr'])): echo '<span class="required"> * </span>'; endif ?></label>
                                                    <?php echo form_password('cpassword', "", 'class="form-control"');?>
                                                </div>

                                                <div class="form-group">
                                                    <label><?php echo lang('page_fl_framecontno');?></label>
                                                    <?php echo form_input('framecontno', isset($customer['framecontno'])?$customer['framecontno']:'', 'class="form-control"');?>
                                                </div>

                                                <div class="form-group">
                                                    <label><?php echo lang('page_fl_company');?> <span class="required"> * </span></label>
                                                    <?php echo form_input('company', isset($customer['company'])?$customer['company']:'', 'class="form-control"');?>
                                                </div>

                                                <div class="form-group">
                                                    <label><?php echo lang('page_fl_salutation');?> <span class="required"> * </span></label>
                                                    <?php echo form_dropdown('salutation', $salutations, isset($customer['salutation'])?$customer['salutation']:'', 'class="form-control"');?>
                                                </div>

                                                <div class="form-group">
                                                    <label><?php echo lang('page_fl_surname');?> <span class="required"> * </span></label>
                                                    <?php echo form_input('surname', isset($customer['surname'])?$customer['surname']:'', 'class="form-control"');?>
                                                </div>

                                                <div class="form-group">
                                                    <label><?php echo lang('page_fl_name');?> <span class="required"> * </span></label>
                                                    <?php echo form_input('name', isset($customer['name'])?$customer['name']:'', 'class="form-control"');?>
                                                </div>

                                                <div class="form-group">
                                                    <label><?php echo lang('page_fl_contactperson');?> </label>
                                                    <?php echo form_input('contactperson', isset($customer['contactperson'])?$customer['contactperson']:'', 'class="form-control"');?>
                                                </div>

                                                <div class="form-group">
                                                    <label><?php echo lang('page_fl_position');?> </label>
                                                    <?php echo form_input('position', isset($customer['position'])?$customer['position']:'', 'class="form-control"');?>
                                                </div>

                                                <div class="form-group">
                                                    <label><?php echo lang('page_fl_email');?> <span class="required"> * </span></label>
                                                    <?php echo form_input(array('type'=>'email','name'=>'email'), isset($customer['email'])?$customer['email']:'', 'class="form-control"');?>
                                                </div>

                                                <div class="form-group">
                                                    <label><?php echo lang('page_fl_phonenumber');?> <span class="required"> * </span></label>
                                                    <?php echo form_input('phone', isset($customer['phone'])?$customer['phone']:'', 'class="form-control"');?>
                                                </div>

                                                <div class="form-group">
                                                    <label><?php echo lang('page_fl_mobilnr');?></label>
                                                    <?php echo form_input('mobilnr', isset($customer['mobilnr'])?$customer['mobilnr']:'', 'class="form-control"');?>
                                                </div>

                                                <div class="form-group">
                                                    <label><?php echo lang('page_fl_directdialing');?> </label>
                                                    <?php echo form_input('directdialing', isset($customer['directdialing'])?$customer['directdialing']:'', 'class="form-control"');?>
                                                </div>

                                                <div class="form-group">
                                                    <label><?php echo lang('page_fl_faxnr');?> </label>
                                                    <?php echo form_input('faxnr', isset($customer['faxnr'])?$customer['faxnr']:'', 'class="form-control"');?>
                                                </div>

                                                <div class="form-group">
                                                    <label><?php echo lang('page_fl_street');?> <span class="required"> * </span></label>
                                                    <?php echo form_textarea(array('name'=>'street','rows'=>1), isset($customer['street'])?$customer['street']:'', 'class="form-control"');?>
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

                                                            <!--<img src="<?php echo base_url('assets/pages/img/avatars/user-placeholder.jpg');?>" alt="" />-->
                                                            <?php
                                                            $customernr = isset($customer['customernr'])?$customer['customernr']:'';
                                                            echo customer_profile_image($customernr,array('customer-profile-image'),'thumb');
                                                            ?>

                                                        </div>
                                                        <div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 160px; max-height: 160px;"> </div>
                                                        <div>
                                                            <span class="btn default btn-file">
                                                                <span class="fileinput-new"> <?php echo lang('page_lb_selectimage');?> </span>
                                                                <span class="fileinput-exists"> <?php echo lang('page_lb_change');?> </span>
                                                                <!--<input type="file" name="...">-->
                                                                <?php
                                                                echo form_upload('customerthumb');
                                                                ?>
                                                            </span>
                                                            <a href="javascript:;" class="btn default fileinput-exists" data-dismiss="fileinput"> <?php echo lang('page_lb_remove');?> </a>
                                                        </div>
                                                    </div>
                                                    <div class="clearfix margin-top-10">
                                                        <span class="label label-danger"><?php echo lang('page_lb_note');?> </span>
                                                        <span>&nbsp;<?php echo lang('page_lb_selectimage_note_text');?></span>
                                                    </div>
                                                </div>


                                                <div class="form-group">
                                                    <label><?php echo lang('page_fl_zipcode');?> <span class="required"> * </span></label>
                                                    <?php echo form_input('zipcode', isset($customer['zipcode'])?$customer['zipcode']:'', 'class="form-control"');?>
                                                </div>

                                                <div class="form-group">
                                                    <label><?php echo lang('page_fl_city');?> <span class="required"> * </span></label>
                                                    <?php echo form_input('city', isset($customer['city'])?$customer['city']:'', 'class="form-control"');?>
                                                </div>

                                                <div class="form-group">
                                                    <label><?php echo lang('page_fl_registernr');?> </label>
                                                    <?php echo form_input('registernr', isset($customer['registernr'])?$customer['registernr']:'', 'class="form-control"');?>
                                                </div>

                                                <div class="form-group">
                                                    <label><?php echo lang('page_fl_districtcourt');?> </label>
                                                    <?php echo form_input('districtcourt', isset($customer['districtcourt'])?$customer['districtcourt']:'', 'class="form-control"');?>
                                                </div>

                                                <div class="form-group form-horizontal">
                                                    <div class="row">
                                                        <div class="col-sm-4">
                                                            <label class="control-label">
                                                                <?php echo lang('page_fl_monitoring');?>
                                                                <?php
                                                                $monitoring = (isset($customer['monitoring']) && $customer['monitoring']==1)?true:false;
                                                                $dc = array('name'=>'monitoring','id'=>'monitoring','class'=>'form-control','checked'=>$monitoring, 'value'=>1);
                                                                echo form_checkbox($dc);?>
                                                            </label>
                                                        </div>
                                                        <label class="col-sm-2 control-label"><?php echo lang('page_fl_monitoringvalue');?>:</label>
                                                        <div class="col-md-6">
                                                            <?php echo form_dropdown('monitoringvalue', $monitoringvalues, isset($customer['monitoringvalue'])?$customer['monitoringvalue']:'', 'class="form-control"');?>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="form-group" id="fld_monitoringlink">
                                                    <label><?php echo lang('page_fl_monitoringlink');?> <span class="required"> * </span></label>
                                                    <?php echo form_input('monitoringlink', isset($customer['monitoringlink'])?$customer['monitoringlink']:'', 'class="form-control" id="monitoringlink" ');?>
                                                </div>

                                                <div class="form-group" id="fld_monitoringuser">
                                                    <label><?php echo lang('page_fl_monitoringuser');?> <span class="required"> * </span></label>
                                                    <?php echo form_input('monitoringuser', isset($customer['monitoringuser'])?$customer['monitoringuser']:'', 'class="form-control" id="monitoringuser" ');?>
                                                </div>

                                                <div class="form-group" id="fld_monitoringpass">
                                                    <label><?php echo lang('page_fl_monitoringpass');?> <span class="required"> * </span></label>
                                                    <?php echo form_input('monitoringpass', isset($customer['monitoringpass'])?$customer['monitoringpass']:'', 'class="form-control" id="monitoringpass" ');?>
                                                </div>

                                                <div class="form-group">
                                                    <label><?php echo lang('page_fl_companysize');?> <span class="required"> * </span></label>
                                                    <?php echo form_dropdown('companysize', $companysizes, isset($customer['companysize'])?$customer['companysize']:'', 'class="form-control"');?>
                                                </div>

                                                <div class="form-group">
                                                    <label><?php echo lang('page_fl_website');?> </label>
                                                    <?php echo form_input('website', isset($customer['website'])?$customer['website']:'', 'class="form-control"');?>
                                                </div>

                                                <div class="form-group">
                                                    <label><?php echo lang('page_fl_business');?> </label>
                                                    <?php echo form_input('business', isset($customer['business'])?$customer['business']:'', 'class="form-control"');?>
                                                </div>

                                                <div class="form-group">
                                                    <label><?php echo lang('page_fl_lastcontact');?> </label>

                                                    <div class="input-group date form_datetime">
                                                        <?php $dd = array('name'=>'lastcontact', 'class'=>'form-control', 'readonly'=>true, 'size'=>16, 'value'=> isset($customer['lastcontact'])?_dt($customer['lastcontact']):_dt(date('Y-m-d H:i:s')));
                                                        echo form_input($dd);?>

                                                        <span class="input-group-btn">
                                                            <button class="btn default date-set" type="button">
                                                                <i class="fa fa-calendar"></i>
                                                            </button>
                                                        </span>
                                                    </div>

                                                </div>

                                                <div class="form-group">
                                                    <label><?php echo lang('page_fl_provider');?> </label>
                                                    <?php echo form_dropdown('provider', provider_values(), isset($customer['provider'])?$customer['provider']:'', 'class="form-control"');?>
                                                </div>

                                            </div>

                                        </div>
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
                                                    <button type="submit" class="btn blue"><?php echo lang('save');?></button>
                                                    <a href="<?php echo base_url('admin/customers')?>"><button type="button" class="btn default"><?php echo lang('cancel');?></button></a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- END SAMPLE FORM PORTLET-->
                                </div>

                                <?php echo form_close();?>
                            </div>

                            <div class="tab-pane" id="tab_document" style="display:<?php echo $tab_document;?>">

                                <?php
                                if(isset($customer['customernr']) && $customer['customernr']>0){
                                    $this->load->view('admin/customers/tab-document', array('customer'=>$customer,'categories'=>$categories));
                                }
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
                                if(isset($customer['customernr']) && $customer['customernr']>0){
                                    $this->load->view('admin/customers/tab-reminder', array('customer'=>$customer));
                                }
                                ?>

                            </div>
                            <div class="tab-pane" id="tab_quotation" style="display:<?php echo $tab_quotation;?>">

                                <?php
                                if(isset($customer['customernr']) && $customer['customernr']>0){
                                    $this->load->view('admin/customers/tab-quotation', array('customer'=>$customer));
                                }
                                ?>

                            </div>

                            <div class="tab-pane" id="tab_assignment" style="display:<?php echo $tab_assignment;?>">

                                <?php
                                if(isset($customer['customernr']) && $customer['customernr']>0){
                                    $this->load->view('admin/customers/tab-assignment', array('customer'=>$customer));
                                }
                                ?>

                            </div>

                            <div class="tab-pane" id="tab_ticket" style="display:<?php echo $tab_ticket;?>">

                                <?php
                                if(isset($customer['customernr']) && $customer['customernr']>0){
                                    $this->load->view('admin/customers/tab-ticket', array('customer'=>$customer));
                                }
                                ?>

                            </div>

                            <div class="tab-pane" id="tab_hardwareassignment" style="display:<?php echo $tab_hardwareassignment;?>">

                                <?php
                                if(isset($customer['customernr']) && $customer['customernr']>0){
                                    $this->load->view('admin/customers/tab-hardwareassignment', array('customer'=>$customer));
                                }
                                ?>

                            </div>
                        </div>






                    </div>


                </div>
                <!-- END CONTENT BODY -->
            </div>
            <!-- END CONTENT -->


        </div>
        <!-- END CONTAINER -->

<script>
    var form_id = 'form_customer';
    var func_FormValidation = 'FormCustomValidation';

    function after_func_FormValidation(form1, error1, success1){

        form1.validate({
            errorElement: 'span', //default input error message container
            errorClass: 'help-block help-block-error', // default input error message class
            focusInvalid: false, // do not focus the last invalid input
            ignore: "",  // validate all fields including form hidden input

            rules: {
                username: {
                    minlength: 2,
                    required: true
                },
                password: {
                    minlength: 5,
                    required: <?php echo isset($customer['customernr'])?'false':'true'?>
                },
                cpassword: {
                    minlength: 5,
                    required: <?php echo isset($customer['customernr'])?'false':'true'?>,
                    equalTo: "#submit_form_password"
                },
                responsible: {
                    required: true
                },
                /*recommend: {
                    required: true
                },*/
                /*framecontno: {
                    required: true
                },*/
                company: {
                    minlength: 2,
                    required: true
                },
                salutation: {
                    required: true
                },
                surname: {
                    minlength: 2,
                    required: true
                },
                name: {
                    minlength: 2,
                    required: true
                },
                /*contactperson: {
                    minlength: 2,
                    required: true
                },
                position: {
                    required: true
                },*/
                email: {
                    required: true,
                    email: true
                },
                phone: {
                    required: true
                },
                /*mobilnr: {
                    required: true
                },*/
                /*directdialing: {
                    required: true
                },
                faxnr: {
                    required: true
                },*/
                street: {
                    required: true
                },
                zipcode: {
                    required: true
                },
                city: {
                    required: true
                },
                customerthumb: {
                  extension: "jpg|jpeg|png"
                },
                /*registernr: {
                    required: true
                },
                districtcourt: {
                    required: true
                },*/
                companysize: {
                    required: true
                },
                /*website: {
                    required: true
                },
                business: {
                    required: true
                },*/
            },

            invalidHandler: function (event, validator) { //display error alert on form submit
                    success1.hide();
                    error1.show();
                    App.scrollTo(error1, -200);
            },

            highlight: function (element) { // hightlight error inputs
                    $(element)
                            .closest('.form-group').addClass('has-error'); // set error class to the control group
            },

            unhighlight: function (element) { // revert the change done by hightlight
                    $(element)
                            .closest('.form-group').removeClass('has-error'); // set error class to the control group
            },

            success: function (label) {
                    label
                            .closest('.form-group').removeClass('has-error'); // set success class to the control group
            },

            submitHandler: function (form) {
                    //success1.show();
                    error1.hide();
                    App.scrollTo(error1, -200);
                    return true;
            }
	});
    }
</script>

<?php $this->load->view('admin/footer.php'); ?>

<script>
jQuery('.addcustomerprovidercompany').click( function(){
    var rownum = parseInt(jQuery('#count_customerprovidercompany').val()) + 1;
    var inputhtml = '<div class="form-group" id="new_customerprovider_'+rownum+'"><div class="input-group"><input type="text" name="providernr[]" value="" class="form-control"><span class="input-group-btn"><button type="button" class="btn default red pull-right" onclick="javascript:deletecustomerprovidercompany('+rownum+', \'new\')" data-toggle="tooltip" data-title="<?php echo lang('page_lb_delete_provider_company_no');?>">x</button></span></div></div>';
    jQuery('#customerprovider_inputbox').append(inputhtml);
    jQuery('#count_customerprovidercompany').val(rownum);
    jQuery('[data-toggle="tooltip"]').tooltip();
});
function deletecustomerprovidercompany(dataid,datatype){
    var rownum = jQuery('#count_customerprovidercompany').val();
    if(datatype=='old'){
        //Delete record from db by ajax
        jQuery.ajax({url: '<?php echo base_url('admin/customers/deleteProviderCompany/');?>'+dataid, success: function(result){
            jQuery('#old_customerprovider_'+dataid).remove();
        }});
    }
    else{
        jQuery('#new_customerprovider_'+dataid).remove();
    }
    jQuery('#count_customerprovidercompany').val(rownum);
}

//Monitoring Fields Show and Hide
function monitoringfields(){
    if(jQuery('#monitoring').is(':checked')==true){
        jQuery('#fld_monitoringlink').show();
        jQuery('#monitoringlink').addClass('required');
        jQuery('#fld_monitoringuser').show();
        jQuery('#monitoringuser').addClass('required');
        jQuery('#fld_monitoringpass').show();
        jQuery('#monitoringpass').addClass('required');
    }
    else{
        jQuery('#fld_monitoringlink').hide();
        jQuery('#monitoringlink').removeClass('required');
        jQuery('#fld_monitoringuser').hide();
        jQuery('#monitoringuser').removeClass('required');
        jQuery('#fld_monitoringpass').hide();
        jQuery('#monitoringpass').removeClass('required');
    }
}
jQuery('#monitoring').click( function(){
    monitoringfields();
});
monitoringfields();
</script>
<?php $this->load->view('admin/customers/customerjs',array('customer'=>isset($customer)?$customer:'', 'remindersubjects'=>$remindersubjects));?>
