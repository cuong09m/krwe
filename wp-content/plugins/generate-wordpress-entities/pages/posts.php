<?php 
require_once(dirname(__FILE__) . '/../functions.php');
function gwe_posts_manage_menu() {
$msg = '';
if (wp_verify_nonce($_POST['gwe_nonce'], basename(__FILE__))) {
	if(isset($_POST['delete'])) {
		gwe_delete_posts();
		$msg = '<div id="message" class="updated below-h2"><p>Posts deleted</p></div>';
	}
	else {
		gwe_generate_posts($_POST['gwe_name'], $_POST['gwe_count'], $_POST['gwe_comments'], $_POST['post_category'], $_POST['gwe_thumb'], $_POST['gwe_custom_key'], $_POST['gwe_custom_gen'], $_POST['gwe_custom_val']);
		$msg = '<div id="message" class="updated below-h2"><p>Posts generated</p></div>';
	}
}
?>
<script type="text/javascript">
	jQuery(document).ready(function($) {
		$('#gwe-form input:text, #gwe-form select').width('99%');
		
		$('.categorychecklist input:checkbox[value!=-1]').click(function(){
			$('#in-category-random').removeAttr('checked');
		});
		
		$('#in-category-random').click(function(){
			$('.categorychecklist input:checkbox[value!=-1]').removeAttr('checked');
		});
		
		$('#custom_fields_table select').live('change', function(){
			$(this).closest('tr').find('input:text:last').toggle($(this).val() == 'Custom');
		});
		
		$('.delete_this_custom_field').live('click', function(event){
			event.preventDefault();			
			$(this).closest('tr').remove();			
			return false;
		});
		$('#add_custom_field').click(function(event){
			event.preventDefault();			
			add_custom_fields_row();
			return false;
		});
				
		function add_custom_fields_row() {			
			$('#custom_fields_table tbody').append($('#custom_fields_table tfoot').html());
		}
		add_custom_fields_row();
	});
</script>
<div class="wrap">
	<form id="gwe-form" name="gwe-form" method="post" action="admin.php?page=gwe-posts">
		<div id="poststuff">
			<h2>Generate Posts</h2>
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
					<p>
						<label>Post featured image:</label>
						<select id="gwe_thumb" name="gwe_thumb">
							<option value="-1">random</option>
							<option value="0">No</option>
							<option value="1">Yes</option>
						</select>
					</p>
					<p>
						<label>Categories:</label>
						<div class="categorydiv">
							<ul id="gwe_cats" class="categorychecklist form-no-clear" style="margin-left:20px" >
								<li id="category-random"><label class="selectit"><input value="-1" type="checkbox" name="post_category[]" id="in-category-random" checked="checked"> <b>random</b></label></li>
								<?php wp_terms_checklist()?>
							</ul>
						</div>
					</p>
					<p>
						<label>Custom fields:</label>
						<div class="categorydiv">
							<table id="custom_fields_table" width="100%">
								<thead>
									<tr>
										<th>Key</th>
										<th>Value</th>
										<th></th>
										<th></th>
									</tr>
								</thead>								
								<tbody>
								</tbody>								
								<tfoot style="display:none">
									<tr>							
										<td>							
											<input name="gwe_custom_key[]" type="text" value="" />							
										</td>
										<td>
											<select name="gwe_custom_gen[]">
												<option value="Custom" selected="selected">Custom</option>
												<option value="Word">Word</option>
												<option value="Sentence">Sentence</option>
												<option value="Email">Email</option>
												<option value="Number_1_5">Number (1-5)</option>
												<option value="Number_0_100">Number (0-100)</option>
											</select>
										</td>
										<td>
											<input name="gwe_custom_val[]" type="text" value="" />
										</td>
										<td>
											<input type="submit" value="Delete" class="delete_this_custom_field" class="button-secondary" name="delete_this_custom_field">
										</td>
									</tr>
								</tfoot>
							</table>
							<input type="submit" value="Add another custom field" id="add_custom_field" class="button-secondary" name="add_custom_field">
						</div>
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