<!-- Event Modal Detail -->
<div class="modal fade in" id="FormAjaxDetail" tabindex="-1" role="dialog">
	<div class="modal-dialog" id="modal_size" role="document">
		<div class="modal-content">
			<div class="modal-body" style="min-height: 100px;">
			</div>
			<?php //if ($GLOBALS['calendar_permission']['edit'] && $GLOBALS['current_user']->userrole == 1): ?>
			<?php if ($GLOBALS['calendar_permission']['edit']): ?>
				<div class="modal-footer">
					<button type="button" class="btn btn-default blue" data-dismiss="modal" id="editEvent"><?php echo lang('page_lb_permission_edit'); ?></button>
				</div>
			<?php endif ?>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<!-- End Event Modal -->


<!-- Event Modal -->
<div class="modal fade in" id="FormAjax" tabindex="-1" role="dialog">
	<div class="modal-dialog" id="modal_size" role="document">
		<?php echo form_open("",array("id"=>"FormModalAjax")); ?>
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title"></h4>
			</div>
			<div class="modal-body">

			</div>
			<div class="modal-footer">
								<button id="btn_delete_event" type="button" onclick="" class="btn btn-default red"><?php echo lang('page_lb_remove'); ?></button>
								<button id="btn_save_event" type="submit" class="btn btn-default blue"><?php echo lang('save'); ?></button>
								<button type="button" class="btn btn-default" data-dismiss="modal"><?php echo lang('page_lb_close'); ?></button>
			</div>
		</div><!-- /.modal-content -->
		<?php echo form_close(); ?>
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<!-- End Event Modal -->

<script src="<?php echo base_url('assets/global/plugins/fullcalendar/lang-all.js'); ?>" type="text/javascript"></script>
<script>
//Real Get Calendar Submit Data
function eventSubmit(){
	//Filter Calendar Submit
	jQuery("#form_calendar_filter").submit(function(e){
		var form = jQuery(this);
		var url = form.attr('action');
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
					$('#calendar').fullCalendar('removeEvents');
					$('#calendar').fullCalendar('addEventSource', data);
			   }
			});
		});
		e.preventDefault(); // avoid to execute the actual submit of the form.
	});
}
function eventGet(){
	var view = $('#calendar').fullCalendar('getView');

	//Start Date
	var startdate = new Date(view.start);
	var sd = startdate.getDate();
	if(sd<10){ var sd="0"+sd; }
	var sm = startdate.getMonth()+1;
	if(sm<10){ var sm="0"+sm; }
	var sy = startdate.getFullYear();
	startdate = sy+"-"+sm+"-"+sd;

	//End Date
	var enddate = new Date(view.end);
	var ed = enddate.getDate();
	if(ed<10){ var ed="0"+ed; }
	var em = enddate.getMonth()+1;
	if(em<10){ var em="0"+em; }
	var ey = enddate.getFullYear();
	enddate = ey+"-"+em+"-"+ed;

	var form_url = '<?php echo base_url('admin/calendars/getEvents');?>';
	jQuery("#form_calendar_filter").attr('action',form_url+"?start="+startdate+"&end="+enddate+"&_="+Math.random());
	jQuery("#form_calendar_filter").submit();
}

