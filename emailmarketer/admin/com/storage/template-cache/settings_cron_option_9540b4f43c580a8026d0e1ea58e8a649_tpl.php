<?php $IEM = $tpl->Get('IEM'); ?><tr class="GridRow">
	<td style="padding-left:10px">
		<?php if(isset($GLOBALS['Cron_Option_Heading'])) print $GLOBALS['Cron_Option_Heading']; ?>
	</td>
	<td>
		<?php if(isset($GLOBALS['Cron_LastRun'])) print $GLOBALS['Cron_LastRun']; ?>
	</td>
	<td>
		<?php if(isset($GLOBALS['Cron_NextRun'])) print $GLOBALS['Cron_NextRun']; ?>
	</td>
	<td>
		<select name="<?php if(isset($GLOBALS['Cron_Option_SelectName'])) print $GLOBALS['Cron_Option_SelectName']; ?>" style="width: 90px">
			<?php if(isset($GLOBALS['Cron_Options'])) print $GLOBALS['Cron_Options']; ?>
		</select>
	</td>
</tr>
