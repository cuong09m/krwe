<?php
$parent_term = $wp_query->queried_object;
$terms = get_term_children($parent_term->term_id, 'regions-nations');
$term_names = array();
foreach ($terms as $term_id) {
    $term = get_term_by('id', $term_id, 'regions-nations');
    $term_names[] = $term->name;
}
$i = 0;
asort($term_names);
echo '<div class="row"><div class="span8">';
foreach ($term_names as $term_name) {
    if($i %2==0) echo '</div></div><div class="row"><div class="span8">';
    if(get_region_articles($term_name)) $i++;
}
echo '</div></div>';
?>