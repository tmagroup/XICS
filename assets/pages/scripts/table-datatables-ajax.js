var TableDatatablesAjax = function () {

    var initPickers = function () {
        //init date pickers
        $('.date-picker').datepicker({
            rtl: App.isRTL(),
            autoclose: true
        });
    }

    var handleRecords = function () {

        var grid = new Datatable();

        grid.init({
            src: $("#datatable_ajax"),
            onSuccess: function (grid, response) {
                // grid:        grid object
                // response:    json object of server side ajax response
                // execute some code after table records loaded
            },
            onError: function (grid) {
                // execute some code on network or other general error
            },
            onDataLoad: function(grid) {
                // execute some code on ajax data load
            },
            loadingMessage: 'Loading...',
            dataTable: { // here you can define a typical datatable settings from http://datatables.net/usage/options

                // Uncomment below line("dom" parameter) to fix the dropdown overflow issue in the datatable cells. The default datatable layout
                // setup uses scrollable div(table-scrollable) with overflow:auto to enable vertical scroll(see: assets/global/scripts/datatable.js).
                // So when dropdowns used the scrollable div should be removed.
                //"dom": "<'row'<'col-md-8 col-sm-12'pli><'col-md-4 col-sm-12'<'table-group-actions pull-right'>>r>t<'row'<'col-md-8 col-sm-12'pli><'col-md-4 col-sm-12'>>",

                "bStateSave": true, // save datatable state(pagination, sort, etc) in cookie.

                "lengthMenu": [
                    [10, 20, 50, 100, 150, -1],
                    [10, 20, 50, 100, 150, "All"] // change per page values here
                ],
                "pageLength": 10, // default record count per page
                "ajax": {
                    "url": 'http://localhost:8080/optimus/admin/ratesmobile/ajax', // ajax source
                },
                "order": [
                    [1, "asc"]
                ]// set first column as a default sort by asc
            }
        });

        // handle group actionsubmit button click
        grid.getTableWrapper().on('click', '.table-group-action-submit', function (e) {
            e.preventDefault();
            var action = $(".table-group-action-input", grid.getTableWrapper());
            if (action.val() != "" && grid.getSelectedRowsCount() > 0) {
                grid.setAjaxParam("customActionType", "group_action");
                grid.setAjaxParam("customActionName", action.val());
                grid.setAjaxParam("id", grid.getSelectedRows());
                grid.getDataTable().ajax.reload();
                grid.clearAjaxParams();
            } else if (action.val() == "") {
                App.alert({
                    type: 'danger',
                    icon: 'warning',
                    message: 'Please select an action',
                    container: grid.getTableWrapper(),
                    place: 'prepend'
                });
            } else if (grid.getSelectedRowsCount() === 0) {
                App.alert({
                    type: 'danger',
                    icon: 'warning',
                    message: 'No record selected',
                    container: grid.getTableWrapper(),
                    place: 'prepend'
                });
            }
        });

        grid.setAjaxParam("customActionType", "group_action");
        grid.getDataTable().ajax.reload();
        grid.clearAjaxParams();
    }

    return {

        //main function to initiate the module
        init: function () {

            initPickers();
            handleRecords();
        }

    };

}();
jQuery(document).ready(function() {
    TableDatatablesAjax.init();
});


