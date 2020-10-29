<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php $this->load->view('admin/header.php'); ?>
<style type="text/css">
    .error {
        color: red;
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
                                <a href="<?php echo base_url('admin/leads');?>"><?php echo lang('page_leads');?></a>
                                <i class="fa fa-circle"></i>
                            </li>

                            <li>
                                <span>
                                    <?php
                                        if(isset($lead['leadnr']) && $lead['leadnr']>0){
                                            echo lang('page_edit_termination');
                                        } else {
                                            echo lang('page_create_termination');
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
                        $url = base_url().'admin/termination/commit/';
                        if(isset($terminationData['id']) && $terminationData['id']>0){
                            $url = base_url().'admin/termination/commit/'.$terminationData['id'];
                        ?>
                            <i class="fa fa-pencil"></i>
                            <?php echo lang('page_edit_termination');
                        } else {
                        ?>
                            <i class="fa fa-plus"></i>
                            <?php echo lang('page_create_termination'); }
                        ?>

                    </h3>

                    <div class="row">
                        <ul class="nav nav-tabs">
                            <li class="active">
                                <a href="#tab_profile" data-toggle="tab"><?php echo lang('page_lb_profile');?></a>
                            </li>
                        </ul>

                        <div class="tab-content">
                            <div class="tab-pane active" id="tab_profile">
                                <?php echo form_open($url, array('enctype' => "multipart/form-data", 'id' => 'form_termination') );?>
                                <div class="col-md-6">
                                    <div class="portlet light bordered">
                                        <div class="portlet-body form">
                                            <div class="form-body">
                                                <div class="form-group">
                                                    <label><?php echo lang('page_fl_leadstatus');?> <span class="required"> * </span></label>
                                                    <select name="lead_status" class="form-control">
                                                        <option value="">Select Lead Status</option>
                                                        <?php if(!empty($leadStatusData)) { ?>
                                                            <?php foreach ($leadStatusData as $key => $leadstatus) {
                                                                $seleted = isset($terminationData) && $terminationData['lead_status'] == $leadstatus['id'] ? 'selected="selected"' : '';
                                                            ?>
                                                                <option value="<?php echo $leadstatus['id'];?>" <?php echo $seleted;?>><?php echo $leadstatus['name'];?></option>
                                                            <?php } ?>
                                                        <?php } ?>
                                                    </select>
                                                </div>
                                                <div class="form-group">
                                                    <label><?php echo lang('page_fl_provider');?> <span class="required"> * </span></label>
                                                    <select name="provider" class="form-control">
                                                        <option value="">Select Provider</option>
                                                        <?php if(!empty($providerData)) { ?>
                                                            <?php foreach ($providerData as $key => $provider) {
                                                                $seleted = isset($terminationData) && $terminationData['provider'] == $provider['id'] ? 'selected="selected"' : '';
                                                            ?>
                                                                <option value="<?php echo $provider['id'];?>" <?php echo $seleted;?>><?php echo $provider['name'];?></option>
                                                            <?php } ?>
                                                        <?php } ?>
                                                    </select>
                                                </div>
                                                <div class="form-group">
                                                    <label><?php echo lang('page_fl_surname');?> <span class="required"> * </span></label>
                                                    <input type="text" name="surname" class="form-control" value="<?php echo isset($terminationData) ? $terminationData['surname'] :'';?>">
                                                </div>
                                                <div class="form-group">
                                                    <label><?php echo lang('page_fl_position');?> <span class="required"> * </span></label>
                                                    <input type="text" name="position" class="form-control" value="<?php echo isset($terminationData) ? $terminationData['position'] :'';?>">
                                                </div>
                                                <div class="form-group">
                                                    <label><?php echo lang('page_fl_phone');?> <span class="required"> * </span></label>
                                                    <input type="text" name="phone_number" class="form-control" value="<?php echo isset($terminationData) ? $terminationData['phone_number'] :'';?>">
                                                </div>
                                                <div class="form-group">
                                                    <label><?php echo lang('page_fl_cards');?> <span class="required"> * </span></label>
                                                    <input type="number" name="cards" class="form-control" value="<?php echo isset($terminationData) ? $terminationData['cards'] :'';?>">
                                                </div>
                                                <div class="form-group">
                                                    <label><?php echo lang('page_fl_street');?> <span class="required"> * </span></label>
                                                    <input type="text" name="street" class="form-control" value="<?php echo isset($terminationData) ? $terminationData['street'] :'';?>">
                                                </div>

                                                 <div class="form-group">
                                                    <label><?php echo lang('page_fl_city');?> <span class="required"> * </span></label>
                                                    <input type="text" name="city" class="form-control" value="<?php echo isset($terminationData) ? $terminationData['city'] :'';?>">
                                                </div>

                                                <div class="form-group">
                                                    <label><?php echo lang('page_fl_responsive_user');?> <span class="required"> * </span></label>
                                                    <select name="responsive_user" class="form-control">
                                                        <option value="">Select Responsive user</option>
                                                        <?php if(!empty($responsiveUserData)) { ?>
                                                            <?php foreach ($responsiveUserData as $key => $reponsive_user) {
                                                                $seleted = isset($terminationData) && $terminationData['responsive_user'] == $reponsive_user['userid'] ? 'selected="selected"' : '';
                                                            ?>
                                                                <option value="<?php echo $reponsive_user['userid'];?>" <?php echo $seleted;?>><?php echo $reponsive_user['username'];?></option>
                                                            <?php } ?>
                                                        <?php } ?>
                                                    </select>
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
                                                    <label><?php echo lang('appointment_type');?> <span class="required"> * </span></label>
                                                    <select name="appointment_type" class="form-control">
                                                        <option value="">Select Appointment Type</option>
                                                        <?php if(!empty($appointmentTypeData)) { ?>
                                                            <?php foreach ($appointmentTypeData as $key => $appointment_type) {
                                                                $seleted = isset($terminationData) && $terminationData['appointment_type'] == $appointment_type['id'] ? 'selected="selected"' : '';
                                                            ?>
                                                                <option value="<?php echo $appointment_type['id'];?>" <?php echo $seleted;?>><?php echo $appointment_type['name'];?></option>
                                                            <?php } ?>
                                                        <?php } ?>
                                                    </select>
                                                </div>
                                                <div class="form-group">
                                                    <label><?php echo lang('page_fl_salutation');?> <span class="required"> * </span></label>
                                                    <select name="salutation" class="form-control">
                                                        <option value="1" <?php echo isset($terminationData) && $terminationData['salutation'] == '1' ? 'selected="selected"' : '';?>>Herr</option>
                                                        <option value="2" <?php echo isset($terminationData) && $terminationData['salutation'] == '2' ? 'selected="selected"' : '';?>>Frau</option>
                                                    </select>
                                                </div>
                                                <div class="form-group">
                                                    <label><?php echo lang('page_fl_name');?> <span class="required"> * </span></label>
                                                    <input type="text" name="name" class="form-control" value="<?php echo isset($terminationData) ? $terminationData['name'] :'';?>">
                                                </div>
                                                <div class="form-group">
                                                    <label><?php echo lang('page_fl_company');?> <span class="required"> * </span></label>
                                                    <input type="text" name="company_name" class="form-control" value="<?php echo isset($terminationData) ? $terminationData['company_name'] :'';?>">
                                                </div>
                                                <div class="form-group">
                                                    <label><?php echo lang('page_fl_email');?> <span class="required"> * </span></label>
                                                    <input type="text" name="email" class="form-control" value="<?php echo isset($terminationData) ? $terminationData['email'] :'';?>">
                                                </div>
                                                <div class="form-group">
                                                    <label><?php echo lang('page_fl_employment');?> <span class="required"> * </span></label>
                                                    <input type="number" name="employment" class="form-control" value="<?php echo isset($terminationData) ? $terminationData['employment'] :'';?>">
                                                </div>
                                                <div class="form-group">
                                                    <label><?php echo lang('page_fl_zipcode');?> <span class="required"> * </span></label>
                                                    <input type="text" name="zipcode" class="form-control" value="<?php echo isset($terminationData) ? $terminationData['zipcode'] :'';?>">
                                                </div>
                                                <div class="form-group">
                                                    <label><?php echo lang('page_fl_date');?> <span class="required"> * </span></label>
                                                    <div class="input-group date form_datetime">
                                                         <input type="text" class="form-control" readonly="readonly" size="16" name="date" value="">
                                                        <span class="input-group-btn">
                                                            <button class="btn default date-set" type="button">
                                                                <i class="fa fa-calendar"></i>
                                                            </button>
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label><?php echo lang('page_fl_notice');?></label>
                                                    <textarea name="notice" class="form-control"><?php echo isset($terminationData) ? $terminationData['notice'] :'';?></textarea>
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
                                                    <a href="<?php echo base_url('admin/termination')?>"><button type="button" class="btn default"><?php echo lang('cancel');?></button></a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- END SAMPLE FORM PORTLET-->
                                </div>
                                <?php echo form_close();?>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- END CONTENT BODY -->
            </div>
            <!-- END CONTENT -->
        </div>
        <!-- END CONTAINER -->

<?php $this->load->view('admin/footer.php'); ?>

<script>

    jQuery(".form_datetime").datetimepicker({
        autoclose: true,
        isRTL: App.isRTL(),
        format: "dd.mm.yyyy"+" hh:ii:ss",
        pickerPosition: (App.isRTL() ? "bottom-right" : "bottom-left")
    });

    $("#form_termination").validate({
      rules: {
        lead_status: {
          required: true
        },
        company_name: {
          required: true
        },
        salutation: {
          required: true
        },
        name: {
          required: true
        },
        surname: {
          required: true
        },
        position: {
          required: true
        },
        zipcode: {
          required: true,
          number : true
        },
        street: {
          required: true
        },
        city: {
          required: true
        },
        phone_number: {
          required: true,
          number : true
        },
        email: {
          required: true,
          email : true,
        },
        employment: {
          required: true
        },
        provider: {
          required: true
        },
        cards: {
          required: true
        },
        date: {
          required: true
        },
        responsive_user: {
          required: true
        },
        appointment_type: {
          required: true
        },
      },
      messages: {
        lead_status: {
          required: "Please select lead status",
        },
        company_name: {
          required: "Please enter Firma",
        },
        salutation: {
          required: "Please enter Anrede",
        },
        name: {
          required: "Please enter first name",
        },
        surname: {
          required: "Please enter Nachname",
        },
        position: {
          required: "Please enter Position",
        },
        zipcode: {
          required: "Please enter Postleitzahl",
          number: "Please enter only number"
        },
        street: {
          required: "Please enter Straße",
        },
        city: {
          required: "Please enter Stadt",
        },
        phone_number: {
          required: "Please enter Telefon",
          number : "Please enter only number"
        },
        email: {
          required: "Please enter email",
          email: "Please enter email valid email address",
        },
        employment: {
          required: "Please enter Beschäftigung",
        },
        provider: {
          required: "Please enter Anbieter",
        },
        cards: {
          required: "Please enter Karten",
        },
        date: {
          required: "Please enter Datum",
        },
        responsive_user: {
          required: "Please select Responsive user",
        },
        appointment_type: {
          required: "Please select Terminart",
        }
      }
    });

    jQuery(".form_date").datepicker({
        autoclose: true,
        isRTL: App.isRTL(),
        format: "dd.mm.yyyy",
        pickerPosition: (App.isRTL() ? "bottom-right" : "bottom-left")
    });
</script>