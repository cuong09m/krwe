<?php 
/**
 * @package Featured articles Lite - Wordpress plugin
 * @author CodeFlavors ( http://www.codeflavors.com )
 * @version 2.4
 */

include FA_dir('includes/custom_wp_list_table.php');


$pages_table = new FA_List_Table(array(
	'singular'=>'page', 
	'plural'=>'pages'
));
// set columns to be displayed
// set columns to be displayed
$columns = array(
   	'cb'        		=> '', //Render a checkbox instead of text
   	'post_title_lbl'	=> __('Title', 'falite'),
    'post_author'   	=> __('Author', 'falite'),
   	'post_date'  		=> __('Date', 'falite')
   );
$pages_table->columns = $columns; 


$pages_table->per_page = 13;
// set sortable columns
$sortable_columns = array(
   	'post_title_lbl'=> array('post_title', true),
	'post_author'	=> array('post_author', false),
	'post_date'		=> array('post_date', false)
);    
// set delete and edit pages
$pages_table->edit_page = '#';

$pages_table->bulk_actions = false;
$pages_table->sortable_columns = $sortable_columns;
// get the records from DB
$pages_table->prepare_items('page');
// styling and scripts
// styling and scripts
wp_enqueue_style(
	'FA_add_content', 
	FA_path('styles/add_content_modal.css')
);
wp_enqueue_script(
	'FA_content_add_script', 
	FA_path('scripts/admin/display_control_modal.js'), 
	array('jquery')
);

// output iframe header
iframe_header(__('Pages', 'falite'));
?>
<div class="wrap">
	<div class="icon32 icon32-posts-page" id="icon-edit"><br></div>
    <h2><?php _e('Select pages', 'falite');?></h2>
    <?php $pages_table->display();?>
    <input class="button-primary" value="<?php _e('Close window', '');?>" id="close_window" type="button" />
</div>
<script language="javascript" type="text/javascript">
	var FA_parent_item = '#pages_display';
	var FA_item_id_prefix = 'FA_page_display_';
	var FA_fields_prefix = 'page_display';
</script>
<?php 
	// output iframe footer
	iframe_footer();
?>
<?php die();?>    