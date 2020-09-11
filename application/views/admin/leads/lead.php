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
                                    if(isset($lead['leadnr']) && $lead['leadnr']>0){
                                        echo lang('page_edit_lead');
                                    }
                                    else
                                    {
                                        echo lang('page_create_lead');
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
                        if(isset($lead['leadnr']) && $lead['leadnr']>0){
                            ?>
                            <i class="fa fa-pencil"></i>
                            <?php
                            echo lang('page_edit_lead');
                        }
                        else
                        {
                            ?>
                            <i class="fa fa-plus"></i>
                            <?php
                            echo lang('page_create_lead');
                        }
                        ?>

                    </h3>
                    <!-- END PAGE TITLE-->
                    <!-- END PAGE HEADER-->


                    <?php
                    //Only Editable
                    $tab_document = '';
                    $tab_reminder = '';
                    if(empty($lead['leadnr'])){
                       $tab_document = 'none';
                       $tab_reminder = 'none';
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
                            <li style="display:<?php echo $tab_reminder;?>">
                                <a href="#tab_reminder" data-toggle="tab"><?php echo lang('page_lb_reminder');?></a>
                            </li>
                        </ul>


                        <div class="tab-content">

                            <div class="tab-pane active" id="tab_profile">
                                <?php echo form_open($this->uri->uri_string(), array('enctype' => "multipart/form-data", 'id' => 'form_lead') );?>
                                <div class="col-md-6">


                                    <!-- BEGIN SAMPLE FORM PORTLET-->
                                    <div class="portlet light bordered">
                                        <div class="portlet-body form">

                                            <div class="form-body">

                                                <!-- POS cant set Responsible -->
                                                <?php
                                                if($GLOBALS['current_user']->userrole==6){
                                                    if(isset($lead['leadnr'])){
                                                        ?>
                                                        <div class="form-group">
                                                            <label><?php echo lang('page_fl_responsible');?></label>
                                                            <?php echo form_dropdown('responsible', $responsibles, isset($lead['responsible'])?$lead['responsible']:'', 'class="form-control" disabled ');?>
                                                        </div>
                                                        <?php
                                                    }
                                                }
                                                else{
                                                    ?>
                                                    <div class="form-group">
                                                        <label><?php echo lang('page_fl_responsible');?> <span class="required"> * </span></label>
                                                        <?php echo form_dropdown('responsible', $responsibles, isset($lead['responsible'])?$lead['responsible']:'', 'class="form-control"');?>
                                                    </div>
                                                    <?php
                                                }
                                                ?>

                                                <?php
                                                if($GLOBALS['current_user']->userrole==6){
                                                    ?>
                                                    <div class="form-group">
                                                        <label><?php echo lang('page_fl_recommend');?>: </label>
                                                        <?php //echo form_dropdown('recommend', $recommends, isset($lead['recommend'])?$lead['recommend']:'', 'class="form-control"');?>
                                                        <b><?php echo $GLOBALS['current_user']->name." ".$GLOBALS['current_user']->surname;?></b>
                                                        <?php echo form_hidden('recommend', get_user_id(), 'class="form-control"');?>
                                                    </div>
                                                    <?php
                                                }
                                                else{
                                                    ?>
                                                    <div class="form-group">
                                                        <label><?php echo lang('page_fl_recommend');?> </label>
                                                        <?php echo form_dropdown('recommend', $recommends, isset($lead['recommend'])?$lead['recommend']:'', 'class="form-control"');?>
                                                    </div>
                                                    <?php
                                                }
                                                ?>


                                                <div class="form-group">
                                                    <label><?php echo lang('page_fl_leadstatus');?> <span class="required"> * </span></label>
                                                    <?php echo form_dropdown('leadstatus', $leadstatus, isset($lead['leadstatus'])?$lead['leadstatus']:'', 'class="form-control"');?>
                                                </div>

                                                <div class="form-group">
                                                    <label><?php echo lang('page_fl_leadsource');?> <span class="required"> * </span></label>
                                                    <?php echo form_dropdown('leadsource', $leadsources, isset($lead['leadsource'])?$lead['leadsource']:'', 'class="form-control"');?>
                                                </div>

                                                <div class="form-group">
                                                    <label><?php echo lang('page_fl_leadprovider');?> </label>

                                                    <div id="leadprovider_inputbox">

                                                        <?php
                                                        if(isset($leadprovidercompanies) && count($leadprovidercompanies)>0){
                                                            foreach($leadprovidercompanies as $lkey=>$leadprovidercompany){
                                                                ?>
                                                                <div class="form-group" id="old_leadprovider_<?php echo $leadprovidercompany['id'];?>">

                                                                    <div class="input-group">
                                                                        <?php echo form_hidden('leadprovidercompanyid[]', $leadprovidercompany['id']);?>


                                                                        <?php
                                                                        if($lkey==0){
                                                                            ?>
                                                                            <?php echo form_input('providernr[]', $leadprovidercompany['providernr'], 'class="form-control"  ');?>
                                                                            <span class="input-group-btn"><button type="button" class="btn default blue pull-right addleadprovidercompany" data-toggle="tooltip" data-title="<?php echo lang('page_lb_add_more_provider_company_no');?>">+</button>
                                                                            <?php $data_hidden = array('type'=>'hidden', 'name'=>'count_leadprovidercompany', 'id'=>'count_leadprovidercompany', 'value'=>count($leadprovidercompanies));
                                                                            echo form_input($data_hidden);?></span>
                                                                            <?php
                                                                        }
                                                                        else
                                                                        {
                                                                            ?>
                                                                            <?php echo form_input('providernr[]', $leadprovidercompany['providernr'], 'class="form-control" ');?>
                                                                            <span class="input-group-btn"><button type="button" class="btn default red pull-right deleteleadprovidercompany" onclick="javascript:deleteleadprovidercompany('<?php echo $leadprovidercompany['id'];?>', 'old');" data-toggle="tooltip" data-title="<?php echo lang('page_lb_delete_provider_company_no');?>">x</button></span>
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
                                                                    <?php echo form_input('providernr[]', isset($lead['providernr'][0])?$lead['providernr'][0]:'', 'class="form-control"  ');?>
                                                                    <span class="input-group-btn"><button type="button" class="btn default blue addleadprovidercompany" data-toggle="tooltip" data-title="<?php echo lang('page_lb_add_more_provider_company_no');?>">+</button>
                                                                    <?php $data_hidden = array('type'=>'hidden', 'name'=>'count_leadprovidercompany', 'id'=>'count_leadprovidercompany', 'value'=>isset($lead['providernr'])?count($lead['providernr']):1);
                                                                    echo form_input($data_hidden);?></span>
                                                                </div>

                                                            </div>
                                                            <?php

                                                            if(isset($lead['providernr']) && count($lead['providernr'])>0){
                                                                foreach($lead['providernr'] as $lkey=>$leadprovidercompany){
                                                                    if($lkey==0){ continue; }
                                                                    ?>
                                                                    <div class="form-group" id="new_leadprovider_<?php echo ($lkey);?>">

                                                                        <div class="input-group">
                                                                            <?php echo form_input('providernr[]', $leadprovidercompany, 'class="form-control" ');?>
                                                                            <span class="input-group-btn"><button type="button" class="btn default red pull-right deleteleadprovidercompany" onclick="javascript:deleteleadprovidercompany('<?php echo ($lkey);?>', 'new');" data-toggle="tooltip" data-title="<?php echo lang('page_lb_delete_provider_company_no');?>">x</button></span>
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
                                                    <?php echo form_input('custpassword', isset($lead['custpassword'])?$lead['custpassword']:'', 'class="form-control"');?>
                                                </div>

                                                <div class="form-group">
                                                    <label><?php echo lang('page_fl_framecontno');?></label>
                                                    <?php echo form_input('framecontno', isset($lead['framecontno'])?$lead['framecontno']:'', 'class="form-control"');?>
                                                </div>

                                                <div class="form-group">
                                                    <label><?php echo lang('page_fl_company');?> <span class="required"> * </span></label>
                                                    <?php echo form_input('company', isset($lead['company'])?$lead['company']:'', 'class="form-control"');?>
                                                </div>

                                                <div class="form-group">
                                                    <label><?php echo lang('page_fl_street');?> <span class="required"> * </span></label>
                                                    <?php echo form_textarea(array('name'=>'street','rows'=>3), isset($lead['street'])?$lead['street']:'', 'class="form-control"');?>
                                                </div>

                                                <div class="form-group">
                                                    <label><?php echo lang('page_fl_zipcode');?> <span class="required"> * </span></label>
                                                    <?php echo form_input('zipcode', isset($lead['zipcode'])?$lead['zipcode']:'', 'class="form-control"');?>
                                                </div>

                                                <div class="form-group">
                                                    <label><?php echo lang('page_fl_city');?> <span class="required"> * </span></label>
                                                    <?php echo form_input('city', isset($lead['city'])?$lead['city']:'', 'class="form-control"');?>
                                                </div>

                                                <div class="form-group">
                                                    <label><?php echo lang('page_fl_country');?> </label>
                                                    <?php echo form_input('country', isset($lead['country'])?$lead['country']:'', 'class="form-control"');?>
                                                </div>
                                                <div class="form-group">
                                                    <label><?php echo lang('page_fl_phonenumber');?> <span class="required"> * </span></label>
                                                    <?php echo form_input('phone', isset($lead['phone'])?$lead['phone']:'', 'class="form-control"');?>
                                                </div>
                                                <div class="form-group">
                                                    <label><?php echo lang('page_fl_faxnr');?> </label>
                                                    <?php echo form_input('faxnr', isset($lead['faxnr'])?$lead['faxnr']:'', 'class="form-control"');?>
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
                                                    <label><?php echo lang('page_fl_email');?> <span class="required"> * </span></label>
                                                    <?php echo form_input(array('type'=>'email','name'=>'email'), isset($lead['email'])?$lead['email']:'', 'class="form-control"');?>
                                                </div>

                                                <div class="form-group">
                                                    <label><?php echo lang('page_fl_companysize');?> </label>
                                                    <?php echo form_dropdown('companysize', $companysizes, isset($lead['companysize'])?$lead['companysize']:'', 'class="form-control"');?>
                                                </div>

                                                <div class="form-group">
                                                    <label><?php echo lang('page_fl_website');?> </label>
                                                    <?php echo form_input('website', isset($lead['website'])?$lead['website']:'', 'class="form-control"');?>
                                                </div>

                                                <div class="form-group">
                                                    <label><?php echo lang('page_fl_business');?> </label>
                                                    <?php echo form_input('business', isset($lead['business'])?$lead['business']:'', 'class="form-control"');?>
                                                </div>

                                                <div class="form-group">
                                                    <label><?php echo lang('page_fl_title');?> <span class="required"> * </span> </label>
                                                    <?php echo form_dropdown('salutation', $salutations, isset($lead['salutation'])?$lead['salutation']:'', 'class="form-control"');?>
                                                </div>

                                                <div class="form-group">
                                                    <label><?php echo lang('page_fl_surname');?> <span class="required"> * </span></label>
                                                    <?php echo form_input('surname', isset($lead['surname'])?$lead['surname']:'', 'class="form-control"');?>
                                                </div>

                                                <div class="form-group">
                                                    <label><?php echo lang('page_fl_name');?> <span class="required"> * </span></label>
                                                    <?php echo form_input('name', isset($lead['name'])?$lead['name']:'', 'class="form-control"');?>
                                                </div>

                                                <div class="form-group">
                                                    <label><?php echo lang('page_fl_position');?> </label>
                                                    <?php echo form_input('position', isset($lead['position'])?$lead['position']:'', 'class="form-control"');?>
                                                </div>

                                                <div class="form-group">
                                                    <label><?php echo lang('page_fl_mobilnr');?> </label>
                                                    <?php echo form_input('mobilnr', isset($lead['mobilnr'])?$lead['mobilnr']:'', 'class="form-control"');?>
                                                </div>

                                                <div class="form-group">
                                                    <label><?php echo lang('page_fl_provider');?> </label>
                                                    <?php echo form_dropdown('provider', provider_values(), isset($lead['provider'])?$lead['provider']:'', 'class="form-control"');?>
                                                </div>

                                                <div class="form-group">
                                                    <label><?php echo lang('page_fl_product');?> </label>
                                                    <?php echo form_dropdown('product', $products, isset($lead['product'])?$lead['product']:'', 'class="form-control"');?>
                                                </div>

                                                <div class="form-group" style="display:<?php if(get_user_role()=='customer'){ echo 'none'; }?>">
                                                    <label><?php echo lang('page_fl_teamwork');?> </label>
                                                    <?php //echo form_dropdown('teamwork[]', $teamworks, isset($lead['teamwork'])?$lead['teamwork']:'', 'class="form-control select2-multiple" id="teamwork" multiple');?>

                                                    <?php
                                                    $selected_teamwork = isset($lead['teamwork'])?$lead['teamwork']:'';
                                                    $selected_teamwork = explode(",", $selected_teamwork);
                                                    ?>

                                                    <select name="teamwork[]" class="form-control select2-multiple" id="teamwork" multiple>
                                                    <?php
                                                    foreach($teamworks as $teamwork){
                                                        $selected = '';
                                                        if(in_array($teamwork['userid'],$selected_teamwork)){
                                                            $selected = ' selected';
                                                        }
                                                        ?>
                                                        <option value="<?php echo $teamwork['userid']?>" <?php echo $selected;?>><?php echo $teamwork['name']?></option>
                                                        <?php
                                                    }
                                                    ?>
                                                    </select>
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
                                                    <a href="<?php echo base_url('admin/leads')?>"><button type="button" class="btn default"><?php echo lang('cancel');?></button></a>
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
                                if(isset($lead['leadnr']) && $lead['leadnr']>0){
                                    $this->load->view('admin/leads/tab-document', array('lead'=>$lead,'categories'=>$categories));
                                }
                                ?>

                            </div>

                            <div class="tab-pane" id="tab_reminder" style="display:<?php echo $tab_reminder;?>">

                                <?php
                                if(isset($lead['leadnr']) && $lead['leadnr']>0){
                                    $this->load->view('admin/leads/tab-reminder', array('lead'=>$lead));
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
    var form_id = 'form_lead';
    var func_FormValidation = 'FormCustomValidation';

    function after_func_FormValidation(form1, error1, success1){

        form1.validate({
            errorElement: 'span', //default input error message container
            errorClass: 'help-block help-block-error', // default input error message class
            focusInvalid: false, // do not focus the last invalid input
            ignore: "",  // validate all fields including form hidden input

            rules: {
                responsible: {
                    required: true
                },
                /*recommend: {
                    required: true
                },*/
                leadstatus: {
                    required: true
                },
                leadsource: {
                    required: true
                },
                /*custpassword: {
                    minlength: 5,
                    required: true
                },*/
                /*framecontno: {
                    required: true
                },*/
                company: {
                    minlength: 2,
                    required: true
                },
                street: {
                    required: true
                },
                zipcode: {
                    required: true
                },
                city: {
                    required: true
                },
                /*country: {
                    required: true
                },*/
                phone: {
                    required: true
                },
                /*faxnr: {
                    required: true
                },*/
                email: {
                    required: true,
                    email: true
                },
                /*companysize: {
                    required: true
                },
                website: {
                    required: true
                },
                business: {
                    required: true
                },*/
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
           	/*position: {
                    required: true
                },
                mobilnr: {
                    required: true
                },
                provider: {
                    required: true
                },
                /*product: {
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
jQuery('.addleadprovidercompany').click( function(){
    var rownum = parseInt(jQuery('#count_leadprovidercompany').val()) + 1;
    var inputhtml = '<div class="form-group" id="new_leadprovider_'+rownum+'"><div class="input-group"><input type="text" name="providernr[]" value="" class="form-control"><span class="input-group-btn"><button type="button" class="btn default red pull-right" onclick="javascript:deleteleadprovidercompany('+rownum+', \'new\')" data-toggle="tooltip" data-title="<?php echo lang('page_lb_delete_provider_company_no');?>">x</button></span></div></div>';
    jQuery('#leadprovider_inputbox').append(inputhtml);
    jQuery('#count_leadprovidercompany').val(rownum);
    jQuery('[data-toggle="tooltip"]').tooltip();
});
function deleteleadprovidercompany(dataid,datatype){
    var rownum = jQuery('#count_leadprovidercompany').val();
    if(datatype=='old'){
        //Delete record from db by ajax
        jQuery.ajax({url: '<?php echo base_url('admin/leads/deleteProviderCompany/');?>'+dataid, success: function(result){
            jQuery('#old_leadprovider_'+dataid).remove();
        }});
    }
    else{
        jQuery('#new_leadprovider_'+dataid).remove();
    }
    jQuery('#count_leadprovidercompany').val(rownum);
}
</script>
<?php $this->load->view('admin/leads/leadjs',array('lead'=>isset($lead)?$lead:'', 'remindersubjects'=>$remindersubjects));?>