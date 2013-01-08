<?php $IEM = $tpl->Get('IEM'); ?><table cellspacing="0" cellpadding="0" width="100%" align="center">
	<tr>
		<td class="Heading1"><?php if(isset($GLOBALS['Intro'])) print $GLOBALS['Intro']; ?></td>
	</tr>
	<tr>
		<td class="Intro pageinfo"><p><?php print GetLang('Help_ListsManage'); ?></p></td>
	</tr>
	<tr>
		<td class="body">
			<?php if(isset($GLOBALS['Message'])) print $GLOBALS['Message']; ?>
		</td>
	</tr>
	<tr>
		<td class="body">
			<?php if(isset($GLOBALS['Lists_AddButton'])) print $GLOBALS['Lists_AddButton']; ?>
		</td>
	</tr>
</table>

