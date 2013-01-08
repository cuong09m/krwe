<?php /* If there are no posts to display, such as an empty archive page */ ?>


<?php if (!have_posts()) : ?>
	<div class="notice">
		<p class="bottom"><?php _e('Sorry, no results were found.', 'reverie'); ?></p>
	</div>
	<?php get_search_form(); ?>	
<?php endif; ?>
<?php 
$parent_term =	$wp_query->queried_object;
get_all_daily_hitory_articles($parent_term->name, $parent_term->slug);
?>