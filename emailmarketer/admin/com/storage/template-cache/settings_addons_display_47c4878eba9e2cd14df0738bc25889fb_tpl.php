<?php $IEM = $tpl->Get('IEM'); ?><script>
	function UnInstall(addon_name) {
		if (!confirm('<?php print GetLang('Addon_Uninstall_Confirm'); ?>')) {
			return false;
		}
		document.location = 'index.php?Page=Settings&Tab=4&Action=Addons&SubAction=Uninstall&Addon=' + escape(addon_name);
		return true;
	}
</script>
<table cellspacing="0" cellpadding="2" width="100%" class="Panel" border="0">
	<tr class="Heading3">
		<td><?php echo GetLang('Addon_Name'); ?></td>
		<td><?php echo GetLang('Addon_Description'); ?></td>
		<td><?php echo GetLang('Addon_RunningVersion'); ?></td>
		<td style="text-align: center;"><?php echo GetLang('Addon_Installed'); ?></td>
		<td style="text-align: center;"><?php echo GetLang('Addon_Enabled'); ?></td>
		<td><?php echo GetLang('Action'); ?></td>
	</tr>
	<?php $array = $tpl->Get('records'); if(is_array($array) || is_object($array)): foreach($array as $addon_name=>$record): $tpl->Assign('addon_name', $addon_name, false); $tpl->Assign('record', $record, false);  ?>
		<tr class="GridRow">
			<td><span><?php echo $tpl->Get('record','name'); ?></span></td>
			<td><span title="<?php echo $tpl->Get('record','description'); ?>"><?php echo $tpl->Get('record','short_description'); ?></span></td>
			<td><?php echo $tpl->Get('record','addon_version'); ?></td>
			<td style="text-align: center;">
				<?php if($tpl->Get('record','install_details','installed')): ?>
					<a href="#" onClick="UnInstall('<?php echo $tpl->Get('addon_name'); ?>');" title="<?php echo GetLang('Addon_Tooltip_ClickToUninstall'); ?>">
						<img src="images/tick.gif" border="0" alt="uninstall" />
					</a>
				<?php else: ?>
					<a href="index.php?Page=Settings&Action=Addons&Addon=<?php echo $tpl->Get('addon_name'); ?>&SubAction=install" title="<?php echo GetLang('Addon_Tooltip_ClickToInstall'); ?>">
						<img src="images/cross.gif" border="0" alt="install" />
					</a>
				<?php endif; ?>
			</td>
			<td style="text-align: center;">
				<?php if($tpl->Get('record','install_details','enabled')): ?>
					<a href="index.php?Page=Settings&Action=Addons&Addon=<?php echo $tpl->Get('addon_name'); ?>&SubAction=disable" title="<?php echo GetLang('Addon_Tooltip_ClickToDisable'); ?>">
						<img src="images/tick.gif" border="0" alt="disable" />
					</a>
				<?php elseif($tpl->Get('record','install_details','configured')): ?>
					<a href="index.php?Page=Settings&Action=Addons&Addon=<?php echo $tpl->Get('addon_name'); ?>&SubAction=enable" title="<?php echo GetLang('Addon_Tooltip_ClickToEnable'); ?>">
						<img src="images/cross.gif" border="0" alt="enable" />
					</a>
				<?php elseif($tpl->Get('record','install_details')): ?>
					<a href="#" onClick="alert('<?php echo GetLang('Addon_Alert_NeedToConfigure'); ?>'); return false;" title="<?php echo GetLang('Addon_Tooltip_ClickToEnable'); ?>">
						<img src="images/cross.gif" border="0" alt="enable" />
					</a>
				<?php else: ?>
					<a href="#" onClick="alert('<?php echo GetLang('Addon_Alert_NeedToInstall'); ?>'); return false;" title="<?php echo GetLang('Addon_Tooltip_ClickToEnable'); ?>">
						<img src="images/cross.gif" border="0" alt="enable" />
					</a>
				<?php endif; ?>
			</td>
			<td>
				<?php if($tpl->Get('record','install_details') && $tpl->Get('record','need_upgrade')): ?>
					<a href="index.php?Page=Settings&Action=Addons&Addon=<?php echo $tpl->Get('addon_name'); ?>&SubAction=upgrade"><?php echo GetLang('Addon_Action_Text_Upgrade'); ?></a>
				<?php elseif($tpl->Get('record','install_details') && $tpl->Get('record','hasconfiguration')): ?>
					<a href="#" onClick="LoadAddonSettings('<?php echo $tpl->Get('addon_name'); ?>', '<?php echo $tpl->Get('record','name'); ?>'); return false;"><?php echo GetLang('Addon_Action_Text_Configure'); ?></a>
				<?php elseif($tpl->Get('record','install_details')): ?>
					<a href="#" onClick="alert('<?php echo GetLang('Addon_Alert_NoConfiguration'); ?>'); return false;" style="color:#cacaca;"><?php echo GetLang('Addon_Action_Text_Configure'); ?></a>
				<?php else: ?>
					<span style="color:#cacaca;"><?php echo GetLang('Addon_Action_Text_Configure'); ?></span>
				<?php endif; ?>
			</td>
		</tr>
	 <?php endforeach; endif; ?>
</table>
<div id="addon_settings"></div>
