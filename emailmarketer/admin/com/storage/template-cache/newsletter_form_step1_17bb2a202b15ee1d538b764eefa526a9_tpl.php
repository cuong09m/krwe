<?php $IEM = $tpl->Get('IEM'); ?><form method="post" action="index.php?Page=Newsletters&Action=<?php if(isset($GLOBALS['Action'])) print $GLOBALS['Action']; ?>" onsubmit="return CheckForm()">
	<table cellspacing="0" cellpadding="0" width="100%" align="center">
		<tr>
			<td class="Heading1">
				<?php if(isset($GLOBALS['Heading'])) print $GLOBALS['Heading']; ?>
			</td>
		</tr>
		<tr>
			<td class="body pageinfo">
				<p>
					<?php if(isset($GLOBALS['Intro'])) print $GLOBALS['Intro']; ?>
				</p>
				<?php if(isset($GLOBALS['Message'])) print $GLOBALS['Message']; ?>
			</td>
		</tr>
		<tr>
			<td>
				<input class="FormButton" type="submit" value="<?php print GetLang('Next'); ?>">
				<input class="FormButton" type="button" value="<?php print GetLang('Cancel'); ?>" onClick='if(confirm("<?php if(isset($GLOBALS['CancelButton'])) print $GLOBALS['CancelButton']; ?>")) { document.location="index.php?Page=Newsletters" }'>
				<br />
				&nbsp;
				<table border="0" cellspacing="0" cellpadding="2" width="100%" class="Panel">
					<tr>
						<td colspan="3" class="Heading2">
							&nbsp;&nbsp;<?php if(isset($GLOBALS['NewsletterDetails'])) print $GLOBALS['NewsletterDetails']; ?>
						</td>
					</tr>
					<tr>
						<td width="200" class="FieldLabel">
							<?php $tmpTplFile = $tpl->GetTemplate();
			$tmpTplData = $tpl->TemplateData;
			$tpl->ParseTemplate("Required");
			$tpl->SetTemplate($tmpTplFile);
			$tpl->TemplateData = $tmpTplData; ?>
							<?php print GetLang('NewsletterName'); ?>:
						</td>
						<td width="300">
							<input type="text" name="Name" class="Field250" value="<?php if(isset($GLOBALS['Name'])) print $GLOBALS['Name']; ?>">&nbsp;<span class="HelpToolTip"><img src="images/help.gif" alt="?" width="24" height="16" border="0" /><span class="HelpToolTip_Title" style="display:none;"><?php print stripslashes(GetLang('NewsletterName')); ?></span><span class="HelpToolTip_Contents" style="display:none;"><?php print stripslashes(GetLang('HLP_NewsletterName')); ?></span></span>
						</td>
						<td rowspan="3" valign="top">
							<div class="DisplayTemplateList" style="display: block; width: 255px; text-align: center; display: <?php if(isset($GLOBALS['DisplayTemplateList'])) print $GLOBALS['DisplayTemplateList']; ?>">
								<div><a href="javascript:void(0);" onclick="javascript:ShowPreview();"><img id="imgPreview" src="images/nopreview.gif" width="247" height="200" style="border: 1px solid black" onerror="this.src=images/nopreview.gif"></a></div>
								<div style="padding-top: 10px;"><a href="#" onclick="javascript: ShowPreview(); return false;"><img src="images/magnify.gif" border="0" style="padding-right:5px"><?php print GetLang('Preview_Template'); ?></a></div>
							</div>
						</td>
					</tr>
					<tr>
						<td width="200" class="FieldLabel">
							<?php $tmpTplFile = $tpl->GetTemplate();
			$tmpTplData = $tpl->TemplateData;
			$tpl->ParseTemplate("Required");
			$tpl->SetTemplate($tmpTplFile);
			$tpl->TemplateData = $tmpTplData; ?>
							<?php print GetLang('NewsletterFormat'); ?>:
						</td>
						<td>
							<select name="Format" id="Format">
								<?php if(isset($GLOBALS['FormatList'])) print $GLOBALS['FormatList']; ?>
							</select>
							&nbsp;
							<span class="HelpToolTip"><img src="images/help.gif" alt="?" width="24" height="16" border="0" /><span class="HelpToolTip_Title" style="display:none;"><?php print stripslashes(GetLang('NewsletterFormat')); ?></span><span class="HelpToolTip_Contents" style="display:none;"><?php print stripslashes(GetLang('HLP_NewsletterFormat')); ?></span></span>
						</td>
					</tr>
					<tr class="DisplayTemplateList" style="display: <?php if(isset($GLOBALS['DisplayTemplateList'])) print $GLOBALS['DisplayTemplateList']; ?>">
						<td width="200" class="FieldLabel">
							<?php $tmpTplFile = $tpl->GetTemplate();
			$tmpTplData = $tpl->TemplateData;
			$tpl->ParseTemplate("Not_Required");
			$tpl->SetTemplate($tmpTplFile);
			$tpl->TemplateData = $tmpTplData; ?>
							<?php print GetLang('ChooseTemplate'); ?>:
						</td>
						<td>
							<?php if(isset($GLOBALS['TemplateList'])) print $GLOBALS['TemplateList']; ?>
							&nbsp;
							<span class="HelpToolTip"><img src="images/help.gif" alt="?" width="24" height="16" border="0" /><span class="HelpToolTip_Title" style="display:none;"><?php print stripslashes(GetLang('ChooseTemplate')); ?></span><span class="HelpToolTip_Contents" style="display:none;"><?php print stripslashes(GetLang('HLP_ChooseTemplate')); ?></span></span>
						</td>
					</tr>
				</table>
				<table width="100%" cellspacing="0" cellpadding="2" border="0" class="PanelPlain">
					<tr>
						<td width="200" class="FieldLabel"></td>
						<td>
							<input class="FormButton" type="submit" value="<?php print GetLang('Next'); ?>" />
							<input class="FormButton" type="button" value="<?php print GetLang('Cancel'); ?>" onClick='if (confirm("<?php if(isset($GLOBALS['CancelButton'])) print $GLOBALS['CancelButton']; ?>")) { document.location="index.php?Page=Newsletters" }' />
						</td>
					</tr>
				</table>
			</td>
		</tr>
	</table>
</form>
<script>
	function CheckForm() {
		var f = document.forms[0];
		if (f.Name.value == '') {
			alert("<?php print GetLang('EnterNewsletterName'); ?>");
			f.Name.focus();
			return false;
		}
	}

	$('#Format').change(function() {
		// Hide the template preview if a text campaign was selected
		if($(this).val() == 't') {
			$('.DisplayTemplateList').hide();
		}
		else {
			$('.DisplayTemplateList').show();
		}
	});

</script>
