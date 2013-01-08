<?php $IEM = $tpl->Get('IEM'); ?><script src="includes/js/jquery/plugins/jquery.plugin.js"></script>
<script src="includes/js/jquery/plugins/jquery.tableSelector.js"></script>
<script src="includes/js/imodal/imodal.js"></script>
<script src="includes/js/jquery/plugins/jquery.window.js"></script>
<script src="includes/js/jquery/plugins/jquery.window-extensions.js"></script>

<link rel="stylesheet" href="includes/js/imodal/imodal.css" type="text/css" media="screen" />


<script>

	var newsletterData = '';
	
	$(function() {
		$(document.frmEditNewsletter).submit(function() {
			if (this.subject.value == '') {
				alert("<?php print GetLang('PleaseEnterNewsletterSubject'); ?>");
				this.subject.focus();
				return false;
			}
			syncHTMLEditor();
			Application.Modules.SpamCheck.form = document.frmEditNewsletter;
			if ('<?php if(isset($GLOBALS['ForceSpamCheck'])) print $GLOBALS['ForceSpamCheck']; ?>' == '1' && !Application.Modules.SpamCheck.check_passed) {
				tb_show('<?php print GetLang('Spam_Guide_Heading'); ?>', 'index.php?Page=Newsletters&Action=CheckSpamDisplay&Force=true&keepThis=true&TB_iframe=tue&height=480&width=600', '');
				return false;
			}
			return true
		});

		$('.CancelButton').click(function() { if(confirm("<?php if(isset($GLOBALS['CancelButton'])) print $GLOBALS['CancelButton']; ?>")) { document.location="index.php?Page=Newsletters" } });
		$('.SaveButton').click(function() { document.frmEditNewsletter.action = 'index.php?Page=Newsletters&Action=<?php if(isset($GLOBALS['SaveAction'])) print $GLOBALS['SaveAction']; ?>'; $(document.frmEditNewsletter).submit(); });
		$('.SaveExitButton').click(function() { document.frmEditNewsletter.action = 'index.php?Page=Newsletters&Action=<?php if(isset($GLOBALS['Action'])) print $GLOBALS['Action']; ?>'; });

		$(document.frmEditNewsletter.cmdCheckSpam).click(function() {
			syncHTMLEditor();
			tb_show('<?php print GetLang('Spam_Guide_Heading'); ?>', 'index.php?Page=Newsletters&Action=CheckSpamDisplay&keepThis=true&TB_iframe=tue&height=480&width=600', '');
			return true;
		});

		$(document.frmEditNewsletter.cmdViewCompatibility).click(function() {
			var f = document.frmEditNewsletter;
			f.target = "_blank";

			prevAction = f.action;
			f.action = "index.php?Page=Newsletters&Action=ViewCompatibility&ShowBroken=1";
			f.submit();

			f.target = "";
			f.action = prevAction;
		});

		$(document.frmEditNewsletter.cmdPreviewEmail).click(function() {
			if (document.frmEditNewsletter.PreviewEmail.value == "") {
				alert("<?php print GetLang('EnterPreviewEmail'); ?>");
				document.frmEditNewsletter.PreviewEmail.focus();
				return false;
			}

			tb_show('<?php print GetLang('SendPreview'); ?>', 'index.php?Page=Newsletters&Action=SendPreviewDisplay&keepThis=true&TB_iframe=tue&height=250&width=550', '');
			return true;
		});

	});

	// Create an instance of the multiSelector class, pass it the output target and the max number of files
	<?php if($tpl->Get('ShowAttach') === true): ?>
		$(function() {
			var multi_selector = new MultiSelector( document.getElementById( 'files_list' ), 5 );
			multi_selector.addElement( document.getElementById( 'fileUpload' ) );
		});
	<?php endif; ?>
	
	function Upload() {
		if (document.frmEditNewsletter.newsletterfile.value == "") {
			alert('<?php print GetLang('NewsletterFileEmptyAlert'); ?>');
			document.frmEditNewsletter.newsletterfile.focus();
			return false;
		}
		$('.SaveButton').click();
	}

	function Import() {
		if (document.frmEditNewsletter.newsletterurl.value == "" || document.frmEditNewsletter.newsletterurl.value == 'http://') {
			alert('<?php print GetLang('NewsletterURLEmptyAlert'); ?>');
			document.frmEditNewsletter.newsletterurl.focus();
			return false;
		}
		$('.SaveButton').click();
	}

	function closePopup() {
		tb_remove();
	}

	function getMessage() {
		var message = {};
		if(document.frmEditNewsletter.myDevEditControl_html) message['myDevEditControl_html'] = document.frmEditNewsletter.myDevEditControl_html.value;
		if(document.frmEditNewsletter.TextContent) message['TextContent'] = document.frmEditNewsletter.TextContent.value;
		return message;
	}

	function getSendPreviewParam() {
		var f = document.frmEditNewsletter;
		var html = f.myDevEditControl_html ? f.myDevEditControl_html.value : '';
		// if the WYSIWYG editor is not disabled, get the very latest HTML
		if (Application.WYSIWYGEditor.isWysiwygEditorActive()) {
			html = Application.WYSIWYGEditor.getContent();
		}
		return {	'subject': f.subject.value,
					'myDevEditControl_html': html,
					'TextContent': f.TextContent ? f.TextContent.value : '',
					'PreviewEmail': f.PreviewEmail.value,
					'FromPreviewEmail': f.FromPreviewEmail.value,
					'id': <?php if(isset($GLOBALS['PreviewID'])) print $GLOBALS['PreviewID']; ?>
				};
	}

	function syncHTMLEditor() {
		if (Application.WYSIWYGEditor.isWysiwygEditorActive()) {
			if (document.frmEditNewsletter.myDevEditControl_html) {
				document.frmEditNewsletter.myDevEditControl_html.value = Application.WYSIWYGEditor.getContent();
			}
		}
	}

