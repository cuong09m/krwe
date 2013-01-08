<?php
function reverie_setup() {
	// Add language supports. Please note that Reverie Framework does not
	// include language files.
	load_theme_textdomain ( 'reverie', get_template_directory () . '/lang' );

	// Add post thumbnail supports. http://codex.wordpress.org/Post_Thumbnails
	add_theme_support ( 'post-thumbnails' );

	add_image_size ( 'thumbnail72', 72, 72, true );
	add_image_size ( 'thumbnail290', 290, 290, true );
	add_image_size ( 'thumbnail98', 98, 98, true );

	// set_post_thumbnail_size(150, 150, false);

	// Add post formarts supports. http://codex.wordpress.org/Post_Formats
	add_theme_support ( 'post-formats', array ('aside', 'gallery', 'link', 'image', 'quote', 'status', 'video', 'audio', 'chat' ) );

	// Add menu supports.
	// http://codex.wordpress.org/Function_Reference/register_nav_menus
	add_theme_support ( 'menus' );
	register_nav_menus ( array ('primary_navigation' => __ ( 'Primary Navigation', 'reverie' ), 'utility_navigation' => __ ( 'Utility Navigation', 'reverie' ), 'header_navigation' => __ ( 'Header Navigation', 'reverie' ) ) );
}
add_action ( 'after_setup_theme', 'reverie_setup' );

// Enqueue for header and footer, thanks to flickapix on Github.
// Enqueue css files
function reverie_css() {
	if (! is_admin ()) {

		wp_register_style ( 'foundation', get_template_directory_uri () . '/css/foundation.css', false );
		wp_enqueue_style ( 'foundation' );

		wp_register_style ( 'app', get_template_directory_uri () . '/css/app.css', false );
		wp_enqueue_style ( 'app' );

		// Load style.css to allow contents overwrite foundation & app css
		wp_register_style ( 'style', get_template_directory_uri () . '/style.css', false );
		wp_enqueue_style ( 'style' );

		wp_register_style ( 'google_font', "http://fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,400,300", false );
		wp_enqueue_style ( 'google_font' );

	}
}
add_action ( 'init', 'reverie_css' );

function reverie_ie_css() {
	echo '<!--[if lt IE 9]>';
	echo '<link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/css/ie.css">';
	echo '<![endif]-->';
}
add_action ( 'wp_head', 'reverie_ie_css' );

// Enqueue js files
function reverie_scripts() {

	global $is_IE;

	if (! is_admin ()) {

		// Enqueue to header
		wp_deregister_script ( 'jquery' );
		wp_register_script ( 'jquery', get_template_directory_uri () . '/js/jquery.min.js' );
		wp_enqueue_script ( 'jquery' );

		wp_register_script ( 'modernizr', get_template_directory_uri () . '/js/modernizr.foundation.js', array ('jquery' ) );
		wp_enqueue_script ( 'modernizr' );

		// Enqueue to footer
		wp_register_script ( 'reveal', get_template_directory_uri () . '/js/jquery.reveal.js', array ('jquery' ), false, true );
		wp_enqueue_script ( 'reveal' );

		wp_register_script ( 'orbit', get_template_directory_uri () . '/js/jquery.orbit-1.4.0.js', array ('jquery' ), false, true );
		wp_enqueue_script ( 'orbit' );

		wp_register_script ( 'custom_forms', get_template_directory_uri () . '/js/jquery.customforms.js', array ('jquery' ), false, true );
		wp_enqueue_script ( 'custom_forms' );

		wp_register_script ( 'placeholder', get_template_directory_uri () . '/js/jquery.placeholder.min.js', array ('jquery' ), false, true );
		wp_enqueue_script ( 'placeholder' );

		wp_register_script ( 'tooltips', get_template_directory_uri () . '/js/jquery.tooltips.js', array ('jquery' ), false, true );
		wp_enqueue_script ( 'tooltips' );

		wp_register_script ( 'app', get_template_directory_uri () . '/js/app.js', array ('jquery' ), false, true );
		wp_enqueue_script ( 'app' );

		if ($is_IE) {
			wp_register_script ( 'html5shiv', "http://html5shiv.googlecode.com/svn/trunk/html5.js", false, true );
			wp_enqueue_script ( 'html5shiv' );
		}

		// Enable threaded comments
		if ((! is_admin ()) && is_singular () && comments_open () && get_option ( 'thread_comments' ))
			wp_enqueue_script ( 'comment-reply' );
	}
}
add_action ( 'init', 'reverie_scripts' );

// create widget areas: sidebar, footer
$sidebars = array ('Sidebar' );
foreach ( $sidebars as $sidebar ) {
	register_sidebar ( array ('name' => $sidebar, 'before_widget' => '<article id="%1$s" class="row widget %2$s"><div class="sidebar-section twelve columns">', 'after_widget' => '</div></article>', 'before_title' => '<h6><strong>', 'after_title' => '</strong></h6>' ) );
}
$sidebars = array ('Footer' );
foreach ( $sidebars as $sidebar ) {
	register_sidebar ( array ('name' => $sidebar, 'before_widget' => '<article id="%1$s" class="four columns widget %2$s"><div class="footer-section">', 'after_widget' => '</div></article>', 'before_title' => '<h6><strong>', 'after_title' => '</strong></h6>' ) );
}

register_sidebar ( array ('name' => 'Top header adv', 'before_widget' => '', 'after_widget' => '', 'before_title' => '', 'after_title' => '' ) );

