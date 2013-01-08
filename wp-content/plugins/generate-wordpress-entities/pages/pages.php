<?php 
require_once(dirname(__FILE__) . '/../functions.php');
function gwe_pages_manage_menu() {
$msg = '';
if (wp_verify_nonce($_POST['gwe_nonce'], basename(__FILE__))) {
	if(isset($_POST['delete'])) {
		gwe_delete_pages();
		$msg = '<div id="message" class="updated below-h2"><p>Pages deleted</p></div>';
	}
	else {		
		gwe_generate_pages($_POST['gwe_name'], $_POST['gwe_count'], $_POST['gwe_page_parent'], $_POST['gwe_comments']);
		$msg = '<div id="message" class="updated below-h2"><p>Pages generated</p></div>';
	}
}
?>
<script type="text/javascript">
	jQuery(document).ready(function($) {
		$('#gwe-form input:text, #gwe-form select').width('99%');				
		if($('#gwe_page_parent').size() == 0) {
			$('<select name="gwe_page_parent" id="gwe_page_parent" style="width: 99%; "></select>').insertAfter('#pl');
		}
	});
</script>
<div class="wrap">
	<form id="gwe-form" name="gwe-form" method="post" action="admin.php?page=gwe-pages">
		<div id="poststuff">
			<h2>Generate Pages</h2>
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
						<label id="pl">Parent page:</label>
						<?php gwe_dropdown_page()?>
					</p>
					<p>
						<label>Count:</label>
						<?php gwe_dropdown_count()?>
					</p>
					<p>
						<label>Comments:</label>
						<select id="gwe_comments" name="gwe_comments">
							<option value="-1">random</option>
							<option value="0">No</option>
							<option value="1">Yes</option>
							<option value="2">Yes & generate</option>
						</select>
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