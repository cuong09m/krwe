<?php

/*
Copyright 2010 iThemes (email: support@ithemes.com)

Written by Chris Jean
Version 1.0.0

Version History
	1.0.0 - 2010-06-29
		Release-ready
*/


if ( ! function_exists( 'it_classes_load' ) ) {
	function it_classes_load( $file ) {
		require_once( dirname( __FILE__ ) . "/$file" );
	}
}

it_classes_load( 'it-core-class.php' );

?>