// return entry meta information for posts, used by multiple loops.
function reverie_entry_meta() {
	echo '<time class="updated" datetime="' . get_the_time ( 'c' ) . '" pubdate>' . sprintf ( __ ( 'Posted on %s at %s.', 'reverie' ), get_the_time ( 'l, F jS, Y' ), get_the_time () ) . '</time>';
	echo '<p class="byline author vcard">' . __ ( 'Written by', 'reverie' ) . ' <a href="' . get_author_posts_url ( get_the_author_meta ( 'ID' ) ) . '" rel="author" class="fn">' . get_the_author () . '</a></p>';
}

/*
 * Customized the output of caption, you can remove the filter to restore back
 * to the WP default output. Courtesy of DevPress.
 * http://devpress.com/blog/captions-in-wordpress/
 */
add_filter ( 'img_caption_shortcode', 'cleaner_caption', 10, 3 );

function cleaner_caption($output, $attr, $content) {

	/*
	 * We're not worried abut captions in feeds, so just return the output here.
	 */
	if (is_feed ())
		return $output;

		/*
	 * Set up the default arguments.
	 */
	$defaults = array ('id' => '', 'align' => 'alignnone', 'width' => '', 'caption' => '' );

	/*
	 * Merge the defaults with user input.
	 */
	$attr = shortcode_atts ( $defaults, $attr );

	/*
	 * If the width is less than 1 or there is no caption, return the content
	 * wrapped between the [caption]< tags.
	 */
	if (1 > $attr ['width'] || empty ( $attr ['caption'] ))
		return $content;

		/*
	 * Set up the attributes for the caption <div>.
	 */
	$attributes = ' class="figure ' . esc_attr ( $attr ['align'] ) . '"';

	/*
	 * Open the caption <div>.
	 */
	$output = '<figure' . $attributes . '>';

	/*
	 * Allow shortcodes for the content the caption was created for.
	 */
	$output .= do_shortcode ( $content );

	/*
	 * Append the caption text.
	 */
	$output .= '<figcaption>' . $attr ['caption'] . '</figcaption>';

	/*
	 * Close the caption </div>.
	 */
	$output .= '</figure>';

	/*
	 * Return the formatted, clean caption.
	 */
	return $output;
}

// Clean the output of attributes of images in editor. Courtesy of SitePoint.
// http://www.sitepoint.com/wordpress-change-img-tag-html/
function image_tag_class($class, $id, $align, $size) {
	$align = 'align' . esc_attr ( $align );
	return $align;
}
add_filter ( 'get_image_tag_class', 'image_tag_class', 0, 4 );
function image_tag($html, $id, $alt, $title) {
	return preg_replace ( array ('/\s+width="\d+"/i', '/\s+height="\d+"/i', '/alt=""/i' ), array ('', '', '', 'alt="' . $title . '"' ), $html );
}
add_filter ( 'get_image_tag', 'image_tag', 0, 4 );

// Customize output for menu
class reverie_walker extends Walker_Nav_Menu {
	function start_lvl(&$output, $depth) {
		$indent = str_repeat ( "\t", $depth );
		$output .= "\n$indent<a href=\"#\" class=\"flyout-toggle\"><span> </span></a><ul class=\"flyout\">\n";
	}
}

// Add Foundation 'active' class for the current menu item
function reverie_active_nav_class($classes, $item) {
	if ($item->current == 1) {
		$classes [] = 'active';
	}
	return $classes;
}
add_filter ( 'nav_menu_css_class', 'reverie_active_nav_class', 10, 2 );

// img unautop, Courtesy of Interconnectit
// http://interconnectit.com/2175/how-to-remove-p-tags-from-images-in-wordpress/
function img_unautop($pee) {
	$pee = preg_replace ( '/<p>\\s*?(<a .*?><img.*?><\\/a>|<img.*?>)?\\s*<\\/p>/s', '<figure>$1</figure>', $pee );
	return $pee;
}
add_filter ( 'the_content', 'img_unautop', 30 );

// Pagination
function reverie_pagination() {
	global $wp_query;

	$big = 999999999; // This needs to be an unlikely integer

	// For more options and info view the docs for paginate_links()
	                  // http://codex.wordpress.org/Function_Reference/paginate_links
	$paginate_links = paginate_links ( array ('base' => str_replace ( $big, '%#%', get_pagenum_link ( $big ) ), 'current' => max ( 1, get_query_var ( 'paged' ) ), 'total' => $wp_query->max_num_pages, 'mid_size' => 5, 'prev_next' => True, 'prev_text' => __ ( '&laquo;' ), 'next_text' => __ ( '&raquo;' ), 'type' => 'list' ) );

	// Display the pagination if more than one page is found
	if ($paginate_links) {
		echo '<div class="reverie-pagination">';
		echo $paginate_links;
		echo '</div><!--// end .pagination -->';
	}
}

