<?php

// Custom functions

register_sidebar(array('name' => 'Top header adv', 'before_widget' => '', 'after_widget' => '', 'before_title' => '', 'after_title' => ''));
register_nav_menus(array('primary_navigation' => __('Primary Navigation', 'reverie'), 'utility_navigation' => __('Utility Navigation', 'reverie'), 'header_navigation' => __('Header Navigation', 'reverie')));

add_image_size('thumbnail14997', 149, 97, true);
add_image_size('thumbnail98', 98, 98, true);

function custom_excerpt_length($length)
{
    return 30;
}

function new_excerpt_more($more)
{
    global $post;
    return ' <a href="' . get_permalink($post->ID) . '">more...</a>';
}

function excerpt($limit, $more = true)
{
    global $post;
    $excerpt = explode(' ', get_the_content(), $limit);
    if (count($excerpt) >= $limit) {
        array_pop($excerpt);
        if ($more) $excerpt = strip_tags(implode(" ", $excerpt)) . ' <a href="' . get_permalink($post->ID) . '">more..
        .</a>';
        else $excerpt = strip_tags(implode(" ", $excerpt));
    } else {
        $excerpt = strip_tags(implode(" ", $excerpt));
    }
    $excerpt = preg_replace('`\[[^\]]*\]`', '', $excerpt);
    return $excerpt;
}

function content($limit)
{
    $content = explode(' ', get_the_content(), $limit);
    if (count($content) >= $limit) {
        array_pop($content);
        $content = implode(" ", $content) . '...';
    } else {
        $content = implode(" ", $content);
    }
    $content = preg_replace('/\[.+\]/', '', $content);
    $content = apply_filters('the_content', $content);
    $content = str_replace(']]>', ']]&gt;', $content);
    return $content;
}

add_filter('excerpt_more', 'new_excerpt_more');
add_filter('excerpt_length', 'custom_excerpt_length', 999);

function get_home_article($name, $slug)
{
    ?>

<div class="span4 homebox">
	<h5 class="region"><a href="<?php echo get_home_url();?>/reg/<?php echo $slug;?>"><?php echo $name;?>
		<img src="<?php get_template_directory_uri()?>/assets/img/icon2.png"/></a></h5>

	<div class="span">
		<div>
            <?php
            $the_query = new WP_Query (array('tax_query' => array(array('taxonomy' => 'reg', 'field' => 'slug',
                'terms' => $slug)), 'posts_per_page' => 5, 'post_type' => 'post', meta_key => 'wpcf-start-date', orderby => 'meta_value_num', order => 'DESC'));
            $i = 0;
            while ($the_query->have_posts()) {
                $the_query->the_post();
                // Display first featured post
                ?>
                <?php if ($i == 1) { ?><ul><li><?php } ?>
                <?php if ($i > 1) { ?><li><?php } ?>
				<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                    <?php if (get_post_meta($the_query->post->ID, 'wpcf-nation', true) != '') { ?><span
						class="nation"><a
						href="http://recordofworldevents.com/nation/<?php echo str_replace(" ", "-", get_post_meta($the_query->post->ID, 'wpcf-nation', true));?>"><?php echo get_post_meta($the_query->post->ID, 'wpcf-nation', true);?></a></span>
					:<?php }?>
					<span class="title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></span>
                    <?php if ($i == 0) {
                    echo '<br/>' . excerpt(25);
                }?>
				</article>

                <?php if ($i > 1) { ?></li><?php } ?>
                <?php
                $i++;
            }
            ?>
		</ul>
		</div>
		<div class="viewmore">
			<a href="<?php echo get_home_url();?>/reg/<?php echo $slug;?>">View more</a>
		</div>
	</div>
</div>
<?php
}

function get_region_articles($term_name, $class)
{
    $the_query = new WP_Query(array('post_type' => 'post', 'posts_per_page' => 5, 'meta_query' => array(array('key' => 'wpcf-nation', 'value' => $term_name, 'compare' => '=')), meta_key => 'wpcf-start-date', orderby => 'meta_value_num', 'paged' => $paged));
    $i = 0;
    if ($the_query->post_count > 0) {
        ?>
	<div class="span4 regionbox">
		<h5 class="region"><a href="<?php echo get_home_url();?>/nation/<?php echo $term_name;?>"><?php echo
        $term_name;?>
			<img src="<?php get_template_directory_uri()?>/assets/img/icon2.png"/></a></h5>

		<div class="span">
			<div>
                <?php
                while ($the_query->have_posts()) {
                    $the_query->the_post();
                    // Display first featured post
                    ?>
                    <?php if ($i == 1) { ?><ul><li><?php } ?>
                    <?php if ($i > 1) { ?><li><?php } ?>
					<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
						<span class="title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></span>
                        <?php if ($i == 0) {
                        echo '<br/>' . excerpt(25);
                    }?>
					</article>

                    <?php if ($i > 1) { ?></li><?php } ?>
                    <?php
                    $i++;
                }
                ?>
			</ul>
			</div>
		</div>
	</div>
    <?php
        return true;
    } else return false;
}

