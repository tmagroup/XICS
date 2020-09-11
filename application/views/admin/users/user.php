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
                                <a href="<?php echo base_url('admin/users');?>"><?php echo lang('page_users');?></a>
                                <i class="fa fa-circle"></i>
                            </li> 
                            
                            <li>
                                <span>
                                    <?php
                                    if(isset($user['userid']) && $user['userid']>0){
                                        echo lang('page_edit_user');
                                    }
                                    else
                                    {
                                        echo lang('page_create_user');                                
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
                        if(isset($user['userid']) && $user['userid']>0){
                            ?>
                            <i class="fa fa-pencil"></i>
                            <?php
                            echo lang('page_edit_user');
                        }
                        else
                        {
                            ?>
                            <i class="fa fa-user-plus"></i>
                            <?php
                            echo lang('page_create_user');                                
                        }    
                        ?>
                        
                    </h3>
                    <!-- END PAGE TITLE-->
                    <!-- END PAGE HEADER-->
                    
                    
                    <?php
					//Self and Master Admin 
					$tab_permission = 'none';
					if(isset($user['userid']) && ($user['userid']==get_user_id() || $user['userid']==1)){
						$tab_permission = 'none';
					}					
					?>
                    
                    
                    <div class="row">
                        <?php echo form_open($this->uri->uri_string(), array('enctype' => "multipart/form-data", 'id' => 'form_user') );?>
                        
                        
                        <ul class="nav nav-tabs">
                            <li class="active">
                                <a href="#tab_profile" data-toggle="tab"><?php echo lang('page_lb_profile');?></a>
                            </li>
                            <li style="display:<?php echo $tab_permission;?>">
                                <a href="#tab_permissions" data-toggle="tab"><?php echo lang('page_lb_permissions');?></a>
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
                                                <label><?php echo lang('page_fl_userrole');?> <span class="required"> * </span></label>
                                                <?php 
                                                if(isset($user['userid']) && $user['userid']==1){
                                                    echo form_dropdown(array('name'=>'userrole','id'=>'userrole'), $roles, isset($user['userrole'])?$user['userrole']:'', 'class="form-control" disabled');
                                                    echo form_hidden('userrole', isset($user['userrole'])?$user['userrole']:'', 'class="form-control"');
                                                }else{
                                                    echo form_dropdown(array('name'=>'userrole','id'=>'userrole'), $roles, isset($user['userrole'])?$user['userrole']:'', 'class="form-control"');
                                                }
                                                ?>
                                            </div>
                                            
                                            <div class="form-group">
                                                <label><?php echo lang('page_fl_company');?> <span class="required"> * </span></label>
                                                <?php echo form_input('company', isset($user['company'])?$user['company']:'', 'class="form-control"');?>
                                            </div>
                                            
                                            <div class="form-group">
                                                <label><?php echo lang('page_fl_salutation');?> <span class="required"> * </span></label>
                                                <?php echo form_dropdown('salutation', $salutations, isset($user['salutation'])?$user['salutation']:'', 'class="form-control"');?>
                                            </div>
                                            
                                            <div class="form-group">
                                                <label><?php echo lang('page_fl_surname');?> <span class="required"> * </span></label>
                                                <?php echo form_input('surname', isset($user['surname'])?$user['surname']:'', 'class="form-control"');?>
                                            </div>
                                            
                                            <div class="form-group">
                                                <label><?php echo lang('page_fl_name');?> <span class="required"> * </span></label>
                                                <?php echo form_input('name', isset($user['name'])?$user['name']:'', 'class="form-control"');?>
                                            </div>
                                            
                                            <div class="form-group">
                                                <label><?php echo lang('page_fl_username');?> <span class="required"> * </span></label>
                                                <?php echo form_input('username', isset($user['username'])?$user['username']:'', 'class="form-control"');?>
                                            </div>
                                            
                                            <div class="form-group">
                                                <label><?php echo lang('page_fl_password');?> <span class="required"> * </span></label>
                                                <?php echo form_password('password', "", 'class="form-control" id="submit_form_password"');?>
                                            </div>
                                            
                                            <div class="form-group">
                                                <label><?php echo lang('page_fl_cpassword');?> <span class="required"> * </span></label>
                                                <?php echo form_password('cpassword', "", 'class="form-control"');?>
                                            </div>
                                            
                                            <div class="form-group">
                                                <label><?php echo lang('page_fl_email');?> <span class="required"> * </span></label>
                                                <?php echo form_input(array('type'=>'email','name'=>'email'), isset($user['email'])?$user['email']:'', 'class="form-control"');?>
                                            </div>
                                            
                                            <div class="form-group">
                                                <label><?php echo lang('page_fl_phonenumber');?> </label>
                                                <?php echo form_input('phonenumber', isset($user['phonenumber'])?$user['phonenumber']:'', 'class="form-control"');?>
                                            </div>
                                            
                                            <div class="form-group">
                                                <label><?php echo lang('page_fl_mobilnr');?> </label>
                                                <?php echo form_input('mobilnr', isset($user['mobilnr'])?$user['mobilnr']:'', 'class="form-control"');?>
                                            </div>
                                            
                                            <div class="form-group">
                                                <label><?php echo lang('page_fl_street');?> <span class="required"> * </span></label>
                                                <?php echo form_textarea(array('name'=>'street','rows'=>3), isset($user['street'])?$user['street']:'', 'class="form-control"');?>
                                            </div>
                                            
                                            <div class="form-group">
                                                <label><?php echo lang('page_fl_zipcode');?> <span class="required"> * </span></label>
                                                <?php echo form_input('zipcode', isset($user['zipcode'])?$user['zipcode']:'', 'class="form-control"');?>
                                            </div>
                                            
                                            <div class="form-group">
                                                <label><?php echo lang('page_fl_city');?> <span class="required"> * </span></label>
                                                <?php echo form_input('city', isset($user['city'])?$user['city']:'', 'class="form-control"');?>
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
                                            
                                            <?php
                                            if(isset($user['userid']) && $user['userid']>1){
                                                ?>
                                                <div class="form-group">
                                                    <label><?php echo lang('page_dt_active');?> <span class="required"> * </span></label>
                                                    <div class="clearfix"></div>

                                                        <?php 
                                                        $data = array(
                                                            'id' => 'active_yes',
                                                            'name' => 'active',
                                                            'value' => 1,
                                                            'checked' => (isset($user['active']) && $user['active']==1)?'true':''
                                                        );
                                                        echo form_label(form_radio($data)." ".lang('page_lb_yes'),"active_yes");

                                                        $data = array(
                                                            'id' => 'active_no',
                                                            'name' => 'active',
                                                            'value' => 0,
                                                            'checked' => (isset($user['active']) && $user['active']==0)?'true':''
                                                        );
                                                        echo form_label(form_radio($data)." ".lang('page_lb_no'),"active_no");
                                                        ?>

                                                </div>
                                                <?php
                                            }
                                            ?>
                                            
                                            <div class="form-group">
                                                <label><?php echo lang('page_fl_userthumb');?> <!--<span class="required"> * </span>--></label>
                                                <div class="clearfix">&nbsp;</div>
                                                <div class="fileinput fileinput-new" data-provides="fileinput">
                                                    <div class="fileinput-new thumbnail" style="width: 160px; height: 160px;">
                                                        
                                                        <!--<img src="<?php echo base_url('assets/pages/img/avatars/user-placeholder.jpg');?>" alt="" />-->
                                                        <?php
                                                        $userid = isset($user['userid'])?$user['userid']:'';
                                                        echo user_profile_image($userid,array('user-profile-image'),'thumb');
                                                        ?>
                                                        
                                                    </div>
                                                    <div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 160px; max-height: 160px;"> </div>
                                                    <div>
                                                        <span class="btn default btn-file">
                                                            <span class="fileinput-new"> <?php echo lang('page_lb_selectimage');?> </span>
                                                            <span class="fileinput-exists"> <?php echo lang('page_lb_change');?> </span>
                                                            <!--<input type="file" name="...">-->                                                             
                                                            <?php
                                                            echo form_upload('userthumb');
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
                                                <img src="<?php echo base_url('assets/pages/img/google_calendar.jpg')?>" width="100" />
                                                <div class="clearfix">&nbsp;</div>
                                                
                                                <?php
                                                $googleCalendarIDs = array();
                                                if(isset($user['googleCalendarIDs'])){
                                                    if(is_array($user['googleCalendarIDs'])){
                                                        $googleCalendarIDs = $user['googleCalendarIDs'];
                                                    }
                                                    else{
                                                        $googleCalendarIDs = explode(",",$user['googleCalendarIDs']);
                                                    }
                                                }
                                                ?>
                                                
                                                
                                                
                                                <?php
                                                //if(isset($user['userid']) && $user['userid']==1){
                                                    ?>
                                                
                                                
                                                    <ul id="googleCalendarIDs"  class="icheck-colors" style="display:none;">
                                                        <?php
                                                        foreach($googlecalendars as $googlecalendar){
                                                            ?>
                                                            <div class="clearfix"></div>                                                            
                                                            <li style="background-color:<?php echo $getSystemCalendarColor[$googlecalendar['colorId']];?>"></li>
                                                            
                                                            <label>
                                                            <?php 
                                                            $cal_checked = '';
                                                            if(in_array($googlecalendar['id'],$googleCalendarIDs)){
                                                                $cal_checked = ' checked';
                                                            }
                                                            
                                                            $ch = array(
                                                                'name' => 'googleCalendarIDs[]',
                                                                'value' => $googlecalendar['id'],
                                                                'checked' => $cal_checked, 
                                                                'class' => 'vchecker'
                                                            );                                                               
                                                            echo form_checkbox($ch);
                                                            echo $googlecalendar['summary'];?>                                                                
                                                            </label>    
                                                            
                                                            <?php
                                                        }
                                                        ?>
                                                    </ul>   
                                                    <?php    
                                                    
                                                //}
                                                //else{
                                                    
                                                    ?>
                                                
                                                    <select id="googleCalendarIDs2" name="googleCalendarIDs2[]" class="form-control" style="display:none;">
                                                        <option value=""><?php echo lang('page_option_select');?></option>
                                                        <?php
                                                        foreach($googlecalendars as $googlecalendar){                                                            
                                                            $cal_selected = '';
                                                            if(in_array($googlecalendar['id'],$googleCalendarIDs)){
                                                                $cal_selected = ' selected';
                                                            }
                                                            //style="background-color:<?php echo $getSystemCalendarColor[$googlecalendar['colorId']];"
                                                            ?>
                                                            <option value="<?php echo $googlecalendar['id'];?>" <?php echo $cal_selected;?>><?php echo $googlecalendar['summary'];?></option>
                                                            <?php
                                                        }
                                                        ?>
                                                    </select>
                                                
                                                    <?php
                                                    
                                                //}
                                                ?>
                                                
                                            </div>
                                            
                                        </div>
                                        
                                </div>
                                    </div>
                                    <!-- END SAMPLE FORM PORTLET-->
                            
                                </div>  
                                
                            </div>
                            
                            <div class="tab-pane" id="tab_permissions" style="display:<?php echo $tab_permission;?>">
                                <div class="portlet light bordered">                                
                                    <div class="portlet-body form">
                                    	
                                        <div class="form-group">
                                            <label><?php echo lang('page_fl_permission_type');?>: </label>
                                            
                                            <?php 
                                            $data = array(
                                                'id' => 'permission_type_role',
                                                'name' => 'permission_type',
                                                'value' => 'role',
                                                'checked' => 'true'
                                            );
                                            echo form_label(form_radio($data)." ".lang('page_fl_permission_type_radio_role'),"permission_type_role");

                                            $data = array(
                                                'id' => 'permission_type_user',
                                                'name' => 'permission_type',
                                                'value' => 'user',
                                                'checked' => (isset($user['permission_type']) && $user['permission_type']=='user')?'true':''
                                            );
                                            echo form_label(form_radio($data)." ".lang('page_fl_permission_type_radio_user'),"permission_type_user");
                                            ?>
                                            
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
                                                
                                                <tbody>
                                                    <?php
                                                    $r = 1;
                                                    foreach($permissions as $permission){
                                                        ?>
                                                        <tr>
                                                           <td class=""><?php echo lang($permission['lang_name']);
                                                           echo form_hidden('Permission[permissionid]['.$permission['permissionid'].']', $permission['permissionid']);
                                                           ?></th>
                                                           <td class="text-center">
                                                               
                                                                    <?php 
																	//Viewable Permission
																	if(in_array($permission['shortname'],$GLOBALS['viewable_permission'])){																		
																		$checked = has_permission($permission['shortname'], 'view', isset($user['userid'])?$user['userid']:'');
																		if($this->input->post()){ $checked = false; }
																		if(isset($user['Permission']['can_view'][$permission['permissionid']])){
																			$checked = true;
																		}
																		
																		$ch = array(
																			'name' => 'Permission[can_view]['.$permission['permissionid'].']',
																			'value' => '1',
																			'checked' => $checked,                                                                   
																		);                                                               
																		echo form_checkbox($ch);
																	}
                                                                    ?>
                                                               
                                                           </th>
                                                           <td class="text-center">
                                                               
                                                                    <?php 
																	//Viewable Own Permission
																	if(in_array($permission['shortname'],$GLOBALS['viewable_own_permission'])){	
																		$checked = has_permission($permission['shortname'], 'view_own', isset($user['userid'])?$user['userid']:'');
																		if($this->input->post()){ $checked = false; }
																		if(isset($user['Permission']['can_view_own'][$permission['permissionid']])){
																			$checked = true;
																		}
																		
																		$ch = array(
																			'name' => 'Permission[can_view_own]['.$permission['permissionid'].']',
																			'value' => '1',
																			'checked' => $checked,                                                                   
																		);                                                               
																		echo form_checkbox($ch);
																	}
                                                                    ?>
                                                               
                                                           </th>
                                                           <td class="text-center">
                                                               
                                                                    <?php 
																	//Creatable Permission
																	if(in_array($permission['shortname'],$GLOBALS['creatable_permission'])){	
																		$checked = has_permission($permission['shortname'], 'create', isset($user['userid'])?$user['userid']:'');
																		if($this->input->post()){ $checked = false; }
																		if(isset($user['Permission']['can_create'][$permission['permissionid']])){
																			$checked = true;
																		}
																		
																		$ch = array(
																			'name' => 'Permission[can_create]['.$permission['permissionid'].']',
																			'value' => '1',
																			'checked' => $checked,                                                                   
																		);                                                               
																		echo form_checkbox($ch);
																	}
                                                                    ?>
                                                              
                                                           </th>
                                                           <td class="text-center">
                                                               
                                                                    <?php 
																	//Editable Permission
																	if(in_array($permission['shortname'],$GLOBALS['editable_permission'])){	
																		$checked = has_permission($permission['shortname'], 'edit', isset($user['userid'])?$user['userid']:'');
																		if($this->input->post()){ $checked = false; }
																		if(isset($user['Permission']['can_edit'][$permission['permissionid']])){
																			$checked = true;
																		}
																		
																		$ch = array(
																			'name' => 'Permission[can_edit]['.$permission['permissionid'].']',
																			'value' => '1',
																			'checked' => $checked,                                                                   
																		);                                                               
																		echo form_checkbox($ch);
																	}
                                                                    ?>
                                                               
                                                           </th>
                                                           <td class="text-center text-danger">
                                                               
                                                                    <?php
																	//Deletable Permission
																	if(in_array($permission['shortname'],$GLOBALS['deletable_permission'])){	 
																		$checked = has_permission($permission['shortname'], 'delete', isset($user['userid'])?$user['userid']:'');
																		if($this->input->post()){ $checked = false; }
																		if(isset($user['Permission']['can_delete'][$permission['permissionid']])){
																			$checked = true;
																		}
																		
																		$ch = array(
																			'name' => 'Permission[can_delete]['.$permission['permissionid'].']',
																			'value' => '1',
																			'checked' => $checked,                                                                   
																		);                                                               
																		echo form_checkbox($ch);
																	}
                                                                    ?>
                                                              
                                                           </th>
                                                           <td class="text-center">
                                                               
                                                                    <?php 
																	//Importable Permission
																	if(in_array($permission['shortname'],$GLOBALS['importable_permission'])){	 
                                                                        $checked = has_permission($permission['shortname'], 'import', isset($user['userid'])?$user['userid']:'');
                                                                        if($this->input->post()){ $checked = false; }
                                                                        if(isset($user['Permission']['can_import'][$permission['permissionid']])){
                                                                            $checked = true;
                                                                        }

                                                                        $ch = array(
                                                                            'name' => 'Permission[can_import]['.$permission['permissionid'].']',
                                                                            'value' => '1',
                                                                            'checked' => $checked,                                                                   
                                                                        );                                                               
                                                                        echo form_checkbox($ch);
                                                                    }
                                                                    ?>
                                                               
                                                           </th>
                                                        </tr>
                                                        <?php
                                                        $r++;
                                                    }
                                                    ?>
                                                </tbody>
                                                
                                            </table>    
                                        </div>
                                    </div>
                                </div>
                            </div>    
                        </div>        
                        
                        
                        <div class="clearfix"></div>
                        <div class="col-md-6">
                            <!-- BEGIN SAMPLE FORM PORTLET-->
                            <div class="portlet light">                                
                                <div class="portlet-body">
                                    <div class="form-body">
                                        <div class="form-actions">
                                            <button type="submit" class="btn blue"><?php echo lang('save');?></button>
                                            <a href="<?php echo base_url('admin/users')?>"><button type="button" class="btn default"><?php echo lang('cancel');?></button></a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- END SAMPLE FORM PORTLET-->
                        </div>                

                        
                        <?php echo form_close();?>
                    </div>
                    
                    
                </div>
                <!-- END CONTENT BODY -->
            </div>
            <!-- END CONTENT -->
                        
            
        </div>
        <!-- END CONTAINER -->
               
<script>
    var form_id = 'form_user'; 
    var func_FormValidation = 'FormCustomValidation';
    
    function after_func_FormValidation(form1, error1, success1){
      
        form1.validate({
            errorElement: 'span', //default input error message container
            errorClass: 'help-block help-block-error', // default input error message class
            focusInvalid: false, // do not focus the last invalid input
            ignore: "",  // validate all fields including form hidden input

            rules: { 
                userrole: {                        
                    required: true
                },
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
                username: {    
                    minlength: 2,                    
                    required: true
                },			
                password: {
                    minlength: 5,
                    required: <?php echo isset($user['userid'])?'false':'true'?>
                },
                cpassword: {
                    minlength: 5,
                    required: <?php echo isset($user['userid'])?'false':'true'?>,
                    equalTo: "#submit_form_password"
                },			
                email: {
                    required: true,
                    email: true
                },
                /*phonenumber: {
                    required: true
                },
                mobilnr: {
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
                userthumb: {					  
                  extension: "jpg|jpeg|png"
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
                    App.scrollTo(error1, -200);
                    return true;
            }
	});
    }
</script>

<?php $this->load->view('admin/footer.php'); ?>        


<script>
jQuery('input[name=permission_type]').change(function () {    
	//jQuery("#loadPermissionTable").hide();
	
	if(jQuery(this).is(":checked") ){ // check if the radio is checked
		var val = jQuery(this).val(); // retrieve the value
	}
			
	if(val=='user'){
		jQuery("#loadPermissionTable").slideDown("slow","swing");					
	}
	else{		
		jQuery("#loadPermissionTable").slideUp("slow","swing");			
	}
});
jQuery('input[name=permission_type]').change();

//The Support should have all right like Admin. He should see all enries from each calender like the admin view.
jQuery('#userrole').change( function(){
   var userrole = $(this).val();
   $('#googleCalendarIDs').hide();
   $('#googleCalendarIDs2').hide();
   
   if(userrole==1 || userrole==5){
       $('#googleCalendarIDs').show('slow');
       $('#googleCalendarIDs2').hide('slow');
   }else{
       $('#googleCalendarIDs').hide('slow');
       $('#googleCalendarIDs2').show('slow');
   }
});
jQuery('#userrole').change();
</script> 