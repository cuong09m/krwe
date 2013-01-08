<?php
/**
 * Scripts and stylesheets
 *
 * Enqueue stylesheets in the following order:
 * 1. /theme/assets/css/bootstrap.css
 * 2. /theme/assets/css/bootstrap-responsive.css
 * 3. /theme/assets/css/app.css
 * 4. /child-theme/style.css (if a child theme is activated)
 *
 * Enqueue scripts in the following order:
 * 1. /theme/assets/js/vendor/modernizr-2.6.2.min.js  (in head.php)
 * 2. jquery-1.8.2.min.js via Google CDN              (in head.php)
 * 3. /theme/assets/js/plugins.js
 * 4. /theme/assets/js/main.js
 */

function roots_scripts()
{
    wp_enqueue_style('roots_bootstrap', get_template_directory_uri() . '/assets/css/bootstrap.css', false, null);
    wp_enqueue_style('roots_bootstrap_responsive', get_template_directory_uri() . '/assets/css/bootstrap-responsive.css', array('roots_bootstrap'), null);
    wp_enqueue_style('roots_app', get_template_directory_uri() . '/assets/css/app.css', false, null);
    wp_enqueue_style('roots_jcarousel', get_template_directory_uri() . '/assets/css/jcarousel/skin.css', false, null);
    wp_enqueue_style('roots_mosaic', get_template_directory_uri() . '/assets/css/mosaic.css', false, null);
    wp_enqueue_style('roots_jqueryui', 'http://code.jquery.com/ui/1.9.0/themes/base/jquery-ui.css', false, null);
    wp_enqueue_style('roots_style', get_template_directory_uri() . '/assets/css/custom.css', false, null);

    // Load style.css from child theme
    if (is_child_theme()) {
        wp_enqueue_style('roots_child', get_stylesheet_uri(), false, null);
    }

    // jQuery is loaded in header.php using the same method from HTML5 Boilerplate:
    // Grab Google CDN's jQuery, with a protocol relative URL; fall back to local if offline
    // It's kept in the header instead of footer to avoid conflicts with plugins.
    if (!is_admin()) {
        wp_deregister_script('jquery');
        wp_register_script('jquery', '', '', '1.8.3', false);
    }

    if (is_single() && comments_open() && get_option('thread_comments')) {
        wp_enqueue_script('comment-reply');
    }

    wp_register_script('roots_plugins', get_template_directory_uri() . '/assets/js/plugins.js', false, null, false);
    wp_register_script('roots_main', get_template_directory_uri() . '/assets/js/main.js', false, null, false);
    wp_register_script('roots_jcarousel', get_template_directory_uri() . '/assets/js/vendor/jquery.jcarousel.js',false,null, false);
    wp_register_script('roots_mosaic', get_template_directory_uri() . '/assets/js/vendor/mosaic.1.0.1.js',false,
        null, false);
    wp_register_script('roots_monthpicker', get_template_directory_uri() .'/assets/js/jquery.ui.monthpicker.js',false,
        null, false);
    wp_register_script('roots_jqueryui', 'http://code.jquery.com/ui/1.9.0/jquery-ui.js',false,
        null, false);
    wp_enqueue_script('roots_plugins');
    wp_enqueue_script('roots_main');
    wp_enqueue_script('roots_jcarousel');
    wp_enqueue_script('roots_mosaic');
    wp_enqueue_script('roots_monthpicker');
    wp_enqueue_script('roots_jqueryui');
}

add_action('wp_enqueue_scripts', 'roots_scripts', 100);
