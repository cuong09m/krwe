<?php

/*
Copyright 2010 iThemes (email: support@ithemes.com)

Written by Chris Jean
Version 1.1.7

Version History
	1.0.1 - 2009-07-14
		Release-ready
	1.0.2 - 2009-09-24
		Updated require_once calls to rely on local files
	1.0.3 - 2009-10-06
		Added register_widgets call
	1.0.4 - 2009-10-27
		Added _parent_page_var check to add to existing menu
	1.0.5 - 2009-10-27
		Updated CSS request to not use theme-specific locations
	1.0.6 - 2009-11-17
		Fixed JS and CSS linking directories
	1.0.7 - 2009-11-18
		Added a couple of array index checks to prevent warnings
	1.1.0 - 2009-12-08
		Added storage versioning
	1.1.1 - 2009-12-11
		Changed init action to a priority of 0
	1.1.2 - 2010-01-13
		Aded _default_menu_function with a default of add_theme_page
	1.1.3 - 2010-03-03
		Removed function_exists check from current_user_can
	1.1.4 - 2010-04-15
		Added _suppress_errors variable to pass to ITStorage
	1.1.5 - 2010-06-25
		Added _menu_priority variable
		Replaced all require_once calls with it_classes_load
	1.1.6 - 2010-09-02
		Changed edit_themes to switch_themes for better multisite compatibility.
	1.1.7 - 2010-12-14
		Added support for both ITStorage and ITStorage2
		Added Screen Settings function
		Added active_init function call
*/


