<?php

/*
Plugin Name: Tabber Widget
Plugin URI: http://ithemes.com/
Description: Provides a simple-to-use editor to create tabbed widgets
Version: 1.0.6
Author: iThemes
Author URI: http://ithemes.com/
*/


require_once( dirname( __FILE__ ) . '/lib/classes/load.php' );

function it_tabber_widget_init() {
	require_once( dirname( __FILE__ ) . '/editor.php' );
}
add_action( 'it_libraries_loaded', 'it_tabber_widget_init', 20 );


function it_tabber_widget_register_widgets() {
	require_once( dirname( __FILE__ ) . '/widget.php' );
	
	register_widget( 'ITTabberWidget' );
}
add_action( 'widgets_init', 'it_tabber_widget_register_widgets' );


?>
