<?php

/*
Written by Chris Jean for iThemes.com
Version 1.0.2

Version History
	1.0.0 - 2010-07-27
		Initial release version
	1.0.1 - 2010-10-05
		Added check for function get_post_type_object to force 3.0-only use
	1.0.2 - 2010-10-06 - Chris Jean
		Removed "public" from function definition for PHP 4 compatibility
*/


if ( ! class_exists( 'ITPostType' ) ) {
	class ITPostType {
		var $_file = '';
		
		var $_var = '';
		var $_name = '';
		var $_name_plural = '';
		
		var $_settings = array();
		var $_meta_boxes = array();
		var $_menu_pages = array();
		
		var $_editor_load_jquery = false;
		var $_editor_load_thickbox = false;
		var $_public_load_jquery = false;
		var $_public_load_thickbox = false;
		var $_has_custom_screen_icon = false;
		
		var $_page_refs = array();
		
		
		function ITPostType() {
			if ( empty( $this->_var ) || ! function_exists( 'get_post_type_object' ) )
				return;
			
			if ( empty( $this->_template_file_base ) )
				$this->_template_file_base = str_replace( '_', '-', $this->_var );
			
			$this->__setup_default_settings();
			
			$this->__add_hooks();
		}
		
		function __add_hooks() {
			add_action( 'init', array( &$this, '__init' ) );
			add_action( 'admin_init', array( &$this, '__admin_init' ) );
			add_action( 'generate_rewrite_rules', array( &$this, '__generate_rewrite_rules' ) );
			add_action( 'deactivated_plugin', array( &$this, 'deactivate_plugin' ) );
			add_action( 'save_post', array( &$this, 'save_meta_box_options' ) );
			add_action( 'admin_print_scripts-post.php', array( &$this, '__admin_print_editor_scripts' ) );
			add_action( 'admin_print_scripts-post-new.php', array( &$this, '__admin_print_editor_scripts' ) );
			add_action( 'admin_print_styles-post.php', array( &$this, '__admin_print_editor_styles' ) );
			add_action( 'admin_print_styles-post-new.php', array( &$this, '__admin_print_editor_styles' ) );
			add_action( 'admin_print_styles', array( &$this, '__admin_print_styles' ) );
			add_action( 'wp_print_scripts', array( &$this, '__print_scripts' ) );
			add_action( 'wp_print_styles', array( &$this, '__print_styles' ) );
			add_action( 'admin_menu', array( &$this, '__admin_menu' ) );
			add_action( 'template_redirect', array( &$this, '__modify_query_flags' ), -100 );
			
			add_filter( 'template_include', array( &$this, '__filter_template_include' ) );
			
			if ( true === $this->_has_custom_screen_icon )
				add_action( 'admin_notices', array( &$this, '__modify_current_screen' ) );
		}
		
		function __init() {
			$this->_registered_args = register_post_type( $this->_var, $this->_settings );
			
			if ( method_exists( $this, 'init' ) )
				$this->init();
		}
		
		function __admin_init() {
			if ( false === get_option( $this->_var . '_activated' ) )
				$this->__activate();
			
			$this->__add_contextual_help();
		}
		
		function __modify_query_flags() {
			global $wp_query;
			
			if ( ( $this->_var === get_query_var( 'post_type' ) ) && is_home() ) {
				$wp_query->is_home = false;
				$wp_query->is_archive = true;
			}
		}
		
		function __add_contextual_help() {
			if ( empty( $this->_menu_pages ) )
				return;
			
			foreach ( (array) $this->_menu_pages as $var => $args ) {
				if ( method_exists( $this, "{$var}_get_contextual_help" ) )
					add_contextual_help( $this->_page_refs[$var], call_user_func( array( $this, "{$var}_get_contextual_help" ) ) );
			}
		}
		
		function __admin_menu() {
			if ( empty( $this->_menu_pages ) )
				return;
			
			foreach ( (array) $this->_menu_pages as $var => $args )
				$this->_page_refs[$var] = add_submenu_page( "edit.php?post_type={$this->_var}", $args['page_title'], $args['menu_title'], $args['capability'], "{$this->_var}_{$var}", array( &$this, $args['callback'] ) );
		}
		
		function __activate() {
			$this->__flush_rewrite_rules();
		}
		
		function __generate_rewrite_rules( $wp_rewrite ) {
			$slug = $this->_settings['rewrite']['slug'];
			
			$new_rules = array();
			$new_rules["$slug/page/?([0-9]{1,})/?$"] = "index.php?post_type={$this->_var}&paged=" . $wp_rewrite->preg_index( 1 );
			$new_rules["$slug/(feed|rdf|rss|rss2|atom)/?$"] = "index.php?post_type={$this->_var}&feed=" . $wp_rewrite->preg_index( 1 );
			$new_rules["$slug/?$"] = "index.php?post_type={$this->_var}";
			
			$wp_rewrite->rules = array_merge($new_rules, $wp_rewrite->rules);
			
			if ( method_exists( $this, 'generate_rewrite_rules' ) )
				$wp_rewrite = $this->generate_rewrite_rules( $wp_rewrite );
			
			return $wp_rewrite;
		}
		
		function __flush_rewrite_rules() {
			global $wp_rewrite;
			$wp_rewrite->flush_rules();
			
			update_option( $this->_var . '_activated', true );
		}
		
		function deactivate_plugin() {
			delete_option( $this->_var . '_activated' );
		}
		
		function __filter_template_include( $template ) {
			if ( $this->_var !== get_query_var( 'post_type' ) )
				return $template;
			
			if ( is_single() )
				$new_template = locate_template( array( $this->_template_file_base . '/single.php', "single-{$this->_template_file_base}.php", 'single.php' ) );
			else
				$new_template = locate_template( array( $this->_template_file_base . '/archive.php', "archive-{$this->_template_file_base}.php", 'archive.php' ) );
			
			if ( ! empty( $new_template ) )
				return $new_template;
			return $template;
		}
		
		function __admin_print_styles() {
			if ( file_exists( dirname( $this->_file ) . "/css/admin-{$this->_var}.css" ) ) {
				it_classes_load( 'it-file-utility.php' );
				
				$css_url = ITFileUtility::get_url_from_file( dirname( $this->_file ) . "/css/admin-{$this->_var}.css" );
				wp_enqueue_style( "{$this->_var}-admin-style", $css_url );
			}
			
			if ( method_exists( $this, 'admin_print_styles' ) )
				$this->admin_print_styles();
		}
		
		function __admin_print_editor_scripts() {
			global $post;
			$post_type = $post->post_type;
			
			if ( $post_type !== $this->_var )
				return;
			
			
			if ( true === $this->_editor_load_jquery )
				wp_enqueue_script( 'jquery' );
			if ( true === $this->_editor_load_thickbox )
				wp_enqueue_script( 'thickbox' );
			
			if ( file_exists( dirname( $this->_file ) . "/js/editor-{$this->_var}.js" ) ) {
				it_classes_load( 'it-file-utility.php' );
				
				$js_url = ITFileUtility::get_url_from_file( dirname( $this->_file ) . "/js/editor-{$this->_var}.js" );
				wp_enqueue_script( "{$this->_var}-script", $js_url );
			}
			
			if ( method_exists( $this, 'admin_print_editor_scripts' ) )
				$this->admin_print_editor_scripts();
		}
		
		function __admin_print_editor_styles() {
			global $post;
			$post_type = $post->post_type;
			
			if ( $post_type !== $this->_var )
				return;
			
			
			if ( file_exists( dirname( $this->_file ) . "/css/editor-{$this->_var}.css" ) ) {
				it_classes_load( 'it-file-utility.php' );
				
				$css_url = ITFileUtility::get_url_from_file( dirname( $this->_file ) . "/css/editor-{$this->_var}.css" );
				wp_enqueue_style( "{$this->_var}-editor-style", $css_url );
			}
			
			if ( method_exists( $this, 'admin_print_editor_styles' ) )
				$this->admin_print_editor_styles();
		}
		
		function __print_scripts() {
			global $post;
			
			if ( ! isset( $post->post_type ) || ( $post->post_type !== $this->_var ) )
				return;
			
			
			if ( true === $this->_public_load_jquery )
				wp_enqueue_script( 'jquery' );
			if ( true === $this->_public_load_thickbox )
				wp_enqueue_script( 'thickbox' );
			
			if ( file_exists( dirname( $this->_file ) . "/js/public-{$this->_var}.js" ) ) {
				it_classes_load( 'it-file-utility.php' );
				
				$js_url = ITFileUtility::get_url_from_file( dirname( $this->_file ) . "/js/public-{$this->_var}.js" );
				wp_enqueue_script( "{$this->_var}-script", $js_url );
			}
			
			if ( method_exists( $this, 'print_scripts' ) )
				$this->print_scripts();
		}
		
		function __print_styles() {
			global $post;
			
			if ( ! isset( $post->post_type ) || ( $post->post_type !== $this->_var ) )
				return;
			
			
			if ( file_exists( dirname( $this->_file ) . "/css/public-{$this->_var}.css" ) ) {
				it_classes_load( 'it-file-utility.php' );
				
				$css_url = ITFileUtility::get_url_from_file( dirname( $this->_file ) . "/css/public-{$this->_var}.css" );
				wp_enqueue_style( "{$this->_var}-style", $css_url );
			}
			
			if ( method_exists( $this, 'print_styles' ) )
				$this->print_styles();
		}
		
		function __modify_current_screen() {
			global $current_screen;
			if ( empty( $current_screen->parent_file ) || ( "edit.php?post_type={$this->_var}" !== $current_screen->parent_file ) )
				return $current_screen;
			
			$current_screen->parent_base = $this->_var;
		}
		
		function __setup_default_settings() {
			$default_settings = array(
				'description'			=> null,
				'menu_icon'				=> null,	// URL of icon
				'hierarchical'			=> false,
				'exclude_from_search'	=> false,	// Make an option on the back-end?
				'show_in_nav_menus'		=> true,	// null to disable
				
				'taxonomies'			=> array(),	// Taxonomies to register for the post type. Shorthand for register_taxonomy() or register_taxonomy_for_object_type()
				'rewrite'				=> array(
					'slug'			=> null,
					'with_front'	=> false,
				),
				'supports'				=> array(	// Shorthand for calling add_post_type_support()
					'title',
					'editor',
/*					'comments',
					'revisions',
					'author',
					'excerpt',
					'thumbnail',
					'custom-fields',*/
				),
				
				'register_meta_box_cb'	=> array( &$this, 'register_meta_boxes' ),
				'show_ui'				=> true,
				'menu_position'			=> null,	// null is default location (bottom of stack). Specific position can be selected by supplying a number (not string).
				'capability_type'		=> 'post',	// Base set of user permissions to match
				'public'				=> true,	// Show in WP back-end?
				'publicly_queryable'	=> true,	// Dig into further
				'query_var'				=> true,	// false to disable the query_var or a string to set which variable name should be used
				'can_export'			=> true,
			);
			
			$default_settings['labels'] = $this->__get_default_labels();
			
			$this->_settings = array_merge( $default_settings, $this->_settings );
			
			if ( ! empty( $this->_settings['menu_icon'] ) && ! preg_match( '/^http/', $this->_settings['menu_icon'] ) ) {
				if ( ! isset( $this->_url_base ) ) {
					it_classes_load( 'it-file-utility.php' );
					$this->_url_base = ITFileUtility::get_url_from_file( dirname( $this->_file ) );
				}
				
				$this->_settings['menu_icon'] = $this->_url_base . '/' . $this->_settings['menu_icon'];
			}
		}
		
		function __get_default_labels() {
			$labels = array(
				'name'					=> $this->_name_plural,
				'singular_name'			=> $this->_name,
				'add_new'				=> 'Add New',
				'add_new_item'			=> "Add New {$this->_name}",
				'edit_item'				=> "Edit {$this->_name}",
				'new_item'				=> "New {$this->_name}",
				'view_item'				=> "View {$this->_name}",
				'search_items'			=> "Search {$this->_name_plural}",
				'not_found'				=> 'No ' . strtolower( $this->_name ) . ' found',
				'not_found_in_trash'	=> 'No ' . strtolower( $this->_name_plural ) . ' found in trash',
				'parent_item_colon'		=> "Parent {$this->_name}:",
			);
			
			return $labels;
		}
		
		function register_meta_boxes() {
			do_action( "register_post_type_meta_boxes-{$this->_var}" );
			
			foreach ( (array) $this->_meta_boxes as $var => $args )
				add_meta_box( $var, $args['title'], array( &$this, 'render_meta_box' ), $this->_var, $args['context'], $args['priority'], $args );
		}
		
		function render_meta_box( $post, $object ) {
			if ( ! isset( $this->_meta_box_options ) )
				$this->_meta_box_options = get_post_meta( $post->ID, '_it_options', true );
			
			if ( ! isset( $this->_meta_box_form ) )
				$this->_meta_box_form =& new ITForm( $this->_meta_box_options, array( 'prefix' => $this->_var ) );
			
			call_user_func( array( &$this, $object['args']['callback'] ), $post, $this->_meta_box_form, $this->_meta_box_options, $object );
			
			if ( ! isset( $this->_meta_box_nonce_added ) ) {
				$this->_meta_box_form->add_hidden( 'nonce', wp_create_nonce( $this->_var ) );
				
				$this->_meta_box_nonce_added = true;
			}
		}
		
		function save_meta_box_options( $post_id ) {
			// Skip if the nonce check fails
			if ( ! isset( $_POST["{$this->_var}-nonce"] ) || ! wp_verify_nonce( $_POST["{$this->_var}-nonce"], $this->_var ) )
				return;
			
			// Don't save or update on autosave
			if ( defined( 'DOING_AUTOSAVE' ) && ( true === DOING_AUTOSAVE ) )
				return;
			
			// Only allow those with permissions to modify the type to save/update a layout
			if ( ! current_user_can( 'edit_post', $post_id ) )
				return;
			
			
			// Save/update options
			$options = ITForm::parse_values( $_POST, array( 'prefix' => $this->_var ) );
			unset( $options['nonce'] );
			
			if ( method_exists( $this, 'validate_meta_box_options' ) )
				$options = $this->validate_meta_box_options( $options );
			
			update_post_meta( $post_id, '_it_options', $options );
		}
	}
}


?>
