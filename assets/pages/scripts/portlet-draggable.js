var PortletDraggable = function () {

    return {
        //main function to initiate the module
        init: function () {

            if (!jQuery().sortable) {
                return;
            }

            $("#sortable_portlets").sortable({
                connectWith: ".portlet",
                items: ".portlet", 
                opacity: 0.8,
                handle : '.portlet-title',
                coneHelperSize: true,
                placeholder: 'portlet-sortable-placeholder',
                forcePlaceholderSize: true,
                tolerance: "pointer",
                helper: "clone",
                tolerance: "pointer",
                forcePlaceholderSize: !0,
                helper: "clone",
                cancel: ".portlet-sortable-empty, .portlet-fullscreen", // cancel dragging if portlet is in fullscreen mode
                revert: 250, // animation in milliseconds
                update: function(b, c) {
                    if (c.item.prev().hasClass("portlet-sortable-empty")) {
                        c.item.prev().before(c.item);
                    }                    
                }
            });
			
			
			$("#dashboard_sortable_portlets").sortable({
                connectWith: ".portlet",
                items: ".portlet", 
                opacity: 0.8,
                handle : '.portlet-title',
                coneHelperSize: true,
                placeholder: 'portlet-sortable-placeholder',
                forcePlaceholderSize: true,
                tolerance: "pointer",
                helper: "clone",
                tolerance: "pointer",
                forcePlaceholderSize: !0,
                helper: "clone",
                cancel: ".portlet-sortable-empty, .portlet-fullscreen", // cancel dragging if portlet is in fullscreen mode
                revert: 250, // animation in milliseconds
                update: function(event, ui) {
                    if (ui.item.prev().hasClass("portlet-sortable-empty")) {
                        ui.item.prev().before(ui.item);
                    }  

					
					var widget_order="";
					var widget_role="";
					$.each($("#dashboard_sortable_portlets .portlet-sortable"),function(){
						var widget_col = $(this).parent().attr('data-colid');
						widget_role = $(this).parent().attr('data-role');
						
						if (widget_order==''){
							widget_order = widget_col + ':' + $(this).attr('data-widget-id');
						}else{
							widget_order += "," + widget_col + ':' + $(this).attr('data-widget-id');
						}
					});
					
					
					$.post('dashboard/save_dashboard_widgets_order', 'widget_role='+widget_role+'&widget_data='+widget_order);  
                }
            });
        }
    };
}();

jQuery(document).ready(function() {
    PortletDraggable.init();
});