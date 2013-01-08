<?php /* If there are no posts to display, such as an empty archive page */ ?>
<?php if (!have_posts()) : ?>
<div class="notice">
    <p class="bottom"><?php _e('Sorry, no results were found.', 'reverie'); ?></p>
</div>
<?php get_search_form(); ?>
<?php endif; ?>
<?php
$parent_term = $wp_query->queried_object;
$terms = get_term_children($parent_term->term_id, 'regions-nations');
$term_names = array();
foreach ($terms as $term_id) {
    $term = get_term_by('id', $term_id, 'regions-nations');
    $term_names[] = $term->name;
}
$i = 0;
foreach ($term_names as $term_name) {
//    $term = get_term_by('name', $term_name, 'nation');
//    $term2 = get_term_by('id', $term->term_id, 'nation');
//    if ($term) {
//        if ($i % 2 == 0) get_articles($term->term_id, 'float:left;clear:left;');
//        else get_articles($term->term_id);
//        $i++;
//    }
    get_articles($term_name);
}
?>