if ( ! class_exists( 'ITCoreClass' ) ) {
	it_classes_load( 'it-error.php' );
	it_classes_load( 'it-utility.php' );
	it_classes_load( 'it-storage.php' );
	it_classes_load( 'it-form.php' );
	
	class ITCoreClass {
		var $_var = 'class_var_name';
		var $_page_title = 'Page Title';
		var $_page_var = 'class-page-var';
		var $_menu_title = 'Menu Title';
		var $_default_menu_function = 'add_theme_page';
		var $_access_level = 'switch_themes';
		var $_menu_priority = '10';
		var $_storage_version = '0';
		var $_it_storage_version = '';
		
		var $_page_ref = '';
		
		var $_storage;
		var $_global_storage = false;
		var $_suppress_errors = false;
		var $_options = array();
		
		var $_file;
		var $_class;
		var $_self_link;
		var $_plugin_path;
		var $_plugin_relative_path;
		var $_plugin_url;
		
		
		function ITCoreClass() {
			if ( '2' != $this->_it_storage_version )
				$this->_storage =& new ITStorage( $this->_var, $this->_global_storage, $this->_storage_version, $this->_suppress_errors );
			else
				$this->_storage =& new ITStorage2( $this->_var, $this->_storage_version );
			
			add_action( 'init', array( &$this, 'init' ), 0, 0 );
			add_action( 'admin_menu', array( &$this, 'add_admin_pages' ), $this->_menu_priority );
			add_action( 'widgets_init', array( &$this, 'register_widgets' ) );
			add_action( "it_storage_do_upgrade_{$this->_var}", array( &$this, 'add_storage_upgrade_handler' ) );
		}
		
		function init() {
			$this->_load();
			$this->_set_vars();
			
			if ( isset( $_REQUEST['page'] ) && ( $_REQUEST['page'] === $this->_page_var ) ) {
				add_filter( 'contextual_help', array( &$this, 'contextual_help' ), 10, 2 );
				add_filter( 'screen_settings', array( &$this, 'screen_settings' ), 10, 2 );
				
				if ( ! empty( $_REQUEST['it_ajax_handler'] ) )
					$this->_ajax_handler();
				else if ( ! empty( $_REQUEST['render_clean'] ) )
					add_action( 'admin_init', array( &$this, 'render_clean' ) );
				
				$this->active_init();
			}
		}
		
		function active_init() {
			// This function runs when the page is active
		}
		
		function add_admin_pages() {
			$theme_menu_var = apply_filters( 'it_filter_theme_menu_var', '' );
			$default_menu_function = $this->_default_menu_function;
			
			if ( ! empty( $this->_parent_page_var ) )
				$this->_page_ref = add_submenu_page( $this->_parent_page_var, $this->_page_title, $this->_menu_title, $this->_access_level, $this->_page_var, array( &$this, 'index' ) );
			else if ( ! empty( $theme_menu_var ) )
				$this->_page_ref = add_submenu_page( $theme_menu_var, $this->_page_title, $this->_menu_title, $this->_access_level, $this->_page_var, array( &$this, 'index' ) );
			else
				$this->_page_ref = $default_menu_function( $this->_page_title, $this->_menu_title, $this->_access_level, $this->_page_var, array( &$this, 'index' ) );
			
			add_action( "admin_print_scripts-{$this->_page_ref}", array( $this, 'add_admin_scripts' ) );
			add_action( "admin_print_styles-{$this->_page_ref}", array( $this, 'add_admin_styles' ) );
		}
		
		function add_admin_scripts() {
			wp_enqueue_script( 'jquery' );
			wp_enqueue_script( 'thickbox' );
			
			wp_enqueue_script( 'it-core-class-script', "{$this->_class_url}/js/it-core-class.js" );
		}
		
		function add_admin_styles() {
			wp_enqueue_style( 'thickbox' );
			
			wp_enqueue_style( 'it-classes-style', "{$this->_class_url}/css/classes.css" );
		}
		
		function add_storage_upgrade_handler() {
			if ( file_exists( dirname( $this->_file ) . '/upgrade-storage.php' ) )
				ITUtility::require_file_once( dirname( $this->_file ) . '/upgrade-storage.php' );
		}
		
		function _set_vars() {
			$this->_class = get_class( $this );
			
			if ( isset( $_REQUEST['page'] ) )
				$this->_self_link = array_shift( explode( '?', $_SERVER['REQUEST_URI'] ) ) . '?page=' . $_REQUEST['page'];
			
			if ( empty( $this->_file ) || ! is_file( $this->_file ) )
				ITError::fatal( "empty_var:class_var:{$this->_class}->_file", "The $this->_class class did not fill in the \$this->_file variable. This should be done in the class constructor with a value of __FILE__." );
			
			$this->_plugin_path = dirname( $this->_file );
			$this->_plugin_relative_path = ltrim( str_replace( '\\', '/', str_replace( rtrim( ABSPATH, '\\\/' ), '', $this->_plugin_path ) ), '\\\/' );
			$this->_plugin_url = get_option( 'siteurl' ) . '/' . $this->_plugin_relative_path;
			
			$this->_class_path = dirname( __FILE__ );
			$this->_class_relative_path = ltrim( str_replace( '\\', '/', str_replace( rtrim( ABSPATH, '\\\/' ), '', $this->_class_path ) ), '\\\/' );
			$this->_class_url = get_option( 'siteurl' ) . '/' . $this->_class_relative_path;
		}
		
		function register_widgets() {
/*			ITUtility::require_file_once( dirname( __FILE__ ) . '/widget.php' );
			
			register_widget( 'PluginWidget' );*/
		}
		
		
		// Options Storage ////////////////////////////
		
		function _save() {
			$this->_storage->save( $this->_options );
		}
		
		function _load() {
			if ( ! isset( $this->_storage ) || ! is_callable( array( $this->_storage, 'load' ) ) )
				ITError::fatal( "empty_var:class_var:{$this->_class}->_storage", "The $this->_class class did not set the \$this->_storage variable. This should be set by the ITCoreClass class, ensure that the ITCoreClass::ITCoreClass() method is called." );
			
			$this->_options = $this->_storage->load();
		}
		
		
		// Rendering //////////////////////////////////
		
		function contextual_help( $text, $screen ) {
			return $text;
		}
		
		function screen_settings( $settings, $screen ) {
			return $settings;
		}
		
		function render_clean() {
			$this->add_admin_scripts();
			$this->add_admin_styles();
			
			it_classes_load( 'it-thickbox.php' );
			
			ITThickbox::render_thickbox( array( &$this, 'index' ) );
			
			exit;
		}
		
		function index() {
			if ( ! current_user_can( $this->_access_level ) )
				die( __( 'Cheatin&#8217; uh?' ) );
			if ( ! empty( $_REQUEST['_wpnonce'] ) )
				ITForm::check_nonce( ( ! empty( $this->_nonce ) ) ? $this->_nonce : null );
			
			ITUtility::cleanup_request_vars();
		}
		
		
		// Ajax Handlers //////////////////////////////
		
		function _ajax_handler() {
			if ( is_admin() )
				$this->_ajax_index();
			
			exit;
		}
		
		function _ajax_index() {
			// This function should be overridden to provide AJAX functionality.
		}
	}
}