</script>
<form name="frmEditNewsletter" method="post" action="index.php?Page=Newsletters&Action=<?php if(isset($GLOBALS['Action'])) print $GLOBALS['Action']; ?>" enctype="multipart/form-data">
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
				<input class="FormButton SaveButton" type="button" value="<?php print GetLang('SaveAndKeepEditing'); ?>" style="width:130px" />
				<input class="FormButton_wide SaveExitButton" type="submit" value="<?php print GetLang('SaveAndExit'); ?>"/>
				<input class="FormButton CancelButton" type="button" value="<?php print GetLang('Cancel'); ?>"/>
				<br />
				&nbsp;
				<table border="0" cellspacing="0" cellpadding="2" width="100%" class="Panel">
					<tr>
						<td colspan="2" class="Heading2">
							&nbsp;&nbsp;<?php print GetLang('Newsletter_Details'); ?>
						</td>
					</tr>
					<tr>
						<td width="10%" class="FieldLabel">
							<img src="images/blank.gif" width="200" height="1" /><br />
							<?php $tmpTplFile = $tpl->GetTemplate();
			$tmpTplData = $tpl->TemplateData;
			$tpl->ParseTemplate("Required");
			$tpl->SetTemplate($tmpTplFile);
			$tpl->TemplateData = $tmpTplData; ?>
							<?php print GetLang('NewsletterSubject'); ?>:
						</td>
						<td width="90%">
							<input type="text" name="subject" value="<?php if(isset($GLOBALS['Subject'])) print $GLOBALS['Subject']; ?>" class="Field250" style="width:300px">&nbsp;<span class="HelpToolTip"><img src="images/help.gif" alt="?" width="24" height="16" border="0" /><span class="HelpToolTip_Title" style="display:none;"><?php print stripslashes(GetLang('NewsletterSubject')); ?></span><span class="HelpToolTip_Contents" style="display:none;"><?php print stripslashes(GetLang('HLP_NewsletterSubject')); ?></span></span>
							<br/><?php print GetLang('Subject_Guide_Link'); ?>
						</td>
					</tr>

					<?php if(isset($GLOBALS['Editor'])) print $GLOBALS['Editor']; ?>

					<tr>
						<td colspan="2" class="EmptyRow">
							&nbsp;
						</td>
					</tr>
					<tr>
						<td colspan="2" class="Heading2">
							&nbsp;&nbsp;<?php print GetLang('Attachments'); ?>
						</td>
					</tr>
					   
					<?php if($tpl->Get('ShowAttach') === true): ?>
						<tr>
							<td valign="top" class="FieldLabel">
								<?php $tmpTplFile = $tpl->GetTemplate();
			$tmpTplData = $tpl->TemplateData;
			$tpl->ParseTemplate("Not_Required");
			$tpl->SetTemplate($tmpTplFile);
			$tpl->TemplateData = $tmpTplData; ?>
								<?php print GetLang('Attachments'); ?>:&nbsp;
							</td>
							<td>
								<table border="0" cellspacing="0" cellpadding="0" id="AttachmentsTable">
									<tr>
										<td>
											<input type="file" name="attachments[]" value="" class="FormButton" id="fileUpload" style="width: 200px">&nbsp;<span class="HelpToolTip"><img src="images/help.gif" alt="?" width="24" height="16" border="0" /><span class="HelpToolTip_Title" style="display:none;"><?php print stripslashes(GetLang('Attachments')); ?></span><span class="HelpToolTip_Contents" style="display:none;"><?php print stripslashes(GetLang('HLP_Attachments')); ?></span></span>
											<div id="files_list" style="margin-top: 5px"></div>
										</td>
									</tr>
								</table>
							</td>
						</tr>
						<tr>
							<td colspan="2" align="left">
								<?php if(isset($GLOBALS['AttachmentsList'])) print $GLOBALS['AttachmentsList']; ?>
							</td>
						</tr>
					<?php else: ?>
						<tr>						
							<td class="FieldLabel">
							</td>
							<td colspan="2">
								<p>
									<?php if(isset($GLOBALS['AttachmentsMsg'])) print $GLOBALS['AttachmentsMsg']; ?>
								</p>
							</td>
						</tr>
					<?php endif; ?>
					<tr>
						<td colspan="2" class="Heading2">
							&nbsp;&nbsp;<?php print GetLang('EmailValidation'); ?>
						</td>
					</tr>
					<tr>
						<td class="FieldLabel">
							<?php $tmpTplFile = $tpl->GetTemplate();
			$tmpTplData = $tpl->TemplateData;
			$tpl->ParseTemplate("Not_Required");
			$tpl->SetTemplate($tmpTplFile);
			$tpl->TemplateData = $tmpTplData; ?>
							<?php print GetLang('SpamKeywordsCheck'); ?>:
						</td>
						<td>
							<input type="button" name="cmdCheckSpam" class="Field300" value="<?php print GetLang('SpamKeywordsCheck_Button'); ?>"/>
						</td>
					</tr>
					<tr>
						<td class="FieldLabel">
							<?php $tmpTplFile = $tpl->GetTemplate();
			$tmpTplData = $tpl->TemplateData;
			$tpl->ParseTemplate("Not_Required");
			$tpl->SetTemplate($tmpTplFile);
			$tpl->TemplateData = $tmpTplData; ?>
							<?php print GetLang('EmailClientCompatibility'); ?>:
						</td>
						<td>
							<input type="button" name="cmdViewCompatibility" class="Field300" value="<?php print GetLang('EmailClientCompatibility_Button'); ?>"/>
						</td>
					</tr>
					<tr>
						<td colspan="2" class="EmptyRow">
							&nbsp;
						</td>
					</tr>
					<tr>
						<td colspan="2" class="Heading2">
							&nbsp;&nbsp;<?php print GetLang('MiscellaneousOptions'); ?>
						</td>
					</tr>
					<tr>
						<td class="FieldLabel">
							<?php $tmpTplFile = $tpl->GetTemplate();
			$tmpTplData = $tpl->TemplateData;
			$tpl->ParseTemplate("Not_Required");
			$tpl->SetTemplate($tmpTplFile);
			$tpl->TemplateData = $tmpTplData; ?>
							<?php print GetLang('NewsletterIsActive'); ?>:
						</td>
						<td>
							<label for="active">
							<input type="checkbox" name="active" id="active" value="1"<?php if(isset($GLOBALS['IsActive'])) print $GLOBALS['IsActive']; ?>>
							<?php print GetLang('NewsletterIsActiveExplain'); ?>
							</label>
							<span class="HelpToolTip"><img src="images/help.gif" alt="?" width="24" height="16" border="0" /><span class="HelpToolTip_Title" style="display:none;"><?php print stripslashes(GetLang('NewsletterIsActive')); ?></span><span class="HelpToolTip_Contents" style="display:none;"><?php print stripslashes(GetLang('HLP_NewsletterIsActive')); ?></span></span>
						</td>
					</tr>
					<tr>
						<td class="FieldLabel">
							<?php $tmpTplFile = $tpl->GetTemplate();
			$tmpTplData = $tpl->TemplateData;
			$tpl->ParseTemplate("Not_Required");
			$tpl->SetTemplate($tmpTplFile);
			$tpl->TemplateData = $tmpTplData; ?>
							<?php print GetLang('NewsletterArchive'); ?>:
						</td>
						<td>
							<label for="archive">
							<input type="checkbox" name="archive" id="archive" value="1"<?php if(isset($GLOBALS['Archive'])) print $GLOBALS['Archive']; ?>>
							<?php print GetLang('NewsletterArchiveExplain'); ?>
							</label>
							<span class="HelpToolTip"><img src="images/help.gif" alt="?" width="24" height="16" border="0" /><span class="HelpToolTip_Title" style="display:none;"><?php print stripslashes(GetLang('NewsletterArchive')); ?></span><span class="HelpToolTip_Contents" style="display:none;"><?php print stripslashes(GetLang('HLP_NewsletterArchive')); ?></span></span>
						</td>
					</tr>
					<tr>
						<td colspan="2" class="EmptyRow">
							&nbsp;
						</td>
					</tr>
					<tr>
						<td colspan="2" class="Heading2">
							&nbsp;&nbsp;<?php print GetLang('SendPreview'); ?>
						</td>
					</tr>
					<tr>
						<td valign="top" class="FieldLabel">
							<?php $tmpTplFile = $tpl->GetTemplate();
			$tmpTplData = $tpl->TemplateData;
			$tpl->ParseTemplate("Not_Required");
			$tpl->SetTemplate($tmpTplFile);
			$tpl->TemplateData = $tmpTplData; ?>
							<?php print GetLang('SendPreviewFrom'); ?>:
						</td>
						<td>
							<input type="text" name="FromPreviewEmail" value="<?php if(isset($GLOBALS['FromPreviewEmail'])) print $GLOBALS['FromPreviewEmail']; ?>" class="Field" style="width:150px">
						</td>
					</tr>
					<tr>
						<td valign="top" class="FieldLabel">
							<?php $tmpTplFile = $tpl->GetTemplate();
			$tmpTplData = $tpl->TemplateData;
			$tpl->ParseTemplate("Not_Required");
			$tpl->SetTemplate($tmpTplFile);
			$tpl->TemplateData = $tmpTplData; ?>
							<?php print GetLang('SendPreviewTo'); ?>:
						</td>
						<td>
							<input type="text" name="PreviewEmail" value="" class="Field" style="width:150px">
							<input type="button" name="cmdPreviewEmail" value="<?php print GetLang('SendPreview'); ?>" class="Field"/>
							<span class="HelpToolTip"><img src="images/help.gif" alt="?" width="24" height="16" border="0" /><span class="HelpToolTip_Title" style="display:none;"><?php print stripslashes(GetLang('SendPreview')); ?></span><span class="HelpToolTip_Contents" style="display:none;"><?php print stripslashes(GetLang('HLP_SendPreview')); ?></span></span>
						</td>
					</tr>
					<tr>
						<td colspan="2">
							&nbsp;
						</td>
					</tr>
				</table>
				<table width="100%" cellspacing="0" cellpadding="2" border="0" class="PanelPlain">
					<tr>
						<td width="200" class="FieldLabel"></td>
						<td>
							<input class="FormButton SaveButton" type="button" value="<?php print GetLang('SaveAndKeepEditing'); ?>" style="width:130px" />
							<input class="FormButton_wide SaveExitButton" type="submit" value="<?php print GetLang('SaveAndExit'); ?>" />
							<input class="FormButton CancelButton" type="button" value="<?php print GetLang('Cancel'); ?>" />
						</td>
					</tr>
				</table>
			</td>
		</tr>
	</table>
</form>
