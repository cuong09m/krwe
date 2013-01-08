<?php $IEM = $tpl->Get('IEM'); ?><script src="includes/js/jquery.js"></script>
<script src="includes/js/jquery/form.js"></script>
<script src="includes/js/jquery/thickbox.js"></script>
<link rel="stylesheet" type="text/css" href="includes/styles/thickbox.css" />
<script>
	$(function() {
		$(document.frmListEditor).submit(function(event) {
			try {

				var fieldNames = ["Name", "OwnerName", "OwnerEmail", "ReplyToEmail", "BounceEmail"];
				var emptyToks = ["<?php print GetLang('EnterListName'); ?>", "<?php print GetLang('EnterOwnerName'); ?>", "<?php print GetLang('EnterOwnerEmail'); ?>", "<?php print GetLang('EnterReplyToEmail'); ?>", "<?php print GetLang('EnterBounceEmail'); ?>"];
				var invalidToks = ["<?php print GetLang('ListNameIsNotValid'); ?>", "<?php print GetLang('ListOwnerNameIsNotValid'); ?>", "<?php print GetLang('ListOwnerEmailIsNotValid'); ?>", "<?php print GetLang('ListReplyToEmailIsNotValid'); ?>", "<?php print GetLang('ListBounceEmailIsNotValid'); ?>"];
				var form = this;

				var fields = jQuery.map(fieldNames, function(el, i) {
					return form.elements[el];
				});

				var error = false;
				jQuery.each(fields, function(i, el){
					if (el.value == '') {
						error = emptyToks[i];
						el.focus();
						return false;
					} else if (fieldNames[i].indexOf('Email') != -1 && !isValidEmail(el.value)) {
						error = invalidToks[i];
						el.focus();
						return false;
					}
				});

				if (error) {
					alert(error);
					return false;
				}

				var count = 0;
				for (var i = 0; i < $('#fields')[0].options.length; i++) {
					if ($('#fields')[0].options[i].selected) { count++; break; }
				}

				if (count == 0) {
					alert("<?php print GetLang('SelectFields'); ?>");
					$('#fields')[0].focus();
					return false;
				}

				return true;

			} catch(e) {
				alert('Unable to validate');
				return false;
			}
		});

		$('.CancelButton', document.frmListEditor).click(function() { if (confirm("<?php if(isset($GLOBALS['CancelButton'])) print $GLOBALS['CancelButton']; ?>")) { document.location="index.php?Page=Lists" } });

		$('.form_text', document.frmListEditor).focus(function() { this.select(); });

		$('#availablefields').click(AvailableFieldsClicked);
	});

	function AvailableFieldsClicked() {
		var availableFields = $('#availablefields').get(0);
		var visibleFields = $('#fields').get(0);
		for(var i = 0, j = availableFields.options.length; i < j; ++i) {
			var currentValue = availableFields.options[i].value;
			var currentText = availableFields.options[i].text;
			var entryInVisibleFields = $('ul li input[@value='+currentValue+']', visibleFields).get(0);

			if(availableFields.options[i].selected) {
				if(entryInVisibleFields) continue;

				var newIndex = document.frmListEditor['VisibleFields[]'].options.length;
				var newOption = new Option(currentText, currentValue);
				document.frmListEditor['VisibleFields[]'].options[newIndex] = newOption;
				$(ISSelectReplacement.add_option(document.frmListEditor['VisibleFields[]'], newOption, newIndex)).appendTo($('ul', visibleFields));
			} else {
				if(!entryInVisibleFields) continue;

				$(entryInVisibleFields).parent().remove();
				$('option[@value='+currentValue+']', document.frmListEditor['VisibleFields[]']).remove();
			}
		}
	}
