<?php
get_header();
?>
<div id="content">
			<div class="post hentry error404 not-found">
				<h1 class="entry-title">Oops, but the page you were looking for was not found</h1>
				<div class="entry-content">
Apologies, but the page you requested could not be found.
<?php 
		if(function_exists('trueGoogle404')){ echo "<br/><b>Here are some suggestions for the page you might be looking for:</b>";echo trueGoogle404(); }?>
<br/>If the page you wanted is not listed above, maybe it has been removed from this website or moved somewhere else on this website.<br/>Possibilities are that you might have done a typo error in the address or the page linking to us had a wrong address of the page you looked for.<br /><b>Perhaps searching or looking into the recent posts and posts by categories will help.</b>
					<?php get_search_form(); ?>
				</div><!-- .entry-content -->
			</div><!-- .post -->
		</div><!-- #content -->
<?php get_sidebar(); ?>
<?php get_footer(); ?>