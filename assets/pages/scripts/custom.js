/**
Custom module for you to write your own javascript functions
**/
/*var Custom = function () {

    // private functions & variables

    var myFunc = function(text) {
        alert(text);
    }

    // public functions
    return {

        //main function
        init: function () {
            //initialize here something.            
        },

        //some helper function
        doSomeStuff: function () {
            myFunc();
        }

    };

}();

jQuery(document).ready(function() {    
   Custom.init(); 
});*/

/***
Usage
***/
//Custom.doSomeStuff();


jQuery(document).ready(function() {	
	/* Use Tooltip for Global */
	jQuery('[data-toggle="tooltip"]').tooltip();	
});	

function showtoast(type, title, msg){		
	toastr.options = {
		"closeButton": true,
		"debug": false,
		"positionClass": "toast-top-right",
		"onclick": null,
		"showDuration": "1000",
		"hideDuration": "1000",
		"timeOut": "5000",
		"extendedTimeOut": "1000",
		"showEasing": "swing",
		"hideEasing": "linear",
		"showMethod": "fadeIn",
		"hideMethod": "fadeOut"
	};	
	var $toast = toastr[type](msg, title);		
}

function showtoast_reminder(type, title, msg, open_status_url, read_status_url){		
	toastr.options = {
		"closeButton": true,
		"debug": false,
		"positionClass": "toast-top-right",
		"onclick": null,
		"showDuration": "1000",
		"hideDuration": "1000",
		"timeOut": "50000000",
		"extendedTimeOut": "1000",
		"showEasing": "swing",
		"hideEasing": "linear",
		"showMethod": "fadeIn",
		"hideMethod": "fadeOut"
	};
	toastr.options.onShown = function () {
		//Open Status Change by Ajax call
		jQuery.ajax({dataType: "JSON", url: open_status_url, success: function(data){}});
		document.getElementById("bell").play();
	};
			
	toastr.options.onHidden = function () {
		//Read Status Change by Ajax call
		jQuery.ajax({dataType: "JSON", url: read_status_url, success: function(data){}});
	};
	var $toast = toastr[type](msg, title);		
}

//Send Reminder 
function sendReminder(url, id, reltype, isAjax, parentid){  

		if (typeof parentid == 'undefined') {
            parentid = 0;
        }
		 
    if(isAjax=='true'){        
        Pace.track(function(){
            Pace.restart();            
            jQuery.ajax({dataType: "JSON", url: (url+'/'+id+'/'+reltype+'/'+parentid), success: function(data){                           
                showtoast(data.response,'',data.message); 
            }});        
        });
    }else{
        window.location = url+'/'+id;
    }
}