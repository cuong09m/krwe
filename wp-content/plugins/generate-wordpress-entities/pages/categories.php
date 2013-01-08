<?php 
require_once(dirname(__FILE__) . '/../functions.php');
function gwe_categories_manage_menu() {
$msg = '';
if (wp_verify_nonce($_POST['gwe_nonce'], basename(__FILE__))) {
	if(isset($_POST['delete'])) {
		gwe_delete_categories();
		$msg = '<div id="message" class="updated below-h2"><p>Categories deleted</p></div>';
	}
	else {
		gwe_generate_categories($_POST['gwe_name'], $_POST['gwe_count'], $_POST['gwe_cat_parent']);
		$msg = '<div id="message" class="updated below-h2"><p>Categories generated</p></div>';
	}
}
?>
<script type="text/javascript">
	jQuery(document).ready(function($) {
		$('#gwe-form input:text, #gwe-form select').width('99%');				
	});
</script>
<div class="wrap">
	<form id="gwe-form" name="gwe-form" method="post" action="admin.php?page=gwe-categories">
		<div id="poststuff">
			<h2>Generate Categories</h2>
			<?php echo $msg?>
			<div class="meta-box-sortables ui-sortable" id="normal-sortables">
						
			
			<div class="postbox">
				<div title="Click to toggle" class="handlediv"><br></div>
				<h3 class="hndle"><span>Parameters</span></h3>
				<div class="inside">
					<p>
						<label>Name:</label>
						<input id="gwe_name" name="gwe_name" type="text" value="random" />
						<small>
							Leave "random" value to generate random names.<br />
							If You will change it, auto generated number will be added to end of each record.
						</small>
					</p>
					<p>
						<label>Parent category:</label>
						<?php gwe_dropdown_category()?>
					</p>
					<p>
						<label>Count:</label>
						<?php gwe_dropdown_count()?>
					</p>
				</div>
			</div>
						
			<input type="hidden" name="gwe_nonce" value="<?php echo wp_create_nonce(basename(__FILE__))?>" />
			<input type="submit" value="Submit" accesskey="p" id="submit" class="button-primary" name="submit"> 
			<input type="submit" value="Delete All" accesskey="p" id="delete" class="button-secondary" name="delete">
			
			</div>
		</div>
	</form>
</div>

<?php
}