/***********************************************************************************/
/*** CUSTOM FUNCTION DATA LISTING ********************************/
/***********************************************************************************/
/* Manage User Style for excel */
function TableCustomDatatablesAjax_excel(admin_url){

	/*if(typeof datatable_hide_columns == 'undefined'){
		var datatable_hide_columns=0;
	}*/
	var language_url = '//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/German.json';

	var table = $('#'+datatable_id);
	table.DataTable( {
		"retrieve": true,
		"processing": true,

		"serverSide": true,
		"ajax": {
			"url": admin_url,
			"type": "POST",
		},
		"pageLength": datatable_pagelength,
		"pagingType": "bootstrap_full_number",
		"filter": true,

		// setup responsive extension: http://datatables.net/extensions/responsive/
		responsive: {
			details: {
				/*type: 'column',
				target: 'tr'*/
			}
		},

		"language": {
			"url": language_url
		},

		"lengthMenu": [
			[10, 20, 50, 100, 150],
			[10, 20, 50, 100, 150] // change per page values here
		],

		"columnDefs": [
			{
				"targets": [datatable_hide_columns],
				"orderable": false,
				"searchable": false,
				"class": "hide_column"
			},
			{
				"targets": [datatable_columnDefs,datatable_columnDefs2],
				"orderable": false,
				"searchable": false,
			}
		],

		"order": [
			[datatable_sortColumn, datatable_sortColumnBy]
		], // set first column as a default sort by asc

		"dom": "<'row' <'col-md-12'B>><'row'<'col-md-6 col-sm-12'l><'col-md-6 col-sm-12'f>r><'table-scrollable't><'row'<'col-md-5 col-sm-12'i><'col-md-7 col-sm-12'p>>", // horizobtal scrollable datatable

		buttons: [
			{ extend: 'excel', text:'<i class="fa fa-file-excel-o"></i> excel', className: 'btn blue', exportOptions: { columns: excel_columns },

				//--------------------------
				/*customize: function (doc) {
					doc.content[1].table.widths = ['25%', '25%', '25%', '25%'];
				},*/
			},
		],


		"drawCallback": function(settings) {
		   jQuery('[data-toggle="tooltip"]').tooltip();
		   jQuery('.make-switch').bootstrapSwitch();
		   $('.checkboxes').uniform();

		   jQuery('.make-switch').on('switchChange.bootstrapSwitch', function (e, state) {
				var id = $(this).data('id');
				var uri = $(this).data('switch-url');
				if (!uri || !id) { return; }
				if(id==1 && uri.indexOf('users/ajax/change_active')!=-1){ return true; } //Master Admin User Can't Inactive

				if ($(this).prop('checked') == true) { status = 1; }else{  status = 0; }
				var options = {
					type: 'GET',
					url: uri+'/'+id+'/'+status
				};
				return $.ajax($.extend({}, options));
		   });

		   jQuery('#lead_datatable_ajax_filter .btn_delete_all_lead').remove();
		   jQuery('#lead_datatable_ajax_filter').append(jQuery('#btn_delete_all').html());

		},

		"fnRowCallback": function( nRow, aData, iDisplayIndex, iDisplayIndexFull ) {
			if(aData[3]=='#ff0000'){
				$('td', nRow).css('background-color', aData[3]);
				$('td', nRow).css('color', '#aaaaaa');
			}else{
				$('td', nRow).css('background-color', aData[3]);
			}
		},

		'rowsGroup': [0],

		'createdRow': function(row, data, dataIndex){
			 // Use empty value in the "Office" column
			 // as an indication that grouping with COLSPAN is needed
			 if(data[1] === 'COLSPAN'){
				// Add COLSPAN attribute
				$('td:eq(0)', row).attr('colspan', 6);
				//$('td:eq(0)', row).addClass('text-right');
				$('td:eq(1)', row).css('display', 'none');
				$('td:eq(2)', row).css('display', 'none');
				$('td:eq(3)', row).css('display', 'none');
				$('td:eq(4)', row).css('display', 'none');
				$('td:eq(5)', row).css('display', 'none');
				$('td:eq(6)', row).css('display', 'none');
			 }
		  }

	});


	table.find('.group-checkable').change(function () {
		var set = jQuery(this).attr("data-set");
		var checked = jQuery(this).is(":checked");
		jQuery(set).each(function () {
			if (checked) {
				$(this).prop("checked", true);
				$(this).parents('tr').addClass("active");
			} else {
				$(this).prop("checked", false);
				$(this).parents('tr').removeClass("active");
			}
		});
		jQuery.uniform.update(set);
	});
	table.on('change', 'tbody tr .checkboxes', function () {
		$(this).parents('tr').toggleClass("active");
	});


	var tableWrapper = jQuery('#'+datatable_id+'_wrapper');
	//jQuery(tableWrapper).removeClass('dataTables_extended_wrapper');
	setInterval(function (){ jQuery('#'+datatable_id+'_wrapper').removeClass('dataTables_extended_wrapper'); },100);
}
/* Manage User Style 1 */
function TableCustomDatatablesAjax(admin_url){

	/*if(typeof datatable_hide_columns == 'undefined'){
		var datatable_hide_columns=0;
	}*/
	var language_url = '//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/German.json';

	var table = $('#'+datatable_id);
	table.DataTable( {
		"retrieve": true,
		"processing": true,

		"serverSide": true,
		"ajax": {
			"url": admin_url,
			"type": "POST",
		},
		"pageLength": datatable_pagelength,
		"pagingType": "bootstrap_full_number",
		"filter": true,

		// setup responsive extension: http://datatables.net/extensions/responsive/
		responsive: {
			details: {
				/*type: 'column',
				target: 'tr'*/
			}
		},

		"language": {
			"url": language_url
		},

		"lengthMenu": [
			[10, 20, 50, 100, 150],
			[10, 20, 50, 100, 150] // change per page values here
		],

		"columnDefs": [
			{
				"targets": [datatable_hide_columns],
				"orderable": false,
				"searchable": false,
				"class": "hide_column"
			},
			{
				"targets": [datatable_columnDefs,datatable_columnDefs2],
				"orderable": false,
				"searchable": false,
			}
		],

		"order": [
			[datatable_sortColumn, datatable_sortColumnBy]
		], // set first column as a default sort by asc

		"drawCallback": function(settings) {
		   jQuery('[data-toggle="tooltip"]').tooltip();
		   jQuery('.make-switch').bootstrapSwitch();
		   $('.checkboxes').uniform();

		   jQuery('.make-switch').on('switchChange.bootstrapSwitch', function (e, state) {
				var id = $(this).data('id');
				var uri = $(this).data('switch-url');
				if (!uri || !id) { return; }
				if(id==1 && uri.indexOf('users/ajax/change_active')!=-1){ return true; } //Master Admin User Can't Inactive

				if ($(this).prop('checked') == true) { status = 1; }else{  status = 0; }
				var options = {
					type: 'GET',
					url: uri+'/'+id+'/'+status
				};
				return $.ajax($.extend({}, options));
		   });

		   jQuery('#lead_datatable_ajax_filter .btn_delete_all_lead').remove();
		   jQuery('#lead_datatable_ajax_filter').append(jQuery('#btn_delete_all').html());

		},

		"fnRowCallback": function( nRow, aData, iDisplayIndex, iDisplayIndexFull ) {
			if(aData[3]=='#ff0000'){
				$('td', nRow).css('background-color', aData[3]);
				$('td', nRow).css('color', '#aaaaaa');
			}else{
				$('td', nRow).css('background-color', aData[3]);
			}
		},

		'rowsGroup': [0],

		'createdRow': function(row, data, dataIndex){
			 // Use empty value in the "Office" column
			 // as an indication that grouping with COLSPAN is needed
			 if(data[1] === 'COLSPAN'){
				// Add COLSPAN attribute
				$('td:eq(0)', row).attr('colspan', 6);
				//$('td:eq(0)', row).addClass('text-right');
				$('td:eq(1)', row).css('display', 'none');
				$('td:eq(2)', row).css('display', 'none');
				$('td:eq(3)', row).css('display', 'none');
				$('td:eq(4)', row).css('display', 'none');
				$('td:eq(5)', row).css('display', 'none');
				$('td:eq(6)', row).css('display', 'none');
			 }
		  }

	});


	table.find('.group-checkable').change(function () {
		var set = jQuery(this).attr("data-set");
		var checked = jQuery(this).is(":checked");
		jQuery(set).each(function () {
			if (checked) {
				$(this).prop("checked", true);
				$(this).parents('tr').addClass("active");
			} else {
				$(this).prop("checked", false);
				$(this).parents('tr').removeClass("active");
			}
		});
		jQuery.uniform.update(set);
	});
	table.on('change', 'tbody tr .checkboxes', function () {
		$(this).parents('tr').toggleClass("active");
	});


	var tableWrapper = jQuery('#'+datatable_id+'_wrapper');
	//jQuery(tableWrapper).removeClass('dataTables_extended_wrapper');
	setInterval(function (){ jQuery('#'+datatable_id+'_wrapper').removeClass('dataTables_extended_wrapper'); },100);
}
/* Manage User Style 1-2 */
function TableCustomDatatablesAjax_2(admin_url){

	/*if(typeof datatable_hide_columns_2 == 'undefined'){
		var datatable_hide_columns=0;
	}*/
	var language_url = '//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/German.json';

	var table = $('#'+datatable_id_2);
	table.DataTable( {
		"retrieve": true,
		"processing": true,

		"serverSide": true,
		"ajax": {
			"url": admin_url,
			"type": "POST",
		},
		"pageLength": datatable_pagelength_2,
		"pagingType": "bootstrap_full_number",
		"filter": true,

		// setup responsive extension: http://datatables.net/extensions/responsive/
		responsive: {
			details: {
				/*type: 'column',
				target: 'tr'*/
			}
		},

		"language": {
			"url": language_url
		},

		"lengthMenu": [
			[10, 20, 50, 100, 150],
			[10, 20, 50, 100, 150] // change per page values here
		],

		"columnDefs": [
			{
				"targets": [datatable_hide_columns_2],
				"orderable": false,
				"searchable": false,
				"class": "hide_column"
			},
			{
				"targets": [datatable_columnDefs_2,datatable_columnDefs2_2],
				"orderable": false,
				"searchable": false,
			}
		],

		"order": [
			[datatable_sortColumn_2, datatable_sortColumnBy_2]
		], // set first column as a default sort by asc

		"drawCallback": function(settings) {
		   jQuery('[data-toggle="tooltip"]').tooltip();
		   jQuery('.make-switch').bootstrapSwitch();
		   $('.checkboxes').uniform();

		   jQuery('.make-switch').on('switchChange.bootstrapSwitch', function (e, state) {
				var id = $(this).data('id');
				var uri = $(this).data('switch-url');
				if (!uri || !id) { return; }
				if(id==1 && uri.indexOf('users/ajax/change_active')!=-1){ return true; } //Master Admin User Can't Inactive

				if ($(this).prop('checked') == true) { status = 1; }else{  status = 0; }
				var options = {
					type: 'GET',
					url: uri+'/'+id+'/'+status
				};
				return $.ajax($.extend({}, options));
		   });

		   jQuery('#lead_datatable_ajax_filter .btn_delete_all_lead').remove();
		   jQuery('#lead_datatable_ajax_filter').append(jQuery('#btn_delete_all').html());

		},

		"fnRowCallback": function( nRow, aData, iDisplayIndex, iDisplayIndexFull ) {
			if(aData[3]=='#ff0000'){
				$('td', nRow).css('background-color', aData[3]);
				$('td', nRow).css('color', '#aaaaaa');
			}else{
				$('td', nRow).css('background-color', aData[3]);
			}
		},

		'rowsGroup': [0],

		'createdRow': function(row, data, dataIndex){
			 // Use empty value in the "Office" column
			 // as an indication that grouping with COLSPAN is needed
			 if(data[1] === 'COLSPAN'){
				// Add COLSPAN attribute
				$('td:eq(0)', row).attr('colspan', 6);
				//$('td:eq(0)', row).addClass('text-right');
				$('td:eq(1)', row).css('display', 'none');
				$('td:eq(2)', row).css('display', 'none');
				$('td:eq(3)', row).css('display', 'none');
				$('td:eq(4)', row).css('display', 'none');
				$('td:eq(5)', row).css('display', 'none');
				$('td:eq(6)', row).css('display', 'none');
			 }
		  }

	});


	table.find('.group-checkable').change(function () {
		var set = jQuery(this).attr("data-set");
		var checked = jQuery(this).is(":checked");
		jQuery(set).each(function () {
			if (checked) {
				$(this).prop("checked", true);
				$(this).parents('tr').addClass("active");
			} else {
				$(this).prop("checked", false);
				$(this).parents('tr').removeClass("active");
			}
		});
		jQuery.uniform.update(set);
	});
	table.on('change', 'tbody tr .checkboxes', function () {
		$(this).parents('tr').toggleClass("active");
	});


	var tableWrapper = jQuery('#'+datatable_id_2+'_wrapper');
	//jQuery(tableWrapper).removeClass('dataTables_extended_wrapper');
	setInterval(function (){ jQuery('#'+datatable_id_2+'_wrapper').removeClass('dataTables_extended_wrapper'); },100);
}
/* Manage User Style 1-3 */
function TableCustomDatatablesAjax_3(admin_url){

	/*if(typeof datatable_hide_columns_3 == 'undefined'){
		var datatable_hide_columns=0;
	}*/
	var language_url = '//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/German.json';

	var table = $('#'+datatable_id_3);
	table.DataTable( {
		"retrieve": true,
		"processing": true,

		"serverSide": true,
		"ajax": {
			"url": admin_url,
			"type": "POST",
		},
		"pageLength": datatable_pagelength_3,
		"pagingType": "bootstrap_full_number",
		"filter": true,

		// setup responsive extension: http://datatables.net/extensions/responsive/
		responsive: {
			details: {
				/*type: 'column',
				target: 'tr'*/
			}
		},

		"language": {
			"url": language_url
		},

		"lengthMenu": [
			[10, 20, 50, 100, 150],
			[10, 20, 50, 100, 150] // change per page values here
		],

		"columnDefs": [
			{
				"targets": [datatable_hide_columns_3],
				"orderable": false,
				"searchable": false,
				"class": "hide_column"
			},
			{
				"targets": [datatable_columnDefs_3,datatable_columnDefs2_3],
				"orderable": false,
				"searchable": false,
			}
		],

		"order": [
			[datatable_sortColumn_3, datatable_sortColumnBy_3]
		], // set first column as a default sort by asc

		"drawCallback": function(settings) {
		   jQuery('[data-toggle="tooltip"]').tooltip();
		   jQuery('.make-switch').bootstrapSwitch();
		   $('.checkboxes').uniform();

		   jQuery('.make-switch').on('switchChange.bootstrapSwitch', function (e, state) {
				var id = $(this).data('id');
				var uri = $(this).data('switch-url');
				if (!uri || !id) { return; }
				if(id==1 && uri.indexOf('users/ajax/change_active')!=-1){ return true; } //Master Admin User Can't Inactive

				if ($(this).prop('checked') == true) { status = 1; }else{  status = 0; }
				var options = {
					type: 'GET',
					url: uri+'/'+id+'/'+status
				};
				return $.ajax($.extend({}, options));
		   });

		   jQuery('#lead_datatable_ajax_filter .btn_delete_all_lead').remove();
		   jQuery('#lead_datatable_ajax_filter').append(jQuery('#btn_delete_all').html());

		},

		"fnRowCallback": function( nRow, aData, iDisplayIndex, iDisplayIndexFull ) {
			if(aData[3]=='#ff0000'){
				$('td', nRow).css('background-color', aData[3]);
				$('td', nRow).css('color', '#aaaaaa');
			}else{
				$('td', nRow).css('background-color', aData[3]);
			}
		},

		'rowsGroup': [0],

		'createdRow': function(row, data, dataIndex){
			 // Use empty value in the "Office" column
			 // as an indication that grouping with COLSPAN is needed
			 if(data[1] === 'COLSPAN'){
				// Add COLSPAN attribute
				$('td:eq(0)', row).attr('colspan', 6);
				//$('td:eq(0)', row).addClass('text-right');
				$('td:eq(1)', row).css('display', 'none');
				$('td:eq(2)', row).css('display', 'none');
				$('td:eq(3)', row).css('display', 'none');
				$('td:eq(4)', row).css('display', 'none');
				$('td:eq(5)', row).css('display', 'none');
				$('td:eq(6)', row).css('display', 'none');
			 }
		  }

	});


	table.find('.group-checkable').change(function () {
		var set = jQuery(this).attr("data-set");
		var checked = jQuery(this).is(":checked");
		jQuery(set).each(function () {
			if (checked) {
				$(this).prop("checked", true);
				$(this).parents('tr').addClass("active");
			} else {
				$(this).prop("checked", false);
				$(this).parents('tr').removeClass("active");
			}
		});
		jQuery.uniform.update(set);
	});
	table.on('change', 'tbody tr .checkboxes', function () {
		$(this).parents('tr').toggleClass("active");
	});


	var tableWrapper = jQuery('#'+datatable_id_3+'_wrapper');
	//jQuery(tableWrapper).removeClass('dataTables_extended_wrapper');
	setInterval(function (){ jQuery('#'+datatable_id_3+'_wrapper').removeClass('dataTables_extended_wrapper'); },100);
}
/* Manage User Style 1-4 */
function TableCustomDatatablesAjax_4(admin_url){

	/*if(typeof datatable_hide_columns == 'undefined'){
		var datatable_hide_columns=0;
	}*/
	var language_url = '//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/German.json';

	var table = $('#'+datatable_id_4);
	table.DataTable( {
		"retrieve": true,
		"processing": true,

		"serverSide": true,
		"ajax": {
			"url": admin_url,
			"type": "POST",
		},
		"pageLength": datatable_pagelength_4,
		"pagingType": "bootstrap_full_number",
		"filter": true,

		// setup responsive extension: http://datatables.net/extensions/responsive/
		responsive: {
			details: {
				/*type: 'column',
				target: 'tr'*/
			}
		},

		"language": {
			"url": language_url
		},

		"lengthMenu": [
			[10, 20, 50, 100, 150],
			[10, 20, 50, 100, 150] // change per page values here
		],

		"columnDefs": [
			{
				"targets": [datatable_hide_columns_4],
				"orderable": false,
				"searchable": false,
				"class": "hide_column"
			},
			{
				"targets": [datatable_columnDefs_4,datatable_columnDefs2_4],
				"orderable": false,
				"searchable": false,
			}
		],

		"order": [
			[datatable_sortColumn_4, datatable_sortColumnBy_4]
		], // set first column as a default sort by asc

		"drawCallback": function(settings) {
		   jQuery('[data-toggle="tooltip"]').tooltip();
		   jQuery('.make-switch').bootstrapSwitch();
		   $('.checkboxes').uniform();

		   jQuery('.make-switch').on('switchChange.bootstrapSwitch', function (e, state) {
				var id = $(this).data('id');
				var uri = $(this).data('switch-url');
				if (!uri || !id) { return; }
				if(id==1 && uri.indexOf('users/ajax/change_active')!=-1){ return true; } //Master Admin User Can't Inactive

				if ($(this).prop('checked') == true) { status = 1; }else{  status = 0; }
				var options = {
					type: 'GET',
					url: uri+'/'+id+'/'+status
				};
				return $.ajax($.extend({}, options));
		   });

		   jQuery('#lead_datatable_ajax_filter .btn_delete_all_lead').remove();
		   jQuery('#lead_datatable_ajax_filter').append(jQuery('#btn_delete_all').html());

		},

		"fnRowCallback": function( nRow, aData, iDisplayIndex, iDisplayIndexFull ) {
			if(aData[3]=='#ff0000'){
				$('td', nRow).css('background-color', aData[3]);
				$('td', nRow).css('color', '#aaaaaa');
			}else{
				$('td', nRow).css('background-color', aData[3]);
			}
		},

		'rowsGroup': [0],

		'createdRow': function(row, data, dataIndex){
			 // Use empty value in the "Office" column
			 // as an indication that grouping with COLSPAN is needed
			 if(data[1] === 'COLSPAN'){
				// Add COLSPAN attribute
				$('td:eq(0)', row).attr('colspan', 6);
				//$('td:eq(0)', row).addClass('text-right');
				$('td:eq(1)', row).css('display', 'none');
				$('td:eq(2)', row).css('display', 'none');
				$('td:eq(3)', row).css('display', 'none');
				$('td:eq(4)', row).css('display', 'none');
				$('td:eq(5)', row).css('display', 'none');
				$('td:eq(6)', row).css('display', 'none');
			 }
		  }

	});


	table.find('.group-checkable').change(function () {
		var set = jQuery(this).attr("data-set");
		var checked = jQuery(this).is(":checked");
		jQuery(set).each(function () {
			if (checked) {
				$(this).prop("checked", true);
				$(this).parents('tr').addClass("active");
			} else {
				$(this).prop("checked", false);
				$(this).parents('tr').removeClass("active");
			}
		});
		jQuery.uniform.update(set);
	});
	table.on('change', 'tbody tr .checkboxes', function () {
		$(this).parents('tr').toggleClass("active");
	});


	var tableWrapper = jQuery('#'+datatable_id_4+'_wrapper');
	//jQuery(tableWrapper).removeClass('dataTables_extended_wrapper');
	setInterval(function (){ jQuery('#'+datatable_id_4+'_wrapper').removeClass('dataTables_extended_wrapper'); },100);
}
/* Manage User Style 1-5 */
function TableCustomDatatablesAjax_5(admin_url){

	/*if(typeof datatable_hide_columns == 'undefined'){
		var datatable_hide_columns=0;
	}*/
	var language_url = '//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/German.json';

	var table = $('#'+datatable_id_5);
	table.DataTable( {
		"retrieve": true,
		"processing": true,

		"serverSide": true,
		"ajax": {
			"url": admin_url,
			"type": "POST",
		},
		"pageLength": datatable_pagelength_5,
		"pagingType": "bootstrap_full_number",
		"filter": true,

		// setup responsive extension: http://datatables.net/extensions/responsive/
		responsive: {
			details: {
				/*type: 'column',
				target: 'tr'*/
			}
		},

		"language": {
			"url": language_url
		},

		"lengthMenu": [
			[10, 20, 50, 100, 150],
			[10, 20, 50, 100, 150] // change per page values here
		],

		"columnDefs": [
			{
				"targets": [datatable_hide_columns_5],
				"orderable": false,
				"searchable": false,
				"class": "hide_column"
			},
			{
				"targets": [datatable_columnDefs_5,datatable_columnDefs2_5],
				"orderable": false,
				"searchable": false,
			}
		],

		"order": [
			[datatable_sortColumn_5, datatable_sortColumnBy_5]
		], // set first column as a default sort by asc

		"drawCallback": function(settings) {
		   jQuery('[data-toggle="tooltip"]').tooltip();
		   jQuery('.make-switch').bootstrapSwitch();
		   $('.checkboxes').uniform();

		   jQuery('.make-switch').on('switchChange.bootstrapSwitch', function (e, state) {
				var id = $(this).data('id');
				var uri = $(this).data('switch-url');
				if (!uri || !id) { return; }
				if(id==1 && uri.indexOf('users/ajax/change_active')!=-1){ return true; } //Master Admin User Can't Inactive

				if ($(this).prop('checked') == true) { status = 1; }else{  status = 0; }
				var options = {
					type: 'GET',
					url: uri+'/'+id+'/'+status
				};
				return $.ajax($.extend({}, options));
		   });

		   jQuery('#lead_datatable_ajax_filter .btn_delete_all_lead').remove();
		   jQuery('#lead_datatable_ajax_filter').append(jQuery('#btn_delete_all').html());

		},

		"fnRowCallback": function( nRow, aData, iDisplayIndex, iDisplayIndexFull ) {
			if(aData[3]=='#ff0000'){
				$('td', nRow).css('background-color', aData[3]);
				$('td', nRow).css('color', '#aaaaaa');
			}else{
				$('td', nRow).css('background-color', aData[3]);
			}
		},

		'rowsGroup': [0],

		'createdRow': function(row, data, dataIndex){
			 // Use empty value in the "Office" column
			 // as an indication that grouping with COLSPAN is needed
			 if(data[1] === 'COLSPAN'){
				// Add COLSPAN attribute
				$('td:eq(0)', row).attr('colspan', 6);
				//$('td:eq(0)', row).addClass('text-right');
				$('td:eq(1)', row).css('display', 'none');
				$('td:eq(2)', row).css('display', 'none');
				$('td:eq(3)', row).css('display', 'none');
				$('td:eq(4)', row).css('display', 'none');
				$('td:eq(5)', row).css('display', 'none');
				$('td:eq(6)', row).css('display', 'none');
			 }
		  }

	});


	table.find('.group-checkable').change(function () {
		var set = jQuery(this).attr("data-set");
		var checked = jQuery(this).is(":checked");
		jQuery(set).each(function () {
			if (checked) {
				$(this).prop("checked", true);
				$(this).parents('tr').addClass("active");
			} else {
				$(this).prop("checked", false);
				$(this).parents('tr').removeClass("active");
			}
		});
		jQuery.uniform.update(set);
	});
	table.on('change', 'tbody tr .checkboxes', function () {
		$(this).parents('tr').toggleClass("active");
	});


	var tableWrapper = jQuery('#'+datatable_id_5+'_wrapper');
	//jQuery(tableWrapper).removeClass('dataTables_extended_wrapper');
	setInterval(function (){ jQuery('#'+datatable_id_5+'_wrapper').removeClass('dataTables_extended_wrapper'); },100);
}
/* Manage User Style 1-6 */
function TableCustomDatatablesAjax_6(admin_url){

	/*if(typeof datatable_hide_columns == 'undefined'){
		var datatable_hide_columns=0;
	}*/
	var language_url = '//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/German.json';

	var table = $('#'+datatable_id_6);
	table.DataTable( {
		"retrieve": true,
		"processing": true,

		"serverSide": true,
		"ajax": {
			"url": admin_url,
			"type": "POST",
		},
		"pageLength": datatable_pagelength_6,
		"pagingType": "bootstrap_full_number",
		"filter": true,

		// setup responsive extension: http://datatables.net/extensions/responsive/
		responsive: {
			details: {
				/*type: 'column',
				target: 'tr'*/
			}
		},

		"language": {
			"url": language_url
		},

		"lengthMenu": [
			[10, 20, 50, 100, 150],
			[10, 20, 50, 100, 150] // change per page values here
		],

		"columnDefs": [
			{
				"targets": [datatable_hide_columns_6],
				"orderable": false,
				"searchable": false,
				"class": "hide_column"
			},
			{
				"targets": [datatable_columnDefs_6,datatable_columnDefs2_6],
				"orderable": false,
				"searchable": false,
			}
		],

		"order": [
			[datatable_sortColumn_6, datatable_sortColumnBy_6]
		], // set first column as a default sort by asc

		"drawCallback": function(settings) {
		   jQuery('[data-toggle="tooltip"]').tooltip();
		   jQuery('.make-switch').bootstrapSwitch();
		   $('.checkboxes').uniform();

		   jQuery('.make-switch').on('switchChange.bootstrapSwitch', function (e, state) {
				var id = $(this).data('id');
				var uri = $(this).data('switch-url');
				if (!uri || !id) { return; }
				if(id==1 && uri.indexOf('users/ajax/change_active')!=-1){ return true; } //Master Admin User Can't Inactive

				if ($(this).prop('checked') == true) { status = 1; }else{  status = 0; }
				var options = {
					type: 'GET',
					url: uri+'/'+id+'/'+status
				};
				return $.ajax($.extend({}, options));
		   });

		   jQuery('#lead_datatable_ajax_filter .btn_delete_all_lead').remove();
		   jQuery('#lead_datatable_ajax_filter').append(jQuery('#btn_delete_all').html());

		},

		"fnRowCallback": function( nRow, aData, iDisplayIndex, iDisplayIndexFull ) {
			if(aData[3]=='#ff0000'){
				$('td', nRow).css('background-color', aData[3]);
				$('td', nRow).css('color', '#aaaaaa');
			}else{
				$('td', nRow).css('background-color', aData[3]);
			}
		},

		'rowsGroup': [0],

		'createdRow': function(row, data, dataIndex){
			 // Use empty value in the "Office" column
			 // as an indication that grouping with COLSPAN is needed
			 if(data[1] === 'COLSPAN'){
				// Add COLSPAN attribute
				$('td:eq(0)', row).attr('colspan', 6);
				//$('td:eq(0)', row).addClass('text-right');
				$('td:eq(1)', row).css('display', 'none');
				$('td:eq(2)', row).css('display', 'none');
				$('td:eq(3)', row).css('display', 'none');
				$('td:eq(4)', row).css('display', 'none');
				$('td:eq(5)', row).css('display', 'none');
				$('td:eq(6)', row).css('display', 'none');
			 }
		  }

	});


	table.find('.group-checkable').change(function () {
		var set = jQuery(this).attr("data-set");
		var checked = jQuery(this).is(":checked");
		jQuery(set).each(function () {
			if (checked) {
				$(this).prop("checked", true);
				$(this).parents('tr').addClass("active");
			} else {
				$(this).prop("checked", false);
				$(this).parents('tr').removeClass("active");
			}
		});
		jQuery.uniform.update(set);
	});
	table.on('change', 'tbody tr .checkboxes', function () {
		$(this).parents('tr').toggleClass("active");
	});


	var tableWrapper = jQuery('#'+datatable_id_6+'_wrapper');
	//jQuery(tableWrapper).removeClass('dataTables_extended_wrapper');
	setInterval(function (){ jQuery('#'+datatable_id_6+'_wrapper').removeClass('dataTables_extended_wrapper'); },100);
}

