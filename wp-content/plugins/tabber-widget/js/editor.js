var show_hide_title_inputs = function() {
	var num_widgets = jQuery("#num").val();
	
	for(var temp_counter = 1; temp_counter <= 10; temp_counter++) {
		if(temp_counter <= num_widgets) {
			jQuery("#title_input_row_" + temp_counter).show();
		}
		else {
			jQuery("#title_input_row_" + temp_counter).hide();
		}
	}
}

jQuery(document).ready(
	function() {
		show_hide_title_inputs();
		jQuery("#num").change(show_hide_title_inputs);
		
		jQuery(".delete_tabber_link").click(
			function() {
				var tabber_name = jQuery(this).parents("tr:first").find(".tabber_name").html();
				confirm("Are you sure you want to delete " + tabber_name + "?");
			}
		);
	}
);
