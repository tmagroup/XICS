<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$secure_connection = false;
if(isset($_SERVER['HTTPS'])) {
    if ($_SERVER['HTTPS'] == "on") {
        $secure_connection = true;
    }
}
$dohttp = '';
if($secure_connection){
    $dohttp = 's';
}
?>
<!DOCTYPE html>
<!--
Template Name: Metronic - Responsive Admin Dashboard Template build with Twitter Bootstrap 3.3.5
Version: 4.5.2
Author: KeenThemes
Website: http://www.keenthemes.com/
Contact: support@keenthemes.com
Follow: www.twitter.com/keenthemes
Like: www.facebook.com/keenthemes
Purchase: http://themeforest.net/item/metronic-responsive-admin-dashboard-template/4021469?ref=keenthemes
License: You must have a valid license purchased only from themeforest(the above link) in order to legally use the theme for your project.
-->
<!--[if IE 8]> <html lang="en" class="ie8 no-js"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9 no-js"> <![endif]-->
<!--[if !IE]><!-->
<html lang="en">
    <!--<![endif]-->
    <!-- BEGIN HEAD -->

    <head>
        <meta charset="utf-8" />
        <title><?php if (isset($title)){ echo $title; } else { echo get_option('company_name'); } ?></title>
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta content="width=device-width, initial-scale=1" name="viewport" />
        <meta content="" name="description" />
        <meta content="" name="author" />
        <!-- BEGIN PAGE FIRST SCRIPTS -->
		<script src="<?php echo base_url('assets/global/plugins/pace/pace.min.js'); ?>" type="text/javascript"></script>
        <link href="<?php echo base_url('assets/global/plugins/pace/themes/pace-theme-flash.css'); ?>" rel="stylesheet" type="text/css" />
        <!-- END PAGE FIRST SCRIPTS -->


        <!-- BEGIN GLOBAL MANDATORY STYLES -->
        <link href="<?php echo 'http'.$dohttp.'://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&subset=all';?>" rel="stylesheet" type="text/css" />
        <link href="<?php echo base_url('assets/global/plugins/font-awesome/css/font-awesome.min.css'); ?>" rel="stylesheet" type="text/css" />
        <link href="<?php echo base_url('assets/global/plugins/simple-line-icons/simple-line-icons.min.css'); ?>" rel="stylesheet" type="text/css" />
        <link href="<?php echo base_url('assets/global/plugins/bootstrap/css/bootstrap.min.css'); ?>" rel="stylesheet" type="text/css" />
        <link href="<?php echo base_url('assets/global/plugins/uniform/css/uniform.default.css'); ?>" rel="stylesheet" type="text/css" />
        <link href="<?php echo base_url('assets/global/plugins/bootstrap-switch/css/bootstrap-switch.min.css'); ?>" rel="stylesheet" type="text/css" />
        <!-- END GLOBAL MANDATORY STYLES -->


        <!-- BEGIN PAGE LEVEL PLUGINS -->

            <link href="<?php echo base_url('assets/global/plugins/bootstrap-daterangepicker/daterangepicker.min.css'); ?>" rel="stylesheet" type="text/css" />
            <link href="<?php echo base_url('assets/global/plugins/morris/morris.css'); ?>" rel="stylesheet" type="text/css" />
            <link href="<?php echo base_url('assets/global/plugins/fullcalendar/fullcalendar.min.css'); ?>" rel="stylesheet" type="text/css" />
            <link href="<?php echo base_url('assets/global/plugins/jqvmap/jqvmap/jqvmap.css'); ?>" rel="stylesheet" type="text/css" />

            <!-- List -->
            <link href="<?php echo base_url('assets/global/plugins/select2/css/select2.min.css'); ?>" rel="stylesheet" type="text/css" />
            <link href="<?php echo base_url('assets/global/plugins/select2/css/select2-bootstrap.min.css'); ?>" rel="stylesheet" type="text/css" />

            <link href="<?php echo base_url('assets/global/plugins/datatables/datatables.css'); ?>" rel="stylesheet" type="text/css" />
            <link href="<?php echo base_url('assets/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.css'); ?>" rel="stylesheet" type="text/css" />
            <link href="<?php echo base_url('assets/global/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css'); ?>" rel="stylesheet" type="text/css" />

            <!-- Add/Edit Form -->
            <link href="<?php echo base_url('assets/global/plugins/bootstrap-fileinput/bootstrap-fileinput.css'); ?>" rel="stylesheet" type="text/css" />
            <link href="<?php echo base_url('assets/pages/css/profile.min.css'); ?>" rel="stylesheet" type="text/css" />

            <link href="<?php echo base_url('assets/global/plugins/dropzone/dropzone.min.css'); ?>" rel="stylesheet" type="text/css" />
        	<link href="<?php echo base_url('assets/global/plugins/dropzone/basic.min.css'); ?>" rel="stylesheet" type="text/css" />

            <link href="<?php echo base_url('assets/global/plugins/bootstrap-toastr/toastr.css'); ?>" rel="stylesheet" type="text/css" />
            <link href="<?php echo base_url('assets/global/plugins/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css'); ?>" rel="stylesheet" type="text/css" />

            <!-- Calendar -->
            <link href="<?php echo base_url('assets/global/plugins/fullcalendar/fullcalendar.min.css'); ?>" rel="stylesheet" type="text/css" />
            <!-- End Calendar -->

        <!-- END PAGE LEVEL PLUGINS -->


        <!-- BEGIN THEME GLOBAL STYLES -->
        <link href="<?php echo base_url('assets/global/css/components.min.css'); ?>" rel="stylesheet" id="style_components" type="text/css" />
        <link href="<?php echo base_url('assets/global/css/plugins.css?v=2'); ?>" rel="stylesheet" type="text/css" />
        <!-- END THEME GLOBAL STYLES -->
        <!-- BEGIN THEME LAYOUT STYLES -->
        <link href="<?php echo base_url('assets/layouts/layout/css/layout.min.css'); ?>" rel="stylesheet" type="text/css" />
        <link href="<?php echo base_url('assets/layouts/layout/css/themes/darkblue.min.css'); ?>" rel="stylesheet" type="text/css" id="style_color" />
        <link href="<?php echo base_url('assets/layouts/layout/css/custom.css?v=11'); ?>" rel="stylesheet" type="text/css" />
        <!-- END THEME LAYOUT STYLES -->
        <link rel="shortcut icon" href="<?php echo base_url('assets/pages/img/favicon.ico'); ?>" /> </head>
    <!-- END HEAD -->
    <style type="text/css">
        .error {
            color: red;
        }
    </style>