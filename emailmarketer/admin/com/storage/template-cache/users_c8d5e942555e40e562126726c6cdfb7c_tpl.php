<?php $IEM = $tpl->Get('IEM'); ?><style type="text/css">
	tr.Heading3 td { white-space:nowrap }
	div.UserInfo img { margin-top:-3px }
	
	div.NoRecords { padding-top: 50px; text-align: center }

	table#ActionContainerTop { padding-top:5px; border:0; width:100% }
	table#ActionContainerTop input#createAccountButton { width:150px }
	table#ActionContainerTop button#deleteAccountButton { white-space:nowrap }
	table#ActionContainerTop div#deleteAccount { display:none; width:130px }
	table#ActionContainerTop div#deleteAccount img { border:0; padding:0 5px 0 0; margin:0; vertical-align:middle }
</style>
<table cellspacing="0" cellpadding="0" width="100%" align="center">
	<tr><td class="Heading1"><?php echo GetLang('Users'); ?><?php if($tpl->Get('groupInformation','groupid')): ?> : <?php echo $tpl->Get('groupInformation','groupname'); ?><?php endif; ?></td></tr>
	<tr><td class="body pageinfo"><p><?php echo GetLang('Help_Users'); ?></p></td></tr>
	<?php if(trim($tpl->Get('PAGE','messages')) != ''): ?><tr><td><?php echo $tpl->Get('PAGE','messages'); ?></td></tr><?php endif; ?>
	<?php if($tpl->Get('PAGE','userreport')): ?>
	<tr>
		<td>
			<div class="UserInfo">
				<img src="images/user.gif" alt="user_icon" align ="left"><?php echo $tpl->Get('PAGE','userreport'); ?>
			</div>
		</td>
	</tr>
	<?php endif; ?>
	<tr>
		<td class="body">
			<form name="userform" method="post" action="index.php?Page=Users">
				<table id="ActionContainerTop">
					<tr>
						<td colspan="2" align="right" style="padding-bottom: 10px;">
					 		<input type="text" class="Field250" size="20" value="<?php echo $tpl->Get('quicksearchstring'); ?>" name="QuickSearchString" title="Search for emails in this mailing list." />
							<input type="image" border="0" src="images/searchicon.gif" id="SearchButton" style="padding-left: 10px; vertical-align: top;" name="SearchButton" alt="Search" />
							<input type="submit" value="search" style="display:none;" />
						</td>
					</tr>
					<tr>
						<td>
							<input id="createAccountButton" type="button" class="SmallButton" value="<?php echo GetLang('UserAdd'); ?>" disabled="disabled" />
							<?php if(count($tpl->Get('records')) != 0): ?>
								<button id="deleteAccountButton" class="SmallButton"><?php echo GetLang('UserDeletePopDown'); ?></button>
								<div id="deleteAccount" class="DropDownMenu DropShadow">
									<ul>
										<li>
											<a href="#N" title="<?php echo GetLang('UserDeleteNoData_Summary'); ?>">
												<img src="images/lists_view.gif" alt="icon" /> <?php echo GetLang('UserDeleteNoData'); ?>
											</a>
										</li>
										<li>
											<a href="#Y" title="<?php echo GetLang('UserDeleteWithData_Summary'); ?>">
												<img src="images/lists_view.gif" alt="icon" /> <?php echo GetLang('UserDeleteWithData'); ?>
											</a>
										</li>
									</ul>
								</div>
							<?php endif; ?>
						</td>
						<?php if(count($tpl->Get('records')) != 0): ?><td align="right" valign="bottom"><?php $tmpTplFile = $tpl->GetTemplate();
			$tmpTplData = $tpl->TemplateData;
			$tpl->ParseTemplate("Paging");
			$tpl->SetTemplate($tmpTplFile);
			$tpl->TemplateData = $tmpTplData; ?></td><?php endif; ?>
					</tr>
				</table>
				<?php if(count($tpl->Get('records')) == 0): ?>
					<div class="NoRecords"><?php echo GetLang('SearchRecordNotFound'); ?></div>
				<?php else: ?>
					<table border="0" cellspacing="0" cellpadding="0" width="100%" class="Text" id="UserListTable">
						<tr class="Heading3">
							<td width="5" nowrap align="center">
								<input type="checkbox" name="toggle" class="UICheckboxToggleSelector" />
							</td>
							<td width="5">&nbsp;</td>
							<td width="24%">
								<?php echo GetLang('UserName'); ?>
								<a href="index.php?Page=Users&SortBy=username&Direction=Up"><img src="images/sortup.gif" border="0" /></a>
								<a href="index.php?Page=Users&SortBy=username&Direction=Down"><img src="images/sortdown.gif" border="0" /></a>
							</td>
							<td width="24%">
								<?php echo GetLang('FullName'); ?>
								<a href="index.php?Page=Users&SortBy=fullname&Direction=Up"><img src="images/sortup.gif" border="0" /></a>
								<a href="index.php?Page=Users&SortBy=fullname&Direction=Down"><img src="images/sortdown.gif" border="0" /></a>
							</td>
							<td width="12%">
								<?php echo GetLang('UserType'); ?>
								<a href="index.php?Page=Users&SortBy=admintype&Direction=Up"><img src="images/sortup.gif" border="0" /></a>
								<a href="index.php?Page=Users&SortBy=admintype&Direction=Down"><img src="images/sortdown.gif" border="0" /></a>
							</td>
							<td width="15%">
								<?php echo GetLang('UsersGroups'); ?>
								<a href="index.php?Page=Users&SortBy=usergroup&Direction=Up"><img src="images/sortup.gif" border="0" /></a>
								<a href="index.php?Page=Users&SortBy=usergroup&Direction=Down"><img src="images/sortdown.gif" border="0" /></a>
							</td>
							<td width="12%">
								<?php echo GetLang('UserCreatedOn'); ?>
								<a href="index.php?Page=Users&SortBy=createdate&Direction=Up"><img src="images/sortup.gif" border="0" /></a>
								<a href="index.php?Page=Users&SortBy=createdate&Direction=Down"><img src="images/sortdown.gif" border="0" /></a>
							</td>
							<td width="12%">
								<?php echo GetLang('LastLoggedIn'); ?>
								<a href="index.php?Page=Users&SortBy=lastloggedin&Direction=Up"><img src="images/sortup.gif" border="0" /></a>
								<a href="index.php?Page=Users&SortBy=lastloggedin&Direction=Down"><img src="images/sortdown.gif" border="0" /></a>
							</td>
							<td width="15">
								<?php echo GetLang('UserStatusColumn'); ?>
								<a href="index.php?Page=Users&SortBy=status&Direction=Up"><img src="images/sortup.gif" border="0" /></a>
								<a href="index.php?Page=Users&SortBy=status&Direction=Down"><img src="images/sortdown.gif" border="0" /></a>
							</td>
							<td width="70">
								<?php echo GetLang('Action'); ?>
							</td>
						</tr>
						<?php $array = $tpl->Get('records'); if(is_array($array) || is_object($array)): foreach($array as $__key=>$each): $tpl->Assign('__key', $__key, false); $tpl->Assign('each', $each, false);  ?>
							<tr class="GridRow UserRecordRow">
								<td valign="top" align="center"><input type="checkbox" name="users[]" value="<?php echo $tpl->Get('each','userid'); ?>" class="UICheckboxToggleRows" /></td>
								<td><img src="images/user.gif"></td>
								<td><?php echo $tpl->Get('each','username'); ?></td>
								<td><?php if(trim($tpl->Get('each','fullname')) == ''): ?><?php echo GetLang('N/A'); ?><?php else: ?><?php echo $tpl->Get('each','fullname'); ?><?php endif; ?></td>
								<td style="white-space: nowrap;">
									<?php if($tpl->Get('each','admintype') == 'a'): ?>
										<?php echo GetLang('AdministratorType_SystemAdministrator'); ?>
									<?php elseif($tpl->Get('each','admintype') == 'l'): ?>
										<?php echo GetLang('AdministratorType_ListAdministrator'); ?>
									<?php elseif($tpl->Get('each','admintype') == 'n'): ?>
										<?php echo GetLang('AdministratorType_NewsletterAdministrator'); ?>
									<?php elseif($tpl->Get('each','admintype') == 't'): ?>
										<?php echo GetLang('AdministratorType_TemplateAdministrator'); ?>
									<?php elseif($tpl->Get('each','admintype') == 'u'): ?>
										<?php echo GetLang('AdministratorType_UserAdministrator'); ?>
									<?php elseif(!$tpl->Get('each','trialuser')): ?>
										<?php echo GetLang('AdministratorType_RegularUser'); ?>
									<?php else: ?>
										<?php echo GetLang('AdministratorType_TrialUser'); ?>
									<?php endif; ?>
								</td>
								<td style="white-space: nowrap;"><?php echo $tpl->Get('each','groupname'); ?></td>
								<td><?php echo $tpl->Get('each','processed_CreateDate'); ?></td>
								<td style="white-space:nowrap;"><?php echo $tpl->Get('each','processed_LastLoggedIn'); ?></td>
								<td align="center">
									<?php if($tpl->Get('each','status') == '0'): ?>
										<img alt="<?php echo GetLang('Inactive'); ?>" src="images/cross.gif" border="0" title="<?php echo GetLang('Inactive'); ?>" />
									<?php elseif($tpl->Get('each','status') == '1'): ?>
										<img alt="<?php echo GetLang('Active'); ?>" src="images/tick.gif" border="0" title="<?php echo GetLang('Active'); ?>" />
									<?php else: ?>
										-
									<?php endif; ?>
								</td>
								<td style="white-space:nowrap;">
									<a href="index.php?Page=Users&Action=Edit&UserID=<?php echo $tpl->Get('each','userid'); ?>"><?php echo GetLang('Edit'); ?></a>
									<?php if($tpl->Get('each','userid') != $tpl->Get('PAGE','currentuserid')): ?>
										&nbsp;<a href="index.php?page=AdminTools&action=disguise&newID=<?php echo $tpl->Get('each','userid'); ?>" class="ActionLink ActionType_disguise"><?php echo GetLang('LoginAsUser'); ?></a>
									<?php endif; ?>
								</td>
							</tr>
						 <?php endforeach; endif; ?>
					</table>
				<?php endif; ?>
			</form>
			
			<?php if(count($tpl->Get('records')) != 0): ?><?php $tmpTplFile = $tpl->GetTemplate();
			$tmpTplData = $tpl->TemplateData;
			$tpl->ParseTemplate("Paging_Bottom");
			$tpl->SetTemplate($tmpTplFile);
			$tpl->TemplateData = $tmpTplData; ?><?php endif; ?>
		</td>
	</tr>
