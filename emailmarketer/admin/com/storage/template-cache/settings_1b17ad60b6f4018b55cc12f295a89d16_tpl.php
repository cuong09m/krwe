<?php $IEM = $tpl->Get('IEM'); ?><script src="includes/js/jquery/form.js"></script>
<script src="includes/js/jquery/thickbox.js"></script>
<link rel="stylesheet" type="text/css" href="includes/styles/thickbox.css" />
<script>
	$(function() {
		$(document.settings).submit(function(event) {
			if ($(document.settings.email_address).val().trim() == '') {
				alert('<?php print GetLang('ErrorAlertMessage_BlankContactEmail'); ?>');
				document.settings.email_address.focus();
				event.preventDefault();
				event.stopPropagation();
				return false;
			}

			if ($(document.settings.licensekey).val().trim() == '') {
				alert('<?php print GetLang('ErrorAlertMessage_BlankLicenseKey'); ?>');
				document.settings.licensekey.focus();
				event.preventDefault();
				event.stopPropagation();
				return false;
			}

			if ($(document.settings.lng_applicationtitle).val().trim() == '') {
				alert('<?php print GetLang('ErrorAlertMessage_BlankApplicationName'); ?>');
				document.settings.lng_applicationtitle.focus();
				event.preventDefault();
				event.stopPropagation();
				return false;
			}
			<?php if(trim($tpl->Get('AgencyEdition','agencyid')) != ''): ?>
				if ($(document.settings.lng_accountupgrademessage).val().trim() == '') {
					alert('<?php print GetLang('ErrorAlertMessage_BlankAccountUpgradeMessage'); ?>');
					document.settings.lng_accountupgrademessage.focus();
					event.preventDefault();
					event.stopPropagation();
					return false;
				}
				if ($(document.settings.lng_freetrial_expiry_login).val().trim() == '') {
					alert('<?php print GetLang('ErrorAlertMessage_BlankExpiredLogin'); ?>');
					document.settings.lng_freetrial_expiry_login.focus();
					event.preventDefault();
					event.stopPropagation();
					return false;
				}
			<?php endif; ?>

			var temp = $('input.percentage_credit_warning', document.settings);
			var selected = {};
			for (var i = 0, j = temp.size(); i < j; ++i) {
				var select_element = temp.get(i);
				var index = select_element.id.match(/_(\d+)$/)[1];
				var elementEmailContents =  $('div#credit_percentage_warnings_text_' + index + ' textarea', document.settings).get(0);
				var elementSubject = $('div#credit_percentage_warnings_text_' + index + ' input', document.settings).get(0);

				if (elementSubject.value.trim() == '') {
					ShowTab(3);
					elementSubject.focus();
					alert('<?php echo addslashes(GetLang('CreditSettings_Warnings_Alert_EnterEmailSubject')); ?>');
					event.preventDefault();
					event.stopPropagation();
					return false;
				}

				if (elementEmailContents.value.trim() == '') {
					ShowTab(3);
					elementEmailContents.focus();
					alert('<?php echo addslashes(GetLang('CreditSettings_Warnings_Alert_EnterEmailContents')); ?>');
					event.preventDefault();
					event.stopPropagation();
					return false;
				}
			};

			if (!$('#usesmtp').attr('checked')) {
				$('form#frmSettings .smtpSettings').val('');
			}

			$('select.percentage_credit_warning_level', document.settings).attr('disabled', false);

			return true;
		});

		$('input.CancelButton', document.settings).click(function() {
			if (confirm("<?php print GetLang('ConfirmCancel'); ?>")) {
				document.location.href='index.php';
			} else {
				return false;
			}
		});

		$(document.settings.cmdPreviewEmail).click(function() {
			var f = document.forms[0];
			if (f.PreviewEmail.value == "") {
				alert("<?php print GetLang('EnterPreviewEmail'); ?>");
				f.PreviewEmail.focus();
				return false;
			}

			tb_show('', 'index.php?Page=Settings&Action=SendPreviewDisplay&keepThis=true&TB_iframe=true&height=250&width=420&modal=true', '');
			return true;
		});

		$(document.settings.allow_attachments).click(function() { $('#ShowAttachmentSize').toggle(); });

		$(document.settings.allow_embedimages).click(function() { $('#ShowDefaultEmbeddedImages')[this.checked? 'show' : 'hide' ](); });

		$(document.settings.usesmtp).click(function() {
			$('.SMTPOptions')[$('#usesmtp').attr('checked') ? 'show' : 'hide']();
			$('.sectionSignuptoSMTP')[$('#signtosmtp').attr('checked') ? 'show' : 'hide']();
			$('#sectionSMTPComOption').html($('#signtosmtp').attr('checked') ? '<?php print GetLang('SMTPCOM_UseSMTPOption'); ?> <?php print GetLang('SMTPCOM_UseSMTPOptionSeeBelow'); ?>' : '<?php print GetLang('SMTPCOM_UseSMTPOption'); ?>');
		});

		$(document.settings.cmdTestSMTP).click(function() {
			var f = document.forms[0];
			if (f.smtp_server.value == '') {
				alert("<?php print GetLang('EnterSMTPServer'); ?>");
				f.smtp_server.focus();
				return false;
			}

			if (f.smtp_test.value == '') {
				alert("<?php print GetLang('EnterTestEmail'); ?>");
				f.smtp_test.focus();
				return false;
			}

			tb_show('', 'index.php?Page=Settings&Action=SendSMTPPreviewDisplay&keepThis=true&TB_iframe=tue&height=250&width=420&modal=true', '');
			return true;
		});

		$(document.settings.cron_enabled).click(function() { $('.CronInfo', document.settings)[this.checked? 'show' : 'hide'](); });

		$(document.settings.security_wrong_login_wait_enable).click(function() { $('tr.security_wrong_login_wait_options').toggle(); });
		$(document.settings.security_wrong_login_threshold_enable).click(function() { $('tr.security_wrong_login_threshold_options').toggle(); });

		$(document.settings.credit_warnings).click(function() { $('div#credit_percentage_warnings_options', document.settings)[this.checked? 'show' : 'hide'](); });
		$('input.percentage_credit_warning', document.settings).click(function() {
			var index = this.id.match(/_(\d+)$/)[1];
			$('select#credit_percentage_warnings_level_' + index, document.settings).attr('disabled', !this.checked);
			$('div#credit_percentage_warnings_text_' + index, document.settings)[this.checked? 'show' : 'hide']();
		});

		$('input.OnFocusSelect, textarea.OnFocusSelect', document.settings).focus(function() { this.select(); });
	});

	function getPreviewParameters() {
		var values = getSMTPPreviewParameters();
		$($('form#frmSettings .emailPreviewSettings').fieldSerialize().split('&')).each(function(i,n) {
			var temp = n.split('=');
			if (temp.length == 2) values[temp[0]] = temp[1];
		});
		return values;
	}

	function getSMTPPreviewParameters() {
		var values = {};
		$($('form#frmSettings .smtpSettings').fieldSerialize().split('&')).each(function(i,n) {
			var temp = n.split('=');
			if (temp.length == 2) values[temp[0]] = temp[1];
		});
		return values;
	}

	function closePopup()
	{
		tb_remove();
	}
</script>

