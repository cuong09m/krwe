<?php

/*
Copyright 2010 iThemes (email: support@ithemes.com)

Written by Chris Jean
Version 1.5.3

Version History
	1.2.0 - 2010-06-29
		Release-ready
		Version should be kept in sync with repo version
	1.3.0 - 2010-07-27
		Added it-post-type.php and it-taxonomy.php
	1.4.0 - 2010-10-05
		Updated to release version 1.4.0
	1.5.0 - 2010-12-14
		Updated to release version 1.5.0
	1.5.3 - 2010-12-20
		Release version 1.5.3
*/


$it_registration_list_version		= '1.5.3';
$it_registration_list_library		= 'classes';
$it_registration_list_init_file		= dirname( __FILE__ ) . '/init.php';

$GLOBALS['it_classes_registration_list'][$it_registration_list_library][$it_registration_list_version] = $it_registration_list_init_file;

if ( ! function_exists( 'it_registration_list_init' ) ) {
	function it_registration_list_init() {
		// The $wp_locale variable is set just before the theme's functions.php file is loaded,
		// this acts as a good check to ensure that both the plugins and the theme have loaded.
		global $wp_locale;
		if ( ! isset( $wp_locale ) )
			return;
		
		
		$init_files = array();
		
		foreach ( (array) $GLOBALS['it_classes_registration_list'] as $library => $versions ) {
			$max_version = '-10000';
			$init_file = '';
			
			foreach ( (array) $versions as $version => $file ) {
				if ( version_compare( $version, $max_version, '>' ) ) {
					$max_version = $version;
					$init_file = $file;
				}
			}
			
			if ( ! empty( $init_file ) )
				$init_files[] = $init_file;
		}
		
		unset( $GLOBALS['it_classes_registration_list'] );
		
		foreach ( (array) $init_files as $init_file )
			require_once( $init_file );
		
		do_action( 'it_libraries_loaded' );
	}
	
	global $wp_version;
	
	if ( version_compare( $wp_version, '2.9.7', '>' ) )
		add_action( 'after_setup_theme', 'it_registration_list_init' );
	else
		add_action( 'set_current_user', 'it_registration_list_init' );
}

?>
