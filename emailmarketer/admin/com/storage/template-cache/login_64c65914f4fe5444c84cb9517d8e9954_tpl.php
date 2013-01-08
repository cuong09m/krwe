<?php $IEM = $tpl->Get('IEM'); ?><style type="text/css">
	.popupContainer {
		border: 0px;
	}
</style>
<script>
	$(function() {
		$(document.frmLogin.ss_takemeto).val('<?php if(isset($GLOBALS['ss_takemeto'])) print $GLOBALS['ss_takemeto']; ?>');
	});
</script>
<form action="index.php?Page=<?php print $IEM['CurrentPage']; ?>&Action=<?php if(isset($GLOBALS['SubmitAction'])) print $GLOBALS['SubmitAction']; ?>" method="post" name="frmLogin" id="frmLogin">
	<div id="box" class="loginBox">
		<table><tr><td style="border:solid 2px #DDD; padding:20px; background-color:#FFF; width:300px;">
		<table>
			<tr>
			<td class="Heading1">
				<img src="<?php print $IEM['ApplicationLogoImage']; ?>" alt="<?php echo GetLang('SendingSystem'); ?>" />
			</td>
			</tr>
			<tr>
			<td style="padding:10px 0px 5px 0px"><?php if(isset($GLOBALS['Message'])) print $GLOBALS['Message']; ?></td>
			</tr>
			<tr>
			<td>
				<table>

				<tr>
					<td nowrap="nowrap" style="padding:0px 10px 0px 10px"><?php print GetLang('UserName'); ?>:</td>
					<td>
					<input type="text" name="ss_username" id="username" class="Field150" value="<?php if(isset($GLOBALS['ss_username'])) print $GLOBALS['ss_username']; ?>">
					</td>
				</tr>
				<tr>
					<td nowrap="nowrap" style="padding:0px 10px 0px 10px"><?php print GetLang('Password'); ?>:</td>

					<td>
					<input type="password" name="ss_password" id="password" class="Field150" value="">
					</td>
				</tr>
				<tr>
					<td nowrap="nowrap" style="padding:0px 10px 0px 10px"><?php print GetLang('TakeMeTo'); ?>:</td>
					<td>
						<select name="ss_takemeto" class="Field150">
							<option value="index.php"><?php print GetLang('TakeMeTo_HomePage'); ?></option>
							<option value="index.php?Page=Subscribers&Action=Manage"><?php print GetLang('TakeMeTo_Contacts'); ?></option>
							<option value="index.php?Page=Lists"><?php print GetLang('TakeMeTo_Lists'); ?></option>
							<option value="index.php?Page=Segment"><?php print GetLang('TakeMeTo_Segments'); ?></option>
							<option value="index.php?Page=Newsletters&Action=Manage"><?php print GetLang('TakeMeTo_Campaign'); ?></option>
							<option value="index.php?Page=Autoresponders&Action=Manage"><?php print GetLang('TakeMeTo_Autoresponder'); ?></option>
							<option value="index.php?Page=Stats"><?php print GetLang('TakeMeTo_Statistics'); ?></option>
						</select>
					</td>
				</tr>
				<tr>
					<td nowrap>&nbsp;</td>
					<td>&nbsp;<input type="checkbox" name="rememberme" id="remember" value="1" style="margin-left:-0px" > <label for="remember"><?php print GetLang('RememberMe'); ?></label>
					</td>
				</tr>
					<tr>
					<td>&nbsp;</td>
					<td>
						<input type="submit" name="SubmitButton" value="<?php print GetLang('Login'); ?>" class="FormButton">
						&nbsp;&nbsp;<?php print GetLang('ForgotPasswordReminder'); ?>
					</td>
					</tr>

					<tr><td class="Gap"></td></tr>
				</table>
			</td>
			</tr>
		</table>
		</td></tr>

		<tr>
			<td>

				<div class="PageFooter" style="padding: 10px 10px 10px 0px; margin-bottom: 20px; text-align: center;">
					<?php print GetLang('Copyright'); ?>
				</div>
			</td>
		</tr>

		</table>

	</div>

	</form>

	<script>

		$('#frmLogin').submit(function() {
			var f = document.frmLogin;

			if(f.username.value == '')
			{
				alert('Please enter your username.');
				f.username.focus();
				f.username.select();
				return false;
			}

			if(f.password.value == '')
			{
				alert('Please enter your password.');
				f.password.focus();
				f.password.select();
				return false;
			}

			// Everything is OK
			f.action = 'index.php?Page=<?php print $IEM['CurrentPage']; ?>&Action=<?php if(isset($GLOBALS['SubmitAction'])) print $GLOBALS['SubmitAction']; ?>';
			return true;
		});

		function sizeBox() {
			var w = $(window).width();
			var h = $(window).height();
			$('#box').css('position', 'absolute');
			$('#box').css('top', h/2-($('#box').height()/2)-50);
			$('#box').css('left', w/2-($('#box').width()/2));
		}

		$(document).ready(function() {
			sizeBox();
			$('#username').focus();
		});

		$(window).resize(function() {
			sizeBox();
		});
		createCookie("screenWidth", screen.availWidth, 1);

	</script>