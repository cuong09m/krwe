<?php get_template_part('templates/page', 'header-taxonomy'); ?>
<?php
if($_GET['type']) get_template_part('templates/content','');
else get_template_part('templates/content','reg');
?>