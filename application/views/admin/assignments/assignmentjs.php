<!-- Convert to Hardware Position Modal -->
<div class="modal fade bs-modal-lg in" id="FormHardwarePositionUploadAjax" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-full" id="modal_size" role="document">
        <?php //echo form_open("",array("id"=>"FormHardwarePositionUploadModalAjax")); ?>
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"></h4>
            </div>
            <div class="modal-body">
                            <div class="form-body">
                                <div class="col-md-12">
                                    <!--<form id="FormHardwarePositionUploadModalAjax" action="<?php echo base_url('admin/assignments/uploadHardwareAssignmentPositionDocuments/');?>" class="dropzone dropzone-file-area dz-clickable" id="my-dropzone" style="padding-top:40px;"></form>-->

                                    <div id="file_upload"></div>
                                    <div class="clearfix">&nbsp;</div>
                                    <div id="hardwareassignmentproduct_attachments">

                                    </div>
                                </div>
                            </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo lang('page_lb_close'); ?></button>
            </div>
        </div><!-- /.modal-content -->
        <?php //echo form_close(); ?>
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<!-- End Convert to Hardware Position Modal -->


<!-- Ticket Modal -->
<div class="modal fade bs-modal-lg in" id="FormTicketAjax" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <?php echo form_open("",array("id"=>"FormTicketModalAjax")); ?>
        <div class="modal-content">
            <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title"></h4>
            </div>
            <div class="modal-body">

                            <?php
                            $data_hidden = array('type'=>'hidden', 'name'=>'assignmentId', 'id'=>'assignmentId', 'value'=>'');
                            echo form_input($data_hidden);
                            ?>

                            <?php
                            $data_hidden = array('type'=>'hidden', 'name'=>'assignmentProductId', 'id'=>'assignmentProductId', 'value'=>'');
                            echo form_input($data_hidden);
                            ?>

                            <?php
                            $data_hidden = array('type'=>'hidden', 'name'=>'ticketType', 'id'=>'ticketType', 'value'=>'');
                            echo form_input($data_hidden);
                            ?>

                            <?php
                            $data_hidden = array('type'=>'hidden', 'name'=>'emailSend', 'id'=>'emailSend', 'value'=>0);
                            echo form_input($data_hidden);
                            ?>

                            <p class="modal-text"></p>
            </div>
            <div class="modal-footer">
                            <div class="pull-left"><a href="<?php echo base_url('admin/infodocuments');?>" target="_blank"><?php echo lang('page_lb_ultracardorder_popup_ask2');?></a></div><br><br>

                            <button id="btn_cardbreak" style="display: none;" type="button" class="btn btn-default green-jungle disabled"><?php echo lang('page_lb_breaklaid');?></button>

                            <button id="btn_yes" type="submit" class="btn btn-default green" onclick="$('#FormTicketAjax #emailSend').val(1);"><?php echo lang('page_lb_yes'); ?></button>

                            <?php /*<button id="btn_no" type="submit" class="btn btn-default red" onclick="$('#FormTicketAjax #emailSend').val(0);"><?php echo lang('page_lb_no'); ?></button>*/ ?>
                            <button id="btn_no" type="button" data-dismiss="modal" class="btn btn-default red"><?php echo lang('page_lb_no'); ?></button>

            </div>
        </div><!-- /.modal-content -->
        <?php echo form_close(); ?>
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<!-- End Ticket Modal -->

<!-- Ticket Mobile Option Modal -->
<div class="modal fade bs-modal-lg in" id="FormTicketMobileOptionAjax" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <?php echo form_open("",array("id"=>"FormTicketMobileOptionModalAjax")); ?>
        <div class="modal-content">
            <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title"></h4>
            </div>
            <div class="modal-body">

                            <!-- BEGIN PAGE MESSAGE-->
                            <?php $this->load->view('admin/alerts_modal'); ?>
                            <!-- BEGIN PAGE MESSAGE-->

                            <?php
                            $data_hidden = array('type'=>'hidden', 'name'=>'assignmentId', 'id'=>'assignmentId', 'value'=>'');
                            echo form_input($data_hidden);
                            ?>

                            <?php
                            $data_hidden = array('type'=>'hidden', 'name'=>'assignmentProductId', 'id'=>'assignmentProductId', 'value'=>'');
                            echo form_input($data_hidden);
                            ?>

                            <?php
                            $data_hidden = array('type'=>'hidden', 'name'=>'ticketType', 'id'=>'ticketType', 'value'=>'');
                            echo form_input($data_hidden);
                            ?>

                            <?php
                            $data_hidden = array('type'=>'hidden', 'name'=>'emailSend', 'id'=>'emailSend', 'value'=>0);
                            echo form_input($data_hidden);
                            ?>

                            <!--<p class="modal-text"></p>-->
                            <div class="form-group">
                                <label><?php echo lang('page_lb_optionbook_popup_ask');?>: <span class="required"> * </span></label>
                                <?php echo form_dropdown('mobileoption', $mobileoptions, '', 'class="form-control" id="book_mobileoption"');?>
                            </div>
                            <div class="form-group">
                                <label><?php echo lang('page_fl_price');?>: <b><span id="book_price"></span></b></label>
                            </div>
                            <div class="form-group">
                                <label><?php echo lang('page_fl_runningtime');?>: <b><span id="book_runningtime"></span></b></label>
                            </div>

            </div>
            <div class="modal-footer">
                            <div class="pull-left"><a href="<?php echo base_url('admin/infodocuments');?>" target="_blank"><?php echo lang('page_lb_ultracardorder_popup_ask2');?></a></div>

                            <button type="submit" class="btn btn-default green" onclick="$('#FormTicketMobileOptionAjax #emailSend').val(1);"><?php echo lang('page_lb_book'); ?></button>
                            <button type="button" class="btn btn-default red" data-dismiss="modal" onclick="$('#FormTicketMobileOptionAjax #emailSend').val(0);"><?php echo lang('page_lb_stop'); ?></button>
            </div>
        </div><!-- /.modal-content -->
        <?php echo form_close(); ?>
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<!-- End Ticket Modal -->


<!-- Ticket Hardware Order Modal -->
<div class="modal fade bs-modal-lg in" id="FormTicketHardwareOrderAjax" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <?php echo form_open("",array("id"=>"FormTicketHardwareOrderModalAjax")); ?>
        <div class="modal-content">
            <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title"></h4>
            </div>
            <div class="modal-body">

                            <!-- BEGIN PAGE MESSAGE-->
                            <?php $this->load->view('admin/alerts_modal'); ?>
                            <!-- BEGIN PAGE MESSAGE-->

                            <?php
                            $data_hidden = array('type'=>'hidden', 'name'=>'assignmentId', 'id'=>'assignmentId', 'value'=>'');
                            echo form_input($data_hidden);
                            ?>

                            <?php
                            $data_hidden = array('type'=>'hidden', 'name'=>'assignmentProductId', 'id'=>'assignmentProductId', 'value'=>'');
                            echo form_input($data_hidden);
                            ?>

                            <?php
                            $data_hidden = array('type'=>'hidden', 'name'=>'discountLevelId', 'id'=>'discountLevelId', 'value'=>$assignment['newdiscountlevel_id']);
                            echo form_input($data_hidden);
                            ?>

                            <?php
                            $data_hidden = array('type'=>'hidden', 'name'=>'ticketType', 'id'=>'ticketType', 'value'=>'');
                            echo form_input($data_hidden);
                            ?>

                            <?php
                            $data_hidden = array('type'=>'hidden', 'name'=>'emailSend', 'id'=>'emailSend', 'value'=>0);
                            echo form_input($data_hidden);
                            ?>

                            <!--<p class="modal-text"></p>-->
                            <div class="form-group">
                                <label><?php echo lang('page_lb_hardwareorder_popup_ask');?>: <span class="required"> * </span></label>
                                <?php echo form_dropdown('hardware', $hardwares, '', 'class="form-control" id="order_hardware"');?>
                            </div>
                            <div class="form-group">
                                <label><?php echo lang('page_fl_price');?>: <b><span id="order_hardwareprice"></span></b></label>
                            </div>

            </div>
            <div class="modal-footer">
                            <div class="pull-left"><a href="<?php echo base_url('admin/infodocuments');?>" target="_blank"><?php echo lang('page_lb_ultracardorder_popup_ask2');?></a></div>

                            <button type="submit" class="btn btn-default green" onclick="$('#FormTicketHardwareOrderAjax #emailSend').val(1);"><?php echo lang('page_lb_order'); ?></button>
                            <button type="button" class="btn btn-default red" data-dismiss="modal" onclick="$('#FormTicketHardwareOrderAjax #emailSend').val(0);"><?php echo lang('page_lb_stop'); ?></button>
            </div>
        </div><!-- /.modal-content -->
        <?php echo form_close(); ?>
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<!-- End Ticket Modal -->


<!-- Ticket Card Order Modal -->
<div class="modal fade bs-modal-lg in" id="FormTicketCardOrderAjax" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <?php echo form_open("",array("id"=>"FormTicketCardOrderModalAjax")); ?>
        <div class="modal-content">
            <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title"></h4>
            </div>
            <div class="modal-body">

                            <?php
                            $data_hidden = array('type'=>'hidden', 'name'=>'assignmentId', 'id'=>'assignmentId', 'value'=>'');
                            echo form_input($data_hidden);
                            ?>

                            <?php
                            $data_hidden = array('type'=>'hidden', 'name'=>'assignmentProductId', 'id'=>'assignmentProductId', 'value'=>'');
                            echo form_input($data_hidden);
                            ?>

                            <?php
                            $data_hidden = array('type'=>'hidden', 'name'=>'ticketType', 'id'=>'ticketType', 'value'=>'');
                            echo form_input($data_hidden);
                            ?>

                            <?php
                            $data_hidden = array('type'=>'hidden', 'name'=>'emailSend', 'id'=>'emailSend', 'value'=>0);
                            echo form_input($data_hidden);
                            ?>

                            <p class="modal-text text-center"><?php echo lang('page_lb_ultracardorder_popup_ask');?></p>
            </div>
            <div class="modal-footer">
                            <div class="pull-left"><a href="<?php echo base_url('admin/infodocuments');?>" target="_blank"><?php echo lang('page_lb_ultracardorder_popup_ask2');?></a></div>

                            <button type="submit" class="btn btn-default green" onclick="$('#FormTicketCardOrderAjax #emailSend').val(1);"><?php echo lang('page_lb_yes'); ?></button>
                            <button type="button" class="btn btn-default red" data-dismiss="modal" onclick="$('#FormTicketCardOrderAjax #emailSend').val(0);"><?php echo lang('page_lb_no'); ?></button>
                            <!--<button type="submit" class="btn btn-default red" onclick="$('#FormTicketCardOrderAjax #emailSend').val(0);"><?php echo lang('page_lb_no'); ?></button>-->

            </div>
        </div><!-- /.modal-content -->
        <?php echo form_close(); ?>
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<!-- End Ticket Card Order Modal -->