</script>
<form name="frmListEditor" id="frmListEditor" method="post" action="index.php?Page=Lists&Action=<?php if(isset($GLOBALS['Action'])) print $GLOBALS['Action']; ?>">
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
			</td>
		</tr>
		<tr>
			<td>
				<?php if(isset($GLOBALS['Message'])) print $GLOBALS['Message']; ?>
			</td>
		</tr>
		<tr>
			<td>
				<input class="FormButton SubmitButton" type="submit" value="<?php print GetLang('Save'); ?>">
				<input class="FormButton CancelButton" type="button" value="<?php print GetLang('Cancel'); ?>" />
				<br />&nbsp;
				<table border="0" cellspacing="0" cellpadding="2" width="100%" class="Panel">
					<tr>
						<td colspan="2" class="Heading2">
							&nbsp;&nbsp;<?php if(isset($GLOBALS['ListDetails'])) print $GLOBALS['ListDetails']; ?>
						</td>
					</tr>
					<tr>
						<td class="FieldLabel">
							<?php $tmpTplFile = $tpl->GetTemplate();
			$tmpTplData = $tpl->TemplateData;
			$tpl->ParseTemplate("Required");
			$tpl->SetTemplate($tmpTplFile);
			$tpl->TemplateData = $tmpTplData; ?>
							<?php print GetLang('ListName'); ?>:&nbsp;
						</td>
						<td>
							<input type="text" name="Name" class="Field250 form_text" value="<?php if(isset($GLOBALS['Name'])) print $GLOBALS['Name']; ?>">&nbsp;<span class="HelpToolTip"><img src="images/help.gif" alt="?" width="24" height="16" border="0" /><span class="HelpToolTip_Title" style="display:none;"><?php print stripslashes(GetLang('ListName')); ?></span><span class="HelpToolTip_Contents" style="display:none;"><?php print stripslashes(GetLang('HLP_ListName')); ?></span></span>
						</td>
					</tr>
					<tr>
						<td class="FieldLabel">
							<?php $tmpTplFile = $tpl->GetTemplate();
			$tmpTplData = $tpl->TemplateData;
			$tpl->ParseTemplate("Required");
			$tpl->SetTemplate($tmpTplFile);
			$tpl->TemplateData = $tmpTplData; ?>
							<?php print GetLang('ListOwnerName'); ?>:&nbsp;
						</td>
						<td>
							<input type="text" name="OwnerName" class="Field250 form_text" value="<?php if(isset($GLOBALS['OwnerName'])) print $GLOBALS['OwnerName']; ?>">&nbsp;<span class="HelpToolTip"><img src="images/help.gif" alt="?" width="24" height="16" border="0" /><span class="HelpToolTip_Title" style="display:none;"><?php print stripslashes(GetLang('ListOwnerName')); ?></span><span class="HelpToolTip_Contents" style="display:none;"><?php print stripslashes(GetLang('HLP_ListOwnerName')); ?></span></span>
						</td>
					</tr>
					<tr>
						<td class="FieldLabel">
							<?php $tmpTplFile = $tpl->GetTemplate();
			$tmpTplData = $tpl->TemplateData;
			$tpl->ParseTemplate("Required");
			$tpl->SetTemplate($tmpTplFile);
			$tpl->TemplateData = $tmpTplData; ?>
							<?php print GetLang('ListOwnerEmail'); ?>:&nbsp;
						</td>
						<td>
							<input type="text" name="OwnerEmail" class="Field250 form_text" value="<?php if(isset($GLOBALS['OwnerEmail'])) print $GLOBALS['OwnerEmail']; ?>">&nbsp;<span class="HelpToolTip"><img src="images/help.gif" alt="?" width="24" height="16" border="0" /><span class="HelpToolTip_Title" style="display:none;"><?php print stripslashes(GetLang('ListOwnerEmail')); ?></span><span class="HelpToolTip_Contents" style="display:none;"><?php print stripslashes(GetLang('HLP_ListOwnerEmail')); ?></span></span>
						</td>
					</tr>
					<tr>
						<td class="FieldLabel">
							<?php $tmpTplFile = $tpl->GetTemplate();
			$tmpTplData = $tpl->TemplateData;
			$tpl->ParseTemplate("Required");
			$tpl->SetTemplate($tmpTplFile);
			$tpl->TemplateData = $tmpTplData; ?>
							<?php print GetLang('ListReplyToEmail'); ?>:&nbsp;
						</td>
						<td>
							<input type="text" name="ReplyToEmail" class="Field250 form_text" value="<?php if(isset($GLOBALS['ReplyToEmail'])) print $GLOBALS['ReplyToEmail']; ?>">&nbsp;<span class="HelpToolTip"><img src="images/help.gif" alt="?" width="24" height="16" border="0" /><span class="HelpToolTip_Title" style="display:none;"><?php print stripslashes(GetLang('ListReplyToEmail')); ?></span><span class="HelpToolTip_Contents" style="display:none;"><?php print stripslashes(GetLang('HLP_ListReplyToEmail')); ?></span></span>
						</td>
					</tr>
					<tr style="display: <?php if(isset($GLOBALS['ShowBounceInfo'])) print $GLOBALS['ShowBounceInfo']; ?>">
						<td class="FieldLabel">
							<?php $tmpTplFile = $tpl->GetTemplate();
			$tmpTplData = $tpl->TemplateData;
			$tpl->ParseTemplate("Required");
			$tpl->SetTemplate($tmpTplFile);
			$tpl->TemplateData = $tmpTplData; ?>
							<?php print GetLang('ListBounceEmail'); ?>:&nbsp;
						</td>
						<td>
							<input type="text" name="BounceEmail" class="Field250 form_text" value="<?php if(isset($GLOBALS['BounceEmail'])) print $GLOBALS['BounceEmail']; ?>">&nbsp;<span class="HelpToolTip"><img src="images/help.gif" alt="?" width="24" height="16" border="0" /><span class="HelpToolTip_Title" style="display:none;"><?php print stripslashes(GetLang('ListBounceEmail')); ?></span><span class="HelpToolTip_Contents" style="display:none;"><?php print stripslashes(GetLang('HLP_ListBounceEmail')); ?></span></span>
						</td>
					</tr>
					<tr>
						<td class="FieldLabel">
							<?php $tmpTplFile = $tpl->GetTemplate();
			$tmpTplData = $tpl->TemplateData;
			$tpl->ParseTemplate("Not_Required");
			$tpl->SetTemplate($tmpTplFile);
			$tpl->TemplateData = $tmpTplData; ?>
							<?php print GetLang('NotifyOwner'); ?>:&nbsp;
						</td>
						<td>
							<label for="NotifyOwner"><input type="checkbox" name="NotifyOwner" id="NotifyOwner" value="1" <?php if(isset($GLOBALS['NotifyOwner'])) print $GLOBALS['NotifyOwner']; ?>><?php print GetLang('NotifyOwnerExplain'); ?></label> <span class="HelpToolTip"><img src="images/help.gif" alt="?" width="24" height="16" border="0" /><span class="HelpToolTip_Title" style="display:none;"><?php print stripslashes(GetLang('NotifyOwner')); ?></span><span class="HelpToolTip_Contents" style="display:none;"><?php print stripslashes(GetLang('HLP_NotifyOwner')); ?></span></span>
						</td>
					</tr>

					<tr <?php if(isset($GLOBALS['ShowCustomFields'])) print $GLOBALS['ShowCustomFields']; ?>>
						<td class="EmptyRow" colspan="2">
							&nbsp;
						</td>
					</tr>
					<tr <?php if(isset($GLOBALS['ShowCustomFields'])) print $GLOBALS['ShowCustomFields']; ?>>
						<td colspan="2" class="Heading2">
							&nbsp;&nbsp;<?php print GetLang('Add_Customfields_To_List'); ?>
						</td>
					</tr>
					<tr <?php if(isset($GLOBALS['ShowCustomFields'])) print $GLOBALS['ShowCustomFields']; ?>>
						<td class="FieldLabel">
							<?php print GetLang('AddTheseFields'); ?>:
						</td>
						<td>
							<select id="availablefields" name="AvailableFields[]" multiple="multiple" class="ISSelectReplacement" style="<?php if(isset($GLOBALS['VisibleFields_Style'])) print $GLOBALS['VisibleFields_Style']; ?>" onClick="AvailableFieldsClicked();">
								<?php if(isset($GLOBALS['AvailableFields'])) print $GLOBALS['AvailableFields']; ?>
							</select>&nbsp;<span class="HelpToolTip"><img src="images/help.gif" alt="?" width="24" height="16" border="0" /><span class="HelpToolTip_Title" style="display:none;"><?php print stripslashes(GetLang('AddTheseFields')); ?></span><span class="HelpToolTip_Contents" style="display:none;"><?php print stripslashes(GetLang('HLP_AddTheseFields')); ?></span></span>
						</td>
					</tr>

					<tr>
						<td class="EmptyRow" colspan="2">
							&nbsp;
						</td>
					</tr>
					<tr>
						<td colspan="2" class="Heading2">
							&nbsp;&nbsp;<?php print GetLang('VisibleFields'); ?>
						</td>
					</tr>
					<tr>
						<td class="FieldLabel">
							<?php print GetLang('ShowTheseFields'); ?>:
						</td>
						<td>
							<select id="fields" name="VisibleFields[]" multiple="multiple" class="ISSelectReplacement" style="<?php if(isset($GLOBALS['VisibleFields_Style'])) print $GLOBALS['VisibleFields_Style']; ?>">
								<?php if(isset($GLOBALS['VisibleFields'])) print $GLOBALS['VisibleFields']; ?>
							</select>&nbsp;<span class="HelpToolTip"><img src="images/help.gif" alt="?" width="24" height="16" border="0" /><span class="HelpToolTip_Title" style="display:none;"><?php print stripslashes(GetLang('VisibleFields')); ?></span><span class="HelpToolTip_Contents" style="display:none;"><?php print stripslashes(GetLang('HLP_VisibleFields')); ?></span></span>
						</td>
					</tr>
					<tr>
						<td class="EmptyRow" colspan="2">
							&nbsp;
						</td>
					</tr>
					<tr>
						<td colspan="2" class="Heading2">
							&nbsp;&nbsp;<?php print GetLang('PredefinedCustomFields'); ?>
						</td>
					</tr>
					<tr>
						<td class="FieldLabel">
							<?php $tmpTplFile = $tpl->GetTemplate();
			$tmpTplData = $tpl->TemplateData;
			$tpl->ParseTemplate("Not_Required");
			$tpl->SetTemplate($tmpTplFile);
			$tpl->TemplateData = $tmpTplData; ?>
							<?php print GetLang('CompanyName'); ?>:&nbsp;
						</td>
						<td>
							<input type="text" name="CompanyName" class="Field250 form_text" value="<?php if(isset($GLOBALS['CompanyName'])) print $GLOBALS['CompanyName']; ?>">&nbsp;<span class="HelpToolTip"><img src="images/help.gif" alt="?" width="24" height="16" border="0" /><span class="HelpToolTip_Title" style="display:none;"><?php print stripslashes(GetLang('CompanyName')); ?></span><span class="HelpToolTip_Contents" style="display:none;"><?php print stripslashes(GetLang('HLP_CompanyName')); ?></span></span>
						</td>
					</tr>
					<tr>
						<td class="FieldLabel">
							<?php $tmpTplFile = $tpl->GetTemplate();
			$tmpTplData = $tpl->TemplateData;
			$tpl->ParseTemplate("Not_Required");
			$tpl->SetTemplate($tmpTplFile);
			$tpl->TemplateData = $tmpTplData; ?>
							<?php print GetLang('CompanyAddress'); ?>:&nbsp;
						</td>
						<td>
							<input type="text" name="CompanyAddress" class="Field250 form_text" value="<?php if(isset($GLOBALS['CompanyAddress'])) print $GLOBALS['CompanyAddress']; ?>">&nbsp;<span class="HelpToolTip"><img src="images/help.gif" alt="?" width="24" height="16" border="0" /><span class="HelpToolTip_Title" style="display:none;"><?php print stripslashes(GetLang('CompanyAddress')); ?></span><span class="HelpToolTip_Contents" style="display:none;"><?php print stripslashes(GetLang('HLP_CompanyAddress')); ?></span></span>
						</td>
					</tr>
					<tr>
						<td class="FieldLabel">
							<?php $tmpTplFile = $tpl->GetTemplate();
			$tmpTplData = $tpl->TemplateData;
			$tpl->ParseTemplate("Not_Required");
			$tpl->SetTemplate($tmpTplFile);
			$tpl->TemplateData = $tmpTplData; ?>
							<?php print GetLang('CompanyPhone'); ?>:&nbsp;
						</td>
						<td>
							<input type="text" name="CompanyPhone" class="Field250 form_text" value="<?php if(isset($GLOBALS['CompanyPhone'])) print $GLOBALS['CompanyPhone']; ?>" maxlength="20">&nbsp;<span class="HelpToolTip"><img src="images/help.gif" alt="?" width="24" height="16" border="0" /><span class="HelpToolTip_Title" style="display:none;"><?php print stripslashes(GetLang('CompanyPhone')); ?></span><span class="HelpToolTip_Contents" style="display:none;"><?php print stripslashes(GetLang('HLP_CompanyPhone')); ?></span></span>
						</td>
					</tr>
					<?php $tmpTplFile = $tpl->GetTemplate();
			$tmpTplData = $tpl->TemplateData;
			$tpl->ParseTemplate("bounce_details");
			$tpl->SetTemplate($tmpTplFile);
			$tpl->TemplateData = $tmpTplData; ?>
				</table>
				<table width="100%" cellspacing="0" cellpadding="2" border="0" class="PanelPlain">
					<tr>
						<td width="200" class="FieldLabel">&nbsp;</td>
						<td valign="top" height="30">
							<input class="FormButton SubmitButton" type="submit" value="<?php print GetLang('Save'); ?>" />
							<input class="FormButton CancelButton" type="button" value="<?php print GetLang('Cancel'); ?>" />
						</td>
					</tr>
				</table>
			</td>
		</tr>
	</table>
</form>
