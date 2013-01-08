<?php get_header(); ?>

		<!-- Row for main content area -->
		<div id="content" class="eight columns" role="main">
	
			<div class="post-box">
				<h1><?php _e('An Error Has Occurred', 'reverie'); ?></h1>
				<div class="error">
<!--					<p class="bottom">--><?php //_e('We\'re sorry; an error has occurred and we could not complete your request.
//Please contact our Customer Service Department at info@keesings.com.', 'reverie'); ?><!--</p>-->
                    <?php if(function_exists("trueGoogle404"))echo trueGoogle404();?>
				</div>
			</div>

		</div><!-- End Content row -->
		
		<?php get_sidebar(); ?>
		
<?php get_footer(); ?>