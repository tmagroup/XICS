<!-- BEGIN PAGE MESSAGE-->
<?php $this->load->view('admin/alerts_modal'); ?>
<!-- BEGIN PAGE MESSAGE-->

<div class="col-md-6">

    <!-- BEGIN SAMPLE FORM PORTLET-->
    <div class="portlet light bordered">
        <div class="portlet-body form">

            <div class="form-body">

                <!--<div class="form-group">
                    <label><?php echo lang('page_fl_company');?> <span class="required"> * </span></label>
                    <?php echo form_input('company', isset($quotation['company'])?$quotation['company']:'', 'class="form-control"');?>
                </div>-->

                <div class="form-group">
                    <label><?php echo lang('page_fl_assignmentdate');?> <span class="required"> * </span></label>

                    <div class="input-group date form_date">
                        <?php $dd = array('name'=>'assignmentdate', 'class'=>'form-control', 'readonly'=>true, 'size'=>16, 'value'=> date('d.m.Y'));
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
                    <?php echo form_dropdown('assignmentstatus', $assignmentstatus, '', 'class="form-control"');?>
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
                                <th class="text-nowrap"><?php echo lang('page_fl_mobilenr');?> <span class="required"> * </span></th>
                                <th class="text-nowrap"><?php echo lang('page_fl_vvl_neu');?> <span class="required"> * </span></th>
                                <th class="text-nowrap"><?php echo lang('page_fl_newratemobile');?> <span class="required"> * </span></th>
                                <th class="text-nowrap"><?php echo lang('page_fl_value');?> <span class="required"> * </span></th>
                                <th class="text-nowrap"><?php echo lang('page_fl_newoptionmobile');?> </th>
                                <th class="text-nowrap"><?php echo lang('page_fl_value');?> </th>
                                <th class="text-nowrap"><?php echo lang('page_fl_endofcontract');?> </th>
                                <th class="text-nowrap"><?php echo lang('page_fl_hardware');?> </th>
                            </tr>
                        </thead>
                        <tbody id="quotationproduct_inputbox">
                        <?php
                        if(isset($quotationproducts) && count($quotationproducts)>0){
                            $fnum = 0;
                            foreach($quotationproducts as $pkey=>$quotationproduct){
                                if($quotationproduct['formula']=='M'){ continue; }

                                 $formula = ($quotationproduct['formula']=='M')?'M':'A';
                                ?>
                                <!-- ROW -->
                                <tr id="row1_new_quotationproduct_<?php echo $quotationproduct['id'];?>">
                                    <td><?php
                                    $data_hidden = array('type'=>'hidden', 'name'=>'new_formula_'.$pkey, 'value'=>$quotationproduct['formula']);
                                    echo form_input($data_hidden);

                                    echo form_input('mobilenr['.$pkey.']', $quotationproduct['mobilenr'], 'class="form-control"');?></td>
                                    <td><?php echo form_dropdown('vvlneu['.$pkey.']', $vvlneu, $quotationproduct['vvlneu'], 'class="form-control"');?></td>
                                    <td><?php echo form_dropdown('newratemobile['.$pkey.']', $mobilerates, $quotationproduct['newratemobile'], 'class="form-control newratemobile" id="new_newratemobile_'.$pkey.'" datarow="'.$pkey.'" datatype="new" ');?></td>
                                    <td><?php echo form_input('value2['.$pkey.']', $quotationproduct['value2'], 'class="form-control" id="new_value2_'.$pkey.'" ');?></td>
                                    <td><?php echo form_dropdown('newoptionmobile['.$pkey.']', $mobileoptions, $quotationproduct['newoptionmobile'], 'class="form-control newoptionmobile noerror" id="new_newoptionmobile_'.$pkey.'" datarow="'.$pkey.'" datatype="new" ');?></td>
                                    <td><?php echo form_input('value4['.$pkey.']', $quotationproduct['value4'], 'class="form-control noerror" id="new_value4_'.$pkey.'" ');?></td>
                                    <td>
                                        <div class="input-group date form_date">
                                            <?php $dd = array('name'=>'endofcontract['.$pkey.']', 'class'=>'form-control noerror', 'readonly'=>true, 'size'=>16, 'value'=> _d($quotationproduct['endofcontract']));
                                            echo form_input($dd);?>

                                            <span class="input-group-btn">
                                                <button class="btn default date-set" type="button">
                                                    <i class="fa fa-calendar"></i>
                                                </button>
                                            </span>
                                        </div>
                                    </td>
                                    <td><?php echo form_dropdown('hardware['.$pkey.']', $hardwares, $quotationproduct['hardware'], 'class="form-control noerror"');?></td>
                                </tr>

                                <tr id="row3_new_quotationproduct_<?php echo $pkey;?>" style="display:none;">
                                    <td></td>
                                    <td></td>

                                    <td colspan="6">
                                        <label>
                                            <?php
                                            $ultracard1 = (isset($quotationproduct['ultracard1']) && $quotationproduct['ultracard1']==1)?true:false;
                                            $dc = array('name'=>'ultracard1['.$pkey.']','class'=>'form-control ultracard','checked'=>$ultracard1, 'value'=>1);
                                            echo form_checkbox($dc);?>
                                            <?php echo lang('page_fl_ultracard1');?>
                                        </label>
                                        <label>
                                            <?php
                                            $ultracard2 = (isset($quotationproduct['ultracard2']) && $quotationproduct['ultracard2']==1)?true:false;
                                            $dc = array('name'=>'ultracard2['.$pkey.']','class'=>'form-control ultracard','checked'=>$ultracard2, 'value'=>1);
                                            echo form_checkbox($dc);?>
                                            <?php echo lang('page_fl_ultracard2');?>
                                        </label>
                                    </td>
                                </tr>
                                <?php
                                if($formula=='A'){
                                    ?>
                                    <script>
                                    jQuery.ajax({url: '<?php echo base_url('admin/ratesmobile/getInputUltraCard/'.$quotationproduct['newratemobile'].'/');?>', success: function(result){
                                        if(result==1){
                                            $('#row3_new_quotationproduct_<?php echo $pkey;?>').show();
                                        }else{
                                            $('#row3_new_quotationproduct_<?php echo $pkey;?>').hide();
                                        }
                                    }});
                                    </script>
                                    <?php
                                }
                                ?>

                                <!-- END ROW -->
                                <?php
                                $fnum++;
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


<script>
//Get Responsibles (Users of Customer) by Ajax
var selected_responsible = '<?php echo isset($quotation['responsible'])?$quotation['responsible']:'';?>'
jQuery("#customer").change( function(){
    var custid = jQuery(this).val();
    jQuery("#responsible").html("<option value=''><?php echo lang('page_option_wait');?></option>");
    jQuery.ajax({dataType: "JSON", url: '<?php echo base_url('admin/quotations/getResponsibleOfCustomer/');?>'+custid, success: function(data){
        var optionhtml = '';
        jQuery("#responsible").html("");
        jQuery.each( data, function( key, value ) {
            if(key!=""){
                var selected = '';
                if(selected_responsible==key){ var selected=' selected';  }
                optionhtml = optionhtml+"<option value='"+key+"' "+selected+">"+value+"</option>";
            }
        });
        jQuery("#responsible").append(optionhtml);
    }});
});

jQuery('.newratemobile').change( function(){

    var rown = jQuery(this).attr('datarow');
    var rowt = jQuery(this).attr('datatype');

    var discountlevel = jQuery('#newdiscountlevel').val();
    if(discountlevel==""){ discountlevel="none"; }

    var formula = jQuery('input[name='+rowt+'_formula_'+rown+']').val();
    var id = jQuery('#'+rowt+'_newratemobile_'+rown).val();

    if(formula=='A'){
        if(id==""){ id="none"; }

        jQuery.ajax({url: '<?php echo base_url('admin/quotations/getMobileRateValue/');?>'+id+'/'+discountlevel+'/'+formula, success: function(result){
            var temp = result.split('[=]');
            jQuery('#'+rowt+'_value2_'+rown).val(temp[0]);

            if($.trim(temp[1])==1){
                $('#row3_'+rowt+'_quotationproduct_'+rown).show();
            }else{
                $('#row3_'+rowt+'_quotationproduct_'+rown).hide();
            }
        }});

        jQuery.ajax({url: '<?php echo base_url('admin/ratesmobile/getnewInputSimcardFunction/');?>'+rown+'/'+id, success: function(result){
            jQuery('#'+rowt+'_simcard_function_'+rown).html(result);
        }});

    }
    else{
        jQuery('#row3_'+rowt+'_quotationproduct_'+rown).hide();
    }
});

<?php
if(isset($quotation['quotationnr'])){
    ?>
    jQuery("#customer").change();
    <?php
}
?>

jQuery('.ultracard').uniform();
</script>