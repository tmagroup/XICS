<div class="col-md-6">
                            
                            
    <!-- BEGIN SAMPLE FORM PORTLET-->
    <div class="portlet light bordered">                                
        <div class="portlet-body form">

            <div class="form-body">

                <div class="form-group">
                    <label><?php echo lang('page_fl_company');?>:</label>
                    <?php echo $hardwareassignment['company'];?>
                </div>

                <div class="form-group">
                    <label><?php echo lang('page_fl_hardwareassignmentstatus');?>:</label>
                    <?php echo $hardwareassignment['hardwareassignmentstatus'];?>
                </div>

                <div class="form-group">
                    <label><?php echo lang('page_dt_created');?>:</label>
                    <?php echo _dt($hardwareassignment['created']);?>
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
                    <?php echo $hardwareassignment['customer'];?>
                </div>

                <div class="form-group">
                    <label><?php echo lang('page_fl_responsible');?>:</label>
                    <?php echo $hardwareassignment['responsible'];?>
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

            <div class="form-body">

                <div class="form-group">
                    <div class="table-responsive no-dt">
                        <table class="table table-striped table-bordered table-hover dt-responsive" width="100%">    
                            <thead>
                                <tr role="row" class="heading">                          
                                    <th class="text-nowrap" width="10%"><?php echo lang('page_fl_mobilenr');?></th>
                                    <th class="text-nowrap" width="10%"><?php echo lang('page_fl_simnr');?></th>
                                    <th class="text-nowrap" width="20%"><?php echo lang('page_fl_ratetitle');?></th>
                                    <th class="text-nowrap" width="20%"><?php echo lang('page_fl_choosenhardware');?></th>
                                    <th class="text-nowrap" width="20%"><?php echo lang('page_fl_stockhardware');?></th>
                                    <th class="text-nowrap" width="10%"><?php echo lang('page_fl_seriesnr');?></th>
                                    <th class="text-nowrap" width="10%"><?php echo lang('page_fl_shippingnr');?></th>
                                    <th class="text-nowrap" width="10%"><?php echo lang('page_fl_hardwarevalue');?></th>
                                </tr> 
                            </thead>   
                            <tbody id="hardwareassignment_inputbox">

                                <?php
                                if(isset($hardwareassignmentproducts) && count($hardwareassignmentproducts)>0){
                                    $data_hidden = array('type'=>'hidden', 'name'=>'count_hardwareassignmentproduct', 'id'=>'count_hardwareassignmentproduct', 'value'=>isset($hardwareassignmentproducts)?count($hardwareassignmentproducts):1);  
                                    echo form_input($data_hidden);

                                    foreach($hardwareassignmentproducts as $pkey=>$hardwareassignmentproduct){                                                                
                                        echo form_hidden('hardwareassignmentproductid['.$pkey.']', $hardwareassignmentproduct['id']);
                                        ?>
                                        <!-- ROW -->
                                        <tr id="row1_old_hardwareassignment_<?php echo $hardwareassignmentproduct['id'];?>">
                                            <td><?php echo $hardwareassignmentproduct['mobilenr'];?></td>
                                            <td><?php echo $hardwareassignmentproduct['simnr'];?></td>
                                            <td><?php echo $hardwareassignmentproduct['newratemobile'];?></td>
                                            <td><?php echo $hardwareassignmentproduct['hardware'];?></td>
                                            <td><?php echo $hardwareassignmentproduct['stockhardwaretitle'];?></td>
                                            <td><?php echo $hardwareassignmentproduct['seriesnr'];?></td>
                                            <td><?php echo $hardwareassignmentproduct['shippingnr'];?></td>
                                            <td><?php echo format_money($hardwareassignmentproduct['hardwarevalue'],"&nbsp;".$GLOBALS['currency_data']['currency_symbol']);?></td>
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
    </div>
    <!-- END SAMPLE FORM PORTLET-->

</div>