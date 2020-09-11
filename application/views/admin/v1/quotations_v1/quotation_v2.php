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
                                <a href="<?php echo base_url('admin/quotations');?>"><?php echo lang('page_quotations');?></a>
                                <i class="fa fa-circle"></i>
                            </li> 
                            
                            <li>
                                <span>
                                    <?php
                                    if(isset($quotation['quotationnr']) && $quotation['quotationnr']>0){
                                        echo lang('page_edit_quotation');
                                    }
                                    else
                                    {
                                        echo lang('page_create_quotation');                                
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
                        if(isset($quotation['quotationnr']) && $quotation['quotationnr']>0){
                            ?>
                            <i class="fa fa-pencil"></i>
                            <?php
                            echo lang('page_edit_quotation');
                        }
                        else
                        {
                            ?>
                            <i class="fa fa-plus"></i>
                            <?php
                            echo lang('page_create_quotation');                                
                        }    
                        ?>
                        
                    </h3>
                    <!-- END PAGE TITLE-->
                    <!-- END PAGE HEADER-->
                    
                    
                    <?php
                    //Only Editable 
                    $tab_document = '';
                    $tab_reminder = '';
                    if(empty($quotation['quotationnr'])){
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
                                <?php echo form_open($this->uri->uri_string(), array('enctype' => "multipart/form-data", 'id' => 'form_quotation') );?>        
                                <div class="col-md-6">
                            
                            
                                    <!-- BEGIN SAMPLE FORM PORTLET-->
                                    <div class="portlet light bordered">                                
                                        <div class="portlet-body form">
                                    
                                            <div class="form-body">
                                                
                                                <div class="form-group">
                                                    <label><?php echo lang('page_fl_company');?> <span class="required"> * </span></label>
                                                    <?php echo form_input('company', isset($quotation['company'])?$quotation['company']:'', 'class="form-control"');?>
                                                </div>
         
                                                <div class="form-group">
                                                    <label><?php echo lang('page_fl_quotationdate');?> <span class="required"> * </span></label>

                                                    <div class="input-group date form_date">
                                                        <?php $dd = array('name'=>'quotationdate', 'class'=>'form-control', 'readonly'=>true, 'size'=>16, 'value'=> isset($quotation['quotationdate'])?_d($quotation['quotationdate']):date('d.m.Y'));
                                                        echo form_input($dd);?>  

                                                        <span class="input-group-btn">
                                                            <button class="btn default date-set" type="button">
                                                                <i class="fa fa-calendar"></i>
                                                            </button>
                                                        </span>
                                                    </div>

                                                </div>
                                                
                                                <div class="form-group">
                                                    <label><?php echo lang('page_fl_quotationstatus');?> <span class="required"> * </span></label>
                                                    <?php echo form_dropdown('quotationstatus', $quotationstatus, isset($quotation['quotationstatus'])?$quotation['quotationstatus']:'', 'class="form-control"');?>
                                                </div>
                                                
                                                
                                                <div class="form-group">
                                                    <label><?php echo lang('page_fl_quotationprovider');?> <span class="required"> * </span></label>
                                                    <?php echo form_input('providercompanynr', isset($quotation['providercompanynr'])?$quotation['providercompanynr']:'', 'class="form-control"');?>
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
                                                    <?php echo form_dropdown('customer', $customers, isset($quotation['customer'])?$quotation['customer']:'', 'class="form-control" id="customer" ');?>
                                                </div>
                                                
                                                <div class="form-group">
                                                    <label><?php echo lang('page_fl_responsible');?> <span class="required"> * </span></label>
                                                    <?php echo form_dropdown('responsible', array(''=>lang('page_option_select')) , '', 'class="form-control" id="responsible" ');?>
                                                </div>
                                                
                                                <div class="form-group">
                                                    <label><?php echo lang('page_fl_recommend');?> </label>
                                                    <?php echo form_dropdown('recommend', $recommends, isset($quotation['recommend'])?$quotation['recommend']:'', 'class="form-control"');?>
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
                                                        <label><?php echo lang('page_fl_currentdiscountlevel');?> <span class="required"> * </span></label>
                                                        <?php echo form_dropdown('currentdiscountlevel', $discountlevels, isset($quotation['currentdiscountlevel'])?$quotation['currentdiscountlevel']:'', 'class="form-control" id="currentdiscountlevel" ');?>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label><?php echo lang('page_fl_newdiscountlevel');?> <span class="required"> * </span></label>
                                                        <?php echo form_dropdown('newdiscountlevel', $discountlevels, isset($quotation['newdiscountlevel'])?$quotation['newdiscountlevel']:'', 'class="form-control" id="newdiscountlevel" ');?>
                                                    </div>
                                                </div>
                                            </div>
                                                
                                            
                                            <div class="portlet-title">
                                                <div class="caption font-dark">
                                                    <span class="caption-subject font-green-sharp sbold"><?php echo lang('page_lb_quotationproducts');?></span>
                                                </div>
                                            </div>
                                            
                                            <div class="form-body">
                                                
                                                <div class="form-group">
                                                    
                                                    <table class="table table-striped table-bordered table-hover dt-responsive" width="100%">    
                                                        <thead>
                                                            <tr role="row" class="heading">                                                                                                        
                                                                <th class="text-nowrap"></th> 
                                                                <th class="text-nowrap"><?php echo lang('page_fl_productenterform');?> <span class="required"> * </span></th>
                                                                <th class="text-nowrap"><?php echo lang('page_fl_mobilenr');?> </th>                                                    
                                                                <th class="text-nowrap"><?php echo lang('page_fl_vvl_neu');?> <span class="required"> * </span></th>
                                                                <th class="text-nowrap"><?php echo lang('page_fl_currentratemobile');?> </th>
                                                                <th class="text-nowrap"><?php echo lang('page_fl_value');?> </th>                                                    
                                                                <th class="text-nowrap"><?php echo lang('page_fl_use');?> </th>
                                                                <th class="text-nowrap"><?php echo lang('page_fl_newratemobile');?> <span class="required"> * </span></th>
                                                                <th class="text-nowrap"><?php echo lang('page_fl_value');?> <span class="required"> * </span></th>                                                    
                                                                <th class="text-nowrap"><?php echo lang('page_fl_endofcontract');?> <span class="required"> * </span></th>
                                                                <th class="text-nowrap"><?php echo lang('page_fl_hardware');?> </th>
                                                            </tr>                                                
                                                        </thead>   
                                                        <tbody id="quotationproduct_inputbox">
                                                        <?php
                                                        if(isset($quotationproducts) && count($quotationproducts)>0){
                                                            
                                                            $data_hidden = array('type'=>'hidden', 'name'=>'count_quotationproduct', 'id'=>'count_quotationproduct', 'value'=>isset($quotationproducts)?count($quotationproducts):1);  
                                                            echo form_input($data_hidden);
                                                                            
                                                            foreach($quotationproducts as $pkey=>$quotationproduct){
                                                                ?>
                                                                <!-- ROW -->
                                                                <tr id="row1_old_quotationproduct_<?php echo $quotationproduct['id'];?>">
                                                                    <td class="text-center">
                                                                        <?php
                                                                        if($pkey==(count($quotationproducts)-1)){
                                                                            ?>
                                                                            <a href="javascript:void(0);" class="btn btn-sm btn-default btn-circle btn-editable green addquotationproduct"><i class="fa fa-plus"></i></a>
                                                                            <?php
                                                                        }
                                                                        else{
                                                                            ?>
                                                                            <a href="javascript:void(0);" class="btn btn-sm btn-default btn-circle btn-editable red" onClick="deletequotationproduct('<?php echo $quotationproduct['id'];?>','old')"><i class="fa fa-minus"></i></a>
                                                                            <?php
                                                                        }
                                                                        ?>    
                                                                    </td>
                                                                    <td class="text-nowrap">
                                                                        <?php echo form_hidden('quotationproductid['.$pkey.']', $quotationproduct['id']);?>
                                                                        
                                                                        <?php 
                                                                        //echo form_hidden('old_formula_'.$quotationproduct['id'], $quotationproduct['formula']);
                                                                        //lang('page_lb_'.($quotationproduct['formula']=='M')?'manual':'auto');
                                                                        ?>
                                                                        
                                                                        
                                                                        <?php 
                                                                        $data = array(
                                                                            'id' => 'old_active_auto_'.$pkey,
                                                                            'name' => 'old_formula_'.$pkey,
                                                                            'value' => 'A',
                                                                            'class' => 'formula',
                                                                            'datarow' => $pkey,                                                                                
                                                                            'datatype' => 'old',
                                                                            'checked' => true
                                                                        );
                                                                        echo form_label(form_radio($data)." ".lang('page_lb_auto'),"old_active_auto_".$pkey);

                                                                        echo '<br />';

                                                                        $data = array(
                                                                            'id' => 'old_active_manual_'.$pkey,
                                                                            'name' => 'old_formula_'.$pkey,
                                                                            'value' => 'M',
                                                                            'class' => 'formula',
                                                                            'datarow' => $pkey,                                                                                
                                                                            'datatype' => 'old',
                                                                            'checked' => ($quotationproduct['formula']=='M')?true:false
                                                                        );
                                                                        echo form_label(form_radio($data)." ".lang('page_lb_manual'),"old_active_manual_".$pkey);
                                                                        
                                                                        $formula = ($quotationproduct['formula']=='M')?'M':'A';
                                                                        ?>

                                                                        
                                                                    </td>
                                                                    <td><?php echo form_input('mobilenr['.$pkey.']', $quotationproduct['mobilenr'], 'class="form-control noerror"');?></td>
                                                                    <td><?php echo form_dropdown('vvlneu['.$pkey.']', $vvlneu, $quotationproduct['vvlneu'], 'class="form-control"');?></td>
                                                                    <td id="old_currentratemobile_box_<?php echo $pkey;?>"><?php if($formula=='A'){ echo form_dropdown('currentratemobile['.$pkey.']', $mobilerates, $quotationproduct['currentratemobile'], 'class="form-control currentratemobile noerror" id="old_currentratemobile_'.$pkey.'" datarow="'.$pkey.'" datatype="old" '); }else{
                                                                        echo form_input('currentratemobile['.$pkey.']', $quotationproduct['currentratemobile'], 'class="form-control noerror" id="old_currentratemobile_'.$pkey.'" ');
                                                                    }?></td>
                                                                    <td><?php echo form_input('value1['.$pkey.']', $quotationproduct['value1'], 'class="form-control noerror" id="old_value1_'.$pkey.'" ');?></td>
                                                                    <td><?php echo form_input('use['.$pkey.']', $quotationproduct['use'], 'class="form-control noerror"');?></td>
                                                                    <td id="old_newratemobile_box_<?php echo $pkey;?>"><?php if($formula=='A'){ echo form_dropdown('newratemobile['.$pkey.']', $mobilerates, $quotationproduct['newratemobile'], 'class="form-control newratemobile" id="old_newratemobile_'.$pkey.'" datarow="'.$pkey.'" datatype="old" '); }else{
                                                                        echo form_input('newratemobile['.$pkey.']', $quotationproduct['newratemobile'], 'class="form-control" id="old_newratemobile_'.$pkey.'" ');
                                                                    }?></td>
                                                                    <td><?php echo form_input('value2['.$pkey.']', $quotationproduct['value2'], 'class="form-control" id="old_value2_'.$pkey.'" ');?></td>
                                                                    <td>
                                                                        <div class="input-group date form_date">
                                                                            <?php $dd = array('name'=>'endofcontract['.$pkey.']', 'class'=>'form-control', 'readonly'=>true, 'size'=>16, 'value'=> _d($quotationproduct['endofcontract']));
                                                                            echo form_input($dd);?>  

                                                                            <span class="input-group-btn">
                                                                                <button class="btn default date-set" type="button">
                                                                                    <i class="fa fa-calendar"></i>
                                                                                </button>
                                                                            </span>
                                                                        </div>
                                                                    </td>
                                                                    <td><?php echo form_dropdown('hardware['.$pkey.']', $hardwares, $quotationproduct['hardware'], 'class="form-control quotation_hardware noerror" datarow="'.$pkey.'" ');?></td>
                                                                </tr>
                                                                <tr id="row2_old_quotationproduct_<?php echo $quotationproduct['id'];?>">
                                                                    <td></td>
                                                                    <td></td>
                                                                    <td></td>
                                                                    <td></td>
                                                                    <td id="old_currentoptionmobile_box_<?php echo $pkey;?>"><?php if($formula=='A'){ echo form_dropdown('currentoptionmobile['.$pkey.']', $mobileoptions, $quotationproduct['currentoptionmobile'], 'class="form-control currentoptionmobile noerror" id="old_currentoptionmobile_'.$pkey.'" datarow="'.$pkey.'" datatype="old" '); }else{
                                                                        echo form_input('currentoptionmobile['.$pkey.']', $quotationproduct['currentoptionmobile'], 'class="form-control noerror" id="old_currentoptionmobile_'.$pkey.'" ');
                                                                    }?></td>
                                                                    <td><?php echo form_input('value3['.$pkey.']', $quotationproduct['value3'], 'class="form-control noerror" id="old_value3_'.$pkey.'" ');?></td>
                                                                    <td></td>
                                                                    <td id="old_newoptionmobile_box_<?php echo $pkey;?>"><?php if($formula=='A'){ echo form_dropdown('newoptionmobile['.$pkey.']', $mobileoptions, $quotationproduct['newoptionmobile'], 'class="form-control newoptionmobile noerror" id="old_newoptionmobile_'.$pkey.'" datarow="'.$pkey.'" datatype="old" '); }else{
                                                                        echo form_input('newoptionmobile['.$pkey.']', $quotationproduct['newoptionmobile'], 'class="form-control noerror" id="old_newoptionmobile_'.$pkey.'" ');
                                                                    }?></td>
                                                                    <td><?php echo form_input('value4['.$pkey.']', $quotationproduct['value4'], 'class="form-control noerror" id="old_value4_'.$pkey.'" ');?></td>
                                                                    <td colspan="2">                                                                        
                                                                        <?php
                                                                        $simcard_function_id = isset($quotationproduct['simcard_function_id'])?$quotationproduct['simcard_function_id']:'0';
                                                                        $simcard_function_nm = isset($quotationproduct['simcard_function_nm'])?$quotationproduct['simcard_function_nm']:'0';
                                                                        $simcard_function_qty = isset($quotationproduct['simcard_function_qty'])?$quotationproduct['simcard_function_qty']:'0';
                                                                        ?>
                                                                        <div id="old_simcard_function_<?php echo $pkey;?>"><input type="hidden" name="simcard_function_id[<?php echo $pkey;?>]" value="<?php echo $simcard_function_id;?>" /><input type="hidden" name="simcard_function_nm[<?php echo $pkey;?>]" value="<?php echo $simcard_function_nm;?>" /><input type="hidden" name="simcard_function_qty[<?php echo $pkey;?>]" value="<?php echo $simcard_function_qty;?>" /></div>
                                                                    </td>
                                                                </tr>
                                                                <!-- END ROW -->   
                                                                
                                                                <?php
                                                                if($formula=='A' && $quotationproduct['simcard_function_id']!="0"){
                                                                    ?>
                                                                    <script>
                                                                    jQuery.ajax({url: '<?php echo base_url('admin/ratesmobile/getoldInputSimcardFunction/'.$pkey.'/'.$quotationproduct['simcard_function_id'].'/'.$quotationproduct['simcard_function_nm'].'/'.$quotationproduct['simcard_function_qty']);?>', success: function(result){        
                                                                        jQuery('#old_simcard_function_<?php echo $pkey;?>').html(result);
                                                                    }}); 
                                                                    </script>    
                                                                    <?php
                                                                }
                                                            }
                                                        }
                                                        else{
                                                            ?>
                                                            
                                                            <?php $data_hidden = array('type'=>'hidden', 'name'=>'count_quotationproduct', 'id'=>'count_quotationproduct', 'value'=>isset($quotation['mobilenr'])?count($quotation['mobilenr']):1);  
                                                            echo form_input($data_hidden);?>
                                                            
                                                            
                                                            <!-- ROW -->
                                                            <tr>
                                                                <td class="text-center"><a href="javascript:void(0);" class="btn btn-sm btn-default btn-circle btn-editable green addquotationproduct"><i class="fa fa-plus"></i></a></td>
                                                                <td class="text-nowrap">
                                                                    <?php 
                                                                    $data = array(
                                                                        'id' => 'new_active_auto_0',
                                                                        'name' => 'new_formula_0',
                                                                        'value' => 'A',
                                                                        'class' => 'formula',
                                                                        'datarow' => '0',                                                                                
                                                                        'datatype' => 'new',
                                                                        'checked' => true
                                                                    );
                                                                    echo form_label(form_radio($data)." ".lang('page_lb_auto'),"new_active_auto_0");
                                                                    
                                                                    echo '<br />';

                                                                    $data = array(
                                                                        'id' => 'new_active_manual_0',
                                                                        'name' => 'new_formula_0',
                                                                        'value' => 'M',
                                                                        'class' => 'formula',
                                                                        'datarow' => '0',                                                                                
                                                                        'datatype' => 'new',
                                                                        'checked' => (isset($quotation['new_formula_0']) && $quotation['new_formula_0']=='M')?true:false
                                                                    );
                                                                    echo form_label(form_radio($data)." ".lang('page_lb_manual'),"new_active_manual_0");
                                                                    
                                                                    $formula = (isset($quotation['new_formula_0']) && $quotation['new_formula_0']=='M')?'M':'A';
                                                                    ?>
                                                                </td>
                                                                <td><?php echo form_input('mobilenr[0]', isset($quotation['mobilenr'][0])?$quotation['mobilenr'][0]:'', 'class="form-control noerror"  ');?></td>
                                                                <td><?php echo form_dropdown('vvlneu[0]', $vvlneu, isset($quotation['vvlneu'][0])?$quotation['vvlneu'][0]:'', 'class="form-control"  ');?></td>
                                                                <td id="new_currentratemobile_box_0"><?php if($formula=='A'){ echo form_dropdown('currentratemobile[0]', $mobilerates, isset($quotation['currentratemobile'][0])?$quotation['currentratemobile'][0]:'', 'class="form-control currentratemobile noerror" id="new_currentratemobile_0" datarow="0" datatype="new"  '); }else{
                                                                        echo form_input('currentratemobile[0]', isset($quotation['currentratemobile'][0])?$quotation['currentratemobile'][0]:'', 'class="form-control noerror" id="new_currentratemobile_0"  ');
                                                                }?></td>
                                                                <td><?php echo form_input('value1[0]', isset($quotation['value1'][0])?$quotation['value1'][0]:'', 'class="form-control noerror" id="new_value1_0"  ');?></td>
                                                                <td><?php echo form_input('use[0]', isset($quotation['use'][0])?$quotation['use'][0]:'', 'class="form-control noerror"');?></td>
                                                                <td id="new_newratemobile_box_0"><?php if($formula=='A'){ echo form_dropdown('newratemobile[0]', $mobilerates, isset($quotation['newratemobile'][0])?$quotation['newratemobile'][0]:'', 'class="form-control newratemobile" id="new_newratemobile_0" datarow="0" datatype="new"  '); }else{
                                                                        echo form_input('newratemobile[0]', isset($quotation['newratemobile'][0])?$quotation['newratemobile'][0]:'', 'class="form-control" id="new_newratemobile_0"  ');
                                                                }?></td>
                                                                <td><?php echo form_input('value2[0]', isset($quotation['value2'][0])?$quotation['value2'][0]:'', 'class="form-control" id="new_value2_0"  ');?></td>
                                                                <td>
                                                                    <div class="input-group date form_date">
                                                                        <?php $dd = array('name'=>'endofcontract[0]', 'class'=>'form-control', 'readonly'=>true, 'size'=>16, 'value'=> isset($quotation['endofcontract'][0])?$quotation['endofcontract'][0]:'');
                                                                        echo form_input($dd);?>  

                                                                        <span class="input-group-btn">
                                                                            <button class="btn default date-set" type="button">
                                                                                <i class="fa fa-calendar"></i>
                                                                            </button>
                                                                        </span>
                                                                    </div>
                                                                </td>
                                                                <td><?php echo form_dropdown('hardware[0]', $hardwares, isset($quotation['hardware'][0])?$quotation['hardware'][0]:'', 'class="form-control quotation_hardware noerror" datarow="0" ');?></td>
                                                            </tr>
                                                            <tr>
                                                                <td></td>
                                                                <td></td>
                                                                <td></td>
                                                                <td></td>
                                                                <td id="new_currentoptionmobile_box_0"><?php if($formula=='A'){ echo form_dropdown('currentoptionmobile[0]', $mobileoptions, isset($quotation['currentoptionmobile'][0])?$quotation['currentoptionmobile'][0]:'', 'class="form-control currentoptionmobile noerror" id="new_currentoptionmobile_0" datarow="0" datatype="new"  '); }else{
                                                                    echo form_input('currentoptionmobile[0]', isset($quotation['currentoptionmobile'][0])?$quotation['currentoptionmobile'][0]:'', 'class="form-control noerror" id="new_currentoptionmobile_0"  ');
                                                                }?></td>
                                                                <td><?php echo form_input('value3[0]', isset($quotation['value3'][0])?$quotation['value3'][0]:'', 'class="form-control noerror" id="new_value3_0"  ');?></td>
                                                                <td></td>
                                                                <td id="new_newoptionmobile_box_0"><?php if($formula=='A'){ echo form_dropdown('newoptionmobile[0]', $mobileoptions, isset($quotation['newoptionmobile'][0])?$quotation['newoptionmobile'][0]:'', 'class="form-control newoptionmobile noerror" id="new_newoptionmobile_0" datarow="0" datatype="new" '); }else{
                                                                    echo form_input('newoptionmobile[0]', isset($quotation['newoptionmobile'][0])?$quotation['newoptionmobile'][0]:'', 'class="form-control noerror" id="new_newoptionmobile_0"  ');
                                                                }?></td>
                                                                <td><?php echo form_input('value4[0]', isset($quotation['value4'][0])?$quotation['value4'][0]:'', 'class="form-control noerror" id="new_value4_0"  ');?></td>
                                                                <td colspan="2">
                                                                    <?php
                                                                    $simcard_function_id = isset($quotation['simcard_function_id'][0])?$quotation['simcard_function_id'][0]:'0';
                                                                    $simcard_function_nm = isset($quotation['simcard_function_nm'][0])?$quotation['simcard_function_nm'][0]:'0';
                                                                    $simcard_function_qty = isset($quotation['simcard_function_qty'][0])?$quotation['simcard_function_qty'][0]:'0';
                                                                    ?>
                                                                    <div id="new_simcard_function_0"><input type="hidden" name="simcard_function_id[0]" value="<?php echo $simcard_function_id;?>" /><input type="hidden" name="simcard_function_nm[0]" value="<?php echo $simcard_function_nm;?>" /><input type="hidden" name="simcard_function_qty[0]" value="<?php echo $simcard_function_qty;?>" /></div>
                                                                </td>
                                                            </tr>
                                                            <!-- END ROW -->
                                                            
                                                            <?php
                                                            if($formula=='A' && $simcard_function_id!="0"){
                                                                ?>
                                                                <script>
                                                                jQuery.ajax({url: '<?php echo base_url('admin/ratesmobile/getoldInputSimcardFunction/0/'.$simcard_function_id.'/'.$simcard_function_nm.'/'.$simcard_function_qty);?>', success: function(result){        
                                                                    jQuery('#new_simcard_function_0').html(result);
                                                                }}); 
                                                                </script>                                                                
                                                                <?php
                                                            }
                                                            
                                                            if(isset($quotation['mobilenr']) && count($quotation['mobilenr'])>0){
                                                                foreach($quotation['mobilenr'] as $pkey=>$quotationproduct){
                                                                    if($pkey==0){ continue; }
                                                                    ?>
                                                                    <!-- ROW -->
                                                                    <tr id="row1_new_quotationproduct_<?php echo $pkey;?>">
                                                                        <td class="text-center"><a href="javascript:void(0);" class="btn btn-sm btn-default btn-circle btn-editable red" onClick="deletequotationproduct('<?php echo $pkey;?>','new')"><i class="fa fa-minus"></i></a></td>
                                                                        <td class="text-nowrap">
                                                                            <?php 
                                                                            $data = array(
                                                                                'id' => 'new_active_auto_'.$pkey,
                                                                                'name' => 'new_formula_'.$pkey,
                                                                                'value' => 'A',
                                                                                'class' => 'formula',
                                                                                'datarow' => $pkey,                                                                                
                                                                                'datatype' => 'new',
                                                                                'checked' => true
                                                                            );
                                                                            echo form_label(form_radio($data)." ".lang('page_lb_auto'),"new_active_auto_".$pkey);

                                                                            echo '<br />';

                                                                            $data = array(
                                                                                'id' => 'new_active_manual_'.$pkey,
                                                                                'name' => 'new_formula_'.$pkey,
                                                                                'value' => 'M',
                                                                                'class' => 'formula',                                                                                
                                                                                'datarow' => $pkey,                                                                                
                                                                                'datatype' => 'new',                                                                                
                                                                                'checked' => (isset($quotation['new_formula_'.$pkey]) && $quotation['new_formula_'.$pkey])=='M'?true:false
                                                                            );
                                                                            echo form_label(form_radio($data)." ".lang('page_lb_manual'),"new_active_manual_".$pkey);
                                                                            
                                                                            $formula = (isset($quotation['new_formula_'.$pkey]) && $quotation['new_formula_'.$pkey])=='M'?'M':'A';
                                                                            ?>
                                                                        </td>
                                                                        <td><?php echo form_input('mobilenr['.$pkey.']', isset($quotation['mobilenr'][$pkey])?$quotation['mobilenr'][$pkey]:'', 'class="form-control noerror"  ');?></td>
                                                                        <td><?php echo form_dropdown('vvlneu['.$pkey.']', $vvlneu, isset($quotation['vvlneu'][$pkey])?$quotation['vvlneu'][$pkey]:'', 'class="form-control"  ');?></td>
                                                                        <td id="new_currentratemobile_box_<?php echo $pkey;?>"><?php if($formula=='A'){ echo form_dropdown('currentratemobile['.$pkey.']', $mobilerates, isset($quotation['currentratemobile'][$pkey])?$quotation['currentratemobile'][$pkey]:'', 'class="form-control currentratemobile noerror" id="new_currentratemobile_'.$pkey.'" datarow="'.$pkey.'" datatype="new" '); }else{
                                                                            echo form_input('currentratemobile['.$pkey.']', isset($quotation['currentratemobile'][$pkey])?$quotation['currentratemobile'][$pkey]:'', 'class="form-control noerror" id="new_currentratemobile_'.$pkey.'"  ');
                                                                        }?></td>
                                                                        <td><?php echo form_input('value1['.$pkey.']', isset($quotation['value1'][$pkey])?$quotation['value1'][$pkey]:'', 'class="form-control noerror" id="new_value1_'.$pkey.'"  ');?></td>
                                                                        <td><?php echo form_input('use['.$pkey.']', isset($quotation['use'][$pkey])?$quotation['use'][$pkey]:'', 'class="form-control noerror"  ');?></td>
                                                                        <td id="new_newratemobile_box_<?php echo $pkey;?>"><?php if($formula=='A'){ echo form_dropdown('newratemobile['.$pkey.']', $mobilerates, isset($quotation['newratemobile'][$pkey])?$quotation['newratemobile'][$pkey]:'', 'class="form-control newratemobile" id="new_newratemobile_'.$pkey.'" datarow="'.$pkey.'" datatype="new" '); }else{
                                                                            echo form_input('newratemobile['.$pkey.']', isset($quotation['newratemobile'][$pkey])?$quotation['newratemobile'][$pkey]:'', 'class="form-control" id="new_newratemobile_'.$pkey.'"  ');
                                                                        }?></td>
                                                                        <td><?php echo form_input('value2['.$pkey.']', isset($quotation['value2'][$pkey])?$quotation['value2'][$pkey]:'', 'class="form-control"  id="new_value2_'.$pkey.'"  ');?></td>
                                                                        <td>
                                                                            <div class="input-group date form_date">
                                                                                <?php $dd = array('name'=>'endofcontract['.$pkey.']', 'class'=>'form-control', 'readonly'=>true, 'size'=>16, 'value'=> isset($quotation['endofcontract'][$pkey])?$quotation['endofcontract'][$pkey]:'');
                                                                                echo form_input($dd);?>  

                                                                                <span class="input-group-btn">
                                                                                    <button class="btn default date-set" type="button">
                                                                                        <i class="fa fa-calendar"></i>
                                                                                    </button>
                                                                                </span>
                                                                            </div>
                                                                        </td>
                                                                        <td><?php echo form_dropdown('hardware['.$pkey.']', $hardwares, isset($quotation['hardware'][$pkey])?$quotation['hardware'][$pkey]:'', 'class="form-control quotation_hardware noerror" datarow="'.$pkey.'" ');?></td>
                                                                    </tr>
                                                                    <tr id="row2_new_quotationproduct_<?php echo $pkey;?>">
                                                                        <td></td>
                                                                        <td></td>
                                                                        <td></td>
                                                                        <td></td>
                                                                        <td id="new_currentoptionmobile_box_<?php echo $pkey;?>"><?php if($formula=='A'){ echo form_dropdown('currentoptionmobile['.$pkey.']', $mobileoptions, isset($quotation['currentoptionmobile'][$pkey])?$quotation['currentoptionmobile'][$pkey]:'', 'class="form-control currentoptionmobile noerror" id="new_currentoptionmobile_'.$pkey.'" datarow="'.$pkey.'" datatype="new"  '); }else{
                                                                            echo form_input('currentoptionmobile['.$pkey.']', isset($quotation['currentoptionmobile'][$pkey])?$quotation['currentoptionmobile'][$pkey]:'', 'class="form-control noerror" id="new_currentoptionmobile_'.$pkey.'"  ');
                                                                        }?></td>
                                                                        <td><?php echo form_input('value3['.$pkey.']', isset($quotation['value3'][$pkey])?$quotation['value3'][$pkey]:'', 'class="form-control noerror" id="new_value3_'.$pkey.'"  ');?></td>
                                                                        <td></td>
                                                                        <td id="new_newoptionmobile_box_<?php echo $pkey;?>"><?php if($formula=='A'){ echo form_dropdown('newoptionmobile['.$pkey.']', $mobileoptions, isset($quotation['newoptionmobile'][$pkey])?$quotation['newoptionmobile'][$pkey]:'', 'class="form-control newoptionmobile noerror" id="new_newoptionmobile_'.$pkey.'" datarow="'.$pkey.'" datatype="new"  '); }else{
                                                                            echo form_input('newoptionmobile['.$pkey.']', isset($quotation['newoptionmobile'][$pkey])?$quotation['newoptionmobile'][$pkey]:'', 'class="form-control noerror" id="new_newoptionmobile_'.$pkey.'"  ');
                                                                        }?></td>
                                                                        <td><?php echo form_input('value4['.$pkey.']', isset($quotation['value4'][$pkey])?$quotation['value4'][$pkey]:'', 'class="form-control noerror" id="new_value4_'.$pkey.'"  ');?></td>
                                                                        <td colspan="2">
                                                                            <?php
                                                                            $simcard_function_id = isset($quotation['simcard_function_id'][$pkey])?$quotation['simcard_function_id'][$pkey]:'0';
                                                                            $simcard_function_nm = isset($quotation['simcard_function_nm'][$pkey])?$quotation['simcard_function_nm'][$pkey]:'0';
                                                                            $simcard_function_qty = isset($quotation['simcard_function_qty'][$pkey])?$quotation['simcard_function_qty'][$pkey]:'0';                                                                        
                                                                            ?>
                                                                            <div id="new_simcard_function_<?php echo $pkey;?>"><input type="hidden" name="simcard_function_id[<?php echo $pkey;?>]" value="<?php echo $simcard_function_id;?>" /><input type="hidden" name="simcard_function_nm[<?php echo $pkey;?>]" value="<?php echo $simcard_function_nm;?>" /><input type="hidden" name="simcard_function_qty[<?php echo $pkey;?>]" value="<?php echo $simcard_function_qty;?>" /></div>
                                                                        </td>
                                                                    </tr>
                                                                    <!-- END ROW -->
                                                                    
                                                                    <?php
                                                                    if($formula=='A' && $simcard_function_id!="0"){                                                                        
                                                                        ?>
                                                                        <script>
                                                                        jQuery.ajax({url: '<?php echo base_url('admin/ratesmobile/getoldInputSimcardFunction/'.$pkey.'/'.$simcard_function_id.'/'.$simcard_function_nm.'/'.$simcard_function_qty);?>', success: function(result){        
                                                                            jQuery('#new_simcard_function_<?php echo $pkey;?>').html(result);
                                                                        }}); 
                                                                        </script>
                                                                        <?php
                                                                    }
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
                                                    <a href="<?php echo base_url('admin/quotations')?>"><button type="button" class="btn default"><?php echo lang('cancel');?></button></a>
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
                                if(isset($quotation['quotationnr']) && $quotation['quotationnr']>0){
                                    $this->load->view('admin/quotations/tab-document', array('quotation'=>$quotation,'categories'=>$categories)); 
                                }
                                ?>
                                
                            </div>
                            
                            <div class="tab-pane" id="tab_reminder" style="display:<?php echo $tab_reminder;?>">
                                
                                <?php 
                                if(isset($quotation['quotationnr']) && $quotation['quotationnr']>0){ 
                                    $this->load->view('admin/quotations/tab-reminder', array('quotation'=>$quotation)); 
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
    var form_id = 'form_quotation'; 
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
                quotationdate: {                    
                    required: true
                },                
                quotationstatus: {                        
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
                currentdiscountlevel: {                        
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
jQuery('.addquotationproduct').click( function(){    
    var rownum = parseInt(jQuery('#count_quotationproduct').val()) + 1;        
    var inputhtml = '<tr id="row1_new_quotationproduct_'+rownum+'">';    
    inputhtml = inputhtml + '<td class="text-center"><a href="javascript:void(0);" class="btn btn-sm btn-default btn-circle btn-editable red" onClick="deletequotationproduct(\''+rownum+'\',\'new\')"><i class="fa fa-minus"></i></a></td>';
    inputhtml = inputhtml + '<td class="text-nowrap">';            
    inputhtml = inputhtml + '<label for="new_active_auto_'+rownum+'"><input type="radio" name="new_formula_'+rownum+'" value="A" class="formula" checked="checked" id="new_active_auto_'+rownum+'" datarow="'+rownum+'" datatype="new" /> <?php echo lang('page_lb_auto');?></label><br /><label for="new_active_manual_'+rownum+'"><input type="radio" name="new_formula_'+rownum+'" value="M" class="formula" id="new_active_manual_'+rownum+'" datarow="'+rownum+'" datatype="new" /> <?php echo lang('page_lb_manual');?></label>';        
    inputhtml = inputhtml + '</td>';
    inputhtml = inputhtml + '<td><input type="text" name="mobilenr['+rownum+']" value=""  class="form-control noerror" /></td>';
    inputhtml = inputhtml + '<td><select name="vvlneu['+rownum+']" class="form-control">';           
    <?php
    foreach($vvlneu as $k=>$v){
        ?>
        inputhtml = inputhtml + "<option value='<?php echo $k;?>'><?php echo $v;?></option>";
        <?php
    }
    ?>    
    inputhtml = inputhtml + '</select></td>';
    inputhtml = inputhtml + '<td id="new_currentratemobile_box_'+rownum+'"><select name="currentratemobile['+rownum+']" class="form-control currentratemobile noerror" id="new_currentratemobile_'+rownum+'" datarow="'+rownum+'" datatype="new" >';
    <?php
    foreach($mobilerates as $k=>$v){
        if($k==0){ $v = lang("page_lb_select_mobile_rate"); }
        ?>
        inputhtml = inputhtml + "<option value='<?php echo $k;?>'><?php echo $v;?></option>";
        <?php
    }
    ?>              
    inputhtml = inputhtml + '</select></td>';
    inputhtml = inputhtml + '<td><input type="text" name="value1['+rownum+']" value=""  class="form-control noerror" id="new_value1_'+rownum+'" /></td>';
    inputhtml = inputhtml + '<td><input type="text" name="use['+rownum+']" value=""  class="form-control noerror" /></td>';
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
    
    inputhtml = inputhtml + '<td><div class="input-group date form_date"><input type="text" name="endofcontract['+rownum+']" value="" class="form-control" readonly="1" size="16" >';
    inputhtml = inputhtml + '<span class="input-group-btn"><button class="btn default date-set" type="button"><i class="fa fa-calendar"></i></button></span></div></td>'; 
    
    inputhtml = inputhtml + '<td><select name="hardware['+rownum+']" class="form-control quotation_hardware noerror" datarow="'+rownum+'">';
    <?php
    foreach($hardwares as $k=>$v){
        ?>
        inputhtml = inputhtml + "<option value='<?php echo $k;?>'><?php echo $v;?></option>";
        <?php
    }
    ?>
    inputhtml = inputhtml + '</select></td>';        
    inputhtml = inputhtml + '</tr>';    
    inputhtml = inputhtml + '<tr id="row2_new_quotationproduct_'+rownum+'">';
    inputhtml = inputhtml + '<td></td>';
    inputhtml = inputhtml + '<td></td>';
    inputhtml = inputhtml + '<td></td>';
    inputhtml = inputhtml + '<td></td>';
    inputhtml = inputhtml + '<td id="new_currentoptionmobile_box_'+rownum+'"><select name="currentoptionmobile['+rownum+']" class="form-control currentoptionmobile noerror" id="new_currentoptionmobile_'+rownum+'" datarow="'+rownum+'" datatype="new" >';
    <?php
    foreach($mobileoptions as $k=>$v){
        if($k==0){ $v = lang("page_lb_select_mobile_option"); }
        ?>
        inputhtml = inputhtml + "<option value='<?php echo $k;?>'><?php echo $v;?></option>";
        <?php
    }
    ?>
    inputhtml = inputhtml + '</select></td>';   
    inputhtml = inputhtml + '<td><input type="text" name="value3['+rownum+']" value=""  class="form-control noerror" id="new_value3_'+rownum+'" /></td>';
    inputhtml = inputhtml + '<td></td>';
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
    inputhtml = inputhtml + '<td colspan="2"><div id="new_simcard_function_'+rownum+'"><input type="hidden" name="simcard_function_id['+rownum+']" value="0" /><input type="hidden" name="simcard_function_nm['+rownum+']" value="0" /><input type="hidden" name="simcard_function_qty['+rownum+']" value="0" /></div></td>';
    inputhtml = inputhtml + '</tr>';
    
    jQuery('#quotationproduct_inputbox').prepend(inputhtml);
    jQuery('#count_quotationproduct').val(rownum);    
    jQuery('[data-toggle="tooltip"]').tooltip();
    
    changenewRateMobile();
    changecurrentRateMobile();    
    changenewOptionMobile();
    changecurrentOptionMobile(); 
    changeFormula();
    changeHardware();
    datapicker();
    
    App.scrollTo(jQuery('#quotationproduct_inputbox'), 1);
});
jQuery('#form_quotation').on('submit', function(e) {
    extraFieldsValidate();
});
jQuery('#currentdiscountlevel').change( function(){     
    jQuery('#quotationproduct_inputbox').find('select').each(function() {
        //if(jQuery(this).attr('class')=='form-control currentratemobile noerror'){              
        
        if(jQuery(this).hasClass("currentratemobile")){    
            var rown = jQuery(this).attr('datarow');   
            var rowt = jQuery(this).attr('datatype');      
            changecurrentRateMobileDiscountLevel(rown,rowt);
        }
    });    
});
jQuery('#newdiscountlevel').change( function(){    
    jQuery('#quotationproduct_inputbox').find('select').each(function() {
        //if(jQuery(this).attr('class')=='form-control newratemobile'){
        
        if(jQuery(this).hasClass("newratemobile")){ 
            var rown = jQuery(this).attr('datarow');   
            var rowt = jQuery(this).attr('datatype');      
            changenewRateMobileDiscountLevel(rown,rowt);
        }
    }); 
});
function extraFieldsValidate1(){
    var isValid = true;
    
    jQuery('#quotationproduct_inputbox select').each(function() {
        if($(this).val() == "" && $(this).val().length < 1) {
            $(this).addClass('field_error');
            isValid = false;
        } else {
            $(this).removeClass('field_error');
        }
    });  

    jQuery('#quotationproduct_inputbox input').each(function() {
        if(jQuery(this).attr('type')!='radio'){
            if($(this).val() == "" && $(this).val().length < 1) {
                $(this).addClass('field_error');
                isValid = false;
            } else {
                $(this).removeClass('field_error');
            }
        }
    }); 
    
    return isValid;
}

function changeFormula(){
    jQuery('.formula').change( function(){   
        var rown = jQuery(this).attr('datarow');   
        var rowt = jQuery(this).attr('datatype');     
        var formula = jQuery('input[name='+rowt+'_formula_'+rown+']:checked').val(); 
        
        if(formula=='A'){
            
            //Current Rate Mobile
            selecthtml ='<select name="currentratemobile['+rown+']" class="form-control currentratemobile noerror" id="'+rowt+'_currentratemobile_'+rown+'" datarow="'+rown+'" datatype="'+rowt+'" >';
            <?php
            foreach($mobilerates as $k=>$v){
                ?>
                selecthtml = selecthtml + "<option value='<?php echo $k;?>'><?php echo $v;?></option>";
                <?php
            }
            ?>              
            selecthtml = selecthtml + '</select>';
            jQuery('#'+rowt+'_currentratemobile_box_'+rown).html(selecthtml);
            
            
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
            
            
            //Current Option Mobile
            selecthtml ='<select name="currentoptionmobile['+rown+']" class="form-control currentoptionmobile noerror" id="'+rowt+'_currentoptionmobile_'+rown+'" datarow="'+rown+'" datatype="'+rowt+'" >';
            <?php
            foreach($mobileoptions as $k=>$v){
                ?>                          
                selecthtml = selecthtml + "<option value='<?php echo $k;?>'><?php echo $v;?></option>";
                <?php
            }
            ?>           
            selecthtml = selecthtml + '</select>';            
            jQuery('#'+rowt+'_currentoptionmobile_box_'+rown).html(selecthtml);        
            
            
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
            changecurrentRateMobile();    
            changenewOptionMobile();
            changecurrentOptionMobile(); 
            
        }else{            
            //Current Rate Mobile
            jQuery('#'+rowt+'_currentratemobile_box_'+rown).html('<input type="text" name="currentratemobile['+rown+']" class="form-control noerror" id="'+rowt+'_currentratemobile_'+rown+'">');
            jQuery('#'+rowt+'_value1_'+rown).val('');
            
            //New Rate Mobile
            jQuery('#'+rowt+'_newratemobile_box_'+rown).html('<input type="text" name="newratemobile['+rown+']" class="form-control" id="'+rowt+'_newratemobile_'+rown+'">');
            jQuery('#'+rowt+'_value2_'+rown).val('');
            
            //Current Option Mobile
            jQuery('#'+rowt+'_currentoptionmobile_box_'+rown).html('<input type="text" name="currentoptionmobile['+rown+']" class="form-control noerror" id="'+rowt+'_currentoptionmobile_'+rown+'">');
            jQuery('#'+rowt+'_value3_'+rown).val('');
            
            //New Option Mobile
            jQuery('#'+rowt+'_newoptionmobile_box_'+rown).html('<input type="text" name="newoptionmobile['+rown+']" class="form-control noerror" id="'+rowt+'_newoptionmobile_'+rown+'">');
            jQuery('#'+rowt+'_value4_'+rown).val('');
            
            //Simcard Function
            jQuery('#'+rowt+'_simcard_function_'+rown).html('<input type="hidden" name="simcard_function_id['+rown+']" value="0" /><input type="hidden" name="simcard_function_nm['+rown+']" value="0" /><input type="hidden" name="simcard_function_qty['+rown+']" value="0" />');            
        }
    });
}

function changecurrentRateMobile(){
    jQuery('.currentratemobile').change( function(){        
        var rown = jQuery(this).attr('datarow');   
        var rowt = jQuery(this).attr('datatype');             
        
        var discountlevel = jQuery('#currentdiscountlevel').val();
        if(discountlevel==""){ discountlevel="none"; }        
        
        var formula = jQuery('input[name='+rowt+'_formula_'+rown+']:checked').val();     
        var id = jQuery('#'+rowt+'_currentratemobile_'+rown).val();
        
        if(formula=='A'){
            if(id==""){ id="none"; }                            
            jQuery.ajax({url: '<?php echo base_url('admin/quotations/getMobileRateValue/');?>'+id+'/'+discountlevel+'/'+formula, success: function(result){              
                jQuery('#'+rowt+'_value1_'+rown).val(result);
            }});    
        }
    });
}

function changenewRateMobile(){
    jQuery('.newratemobile').change( function(){
        var rown = jQuery(this).attr('datarow');   
        var rowt = jQuery(this).attr('datatype');         
        
        var discountlevel = jQuery('#newdiscountlevel').val();        
        if(discountlevel==""){ discountlevel="none"; }        
        
        var formula = jQuery('input[name='+rowt+'_formula_'+rown+']:checked').val();     
        var id = jQuery('#'+rowt+'_newratemobile_'+rown).val();
        
        if(formula=='A'){
            if(id==""){ id="none"; }  
            
            jQuery.ajax({url: '<?php echo base_url('admin/quotations/getMobileRateValue/');?>'+id+'/'+discountlevel+'/'+formula, success: function(result){        
                jQuery('#'+rowt+'_value2_'+rown).val(result);
            }});   

            jQuery.ajax({url: '<?php echo base_url('admin/ratesmobile/getnewInputSimcardFunction/');?>'+rown+'/'+id, success: function(result){        
                jQuery('#'+rowt+'_simcard_function_'+rown).html(result);
            }}); 
        }
    });
}

function changecurrentOptionMobile(){
    jQuery('.currentoptionmobile').change( function(){
        var rown = jQuery(this).attr('datarow');   
        var rowt = jQuery(this).attr('datatype'); 
        var id = jQuery('#'+rowt+'_currentoptionmobile_'+rown).val();        
        var formula = jQuery('input[name='+rowt+'_formula_'+rown+']:checked').val();
        
        if(formula=='A'){
            jQuery.ajax({url: '<?php echo base_url('admin/quotations/getMobileOptionValue/');?>'+id, success: function(result){        
                jQuery('#'+rowt+'_value3_'+rown).val(result);
            }});   
        }
    });
}

function changenewOptionMobile(){
    jQuery('.newoptionmobile').change( function(){
        var rown = jQuery(this).attr('datarow');   
        var rowt = jQuery(this).attr('datatype'); 
        var id = jQuery('#'+rowt+'_newoptionmobile_'+rown).val();        
        var formula = jQuery('input[name='+rowt+'_formula_'+rown+']:checked').val(); 
        
        if(formula=='A'){
            jQuery.ajax({url: '<?php echo base_url('admin/quotations/getMobileOptionValue/');?>'+id, success: function(result){        
                jQuery('#'+rowt+'_value4_'+rown).val(result);
            }});   
        }
    });
}

function changecurrentRateMobileDiscountLevel(rown,rowt){    
    var discountlevel = jQuery('#currentdiscountlevel').val();
    if(discountlevel==""){ discountlevel="none"; }        

    var formula = jQuery('input[name='+rowt+'_formula_'+rown+']:checked').val();     
    var id = jQuery('#'+rowt+'_currentratemobile_'+rown).val();
    
    if(formula=='A'){
        if(id==""){ id="none"; }   
        jQuery.ajax({url: '<?php echo base_url('admin/quotations/getMobileRateValue/');?>'+id+'/'+discountlevel+'/'+formula, success: function(result){              
            jQuery('#'+rowt+'_value1_'+rown).val(result);
        }}); 
    }
}

function changenewRateMobileDiscountLevel(rown,rowt){
    var discountlevel = jQuery('#newdiscountlevel').val();        
    if(discountlevel==""){ discountlevel="none"; }        

    var formula = jQuery('input[name='+rowt+'_formula_'+rown+']:checked').val();     
    var id = jQuery('#'+rowt+'_newratemobile_'+rown).val();
    
    if(formula=='A'){
        if(id==""){ id="none"; }  
        jQuery.ajax({url: '<?php echo base_url('admin/quotations/getMobileRateValue/');?>'+id+'/'+discountlevel+'/'+formula, success: function(result){        
            jQuery('#'+rowt+'_value2_'+rown).val(result);
        }}); 
    }
}

function deletequotationproduct(dataid,datatype){
    var rownum = jQuery('#count_quotationproduct').val();
    if(datatype=='old'){    
        //Delete record from db by ajax
        deleteConfirmation('<?php echo base_url('admin/quotations/deleteQuotationProduct/');?>',dataid,'<?php echo lang('page_lb_delete_quotationproduct')?>','<?php echo lang('page_lb_delete_quotationproduct_info')?>','true');        
    }
    else{
        jQuery('#row1_new_quotationproduct_'+dataid).remove();
        jQuery('#row2_new_quotationproduct_'+dataid).remove();
    }
    jQuery('#count_quotationproduct').val(rownum);        
}

function changeHardware(){
    jQuery('.quotation_hardware').change( function(){ 
         var current_val = $(this).val();
         var current_rown = $(this).attr('datarow');
         
         var selected_ok = 1;
         jQuery('#quotationproduct_inputbox select').each(function() {
             //if(jQuery(this).attr('class')=='form-control quotation_hardware'){
             
             if(jQuery(this).hasClass("quotation_hardware")){ 
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
changecurrentRateMobile();
changenewOptionMobile();
changecurrentOptionMobile();
changeFormula();
changeHardware();
</script>
<?php $this->load->view('admin/quotations/quotationjs',array('quotation'=>isset($quotation)?$quotation:'', 'remindersubjects'=>$remindersubjects));?>