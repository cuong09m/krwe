<?php
/*
Plugin Name: Widget Entries
Plugin URI: http://marquex.posterous.com/pages/widget-entries
Description: Widget Entries plugin creates the Widget post-type in the administration area to make easier the edition of the text widgets, and also register a new widget to import the widget entries easily. 
Author: Javier Márquez
Version: 0.1
Author URI: http://marquex.mp/
*/

if(!class_exists('WidgetEntries')):

class WidgetEntries {
	
	var $post_type_name = 'pt-widget';
	var $cap_required = 'edit_theme_options';
	
	function WidgetEntries(){
		
	}
	
	function registerWidget(){
		//let's load languagwe files first
		$dir = basename(dirname(__FILE__))."/lang";
		load_plugin_textdomain( 'widget-entries', 'wp-content/plugins/'.$dir, $dir);
		
		//require the iip plugin and the widget
		if(!function_exists('iinclude_page'))
			require_once 'iinclude_page.php';
			
		require_once 'widget-include-post.php';
		
		register_widget('Widget_Include_Post');		
	}
	
	function registerWidgetPostType(){
		
		//register the post type
		register_post_type(
			$this->post_type_name,
			array(
				'publicy_queryable' => false,
				'exclude_from_search' => true,
				'show_ui' => true,
				'show_in_nav_menus' => false,
				'labels' => array( 
					'name' => 'Widgets', 
					'singular_name' => 'Widget',
					'add_new' => __('Add New', 'widget-entries'),
					'add_new_item' => __('Add New Widget', 'widget-entries'),
					'edit_item' => __('Edit Widget', 'widget-entries'),
					'new_item' => __('New Widget', 'widget-entries'),
					'view_item' => __('Widgets will not be displayed as posts.', 'widget-entries'),
					'search_item' => __('Search Widgets', 'widget-entries'),
					'not found' => __('No widgets found', 'widget-entries'),
					'not_found in trash' => __('No widgets found in Trash', 'widget-entries'),
				),
				'capabilities' => array(
					'edit_post' => $this->cap_required,
					'edit_posts' => $this->cap_required,
					'edit_others_posts' => $this->cap_required,
					'publish_posts' => $this->cap_required,
					'read_post' => 'read',
					'read_private_posts' => 'read',
					'delete_post' => $this->cap_required,
				),
				'supports' => array('title','editor','revisions')
			)
		);
	}
}



endif; //class exists



if(!isset($plugin_widgetentries)){
	$plugin_widgetentries = new WidgetEntries();
	add_action('init', array($plugin_widgetentries, 'registerWidgetPostType'));
	add_action('widgets_init', array($plugin_widgetentries, 'registerWidget'));
}