<!-- Ticket Card Pause Modal -->
<div class="modal fade bs-modal-lg in" id="FormTicketCardPauseAjax" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <?php echo form_open("",array("id"=>"FormTicketCardPauseModalAjax")); ?>
        <div class="modal-content">
            <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title"></h4>
            </div>
            <div class="modal-body">

                            <!-- BEGIN PAGE MESSAGE-->
                            <?php $this->load->view('admin/alerts_modal'); ?>
                            <!-- BEGIN PAGE MESSAGE-->

                            <?php
                            $data_hidden = array('type'=>'hidden', 'name'=>'assignmentId', 'id'=>'assignmentId', 'value'=>'');
                            echo form_input($data_hidden);
                            ?>

                            <?php
                            $data_hidden = array('type'=>'hidden', 'name'=>'assignmentProductId', 'id'=>'assignmentProductId', 'value'=>'');
                            echo form_input($data_hidden);
                            ?>

                            <?php
                            $data_hidden = array('type'=>'hidden', 'name'=>'is_paused', 'id'=>'is_paused', 'value'=>'');
                            echo form_input($data_hidden);
                            ?>

                            <?php
                            $data_hidden = array('type'=>'hidden', 'name'=>'discountLevelId', 'id'=>'discountLevelId', 'value'=>$assignment['newdiscountlevel_id']);
                            echo form_input($data_hidden);
                            ?>

                            <?php
                            $data_hidden = array('type'=>'hidden', 'name'=>'ticketType', 'id'=>'ticketType', 'value'=>'');
                            echo form_input($data_hidden);
                            ?>

                            <?php
                            $data_hidden = array('type'=>'hidden', 'name'=>'emailSend', 'id'=>'emailSend', 'value'=>0);
                            echo form_input($data_hidden);
                            ?>

                            <?php
                            $data_hidden = array('type'=>'hidden', 'name'=>'cardbreak', 'id'=>'cardbreak', 'value'=>0);
                            echo form_input($data_hidden);
                            ?>

                            <!--<p class="modal-text"></p>-->
                            <div class="form-group">
                                <label><?php echo lang('page_lb_cardpause_popup_ask');?> <span class="required"> * </span></label>
                                <?php
                                $months = array(''=>lang('page_lb_month'));
                                foreach(range(1,12) as $month){
                                    $months[$month.' '.lang('page_lb_month')] = $month.' '.lang('page_lb_month');
                                }
                                echo form_dropdown('card_month', $months, '', 'class="form-control"');
                                ?>
                            </div>
                            <div class="form-group">
                                <label><?php echo lang('page_lb_cardpause_popup_ask2');?> <span class="required"> * </span></label>
                                <?php echo form_input('card_reason', '', 'class="form-control"');?>
                            </div>

            </div>
            <div class="modal-footer">
                            <div class="pull-left"><a href="<?php echo base_url('admin/infodocuments');?>" target="_blank"><?php echo lang('page_lb_ultracardorder_popup_ask2');?></a></div><br><br>

                            <?php
                            if(get_user_role()=='customer'){
                                ?>
                                <button type="submit" class="btn btn-default green" onclick="$('#FormTicketCardPauseAjax #emailSend').val(1);$('#FormTicketCardPauseAjax #cardbreak').val(0);$('#FormTicketCardPauseAjax #is_paused').val(1);"><?php echo lang('page_lb_apply'); ?></button>
                                <?php
                            }
                            else{
                                //if($GLOBALS['a_cardbreak_permission']['create']){
                                    ?>
                                    <button type="submit" class="btn btn-default green" onclick="$('#FormTicketCardPauseAjax #cardbreak').val(0);$('#FormTicketCardPauseAjax #is_paused').val(1);"><?php echo lang('page_lb_breaktake'); ?></button>
                                    <?php
                                //}
                            }
                            ?>

                            <button type="button" class="btn btn-default red" data-dismiss="modal" onclick="$('#FormTicketCardPauseAjax #emailSend').val(0);$('#FormTicketCardPauseAjax #cardbreak').val(0);$('#FormTicketCardPauseAjax #is_paused').val(0);"><?php echo lang('page_lb_stop'); ?></button>
            </div>
        </div><!-- /.modal-content -->
        <?php echo form_close(); ?>
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<!-- End Ticket Modal -->


<!-- Ticket Contract Order Modal -->
<div class="modal fade bs-modal-lg in" id="FormTicketContractOrderAjax" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <?php echo form_open("",array("id"=>"FormTicketContractOrderModalAjax")); ?>
        <div class="modal-content">
            <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title"></h4>
            </div>
            <div class="modal-body">

                            <!-- BEGIN PAGE MESSAGE-->
                            <?php $this->load->view('admin/alerts_modal'); ?>
                            <!-- BEGIN PAGE MESSAGE-->

                            <?php
                            $data_hidden = array('type'=>'hidden', 'name'=>'assignmentId', 'id'=>'assignmentId', 'value'=>'');
                            echo form_input($data_hidden);
                            ?>

                            <?php
                            $data_hidden = array('type'=>'hidden', 'name'=>'discountLevelId', 'id'=>'discountLevelId', 'value'=>$assignment['newdiscountlevel_id']);
                            echo form_input($data_hidden);
                            ?>

                            <?php
                            $data_hidden = array('type'=>'hidden', 'name'=>'ticketType', 'id'=>'ticketType', 'value'=>'');
                            echo form_input($data_hidden);
                            ?>

                            <?php
                            $data_hidden = array('type'=>'hidden', 'name'=>'emailSend', 'id'=>'emailSend', 'value'=>0);
                            echo form_input($data_hidden);
                            ?>

                            <!--<p class="modal-text"></p>-->
                            <div class="form-group">
                                <label><?php echo lang('page_lb_contractorder_popup_ask');?>: <span class="required"> * </span></label>
                                <?php echo form_dropdown('ratemobile', $mobilerates, '', 'class="form-control" id="order_ratemobile"');?>
                            </div>
                            <div class="form-group">
                                <label><?php echo lang('page_fl_price');?>: <b><span id="order_ratemobileprice"></span></b></label>
                            </div>
                            <div class="form-group">
                                <label><?php echo lang('page_lb_contractorder_popup_ask2');?> <span class="required"> * </span></label>
                                <?php echo form_input(array('name'=>'quantity','type'=>'number'), '', 'class="form-control"');?>
                            </div>

            </div>
            <div class="modal-footer">
                            <div class="pull-left"><a href="<?php echo base_url('admin/infodocuments');?>" target="_blank"><?php echo lang('page_lb_ultracardorder_popup_ask2');?></a></div>

                            <button type="submit" class="btn btn-default green" onclick="$('#FormTicketContractOrderAjax #emailSend').val(1);"><?php echo lang('page_lb_order'); ?></button>
                            <button type="button" class="btn btn-default red" data-dismiss="modal" onclick="$('#FormTicketContractOrderAjax #emailSend').val(0);"><?php echo lang('page_lb_stop'); ?></button>
            </div>
        </div><!-- /.modal-content -->
        <?php echo form_close(); ?>
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<!-- End Ticket Modal -->


<!-- Add/Edit Reminder Modal -->
<div class="modal fade" id="addeditReminderAjax" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <?php echo form_open("",array("id"=>"addeditReminderModalAjax")); ?>
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"></h4>
            </div>
            <div class="modal-body">

                            <!-- BEGIN PAGE MESSAGE-->
                            <?php $this->load->view('admin/alerts_modal'); ?>
                            <!-- BEGIN PAGE MESSAGE-->

                            <div class="id">
                                <?php echo form_hidden('rel_id', $assignment['assignmentnr']); ?>
                                <?php echo form_hidden('rel_type', 'assignment'); ?>
                            </div>

                            <!-- Form -->
                            <div class="form-group">
                                <label><?php echo lang('page_fl_reminddate');?> <span class="required"> * </span></label>

                                <div class="input-group date form_datetime">
                                    <?php $dd = array('name'=>'reminddate', 'class'=>'form-control', 'readonly'=>true, 'size'=>16);
                                    echo form_input($dd);?>

                                    <span class="input-group-btn">
                                        <button class="btn default date-set" type="button">
                                            <i class="fa fa-calendar"></i>
                                        </button>
                                    </span>
                                </div>

                            </div>

                            <div class="form-group">
                                <label><?php echo lang('page_fl_remindersubject');?> <span class="required"> * </span></label>
                                <?php echo form_dropdown('remindersubject', $remindersubjects, '', 'class="form-control"');?>
                            </div>

                            <div class="form-group">
                                <label><?php echo lang('page_fl_notice');?> <span class="required"> * </span></label>
                                <?php echo form_textarea('notice', '', 'class="form-control"');?>
                            </div>

                            <div class="form-group">
                                <label>
                                    <?php $dc = array('name'=>'reminderway','class'=>'form-control');
                                    echo form_checkbox($dc);?>
                                    <?php echo lang('page_fl_reminderway');?>
                                </label>
                            </div>
                            <!-- End Form -->

            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-default blue"><?php echo lang('save'); ?></button>
                                <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo lang('page_lb_close'); ?></button>
            </div>
        </div><!-- /.modal-content -->
        <?php echo form_close(); ?>
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<!-- End Add/Edit Reminder Modal -->


