<?php

/*
Copyright 2010 iThemes (email: support@ithemes.com)

Written by Chris Jean
Version 1.0.1

Version History
	1.0.0 - 2009-08-14
		Release-ready
	1.0.1 - 2009-11-18
		Fixed array index warnings
*/


if ( ! class_exists( 'ITArraySort' ) ) {
	class ITArraySort {
		var $_array;
		var $_key;
		var $_sort_direction;
		var $_case_sensitive;
		
		function ITArraySort( $array, $key, $sort_direction = 'asc', $sort_type = 'string', $case_sensitive = false ) {
			$this->_array = $array;
			$this->_key = $key;
			$this->_sort_direction = $sort_direction;
			$this->_sort_type = $sort_type;
			$this->_case_sensitive = $case_sensitive;
			
			if ( ! preg_match( '/^\[.*\]$/', $this->_key ) )
				$this->_key = "[{$this->_key}]";
		}
		
		function get_sorted_array() {
			if ( ! is_array( $this->_array ) ) {
				error_log( 'ITArraySort received a non-array array parameter' );
				return $this->_array;
			}
			
			$key = key( $this->_array );
			$pass = @eval( 'return isset( $this->_array[$key]' . $this->_key . ' );' );
			
			if ( true !== $pass ) {
				error_log( "ITArraySort was unable to find the following key in the provided array: {$this->_key}");
				return $this->_array;
			}
			
			uksort( $this->_array, array( &$this, '_sorter' ) );
			
			return $this->_array;
		}
		
		function _sorter( $a, $b ) {
			$a = $this->_get_reference_value( $a );
			$b = $this->_get_reference_value( $b );
			
			if ( 'number' === $this->_sort_type ) {
				if ( $a == $b )
					return 0;
				
				if ( 'asc' === $this->_sort_direction )
					return ( $a > $b ) ? -1 : 1;
				return ( $a > $b ) ? 1 : -1;
			}
			
			if ( 'desc' === $this->_sort_direction ) {
				if ( true === $this->_case_sensitive )
					return strnatcmp( $b, $a );
				return strnatcasecmp( $b, $a );
			}
			
			if ( true === $this->_case_sensitive )
				return strnatcmp( $a, $b );
			return strnatcasecmp( $a, $b );
		}
		
		function _get_reference_value( $key ) {
			return @eval( 'return $this->_array[$key]' . $this->_key . ';' );
		}
	}
}
