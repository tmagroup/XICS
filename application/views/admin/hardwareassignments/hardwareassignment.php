<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<?php $this->load->view('admin/header.php'); ?>

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
                                <a href="<?php echo base_url('admin/hardwareassignments');?>"><?php echo lang('page_hardwareassignments');?></a>
                                <i class="fa fa-circle"></i>
                            </li>

                            <li>
                                <span>
                                    <?php
                                    if(isset($hardwareassignment['hardwareassignmentnr'])){
                                        echo lang('page_edit_hardwareassignment');
                                    }
                                    else
                                    {
                                        echo lang('page_create_hardwareassignment');
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
                        if(isset($hardwareassignment['hardwareassignmentnr'])){
                            ?>
                            <i class="fa fa-pencil"></i>
                            <?php
                            echo lang('page_edit_hardwareassignment');
                        }
                        else
                        {
                            ?>
                            <i class="fa fa-plus"></i>
                            <?php
                            echo lang('page_create_hardwareassignment');
                        }
                        ?>

                    </h3>
                    <!-- END PAGE TITLE-->
                    <!-- END PAGE HEADER-->

                    <?php
                    //Only Editable
                    $tab_document = '';
                    $tab_reminder = '';
                    if(empty($hardwareassignment['hardwareassignmentnr'])){
                       $tab_document = 'none';
                       $tab_reminder = 'none';
                    }
                    ?>


                    <?php
                    $field_company_disabled = ' disabled';
                    $field_customer_disabled = ' disabled';
                    $field_responsible_disabled = ' disabled';
                    $field_hardwareassignmentstatus_disabled = '';
                    $field_mobilenr_disabled = ' disabled';
                    $field_simnr_disabled = ' disabled';
                    $field_newratemobile_disabled = ' disabled';
                    $field_hardware_disabled = ' disabled';
                    $field_stockhardware_disabled = '';
                    $field_seriesnr_disabled = ' readonly';
                    $field_shippingnr_disabled = '';
                    $field_hardwarevalue_disabled = ' disabled';

                    if($GLOBALS['current_user']->userrole==1){
                        $field_company_disabled = ' disabled';
                        $field_customer_disabled = ' disabled';
                        $field_responsible_disabled = ' disabled';
                        $field_hardwareassignmentstatus_disabled = '';
                        $field_mobilenr_disabled = ' disabled';
                        $field_simnr_disabled = ' disabled';
                        $field_newratemobile_disabled = ' disabled';
                        $field_hardware_disabled = ' disabled';
                        $field_stockhardware_disabled = '';
                        $field_seriesnr_disabled = ' readonly';
                        $field_shippingnr_disabled = '';
                        $field_hardwarevalue_disabled = ' disabled';
                    }
                    ?>

                    <div class="row">

                        <ul class="nav nav-tabs">
                            <li class="active">
                                <a href="#tab_profile" data-toggle="tab"><?php echo lang('page_lb_profile');?></a>
                            </li>
                            <li>
                                <a href="#tab_document" data-toggle="tab"><?php echo lang('page_lb_document');?></a>
                            </li>
                            <li>
                                <a href="#tab_reminder" data-toggle="tab"><?php echo lang('page_lb_reminder');?></a>
                            </li>
                        </ul>


                        <div class="tab-content">

                            <div class="tab-pane active" id="tab_profile">
                                <?php echo form_open($this->uri->uri_string(), array('enctype' => "multipart/form-data", 'id' => 'form_hardwareassignment') );?>
                                <div class="col-md-6">

                                    <!-- BEGIN SAMPLE FORM PORTLET-->
                                    <div class="portlet light bordered">
                                        <div class="portlet-body form">

                                            <div class="form-body">

                                                <div class="form-group">
                                                    <label><?php echo lang('page_fl_company');?> <span class="required"> * </span></label>
                                                    <?php echo form_input('company', isset($hardwareassignment['company'])?$hardwareassignment['company']:'', 'class="form-control" '.$field_company_disabled);?>
                                                </div>

                                                <div class="form-group">
                                                    <label><?php echo lang('page_fl_hardwareassignmentstatus');?> <span class="required"> * </span></label>
                                                    <?php echo form_dropdown('hardwareassignmentstatus', $hardwareassignmentstatus, isset($hardwareassignment['hardwareassignmentstatus'])?$hardwareassignment['hardwareassignmentstatus']:'', 'class="form-control" id="hardwareassignmentstatus" '.$field_hardwareassignmentstatus_disabled);?>
                                                </div>

                                                <div class="form-group">
                                                    <label><?php echo lang('page_fl_provider');?> </label>
                                                    <?php echo form_dropdown('provider', provider_values(), isset($hardwareassignment['provider'])?$hardwareassignment['provider']:'', 'class="form-control"');?>
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
                                                    <label><?php echo lang('page_fl_customer');?> <span class="required"> * </span></label>
                                                    <?php echo form_dropdown('customer', $customers, isset($hardwareassignment['customer'])?$hardwareassignment['customer']:'', 'class="form-control" id="customer" '.$field_customer_disabled);?>
                                                </div>

                                                <div class="form-group">
                                                    <label><?php echo lang('page_fl_responsible');?> <span class="required"> * </span></label>
                                                    <?php echo form_dropdown('responsible', array(''=>lang('page_option_select')) , '', 'class="form-control" id="responsible" '.$field_responsible_disabled);?>
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

                                            <div class="form-body">

                                                <div class="form-group">
                                                    <div class="table-responsive no-dt">
                                                        <table class="table table-striped table-bordered table-hover dt-responsive" width="100%">
                                                            <thead>
                                                                <tr role="row" class="heading">
                                                                    <th class="text-nowrap" width="10%"><?php echo lang('page_fl_mobilenr');?></th>
                                                                    <th class="text-nowrap" width="10%"><?php echo lang('page_fl_simnr');?></th>
                                                                    <th class="text-nowrap" width="20%"><?php echo lang('page_fl_ratetitle');?></th>
                                                                    <th class="text-nowrap" width="20%"><?php echo lang('page_fl_choosenhardware');?></th>
                                                                    <th class="text-nowrap" width="20%"><?php echo lang('page_fl_stockhardware');?></th>
                                                                    <th class="text-nowrap" width="10%"><?php echo lang('page_fl_seriesnr');?></th>
                                                                    <th class="text-nowrap" width="10%"><?php echo lang('page_fl_shippingnr');?></th>
                                                                    <th class="text-nowrap" width="10%"><?php echo lang('page_fl_hardwarevalue');?></th>
                                                                </tr>
                                                            </thead>

                                                            <tbody id="hardwareassignment_inputbox">
                                                                <?php
                                                                if(isset($hardwareassignmentproducts) && count($hardwareassignmentproducts)>0){
                                                                    $data_hidden = array('type'=>'hidden', 'name'=>'count_hardwareassignmentproduct', 'id'=>'count_hardwareassignmentproduct', 'value'=>isset($hardwareassignmentproducts)?count($hardwareassignmentproducts):1);
                                                                    echo form_input($data_hidden);

                                                                    foreach($hardwareassignmentproducts as $pkey=>$hardwareassignmentproduct){
                                                                        echo form_hidden('hardwareassignmentproductid['.$pkey.']', $hardwareassignmentproduct['id']);
                                                                        ?>
                                                                        <!-- ROW -->
                                                                        <tr id="row1_old_hardwareassignment_<?php echo $hardwareassignmentproduct['id'];?>">
                                                                            <td><?php echo form_input('mobilenr['.$pkey.']', $hardwareassignmentproduct['mobilenr'], 'class="form-control" '.$field_mobilenr_disabled);?></td>
                                                                            <td><?php echo form_input('simnr['.$pkey.']', $hardwareassignmentproduct['simnr'], 'class="form-control noerror" '.$field_simnr_disabled);?></td>
                                                                            <td><?php echo form_dropdown('newratemobile['.$pkey.']', $mobilerates, $hardwareassignmentproduct['newratemobile'], 'class="form-control" '.$field_newratemobile_disabled);?></td>
                                                                            <td><?php echo form_dropdown('hardware['.$pkey.']', $hardwares, $hardwareassignmentproduct['hardware'], 'class="form-control hardware" id="old_hardware_'.$pkey.'" dataid="'.$hardwareassignmentproduct['id'].'" datarow="'.$pkey.'" datatype="old" '.$field_hardware_disabled);?></td>
                                                                            <td><?php echo form_dropdown('stockhardware['.$pkey.']', '', $hardwareassignmentproduct['stockhardware'], 'class="form-control stockhardware" id="old_stockhardware_'.$pkey.'" datarow="'.$pkey.'" datatype="old" '.$field_stockhardware_disabled);?></td>
                                                                            <td><?php echo form_input('seriesnr['.$pkey.']', $hardwareassignmentproduct['seriesnr'], 'class="form-control" id="old_seriesnr_'.$pkey.'" '.$field_seriesnr_disabled);?></td>
                                                                            <td><?php echo form_input('shippingnr['.$pkey.']', $hardwareassignmentproduct['shippingnr'], 'class="form-control" '.$field_shippingnr_disabled);?></td>
                                                                            <td><?php echo form_input('hardwarevalue['.$pkey.']', $hardwareassignmentproduct['hardwarevalue'], 'class="form-control" '.$field_hardwarevalue_disabled);?></td>
                                                                        </tr>
                                                                        <!-- END ROW -->
                                                                        <?php
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
                                                    <a href="<?php echo base_url('admin/hardwareassignments')?>"><button type="button" class="btn default"><?php echo lang('cancel');?></button></a>
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
                                if(isset($hardwareassignment['hardwareassignmentnr']) && $hardwareassignment['hardwareassignmentnr']>0){
                                    $this->load->view('admin/hardwareassignments/tab-document', array('hardwareassignment'=>$hardwareassignment,'categories'=>$categories));
                                }
                                ?>

                            </div>

                            <div class="tab-pane" id="tab_reminder" style="display:<?php echo $tab_reminder;?>">

                                <?php
                                if(isset($hardwareassignment['hardwareassignmentnr']) && $hardwareassignment['hardwareassignmentnr']>0){
                                    $this->load->view('admin/hardwareassignments/tab-reminder', array('hardwareassignment'=>$hardwareassignment));
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
    var form_id = 'form_hardwareassignment';
    var func_FormValidation = 'FormCustomValidation';

    function after_func_FormValidation(form1, error1, success1){

        form1.validate({
            errorElement: 'span', //default input error message container
            errorClass: 'help-block help-block-error', // default input error message class
            focusInvalid: false, // do not focus the last invalid input
            ignore: "",  // validate all fields including form hidden input

            rules: {
                company: {
                    required: true
                },
                hardwareassignmentstatus: {
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
                if(extraFieldsValidate()){
                    App.scrollTo(error1, -200);
                    return true;
                }else{
                    //return false; v1
                    //return true; v2

                    //v3
                    /*if($('#hardwareassignmentstatus').val()==3){
                        return false;
                    }
                    else{
                        return true;
                    }*/

                    return false;
                }
            }
	});
    }
</script>

<?php $this->load->view('admin/footer.php'); ?>
<script>
jQuery(".stockhardware").find("option").eq(0).text('<?php echo lang('page_option_select')?>');
jQuery('#form_hardwareassignment').on('submit', function(e) {
    //extraFieldsValidate();
});

//Get Stock Hardwares
jQuery('.hardware').change(function(e) {
    var hardware = jQuery(this).val();
    var dataid = jQuery(this).attr('dataid');
    var rown = jQuery(this).attr('datarow');
    var rowt = jQuery(this).attr('datatype');

    jQuery("#"+rowt+"_stockhardware_"+rown).html("<option value=''><?php echo lang('page_option_wait')?></option>");

    jQuery.ajax({dataType: "JSON", url: '<?php echo base_url('admin/hardwareassignments/getStockHardwares/');?>'+hardware+"/<?php echo $hardwareassignment['hardwareassignmentnr'];?>", success: function(data){
        jQuery("#"+rowt+"_stockhardware_"+rown).find("option").eq(0).text('<?php echo lang('page_option_select')?>');
        jQuery.each( data, function( key, value ) {
            if(key!=""){
                var selected = '';
                if(key == '<?php //echo $hardwareassignment['stockhardware']?>'){
                    selected = ' selected';
                }

                jQuery("#"+rowt+"_stockhardware_"+rown).append("<option value='"+key+"' "+selected+">"+value+"</option>");
            }
        });
    }});
});
jQuery('.hardware').change();

//Get Stock Hardware Seriesnr
jQuery('.stockhardware').change(function(e) {
    var current_val = jQuery(this).val();
    var current_rown = jQuery(this).attr('datarow');
    var rowt = jQuery(this).attr('datatype');

    var selected_ok = 1;
    jQuery('#hardwareassignment_inputbox select').each(function() {
        //if(jQuery(this).attr('class')=='form-control stockhardware'){
       if(jQuery(this).hasClass("stockhardware")){
            if($(this).val() != "" && $(this).val().length > 0) {
                if(current_val==$(this).val() && current_rown!=$(this).attr('datarow')){
                    selected_ok = 0;
                }
            }
        }
    });

    if(selected_ok==0){
        alert('<?php echo lang('page_lb_already_chosen')?>');
        $(this).val('');
        jQuery("#"+rowt+"_seriesnr_"+current_rown).val("");
    }
    else{
        jQuery("#"+rowt+"_seriesnr_"+current_rown).val("");
        jQuery.ajax({url: '<?php echo base_url('admin/hardwareassignments/getStockHardwareSeriesnr/');?>'+eval(current_val), success: function(data){
             jQuery("#"+rowt+"_seriesnr_"+current_rown).val(data);
        }});
    }
});
</script>
<?php $this->load->view('admin/hardwareassignments/hardwareassignmentjs',array('hardwareassignment'=>isset($hardwareassignment)?$hardwareassignment:'', 'remindersubjects'=>$remindersubjects));?>

<script>
<?php
//Initilize for Selected Stock Hardware
if(isset($hardwareassignmentproducts) && count($hardwareassignmentproducts)>0){
    foreach($hardwareassignmentproducts as $pkey=>$hardwareassignmentproduct){
        ?>
        setTimeout(function(){ jQuery("#old_stockhardware_<?php echo $pkey;?>").val('<?php echo ($hardwareassignmentproduct['stockhardware']>0)?$hardwareassignmentproduct['stockhardware']:'';?>'); },1000);
        <?php
    }
}
?>
</script>