// Presstrends
function presstrends() {

	// Add your PressTrends and Theme API Keys
	$api_key = 'xc11x4vpf17icuwver0bhgbzz4uewlu5ql38';
	$auth = 'kw1f8yr8eo1op9c859qcqkm2jjseuj7zp';

	// NO NEED TO EDIT BELOW
	$data = get_transient ( 'presstrends_data' );
	if (! $data || $data == '') {
		$api_base = 'http://api.presstrends.io/index.php/api/sites/add/auth/';
		$url = $api_base . $auth . '/api/' . $api_key . '/';
		$data = array ();
		$count_posts = wp_count_posts ();
		$count_pages = wp_count_posts ( 'page' );
		$comments_count = wp_count_comments ();
		$theme_data = wp_get_theme ();
		$plugin_count = count ( get_option ( 'active_plugins' ) );
		$all_plugins = get_plugins ();
		foreach ( $all_plugins as $plugin_file => $plugin_data ) {
			$plugin_name .= $plugin_data ['Name'];
			$plugin_name .= '&';
		}
		$data ['url'] = stripslashes ( str_replace ( array ('http://', '/', ':' ), '', site_url () ) );
		$data ['posts'] = $count_posts->publish;
		$data ['pages'] = $count_pages->publish;
		$data ['comments'] = $comments_count->total_comments;
		$data ['approved'] = $comments_count->approved;
		$data ['spam'] = $comments_count->spam;
		$data ['theme_version'] = $theme_data ['Version'];
		$data ['theme_name'] = urlencode ( $theme_data ['Name'] );
		$data ['site_name'] = str_replace ( ' ', '', get_bloginfo ( 'name' ) );
		$data ['plugins'] = $plugin_count;
		$data ['plugin'] = urlencode ( $plugin_name );
		$data ['wpversion'] = get_bloginfo ( 'version' );
		foreach ( $data as $k => $v ) {
			$url .= $k . '/' . $v . '/';
		}
		$response = wp_remote_get ( $url );
		set_transient ( 'presstrends_data', $data, 60 * 60 * 24 );
	}
}
add_action ( 'admin_init', 'presstrends' );

/**
 * custom function by : Alex
 * Automatically login VIP users by refferal URL
 */
function auto_login() {

	if (! is_user_logged_in ()) {
		if (! empty ( $_GET ['usUsername'] )) {
			$ul_encrypted = $_GET ['usUsername'];
			$trans = array (" " => "+" );
			$ul_encrypted = strtr ( $ul_encrypted, $trans );
			$user_login = trim ( mcrypt_decrypt ( MCRYPT_RIJNDAEL_256, 'refferel_link', base64_decode ( $ul_encrypted ), MCRYPT_MODE_ECB, mcrypt_create_iv ( mcrypt_get_iv_size ( MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB ), MCRYPT_RAND ) ) );
			$user = get_userdatabylogin ( $user_login );
			$user_id = $user->ID;
			wp_set_current_user ( $user_id, $user_login );
			wp_set_auth_cookie ( $user_id );
			do_action ( 'wp_login', $user_login );
		}
	}
}
add_action ( 'init', 'auto_login' );

/**
 * Function by : Alex
 * Automaticall login VIP users if their IP is in VIP IP list
 */

function auto_login_vip() {
	global $wpdb;
	if (! is_user_logged_in ()) {
		$ip = $_SERVER ['REMOTE_ADDR'];
		$user_login = $wpdb->get_var ( "SELECT user_login FROM wp_users as u, wp_vip as ip where (u.ID=ip.user_id) and (inet_aton (ip.start_ip) <= inet_aton('" . $ip . "')) and (inet_aton(ip.end_ip) >= inet_aton('" . $ip . "'))" );
		$user = get_userdatabylogin ( $user_login );
		$user_id = $user->ID;
		wp_set_current_user ( $user_id, $user_login );
		wp_set_auth_cookie ( $user_id );
		do_action ( 'wp_login', $user_login );
	}
}

// add_action ( 'init', 'auto_login_vip' );

/**
 * Function by : Ajith
 * encrypt usernames/any string
 *
 * @since Campus Review 1.0
 *
 *
 */
define ( 'SALT', 'refferel_link' );

//function encrypt($text) {
//	return trim ( base64_encode ( mcrypt_encrypt ( MCRYPT_RIJNDAEL_256, SALT, $text, MCRYPT_MODE_ECB, mcrypt_create_iv ( mcrypt_get_iv_size ( MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB ), MCRYPT_RAND ) ) ) );
//}

/**
 * Function by : Ajith
 * decrypt usernames/any string
 *
 * @since Campus Review 1.0
 *
 *
 */
//function decrypt($text) {
//	return trim ( mcrypt_decrypt ( MCRYPT_RIJNDAEL_256, SALT, base64_decode ( $text ), MCRYPT_MODE_ECB, mcrypt_create_iv ( mcrypt_get_iv_size ( MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB ), MCRYPT_RAND ) ) );
//}

function reff_url_function() {
	global $current_user;
	get_currentuserinfo ();
	$user_login = $current_user->user_login;
	$user_login_enc = encrypt ( $user_login );
	$reff_url = "http://www.campusreview.com.au/?usUsername=" . $user_login_enc;
	if (is_user_logged_in ()) {
		if (! current_user_can ( 'manage_options' )) {
			echo "<b>Your Refferal URL is : </b><br /> <a href='$reff_url' target='_blank'>$reff_url</a>";
		}
	}
}

add_shortcode ( 'reff_url', 'reff_url_function' );
// shortcode[reff_url]

function ip_shortcode() {
	global $wpdb;
	$ip = $_SERVER ['REMOTE_ADDR'];
	// $user_login = $wpdb->get_var( "SELECT user_login FROM wp_users as u,
	// wp_vip as ip where (u.ID=ip.user_id) and (inet_aton (ip.start_ip) <=
	// inet_aton('".$ip."')) and (inet_aton(ip.end_ip) >=
	// inet_aton('".$ip."'))");
	// $user = get_userdatabylogin($user_login);
	// $user_id = $user->ID;
	// wp_set_current_user($user_id, $user_login);
	// wp_set_auth_cookie($user_id);
	// do_action('wp_login', $user_login);
	echo "<b>Your IP is : <strong>$ip</strong></br></br>";
}

add_shortcode ( 'user_ip', 'ip_shortcode' );

