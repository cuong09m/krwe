<?php

/*
Copyright 2010 iThemes (email: support@ithemes.com)

Written by Chris Jean
Version 1.1.1

Version History
	1.0.0 - 2009-07-14
		Release-ready
	1.0.1 - 2009-12-03
		Made code more robust by adding ITUtility check
	1.0.2 - 2010-06-25
		Replaced all require_once calls with it_classes_load
	1.1.0 - 2010-10-05
		Updated to use ITStorage2 for more efficient storage without autoloading
		Changed output message to go outside the content to make it easier to see the full message
		Added debug_backtrace data
	1.1.1 - 2010-12-14
		Removed logging in order to reduce database consumption
*/


if ( ! class_exists( 'ITError' ) ) {
	it_classes_load( 'it-utility.php' );
	it_classes_load( 'it-storage.php' );
	
	class ITError {
		function fatal( $code, $message ) {
			$debug_data = debug_backtrace( false );
			
?>
	<script>
		function show_it_fatal_error_message() {
			if('block' === document.getElementById('it-fatal-error-message').style.display) {
				document.getElementById('it-fatal-error-message').style.display = 'none';
				document.getElementById('it-fatal-error-message-link').innerHTML = 'show';
			}
			else {
				document.getElementById('it-fatal-error-message').style.display = 'block';
				document.getElementById('it-fatal-error-message-link').innerHTML = 'hide';
			}
		}
	</script>
	<div style="background:white;color:black;border:3px double red;padding:10px;margin:10px;font-size:12px;position:absolute;top:0;left:0;z-index:100000;">
		<div><span style="color:red;font-weight:bold;font-size:20px;margin-right:10px;">Fatal Error</span> (<a id="it-fatal-error-message-link" style="color:blue;text-decoration:underline;" href="javascript:show_it_fatal_error_message()">show</a>)</div>
		
		<div id="it-fatal-error-message" style="display:none;">
			<br />
			
			<div>The theme has encountered a problem that it cannot recover from. Please use the following information to try to resolve the problem.</div>
			<br />
			
			<table style="font-size:12px;padding:0;margin:0;">
				<tr><td style="font-weight:bold;">Error Code:</td>
					<td><?php echo $code; ?></td>
				</tr>
				<tr><td style="font-weight:bold;">Message:</td>
					<td><?php echo $message; ?></td>
				</tr>
			</table>
			<br />
			
			<div>If you are unable to fix this problem, please copy all the text on this screen and send it to <a href="mailto:support@ithemes.com">support@ithemes.com</a>.</div>
			
			<?php if ( ! empty( $debug_data ) ) : ?>
				<br />
				
				<div><strong>Debug Data:</strong></div>
				<table style="font-size:10px;padding:0;margin:0;">
					<tr><td><pre style="margin:0;padding:0;"><?php print_r( $debug_data ); ?></pre></td></tr>
				</table>
			<?php endif; ?>
		</div>
	</div>
<?php
			
//			ITError::log( $code, $message, 'fatal' );
			
			exit;
		}
		
		function admin_warn( $code, $message ) {
			if ( current_user_can( 'switch_themes' ) || ! empty( $_GET['show_it_error_messages'] ) )
				ITUtility::show_error_message( $message );
			
//			ITError::log( $code, $message, 'warning' );
		}
		
		function warn( $code, $message ) {
			ITUtility::show_error_message( $message );
			
//			ITError::log( $code, $message, 'warning' );
		}
		
		function log( $code, $message, $type = 'warning' ) {
			if ( ! preg_match( '/^(warning|error|fatal)$/', $type ) )
				$type = 'warning';
			
			$store =& new ITStorage2( 'it-error', array( 'version' => '1.0.0', 'autoload' => 'no' ) );
			
			$data = $store->load();
			
			$log = ITError::get_debug_data( $code );
			$log['message'] = $message;
			
			$data[] = $log;
			
			$store->save( $data );
		}
		
		function get_debug_data( $code ) {
			$data = array();
			
			// Implement this at some time... Add info about timestamp, theme, files, permissions, debug_backtrace, etc
			
			return $data;
		}
		
		function handle_error( $error_code, $error_message, $source_file, $source_line ) {
			if ( ( $error_code & ( E_ERROR | E_PARSE | E_CORE_ERROR | E_COMPILE_ERROR | E_USER_ERROR ) ) > 0 )
				ITError::fatal( "php_code_error:$error_code:$source_file:$source_line:$error_message", 'A fatal code error occurred.' );
		}
		
		function upgrade_storage( $data ) {
			if ( version_compare( $data['storage_version'], '1.0.0', '<' ) )
				$data = ITError::_upgrade_storage_1_0_0( $data );
			
			return $data;
		}
		
		function _upgrade_storage_1_0_0( $data ) {
			$store =& new ITStorage( 'ITError', true );
			$data = $store->load( false );
			$store->remove();
			
			$data['storage_version'] = '1.0.0';
			
			return $data;
		}
	}
	
	add_filter( 'it_storage_upgrade_it-error', array( 'ITError', 'upgrade_storage' ) );
	
	function it_error_shutdown() {
		$error = error_get_last();
		
		if ( is_array( $error ) )
			ITError::handle_error( $error['type'], $error['message'], $error['file'], $error['line'] );
	}
	
	
	if ( function_exists( 'error_get_last' ) )
		register_shutdown_function( 'it_error_shutdown' );
}
