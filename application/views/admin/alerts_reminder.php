<script>
//Get Reminder By Ajax    
function showReminder(){	    
    /*jQuery.ajax({dataType: "JSON", url: '<?php echo base_url('admin/reminders/getUserReminders/');?>', success: function(data){		
        if(data.length>0){       
            jQuery.each( data, function( key, value ) {
               if (typeof value.subject !== 'undefined') {        
                    showtoast_reminder('bell', value.subject+' ('+value.type+')', value.message+'<br /><?php echo lang('from');?>: '+value.fromname, '<?php echo base_url('admin/reminders/changeUserReminderStatus/open/');?>'+value.id+'/'+value.rel_type, '<?php echo base_url('admin/reminders/changeUserReminderStatus/read/');?>'+value.id+'/'+value.rel_type); 
               }
            });
        }
    }});*/    
    //Pusher Notification 
    jQuery.ajax({url: '<?php echo base_url('admin/dashboard/getDashboardUserReminders/');?>', success: function(data){	
        var cn = $('#currentNotificationCount').val();   
        var temp = data.split('[=]');  
        if(cn==temp[0]){            
        }
        else{
            if(parseInt(temp[0])>0){
                $('#currentNotificationCount').val(temp[0]);
                $('#header_notification_bar').html(temp[1]);
                $('#widget_dashboard_notifications').html(temp[2]);
                document.getElementById("bell").play();
            }
        }
    }});
}
//setTimeout("showReminder()",5000);
setInterval("showReminder();",5000);


//Change Read Status of Notification
$('#header_notification_bar').click( function(){     
    jQuery.ajax({url: '<?php echo base_url('admin/dashboard/readDashboardUserReminders/');?>', success: function(){}});
});
</script>