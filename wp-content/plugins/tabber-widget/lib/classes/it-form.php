<?php

/*
Copyright 2010 iThemes (email: support@ithemes.com)

Written by Chris Jean
Version 1.0.14

Version History
	1.0.0 - 2009-07-14
		Release-ready
	1.0.1 - 2009-09-11
		Added parse_values function
		Added get_ functions
		Removed $widget args for add_ functions
		Added defaults arg
	1.0.2 - 2009-10-06
		Updated to handle widget forms/data
		Fixed bug in checkbox handling
	1.0.3 - 2009-11-11
		Improved nonce support by forcing a non-null nonce name
	1.0.4 - 2009-11-11
		Reverted 1.0.3 change until a better solution can be created
	1.0.5 - 2009-11-11
		Fixed coding bug
	1.0.6 - 2009-11-12
		Removed unnecessary [] from id and class on multi checkboxes
	1.0.7 - 2009-11-18
		Fixed index warnings
	1.0.8 - 2009-12-08
		Fixed misc warnings
	1.0.9 - 2010-03-03
		Now uses esc_html rather than wp_specialchars when esc_html is
			available. This is due to wp_specialchars being deprecated.
	1.0.10 - 2010-04-13
		Fixed html-encoded string parsing problems by filtering text values through htmlentities
	1.0.11 - 2010-04-13
		Applied better fix for html-encoded strings parsing
	1.0.12 - 2010-06-25
		Replaced all require_once calls with it_classes_load
	1.0.13 - 2010-12-14
		Removed buggy dropdown type attribute
		Fixed radio selected value code
		Fixed input names for array values
	1.0.14 - 2010-12-20
		Fixed get_post_data()'s handling of prefixes

Notes:
	Need to fix $this->_var support or handle used_inputs better
*/