var AppCalendar = function() {
	return {
		//main function to initiate the module
		init: function() {
			this.initCalendar();
		},

		initCalendar: function() {

			if (!jQuery().fullCalendar) {
				return;
			}

			var date = new Date();
			var d = date.getDate();
			var m = date.getMonth();
			var y = date.getFullYear();
			var h = {};

			if (App.isRTL()) {
				if ($('#calendar').parents(".portlet").width() <= 720) {
					$('#calendar').addClass("mobile");
					h = {
						right: 'title, prev, next',
						center: '',
						left: 'agendaDay, agendaWeek, month, today'
					};
				} else {
					$('#calendar').removeClass("mobile");
					h = {
						right: 'title',
						center: '',
						left: 'agendaDay, agendaWeek, month, today, prev,next'
					};
				}
			} else {
				if ($('#calendar').parents(".portlet").width() <= 720) {
					$('#calendar').addClass("mobile");
					h = {
						left: 'title, prev, next',
						center: '',
						right: 'today,month,agendaWeek,agendaDay'
					};
				} else {
					$('#calendar').removeClass("mobile");
					h = {
						left: 'title',
						center: '',
						right: 'prev,next,today,month,agendaWeek,agendaDay'
					};
				}
			}

			//predefined events
			$('#calendar').fullCalendar('destroy'); // destroy the calendar
			$('#calendar').fullCalendar({ //re-initialize the calendar
				header: h,
				defaultView: 'month', // change default view with available options from http://arshaw.com/fullcalendar/docs/views/Available_Views/
				slotMinutes: 15,
				editable: true,
				eventStartEditable: false,
				droppable: false, // this allows things to be dropped onto the calendar !!!
				eventOrder: 'timestemp',

				dayClick: function(date, jsEvent, view) {
					// call the model

					var date = new Date(date);
					var year = date.getFullYear();
					var rawmonth = date.getMonth()+1 //getMonth is zero based;
					var month = rawmonth < 10 ? '0' + rawmonth : rawmonth;
					var rawDay = date.getDate();
					var day = rawDay < 10 ? '0' + rawDay : rawDay;
					var formatted=day+"."+month+"."+year+" 00:00:00";

					<?php
					if($GLOBALS['calendar_permission']['create']){
						?>
						// DO NOT REMOVE CODE FormAjax('<?php echo base_url('admin/calendars/addEvent');?>', '', '<?php echo lang('page_create_event')?>', formatted, 'event');
						FormAjax('<?php echo base_url('admin/calendars/addEvent');?>', '', '<?php echo lang('page_create_event')?>', formatted);
						<?php
					}
					?>
				},

				eventClick: function(event) {
					//FormAjax('<?php echo base_url('admin/calendars/addEvent/');?>',event.id, event.id ,'<?php echo lang('page_edit_event')?>', '', 'event');
					// FormAjax('<?php echo base_url('admin/calendars/addEvent/');?>'+event.id, event.id ,'<?php echo lang('page_edit_event')?>', '', 'event');

					<?php
					if($GLOBALS['calendar_permission']['view']){
						?>
						FormAjaxDetail('<?php echo base_url('admin/calendars/detailEvent/');?>',event.id, event.google_eid, event.calendarId);
						<?php
					}
					?>
				},

				/*events: {
					url: '<?php echo base_url('admin/calendars/getEvents')?>'
				}*/

				viewRender : function (view, element) {
					eventGet();
				},

				lang: 'de'
			});

		}

	};

}();

//Event Date Picker
function datetimepicker(){
	//Date & Time Picker Initialize
	jQuery(".form_datetime").datetimepicker({
		autoclose: true,
		isRTL: App.isRTL(),
		format: "dd.mm.yyyy"+" hh:ii:ss",
		pickerPosition: (App.isRTL() ? "bottom-right" : "bottom-left")
	});
}

//Event Color Change
function eventColorChange(){
	// Change live the colors for colorpicker in kanban/pipeline
	$("body").on('click', '.cpicker', function() {
		var colorid = $(this).data('colorid');
		var color = $(this).data('color');
		var forecolor = $(this).data('forecolor');

		// Clicked on the same selected color
		if ($(this).hasClass('cpicker-big')) { return false; }

		$(this).parents('.cpicker-wrapper').find('.cpicker-big').removeClass('cpicker-big').addClass('cpicker-small');
		$(this).removeClass('cpicker-small', 'slow').addClass('cpicker-big', 'slow');
		if ($(this).hasClass('kanban-cpicker')) {
			$(this).parents('.panel-heading-bg').css('background', color);
			$(this).parents('.panel-heading-bg').css('border', '1px solid ' + color);
		} else if ($(this).hasClass('calendar-cpicker')) {
			$('#event_google_color_id').val(colorid);
			$('#event_color').val(color);
			$('#event_forecolor').val(forecolor);
		}
	});
}