</table>
<script type="text/javascript">
$(function() {
	Application.Ui.CheckboxSelection(	'table#UserListTable',
										'input.UICheckboxToggleSelector',
										'input.UICheckboxToggleRows');

	$(document.userform.QuickSearchString).focus(function() {
		if (this.readonly) {
			this.value = '';
		}

		this.readonly = false;
		$(this).css('color', '');
		this.select();
	});
	
	$(document.userform.QuickSearchString).blur(function() { EvaluateQuickSearch(); });
	EvaluateQuickSearch();

	<?php if(count($tpl->Get('records')) == 0): ?>
		$(document.userform.QuickSearchString).focus();
	<?php else: ?>
		$('table#UserListTable').click(function(e) {
			if (!$(e.target).is('a.ActionType_disguise')) return;
			var matches = $(e.target).attr('href').match(/(.*?)&newID=(\d+)$/);
	
			if (matches.length == 3) {
				Application.Util.submitPost(matches[1], {newUserID:matches[2]});
			}
	
			e.stopPropagation();
			e.preventDefault();
			return false;
		});
	
		$('div#deleteAccount a').click(function(e) {
			e.preventDefault();
	
			var deleteData = ($(this).attr('href').match(/^#Y/) == '#Y');
			var selectedIDs = [];
			var selectedRows = $('table tr.UserRecordRow input.UICheckboxToggleRows[type=checkbox]:checked');
			for(var i = 0, j = selectedRows.size(); i < j; ++i) selectedIDs.push(selectedRows.get(i).value);
	
			if (selectedRows.length == 0) {
				alert("<?php echo GetLang('ChooseUsersToDelete'); ?>");
				return true;
			}
	
			if ($.inArray('<?php echo $tpl->Get('PAGE','currentuserid'); ?>', selectedIDs) != -1) {
				alert("<?php echo GetLang('User_CantDeleteOwn'); ?>");
				return true;
			}
	
			if (!confirm(deleteData ? "<?php echo GetLang('ConfirmRemoveUsersWithData'); ?>" : "<?php echo GetLang('ConfirmRemoveUsers'); ?>")) {
				return true;
			}
	
			$('button#deleteAccountButton', document.userform).attr('disabled', true);
			Application.Util.submitPost('index.php?Page=Users&Action=Delete', {users:selectedIDs, deleteData:(deleteData ? '1' : '0')});
			return true;
		});
	<?php endif; ?>

	$('input#createAccountButton', document.userform).click(function() {
		document.location="index.php?Page=Users&Action=Add";
	});

	Application.Ui.Menu.PopDown('button#deleteAccountButton', {topMarginPixel: -3});
});

function DeleteSelectedUsers(formObj) {
	users_found = 0;
	for (var i=0;i < formObj.length;i++)
	{
		fldObj = formObj.elements[i];
		if (fldObj.type == 'checkbox')
		{
			if (fldObj.checked) {
				users_found++;
				break;
			}
		}
	}

	if (users_found <= 0) {
		alert("<?php print GetLang('ChooseUsersToDelete'); ?>");
		return false;
	}

	if (confirm("<?php print GetLang('ConfirmRemoveUsers'); ?>")) {
		return true;
	}
	return false;
}

function EvaluateQuickSearch() {
	var elm = document.userform.QuickSearchString;
	if(elm.value.trim() == '') {
		$(elm).css('color', '#999999');
		elm.value = '<?php echo GetLang('QuickUserSearchIntro'); ?>';
		elm.readonly = true;
	}
}
</script>