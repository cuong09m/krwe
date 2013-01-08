<?php while (have_posts()) : the_post(); ?>
	<article <?php post_class() ?> id="post-<?php the_ID(); ?>">
		<header>
			<?php post_util_bar();?>
			<p>Keesing's Record of World Events (formerly Keesing's Contemporary Archives),<br/>
			<?php $terms = wp_get_post_terms( $post->ID, "volumeid"); ?>
<?php print_r($terms[0]->name);?>, <?php echo date("M, Y",get_post_meta($post->ID, 'wpcf-end-date', true));?> <?php echo get_post_meta($post->ID, 'wpcf-nation', true);?>, Page <?php echo get_post_meta($post->ID, 'wpcf-page-id', true);?><br/>
© 1931-2012 Keesing's Worldwide, LLC - All Rights Reserved. </p>
			<span class="nation"><a
			href="http://recordofworldevents.com/nation/<?php echo sanitize_title(get_post_meta($post->ID, 'wpcf-nation', true));?>"><?php echo get_post_meta($post->ID, 'wpcf-nation', true);?></a></span>
			<h1 class="entry-title"><?php the_title(); ?></h1>
			<?php //reverie_entry_meta(); ?>
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