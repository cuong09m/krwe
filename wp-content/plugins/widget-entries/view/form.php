<p><?php _e('Select a widget entry to include in the sidebar.', 'widget-entries'); ?></p>
<p>
<select name="<?php echo $this->get_field_name('postwidgetid'); ?>">
	<option value=""></option>
<?php foreach($widgets as $w):?>
	<?php if($w->ID == $id_selected){$widgettitle = $w->post_title;}?>
	<option value="<?php echo $w->ID; ?>" <?php echo ($w->ID == $id_selected) ? 'selected="selected"' : ''; ?>>
		<?php echo $w->post_title; ?>
	</option>
<?php endforeach;?>
</select>
</p>
<p>
<input type="checkbox" name="<?php echo $this->get_field_name('includetitle'); ?>" <?php echo $includetitle; ?> />
<label for="<?php echo $this->get_field_name('includetitle'); ?>">
	<?php _e('Show post title', 'widget-entries'); ?>
</label>
</p>
<?php //Field hack to show the widget entry title on the widget header?>
<input type="hidden" id="-title" value="<?php echo $widgettitle; ?>" />