function fb_add_custom_user_profile_fields($user) {
	global $current_user;
	get_currentuserinfo ();
	$user_login = $current_user->user_login;
	$user_login_enc = encrypt ( $user_login );
	$reff_url = home_url ( '/' ) . "/?usUsername=" . $user_login_enc;
	?>
<h3><?php _e('IP information', 'your_textdomain'); ?></h3>
<table class="form-table">
	<tr>
		<th><label for="address"><?php _e('Your current referral URL is ', 'your_textdomain'); ?>
			</label></th>
		<td>
				<?php echo $reff_url; ?>
			</td>
	</tr>

	<tr>
		<th><label for="address"><?php _e('Start IP address', 'your_textdomain'); ?>
			</label></th>
		<td><input type="text" name="start_ip_address" id="start_ip_address"
			value="<?php echo esc_attr( get_usermeta( $user->ID,'start_ip_address' ) ); ?>"
			class="regular-text" /><br /> <span class="description"><?php _e('Please enter your start IP address.', 'your_textdomain'); ?></span>
		</td>
	</tr>

	<tr>
		<th><label for="address"><?php _e('End IP address', 'your_textdomain'); ?>
			</label></th>
		<td><input type="text" name="end_ip_address" id="end_ip_address"
			value="<?php echo esc_attr( get_usermeta( $user->ID,'end_ip_address' ) ); ?>"
			class="regular-text" /><br /> <span class="description"><?php _e('Please enter your end IP address.', 'your_textdomain'); ?></span>
		</td>
	</tr>

</table>
<?php

}
function fb_save_custom_user_profile_fields($user_id) {
	if (! current_user_can ( 'edit_user', $user_id ))
		return FALSE;
	if (isset ( $_POST ['start_ip_address'] ) && isset ( $_POST ['end_ip_address'] )) {
		update_usermeta ( $user_id, 'start_ip_address', $_POST ['start_ip_address'] );
		update_usermeta ( $user_id, 'end_ip_address', $_POST ['end_ip_address'] );

		global $wpdb;
		$wpvip_data = $wpdb->get_row ( 'SELECT * FROM wp_vip WHERE user_id=' . $user_id, ARRAY_A );

		if ($wpvip_data == null) {
			$wpvip_data = array ('id' => '', 'user_id' => $user_id, 'start_ip' => $_POST ['start_ip_address'], 'end_ip' => $_POST ['end_ip_address'] );

			$wpdb->insert ( 'wp_vip', $wpvip_data );

		} else {
			$wpvip_data ['start_ip'] = $_POST ['start_ip_address'];
			$wpvip_data ['end_ip'] = $_POST ['end_ip_address'];
			$wpdb->update ( 'wp_vip', $wpvip_data, array ('id' => $wpvip_data ['id'] ) );
		}
	}
}

add_filter ( 'the_content', 'ezy_fix_error_link', 20 );
/**
 * Add a icon to the beginning of every post page.
 *
 * @uses is_single()
 */
function ezy_fix_error_link($content) {

	if (is_single ()) {
		$blog_url = get_home_url ();
		$content = preg_replace ( '#(href)="([^:"]*)(?:")#', '$1="' . $blog_url . '/$2"', $content );
	}

	// Returns the content.
	return $content;
}
add_action ( 'show_user_profile', 'fb_add_custom_user_profile_fields' );
add_action ( 'edit_user_profile', 'fb_add_custom_user_profile_fields' );
add_action ( 'personal_options_update', 'fb_save_custom_user_profile_fields' );
add_action ( 'edit_user_profile_update', 'fb_save_custom_user_profile_fields' );

function custom_excerpt_length($length) {
	return 30;
}

function new_excerpt_more($more) {
	global $post;
	return ' <a href="' . get_permalink ( $post->ID ) . '">more...</a>';
}

function excerpt($limit) {
	global $post;
	$excerpt = explode(' ', get_the_content(), $limit);
	if (count($excerpt)>=$limit) {
		array_pop($excerpt);
		$excerpt = implode(" ",$excerpt).' <a href="' . get_permalink ( $post->ID ) . '">more...</a>';
	} else {
		$excerpt = implode(" ",$excerpt);
	}
	$excerpt = preg_replace('`\[[^\]]*\]`','',$excerpt);
	return $excerpt;
}

function content($limit) {
	$content = explode(' ', get_the_content(), $limit);
	if (count($content)>=$limit) {
		array_pop($content);
		$content = implode(" ",$content).'...';
	} else {
		$content = implode(" ",$content);
	}
	$content = preg_replace('/\[.+\]/','', $content);
	$content = apply_filters('the_content', $content);
	$content = str_replace(']]>', ']]&gt;', $content);
	return $content;
}

add_filter ( 'excerpt_more', 'new_excerpt_more' );
add_filter ( 'excerpt_length', 'custom_excerpt_length', 999 );

function get_home_article($name, $slug) {
	?>
<div class="six columns homelistbox">
	<span class="region"><a href="http://recordofworldevents.com/reg/<?php echo $slug;?>"><?php echo $name;?></a></span>
		<?php
	$the_query = new WP_Query ( array ('tax_query' => array (array ('taxonomy' => 'reg', 'field' => 'slug', 'terms' => $slug ) ), 'posts_per_page' => 8 ,'post_type' => 'post',meta_key=>'wpcf-start-date',orderby=>'meta_value_num',order=>'DESC') );
	$i=0;
	while ( $the_query->have_posts () ) {
		$the_query->the_post ();
		 // Display first featured post
		?>
			<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
		<?php if(get_post_meta($the_query->post->ID, 'wpcf-nation', true)!='') {?><span class="nation"><a
					href="http://recordofworldevents.com/nation/<?php echo str_replace(" ", "-",get_post_meta($the_query->post->ID, 'wpcf-nation', true));?>"><?php echo get_post_meta($the_query->post->ID, 'wpcf-nation', true);?></a></span>:<?php }?>
		<span class="title">
			<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
		</span>
					<?php if($i==0) { echo excerpt(15); }?>
			</article>
			<?php
			$i++;
	}
	?>
		</div>
<?php
}

