<?php /* Start loop */ ?>
<?php while (have_posts()) : the_post(); ?>
<article <?php post_class() ?> id="post-<?php the_ID(); ?>">
	<header>
		<h1 class="entry-title"><?php the_title(); ?></h1>
		<span class="start_date"><?php echo date("F d, Y",get_post_meta($post->ID, 'wpcf-end-date', true));?></span><br />
		<span class="nation"><a
			href="http://recordofworldevents.com/nation/<?php echo str_replace(" ", "-",get_post_meta($post->ID, 'wpcf-nation', true));?>"><?php echo get_post_meta($post->ID, 'wpcf-nation', true);?></a></span>

	</header>
	<div class="entry-content">
		<?php 
			$view = $_GET['view'];
			if($view == 'full') the_content();
			else if($view == 'timeline') echo get_post_meta($post->ID, 'wpcf-timeline', true);
			else the_excerpt(); ?>
			<p></p>
		<ul>
			<?php if($view == 'full' || $view == 'timeline') {?><li><a href="<?php the_permalink();?>">Click here to read the intro (free)</a></li><?php }?>
			<?php if($view == '' || $view == 'timeline') {?><li><a href="<?php the_permalink();?>?view=full">Click here to read the full
					article (free)</a></li><?php }?>
			<?php if($view == '' || $view == 'full') {?><li><a href="<?php the_permalink();?>?view=timeline">Click here to browse the timeline (free)</a></li><?php }?>
			<li><a href="http://recordofworldevents.com/subscribe/">Click here to subscribe to Keesing's online, for unlimited access to our full archive and research features</a></li>
		</ul>
		<div class="navigation">
			<p><?php posts_nav_link('&#8734;','&laquo;&laquo; Previous article','Next article &raquo;&raquo;'); ?></p>
		</div>
	</div>
	<footer>
			<?php wp_link_pages(array('before' => '<nav id="page-nav"><p>' . __('Pages:', 'reverie'), 'after' => '</p></nav>' )); ?>
			<p><?php the_tags(); ?></p>
	</footer>
		<?php // comments_template(); ?>
	</article>
<?php endwhile; // End the loop ?>