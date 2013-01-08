<time class="updated" datetime="<?php echo get_the_time('c'); ?>"
      pubdate>
      <?php 
      	if(get_post_meta($post->ID, 'wpcf-end-date', true)) echo sprintf(__('%s.', 'roots'), date("M, Y",get_post_meta($post->ID, 'wpcf-end-date', true))); 
      	elseif(get_post_meta($post->ID, 'wpcf-start-date', true)) echo sprintf(__('%s.', 'roots'), date("M, Y",get_post_meta($post->ID, 'wpcf-start-date', true))); 
      	else echo sprintf(__('%s.', 'roots'), get_the_time('M, Y'));
      	?>
  </time>
