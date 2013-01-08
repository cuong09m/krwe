<?php 
/**
 * @package Featured articles Lite - Wordpress plugin
 * @author CodeFlavors ( http://www.codeflavors.com )
 * @version 2.4
 */

include FA_dir('includes/custom_wp_list_table.php');

$sliders_table = new FA_List_Table(array(
	'singular'=>'slider', 
	'plural'=>'sliders'
));
// set columns to be displayed
$columns = array(
   	'post_title_s'	=> __('Title', 'falite'),
   	'ID'			=> __('Slider ID', 'falite'),
	'slider_content'=> __('Content', 'falite'),
	'slider_theme'	=> __('Slider theme', 'falite')
);
$sliders_table->columns = $columns;   
$sliders_table->per_page = 13;
// set sortable columns
$sortable_columns = array(
   	'post_title'     => array('post_title',false),     //true means its already sorted
   	'post_author'    => array('post_author',false),
   	'post_date'  	 => array('post_date',true)
);    
// set delete and edit pages
$sliders_table->edit_page = 'featured-articles-pro';

$sliders_table->bulk_actions = false;
$sliders_table->sortable_columns = $sortable_columns;
// get the records from DB
$sliders_table->prepare_items('fa_slider');
// styling and scripts
wp_enqueue_style('FA_add_content', FA_path('styles/add_content_modal.css'));
wp_enqueue_script('FA_sliders_shortcode', FA_path('scripts/admin/admin_shortcode.js'), array('jquery'));

// output iframe header
iframe_header(__('Slideshows', 'falite'));
?>
<div class="wrap">
	<div class="icon32 icon32-posts-page" id="icon-edit"><br></div>
    <h2><?php _e('Select slideshow', 'falite');?></h2>
    <?php $sliders_table->display();?>
</div>
<?php 
	// output iframe footer
	iframe_footer();
?>
<?php die();?>    