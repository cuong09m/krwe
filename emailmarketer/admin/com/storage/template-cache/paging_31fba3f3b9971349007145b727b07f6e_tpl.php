<?php $IEM = $tpl->Get('IEM'); ?><div align="right">
	<?php print GetLang('ResultsPerPage'); ?>:&nbsp;
	<select name="PerPageDisplay<?php if(isset($GLOBALS['PPDisplayName'])) print $GLOBALS['PPDisplayName']; ?>" id="PerPageDisplay<?php if(isset($GLOBALS['PPDisplayName'])) print $GLOBALS['PPDisplayName']; ?>" style="margin-bottom: 4px; width: 55px;" onChange="ChangePaging('<?php print $IEM['CurrentPage']; ?>', '<?php if(isset($GLOBALS['FormAction'])) print $GLOBALS['FormAction']; ?>', '<?php if(isset($GLOBALS['PPDisplayName'])) print $GLOBALS['PPDisplayName']; ?>', '<?php print GetLang('Paging_Confirm_All'); ?>')"><?php if(isset($GLOBALS['PerPageDisplayOptions'])) print $GLOBALS['PerPageDisplayOptions']; ?></select>
	&nbsp;&nbsp;&nbsp;&nbsp;
	<?php print GetLang('Pages'); ?>: <?php if(isset($GLOBALS['DisplayPage'])) print $GLOBALS['DisplayPage']; ?>
</div>

