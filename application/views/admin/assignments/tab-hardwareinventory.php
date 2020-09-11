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
                                    <th class="text-nowrap"><?php echo lang('page_fl_positionnr');?></th>
                                    <th class="text-nowrap"><?php echo lang('page_fl_seriesnr');?></th>
                                    <th class="text-nowrap"><?php echo lang('page_fl_mobilenr');?></th>
                                    <th class="text-nowrap"><?php echo lang('page_fl_employee');?></th>
                                    <th class="text-nowrap"><?php echo lang('page_fl_stockhardware');?></th>
                                    <th class="text-nowrap"><?php echo lang('page_fl_hardwarsentout');?></th>
                                    <th class="text-nowrap"><?php echo lang('page_fl_notice');?></th>
                                    <th class="text-nowrap"><?php echo lang('page_fl_category');?></th>
                                    <th class="text-nowrap"><?php echo lang('page_fl_insurance');?></th>
                                    <th class="text-nowrap"><?php echo lang('page_fl_mdm');?></th>
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
                                            <td><?php echo $hardwareassignmentproduct['id'];?></td>
                                            <td><?php echo $hardwareassignmentproduct['seriesnr'];?></td>
                                            <td><?php echo $hardwareassignmentproduct['mobilenr'];?></td>
                                            <td><?php echo $hardwareassignmentproduct['employee'];?></td>
                                            <td><?php echo $hardwareassignmentproduct['hardware'];?></td>
                                            <td><?php echo _d($hardwareassignmentproduct['shippingnr_date']);?></td>
                                            <td><?php echo $hardwareassignmentproduct['notice'];?></td>
                                            <td><?php echo $hardwareassignmentproduct['hardwarecategory'];?></td>
                                            <td>
                                                <?php
                                                $is_insurance = (isset($hardwareassignmentproduct['is_insurance']) && $hardwareassignmentproduct['is_insurance']==1)?'  checked':'';
                                                ?>
                                                <div class="onoffswitch" data-toggle="tooltip" data-title=""><input <?php echo $is_insurance;?> type="checkbox" data-switch-url="<?php echo base_url('admin/assignments/change_hardwareassignment_insurance');?>" data-id="<?php echo $hardwareassignmentproduct['id'];?>" class="make-switch" data-on-text="<?php echo lang('page_lb_yes');?>" data-off-text="<?php echo lang('page_lb_no');?>" data-on-color="primary" data-off-color="danger" data-size="small"></div>
                                            </td>
                                            <td>
                                                <?php
                                                $is_mdm = (isset($hardwareassignmentproduct['is_mdm']) && $hardwareassignmentproduct['is_mdm']==1)?'  checked':'';
                                                ?>
                                                <div class="onoffswitch" data-toggle="tooltip" data-title=""><input <?php echo $is_mdm;?> type="checkbox" data-switch-url="<?php echo base_url('admin/assignments/change_hardwareassignment_mdm');?>" data-id="<?php echo $hardwareassignmentproduct['id'];?>" class="make-switch" data-on-text="<?php echo lang('page_lb_yes');?>" data-off-text="<?php echo lang('page_lb_no');?>" data-on-color="primary" data-off-color="danger" data-size="small"></div>
                                            </td>
                                        </tr>

                                        <tr id="row2_old_hardwareassignment_<?php echo $hardwareassignmentproduct['id'];?>">
                                            <td colspan="8">

                                                <?php
                                                if($GLOBALS['a_repairorder_permission']['create']){
                                                    ?>
                                                    <a href="javascript:void(0);" onclick="FormTicketAjax('<?php echo base_url('admin/assignments/generateTicket/'.$assignment['assignmentnr']);?>','<?php echo $assignment['assignmentnr'];?>','repairorder','<?php echo lang('page_ticket')." - ".lang('page_lb_repairorder');?>','<?php echo lang('page_lb_repairorder_popup_ask');?>','<?php echo $hardwareassignmentproduct['id'];?>');" class="btn sbold red btn-sm"> <?php echo lang('page_lb_repairorder');?></a>
                                                    <?php
                                                }

                                                if($GLOBALS['a_rebuyorder_permission']['create']){
                                                    ?>
                                                    <a href="javascript:void(0);" onclick="FormTicketAjax('<?php echo base_url('admin/assignments/generateTicket/'.$assignment['assignmentnr']);?>','<?php echo $assignment['assignmentnr'];?>','rebuyorder','<?php echo lang('page_ticket')." - ".lang('page_lb_rebuyorder');?>','<?php echo lang('page_lb_rebuyorder_popup_ask');?>','<?php echo $hardwareassignmentproduct['id'];?>');" class="btn sbold red btn-sm"> <?php echo lang('page_lb_rebuyorder');?></a>
                                                    <?php
                                                }

                                                if($GLOBALS['a_bookinsurance_permission']['create']){
                                                    ?>
                                                    <a href="javascript:void(0);" onclick="FormTicketAjax('<?php echo base_url('admin/assignments/generateTicket/'.$assignment['assignmentnr']);?>','<?php echo $assignment['assignmentnr'];?>','bookinsurance','<?php echo lang('page_ticket')." - ".lang('page_lb_bookinsurance');?>','<?php echo lang('page_lb_bookinsurance_popup_ask');?>','<?php echo $hardwareassignmentproduct['id'];?>');" class="btn sbold red btn-sm"> <?php echo lang('page_lb_bookinsurance');?></a>
                                                    <?php
                                                }

                                                if($GLOBALS['a_hardwareuploaddocument_permission']['create']){
                                                    ?>
                                                    <a href="javascript:void(0);" onclick="FormHardwarePositionUploadAjax('<?php echo base_url('admin/assignments/getHardwareAssignmentPositionDocument/'.$hardwareassignmentproduct['id']);?>','<?php echo $hardwareassignmentproduct['id'];?> - <?php echo lang('page_lb_hardwareuploaddocument');?>','<?php echo $hardwareassignmentproduct['id'];?>');" class="btn sbold red btn-sm"> <i class="fa fa-upload"></i> <?php echo lang('page_lb_hardwareuploaddocument');?></a>
                                                    <?php
                                                }

                                                // if(get_user_role()!='customer'){
                                                if ($hardwareassignmentproduct['extrenalhardwareassignmentnr'] > 0) {
                                                    ?>
                                                    <a href="javascript:void(0);" class="btn btn-sm btn-default btn-circle btn-editable red" onclick="deleteConfirmation(<?= "'".base_url('admin/assignments/deleteHardware')."','".$hardwareassignmentproduct['hardwareassignmentnr']."','".lang('page_lb_delete_hardware')."','".lang('page_lb_delete_hardware_info')."','false','".$hardwareassignmentproduct['productpositionid']."'"?>)"><i class="fa fa-remove"></i></a>
                                                    <?php
                                                }
                                                // }
                                                ?>

                                            </td>
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