function get_breaking_hitory_articles($name, $slug) {
	?>
<div class="six columns breaking_history">
	<span class="region"><a href="http://recordofworldevents.com/reg/<?php echo $name;?>?type=breaking_history"><?php echo $name;?></a></span>
		<?php
	$the_query = new WP_Query (array ('tax_query' => array (array ('taxonomy' => 'reg', 'field' => 'slug', 'terms' => $slug ) ), 'posts_per_page' => 6,
			'post_type' => 'breaking_history',meta_key=>'wpcf-start-date',orderby=>'meta_value_num') );
	$i=0;
	while ( $the_query->have_posts () ) {
		$the_query->the_post ();
		 // Display first featured post
		?>
			<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
				<?php if(get_post_meta($the_query->post->ID, 'wpcf-nation', true)!='') {?><span class="nation"><a
					href="http://recordofworldevents.com/nation/<?php echo str_replace(" ", "-",get_post_meta($the_query->post->ID, 'wpcf-nation', true));?>"><?php echo get_post_meta($the_query->post->ID, 'wpcf-nation', true);?></a></span>:<?php }?>
				<span class="title">
					<a href="<?php the_permalink(); ?>"><?php the_title(); ?> (pub <span class="start_date"><?php echo date("M d, Y",get_post_meta($the_query->post->ID, 'wpcf-start-date', true));?></span>)</a>
				</span>
			</article>
			<?php
	}
	?>
	<article><a href="http://recordofworldevents.com/reg/<?php echo $name;?>?type=breaking_history">More...</a></article>
		</div>
<?php
}

function get_all_breaking_hitory_articles($name, $slug) {
	?>
<div class="twelve columns breaking_history_full">
	<h1>Breaking history:</h1>
	<h1>In-depth special reports</h1>
	<span class="region"><a href="http://recordofworldevents.com/reg/<?php echo $name;?>?type=breaking_history"><?php echo $name;?></a></span>
		<?php
	$the_query = new WP_Query (array ('tax_query' => array (array ('taxonomy' => 'reg', 'field' => 'slug', 'terms' => $slug ) ),'post_type' => 'breaking_history' ) );
	$i=0;
	while ( $the_query->have_posts () ) {
		$the_query->the_post ();
		 // Display first featured post
		?>
			<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
				<span class="nation"><a
					href="http://recordofworldevents.com/nation/<?php echo str_replace(" ", "-",get_post_meta($the_query->post->ID, 'wpcf-nation', true));?>"><?php echo get_post_meta($the_query->post->ID, 'wpcf-nation', true);?></a></span>:
				<span class="title">
					<a href="<?php the_permalink(); ?>"><?php the_title(); ?> (pub <span class="start_date"><?php echo date("M d, Y",get_post_meta($the_query->post->ID, 'wpcf-start-date', true));?></span>)</a>
				</span>
			</article>
			<?php
	}
	?>
		</div>
<?php
}

function get_daily_hitory_articles($name, $slug) {
	?>
<div class="six columns breaking_history">
	<span class="region"><a href="http://recordofworldevents.com/reg/<?php echo $name;?>?type=daily_history"><?php echo $name;?></a></span>
		<?php
	$the_query = new WP_Query (array ('tax_query' => array (array ('taxonomy' => 'reg', 'field' => 'slug', 'terms' => $slug ) ), 'posts_per_page' => 6,
			'post_type' => 'daily_history',meta_key=>'wpcf-start-date',orderby=>'meta_value_num') );
	$i=0;
	while ( $the_query->have_posts () ) {
		$the_query->the_post ();
		 // Display first featured post
		?>
			<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
				<?php if(get_post_meta($the_query->post->ID, 'wpcf-nation', true)!='') {?><span class="nation"><a
					href="http://recordofworldevents.com/nation/<?php echo str_replace(" ", "-",get_post_meta($the_query->post->ID, 'wpcf-nation', true));?>"><?php echo get_post_meta($the_query->post->ID, 'wpcf-nation', true);?></a></span>:<?php }?>
				<span class="title">
					<a href="<?php the_permalink(); ?>"><?php the_title(); ?> (pub <span class="start_date"><?php echo date("M d, Y",get_post_meta($the_query->post->ID, 'wpcf-start-date', true));?></span>)</a>
				</span>
			</article>
			<?php
	}
	?>
	<article><a href="http://recordofworldevents.com/reg/<?php echo $name;?>?type=daily_history">More...</a></article>
		</div>
<?php
}

function get_all_daily_hitory_articles($name, $slug) {
	?>
<div class="twelve columns breaking_history_full">
	<h1>Breaking history:</h1>
	<span class="region"><a href="http://recordofworldevents.com/reg/<?php echo $name;?>?type=daily_history"><?php echo $name;?></a></span>
		<?php
	$the_query = new WP_Query (array ('tax_query' => array (array ('taxonomy' => 'reg', 'field' => 'slug', 'terms' => $slug ) ),'post_type' => 'daily_history' ) );
	$i=0;
	while ( $the_query->have_posts () ) {
		$the_query->the_post ();
		 // Display first featured post
		?>
			<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
				<span class="nation"><a
					href="http://recordofworldevents.com/nation/<?php echo str_replace(" ", "-",get_post_meta($the_query->post->ID, 'wpcf-nation', true));?>"><?php echo get_post_meta($the_query->post->ID, 'wpcf-nation', true);?></a></span>:
				<span class="title">
					<a href="<?php the_permalink(); ?>"><?php the_title(); ?> (pub <span class="start_date"><?php echo date("M d, Y",get_post_meta($the_query->post->ID, 'wpcf-start-date', true));?></span>)</a>
				</span>
			</article>
			<?php
	}
	?>
		</div>
<?php
}

