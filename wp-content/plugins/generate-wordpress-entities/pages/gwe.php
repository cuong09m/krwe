<?php
require_once(dirname(__FILE__) . '/../functions.php');
function gwe_manage_menu() {
$msg = '';
if (wp_verify_nonce($_POST['gwe_nonce'], basename(__FILE__))) {
	if(isset($_POST['delete'])) {
		if(isset($_POST['gwe-categories'])) gwe_delete_categories();
		if(isset($_POST['gwe-pages'])) gwe_delete_pages();
		if(isset($_POST['gwe-posts'])) gwe_delete_posts();
		$msg = '<div id="message" class="updated below-h2"><p>Selected entities was deleted</p></div>';
	}
	else {
		if(isset($_POST['gwe-categories'])) gwe_generate_categories();
		if(isset($_POST['gwe-pages'])) gwe_generate_pages();
		if(isset($_POST['gwe-posts'])) gwe_generate_posts();
		$msg = '<div id="message" class="updated below-h2"><p>Selected entities was generated</p></div>';
	}
}
?>
<script type="text/javascript">
	jQuery(document).ready(function($) {
		$('#gwe-form input:text, #gwe-form select').width('99%');				
	});
</script>
<div class="wrap">
	<form id="gwe-form" name="gwe-form" method="post" action="admin.php?page=gwe">
		<div id="poststuff">
			<h2>Generate Wordpress Entities</h2>
			<?php echo $msg?>
			<div class="meta-box-sortables ui-sortable" id="normal-sortables">
			
			
			<div class="postbox">
				<div title="Click to toggle" class="handlediv"><br></div>
				<h3 class="hndle"><span>Parameters</span></h3>
				<div class="inside">
					<p>						
						<label><input name="gwe-pages" id="gwe-pages" checked="checked" value="1" type="checkbox"> Pages</label>
					</p>
					<p>
						<label><input name="gwe-posts" id="gwe-posts" checked="checked" value="1" type="checkbox"> Posts</label>
					<p>
						<label><input name="gwe-categories" id="gwe-categories" checked="checked" value="1" type="checkbox"> Categories</label>
					</p>
				</div>
			</div>
			
			
			</div>
			
			<input type="hidden" name="gwe_nonce" value="<?php echo wp_create_nonce(basename(__FILE__))?>" />
			<input type="submit" value="Generate Selected Entities" accesskey="p" id="submit" class="button-primary" name="submit"> 
			<input type="submit" value="Delete Selected Entities" accesskey="p" id="delete" class="button-secondary" name="delete">
			
		</div>
	</form>
</div>

<?php
}