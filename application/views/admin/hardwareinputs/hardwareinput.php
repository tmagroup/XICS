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
                                <a href="<?php echo base_url('admin/hardwareinputs');?>"><?php echo lang('page_hardwareinputs');?></a>
                                <i class="fa fa-circle"></i>
                            </li> 
                            
                            <li>
                                <span>
                                    <?php
                                    if(isset($hardwareinput['hardwareinputnr']) && $hardwareinput['hardwareinputnr']>0){
                                        echo lang('page_edit_hardwareinput');
                                    }
                                    else
                                    {
                                        echo lang('page_create_hardwareinput');                                
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
                        if(isset($hardwareinput['hardwareinputnr']) && $hardwareinput['hardwareinputnr']>0){
                            ?>
                            <i class="fa fa-pencil"></i>
                            <?php
                            echo lang('page_edit_hardwareinput');
                        }
                        else
                        {
                            ?>
                            <i class="fa fa-plus"></i>
                            <?php
                            echo lang('page_create_hardwareinput');                                
                        }    
                        ?>
                        
                    </h3>
                    <!-- END PAGE TITLE-->
                    <!-- END PAGE HEADER-->
                    
                    
                    <div class="row">
                        
                        <?php echo form_open($this->uri->uri_string(), array('enctype' => "multipart/form-data", 'id' => 'form_hardwareinput') );?>        
                        <div class="col-md-12">


                            <!-- BEGIN SAMPLE FORM PORTLET-->
                            <div class="portlet light bordered">                                
                                <div class="portlet-body form">

                                    <div class="form-body">
                                        
                                        <div class="row">
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label><?php echo lang('page_fl_supplier');?> <span class="required"> * </span></label>
                                                    <?php echo form_dropdown('supplier', $suppliers, isset($hardwareinput['supplier'])?$hardwareinput['supplier']:'', 'class="form-control"');?>
                                                </div>
                                            </div>    
                                        
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label><?php echo lang('page_fl_hardwareinputdate');?> <span class="required"> * </span></label>

                                                    <div class="input-group date form_date">
                                                        <?php $dd = array('name'=>'hardwareinputdate', 'class'=>'form-control', 'readonly'=>true, 'size'=>16, 'value'=> isset($hardwareinput['hardwareinputdate'])?_d($hardwareinput['hardwareinputdate']):date('d.m.Y'));
                                                        echo form_input($dd);?>  

                                                        <span class="input-group-btn">
                                                            <button class="btn default date-set" type="button">
                                                                <i class="fa fa-calendar"></i>
                                                            </button>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>    
                                            
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

                                    <div class="portlet-title">
                                        <div class="caption font-dark">
                                            <span class="caption-subject font-green-sharp sbold"><?php echo lang('page_lb_hardwareinputproducts');?></span>
                                        </div>
                                    </div>

                                    <div class="form-body">

                                        <div class="form-group">
                                            <div class="table-responsive no-dt">
                                                <table class="table table-striped table-bordered table-hover dt-responsive" width="100%">    
                                                    <thead>
                                                        <tr role="row" class="heading">                                                                                                        
                                                            <th class="text-nowrap" width="1%"></th>                                                                 
                                                            <th class="text-nowrap"><?php echo lang('page_fl_hardware');?> <span class="required"> * </span></th>                                                                    
                                                            <th class="text-nowrap"><?php echo lang('page_fl_seriesnr');?> <span class="required"> * </span></th>       
                                                            <th class="text-nowrap"><?php echo lang('page_fl_lampsymbol');?> <span class="required"> * </span></th>       
                                                        </tr>                                                
                                                    </thead>   
                                                    <tbody id="hardwareinputproduct_inputbox">
                                                    <?php
                                                    if(isset($hardwareinputproducts) && count($hardwareinputproducts)>0){

                                                        $data_hidden = array('type'=>'hidden', 'name'=>'count_hardwareinputproduct', 'id'=>'count_hardwareinputproduct', 'value'=>isset($hardwareinputproducts)?count($hardwareinputproducts):1);  
                                                        echo form_input($data_hidden);

                                                        foreach($hardwareinputproducts as $pkey=>$hardwareinputproduct){                                                                    
                                                            ?>
                                                            <!-- ROW -->
                                                            <tr id="row1_old_hardwareinputproduct_<?php echo $hardwareinputproduct['id'];?>">
                                                                <td class="text-center">
                                                                    <?php
                                                                    if($pkey==(count($hardwareinputproducts)-1)){
                                                                    //if($pkey==0){
                                                                        ?>                                                                                                                                                       
                                                                        <a href="javascript:void(0);" class="btn btn-sm btn-default btn-circle btn-editable green addhardwareinputproduct" datarow="<?php echo $hardwareinputproduct['id'];?>" datatype="old" datainit="0"><i class="fa fa-plus"></i></a>
                                                                        <?php
                                                                    }
                                                                    else{
                                                                        ?>
                                                                        <a href="javascript:void(0);" class="btn btn-sm btn-default btn-circle btn-editable red" onClick="deletehardwareinputproduct('<?php echo $hardwareinputproduct['id'];?>','old')"><i class="fa fa-minus"></i></a>
                                                                        <?php
                                                                    }                                                                        

                                                                    echo form_hidden('hardwareinputproductid['.$pkey.']', $hardwareinputproduct['id']);
                                                                    ?>    
                                                                </td>                                                                   
                                                                <td><?php echo form_dropdown('hardware['.$pkey.']', $hardwares, $hardwareinputproduct['hardware'], 'class="form-control" id="old_hardware_'.$pkey.'" ');?></td>                                                                        
                                                                <td><?php echo form_input('seriesnr['.$pkey.']', $hardwareinputproduct['seriesnr'], 'class="form-control"');?></td>                                                                        
                                                                <td>
                                                                    <?php 
                                                                    $data = array(
                                                                        'id' => 'old_quantity0_'.$pkey,
                                                                        'name' => 'old_quantity_'.$pkey,
                                                                        'value' => '0',
                                                                        'class' => 'lampSymbol',
                                                                        'datarow' => '0',                                                                                
                                                                        'datatype' => 'old',
                                                                        'checked' => true
                                                                    );
                                                                    echo form_label(form_radio($data)." <img src='".base_url('assets/pages/img/red.png')."' width='24' />","old_quantity0_".$pkey);

                                                                    $data = array(
                                                                        'id' => 'old_quantity1_'.$pkey,
                                                                        'name' => 'old_quantity_'.$pkey,
                                                                        'value' => '1',
                                                                        'class' => 'lampSymbol',
                                                                        'datarow' => '0',                                                                                
                                                                        'datatype' => 'old',
                                                                        'checked' => (isset($hardwareinputproduct['quantity']) && $hardwareinputproduct['quantity']=='1')?true:false
                                                                    );
                                                                    echo form_label(form_radio($data)." <img src='".base_url('assets/pages/img/green.png')."' width='24' />","old_quantity1_".$pkey);
                                                                    ?>
                                                                </td>
                                                            </tr>
                                                            <!-- END ROW -->   
                                                            <?php
                                                        }
                                                    }
                                                    else{
                                                        ?>

                                                        <?php $data_hidden = array('type'=>'hidden', 'name'=>'count_hardwareinputproduct', 'id'=>'count_hardwareinputproduct', 'value'=>isset($hardwareinput['seriesnr'])?count($hardwareinput['seriesnr']):1);  
                                                        echo form_input($data_hidden);?>

                                                        <!-- ROW -->
                                                        <tr id="row1_new_hardwareinputproduct_0">
                                                            <td class="text-center">
                                                            
                                                                <?php
                                                                if(isset($hardwareinput['seriesnr']) && count($hardwareinput['seriesnr'])>0){
                                                                    ?>
                                                                    <a href="javascript:void(0);" class="btn btn-sm btn-default btn-circle btn-editable red" onClick="deletehardwareinputproduct('0','new')"><i class="fa fa-minus"></i></a>
                                                                    <?php
                                                                }
                                                                else{
                                                                    ?>
                                                                    <a href="javascript:void(0);" class="btn btn-sm btn-default btn-circle btn-editable green addhardwareinputproduct" datarow="0" datatype="new" datainit="0"><i class="fa fa-plus"></i></a>
                                                                    <?php
                                                                }
                                                                ?>
                                                            
                                                            </td>                                                                                                                                    
                                                            <td><?php echo form_dropdown('hardware[0]', $hardwares, isset($hardwareinput['hardware'][0])?$hardwareinput['hardware'][0]:'', 'class="form-control" ');?></td>
                                                            <td><?php echo form_input('seriesnr[0]', isset($hardwareinput['seriesnr'][0])?$hardwareinput['seriesnr'][0]:'', 'class="form-control"  ');?></td>
                                                            <td>
                                                                <?php 
                                                                $data = array(
                                                                    'id' => 'new_quantity0_0',
                                                                    'name' => 'new_quantity_0',
                                                                    'value' => '0',
                                                                    'class' => 'lampSymbol',
                                                                    'datarow' => '0',                                                                                
                                                                    'datatype' => 'new',
                                                                    'checked' => true
                                                                );
                                                                echo form_label(form_radio($data)." <img src='".base_url('assets/pages/img/red.png')."' width='24' />","new_quantity0_0");

                                                                $data = array(
                                                                    'id' => 'new_quantity1_0',
                                                                    'name' => 'new_quantity_0',
                                                                    'value' => '1',
                                                                    'class' => 'lampSymbol',
                                                                    'datarow' => '0',                                                                                
                                                                    'datatype' => 'new',
                                                                    'checked' => (isset($hardwareinput['new_quantity_0']) && $hardwareinput['new_quantity_0']=='1')?true:false
                                                                );
                                                                echo form_label(form_radio($data)." <img src='".base_url('assets/pages/img/green.png')."' width='24' />","new_quantity1_0");
                                                                ?>
                                                            </td>
                                                        </tr> 
                                                        <!-- END ROW -->

                                                        <?php
                                                        if(isset($hardwareinput['seriesnr']) && count($hardwareinput['seriesnr'])>0){
                                                            foreach($hardwareinput['seriesnr'] as $pkey=>$hardwareinputproduct){
                                                                if($pkey==0){ continue; }
                                                                ?>
                                                                <!-- ROW -->
                                                                <tr id="row1_new_hardwareinputproduct_<?php echo $pkey;?>">
                                                                    <td class="text-center">
                                                                        
                                                                        <?php
                                                                        if($pkey==(count($hardwareinput['seriesnr'])-1)){                                                                        
                                                                            ?>
                                                                            <a href="javascript:void(0);" class="btn btn-sm btn-default btn-circle btn-editable green addhardwareinputproduct" datarow="<?php echo $pkey;?>" datatype="new" datainit="0"><i class="fa fa-plus"></i></a>
                                                                            <?php
                                                                        }
                                                                        else{
                                                                            ?>
                                                                            <a href="javascript:void(0);" class="btn btn-sm btn-default btn-circle btn-editable red" onClick="deletehardwareinputproduct('<?php echo $pkey;?>','new')"><i class="fa fa-minus"></i></a>
                                                                            <?php
                                                                        }
                                                                        ?>
                                                                        
                                                                    </td>
                                                                    <td><?php echo form_dropdown('hardware['.$pkey.']', $hardwares, isset($hardwareinput['hardware'][$pkey])?$hardwareinput['hardware'][$pkey]:'', 'class="form-control"  ');?></td>
                                                                    <td><?php echo form_input('seriesnr['.$pkey.']', isset($hardwareinput['seriesnr'][$pkey])?$hardwareinput['seriesnr'][$pkey]:'', 'class="form-control"  ');?></td>                                                                            
                                                                    <td>
                                                                        <?php 
                                                                        $data = array(
                                                                            'id' => 'new_quantity0_'.$pkey,
                                                                            'name' => 'new_quantity_'.$pkey,
                                                                            'value' => '0',
                                                                            'class' => 'lampSymbol',
                                                                            'datarow' => '0',                                                                                
                                                                            'datatype' => 'new',
                                                                            'checked' => true
                                                                        );
                                                                        echo form_label(form_radio($data)." <img src='".base_url('assets/pages/img/red.png')."' width='24' />","new_quantity0_".$pkey);

                                                                        $data = array(
                                                                            'id' => 'new_quantity1_'.$pkey,
                                                                            'name' => 'new_quantity_'.$pkey,
                                                                            'value' => '1',
                                                                            'class' => 'lampSymbol',
                                                                            'datarow' => '0',                                                                                
                                                                            'datatype' => 'new',
                                                                            'checked' => (isset($hardwareinput['new_quantity_'.$pkey]) && $hardwareinput['new_quantity_'.$pkey]=='1')?true:false
                                                                        );
                                                                        echo form_label(form_radio($data)." <img src='".base_url('assets/pages/img/green.png')."' width='24' />","new_quantity1_".$pkey);
                                                                        ?>
                                                                    </td>
                                                                </tr>
                                                                <!-- END ROW -->
                                                                <?php
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
                                            <a href="<?php echo base_url('admin/hardwareinputs')?>"><button type="button" class="btn default"><?php echo lang('cancel');?></button></a>
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
    var form_id = 'form_hardwareinput'; 
    var func_FormValidation = 'FormCustomValidation';
    
    function after_func_FormValidation(form1, error1, success1){
      
        form1.validate({
            errorElement: 'span', //default input error message container
            errorClass: 'help-block help-block-error', // default input error message class
            focusInvalid: false, // do not focus the last invalid input
            ignore: "",  // validate all fields including form hidden input

            rules: { 
                supplier: {
                    required: true
                },
                hardwareinputdate: {                    
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
function addhardwareinputproduct(){     
jQuery('.addhardwareinputproduct').click( function(){  
    var datainit = $(this).attr('datainit');
    if(datainit==1){ return false; }
        
    //Swap Class
    var sdatarow = $(this).attr('datarow');
    var sdatatype = $(this).attr('datatype');    
    $(this).removeClass('addhardwareinputproduct');
    $(this).removeClass('green');
    $(this).addClass('red');
    $(this).html('<i class="fa fa-minus"></i>');
    $(this).attr('onClick',"deletehardwareinputproduct('"+sdatarow+"','"+sdatatype+"')");
    $(this).attr('datainit',1);


    var rownum = parseInt(jQuery('#count_hardwareinputproduct').val()) + 1;        
    var inputhtml = '<tr id="row1_new_hardwareinputproduct_'+rownum+'">';    
    inputhtml = inputhtml + '<td class="text-center"><a href="javascript:void(0);" class="btn btn-sm btn-default btn-circle btn-editable green addhardwareinputproduct" datarow="'+rownum+'" datatype="new" datainit="0"><i class="fa fa-plus"></i></a></td>';           
    inputhtml = inputhtml + '<td><select name="hardware['+rownum+']" class="form-control">';
    <?php
    foreach($hardwares as $k=>$v){
        ?>
        inputhtml = inputhtml + "<option value='<?php echo $k;?>'><?php echo $v;?></option>";
        <?php
    }
    ?>
    inputhtml = inputhtml + '</select></td>';        
    inputhtml = inputhtml + '<td><input type="text" name="seriesnr['+rownum+']" value=""  class="form-control" /></td>';
    inputhtml = inputhtml + '<td><label for="new_quantity0_'+rownum+'"> <input type="radio" name="new_quantity_'+rownum+'" value="0" checked="checked" id="new_quantity0_'+rownum+'" class="lampSymbol" datarow="0" datatype="new"> <img src="<?php echo base_url('assets/pages/img/red.png');?>" width="24"></label> <label for="new_quantity1_'+rownum+'"> <input type="radio" name="new_quantity_'+rownum+'" value="1" id="new_quantity1_'+rownum+'" class="lampSymbol" datarow="0" datatype="new"> <img src="<?php echo base_url('assets/pages/img/green.png');?>" width="24"></label></td>';
    inputhtml = inputhtml + '</tr>';    
    
    jQuery('#hardwareinputproduct_inputbox').append(inputhtml);
    jQuery('#count_hardwareinputproduct').val(rownum);    
    jQuery('[data-toggle="tooltip"]').tooltip();
    
    //Radio Uniform Update
    jQuery('#new_quantity0_'+rownum).uniform();
    jQuery('#new_quantity1_'+rownum).uniform();
    addhardwareinputproduct();
        
    App.scrollTo(jQuery('#hardwareinputproduct_inputbox'), 2000);
});
}
addhardwareinputproduct();

jQuery('#form_hardwareinput').on('submit', function(e) {
    extraFieldsValidate();
});

function deletehardwareinputproduct(dataid,datatype){
    var rownum = jQuery('#count_hardwareinputproduct').val();
    if(datatype=='old'){    
        //Delete record from db by ajax
        deleteConfirmation('<?php echo base_url('admin/hardwareinputs/deleteHardwareinputProduct/');?>',dataid,'<?php echo lang('page_lb_delete_hardwareinputproduct')?>','<?php echo lang('page_lb_delete_hardwareinputproduct_info')?>','true');        
    }
    else{
        jQuery('#row1_new_hardwareinputproduct_'+dataid).remove();
    }
    jQuery('#count_hardwareinputproduct').val(rownum);        
}
</script>
<?php $this->load->view('admin/hardwareinputs/hardwareinputjs',array('hardwareinput'=>isset($hardwareinput)?$hardwareinput:''));?>