function be_taxonomy_breadcrumb()
{
    //Display home page
    echo '<span class="breadcrumb"><a href="/">Home&nbsp;&nbsp;</a>';
// Get the current term
    $term = get_term_by('slug', get_query_var('term'), get_query_var('taxonomy'));

// Create a list of all the term's parents
    $parent = $term->parent;
    while ($parent):
        $parents[] = $parent;
        $new_parent = get_term_by('id', $parent, get_query_var('taxonomy'));
        $parent = $new_parent->parent;
    endwhile;
    if (!empty($parents)):
        $parents = array_reverse($parents);

// For each parent, create a breadcrumb item
        foreach ($parents as $parent):
            $item = get_term_by('id', $parent, get_query_var('taxonomy'));
            $url = get_bloginfo('url') . '/' . $item->taxonomy . '/' . $item->slug;
            echo '<a href="' . $url . '">' . $item->name . '</a>';
        endforeach;
    endif;

// Display the current term in the breadcrumb
    if($_GET['type']=='breaking_history') echo '|&nbsp;&nbsp;' . $term->name . ': Breaking History In-depth</span>';
    elseif($_GET['type']=='daily_history') echo '|&nbsp;&nbsp;' . $term->name . ': Breaking History </span>';
    else echo '|&nbsp;&nbsp;' . $term->name . ' </span>';
}

function custom_wpquery($query)
{
    // the main query
    global $wp_the_query;
    if ($query->is_archive) {
        if ($wp_the_query === $query) {
            if ($query->query_vars['nation']) {
                $year = $query->query_vars['year'];
                $meta_query = array();
                $meta_query['relation']='AND';
                $nation_term_name = $query->query_vars['nation'];
                $meta_query[]=array('key' => 'wpcf-nation',
                    'value' => $nation_term_name, 'compare' => '=');
                if($year) {
                    $query->query_vars['year'] = '';
                    $start_range = strtotime($year . '-01-01');
                    $end_range = strtotime($year . '-12-31');
                    $meta_query[] = array('key' => 'wpcf-end-date',
                        'value' => array($start_range, $end_range), 'type' => 'numeric', 'compare' => 'BETWEEN');

                }

                $query->query_vars['meta_query'] = $meta_query;
            }
            elseif ($query->query_vars['reg'] && $_GET['type']) {
                $query->query_vars['post_type']=$_GET['type'];
            }

        }
    }
}

add_filter('pre_get_posts', 'custom_wpquery');

function get_breaking_hitory_articles($name, $slug) {
    ?>
<div class="span4 regionbox">
    <h5 class="region"><a href="http://recordofworldevents.com/reg/<?php echo $slug;?>?type=breaking_history"><?php echo $name;?></a></h5>
    <div class="span">
            <div>
        <?php
    $the_query = new WP_Query (array ('tax_query' => array (array ('taxonomy' => 'reg', 'field' => 'slug', 'terms' => $slug ) ), 'posts_per_page' => 6,
            'post_type' => 'breaking_history',meta_key=>'wpcf-start-date',orderby=>'meta_value_num') );
    $i=0;
    while ( $the_query->have_posts () ) {
        $the_query->the_post ();
         // Display first featured post
        ?>
            <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                <?php if(get_post_meta($the_query->post->ID, 'wpcf-nation', true)!='') {?><span class="nation"><a
                    href="http://recordofworldevents.com/nation/<?php echo str_replace(" ", "-",get_post_meta($the_query->post->ID, 'wpcf-nation', true));?>"><?php echo get_post_meta($the_query->post->ID, 'wpcf-nation', true);?></a></span>:<?php }?>
                <span class="title">
                    <a href="<?php the_permalink(); ?>"><?php the_title(); ?> (pub <span class="start_date"><?php echo date("M d, Y",get_post_meta($the_query->post->ID, 'wpcf-start-date', true));?></span>)</a>
                </span>
            </article>
            <?php
    }
    ?>
    </div>
    </div>
    <article><a href="http://recordofworldevents.com/reg/<?php echo $slug;?>?type=breaking_history">More...</a></article>
        </div>
<?php
}

