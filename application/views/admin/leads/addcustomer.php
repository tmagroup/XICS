<!-- BEGIN PAGE MESSAGE-->
<?php $this->load->view('admin/alerts_modal'); ?>
<!-- BEGIN PAGE MESSAGE-->

<div class="col-md-6">

    <!-- BEGIN SAMPLE FORM PORTLET-->
    <div class="portlet light bordered">
        <div class="portlet-body form">

            <div class="form-body">

                <div class="form-group">
                    <label><?php echo lang('page_fl_responsible');?> <span class="required"> * </span></label>
                    <?php echo form_dropdown('responsible', $responsibles, isset($lead['responsible'])?$lead['responsible']:'', 'class="form-control"');?>
                </div>

                <div class="form-group">
                    <label><?php echo lang('page_fl_recommend');?></label>
                    <?php echo form_dropdown('recommend', $recommends, isset($lead['recommend'])?$lead['recommend']:'', 'class="form-control"');?>
                </div>

                <div class="form-group">
                    <label><?php echo lang('page_fl_leadprovider');?></label>

                    <div id="leadprovider_inputbox">

                        <?php
                        if(isset($leadprovidercompanies) && count($leadprovidercompanies)>0){
                            foreach($leadprovidercompanies as $lkey=>$leadprovidercompany){
                                ?>
                                <div class="form-group" id="old_leadprovider_<?php echo $leadprovidercompany['id'];?>">

                                    <?php echo form_hidden('customerprovidercompanyid[]', $leadprovidercompany['id']);?>

                                    <?php
                                    if($lkey==0){
                                        echo form_input('providernr[]', $leadprovidercompany['providernr'], 'class="form-control" ');
                                    }
                                    else
                                    {
                                         echo form_input('providernr[]', $leadprovidercompany['providernr'], 'class="form-control" ');
                                    }
                                    ?>

                                </div>
                                <?php
                            }
                        }
                        else{
                            ?>
                            <div class="form-group">
                                <?php echo form_input('providernr[]', isset($lead['providernr'][0])?$lead['providernr'][0]:'', 'class="form-control" required="true" ');?>
                            </div>
                            <?php
                        }
                        ?>

                    </div>

                </div>

                <div class="form-group">
                    <label><?php echo lang('page_fl_username');?> <span class="required"> * </span></label>
                    <?php echo form_input('username', '', 'class="form-control"');?>
                </div>

                <!-- <div class="form-group">
                    <label><?php echo lang('page_fl_custpassword');?></label>
                    <?php echo form_input('password', isset($lead['custpassword'])?$lead['custpassword']:'', 'class="form-control"');?>
                </div> -->

                <div class="form-group">
                    <label><?php echo lang('page_fl_custpassword');?></label>
                    <?php echo form_input('userpassword', '', 'class="form-control"');?>
                </div>

                <div class="form-group">
                    <label><?php echo lang('page_fl_userpassword');?> <span class="required"> * </span></label>
                    <?php echo form_password('password', "", 'class="form-control" id="submit_form_password"');?>
                </div>

                <div class="form-group">
                    <label><?php echo lang('page_fl_cpassword');?> <span class="required"> * </span></label>
                    <?php echo form_password('cpassword', "", 'class="form-control"');?>
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
                    <label><?php echo lang('page_fl_salutation');?> <span class="required"> * </span></label>
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
                    <label><?php echo lang('page_fl_position');?></label>
                    <?php echo form_input('position', isset($lead['position'])?$lead['position']:'', 'class="form-control"');?>
                </div>

                 <div class="form-group">
                    <label><?php echo lang('page_fl_email');?> <span class="required"> * </span></label>
                    <?php echo form_input(array('type'=>'email','name'=>'email'), isset($lead['email'])?$lead['email']:'', 'class="form-control"');?>
                </div>

                <div class="form-group">
                    <label><?php echo lang('page_fl_phonenumber');?> <span class="required"> * </span></label>
                    <?php echo form_input('phone', isset($lead['phone'])?$lead['phone']:'', 'class="form-control"');?>
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
                    <label><?php echo lang('page_fl_faxnr');?></label>
                    <?php echo form_input('faxnr', isset($lead['faxnr'])?$lead['faxnr']:'', 'class="form-control"');?>
                </div>

                <div class="form-group">
                    <label><?php echo lang('page_fl_mobilnr');?></label>
                    <?php echo form_input('mobilnr', isset($lead['mobilnr'])?$lead['mobilnr']:'', 'class="form-control"');?>
                </div>

                <div class="form-group">
                    <label><?php echo lang('page_fl_street');?> <span class="required"> * </span></label>
                    <?php echo form_textarea('street', isset($lead['street'])?$lead['street']:'', 'class="form-control"');?>
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
                    <label><?php echo lang('page_fl_companysize');?> <span class="required"> * </span></label>
                    <?php echo form_dropdown('companysize', $companysizes, isset($lead['companysize'])?$lead['companysize']:'', 'class="form-control"');?>
                </div>

                <div class="form-group">
                    <label><?php echo lang('page_fl_website');?></label>
                    <?php echo form_input('website', isset($lead['website'])?$lead['website']:'', 'class="form-control"');?>
                </div>

                <div class="form-group">
                    <label><?php echo lang('page_fl_business');?></label>
                    <?php echo form_input('business', isset($lead['business'])?$lead['business']:'', 'class="form-control"');?>
                </div>

            </div>

        </div>
    </div>
    <!-- END SAMPLE FORM PORTLET-->

</div>


<div class="clearfix"></div>