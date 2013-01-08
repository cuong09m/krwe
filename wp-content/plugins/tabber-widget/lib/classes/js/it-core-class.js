jQuery(document).ready(
	function() {
		jQuery("form:not(.no-auto-focus) :input:not(.no-auto-focus):not(:checkbox):not(:button):not(:submit):visible:enabled:first").focus();
	}
);
