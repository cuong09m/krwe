<header id="banner" role="banner">
	<div class="container">
		<div id="top-header">
            <?php dynamic_sidebar("Top header adv"); ?>
		</div>

		<div id="header">
			<div id="logo">
				<h1>
					<a href="<?php bloginfo('url'); ?>"
					   title="<?php bloginfo('name'); ?>"><img
							src="<?php bloginfo('template_url'); ?>/assets/img/logo.png"
							alt="<?php bloginfo('name'); ?>"/></a>
				</h1>
			</div>

            <?php
				wp_nav_menu ( array ('theme_location' => 'header_navigation','menu_class' => 'nav nav-pills'));
            ?>

			<div id="search_header">
				<form action="/">

					<input type="text" value="" name="s" id="s" placeholder="Search">
					<img src="<?php get_template_directory_uri()?>/assets/img/searchicon.png"/>
					<p>
						<img src="<?php get_template_directory_uri()?>/assets/img/icon4.png"/><a href="/?s=">Advanced
                        search</a></p>

				</form>

			</div>

            <div id="social_header">
	            Follow us:
                <a href="https://www.facebook.com/Keesings" target="_blank"><img src="<?php get_template_directory_uri()
            ?>/assets/img/Facebook_24.png"/></a>
	            <a href="https://plus.google.com/106387150410012373000" target="_blank"><img src="<?php get_template_directory_uri()
                ?>/assets/img/Google_64.png"/></a>
	            <a href="https://twitter.com/Keesings" target="_blank"><img src="<?php get_template_directory_uri()
                ?>/assets/img/Twitter_24.png"/></a>
	            <a href="http://www.linkedin.com/company/keesing%27s" target="_blank"><img src="<?php get_template_directory_uri()
                ?>/assets/img/Linkedin_24.png"/></a>
	            <a href="http://recordofworldevents.com/feed" target="_blank"><img src="<?php get_template_directory_uri()
                ?>/assets/img/Rss_24.png"/></a>
	            <a href="mailto:info@keesings.com" target="_blank"><img src="<?php get_template_directory_uri()
                ?>/assets/img/Mail_24.png"/></a>
            </div>

		</div>

		<div id="nav-main" role="navigation">
            <?php
            if (has_nav_menu('primary_navigation')) :
                wp_nav_menu(array('theme_location' => 'primary_navigation', 'menu_class' => 'nav nav-pills'));
            endif;
            ?>
		</div>

	</div>
</header>