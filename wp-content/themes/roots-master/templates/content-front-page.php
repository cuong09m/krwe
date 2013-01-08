<?php if (!have_posts()) : ?>
<div class="alert alert-block fade in">
	<a class="close" data-dismiss="alert">&times;</a>

	<p><?php _e('Sorry, no results were found.', 'roots'); ?></p>
</div>
<?php get_search_form(); ?>
<?php endif; ?>

<?php include (ABSPATH . '/wp-content/plugins/wp-content-slideshow/content-slideshow.php'); ?>

<div class="row browse-bar">
	<div class="span8">

		    <!-- <span><a href="/browse-all-nation">Browser all
			    nations</a>&nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;&nbsp;<a href="/browse-all-people">Browse all
                people</a></span> -->
        <form class="select_category_form"><?php wp_dropdown_categories('show_option_none=Browse by
        topics&orderby=name&exclude=68493');?></form>

		</select>
	</div>

</div>

<div class="row">
	<div class="span8">
        <?php get_home_article('Africa', 'Africa')?>
        <?php get_home_article('Americas', 'Americas')?>
	</div>
</div>
<div class="row">
	<div class="span8">
        <?php get_home_article('Asia', 'Asia')?>
        <?php get_home_article('Europe', 'Europe')?>
	</div>
</div>
<div class="row">
	<div class="span8">
        <?php get_home_article('Middle East', 'middle-east')?>
        <?php get_home_article('International', 'International')?>
	</div>
</div>
<div class="row">
	<div class="span8 bhslide">
		<h5><a href="/breaking-history">Breaking history in-depth <img src="<?php get_template_directory_uri()
        ?>/assets/img/icon2.png"/></a></h5>

		<div class="span">
			<ul id="mycarousel" class="jcarousel-skin-tango">
                <?php
                $the_query = new WP_Query(array('post_type' => 'breaking_history', 'posts_per_page' => 10));
                while ($the_query->have_posts()) {
                    $the_query->the_post();
                    ?>

					<li>
						<div class="mosaic-block bar2"><a href="<?php the_permalink(); ?>" target="_blank"
						                                  class="mosaic-overlay">
							<div class="details">
								<h4><?php the_title();?></h4>
							</div>
						</a>

							<div class="mosaic-backdrop"><?php echo get_the_post_thumbnail($the_query->post->ID,
                                'thumbnail14997');?></div>
						</div>
					</li>
                    <?php
                }
                ?>
			</ul>
		</div>
	</div>
</div>