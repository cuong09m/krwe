<?php $IEM = $tpl->Get('IEM'); ?><link rel="stylesheet" type="text/css" href="includes/styles/thickbox.css" />
<form name="users" method="post" action="index.php?Page=<?php print $IEM['CurrentPage']; ?>&<?php if(isset($GLOBALS['FormAction'])) print $GLOBALS['FormAction']; ?>">
	<input type="hidden" name="id_tab_num" id="id_tab_num" value="<?php echo $tpl->Get('DefaultIdTab'); ?>" />

	<table cellspacing="0" cellpadding="0" width="100%" align="center">
		<tr>
			<td class="Heading1"><?php if(isset($GLOBALS['Heading'])) print $GLOBALS['Heading']; ?></td>
		</tr>
		<tr>
			<td class="body pageinfo"><p><?php if(isset($GLOBALS['Help_Heading'])) print $GLOBALS['Help_Heading']; ?></p></td>
		</tr>
		<tr>
			<td>
				<?php if(isset($GLOBALS['Message'])) print $GLOBALS['Message']; ?>
			</td>
		</tr>
		<tr>
			<td class=body>
				<input class="FormButton" type="submit" value="<?php print GetLang('Save'); ?>"/>
				<input class="FormButton CancelButton" type="button" value="<?php print GetLang('Cancel'); ?>"/>
			</td>
		</tr>
		<tr>
			<td>
				<div>
					<br />
					
					<ul id="tabnav">
						<li><a href="#" class="active" id="tab1" onclick="ShowTab(1); SetDefaultTab(1); return false;"><span><?php print GetLang('UserSettings_Heading'); ?></span></a></li>
						<li><a href="#" id="tab2" onclick="ShowTab(2); SetDefaultTab(2); return false;"><span><?php print GetLang('UserSettingsAdvanced_Heading'); ?></span></a></li>
						<li><a href="#" id="tab3" onclick="ShowTab(3); SetDefaultTab(3); return false;"><span><?php print GetLang('EmailSettings_Heading'); ?></span></a></li>
						<li><a href="#" id="tab4" onclick="ShowTab(4); SetDefaultTab(4); return false;"><span><?php print GetLang('GoogleSettings_Heading'); ?></span></a></li>
						<li><a href="#" id="tab5" onclick="ShowTab(5); SetDefaultTab(5); return false;"><span><?php print GetLang('AdminNotifications_Heading'); ?></span></a></li>
					</ul>

					<div id="div1" style="padding-top:10px">
						<table border="0" cellspacing="0" cellpadding="2" width="100%" class="Panel">
							<tr>
								<td class="Heading2" colspan="2" style="padding-left: 10px">
									<?php print GetLang('UserDetails'); ?>
								</td>
							</tr>
							<?php if(trim($tpl->Get('AgencyEdition','agencyid')) != 0): ?>
							<tr>
								<td class="FieldLabel">
									<?php $tmpTplFile = $tpl->GetTemplate();
			$tmpTplData = $tpl->TemplateData;
			$tpl->ParseTemplate("Required");
			$tpl->SetTemplate($tmpTplFile);
			$tpl->TemplateData = $tmpTplData; ?>
									<?php echo GetLang('UserType'); ?>:
								</td>
								<td>
									<?php if($tpl->Get('EditOwn')): ?><input type="hidden" name="trialuser" value="<?php echo $tpl->Get('TrialUser'); ?>" /><?php endif; ?>
									<select <?php if($tpl->Get('EditOwn')): ?>disabled="disabled"<?php else: ?> name="trialuser"<?php endif; ?>>
										<?php if($tpl->Get('AvailableNormalUsers') === true || $tpl->Get('AvailableNormalUsers') > 0 || ($tpl->Get('EditMode') && $tpl->Get('TrialUser') == '0')): ?>
										<option value="0" <?php if($tpl->Get('TrialUser') == '0'): ?>selected="selected"<?php endif; ?>><?php echo GetLang('UserType_NormalUser'); ?></option>
										<?php endif; ?>
										<?php if($tpl->Get('AvailableTrialUsers') === true || $tpl->Get('AvailableTrialUsers') > 0 || ($tpl->Get('EditMode') && $tpl->Get('TrialUser') == '1')): ?>
										<option value="1" <?php if($tpl->Get('TrialUser') == '1'): ?>selected="selected"<?php endif; ?>><?php echo GetLang('UserType_TrialUser'); ?></option>
										<?php endif; ?>
									</select>
									<span class="HelpToolTip"><img src="images/help.gif" alt="?" width="24" height="16" border="0" /><span class="HelpToolTip_Title" style="display:none;"><?php print stripslashes(GetLang('UserType')); ?></span><span class="HelpToolTip_Contents" style="display:none;"><?php print stripslashes(GetLang('HLP_UserType')); ?></span></span>
								</td>
							</tr>
							<?php else: ?>
								<input type="hidden" name="trialuser" value="0" />
							<?php endif; ?>
							<tr>
								<td class="FieldLabel">
									<?php $tmpTplFile = $tpl->GetTemplate();
			$tmpTplData = $tpl->TemplateData;
			$tpl->ParseTemplate("Required");
			$tpl->SetTemplate($tmpTplFile);
			$tpl->TemplateData = $tmpTplData; ?>
									<?php echo GetLang('UserName'); ?>:
								</td>
								<td>
									<input type="text" name="username" id="username" value="<?php if(isset($GLOBALS['UserName'])) print $GLOBALS['UserName']; ?>" id="username" class="Field250" />
								</td>
							</tr>
							<tr>
								<td class="FieldLabel">
									<?php $tmpTplFile = $tpl->GetTemplate();
			$tmpTplData = $tpl->TemplateData;
			$tpl->ParseTemplate("Required");
			$tpl->SetTemplate($tmpTplFile);
			$tpl->TemplateData = $tmpTplData; ?>
									<?php echo GetLang('Password'); ?>:
								</td>
								<td>
									<input type="password" name="ss_p" id="ss_p" value="" class="Field250" autocomplete="off" />
								</td>
							</tr>
							<tr>
								<td class="FieldLabel">
									<?php $tmpTplFile = $tpl->GetTemplate();
			$tmpTplData = $tpl->TemplateData;
			$tpl->ParseTemplate("Required");
			$tpl->SetTemplate($tmpTplFile);
			$tpl->TemplateData = $tmpTplData; ?>
									<?php echo GetLang('PasswordConfirm'); ?>:
								</td>
								<td>
									<input type="password" name="ss_p_confirm" id="ss_p_confirm" value="" class="Field250" autocomplete="off" />
								</td>
							</tr>
							<tr>
								<td class="FieldLabel">
									<?php if($tpl->Get('canChangeUserGroup')): ?>
									<?php $tmpTplFile = $tpl->GetTemplate();
			$tmpTplData = $tpl->TemplateData;
			$tpl->ParseTemplate("Required");
			$tpl->SetTemplate($tmpTplFile);
			$tpl->TemplateData = $tmpTplData; ?>
									<?php else: ?>
									<?php $tmpTplFile = $tpl->GetTemplate();
			$tmpTplData = $tpl->TemplateData;
			$tpl->ParseTemplate("Not_Required");
			$tpl->SetTemplate($tmpTplFile);
			$tpl->TemplateData = $tmpTplData; ?>
									<?php endif; ?>
									<?php echo GetLang('UsersGroups'); ?>:
								</td>
								<td>
									<?php if($tpl->Get('canChangeUserGroup')): ?>
									<select name="groupid">
										<option value="0"><?php echo GetLang('UsersGroups_Intro'); ?></option>
										<?php $array = $tpl->Get('AvailableGroups'); if(is_array($array) || is_object($array)): foreach($array as $__key=>$EachGroup): $tpl->Assign('__key', $__key, false); $tpl->Assign('EachGroup', $EachGroup, false);  ?>
											<option value="<?php echo $tpl->Get('EachGroup','groupid'); ?>" <?php if($tpl->Get('EachGroup','groupid') == $tpl->Get('record_groupid')): ?>selected="selected"<?php endif; ?>><?php echo $tpl->Get('EachGroup','groupname'); ?></option>
										 <?php endforeach; endif; ?> 
									</select>
									<span class="HelpToolTip"><img src="images/help.gif" alt="?" width="24" height="16" border="0" /><span class="HelpToolTip_Title" style="display:none;"><?php print stripslashes(GetLang('UsersGroups')); ?></span><span class="HelpToolTip_Contents" style="display:none;"><?php print stripslashes(GetLang('HLP_UsersGroups')); ?></span></span>
									<?php else: ?>
									<?php echo GetLang('AdminCannotChangeGroup'); ?>
									<input type="hidden" name="groupid" value="<?php echo $tpl->Get('groupid'); ?>" />
									<?php endif; ?>
								</td>
							</tr>
							<tr>
								<td class="FieldLabel">
									<?php $tmpTplFile = $tpl->GetTemplate();
			$tmpTplData = $tpl->TemplateData;
			$tpl->ParseTemplate("Not_Required");
			$tpl->SetTemplate($tmpTplFile);
			$tpl->TemplateData = $tmpTplData; ?>
									<?php print GetLang('FullName'); ?>:
								</td>
								<td>
									<input type="text" name="fullname" value="<?php if(isset($GLOBALS['FullName'])) print $GLOBALS['FullName']; ?>" id="fullname" class="Field250" />
								</td>
							</tr>
							<tr>
								<td class="FieldLabel">
									<?php $tmpTplFile = $tpl->GetTemplate();
			$tmpTplData = $tpl->TemplateData;
			$tpl->ParseTemplate("Required");
			$tpl->SetTemplate($tmpTplFile);
			$tpl->TemplateData = $tmpTplData; ?>
									<?php print GetLang('EmailAddress'); ?>:
								</td>
								<td>
									<input type="text" name="emailaddress" id="emailaddress" value="<?php if(isset($GLOBALS['EmailAddress'])) print $GLOBALS['EmailAddress']; ?>" class="Field250" />&nbsp;<span class="HelpToolTip"><img src="images/help.gif" alt="?" width="24" height="16" border="0" /><span class="HelpToolTip_Title" style="display:none;"><?php print stripslashes(GetLang('EmailAddress')); ?></span><span class="HelpToolTip_Contents" style="display:none;"><?php print stripslashes(GetLang('HLP_EmailAddress')); ?></span></span>
								</td>
							</tr>
							<tr>
								<td class="FieldLabel">
									<?php $tmpTplFile = $tpl->GetTemplate();
			$tmpTplData = $tpl->TemplateData;
			$tpl->ParseTemplate("Required");
			$tpl->SetTemplate($tmpTplFile);
			$tpl->TemplateData = $tmpTplData; ?>
									<?php print GetLang('TimeZone'); ?>:
								</td>
								<td>
									<select name="usertimezone">
										<?php if(isset($GLOBALS['TimeZoneList'])) print $GLOBALS['TimeZoneList']; ?>
									</select>&nbsp;&nbsp;&nbsp;<span class="HelpToolTip"><img src="images/help.gif" alt="?" width="24" height="16" border="0" /><span class="HelpToolTip_Title" style="display:none;"><?php print stripslashes(GetLang('TimeZone')); ?></span><span class="HelpToolTip_Contents" style="display:none;"><?php print stripslashes(GetLang('HLP_TimeZone')); ?></span></span>
								</td>
							</tr>
						</table>
					</div>
					
					<div id="div2" style="padding-top:10px">
						<table border="0" cellspacing="0" cellpadding="2" width="100%" class="Panel">
							<tr>
								<td class="Heading2" colspan="2" style="padding-left: 10px">
									<?php print GetLang('UserDetailsAdvanced'); ?>
								</td>
							</tr>
							<tr>
								<td class="FieldLabel">
									<?php $tmpTplFile = $tpl->GetTemplate();
			$tmpTplData = $tpl->TemplateData;
			$tpl->ParseTemplate("Not_Required");
			$tpl->SetTemplate($tmpTplFile);
			$tpl->TemplateData = $tmpTplData; ?>
									<?php echo GetLang('UseXMLAPI'); ?>:
								</td>
								<td>
									<label for="xmlapi"><input type="checkbox" name="xmlapi" id="xmlapi" value="1" <?php if(isset($GLOBALS['Xmlapi'])) print $GLOBALS['Xmlapi']; ?> <?php if(trim($tpl->Get('AgencyEdition','agencyid')) != '' && $tpl->Get('TrialUser') == '1'): ?>disabled="disabled"<?php endif; ?>/> <?php echo GetLang('YesUseXMLAPI'); ?></label> <span class="HelpToolTip"><img src="images/help.gif" alt="?" width="24" height="16" border="0" /><span class="HelpToolTip_Title" style="display:none;"><?php print stripslashes(GetLang('UseXMLAPI')); ?></span><span class="HelpToolTip_Contents" style="display:none;"><?php print stripslashes(GetLang('HLP_UseXMLAPI')); ?></span></span><br/>
									<table id="sectionXMLToken"<?php if(isset($GLOBALS['XMLTokenDisplay'])) print $GLOBALS['XMLTokenDisplay']; ?> border="0" cellspacing="0" cellpadding="2" class="Panel">
										<tr>
											<td width="100">
												<img src="images/nodejoin.gif" width="20" height="20">&nbsp;<?php echo GetLang('XMLPath'); ?>:
											</td>
											<td>
												<input type="text" name="xmlpath" id="xmlpath" value="<?php if(isset($GLOBALS['XmlPath'])) print $GLOBALS['XmlPath']; ?>" class="Field250 SelectOnFocus" readonly/> <span class="HelpToolTip"><img src="images/help.gif" alt="?" width="24" height="16" border="0" /><span class="HelpToolTip_Title" style="display:none;"><?php print stripslashes(GetLang('XMLPath')); ?></span><span class="HelpToolTip_Contents" style="display:none;"><?php print stripslashes(GetLang('HLP_XMLPath')); ?></span></span>
											</td>
										</tr>
										<tr>
											<td>
												<img src="images/blank.gif" width="20" height="20">&nbsp;<?php echo GetLang('XMLUsername'); ?>:
											</td>
											<td>
												<input type="text" name="xmlusername" id="xmlusername" value="<?php if(isset($GLOBALS['UserName'])) print $GLOBALS['UserName']; ?>" class="Field250 SelectOnFocus" readonly/> <span class="HelpToolTip"><img src="images/help.gif" alt="?" width="24" height="16" border="0" /><span class="HelpToolTip_Title" style="display:none;"><?php print stripslashes(GetLang('XMLUsername')); ?></span><span class="HelpToolTip_Contents" style="display:none;"><?php print stripslashes(GetLang('HLP_XMLUsername')); ?></span></span>
											</td>
										</tr>
										<tr>
											<td>
												<img src="images/blank.gif" width="20" height="20">&nbsp;<?php echo GetLang('XMLToken'); ?>:
											</td>
											<td>
												<input type="text" name="xmltoken" id="xmltoken" value="<?php if(isset($GLOBALS['XmlToken'])) print $GLOBALS['XmlToken']; ?>" class="Field250 SelectOnFocus" readonly/> <span class="HelpToolTip"><img src="images/help.gif" alt="?" width="24" height="16" border="0" /><span class="HelpToolTip_Title" style="display:none;"><?php print stripslashes(GetLang('XMLToken')); ?></span><span class="HelpToolTip_Contents" style="display:none;"><?php print stripslashes(GetLang('HLP_XMLToken')); ?></span></span>
											</td>
										</tr>
										<tr>
											<td>&nbsp;
												
											</td>
											<td>
												<a href="#" id="hrefRegenerateXMLToken" style="color: gray;"><?php print GetLang('XMLToken_Regenerate'); ?></a>
											</td>
										</tr>
									</table>
								</td>
							</tr>
							<tr>
								<td class="FieldLabel">
									<?php $tmpTplFile = $tpl->GetTemplate();
			$tmpTplData = $tpl->TemplateData;
			$tpl->ParseTemplate("Not_Required");
			$tpl->SetTemplate($tmpTplFile);
			$tpl->TemplateData = $tmpTplData; ?>
									<?php print GetLang('Active'); ?>:
								</td>
								<td>
									<label for="status"><input type="checkbox" name="status" id="status" value="1"<?php if(isset($GLOBALS['StatusChecked'])) print $GLOBALS['StatusChecked']; ?> /> <?php print GetLang('YesIsActive'); ?></label> <span class="HelpToolTip"><img src="images/help.gif" alt="?" width="24" height="16" border="0" /><span class="HelpToolTip_Title" style="display:none;"><?php print stripslashes(GetLang('Active')); ?></span><span class="HelpToolTip_Contents" style="display:none;"><?php print stripslashes(GetLang('HLP_Active')); ?></span></span>
								</td>
							</tr>
							<tr>
								<td class="FieldLabel">
									<?php $tmpTplFile = $tpl->GetTemplate();
			$tmpTplData = $tpl->TemplateData;
			$tpl->ParseTemplate("Not_Required");
			$tpl->SetTemplate($tmpTplFile);
			$tpl->TemplateData = $tmpTplData; ?>
									<?php print GetLang('EditOwnSettings'); ?>:
								</td>
								<td>
									<label for="editownsettings"><input type="checkbox" name="editownsettings" id="editownsettings" value="1"<?php if(isset($GLOBALS['EditOwnSettingsChecked'])) print $GLOBALS['EditOwnSettingsChecked']; ?> /> <?php print GetLang('YesEditOwnSettings'); ?></label> <span class="HelpToolTip"><img src="images/help.gif" alt="?" width="24" height="16" border="0" /><span class="HelpToolTip_Title" style="display:none;"><?php print stripslashes(GetLang('EditOwnSettings')); ?></span><span class="HelpToolTip_Contents" style="display:none;"><?php print stripslashes(GetLang('HLP_EditOwnSettings')); ?></span></span>
								</td>
							</tr>
							<tr>
								<td class="FieldLabel">
									<?php $tmpTplFile = $tpl->GetTemplate();
			$tmpTplData = $tpl->TemplateData;
			$tpl->ParseTemplate("Not_Required");
			$tpl->SetTemplate($tmpTplFile);
			$tpl->TemplateData = $tmpTplData; ?>
									<?php print GetLang('ShowInfoTips'); ?>:
								</td>
								<td>
									<label for="infotips"><input type="checkbox" name="infotips" id="infotips" value="1"<?php if(isset($GLOBALS['InfoTipsChecked'])) print $GLOBALS['InfoTipsChecked']; ?> /> <?php print GetLang('YesShowInfoTips'); ?></label> <span class="HelpToolTip"><img src="images/help.gif" alt="?" width="24" height="16" border="0" /><span class="HelpToolTip_Title" style="display:none;"><?php print stripslashes(GetLang('ShowInfoTips')); ?></span><span class="HelpToolTip_Contents" style="display:none;"><?php print stripslashes(GetLang('HLP_ShowInfoTips')); ?></span></span>
								</td>
							</tr>
							<tr>
								<td class="FieldLabel">
									<?php $tmpTplFile = $tpl->GetTemplate();
			$tmpTplData = $tpl->TemplateData;
			$tpl->ParseTemplate("Not_Required");
			$tpl->SetTemplate($tmpTplFile);
			$tpl->TemplateData = $tmpTplData; ?>
									<?php print GetLang('UseWysiwygEditor'); ?>:
								</td>
								<td>
									<div><label for="usewysiwyg"><input type="checkbox" name="usewysiwyg" id="usewysiwyg" value="1" <?php if(isset($GLOBALS['UseWysiwyg'])) print $GLOBALS['UseWysiwyg']; ?> /> <?php print GetLang('YesUseWysiwygEditor'); ?></label> <span class="HelpToolTip"><img src="images/help.gif" alt="?" width="24" height="16" border="0" /><span class="HelpToolTip_Title" style="display:none;"><?php print stripslashes(GetLang('UseWysiwygEditor')); ?></span><span class="HelpToolTip_Contents" style="display:none;"><?php print stripslashes(GetLang('HLP_UseWysiwygEditor')); ?></span></span></div>
									<div id="sectionUseXHTML"<?php if(isset($GLOBALS['UseXHTMLDisplay'])) print $GLOBALS['UseXHTMLDisplay']; ?>><img src="images/nodejoin.gif" width="20" height="20"><label for="usexhtml"><input type="checkbox" name="usexhtml" id="usexhtml" value="1"<?php if(isset($GLOBALS['UseXHTMLCheckbox'])) print $GLOBALS['UseXHTMLCheckbox']; ?>> <?php print GetLang('YesUseXHTML'); ?></label> <span class="HelpToolTip"><img src="images/help.gif" alt="?" width="24" height="16" border="0" /><span class="HelpToolTip_Title" style="display:none;"><?php print stripslashes(GetLang('UseWysiwygXHTML')); ?></span><span class="HelpToolTip_Contents" style="display:none;"><?php print stripslashes(GetLang('HLP_UseWysiwygXHTML')); ?></span></span></div>
								</td>
							</tr>
							<tr>
								<td class="FieldLabel">
									<?php $tmpTplFile = $tpl->GetTemplate();
			$tmpTplData = $tpl->TemplateData;
			$tpl->ParseTemplate("Not_Required");
			$tpl->SetTemplate($tmpTplFile);
			$tpl->TemplateData = $tmpTplData; ?>
									<?php echo GetLang('EnableActivityLog'); ?>:
								</td>
								<td>
									<label for="enableactivitylog"><input type="checkbox" name="enableactivitylog" id="enableactivitylog" value="1" <?php if(isset($GLOBALS['EnableActivityLog'])) print $GLOBALS['EnableActivityLog']; ?> /> <?php echo GetLang('YesEnableActivityLog'); ?></label> <span class="HelpToolTip"><img src="images/help.gif" alt="?" width="24" height="16" border="0" /><span class="HelpToolTip_Title" style="display:none;"><?php print stripslashes(GetLang('EnableActivityLog')); ?></span><span class="HelpToolTip_Contents" style="display:none;"><?php print stripslashes(GetLang('HLP_EnableActivityLog')); ?></span></span>
								</td>
							</tr>
							<tr>
								<td class="FieldLabel">
									<?php $tmpTplFile = $tpl->GetTemplate();
			$tmpTplData = $tpl->TemplateData;
			$tpl->ParseTemplate("Not_Required");
			$tpl->SetTemplate($tmpTplFile);
			$tpl->TemplateData = $tmpTplData; ?>
									<?php echo GetLang('EventTypeList'); ?>:
								</td>
								<td>
									<textarea name="eventactivitytype" rows="10" cols="50" wrap="virtual"><?php if(isset($GLOBALS['EventActivityType'])) print $GLOBALS['EventActivityType']; ?></textarea>&nbsp;&nbsp;&nbsp;<span class="HelpToolTip"><img src="images/help.gif" alt="?" width="24" height="16" border="0" /><span class="HelpToolTip_Title" style="display:none;"><?php print stripslashes(GetLang('EventTypeList')); ?></span><span class="HelpToolTip_Contents" style="display:none;"><?php print stripslashes(GetLang('HLP_EventTypeList')); ?></span></span>
								</td>
							</tr>
						</table>
					</div>

					<div id="div3" style="display:none; padding-top:10px">
						<table border="0" cellspacing="0" cellpadding="2" width="100%" class="Panel">
							<tr>
								<td colspan="2" class="Heading2" style="padding-left:10px">
									<?php print GetLang('SmtpServerIntro'); ?>
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
									<?php print GetLang('SmtpServer'); ?>:
								</td>
								<td width="90%">
									<label for="usedefaultsmtp">
										<input type="radio" name="smtptype" id="usedefaultsmtp" value="0" <?php if(!$tpl->Get('showSmtpInfo')): ?>checked="checked"<?php endif; ?> />
										<?php print GetLang('SmtpDefault'); ?>
									</label>
									<span class="HelpToolTip"><img src="images/help.gif" alt="?" width="24" height="16" border="0" /><span class="HelpToolTip_Title" style="display:none;"><?php print stripslashes(GetLang('UseDefaultMail')); ?></span><span class="HelpToolTip_Contents" style="display:none;"><?php print stripslashes(GetLang('HLP_UseDefaultMail')); ?></span></span>
								</td>
							</tr>
							<tr>
								<td class="FieldLabel">&nbsp;</td>
								<td>
									<label for="usecustomsmtp">
										<input type="radio" name="smtptype" id="usecustomsmtp" value="1" <?php if($tpl->Get('showSmtpInfo')): ?>checked="checked"<?php endif; ?> />
										<?php print GetLang('SmtpCustom'); ?>
									</label>
									<span class="HelpToolTip"><img src="images/help.gif" alt="?" width="24" height="16" border="0" /><span class="HelpToolTip_Title" style="display:none;"><?php print stripslashes(GetLang('UseSMTP_User')); ?></span><span class="HelpToolTip_Contents" style="display:none;"><?php print stripslashes(GetLang('HLP_UseSMTP_User')); ?></span></span>
								</td>
							</tr>
							<tr class="SMTPOptions" style="display:none">
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
									<input type="text" name="smtp_server" value="<?php if(isset($GLOBALS['SmtpServer'])) print $GLOBALS['SmtpServer']; ?>" class="Field250 smtpSettings"/> <span class="HelpToolTip"><img src="images/help.gif" alt="?" width="24" height="16" border="0" /><span class="HelpToolTip_Title" style="display:none;"><?php print stripslashes(GetLang('SmtpServerName')); ?></span><span class="HelpToolTip_Contents" style="display:none;"><?php print stripslashes(GetLang('HLP_SmtpServerName')); ?></span></span>
								</td>
							</tr>
							<tr class="SMTPOptions" style="display:none">
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
									<input type="text" name="smtp_u" value="<?php if(isset($GLOBALS['SmtpUsername'])) print $GLOBALS['SmtpUsername']; ?>" class="Field250 smtpSettings"/> <span class="HelpToolTip"><img src="images/help.gif" alt="?" width="24" height="16" border="0" /><span class="HelpToolTip_Title" style="display:none;"><?php print stripslashes(GetLang('SmtpServerUsername')); ?></span><span class="HelpToolTip_Contents" style="display:none;"><?php print stripslashes(GetLang('HLP_SmtpServerUsername')); ?></span></span>
								</td>
							</tr>
							<tr class="SMTPOptions" style="display:none">
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
									<input type="password" name="smtp_p" value="<?php if(isset($GLOBALS['SmtpPassword'])) print $GLOBALS['SmtpPassword']; ?>" class="Field250 smtpSettings" autocomplete="off" /> <span class="HelpToolTip"><img src="images/help.gif" alt="?" width="24" height="16" border="0" /><span class="HelpToolTip_Title" style="display:none;"><?php print stripslashes(GetLang('SmtpServerPassword')); ?></span><span class="HelpToolTip_Contents" style="display:none;"><?php print stripslashes(GetLang('HLP_SmtpServerPassword')); ?></span></span>
								</td>
							</tr>
							<tr class="SMTPOptions" style="display:none">
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
									<input type="text" name="smtp_port" value="<?php if(isset($GLOBALS['SmtpPort'])) print $GLOBALS['SmtpPort']; ?>" class="field50 smtpSettings"/> <span class="HelpToolTip"><img src="images/help.gif" alt="?" width="24" height="16" border="0" /><span class="HelpToolTip_Title" style="display:none;"><?php print stripslashes(GetLang('SmtpServerPort')); ?></span><span class="HelpToolTip_Contents" style="display:none;"><?php print stripslashes(GetLang('HLP_SmtpServerPort')); ?></span></span>
								</td>
							</tr>
							<tr class="SMTPOptions" style="display:none">
								<td class="FieldLabel">
									<?php $tmpTplFile = $tpl->GetTemplate();
			$tmpTplData = $tpl->TemplateData;
			$tpl->ParseTemplate("Not_Required");
			$tpl->SetTemplate($tmpTplFile);
			$tpl->TemplateData = $tmpTplData; ?>
									<?php print GetLang('TestSMTPSettings'); ?>:
								</td>
								<td>
									<img width="20" height="20" src="images/blank.gif"/>
									<input type="text" name="smtp_test" id="smtp_test" value="" class="Field250 smtpSettings"/> <span class="HelpToolTip"><img src="images/help.gif" alt="?" width="24" height="16" border="0" /><span class="HelpToolTip_Title" style="display:none;"><?php print stripslashes(GetLang('TestSMTPSettings')); ?></span><span class="HelpToolTip_Contents" style="display:none;"><?php print stripslashes(GetLang('HLP_TestSMTPSettings')); ?></span></span>
								</td>
							</tr>
							<tr class="SMTPOptions" style="display:none">
								<td class="FieldLabel">&nbsp;
									
								</td>
								<td>
									<img width="20" height="20" src="images/blank.gif"/>
									<input type="button" name="cmdTestSMTP" value="<?php print GetLang('TestSMTPSettings'); ?>" class="FormButton" style="width: 120px;"/>
								</td>
							</tr>
							<tr style="display:<?php if(isset($GLOBALS['ShowSMTPCOMOption'])) print $GLOBALS['ShowSMTPCOMOption']; ?>">
								<td class="FieldLabel">&nbsp;</td>
								<td>
									<label for="signtosmtp">
										<input type="radio" name="smtptype" id="signtosmtp" value="2"/>
										<?php print GetLang('SMTPCOM_UseSMTPOption'); ?>
									</label>
									<span class="HelpToolTip"><img src="images/help.gif" alt="?" width="24" height="16" border="0" /><span class="HelpToolTip_Title" style="display:none;"><?php print stripslashes(GetLang('UseSMTPCOM')); ?></span><span class="HelpToolTip_Contents" style="display:none;"><?php print stripslashes(GetLang('HLP_UseSMTPCOM')); ?></span></span>
								</td>
							</tr>
							<tr class="sectionSignuptoSMTP" style="display: none;">
								<td colspan="2" class="EmptyRow">&nbsp;
									
								</td>
							</tr>
							<tr class="sectionSignuptoSMTP" style="display: none;">
								<td colspan="2" class="Heading2">
									&nbsp;&nbsp;<?php print GetLang('SMTPCOM_Header'); ?>
								</td>
							</tr>
							<tr class="sectionSignuptoSMTP" style="display: none;">
								<td colspan="2" style="padding-left: 10px; padding-top:10px"><?php print GetLang('SMTPCOM_Explain'); ?></td>
							</tr>
							
							<tr>
								<td colspan="2" class="Heading2" style="padding-left:10px">
									<?php print GetLang('HeaderFooter_Heading'); ?>
								</td>
							</tr>
							<tr>
								<td class="FieldLabel">
									<?php $tmpTplFile = $tpl->GetTemplate();
			$tmpTplData = $tpl->TemplateData;
			$tpl->ParseTemplate("Not_Required");
			$tpl->SetTemplate($tmpTplFile);
			$tpl->TemplateData = $tmpTplData; ?>
									<?php print GetLang('HTMLFooter'); ?>:
								</td>
								<td>
									<textarea name="htmlfooter" rows="10" cols="50" wrap="virtual"><?php if(isset($GLOBALS['HTMLFooter'])) print $GLOBALS['HTMLFooter']; ?></textarea>&nbsp;&nbsp;&nbsp;<span class="HelpToolTip"><img src="images/help.gif" alt="?" width="24" height="16" border="0" /><span class="HelpToolTip_Title" style="display:none;"><?php print stripslashes(GetLang('HTMLFooter')); ?></span><span class="HelpToolTip_Contents" style="display:none;"><?php print stripslashes(GetLang('HLP_HTMLFooter')); ?></span></span>
								</td>
							</tr>
							<tr>
								<td class="FieldLabel">&nbsp;</td>
								<td><?php echo GetLang('ViewKB_ExplainDefaultFooter'); ?></td>
							</tr>
							<tr>
								<td class="FieldLabel">
									<?php $tmpTplFile = $tpl->GetTemplate();
			$tmpTplData = $tpl->TemplateData;
			$tpl->ParseTemplate("Not_Required");
			$tpl->SetTemplate($tmpTplFile);
			$tpl->TemplateData = $tmpTplData; ?>
									<?php print GetLang('TextFooter'); ?>:
								</td>
								<td>
									<textarea name="textfooter" rows="10" cols="50" wrap="virtual"><?php if(isset($GLOBALS['TextFooter'])) print $GLOBALS['TextFooter']; ?></textarea>&nbsp;&nbsp;&nbsp;<span class="HelpToolTip"><img src="images/help.gif" alt="?" width="24" height="16" border="0" /><span class="HelpToolTip_Title" style="display:none;"><?php print stripslashes(GetLang('TextFooter')); ?></span><span class="HelpToolTip_Contents" style="display:none;"><?php print stripslashes(GetLang('HLP_TextFooter')); ?></span></span>
								</td>
							</tr>
						</table>
					</div>
	
					<div id="div4" style="display:none; padding-top:10px">
						<table border="0" cellspacing="0" cellpadding="2" width="100%" class="Panel">
							<tr>
								<td colspan="2" class="Heading2" style="padding-left:10px">
									<?php print GetLang('GoogleCalendarIntro'); ?>
								</td>
							</tr>
							<tr>
								<td colspan="2">
									<p class="HelpInfo"><?php print GetLang('GoogleCalendarIntroText'); ?></p>
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
									<?php print GetLang('GoogleCalendarUsername'); ?>:
								</td>
								<td width="90%">
									<label for="googlecalendarusername">
										<input type="text" class="Field250 googlecalendar" name="googlecalendarusername" id="googlecalendarusername" value="<?php if(isset($GLOBALS['googlecalendarusername'])) print $GLOBALS['googlecalendarusername']; ?>" autocomplete="off" />
									</label>
									<span class="HelpToolTip"><img src="images/help.gif" alt="?" width="24" height="16" border="0" /><span class="HelpToolTip_Title" style="display:none;"><?php print stripslashes(GetLang('GoogleCalendarUsername')); ?></span><span class="HelpToolTip_Contents" style="display:none;"><?php print stripslashes(GetLang('HLP_GoogleCalendarUsername')); ?></span></span>
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
									<?php print GetLang('GoogleCalendarPassword'); ?>:
								</td>
								<td width="90%">
									<label for="googlecalendarpassword">
										<input type="password" class="Field250 googlecalendar" name="googlecalendarpassword" id="googlecalendarpassword" value="<?php if(isset($GLOBALS['googlecalendarpassword'])) print $GLOBALS['googlecalendarpassword']; ?>" autocomplete="off" />
									</label>
									<span class="HelpToolTip"><img src="images/help.gif" alt="?" width="24" height="16" border="0" /><span class="HelpToolTip_Title" style="display:none;"><?php print stripslashes(GetLang('GoogleCalendarPassword')); ?></span><span class="HelpToolTip_Contents" style="display:none;"><?php print stripslashes(GetLang('HLP_GoogleCalendarPassword')); ?></span></span>
								</td>
							</tr>
						</table>
					</div>
						
					<div id="div5" style="display: none; padding-top: 10px">
						<table border="0" cellspacing="0" cellpadding="2" width="100%" class="Panel">
							<tr>
								<td class=Heading2 colspan=2 style="padding-left:10px">
									<?php print GetLang('AdminNotifications_SubHeading'); ?>
								</td>
							</tr>
							<tr>
								<td class="FieldLabel">
									<?php $tmpTplFile = $tpl->GetTemplate();
			$tmpTplData = $tpl->TemplateData;
			$tpl->ParseTemplate("Required");
			$tpl->SetTemplate($tmpTplFile);
			$tpl->TemplateData = $tmpTplData; ?>
									<?php print GetLang('EmailAddress'); ?>:
								</td>
								<td>
									<textarea id="adminnotify_email" name="adminnotify_email" class="Field300" style="height: 50px;"><?php if(isset($GLOBALS['AdminNotifyEmailAddress'])) print $GLOBALS['AdminNotifyEmailAddress']; ?></textarea><span class="HelpToolTip"><img src="images/help.gif" alt="?" width="24" height="16" border="0" /><span class="HelpToolTip_Title" style="display:none;"><?php print stripslashes(GetLang('NotifyEmailAddress')); ?></span><span class="HelpToolTip_Contents" style="display:none;"><?php print stripslashes(GetLang('HLP_NotifyEmailAddress')); ?></span></span>
								</td>
							</tr>
							<tr>
								<td class="FieldLabel">&nbsp;</td>
								<td> <?php print GetLang('AdminNotifications_EmailInstruction'); ?> </td>
							</tr>
							<tr>
								<td class="FieldLabel">
									<?php $tmpTplFile = $tpl->GetTemplate();
			$tmpTplData = $tpl->TemplateData;
			$tpl->ParseTemplate("Not_Required");
			$tpl->SetTemplate($tmpTplFile);
			$tpl->TemplateData = $tmpTplData; ?>
									<?php print GetLang('AdminNotifications_Notify_Send'); ?>
								</td>
								<td>
									<div><label for="adminnotify_send_flag"><input type="checkbox" name="adminnotify_send_flag" id="adminnotify_send_flag" value="1" <?php if(isset($GLOBALS['AdminNotificationsSend'])) print $GLOBALS['AdminNotificationsSend']; ?>> <?php print GetLang('AdminNotifications_Send_Desc'); ?></label> <span class="HelpToolTip"><img src="images/help.gif" alt="?" width="24" height="16" border="0" /><span class="HelpToolTip_Title" style="display:none;"><?php print stripslashes(GetLang('AdminNotifications_Notify_SendEnable')); ?></span><span class="HelpToolTip_Contents" style="display:none;"><?php print stripslashes(GetLang('HLP_AdminNotifications_Notify_SendEnable')); ?></span></span></div>
									<div class="sectionNotifySend" <?php if(isset($GLOBALS['UseNotifySend'])) print $GLOBALS['UseNotifySend']; ?>"><img src="images/nodejoin.gif" width="20" height="20"><label for="adminnotify_send_threshold"><input type="textbox" name="adminnotify_send_threshold" id="adminnotify_send_threshold" value="<?php if(isset($GLOBALS['SendLimit'])) print $GLOBALS['SendLimit']; ?>" class="Field30"> <?php print GetLang('AdminNotifications_Send_LimitDesc'); ?></label> <span class="HelpToolTip"><img src="images/help.gif" alt="?" width="24" height="16" border="0" /><span class="HelpToolTip_Title" style="display:none;"><?php print stripslashes(GetLang('AdminNotifications_Send_Enabled')); ?></span><span class="HelpToolTip_Contents" style="display:none;"><?php print stripslashes(GetLang('HLP_AdminNotifications_Send_Enabled')); ?></span></span></div>
								</td>
							</tr>
							<tr class="sectionNotifySend" <?php if(isset($GLOBALS['UseNotifySend'])) print $GLOBALS['UseNotifySend']; ?>>
								<td class="FieldLabel">
									<?php $tmpTplFile = $tpl->GetTemplate();
			$tmpTplData = $tpl->TemplateData;
			$tpl->ParseTemplate("Required");
			$tpl->SetTemplate($tmpTplFile);
			$tpl->TemplateData = $tmpTplData; ?><?php print GetLang('AdminNotifications_Email_Text'); ?>
								</td>
								<td>
									<div style="width: 20px; height: 20px; float: left;"></div><textarea class="Field300" id="adminnotify_send_emailtext" name="adminnotify_send_emailtext" rows="10" cols="50" wrap="virtual"><?php if(isset($GLOBALS['AdminNotifications_Send_Email'])) print $GLOBALS['AdminNotifications_Send_Email']; ?></textarea><span class="HelpToolTip"><img src="images/help.gif" alt="?" width="24" height="16" border="0" /><span class="HelpToolTip_Title" style="display:none;"><?php print stripslashes(GetLang('AdminNotifications_Send_Text')); ?></span><span class="HelpToolTip_Contents" style="display:none;"><?php print stripslashes(GetLang('HLP_AdminNotifications_Send_Text')); ?></span></span>
								</td>
							</tr>
							<tr>
								<td class="FieldLabel">
									<?php $tmpTplFile = $tpl->GetTemplate();
			$tmpTplData = $tpl->TemplateData;
			$tpl->ParseTemplate("Not_Required");
			$tpl->SetTemplate($tmpTplFile);
			$tpl->TemplateData = $tmpTplData; ?>
									<?php print GetLang('AdminNotifications_Notify_Import'); ?>
								</td>
								<td>
									<div><label for="adminnotify_import_flag"><input type="checkbox" name="adminnotify_import_flag" id="adminnotify_import_flag" value="1" <?php if(isset($GLOBALS['AdminNotificationsImport'])) print $GLOBALS['AdminNotificationsImport']; ?>> <?php print GetLang('AdminNotifications_Import_Desc'); ?></label> <span class="HelpToolTip"><img src="images/help.gif" alt="?" width="24" height="16" border="0" /><span class="HelpToolTip_Title" style="display:none;"><?php print stripslashes(GetLang('AdminNotifications_Notify_ImportEnable')); ?></span><span class="HelpToolTip_Contents" style="display:none;"><?php print stripslashes(GetLang('HLP_AdminNotifications_Notify_ImportEnable')); ?></span></span></div>
									<div class="sectionNotifyImport" <?php if(isset($GLOBALS['UseNotifyImport'])) print $GLOBALS['UseNotifyImport']; ?>"><img src="images/nodejoin.gif" width="20" height="20"><label for="adminnotify_import_threshold"><input type="textbox" name="adminnotify_import_threshold" id="adminnotify_import_threshold" value="<?php if(isset($GLOBALS['ImportLimit'])) print $GLOBALS['ImportLimit']; ?>" class="Field30"> <?php print GetLang('AdminNotifications_Import_LimitDesc'); ?></label> <span class="HelpToolTip"><img src="images/help.gif" alt="?" width="24" height="16" border="0" /><span class="HelpToolTip_Title" style="display:none;"><?php print stripslashes(GetLang('AdminNotifications_Import_Enabled')); ?></span><span class="HelpToolTip_Contents" style="display:none;"><?php print stripslashes(GetLang('HLP_AdminNotifications_Import_Enabled')); ?></span></span></div>
								</td>
							</tr>
							<tr class="sectionNotifyImport" <?php if(isset($GLOBALS['UseNotifyImport'])) print $GLOBALS['UseNotifyImport']; ?>>
								<td class="FieldLabel">
									<?php $tmpTplFile = $tpl->GetTemplate();
			$tmpTplData = $tpl->TemplateData;
			$tpl->ParseTemplate("Required");
			$tpl->SetTemplate($tmpTplFile);
			$tpl->TemplateData = $tmpTplData; ?>
									<?php print GetLang('AdminNotifications_Email_Text'); ?>
								</td>
								<td>
									<div style="width: 20px; height: 20px; float: left;"></div><textarea class="Field300" id="adminnotify_import_emailtext" name="adminnotify_import_emailtext" rows="10" cols="50" wrap="virtual"><?php if(isset($GLOBALS['AdminNotifications_Import_Email'])) print $GLOBALS['AdminNotifications_Import_Email']; ?></textarea><span class="HelpToolTip"><img src="images/help.gif" alt="?" width="24" height="16" border="0" /><span class="HelpToolTip_Title" style="display:none;"><?php print stripslashes(GetLang('AdminNotifications_Import_Text')); ?></span><span class="HelpToolTip_Contents" style="display:none;"><?php print stripslashes(GetLang('HLP_AdminNotifications_Import_Text')); ?></span></span>
								</td>
							</tr>
						</table>
					</div>
				</div>

				<table border="0" cellspacing="0" cellpadding="2" width="100%" class=PanelPlain>
					<tr>
						<td width="200" class="FieldLabel">&nbsp;
							
						</td>
						<td height="30" valign="top">
							<input type="button" id="cmdTestGoogleCalendar" value="<?php print GetLang('TestLogin'); ?>" class="FormButton" style="display: none;"/>
							<input class="FormButton" type="submit" value="<?php print GetLang('Save'); ?>"/>
							<input class="FormButton CancelButton" type="button" value="<?php print GetLang('Cancel'); ?>"/>
							<span id="spanTestGoogleCalendar" style="display:none;">&nbsp;&nbsp;<img src="images/searching.gif" alt="wait" /></span>
						</td>
					</tr>
				</table>
			</td>
		</tr>
	</table>
