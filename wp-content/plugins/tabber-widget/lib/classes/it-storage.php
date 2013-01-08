<?php

/*
Copyright 2010 iThemes (email: support@ithemes.com)

Written by Chris Jean
Version 1.2.1

Version History
	1.0.0 - 2009-07-14
		Release-ready
	1.0.1 - 2009-07-18
		Added global check before theme_index error
	1.0.2 - 2009-11-23
		Fixed index warning in load function
	1.0.3 - 2009-11-23
		Improved reset capability
	1.1.0 - 2009-12-08
		Added storage versioning
	1.1.1 - 2010-04-15
		Added suppress_errors option to allow code to not fail when theme
			or global vars are not properly loaded
	1.1.2 - 2010-09-20
		Added caching system using a global variable. This saves on memory.
	1.2.0 - 2010-10-05
		Added ITStorage2 which adds ability to turn off autoloading uses separate storage locations
		Over time, all code should migrate to ITStorage2
	1.2.1 - 2010-12-14
		Added loaded value caching
		Moved load filter above upgrade check to properly upgrade all existing data
To-Do -
	Add export/import routines for all storage and theme-specific
*/


if ( ! class_exists( 'ITStorage' ) ) {
	class ITStorage {
		var $_base_option = 'it-storage';
		var $_global_index = 'global_data';
		var $_theme_index = '';
		
		var $_var = '';
		var $_global = false;
		var $_storage_version = '';
		var $_suppress_errors = false;
		
		var $_data = false;
		
		
		function ITStorage( $var, $global = false, $storage_version = '0', $suppress_errors = false ) {
			$this->_var = $var;
			$this->_global = $global;
			$this->_storage_version = $storage_version;
			$this->_suppress_errors = $suppress_errors;
			
			if ( empty( $this->_var ) )
				ITError::fatal( 'empty_parameter:0', 'Unable to store or retrieve data due to empty first parameter passed to ITStorage.' );
			
			add_action( 'init', array( &$this, 'filter_vars' ), 0, -10 );
			add_action( 'it_storage_reset', array( &$this, 'reset' ) );
			add_action( 'it_storage_remove', array( &$this, 'remove' ) );
			
			add_action( "it_storage_clear_cache_{$this->_var}", array( &$this, 'clear_cache' ) );
			add_action( "it_storage_save_{$this->_var}", array( &$this, 'save' ) );
			add_action( "it_storage_reset_{$this->_var}", array( &$this, 'reset' ) );
			add_action( "it_storage_remove_{$this->_var}", array( &$this, 'remove' ) );
			
			add_filter( "it_storage_load_{$this->_var}", array( &$this, 'load' ) );
		}
		
		function _remove_filters() {
			remove_action( 'it_storage_reset', array( &$this, 'reset' ) );
			remove_action( 'it_storage_remove', array( &$this, 'remove' ) );
			
			remove_action( "it_storage_clear_cache_{$this->_var}", array( &$this, 'clear_cache' ) );
			remove_action( "it_storage_save_{$this->_var}", array( &$this, 'save' ) );
			remove_action( "it_storage_reset_{$this->_var}", array( &$this, 'reset' ) );
			remove_action( "it_storage_remove_{$this->_var}", array( &$this, 'remove' ) );
			
			remove_filter( "it_storage_load_{$this->_var}", array( &$this, 'load' ) );
		}
		
		function clear_cache() {
			$this->_data = false;
			unset( $GLOBALS["it_storage_cache_{$this->_var}"] );
		}
		
		function load( $merge_defaults = true ) {
			if ( isset( $GLOBALS["it_storage_cache_{$this->_var}"] ) )
				return $GLOBALS["it_storage_cache_{$this->_var}"];
			
			$options = @get_option( $this->_base_option );
			
			$data = array();
			
			
			if ( true === $this->_global ) {
				if ( isset( $options[$this->_global_index][$this->_var] ) )
					$data = $options[$this->_global_index][$this->_var];
			}
			else {
				if ( isset( $options['themes'][$this->_theme_index][$this->_var] ) )
					$data = $options['themes'][$this->_theme_index][$this->_var];
			}
			
			$original_data = $data;
			
			if ( true === $merge_defaults ) {
				$defaults = apply_filters( "it_storage_get_defaults_{$this->_var}", array() );
				if ( ! is_array( $defaults ) )
					$defaults = array();
				
				$data = ITUtility::merge_defaults( $data, $defaults );
			}
			
			
			if ( ! isset( $data['storage_version'] ) )
				$data['storage_version'] = '0';
			
			$data = apply_filters( "it_storage_filter_load_{$this->_var}", $data );
			
			if ( version_compare( $this->_storage_version, $data['storage_version'], '>' ) ) {
				do_action( "it_storage_do_upgrade_{$this->_var}" );
				
				$upgrade_data = apply_filters( "it_storage_upgrade_{$this->_var}", array( 'data' => $data, 'current_version' => $this->_storage_version ) );
				
				$data = $upgrade_data['data'];
			}
			
			
			$this->_data = $data;
			$GLOBALS["it_storage_cache_{$this->_var}"] = $data;
			
			
			if ( $original_data !== $data )
				$this->save( $data );
			
			return $data;
		}
		
		function save( $data ) {
			$data = apply_filters( 'it_storage_filter_save_{$this->_var}', $data );
			
			$options = @get_option( $this->_base_option );
			
			if ( true === $this->_global )
				$options[$this->_global_index][$this->_var] = $data;
			else
				$options['themes'][$this->_theme_index][$this->_var] = $data;
			
			$this->_data = $data;
			$GLOBALS["it_storage_cache_{$this->_var}"] = $data;
			
			return @update_option( $this->_base_option, $options );
		}
		
		// Removes the existing data and saves the default data
		function reset() {
			$this->remove();
		}
		
		// Removes this storage location from the data
		function remove() {
			$options = @get_option( $this->_base_option );
			
			if ( true === $this->_global )
				unset( $options[$this->_global_index][$this->_var] );
			else if ( ! empty( $options['themes'][$this->_theme_index] ) && is_array( $options['themes'][$this->_theme_index] ) )
				unset( $options['themes'][$this->_theme_index][$this->_var] );
			
			@update_option( $this->_base_option, $options );
			
			$this->clear_cache();
			$this->load();
		}
		
		
		function filter_vars() {
			$this->_base_option = apply_filters( 'it_storage_filter_base_option', $this->_base_option );
			$this->_base_option = apply_filters( "it_storage_filter_base_option_{$this->_var}", $this->_base_option );
			if ( empty( $this->_base_option ) ) {
				if ( false === $this->_suppress_errors )
					ITError::fatal( 'empty_var:filter_result:it_storage_filter_base_option', 'Unable to store or retrieve data due to missing base option.' );
				
				$this->_remove_filters();
			}
			
			$this->_global_index = apply_filters( 'it_storage_filter_global_index', $this->_global_index );
			$this->_global_index = apply_filters( "it_storage_filter_global_index_{$this->_var}", $this->_global_index );
			if ( empty( $this->_global_index ) ) {
				if ( false === $this->_suppress_errors )
					ITError::fatal( 'empty_var:filter_result:it_storage_filter_global_index', 'Unable to store or retrieve data due to missing global index.' );
				
				$this->_remove_filters();
			}
			
			$this->_theme_index = apply_filters( 'it_storage_filter_theme_index', '' );
			if ( empty( $this->_theme_index ) && ( true !== $this->_global ) ) {
				if ( false === $this->_suppress_errors )
					ITError::fatal( 'empty_var:filter_result:it_storage_filter_theme_index', 'Unable to store or retrieve data due to missing theme index.' . "<br />Var: {$this->_var}" );
				
				$this->_remove_filters();
			}
		}
	}
}


