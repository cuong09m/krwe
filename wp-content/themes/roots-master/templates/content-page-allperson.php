<?php
$args = array( 'orderby' => 'name' );

$terms = get_terms('person', $args);

$count = count($terms); $i=0;
if ($count > 0) {
    $cape_list = '<p class="my_term-archive">';
    $current_letter='';
    foreach ($terms as $term) {
        $first_letter=strtoupper(substr($term->name,0,1));
        if($first_letter!=$current_letter) {
            $term_list.= '<br/>'.$first_letter.'<br/>';
            $current_letter=$first_letter;
        }
        $i++;
        $term_list .= '<a href="/nation/' . $term->slug . '" title="' . sprintf(__('View all post filed under %s',
            'my_localization_domain'), $term->name) . '">' . $term->name . '</a>';
        if ($count != $i) $term_list .= ' &middot; '; else $term_list .= '</p>';
    }
    echo $term_list;
}
?>