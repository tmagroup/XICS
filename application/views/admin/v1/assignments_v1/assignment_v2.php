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
                                                
                                                <div class="form-group">
                                                    <label><?php echo lang('page_fl_company');?> <span class="required"> * </span></label>
                                                    <?php echo form_input('company', isset($assignment['company'])?$assignment['company']:'', 'class="form-control"');?>
                                                </div>
         
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
                                                    <?php echo form_dropdown('assignmentstatus', $assignmentstatus, isset($assignment['assignmentstatus'])?$assignment['assignmentstatus']:'', 'class="form-control"');?>
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
                                                    <label><?php echo lang('page_fl_customer');?> <span class="required"> * </span></label>
                                                    <?php echo form_dropdown('customer', $customers, isset($assignment['customer'])?$assignment['customer']:'', 'class="form-control" id="customer" ');?>
                                                </div>
                                                
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
                                                        <?php echo form_dropdown('newdiscountlevel', $discountlevels, isset($assignment['newdiscountlevel'])?$assignment['newdiscountlevel']:'', 'class="form-control" id="newdiscountlevel" ');?>
                                                    </div>
                                                </div>
                                            </div>
                                                
                                            
                                            <div class="portlet-title">
                                                <div class="caption font-dark">
                                                    <span class="caption-subject font-green-sharp sbold"><?php echo lang('page_lb_assignmentproducts');?></span>
                                                </div>
                                            </div>
                                            
                                            <div class="form-body">
                                                
                                                <div class="form-group">
                                                    <div class="table-responsive no-dt">
                                                        <table class="table table-striped table-bordered table-hover dt-responsive" width="100%">    
                                                            <thead>
                                                                <tr role="row" class="heading">                                                                                                        
                                                                    <th class="text-nowrap"></th>                                                                 
                                                                    <th class="text-nowrap"><?php echo lang('page_fl_simnr');?> <span class="required"> * </span></th>
                                                                    <th class="text-nowrap"><?php echo lang('page_fl_mobilenr');?> <span class="required"> * </span></th>                                                    
                                                                    <th class="text-nowrap"><?php echo lang('page_fl_employee');?> </th>
                                                                    <th class="text-nowrap"><?php echo lang('page_fl_vvl_neu');?> <span class="required"> * </span></th>
                                                                    <th class="text-nowrap"><?php echo lang('page_fl_ratetitle');?> <span class="required"> * </span></th>
                                                                    <th class="text-nowrap"><?php echo lang('page_fl_value');?> <span class="required"> * </span></th>                                                    
                                                                    <th class=""><?php echo lang('page_fl_extemtedterm');?></th>                                                    
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
                                                                echo form_input($data_hidden);
                                                                
                                                                foreach($assignmentproducts as $pkey=>$assignmentproduct){
                                                                    
                                                                    $finished = (isset($assignmentproduct['finished']) && $assignmentproduct['finished']==1)?true:false;
                                                                    ?>
                                                                    <!-- ROW -->
                                                                    <tr id="row1_old_assignmentproduct_<?php echo $assignmentproduct['id'];?>">
                                                                        <td class="text-center">
                                                                            <?php
                                                                            if($pkey==(count($assignmentproducts)-1)){
                                                                                ?>                                                                                                                                                       
                                                                                <a href="javascript:void(0);" class="btn btn-sm btn-default btn-circle btn-editable green addassignmentproduct"><i class="fa fa-plus"></i></a>
                                                                                <?php
                                                                            }
                                                                            else{
                                                                                ?>
                                                                                <a href="javascript:void(0);" class="btn btn-sm btn-default btn-circle btn-editable red" onClick="deleteassignmentproduct('<?php echo $assignmentproduct['id'];?>','old')"><i class="fa fa-minus"></i></a>
                                                                                <?php
                                                                            }                                                                        

                                                                            echo form_hidden('assignmentproductid['.$pkey.']', $assignmentproduct['id']);

                                                                            $formula = $assignmentproduct['formula']?$assignmentproduct['formula']:'A';
                                                                            $data_hidden = array('type'=>'hidden', 'name'=>'old_formula_'.$pkey, 'value'=>$assignmentproduct['formula']);  
                                                                            echo form_input($data_hidden);
                                                                            ?>    
                                                                        </td>                                                                   
                                                                        <td><?php echo form_input('simnr['.$pkey.']', $assignmentproduct['simnr'], 'class="form-control"');?></td>
                                                                        <td><?php echo form_input('mobilenr['.$pkey.']', $assignmentproduct['mobilenr'], 'class="form-control"');?></td>
                                                                        <td><?php echo form_input('employee['.$pkey.']', $assignmentproduct['employee'], 'class="form-control noerror"');?></td>
                                                                        <td><?php echo form_dropdown('vvlneu['.$pkey.']', $vvlneu, $assignmentproduct['vvlneu'], 'class="form-control vvlneu" datarow="'.$pkey.'" datatype="old" ');?></td>
                                                                        <td id="old_newratemobile_box_<?php echo $assignmentproduct['id'];?>"><?php if($formula=='A'){ echo form_dropdown('newratemobile['.$pkey.']', $mobilerates, $assignmentproduct['newratemobile'], 'class="form-control newratemobile" id="old_newratemobile_'.$pkey.'" datarow="'.$pkey.'" datatype="old" '); }else{
                                                                            echo form_input('newratemobile['.$pkey.']', $assignmentproduct['newratemobile'], 'class="form-control" id="old_newratemobile_'.$pkey.'" ');
                                                                        }?></td>
                                                                        <td><?php echo form_input('value2['.$pkey.']', $assignmentproduct['value2'], 'class="form-control" id="old_value2_'.$pkey.'" ');?></td>

                                                                        <td class="text-center">
                                                                            <?php 
                                                                            $extemtedterm = (isset($assignmentproduct['extemtedterm']) && $assignmentproduct['extemtedterm']==1)?true:false;
                                                                            $dc = array('name'=>'extemtedterm['.$pkey.']','class'=>'form-control','checked'=>$extemtedterm, 'value'=>1);
                                                                            echo form_checkbox($dc);?>  
                                                                        </td>

                                                                        <td id="old_newoptionmobile_box_<?php echo $assignmentproduct['id'];?>"><?php if($formula=='A'){ echo form_dropdown('newoptionmobile['.$pkey.']', $mobileoptions, $assignmentproduct['newoptionmobile'], 'class="form-control newoptionmobile noerror" id="old_newoptionmobile_'.$pkey.'" datarow="'.$pkey.'" datatype="old" '); }else{
                                                                            echo form_input('newoptionmobile['.$pkey.']', $assignmentproduct['newoptionmobile'], 'class="form-control noerror" id="old_newoptionmobile_'.$pkey.'" ');
                                                                        }?></td>
                                                                        <td><?php echo form_input('value4['.$pkey.']', $assignmentproduct['value4'], 'class="form-control noerror" id="old_value4_'.$pkey.'" ');?></td>
                                                                        <td><?php 
                                                                        if($assignmentproduct['hardwarecheck']==1){
                                                                            echo form_dropdown('hardware['.$pkey.']', $hardwares, $assignmentproduct['hardware'], 'class="form-control assignment_hardware noerror" datarow="'.$pkey.'" id="old_hardware_'.$pkey.'" disabled ');
                                                                            
                                                                            $data_hidden = array('type'=>'hidden', 'name'=>'hardware['.$pkey.']', 'value'=>$assignmentproduct['hardware'], 'class'=>'noerror');  
                                                                            echo form_input($data_hidden);
                                                                        }
                                                                        else{
                                                                            echo form_dropdown('hardware['.$pkey.']', $hardwares, $assignmentproduct['hardware'], 'class="form-control assignment_hardware noerror" datarow="'.$pkey.'" id="old_hardware_'.$pkey.'" ');
                                                                        }
                                                                        ?></td>
                                                                        
                                                                        <td class="text-center">
                                                                            <?php 
                                                                            $cardstatus = (isset($assignmentproduct['cardstatus']) && $assignmentproduct['cardstatus']==1)?true:false;
                                                                            $dc = array('name'=>'cardstatus['.$pkey.']','class'=>'form-control','checked'=>$cardstatus, 'value'=>1);
                                                                            echo form_checkbox($dc);?>  
                                                                        </td>
                                                                        
                                                                        <td>
                                                                            <?php
                                                                            if(!$finished){
                                                                                ?>
                                                                                <div id="old_form_date_<?php echo $pkey;?>" class="input-group date form_date">
                                                                                    <?php $dd = array('name'=>'endofcontract['.$pkey.']', 'class'=>'form-control noerror', 'readonly'=>true, 'size'=>16, 'value'=> _d($assignmentproduct['endofcontract']));
                                                                                    echo form_input($dd);?>  

                                                                                    <span class="input-group-btn">
                                                                                        <button class="btn default date-set" type="button">
                                                                                            <i class="fa fa-calendar"></i>
                                                                                        </button>
                                                                                    </span>
                                                                                </div>
                                                                                <?php
                                                                            }
                                                                            else{
                                                                                ?>
                                                                                <div>
                                                                                    <?php $dd = array('name'=>'endofcontract['.$pkey.']', 'class'=>'form-control noerror', 'readonly'=>true, 'size'=>16, 'value'=> _d($assignmentproduct['endofcontract']));
                                                                                    echo form_input($dd);?>  
                                                                                </div>    
                                                                                <?php
                                                                            }
                                                                            ?>

                                                                            <?php
                                                                            $simcard_function_id = isset($assignmentproduct['simcard_function_id'])?$assignmentproduct['simcard_function_id']:'0';
                                                                            $simcard_function_nm = isset($assignmentproduct['simcard_function_nm'])?$assignmentproduct['simcard_function_nm']:'0';
                                                                            $simcard_function_qty = isset($assignmentproduct['simcard_function_qty'])?$assignmentproduct['simcard_function_qty']:'0';
                                                                            ?>
                                                                            <div id="old_simcard_function_<?php echo $pkey;?>"><input type="hidden" name="simcard_function_id[<?php echo $pkey;?>]" value="<?php echo $simcard_function_id;?>" /><input type="hidden" name="simcard_function_nm[<?php echo $pkey;?>]" value="<?php echo $simcard_function_nm;?>" /><input type="hidden" name="simcard_function_qty[<?php echo $pkey;?>]" value="<?php echo $simcard_function_qty;?>" /></div>
                                                                        </td>   
                                                                        
                                                                        <td class="text-center">
                                                                            <?php                                                                             
                                                                            if(!$finished){
                                                                                $dc = array('name'=>'finished['.$pkey.']','class'=>'form-control','checked'=>$finished, 'value'=>1);
                                                                                echo form_checkbox($dc);
                                                                            }
                                                                            else{
                                                                                echo form_hidden('finished['.$pkey.']', 1);
                                                                                echo '<i class="fa fa-check"></i>';
                                                                            }
                                                                            ?>  
                                                                        </td>
                                                                        
                                                                    </tr>
                                                                    <!-- END ROW -->   

                                                                    <?php
                                                                    /*if($formula=='A'){
                                                                        ?>
                                                                        <script>
                                                                        jQuery.ajax({url: '<?php echo base_url('admin/ratesmobile/getoldInputSimcardFunction/'.$pkey.'/'.$assignmentproduct['simcard_function_id'].'/'.$assignmentproduct['simcard_function_nm'].'/'.$assignmentproduct['simcard_function_qty']);?>', success: function(result){        
                                                                            jQuery('#old_simcard_function_<?php echo $assignmentproduct['id'];?>').html(result);
                                                                        }}); 
                                                                        </script>    
                                                                        <?php
                                                                    }*/
                                                                }
                                                            }
                                                            else{
                                                                ?>

                                                                <?php $data_hidden = array('type'=>'hidden', 'name'=>'count_assignmentproduct', 'id'=>'count_assignmentproduct', 'value'=>isset($assignment['mobilenr'])?count($assignment['mobilenr']):1);  
                                                                echo form_input($data_hidden);?>


                                                                <!-- ROW -->
                                                                <tr>
                                                                    <td class="text-center"><a href="javascript:void(0);" class="btn btn-sm btn-default btn-circle btn-editable green addassignmentproduct"><i class="fa fa-plus"></i></a>

                                                                        <?php
                                                                        $formula = isset($assignment['new_formula_0'])?$assignment['new_formula_0']:'A';
                                                                        $data_hidden = array('type'=>'hidden', 'name'=>'new_formula_0', 'value'=>$formula);  
                                                                        echo form_input($data_hidden);
                                                                        ?>
                                                                    </td>                                                                

                                                                    <td><?php echo form_input('simnr[0]', isset($assignment['simnr'][0])?$assignment['simnr'][0]:'', 'class="form-control"  ');?></td>
                                                                    <td><?php echo form_input('mobilenr[0]', isset($assignment['mobilenr'][0])?$assignment['mobilenr'][0]:'', 'class="form-control"  ');?></td>
                                                                    <td><?php echo form_input('employee[0]', isset($assignment['employee'][0])?$assignment['employee'][0]:'', 'class="form-control noerror"  ');?></td>

                                                                    <td><?php echo form_dropdown('vvlneu[0]', $vvlneu, isset($assignment['vvlneu'][0])?$assignment['vvlneu'][0]:'', 'class="form-control vvlneu" datarow="0" datatype="new"  ');?></td>
                                                                    <td id="new_newratemobile_box_0"><?php if($formula=='A'){ echo form_dropdown('newratemobile[0]', $mobilerates, isset($assignment['newratemobile'][0])?$assignment['newratemobile'][0]:'', 'class="form-control newratemobile" id="new_newratemobile_0" datarow="0" datatype="new"  '); }else{
                                                                            echo form_input('newratemobile[0]', isset($assignment['newratemobile'][0])?$assignment['newratemobile'][0]:'', 'class="form-control" id="new_newratemobile_0"  ');
                                                                    }?></td>
                                                                    <td><?php echo form_input('value2[0]', isset($assignment['value2'][0])?$assignment['value2'][0]:'', 'class="form-control" id="new_value2_0"  ');?></td>

                                                                    <td class="text-center">
                                                                        <?php 
                                                                        $extemtedterm = (isset($assignment['extemtedterm'][0]) && $assignment['extemtedterm'][0]==1)?true:false;
                                                                        $dc = array('name'=>'extemtedterm[0]','class'=>'form-control','checked'=>$extemtedterm, 'value'=>1);
                                                                        echo form_checkbox($dc);?>  
                                                                    </td>

                                                                    <td id="new_newoptionmobile_box_0"><?php if($formula=='A'){ echo form_dropdown('newoptionmobile[0]', $mobileoptions, isset($assignment['newoptionmobile'][0])?$assignment['newoptionmobile'][0]:'', 'class="form-control newoptionmobile noerror" id="new_newoptionmobile_0" datarow="0" datatype="new" '); }else{
                                                                        echo form_input('newoptionmobile[0]', isset($assignment['newoptionmobile'][0])?$assignment['newoptionmobile'][0]:'', 'class="form-control noerror" id="new_newoptionmobile_0"  ');
                                                                    }?></td>
                                                                    <td><?php echo form_input('value4[0]', isset($assignment['value4'][0])?$assignment['value4'][0]:'', 'class="form-control noerror" id="new_value4_0"  ');?></td>
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

                                                                if(isset($assignment['mobilenr']) && count($assignment['mobilenr'])>0){
                                                                    foreach($assignment['mobilenr'] as $pkey=>$assignmentproduct){
                                                                        if($pkey==0){ continue; }
                                                                        ?>
                                                                        <!-- ROW -->
                                                                        <tr id="row1_new_assignmentproduct_<?php echo $pkey;?>">
                                                                            <td class="text-center"><a href="javascript:void(0);" class="btn btn-sm btn-default btn-circle btn-editable red" onClick="deleteassignmentproduct('<?php echo $pkey;?>','new')"><i class="fa fa-minus"></i></a>

                                                                                <?php
                                                                                $formula = isset($assignment['new_formula_'.$pkey])?$assignment['new_formula_'.$pkey]:'A';
                                                                                $data_hidden = array('type'=>'hidden', 'name'=>'new_formula_'.$pkey, 'value'=>$formula);  
                                                                                echo form_input($data_hidden);
                                                                                ?>
                                                                            </td>
                                                                            <td><?php echo form_input('simnr['.$pkey.']', isset($assignment['simnr'][$pkey])?$assignment['simnr'][$pkey]:'', 'class="form-control"  ');?></td>
                                                                            <td><?php echo form_input('mobilenr['.$pkey.']', isset($assignment['mobilenr'][$pkey])?$assignment['mobilenr'][$pkey]:'', 'class="form-control"  ');?></td>
                                                                            <td><?php echo form_input('employee['.$pkey.']', isset($assignment['employee'][$pkey])?$assignment['employee'][$pkey]:'', 'class="form-control noerror"  ');?></td>
                                                                            <td><?php echo form_dropdown('vvlneu['.$pkey.']', $vvlneu, isset($assignment['vvlneu'][$pkey])?$assignment['vvlneu'][$pkey]:'', 'class="form-control vvlneu" datarow="'.$pkey.'" datatype="new" ');?></td>
                                                                            <td id="new_newratemobile_box_<?php echo $pkey;?>"><?php if($formula=='A'){ echo form_dropdown('newratemobile['.$pkey.']', $mobilerates, isset($assignment['newratemobile'][$pkey])?$assignment['newratemobile'][$pkey]:'', 'class="form-control newratemobile" id="new_newratemobile_'.$pkey.'" datarow="'.$pkey.'" datatype="new" '); }else{
                                                                                echo form_input('newratemobile['.$pkey.']', isset($assignment['newratemobile'][$pkey])?$assignment['newratemobile'][$pkey]:'', 'class="form-control" id="new_newratemobile_'.$pkey.'"  ');
                                                                            }?></td>
                                                                            <td><?php echo form_input('value2['.$pkey.']', isset($assignment['value2'][$pkey])?$assignment['value2'][$pkey]:'', 'class="form-control"  id="new_value2_'.$pkey.'"  ');?></td>

                                                                            <td class="text-center">
                                                                                <?php 
                                                                                $extemtedterm = (isset($assignment['extemtedterm'][$pkey]) && $assignment['extemtedterm'][$pkey]==1)?true:false;
                                                                                $dc = array('name'=>'extemtedterm['.$pkey.']','class'=>'form-control','checked'=>$extemtedterm, 'value'=>1);
                                                                                echo form_checkbox($dc);?>  
                                                                            </td>

                                                                            <td id="new_newoptionmobile_box_<?php echo $pkey;?>"><?php if($formula=='A'){ echo form_dropdown('newoptionmobile['.$pkey.']', $mobileoptions, isset($assignment['newoptionmobile'][$pkey])?$assignment['newoptionmobile'][$pkey]:'', 'class="form-control newoptionmobile noerror" id="new_newoptionmobile_'.$pkey.'" datarow="'.$pkey.'" datatype="new"  '); }else{
                                                                                echo form_input('newoptionmobile['.$pkey.']', isset($assignment['newoptionmobile'][$pkey])?$assignment['newoptionmobile'][$pkey]:'', 'class="form-control noerror" id="new_newoptionmobile_'.$pkey.'"  ');
                                                                            }?></td>
                                                                            <td><?php echo form_input('value4['.$pkey.']', isset($assignment['value4'][$pkey])?$assignment['value4'][$pkey]:'', 'class="form-control noerror" id="new_value4_'.$pkey.'"  ');?></td>
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
               
<script>
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
                if(extraFieldsValidate()){
                    App.scrollTo(error1, -200);
                    return true;
                }else{
                    return false;
                }
            }
	});
    }
