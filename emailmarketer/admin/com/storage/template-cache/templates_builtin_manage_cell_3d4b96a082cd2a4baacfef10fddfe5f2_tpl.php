<?php $IEM = $tpl->Get('IEM'); ?><div class="TemplateBox" onmouseout="this.className='TemplateBox'" onmouseover="this.className='TemplateBoxOver'" class="TemplateBox">
	<a href="index.php?Page=Templates&Action=View&id=<?php if(isset($GLOBALS['Template_ID'])) print $GLOBALS['Template_ID']; ?>" target="_blank" style="color:#333333; font-family:tahoma; font-size:11px;">
		<div class="TemplateHeading"><?php if(isset($GLOBALS['Name'])) print $GLOBALS['Name']; ?></div>
		<img src="<?php if(isset($GLOBALS['PreviewImage'])) print $GLOBALS['PreviewImage']; ?>" width="173" height="140" border="0" style="margin-bottom: 5px;">
	</a>
	<br />
	<a href="index.php?Page=Templates&Action=View&id=<?php if(isset($GLOBALS['Template_ID'])) print $GLOBALS['Template_ID']; ?>" target="_blank" style="color:#333333; font-family:tahoma; font-size:11px;">
		<img border="0" style="padding-right: 5px;" src="images/magnify.gif"/> <?php print GetLang('Preview_Template'); ?>
	</a>
</div>