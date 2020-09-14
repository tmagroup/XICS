<style>
.control-label input.required, .form-group input.required{
    color: #4d6b8a;
    padding: 6px 12px;
    font-size: 14px;
}
</style>

<!-- BEGIN PAGE MESSAGE-->
<?php $this->load->view('admin/alerts_modal'); ?>
<!-- BEGIN PAGE MESSAGE-->

<?php
if(isset($dataAssignedCalendarIDs) && count($dataAssignedCalendarIDs)>0)
{
    ?>
    <div class="form-group">
    <img src="<?php echo base_url('assets/pages/img/google_calendar.jpg')?>" width="100" />
    <?php

    if(count($dataAssignedCalendarIDs)>1){
           /* if(isset($event['eventid']) && $event['eventid']>0){
                $selected_calendarId = isset($event['calendarId'])?$event['calendarId']:'';
                foreach($dataAssignedCalendarIDs as $ckey=>$dataAssignedCalendarID){
                    if(trim($selected_calendarId)==trim($dataAssignedCalendarID['id'])){
                        ?>
                        <ul class="icheck-colors pull-right"><li style="background-color:<?php echo $getSystemCalendarColor[$dataAssignedCalendarID['colorId']];?>"></li> <?php echo $dataAssignedCalendarID['summary'];?> </ul>
                        <input type="hidden" name="calendarId" value="<?php echo $dataAssignedCalendarID['id'];?>" />
                        <?php
                    }
                }
            }
            else*/{
                ?>
                <select name="calendarId" required>
                    <option value=""><?php echo lang('page_option_select');?></option>
                    <?php foreach ( $dataAssignedCalendarIDs as $ckey => $dataAssignedCalendarID ) {
                        $selected_calendarId = isset($event['calendarId']) ? $event['calendarId'] : '';
                        $selected_calendar = '';
                        if ( $dataAssignedCalendarID['id'] == $selected_calendarId ) {
                            $selected_calendar = ' selected';
                        }
                    ?>
                        <option value="<?php echo $dataAssignedCalendarID['id']; ?>" <?php echo $selected_calendar; ?>><?php echo $dataAssignedCalendarID['summary']; ?></option>
                    <?php } ?>
                </select>
                <?php
            }
    }
    else{
        if(isset($event['eventid']) && $event['eventid']>0){
            $selected_calendarId = isset($event['calendarId'])?$event['calendarId']:'';
            foreach($dataAssignedCalendarIDs as $ckey=>$dataAssignedCalendarID){
                if(trim($selected_calendarId)==trim($dataAssignedCalendarID['id'])){
                    ?>
                    <ul class="icheck-colors pull-right"><li style="background-color:<?php echo $getSystemCalendarColor[$dataAssignedCalendarID['colorId']];?>"></li> <?php echo $dataAssignedCalendarID['summary'];?> </ul>
                    <input type="hidden" name="calendarId" value="<?php echo $dataAssignedCalendarID['id'];?>" />
                    <?php
                }
            }
        }
        else{
            ?>
            <ul class="icheck-colors pull-right"><li style="background-color:<?php echo $getSystemCalendarColor[$dataAssignedCalendarIDs[0]['colorId']];?>"></li> <?php echo $dataAssignedCalendarIDs[0]['summary'];?> </ul>
            <input type="hidden" name="calendarId" value="<?php echo $dataAssignedCalendarIDs[0]['id'];?>" />
            <?php
        }
    }
    ?>
    </div>

    <div class="form-group">
        <label><?php echo lang('page_fl_eventtitle');?> <span class="required"> * </span></label>
        <?php echo form_input('title', isset($event['title'])?$event['title']:'', 'class="form-control" id="event_title" ');?>
    </div>

    <div class="form-group">
        <label><?php echo lang('page_fl_eventstatus');?> <span class="required"> * </span></label>
        <?php echo form_dropdown('eventstatus', $eventstatus, isset($event['eventstatus'])?$event['eventstatus']:'', 'class="form-control" id="event_status" ');?>
    </div>

    <?php
    $required_assignment = '';
    $field_assignment = 'none';
    $required_lead = '';
    $field_lead = 'none';
    $required_proofuser = '';
    $field_proofuser = 'none';
    $eventstatus = isset($event['eventstatus'])?$event['eventstatus']:'';
    if($eventstatus==2){
        $required_assignment = 'required';
        $field_assignment = '';
        $required_lead = 'required';
        $field_lead = '';
        $required_proofuser = 'required';
        $field_proofuser = '';
    }

    if(!empty($event['assignmentnr']) && empty($event['leadnr'])){
        $required_assignment = 'required';
        $required_lead = '';
    }
    else if(!empty($event['leadnr']) && empty($event['assignmentnr'])){
        $required_assignment = '';
        $required_lead = 'required';
    }

    $required_assignment = '';
    $required_lead = '';
    ?>
    <div class="form-group" id="fld_assignmentnr" style="display:<?php echo $field_assignment;?>">
        <label><?php echo lang('page_fl_eventacompany');?> <span class="required"> * </span></label>
        <?php echo form_dropdown('assignmentnr', $assignments, isset($event['assignmentnr'])?$event['assignmentnr']:'', 'class="form-control" id="assignmentnr" '.$required_assignment);?>
    </div>

    <div class="form-group" id="fld_leadnr" style="display:<?php echo $field_lead;?>">
        <label><?php echo lang('page_fl_eventlcompany');?> <span class="required"> * </span></label>
        <?php echo form_dropdown('leadnr', $leads, isset($event['leadnr'])?$event['leadnr']:'', 'class="form-control" id="leadnr" '.$required_lead);?>
    </div>

    <div class="form-group" id="fld_proofuser" style="display:<?php echo $field_proofuser;?>">
        <label><?php echo lang('page_fl_proofuser');?> <span class="required"> * </span></label>
        <?php //echo form_dropdown('proofuser', $proofusers, isset($event['proofuser'])?$event['proofuser']:'', 'class="form-control" id="proofuser" '.$required_proofuser);?>
    </div>

    <div class="form-group">
        <label><?php echo lang('page_fl_eventstartdate');?> <span class="required"> * </span></label>
        <div class="input-group date form_datetime">
            <?php $dd = array('name'=>'start', 'id' => 'event_start',  'class'=>'form-control', 'readonly'=>true, 'size'=>16, 'value'=> isset($event['start'])?_dt($event['start']):'');
            echo form_input($dd);?>
            <span class="input-group-btn">
                <button class="btn default date-set" type="button">
                    <i class="fa fa-calendar"></i>
                </button>
            </span>
        </div>
    </div>

    <div class="form-group">
        <label><?php echo lang('page_fl_eventenddate');?> <span class="required"> * </span></label>
        <div class="input-group date form_datetime">
            <?php $dd = array('name'=>'end', 'id' => 'event_end',  'class'=>'form-control', 'readonly'=>true, 'size'=>16, 'value'=> isset($event['end'])?_dt($event['end']):'');
            echo form_input($dd);?>
            <span class="input-group-btn">
                <button class="btn default date-set" type="button">
                    <i class="fa fa-calendar"></i>
                </button>
            </span>
        </div>
    </div>

    <div class="form-group" id="fld_event_startaddress">
        <label><?php echo lang('page_fl_eventstartaddress');?> <span class="required"> * </span></label>
        <?php echo form_input('event_startaddress', isset($event['event_startaddress'])?$event['event_startaddress']:'', 'class="form-control" id="event_startaddress" ');?>

        <?php
        $data_hidden = array('type'=>'hidden', 'name'=>'event_startaddress_lat', 'id'=>'event_startaddress_lat', 'value'=>isset($event['event_startaddress_lat'])?$event['event_startaddress_lat']:'');
        echo form_input($data_hidden);

        $data_hidden = array('type'=>'hidden', 'name'=>'event_startaddress_lng', 'id'=>'event_startaddress_lng', 'value'=>isset($event['event_startaddress_lng'])?$event['event_startaddress_lng']:'');
        echo form_input($data_hidden);
        ?>

    </div>

    <div class="form-group" id="fld_event_address">
        <label><?php echo lang('page_fl_eventendaddress');?> <span class="required"> * </span></label>
        <?php echo form_input('event_address', isset($event['event_address'])?$event['event_address']:'', 'class="form-control" id="event_address" ');?>

        <?php
        $data_hidden = array('type'=>'hidden', 'name'=>'event_address_lat', 'id'=>'event_address_lat', 'value'=>isset($event['event_address_lat'])?$event['event_address_lat']:'');
        echo form_input($data_hidden);

        $data_hidden = array('type'=>'hidden', 'name'=>'event_address_lng', 'id'=>'event_address_lng', 'value'=>isset($event['event_address_lng'])?$event['event_address_lng']:'');
        echo form_input($data_hidden);
        ?>

    </div>

    <div class="form-group">
        <label><?php echo lang('page_fl_eventdesc');?> <span class="required"> * </span></label>
        <?php echo form_textarea('description', isset($event['description'])?$event['description']:'', 'class="form-control" id="event_desc" ');?>
    </div>

    <div class="form-group">
        <?php
        /*$colors = get_system_favourite_colors();
        foreach($colors as $color){
            echo '<div style="float:left;background-color:'.$color.'; width:20px; height:20px;"></div> &nbsp; ';
        }*/
        ?>


            <label><?php echo lang('page_fl_eventcolor');?> <span class="required"> * </span></label>
            <div class="cpicker-wrapper"></div>


        <?php
        $data_hidden = array('type'=>'hidden', 'name'=>'google_eid', 'id'=>'event_google_eid', 'value'=>isset($event['google_eid'])?$event['google_eid']:'');
        echo form_input($data_hidden);

        $data_hidden = array('type'=>'hidden', 'name'=>'google_color_id', 'id'=>'event_google_color_id', 'value'=>isset($event['google_color_id'])?$event['google_color_id']:'');
        echo form_input($data_hidden);

        $data_hidden = array('type'=>'hidden', 'name'=>'color', 'id'=>'event_color', 'value'=>isset($event['color'])?$event['color']:'');
        echo form_input($data_hidden);

        $data_hidden = array('type'=>'hidden', 'name'=>'forecolor', 'id'=>'event_forecolor', 'value'=>isset($event['forecolor'])?$event['forecolor']:'');
        echo form_input($data_hidden);
        ?>
        <div class="clearfix"></div>
    </div>

    <div class="form-group">
        <label>
            <?php
            $public = (isset($event['public']) && $event['public']==1)?true:false;
            $dc = array('name'=>'public', 'id'=>'event_public' ,'class'=>'form-control','checked'=>$public, 'value'=>1);
            echo form_checkbox($dc);?>

            <?php echo lang('page_fl_eventpublic');?>
        </label>
    </div>
    <div class="clearfix"></div>

    <script>
    function initMap() {
        var input = document.getElementById('event_startaddress');
        var autocomplete = new google.maps.places.Autocomplete(input);

        autocomplete.addListener('place_changed', function() {
            var place = autocomplete.getPlace();

            if (!place.geometry) {
                // User entered the name of a Place that was not suggested and
                // pressed the Enter key, or the Place Details request failed.
                alert("<?php echo lang('page_lb_no_details_available_for_location');?>: '" + place.name + "'");
                return;
            }

            var latitude = place.geometry.location.lat();
            var longitude = place.geometry.location.lng();

            document.getElementById('event_startaddress_lat').value = latitude;
            document.getElementById('event_startaddress_lng').value = longitude;
        });

        var input2 = document.getElementById('event_address');
        var autocomplete2 = new google.maps.places.Autocomplete(input2);

        autocomplete2.addListener('place_changed', function() {
            var place2 = autocomplete2.getPlace();

            if (!place2.geometry) {
                // User entered the name of a Place that was not suggested and
                // pressed the Enter key, or the Place Details request failed.
                alert("<?php echo lang('page_lb_no_details_available_for_location');?>: '" + place2.name + "'");
                return;
            }

            var latitude2 = place2.geometry.location.lat();
            var longitude2 = place2.geometry.location.lng();

            document.getElementById('event_address_lat').value = latitude2;
            document.getElementById('event_address_lng').value = longitude2;
        });
    }

    <?php
    if(1 || $GLOBALS['calendar_permission']['edit'] && $GLOBALS['current_user']->userrole == 1){
    // if($GLOBALS['calendar_permission']['edit'] && isset($event['userid']) && $event['userid']==get_user_id()){
        ?>
        $('#btn_save_event').show();
        <?php
    }
    else{
        if(empty($event['userid'])){
            ?>
            $('#btn_save_event').show();
            <?php
        }
        else{
           ?>
            $('#btn_save_event').hide();
            <?php
        }
    }
    if(1 || $GLOBALS['calendar_permission']['delete'] && isset($event['userid']) && $event['userid']==get_user_id()){
        ?>
        $('#btn_delete_event').show();
        <?php
    }
    else{
        ?>
        $('#btn_delete_event').hide();
        <?php
    }
    ?>
    </script>
    <script type="text/javascript" src="http://maps.googleapis.com/maps/api/js?key=<?php echo get_option('google_api_key')?>&libraries=places&callback=initMap"></script>
    <?php
}
else{
    ?>
    <div class="form-group">
        <p class="text-danger"><?php echo lang('page_lb_event_permission_error');?></p>
    </div>
    <?php
}
?>