function get_articles($term_name, $class) {
	$the_query = new WP_Query(array ('post_type' => 'post', 'posts_per_page' => 5,'meta_query'=>array(array('key' => 'wpcf-nation', 'value' => $term_name,'compare'=>'=')),meta_key=>'wpcf-start-date',orderby=>'meta_value_num','paged'=>$paged));
    if($the_query->post_count>0) {
        echo '<div class="six columns reg_listbox" style="'.$class.'">';
        echo '<span class="nation"><a
			href="http://recordofworldevents.com/nation/'.$term_name.'">'.$term_name.'</a></span>';
        while ( $the_query->have_posts () ) {
            $the_query->the_post ();
             // Display first featured post
            ?>
                <article id="post-<?php the_ID(); ?>" >
                    <span class="title">
                        <a href="<?php the_permalink(); ?>"><?php the_title(); ?> <span class="start_date"><?php echo date("M, Y",get_post_meta($the_query->post->ID, 'wpcf-start-date', true));?></span></a>
                    </span>
                </article>
                <?php

        }
        echo '</div>';
    }
}

function post_util_bar() {
	?>
	<div class="twelve columns">
		<div id="post_util_bar">
		<span class="print"><a href="#" onclick="window.print();"><img src="<?php bloginfo('template_url'); ?>/images/printico.jpg"/> Print</a></span>
		<span class="share"><!-- Hupso Share Buttons - http://www.hupso.com/share/ -->
			<a class="hupso_pop" href="http://www.hupso.com/share/"><img
				src="<?php bloginfo('template_url'); ?>/images/shareico.jpg" border="0" alt="Share Button" /> Share</a>
		<script type="text/javascript">var hupso_services=new Array("Twitter","Facebook","Google Plus","Linkedin","StumbleUpon");var hupso_icon_type = "labels";var hupso_background="#EAF4FF";var hupso_border="#66CCFF";</script>
			<script type="text/javascript"
				src="http://static.hupso.com/share/js/share.js"></script>
			<!-- Hupso Share Buttons -->
			<a href="#" onclick="document.execCommand("SaveAs");"><img src="<?php bloginfo('template_url'); ?>/images/shareico.jpg"/> </a></span>
		<span class="save"><a href="#"><img src="<?php bloginfo('template_url'); ?>/images/saveico.jpg"/> Save</a></span></div>
	</div>
	<?php
}

class BreakingHistoryWidget extends WP_Widget {

	function BreakingHistoryWidget() {
		$widget_ops = array ('classname' => 'breaking_history', 'description' => 'Displays first breaking history' );
		$this->WP_Widget ( 'BreakingHistoryWidget', 'Breaking history', $widget_ops );
	}

	function widget($args, $instance) {
		extract ( $args, EXTR_SKIP );

		echo $before_widget;
		$title = empty ( $instance ['title'] ) ? ' ' : apply_filters ( 'widget_title', $instance ['title'] );

		if (! empty ( $title ))
			echo $before_title . $title . $after_title;
		;

		$the_query = new WP_Query(array ('post_type' => 'breaking_history', 'posts_per_page' => 1,meta_key=>'wpcf-start-date',orderby=>'meta_value' ));
		while ( $the_query->have_posts () ) {
			$the_query->the_post();
			?>
			<article id="post-<?php the_ID(); ?>" <?php post_class('breaking-history'); ?>>
			<?php
					echo get_the_post_thumbnail ($the_query->post->ID, 'thumbnail98' );
				?>
				<span class="nation"><a href="http://recordofworldevents.com/nation/<?php echo str_replace(" ", "-",get_post_meta($the_query->post->ID, 'wpcf-nation', true));?>"><?php echo get_post_meta($the_query->post->ID, 'wpcf-nation', true);?></a></span>
				<span class="title"> <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
				</span>

				<?php the_excerpt();?>
			</article>
            <a href="/breaking_history">See full listing of articles</a>
			<?php
		}
		echo $after_widget;
	}

	function update($new_instance, $old_instance) {
		$instance = $old_instance;
		$instance ['title'] = $new_instance ['title'];
		return $instance;
	}

	function form($instance) {
		$instance = wp_parse_args( (array) $instance, array( 'title' => '' ) );
    	$title = $instance['title'];
		?>
		  <p><label for="<?php echo $this->get_field_id('title'); ?>">Title: <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo attribute_escape($title); ?>" /></label></p>
		<?php
	}
}

class DailyHistoryWidget extends WP_Widget {

	function DailyHistoryWidget() {
		$widget_ops = array ('classname' => 'daily_history', 'description' => 'Displays first daily history' );
		$this->WP_Widget ( 'DailyHistoryWidget', 'Daily history', $widget_ops );
	}

