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
                                <span>
                                    <?php
                                    echo lang('page_roles');
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
                        
                        <i class="fa fa-briefcase"></i>
                        <?php
                        echo lang('page_update_rolepermission');
                        ?>
                        
                    </h3>
                    <!-- END PAGE TITLE-->
                    <!-- END PAGE HEADER-->
                    
                    
                    
                    <div class="row">
                        <?php echo form_open($this->uri->uri_string(), array('enctype' => "multipart/form-data", 'id' => 'form_role') );?>
                        
                        
                        <ul class="nav nav-tabs">                            
                            <li class="active">
                                <a href="#tab_permissions" data-toggle="tab"><?php echo lang('page_lb_permissions');?></a>
                            </li>
                        </ul>
                        
                        
                        <div class="tab-content">
                            
                            <div class="tab-pane active" id="tab_permissions">
                                <div class="portlet light bordered">                                
                                    <div class="portlet-body form">
                                        
                                        <div class="form-group">
                                            <label><?php echo lang('page_fl_userrole');?></label>
                                            <?php echo form_dropdown('userrole', $roles, isset($role['userrole'])?$role['userrole']:'', 'class="form-control"');?>
                                        </div>
                                        
                                        <div class="clearfix"></div>
                                        <div class="table-responsive" id="loadPermissionTable">
                                            <table class="table table-bordered roles no-margin">
                                                <thead>
                                                    <tr>
                                                       <th class=""><?php echo lang('page_lb_permission'); ?></th>
                                                       <th class="text-center"><?php echo lang('page_lb_permission_view'); ?></th>
                                                       <th class="text-center"><?php echo lang('page_lb_permission_view_own'); ?></th>
                                                       <th class="text-center"><?php echo lang('page_lb_permission_create'); ?></th>
                                                       <th class="text-center"><?php echo lang('page_lb_permission_edit'); ?></th>
                                                       <th class="text-center text-danger"><?php echo lang('page_lb_permission_delete'); ?></th>
                                                       <th class="text-center"><?php echo lang('page_lb_permission_import'); ?></th>
                                                    </tr>
                                                </thead>
                                                
                                                <tbody id="loadPermission">
                                                </tbody>
                                                
                                            </table>    
                                        </div>
                                        
                                        <div class="clearfix"></div>
                                        <div class="form-actions">
                                            <button type="submit" id="btn-submit" class="btn blue"><?php echo lang('save');?></button>
                                        </div>
                                    </div>
                                </div>
                            </div>    
                        </div>        
                        
                        <?php echo form_close();?>
                    </div>
                    
                    
                </div>
                <!-- END CONTENT BODY -->
            </div>
            <!-- END CONTENT -->
                        
            
        </div>
        <!-- END CONTAINER -->

<?php $this->load->view('admin/footer.php'); ?> 
                       
<script>
$("select[name=userrole]").find("option").eq(0).remove();
jQuery('select[name=userrole]').change(function () {    
	var id = $(this).val();
	jQuery("#btn-submit").hide();
	jQuery("#loadPermissionTable").hide();
		
	if(id>0){
		jQuery.ajax({url: '<?php echo base_url('admin/roles/ajax/');?>'+id, success: function(result){        
			jQuery("#loadPermission").html(result);			
			jQuery("#loadPermissionTable").slideDown("slow","swing");
                        $('.vchecker').uniform();
			jQuery("#btn-submit").show();
		}});
	}
	else{		
		jQuery("#loadPermissionTable").slideUp("slow","swing");	
		jQuery("#btn-submit").hide();
	}
        
        
});
jQuery('select[name=userrole]').focus();
jQuery('select[name=userrole]').change();
</script>        