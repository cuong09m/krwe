<?php $IEM = $tpl->Get('IEM'); ?><table width="100%" align="center" cellspacing="0" cellpadding="0" class="toolTipBox">
	<tr>
		<td>
			<img src="images/infoballon.gif" align="left" width="20" height="16">
			<b><?php if(isset($GLOBALS['TipIntro'])) print $GLOBALS['TipIntro']; ?><?php if(isset($GLOBALS['TipNumber'])) print $GLOBALS['TipNumber']; ?><?php if(isset($GLOBALS['Extra'])) print $GLOBALS['Extra']; ?></b> <?php if(isset($GLOBALS['Tip'])) print $GLOBALS['Tip']; ?> <?php if(isset($GLOBALS['InfoTip_ReadMore'])) print $GLOBALS['InfoTip_ReadMore']; ?>
		</td>
	</tr>
</table>