	function widget($args, $instance) {
		extract ( $args, EXTR_SKIP );

		echo $before_widget;
		$title = empty ( $instance ['title'] ) ? ' ' : apply_filters ( 'widget_title', $instance ['title'] );

		if (! empty ( $title ))
			echo $before_title . $title . $after_title;
		;

		$the_query = new WP_Query(array ('post_type' => 'daily_history', 'posts_per_page' => 1 ));
		while ( $the_query->have_posts () ) {
			$the_query->the_post();
			?>
			<article id="post-<?php the_ID(); ?>" <?php post_class('breaking-history'); ?>>
			<?php
					echo get_the_post_thumbnail ($the_query->post->ID, 'thumbnail98' );
				?>
				<span class="nation"><a href="http://recordofworldevents.com/nation/<?php echo str_replace(" ", "-",get_post_meta($the_query->post->ID, 'wpcf-nation', true));?>"><?php echo get_post_meta($the_query->post->ID, 'wpcf-nation', true);?></a></span>
				<span class="title"> <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
				</span>

				<?php the_excerpt();?>
			</article>
            <a href="/daily_history">See full listing of articles</a>
			<?php
		}
		echo $after_widget;
	}

	function update($new_instance, $old_instance) {
		$instance = $old_instance;
		$instance ['title'] = $new_instance ['title'];
		return $instance;
	}

	function form($instance) {
		$instance = wp_parse_args( (array) $instance, array( 'title' => '' ) );
    	$title = $instance['title'];
		?>
		  <p><label for="<?php echo $this->get_field_id('title'); ?>">Title: <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo attribute_escape($title); ?>" /></label></p>
		<?php
	}
}

class RelatedPostByCountryWidget extends WP_Widget {

	function RelatedPostByCountryWidget() {
		$widget_ops = array ('classname' => 'related_countries', 'description' => 'Displays Related post by country' );
		$this->WP_Widget ( 'RelatedPostByCountryWidget', 'Related post by country', $widget_ops );
	}

	function widget($args, $instance) {
		extract ( $args, EXTR_SKIP );

		echo $before_widget;
		$title = empty ( $instance ['title'] ) ? ' ' : apply_filters ( 'widget_title', $instance ['title'] );

		if (! empty ( $title ))
			echo $before_title . $title . $after_title;
		;
		global $post;
        $post_nation=get_post_meta($post->ID, 'wpcf-nation', true);
		$post_terms=wp_get_post_categories($post->ID);
		echo "<ul>";
		$the_query = new WP_Query(array('category__in'=>$post_terms, 'posts_per_page' => 10, 'post__not_in' => array($post->ID),'meta_key' => 'wpcf-nation', 'meta_value' => $post_nation) );
		while ( $the_query->have_posts () ) {
			$the_query->the_post();
		?>
        <li>
            <a href="<?php the_permalink() ?>" rel="bookmark" title="<?php the_title(); ?>"><?php the_title(); ?></a> <?php if(get_post_meta($the_query->post->ID, 'wpcf-nation', true)) { ?>(<span class="nation"><a href="http://recordofworldevents.com/nation/<?php echo str_replace(" ", "-",get_post_meta($the_query->post->ID, 'wpcf-nation', true));?>"><?php echo get_post_meta($post->ID, 'wpcf-nation', true);?></a></span>)<?php }?><br>
            <span class="start_date"><?php echo date("F d, Y",get_post_meta($post->ID, 'wpcf-start-date', true));?></span>
        </li>

		<?php }
		echo "</ul>";
		wp_reset_query();
		wp_reset_postdata();

		echo $after_widget;
	}

	function update($new_instance, $old_instance) {
		$instance = $old_instance;
		$instance ['title'] = $new_instance ['title'];
		return $instance;
	}

	function form($instance) {
		$instance = wp_parse_args( (array) $instance, array( 'title' => '' ) );
    	$title = $instance['title'];
		?>
		  <p><label for="<?php echo $this->get_field_id('title'); ?>">Title: <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo attribute_escape($title); ?>" /></label></p>
		<?php
	}
}

class RelatedPostByPersonWidget extends WP_Widget {

	function RelatedPostByPersonWidget() {
		$widget_ops = array ('classname' => 'related_names', 'description' => 'Displays Related post by person' );
		$this->WP_Widget ( 'RelatedPostByPersonWidget', 'Related post by person', $widget_ops );
	}

	function widget($args, $instance) {
		extract ( $args, EXTR_SKIP );

		echo $before_widget;
		$title = empty ( $instance ['title'] ) ? ' ' : apply_filters ( 'widget_title', $instance ['title'] );

		if (! empty ( $title ))
			echo $before_title . $title . $after_title;
		;
		global $post;
		$post_terms=wp_get_post_terms($post->ID,"person");
		$termIDs = array();
		foreach($post_terms as $postterm) {
			$termIDs[]=$postterm->term_id;
		}
		echo "<ul>";
		$the_query = new WP_Query(array( array ('tax_query' => array (array ('taxonomy' => 'nation', 'field' => 'id', 'terms' => $termIDs) )), 'posts_per_page' => 10, 'post__not_in' => array($post->ID) ) );
		while ( $the_query->have_posts () ) {
			$the_query->the_post();
			?>
		        <li>
		        	<a href="<?php the_permalink() ?>" rel="bookmark" title="<?php the_title(); ?>"><?php the_title(); ?></a>
		        </li>
		<?php }
		echo "</ul>";
		wp_reset_query();
		wp_reset_postdata();

		echo $after_widget;
	}

	function update($new_instance, $old_instance) {
		$instance = $old_instance;
		$instance ['title'] = $new_instance ['title'];
		return $instance;
	}

	function form($instance) {
		$instance = wp_parse_args( (array) $instance, array( 'title' => '' ) );
    	$title = $instance['title'];
		?>
		  <p><label for="<?php echo $this->get_field_id('title'); ?>">Title: <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo attribute_escape($title); ?>" /></label></p>
		<?php
	}
}

class RelatedPostForDailyHistoryWidget extends WP_Widget {