//Event Colors Show
function eventColorDropdown(selectedColor){
	$('.cpicker-wrapper').html("<img src='<?php echo base_url('assets/global/img/loading.gif');?>' />");

	jQuery.ajax({
	   type: "POST",
	   url: '<?php echo base_url('admin/calendars/getGoogleEventColor')?>',
	   dataType: "JSON",
	   success: function(data){
			$('.cpicker-wrapper').html("");

			$.each(data, function(i, item) {
				//$('#event_color').append("<option value='"+(item.colorId)+"'>"+(item.background)+"</option>");
				var color_selected_class = 'cpicker-small';

				if(selectedColor>0){
					if (selectedColor == (i+1)){
						var color_selected_class = 'cpicker-big';
					}
				}else{
					if (i == 0) {
						var color_selected_class = 'cpicker-big';

						//Default
						$('#event_google_color_id').val(item.colorId);
						$('#event_color').val(item.background);
						$('#event_forecolor').val(item.foreground);

					}
				}

				$('.cpicker-wrapper').append("<div class='calendar-cpicker cpicker " + color_selected_class + "' data-colorid='"+item.colorId+"' data-forecolor='" + item.foreground + "' data-color='" + item.background + "' style='background:" + item.background + ";border:1px solid " + item.background + "'></div>");

			});
	   }
	});
}

//Validation Function for Add Event
/*function eventFormValidation(){
	var form1 = $('#FormModalAjax');
	var error1 = $('#FormAjax #alert_modal .alert-danger');
	var success1 = $('#FormAjax #alert_modal .alert-success');
	alert(2);
	form1.validate({
		errorElement: 'span', //default input error message container
		errorClass: 'help-block help-block-error', // default input error message class
		focusInvalid: false, // do not focus the last invalid input
		ignore: "",  // validate all fields including form hidden input

		rules: {
			title: {
				minlength: 2,
				required: true
			},
			eventstatus: {
				required: true
			},
			start: {
				required: true
			},
			end: {
				required: true
			},
			description: {
				maxlength: 255,
				required: true
			},
		},

		invalidHandler: function (event, validator) { //display error alert on form submit

				console.log(event);
				console.log(validator);
				success1.hide();
				error1.show();
				App.scrollTo(error1, -200);
				$('#FormAjax').animate({ scrollTop: 0 }, 'fast');
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
				$('#FormAjax').animate({ scrollTop: 0 }, 'fast');
				return true;
		}
	});
}*/

//Startaddress and Eventlocation Fields Show and Hide
function eventAddressFields(){
	jQuery('#event_status').change( function(){
		if(jQuery(this).val()==1 || jQuery(this).val()==2){
			jQuery('#fld_event_startaddress').show();
			jQuery('#event_startaddress').addClass('required');
			jQuery('#fld_event_address').show();
			jQuery('#event_address').addClass('required');

			//if(jQuery(this).val()==2){

				jQuery('#fld_assignmentnr').show();
				jQuery('#assignmentnr').addClass('required');

				jQuery('#fld_leadnr').show();
				jQuery('#leadnr').addClass('required');

				//jQuery('#fld_proofuser').show();
				//jQuery('#proofuser').addClass('required');

			/*}else{

				jQuery('#fld_assignmentnr').hide();
				jQuery('#assignmentnr').removeClass('required');

				//jQuery('#fld_proofuser').hide();
				//jQuery('#proofuser').removeClass('required');

			}*/
		}
		else{
			jQuery('#fld_event_startaddress').hide();
			jQuery('#event_startaddress').removeClass('required');
			//jQuery('#event_startaddress').val('');
			jQuery('#fld_event_address').hide();
			jQuery('#event_address').removeClass('required');
			//jQuery('#event_address').val('');

			jQuery('#fld_assignmentnr').hide();
			jQuery('#assignmentnr').removeClass('required');

			jQuery('#fld_leadnr').hide();
			jQuery('#leadnr').removeClass('required');


			//jQuery('#fld_proofuser').hide();
			//jQuery('#proofuser').removeClass('required');
		}
	});
}

