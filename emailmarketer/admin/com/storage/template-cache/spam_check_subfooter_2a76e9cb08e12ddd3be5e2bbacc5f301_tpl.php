<?php $IEM = $tpl->Get('IEM'); ?><div class="spam_info spamRuleBroken_row">
	<div class="spamRuleBroken_graph">
		<div class="spam_<?php echo $tpl->Get('spam_display_style'); ?>" style="width:<?php echo $tpl->Get('spam_percentage'); ?>%;"><img src="images/1x1.gif" width="1" height="5" /></div>
	</div>
	<div style="line-height:1"><?php echo $tpl->Get('spam_rating_message'); ?></div>
</div>
<input type="hidden" name="<?php echo $tpl->Get('type'); ?>_is_spam" id="<?php echo $tpl->Get('type'); ?>_is_spam" value="<?php echo $tpl->Get('is_spam'); ?>" />
