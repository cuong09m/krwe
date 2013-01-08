<?php $IEM = $tpl->Get('IEM'); ?><select name="campaignstat" style="width:100%;" id="CampaignStatsListDropdown">
<option value="0" <?php if($tpl->Get('mystatsSelected','selected') == $tpl->Get('each','statid')): ?> SELECTED <?php endif; ?>><?php print GetLang('SubscriberActivity_Last7Days'); ?></option>
<?php $array = $tpl->Get('mystats'); if(is_array($array) || is_object($array)): foreach($array as $__key=>$each): $tpl->Assign('__key', $__key, false); $tpl->Assign('each', $each, false);  ?>
	<option value="<?php echo $tpl->Get('each','statid'); ?>" <?php if($tpl->Get('mystatsSelected','selected') == $tpl->Get('each','statid')): ?> SELECTED <?php endif; ?>><?php echo $tpl->Get('each','newslettername'); ?> - <?php echo GetLang('DateStarted'); ?>: <?php echo $tpl->Get('each','starttime'); ?> - (<?php echo $tpl->Get('each','totalrecipients'); ?> <?php echo GetLang('TotalRecipients'); ?>)</option>
 <?php endforeach; endif; ?>
</select>