function get_all_breaking_hitory_articles($name, $slug) {
    ?>
<div class="twelve columns breaking_history_full">
    <h1>Breaking history:</h1>
    <h1>In-depth special reports</h1>
    <span class="region"><a href="http://recordofworldevents.com/reg/<?php echo $name;?>?type=breaking_history"><?php echo $name;?></a></span>
        <?php
    $the_query = new WP_Query (array ('tax_query' => array (array ('taxonomy' => 'reg', 'field' => 'slug', 'terms' => $slug ) ),'post_type' => 'breaking_history' ) );
    $i=0;
    while ( $the_query->have_posts () ) {
        $the_query->the_post ();
         // Display first featured post
        ?>
            <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                <span class="nation"><a
                    href="http://recordofworldevents.com/nation/<?php echo str_replace(" ", "-",get_post_meta($the_query->post->ID, 'wpcf-nation', true));?>"><?php echo get_post_meta($the_query->post->ID, 'wpcf-nation', true);?></a></span>:
                <span class="title">
                    <a href="<?php the_permalink(); ?>"><?php the_title(); ?> (pub <span class="start_date"><?php echo date("M d, Y",get_post_meta($the_query->post->ID, 'wpcf-start-date', true));?></span>)</a>
                </span>
            </article>
            <?php
    }
    ?>
        </div>
<?php
}

function get_daily_hitory_articles($name, $slug) {
    ?>
<div class="span4 regionbox">
    <h5 class="region"><a href="http://recordofworldevents.com/reg/<?php echo $slug;?>?type=daily_history"><?php echo $name;?></a></h5>
    <div class="span">
            <div>
        <?php
    $the_query = new WP_Query (array ('tax_query' => array (array ('taxonomy' => 'reg', 'field' => 'slug', 'terms' => $slug ) ), 'posts_per_page' => 6,
            'post_type' => 'daily_history',meta_key=>'wpcf-start-date',orderby=>'meta_value_num') );
    $i=0;
    while ( $the_query->have_posts () ) {
        $the_query->the_post ();
         // Display first featured post
        ?>
            <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                <?php if(get_post_meta($the_query->post->ID, 'wpcf-nation', true)!='') {?><span class="nation"><a
                    href="http://recordofworldevents.com/nation/<?php echo str_replace(" ", "-",get_post_meta($the_query->post->ID, 'wpcf-nation', true));?>"><?php echo get_post_meta($the_query->post->ID, 'wpcf-nation', true);?></a></span>:<?php }?>
                <span class="title">
                    <a href="<?php the_permalink(); ?>"><?php the_title(); ?> (pub <span class="start_date"><?php echo date("M d, Y",get_post_meta($the_query->post->ID, 'wpcf-start-date', true));?></span>)</a>
                </span>
            </article>
            <?php
    }
    ?>
    </div>
    </div>
    <article><a href="http://recordofworldevents.com/reg/<?php echo $slug;?>?type=daily_history">More...</a></article>
        </div>
<?php
}

function get_all_daily_hitory_articles($name, $slug) {
    ?>
<div class="twelve columns breaking_history_full">
    <h1>Breaking history:</h1>
    <span class="region"><a href="http://recordofworldevents.com/reg/<?php echo $name;?>?type=daily_history"><?php echo $name;?></a></span>
        <?php
    $the_query = new WP_Query (array ('tax_query' => array (array ('taxonomy' => 'reg', 'field' => 'slug', 'terms' => $slug ) ),'post_type' => 'daily_history' ) );
    $i=0;
    while ( $the_query->have_posts () ) {
        $the_query->the_post ();
         // Display first featured post
        ?>
            <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                <span class="nation"><a
                    href="http://recordofworldevents.com/nation/<?php echo str_replace(" ", "-",get_post_meta($the_query->post->ID, 'wpcf-nation', true));?>"><?php echo get_post_meta($the_query->post->ID, 'wpcf-nation', true);?></a></span>:
                <span class="title">
                    <a href="<?php the_permalink(); ?>"><?php the_title(); ?> (pub <span class="start_date"><?php echo date("M d, Y",get_post_meta($the_query->post->ID, 'wpcf-start-date', true));?></span>)</a>
                </span>
            </article>
            <?php
    }
    ?>
        </div>
<?php
}

class BreakingHistoryWidget extends WP_Widget
{

    function BreakingHistoryWidget()
    {
        $widget_ops = array('classname' => 'breaking_history', 'description' => 'Displays first breaking history');
        $this->WP_Widget('BreakingHistoryWidget', 'Breaking history', $widget_ops);
    }