	function RelatedPostForDailyHistoryWidget() {
		$widget_ops = array ('classname' => 'related_dailyhistory', 'description' => 'Displays Related post for daily history' );
		$this->WP_Widget ( 'RelatedPostByDailyHistoryWidget', 'Related post for daily history', $widget_ops );
	}

	function widget($args, $instance) {
		extract ( $args, EXTR_SKIP );

		echo $before_widget;
		$title = empty ( $instance ['title'] ) ? ' ' : apply_filters ( 'widget_title', $instance ['title'] );

		if (! empty ( $title ))
			echo $before_title . $title . $after_title;
		;
		global $post;
//		$post_terms=wp_get_post_terms($post->ID,"nation");
//		$termIDs = array();
//		foreach($post_terms as $postterm) {
//			$termIDs[]=$postterm->term_id;
//		}
		echo "<ul>";
		$the_query = new WP_Query(array('posts_per_page' => 10, 'post__not_in' => array($post->ID),'post_type' => 'daily_history' ) );
		while ( $the_query->have_posts () ) {
			$the_query->the_post();
			?>
		        <li>
		        	<a href="<?php the_permalink() ?>" rel="bookmark" title="<?php the_title(); ?>"><?php the_title(); ?></a> <?php if(get_post_meta($the_query->post->ID, 'wpcf-nation', true)) { ?>(<span class="nation"><a href="http://recordofworldevents.com/nation/<?php echo str_replace(" ", "-",get_post_meta($the_query->post->ID, 'wpcf-nation', true));?>"><?php echo get_post_meta($post->ID, 'wpcf-nation', true);?></a></span>)<?php }?><br>
		        	<span class="start_date"><?php echo date("F d, Y",get_post_meta($post->ID, 'wpcf-start-date', true));?></span>
		        </li>

		<?php }
		echo "</ul>";
		wp_reset_query();
		wp_reset_postdata();

		echo $after_widget;
	}

	function update($new_instance, $old_instance) {
		$instance = $old_instance;
		$instance ['title'] = $new_instance ['title'];
		return $instance;
	}

	function form($instance) {
		$instance = wp_parse_args( (array) $instance, array( 'title' => '' ) );
    	$title = $instance['title'];
		?>
		  <p><label for="<?php echo $this->get_field_id('title'); ?>">Title: <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo attribute_escape($title); ?>" /></label></p>
		<?php
	}
}

class RelatedPostForBreakingIndepthHistoryWidget extends WP_Widget {

    function RelatedPostForBreakingIndepthHistoryWidget() {
        $widget_ops = array ('classname' => 'related_breakinghistory', 'description' => 'Displays Related post for breaking history' );
        $this->WP_Widget ( 'RelatedPostForBreakingIndepthHistoryWidget', 'Related post for breaking history', $widget_ops );
    }

    function widget($args, $instance) {
        extract ( $args, EXTR_SKIP );

        echo $before_widget;
        $title = empty ( $instance ['title'] ) ? ' ' : apply_filters ( 'widget_title', $instance ['title'] );

        if (! empty ( $title ))
            echo $before_title . $title . $after_title;
        ;
        global $post;
//        global $post;
//        $post_nation=get_post_meta($post->ID, 'wpcf-nation', true);
//        $post_terms=wp_get_post_categories($post->ID);
//        echo "<ul>";
//        $the_query = new WP_Query(array('category__in'=>$post_terms, 'posts_per_page' => 10, 'post__not_in' => array($post->ID),'meta_key' => 'wpcf-nation', 'meta_value' => $post_nation,'post_type' => 'breaking_history') );
        echo "<ul>";
        $the_query = new WP_Query(array('posts_per_page' => 10, 'post__not_in' => array($post->ID),'post_type' => 'breaking_history' ) );
        while ( $the_query->have_posts () ) {
            $the_query->the_post();
            ?>
        <li>
            <a href="<?php the_permalink() ?>" rel="bookmark" title="<?php the_title(); ?>"><?php the_title(); ?></a> <?php if(get_post_meta($the_query->post->ID, 'wpcf-nation', true)) { ?>(<span class="nation"><a href="http://recordofworldevents.com/nation/<?php echo str_replace(" ", "-",get_post_meta($the_query->post->ID, 'wpcf-nation', true));?>"><?php echo get_post_meta($post->ID, 'wpcf-nation', true);?></a></span>)<?php }?><br>
            <span class="start_date"><?php echo date("F d, Y",get_post_meta($post->ID, 'wpcf-start-date', true));?></span>
        </li>

        <?php }
        echo "</ul>";
        wp_reset_query();
        wp_reset_postdata();

        echo $after_widget;
    }

    function update($new_instance, $old_instance) {
        $instance = $old_instance;
        $instance ['title'] = $new_instance ['title'];
        return $instance;
    }

    function form($instance) {
        $instance = wp_parse_args( (array) $instance, array( 'title' => '' ) );
        $title = $instance['title'];
        ?>
    <p><label for="<?php echo $this->get_field_id('title'); ?>">Title: <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo attribute_escape($title); ?>" /></label></p>
    <?php
    }
}

function keesings_register_widgets() {
	register_widget ( 'BreakingHistoryWidget' );
	register_widget ( 'dailyHistoryWidget' );
	register_widget ( 'RelatedPostByCountryWidget' );
	register_widget ( 'RelatedPostByPersonWidget' );
	register_widget ( 'RelatedPostForDailyHistoryWidget' );
    register_widget ( 'RelatedPostForBreakingIndepthHistoryWidget' );
}

add_action ( 'widgets_init', 'keesings_register_widgets' );

/*
 *
 * -----------------------------------------------------------------------------------
 */
/* Don't add anything below this line												 */
/*-----------------------------------------------------------------------------------*/

?>