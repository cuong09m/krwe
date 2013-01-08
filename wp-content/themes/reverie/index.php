<?php get_header(); ?>

<!-- Row for main content area -->
<div id="content" class="eight columns" role="main">
	<div class="featured-box row">
        <?php
        if( function_exists('FA_display_slider') ){
            FA_display_slider(100000205);
        }
        ?>
	</div>
	<hr style="background-color: #cbd5e2;height: 2px;border: none;margin: 0px;"/>
	<hr style="background-color: #000;height: 10px;border: none;margin: 4px 0px 0px 0px;"/>
	<div class="row">
		<?php get_home_article('Africa', 'Africa')?>
		<?php get_home_article('Americas', 'Americas')?>
	</div>
	<div class="row">
		<?php get_home_article('Asia', 'Asia')?>
		<?php get_home_article('Europe', 'Europe')?>
	</div>
	<div class="row">
		<?php get_home_article('Middle East', 'middle-east')?>
		<?php get_home_article('International', 'International')?>
	</div>
</div>
<!-- End Content row -->

<?php get_sidebar(); ?>
		
<?php get_footer(); ?>