<!-- Add/Edit Invoice Modal -->
<div class="modal fade" id="addeditInvoiceAjax" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <?php echo form_open("",array("id"=>"addeditInvoiceModalAjax")); ?>
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"></h4>
            </div>
            <div class="modal-body">

                            <!-- BEGIN PAGE MESSAGE-->
                            <?php $this->load->view('admin/alerts_modal'); ?>
                            <!-- BEGIN PAGE MESSAGE-->

                            <div class="id">
                                <?php echo form_hidden('assignmentnr', $assignment['assignmentnr']); ?>
                            </div>

                            <!-- Form -->

                            <div class="form-group">
                                <div class="row">
                                    <div class="form-group">
                                        <label class="col-md-12"><?php echo lang('page_dt_assignment_monthyear');?> <span class="required"> * </span></label>
                                        <?php
                                        $m_monthyear = array(''=>lang('page_option_select'));
                                        foreach(range(1,12) as $mname){
                                            $m_monthyear[str_pad($mname,2,"0",STR_PAD_LEFT)] = date('M',strtotime(date('Y').'-'.str_pad($mname,2,"0",STR_PAD_LEFT).'-01'));
                                        }

                                        $y_monthyear = array(''=>lang('page_option_select'));
                                        foreach(range(date('Y')-10,(date('Y')+50)) as $yname){
                                            $y_monthyear[$yname] = $yname;
                                        }
                                        ?>

                                        <div class="col-md-6 col-sm-6">
                                            <?php echo form_dropdown('m_monthyear', $m_monthyear, '', 'class="form-control"');?>
                                        </div>
                                        <!--<label class="col-md-1 col-sm-1 control-label">-</label>-->
                                        <div class="col-md-6 col-sm-6">
                                            <?php echo form_dropdown('y_monthyear', $y_monthyear, '', 'class="form-control"');?>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label><?php echo lang('page_dt_assignment_invoicenr');?> <span class="required"> * </span></label>
                                <?php echo form_input('invoicenr', '', 'class="form-control"');?>
                            </div>
                            <div class="form-group">
                                <label><?php echo lang('page_dt_assignment_description');?> <span class="required"> * </span></label>
                                <?php echo form_input('description', '', 'class="form-control"');?>
                            </div>
                            <div class="form-group">
                                <label><?php echo lang('page_dt_assignment_netamount');?> <span class="required"> * </span></label>
                                <?php echo form_input(array('name'=>'netamount','type'=>'number'), '', 'class="form-control"');?>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group"><label><?php echo lang('page_dt_assignment_invoicefile');?> <span class="required"> * </span></label>
                                        <div class="clearfix"></div>
                                        <div class="fileinput fileinput-new" data-provides="fileinput">
                                            <div>
                                                <span class="btn btn-sm btn-default m-t-n-xs btn-file">
                                                    <span class="fileinput-new"> Select </span>
                                                    <span class="fileinput-exists"> <?php echo lang('page_lb_change');?> </span>
                                                    <input type="file" name="invoicefile" accept="application/pdf">
                                                </span>
                                                <a href="javascript:;" class="btn btn-sm btn-primary m-t-n-xs fileinput-exists" data-dismiss="fileinput"> <?php echo lang('page_lb_remove');?> </a>
                                            </div>
                                            <div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 160px; max-height: 160px;"></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group"><label><?php echo lang('page_dt_assignment_invoicefilecsv');?></label>
                                        <div class="clearfix"></div>
                                        <div class="fileinput fileinput-new" data-provides="fileinput">
                                            <div>
                                                <span class="btn btn-sm btn-default m-t-n-xs btn-file">
                                                    <span class="fileinput-new"> Select </span>
                                                    <span class="fileinput-exists"> <?php echo lang('page_lb_change');?> </span>
                                                    <input type="file" name="invoicefilecsv" accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel" >
                                                </span>
                                                <a href="javascript:;" class="btn btn-sm btn-primary m-t-n-xs fileinput-exists" data-dismiss="fileinput"> <?php echo lang('page_lb_remove');?> </a>
                                            </div>
                                            <div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 160px; max-height: 160px;"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- End Form -->

            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-default blue"><?php echo lang('save'); ?></button>
                                <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo lang('page_lb_close'); ?></button>
            </div>
        </div><!-- /.modal-content -->
        <?php echo form_close(); ?>
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<!-- End Add/Edit Invoice Modal -->

<!-- Ticket Card Pause Modal -->
<div class="modal fade bs-modal-lg in" id="FormExternalHardware" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <?php echo form_open(base_url("admin/assignments/addExternalHardware"),array("id"=>"FormExternalHardwareModalAjax")); ?>
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Externe Hardware hinzufügen</h4>
            </div>
            <div class="modal-body">

                <!-- BEGIN PAGE MESSAGE-->
                <?php $this->load->view('admin/alerts_modal'); ?>
                <!-- BEGIN PAGE MESSAGE-->

                <?php
                echo form_input(array('type'=>'hidden', 'name'=>'assignmentnr', 'id'=>'assignmentnr', 'value'=>''));
                echo form_input(array('type'=>'hidden', 'name'=>'id', 'id'=>'id', 'value'=>''));
                ?>

                <div class="form-group">
                    <label><?php echo lang('page_fl_category');?></label>
                    <?php echo form_dropdown('hardwarecategory', $hardwarecategories, '', 'class="form-control" id="hardwarecategory"');?>
                </div>

                <div class="form-group">
                    <label><?php echo lang('page_lb_hardwareorder_popup_ask');?>: <span class="required"> * </span></label>
                    <select name="hardware" id="hardware" class="form-control" required="true">
                        <option value=""><?php echo lang('page_option_select') ?></option>
                        <?php foreach ($hardware_data as $key => $value): ?>
                            <option value="<?php echo $value['hardwarenr']; ?>" data-hardwarecategory="<?php echo $value['hardwarecategory']; ?>"><?php echo $value['hardwaretitle']; ?></option>
                        <?php endforeach ?>
                    </select>
                </div>

                <div class="form-group">
                    <label><?php echo lang('page_fl_seriesnr');?></label>
                    <?php echo form_input('seriesnr', '', 'class="form-control"');?>
                </div>

            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-default green"><?php echo lang('save'); ?></button>
                <button type="button" class="btn btn-default red" data-dismiss="modal"><?php echo lang('cancel'); ?></button>
            </div>
        </div><!-- /.modal-content -->
        <?php echo form_close(); ?>
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<!-- End Ticket Modal -->

<script>
function datapicker(){
    jQuery(".form_date1").datepicker({
        autoclose: true,
        isRTL: App.isRTL(),
        format: "dd.mm.yyyy",
        pickerPosition: (App.isRTL() ? "bottom-right" : "bottom-left")
    });
    jQuery(".form_date").datepicker({
        autoclose: true,
        isRTL: App.isRTL(),
        format: "dd.mm.yyyy",
        pickerPosition: (App.isRTL() ? "bottom-right" : "bottom-left")
    });
}
function datepicker_vvlneu(selectedvalue, rown,rowt){
    if(selectedvalue=='VVL'){
        datapicker();
        /*jQuery("#"+rowt+"_form_date_"+rown).datepicker('setStartDate',"<?php echo date("d.m.Y",strtotime("-3 Months"));?>");
        jQuery("#"+rowt+"_form_date_"+rown).datepicker('setEndDate',"<?php echo date("d.m.Y",strtotime("+3 Months"));?>");*/

        jQuery("#"+rowt+"_form_date_"+rown).datepicker('setStartDate');
        jQuery("#"+rowt+"_form_date_"+rown).datepicker('setEndDate');
        if(rowt=='new'){
            //jQuery("#"+rowt+"_form_date_"+rown+ " input").val("<?php echo date("d.m.Y");?>");

            //v1
            //jQuery("#"+rowt+"_form_date_"+rown+ " input").removeClass('noerror');

            //v2
            jQuery("#"+rowt+"_form_date_"+rown+ " input").addClass('noerror');
        }
    }
    else{
        jQuery("#"+rowt+"_form_date_"+rown).datepicker('setStartDate',0);
        jQuery("#"+rowt+"_form_date_"+rown).datepicker('remove');
        jQuery("#"+rowt+"_form_date_"+rown+ " input").val('');

        //if(rowt=='new'){
            jQuery("#"+rowt+"_form_date_"+rown+ " input").addClass('noerror');
        //}
    }
}
function datetimepicker(){
    //Date & Time Picker Initialize
    jQuery(".form_datetime").datetimepicker({
        autoclose: true,
        isRTL: App.isRTL(),

        <?php
        $time_format = get_option('time_format');
        if($time_format == 24){
            ?>
            format: "dd.mm.yyyy"+" hh:ii:ss",
            <?php
        }
        else{
            ?>
            format: "dd.mm.yyyy"+" HH:ii:ss P",
            showMeridian: true,
            <?php
        }
        ?>

        pickerPosition: (App.isRTL() ? "bottom-right" : "bottom-left")
    });
}
function extraFieldsValidate_form(){
    var isValid = true;


    jQuery('#assignmentproduct_inputbox select').each(function() {
        if(!jQuery(this).hasClass("noerror")){
            if($(this).val() == "" && $(this).val().length < 1) {
                $(this).addClass('field_error');
                isValid = false;
            } else {
                $(this).removeClass('field_error');
            }
        }
    });

    jQuery('#assignmentproduct_inputbox input').each(function() {
        if(jQuery(this).attr('type')!='radio'){
            if(!jQuery(this).hasClass("noerror")){
                if($(this).val() == "" && $(this).val().length < 1) {
                    $(this).addClass('field_error');
                    isValid = false;
                } else {
                    $(this).removeClass('field_error');
                }
            }
        }
    });

    //Check Legitimation is uploaded or not
    <?php
    if(isset($assignment['assignmentnr'])){
        ?>
        if(isValid){
            var assignmentstatus = $("#assignmentstatus").val();
            var count = $("#assignment_legitimations a").children().length;
            if(count==0 && assignmentstatus==3){
                showtoast('error','','<?php echo lang('page_lb_please_upload_legitimation')?>');
                isValid = false;
            }
        }
        <?php
    }
    ?>

    return isValid;
}
function extraFieldsValidate(){
    var isValid = true;

    jQuery('#assignmentproduct_inputbox select').each(function() {
        if(!jQuery(this).hasClass("noerror")){
            if($(this).val() == "" && $(this).val().length < 1) {
                $(this).addClass('field_error');
                isValid = false;
            } else {
                $(this).removeClass('field_error');
            }
        }
    });

    jQuery('#assignmentproduct_inputbox input').each(function() {
        if(jQuery(this).attr('type')!='radio'){
            if(!jQuery(this).hasClass("noerror")){
                if($(this).val() == "" && $(this).val().length < 1) {
                    $(this).addClass('field_error');
                    isValid = false;
                } else {
                    $(this).removeClass('field_error');
                }
            }
        }
    });

    return isValid;
}

