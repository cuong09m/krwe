<?php $IEM = $tpl->Get('IEM'); ?>
<?php ob_start(); ?><?php echo strtolower($tpl->Get('IEM','CurrentPage')); ?><?php $tpl->Assign("currentPage", ob_get_contents()); ob_end_clean(); ?>


<?php ob_start(); ?><?php if($tpl->Get('currentPage') == 'lists'): ?>padding-left:20px;<?php endif; ?><?php $tpl->Assign("style", ob_get_contents()); ob_end_clean(); ?>


<?php ob_start(); ?><?php if($tpl->Get('currentPage') == 'bounce'): ?><img src="images/nodejoin.gif" /><?php endif; ?><?php $tpl->Assign("nodejoin", ob_get_contents()); ob_end_clean(); ?>
<?php ob_start(); ?><?php if($tpl->Get('currentPage') == 'bounce'): ?><img width="20" height="20" src="images/blank.gif" /><?php endif; ?><?php $tpl->Assign("blankimg", ob_get_contents()); ob_end_clean(); ?>

<?php if($tpl->Get('currentPage') != 'bounce' && $tpl->Get('currentPage') != 'settings'): ?>
	<tr>
		<td class="EmptyRow" colspan="2" style="display: <?php if(isset($GLOBALS['ShowBounceInfo'])) print $GLOBALS['ShowBounceInfo']; ?>">
