<?php
/**
 * @package Featured articles PRO - Wordpress plugin
 * @author CodeFlavors ( codeflavors[at]codeflavors.com )
 * @version 2.4
 */

/**
 * Some details about the theme. 
 * Also notice key Fields. It stores the above field and flags it as enabled for this theme. All other themes will display this field disabled.
 */
function fa_classic_responsive_theme_details( $defaults ){
	$defaults = array(
		'Author'		=> 'CodeFlavors',
		'AuthorURI'		=> 'http://www.codeflavors.com',
		'Copyright'		=> 'author',
		'Compatibility'	=> 'Featured Articles 2.4',
		'Fields'		=> array(),
		'Image'			=> 'image',
		'ThemePreview' 	=> 'thumbnail',
		'Message'		=> 'Slide background color is disabled for this theme.'		
	);	
	return $defaults;
	
}
add_filter('fa-theme-details-classic_responsive', 'fa_classic_responsive_theme_details', 1);