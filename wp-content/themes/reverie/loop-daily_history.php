<?php /* Start loop */ ?>
<?php while (have_posts()) : the_post(); ?>
	<article <?php post_class() ?> id="post-<?php the_ID(); ?>">
		<header>
			<h1>Breaking history</h1>
			<h1>Current events in historical context</h1>
			<!-- <?php the_date('F d, Y', '<h3>', '</h3>'); ?>-->
			 <span class="start_date"><?php echo date("F d, Y",get_post_meta($post->ID, 'wpcf-end-date', true));?></span><br/>
			<span class="nation"><a
			href="http://recordofworldevents.com/nation/<?php echo str_replace(" ", "-",get_post_meta($post->ID, 'wpcf-nation', true));?>"><?php echo get_post_meta($post->ID, 'wpcf-nation', true);?></a></span>
			
		</header>
		<div class="entry-content">
			<?php the_content(); ?>
			<div class="navigation"><p><?php posts_nav_link('&#8734;','&laquo;&laquo; Previous article','Next article &raquo;&raquo;'); ?></p></div>
		</div>
		<footer>
			<?php wp_link_pages(array('before' => '<nav id="page-nav"><p>' . __('Pages:', 'reverie'), 'after' => '</p></nav>' )); ?>
			<p><?php the_tags(); ?></p>
		</footer>
		<?php // comments_template(); ?>
	</article>
<?php endwhile; // End the loop ?>