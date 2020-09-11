<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<?php $this->load->view('authentication/header.php'); ?>
<style>
.logo img{ width:20%; }
</style>
<body class=" login">
        <div class="menu-toggler sidebar-toggler"></div>
        <!-- END SIDEBAR TOGGLER BUTTON -->
        <!-- BEGIN LOGO -->
        <div class="logo">
            <a href="">
                <!--<img src="<?php echo base_url('assets/pages/img/logo-big.png'); ?>" alt="" />-->
                <!--<img src="<?php echo base_url('uploads/company/logo-big.png'); ?>" alt="" />-->
                <img src="<?php echo base_url('uploads/company/xics_original.png'); ?>" alt="" />
			</a>
        </div>
        <!-- END LOGO -->
        <!-- BEGIN LOGIN -->
        <div class="content">
            <!-- BEGIN LOGIN FORM -->
            <?php echo form_open($this->uri->uri_string(),'class="login-form"'); ?>

                <h3 class="form-title font-green"><?php echo lang('admin_auth_login_heading'); ?></h3>
                <?php $this->load->view('authentication/alerts'); ?>
                <?php echo validation_errors('<div class="alert alert-danger text-center"><button class="close" data-close="alert"></button>', '</div>'); ?>

                <?php do_action('after_admin_login_form_start'); ?>

                <div class="form-group">
                    <!--ie8, ie9 does not support html5 placeholder, so we just show field title for that-->
                    <label class="control-label visible-ie8 visible-ie9"><?php echo lang('admin_auth_login_username'); ?></label>
                    <input class="form-control form-control-solid placeholder-no-fix" type="text" autocomplete="off" placeholder="<?php echo lang('admin_auth_login_username'); ?>" name="username" /> </div>
                <div class="form-group">
                    <label class="control-label visible-ie8 visible-ie9"><?php echo lang('admin_auth_login_password'); ?></label>
                    <input class="form-control form-control-solid placeholder-no-fix" type="password" autocomplete="off" placeholder="<?php echo lang('admin_auth_login_password'); ?>" name="password" /> </div>
                <div class="form-actions">
                    <button type="submit" class="btn green uppercase"><?php echo lang('admin_auth_login_button'); ?></button>
                    <label class="rememberme check">
                        <input type="checkbox" name="Remember" value="1" /><?php echo lang('admin_auth_login_remember_me'); ?> </label>
                    <!--<a href="<?php echo site_url('forgotpassword'); ?>" id="forget-password" class="forget-password"><?php echo lang('admin_auth_login_fp'); ?></a>-->
                </div>

           		<?php do_action('before_admin_login_form_close'); ?>

	      	<?php echo form_close(); ?>

            <!-- END LOGIN FORM -->
        </div>

<?php $this->load->view('authentication/footer.php'); ?>