<div id="wrap" class="footer_menu">
<div class="container">
<div id="nav-main" role="navigation" >
    <?php
    if (has_nav_menu('primary_navigation')) :
        wp_nav_menu(array('theme_location' => 'primary_navigation', 'menu_class' => 'nav nav-pills'));
    endif;
    ?>
</div>
</div>
</div>
<div id="wrap" class="footer_wrap"><footer id="content-info" class="container" role="contentinfo">
  <?php dynamic_sidebar('sidebar-footer'); ?>
  <p>&copy;Copyright 1987-<?php echo date('Y'); ?> Keesing’s Worldwide, LLC | <a href="http://recordofworldevents.com/terms-and-conditions-of-use/">Terms and Conditions of Use</a> |  <a href="http://recordofworldevents.com/terms-of-sale/">Terms of Sale</a> | <a href="http://recordofworldevents.com/privacy-policy/">Privacy Policy</a></p>
</footer>
</div>
<?php if (GOOGLE_ANALYTICS_ID) : ?>
<script>
  var _gaq=[['_setAccount','<?php echo GOOGLE_ANALYTICS_ID; ?>'],['_trackPageview']];
  (function(d,t){var g=d.createElement(t),s=d.getElementsByTagName(t)[0];
    g.src=('https:'==location.protocol?'//ssl':'//www')+'.google-analytics.com/ga.js';
    s.parentNode.insertBefore(g,s)}(document,'script'));
</script>
<?php endif; ?>

<?php wp_footer(); ?>
