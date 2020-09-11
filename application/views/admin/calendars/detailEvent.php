<!--<div class="form-group">
    <label><?php echo lang('page_fl_eventtitle');?>:</label>
    <?php //echo $event['title'];?>
</div>

<div class="form-group">
    <label><?php echo lang('page_fl_eventstatus');?>:</label>
    <?php //echo $event['eventstatus_name'];?>
</div>

<div class="form-group">
    <label><?php echo lang('page_fl_eventstartdate');?>:</label>
    <?php //echo _dt($event['start']);?>
</div>

<div class="form-group">
    <label><?php echo lang('page_fl_eventenddate');?>:</label>
    <?php //echo _dt($event['end']);?>
</div>

<div class="form-group" id="fld_event_startaddress">
    <label><?php echo lang('page_fl_eventstartaddress');?>:</label>
    <?php //echo $event['event_startaddress'];?>
</div>

<div class="form-group" id="fld_event_address">
    <label><?php echo lang('page_fl_eventendaddress');?>:</label>
    <?php //echo $event['event_address'];?>
</div>

<div class="form-group">
    <label><?php echo lang('page_fl_eventdesc');?>:</label>
    <?php //echo $event['description'];?>
</div>

<div class="form-group">
    <div class="calendar-cpicker cpicker cpicker-big" data-colorid="<?php //echo $event['google_color_id'];?>" data-forecolor="<?php //echo $event['forecolor'];?>" data-color="<?php //echo $event['color'];?>" style="background:<?php //echo $event['color'];?>;border:1px solid <?php //echo $event['color'];?>"></div>
    <div class="clearfix"></div>
</div>

<div class="form-group">
    <label><?php //echo lang('page_fl_eventpublic');?>: <?php //$public = ($event['public']==1)?'yes':'no'; echo lang('page_lb_'.$public)?></label>
</div>

<div class="clearfix"></div>-->


<style>
#FormAjaxDetail .form-body i{
    color:<?php echo $event['color']?$event['color']:'#3a87ad';?>;
}
</style>

<div class="modal-header" style="background-color: <?php echo $event['color']?$event['color']:'#3a87ad';?>">

    <div class="eventClose">

        <?php
        if($GLOBALS['calendar_permission']['delete'] && $event['userid']==get_user_id() && $event['event_type']=='CRM_EVENT'){
            ?>
            <div><a href="javascript:void(0);" onclick="deleteConfirmation('<?php echo base_url('admin/calendars/deleteEvent/');?>','<?php echo $event['eventid'];?>','<?php echo lang('page_lb_delete_event')?>','<?php echo lang('page_lb_delete_event_info')?>','true');"><i data-toggle="tooltip" data-title="<?php echo lang('page_lb_delete_event');?>" class="fa fa-trash"></i></a></div>
            <?php
        }
        /*else if($GLOBALS['calendar_permission']['delete'] && $event['event_type']=='GOOGLE_EVENT'){
            ?>
            <div><a href="javascript:void(0);" onclick="deleteConfirmation('<?php echo base_url('admin/calendars/deleteGoogleEvent/');?>','<?php echo $event['google_eid'];?>[=]<?php echo $event['calendarId'];?>','<?php echo lang('page_lb_delete_event')?>','<?php echo lang('page_lb_delete_event_info')?>','true');"><i data-toggle="tooltip" data-title="<?php echo lang('page_lb_delete_event');?>" class="fa fa-trash"></i></a></div>
            <?php
        }*/
        ?>

        <div><i data-toggle="tooltip" data-title="<?php echo lang('close');?>" data-dismiss="modal" aria-label="Close" class="fa fa-remove"></i></div></div>

        <div class="clearfix"></div>

    <div>

        <?php
        /*if($GLOBALS['calendar_permission']['edit'] && $event['userid']==get_user_id() && $event['event_type']=='CRM_EVENT'){
            ?>
            <a href="javascript:void(0);" onclick="FormAjax('<?php echo base_url('admin/calendars/addEvent/'.$event['eventid']);?>', '<?php echo $event['eventid'];?>' ,'<?php echo lang('page_edit_event')?>', '', 'event');"><i data-toggle="tooltip" data-title="<?php echo lang('page_edit_event');?>" class="fa fa-pencil"></i></a>
            <?php
        }*/
        ?>

        <?php echo '&nbsp;&nbsp;'.$event['title'];?>

    </div>

</div>

<div class="portlet light">
    <div class="portlet-body form">
        <div class="form-body">

            <div class="form-group">
                <i class="icon-clock"></i>
                <?php echo _dt($event['start']);?> - <?php echo _dt($event['end']);?>
            </div>

            <?php
            if(trim($event['event_company'])!=""){
                ?>
                <div class="form-group">
                    <i class="fa fa-building"></i>
                    <?php echo $event['event_company'];?>
                </div>
                <?php
            }
            ?>

            <?php
            if(trim($event['description'])!=""){
                ?>
                <div class="form-group">
                    <i class="fa fa-bars"></i>
                    <?php echo $event['description'];?>
                </div>
                <?php
            }
            ?>

            <?php
            if(trim($event['event_startaddress'])!="" || trim($event['event_address'])!=""){
                ?>
                <div class="form-group">
                    <i class="fa fa-map"></i>
                    <?php if(isset($event['event_startaddress']) && $event['event_startaddress']!=""){ echo lang('from').': '.$event['event_startaddress'];}?><div class="clearfix"></div>
                    <?php if(isset($event['event_address']) && $event['event_address']!=""){ echo lang('to').': '.$event['event_address'];}?>
                </div>
                <?php
            }
            ?>

            <div class="form-group">
                <i class="fa fa-user"></i>
                <?php
                if($event['event_type']=='CRM_EVENT'){
                    echo $event['full_name'];
                }
                else{
                    ?>
                    <a href="<?php echo $event['google_htmllink'];?>" target="_blank"><img src="<?php echo base_url('assets/pages/img/google_calendar.jpg')?>" width="100" /></a> (<?php echo $event['googleCalendarName'];?>)
                    <?php
                }
                ?>
            </div>


            <?php if (!empty($event['responsive_to_name'])) { ?>
                <div class="form-group">
                    <i class="fa fa-users"></i>
                    <?php echo $event['responsive_to_name']; ?>
                </div>
            <?php } ?>



            <?php
            if(isset($event['distance']) && $event['distance']!="" && $event['eventstatus']==2){
                ?>
                <div class="form-group">
                    <i class="fa fa-road"></i>
                    <?php echo $event['distance'];?>
                </div>
                <?php
            }
            ?>

        </div>
    </div>
</div>

<div class="clearfix"></div>
