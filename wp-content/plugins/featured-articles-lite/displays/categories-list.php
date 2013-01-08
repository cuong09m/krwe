<?php 
/**
 * @package Featured articles Lite - Wordpress plugin
 * @author CodeFlavors ( http://www.codeflavors.com )
 * @version 2.4
 */

include FA_dir('includes/class-fa-categories-list-table.php');

$categories_table = new FA_Categories_List_Table();
// get the records from DB
$categories_table->prepare_items();
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
iframe_header(__('Categories', 'falite'));
?>
<div class="wrap">
	<div class="icon32 icon32-posts-page" id="icon-edit"><br></div>
    <h2><?php _e('Select categories', 'falite');?></h2>
    <?php $categories_table->views();?>
    <?php $categories_table->display();?>
    <input class="button-primary" value="<?php _e('Close window', '');?>" id="close_window" type="button" />
</div>
<script language="javascript" type="text/javascript">
	var FA_parent_item = '#categories_display';
	var FA_item_id_prefix = 'FA_category_display_';
	var FA_fields_prefix = 'categ_display';
</script>
<?php 
	// output iframe footer
	iframe_footer();
?>
<?php die();?>    