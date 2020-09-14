<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<?php $this->load->view('admin/header.php'); ?>

<?php
//Pin and Puk Allow Access
$allowPinPuk = false;
//if(get_user_role()=='customer' || $GLOBALS['current_user']->userrole==1 || $GLOBALS['current_user']->userrole==2 || $GLOBALS['current_user']->userrole==3){
if($GLOBALS['a_pin_puk_permission']['create']){
    $allowPinPuk = true;
}

//More Option Mobile Access
$allowMoreOptionMobile = false;
if($GLOBALS['a_moreoptionmobile_permission']['create']){
    $allowMoreOptionMobile = true;
}
?>

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
                                <a href="<?php echo base_url('admin/assignments');?>"><?php echo lang('page_assignments');?></a>
                                <i class="fa fa-circle"></i>
                            </li>

                            <li>
                                <span>
                                    <?php
                                    if(isset($assignment['assignmentnr']) && $assignment['assignmentnr']>0){
                                        echo lang('page_edit_assignment');
                                    }
                                    else
                                    {
                                        echo lang('page_create_assignment');
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
                        if(isset($assignment['assignmentnr']) && $assignment['assignmentnr']>0){
                            ?>
                            <i class="fa fa-pencil"></i>
                            <?php
                            echo lang('page_edit_assignment');
                        }
                        else
                        {
                            ?>
                            <i class="fa fa-plus"></i>
                            <?php
                            echo lang('page_create_assignment');
                        }
                        ?>

                    </h3>
                    <!-- END PAGE TITLE-->
                    <!-- END PAGE HEADER-->


                    <?php
                    //Only Editable
                    $tab_document = '';
                    $tab_reminder = '';
                    $tab_legitimation = '';
                    if(empty($assignment['assignmentnr'])){
                       $tab_document = 'none';
                       $tab_reminder = 'none';
                       $tab_legitimation = 'none';
                    }

                    //POS - When he go to Assignment and click on the Detailview of one assignment he should not see the Tab "Legitimation", "Dokumente", "Erinnerung hinzufügen"
                    if($GLOBALS['current_user']->userrole==6){
                        $tab_document = 'none';
                        $tab_reminder = 'none';
                        $tab_legitimation = 'none';
                    }
                    ?>


                    <div class="row">

                        <ul class="nav nav-tabs">
                            <li class="active">
                                <a href="#tab_profile" data-toggle="tab"><?php echo lang('page_lb_profile');?></a>
                            </li>
                            <li style="display:<?php echo $tab_legitimation;?>">
                                <a href="#tab_legitimation" data-toggle="tab"><?php echo lang('page_lb_legitimation');?></a>
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
                                <?php echo form_open($this->uri->uri_string(), array('enctype' => "multipart/form-data", 'id' => 'form_assignment') );?>
                                <div class="col-md-6">


                                    <!-- BEGIN SAMPLE FORM PORTLET-->
                                    <div class="portlet light bordered">
                                        <div class="portlet-body form">

                                            <div class="form-body">

                                                <!--<div class="form-group">
                                                    <label><?php //echo lang('page_fl_company');?> <span class="required"> * </span></label>
                                                    <?php //echo form_input('company', isset($assignment['company'])?$assignment['company']:'', 'class="form-control"');?>
                                                </div>-->

                                                <div class="form-group">
                                                    <label><?php echo lang('page_fl_assignment_company');?> <span class="required"> * </span></label>
                                                    <?php echo form_dropdown('customer', $customers, isset($assignment['customer'])?$assignment['customer']:'', 'class="form-control" id="customer" ');?>
                                                </div>

                                                <div class="form-group">
                                                    <label><?php echo lang('page_fl_provider');?> </label>
                                                        <select name="provider" class="form-control" id="provider">
                                                        <?php if(!empty($providerData)) { ?>
                                                            <option value="">Select <?php echo lang('page_fl_provider');?></option>
                                                            <?php foreach($providerData as $provider) {
                                                                $selected = isset($assignment['provider']) && $provider['name'] == $assignment['provider'] ? 'selected=selected' : '';
                                                            ?>
                                                            <option value="<?php echo $provider['name'];?>" <?php echo $selected;?>><?php echo $provider['name'];?></option>
                                                        <?php } ?>
                                                    <?php } ?>
                                                </select>
                                                </div>

                                               <!--  <div class="row">
                                                    <div class="col-md-9">
                                                        <div class="form-group">
                                                            <label><?php //echo lang('page_fl_provider_logo');?> </label>
                                                            <input type="file" name="provider_logo" id="provider_logo" class="form-control" onchange="PreviewImage();" accept="image/*">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <?php //if(isset($assignment['assignmentnr']) && $assignment['assignmentnr'] > 0 && $assignment['provider_logo'] != '') { ?>
                                                            <img src="<?php //echo base_url().'uploads/assignments/provider/'.$assignment['assignmentnr'].'/'.$assignment['provider_logo'];?>" id="logoPreview" width="120px" height="70px">
                                                        <?php// } else { ?>
                                                            <img id="logoPreview"/>
                                                        <?php// } ?>
                                                    </div>
                                                </div>
 -->
                                                <div class="form-group">
                                                    <label><?php echo lang('page_fl_assignmentdate');?> <span class="required"> * </span></label>

                                                    <div class="input-group date form_date1">
                                                        <?php $dd = array('name'=>'assignmentdate', 'class'=>'form-control', 'readonly'=>true, 'size'=>16, 'value'=> isset($assignment['assignmentdate'])?_d($assignment['assignmentdate']):date('d.m.Y'));
                                                        echo form_input($dd);?>

                                                        <span class="input-group-btn">
                                                            <button class="btn default date-set" type="button">
                                                                <i class="fa fa-calendar"></i>
                                                            </button>
                                                        </span>
                                                    </div>

                                                </div>

                                                <div class="form-group">
                                                    <label><?php echo lang('page_fl_assignmentstatus');?> <span class="required"> * </span></label>
                                                    <?php echo form_dropdown('assignmentstatus', $assignmentstatus, isset($assignment['assignmentstatus'])?$assignment['assignmentstatus']:'', 'class="form-control" id="assignmentstatus" ');?>
                                                </div>


                                                <div class="form-group">
                                                    <label><?php echo lang('page_fl_assignmentprovider');?> <span class="required"> * </span></label>
                                                    <?php echo form_input('providercompanynr', isset($assignment['providercompanynr'])?$assignment['providercompanynr']:'', 'class="form-control"');?>
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
                                                    <label><?php echo lang('page_fl_responsible');?> <span class="required"> * </span></label>
                                                    <?php echo form_dropdown('responsible', array(''=>lang('page_option_select')) , '', 'class="form-control" id="responsible" ');?>
                                                </div>

                                                <div class="form-group">
                                                    <label><?php echo lang('page_fl_recommend');?> </label>
                                                    <?php echo form_dropdown('recommend', $recommends, isset($assignment['recommend'])?$assignment['recommend']:'', 'class="form-control"');?>
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
                                        <div class="portlet-body form">


                                            <div class="row">
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label><?php echo lang('page_discountlevel');?> <span class="required"> * </span></label>
                                                        <select name="newdiscountlevel" id="newdiscountlevel" class="form-control">
                                                            <option value=""><?= lang('page_option_select')?></option>
                                                            <?php foreach ($discountlevels as $key => $value): ?>
                                                                <option value="<?= $value['discountnr']?>" <?php (isset($assignment['newdiscountlevel']) && $assignment['newdiscountlevel'] == $value['discountnr']) && print 'selected'; ?> data-provider="<?= $value['provider']?>"><?= $value['discounttitle']?></option>
                                                            <?php endforeach ?>
                                                        </select>

                                                        <?php //echo form_dropdown('newdiscountlevel', $discountlevels, isset($assignment['newdiscountlevel'])?$assignment['newdiscountlevel']:'', 'class="form-control" id="newdiscountlevel" ');?>
                                                    </div>
                                                </div>
                                                <div class="col-md-3 form-group">
                                                    <label><?php echo lang('page_leadquotation_importcsv');?></label>
                                                    <?php echo form_upload('', '', 'class="form-control" id="file_csv" accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel"');?>
                                                </div>
                                                <div class="col-md-6 form-group" style="margin-top: 24px;">
                                                    <button type="button" class="btn blue" id="submit_import_assignment" style="margin-right: 5px;"><?php echo lang('import');?></button>
                                                    <button type="button" class="btn sbold green" id="submit_form_import_assignment"><i class="fa fa-file-excel-o"></i> <?php echo lang('sample_csv');?></button>
                                                    <?php if (get_user_role()!='customer'): ?>
                                                        <a href="<?= base_url('admin/assignments/export_product_csv/'.$assignment['assignmentnr'])?>" class="btn sbold green" ><?php echo lang('page_exportcsv');?></a>                                                </div>
                                                    <?php endif ?>
                                            </div>


                                            <div class="portlet-title">
                                                <div class="caption font-dark">
                                                    <span class="caption-subject font-green-sharp sbold"><?php echo lang('page_lb_assignmentproducts');?></span>
                                                </div>
                                            </div>

                                            <?php if (isset($assignmentproducts) && count($assignmentproducts)>0): ?>
                                                <div id="show_old_products" data-limit="30" data-start="0" data-is_complete="0"></div>
                                            <?php endif ?>

                                            <div class="form-body">

                                                <div class="form-group">
                                                    <div class="table-responsive no-dt">
                                                        <table class="table table-striped table-bordered table-hover dt-responsive" width="100%">
                                                            <thead>
                                                                <tr role="row" class="heading">
                                                                    <th class="text-nowrap"></th>
                                                                    <th class="text-nowrap"><?php echo lang('page_fl_simnr');?> </th>
                                                                    <th class="text-nowrap"><?php echo lang('page_fl_mobilenr');?> <span class="required"> * </span></th>
                                                                    <th class="text-nowrap"><?php echo lang('page_fl_employee');?> </th>
                                                                    <th class="text-nowrap"><?php echo lang('page_fl_vvl_neu');?> <span class="required"> * </span></th>
                                                                    <th class="text-nowrap"><?php echo lang('page_fl_ratetitle');?> <span class="required"> * </span></th>
                                                                    <th class="text-nowrap"><?php echo lang('page_fl_value');?> <span class="required"> * </span></th>
                                                                    <th class=""><?php echo lang('page_fl_extemtedterm');?></th>
                                                                    <?php
                                                                    if($GLOBALS['a_subscriptionlock2_permission']['create']){
                                                                        ?>
                                                                        <th class=""><?php echo lang('page_fl_subscriptionlock');?></th>
                                                                        <?php
                                                                    }
                                                                    ?>
                                                                    <th class="text-nowrap"><?php echo lang('page_fl_optiontitle');?> </th>
                                                                    <th class="text-nowrap"><?php echo lang('page_fl_value');?> </th>
                                                                    <th class="text-nowrap"><?php echo lang('page_fl_hardware');?> </th>
                                                                    <th class=""><?php echo lang('page_fl_cardstatus');?></th>
                                                                    <th class="text-nowrap"><?php echo lang('page_fl_endofcontract');?> </th>
                                                                    <th class=""><?php echo lang('page_fl_finished');?></th>
                                                                </tr>
                                                            </thead>
                                                            <tbody id="assignmentproduct_inputbox">
                                                            <?php
                                                            if(isset($assignmentproducts) && count($assignmentproducts)>0){

                                                                $data_hidden = array('type'=>'hidden', 'name'=>'count_assignmentproduct', 'id'=>'count_assignmentproduct', 'value'=>isset($assignmentproducts)?count($assignmentproducts):1);
                                                                echo form_input($data_hidden); ?>

                                                            <?php
                                                            }
                                                            else{
                                                                ?>

                                                                <?php $data_hidden = array('type'=>'hidden', 'name'=>'count_assignmentproduct', 'id'=>'count_assignmentproduct', 'value'=>isset($assignment['mobilenr'])?count($assignment['mobilenr']):1);
                                                                echo form_input($data_hidden);?>


                                                                <!-- ROW -->
                                                                <tr id="row1_new_assignmentproduct_0">
                                                                    <td class="text-center">

                                                                        <?php
                                                                        if(isset($assignment['mobilenr']) && count($assignment['mobilenr'])>0){
                                                                            ?>
                                                                            <a href="javascript:void(0);" class="btn btn-sm btn-default btn-circle btn-editable red" onClick="deleteassignmentproduct('0','new')"><i class="fa fa-minus"></i></a>
                                                                            <?php
                                                                        }
                                                                        else{
                                                                            ?>
                                                                            <a href="javascript:void(0);" class="btn btn-sm btn-default btn-circle btn-editable green addassignmentproduct" datarow="0" datatype="new" datainit="0"><i class="fa fa-plus"></i></a>
                                                                            <?php
                                                                        }
                                                                        ?>


                                                                        <?php
                                                                        $formula = isset($assignment['new_formula_0'])?$assignment['new_formula_0']:'A';
                                                                        $data_hidden = array('type'=>'hidden', 'name'=>'new_formula_0', 'value'=>$formula);
                                                                        echo form_input($data_hidden);
                                                                        ?>
                                                                    </td>

                                                                    <td><?php echo form_input('simnr[0]', isset($assignment['simnr'][0])?$assignment['simnr'][0]:'', 'class="form-control noerror"  ');?></td>
                                                                    <td><?php echo form_input('mobilenr[0]', isset($assignment['mobilenr'][0])?$assignment['mobilenr'][0]:'', 'class="form-control"  ');?></td>
                                                                    <td><?php echo form_input('employee[0]', isset($assignment['employee'][0])?$assignment['employee'][0]:'', 'class="form-control noerror"  ');?></td>

                                                                    <td><?php echo form_dropdown('vvlneu[0]', $vvlneu, isset($assignment['vvlneu'][0])?$assignment['vvlneu'][0]:'', 'class="form-control vvlneu" datarow="0" datatype="new"  ');?></td>
                                                                    <td id="new_newratemobile_box_0">
                                                                        <?php if($formula=='A') { ?>
                                                                            <select name="newratemobile[0]" class="form-control newratemobile" id="new_newratemobile_0" datarow="0" datatype="new">
                                                                                <option value=""><?= lang('page_option_select')?></option>
                                                                                <?php foreach ($mobilerates as $key => $value): ?>
                                                                                    <option value="<?= $value['ratenr']?>" <?php (isset($assignment['newratemobile'][0]) && $assignment['newratemobile'][0] == $value['ratenr']) && print 'selected'; ?> data-provider="<?= $value['provider']?>"><?= $value['ratetitle']?></option>
                                                                                <?php endforeach ?>
                                                                            </select>

                                                                            <?php
                                                                        } else {
                                                                            echo form_input('newratemobile[0]', isset($assignment['newratemobile'][0])?$assignment['newratemobile'][0]:'', 'class="form-control" id="new_newratemobile_0"  ');
                                                                        }?>
                                                                    </td>
                                                                    <td><?php echo form_input('value2[0]', isset($assignment['value2'][0])?$assignment['value2'][0]:'', 'class="form-control" id="new_value2_0"  ');?></td>

                                                                    <td class="text-center">
                                                                        <?php
                                                                        $extemtedterm = (isset($assignment['extemtedterm'][0]) && $assignment['extemtedterm'][0]==1)?true:false;
                                                                        $dc = array('name'=>'extemtedterm[0]','class'=>'form-control','checked'=>$extemtedterm, 'value'=>1);
                                                                        echo form_checkbox($dc);?>
                                                                    </td>

                                                                    <td class="text-center">
                                                                        <?php
                                                                        $subscriptionlock = (isset($assignment['subscriptionlock'][0]) && $assignment['subscriptionlock'][0]==1)?true:false;
                                                                        $dc = array('name'=>'subscriptionlock[0]','class'=>'form-control','checked'=>$subscriptionlock, 'value'=>1);
                                                                        echo form_checkbox($dc);?>
                                                                    </td>

                                                                    <td id="new_newoptionmobile_box_0">
                                                                        <?php if($formula=='A'){ ?>
                                                                            <select name="newoptionmobile[0]" class="form-control newoptionmobile noerror" id="new_newoptionmobile_0" datarow="0" datatype="new">
                                                                                <option value=""><?= lang('page_option_select')?></option>
                                                                                <?php foreach ($mobileoptions as $key => $value): ?>
                                                                                    <option value="<?= $value['optionnr']?>" <?php (isset($assignment['newoptionmobile'][0]) && $assignment['newoptionmobile'][0] == $value['optionnr']) && print 'selected'; ?> data-provider="<?= $value['provider']?>"><?= $value['optiontitle']?></option>
                                                                                <?php endforeach ?>
                                                                            </select>
                                                                        <?php } else {
                                                                            echo form_input('newoptionmobile[0]', isset($assignment['newoptionmobile'][0])?$assignment['newoptionmobile'][0]:'', 'class="form-control noerror" id="new_newoptionmobile_0"  ');
                                                                        }?>

                                                                        <!-- More Option -->
                                                                        <?php
                                                                        if($allowMoreOptionMobile){
                                                                            $data_hidden = array('type'=>'hidden', 'id'=>'count_moreoptionmobile_0', 'value'=>isset($assignment['more_newoptionmobile'][0])?count($assignment['more_newoptionmobile'][0]):1);
                                                                            echo form_input($data_hidden);
                                                                        }
                                                                        ?>
                                                                        <!-- End More Option -->

                                                                    </td>
                                                                    <td id="new_newoptionmobile_box_value_0"><?php echo form_input('value4[0]', isset($assignment['value4'][0])?$assignment['value4'][0]:'', 'class="form-control noerror" id="new_value4_0"  ');?></td>
                                                                    <td><?php echo form_dropdown('hardware[0]', $hardwares, isset($assignment['hardware'][0])?$assignment['hardware'][0]:'', 'class="form-control assignment_hardware noerror" datarow="0" ');?></td>

                                                                    <td class="text-center">
                                                                        <?php
                                                                        $cardstatus = (isset($assignment['cardstatus'][0]) && $assignment['cardstatus'][0]==1)?true:false;
                                                                        $dc = array('name'=>'cardstatus[0]','class'=>'form-control','checked'=>$cardstatus, 'value'=>1);
                                                                        echo form_checkbox($dc);?>
                                                                    </td>

                                                                    <td>
                                                                        <div id="new_form_date_0" class="input-group date form_date">
                                                                            <?php $dd = array('name'=>'endofcontract[0]', 'class'=>'form-control noerror', 'readonly'=>true, 'size'=>16, 'value'=> isset($assignment['endofcontract'][0])?$assignment['endofcontract'][0]:'');
                                                                            echo form_input($dd);?>

                                                                            <span class="input-group-btn">
                                                                                <button class="btn default date-set" type="button">
                                                                                    <i class="fa fa-calendar"></i>
                                                                                </button>
                                                                            </span>
                                                                        </div>

                                                                        <?php
                                                                        $simcard_function_id = isset($assignment['simcard_function_id'][0])?$assignment['simcard_function_id'][0]:'0';
                                                                        $simcard_function_nm = isset($assignment['simcard_function_nm'][0])?$assignment['simcard_function_nm'][0]:'0';
                                                                        $simcard_function_qty = isset($assignment['simcard_function_qty'][0])?$assignment['simcard_function_qty'][0]:'0';
                                                                        ?>
                                                                        <div id="new_simcard_function_0"><input type="hidden" name="simcard_function_id[0]" value="<?php echo $simcard_function_id;?>" /><input type="hidden" name="simcard_function_nm[0]" value="<?php echo $simcard_function_nm;?>" /><input type="hidden" name="simcard_function_qty[0]" value="<?php echo $simcard_function_qty;?>" /></div>
                                                                    </td>

                                                                    <td class="text-center">
                                                                        <?php
                                                                        $finished = (isset($assignment['finished'][0]) && $assignment['finished'][0]==1)?true:false;
                                                                        $dc = array('name'=>'finished[0]','class'=>'form-control','checked'=>$finished, 'value'=>1);
                                                                        echo form_checkbox($dc);?>
                                                                    </td>
                                                                </tr>
                                                                <tr id="row3_new_assignmentproduct_0">
                                                                    <td></td>
                                                                    <td></td>
                                                                    <td></td>
                                                                    <td>
                                                                        <!-- PIN -->
                                                                        <?php
                                                                        if($allowPinPuk){
                                                                            ?>
                                                                            <?php echo lang('page_fl_pin');?>:<br>
                                                                            <?php echo form_input('pin[0]', ( (isset($assignment['pin']) && isset($assignment['pin'][0])) ? $assignment['pin'][0] : '' ), 'class="form-control noerror"');?>
                                                                            <?php
                                                                        }
                                                                        ?>
                                                                    </td>
                                                                    <td>
                                                                        <!-- PUK -->
                                                                        <?php
                                                                        if($allowPinPuk){
                                                                            ?>
                                                                            <?php echo lang('page_fl_puk');?>:<br>
                                                                            <?php echo form_input('puk[0]', ( (isset($assignment['puk']) && isset($assignment['puk'][0])) ? $assignment['puk'][0] : '' ), 'class="form-control noerror"');?>
                                                                            <?php
                                                                        }
                                                                        ?>
                                                                    </td>

                                                                    <td colspan="4">
                                                                        <div id="div3_new_assignmentproduct_0" style="display:none;">
                                                                            <label>
                                                                                <?php
                                                                                $ultracard1 = (isset($assignment['ultracard1'][0]) && $assignment['ultracard1'][0]==1)?true:false;
                                                                                $dc = array('name'=>'ultracard1[0]','class'=>'form-control','checked'=>$ultracard1, 'value'=>1);
                                                                                echo form_checkbox($dc);?>
                                                                                <?php echo lang('page_fl_ultracard1');?>
                                                                            </label>
                                                                            <label>
                                                                                <?php
                                                                                $ultracard2 = (isset($assignment['ultracard2'][0]) && $assignment['ultracard2'][0]==1)?true:false;
                                                                                $dc = array('name'=>'ultracard2[0]','class'=>'form-control','checked'=>$ultracard2, 'value'=>1);
                                                                                echo form_checkbox($dc);?>
                                                                                <?php echo lang('page_fl_ultracard2');?>
                                                                            </label>
                                                                        </div>
                                                                    </td>

                                                                    <td>
                                                                        <?php
                                                                        if($allowMoreOptionMobile){
                                                                            ?>
                                                                            <a href="javascript:void(0);" class="btn btn-sm btn-default btn-editable yellow" onclick="AddMoreOptionMobile('<?php echo lang('page_lb_moreoption');?>',0,'new');"><i class="icon-plus"></i> <?php echo lang('page_lb_moreoption');?></a>
                                                                            <?php
                                                                        }
                                                                        ?>
                                                                    </td>

                                                                    <td colspan="5"></td>

                                                                </tr>
                                                                <!-- END ROW -->

                                                                <?php
                                                                /*if($formula=='A' && $simcard_function_id!="0"){
                                                                    ?>
                                                                    <script>
                                                                    jQuery.ajax({url: '<?php echo base_url('admin/ratesmobile/getoldInputSimcardFunction/0/'.$simcard_function_id.'/'.$simcard_function_nm.'/'.$simcard_function_qty);?>', success: function(result){
                                                                        jQuery('#new_simcard_function_0').html(result);
                                                                    }});
                                                                    </script>
                                                                    <?php
                                                                }*/

                                                                if($formula=='A'){
                                                                    $newratemobile = isset($assignment['newratemobile'][0])?$assignment['newratemobile'][0]:'';
                                                                    ?>
                                                                    <script>
                                                                    jQuery.ajax({url: '<?php echo base_url('admin/ratesmobile/getInputUltraCard/'.$newratemobile.'/');?>', success: function(result){
                                                                        if(result==1){
                                                                            $('#div3_new_assignmentproduct_0').show();
                                                                        }else{
                                                                            $('#div3_new_assignmentproduct_0').hide();
                                                                        }
                                                                    }});
                                                                    </script>
                                                                    <?php
                                                                }

                                                                if(isset($assignment['mobilenr']) && count($assignment['mobilenr'])>0){
                                                                    foreach($assignment['mobilenr'] as $pkey=>$assignmentproduct){
                                                                        if($pkey==0){ continue; }
                                                                        ?>
                                                                        <!-- ROW -->
                                                                        <tr id="row1_new_assignmentproduct_<?php echo $pkey;?>">
                                                                            <td class="text-center">

                                                                                <?php
                                                                                if($pkey==(count($assignment['mobilenr'])-1)){
                                                                                    ?>
                                                                                    <a href="javascript:void(0);" class="btn btn-sm btn-default btn-circle btn-editable green addassignmentproduct" datarow="<?php echo $pkey;?>" datatype="new" datainit="0"><i class="fa fa-plus"></i></a>
                                                                                    <?php
                                                                                }
                                                                                else{
                                                                                    ?>
                                                                                    <a href="javascript:void(0);" class="btn btn-sm btn-default btn-circle btn-editable red" onClick="deleteassignmentproduct('<?php echo $pkey;?>','new')"><i class="fa fa-minus"></i></a>
                                                                                    <?php
                                                                                }
                                                                                ?>

                                                                                <?php
                                                                                $formula = isset($assignment['new_formula_'.$pkey])?$assignment['new_formula_'.$pkey]:'A';
                                                                                $data_hidden = array('type'=>'hidden', 'name'=>'new_formula_'.$pkey, 'value'=>$formula);
                                                                                echo form_input($data_hidden);
                                                                                ?>
                                                                            </td>
                                                                            <td><?php echo form_input('simnr['.$pkey.']', isset($assignment['simnr'][$pkey])?$assignment['simnr'][$pkey]:'', 'class="form-control noerror"  ');?></td>
                                                                            <td><?php echo form_input('mobilenr['.$pkey.']', isset($assignment['mobilenr'][$pkey])?$assignment['mobilenr'][$pkey]:'', 'class="form-control"  ');?></td>
                                                                            <td><?php echo form_input('employee['.$pkey.']', isset($assignment['employee'][$pkey])?$assignment['employee'][$pkey]:'', 'class="form-control noerror"  ');?></td>
                                                                            <td><?php echo form_dropdown('vvlneu['.$pkey.']', $vvlneu, isset($assignment['vvlneu'][$pkey])?$assignment['vvlneu'][$pkey]:'', 'class="form-control vvlneu" datarow="'.$pkey.'" datatype="new" ');?></td>
                                                                            <td id="new_newratemobile_box_<?php echo $pkey;?>">
                                                                                <?php if($formula=='A'){ ?>
                                                                                    <select name="newratemobile[<?= $pkey?>]" class="form-control newratemobile" id="new_newratemobile_<?= $pkey?>" datarow="<?= $pkey?>" datatype="new">
                                                                                        <option value=""><?= lang('page_option_select')?></option>
                                                                                        <?php foreach ($mobilerates as $key => $value): ?>
                                                                                            <option value="<?= $value['ratenr']?>" <?php (isset($assignment['newratemobile'][$pkey]) && $assignment['newratemobile'][$pkey] == $value['ratenr']) && print 'selected'; ?> data-provider="<?= $value['provider']?>"><?= $value['ratetitle']?></option>
                                                                                        <?php endforeach ?>
                                                                                    </select>
                                                                                <?php } else {
                                                                                    echo form_input('newratemobile['.$pkey.']', isset($assignment['newratemobile'][$pkey])?$assignment['newratemobile'][$pkey]:'', 'class="form-control" id="new_newratemobile_'.$pkey.'"  ');
                                                                                }?>
                                                                            </td>
                                                                            <td><?php echo form_input('value2['.$pkey.']', isset($assignment['value2'][$pkey])?$assignment['value2'][$pkey]:'', 'class="form-control"  id="new_value2_'.$pkey.'"  ');?></td>

                                                                            <td class="text-center">
                                                                                <?php
                                                                                $extemtedterm = (isset($assignment['extemtedterm'][$pkey]) && $assignment['extemtedterm'][$pkey]==1)?true:false;
                                                                                $dc = array('name'=>'extemtedterm['.$pkey.']','class'=>'form-control','checked'=>$extemtedterm, 'value'=>1);
                                                                                echo form_checkbox($dc);?>
                                                                            </td>

                                                                            <td class="text-center">
                                                                                <?php
                                                                                $subscriptionlock = (isset($assignment['subscriptionlock'][$pkey]) && $assignment['subscriptionlock'][$pkey]==1)?true:false;
                                                                                $dc = array('name'=>'subscriptionlock['.$pkey.']','class'=>'form-control','checked'=>$subscriptionlock, 'value'=>1);
                                                                                echo form_checkbox($dc);?>
                                                                            </td>

                                                                            <td id="new_newoptionmobile_box_<?php echo $pkey;?>">
                                                                                <?php if($formula=='A'){ ?>
                                                                                    <select name="newoptionmobile[<?= $pkey?>]" class="form-control newoptionmobile noerror" id="new_newoptionmobile_<?= $pkey?>" datarow="<?= $pkey?>" datatype="new">
                                                                                        <option value=""><?= lang('page_option_select')?></option>
                                                                                        <?php foreach ($mobileoptions as $key => $value): ?>
                                                                                            <option value="<?= $value['optionnr']?>" <?php (isset($assignment['newoptionmobile'][$pkey]) && $assignment['newoptionmobile'][$pkey] == $value['optionnr']) && print 'selected'; ?> data-provider="<?= $value['provider']?>"><?= $value['optiontitle']?></option>
                                                                                        <?php endforeach ?>
                                                                                    </select>
                                                                                <?php } else {
                                                                                    echo form_input('newoptionmobile['.$pkey.']', isset($assignment['newoptionmobile'][$pkey])?$assignment['newoptionmobile'][$pkey]:'', 'class="form-control noerror" id="new_newoptionmobile_'.$pkey.'"  ');
                                                                                }?>
                                                                            </td>
                                                                            <td id="new_newoptionmobile_box_value_<?php echo $pkey;?>"><?php echo form_input('value4['.$pkey.']', isset($assignment['value4'][$pkey])?$assignment['value4'][$pkey]:'', 'class="form-control noerror" id="new_value4_'.$pkey.'"  ');?></td>
                                                                            <td><?php echo form_dropdown('hardware['.$pkey.']', $hardwares, isset($assignment['hardware'][$pkey])?$assignment['hardware'][$pkey]:'', 'class="form-control assignment_hardware noerror" datarow="'.$pkey.'" ');?></td>

                                                                            <td class="text-center">
                                                                                <?php
                                                                                $cardstatus = (isset($assignment['cardstatus'][$pkey]) && $assignment['cardstatus'][$pkey]==1)?true:false;
                                                                                $dc = array('name'=>'cardstatus['.$pkey.']','class'=>'form-control','checked'=>$cardstatus, 'value'=>1);
                                                                                echo form_checkbox($dc);?>
                                                                            </td>

                                                                            <td>
                                                                                <div id="new_form_date_<?php echo $pkey;?>" class="input-group date form_date">
                                                                                    <?php $dd = array('name'=>'endofcontract['.$pkey.']', 'class'=>'form-control noerror', 'readonly'=>true, 'size'=>16, 'value'=> isset($assignment['endofcontract'][$pkey])?$assignment['endofcontract'][$pkey]:'');
                                                                                    echo form_input($dd);?>

                                                                                    <span class="input-group-btn">
                                                                                        <button class="btn default date-set" type="button">
                                                                                            <i class="fa fa-calendar"></i>
                                                                                        </button>
                                                                                    </span>
                                                                                </div>

                                                                                <?php
                                                                                $simcard_function_id = isset($assignment['simcard_function_id'][$pkey])?$assignment['simcard_function_id'][$pkey]:'0';
                                                                                $simcard_function_nm = isset($assignment['simcard_function_nm'][$pkey])?$assignment['simcard_function_nm'][$pkey]:'0';
                                                                                $simcard_function_qty = isset($assignment['simcard_function_qty'][$pkey])?$assignment['simcard_function_qty'][$pkey]:'0';
                                                                                ?>
                                                                                <div id="new_simcard_function_<?php echo $pkey;?>"><input type="hidden" name="simcard_function_id[<?php echo $pkey;?>]" value="<?php echo $simcard_function_id;?>" /><input type="hidden" name="simcard_function_nm[<?php echo $pkey;?>]" value="<?php echo $simcard_function_nm;?>" /><input type="hidden" name="simcard_function_qty[<?php echo $pkey;?>]" value="<?php echo $simcard_function_qty;?>" /></div>
                                                                            </td>

                                                                            <td class="text-center">
                                                                                <?php
                                                                                $finished = (isset($assignment['finished'][$pkey]) && $assignment['finished'][$pkey]==1)?true:false;
                                                                                $dc = array('name'=>'finished['.$pkey.']','class'=>'form-control','checked'=>$finished, 'value'=>1);
                                                                                echo form_checkbox($dc);?>
                                                                            </td>

                                                                        </tr>
                                                                        <tr id="row3_new_assignmentproduct_<?php echo $pkey;?>">
                                                                            <td></td>
                                                                            <td></td>
                                                                            <td></td>
                                                                            <td>
                                                                                <!-- PIN -->
                                                                                <?php
                                                                                if($allowPinPuk){
                                                                                    ?>
                                                                                    <?php echo lang('page_fl_pin');?>:<br>
                                                                                    <?php echo form_input('pin['.$pkey.']', $assignment['pin'][$pkey], 'class="form-control noerror"');?>
                                                                                    <?php
                                                                                }
                                                                                ?>
                                                                            </td>
                                                                            <td>
                                                                                <!-- PUK -->
                                                                                <?php
                                                                                if($allowPinPuk){
                                                                                    ?>
                                                                                    <?php echo lang('page_fl_puk');?>:<br>
                                                                                    <?php echo form_input('puk['.$pkey.']', $assignment['puk'][$pkey], 'class="form-control noerror"');?>
                                                                                    <?php
                                                                                }
                                                                                ?>
                                                                            </td>

                                                                            <td colspan="10">
                                                                                <div id="div3_new_assignmentproduct_<?php echo $pkey;?>" style="display:none;">
                                                                                    <label>
                                                                                        <?php
                                                                                        $ultracard1 = (isset($assignment['ultracard1'][$pkey]) && $assignment['ultracard1'][$pkey]==1)?true:false;
                                                                                        $dc = array('name'=>'ultracard1['.$pkey.']','class'=>'form-control','checked'=>$ultracard1, 'value'=>1);
                                                                                        echo form_checkbox($dc);?>
                                                                                        <?php echo lang('page_fl_ultracard1');?>
                                                                                    </label>
                                                                                    <label>
                                                                                        <?php
                                                                                        $ultracard2 = (isset($assignment['ultracard2'][$pkey]) && $assignment['ultracard2'][$pkey]==1)?true:false;
                                                                                        $dc = array('name'=>'ultracard2['.$pkey.']','class'=>'form-control','checked'=>$ultracard2, 'value'=>1);
                                                                                        echo form_checkbox($dc);?>
                                                                                        <?php echo lang('page_fl_ultracard2');?>
                                                                                    </label>
                                                                                </div>
                                                                            </td>
                                                                        </tr>
                                                                        <!-- END ROW -->

                                                                        <?php
                                                                        /*if($formula=='A' && $simcard_function_id!="0"){
                                                                            ?>
                                                                            <script>
                                                                            jQuery.ajax({url: '<?php echo base_url('admin/ratesmobile/getoldInputSimcardFunction/'.$pkey.'/'.$simcard_function_id.'/'.$simcard_function_nm.'/'.$simcard_function_qty);?>', success: function(result){
                                                                                jQuery('#new_simcard_function_<?php echo $pkey);?>').html(result);
                                                                            }});
                                                                            </script>
                                                                            <?php
                                                                        }*/

                                                                        if($formula=='A'){
                                                                            $newratemobile = isset($assignment['newratemobile'][$pkey])?$assignment['newratemobile'][$pkey]:'';
                                                                            ?>
                                                                            <script>
                                                                            jQuery.ajax({url: '<?php echo base_url('admin/ratesmobile/getInputUltraCard/'.$newratemobile.'/');?>', success: function(result){
                                                                                if(result==1){
                                                                                    $('#div3_new_assignmentproduct_<?php echo $pkey;?>').show();
                                                                                }else{
                                                                                    $('#div3_new_assignmentproduct_<?php echo $pkey;?>').hide();
                                                                                }
                                                                            }});
                                                                            </script>
                                                                            <?php
                                                                        }
                                                                    }
                                                                }
                                                            }
                                                            ?>
                                                            </tbody>
                                                        </table>
                                                    </div>
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
                                                    <a href="<?php echo base_url('admin/assignments')?>"><button type="button" class="btn default"><?php echo lang('cancel');?></button></a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- END SAMPLE FORM PORTLET-->
                                </div>

                                <?php echo form_close();?>
                                <?php echo form_open(site_url('admin/assignments/download_product_csv'), array('id' => 'form_import_assignment'));?>
                                <?php echo form_close();?>
                            </div>

                            <div class="tab-pane" id="tab_legitimation" style="display:<?php echo $tab_legitimation;?>">

                                <?php
                                if(isset($assignment['assignmentnr']) && $assignment['assignmentnr']>0){
                                    $this->load->view('admin/assignments/tab-legitimation', array('assignment'=>$assignment));
                                }
                                ?>

                            </div>

                            <div class="tab-pane" id="tab_document" style="display:<?php echo $tab_document;?>">

                                <?php
                                if(isset($assignment['assignmentnr']) && $assignment['assignmentnr']>0){
                                    $this->load->view('admin/assignments/tab-document', array('assignment'=>$assignment,'categories'=>$categories));
                                }
                                ?>

                            </div>

                            <div class="tab-pane" id="tab_reminder" style="display:<?php echo $tab_reminder;?>">

                                <?php
                                if(isset($assignment['assignmentnr']) && $assignment['assignmentnr']>0){
                                    $this->load->view('admin/assignments/tab-reminder', array('assignment'=>$assignment));
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
<?php
//print_r($assignment); die();
?>
<script>
    function PreviewImage()
     {
        var oFReader = new FileReader();
        oFReader.readAsDataURL(document.getElementById("provider_logo").files[0]);

        oFReader.onload = function (oFREvent) {
            document.getElementById("logoPreview").src = oFREvent.target.result;
        };

        var yourImg = document.getElementById('logoPreview');

        if(yourImg && yourImg.style) {
            yourImg.style.height = '70px';
            yourImg.style.width = '120px';
        }
    }

    $('#submit_form_import_assignment').click(function(event) {
        $('#form_import_assignment').submit();
    });

    $('#submit_import_assignment').click(function(event) {
        var file_csv = $('#file_csv').val();
        $('#file_csv').removeAttr('style');
        if (!file_csv) { $('#file_csv').css('border-color', '#e73d4a'); return false; };

        var myFormData = new FormData();
        myFormData.append('file_csv', $('#file_csv').prop('files')[0]);

        $.ajax({
            url: "<?php echo base_url('admin/assignments/import_product_csv');?>",
            type: 'POST',
            processData: false, // important
            contentType: false, // important
            dataType : 'json',
            data: myFormData,
            success: function(response){
                if (!response.status) { return false; }
                $('#assignmentproduct_inputbox').find('tr, script').remove();
                $('#assignmentproduct_inputbox').append(response.html_data);
                addassignmentproduct();
                /*var idx = ($('tr[id*="_assignmentproduct_"]').length/2 > 1 || ($('tr[id*="_old_assignmentproduct_"]').length/2)) ? ($('tr[id*="_assignmentproduct_"]').length/2) : 0;
                $.each(response.add_data, function(index, val) {
                    if (index!=0 || idx!=0) {
                        $('.addassignmentproduct')[0].click();
                        idx++;
                        if (idx==1) { idx=2; }
                    }
                    $.each(val, function(s_index, s_val) {
                        var temp = $('#row1_new_assignmentproduct_'+idx+' [name="'+s_index+'['+idx+']"]');
                        if (temp.attr('type') == 'checkbox') {
                            temp.attr('checked', (s_val==1));
                            if (s_val==1) {
                                temp.closest('span').addClass('checked');
                            }

                        } else {
                            temp.val(s_val);
                            $('#row3_new_assignmentproduct_'+idx+' [name="'+s_index+'['+idx+']"]').val(s_val);
                        }
                    });
                });*/
            }
        });
    });


    var form_id = 'form_assignment';
    var func_FormValidation = 'FormCustomValidation';

    function after_func_FormValidation(form1, error1, success1){
        form1.validate({
            errorElement: 'span', //default input error message container
            errorClass: 'help-block help-block-error', // default input error message class
            focusInvalid: false, // do not focus the last invalid input
            ignore: "",  // validate all fields including form hidden input

            rules: {
                company: {
                    minlength: 2,
                    required: true
                },
                assignmentdate: {
                    required: true
                },
                assignmentstatus: {
                    required: true
                },
                customer: {
                    required: true
                },
                responsible: {
                    required: true
                },
                /*recommend: {
                    required: true
                },*/
                providercompanynr: {
                    required: true
                },
                newdiscountlevel: {
                    required: true
                },
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

                var validRow = true;
                var validate_arr = ['mobilenr', 'vvlneu', 'newratemobile', 'value2'];
                var oldRowCount = jQuery('[id^="row1_old_assignmentproduct"]').length;
                for (var i = 0; i < oldRowCount; i++) {
                    var old_row1 = '#assignmentproduct_inputbox #row1_old_assignmentproduct_'+i+' :input[type!="radio"][type!="checkbox"][type!="hidden"]:not(.noerror)[name]';
                    var old_row3 = '#assignmentproduct_inputbox #row3_old_assignmentproduct_'+i+' :input[type!="radio"][type!="checkbox"][type!="hidden"]:not(.noerror)[name]';
                    var temp = jQuery(old_row1+', '+old_row3);
                    temp.each(function() {
                        var test = jQuery(this).attr('name').split('[')[0];
                        if ($.inArray(test, validate_arr) !== -1) {
                            $(this).removeClass('field_error');
                            if ($(this).val() == "" && $(this).val().length < 1) {
                                $(this).addClass('field_error');
                                validRow = false;
                            }
                        }
                    });
                    if (!validRow) { return false; }
                }

                if (oldRowCount != 0) {
                    oldRowCount++;
                }
                for (var i = oldRowCount; i < (oldRowCount+jQuery('[id^="row1_new_assignmentproduct"]').length); i++) {
                    // if ( i == 1 ) { continue; }

                    var new_row1 = '#assignmentproduct_inputbox #row1_new_assignmentproduct_'+i+' :input[type!="radio"][type!="checkbox"][type!="hidden"]:not(.noerror)[name]';
                    var new_row3 = '#assignmentproduct_inputbox #row3_new_assignmentproduct_'+i+' :input[type!="radio"][type!="checkbox"][type!="hidden"]:not(.noerror)[name]';
                    var temp = jQuery(new_row1+', '+new_row3);

                    if ( !temp.length ) { continue; }

                    var personal_valid = {};
                    temp.each(function() {
                        var test = jQuery(this).attr('name').split('[')[0];
                        if ($.inArray(test, validate_arr) !== -1) {
                            personal_valid[test] = !($(this).val() == "" && $(this).val().length < 1);
                            $(this).removeClass('field_error');
                            if ($(this).val() == "" && $(this).val().length < 1) {
                                $(this).addClass('field_error');
                            }
                        }
                    });
                    validRow = (Object.values(personal_valid).filter((x,i) => { return x; }).length == validate_arr.length);
                    if (validRow) {
                        jQuery(new_row1+'.field_error, '+new_row3+'.field_error').removeClass('field_error');
                    }
                    if (!validRow) { return false; }
                }

                if (validRow) {
                    App.scrollTo(error1, -200);
                    return true;
                } else {
                    return false;
                }
                /*if(extraFieldsValidate_form()){
                    App.scrollTo(error1, -200);
                    return true;
                }else{
                    return false;
                }*/
            }
        });
    }
</script>

<?php $this->load->view('admin/footer.php'); ?>

<script>
    if (jQuery('#show_old_products').length) {
        jQuery.ajax({url: '<?php echo base_url('admin/assignments/getAssignmentproduct/'.$assignment['assignmentnr']);?>',
            // dataType: 'html',
            success: function(result){
            $('#assignmentproduct_inputbox').append(result);
            addassignmentproduct();
        }});
    }

function addassignmentproduct(){
    jQuery('.addassignmentproduct').click( function(){
        var datainit = $(this).attr('datainit');
        if(datainit==1){ return false; }

        //Swap Class
        var sdatarow = $(this).attr('datarow');
        var sdatatype = $(this).attr('datatype');
        $(this).removeClass('addassignmentproduct');
        $(this).removeClass('green');
        $(this).addClass('red');
        $(this).html('<i class="fa fa-minus"></i>');
        $(this).attr('onClick',"deleteassignmentproduct('"+sdatarow+"','"+sdatatype+"')");
        $(this).attr('datainit',1);

        var rownum = parseInt(jQuery('#count_assignmentproduct').val()) + 1;
        var inputhtml = '<tr id="row1_new_assignmentproduct_'+rownum+'">';
        inputhtml = inputhtml + '<td class="text-center"><a href="javascript:void(0);" class="btn btn-sm btn-default btn-circle btn-editable green addassignmentproduct" datarow="'+rownum+'" datatype="new" datainit="0"><i class="fa fa-plus"></i></a> <input type="hidden" name="new_formula_'+rownum+'" value="A" /></td>';
        inputhtml = inputhtml + '<td><input type="text" name="simnr['+rownum+']" value=""  class="form-control noerror" /></td>';
        inputhtml = inputhtml + '<td><input type="text" name="mobilenr['+rownum+']" value=""  class="form-control" /></td>';
        inputhtml = inputhtml + '<td><input type="text" name="employee['+rownum+']" value=""  class="form-control noerror" /></td>';
        inputhtml = inputhtml + '<td><select name="vvlneu['+rownum+']" class="form-control vvlneu" onchange="javascript:datepicker_vvlneu(jQuery(this).find(\'option:selected\').text(), '+rownum+' , \'new\' );" datarow="'+rownum+'" datatype="new">';
        <?php
        foreach($vvlneu as $k=>$v){
            ?>
            inputhtml = inputhtml + "<option value='<?php echo $k;?>'><?php echo $v;?></option>";
            <?php
        }
        ?>
        inputhtml = inputhtml + '</select></td>';
        inputhtml = inputhtml + '<td id="new_newratemobile_box_'+rownum+'"><select name="newratemobile['+rownum+']" class="form-control newratemobile" id="new_newratemobile_'+rownum+'" datarow="'+rownum+'" datatype="new" >';
        inputhtml += "<option value=''><?= lang("page_lb_select_mobile_rate");?></option>";
        <?php foreach($mobilerates as $k=>$v){ ?>
            inputhtml = inputhtml + "<option value='<?php echo $v['ratenr'];?>' data-provider='<?php echo $v['provider'];?>'><?php echo $v['ratetitle'];?></option>";
        <?php } ?>
        inputhtml = inputhtml + '</select></td>';
        inputhtml = inputhtml + '<td><input type="text" name="value2['+rownum+']" value=""  class="form-control" id="new_value2_'+rownum+'" /></td>';
        inputhtml = inputhtml + '<td class="text-center"><input type="checkbox" name="extemtedterm['+rownum+']" value="1" class="form-control extemtedterm"></td>';
        inputhtml = inputhtml + '<td class="text-center"><input type="checkbox" name="subscriptionlock['+rownum+']" value="1" class="form-control subscriptionlock"></td>';
        inputhtml = inputhtml + '<td id="new_newoptionmobile_box_'+rownum+'"><select name="newoptionmobile['+rownum+']" class="form-control newoptionmobile noerror" id="new_newoptionmobile_'+rownum+'" datarow="'+rownum+'" datatype="new" >';
        inputhtml += "<option value=''><?= lang("page_lb_select_mobile_rate");?></option>";
        <?php
        foreach($mobileoptions as $k=>$v){ ?>
            inputhtml = inputhtml + "<option value='<?php echo $v['optionnr'];?>' data-provider='<?php echo $v['provider'];?>'><?php echo $v['optiontitle'];?></option>";
        <?php } ?>
        inputhtml = inputhtml + '</select>';

        inputhtml = inputhtml + '</td>';
        inputhtml = inputhtml + '<td id="new_newoptionmobile_box_value_'+rownum+'"><input type="text" name="value4['+rownum+']" value=""  class="form-control noerror" id="new_value4_'+rownum+'" /></td>';
        inputhtml = inputhtml + '<td><select name="hardware['+rownum+']" class="form-control assignment_hardware noerror" datarow="'+rownum+'">';
        <?php
        foreach($hardwares as $k=>$v){
            ?>
            inputhtml = inputhtml + "<option value='<?php echo $k;?>'><?php echo $v;?></option>";
            <?php
        }
        ?>
        inputhtml = inputhtml + '</select></td>';
        inputhtml = inputhtml + '<td class="text-center"><input type="checkbox" name="cardstatus['+rownum+']" value="1" class="form-control cardstatus"></td>';
        inputhtml = inputhtml + '<td><div id="new_form_date_'+rownum+'" class="input-group date form_date"><input type="text" name="endofcontract['+rownum+']" value="" class="form-control noerror" readonly="1" size="16" >';
        inputhtml = inputhtml + '<span class="input-group-btn"><button class="btn default date-set" type="button"><i class="fa fa-calendar"></i></button></span></div><div id="new_simcard_function_'+rownum+'"><input type="hidden" name="simcard_function_id['+rownum+']" value="0" /><input type="hidden" name="simcard_function_nm['+rownum+']" value="0" /><input type="hidden" name="simcard_function_qty['+rownum+']" value="0" /></div></td>';
        inputhtml = inputhtml + '<td class="text-center"><input type="checkbox" name="finished['+rownum+']" value="1" class="form-control finished"></td>';
        inputhtml = inputhtml + '</tr>';

        inputhtml = inputhtml + '<tr id="row3_new_assignmentproduct_'+rownum+'">';
            inputhtml = inputhtml + '<td></td>';
            inputhtml = inputhtml + '<td></td>';
            inputhtml = inputhtml + '<td></td>';
            inputhtml = inputhtml + '<td><!-- PIN -->';
                <?php
                if($allowPinPuk){
                    ?>
                    inputhtml = inputhtml + '<?php echo lang('page_fl_pin');?>:<br><input type="text" name="pin['+rownum+']" value="" class="form-control noerror" />';
                    <?php
                }
                ?>
            inputhtml = inputhtml + '</td>';

            inputhtml = inputhtml + '<td><!-- PUK -->';
                <?php
                if($allowPinPuk){
                    ?>
                    inputhtml = inputhtml + '<?php echo lang('page_fl_puk');?>:<br><input type="text" name="puk['+rownum+']" value="" class="form-control noerror" />';
                    <?php
                }
                ?>
            inputhtml = inputhtml + '</td>';

            inputhtml = inputhtml + '<td colspan="4">';
                inputhtml = inputhtml + '<div id="div3_new_assignmentproduct_'+rownum+'" style="display:none;"><label><input type="checkbox" name="ultracard1['+rownum+']" value="1" class="form-control ultracard"> <?php echo lang('page_fl_ultracard1');?></label> ';
                inputhtml = inputhtml + '<label><input type="checkbox" name="ultracard2['+rownum+']" value="1" class="form-control ultracard"> <?php echo lang('page_fl_ultracard2');?></label></div>';
            inputhtml = inputhtml + '</td>';

            inputhtml = inputhtml + '<td>';
            <?php
            if($allowMoreOptionMobile){
                ?>
                inputhtml = inputhtml + '<a href="javascript:void(0);" class="btn btn-sm btn-default btn-editable yellow" onclick="AddMoreOptionMobile(\'<?php echo lang('page_lb_moreoption');?>\','+rownum+',\'new\');"><i class="icon-plus"></i> <?php echo lang('page_lb_moreoption');?></a>';
                inputhtml = inputhtml + '<input type="hidden" id="count_moreoptionmobile_'+rownum+'" value="1">';
                <?php
            }
            ?>
            inputhtml = inputhtml + '</td>';

            inputhtml = inputhtml + '<td colspan="5"></td>';

        inputhtml = inputhtml + '</tr>';

        //jQuery('#assignmentproduct_inputbox').prepend(inputhtml);
        jQuery('#assignmentproduct_inputbox').append(inputhtml);
        jQuery('#count_assignmentproduct').val(rownum);
        jQuery('[data-toggle="tooltip"]').tooltip();
        jQuery('.extemtedterm').uniform();
        jQuery('.subscriptionlock').uniform();
        jQuery('.cardstatus').uniform();
        jQuery('.finished').uniform();
        jQuery('.ultracard').uniform();

        changenewRateMobile();
        changenewOptionMobile();
        changeFormula();
        changeHardware();
        datapicker();
        addassignmentproduct();
        datepicker_vvlneu('',rownum,'new');
        getProviderOptions();

        //App.scrollTo(jQuery('#assignmentproduct_inputbox'), 1);
        App.scrollTo(jQuery('#assignmentproduct_inputbox'), 2000);
    });
}
addassignmentproduct();


jQuery('#form_assignment').on('submit', function(e) {
    //extraFieldsValidate();
});

jQuery('#newdiscountlevel').change( function(){
    jQuery('#assignmentproduct_inputbox').find('select').each(function() {
        if(jQuery(this).attr('class')=='form-control newratemobile'){
            var rown = jQuery(this).attr('datarow');
            var rowt = jQuery(this).attr('datatype');
            changenewRateMobileDiscountLevel(rown,rowt);
        }
    });
});

jQuery('.vvlneu').change( function(){
    var rown = jQuery(this).attr('datarow');
    var rowt = jQuery(this).attr('datatype');
    var selectedvalue = jQuery(this).find('option:selected').text();
    datepicker_vvlneu(selectedvalue, rown, rowt);
});

function changeFormula(){
    jQuery('.formula').change( function(){
        var rown = jQuery(this).attr('datarow');
        var rowt = jQuery(this).attr('datatype');
        var formula = jQuery('input[name='+rowt+'_formula_'+rown+']').val();

        if(formula=='A'){

            //New Rate Mobile
            selecthtml ='<select name="newratemobile['+rown+']" class="form-control newratemobile" id="'+rowt+'_newratemobile_'+rown+'" datarow="'+rown+'" datatype="'+rowt+'" >';
            selecthtml += "<option value=''><?= lang("page_lb_select_mobile_rate");?></option>";
            <?php foreach($mobilerates as $k=>$v){ ?>
                selecthtml = selecthtml + "<option value='<?php echo $v['ratenr'];?>' data-provider='<?php echo $v['provider'];?>'><?php echo $v['ratetitle'];?></option>";
            <?php } ?>
            selecthtml = selecthtml + '</select>';
            jQuery('#'+rowt+'_newratemobile_box_'+rown).html(selecthtml);

            //New Option Mobile
            selecthtml ='<select name="newoptionmobile['+rown+']" class="form-control newoptionmobile noerror" id="'+rowt+'_newoptionmobile_'+rown+'" datarow="'+rown+'" datatype="'+rowt+'" >';
            selecthtml += "<option value=''><?= lang("page_lb_select_mobile_rate");?></option>";
            <?php foreach($mobileoptions as $k=>$v){ ?>
                selecthtml = selecthtml + "<option value='<?php echo $v['optionnr'];?>' data-provider='<?php echo $v['provider'];?>'><?php echo $v['optiontitle'];?></option>";
            <?php } ?>
            selecthtml = selecthtml + '</select>';
            jQuery('#'+rowt+'_newoptionmobile_box_'+rown).html(selecthtml);

            //Initialize
            changenewRateMobile();
            changenewOptionMobile();

        }else{
            //New Rate Mobile
            jQuery('#'+rowt+'_newratemobile_box_'+rown).html('<input type="text" name="newratemobile['+rown+']" class="form-control" id="'+rowt+'_newratemobile_'+rown+'">');
            jQuery('#'+rowt+'_value2_'+rown).val('');

            //New Option Mobile
            jQuery('#'+rowt+'_newoptionmobile_box_'+rown).html('<input type="text" name="newoptionmobile['+rown+']" class="form-control noerror" id="'+rowt+'_newoptionmobile_'+rown+'">');
            jQuery('#'+rowt+'_value4_'+rown).val('');

            //Simcard Function
            jQuery('#'+rowt+'_simcard_function_'+rown).html('<input type="hidden" name="simcard_function_id['+rown+']" value="0" /><input type="hidden" name="simcard_function_nm['+rown+']" value="0" /><input type="hidden" name="simcard_function_qty['+rown+']" value="0" />');
        }
    });
}

function changenewRateMobile(){
    jQuery('.newratemobile').change( function(){
        var rown = jQuery(this).attr('datarow');
        var rowt = jQuery(this).attr('datatype');

        var discountlevel = jQuery('#newdiscountlevel').val();
        if(discountlevel==""){ discountlevel="none"; }

        var formula = jQuery('input[name='+rowt+'_formula_'+rown+']').val();
        var id = jQuery('#'+rowt+'_newratemobile_'+rown).val();

        if(formula=='A'){
            if(id==""){ id="none"; }

            jQuery.ajax({url: '<?php echo base_url('admin/assignments/getMobileRateValue/');?>'+id+'/'+discountlevel+'/'+formula, success: function(result){
                var temp = result.split('[=]');
                jQuery('#'+rowt+'_value2_'+rown).val(temp[0]);

                if($.trim(temp[1])==1){
                    $('#div3_'+rowt+'_assignmentproduct_'+rown).show();
                }else{
                    $('#div3_'+rowt+'_assignmentproduct_'+rown).hide();
                }
            }});

            /*jQuery.ajax({url: '<?php echo base_url('admin/ratesmobile/getnewInputSimcardFunction/');?>'+rown+'/'+id, success: function(result){
                jQuery('#'+rowt+'_simcard_function_'+rown).html(result);
            }});*/
        }
        else{
            jQuery('#div3_'+rowt+'_assignmentproduct_'+rown).hide();
        }
    });
}

function changenewOptionMobile(){
    jQuery('.newoptionmobile').change( function(){
        var rown = jQuery(this).attr('datarow');
        var rowt = jQuery(this).attr('datatype');
        var id = jQuery('#'+rowt+'_newoptionmobile_'+rown).val();
        var formula = jQuery('input[name='+rowt+'_formula_'+rown+']').val();

        if(formula=='A'){
            jQuery.ajax({url: '<?php echo base_url('admin/assignments/getMobileOptionValue/');?>'+id, success: function(result){
                jQuery('#'+rowt+'_value4_'+rown).val(result);
            }});
        }
    });
}

function changenewRateMobileDiscountLevel(rown,rowt){
    var discountlevel = jQuery('#newdiscountlevel').val();
    if(discountlevel==""){ discountlevel="none"; }

    var formula = jQuery('input[name='+rowt+'_formula_'+rown+']').val();
    var id = jQuery('#'+rowt+'_newratemobile_'+rown).val();

    if(formula=='A'){
        if(id==""){ id="none"; }
        jQuery.ajax({url: '<?php echo base_url('admin/assignments/getMobileRateValue/');?>'+id+'/'+discountlevel+'/'+formula, success: function(result){
            var temp = result.split('[=]');
            jQuery('#'+rowt+'_value2_'+rown).val(temp[0]);
        }});
    }
}

function deleteassignmentproduct(dataid,datatype,parentid){

    if (typeof parentid == 'undefined') {
        parentid = '';
    }

    var rownum = jQuery('#count_assignmentproduct').val();
    if(datatype=='old'){
        //Delete record from db by ajax
        deleteConfirmation('<?php echo base_url('admin/assignments/deleteAssignmentProduct/');?>',dataid,'<?php echo lang('page_lb_delete_assignmentproduct')?>','<?php echo lang('page_lb_delete_assignmentproduct_info')?>','true',parentid);
    }
    else{
        jQuery('#row1_new_assignmentproduct_'+dataid).remove();
        jQuery('#row3_new_assignmentproduct_'+dataid).remove();
    }
    jQuery('#count_assignmentproduct').val(rownum);
}

function changeHardware(){
    jQuery('.assignment_hardware').change( function(){
         var current_val = $(this).val();
         var current_rown = $(this).attr('datarow');

         var selected_ok = 1;
         jQuery('#assignmentproduct_inputbox select').each(function() {
             //if(jQuery(this).attr('class')=='form-control assignment_hardware'){
            if(jQuery(this).hasClass("assignment_hardware")){
                 if($(this).val() != "" && $(this).val().length > 0) {
                     if(current_val==$(this).val() && current_rown!=$(this).attr('datarow')){
                         selected_ok = 0;
                     }
                 }
             }
        });

        if(selected_ok==0){
            /*alert('<?php echo lang('page_lb_already_chosen')?>');
            $(this).val('');*/
        }
    });
}

getProviderOptions(true);
jQuery('#provider').change(function(event) {
    getProviderOptions();
});

function getProviderOptions(onLoad) {
    var allDropdown = jQuery('#newdiscountlevel, .newratemobile, .newoptionmobile, .more_newoptionmobile');
    if(!(onLoad || false)) {
        // allDropdown.val('');
    }
    allDropdown.find('option').removeClass('hide');
    var provider = jQuery('#provider').val() || '';
    if (provider != '') {
        allDropdown.find('option:not(:first)').addClass('hide').end().find('option[data-provider="'+provider+'"]').removeClass('hide');
    }
}

changenewRateMobile();
changenewOptionMobile();
changeFormula();
changeHardware();
</script>

<?php $this->load->view('admin/assignments/assignmentjs',array('assignment'=>isset($assignment) ? $assignment:'', 'remindersubjects'=>$remindersubjects));?>

<script>
<?php
//Initilize Date for Condition VVL/Neu
if(isset($assignmentproducts) && count($assignmentproducts)>0){
    foreach($assignmentproducts as $pkey=>$assignmentproduct){
        ?>
        datepicker_vvlneu('<?php echo $assignmentproduct['vvlneuname']?>', '<?php echo $pkey;?>' ,'old');
        <?php
    }
}
?>
</script>
