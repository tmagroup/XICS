<script type="text/javascript">
jQuery(document).ready(function() {
	// Date & Time Picker Initialize
	jQuery('.form_datetime').datetimepicker({
		autoclose: true,
		isRTL: App.isRTL(),
		pickerPosition: (App.isRTL() ? 'bottom-right' : 'bottom-left'),

		<?php $time_format = get_option('time_format'); ?>
		<?php if ( $time_format == 24 ) { ?>
			format: 'dd.mm.yyyy hh:ii:ss',
		<?php } else { ?>
			format: 'dd.mm.yyyy HH:ii:ss P',
			showMeridian: true,
		<?php } ?>
	});
});
</script>
