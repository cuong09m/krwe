<?php get_header(); ?>

		<!-- Row for main content area -->
		<div id="content" class="eight columns" role="main">
			<div class="post-box reg-archive">
				<?php 
					if($_GET['type']=='daily_history') get_template_part('loop', 'regbh'); 
					elseif($_GET['type']=='breaking_history') get_template_part('loop', 'regbhindepth'); 
					else get_template_part('loop', 'reg'); 
				?>
			</div>

		</div><!-- End Content row -->
		
		<?php get_sidebar(); ?>
		
<?php get_footer(); ?>