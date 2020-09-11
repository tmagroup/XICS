<div class="col-md-6">

<!-- BEGIN SAMPLE FORM PORTLET-->
<div class="portlet light bordered">                                
    <div class="portlet-body form">

        <div class="form-body">

            <div class="form-group">
                <label><?php echo lang('page_fl_company');?>:</label>
                <?php echo $quotation['company'];?>
            </div>

            <div class="form-group">
                <label><?php echo lang('page_fl_quotationdate');?>:</label>
                <?php echo _d($quotation['quotationdate']);?>
            </div>

            <div class="form-group">
                <label><?php echo lang('page_fl_quotationstatus');?>:</label>
                <?php echo $quotation['quotationstatusname'];?>
            </div>

            <div class="form-group">
                <label><?php echo lang('page_fl_quotationprovider');?>:</label>
                <?php echo $quotation['providercompanynr'];?>
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
                <label><?php echo lang('page_fl_customer');?>:</label>
                <?php echo $quotation['customer'];?>
            </div>

            <div class="form-group">
                <label><?php echo lang('page_fl_responsible');?>:</label>
                <?php echo $quotation['responsible'];?>
            </div>

            <div class="form-group">
                <label><?php echo lang('page_fl_recommend');?>:</label>
                <?php echo $quotation['recommend'];?>
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
            <div class="col-md-4">
                <div class="form-group">
                    <label><?php echo lang('page_fl_currentdiscountlevel');?>:</label>
                    <?php echo $quotation['currentdiscountlevel'];?>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label><?php echo lang('page_fl_newdiscountlevel');?>:</label>
                    <?php echo $quotation['newdiscountlevel'];?>
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
                            <th class="text-nowrap text-center"><?php echo lang('page_fl_productenterform');?></th>
                            <th class="text-nowrap text-center"><?php echo lang('page_fl_mobilenr');?></th>                                                    
                            <th class="text-nowrap text-center"><?php echo lang('page_fl_vvl_neu');?></th>
                            <th class="text-nowrap text-center"><?php echo lang('page_fl_currentratemobile');?></th>
                            <th class="text-nowrap text-center"><?php echo lang('page_fl_value');?></th>                                                    
                            <th class="text-nowrap text-center"><?php echo lang('page_fl_use');?></th>
                            <th class="text-nowrap text-center"><?php echo lang('page_fl_newratemobile');?></th>
                            <th class="text-nowrap text-center"><?php echo lang('page_fl_value');?></th>                                                    
                            <th class="text-nowrap text-center"><?php echo lang('page_fl_endofcontract');?></th>
                            <th class="text-nowrap text-center"><?php echo lang('page_fl_hardware');?></th>
                        </tr>                                                
                    </thead>   
                    <tbody id="quotationproduct_inputbox">
                    <?php
                    if(isset($quotationproducts) && count($quotationproducts)>0){
                        foreach($quotationproducts as $pkey=>$quotationproduct){
                            ?>
                            <!-- ROW -->
                            <tr id="row1_old_quotationproduct_<?php echo $quotationproduct['id'];?>">
                                <td class="text-nowrap text-center"><?php echo $quotationproduct['formula']=='M'?lang('page_lb_manual'):lang('page_lb_auto');?></td>
                                <td class="text-center"><?php echo $quotationproduct['mobilenr'];?></td>
                                <td class="text-center"><?php echo $quotationproduct['vvlneu'];?></td>
                                <td class="text-center"><?php echo $quotationproduct['currentratemobile'];?></td>
                                <td class="text-center"><?php echo $quotationproduct['value1'];?></td>
                                <td class="text-center"><?php echo $quotationproduct['use'];?></td>
                                <td class="text-center"><?php echo $quotationproduct['newratemobile'];?></td>
                                <td class="text-center"><?php echo $quotationproduct['value2'];?></td>
                                <td class="text-center"><?php echo _d($quotationproduct['endofcontract']);?></td>
                                <td class="text-center"><?php echo $quotationproduct['hardware'];?></td>
                            </tr>
                            <tr id="row2_old_quotationproduct_<?php echo $quotationproduct['id'];?>">
                                <td></td>
                                <td></td>
                                <td></td>
                                <td class="text-center"><?php echo $quotationproduct['currentoptionmobile'];?></td>
                                <td class="text-center"><?php echo $quotationproduct['value3'];?></td>
                                <td></td>
                                <td class="text-center"><?php echo $quotationproduct['newoptionmobile'];?></td>
                                <td class="text-center"><?php echo $quotationproduct['value4'];?></td>
                                <td colspan="2"><?php if($quotationproduct['formula']=='A'){ echo lang('page_fl_fqty'.$quotationproduct['simcard_function_id']).': '.$quotationproduct['simcard_function_qty']; }?></td>
                            </tr>
                            <!-- END ROW -->                                                                                                                                
                            <?php

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