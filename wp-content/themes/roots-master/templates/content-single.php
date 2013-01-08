<?php while (have_posts()) : the_post(); ?>
  <article <?php post_class() ?> id="post-<?php the_ID(); ?>">
	  <p>Keesing's Record of World Events (formerly Keesing's Contemporary Archives),<br/>
          <?php $terms = wp_get_post_terms( $post->ID, "volumeid"); ?>
          <?php print_r($terms[0]->name);?>, <?php echo date("M, Y",get_post_meta($post->ID, 'wpcf-end-date', true));?> <?php echo get_post_meta($post->ID, 'wpcf-nation', true);?>, Page <?php echo get_post_meta($post->ID, 'wpcf-page-id', true);?><br/>
		  © 1931-2012 Keesing's Worldwide, LLC - All Rights Reserved. </p>  
    <header>

        <h1><?php if(get_post_type( $post->ID )=='breaking_history') echo 'Breaking history In-depth: '; elseif(get_post_type( $post->ID )=='daily_history') echo 'Breaking history: ';?><a
		        href="http://recordofworldevents.com/nation/<?php echo sanitize_title(get_post_meta($post->ID,
                    'wpcf-nation', true));?>"><?php echo get_post_meta($post->ID, 'wpcf-nation', true);?></a></h1>
      <h2 class="entry-title"><?php the_title(); ?></h2>
      <?php get_template_part('templates/entry-meta'); ?>
    </header>
    <div class="entry-content">
      <?php 
      if(get_post_type( $post->ID )=='breaking_history') {
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
    <?php } else { ?>
      <?php the_content(); ?>
      <?php } ?>
    </div>
    <footer>
      <?php wp_link_pages(array('before' => '<nav id="page-nav"><p>' . __('Pages:', 'roots'), 'after' => '</p></nav>')); ?>
      <?php the_tags('<ul class="entry-tags"><li>','</li><li>','</li></ul>'); ?>
    </footer>
  </article>
<?php endwhile; ?>