    function widget($args, $instance)
    {
        extract($args, EXTR_SKIP);

        echo $before_widget;
        $title = empty ($instance ['title']) ? ' ' : apply_filters('widget_title', $instance ['title']);

        if (!empty ($title))
            echo $before_title . $title . $after_title;
        ;

        $the_query = new WP_Query(array('post_type' => 'breaking_history', 'posts_per_page' => 3));
        ?>
	<div class="widget_body">
        <?php
        while ($the_query->have_posts()) {
            $the_query->the_post();
            ?>
			<article id="post-<?php the_ID(); ?>" <?php post_class('breaking-history'); ?>>
                <?php
                echo get_the_post_thumbnail($the_query->post->ID, 'thumbnail98');
                if (get_post_meta($the_query->post->ID, 'wpcf-nation', true)) {
                    ?>
					<span class="nation"><a
							href="http://recordofworldevents.com/nation/<?php echo str_replace(" ", "-",
                                get_post_meta($the_query->post->ID, 'wpcf-nation', true));?>"><?php echo get_post_meta
                    ($the_query->post->ID, 'wpcf-nation', true);?></a>: </span>
					<br/>
                    <?php }?>

				<span class="title"> <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
				</span>

                <?php echo excerpt(15, false);?>
			</article>

            <?php
        }
        ?>
		<span class="viewmore">
		        <a href="http://recordofworldevents.com/breaking_history">View more</a>
	        </span>
	</div>
    <?php
        echo $after_widget;
    }

    function update($new_instance, $old_instance)
    {
        $instance = $old_instance;
        $instance ['title'] = $new_instance ['title'];
        return $instance;
    }

    function form($instance)
    {
        $instance = wp_parse_args((array)$instance, array('title' => ''));
        $title = $instance['title'];
        ?>
	<p><label for="<?php echo $this->get_field_id('title'); ?>">Title: <input class="widefat"
	                                                                          id="<?php echo $this->get_field_id('title'); ?>"
	                                                                          name="<?php echo $this->get_field_name('title'); ?>"
	                                                                          type="text"
	                                                                          value="<?php echo attribute_escape($title); ?>"/></label>
	</p>
    <?php
    }
}

class DailyHistoryWidget extends WP_Widget
{

    function DailyHistoryWidget()
    {
        $widget_ops = array('classname' => 'daily_history', 'description' => 'Displays first daily history');
        $this->WP_Widget('DailyHistoryWidget', 'Daily history', $widget_ops);
    }

    function widget($args, $instance)
    {
        extract($args, EXTR_SKIP);

        echo $before_widget;
        $title = empty ($instance ['title']) ? ' ' : apply_filters('widget_title', $instance ['title']);

        if (!empty ($title))
            echo $before_title . $title . $after_title;
        ;

        $the_query = new WP_Query(array('post_type' => 'daily_history', 'posts_per_page' => 3));
        ?>
	<div class="widget_body">
        <?php
        while ($the_query->have_posts()) {
            $the_query->the_post();
            ?>
			<article id="post-<?php the_ID(); ?>" <?php post_class('breaking-history'); ?>>
                <?php
                echo get_the_post_thumbnail($the_query->post->ID, 'thumbnail98');
                if (get_post_meta($the_query->post->ID, 'wpcf-nation', true)) {
                    ?>
					<span class="nation"><a
							href="http://recordofworldevents.com/nation/<?php echo str_replace(" ", "-",
                                get_post_meta($the_query->post->ID, 'wpcf-nation', true));?>"><?php echo get_post_meta
                    ($the_query->post->ID, 'wpcf-nation', true);?></a>: </span>
					<br/>
                    <?php }?>
				<span class="title"> <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
				</span>

                <?php echo excerpt(15, false);?>
			</article>

            <?php
        }
        ?>
		<span class="viewmore">
		        <a href="http://recordofworldevents.com/daily_history">View more</a>
	        </span>
	</div>
    <?php
        echo $after_widget;
    }

    function update($new_instance, $old_instance)
    {
        $instance = $old_instance;
        $instance ['title'] = $new_instance ['title'];
        return $instance;
    }

    function form($instance)
    {
        $instance = wp_parse_args((array)$instance, array('title' => ''));
        $title = $instance['title'];
        ?>
	<p><label for="<?php echo $this->get_field_id('title'); ?>">Title: <input class="widefat"
	                                                                          id="<?php echo $this->get_field_id('title'); ?>"
	                                                                          name="<?php echo $this->get_field_name('title'); ?>"
	                                                                          type="text"
	                                                                          value="<?php echo attribute_escape($title); ?>"/></label>
	</p>
    <?php
    }
}

function keesings_register_widgets()
{
    register_widget('BreakingHistoryWidget');
    register_widget('dailyHistoryWidget');
}

add_action('widgets_init', 'keesings_register_widgets');