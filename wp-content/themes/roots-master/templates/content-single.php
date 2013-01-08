<?php while (have_posts()) : the_post(); ?>
  <article <?php post_class() ?> id="post-<?php the_ID(); ?>">
	  <p>Keesing's Record of World Events (formerly Keesing's Contemporary Archives),<br/>
          <?php $terms = wp_get_post_terms( $post->ID, "volumeid"); ?>
          <?php print_r($terms[0]->name);?>, <?php echo date("M, Y",get_post_meta($post->ID, 'wpcf-end-date', true));?> <?php echo get_post_meta($post->ID, 'wpcf-nation', true);?>, Page <?php echo get_post_meta($post->ID, 'wpcf-page-id', true);?><br/>
		  © 1931-2012 Keesing's Worldwide, LLC - All Rights Reserved. </p>  
    <header>

        <h1><a
		        href="http://recordofworldevents.com/nation/<?php echo sanitize_title(get_post_meta($post->ID,
                    'wpcf-nation', true));?>"><?php echo get_post_meta($post->ID, 'wpcf-nation', true);?></a></h1>
      <h2 class="entry-title"><?php the_title(); ?></h2>
      <?php get_template_part('templates/entry-meta'); ?>
    </header>
    <div class="entry-content">
      <?php the_content(); ?>
    </div>
    <footer>
      <?php wp_link_pages(array('before' => '<nav id="page-nav"><p>' . __('Pages:', 'roots'), 'after' => '</p></nav>')); ?>
      <?php the_tags('<ul class="entry-tags"><li>','</li><li>','</li></ul>'); ?>
    </footer>
  </article>
<?php endwhile; ?>
