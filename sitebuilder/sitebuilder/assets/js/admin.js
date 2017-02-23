jQuery(document).ready(function ($) {
	
	var colorPicker = $('.colorpicker');
	if (colorPicker.length) {
		colorPicker.farbtastic('#'+colorPicker.data('pickerid'));
	}
	
	//	Datetime picker
	//	http://trentrichardson.com/examples/timepicker/#basic_examples
	
	$('.datetimepicker').datetimepicker();
	$('.datepicker').datepicker();
	$('.timepicker').timepicker();
	
});