function Dropzoneload(){
    Dropzone.options.myDropzone = {

        dictDefaultMessage: '<h3 class="sbold"><?php echo lang('page_lb_drop_files_here_to_upload');?></h3>',
        dictFallbackMessage: '<?php echo lang('page_lb_browser_not_support_drag_and_drop');?>',
        dictFileTooBig: '<?php echo lang('page_lb_file_exceeds_maxfile_size_in_form');?>',
        dictCancelUpload: '<?php echo lang('page_lb_cancel_upload');?>',
        dictRemoveFile: '<?php echo lang('page_lb_remove_file');?>',
        dictMaxFilesExceeded: '<?php lang('page_lb_you_can_not_upload_any_more_files');?>',
       // maxFilesize: (<?php echo file_upload_max_size();?> / (1024 * 1024)).toFixed(0),
        acceptedFiles: '<?php echo get_option('allowed_files');?>',

        init: function() {

            this.on("sending", function(file, xhr, formData) {
                formData.append("categoryid", jQuery('#documentcategory').val()); // Append all the additional input data of your form here!
            });

            this.on('error', function(file, response) {
                //Showtoast Messagebox
                showtoast('error','',response);
            });

            this.on('success', function(file, response) {
                this.removeFile(file);

                //Showtoast Messagebox
                showtoast('success','','<?php echo lang('page_lb_file_uploaded_success');?>');

                //Get New Refresh Lead Document List & Legitimation List
                <?php
                if(isset($assignment['assignmentnr'])){
                    ?>
                    // jQuery.ajax({url: '<?php //echo base_url('admin/assignments/getDocuments/'.$assignment['assignmentnr']);?>', success: function(result){
                    //     jQuery('#assignment_attachments').html(result);
                    // }});

                    jQuery.ajax({url: '<?php echo base_url('admin/assignments/getLegitimations/'.$assignment['assignmentnr']);?>', success: function(result){
                        jQuery('#assignment_legitimations').html(result);
                    }});
                    <?php
                }
                ?>

                // jQuery.ajax({url: '<?php //echo base_url('admin/assignments/getLegitimations/'.$assignment['assignmentnr']);?>', success: function(result){
                //     jQuery('#assignment_legitimations').html(result);
                // }});

                var table = jQuery('#'+datatable_id_2).DataTable();
                table.ajax.reload();

                var table = jQuery('#hardwarepositiondocument_datatable_ajax').DataTable();
                table.ajax.reload();

            });

            /*this.on("complete", function(file) {
                this.removeAllFiles(true);
            });*/

            this.on("addedfile", function(file) {
                // Create the remove button
                var removeButton = Dropzone.createElement("<a href='javascript:;'' class='btn red btn-sm btn-block'><?php echo lang('page_lb_remove');?></a>");

                // Capture the Dropzone instance as closure.
                var _this = this;

                // Listen to the click event
                removeButton.addEventListener("click", function(e) {
                  // Make sure the button click doesn't submit the form:
                  e.preventDefault();
                  e.stopPropagation();

                  // Remove the file preview.
                  _this.removeFile(file);
                  // If you want to the delete the file on the server as well,
                  // you can do the AJAX request here.
                });

                // Add the button to the file preview element.
                file.previewElement.appendChild(removeButton);
            });
        }
    }
}
function Dropzoneload2(eid){
    jQuery("#"+eid).dropzone({

        dictDefaultMessage: '<h3 class="sbold"><?php echo lang('page_lb_drop_files_here_to_upload');?></h3>',
        dictFallbackMessage: '<?php echo lang('page_lb_browser_not_support_drag_and_drop');?>',
        dictFileTooBig: '<?php echo lang('page_lb_file_exceeds_maxfile_size_in_form');?>',
        dictCancelUpload: '<?php echo lang('page_lb_cancel_upload');?>',
        dictRemoveFile: '<?php echo lang('page_lb_remove_file');?>',
        dictMaxFilesExceeded: '<?php lang('page_lb_you_can_not_upload_any_more_files');?>',
        // maxFilesize: (<?php echo file_upload_max_size();?> / (1024 * 1024)).toFixed(0),
        acceptedFiles: '<?php echo get_option('allowed_files');?>',

        init: function() {

            this.on("sending", function(file, xhr, formData) {
                //formData.append("categoryid", jQuery('#documentcategory').val()); // Append all the additional input data of your form here!
            });

            this.on('error', function(file, response) {
                //Showtoast Messagebox
                showtoast('error','',response);
            });

            this.on('success', function(file, response) {
                this.removeFile(file);

                //Showtoast Messagebox
                showtoast('success','','<?php echo lang('page_lb_file_uploaded_success');?>');

                var table = jQuery('#hardwarepositiondocument_datatable_ajax').DataTable();
                table.ajax.reload();
            });

            /*this.on("complete", function(file) {
                this.removeAllFiles(true);
            });*/

            this.on("addedfile", function(file) {
                // Create the remove button
                var removeButton = Dropzone.createElement("<a href='javascript:;'' class='btn red btn-sm btn-block'><?php echo lang('page_lb_remove');?></a>");

                // Capture the Dropzone instance as closure.
                var _this = this;

                // Listen to the click event
                removeButton.addEventListener("click", function(e) {
                  // Make sure the button click doesn't submit the form:
                  e.preventDefault();
                  e.stopPropagation();

                  // Remove the file preview.
                  _this.removeFile(file);
                  // If you want to the delete the file on the server as well,
                  // you can do the AJAX request here.
                });

                // Add the button to the file preview element.
                file.previewElement.appendChild(removeButton);
            });
        }
    });
}