<?php endif; ?>
<script>
	Application.Page.BounceInfo = {
		process_form: ('<?php if(isset($GLOBALS['ShowBounceInfo'])) print $GLOBALS['ShowBounceInfo']; ?>' == ''),
		bounce_form: null,
		bounce_process: null,
		bounce_agreedelete: null,
		extraSettingsPattern: {'notls': 'notls',
								'novalidate-cert': 'novalidate',
								'nossl': 'nossl'},

		eventDOMReady: function(event) {
			// we cannot use 'this' because the event overrides it with the DOM
			var bi = Application.Page.BounceInfo;

			if (bi.process_form) {
				// setup data members that need DOM elements
				bi.bounce_form = $('#bounce_server').parents().filter("form").get(0);
				bi.bounce_process = $('#bounce_process').get(0);
				bi.bounce_agreedelete = $('#bounce_agreedelete').get(0);
				// setup the DOM
				bi.SetupClickEvents();
				bi.SetupSubmitEvents();
				bi.RevealBounceOptions();
				bi.RevealExtraMailSettings();

			}
		},

		/**
		 * Closes the bounce test thickbox.
		 */
		closeBounceTest: function() {
			tb_remove();
		},

		/**
		 * Collects the parameters to connect to the bounce server.
		 *
		 * @return String A parameter string to use in a query string.
		 */
		getBounceParameters: function() {
			if (!this.validateFields()) {
				return false;
			}
			var options = '&';
			var key = $('#token').text();
			$($('.bounceSettings').fieldSerialize().split('&')).each(function(i, n) {
				var temp = n.split('=');
				if (temp.length == 2) {
					if (temp[0] == 'bounce_password') {
						temp[1] = Application.Util.encrypt(unescape(temp[1]), key);
					}
					options = options + temp[0] + '=' + temp[1] + '&';
				}
			});
			return options;
		},

		/**
		 * Transforms the extra mail checkbox settings into their corresponding extrasettings string.
		 *
		 * @return Boolean True if the checkboxes were adjusted successfully, otherwise false.
		 */
		evaluateExtraSettings: function() {
			try {
				if (!this.bounce_form.bounce_extraoption.checked) {
					if (this.bounce_form.extramail_others.checked && !this.bounce_form.extramail_others_value.value.match(/^\//)) {
						alert("<?php echo GetLang('InvalidExtraMailSettings'); ?>");
						this.bounce_form.extramail_others_value.focus();
						return false;
					}
					var tempSettings = [];
					for (var i in this.extraSettingsPattern) {
						if (this.bounce_form['extramail_' + this.extraSettingsPattern[i]].checked) {
							tempSettings.push(i);
						}
					}
					this.bounce_form.bounce_extrasettings.value = (tempSettings.length > 0? '/' + tempSettings.join('/') : '') + (this.bounce_form.extramail_others.checked ? this.bounce_form.extramail_others_value.value : '');
				} else {
					for (var i in this.extraSettingsPattern) {
						this.bounce_form['extramail_' + this.extraSettingsPattern[i]].checked = false;
					}
					this.bounce_form.bounce_extrasettings.value = '';
				}
				if (this.bounce_form.bounce_extrasettings.value == '0') {
					this.bounce_form.bounce_extrasettings.value = '';
				}
			} catch (e) {
				alert('<?php echo GetLang('UnableEvaluateExtraMailSettings'); ?>');
				return false;
			}

			return true;
		},

		/**
		 * Checks the corresponding checkboxes to the current value of the extrasettings.
		 */
		evaluateCheckboxes: function() {
			var master = this.bounce_form.bounce_extrasettings.value;
			var other_box = this.bounce_form.extramail_others_value;
			var auto_button = this.bounce_form.bounce_extraoption;
			// If the auto-detect button is already unchecked, we shouldn't do anything.
			if (!auto_button.checked) {
				return;
			}
			$(auto_button).click();
			// Loop over each checkbox.
			$('#showextramailsettings :checkbox').each(function(i, checkbox) {
				var name = checkbox.name.split('_')[1];
				if (name == 'others') {
					// The 'Other' checkbox needs to be handled specially.
					var cur = '';
					$(['/ssl', '/tls']).each(function(j, option) {
						if (master.indexOf(option) >= 0) {
							cur += option;
						}
					});
					if (cur.length > 0) {
						// We don't just use the checkbox's click() event for this because:
						// - jQuery fires click() before it puts a check in the checkbox (unlike the browser), and
						// - IE doesn't seem to check the box at all when click() is called on it.
						$(checkbox).attr('checked', true);
						other_box.disabled = false;
						other_box.value = cur;
					}
					return;
				}
				// If the checkbox setting is in the extra settings, check it.
				if (master.indexOf(name) >= 0) {
					$(checkbox).attr('checked', true);
				}
			});
		},

		/**
		 * Set up the various onClick event handlers.
		 */
		SetupClickEvents: function() {
			var bi = this;
			var f = bi.bounce_form;
			// Toggle the extra mail settings
			$(f.bounce_extraoption).click(function() { $('#showextramailsettings').toggle(); });
			// Enable extra mail settings when revealed
			$(f.extramail_others).click(function() { f.extramail_others_value.disabled = !this.checked; });
			// Perform the bounce test if the button is there
			f.cmdTestBounce && $(f.cmdTestBounce).click(function() {
				if (!bi.validateFields()) {
					return false;
				}
				var url = 'index.php?Page=Bounce&Action=PopupBounceTest<?php if($tpl->Get('currentPage') != 'bounce'): ?>&InPlace=true<?php endif; ?>' + Application.Page.BounceInfo.getBounceParameters() + '&keepThis=true&TB_iframe=true&height=240&width=400&modal=true&random=' + new Date().getTime();
				tb_show('', url, '');
				return true;
			});
			<?php if($tpl->Get('currentPage') != 'bounce'): ?>
			
			// Reveal all of the bounce options if they want to process bounces
			$('#bounce_process').click(function() {
				$('.YesProcessBounce').toggle(true);
				if (!this.checked) {
					$('.YesProcessBounce').toggle(false);
					$('.SubmitButton').attr('disabled', false);
					$('.YesProcessBounce').hide();
				}
			});
			<?php endif; ?>
			$('#bounce_agreedeleteall').click(function() {
				// give them a warning after they check the option
				if (this.checked) {
					<?php if($tpl->Get('currentPage') == 'bounce'): ?>
					var prompter = '<?php echo GetLang('ProcessBounceDeleteAll_ManualPrompt'); ?>';
					<?php else: ?>
					var prompter = '<?php echo GetLang('ProcessBounceDeleteAll_AutoPrompt'); ?>';
					<?php endif; ?>
					this.checked = confirm(prompter);
				}
			});
		},

		/**
		 * Set up the various onSubmit event handlers.
		 */
		SetupSubmitEvents: function() {
			// Ensure the Bounce Details Fields are populated.
			var bounce_details = this;
			$(bounce_details.bounce_form).submit(function(event) {
				try {
					var bounceFrm = bounce_details.bounce_form;
					// Don't check if they're not doing bounce processing.
					if (!bounceFrm.bounce_process || (bounceFrm.bounce_process && bounceFrm.bounce_process.checked)) {
						return bounce_details.validateFields();
					} else {
						Application.Page.BounceInfo.ClearBounceSettings();
					}
				} catch (e) {
					alert('Unable to validate');
					return false;
				}
			});
		},

		/**
		 * Reveal the bounce options if applicable, otherwise hide them.
		 */
		RevealBounceOptions: function() {
			// If showing the bounce options is optional, check if we should show them.
			if (!this.bounce_process) {
				return;
			}

			if ( (this.bounce_process.checked )&& '<?php if(isset($GLOBALS['ShowBounceInfo'])) print $GLOBALS['ShowBounceInfo']; ?>' != 'none') {
				$('.YesProcessBounce').show();
			} else {
				$('.YesProcessBounce').hide();
			}
		},

		/**
		 * Reveal the extra mail settings if applicable, otherwise hide them.
		 */
		RevealExtraMailSettings: function() {
			if (!this.bounce_form.bounce_extraoption.checked || (this.bounce_form.bounce_extrasettings && this.bounce_form.bounce_extrasettings != '')) {
				this.bounce_form.bounce_extraoption.checked = false;
				if (this.bounce_form.bounce_extrasettings.value == '') {
					this.bounce_form.bounce_extraoption.checked = true;
					$('#showextramailsettings').hide();
				} else {
					var tempSettings = this.bounce_form.bounce_extrasettings.value.split('/');
					var tempOthers = [];
					for (var i=0, j=tempSettings.length; i<j; i++) {
						if (tempSettings[i] == '') {
							continue;
						}
						if (!this.extraSettingsPattern[tempSettings[i]]) {
							tempOthers.push(tempSettings[i]);
						} else {
							this.bounce_form['extramail_' + this.extraSettingsPattern[tempSettings[i]]].checked = true;
						}
					}
					if (tempOthers.length > 0) {
						this.bounce_form.extramail_others.checked = true;
						this.bounce_form.extramail_others_value.value = '/' + tempOthers.join('/');
						this.bounce_form.extramail_others_value.disabled = false;
					} else {
						this.bounce_form.extramail_others.checked = false;
						this.bounce_form.extramail_others_value.disabled = true;
					}
				}
			}
		},

		/**
		 * Clears all the bounce options. This is useful to avoid stale values getting saved when we don't want to process bounces any more.
		 */
		ClearBounceSettings: function() {
			$('.YesProcessBounce input[type!=button]').each(function() {
				if (this.value) {
					this.value = '';
				}
				if (this.checked) {
					this.checked = false;
				}
				if (this.name == 'bounce_extraoption') {
					this.checked = true;
				}
				$('#showextramailsettings').hide();
			});
		},

		/**
		 * Validates the bounce server, bounce username and bounce password fields.
		 */
		validateFields: function() {
			var form = this.bounce_form;

			// check that a bounce server name has been entered
			if (form.bounce_server.value.trim() == '') {
				alert("<?php echo GetLang('EnterBounceServer'); ?>");
				form.bounce_server.focus();
				return false;
			}

			// check that a username has been entered1
			if  (form.bounce_username.value.trim() == '') {
				alert("<?php echo GetLang('EnterBounceUsername'); ?>");
				form.bounce_username.focus();
				return false;
			}

			// check that a password has been entered for the bounce email account
			if  (form.bounce_password.value.trim() == '') {
				alert("<?php echo GetLang('EnterBouncePassword'); ?>");
				form.bounce_password.focus();
				return false;
			}

			return this.evaluateExtraSettings();
		}
	};

	Application.init.push(Application.Page.BounceInfo.eventDOMReady);

</script>
<?php if($tpl->Get('currentPage') != 'bounce'): ?>
		</td>
	</tr>
<?php endif; ?>
<?php if(in_array($tpl->Get('currentPage'), array('lists', 'settings'))): ?>
	<tr style="display: <?php if(isset($GLOBALS['ShowBounceInfo'])) print $GLOBALS['ShowBounceInfo']; ?>">
		<td colspan="2" class="Heading2">
			&nbsp;&nbsp;<?php print GetLang('BounceAccountDetails'); ?>
		</td>
	</tr>
	<tr style="display: <?php if(isset($GLOBALS['ShowBounceInfo'])) print $GLOBALS['ShowBounceInfo']; ?>">
		<td class="FieldLabel">
			<?php $tmpTplFile = $tpl->GetTemplate();
			$tmpTplData = $tpl->TemplateData;
			$tpl->ParseTemplate("Not_Required");
			$tpl->SetTemplate($tmpTplFile);
			$tpl->TemplateData = $tmpTplData; ?>
			<?php if($tpl->Get('currentPage') == 'settings'): ?><?php print GetLang('SetDefaultBounceAccountDetails'); ?><?php else: ?><?php print GetLang('ProcessBouncesLabel'); ?><?php endif; ?>:&nbsp;
		</td>
		<td>
			<input type="checkbox" name="bounce_process" id="bounce_process" value="1" <?php if(isset($GLOBALS['ProcessBounceChecked'])) print $GLOBALS['ProcessBounceChecked']; ?>/><label for="bounce_process"><?php if($tpl->Get('currentPage') == 'settings'): ?><?php print GetLang('SetDefaultBounceAccountDetailsExplain'); ?><?php else: ?><?php print GetLang('YesProcessBounces'); ?><?php endif; ?></label>
			<?php if($tpl->Get('currentPage') == 'settings'): ?>
			<span class="HelpToolTip"><img src="images/help.gif" alt="?" width="24" height="16" border="0" /><span class="HelpToolTip_Title" style="display:none;"><?php print stripslashes(GetLang('SetDefaultBounceAccountDetails')); ?></span><span class="HelpToolTip_Contents" style="display:none;"><?php print stripslashes(GetLang('HLP_SetDefaultBounceAccountDetails')); ?></span></span>
			<?php else: ?>
			<br/><a href="#" onClick="LaunchHelp('798'); return false;" style="margin-left:30px; color: gray;"><?php print GetLang('ProcessBounceGuideLink'); ?></a>
			<?php endif; ?>
		</td>
	</tr>
<?php endif; ?>
<?php if($tpl->Get('currentPage') == 'settings'): ?>
	<tr style="display: <?php if(isset($GLOBALS['ShowBounceInfo'])) print $GLOBALS['ShowBounceInfo']; ?>" class="YesProcessBounce">
		<td class="FieldLabel">
			<?php $tmpTplFile = $tpl->GetTemplate();
			$tmpTplData = $tpl->TemplateData;
			$tpl->ParseTemplate("Not_Required");
			$tpl->SetTemplate($tmpTplFile);
			$tpl->TemplateData = $tmpTplData; ?>
			<?php print GetLang('DefaultBounceAddress'); ?>:
		</td>
		<td>
			<input type="text" name="bounce_address" id="bounce_address" value="<?php if(isset($GLOBALS['Bounce_Address'])) print $GLOBALS['Bounce_Address']; ?>" class="Field250 bounceSettings"> <span class="HelpToolTip"><img src="images/help.gif" alt="?" width="24" height="16" border="0" /><span class="HelpToolTip_Title" style="display:none;"><?php print stripslashes(GetLang('DefaultBounceAddress')); ?></span><span class="HelpToolTip_Contents" style="display:none;"><?php print stripslashes(GetLang('HLP_DefaultBounceAddress')); ?></span></span>
		</td>
	</tr>
<?php endif; ?>
<tr style="display: <?php if(isset($GLOBALS['ShowBounceInfo'])) print $GLOBALS['ShowBounceInfo']; ?>" class="YesProcessBounce">
	<td class="FieldLabel">
		<?php if($tpl->Get('currentPage') == 'settings'): ?><?php $tmpTplFile = $tpl->GetTemplate();
			$tmpTplData = $tpl->TemplateData;
			$tpl->ParseTemplate("Not_Required");
			$tpl->SetTemplate($tmpTplFile);
			$tpl->TemplateData = $tmpTplData; ?><?php else: ?><?php $tmpTplFile = $tpl->GetTemplate();
			$tmpTplData = $tpl->TemplateData;
			$tpl->ParseTemplate("Required");
			$tpl->SetTemplate($tmpTplFile);
			$tpl->TemplateData = $tmpTplData; ?><?php endif; ?>
		<?php if($tpl->Get('currentPage') == 'settings'): ?><?php print GetLang('DefaultBounceServer'); ?><?php else: ?><?php print GetLang('ListBounceServer'); ?><?php endif; ?>:&nbsp;
	</td>
	<td style="<?php echo $tpl->Get('style'); ?>">
		<?php echo $tpl->Get('nodejoin'); ?> <input type="text" name="bounce_server" id="bounce_server" class="Field250 form_text bounceSettings" value="<?php if(isset($GLOBALS['Bounce_Server'])) print $GLOBALS['Bounce_Server']; ?>">&nbsp;<?php if($tpl->Get('currentPage') == 'settings'): ?><span class="HelpToolTip"><img src="images/help.gif" alt="?" width="24" height="16" border="0" /><span class="HelpToolTip_Title" style="display:none;"><?php print stripslashes(GetLang('DefaultBounceServer')); ?></span><span class="HelpToolTip_Contents" style="display:none;"><?php print stripslashes(GetLang('HLP_DefaultBounceServer')); ?></span></span><?php else: ?><span class="HelpToolTip"><img src="images/help.gif" alt="?" width="24" height="16" border="0" /><span class="HelpToolTip_Title" style="display:none;"><?php print stripslashes(GetLang('ListBounceServer')); ?></span><span class="HelpToolTip_Contents" style="display:none;"><?php print stripslashes(GetLang('HLP_ListBounceServer')); ?></span></span><?php endif; ?>
	</td>
</tr>
<tr style="display: <?php if(isset($GLOBALS['ShowBounceInfo'])) print $GLOBALS['ShowBounceInfo']; ?>" class="YesProcessBounce">
	<td class="FieldLabel">
		<?php if($tpl->Get('currentPage') == 'settings'): ?><?php $tmpTplFile = $tpl->GetTemplate();
			$tmpTplData = $tpl->TemplateData;
			$tpl->ParseTemplate("Not_Required");
			$tpl->SetTemplate($tmpTplFile);
			$tpl->TemplateData = $tmpTplData; ?><?php else: ?><?php $tmpTplFile = $tpl->GetTemplate();
			$tmpTplData = $tpl->TemplateData;
			$tpl->ParseTemplate("Required");
			$tpl->SetTemplate($tmpTplFile);
			$tpl->TemplateData = $tmpTplData; ?><?php endif; ?>
		<?php if($tpl->Get('currentPage') == 'settings'): ?><?php print GetLang('DefaultBounceUsername'); ?><?php else: ?><?php print GetLang('ListBounceUsername'); ?><?php endif; ?>:&nbsp;
	</td>
	<td style="<?php echo $tpl->Get('style'); ?>">
		<?php echo $tpl->Get('blankimg'); ?> <input type="text" name="bounce_username" class="Field250 form_text bounceSettings" value="<?php if(isset($GLOBALS['Bounce_Username'])) print $GLOBALS['Bounce_Username']; ?>">&nbsp;<?php if($tpl->Get('currentPage') == 'settings'): ?><span class="HelpToolTip"><img src="images/help.gif" alt="?" width="24" height="16" border="0" /><span class="HelpToolTip_Title" style="display:none;"><?php print stripslashes(GetLang('DefaultBounceUsername')); ?></span><span class="HelpToolTip_Contents" style="display:none;"><?php print stripslashes(GetLang('HLP_DefaultBounceUsername')); ?></span></span><?php else: ?><span class="HelpToolTip"><img src="images/help.gif" alt="?" width="24" height="16" border="0" /><span class="HelpToolTip_Title" style="display:none;"><?php print stripslashes(GetLang('ListBounceUsername')); ?></span><span class="HelpToolTip_Contents" style="display:none;"><?php print stripslashes(GetLang('HLP_ListBounceUsername')); ?></span></span><?php endif; ?>
	</td>
</tr>
<tr style="display: <?php if(isset($GLOBALS['ShowBounceInfo'])) print $GLOBALS['ShowBounceInfo']; ?>" class="YesProcessBounce">
	<td class="FieldLabel">
		<?php if($tpl->Get('currentPage') == 'settings'): ?><?php $tmpTplFile = $tpl->GetTemplate();
			$tmpTplData = $tpl->TemplateData;
			$tpl->ParseTemplate("Not_Required");
			$tpl->SetTemplate($tmpTplFile);
			$tpl->TemplateData = $tmpTplData; ?><?php else: ?><?php $tmpTplFile = $tpl->GetTemplate();
			$tmpTplData = $tpl->TemplateData;
			$tpl->ParseTemplate("Required");
			$tpl->SetTemplate($tmpTplFile);
			$tpl->TemplateData = $tmpTplData; ?><?php endif; ?>
		<?php if($tpl->Get('currentPage') == 'settings'): ?><?php print GetLang('DefaultBouncePassword'); ?><?php else: ?><?php print GetLang('ListBouncePassword'); ?><?php endif; ?>:&nbsp;
	</td>
	<td style="<?php echo $tpl->Get('style'); ?>">
		<?php echo $tpl->Get('blankimg'); ?> <input type="password" name="bounce_password" class="Field250 form_password bounceSettings" value="<?php if(isset($GLOBALS['Bounce_Password'])) print $GLOBALS['Bounce_Password']; ?>" autocomplete="off" />&nbsp;<?php if($tpl->Get('currentPage') == 'settings'): ?><span class="HelpToolTip"><img src="images/help.gif" alt="?" width="24" height="16" border="0" /><span class="HelpToolTip_Title" style="display:none;"><?php print stripslashes(GetLang('DefaultBouncePassword')); ?></span><span class="HelpToolTip_Contents" style="display:none;"><?php print stripslashes(GetLang('HLP_DefaultBouncePassword')); ?></span></span><?php else: ?><span class="HelpToolTip"><img src="images/help.gif" alt="?" width="24" height="16" border="0" /><span class="HelpToolTip_Title" style="display:none;"><?php print stripslashes(GetLang('ListBouncePassword')); ?></span><span class="HelpToolTip_Contents" style="display:none;"><?php print stripslashes(GetLang('HLP_ListBouncePassword')); ?></span></span><?php endif; ?>
	</td>
</tr>
<tr style="display: <?php if(isset($GLOBALS['ShowBounceInfo'])) print $GLOBALS['ShowBounceInfo']; ?>" class="YesProcessBounce">
	<td class="FieldLabel">
		<?php $tmpTplFile = $tpl->GetTemplate();
			$tmpTplData = $tpl->TemplateData;
			$tpl->ParseTemplate("Not_Required");
			$tpl->SetTemplate($tmpTplFile);
			$tpl->TemplateData = $tmpTplData; ?>
		<?php print GetLang('Bounce_Account_Type'); ?>:&nbsp;
	</td>
	<td style="<?php echo $tpl->Get('style'); ?>">
		<?php echo $tpl->Get('blankimg'); ?>
		<select name="bounce_imap" class="Field250 bounceSettings" >
			<option value="0"<?php if(isset($GLOBALS['Pop3_Selected'])) print $GLOBALS['Pop3_Selected']; ?>><?php print GetLang('Bounce_POP3_Account'); ?></option>
			<option value="1"<?php if(isset($GLOBALS['Imap_Selected'])) print $GLOBALS['Imap_Selected']; ?>><?php print GetLang('Bounce_IMAP_Account'); ?></option>
		</select>
		<span class="HelpToolTip"><img src="images/help.gif" alt="?" width="24" height="16" border="0" /><span class="HelpToolTip_Title" style="display:none;"><?php print stripslashes(GetLang('Bounce_Account_Type')); ?></span><span class="HelpToolTip_Contents" style="display:none;"><?php print stripslashes(GetLang('HLP_Bounce_Account_Type')); ?></span></span>

	</td>
</tr>
<tr style="display: <?php if(isset($GLOBALS['ShowBounceInfo'])) print $GLOBALS['ShowBounceInfo']; ?>" class="YesProcessBounce">
	<td class="FieldLabel">
		<?php $tmpTplFile = $tpl->GetTemplate();
			$tmpTplData = $tpl->TemplateData;
			$tpl->ParseTemplate("Not_Required");
			$tpl->SetTemplate($tmpTplFile);
			$tpl->TemplateData = $tmpTplData; ?>
		<?php print GetLang('Bounce_Adv_Settings'); ?>:
	</td>
	<td style="<?php echo $tpl->Get('style'); ?>">
		<div>
			<?php echo $tpl->Get('blankimg'); ?><label for="bounce_extraoption"><input type="checkbox" name="bounce_extraoption" id="bounce_extraoption" value="1" class="bounceSettings"<?php if(isset($GLOBALS['Bounce_ExtraOption'])) print $GLOBALS['Bounce_ExtraOption']; ?> /><?php print GetLang('Bounce_Adv_Settings_Autodetect'); ?>
			</label> <span class="HelpToolTip"><img src="images/help.gif" alt="?" width="24" height="16" border="0" /><span class="HelpToolTip_Title" style="display:none;"><?php print stripslashes(GetLang('Bounce_Adv_Settings')); ?></span><span class="HelpToolTip_Contents" style="display:none;"><?php print stripslashes(GetLang('HLP_Bounce_Adv_Settings')); ?></span></span>
		</div>
		<div id="showextramailsettings" style="display: <?php if(isset($GLOBALS['DisplayExtraMailSettings'])) print $GLOBALS['DisplayExtraMailSettings']; ?>">
			<input type="hidden" name="bounce_extrasettings" id="bounce_extrasettings" class="bounceSettings" value="<?php if(isset($GLOBALS['Bounce_ExtraSettings'])) print $GLOBALS['Bounce_ExtraSettings']; ?>" />
			<div>
				<?php echo $tpl->Get('blankimg'); ?><img width="20" height="20" src="images/nodejoin.gif"/>
				<label for="extramail_novalidate">
					<input type="checkbox" name="extramail_novalidate" id="extramail_novalidate" value="validate" /><?php print GetLang('ExtraMailSettingsNoValidate_field'); ?>
				</label>&nbsp;<span class="HelpToolTip"><img src="images/help.gif" alt="?" width="24" height="16" border="0" /><span class="HelpToolTip_Title" style="display:none;"><?php print stripslashes(GetLang('ExtraMailSettingsNoValidate')); ?></span><span class="HelpToolTip_Contents" style="display:none;"><?php print stripslashes(GetLang('HLP_ExtraMailSettingsNoValidate')); ?></span></span>
			</div>
			<div>
				<?php echo $tpl->Get('blankimg'); ?><img width="20" height="20" src="images/blank.gif"/>
				<label for="extramail_notls">
					<input type="checkbox" name="extramail_notls" id="extramail_notls" value="tls" /><?php print GetLang('ExtraMailSettingsNoTLS_field'); ?>
				</label>&nbsp;<span class="HelpToolTip"><img src="images/help.gif" alt="?" width="24" height="16" border="0" /><span class="HelpToolTip_Title" style="display:none;"><?php print stripslashes(GetLang('ExtraMailSettingsNoTLS')); ?></span><span class="HelpToolTip_Contents" style="display:none;"><?php print stripslashes(GetLang('HLP_ExtraMailSettingsNoTLS')); ?></span></span>
			</div>
			<div>
				<?php echo $tpl->Get('blankimg'); ?><img width="20" height="20" src="images/blank.gif"/>
				<label for="extramail_nossl">
					<input type="checkbox" name="extramail_nossl" id="extramail_nossl" value="ssl" /><?php print GetLang('ExtraMailSettingsNoSSL_field'); ?>
				</label>&nbsp;<span class="HelpToolTip"><img src="images/help.gif" alt="?" width="24" height="16" border="0" /><span class="HelpToolTip_Title" style="display:none;"><?php print stripslashes(GetLang('ExtraMailSettingsNoSSL')); ?></span><span class="HelpToolTip_Contents" style="display:none;"><?php print stripslashes(GetLang('HLP_ExtraMailSettingsNoSSL')); ?></span></span>
			</div>
			<div>
				<?php echo $tpl->Get('blankimg'); ?><img width="20" height="20" src="images/blank.gif"/>
				<label for="extramail_others">
					<input type="checkbox" name="extramail_others" id="extramail_others" value="others" /><?php print GetLang('ExtraMailSettingsOthers_field'); ?>
				</label>
				<input type="text" name="extramail_others_value" class="Field250 form_text" value="" disabled="disabled" />&nbsp;<span class="HelpToolTip"><img src="images/help.gif" alt="?" width="24" height="16" border="0" /><span class="HelpToolTip_Title" style="display:none;"><?php print stripslashes(GetLang('ExtraMailSettingsOthers')); ?></span><span class="HelpToolTip_Contents" style="display:none;"><?php print stripslashes(GetLang('HLP_ExtraMailSettingsOthers')); ?></span></span>
			</div>
		</div>
	</td>
</tr>
<tr style="display: <?php if(isset($GLOBALS['ShowBounceInfo'])) print $GLOBALS['ShowBounceInfo']; ?>" class="YesProcessBounce">
	<td class="FieldLabel">
		<?php $tmpTplFile = $tpl->GetTemplate();
			$tmpTplData = $tpl->TemplateData;
			$tpl->ParseTemplate("Not_Required");
			$tpl->SetTemplate($tmpTplFile);
			$tpl->TemplateData = $tmpTplData; ?>
		<?php print GetLang('Bounce_Emp_Inbox'); ?>
	</td>
	<td style="<?php echo $tpl->Get('style'); ?>">
		<?php echo $tpl->Get('blankimg'); ?><input type="checkbox" name="bounce_agreedeleteall" id="bounce_agreedeleteall" class="bounceSettings" value="1"<?php if(isset($GLOBALS['Bounce_AgreeDeleteAll'])) print $GLOBALS['Bounce_AgreeDeleteAll']; ?>/><label for="bounce_agreedeleteall"><?php print GetLang('Bounce_Emp_Inbox_Text'); ?></label>&nbsp;<span class="HelpToolTip"><img src="images/help.gif" alt="?" width="24" height="16" border="0" /><span class="HelpToolTip_Title" style="display:none;"><?php print stripslashes(GetLang('ProcessBounceDeleteAll')); ?></span><span class="HelpToolTip_Contents" style="display:none;"><?php print stripslashes(GetLang('HLP_ProcessBounceDeleteAll')); ?></span></span>
	</td>
</tr>
<?php if($tpl->Get('currentPage') == 'bounce'): ?>
	<tr style="display: <?php if(isset($GLOBALS['ShowBounceInfo'])) print $GLOBALS['ShowBounceInfo']; ?>" class="YesProcessBounce">
		<td class="FieldLabel">
			<?php $tmpTplFile = $tpl->GetTemplate();
			$tmpTplData = $tpl->TemplateData;
			$tpl->ParseTemplate("Not_Required");
			$tpl->SetTemplate($tmpTplFile);
			$tpl->TemplateData = $tmpTplData; ?>
			<?php print GetLang('SaveBounceServerDetails'); ?>&nbsp;
		</td>
		<td style="<?php echo $tpl->Get('style'); ?>">
			<?php echo $tpl->Get('blankimg'); ?><label for="savebounceserverdetails"><input type="checkbox" name="savebounceserverdetails" id="savebounceserverdetails" class="bounceSettings" value="1"><?php print GetLang('SaveBounceServerDetailsExplain'); ?></label> <span class="HelpToolTip"><img src="images/help.gif" alt="?" width="24" height="16" border="0" /><span class="HelpToolTip_Title" style="display:none;"><?php print stripslashes(GetLang('SaveBounceServerDetails')); ?></span><span class="HelpToolTip_Contents" style="display:none;"><?php print stripslashes(GetLang('HLP_SaveBounceServerDetails')); ?></span></span>
		</td>
	</tr>
<?php endif; ?>
<?php if(in_array($tpl->Get('currentPage'), array('lists', 'settings'))): ?>
	<tr class="YesProcessBounce" style="display: <?php if(isset($GLOBALS['ShowBounceInfo'])) print $GLOBALS['ShowBounceInfo']; ?>">
		<td class="FieldLabel">
			<?php $tmpTplFile = $tpl->GetTemplate();
			$tmpTplData = $tpl->TemplateData;
			$tpl->ParseTemplate("Not_Required");
			$tpl->SetTemplate($tmpTplFile);
			$tpl->TemplateData = $tmpTplData; ?>
			<?php if($tpl->Get('currentPage') == 'settings'): ?><?php print GetLang('TestBounceSettings'); ?>:<?php endif; ?>&nbsp;
		</td>
		<td><input name="cmdTestBounce" type="button" value="<?php print GetLang('TestBounceSettings'); ?>" class="FormButton YesProcessBounce" style="width: 120px;" style="display: <?php if(isset($GLOBALS['ShowBounceInfo'])) print $GLOBALS['ShowBounceInfo']; ?>"/></td>
	</tr>
<?php endif; ?>