if ( ! class_exists( 'ITStorage2' ) ) {
	class ITStorage2 {
		var $_var = '';
		var $_storage_version = '';
		var $_autoload = true;
		
		var $_data = false;
		
		
		function ITStorage2( $var, $args = array() ) {
			if ( is_string( $args ) )
				$args = array( 'version' => $args );
			
			$default_args = array(
				'version'		=> 0,
				'autoload'		=> true,
			);
			$args = wp_parse_args( $args, $default_args );
			
			$this->_var = $var;
			$this->_option_name = "it-storage-$var";
			$this->_storage_version = $args['version'];
			$this->_autoload = ( ! $args['autoload'] || ( 'no' === $args['autoload'] ) ) ? 'no' : 'yes';
			
			if ( empty( $this->_var ) )
				ITError::fatal( 'empty_parameter:0', 'Unable to store or retrieve data due to empty first parameter passed to ITStorage2.' );
			
			add_action( 'it_storage_reset', array( &$this, 'reset' ) );
			add_action( 'it_storage_remove', array( &$this, 'remove' ) );
			
			add_action( "it_storage_clear_cache_{$this->_var}", array( &$this, 'clear_cache' ) );
			add_action( "it_storage_save_{$this->_var}", array( &$this, 'save' ) );
			add_action( "it_storage_reset_{$this->_var}", array( &$this, 'reset' ) );
			add_action( "it_storage_remove_{$this->_var}", array( &$this, 'remove' ) );
			
			add_filter( "it_storage_load_{$this->_var}", array( &$this, 'load' ) );
		}
		
		function _remove_filters() {
			remove_action( 'it_storage_reset', array( &$this, 'reset' ) );
			remove_action( 'it_storage_remove', array( &$this, 'remove' ) );
			
			remove_action( "it_storage_clear_cache_{$this->_var}", array( &$this, 'clear_cache' ) );
			remove_action( "it_storage_save_{$this->_var}", array( &$this, 'save' ) );
			remove_action( "it_storage_reset_{$this->_var}", array( &$this, 'reset' ) );
			remove_action( "it_storage_remove_{$this->_var}", array( &$this, 'remove' ) );
			
			remove_filter( "it_storage_load_{$this->_var}", array( &$this, 'load' ) );
		}
		
		function load( $merge_defaults = true ) {
			if ( isset( $GLOBALS["it_storage_cache_{$this->_var}"] ) )
				return $GLOBALS["it_storage_cache_{$this->_var}"];
			
			$data = $original_data = @get_option( $this->_option_name );
			
			if ( true === $merge_defaults ) {
				$defaults = apply_filters( "it_storage_get_defaults_{$this->_var}", array() );
				if ( ! is_array( $defaults ) )
					$defaults = array();
				
				$data = ITUtility::merge_defaults( $data, $defaults );
			}
			
			
			if ( ! isset( $data['storage_version'] ) ) {
				$data['storage_version'] = '0';
				
//				$data = $this->_migrate_old_data( $data );
			}
			
			$data = apply_filters( "it_storage_filter_load_{$this->_var}", $data );
			
			if ( version_compare( $this->_storage_version, $data['storage_version'], '>' ) ) {
				do_action( "it_storage_do_upgrade_{$this->_var}" );
				
				$upgrade_data = apply_filters( "it_storage_upgrade_{$this->_var}", array( 'data' => $data, 'current_version' => $this->_storage_version ) );
				
				$data = $upgrade_data['data'];
			}
			
			
			$this->_data = $data;
			$GLOBALS["it_storage_cache_{$this->_var}"] = $data;
			
			
			if ( $original_data !== $data )
				$this->save( $data );
			
			return $data;
		}
		
		function save( $data ) {
			$data = apply_filters( "it_storage_filter_save_{$this->_var}", $data );
			
			$old_data = @get_option( $this->_option_name );
			
			if ( $data === $old_data )
				return true;
			
			if ( false === $old_data )
				return @add_option( $this->_option_name, $data, '', $this->_autoload );
			return @update_option( $this->_option_name, $data );
		}
		
/*		function migrate_old_data( $var, $global = false, $storage_version = '0', $suppress_errors = false ) {
			$store =& new ITStorage( $var, $global, $storage_version, $suppress_errors );
			$data = $store->load( false );
			$store->remove();
			
			unset( $store );
			
			return $data;
		}*/
		
		function clear_cache() {
			$this->_data = false;
			
			unset( $GLOBALS["it_storage_cache_{$this->_var}"] );
		}
		
		// Removes the existing data and saves the default data
		function reset() {
			$this->remove();
		}
		
		// Removes this storage location from the data
		function remove() {
			@delete_option( $this->_option_name );
			
			$this->clear_cache();
			$this->load();
		}
	}
}
