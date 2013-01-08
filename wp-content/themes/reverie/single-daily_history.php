<?php get_header(); ?>

		<!-- Row for main content area -->
		<div id="content" class="eight columns dailyhistory" role="main">
	
			<div class="post-box">
			
				<?php get_template_part('loop', 'daily_history'); ?>
			</div>

		</div><!-- End Content row -->
		
		<?php get_sidebar(); ?>
		
<?php get_footer(); ?>