//Only One Assignment Company or Lead Company Required
function eventAssignmentChange(){
	jQuery('#assignmentnr').change( function(){
		var v = $(this).val();
		if(v!=""){
			jQuery('#leadnr').removeClass('required');
			jQuery('#leadnr').val('');
		}else{
			jQuery('#leadnr').addClass('required');
		}
		if(v=="" && jQuery('#assignmentnr').val()==""){
			jQuery('#assignmentnr').addClass('required');
			jQuery('#leadnr').addClass('required');
		}
	});
}
function eventLeadChange(){
	jQuery('#leadnr').change( function(){
		var v = $(this).val();
		if(v!=""){
			jQuery('#assignmentnr').removeClass('required');
			jQuery('#assignmentnr').val('');
		}else{
			jQuery('#assignmentnr').addClass('required');
		}
		if(v=="" && jQuery('#leadnr').val()==""){
			jQuery('#assignmentnr').addClass('required');
			jQuery('#leadnr').addClass('required');
		}
	});
}


//Initilize
jQuery(document).ready(function() {
	eventSubmit();
	AppCalendar.init();

	//Delete Event Submit by Ajax
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
			   beforeSend:function(){
					$('#pageloaddiv').show();
			   },
			   success: function(data){
					$('#pageloaddiv').hide();

					//show response from the php script.
					//Showtoast Messagebox
					showtoast(data.response,'',data.message);

					if(url.indexOf('deleteEvent') != -1){
						jQuery('#deleteConfirmationAjax').modal('hide');
						jQuery('#FormAjax').modal('hide');
						jQuery('#FormAjaxDetail').modal('hide');
						//$('#calendar').fullCalendar( 'refetchEvents' );
						eventGet();
					}
					else if(url.indexOf('deleteGoogleEvent') != -1){
						jQuery('#deleteConfirmationAjax').modal('hide');
						jQuery('#FormAjax').modal('hide');
						jQuery('#FormAjaxDetail').modal('hide');
						//$('#calendar').fullCalendar( 'refetchEvents' );
						eventGet();
					}
			   }
		});
		});
		e.preventDefault(); // avoid to execute the actual submit of the form.
	});

	//Add Event Submit by Ajax
	jQuery("#FormModalAjax").submit(function(e) {
		var form = jQuery(this);
		var url = form.attr('action');
		var error1 = jQuery('#FormAjax #alert_modal .alert-danger');

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
			   beforeSend:function(){
					$('#pageloaddiv').show();
			   },
			   success: function(data){
					$('#pageloaddiv').hide();

					//show response from the php script.
					//Showtoast Messagebox
					if(data.response=='success'){
						showtoast(data.response,'',data.message);
						jQuery('#FormAjax').modal('hide');
						jQuery('#FormAjaxDetail').modal('hide');
						//$('#calendar').fullCalendar( 'refetchEvents' );
						eventGet();
					}
					else{
						error1.html(data.message);
						error1.show();
						$('#FormAjax').animate({ scrollTop: 0 }, 'fast');
					}
			   }
			});
		});
		e.preventDefault(); // avoid to execute the actual submit of the form.
	});

	//Filter Calendar
	jQuery(".vchecker").click( function(e){
		/*jQuery('.icheck-colors input[type=checkbox]').each(function(i, val) {
			if($(val).is(':checked')){
				alert($(val).attr('value'));
			}
		});*/
		eventGet();
	});
});