if ( ! class_exists( 'ITForm' ) ) {
	it_classes_load( 'it-utility.php' );
	
	class ITForm {
		var $_used_inputs = array();
		var $_options = array();
		var $_args = array();
		var $_var = '';
		var $_used_inputs_printed = false;
		
		
		function ITForm( $options = array(), $args = array() ) {
			$new_options = array();
			
			foreach ( (array) $options as $var => $val ) {
				if ( is_array( $val ) )
					continue;
				
				$new_options[$var] = $val;
			}
			
			if ( ! empty( $args['defaults'] ) && is_array( $args['defaults'] ) ) {
				foreach ( (array) $args['defaults'] as $var => $val )
					$new_options["default_option_$var"] = $val;
			}
			
			$options = $new_options;
			
			$this->_args =& $args;
			$this->_options =& $options;
		}
		
		
		function get_post_data() {
			$data = array();
			
			if ( ! empty( $_POST['used-inputs'] ) ) {
				foreach ( (array) explode( ',', $_POST['used-inputs'] ) as $var ) {
					$clean_var = preg_replace( '/\[\]$/', '', $var );
					
					if ( isset( $_POST[$clean_var] ) )
						$val = $_POST[$clean_var];
					else if ( $var !== $clean_var )
						$val = array();
					else
						$val = '';
					
					if ( ! empty( $_POST['__it-form-prefix'] ) )
						$clean_var = preg_replace( "|^{$_POST['__it-form-prefix']}-|", '', $clean_var );
					
					$data[$clean_var] = $val;
				}
			}
			else {
				$skip = array( '_wpnonce', '_wp_http_referer', 'used-inputs', '__it-form-prefix' );
				
				foreach ( (array) $_POST as $var => $val ) {
					if ( in_array( $var, $skip ) )
						continue;
					
					$data[$var] = $val;
				}
			}
			
			return $data;
		}
		
		function parse_values( $values = array(), $args = array() ) {
			$new_values = array();
			
			foreach ( (array) $values as $var => $val ) {
				if ( ! empty( $args['prefix'] ) ) {
					if ( preg_match( "/^{$args['prefix']}-(.+)/", $var, $matches ) )
						$var = $matches[1];
					else
						continue;
				}
				
				$new_values[$var] = $val;
			}
			
			return $new_values;
		}
		
		
		function start_form( $options = array(), $nonce_var = null ) {
			$defaults = array(
				'id'		=> 'posts-filter',
				'enctype'	=> 'multipart/form-data',
				'method'	=> 'post',
				'action'	=> array_shift( explode( '?', $_SERVER['REQUEST_URI'] ) ) . '?page=' . $_REQUEST['page']
			);
			
			$options = array_merge( $defaults, $options );
			
			echo '<form';
			
			foreach ( (array) $options as $var => $val ) {
				if ( ! is_array( $val ) ) {
					$val = str_replace( '"', '&quot;', $val );
					echo " $var=\"$val\"";
				}
			}
			
			echo ">\n";
			
			if ( false !== $nonce_var )
				ITForm::add_nonce( $nonce_var );
		}
		
		function end_form() {
			if ( ! empty( $_REQUEST['render_clean'] ) )
				$this->add_hidden_no_save( 'render_clean', '1', true );
			if ( ! empty( $this->_args['prefix'] ) )
				$this->add_hidden_no_save( '__it-form-prefix', $this->_args['prefix'], true );
			if ( false === $this->_used_inputs_printed )
				$this->add_used_inputs();
			
			echo "</form>\n";
		}
		
		function add_nonce( $name = null ) {
			wp_nonce_field( $name );
		}
		
		function check_nonce( $name = null ) {
			check_admin_referer( $name );
		}
		
		function new_form() {
			$this->_used_inputs = array();
			$this->_used_inputs_printed = false;
		}
		
		function get_submit( $var, $options = array(), $override_value = true ) {
			if ( ! is_array( $options ) )
				$options = array( 'value' => $options );
			
			$options['type'] = 'submit';
			$options['name'] = $var;
			$options['class'] = ( empty( $options['class'] ) ) ? 'button-primary' : $options['class'];
			
			return $this->_get_simple_input( $var, $options, $override_value );
		}
		
		function add_submit( $var, $options = array(), $override_value = true ) {
			echo $this->get_submit( $var, $options, $override_value );
		}
		
		function get_button( $var, $options = array(), $override_value = true ) {
			if ( ! is_array( $options ) )
				$options = array( 'value' => $options );
			
			$options['type'] = 'button';
			$options['name'] = $var;
			
			return $this->_get_simple_input( $var, $options, $override_value );
		}
		
		function add_button( $var, $options = array(), $override_value = true ) {
			echo $this->get_button( $var, $options, $override_value );
		}
		
		function get_close_thickbox_button( $var, $options = array(), $override_value = true ) {
			if ( ! is_array( $options ) )
				$options = array( 'value' => $options );
			
			$options['type'] = 'button';
			$options['name'] = $var;
			$options['onclick'] = 'close_thickbox();';
			
			return $this->_get_simple_input( $var, $options, $override_value );
		}
		
		function add_close_thickbox_button( $var, $options = array(), $override_value = true ) {
			echo $this->get_close_thickbox_button( $var, $options, $override_value );
		}
		
		function get_text_box( $var, $options = array(), $override_value = false ) {
			if ( ! is_array( $options ) )
				$options = array( 'value' => $options );
			
			$options['type'] = 'text';
			
			return $this->_get_simple_input( $var, $options, $override_value );
		}
		
		function add_text_box( $var, $options = array(), $override_value = false ) {
			echo $this->get_text_box( $var, $options, $override_value );
		}
		
		function get_text_area( $var, $options = array(), $override_value = false ) {
			if ( ! is_array( $options ) )
				$options = array( 'value' => $options );
			
			$options['type'] = 'textarea';
			
			return $this->_get_simple_input( $var, $options, $override_value );
		}
		
		function add_text_area( $var, $options = array(), $override_value = false ) {
			echo $this->get_text_area( $var, $options, $override_value );
		}
		
		function get_file_upload( $var, $options = array(), $override_value = false ) {
			if ( ! is_array( $options ) )
				$options = array( 'value' => $options );
			
			$options['type'] = 'file';
			$options['name'] = $var;
			
			return $this->_get_simple_input( $var, $options, $override_value );
		}
		
		function add_file_upload( $var, $options = array(), $override_value = false ) {
			echo $this->get_file_upload( $var, $options, $override_value );
		}
		
		function get_check_box( $var, $options = array(), $override_value = false ) {
			if ( ! is_array( $options ) )
				$options = array( 'value' => $options );
			
			$options['type'] = 'checkbox';
			
			if ( empty( $options['value'] ) )
				$options['value'] = '1';
			
			return $this->_get_simple_input( $var, $options, $override_value );
		}
		
		function add_check_box( $var, $options = array(), $override_value = false ) {
			echo $this->get_check_box( $var, $options, $override_value );
		}
		
		function get_multi_check_box( $var, $options = array(), $override_value = false ) {
			if ( ! is_array( $options ) )
				$options = array( 'value' => $options );
			
			if ( empty( $options['id'] ) )
				$options['id'] = "$var-{$options['value']}";
			if ( empty( $options['class'] ) )
				$options['class'] = $var;
			
			$options['type'] = 'checkbox';
			$var = $var . '[]';
			
			return $this->_get_simple_input( $var, $options, $override_value );
		}
		
		function add_multi_check_box( $var, $options = array(), $override_value = false ) {
			echo $this->get_multi_check_box( $var, $options, $override_value );
		}
		
		function get_radio( $var, $options = array(), $override_value = false ) {
			if ( ! is_array( $options ) )
				$options = array( 'value' => $options );
			
			$options['type'] = 'radio';
			
			if ( empty( $options['id'] ) )
				$options['id'] = "$var-{$options['value']}";
			
			return $this->_get_simple_input( $var, $options, $override_value );
		}
		
		function add_radio( $var, $options = array(), $override_value = false ) {
			echo $this->get_radio( $var, $options, $override_value );
		}
		
		function get_yes_no_drop_down( $var, $options = array(), $override_value = false ) {
			if ( ! is_array( $options ) )
				$options = array();
			
			$options['value'] = array( '1' => 'Yes', '' => 'No' );
			
			return $this->get_drop_down( $var, $options, $override_value );
		}
		
		function add_yes_no_drop_down( $var, $options = array(), $override_value = false ) {
			echo $this->get_yes_no_drop_down( $var, $options, $override_value );
		}
		
		function get_drop_down( $var, $options = array(), $override_value = false ) {
			if ( ! is_array( $options ) )
				$options = array();
			else if ( ! isset( $options['value'] ) || ! is_array( $options['value'] ) )
				$options = array( 'value' => $options );
			
			$options['type'] = 'dropdown';
			
			return $this->_get_simple_input( $var, $options, $override_value );
		}
		
		function add_drop_down( $var, $options = array(), $override_value = false ) {
			echo $this->get_drop_down( $var, $options, $override_value );
		}
		
		function get_credit_card_month_drop_down( $var, $options = array(), $override_value = false ) {
			$options['value'][''] = 'Month';
			
			for ( $month = 1; $month <= 12; $month++ ) {
				$month = sprintf( '%02d', $month );
				$options['value'][$month] = $month;
			}
			
			return $this->get_drop_down( $var, $options, $override_value );
		}
		
		function add_credit_card_month_drop_down( $var, $options = array(), $override_value = false ) {
			echo $this->get_credit_card_month_drop_down( $var, $options, $override_value );
		}
		
		function get_credit_card_year_drop_down( $var, $options = array(), $override_value = false ) {
			$options['value'][''] = 'Year';
			
			$cur_year = date( 'Y' );
			
			for ( $year = $cur_year; $year <= $cur_year + 10; $year++ )
				$options['value'][$year] = $year;
			
			return $this->get_drop_down( $var, $options, $override_value );
		}
		
		function add_credit_card_year_drop_down( $var, $options = array(), $override_value = false ) {
			echo $this->get_credit_card_year_drop_down( $var, $options, $override_value );
		}
		
		function get_hidden( $var, $options = array(), $override_value = false ) {
			if ( ! is_array( $options ) )
				$options = array( 'value' => $options );
			
			$options['type'] = 'hidden';
			
			return $this->_get_simple_input( $var, $options, $override_value );
		}
		
		function add_hidden( $var, $options = array(), $override_value = false ) {
			echo $this->get_hidden( $var, $options, $override_value );
		}
		
		function get_hidden_no_save( $var, $options = array(), $override_value = true ) {
			if ( ! is_array( $options ) )
				$options = array( 'value' => $options );
			
			$options['name'] = $var;
			
			return $this->get_hidden( $var, $options, $override_value );
		}
		
		function add_hidden_no_save( $var, $options = array(), $override_value = true ) {
			echo $this->get_hidden_no_save( $var, $options, $override_value );
		}
		
		function get_default_hidden( $var ) {
			$options = array();
			$options['value'] = $this->defaults[$var];
			
			$var = "default_option_$var";
			
			return $this->_get_simple_input( $var, $options );
		}
		
		function add_default_hidden( $var ) {
			echo $this->get_default_hidden( $var );
		}
		
		function get_used_inputs() {
			$options['type'] = 'hidden';
			$options['value'] = implode( ',', $this->_used_inputs );
			$options['name'] = 'used-inputs';
			
			$this->_used_inputs_printed = true;
			
			return $this->_get_simple_input( 'used-inputs', $options, true );
		}
		
		function add_used_inputs() {
			echo $this->get_used_inputs();
		}
		
		function _get_simple_input( $var, $options, $override_value ) {
			if ( empty( $options['type'] ) )
				return "<!-- _get_simple_input called without a type option set. -->\n";
			
			
			$scrublist['textarea']['value'] = true;
			$scrublist['file']['value'] = true;
			$scrublist['dropdown']['value'] = true;
			$scrublist['dropdown']['type'] = true;
			
			$defaults = array();
			
			if ( isset( $this->_args['widget_instance'] ) && @method_exists( $this->_args['widget_instance'], 'get_field_name' ) )
				$defaults['name'] = $this->_args['widget_instance']->get_field_name( $var );
			else if ( empty( $this->_args['prefix'] ) )
				$defaults['name'] = $var;
			else
				$defaults['name'] = "{$this->_args['prefix']}-$var";
			
			$var = str_replace( '[]', '', $var );
			
			$clean_var = $var;
			$clean_var = str_replace( '[', '-', $clean_var );
			$clean_var = str_replace( ']', '' , $clean_var );
			
			if ( isset( $this->_args['widget_instance'] ) && @method_exists( $this->_args['widget_instance'], 'get_field_id' ) )
				$defaults['id'] = $this->_args['widget_instance']->get_field_id( $var );
			else
				$defaults['id'] = $clean_var;
			
			$options = ITUtility::merge_defaults( $options, $defaults );
			
			if ( ( false === $override_value ) && isset( $this->_options[$var] ) ) {
				if ( in_array( $options['type'], array( 'checkbox', 'radio' ) ) ) {
					if ( (string) $this->_options[$var] === (string) $options['value'] )
						$options['checked'] = 'checked';
				}
				else if ( 'dropdown' !== $options['type'] )
					$options['value'] = $this->_options[$var];
			}
			
			if ( ( ( ! empty( $this->_args['prefix'] ) && ( preg_match( "|^{$this->_args['prefix']}-|", $options['name'] ) ) ) || ( empty( $this->_args['prefix'] ) ) ) && ( ! in_array( $options['name'], $this->_used_inputs ) ) )
				$this->_used_inputs[] = $options['name'];
			
			
			$attributes = '';
			
			if ( false !== $options ) {
				foreach ( (array) $options as $name => $val ) {
					if ( ! is_array( $val ) && ( ! isset( $scrublist[$options['type']][$name] ) || ( true !== $scrublist[$options['type']][$name] ) ) ) {
						if ( ! in_array( $options['type'], array( 'submit', 'button' ) ) ) {
							if ( function_exists( 'esc_html' ) )
								$val = esc_html( $val );
							else
								$val = wp_specialchars( $val );
						}
						if ( 'text' === $options['type'] )
							$val = htmlspecialchars( htmlspecialchars_decode( htmlspecialchars_decode( $val ) ) );
						
						$attributes .= "$name=\"$val\" ";
					}
				}
			}
			
			$retval = '';
			
			if ( 'textarea' === $options['type'] ) {
				if ( ! isset( $options['value'] ) )
					$options['value'] = '';
				
				$retval = '<textarea ' . $attributes . '>' . $options['value'] . '</textarea>';
			}
			else if ( 'dropdown' === $options['type'] ) {
				$retval = "<select $attributes>\n";
				
				if ( isset( $options['value'] ) && is_array( $options['value'] ) ) {
					foreach ( (array) $options['value'] as $val => $name ) {
						$selected = ( isset( $this->_options[$var] ) && ( (string) $this->_options[$var] === (string) $val ) ) ? ' selected="selected"' : '';
						$retval .= "<option value=\"$val\"$selected>$name</option>\n";
					}
				}
				
				$retval .= "</select>\n";
			}
			else
				$retval = '<input ' . $attributes . '/>';
			
			return $retval;
		}
	}
}

?>
