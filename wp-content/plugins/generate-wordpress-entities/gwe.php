<?php
/*
Plugin Name: Generate Wordpress Entities
Plugin URI: http://wordpress.org/
Description: Generate Wordpress pages, posts, categories etc.
Author: Marchenko Alexandr
Version: 1.4
Author URI: http://mac-blog.org.ua/
*/

require_once(dirname(__FILE__) . '/functions.php');
require_once(dirname(__FILE__) . '/pages/gwe.php');
require_once(dirname(__FILE__) . '/pages/pages.php');
require_once(dirname(__FILE__) . '/pages/posts.php');
require_once(dirname(__FILE__) . '/pages/categories.php');

add_action( 'admin_menu', 'gwe_admin_menu' );
function gwe_admin_menu() {
	$pages = array();
	$pages[] = add_menu_page('Generate Wordpress Entities', 'GWE', 'manage_options', 'gwe', 'gwe_manage_menu');
	$pages[] = add_submenu_page( 'gwe', 'Pages', 'Pages', 'manage_options', 'gwe-pages', 'gwe_pages_manage_menu');
	$pages[] = add_submenu_page( 'gwe', 'Posts', 'Posts', 'manage_options', 'gwe-posts', 'gwe_posts_manage_menu');
	$pages[] = add_submenu_page( 'gwe', 'Categories', 'Categories', 'manage_options', 'gwe-categories', 'gwe_categories_manage_menu');

	foreach($pages as $page) {
		add_action('admin_print_scripts-' . $page, 'gwe_admin_styles');
	}
}

function gwe_admin_styles() {
	wp_enqueue_script('jquery-ui-core');
    wp_enqueue_script('jquery-ui-sortable');
    wp_enqueue_script('postbox');
    wp_enqueue_script('post');
}
