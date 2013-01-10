<?php get_template_part('templates/head'); ?>

<?php
	$is_google_bot = false;
	if ((strstr($_SERVER['HTTP_USER_AGENT'], "Googlebot")) == true) {
		$ip = $_SERVER['REMOTE_ADDR'];

		$name = gethostbyaddr($ip);
		$host = gethostbyname($name);
		if (strstr($name, "googlebot.com") == true) {
			if ($ip == $host) {
				$is_google_bot = true;
			}
		}
	}
	$ref = $_SERVER['HTTP_REFERER'];

if (current_user_can("access_s2member_level1") || $is_google_bot == true || in_category('free') || get_post_type($post)=="breaking_history"  || get_post_type($post)=="daily_history") {
?>

<body <?php body_class(); ?>>

<!--[if lt IE 7]><div class="alert">Your browser is <em>ancient!</em> <a href="http://browsehappy.com/">Upgrade to a different browser</a> or <a href="http://www.google.com/chromeframe/?redirect=true">install Google Chrome Frame</a> to experience this site.</div><![endif]-->

	<?php
	// Use Bootstrap's navbar if enabled in config.php
	if (current_theme_supports('bootstrap-top-navbar')) {
		get_template_part('templates/header-top-navbar');
	} else {
		get_template_part('templates/header');
	}
	?>
<div id="wrap" class="container" role="document">
    <div id="content" class="row">
        <div id="main" class="<?php echo roots_main_class(); ?>" role="main">
			<?php include roots_template_path(); ?>
        </div>
		<?php if (roots_display_sidebar()) : ?>
        <aside id="sidebar" class="<?php echo roots_sidebar_class(); ?>" role="complementary">
			<?php get_template_part('templates/sidebar'); ?>
        </aside>
		<?php endif; ?>
    </div><!-- /#content -->
</div><!-- /#wrap -->

	<?php get_template_part('templates/footer'); ?>

</body>
</html>

<?php
} else {
	?>
<body <?php body_class(); ?>>
<!--[if lt IE 7]><div class="alert">Your browser is <em>ancient!</em> <a href="http://browsehappy.com/">Upgrade to a different browser</a> or <a href="http://www.google.com/chromeframe/?redirect=true">install Google Chrome Frame</a> to experience this site.</div><![endif]-->

<div id="wrap" class="container" role="document">
    <div id="content" class="row">
		<?php include roots_template_path(); ?>
    </div><!-- /#content -->
</div><!-- /#wrap -->

	<?php get_template_part('templates/footer'); ?>

</body>
</html>



<?php }?>