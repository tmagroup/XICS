<div class="col-md-6">


    <!-- BEGIN SAMPLE FORM PORTLET-->
    <div class="portlet light bordered">                                
        <div class="portlet-body form">

            <div class="form-body">

                <div class="form-group">
                    <label><?php echo lang('page_fl_company');?>:</label>
                    <?php echo $assignment['company'];?>
                </div>

                <div class="form-group">
                    <label><?php echo lang('page_fl_assignmentdate');?>:</label>
                    <?php echo _d($assignment['assignmentdate']);?>
                </div>

                <div class="form-group">
                    <label><?php echo lang('page_fl_assignmentstatus');?>:</label>
                    <?php echo $assignment['assignmentstatus'];?>
                </div>

                <div class="form-group">
                    <label><?php echo lang('page_fl_assignmentprovider');?>:</label>
                    <?php echo $assignment['providercompanynr'];?>
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
                    <?php echo $assignment['customer'];?>
                </div>

                <div class="form-group">
                    <label><?php echo lang('page_fl_responsible');?>:</label>
                    <?php echo $assignment['responsible'];?>
                </div>

                <div class="form-group">
                    <label><?php echo lang('page_fl_recommend');?>:</label>
                    <?php echo $assignment['recommend'];?>
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
                        <label><?php echo lang('page_fl_newdiscountlevel');?>:</label>
                        <?php echo $assignment['newdiscountlevel'];?>
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
                                    <th class="text-nowrap text-center"><?php echo lang('page_fl_simnr');?></th>           
                                    <th class="text-nowrap text-center"><?php echo lang('page_fl_mobilenr');?></th>                                                    
                                    <th class="text-nowrap text-center"><?php echo lang('page_fl_employee');?></th>                                                    
                                    <th class="text-nowrap text-center"><?php echo lang('page_fl_vvl_neu');?></th>
                                    <th class="text-nowrap text-center"><?php echo lang('page_fl_ratetitle');?></th>
                                    <th class="text-nowrap text-center"><?php echo lang('page_fl_value');?></th>   
                                    <th class=""><?php echo lang('page_fl_extemtedterm');?></th>
                                    <th class="text-nowrap text-center"><?php echo lang('page_fl_optiontitle');?></th>
                                    <th class="text-nowrap text-center"><?php echo lang('page_fl_value');?></th>                                                    
                                    <th class="text-nowrap text-center"><?php echo lang('page_fl_hardware');?></th>
                                    <th class=""><?php echo lang('page_fl_cardstatus');?></th>
                                    <th class="text-center"><?php echo lang('page_fl_endofcontract');?></th> 
                                    <th class=""><?php echo lang('page_fl_finished');?></th>                                    
                                </tr>                                                
                            </thead>   
                            <tbody id="assignmentproduct_inputbox">
                            <?php
                            if(isset($assignmentproducts) && count($assignmentproducts)>0){
                                foreach($assignmentproducts as $pkey=>$assignmentproduct){
                                    ?>
                                    <!-- ROW -->
                                    <tr id="row1_old_assignmentproduct_<?php echo $assignmentproduct['id'];?>">
                                        <td class="text-center"><?php echo $assignmentproduct['simnr'];?></td>
                                        <td class="text-center"><?php echo $assignmentproduct['mobilenr'];?></td>
                                        <td class="text-center"><?php echo $assignmentproduct['employee'];?></td>
                                        <td class="text-center"><?php echo $assignmentproduct['vvlneu'];?></td>
                                        <td class="text-center"><?php echo $assignmentproduct['newratemobile'];?></td>                                                                    
                                        <td class="text-center"><?php echo $assignmentproduct['value2'];?></td>
                                        <td class="text-center"><?php echo $assignmentproduct['extemtedterm']=='1'?'<i class="fa fa-check"></i>':'<i class="fa fa-close"></i>';?></td>
                                        <td class="text-center"><?php echo $assignmentproduct['newoptionmobile'];?></td>
                                        <td class="text-center"><?php echo $assignmentproduct['value4'];?></td>                                                                    
                                        <td class="text-center"><?php echo $assignmentproduct['hardware'];?></td>
                                        <td class="text-center"><?php echo $assignmentproduct['cardstatus']=='1'?'<i class="fa fa-check"></i>':'<i class="fa fa-close"></i>';?></td>
                                        <td class="text-center"><?php echo _d($assignmentproduct['endofcontract']);?></td> 
                                        <td class="text-center"><?php echo $assignmentproduct['finished']=='1'?'<i class="fa fa-check"></i>':'<i class="fa fa-close"></i>';?></td>                                        
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

<div class="clearfix"></div>