</form>

<script src="includes/js/jquery/form.js"></script>
<script src="includes/js/jquery/thickbox.js"></script>
<script>
	var CurrentUserID = parseInt('<?php echo $tpl->Get('UserID'); ?>');
	$(function() {
		SetDefaultTab(<?php echo $tpl->Get('DefaultIdTab'); ?>);
		ShowTab(<?php echo $tpl->Get('DefaultIdTab'); ?>);
		$(document.users).submit(function() {
			if ($('#username').val().trim().length < 3) {
				ShowTab(1);
				alert("<?php print GetLang('EnterUsername'); ?>");
				$('#username').focus();
				return false;
			}
 
			if (CurrentUserID == 0 || $('#ss_p').val() != "") {
				if ($('#ss_p').val().trim().length < 3) {
					ShowTab(1);
					alert("<?php print GetLang('EnterPassword'); ?>");
					$('#ss_p').focus().select();
					return false;
				}

				if ($('#ss_p').val() != $('#ss_p_confirm').val()) {
					ShowTab(1);
					alert("<?php print GetLang('PasswordsDontMatch'); ?>");
					$('#ss_p_confirm').focus().select();
					return false;
				}
			}

			if ($(document.users.groupid).val() == 0) {
				ShowTab(1);
				alert("<?php echo GetLang('UsersGroups_Choose_Group'); ?>");
				$(document.users.groupid).focus();
				return false;
			}

			if ($('#emailaddress').val().indexOf('@') == -1 || $('#emailaddress').val().indexOf('.') == -1) {
				ShowTab(1);
				alert("<?php print GetLang('EnterEmailaddress'); ?>");
				$('#emailaddress').focus().select();
				return false;
			}

			var gu = $('#googlecalendarusername');
			var gp = $('#googlecalendarpassword');
			if ((gu.val() != '' && gp.val() == '') || (gu.val() == '' && gp.val() != '')) {
				if (gu.val() == '') {
					alert('<?php print GetLang('EnterGoogleCalendarUsername'); ?>');
					ShowTab(5);
					gu.focus();
					return false;
				} else if (gp.val() == '') {
					alert('<?php print GetLang('EnterGoogleCalendarPassword'); ?>');
					ShowTab(5);
					gp.focus();
					return false;
				}
			}

			if ($('#adminnotify_email').val().indexOf('@') == -1 || $('#emailaddress').val().indexOf('.') == -1) {
				ShowTab(6);
				alert("<?php print GetLang('EnterNotifyAdminEmail'); ?>");
				$('#adminnotify_email').focus().select();
				return false;
			}
			
			if ($('#adminnotify_email').val() == "") {
				ShowTab(6);
				alert("<?php print GetLang('EnterNotifyAdminEmail'); ?>");
				$('#adminnotify_email').focus().select();
				return false;
			}
			
			if ($('#adminnotify_send_flag').attr('checked')) {
				var sendThresholdValue = $('#adminnotify_send_threshold').val();
				var sendThreshold = sendThresholdValue.replace(/[ ]/ig, "");
				
				if (isNaN(sendThreshold)) {
					ShowTab(6);
					alert("<?php print GetLang('EnterNotifyAdminThresholdNotNumber'); ?>");
					$('#adminnotify_send_threshold').focus().select();
					return false;
				}
			
				if ( sendThresholdValue <= 0 ) {
					ShowTab(6);
					alert("<?php print GetLang('EnterNotifyAdminThreshold'); ?>");
					$('#adminnotify_send_threshold').focus().select();
					return false;
				}
				
				if($('#adminnotify_send_emailtext').val() == "") {
					ShowTab(6);
					alert("<?php print GetLang('EnterNotifyAdminEmailText'); ?>");
					$('#adminnotify_send_emailtext').focus().select();
					return false;
				}
			}
			
			if ($('#adminnotify_import_flag').attr('checked')) {
				var importThresholdValue = $('#adminnotify_import_threshold').val();
				var importThreshold = importThresholdValue.replace(/[ ]/ig, "");
				
				if (isNaN(importThreshold)) {
					ShowTab(6);
					alert("<?php print GetLang('EnterNotifyAdminThresholdNotNumber'); ?>");
					$('#adminnotify_import_threshold').focus().select();
					return false;
				}
			
				if ( importThresholdValue <= 0 ) {
					ShowTab(6);
					alert("<?php print GetLang('EnterNotifyAdminThreshold'); ?>");
					$('#adminnotify_import_threshold').focus().select();
					return false;
				}
				
				if($('#adminnotify_import_emailtext').val() == "") {
					ShowTab(6);
					alert("<?php print GetLang('EnterNotifyAdminEmailText'); ?>");
					$('#adminnotify_import_emailtext').focus().select();
					return false;
				}
			}

			$('input#xmlapi', document.users).attr('disabled', false);
			
			return true;
		});

		$('.CancelButton', document.users).click(function() { if(confirm('<?php print GetLang('ConfirmCancel'); ?>')) document.location.href='index.php?Page=Users'; });

		$(document.users.usewysiwyg).click(function() { $('#sectionUseXHTML')[this.checked? 'show' : 'hide'](); });
		$(document.users.adminnotify_send_flag).click(function() { $('.sectionNotifySend')[this.checked? 'show' : 'hide'](); });
		$(document.users.adminnotify_import_flag).click(function() { $('.sectionNotifyImport')[this.checked? 'show' : 'hide'](); });

		$(document.users.xmlapi).click(function() {
			$('#sectionXMLToken').toggle();
			// if(document.users.xmltoken.value == '') $('#hrefRegenerateXMLToken').click();
		});

		$('.SelectOnFocus').focus(function() { this.select(); });

		$('#hrefRegenerateXMLToken').click(function() {
			$.post('index.php?Page=Users&Action=GenerateToken',
					{	'username':	document.users.username.value,
						'fullname':	document.users.fullname.value,
						'emailaddress': document.users.emailaddress.value},
					function(token) { $("#xmltoken").val(token); });
			return false;
		});

		/*
		$(document.users.user_smtp).click(function() {
			document.users.user_smtpcom.disabled = !this.checked;
			document.users.user_smtpcom.checked = this.checked;
			calcUserType();
		});
		*/

		$(document.users.listadmintype).change(function() { $('#PrintLists')[this.selectedIndex == 0? 'hide' : 'show'](); });
		$(document.users.segmentadmintype).change(function() { $('#PrintSegments')[this.selectedIndex == 0? 'hide' : 'show'](); });
		$(document.users.templateadmintype).change(function() { $('#PrintTemplates')[this.selectedIndex == 0? 'hide' : 'show'](); });

		$('#subscribers_add, #subscribers_edit, #subscribers_delete').click(function() {
			$('#subscribers_manage').attr('checked', ($('#subscribers_add, #subscribers_edit, #subscribers_delete').filter(':checked').size() != 0));
		});

		$('#subscribers_manage').click(function(event) {
			if($('#subscribers_add, #subscribers_edit, #subscribers_delete').filter(':checked').size() != 0) {
				event.preventDefault();
				event.stopPropagation();
			}
		});

		$('#segment_create, #segment_edit, #segment_delete, #segment_send').click(function() {
			$('#segment_view').attr('checked', ($('#segment_create, #segment_edit, #segment_delete, #segment_send').filter(':checked').size() != 0));
		});

		$('#segment_view').click(function(event) {
			if($('#segment_create, #segment_edit, #segment_delete, #segment_send').filter(':checked').size() != 0) {
				event.preventDefault();
				event.stopPropagation();
			}
		});

		$('#cmdTestGoogleCalendar').click(function() {
			if ($('#googlecalendarusername').val() == '') {
				alert('<?php print GetLang('EnterGoogleCalendarUsername'); ?>');
				$('#googlecalendarusername').focus();
				return false;
			} else if ($('#googlecalendarpassword').val() == '') {
				alert('<?php print GetLang('EnterGoogleCalendarPassword'); ?>');
				$('#googlecalendarpassword').focus();
				return false;
			}

			$('#spanTestGoogleCalendar').show();
			$(this).attr('disabled', true);

			$.ajax({	type:		'GET',
						url:		'index.php',
						data:		{	Page: 		'Users',
										Action:		'TestGoogleCalendar',
										gcusername:	escape($('#googlecalendarusername').val()),
										gcpassword:	escape($('#googlecalendarpassword').val())},
						timeout:	10000,
						success:	function(data) {
										try {
											var d = eval('(' + data + ')');
											alert(d.message);
										} catch(e) { alert('<?php echo GetLang('GooglecalendarTestError'); ?>'); }
									},
						error:		function() { alert('<?php echo GetLang('GooglecalendarTestError'); ?>'); },
						complete:	function() {
										$('#spanTestGoogleCalendar').hide();
										$('#cmdTestGoogleCalendar').attr('disabled', false);
									}});

			return false;
		});

		$(document.users.smtptype).click(function() {
			$('.SMTPOptions')[document.users.smtptype[1].checked? 'show' : 'hide']();
			$('.sectionSignuptoSMTP')[document.users.smtptype[2].checked? 'show' : 'hide']();
		});

		$(document.users.cmdTestSMTP).click(function() {
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

			tb_show('<?php print GetLang('SendPreview'); ?>', 'index.php?Page=Users&Action=SendPreviewDisplay&keepThis=true&TB_iframe=true&height=250&width=420', '');
			return true;
		});

		// document.users.user_smtpcom.disabled = !document.users.user_smtp.checked;
		// document.users.smtptype[0].checked = !(document.users.smtptype[1].checked = (document.users.smtp_server.value != ''));

		$('.SMTPOptions')[document.users.smtptype[1].checked? 'show' : 'hide']();
		$('.sectionSignuptoSMTP')[document.users.smtptype[2].checked? 'show' : 'hide']();

	});

	function getSMTPPreviewParameters() {
		var values = {};
		$($('.smtpSettings', document.users).fieldSerialize().split('&')).each(function(i,n) {
			var temp = n.split('=');
			if (temp.length == 2) values[temp[0]] = temp[1];
		});
		return values;
	}

	function closePopup() {
		tb_remove();
	}

	/**
	 * Checks that all $(name)s matching 'pattern' are checked, or if
	 * reversed, checks that all $(name)s not matching 'pattern' are
	 * not checked.
	 */
	function allItemsChecked(opts, pattern, reverse)
	{
		var all_checked = true;
		$(opts).each(function() {
			if ((!reverse && this.name.match(pattern) && !this.checked) || (reverse && !this.name.match(pattern) && this.checked)) {
				all_checked = false;
				return false;
			}
		});
		return all_checked;
	}

	/**
	 * Loads/caches the checked state of boxes into bucket.
	 */
	function loadCheckboxes(opts)
	{
		var bucket = [];
		opts.each(function() {
			bucket.push({"name": this.name, "checked": this.checked});
		});
		return bucket;
	}


	// This is called by the ShowTab function in javascript.js
	function onShowTab(tab) {
		// Google tab
		if (tab == 4) {
			$('#cmdTestGoogleCalendar').show();
		} else {
			$('#cmdTestGoogleCalendar').hide();
		}
	}
	
	// To load up default value from whatver form saved last
	function SetDefaultTab(id) {
		$('#id_tab_num').val(id);
	}
	
</script>
