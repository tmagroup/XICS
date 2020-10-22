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
                                <a href="<?php echo base_url('admin/termination');?>"><?php echo lang('page_termination');?></a>
                                <i class="fa fa-circle"></i>
                            </li>

                            <li>
                                <span>
                                    <?php echo lang('page_details_termination');?>
                                </span>
                            </li>
                        </ul>
                    </div>
                    <!-- END PAGE BAR -->

                    <!-- BEGIN PAGE MESSAGE-->
                    <?php $this->load->view('admin/alerts'); ?>
                    <!-- BEGIN PAGE MESSAGE-->

                    <!-- BEGIN PAGE TITLE-->

                    <div class="row">
                        <div class="portlet light">
                            <div class="portlet-title" style="border:0px; margin-bottom: 0px; padding-bottom: 0px;">
                                <div class="caption">
                                    <i class="fa fa-tty"></i>
                                    <?php echo lang('page_details_termination');?>
                                </div>
                                <div class="actions">
                                    <div class="btn-group btn-group-devided">
                                        <a href="javascript:void(0);" class="btn sbold green btn-sm add-lead fresh" data-id="<?php echo $this->uri->segment(4);?>"> Lead erstellen</a>
                                    </div>
                                </div>

                            </div>
                        </div>
                        <ul class="nav nav-tabs">
                            <li class="active">
                                <a href="#tab_profile" data-toggle="tab"><?php echo lang('page_lb_profile');?></a>
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
                                                    <label><?php echo lang('page_fl_leadstatus');?> :</label>
                                                    <?php echo $data['leadName'];?>
                                                </div>
                                                <div class="form-group">
                                                    <label><?php echo lang('page_fl_provider');?> :</label>
                                                    <?php echo $data['providerName'];?>
                                                </div>
                                                <div class="form-group">
                                                    <label><?php echo lang('page_fl_surname');?> :</label>
                                                   <?php echo $data['surname'];?>
                                                </div>
                                                <div class="form-group">
                                                    <label><?php echo lang('page_fl_position');?> :</label>
                                                    <?php echo $data['position'];?>
                                                </div>
                                                <div class="form-group">
                                                    <label><?php echo lang('page_fl_phone');?> :</label>
                                                    <?php echo $data['phone_number'];?>
                                                </div>
                                                <div class="form-group">
                                                    <label><?php echo lang('page_fl_cards');?> :</label>
                                                   <?php echo $data['cards'];?>
                                                </div>
                                                <div class="form-group">
                                                    <label><?php echo lang('page_fl_street');?> :</label>
                                                    <?php echo $data['street'];?>
                                                </div>
                                                <div class="form-group">
                                                    <label><?php echo lang('page_fl_city');?> :</label>
                                                    <?php echo $data['city'];?>
                                                </div>
                                                <div class="form-group">
                                                    <label><?php echo lang('page_fl_responsive_user');?> :</label>
                                                    <?php echo $data['responsiUser'];?>
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
                                                    <label><?php echo lang('appointment_type');?> :</label>
                                                    <?php echo $data['appointmentName'];?>
                                                </div>
                                                <div class="form-group">
                                                    <label><?php echo lang('page_fl_salutation');?> :</label>
                                                    <?php echo $data['salutation'] == '1' ? 'Herr' :'Frau';?>
                                                </div>
                                                <div class="form-group">
                                                    <label><?php echo lang('page_fl_name');?> :</label>
                                                    <?php echo $data['name'];?>
                                                </div>
                                                <div class="form-group">
                                                    <label><?php echo lang('page_fl_company');?>  :</label>
                                                     <?php echo $data['company_name'];?>
                                                </div>
                                                <div class="form-group">
                                                    <label><?php echo lang('page_fl_email');?> :</label>
                                                     <?php echo $data['email'];?>
                                                </div>
                                                <div class="form-group">
                                                    <label><?php echo lang('page_fl_employment');?> :</label>
                                                    <?php echo $data['employment'];?>
                                                </div>
                                                <div class="form-group">
                                                    <label><?php echo lang('page_fl_zipcode');?> :</label>
                                                   <?php echo $data['zipcode'];?>
                                                </div>
                                                <div class="form-group">
                                                    <label><?php echo lang('page_fl_date');?> :</label>
                                                     <?php echo date('d-m-Y',strtotime($data['cards']));?>
                                                </div>
                                                <div class="form-group">
                                                    <label><?php echo lang('page_fl_notice');?> :</label>
                                                    <?php echo isset($data['notice']) ? $data['notice'] : '';?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- END SAMPLE FORM PORTLET-->

                                </div>
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
<script type="text/javascript">
    $(document).on('click', '.add-lead', function(event) {
    event.preventDefault();
    var $this = $(this);
    var id = $this.attr('data-id');
    var text =  $this.text();
    if(confirm("Are you sure you want to Create Lead?")){
        if($this.hasClass('fresh')) {
            $this.removeClass('fresh');
            $this.text('');
            $this.html('<i class="fa fa-spinner fa-spin" style="font-size:24px"></i>');
            $.ajax({
                url: "<?php echo base_url().'admin/termination/createLead/';?>",
                type:"POST",
                dataType:'json',
                data:{id:id},
                success: function(data){
                    if(data.status) {
                        $this.addClass('fresh');
                        $this.text(text);
                        showtoast(data.response,'',data.message);
                        show_datatable();
                    }
                }
            });
        }
    } else {
        return false;
    }
});
</script>