<form ENCTYPE="multipart/form-data" name="settings" id="frmSettings" method="post" action="index.php?Page=<?php print $IEM['CurrentPage']; ?>&<?php if(isset($GLOBALS['FormAction'])) print $GLOBALS['FormAction']; ?>">
<input type="hidden" value="<?php echo $tpl->Get('ShowTab'); ?>" name="tab_num" id="id_tab_num" />
<table cellspacing="0" cellpadding="0" width="100%" align="center" style="margin-left: 4px;">
	<tr>
		<td class="Heading1"><?php print GetLang('Settings'); ?></td>
	</tr>
	<tr>
		<td class="body pageinfo"><p><?php print GetLang('Help_Settings'); ?></p></td>
	</tr>
	<tr>
		<td>
			<?php if(isset($GLOBALS['Message'])) print $GLOBALS['Message']; ?>
			<span style="display: <?php if(isset($GLOBALS['DisplayDbUpgrade'])) print $GLOBALS['DisplayDbUpgrade']; ?>">
				<?php if(isset($GLOBALS['DbUpgradeMessage'])) print $GLOBALS['DbUpgradeMessage']; ?>
			</span>
			<span style="display: <?php if(isset($GLOBALS['DisplayAttachmentsMessage'])) print $GLOBALS['DisplayAttachmentsMessage']; ?>">
				<?php if(isset($GLOBALS['Attachments_Message'])) print $GLOBALS['Attachments_Message']; ?>
			</span>
			<?php if(isset($GLOBALS['Send_TestMode_Report'])) print $GLOBALS['Send_TestMode_Report']; ?>
		</td>
	</tr>
	<tr>
		<td>
			<?php if(isset($GLOBALS['CronWarning'])) print $GLOBALS['CronWarning']; ?>
		</td>
	</tr>
	<tr>
		<td class="body">
			<input name="setting_submit" class="FormButton SubmitButton" type="submit" value="<?php print GetLang('Save'); ?>" />
			<input class="FormButton CancelButton" type="button" value="<?php print GetLang('Cancel'); ?>" />
		</td>
	</tr>
	<tr>
		<td>
			<div>
				<br/>
				<ul id="tabnav">
					<li><a href="index.php?Page=Settings&Tab=1" <?php if($tpl->Get('ShowTab') == 1): ?>class="active"<?php endif; ?> id="tab1" onclick="ShowTab(1); $('#id_tab_num').val(1); return false;"><span><?php echo GetLang('ApplicationSettings_Heading'); ?></span></a></li>
					<li><a href="index.php?Page=Settings&Tab=2" <?php if($tpl->Get('ShowTab') == 2): ?>class="active"<?php endif; ?> id="tab2" onclick="ShowTab(2); $('#id_tab_num').val(2); return false;"><span><?php echo GetLang('EmailSettings_Heading'); ?></span></a></li>
					<li><a href="index.php?Page=Settings&Tab=7" <?php if($tpl->Get('ShowTab') == 7): ?>class="active"<?php endif; ?> id="tab7" onclick="ShowTab(7); $('#id_tab_num').val(7); return false;"><span><?php echo GetLang('BounceSettings_Heading'); ?></span></a></li>
					<li><a href="index.php?Page=Settings&Tab=3" <?php if($tpl->Get('ShowTab') == 3): ?>class="active"<?php endif; ?> id="tab3" onclick="ShowTab(3); $('#id_tab_num').val(3); return false;"><span><?php echo GetLang('CreditSettings_Heading'); ?></span></a></li>
					<li><a href="index.php?Page=Settings&Tab=4" <?php if($tpl->Get('ShowTab') == 4): ?>class="active"<?php endif; ?> id="tab4" onclick="ShowTab(4); $('#id_tab_num').val(4); return false;"><span><?php echo GetLang('CronSettings_Heading'); ?></span></a></li>
					<?php if($tpl->Get('DisplayPrivateLabel')): ?><li><a href="index.php?Page=Settings&Tab=8" <?php if($tpl->Get('ShowTab') == 8): ?>class="active"<?php endif; ?> id="tab8" onclick="ShowTab(8); $('#id_tab_num').val(8); return false;"><span><?php echo GetLang('PrivateLabelSettings_Heading'); ?></span></a></li><?php endif; ?>
					<li><a href="index.php?Page=Settings&Tab=5" <?php if($tpl->Get('ShowTab') == 5): ?>class="active"<?php endif; ?> id="tab5" onclick="ShowTab(5); $('#id_tab_num').val(5); return false;"><span><?php echo GetLang('SecuritySettings_Heading'); ?></span></a></li>
					<li><a href="index.php?Page=Settings&Tab=6" <?php if($tpl->Get('ShowTab') == 6): ?>class="active"<?php endif; ?> id="tab6" onclick="ShowTab(6); $('#id_tab_num').val(6); return false;"><span><?php echo GetLang('AddonsSettings_Heading'); ?></span></a></li>
				</ul>
				<div id="div1" style="padding-top: 10px; display: <?php if($tpl->Get('ShowTab') == 1): ?>block<?php else: ?>none<?php endif; ?>;">
					<table border="0" cellspacing="0" cellpadding="2" width="100%" class="Panel">
						<tr>
							<td colspan="2" class="Heading2">
								&nbsp;&nbsp;<?php print GetLang('Miscellaneous'); ?>
							</td>
						</tr>
						<tr>
							<td width="200" class="FieldLabel">
								<?php $tmpTplFile = $tpl->GetTemplate();
			$tmpTplData = $tpl->TemplateData;
			$tpl->ParseTemplate("Required");
			$tpl->SetTemplate($tmpTplFile);
			$tpl->TemplateData = $tmpTplData; ?>
								<?php print GetLang('ApplicationURL'); ?>:
							</td>
							<td>
								<input type="hidden" name="application_url" value="<?php if(isset($GLOBALS['ApplicationURL'])) print $GLOBALS['ApplicationURL']; ?>" />
								<input type="text" value="<?php if(isset($GLOBALS['ApplicationURL'])) print $GLOBALS['ApplicationURL']; ?>" class="Field250" readonly="readonly" disabled="disabled"> <span class="HelpToolTip"><img src="images/help.gif" alt="?" width="24" height="16" border="0" /><span class="HelpToolTip_Title" style="display:none;"><?php print stripslashes(GetLang('ApplicationURL')); ?></span><span class="HelpToolTip_Contents" style="display:none;"><?php print stripslashes(GetLang('HLP_ApplicationURL')); ?></span></span>
							</td>
						</tr>
						<tr>
							<td class="FieldLabel">
								<?php $tmpTplFile = $tpl->GetTemplate();
			$tmpTplData = $tpl->TemplateData;
			$tpl->ParseTemplate("Required");
			$tpl->SetTemplate($tmpTplFile);
			$tpl->TemplateData = $tmpTplData; ?>
								<?php print GetLang('ApplicationEmail'); ?>:
							</td>
							<td>
								<input type="text" name="email_address" value="<?php if(isset($GLOBALS['EmailAddress'])) print $GLOBALS['EmailAddress']; ?>" class="Field250"> <span class="HelpToolTip"><img src="images/help.gif" alt="?" width="24" height="16" border="0" /><span class="HelpToolTip_Title" style="display:none;"><?php print stripslashes(GetLang('ApplicationEmail')); ?></span><span class="HelpToolTip_Contents" style="display:none;"><?php print stripslashes(GetLang('HLP_ApplicationEmail')); ?></span></span>
							</td>
						</tr>
						<tr>
							<td class="FieldLabel">
								<?php $tmpTplFile = $tpl->GetTemplate();
			$tmpTplData = $tpl->TemplateData;
			$tpl->ParseTemplate("Not_Required");
			$tpl->SetTemplate($tmpTplFile);
			$tpl->TemplateData = $tmpTplData; ?>
								<?php print GetLang('IpTracking'); ?>:
							</td>
							<td>
								<label for="iptracking"><input type="checkbox" name="iptracking" id="iptracking" value="1"<?php if(isset($GLOBALS['IpTracking'])) print $GLOBALS['IpTracking']; ?>><?php print GetLang('IpTrackingExplain'); ?></label> <span class="HelpToolTip"><img src="images/help.gif" alt="?" width="24" height="16" border="0" /><span class="HelpToolTip_Title" style="display:none;"><?php print stripslashes(GetLang('IpTracking')); ?></span><span class="HelpToolTip_Contents" style="display:none;"><?php print stripslashes(GetLang('HLP_IpTracking')); ?></span></span>
							</td>
						</tr>
						<tr>
							<td class="FieldLabel">
								<?php $tmpTplFile = $tpl->GetTemplate();
			$tmpTplData = $tpl->TemplateData;
			$tpl->ParseTemplate("Not_Required");
			$tpl->SetTemplate($tmpTplFile);
			$tpl->TemplateData = $tmpTplData; ?>
								<?php echo GetLang('MultipleUnsubscribe'); ?>:
							</td>
							<td>
								<label for="usemultipleunsubscribe"><input type="checkbox" name="usemultipleunsubscribe" id="usemultipleunsubscribe" value="1" <?php if(isset($GLOBALS['UseMultipleUnsubscribe'])) print $GLOBALS['UseMultipleUnsubscribe']; ?> /> <?php echo GetLang('MultipleUnsubscribe_Explain'); ?></label> <span class="HelpToolTip"><img src="images/help.gif" alt="?" width="24" height="16" border="0" /><span class="HelpToolTip_Title" style="display:none;"><?php print stripslashes(GetLang('MultipleUnsubscribe')); ?></span><span class="HelpToolTip_Contents" style="display:none;"><?php print stripslashes(GetLang('HLP_MultipleUnsubscribe')); ?></span></span>
							</td>
						</tr>
						<tr>
							<td class="FieldLabel">
								<?php $tmpTplFile = $tpl->GetTemplate();
			$tmpTplData = $tpl->TemplateData;
			$tpl->ParseTemplate("Not_Required");
			$tpl->SetTemplate($tmpTplFile);
			$tpl->TemplateData = $tmpTplData; ?>
								<?php echo GetLang('ContactCanModifyEmail'); ?>:
							</td>
							<td>
								<label for="contactcanmodifyemail"><input type="checkbox" name="contactcanmodifyemail" id="contactcanmodifyemail" value="1" <?php if(isset($GLOBALS['ContactCanModifyEmail'])) print $GLOBALS['ContactCanModifyEmail']; ?> /> <?php echo GetLang('ContactCanModifyEmail_Explain'); ?></label> <span class="HelpToolTip"><img src="images/help.gif" alt="?" width="24" height="16" border="0" /><span class="HelpToolTip_Title" style="display:none;"><?php print stripslashes(GetLang('ContactCanModifyEmail')); ?></span><span class="HelpToolTip_Contents" style="display:none;"><?php print stripslashes(GetLang('HLP_ContactCanModifyEmail')); ?></span></span>
							</td>
						</tr>
						<tr>
							<td class="FieldLabel">
								<?php $tmpTplFile = $tpl->GetTemplate();
			$tmpTplData = $tpl->TemplateData;
			$tpl->ParseTemplate("Not_Required");
			$tpl->SetTemplate($tmpTplFile);
			$tpl->TemplateData = $tmpTplData; ?>
								<?php print GetLang('SystemMessage'); ?>:
							</td>
							<td>
								<textarea name="system_message" rows="3" cols="28" wrap="virtual" style="width: 250px;"><?php if(isset($GLOBALS['System_Message'])) print $GLOBALS['System_Message']; ?></textarea>&nbsp;&nbsp;&nbsp;<span class="HelpToolTip"><img src="images/help.gif" alt="?" width="24" height="16" border="0" /><span class="HelpToolTip_Title" style="display:none;"><?php print stripslashes(GetLang('SystemMessage')); ?></span><span class="HelpToolTip_Contents" style="display:none;"><?php print stripslashes(GetLang('HLP_SystemMessage')); ?></span></span>
							</td>
						</tr>
						<tr>
							<td colspan="2" class="EmptyRow">
								&nbsp;
							</td>
						</tr>
						<tr>
							<td colspan="2" class="Heading2">
								&nbsp;&nbsp;<?php print GetLang('DatabaseIntro'); ?>
							</td>
						</tr>
						<tr>
							<td class="FieldLabel">
								<?php $tmpTplFile = $tpl->GetTemplate();
			$tmpTplData = $tpl->TemplateData;
			$tpl->ParseTemplate("Not_Required");
			$tpl->SetTemplate($tmpTplFile);
			$tpl->TemplateData = $tmpTplData; ?>
								<?php print GetLang('DatabaseType'); ?>:
							</td>
							<td class=body>
								[<?php if(isset($GLOBALS['DatabaseType'])) print $GLOBALS['DatabaseType']; ?>]
							</td>
						</tr>
						<tr>
							<td class="FieldLabel">
								<?php $tmpTplFile = $tpl->GetTemplate();
			$tmpTplData = $tpl->TemplateData;
			$tpl->ParseTemplate("Required");
			$tpl->SetTemplate($tmpTplFile);
			$tpl->TemplateData = $tmpTplData; ?>
								<?php print GetLang('DatabaseUser'); ?>:
							</td>
							<td>
								<input type="hidden" name="database_u" value="<?php if(isset($GLOBALS['DatabaseUser'])) print $GLOBALS['DatabaseUser']; ?>" />
								<input type="text" value="<?php if(isset($GLOBALS['DatabaseUser'])) print $GLOBALS['DatabaseUser']; ?>" class="Field250 OnFocusSelect" readonly="readonly" disabled="disabled"> <span class="HelpToolTip"><img src="images/help.gif" alt="?" width="24" height="16" border="0" /><span class="HelpToolTip_Title" style="display:none;"><?php print stripslashes(GetLang('DatabaseUser')); ?></span><span class="HelpToolTip_Contents" style="display:none;"><?php print stripslashes(GetLang('HLP_DatabaseUser')); ?></span></span>
							</td>
						</tr>
						<tr>
							<td class="FieldLabel">
								<?php $tmpTplFile = $tpl->GetTemplate();
			$tmpTplData = $tpl->TemplateData;
			$tpl->ParseTemplate("Not_Required");
			$tpl->SetTemplate($tmpTplFile);
			$tpl->TemplateData = $tmpTplData; ?>
								<?php print GetLang('DatabasePassword'); ?>:
							</td>
							<td>
								<input type="hidden" name="database_p" value="<?php if(isset($GLOBALS['DatabasePass'])) print $GLOBALS['DatabasePass']; ?>" />
								<input type="password" value="<?php if(isset($GLOBALS['DatabasePass'])) print $GLOBALS['DatabasePass']; ?>" class="Field250 OnFocusSelect" readonly="readonly" disabled="disabled" autocomplete="off" /> <span class="HelpToolTip"><img src="images/help.gif" alt="?" width="24" height="16" border="0" /><span class="HelpToolTip_Title" style="display:none;"><?php print stripslashes(GetLang('DatabasePassword')); ?></span><span class="HelpToolTip_Contents" style="display:none;"><?php print stripslashes(GetLang('HLP_DatabasePassword')); ?></span></span>
							</td>
						</tr>
						<tr>
							<td class="FieldLabel">
								<?php $tmpTplFile = $tpl->GetTemplate();
			$tmpTplData = $tpl->TemplateData;
			$tpl->ParseTemplate("Required");
			$tpl->SetTemplate($tmpTplFile);
			$tpl->TemplateData = $tmpTplData; ?>
								<?php print GetLang('DatabaseHost'); ?>:
							</td>
							<td>
								<input type="hidden" name="database_host" value="<?php if(isset($GLOBALS['DatabaseHost'])) print $GLOBALS['DatabaseHost']; ?>" />
								<input type="text" name="database_host" value="<?php if(isset($GLOBALS['DatabaseHost'])) print $GLOBALS['DatabaseHost']; ?>" class="Field250 OnFocusSelect" readonly="readonly" disabled="disabled"> <span class="HelpToolTip"><img src="images/help.gif" alt="?" width="24" height="16" border="0" /><span class="HelpToolTip_Title" style="display:none;"><?php print stripslashes(GetLang('DatabaseHost')); ?></span><span class="HelpToolTip_Contents" style="display:none;"><?php print stripslashes(GetLang('HLP_DatabaseHost')); ?></span></span>
							</td>
						</tr>
						<tr>
							<td class="FieldLabel">
								<?php $tmpTplFile = $tpl->GetTemplate();
			$tmpTplData = $tpl->TemplateData;
			$tpl->ParseTemplate("Required");
			$tpl->SetTemplate($tmpTplFile);
			$tpl->TemplateData = $tmpTplData; ?>
								<?php print GetLang('DatabaseName'); ?>:
							</td>
							<td>
								<input type="hidden" name="database_name" value="<?php if(isset($GLOBALS['DatabaseName'])) print $GLOBALS['DatabaseName']; ?>" />
								<input type="text" value="<?php if(isset($GLOBALS['DatabaseName'])) print $GLOBALS['DatabaseName']; ?>" class="Field250 OnFocusSelect" readonly="readonly" disabled="disabled"> <span class="HelpToolTip"><img src="images/help.gif" alt="?" width="24" height="16" border="0" /><span class="HelpToolTip_Title" style="display:none;"><?php print stripslashes(GetLang('DatabaseName')); ?></span><span class="HelpToolTip_Contents" style="display:none;"><?php print stripslashes(GetLang('HLP_DatabaseName')); ?></span></span>
							</td>
						</tr>
						<tr>
							<td class="FieldLabel">
								<?php $tmpTplFile = $tpl->GetTemplate();
			$tmpTplData = $tpl->TemplateData;
			$tpl->ParseTemplate("Not_Required");
			$tpl->SetTemplate($tmpTplFile);
			$tpl->TemplateData = $tmpTplData; ?>
								<?php print GetLang('DatabaseTablePrefix'); ?>:
							</td>
							<td>
								<input type="hidden" name="tableprefix" value="<?php if(isset($GLOBALS['DatabaseTablePrefix'])) print $GLOBALS['DatabaseTablePrefix']; ?>" />
								<input type="text" value="<?php if(isset($GLOBALS['DatabaseTablePrefix'])) print $GLOBALS['DatabaseTablePrefix']; ?>" class="Field250 OnFocusSelect" readonly="readonly" disabled="disabled"> <span class="HelpToolTip"><img src="images/help.gif" alt="?" width="24" height="16" border="0" /><span class="HelpToolTip_Title" style="display:none;"><?php print stripslashes(GetLang('DatabaseTablePrefix')); ?></span><span class="HelpToolTip_Contents" style="display:none;"><?php print stripslashes(GetLang('HLP_DatabaseTablePrefix')); ?></span></span>
							</td>
						</tr>
						<tr>
							<td class="FieldLabel">
								<?php $tmpTplFile = $tpl->GetTemplate();
			$tmpTplData = $tpl->TemplateData;
			$tpl->ParseTemplate("Not_Required");
			$tpl->SetTemplate($tmpTplFile);
			$tpl->TemplateData = $tmpTplData; ?>
								<?php print GetLang('DatabaseVersion'); ?>:
							</td>
							<td>
								<?php if(isset($GLOBALS['DatabaseVersion'])) print $GLOBALS['DatabaseVersion']; ?>
							</td>
						</tr>
						<tr>
							<td colspan="2" class="EmptyRow">
								&nbsp;
							</td>
						</tr>
						<tr>
							<td colspan="2" class="Heading2">
								&nbsp;&nbsp;<?php print GetLang('LicenseKeyIntro'); ?>
							</td>
						</tr>
						<tr>
							<td class="FieldLabel">
								<?php $tmpTplFile = $tpl->GetTemplate();
			$tmpTplData = $tpl->TemplateData;
			$tpl->ParseTemplate("Required");
			$tpl->SetTemplate($tmpTplFile);
			$tpl->TemplateData = $tmpTplData; ?>
								<?php print GetLang('LicenseKey'); ?>:
							</td>
							<td>
								<input type="text" name="licensekey" id="licensekey" value="<?php if(isset($GLOBALS['LicenseKey'])) print $GLOBALS['LicenseKey']; ?>" class="Field250"> <span class="HelpToolTip"><img src="images/help.gif" alt="?" width="24" height="16" border="0" /><span class="HelpToolTip_Title" style="display:none;"><?php print stripslashes(GetLang('LicenseKey')); ?></span><span class="HelpToolTip_Contents" style="display:none;"><?php print stripslashes(GetLang('HLP_LicenseKey')); ?></span></span>
							</td>
						</tr>
						<tr>
							<td colspan="2" class="EmptyRow">
								&nbsp;
							</td>
						</tr>
						<tr>
							<td colspan="2" class="Heading2">
								&nbsp;&nbsp;<?php print GetLang('SendTestIntro'); ?>
							</td>
						</tr>
						<tr>
							<td class="FieldLabel">
								<?php $tmpTplFile = $tpl->GetTemplate();
			$tmpTplData = $tpl->TemplateData;
			$tpl->ParseTemplate("Not_Required");
			$tpl->SetTemplate($tmpTplFile);
			$tpl->TemplateData = $tmpTplData; ?>
								<?php print GetLang('TestEmailAddress'); ?>:
							</td>
							<td>
								<input type="text" name="PreviewEmail" value="" class="Field250 emailPreviewSettings" />
								<input type="button" name="cmdPreviewEmail" value="<?php print GetLang('Send'); ?>" class="Field" />
								<span class="HelpToolTip"><img src="images/help.gif" alt="?" width="24" height="16" border="0" /><span class="HelpToolTip_Title" style="display:none;"><?php print stripslashes(GetLang('TestEmailAddress')); ?></span><span class="HelpToolTip_Contents" style="display:none;"><?php print stripslashes(GetLang('HLP_TestEmailAddress')); ?></span></span>
							</td>
						</tr>
					</table>
				</div>

				<div id="div2" style="padding-top: 10px; display: <?php if($tpl->Get('ShowTab') == 2): ?>block<?php else: ?>none<?php endif; ?>;">
					<table border="0" cellspacing="0" cellpadding="2" width="100%" class="Panel">
						<tr>
							<td colspan="2" class="Heading2">
								&nbsp;&nbsp;<?php print GetLang('EmailSettings'); ?>
							</td>
						</tr>
						<tr>
							<td class="FieldLabel" width="10%">
								<img src="images/blank.gif" width="200" height="1" /><br />
								<?php $tmpTplFile = $tpl->GetTemplate();
			$tmpTplData = $tpl->TemplateData;
			$tpl->ParseTemplate("Not_Required");
			$tpl->SetTemplate($tmpTplFile);
			$tpl->TemplateData = $tmpTplData; ?>
								<?php print GetLang('EmailSize_Warning'); ?>:
							</td>
							<td width="90%">
								<input type="text" name="emailsize_warning" value="<?php if(isset($GLOBALS['EmailSize_Warning'])) print $GLOBALS['EmailSize_Warning']; ?>" class="Field250" style="width: 50px;"><?php print GetLang('EmailSize_Warning_KB'); ?> <span class="HelpToolTip"><img src="images/help.gif" alt="?" width="24" height="16" border="0" /><span class="HelpToolTip_Title" style="display:none;"><?php print stripslashes(GetLang('EmailSize_Warning')); ?></span><span class="HelpToolTip_Contents" style="display:none;"><?php print stripslashes(GetLang('HLP_EmailSize_Warning')); ?></span></span>
							</td>
						</tr>
						<tr>
							<td class="FieldLabel">
								<?php $tmpTplFile = $tpl->GetTemplate();
			$tmpTplData = $tpl->TemplateData;
			$tpl->ParseTemplate("Not_Required");
			$tpl->SetTemplate($tmpTplFile);
			$tpl->TemplateData = $tmpTplData; ?>
								<?php print GetLang('EmailSize_Maximum'); ?>:
							</td>
							<td>
								<input type="text" name="emailsize_maximum" value="<?php if(isset($GLOBALS['EmailSize_Maximum'])) print $GLOBALS['EmailSize_Maximum']; ?>" class="Field250" style="width: 50px;"><?php print GetLang('EmailSize_Maximum_KB'); ?> <span class="HelpToolTip"><img src="images/help.gif" alt="?" width="24" height="16" border="0" /><span class="HelpToolTip_Title" style="display:none;"><?php print stripslashes(GetLang('EmailSize_Maximum')); ?></span><span class="HelpToolTip_Contents" style="display:none;"><?php print stripslashes(GetLang('HLP_EmailSize_Maximum')); ?></span></span>
							</td>
						</tr>
						<tr>
							<td class="FieldLabel">
								<?php $tmpTplFile = $tpl->GetTemplate();
			$tmpTplData = $tpl->TemplateData;
			$tpl->ParseTemplate("Not_Required");
			$tpl->SetTemplate($tmpTplFile);
			$tpl->TemplateData = $tmpTplData; ?>
								<?php print GetLang('MaxHourlyRate'); ?>:
							</td>
							<td>
								<input type="text" name="maxhourlyrate" value="<?php if(isset($GLOBALS['MaxHourlyRate'])) print $GLOBALS['MaxHourlyRate']; ?>" class="Field250" style="width: 50px;"> <span class="HelpToolTip"><img src="images/help.gif" alt="?" width="24" height="16" border="0" /><span class="HelpToolTip_Title" style="display:none;"><?php print stripslashes(GetLang('MaxHourlyRate')); ?></span><span class="HelpToolTip_Contents" style="display:none;"><?php print stripslashes(GetLang('HLP_MaxHourlyRate')); ?></span></span>
							</td>
						</tr>
						<tr>
							<td class="FieldLabel">
								<?php $tmpTplFile = $tpl->GetTemplate();
			$tmpTplData = $tpl->TemplateData;
			$tpl->ParseTemplate("Not_Required");
			$tpl->SetTemplate($tmpTplFile);
			$tpl->TemplateData = $tmpTplData; ?>
								<?php print GetLang('MaxOverSize'); ?>:
							</td>
							<td>
								<input type="text" name="maxoversize" value="<?php if(isset($GLOBALS['MaxOverSize'])) print $GLOBALS['MaxOverSize']; ?>" class="Field250" style="width: 50px;"> <span class="HelpToolTip"><img src="images/help.gif" alt="?" width="24" height="16" border="0" /><span class="HelpToolTip_Title" style="display:none;"><?php print stripslashes(GetLang('MaxOverSize')); ?></span><span class="HelpToolTip_Contents" style="display:none;"><?php print stripslashes(GetLang('HLP_MaxOverSize')); ?></span></span>
							</td>
						</tr>
						<tr>
							<td class="FieldLabel">
								<?php $tmpTplFile = $tpl->GetTemplate();
			$tmpTplData = $tpl->TemplateData;
			$tpl->ParseTemplate("Not_Required");
			$tpl->SetTemplate($tmpTplFile);
			$tpl->TemplateData = $tmpTplData; ?>
								<?php print GetLang('Resend_Maximum'); ?>:
							</td>
							<td>
								<input type="text" name="resend_maximum" value="<?php if(isset($GLOBALS['Resend_Maximum'])) print $GLOBALS['Resend_Maximum']; ?>" class="Field250" style="width: 50px;"> <span class="HelpToolTip"><img src="images/help.gif" alt="?" width="24" height="16" border="0" /><span class="HelpToolTip_Title" style="display:none;"><?php print stripslashes(GetLang('Resend_Maximum')); ?></span><span class="HelpToolTip_Contents" style="display:none;"><?php print stripslashes(GetLang('HLP_Resend_Maximum')); ?></span></span>
							</td>
						</tr>
						<tr>
							<td class="FieldLabel">
								<?php $tmpTplFile = $tpl->GetTemplate();
			$tmpTplData = $tpl->TemplateData;
			$tpl->ParseTemplate("Not_Required");
			$tpl->SetTemplate($tmpTplFile);
			$tpl->TemplateData = $tmpTplData; ?>
								<?php print GetLang('MaxImageWidth'); ?>:
							</td>
							<td>
								<input type="text" name="max_imagewidth" value="<?php if(isset($GLOBALS['MaxImageWidth'])) print $GLOBALS['MaxImageWidth']; ?>" class="Field250" style="width: 50px;"> <span class="HelpToolTip"><img src="images/help.gif" alt="?" width="24" height="16" border="0" /><span class="HelpToolTip_Title" style="display:none;"><?php print stripslashes(GetLang('MaxImageWidth')); ?></span><span class="HelpToolTip_Contents" style="display:none;"><?php print stripslashes(GetLang('HLP_MaxImageWidth')); ?></span></span>
							</td>
						</tr>
						<tr>
							<td class="FieldLabel">
								<?php $tmpTplFile = $tpl->GetTemplate();
			$tmpTplData = $tpl->TemplateData;
			$tpl->ParseTemplate("Not_Required");
			$tpl->SetTemplate($tmpTplFile);
			$tpl->TemplateData = $tmpTplData; ?>
								<?php print GetLang('MaxImageHeight'); ?>:
							</td>
							<td>
								<input type="text" name="max_imageheight" value="<?php if(isset($GLOBALS['MaxImageHeight'])) print $GLOBALS['MaxImageHeight']; ?>" class="Field250" style="width: 50px;"> <span class="HelpToolTip"><img src="images/help.gif" alt="?" width="24" height="16" border="0" /><span class="HelpToolTip_Title" style="display:none;"><?php print stripslashes(GetLang('MaxImageHeight')); ?></span><span class="HelpToolTip_Contents" style="display:none;"><?php print stripslashes(GetLang('HLP_MaxImageHeight')); ?></span></span>
							</td>
						</tr>
						<tr>
							<td class="FieldLabel">
								<?php $tmpTplFile = $tpl->GetTemplate();
			$tmpTplData = $tpl->TemplateData;
			$tpl->ParseTemplate("Not_Required");
			$tpl->SetTemplate($tmpTplFile);
			$tpl->TemplateData = $tmpTplData; ?>
								<?php print GetLang('GlobalHTMLFooter'); ?>:
							</td>
							<td>
								<textarea name="htmlfooter" rows="3" cols="28" wrap="virtual" style="width: 250px;"><?php if(isset($GLOBALS['HTMLFooter'])) print $GLOBALS['HTMLFooter']; ?></textarea>&nbsp;&nbsp;&nbsp;<span class="HelpToolTip"><img src="images/help.gif" alt="?" width="24" height="16" border="0" /><span class="HelpToolTip_Title" style="display:none;"><?php print stripslashes(GetLang('GlobalHTMLFooter')); ?></span><span class="HelpToolTip_Contents" style="display:none;"><?php print stripslashes(GetLang('HLP_GlobalHTMLFooter')); ?></span></span>
							</td>
						</tr>
						<tr>
							<td class="FieldLabel">
								<?php $tmpTplFile = $tpl->GetTemplate();
			$tmpTplData = $tpl->TemplateData;
			$tpl->ParseTemplate("Not_Required");
			$tpl->SetTemplate($tmpTplFile);
			$tpl->TemplateData = $tmpTplData; ?>
								<?php print GetLang('GlobalTextFooter'); ?>:
							</td>
							<td>
								<textarea name="textfooter" rows="3" cols="28" wrap="virtual" style="width: 250px;"><?php if(isset($GLOBALS['TextFooter'])) print $GLOBALS['TextFooter']; ?></textarea>&nbsp;&nbsp;&nbsp;<span class="HelpToolTip"><img src="images/help.gif" alt="?" width="24" height="16" border="0" /><span class="HelpToolTip_Title" style="display:none;"><?php print stripslashes(GetLang('GlobalTextFooter')); ?></span><span class="HelpToolTip_Contents" style="display:none;"><?php print stripslashes(GetLang('HLP_GlobalTextFooter')); ?></span></span>
							</td>
						</tr>
						<tr>
							<td class="FieldLabel">
								<?php $tmpTplFile = $tpl->GetTemplate();
			$tmpTplData = $tpl->TemplateData;
			$tpl->ParseTemplate("Not_Required");
			$tpl->SetTemplate($tmpTplFile);
			$tpl->TemplateData = $tmpTplData; ?>
								<?php print GetLang('ForceUnsubLink'); ?>:
							</td>
							<td>
								<label for="force_unsublink"><input type="checkbox" name="force_unsublink" id="force_unsublink" value="1"<?php if(isset($GLOBALS['ForceUnsubLink'])) print $GLOBALS['ForceUnsubLink']; ?>><?php print GetLang('ForceUnsubLinkExplain'); ?></label> <span class="HelpToolTip"><img src="images/help.gif" alt="?" width="24" height="16" border="0" /><span class="HelpToolTip_Title" style="display:none;"><?php print stripslashes(GetLang('ForceUnsubLink')); ?></span><span class="HelpToolTip_Contents" style="display:none;"><?php print stripslashes(GetLang('HLP_ForceUnsubLink')); ?></span></span>
							</td>
						</tr>
						<tr>
							<td class="FieldLabel">
								<?php $tmpTplFile = $tpl->GetTemplate();
			$tmpTplData = $tpl->TemplateData;
			$tpl->ParseTemplate("Not_Required");
			$tpl->SetTemplate($tmpTplFile);
			$tpl->TemplateData = $tmpTplData; ?>
								<?php print GetLang('AllowAttachments'); ?>:
							</td>
							<td>
								<div>
									<label for="allow_attachments">
										<input type="checkbox" name="allow_attachments" id="allow_attachments" value="1"<?php if(isset($GLOBALS['AllowAttachments'])) print $GLOBALS['AllowAttachments']; ?>><?php print GetLang('AllowAttachmentsExplain'); ?>
									</label>
									<span class="HelpToolTip"><img src="images/help.gif" alt="?" width="24" height="16" border="0" /><span class="HelpToolTip_Title" style="display:none;"><?php print stripslashes(GetLang('AllowAttachments')); ?></span><span class="HelpToolTip_Contents" style="display:none;"><?php print stripslashes(GetLang('HLP_AllowAttachments')); ?></span></span>
								</div>
								<div id="ShowAttachmentSize" style="display: <?php if(isset($GLOBALS['ShowAttachmentSize'])) print $GLOBALS['ShowAttachmentSize']; ?>">
									<div>
										<img width="20" height="20" src="images/nodejoin.gif"/>
										<?php print GetLang('MaxAttachmentSize'); ?>
										<input type="text" name="attachment_size" value="<?php if(isset($GLOBALS['AttachmentSize'])) print $GLOBALS['AttachmentSize']; ?>" class="Field250" style="width: 50px;"><?php print GetLang('MaxAttachmentSizeKB'); ?>
										<span class="HelpToolTip"><img src="images/help.gif" alt="?" width="24" height="16" border="0" /><span class="HelpToolTip_Title" style="display:none;"><?php print stripslashes(GetLang('MaxAttachmentSize')); ?></span><span class="HelpToolTip_Contents" style="display:none;"><?php print stripslashes(GetLang('HLP_MaxAttachmentSize')); ?></span></span>
									</div>
								</div>
							</td>
						</tr>
						<tr>
							<td class="FieldLabel">
								<?php $tmpTplFile = $tpl->GetTemplate();
			$tmpTplData = $tpl->TemplateData;
			$tpl->ParseTemplate("Not_Required");
			$tpl->SetTemplate($tmpTplFile);
			$tpl->TemplateData = $tmpTplData; ?>
								<?php print GetLang('AllowEmbeddedImages'); ?>:
							</td>
							<td>
								<div>
									<label for="allow_embedimages">
										<input type="checkbox" name="allow_embedimages" id="allow_embedimages" value="1"<?php if(isset($GLOBALS['AllowEmbedImages'])) print $GLOBALS['AllowEmbedImages']; ?>><?php print GetLang('AllowEmbeddedImagesExplain'); ?>
									</label>
									<span class="HelpToolTip"><img src="images/help.gif" alt="?" width="24" height="16" border="0" /><span class="HelpToolTip_Title" style="display:none;"><?php print stripslashes(GetLang('AllowEmbeddedImages')); ?></span><span class="HelpToolTip_Contents" style="display:none;"><?php print stripslashes(GetLang('HLP_AllowEmbeddedImages')); ?></span></span>
								</div>
								<div id="ShowDefaultEmbeddedImages" style="display: <?php if(isset($GLOBALS['ShowDefaultEmbeddedImages'])) print $GLOBALS['ShowDefaultEmbeddedImages']; ?>">
									<div>
										<img width="20" height="20" src="images/nodejoin.gif"/>
										<label for="default_embedimages">
											<input type="checkbox" name="default_embedimages" id="default_embedimages" value="1"<?php if(isset($GLOBALS['DefaultEmbedImages'])) print $GLOBALS['DefaultEmbedImages']; ?>><?php print GetLang('DefaultEmbeddedImagesExplain'); ?>
										</label>
										<span class="HelpToolTip"><img src="images/help.gif" alt="?" width="24" height="16" border="0" /><span class="HelpToolTip_Title" style="display:none;"><?php print stripslashes(GetLang('DefaultEmbeddedImages')); ?></span><span class="HelpToolTip_Contents" style="display:none;"><?php print stripslashes(GetLang('HLP_DefaultEmbeddedImages')); ?></span></span>
									</div>
								</div>
							</td>
						</tr>
						<tr>
							<td class="FieldLabel">
								<?php $tmpTplFile = $tpl->GetTemplate();
			$tmpTplData = $tpl->TemplateData;
			$tpl->ParseTemplate("Not_Required");
			$tpl->SetTemplate($tmpTplFile);
			$tpl->TemplateData = $tmpTplData; ?>
								<?php print GetLang('Send_TestMode'); ?>:
							</td>
							<td>
								<label for="send_test_mode">
									<input type="checkbox" name="send_test_mode" id="send_test_mode" value="1"<?php if(isset($GLOBALS['SendTestMode'])) print $GLOBALS['SendTestMode']; ?>><?php print GetLang('Send_TestModeExplain'); ?>
								</label>
								<span class="HelpToolTip"><img src="images/help.gif" alt="?" width="24" height="16" border="0" /><span class="HelpToolTip_Title" style="display:none;"><?php print stripslashes(GetLang('Send_TestMode')); ?></span><span class="HelpToolTip_Contents" style="display:none;"><?php print stripslashes(GetLang('HLP_Send_TestMode')); ?></span></span>
							</td>
						</tr>
						<tr>
							<td colspan="2" class="EmptyRow">
								&nbsp;
							</td>
						</tr>
						<tr>
							<td colspan="2" class="Heading2">
								&nbsp;&nbsp;<?php print GetLang('SmtpServerIntro'); ?>
							</td>
						</tr>
						<tr>
							<td class="FieldLabel">
								<?php $tmpTplFile = $tpl->GetTemplate();
			$tmpTplData = $tpl->TemplateData;
			$tpl->ParseTemplate("Not_Required");
			$tpl->SetTemplate($tmpTplFile);
			$tpl->TemplateData = $tmpTplData; ?>
								<?php print GetLang('UseSMTP'); ?>:
							</td>
							<td>
								<label for="usephpmail">
									<input type="radio" name="usesmtp" id="usephpmail" value="0"<?php if(isset($GLOBALS['UseDefaultMail'])) print $GLOBALS['UseDefaultMail']; ?>/>
									<?php print GetLang('SmtpDefaultSettings'); ?>
								</label>
								<span class="HelpToolTip"><img src="images/help.gif" alt="?" width="24" height="16" border="0" /><span class="HelpToolTip_Title" style="display:none;"><?php print stripslashes(GetLang('UseDefaultMail')); ?></span><span class="HelpToolTip_Contents" style="display:none;"><?php print stripslashes(GetLang('HLP_UseDefaultMail')); ?></span></span>
							</td>
						</tr>
						<tr>
							<td class="FieldLabel">&nbsp;</td>
							<td>
								<label for="usesmtp">
									<input type="radio" name="usesmtp" id="usesmtp" value="1"<?php if(isset($GLOBALS['UseSMTP'])) print $GLOBALS['UseSMTP']; ?>/>
									<?php print GetLang('SmtpCustom'); ?>
								</label>
								<span class="HelpToolTip"><img src="images/help.gif" alt="?" width="24" height="16" border="0" /><span class="HelpToolTip_Title" style="display:none;"><?php print stripslashes(GetLang('UseSMTP')); ?></span><span class="HelpToolTip_Contents" style="display:none;"><?php print stripslashes(GetLang('HLP_UseSMTP')); ?></span></span>
							</td>
						</tr>
						<tr class="SMTPOptions" style="display: <?php if(isset($GLOBALS['DisplaySMTP'])) print $GLOBALS['DisplaySMTP']; ?>">
							<td class="FieldLabel">
								<?php $tmpTplFile = $tpl->GetTemplate();
			$tmpTplData = $tpl->TemplateData;
			$tpl->ParseTemplate("Required");
			$tpl->SetTemplate($tmpTplFile);
			$tpl->TemplateData = $tmpTplData; ?>
								<?php print GetLang('SmtpServerName'); ?>:
							</td>
							<td>
								<img width="20" height="20" src="images/nodejoin.gif"/>
								<input type="text" name="smtp_server" id="smtp_server" value="<?php if(isset($GLOBALS['Smtp_Server'])) print $GLOBALS['Smtp_Server']; ?>" class="Field250 smtpSettings"> <span class="HelpToolTip"><img src="images/help.gif" alt="?" width="24" height="16" border="0" /><span class="HelpToolTip_Title" style="display:none;"><?php print stripslashes(GetLang('SmtpServerName')); ?></span><span class="HelpToolTip_Contents" style="display:none;"><?php print stripslashes(GetLang('HLP_SmtpServerName')); ?></span></span>
							</td>
						</tr>
						<tr class="SMTPOptions" style="display: <?php if(isset($GLOBALS['DisplaySMTP'])) print $GLOBALS['DisplaySMTP']; ?>">
							<td class="FieldLabel">
								<?php $tmpTplFile = $tpl->GetTemplate();
			$tmpTplData = $tpl->TemplateData;
			$tpl->ParseTemplate("Not_Required");
			$tpl->SetTemplate($tmpTplFile);
			$tpl->TemplateData = $tmpTplData; ?>
								<?php print GetLang('SmtpServerUsername'); ?>:
							</td>
							<td>
								<img width="20" height="20" src="images/blank.gif"/>
								<input type="text" name="smtp_u" id="smtp_u" value="<?php if(isset($GLOBALS['Smtp_Username'])) print $GLOBALS['Smtp_Username']; ?>" class="Field250 smtpSettings"> <span class="HelpToolTip"><img src="images/help.gif" alt="?" width="24" height="16" border="0" /><span class="HelpToolTip_Title" style="display:none;"><?php print stripslashes(GetLang('SmtpServerUsername')); ?></span><span class="HelpToolTip_Contents" style="display:none;"><?php print stripslashes(GetLang('HLP_SmtpServerUsername')); ?></span></span>
							</td>
						</tr>
						<tr class="SMTPOptions" style="display: <?php if(isset($GLOBALS['DisplaySMTP'])) print $GLOBALS['DisplaySMTP']; ?>">
							<td class="FieldLabel">
								<?php $tmpTplFile = $tpl->GetTemplate();
			$tmpTplData = $tpl->TemplateData;
			$tpl->ParseTemplate("Not_Required");
			$tpl->SetTemplate($tmpTplFile);
			$tpl->TemplateData = $tmpTplData; ?>
								<?php print GetLang('SmtpServerPassword'); ?>:
							</td>
							<td>
								<img width="20" height="20" src="images/blank.gif"/>
								<input type="password" name="smtp_p" id="smtp_p" value="<?php if(isset($GLOBALS['Smtp_Password'])) print $GLOBALS['Smtp_Password']; ?>" class="Field250 smtpSettings" autocomplete="off" /> <span class="HelpToolTip"><img src="images/help.gif" alt="?" width="24" height="16" border="0" /><span class="HelpToolTip_Title" style="display:none;"><?php print stripslashes(GetLang('SmtpServerPassword')); ?></span><span class="HelpToolTip_Contents" style="display:none;"><?php print stripslashes(GetLang('HLP_SmtpServerPassword')); ?></span></span>
							</td>
						</tr>
						<tr class="SMTPOptions" style="display: <?php if(isset($GLOBALS['DisplaySMTP'])) print $GLOBALS['DisplaySMTP']; ?>">
							<td class="FieldLabel">
								<?php $tmpTplFile = $tpl->GetTemplate();
			$tmpTplData = $tpl->TemplateData;
			$tpl->ParseTemplate("Not_Required");
			$tpl->SetTemplate($tmpTplFile);
			$tpl->TemplateData = $tmpTplData; ?>
								<?php print GetLang('SmtpServerPort'); ?>:
							</td>
							<td>
								<img width="20" height="20" src="images/blank.gif"/>
								<input type="text" name="smtp_port" id="smtp_port" value="<?php if(isset($GLOBALS['Smtp_Port'])) print $GLOBALS['Smtp_Port']; ?>" class="Field250 smtpSettings"> <span class="HelpToolTip"><img src="images/help.gif" alt="?" width="24" height="16" border="0" /><span class="HelpToolTip_Title" style="display:none;"><?php print stripslashes(GetLang('SmtpServerPort')); ?></span><span class="HelpToolTip_Contents" style="display:none;"><?php print stripslashes(GetLang('HLP_SmtpServerPort')); ?></span></span>
							</td>
						</tr>
						<tr class="SMTPOptions" style="display: <?php if(isset($GLOBALS['DisplaySMTP'])) print $GLOBALS['DisplaySMTP']; ?>">
							<td class="FieldLabel">
								<?php $tmpTplFile = $tpl->GetTemplate();
			$tmpTplData = $tpl->TemplateData;
			$tpl->ParseTemplate("Not_Required");
			$tpl->SetTemplate($tmpTplFile);
			$tpl->TemplateData = $tmpTplData; ?>
								<?php print GetLang('TestSendTo'); ?>:
							</td>
							<td>
								<img width="20" height="20" src="images/blank.gif"/>
								<input type="text" name="smtp_test" id="smtp_test" value="" class="Field250 smtpSettings"> <span class="HelpToolTip"><img src="images/help.gif" alt="?" width="24" height="16" border="0" /><span class="HelpToolTip_Title" style="display:none;"><?php print stripslashes(GetLang('TestSMTPSettings')); ?></span><span class="HelpToolTip_Contents" style="display:none;"><?php print stripslashes(GetLang('HLP_TestSMTPSettings')); ?></span></span>
							</td>
						</tr>
						<tr class="SMTPOptions" style="display: <?php if(isset($GLOBALS['DisplaySMTP'])) print $GLOBALS['DisplaySMTP']; ?>">
							<td class="FieldLabel">
								&nbsp;
							</td>
							<td>
								<img width="20" height="20" src="images/blank.gif"/>
								<input type="button" name="cmdTestSMTP" value="<?php print GetLang('TestSMTPSettings'); ?>" class="FormButton" style="width: 120px;" />
							</td>
						</tr>
						<tr style="display:<?php if(isset($GLOBALS['ShowSmtpComOptionShow'])) print $GLOBALS['ShowSmtpComOptionShow']; ?>;">
							<td class="FieldLabel">&nbsp;</td>
							<td>
								<label for="signtosmtp">
									<input type="radio" name="usesmtp" id="signtosmtp" value="2" />
									<span id="sectionSMTPComOption"><?php print GetLang('SMTPCOM_UseSMTPOption'); ?></span>
								</label>
								<span class="HelpToolTip"><img src="images/help.gif" alt="?" width="24" height="16" border="0" /><span class="HelpToolTip_Title" style="display:none;"><?php print stripslashes(GetLang('UseSMTPCOM')); ?></span><span class="HelpToolTip_Contents" style="display:none;"><?php print stripslashes(GetLang('HLP_UseSMTPCOM')); ?></span></span>
							</td>
						</tr>
						<tr class="sectionSignuptoSMTP" style="display: none;">
							<td colspan="2" class="EmptyRow">
								&nbsp;
							</td>
						</tr>
						<tr class="sectionSignuptoSMTP" style="display: none;">
							<td colspan="2" class="Heading2">
								&nbsp;&nbsp;<?php print GetLang('SMTPCOM_Header'); ?>
							</td>
						</tr>
						<tr class="sectionSignuptoSMTP" style="display: none;">
							<td colspan="2" style="padding-left: 20px;"><?php print GetLang('SMTPCOM_Explain'); ?></td>
						</tr>
					</table>
				</div>

				<div id="div7" style="padding-top: 10px; display: <?php if($tpl->Get('ShowTab') == 7): ?>block<?php else: ?>none<?php endif; ?>;">
					<table border="0" cellspacing="0" cellpadding="2" width="100%" class="Panel">
						<?php $tmpTplFile = $tpl->GetTemplate();
			$tmpTplData = $tpl->TemplateData;
			$tpl->ParseTemplate("bounce_details");
			$tpl->SetTemplate($tmpTplFile);
			$tpl->TemplateData = $tmpTplData; ?>
					</table>
				</div>

				<div id="div3" style="padding-top: 10px; display: <?php if($tpl->Get('ShowTab') == 3): ?>block<?php else: ?>none<?php endif; ?>;">
					<table border="0" cellspacing="0" cellpadding="2" width="100%" class="Panel">
						<tr><td colspan="2" class="Heading2">&nbsp;&nbsp;<?php echo GetLang('CreditSettings_UserCredit_Section'); ?></td></tr>
						<tr>
							<td width="200" class="FieldLabel">
								<?php $tmpTplFile = $tpl->GetTemplate();
			$tmpTplData = $tpl->TemplateData;
			$tpl->ParseTemplate("Not_Required");
			$tpl->SetTemplate($tmpTplFile);
			$tpl->TemplateData = $tmpTplData; ?>
								<?php echo GetLang('CreditSettings_UserCredit_AutorespondersTakeCredit'); ?>
							</td>
							<td>
								<label for="credit_include_autoresponders">
									<input type="checkbox" name="credit_include_autoresponders" id="credit_include_autoresponders" value="1" <?php if($tpl->Get('credit_settings','autoresponders_take_credit')): ?>checked="checked"<?php endif; ?> />
									<?php echo GetLang('CreditSettings_UserCredit_AutorespondersTakeCredit_Description'); ?>
								</label>
								<span class="HelpToolTip"><img src="images/help.gif" alt="?" width="24" height="16" border="0" /><span class="HelpToolTip_Title" style="display:none;"><?php print stripslashes(GetLang('CreditSettings_UserCredit_AutorespondersTakeCredit')); ?></span><span class="HelpToolTip_Contents" style="display:none;"><?php print stripslashes(GetLang('HLP_CreditSettings_UserCredit_AutorespondersTakeCredit')); ?></span></span>
							</td>
						</tr>
						<tr>
							<td width="200" class="FieldLabel">
								<?php $tmpTplFile = $tpl->GetTemplate();
			$tmpTplData = $tpl->TemplateData;
			$tpl->ParseTemplate("Not_Required");
			$tpl->SetTemplate($tmpTplFile);
			$tpl->TemplateData = $tmpTplData; ?>
								<?php echo GetLang('CreditSettings_UserCredit_TriggersTakeCredit'); ?>
							</td>
							<td>
								<label for="credit_include_triggers">
									<input type="checkbox" name="credit_include_triggers" id="credit_include_triggers" value="1" <?php if($tpl->Get('credit_settings','triggers_take_credit')): ?>checked="checked"<?php endif; ?> />
									<?php echo GetLang('CreditSettings_UserCredit_TriggersTakeCredit_Description'); ?>
								</label>
								<span class="HelpToolTip"><img src="images/help.gif" alt="?" width="24" height="16" border="0" /><span class="HelpToolTip_Title" style="display:none;"><?php print stripslashes(GetLang('CreditSettings_UserCredit_TriggersTakeCredit')); ?></span><span class="HelpToolTip_Contents" style="display:none;"><?php print stripslashes(GetLang('HLP_CreditSettings_UserCredit_TriggersTakeCredit')); ?></span></span>
							</td>
						</tr>
						<tr><td colspan="2" class="EmptyRow">&nbsp;</td></tr>
						<tr><td colspan="2" class="Heading2">&nbsp;&nbsp;<?php echo GetLang('CreditSettings_Warnings_Section'); ?></td></tr>
						<tr>
							<td width="200" class="FieldLabel">
								<?php $tmpTplFile = $tpl->GetTemplate();
			$tmpTplData = $tpl->TemplateData;
			$tpl->ParseTemplate("Not_Required");
			$tpl->SetTemplate($tmpTplFile);
			$tpl->TemplateData = $tmpTplData; ?>
								<?php echo GetLang('CreditSettings_Warnings_Enabled'); ?>
							</td>
							<td>
								<label for="credit_warnings">
									<input type="checkbox" name="credit_warnings" id="credit_warnings" value="1" <?php if($tpl->Get('credit_settings','enable_credit_level_warnings')): ?>checked="checked"<?php endif; ?> />
									<?php echo GetLang('CreditSettings_Warnings_Enabled_Description'); ?>
								</label>
								<span class="HelpToolTip"><img src="images/help.gif" alt="?" width="24" height="16" border="0" /><span class="HelpToolTip_Title" style="display:none;"><?php print stripslashes(GetLang('CreditSettings_Warnings_Enabled')); ?></span><span class="HelpToolTip_Contents" style="display:none;"><?php print stripslashes(GetLang('HLP_CreditSettings_Warnings_Enabled')); ?></span></span>
								<div id="credit_percentage_warnings_options" <?php if(!$tpl->Get('credit_settings','enable_credit_level_warnings')): ?>style="display:none;"<?php endif; ?>>
									<?php $array = $tpl->Get('credit_settings','warnings_percentage_level'); if(is_array($array) || is_object($array)): foreach($array as $index=>$warning_level): $tpl->Assign('index', $index, false); $tpl->Assign('warning_level', $warning_level, false);  ?>
										<div>
											<?php if($tpl->Get('index') == 0): ?>
												<img height="20" width="20" src="images/nodejoin.gif" />
											<?php else: ?>
												<img height="20" width="20" src="images/blank.gif" />
											<?php endif; ?>
											<label for="credit_percentage_warnings_enable_<?php echo $tpl->Get('index'); ?>">
												<input type="checkbox" name="credit_percentage_warnings_enable[]" id="credit_percentage_warnings_enable_<?php echo $tpl->Get('index'); ?>" class="percentage_credit_warning" value="<?php echo $tpl->Get('index'); ?>" <?php if($tpl->Get('warning_level','enabled')): ?>checked="checked"<?php endif; ?> />
												<?php echo GetLang('CreditSettings_Warnings_LevelPrompt_PRE'); ?>
											</label>
											<select name="credit_percentage_warnings_level[]" id="credit_percentage_warnings_level_<?php echo $tpl->Get('index'); ?>" class="percentage_credit_warning_level" style="width:auto;" <?php if(!$tpl->Get('warning_level','enabled')): ?>disabled="disabled"<?php endif; ?>>
												<?php $array = $tpl->Get('credit_settings','warnings_percentage_level_choices'); if(is_array($array) || is_object($array)): foreach($array as $__key=>$each): $tpl->Assign('__key', $__key, false); $tpl->Assign('each', $each, false);  ?>
													<option value="<?php echo $tpl->Get('each'); ?>" <?php if($tpl->Get('each') == $tpl->Get('warning_level','creditlevel')): ?>selected="selected"<?php endif; ?>><?php echo $tpl->Get('each'); ?>%</option>
												 <?php endforeach; endif; ?>
											</select>
											<label for="credit_percentage_warnings_enable_<?php echo $tpl->Get('index'); ?>">
												<?php echo GetLang('CreditSettings_Warnings_LevelPrompt_POST'); ?>
											</label>
											<div id="credit_percentage_warnings_text_<?php echo $tpl->Get('index'); ?>" <?php if(!$tpl->Get('warning_level','enabled')): ?>style="display:none;"<?php endif; ?>>
												<span style="float:left;">
													<img height="20" width="20" src="images/blank.gif" />
													<img height="20" width="20" src="images/nodejoin.gif" />
												</span>
												<input type="text" name="credit_percentage_warnings_subject[]" maxlength="250" value="<?php echo $tpl->Get('warning_level','emailsubject'); ?>" class="Field250" style="width:578px;" /><br />
												<textarea name="credit_percentage_warnings_text[]" rows="10" cols="70" class="OnFocusSelect"><?php echo $tpl->Get('warning_level','emailcontents'); ?></textarea>
											</div>
										</div>
									 <?php endforeach; endif; ?>
								</div>
							</td>
						</tr>
					</table>
				</div>

				<div id="div4" style="padding-top: 10px; display: <?php if($tpl->Get('ShowTab') == 4): ?>block<?php else: ?>none<?php endif; ?>;">
					<table border="0" cellspacing="0" cellpadding="2" width="100%" class="Panel">
						<tr>
							<td colspan="2" class="Heading2">
								&nbsp;&nbsp;<?php print GetLang('CronSettings'); ?>
							</td>
						</tr>
						<tr>
							<td class="FieldLabel">
								<?php $tmpTplFile = $tpl->GetTemplate();
			$tmpTplData = $tpl->TemplateData;
			$tpl->ParseTemplate("Not_Required");
			$tpl->SetTemplate($tmpTplFile);
			$tpl->TemplateData = $tmpTplData; ?>
								<?php print GetLang('CronEnabled'); ?>
							</td>
							<td>
								<label for="cron_enabled"><input type="checkbox" name="cron_enabled" id="cron_enabled" value="1"<?php if(isset($GLOBALS['CronEnabled'])) print $GLOBALS['CronEnabled']; ?>><?php print GetLang('CronEnabledExplain'); ?></label> <span class="HelpToolTip"><img src="images/help.gif" alt="?" width="24" height="16" border="0" /><span class="HelpToolTip_Title" style="display:none;"><?php print stripslashes(GetLang('CronEnabled')); ?></span><span class="HelpToolTip_Contents" style="display:none;"><?php print stripslashes(GetLang('HLP_CronEnabled')); ?></span></span>
							</td>
						</tr>
						<tr id="show_cron_path" class="CronInfo" style="display: <?php if(isset($GLOBALS['Cron_ShowInfo'])) print $GLOBALS['Cron_ShowInfo']; ?>">
							<td width="200" class="FieldLabel">
								<?php $tmpTplFile = $tpl->GetTemplate();
			$tmpTplData = $tpl->TemplateData;
			$tpl->ParseTemplate("Not_Required");
			$tpl->SetTemplate($tmpTplFile);
			$tpl->TemplateData = $tmpTplData; ?>
								<?php print GetLang('CronPath'); ?>:
							</td>
							<td>
								<textarea name="cronpath" class="Field250 OnFocusSelect" style="width:400px" rows="4" onclick="this.select()" readonly><?php if(isset($GLOBALS['CronPath'])) print $GLOBALS['CronPath']; ?></textarea> <span class="HelpToolTip"><img src="images/help.gif" alt="?" width="24" height="16" border="0" /><span class="HelpToolTip_Title" style="display:none;"><?php print stripslashes(GetLang('CronPath')); ?></span><span class="HelpToolTip_Contents" style="display:none;"><?php print stripslashes(GetLang('HLP_CronPath')); ?></span></span>
							</td>
						</tr>
						<tr id="show_cron_runtime" class="CronInfo" style="display: <?php if(isset($GLOBALS['Cron_ShowInfo'])) print $GLOBALS['Cron_ShowInfo']; ?>">
							<td width="200" class="FieldLabel">
								<?php $tmpTplFile = $tpl->GetTemplate();
			$tmpTplData = $tpl->TemplateData;
			$tpl->ParseTemplate("Not_Required");
			$tpl->SetTemplate($tmpTplFile);
			$tpl->TemplateData = $tmpTplData; ?>
								<?php print GetLang('CronRunTime'); ?>:
							</td>
							<td align="left">
								<?php if(isset($GLOBALS['CronRunTime'])) print $GLOBALS['CronRunTime']; ?>
							</td>
						</tr>
					</table>
					<table width="100%" cellspacing="0" cellpadding="0" border="0" class="Text CronInfo" style="margin-top:-20px; margin-bottom:10px; display: <?php if(isset($GLOBALS['Cron_ShowInfo'])) print $GLOBALS['Cron_ShowInfo']; ?>">
						<tr>
							<td colspan="4">
								<table width="100%" cellspacing="0" cellpadding="0" align="center" class="message_box">
									<tbody><tr>
										<td class="Message">
											<img width="20" height="16" align="left" src="images/infoballon.gif"/>
											<?php if(isset($GLOBALS['CronRunTime_Explain'])) print $GLOBALS['CronRunTime_Explain']; ?>
										</td>
									</tr>
								</tbody></table>
							</td>
						</tr>
						<tr class="Heading3">
							<td style="width:200px; padding-left:10px">Job Type</td>
							<td>Last Run</td>
							<td>Next Run</td>
							<td>Run Every</td>
						</tr>
						<?php if(isset($GLOBALS['Settings_CronOptionsList'])) print $GLOBALS['Settings_CronOptionsList']; ?>
					</table>
				</div>
				<div id="div5" style="padding-top: 10px; display: <?php if($tpl->Get('ShowTab') == 5): ?>block<?php else: ?>none<?php endif; ?>;">
					<table border="0" cellspacing="0" cellpadding="2" width="100%" class="Panel">
						<tr><td colspan="2" class="Heading2"><?php $tmpTplFile = $tpl->GetTemplate();
			$tmpTplData = $tpl->TemplateData;
			$tpl->ParseTemplate("Not_Required");
			$tpl->SetTemplate($tmpTplFile);
			$tpl->TemplateData = $tmpTplData; ?><?php echo GetLang('SecuritySettings_LoginSecurity_EnableLoginWait_Title'); ?></td></tr>
						<tr>
							<td class="FieldLabel">
								<?php $tmpTplFile = $tpl->GetTemplate();
			$tmpTplData = $tpl->TemplateData;
			$tpl->ParseTemplate("Not_Required");
			$tpl->SetTemplate($tmpTplFile);
			$tpl->TemplateData = $tmpTplData; ?>
								<?php echo GetLang('SecuritySettings_LoginSecurity_EnableLoginWait'); ?>
							</td>
							<td>
								<label for="security_wrong_login_wait_enable">
									<input type="checkbox" name="security_wrong_login_wait_enable" id="security_wrong_login_wait_enable" value="1" <?php if($tpl->Get('security_settings','login_wait') != 0): ?>checked="checked"<?php endif; ?> />
									<?php echo GetLang('SecuritySettings_LoginSecurity_YesEnableLoginWait'); ?>
								</label>
								<span class="HelpToolTip"><img src="images/help.gif" alt="?" width="24" height="16" border="0" /><span class="HelpToolTip_Title" style="display:none;"><?php print stripslashes(GetLang('SecuritySettings_LoginSecurity_EnableLoginWait')); ?></span><span class="HelpToolTip_Contents" style="display:none;"><?php print stripslashes(GetLang('HLP_SecuritySettings_LoginSecurity_EnableLoginWait')); ?></span></span>
							</td>
						</tr>
						<tr class="security_wrong_login_wait_options" <?php if($tpl->Get('security_settings','login_wait') == 0): ?>style="display:none;"<?php endif; ?>>
							<td class="FieldLabel">
								<?php $tmpTplFile = $tpl->GetTemplate();
			$tmpTplData = $tpl->TemplateData;
			$tpl->ParseTemplate("Required");
			$tpl->SetTemplate($tmpTplFile);
			$tpl->TemplateData = $tmpTplData; ?>
								<?php echo GetLang('SecuritySettings_LoginSecurity_EnableLoginWait_DelayFor'); ?>
							</td>
							<td>
								<img width="20" height="20" src="images/nodejoin.gif"/>
								<label for="security_wrong_login_wait">
									<select name="security_wrong_login_wait" id="security_wrong_login_wait" style="width: 50px;">
										<?php $array = $tpl->Get('security_settings_options','login_wait'); if(is_array($array) || is_object($array)): foreach($array as $__key=>$item): $tpl->Assign('__key', $__key, false); $tpl->Assign('item', $item, false);  ?>
											<option value="<?php echo $tpl->Get('item'); ?>" <?php if($tpl->Get('security_settings','login_wait') == $tpl->Get('item')): ?>selected="selected"<?php endif; ?>><?php echo $tpl->Get('item'); ?></option>
										 <?php endforeach; endif; ?>
									</select>
									<?php echo GetLang('Second(s)'); ?>
								</label>
							</td>
						</tr>
						<tr><td colspan="2" class="EmptyRow">&nbsp;</td></tr>
						<tr><td colspan="2" class="Heading2"><?php $tmpTplFile = $tpl->GetTemplate();
			$tmpTplData = $tpl->TemplateData;
			$tpl->ParseTemplate("Not_Required");
			$tpl->SetTemplate($tmpTplFile);
			$tpl->TemplateData = $tmpTplData; ?><?php echo GetLang('SecuritySettings_LoginSecurity_EnableLoginFailureThreshold_Title'); ?></td></tr>
						<tr>
							<td class="FieldLabel">
								<?php $tmpTplFile = $tpl->GetTemplate();
			$tmpTplData = $tpl->TemplateData;
			$tpl->ParseTemplate("Not_Required");
			$tpl->SetTemplate($tmpTplFile);
			$tpl->TemplateData = $tmpTplData; ?>
								<?php echo GetLang('SecuritySettings_LoginSecurity_EnableLoginFailureThreshold'); ?>
							</td>
							<td>
								<label for="security_wrong_login_threshold_enable">
									<input type="checkbox" name="security_wrong_login_threshold_enable" id="security_wrong_login_threshold_enable" value="1" <?php if($tpl->Get('security_settings','threshold_login_count') != 0): ?>checked="checked"<?php endif; ?> />
									<?php echo GetLang('SecuritySettings_LoginSecurity_YesEnableLoginFailureThreshold'); ?>
								</label>
								<span class="HelpToolTip"><img src="images/help.gif" alt="?" width="24" height="16" border="0" /><span class="HelpToolTip_Title" style="display:none;"><?php print stripslashes(GetLang('SecuritySettings_LoginSecurity_EnableLoginFailureThreshold')); ?></span><span class="HelpToolTip_Contents" style="display:none;"><?php print stripslashes(GetLang('HLP_SecuritySettings_LoginSecurity_EnableLoginFailureThreshold')); ?></span></span>
							</td>
						</tr>
						<tr class="security_wrong_login_threshold_options" <?php if($tpl->Get('security_settings','threshold_login_count') == 0): ?>style="display:none;"<?php endif; ?>>
							<td class="FieldLabel">
								<?php $tmpTplFile = $tpl->GetTemplate();
			$tmpTplData = $tpl->TemplateData;
			$tpl->ParseTemplate("Required");
			$tpl->SetTemplate($tmpTplFile);
			$tpl->TemplateData = $tmpTplData; ?>
								<?php echo GetLang('SecuritySettings_LoginSecurity_EnableLoginFailureThreshold_Threshold'); ?>
							</td>
							<td>
								<img width="20" height="20" src="images/nodejoin.gif"/>
								<label for="security_wrong_login_threshold_count">
									<select name="security_wrong_login_threshold_count" id="security_wrong_login_threshold_count" style="width: 50px;">
										<?php $array = $tpl->Get('security_settings_options','threshold_login_count'); if(is_array($array) || is_object($array)): foreach($array as $__key=>$item): $tpl->Assign('__key', $__key, false); $tpl->Assign('item', $item, false);  ?>
											<option value="<?php echo $tpl->Get('item'); ?>" <?php if($tpl->Get('security_settings','threshold_login_count') == $tpl->Get('item')): ?>selected="selected"<?php endif; ?>><?php echo $tpl->Get('item'); ?></option>
										 <?php endforeach; endif; ?>
									</select>
								</label>
								<?php echo GetLang('SecuritySettings_LoginSecurity_EnableLoginFailureThreshold_FailedAttemptsIn'); ?>
								<label for="security_wrong_login_threshold_duration">
									<select name="security_wrong_login_threshold_duration" id="security_wrong_login_threshold_duration" style="width: 50px;">
										<?php $array = $tpl->Get('security_settings_options','threshold_login_duration'); if(is_array($array) || is_object($array)): foreach($array as $__key=>$item): $tpl->Assign('__key', $__key, false); $tpl->Assign('item', $item, false);  ?>
											<option value="<?php echo $tpl->Get('item'); ?>" <?php if($tpl->Get('security_settings','threshold_login_duration') == $tpl->Get('item')): ?>selected="selected"<?php endif; ?>><?php echo $tpl->Get('item'); ?></option>
										 <?php endforeach; endif; ?>
									</select>
								</label>
								<?php echo GetLang('Minute(s)'); ?>
							</td>
						</tr>
						<tr class="security_wrong_login_threshold_options" <?php if($tpl->Get('security_settings','threshold_login_count') == 0): ?>style="display:none;"<?php endif; ?>>
							<td class="FieldLabel">
								<?php $tmpTplFile = $tpl->GetTemplate();
			$tmpTplData = $tpl->TemplateData;
			$tpl->ParseTemplate("Required");
			$tpl->SetTemplate($tmpTplFile);
			$tpl->TemplateData = $tmpTplData; ?>
								<?php echo GetLang('SecuritySettings_LoginSecurity_EnableLoginFailureThreshold_BanIPFor'); ?>
							</td>
							<td>
								<img src="images/blank.gif" height="20" width="20" />
								<label for="security_ban_duration">
									<select name="security_ban_duration" id="security_ban_duration" style="width: 50px;">
										<?php $array = $tpl->Get('security_settings_options','ip_login_ban_duration'); if(is_array($array) || is_object($array)): foreach($array as $__key=>$item): $tpl->Assign('__key', $__key, false); $tpl->Assign('item', $item, false);  ?>
											<option value="<?php echo $tpl->Get('item'); ?>" <?php if($tpl->Get('security_settings','ip_login_ban_duration') == $tpl->Get('item')): ?>selected="selected"<?php endif; ?>><?php echo $tpl->Get('item'); ?></option>
										 <?php endforeach; endif; ?>
									</select>
								</label>
								<?php echo GetLang('Minute(s)'); ?>
							</td>
						</tr>
					</table>
				</div>
				<?php if(!$tpl->Get('DisplayPrivateLabel')): ?><div style="display:none;"><?php endif; ?>
				<div id="div8" style="padding-top: 10px; display: <?php if($tpl->Get('ShowTab') == 8): ?>block<?php else: ?>none<?php endif; ?>;">
					<table border="0" cellspacing="0" cellpadding="2" width="100%" class="Panel">
						<tr>
							<td colspan="2" class="Heading2">
								&nbsp;&nbsp;<?php echo GetLang('PrivateLabelSettings_Heading'); ?>
							</td>
						</tr>
						<tr>
							<td class="FieldLabel" >
								<?php $tmpTplFile = $tpl->GetTemplate();
			$tmpTplData = $tpl->TemplateData;
			$tpl->ParseTemplate("Required");
			$tpl->SetTemplate($tmpTplFile);
			$tpl->TemplateData = $tmpTplData; ?>
								<?php echo GetLang('PrivateLabelSettings_ApplicationName'); ?>:
							</td>
							<td>
								<input type="text" name="lng_applicationtitle" value="<?php echo addslashes(GetLang('ApplicationTitle')); ?>" class="Field300" />
								<div class="HelpToolTipPos">
									<span class="HelpToolTip"><img src="images/help.gif" alt="?" width="24" height="16" border="0" /><span class="HelpToolTip_Title" style="display:none;"><?php print stripslashes(GetLang('PrivateLabelSettings_ApplicationName')); ?></span><span class="HelpToolTip_Contents" style="display:none;"><?php print stripslashes(GetLang('HLP_PrivateLabelSettings_ApplicationName')); ?></span></span>
								</div>
							</td>
						</tr>
						<tr style="height:100px;">
							<td class="FieldLabel" >
								<?php $tmpTplFile = $tpl->GetTemplate();
			$tmpTplData = $tpl->TemplateData;
			$tpl->ParseTemplate("Not_Required");
			$tpl->SetTemplate($tmpTplFile);
			$tpl->TemplateData = $tmpTplData; ?>
								<?php echo GetLang('PrivateLabelSettings_ApplicationFooter'); ?>:
							</td>
							<td >
								<textarea name="lng_copyright" rows="3" cols="28" wrap="virtual" class="Field300"><?php if(isset($GLOBALS['Copyright'])) print $GLOBALS['Copyright']; ?></textarea>
								<div class="HelpToolTipPos">
									<span class="HelpToolTip"><img src="images/help.gif" alt="?" width="24" height="16" border="0" /><span class="HelpToolTip_Title" style="display:none;"><?php print stripslashes(GetLang('PrivateLabelSettings_ApplicationFooter')); ?></span><span class="HelpToolTip_Contents" style="display:none;"><?php print stripslashes(GetLang('HLP_PrivateLabelSettings_ApplicationFooter')); ?></span></span>
								</div>
							</td>
						</tr>
						<tr>
							<td class="FieldLabel" >
								<?php $tmpTplFile = $tpl->GetTemplate();
			$tmpTplData = $tpl->TemplateData;
			$tpl->ParseTemplate("Not_Required");
			$tpl->SetTemplate($tmpTplFile);
			$tpl->TemplateData = $tmpTplData; ?>
								<?php echo GetLang('PrivateLabelSettings_DefaultHtmlEmailFooter'); ?>:
							</td>
							<td >
								<textarea name="lng_default_global_html_footer" rows="12" cols="28" wrap="virtual" class="Field300"><?php echo GetLang('Default_Global_HTML_Footer'); ?></textarea>
								<div class="HelpToolTipPos">
									<span class="HelpToolTip"><img src="images/help.gif" alt="?" width="24" height="16" border="0" /><span class="HelpToolTip_Title" style="display:none;"><?php print stripslashes(GetLang('PrivateLabelSettings_DefaultHtmlEmailFooter')); ?></span><span class="HelpToolTip_Contents" style="display:none;"><?php print stripslashes(GetLang('HLP_PrivateLabelSettings_DefaultHtmlEmailFooter')); ?></span></span>
								</div>
							</td>
						</tr>
						<tr>
							<td class="FieldLabel" >
								<?php $tmpTplFile = $tpl->GetTemplate();
			$tmpTplData = $tpl->TemplateData;
			$tpl->ParseTemplate("Not_Required");
			$tpl->SetTemplate($tmpTplFile);
			$tpl->TemplateData = $tmpTplData; ?>
								<?php echo GetLang('PrivateLabelSettings_DefaultTextEmailFooter'); ?>:
							</td>
							<td >
								<textarea name="lng_default_global_text_footer" rows="3" cols="28" wrap="virtual" class="Field300"><?php echo GetLang('Default_Global_Text_Footer'); ?></textarea>
								<div class="HelpToolTipPos">
									<span class="HelpToolTip"><img src="images/help.gif" alt="?" width="24" height="16" border="0" /><span class="HelpToolTip_Title" style="display:none;"><?php print stripslashes(GetLang('PrivateLabelSettings_DefaultTextEmailFooter')); ?></span><span class="HelpToolTip_Contents" style="display:none;"><?php print stripslashes(GetLang('HLP_PrivateLabelSettings_DefaultTextEmailFooter')); ?></span></span>
								</div>
							</td>
						</tr>
						<?php if(trim($tpl->Get('AgencyEdition','agencyid')) != ''): ?>
							<tr>
								<td class="FieldLabel" >
									<?php $tmpTplFile = $tpl->GetTemplate();
			$tmpTplData = $tpl->TemplateData;
			$tpl->ParseTemplate("Required");
			$tpl->SetTemplate($tmpTplFile);
			$tpl->TemplateData = $tmpTplData; ?>
									<?php echo GetLang('PrivateLabelSettings_ExpiredTrial_LoginMessage'); ?>:
								</td>
								<td >
									<textarea name="lng_freetrial_expiry_login" rows="3" cols="28" wrap="virtual" class="Field300"><?php echo GetLang('FreeTrial_Expiry_Login'); ?></textarea>
									<div class="HelpToolTipPos">
										<span class="HelpToolTip"><img src="images/help.gif" alt="?" width="24" height="16" border="0" /><span class="HelpToolTip_Title" style="display:none;"><?php print stripslashes(GetLang('PrivateLabelSettings_ExpiredTrial_LoginMessage')); ?></span><span class="HelpToolTip_Contents" style="display:none;"><?php print stripslashes(GetLang('HLP_PrivateLabelSettings_ExpiredTrial_LoginMessage')); ?></span></span>
									</div>
									<p />
								</td>
							</tr>

							<tr>
								<td class="FieldLabel" >
									<?php $tmpTplFile = $tpl->GetTemplate();
			$tmpTplData = $tpl->TemplateData;
			$tpl->ParseTemplate("Required");
			$tpl->SetTemplate($tmpTplFile);
			$tpl->TemplateData = $tmpTplData; ?>
									<?php echo GetLang('PrivateLabelSettings_UpgradeMessage'); ?>:
								</td>
								<td >
									<textarea name="lng_accountupgrademessage" rows="3" cols="28" wrap="virtual" class="Field300"><?php echo GetLang('AccountUpgradeMessage'); ?></textarea>
									<div class="HelpToolTipPos">
										<span class="HelpToolTip"><img src="images/help.gif" alt="?" width="24" height="16" border="0" /><span class="HelpToolTip_Title" style="display:none;"><?php print stripslashes(GetLang('PrivateLabelSettings_UpgradeMessage')); ?></span><span class="HelpToolTip_Contents" style="display:none;"><?php print stripslashes(GetLang('HLP_PrivateLabelSettings_UpgradeMessage')); ?></span></span>
									</div>
									<p />
								</td>
							</tr>
						<?php endif; ?>
						<tr>
							<td class="FieldLabel" >
								<?php $tmpTplFile = $tpl->GetTemplate();
			$tmpTplData = $tpl->TemplateData;
			$tpl->ParseTemplate("Not_Required");
			$tpl->SetTemplate($tmpTplFile);
			$tpl->TemplateData = $tmpTplData; ?>
								<?php echo GetLang('PrivateLabelSettings_ApplicationLogoImage'); ?>:
							</td>
							<td>
								<table border="0" cellspacing="0" cellpadding="0" width="100%">
									<tr valign="top">
										<td>
											<img src="<?php if(isset($GLOBALS['Existing_App_Logo_Image'])) print $GLOBALS['Existing_App_Logo_Image']; ?>" alt="logo" border="0" />
											<input type="hidden" name="existing_app_logo_image" value="<?php if(isset($GLOBALS['Existing_App_Logo_Image'])) print $GLOBALS['Existing_App_Logo_Image']; ?>" />
										</td>
									</tr>
									<tr>
										<td>
											<input type="hidden" name="MAX_FILE_SIZE" value="2097152" />
											<input type="file" name="Application_Logo_Image" class="Field300" />
											<span class="HelpToolTip"><img src="images/help.gif" alt="?" width="24" height="16" border="0" /><span class="HelpToolTip_Title" style="display:none;"><?php print stripslashes(GetLang('PrivateLabelSettings_ApplicationLogoImage')); ?></span><span class="HelpToolTip_Contents" style="display:none;"><?php print stripslashes(GetLang('HLP_PrivateLabelSettings_ApplicationLogoImage')); ?></span></span>
										</td>
									</tr>
								</table>

							</td>
						</tr>
						<tr>
							<td class="FieldLabel" >
								<?php $tmpTplFile = $tpl->GetTemplate();
			$tmpTplData = $tpl->TemplateData;
			$tpl->ParseTemplate("Not_Required");
			$tpl->SetTemplate($tmpTplFile);
			$tpl->TemplateData = $tmpTplData; ?>
								<?php echo GetLang('PrivateLabelSettings_ApplicationFavicon'); ?>:
							</td>
							<td>
								<table border="0" cellspacing="0" cellpadding="0" width="100%">
									<tr valign="top">
										<td>
											<img src="<?php if(isset($GLOBALS['Existing_App_Favicon'])) print $GLOBALS['Existing_App_Favicon']; ?>" alt="logo" border="0" />
											<input type="hidden" name="existing_app_favicon" value="<?php if(isset($GLOBALS['Existing_App_Favicon'])) print $GLOBALS['Existing_App_Favicon']; ?>" />
										</td>
									</tr>
									<tr>
										<td>
											<input type="hidden" name="MAX_FILE_SIZE" value="2097152" />
											<input type="file" name="Application_Favicon" class="Field300" />
											<span class="HelpToolTip"><img src="images/help.gif" alt="?" width="24" height="16" border="0" /><span class="HelpToolTip_Title" style="display:none;"><?php print stripslashes(GetLang('PrivateLabelSettings_ApplicationFavicon')); ?></span><span class="HelpToolTip_Contents" style="display:none;"><?php print stripslashes(GetLang('HLP_PrivateLabelSettings_ApplicationFavicon')); ?></span></span>
										</td>
									</tr>
								</table>

							</td>
						</tr>
						<tr>
							<td class="FieldLabel" >
								<?php $tmpTplFile = $tpl->GetTemplate();
			$tmpTplData = $tpl->TemplateData;
			$tpl->ParseTemplate("Not_Required");
			$tpl->SetTemplate($tmpTplFile);
			$tpl->TemplateData = $tmpTplData; ?>
								<?php echo GetLang('PrivateLabelSettings_UpdatesCheck'); ?>
							</td>
							<td >
								<input id="Id_Update_Check_Enabled" type="checkbox" name="update_check_enabled" value="1" <?php if(isset($GLOBALS['EnableUpdatesCheck'])) print $GLOBALS['EnableUpdatesCheck']; ?> />
								<label for="Id_Update_Check_Enabled" >
									<?php echo GetLang('PrivateLabelSettings_YesUpdatesCheck'); ?>
								</label>
								<span class="HelpToolTip"><img src="images/help.gif" alt="?" width="24" height="16" border="0" /><span class="HelpToolTip_Title" style="display:none;"><?php print stripslashes(GetLang('PrivateLabelSettings_UpdatesCheck')); ?></span><span class="HelpToolTip_Contents" style="display:none;"><?php print stripslashes(GetLang('HLP_PrivateLabelSettings_UpdatesCheck')); ?></span></span>
							</td>
						</tr>
						<tr>
							<td class="FieldLabel" >
								<?php $tmpTplFile = $tpl->GetTemplate();
			$tmpTplData = $tpl->TemplateData;
			$tpl->ParseTemplate("Not_Required");
			$tpl->SetTemplate($tmpTplFile);
			$tpl->TemplateData = $tmpTplData; ?>
								<?php echo GetLang('PrivateLabelSettings_SmtpSending'); ?>
							</td>
							<td >
								<input id="Id_Show_Smtp_Com_Option" type="checkbox" name="show_smtp_com_option" value="1" <?php if(isset($GLOBALS['ShowSmtpComOption'])) print $GLOBALS['ShowSmtpComOption']; ?> />
								<label for="Id_Show_Smtp_Com_Option" >
									<?php echo GetLang('PrivateLabelSettings_YesSmtpSending'); ?>
								</label>
								<span class="HelpToolTip"><img src="images/help.gif" alt="?" width="24" height="16" border="0" /><span class="HelpToolTip_Title" style="display:none;"><?php print stripslashes(GetLang('PrivateLabelSettings_SmtpSending')); ?></span><span class="HelpToolTip_Contents" style="display:none;"><?php print stripslashes(GetLang('HLP_PrivateLabelSettings_SmtpSending')); ?></span></span>
							</td>
						</tr>
						<tr>
							<td class="FieldLabel" >
								<?php $tmpTplFile = $tpl->GetTemplate();
			$tmpTplData = $tpl->TemplateData;
			$tpl->ParseTemplate("Not_Required");
			$tpl->SetTemplate($tmpTplFile);
			$tpl->TemplateData = $tmpTplData; ?>
								<?php echo GetLang('PrivateLabelSettings_GettingStartedVideo'); ?>
							</td>
							<td>
								<input id="Id_Show_Intro_Video" type="checkbox" name="show_intro_video" value="1" <?php if(isset($GLOBALS['ShowIntroVideo'])) print $GLOBALS['ShowIntroVideo']; ?> />
								<label for="Id_Show_Intro_Video" >
									<?php echo GetLang('PrivateLabelSettings_YesGettingStartedVideo'); ?>
								</label>
								<span class="HelpToolTip"><img src="images/help.gif" alt="?" width="24" height="16" border="0" /><span class="HelpToolTip_Title" style="display:none;"><?php print stripslashes(GetLang('PrivateLabelSettings_GettingStartedVideo')); ?></span><span class="HelpToolTip_Contents" style="display:none;"><?php print stripslashes(GetLang('HLP_PrivateLabelSettings_GettingStartedVideo')); ?></span></span>
							</td>
						</tr>
					</table>
				</div>
				<?php if(!$tpl->Get('DisplayPrivateLabel')): ?></div><?php endif; ?>
				<div id="div6" style="padding-top: 10px; display: <?php if($tpl->Get('ShowTab') == 6): ?>block<?php else: ?>none<?php endif; ?>;">
					<?php if(isset($GLOBALS['Settings_AddonsDisplay'])) print $GLOBALS['Settings_AddonsDisplay']; ?>
				</div>
			</div>
		</td>
	</tr>
	<tr>
		<td>
			<input type="hidden" name="database_type" value="<?php if(isset($GLOBALS['DatabaseType'])) print $GLOBALS['DatabaseType']; ?>" />
			<input name="setting_submit" class="FormButton SubmitButton" type="submit" value="<?php print GetLang('Save'); ?>" />
			<input class="FormButton CancelButton" type="button" value="<?php print GetLang('Cancel'); ?>" />
		</td>
	</tr>
</table>
</form>

	<script>
	function ShowReport(reporttype)
	{
		var link = 'index.php?Page=Settings&Action=ViewDisabled&Report=' + reporttype;

		var top = screen.height / 2 - (230);
		var left = screen.width / 2 - (250);

		window.open(link,"reportWin","left=" + left + ",top="+top+",toolbar=false,status=no,directories=false,menubar=false,scrollbars=false,resizable=false,copyhistory=false,width=500,height=460");
	}

	function LoadAddonSettings(addon_name, addon_title)
	{
		tb_show(addon_title, 'index.php?Page=Settings&Action=Addons&SubAction=configure&Addon=' + escape(addon_name) + '&keepThis=true&TB_iframe=true&height=320&width=450&', '');
	}
	</script>
<?php if(isset($GLOBALS['ExtraScript'])) print $GLOBALS['ExtraScript']; ?>