jQuery(document).ready(function() {
    jQuery("#documentcategory").find("option").eq(0).remove();
    jQuery(".newratemobile option:first").text('<?php echo lang("page_lb_select_mobile_rate");?>');
    jQuery(".newoptionmobile option:first").text('<?php echo lang("page_lb_select_mobile_option");?>');
    jQuery(".more_newoptionmobile option:first").text('<?php echo lang("page_lb_select_mobile_option");?>');

    //Date Picker Initialize
    datapicker();
    datetimepicker();
    Dropzoneload();

    //Delete Reminder/Comment Submit by Ajax
    jQuery("#deleteModalAjax").submit(function(e) {
        var form = jQuery(this);
        var url = form.attr('action');
        Pace.track(function(){
            Pace.restart();
            jQuery.ajax({
               type: "POST",
               url: url,
               data: form.serialize(), // serializes the form's elements.
               dataType: "JSON",
               success: function(data){
                    //show response from the php script.
                    //Showtoast Messagebox
                    showtoast(data.response,'',data.message);

                    if(url.indexOf('deleteDocument') != -1){

                        //Get New Refresh Assignment Document List
                        <?php
                        /*if(isset($assignment['assignmentnr'])){
                            ?>
                            jQuery.ajax({url: '<?php echo base_url('admin/assignments/getDocuments/'.$assignment['assignmentnr']);?>', success: function(result){
                                jQuery('#assignment_attachments').html(result);
                            }});
                            <?php
                        }*/
                        ?>

                        var table = jQuery('#'+datatable_id_2).DataTable();
                        table.ajax.reload();

                        jQuery('#deleteConfirmationAjax').modal('hide');

                    }
                    else if(url.indexOf('deleteLegitimation') != -1){

                        //Get New Refresh Assignment Legitimation List
                        <?php
                        if(isset($assignment['assignmentnr'])){
                            ?>
                            jQuery.ajax({url: '<?php echo base_url('admin/assignments/getLegitimations/'.$assignment['assignmentnr']);?>', success: function(result){
                                jQuery('#assignment_legitimations').html(result);
                            }});
                            <?php
                        }
                        ?>

                        jQuery('#deleteConfirmationAjax').modal('hide');

                    }
                    else if(url.indexOf('deleteAssignmentProduct') != -1){
                        if(data.response=='success'){
                            jQuery('#row1_old_assignmentproduct_'+data.dataid).remove();
                            jQuery('#row3_old_assignmentproduct_'+data.dataid).remove();
                        }
                        jQuery('#deleteConfirmationAjax').modal('hide');
                    }
                    else if(url.indexOf('deleteReminder') != -1){

                        var table = jQuery('#'+datatable_id).DataTable();
                        table.ajax.reload();

                        jQuery('#deleteConfirmationAjax').modal('hide');

                    }
                    else if(url.indexOf('deleteInvoice') != -1){

                        var table = jQuery('#'+datatable_id_3).DataTable();
                        table.ajax.reload();

                        jQuery('#deleteConfirmationAjax').modal('hide');

                    }
                    else if(url.indexOf('deleteHardwarePositionDocument') != -1){

                        var table = jQuery('#hardwarepositiondocument_datatable_ajax').DataTable();
                        table.ajax.reload();

                        jQuery('#deleteConfirmationAjax').modal('hide');

                    }
               }
        });
        });
        e.preventDefault(); // avoid to execute the actual submit of the form.
    });

    //Add/Edit Reminder Submit by Ajax
    jQuery("#addeditReminderModalAjax").submit(function(e) {
        var form = jQuery(this);
        var url = form.attr('action');

        // check if the input is valid
        if(!form.valid()){
            return false;
        }

        Pace.track(function(){
            Pace.restart();
            jQuery.ajax({
               type: "POST",
               url: url,
               data: form.serialize(), // serializes the form's elements.
               dataType: "JSON",
               success: function(data){
                    //show response from the php script.
                    //Showtoast Messagebox
                    showtoast(data.response,'',data.message);

                    var table = jQuery('#'+datatable_id).DataTable();
                    table.ajax.reload();

                    jQuery('#addeditReminderAjax').modal('hide');
               }
        });
        });
        e.preventDefault(); // avoid to execute the actual submit of the form.
    });


    //Add/Edit Invoice Submit by Ajax
    jQuery("#addeditInvoiceModalAjax").submit(function(e) {
        var form = jQuery(this);
        var url = form.attr('action');

        // check if the input is valid
        if(!form.valid()){
            return false;
        }

        var formData = new FormData(jQuery(this)[0]);

        Pace.track(function(){
            Pace.restart();
            jQuery.ajax({
               type: "POST",
               url: url,
               data: formData, // serializes the form's elements.
               dataType: "JSON",

               cache: false,
               contentType: false,
               processData: false,

               success: function(data){
                    //show response from the php script.
                    //Showtoast Messagebox
                    showtoast(data.response,'',data.message);

                    var table = jQuery('#'+datatable_id_3).DataTable();
                    table.ajax.reload();

                    jQuery('#addeditInvoiceAjax').modal('hide');
               }
        });
        });
        e.preventDefault(); // avoid to execute the actual submit of the form.
    });

    //Generate Ticket Submit by Ajax
    jQuery("#FormTicketModalAjax").submit(function(e) {

        jQuery('#FormTicketAjax').modal('hide');
        var form = jQuery(this);
        var url = form.attr('action');
        var error1 = jQuery('#FormTicketAjax #alert_modal .alert-danger');

        var ticketType = jQuery("#FormTicketModalAjax #ticketType").val();
        var emailSend = $('#FormTicketAjax #emailSend').val();
        if(ticketType=='cardpause2' && emailSend==0){
            return false;
        }

        Pace.track(function(){
            Pace.restart();
            jQuery.ajax({
               type: "POST",
               url: url,
               data: form.serialize(), // serializes the form's elements.
               dataType: "JSON",
               beforeSend:function(){
                    $('#pageloaddiv').show();
               },
               success: function(data){
                    $('#pageloaddiv').hide();

                    //show response from the php script.
                    //Showtoast Messagebox
                    if(data.response=='success'){
                        showtoast(data.response,'',data.message);

                        if(ticketType=='cardpause2'){
                            window.location.reload();
                        }
                    }
                    else{
                        showtoast(data.response,'',data.message);
                    }
               }
        });
        });
        e.preventDefault(); // avoid to execute the actual submit of the form.
    });


    //Generate Ticket Mobile Option Submit by Ajax
    jQuery("#FormTicketMobileOptionModalAjax").submit(function(e) {

        //jQuery('#FormTicketMobileOptionAjax').modal('hide');
        var form = jQuery(this);
        var url = form.attr('action');
        var error1 = jQuery('#FormTicketMobileOptionAjax #alert_modal .alert-danger');

        // check if the input is valid
        if(!form.valid()){
            return false;
        }

        jQuery('#FormTicketMobileOptionAjax').modal('hide');
        Pace.track(function(){
            Pace.restart();
            jQuery.ajax({
               type: "POST",
               url: url,
               data: form.serialize(), // serializes the form's elements.
               dataType: "JSON",
               success: function(data){
                    //show response from the php script.
                    //Showtoast Messagebox
                    if(data.response=='success'){
                        showtoast(data.response,'',data.message);
                        jQuery('#FormTicketMobileOptionAjax').modal('hide');
                    }
                    else{
                        showtoast(data.response,'',data.message);
                    }
               }
        });
        });
        e.preventDefault(); // avoid to execute the actual submit of the form.
    });


    //Generate Ticket Hardware Assignment Submit by Ajax
    jQuery("#FormTicketHardwareOrderModalAjax").submit(function(e) {

        //jQuery('#FormTicketHardwareOrderAjax').modal('hide');
        var form = jQuery(this);
        var url = form.attr('action');
        var error1 = jQuery('#FormTicketHardwareOrderAjax #alert_modal .alert-danger');

        // check if the input is valid
        if(!form.valid()){
            return false;
        }

        jQuery('#FormTicketHardwareOrderAjax').modal('hide');
        Pace.track(function(){
            Pace.restart();
            jQuery.ajax({
               type: "POST",
               url: url,
               data: form.serialize(), // serializes the form's elements.
               dataType: "JSON",
               success: function(data){
                    //show response from the php script.
                    //Showtoast Messagebox
                    if(data.response=='success'){
                        showtoast(data.response,'',data.message);
                        jQuery('#FormTicketHardwareOrderAjax').modal('hide');
                    }
                    else{
                        showtoast(data.response,'',data.message);
                    }
               }
        });
        });
        e.preventDefault(); // avoid to execute the actual submit of the form.
    });


    //Generate Ticket Contract Assignment Submit by Ajax
    jQuery("#FormTicketContractOrderModalAjax").submit(function(e) {

        //jQuery('#FormTicketContractOrderAjax').modal('hide');
        var form = jQuery(this);
        var url = form.attr('action');
        var error1 = jQuery('#FormTicketContractOrderAjax #alert_modal .alert-danger');

        // check if the input is valid
        if(!form.valid()){
            return false;
        }

        jQuery('#FormTicketContractOrderAjax').modal('hide');
        Pace.track(function(){
            Pace.restart();
            jQuery.ajax({
               type: "POST",
               url: url,
               data: form.serialize(), // serializes the form's elements.
               dataType: "JSON",
               success: function(data){
                    //show response from the php script.
                    //Showtoast Messagebox
                    if(data.response=='success'){
                        showtoast(data.response,'',data.message);
                        jQuery('#FormTicketContractOrderAjax').modal('hide');
                    }
                    else{
                        showtoast(data.response,'',data.message);
                    }
               }
        });
        });
        e.preventDefault(); // avoid to execute the actual submit of the form.
    });


    //Generate Ticket Hardware Assignment Submit by Ajax
    jQuery("#FormTicketCardPauseModalAjax").submit(function(e) {

        //jQuery('#FormTicketCardPauseAjax').modal('hide');
        var form = jQuery(this);
        var url = form.attr('action');
        var error1 = jQuery('#FormTicketCardPauseAjax #alert_modal .alert-danger');

        // check if the input is valid
        if(!form.valid()){
            return false;
        }

        jQuery('#FormTicketCardPauseAjax').modal('hide');
        Pace.track(function(){
            Pace.restart();
            jQuery.ajax({
               type: "POST",
               url: url,
               data: form.serialize(), // serializes the form's elements.
               dataType: "JSON",
               beforeSend:function(){
                   $('#pageloaddiv').show();
               },
               success: function(data){
                    $('#pageloaddiv').hide();

                    //show response from the php script.
                    //Showtoast Messagebox
                    if(data.response=='success'){
                        showtoast(data.response,'',data.message);

                        jQuery('#FormTicketCardPauseAjax').modal('hide');
                        var cardbreak = $('#cardbreak').val();
                        if(cardbreak==1){
                            window.location.reload();
                        }

                        var is_paused = $('#is_paused').val();
                        if(is_paused==1){
                            window.location.reload();
                        }
                    }
                    else{
                        showtoast(data.response,'',data.message);
                    }
               }
        });
        });
        e.preventDefault(); // avoid to execute the actual submit of the form.
    });




    //Generate Ticket Submit by Ajax
    jQuery("#FormTicketCardOrderModalAjax").submit(function(e) {

        jQuery('#FormTicketCardOrderAjax').modal('hide');
        var form = jQuery(this);
        var url = form.attr('action');
        var error1 = jQuery('#FormTicketCardOrderAjax #alert_modal .alert-danger');

        Pace.track(function(){
            Pace.restart();
            jQuery.ajax({
               type: "POST",
               url: url,
               data: form.serialize(), // serializes the form's elements.
               dataType: "JSON",
               beforeSend:function(){
                    $('#pageloaddiv').show();
               },
               success: function(data){
                    $('#pageloaddiv').hide();

                    //show response from the php script.
                    //Showtoast Messagebox
                    if(data.response=='success'){
                        showtoast(data.response,'',data.message);
                    }
                    else{
                        showtoast(data.response,'',data.message);
                    }
               }
        });
        });
        e.preventDefault(); // avoid to execute the actual submit of the form.
    });


    //Get Responsibles (Users of Customer) by Ajax
    var selected_responsible = '<?php echo isset($assignment['responsible'])?$assignment['responsible']:'';?>'
    jQuery("#customer").change( function(){
        var custid = jQuery(this).val();
        jQuery("#responsible").html("<option value=''><?php echo lang('page_option_wait');?></option>");
        jQuery.ajax({dataType: "JSON", url: '<?php echo base_url('admin/assignments/getResponsibleOfCustomer/');?>'+custid, success: function(data){
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

    <?php
    if(isset($assignment['assignmentnr'])){
        ?>
        jQuery("#customer").change();
        <?php
    }
    ?>


    //Add/Edit Reminder Submit by Ajax
    jQuery("#save_employees").click(function() {
        var form = jQuery('#form_assignment_employee');
        var url = form.attr('action');

        // check if the input is valid
        if(!extraFieldsValidate()){
            return false;
        }

        Pace.track(function(){
            Pace.restart();
            jQuery.ajax({
                type: "POST",
                url: url,
                data: form.serialize(), // serializes the form's elements.
                dataType: "JSON",
                success: function(data){
                     //show response from the php script.
                     //Showtoast Messagebox
                     showtoast(data.response,'',data.message);

                     //var table = jQuery('#'+datatable_id).DataTable();
                     //table.ajax.reload();

                     jQuery('#addeditReminderAjax').modal('hide');
                }
             });
        });

    });


    //Get Running Time of Mobile Option
    jQuery('#FormTicketMobileOptionAjax #book_mobileoption').change(function() {
        var temp = jQuery(this).val();
        var temp1 = temp.split('=');
        jQuery('#FormTicketMobileOptionAjax #book_runningtime').html(temp1[2]);

        if(temp1[0]!=""){
            var hid = temp1[0];
        }else{
            var hid = 0;
        }
        var url = '<?php echo base_url('admin/assignments/getHardwareMobileOptionValue/')?>'+hid;

        Pace.track(function(){
            Pace.restart();
            jQuery.ajax({
               type: "POST",
               url: url,
               beforeSend:function(){
                    jQuery('#FormTicketMobileOptionAjax #book_price').html("<img src='<?php echo base_url('assets/global/img/loading.gif');?>' />");
               },
               success: function(data){
                    jQuery('#FormTicketMobileOptionAjax #book_price').html(data);
               }
            });
        });
    });


    //Get Value of Hardware Assignment
    jQuery('#FormTicketHardwareOrderAjax #order_hardware').change(function() {
        var assignmentProductId = $('#FormTicketHardwareOrderAjax #assignmentProductId').val();
        var discountLevelId = $('#FormTicketHardwareOrderAjax #discountLevelId').val();

        var temp = jQuery(this).val();
        var temp1 = temp.split('=');
        if(temp1[0]!=""){
            var hid = temp1[0];
        }else{
            var hid = 0;
        }
        var url = '<?php echo base_url('admin/assignments/getHardwareAssignmentValue/')?>'+hid+'/'+assignmentProductId+'/'+discountLevelId;

        Pace.track(function(){
            Pace.restart();
            jQuery.ajax({
               type: "POST",
               url: url,
               beforeSend:function(){
                    jQuery('#FormTicketHardwareOrderAjax #order_hardwareprice').html("<img src='<?php echo base_url('assets/global/img/loading.gif');?>' />");
               },
               success: function(data){
                    jQuery('#FormTicketHardwareOrderAjax #order_hardwareprice').html(data);
               }
            });
        });
    });


    //Get Value of Contract Order
    jQuery('#FormTicketContractOrderAjax #order_ratemobile').change(function() {

        var discountLevelId = $('#FormTicketContractOrderAjax #discountLevelId').val();

        var temp = jQuery(this).val();
        var temp1 = temp.split('=');
        if(temp1[0]!=""){
            var hid = temp1[0];
        }else{
            var hid = 0;
        }
        var url = '<?php echo base_url('admin/assignments/getContractAssignmentValue/')?>'+hid+'/'+discountLevelId;

        Pace.track(function(){
            Pace.restart();
            jQuery.ajax({
               type: "POST",
               url: url,
               beforeSend:function(){
                    jQuery('#FormTicketContractOrderAjax #order_ratemobileprice').html("<img src='<?php echo base_url('assets/global/img/loading.gif');?>' />");
               },
               success: function(data){
                    jQuery('#FormTicketContractOrderAjax #order_ratemobileprice').html(data);
               }
            });
        });
    });

});

//Reminder Modal
function addeditReminderAjax(url, id, title){
    $('#addeditReminderAjax').modal('show');
    $('#addeditReminderAjax #addeditReminderModalAjax').attr('action',url+'/'+id);
    $('#addeditReminderAjax .modal-title').html('<i class="fa fa-bell-o"></i> '+title);

    /* Initialise for Edit */
    $('#addeditReminderModalAjax')[0].reset();
    $.uniform.update('#addeditReminderModalAjax input[name=reminderway]');

    //Clear validation
    $("span.help-block-error").hide();
    $(".has-error").removeClass("has-error");
    $("#"+inner_msg_id2+" .alert").hide();

    if(id>0){
        Pace.track(function(){
            Pace.restart();
            jQuery.ajax({dataType: "JSON", url: '<?php echo base_url('admin/assignmentreminders/getReminder/');?>'+id, success: function(data){

            $('#addeditReminderModalAjax input[name=reminddate]').val(data.reminddate);

            if(data.reminderway==1){
                $('#addeditReminderModalAjax input[name=reminderway]').prop('checked', true);
            }
            else{
                $('#addeditReminderModalAjax input[name=reminderway]').prop('checked', false);
            }
            $.uniform.update('#addeditReminderModalAjax input[name=reminderway]');

            $('#addeditReminderModalAjax select[name=remindersubject]').val(data.remindersubject);
            $('#addeditReminderModalAjax textarea[name=notice]').val(data.notice);
        }});
        });
    }
}

//Invoice Modal
function addeditInvoiceAjax(url, id, title){
    $('#addeditInvoiceAjax').modal('show');
    $('#addeditInvoiceAjax #addeditInvoiceModalAjax').attr('action',url+'/'+id);
    $('#addeditInvoiceAjax .modal-title').html('<i class="fa fa-file-pdf-o"></i> '+title);

    /* Initialise for Edit */
    $('#addeditInvoiceModalAjax')[0].reset();

    //Clear validation
    $("span.help-block-error").hide();
    $(".has-error").removeClass("has-error");
    $("#"+inner_msg_id3+" .alert").hide();
}

//Generate Ticket Modal
function FormTicketAjax(url,id,tickettype,title,text,productid){
    $('#FormTicketAjax').modal('show');
    $('#FormTicketAjax #FormTicketModalAjax').attr('action',url);
    $('#FormTicketAjax #assignmentId').val(id);
    $('#FormTicketAjax #assignmentProductId').val(productid);
    $('#FormTicketAjax #ticketType').val(tickettype);
    $('#FormTicketAjax .modal-title').html('<i class="fa fa-life-ring"></i> '+title);
    $('#FormTicketAjax .modal-text').html(text);

    $('#FormTicketAjax #btn_cardbreak').hide();
    $('#FormTicketAjax #btn_yes').removeClass('red');
    $('#FormTicketAjax #btn_yes').addClass('green');

    if(tickettype=='cardpause2'){
        <?php
        if(get_user_role()=='customer'){
            ?>
            $('#FormTicketAjax #btn_cardbreak').hide();
            $('#FormTicketAjax #btn_yes').html('<?php echo lang('page_lb_apply');?>');
            $('#FormTicketAjax #btn_yes').removeClass('red');
            $('#FormTicketAjax #btn_yes').addClass('green');
            <?php
        }else{
            ?>
            $('#FormTicketAjax #btn_cardbreak').show();
            //$('#FormTicketAjax #btn_yes').html('<?php echo lang('page_lb_breaklaid');?>');
            $('#FormTicketAjax #btn_yes').html('<?php echo lang('page_lb_cardpause2');?>');
            $('#FormTicketAjax #btn_yes').removeClass('green');
            $('#FormTicketAjax #btn_yes').addClass('red');
            <?php
        }
        ?>
        $('#FormTicketAjax #btn_no').html('<?php echo lang('page_lb_stop');?>');
    }else{
        var searchArr = ['subscriptionlock','subscriptionlock2','cardlock','abolock', 'internationaltelephonylock', 'roaminglock'];
        // if(tickettype=='subscriptionlock' || tickettype=='subscriptionlock2' || tickettype=='cardlock'){
        if(jQuery.inArray(tickettype, searchArr)!='-1'){
            $('#FormTicketAjax #btn_no').attr('type','button');
            $('#FormTicketAjax #btn_no').attr('data-dismiss','modal');
        }else{
            // $('#FormTicketAjax #btn_no').attr('type','submit');
            // $('#FormTicketAjax #btn_no').removeAttr('data-dismiss');
        }

        $('#FormTicketAjax #btn_yes').html('<?php echo lang('page_lb_yes');?>');
        $('#FormTicketAjax #btn_no').html('<?php echo lang('page_lb_no');?>');
    }
}


//Validation Form
function TicketMobileOptionFormValidation(){

    var form1 = $('#FormTicketMobileOptionModalAjax');
    var error1 = $('#FormTicketMobileOptionAjax #alert_modal .alert-danger');
    var success1 = $('#FormTicketMobileOptionAjax #alert_modal .alert-success');

    form1.validate({
        errorElement: 'span', //default input error message container
        errorClass: 'help-block help-block-error', // default input error message class
        focusInvalid: false, // do not focus the last invalid input
        ignore: "",  // validate all fields including form hidden input

        rules: {

            mobileoption: {
                required: true
            },

        },

        invalidHandler: function (event, validator) { //display error alert on form submit
                success1.hide();
                error1.show();
                App.scrollTo(error1, -200);
                $('#FormTicketMobileOptionAjax').animate({ scrollTop: 0 }, 'fast');
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
            $('#FormTicketMobileOptionAjax').animate({ scrollTop: 0 }, 'fast');
            return true;
        }
    });
}

//Generate Ticket Mobile Option Modal
function FormTicketMobileOptionAjax(url,id,tickettype,title,productid){
    $('#FormTicketMobileOptionAjax').modal('show');
    $('#FormTicketMobileOptionAjax #FormTicketMobileOptionModalAjax').attr('action',url);
    $('#FormTicketMobileOptionAjax #assignmentId').val(id);
    $('#FormTicketMobileOptionAjax #assignmentProductId').val(productid);
    $('#FormTicketMobileOptionAjax #ticketType').val(tickettype);
    $('#FormTicketMobileOptionAjax .modal-title').html('<i class="fa fa-life-ring"></i> '+title);

    $('#FormTicketMobileOptionModalAjax')[0].reset();
    $('#FormTicketMobileOptionAjax #book_price').html('');
    $('#FormTicketMobileOptionAjax #book_runningtime').html('');

    //Clear validation
    $("span.help-block-error").hide();
    $(".has-error").removeClass("has-error");
    var error1 = jQuery('#FormTicketMobileOptionAjax #alert_modal .alert-danger');
    $(error1).hide();

    //Validation Form
    var validfunc = 'TicketMobileOption';
    if (typeof validfunc !== 'undefined') {
        eval(validfunc + "FormValidation()");
    }
}


//Validation Form
function TicketHardwareOrderFormValidation(){

    var form1 = $('#FormTicketHardwareOrderModalAjax');
    var error1 = $('#FormTicketHardwareOrderAjax #alert_modal .alert-danger');
    var success1 = $('#FormTicketHardwareOrderAjax #alert_modal .alert-success');

    form1.validate({
        errorElement: 'span', //default input error message container
        errorClass: 'help-block help-block-error', // default input error message class
        focusInvalid: false, // do not focus the last invalid input
        ignore: "",  // validate all fields including form hidden input

        rules: {

            hardware: {
                required: true
            },

        },

        invalidHandler: function (event, validator) { //display error alert on form submit
                success1.hide();
                error1.show();
                App.scrollTo(error1, -200);
                $('#FormTicketHardwareOrderAjax').animate({ scrollTop: 0 }, 'fast');
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
            $('#FormTicketHardwareOrderAjax').animate({ scrollTop: 0 }, 'fast');
            return true;
        }
    });
}

//Generate Ticket Hardware Order Modal
function FormTicketHardwareOrderAjax(url,id,tickettype,title,productid){
    $('#FormTicketHardwareOrderAjax').modal('show');
    $('#FormTicketHardwareOrderAjax #FormTicketHardwareOrderModalAjax').attr('action',url);
    $('#FormTicketHardwareOrderAjax #assignmentId').val(id);
    $('#FormTicketHardwareOrderAjax #assignmentProductId').val(productid);
    $('#FormTicketHardwareOrderAjax #ticketType').val(tickettype);
    $('#FormTicketHardwareOrderAjax .modal-title').html('<i class="fa fa-life-ring"></i> '+title);

    $('#FormTicketHardwareOrderModalAjax')[0].reset();
    $('#order_hardwareprice').html('');

    //Clear validation
    $("span.help-block-error").hide();
    $(".has-error").removeClass("has-error");
    var error1 = jQuery('#FormTicketHardwareOrderAjax #alert_modal .alert-danger');
    $(error1).hide();

    //Validation Form
    var validfunc = 'TicketHardwareOrder';
    if (typeof validfunc !== 'undefined') {
        eval(validfunc + "FormValidation()");
    }
}


//Generate Ticket Card Order Modal
function FormTicketCardOrderAjax(url,id,tickettype,title,productid){
    $('#FormTicketCardOrderAjax').modal('show');
    $('#FormTicketCardOrderAjax #FormTicketCardOrderModalAjax').attr('action',url);
    $('#FormTicketCardOrderAjax #assignmentId').val(id);
    $('#FormTicketCardOrderAjax #assignmentProductId').val(productid);
    $('#FormTicketCardOrderAjax #ticketType').val(tickettype);
    $('#FormTicketCardOrderAjax .modal-title').html('<i class="fa fa-life-ring"></i> '+title);
}


//General Hardware Position Modal
var func_TableDatatablesAjax_4 = 'TableCustomDatatablesAjax_4';
var datatable_id_4 = 'hardwarepositiondocument_datatable_ajax';
var datatable_pagelength_4 = '<?php echo get_option('tables_pagination_limit');?>';
var datatable_columnDefs_4 = 3;
var datatable_columnDefs2_4 = 3;
var datatable_sortColumn_4 = 0;
var datatable_sortColumnBy_4 = 'asc';
var datatable_hide_columns_4 = 4;

function getFileUpload(hardwareassignmentproductid){
   var html = '<form id="FormHardwarePositionUploadModalAjax" action="<?php echo base_url('admin/assignments/uploadHardwareAssignmentPositionDocuments/');?>'+hardwareassignmentproductid+'" class="dropzone dropzone-file-area dz-clickable" id="my-dropzone" style="padding-top:40px;"></form>';
   $('#FormHardwarePositionUploadAjax #file_upload').html(html);
   Dropzoneload2('FormHardwarePositionUploadModalAjax');
}

function FormHardwarePositionUploadAjax(url, title, hardwareassignmentproductid){
    $('#FormHardwarePositionUploadAjax').modal('show');
    $('#FormHardwarePositionUploadAjax #FormHardwarePositionUploadModalAjax').attr('action','<?php echo base_url('admin/assignments/uploadHardwareAssignmentPositionDocuments/');?>'+hardwareassignmentproductid);
    $('#FormHardwarePositionUploadAjax .modal-title').html('<i class="fa fa-upload"></i> '+title);
    //$('#FormHardwarePositionUploadAjax .modal-body').html("<div class='text-center'><img src='<?php echo base_url('assets/global/img/loading-spinner-blue.gif');?>' /></div>");

    /* Initialise for Edit */
    //$('#FormHardwarePositionUploadModalAjax')[0].reset();

    //Clear validation
    $("span.help-block-error").hide();
    $(".has-error").removeClass("has-error");
    var error1 = jQuery('#FormHardwarePositionUploadAjax #alert_modal .alert-danger');
    $(error1).hide();

    Pace.track(function(){
        Pace.restart();
        jQuery.ajax({url: url, success: function(result){

            getFileUpload(hardwareassignmentproductid);

            $('#FormHardwarePositionUploadAjax .modal-body #hardwareassignmentproduct_attachments').html(result);
            var admin_url_4 = '<?php echo base_url('admin/assignments/ajaxhardwareassignmentpositiondocument/');?>'+hardwareassignmentproductid;
            if (typeof func_TableDatatablesAjax_4 !== 'undefined') {
                jQuery(document).ready(function() {
                    eval(func_TableDatatablesAjax_4 + "('"+admin_url_4+"')");
                });
            }
        }});
    });
}

//Validation Form
function TicketCardPauseFormValidation(){

    var form1 = $('#FormTicketCardPauseModalAjax');
    var error1 = $('#FormTicketCardPauseAjax #alert_modal .alert-danger');
    var success1 = $('#FormTicketCardPauseAjax #alert_modal .alert-success');

    form1.validate({
        errorElement: 'span', //default input error message container
        errorClass: 'help-block help-block-error', // default input error message class
        focusInvalid: false, // do not focus the last invalid input
        ignore: "",  // validate all fields including form hidden input

        rules: {

            card_month: {
                required: true
            },

            card_reason: {
                required: true
            },

        },

        invalidHandler: function (event, validator) { //display error alert on form submit
                success1.hide();
                error1.show();
                App.scrollTo(error1, -200);
                $('#FormTicketCardPauseAjax').animate({ scrollTop: 0 }, 'fast');
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
            $('#FormTicketCardPauseAjax').animate({ scrollTop: 0 }, 'fast');
            return true;
        }
    });
}

//Generate Ticket Hardware Order Modal
function FormTicketCardPauseAjax(url,id,tickettype,title,productid,cardbreak){
    $('#FormTicketCardPauseAjax').modal('show');
    $('#FormTicketCardPauseAjax #FormTicketCardPauseModalAjax').attr('action',url);
    $('#FormTicketCardPauseAjax #assignmentId').val(id);
    $('#FormTicketCardPauseAjax #is_paused').val(is_paused);
    $('#FormTicketCardPauseAjax #assignmentProductId').val(productid);
    $('#FormTicketCardPauseAjax #ticketType').val(tickettype);
    $('#FormTicketCardPauseAjax .modal-title').html('<i class="fa fa-life-ring"></i> '+title);

    $('#FormTicketCardPauseModalAjax')[0].reset();

    //Clear validation
    $("span.help-block-error").hide();
    $(".has-error").removeClass("has-error");
    var error1 = jQuery('#FormTicketCardPauseAjax #alert_modal .alert-danger');
    $(error1).hide();

    //Validation Form
    var validfunc = 'TicketCardPause';
    if (typeof validfunc !== 'undefined') {
        eval(validfunc + "FormValidation()");
    }

    /*if(cardbreak==1){
        $('#btn_cardbreak').removeClass('red');
        $('#btn_cardbreak').addClass('green-jungle');
        $('#btn_cardbreak').attr('disabled',true);
        $('#btn_cardbreak').html('<?php echo lang('page_lb_breaklaid');?>');
    }*/
}

//Validation Form
function TicketContractOrderFormValidation(){

    var form1 = $('#FormTicketContractOrderModalAjax');
    var error1 = $('#FormTicketContractOrderAjax #alert_modal .alert-danger');
    var success1 = $('#FormTicketContractOrderAjax #alert_modal .alert-success');

    form1.validate({
        errorElement: 'span', //default input error message container
        errorClass: 'help-block help-block-error', // default input error message class
        focusInvalid: false, // do not focus the last invalid input
        ignore: "",  // validate all fields including form hidden input

        rules: {

            ratemobile: {
                required: true
            },
            quantity: {
                required: true
            },

        },

        invalidHandler: function (event, validator) { //display error alert on form submit
                success1.hide();
                error1.show();
                App.scrollTo(error1, -200);
                $('#FormTicketContractOrderAjax').animate({ scrollTop: 0 }, 'fast');
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
            $('#FormTicketContractOrderAjax').animate({ scrollTop: 0 }, 'fast');
            return true;
        }
    });
}

//Generate Ticket Contract Order Modal
function FormTicketContractOrderAjax(url,id,tickettype,title){
    $('#FormTicketContractOrderAjax').modal('show');
    $('#FormTicketContractOrderAjax #FormTicketContractOrderModalAjax').attr('action',url);
    $('#FormTicketContractOrderAjax #assignmentId').val(id);
    $('#FormTicketContractOrderAjax #ticketType').val(tickettype);
    $('#FormTicketContractOrderAjax .modal-title').html('<i class="fa fa-life-ring"></i> '+title);

    $('#FormTicketContractOrderModalAjax')[0].reset();
    $('#order_hardwareprice').html('');

    //Clear validation
    $("span.help-block-error").hide();
    $(".has-error").removeClass("has-error");
    var error1 = jQuery('#FormTicketContractOrderAjax #alert_modal .alert-danger');
    $(error1).hide();

    //Validation Form
    var validfunc = 'TicketContractOrder';
    if (typeof validfunc !== 'undefined') {
        eval(validfunc + "FormValidation()");
    }
}
</script>



<!-- Add More Option Modal -->
<div class="modal fade bs-modal-sm in" id="AddMoreOptionMobile" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <?php echo form_open("",array("id"=>"AddMoreOptionMobileModal")); ?>
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"></h4>
            </div>
            <div class="modal-body">
                            <input type="hidden" id="dataid" value="" />
                            <input type="hidden" id="datatype" value="" />

                            <table class="table table-striped table-bordered table-hover dt-responsive" width="100%">
                                <thead>
                                    <tr>
                                        <th></th><th class="text-nowrap"><?php echo lang('page_fl_optiontitle');?></th><th class="text-nowrap"><?php echo lang('page_fl_value');?></th>
                                    </tr>
                                </thead>
                                <tbody id="assignmentproduct_moreoptionmobile_inputbox">

                                </tbody>
                            </table>
            </div>
            <div class="modal-footer">
                                <button type="button" class="btn btn-default blue" id="apply_ok_optionmobile"><?php echo lang('page_lb_ok'); ?></button>
                                <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo lang('page_lb_close'); ?></button>
            </div>
        </div><!-- /.modal-content -->
        <?php echo form_close(); ?>
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<!-- End Add More Option Modal -->


<!-- View More Option Modal -->
<div class="modal fade bs-modal-sm in" id="ViewMoreOptionMobile" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-sm" role="document">
        <?php echo form_open("",array("id"=>"ViewMoreOptionMobileModal")); ?>
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"></h4>
            </div>
            <div class="modal-body">
                            <table class="table table-striped table-bordered table-hover dt-responsive" width="100%">
                                <thead>
                                    <tr>
                                        <th class="text-nowrap"><?php echo lang('page_fl_optiontitle');?></th><th class="text-nowrap"><?php echo lang('page_fl_value');?></th>
                                    </tr>
                                </thead>
                                <tbody id="view_assignmentproduct_moreoptionmobile_inputbox">

                                </tbody>
                            </table>
            </div>
            <div class="modal-footer">
                                <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo lang('page_lb_close'); ?></button>
            </div>
        </div><!-- /.modal-content -->
        <?php echo form_close(); ?>
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<!-- End View More Option Modal -->


<script>
//General Modal More Option Mobile
function AddMoreOptionMobile(title, dataid, datatype){
    $('#AddMoreOptionMobile').modal('show');
    $('#AddMoreOptionMobileModal .modal-title').html('<i class="icon-plus"></i> '+title);

    $('#dataid').val(dataid);
    $('#datatype').val(datatype);

    var sdatamainrow = dataid;
    var rownum = parseInt(jQuery('#count_moreoptionmobile_'+sdatamainrow).val()) + 1;

    var inputhtml = '<tr id="row_new_moreoptionmobile_'+sdatamainrow+'_'+rownum+'">';
        inputhtml = inputhtml + '<td>';
            inputhtml = inputhtml + '<a href="javascript:void(0);" class="btn btn-sm btn-default btn-circle btn-editable green addmoreoptionmobile" datamainrow="'+sdatamainrow+'" datarow="'+rownum+'" datatype="new" datainit="0"><i class="fa fa-plus"></i></a>';
        inputhtml = inputhtml + '</td>';
        inputhtml = inputhtml + '<td id="int_new_moreoptionmobile_'+sdatamainrow+'_'+rownum+'" datamainrow="'+sdatamainrow+'" datarow="'+rownum+'" datatype="new"><select name="more_newoptionmobile['+sdatamainrow+']['+rownum+']" class="form-control more_newoptionmobile noerror" id="more_new_newoptionmobile_'+sdatamainrow+'_'+rownum+'" datamainrow="'+sdatamainrow+'" datarow="'+rownum+'" datatype="new">';
            <?php
            foreach($mobileoptions as $k=>$v){
                if($k==0){ $v = lang("page_lb_select_mobile_option"); }
                ?>
                inputhtml = inputhtml + "<option value='<?php echo $k;?>'><?php echo $v;?></option>";
                <?php
            }
            ?>
        inputhtml = inputhtml + '</select></td>';
        inputhtml = inputhtml + '<td id="int_new_moreoptionmobile_'+sdatamainrow+'_'+rownum+'" datamainrow="'+sdatamainrow+'" datarow="'+rownum+'" datatype="new">';
            inputhtml = inputhtml + '<input type="number" name="more_value4['+sdatamainrow+']['+rownum+']" value="" class="form-control noerror" id="more_new_value4_'+sdatamainrow+'_'+rownum+'" datamainrow="'+sdatamainrow+'" datarow="'+rownum+'" datatype="new">';
        inputhtml = inputhtml + '</td>';
    inputhtml = inputhtml + '</tr>';

    jQuery('#assignmentproduct_moreoptionmobile_inputbox').html(inputhtml);
    jQuery('#count_moreoptionmobile_'+sdatamainrow).val(rownum);
    addmoreoptionmobile2();
    changemorenewOptionMobile();
    apply_ok_optionmobile();
}
//General Modal More Option Mobile
function ViewMoreOptionMobile(title, dataid, datatype){
    $('#ViewMoreOptionMobile').modal('show');
    $('#ViewMoreOptionMobileModal .modal-title').html('<i class="icon-plus"></i> '+title);

    var rows_moreoptionmobile = $('#'+datatype+'_moreoptionmobile_'+dataid).html();
    rows_moreoptionmobile = rows_moreoptionmobile.replace(/<ul/gi,'<tr');
    rows_moreoptionmobile = rows_moreoptionmobile.replace(/ul>/gi,'tr>');
    rows_moreoptionmobile = rows_moreoptionmobile.replace(/<li/gi,'<td');
    rows_moreoptionmobile = rows_moreoptionmobile.replace(/li>/gi,'td>');

    $('#view_assignmentproduct_moreoptionmobile_inputbox').html(rows_moreoptionmobile);
}
//Add More Option Mobile
function addmoreoptionmobile2(){
    jQuery('.addmoreoptionmobile').click( function(){
        var datainit = $(this).attr('datainit');
        if(datainit==1){ return false; }

        //Swap Class
        var sdatamainrow = $(this).attr('datamainrow');
        var sdatarow = $(this).attr('datarow');
        var sdatatype = $(this).attr('datatype');

        $(this).removeClass('addmoreoptionmobile');
        $(this).removeClass('green');
        $(this).addClass('red');
        $(this).html('<i class="fa fa-minus"></i>');
        $(this).attr('onClick',"deletemoreoptionmobile('"+sdatamainrow+"','"+sdatarow+"','"+sdatatype+"')");
        $(this).attr('datainit',1);
        var rownum = parseInt(jQuery('#count_moreoptionmobile_'+sdatamainrow).val()) + 1;

        var inputhtml = '<tr id="row_new_moreoptionmobile_'+sdatamainrow+'_'+rownum+'">';
            inputhtml = inputhtml + '<td>';
                inputhtml = inputhtml + '<a href="javascript:void(0);" class="btn btn-sm btn-default btn-circle btn-editable green addmoreoptionmobile" datamainrow="'+sdatamainrow+'" datarow="'+rownum+'" datatype="new" datainit="0"><i class="fa fa-plus"></i></a>';
            inputhtml = inputhtml + '</td>';
            inputhtml = inputhtml + '<td id="int_new_moreoptionmobile_'+sdatamainrow+'_'+rownum+'" datamainrow="'+sdatamainrow+'" datarow="'+rownum+'" datatype="new"><select name="more_newoptionmobile['+sdatamainrow+']['+rownum+']" class="form-control more_newoptionmobile noerror" id="more_new_newoptionmobile_'+sdatamainrow+'_'+rownum+'" datamainrow="'+sdatamainrow+'" datarow="'+rownum+'" datatype="new">';
                <?php
                foreach($mobileoptions as $k=>$v){
                    if($k==0){ $v = lang("page_lb_select_mobile_option"); }
                    ?>
                    inputhtml = inputhtml + "<option value='<?php echo $k;?>'><?php echo $v;?></option>";
                    <?php
                }
                ?>
            inputhtml = inputhtml + '</select></td>';
            inputhtml = inputhtml + '<td id="int_new_moreoptionmobile_'+sdatamainrow+'_'+rownum+'" datamainrow="'+sdatamainrow+'" datarow="'+rownum+'" datatype="new">';
                inputhtml = inputhtml + '<input type="number" name="more_value4['+sdatamainrow+']['+rownum+']" value="" class="form-control noerror" id="more_new_value4_'+sdatamainrow+'_'+rownum+'" datamainrow="'+sdatamainrow+'" datarow="'+rownum+'" datatype="new">';
            inputhtml = inputhtml + '</td>';
        inputhtml = inputhtml + '</tr>';

        jQuery('#assignmentproduct_moreoptionmobile_inputbox').append(inputhtml);
        jQuery('#count_moreoptionmobile_'+sdatamainrow).val(rownum);
        addmoreoptionmobile2();
        changemorenewOptionMobile();
    });
}
//Apply OK More Option Mobile
function apply_ok_optionmobile(){
    $('#apply_ok_optionmobile').attr('clicked', false);

    $('#apply_ok_optionmobile').click( function(){
        if($(this).attr('clicked')=="true"){ return false; }
        $(this).attr('clicked', true);

        var dataid = $('#dataid').val();
        var datatype = $('#datatype').val();

        //Init Inputs
        $('#assignmentproduct_moreoptionmobile_inputbox tr td').each( function(){
            var cinput = $(this).html();
            var nstr = cinput.indexOf('<select');
            var nstr2 = cinput.indexOf('<input');
            if(nstr>=0){
                var new_datamainrow = $(this).attr('datamainrow');
                var new_datarow = $(this).attr('datarow');
                var new_datatype = $(this).attr('datatype');

                $('#'+datatype+'_newoptionmobile_box_'+dataid).append('<div id="div_more_'+new_datatype+'_newoptionmobile_'+new_datamainrow+'_'+new_datarow+'" class="row"><div><div class="form-group"><label class="col-md-1 control-label"><a href="javascript:void(0);" id="more_'+new_datatype+'_close_'+new_datamainrow+'_'+new_datarow+'" onclick="row_deletemoreoptionmobile(\''+new_datamainrow+'\',\''+new_datarow+'\',\''+new_datatype+'\');"><i class="fa fa-close"></i></a></label> <div class="col-md-9">'+$(this).html()+'</div></div></div></div>');
            }
            else if(nstr2>=0){
                var new_datamainrow = $(this).attr('datamainrow');
                var new_datarow = $(this).attr('datarow');
                var new_datatype = $(this).attr('datatype');

                $('#'+datatype+'_newoptionmobile_box_value_'+dataid).append('<div id="div_more_'+new_datatype+'_value4_'+new_datamainrow+'_'+new_datarow+'" class="row"><div><div class="form-group"><div class="col-md-12">'+$(this).html()+'</div></div></div></div>');
            }
        });

        //Get Selected Item
        $("#assignmentproduct_moreoptionmobile_inputbox select").each(function(){
            var sdatamainrow = $(this).attr('datamainrow');
            var sdatarow = $(this).attr('datarow');
            var datatype2 = $(this).attr('datatype');
            var selected = $(this).val();
            $("#"+datatype+"_newoptionmobile_box_"+dataid+" #more_"+datatype2+"_newoptionmobile_"+sdatamainrow+"_"+sdatarow).val(selected).change();
        });

        //Get Entered Text
        $("#assignmentproduct_moreoptionmobile_inputbox input").each(function(){
            var sdatamainrow = $(this).attr('datamainrow');
            var sdatarow = $(this).attr('datarow');
            var datatype2 = $(this).attr('datatype');
            var selected = $(this).val();
            $("#"+datatype+"_newoptionmobile_box_value_"+dataid+" #more_"+datatype2+"_value4_"+sdatamainrow+"_"+sdatarow).val(selected);
        });

        $('#AddMoreOptionMobile').modal('hide');
        changemorenewOptionMobile();
    });
}
//Delete More Option Mobile
function deletemoreoptionmobile(dataid, datarow, datatype){
    //if(datatype=='new'){
        $('#assignmentproduct_moreoptionmobile_inputbox #row_'+datatype+'_moreoptionmobile_'+dataid+'_'+datarow).remove();
    //}
}

//Delete More Option Mobile from Row
function row_deletemoreoptionmobile(dataid, datarow, datatype){
    //if(datatype=='new'){
        $('#div_more_'+datatype+'_newoptionmobile_'+dataid+'_'+datarow).remove();
        $('#div_more_'+datatype+'_value4_'+dataid+'_'+datarow).remove();
    //}
}

function changemorenewOptionMobile(){
    jQuery('.more_newoptionmobile').change( function(){
        var mrown = jQuery(this).attr('datamainrow');
        var rown = jQuery(this).attr('datarow');
        var rowt = jQuery(this).attr('datatype');
        //var id = jQuery('#assignmentproduct_moreoptionmobile_inputbox #more_'+rowt+'_newoptionmobile_'+mrown+'_'+rown).val();
        var id = jQuery(this).val();
        //var formula = jQuery('input[name='+rowt+'_formula_'+mrown+']').val();

        //if(formula=='A'){
            jQuery.ajax({url: '<?php echo base_url('admin/assignments/getMobileOptionValue/');?>'+id, success: function(result){
                jQuery('#assignmentproduct_moreoptionmobile_inputbox #more_'+rowt+'_value4_'+mrown+'_'+rown).val(result);
                jQuery('#div_more_'+rowt+'_value4_'+mrown+'_'+rown+' #more_'+rowt+'_value4_'+mrown+'_'+rown).val(result);
                //jQuery('#div_more_old_value4_'+mrown+'_'+rown+' #more_'+rowt+'_value4_'+mrown+'_'+rown).val(result);
            }});
        //}
    });
}
changemorenewOptionMobile();

function FormExternalHardware(assignmentnr, id) {
    $('#FormExternalHardware #assignmentnr').val(assignmentnr);
    $('#FormExternalHardware #id').val(id);
    $('#FormExternalHardware').modal('show');
}

jQuery('#FormExternalHardware #hardwarecategory').change(function(event) {
    jQuery('#FormExternalHardware #hardware').find('option').removeClass('hide').end().val('');
    var hardwarecategory = jQuery(this).val() || '';
    if (hardwarecategory != '') {
        jQuery('#FormExternalHardware #hardware').find('option:not(:first)').addClass('hide').end().find('option[data-hardwarecategory="'+hardwarecategory+'"]').removeClass('hide');
    }
});

jQuery("#FormExternalHardwareModalAjax").submit(function(e) {
    //jQuery('#FormTicketCardPauseAjax').modal('hide');
    var form = jQuery(this);
    var url = form.attr('action');
    var error1 = jQuery('#FormExternalHardware #alert_modal .alert-danger');

    // check if the input is valid
    if(!form.valid()){
        return false;
    }

    // jQuery('#FormExternalHardware').modal('hide');
    Pace.track(function(){
        Pace.restart();
        jQuery.ajax({
           type: "POST",
           url: url,
           data: form.serialize(), // serializes the form's elements.
           dataType: "JSON",
           beforeSend:function(){
               $('#pageloaddiv').show();
           },
           success: function(data){
                $('#pageloaddiv').hide();

                //show response from the php script.
                //Showtoast Messagebox
                if(data.response=='success'){
                    showtoast(data.response,'',data.message);
                    jQuery('#FormExternalHardware').modal('hide');
                    window.location.reload();
                }
                else{
                    showtoast(data.response,'',data.message);
                }
           }
    });
    });
    e.preventDefault(); // avoid to execute the actual submit of the form.
});

//Change Filter By Year
jQuery("#filter_invoice_year").select2({
    placeholder: "<?php echo lang('page_lb_select_a_year');?>",
    allowClear: true,
        width:'150'
});

jQuery('#filter_invoice_year').change( function(){
    <?php if(isset($assignment['assignmentnr'])){?>
        var admin_url_3 = '<?php echo base_url('admin/assignments/ajaxinvoice/'.$assignment['assignmentnr']);?>';
    <?php }?>
    var filter_invoice_year = jQuery('#filter_invoice_year').val();
    var admin_url_3 = admin_url_3 + '/'+ eval(filter_invoice_year);

    if (typeof func_TableDatatablesAjax_3 !== 'undefined') {
        $('#'+datatable_id_3).DataTable().destroy();
        eval(func_TableDatatablesAjax_3 + "('"+admin_url_3+"')");
    }
});

var func_TableDatatablesAjax_5 = 'TableCustomDatatablesAjax_5';
var datatable_id_5 = 'assignment_detail_datatable_ajax';
var datatable_pagelength_5 = '<?php echo get_option('tables_pagination_limit');?>';
var datatable_columnDefs_5 = '';
var datatable_columnDefs2_5 = '';
var datatable_sortColumn_5 = 0;
var datatable_sortColumnBy_5 = 'asc';
var datatable_hide_columns_5 = 4;
<?php if(isset($assignment['assignmentnr'])) { ?>
    var admin_url_5 = '<?php echo base_url('admin/assignments/ajaxproduct/'.$assignment['assignmentnr']);?>';
<?php } else { ?>
    var admin_url_5 = '';
<?php } ?>
eval(func_TableDatatablesAjax_5 + "('"+admin_url_5+"')");

</script>