function TableCustomDatatablesAjax_7(admin_url){

	/*if(typeof datatable_hide_columns == 'undefined'){
		var datatable_hide_columns=0;
	}*/
	var language_url = '//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/German.json';

	var table = $('#'+datatable_id_7);
	table.DataTable( {
		"retrieve": true,
		"processing": true,

		"serverSide": true,
		"ajax": {
			"url": admin_url,
			"type": "POST",
		},
		"pageLength": datatable_pagelength_7,
		"pagingType": "bootstrap_full_number",
		"filter": true,

		// setup responsive extension: http://datatables.net/extensions/responsive/
		responsive: {
			details: {
				/*type: 'column',
				target: 'tr'*/
			}
		},

		"language": {
			"url": language_url
		},

		"lengthMenu": [
			[10, 20, 50, 100, 150],
			[10, 20, 50, 100, 150] // change per page values here
		],

		"columnDefs": [
			{
				"targets": [datatable_hide_columns_7],
				"orderable": false,
				"searchable": false,
				"class": "hide_column"
			},
			{
				"targets": [datatable_columnDefs_7,datatable_columnDefs2_7],
				"orderable": false,
				"searchable": false,
			}
		],

		"order": [
			[datatable_sortColumn_7, datatable_sortColumnBy_7]
		], // set first column as a default sort by asc

		"drawCallback": function(settings) {
		   jQuery('[data-toggle="tooltip"]').tooltip();
		   jQuery('.make-switch').bootstrapSwitch();
		   $('.checkboxes').uniform();

		   jQuery('.make-switch').on('switchChange.bootstrapSwitch', function (e, state) {
				var id = $(this).data('id');
				var uri = $(this).data('switch-url');
				if (!uri || !id) { return; }
				if(id==1 && uri.indexOf('users/ajax/change_active')!=-1){ return true; } //Master Admin User Can't Inactive

				if ($(this).prop('checked') == true) { status = 1; }else{  status = 0; }
				var options = {
					type: 'GET',
					url: uri+'/'+id+'/'+status
				};
				return $.ajax($.extend({}, options));
		   });

		   jQuery('#lead_datatable_ajax_filter .btn_delete_all_lead').remove();
		   jQuery('#lead_datatable_ajax_filter').append(jQuery('#btn_delete_all').html());

		},

		"fnRowCallback": function( nRow, aData, iDisplayIndex, iDisplayIndexFull ) {
			if(aData[3]=='#ff0000'){
				$('td', nRow).css('background-color', aData[3]);
				$('td', nRow).css('color', '#aaaaaa');
			}else{
				$('td', nRow).css('background-color', aData[3]);
			}
		},

		'rowsGroup': [0],

		'createdRow': function(row, data, dataIndex){
			 // Use empty value in the "Office" column
			 // as an indication that grouping with COLSPAN is needed
			 if(data[1] === 'COLSPAN'){
				// Add COLSPAN attribute
				$('td:eq(0)', row).attr('colspan', 6);
				//$('td:eq(0)', row).addClass('text-right');
				$('td:eq(1)', row).css('display', 'none');
				$('td:eq(2)', row).css('display', 'none');
				$('td:eq(3)', row).css('display', 'none');
				$('td:eq(4)', row).css('display', 'none');
				$('td:eq(5)', row).css('display', 'none');
				$('td:eq(6)', row).css('display', 'none');
			 }
		  }

	});


	table.find('.group-checkable').change(function () {
		var set = jQuery(this).attr("data-set");
		var checked = jQuery(this).is(":checked");
		jQuery(set).each(function () {
			if (checked) {
				$(this).prop("checked", true);
				$(this).parents('tr').addClass("active");
			} else {
				$(this).prop("checked", false);
				$(this).parents('tr').removeClass("active");
			}
		});
		jQuery.uniform.update(set);
	});
	table.on('change', 'tbody tr .checkboxes', function () {
		$(this).parents('tr').toggleClass("active");
	});


	var tableWrapper = jQuery('#'+datatable_id_7+'_wrapper');
	//jQuery(tableWrapper).removeClass('dataTables_extended_wrapper');
	setInterval(function (){ jQuery('#'+datatable_id_7+'_wrapper').removeClass('dataTables_extended_wrapper'); },100);
}
/* Manage User Style Button */
function TableCustomDatatablesAjax_Button(admin_url){

	/*if(typeof datatable_hide_columns == 'undefined'){
		var datatable_hide_columns=0;
	}*/
	var language_url = '//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/German.json';

	var table = $('#'+datatable_id);
	table.DataTable( {
		"retrieve": true,
		"processing": true,

		"serverSide": true,
		"ajax": {
			"url": admin_url,
			"type": "POST",
		},
		"pageLength": datatable_pagelength,
		"pagingType": "bootstrap_full_number",
		"filter": true,

		// setup responsive extension: http://datatables.net/extensions/responsive/
		responsive: {
			details: {
				/*type: 'column',
				target: 'tr'*/
			}
		},

		"language": {
			"url": language_url
		},

		"lengthMenu": [
			[10, 20, 50, 100, 150],
			[10, 20, 50, 100, 150] // change per page values here
		],

		"columnDefs": [
			{
				"targets": [datatable_hide_columns],
				"orderable": false,
				"searchable": false,
				"class": "hide_column"
			},
			{
				"targets": [datatable_columnDefs,datatable_columnDefs2],
				"orderable": false,
				"searchable": false,
			}
		],

		"order": [
			[datatable_sortColumn, datatable_sortColumnBy]
		], // set first column as a default sort by asc

		"dom": "<'row' <'col-md-12'B>><'row'<'col-md-6 col-sm-12'l><'col-md-6 col-sm-12'f>r><'table-scrollable't><'row'<'col-md-5 col-sm-12'i><'col-md-7 col-sm-12'p>>", // horizobtal scrollable datatable

		buttons: [
			{ extend: 'pdf', text:'<i class="fa fa-file-pdf-o"></i> PDF', className: 'btn red', exportOptions: { columns: [0,1,2,3] },

				//--------------------------
				customize: function (doc) {
					doc.content[1].table.widths = ['25%', '25%', '25%', '25%'];
				},
			},
		],


		"drawCallback": function(settings) {
		   jQuery('[data-toggle="tooltip"]').tooltip();
		   jQuery('.make-switch').bootstrapSwitch();
		   $('.checkboxes').uniform();

		   jQuery('.make-switch').on('switchChange.bootstrapSwitch', function (e, state) {
				var id = $(this).data('id');
				var uri = $(this).data('switch-url');
				if (!uri || !id) { return; }
				if(id==1 && uri.indexOf('users/ajax/change_active')!=-1){ return true; } //Master Admin User Can't Inactive

				if ($(this).prop('checked') == true) { status = 1; }else{  status = 0; }
				var options = {
					type: 'GET',
					url: uri+'/'+id+'/'+status
				};
				return $.ajax($.extend({}, options));
		   });

		   jQuery('#lead_datatable_ajax_filter .btn_delete_all_lead').remove();
		   jQuery('#lead_datatable_ajax_filter').append(jQuery('#btn_delete_all').html());

		},

		"fnRowCallback": function( nRow, aData, iDisplayIndex, iDisplayIndexFull ) {
			if(aData[3]=='#ff0000'){
				$('td', nRow).css('background-color', aData[3]);
				$('td', nRow).css('color', '#aaaaaa');
			}else{
				$('td', nRow).css('background-color', aData[3]);
			}
		},

		'rowsGroup': [0],

		'createdRow': function(row, data, dataIndex){
			 // Use empty value in the "Office" column
			 // as an indication that grouping with COLSPAN is needed
			 if(data[1] === 'COLSPAN'){
				// Add COLSPAN attribute
				$('td:eq(0)', row).attr('colspan', 6);
				//$('td:eq(0)', row).addClass('text-right');
				$('td:eq(1)', row).css('display', 'none');
				$('td:eq(2)', row).css('display', 'none');
				$('td:eq(3)', row).css('display', 'none');
				$('td:eq(4)', row).css('display', 'none');
				$('td:eq(5)', row).css('display', 'none');
				$('td:eq(6)', row).css('display', 'none');
			 }
		  }

	});


	table.find('.group-checkable').change(function () {
		var set = jQuery(this).attr("data-set");
		var checked = jQuery(this).is(":checked");
		jQuery(set).each(function () {
			if (checked) {
				$(this).prop("checked", true);
				$(this).parents('tr').addClass("active");
			} else {
				$(this).prop("checked", false);
				$(this).parents('tr').removeClass("active");
			}
		});
		jQuery.uniform.update(set);
	});
	table.on('change', 'tbody tr .checkboxes', function () {
		$(this).parents('tr').toggleClass("active");
	});


	var tableWrapper = jQuery('#'+datatable_id+'_wrapper');
	//jQuery(tableWrapper).removeClass('dataTables_extended_wrapper');
	setInterval(function (){ jQuery('#'+datatable_id+'_wrapper').removeClass('dataTables_extended_wrapper'); },100);
}