//General Event Modal Detail
function FormAjaxDetail(url, id, google_eid, calendarId){
	if (typeof id != 'undefined' && id!=""){ var url = url+id; }else{ var url = url+'0'; }
	if (typeof google_eid == 'undefined'){ var google_eid='0'; }

	//URL encode
	if (typeof calendarId == 'undefined'){
		var calendarId = '0';
	}else{
		if(calendarId!="" && calendarId!=null){
			var calendarId = calendarId.replace("#", "_has_");
			var calendarId = calendarId.replace("@", "_at_");
		}
		else{
			 var calendarId = '0';
		}
	}

	$('#FormAjaxDetail').modal('show');
	$('#FormAjaxDetail .modal-body').html("<div class='text-center'><br /><img src='<?php echo base_url('assets/global/img/loading-spinner-blue.gif');?>' /></div>");
	Pace.track(function(){
		Pace.restart();
		jQuery.ajax({url: url+'/'+google_eid+'/'+calendarId, success: function(data){
			if(data.response=='error'){
				showtoast(data.response,'',data.message);
			}
			else{
				$('#FormAjaxDetail .modal-body').html(data);
				$('#FormAjaxDetail #editEvent').attr('data-id', id);
				$('#FormAjaxDetail .modal-body').css('padding','0px');
				jQuery('[data-toggle="tooltip"]').tooltip();
			}
		}});
	});
}

$('#editEvent').click(function() {
	var id = $(this).attr('data-id');
	// DO NOT REMOVE CODE FormAjax('<?php echo base_url('admin/calendars/addEvent/');?>'+id, id,'<?php echo lang('page_edit_event')?>', '', 'event');
	FormAjax('<?php echo base_url('admin/calendars/addEvent/');?>'+id, id,'<?php echo lang('page_edit_event')?>', '');
});

//General Event Modal
function FormAjax(url, id, title, event_date, validfunc){

	$('#btn_delete_event').attr("onclick","");
	$('#btn_save_event').hide();
	$('#btn_delete_event').hide();

	$('#FormAjax').modal('show');
	$('#FormAjax #FormModalAjax').attr('action',url);
	$('#FormModalAjax .modal-title').html('<i class="fa fa-plus"></i> '+title);
	$('#FormModalAjax .modal-body').html("<div class='text-center'><img src='<?php echo base_url('assets/global/img/loading-spinner-blue.gif');?>' /></div>");
	/* Initialise for Edit */
	$('#FormModalAjax')[0].reset();

	//Clear validation
	$("span.help-block-error").hide();
	$(".has-error").removeClass("has-error");
	var error1 = jQuery('#FormAjax #alert_modal .alert-danger');
	$(error1).hide();

	Pace.track(function(){
		Pace.restart();
		jQuery.ajax({url: url, success: function(result){
			console.log(result);
			$('#FormModalAjax .modal-body').html(result);

			if(id>0){
				//$('#btn_delete_event').show();
				$('#btn_delete_event').attr("onclick","deleteConfirmation('<?php echo base_url('admin/calendars/deleteEvent/');?>',"+id+",'<?php echo lang('page_lb_delete_event')?>','<?php echo lang('page_lb_delete_event_info')?>','true');");
			}

			if(event_date!=""){
				$('#event_start').val(event_date);
				$('#event_end').val(event_date);
			}

			$('#event_public').uniform();
			datetimepicker();
			eventColorChange();
			eventColorDropdown(jQuery('#event_google_color_id').val());
			eventAddressFields();
			eventAssignmentChange();
			eventLeadChange();
			jQuery('#event_status').change();
			jQuery('#assignmentnr').change();
			jQuery('#leadnr').change();

			//Validation Form
			if (typeof validfunc !== 'undefined') {
				eval(validfunc + "FormValidation()");
			}

		}});
	});
}

$('#btn_delete_event').hide();
$('#btn_save_event').hide();
</script>