</script>

<?php $this->load->view('admin/footer.php'); ?>        

<script>   
jQuery('.addassignmentproduct').click( function(){    
    var rownum = parseInt(jQuery('#count_assignmentproduct').val()) + 1;        
    var inputhtml = '<tr id="row1_new_assignmentproduct_'+rownum+'">';    
    inputhtml = inputhtml + '<td class="text-center"><a href="javascript:void(0);" class="btn btn-sm btn-default btn-circle btn-editable red" onClick="deleteassignmentproduct(\''+rownum+'\',\'new\')"><i class="fa fa-minus"></i></a><input type="hidden" name="new_formula_'+rownum+'" value="A" /></td>';           
    inputhtml = inputhtml + '<td><input type="text" name="simnr['+rownum+']" value=""  class="form-control" /></td>';
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
    <?php
    foreach($mobilerates as $k=>$v){
        if($k==0){ $v = lang("page_lb_select_mobile_rate"); }
        ?>
        inputhtml = inputhtml + "<option value='<?php echo $k;?>'><?php echo $v;?></option>";
        <?php
    }
    ?>    
    inputhtml = inputhtml + '</select></td>';    
    inputhtml = inputhtml + '<td><input type="text" name="value2['+rownum+']" value=""  class="form-control" id="new_value2_'+rownum+'" /></td>'; 
    inputhtml = inputhtml + '<td class="text-center"><input type="checkbox" name="extemtedterm['+rownum+']" value="1" class="form-control extemtedterm"></td>'; 
    inputhtml = inputhtml + '<td id="new_newoptionmobile_box_'+rownum+'"><select name="newoptionmobile['+rownum+']" class="form-control newoptionmobile noerror" id="new_newoptionmobile_'+rownum+'" datarow="'+rownum+'" datatype="new" >';
    <?php
    foreach($mobileoptions as $k=>$v){
        if($k==0){ $v = lang("page_lb_select_mobile_option"); }
        ?>
        inputhtml = inputhtml + "<option value='<?php echo $k;?>'><?php echo $v;?></option>";
        <?php
    }
    ?>
    inputhtml = inputhtml + '</select></td>';       
    inputhtml = inputhtml + '<td><input type="text" name="value4['+rownum+']" value=""  class="form-control noerror" id="new_value4_'+rownum+'" /></td>';
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
    
    jQuery('#assignmentproduct_inputbox').prepend(inputhtml);
    jQuery('#count_assignmentproduct').val(rownum);    
    jQuery('[data-toggle="tooltip"]').tooltip();
    jQuery('.extemtedterm').uniform();
    jQuery('.cardstatus').uniform();
    jQuery('.finished').uniform();
    
    changenewRateMobile();
    changenewOptionMobile();
    changeFormula();
    changeHardware();
    datapicker();   
    datepicker_vvlneu('',rownum,'new');
    
    App.scrollTo(jQuery('#assignmentproduct_inputbox'), 1);
});
jQuery('#form_assignment').on('submit', function(e) {
    extraFieldsValidate();
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
            <?php
            foreach($mobilerates as $k=>$v){
                ?>                              
                selecthtml = selecthtml + "<option value='<?php echo $k;?>'><?php echo $v;?></option>";
                <?php
            }
            ?>              
            selecthtml = selecthtml + '</select>';            
            jQuery('#'+rowt+'_newratemobile_box_'+rown).html(selecthtml);
            
            //New Option Mobile
            selecthtml ='<select name="newoptionmobile['+rown+']" class="form-control newoptionmobile noerror" id="'+rowt+'_newoptionmobile_'+rown+'" datarow="'+rown+'" datatype="'+rowt+'" >';
            <?php
            foreach($mobileoptions as $k=>$v){
                ?>               
                selecthtml = selecthtml + "<option value='<?php echo $k;?>'><?php echo $v;?></option>";
                <?php
            }
            ?>           
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
                jQuery('#'+rowt+'_value2_'+rown).val(result);
            }});   

            /*jQuery.ajax({url: '<?php echo base_url('admin/ratesmobile/getnewInputSimcardFunction/');?>'+rown+'/'+id, success: function(result){        
                jQuery('#'+rowt+'_simcard_function_'+rown).html(result);
            }});*/ 
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
            jQuery('#'+rowt+'_value2_'+rown).val(result);
        }}); 
    }
}

function deleteassignmentproduct(dataid,datatype){
    var rownum = jQuery('#count_assignmentproduct').val();
    if(datatype=='old'){    
        //Delete record from db by ajax
        deleteConfirmation('<?php echo base_url('admin/assignments/deleteAssignmentProduct/');?>',dataid,'<?php echo lang('page_lb_delete_assignmentproduct')?>','<?php echo lang('page_lb_delete_assignmentproduct_info')?>','true');        
    }
    else{
        jQuery('#row1_new_assignmentproduct_'+dataid).remove();
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
            alert('<?php echo lang('page_lb_already_chosen')?>');    
            $(this).val('');
        }
    });
}

changenewRateMobile();
changenewOptionMobile();
changeFormula();
changeHardware();
</script>
<?php $this->load->view('admin/assignments/assignmentjs',array('assignment'=>isset($assignment)?$assignment:'', 'remindersubjects'=>$remindersubjects));?>

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