/* Manage User Style 2 */
function TableCustomDatatablesAjax2(){

	var language_url = '//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/German.json';

	//init date pickers
	$('.date-picker').datepicker({
		rtl: App.isRTL(),
		autoclose: true
	});

	var grid = new Datatable();

	grid.init({
		src: $("#"+datatable_id),
		onSuccess: function (grid, response) {
			// grid:        grid object
			// response:    json object of server side ajax response
			// execute some code after table records loaded
		},
		onError: function (grid) {
			// execute some code on network or other general error
		},
		onDataLoad: function(grid) {
			// execute some code on ajax data load
		},
		loadingMessage: 'Loading...',
		dataTable: { // here you can define a typical datatable settings from http://datatables.net/usage/options

			// Uncomment below line("dom" parameter) to fix the dropdown overflow issue in the datatable cells. The default datatable layout
			// setup uses scrollable div(table-scrollable) with overflow:auto to enable vertical scroll(see: assets/global/scripts/datatable.js).
			// So when dropdowns used the scrollable div should be removed.
			//"dom": "<'row'<'col-md-8 col-sm-12'pli><'col-md-4 col-sm-12'<'table-group-actions pull-right'>>r>t<'row'<'col-md-8 col-sm-12'pli><'col-md-4 col-sm-12'>>",

			"bStateSave": true, // save datatable state(pagination, sort, etc) in cookie.

			"columnDefs": [ {
				"targets": datatable_columnDefs,
				"orderable": false,
				"searchable": false
			}],

			"language": {
				"url": language_url
			},

			"lengthMenu": [
				[10, 20, 50, 100, 150, -1],
				[10, 20, 50, 100, 150, "All"] // change per page values here
			],
			"pageLength": datatable_pagelength, // default record count per page
			"ajax": {
				"url": admin_url, // ajax source
			},
			"order": [
				[datatable_sortColumn, datatable_sortColumnBy]
			]// set first column as a default sort by asc
		},


	});

	// handle group actionsubmit button click
	grid.getTableWrapper().on('click', '.table-group-action-submit', function (e) {
		e.preventDefault();
		var action = $(".table-group-action-input", grid.getTableWrapper());
		if (action.val() != "" && grid.getSelectedRowsCount() > 0) {
			grid.setAjaxParam("customActionType", "group_action");
			grid.setAjaxParam("customActionName", action.val());
			grid.setAjaxParam("id", grid.getSelectedRows());
			grid.getDataTable().ajax.reload();
			grid.clearAjaxParams();
		} else if (action.val() == "") {
			App.alert({
				type: 'danger',
				icon: 'warning',
				message: 'Please select an action',
				container: grid.getTableWrapper(),
				place: 'prepend'
			});
		} else if (grid.getSelectedRowsCount() === 0) {
			App.alert({
				type: 'danger',
				icon: 'warning',
				message: 'No record selected',
				container: grid.getTableWrapper(),
				place: 'prepend'
			});
		}
	});

	grid.setAjaxParam("customActionType", "group_action");
	grid.getDataTable().ajax.reload();
	grid.clearAjaxParams();

}
/***********************************************************************************/
/*** END CUSTOM FUNCTION DATA LISTING ********************************/
/***********************************************************************************/