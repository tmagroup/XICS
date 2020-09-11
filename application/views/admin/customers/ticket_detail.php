<div class="col-md-6">
                            
                            
    <!-- BEGIN SAMPLE FORM PORTLET-->
    <div class="portlet light bordered">                                
        <div class="portlet-body form">

            <div class="form-body">

                <div class="form-group">
                    <label><?php echo lang('page_fl_tickettitle');?>:</label>
                    <?php echo $ticket['tickettitle'];?>
                </div>

                <div class="form-group">
                    <label><?php echo lang('page_fl_company');?>:</label>
                    <?php echo $ticket['company'];?>
                </div>

                <div class="form-group">
                    <label><?php echo lang('page_fl_ticketstatus');?>:</label>
                    <?php echo $ticket['ticketstatusname'];?>
                </div>

                <div class="form-group">
                    <label><?php echo lang('page_fl_ticketdesc');?>:</label>
                    <?php echo $ticket['ticketdesc'];?>
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
                    <?php echo $ticket['customer'];?>
                </div>

                <div class="form-group">
                    <label><?php echo lang('page_fl_responsible');?>:</label>
                    <?php echo $ticket['responsible'];?>
                </div>

                <div class="form-group">
                    <label><?php echo lang('page_fl_teamwork');?>:</label>
                    <?php echo $ticket['teamwork'];?>
                </div>

            </div>    

        </div>
    </div>
    <!-- END SAMPLE FORM PORTLET-->

</div>

<div class="clearfix"></div>