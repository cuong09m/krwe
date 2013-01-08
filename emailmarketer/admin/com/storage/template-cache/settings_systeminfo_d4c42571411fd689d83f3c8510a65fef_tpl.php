<?php $IEM = $tpl->Get('IEM'); ?><table cellspacing="0" cellpadding="0" width="100%" align="center" style="margin-left: 4px;">
	<tr>
		<td class="Heading1"><?php print GetLang('Settings_SystemInformation'); ?></td>
	</tr>
	<tr>
		<td class="body pageinfo"><p><?php print GetLang('Help_Settings_SystemInformation'); ?></p></td>
	</tr>
	<tr>
		<td>
			<table border="0" cellspacing="0" cellpadding="2" width="100%" class="Panel">
				<tr>
					<td colspan="2" class="Heading2">
						<div style="float:right;">
							<a href="index.php?Page=Settings&Action=showinfo" target="_blank"><?php print GetLang('ShowFullSystemInfo'); ?></a>
						</div>
						&nbsp;&nbsp;<?php print GetLang('ServerInfo'); ?>
					</td>
				</tr>
				<tr>
					<td class="FieldLabel">
						<?php $tmpTplFile = $tpl->GetTemplate();
			$tmpTplData = $tpl->TemplateData;
			$tpl->ParseTemplate("Not_Required");
			$tpl->SetTemplate($tmpTplFile);
			$tpl->TemplateData = $tmpTplData; ?>
						<?php print GetLang('ProductVersion'); ?>:
					</td>
					<td>
						<?php if(isset($GLOBALS['ProductVersion'])) print $GLOBALS['ProductVersion']; ?>
					</td>
				</tr>
				<tr style="display: <?php if(isset($GLOBALS['ShowProd'])) print $GLOBALS['ShowProd']; ?>">
					<td class="FieldLabel">
						<?php $tmpTplFile = $tpl->GetTemplate();
			$tmpTplData = $tpl->TemplateData;
			$tpl->ParseTemplate("Not_Required");
			$tpl->SetTemplate($tmpTplFile);
			$tpl->TemplateData = $tmpTplData; ?>
						<?php print GetLang('ProductEdition'); ?>:
					</td>
					<td>
						<?php if(isset($GLOBALS['ProductEdition'])) print $GLOBALS['ProductEdition']; ?>
					</td>
				</tr>
				<tr>
					<td class="FieldLabel">
						<?php $tmpTplFile = $tpl->GetTemplate();
			$tmpTplData = $tpl->TemplateData;
			$tpl->ParseTemplate("Not_Required");
			$tpl->SetTemplate($tmpTplFile);
			$tpl->TemplateData = $tmpTplData; ?>
						<?php print GetLang('Charset'); ?>:
					</td>
					<td>
						<input type="hidden" name="defaultcharset" value="<?php if(isset($GLOBALS['DefaultCharset'])) print $GLOBALS['DefaultCharset']; ?>">
						<?php if(isset($GLOBALS['CharsetDescription'])) print $GLOBALS['CharsetDescription']; ?>
					</td>
				</tr>
				<tr>
					<td class="FieldLabel">
						<?php $tmpTplFile = $tpl->GetTemplate();
			$tmpTplData = $tpl->TemplateData;
			$tpl->ParseTemplate("Not_Required");
			$tpl->SetTemplate($tmpTplFile);
			$tpl->TemplateData = $tmpTplData; ?>
						<?php print GetLang('ServerTimeZone'); ?>:
					</td>
					<td valign="top">
						<input type="hidden" name="servertimezone" value="<?php if(isset($GLOBALS['ServerTimeZone'])) print $GLOBALS['ServerTimeZone']; ?>">
						<?php if(isset($GLOBALS['ServerTimeZoneDescription'])) print $GLOBALS['ServerTimeZoneDescription']; ?>
					</td>
				</tr>
				<tr>
					<td class="FieldLabel">
						<?php $tmpTplFile = $tpl->GetTemplate();
			$tmpTplData = $tpl->TemplateData;
			$tpl->ParseTemplate("Not_Required");
			$tpl->SetTemplate($tmpTplFile);
			$tpl->TemplateData = $tmpTplData; ?>
						<?php print GetLang('CurrentServerTime'); ?>:
					</td>
					<td>
						<?php if(isset($GLOBALS['ServerTime'])) print $GLOBALS['ServerTime']; ?>
					</td>
				</tr>
				<tr>
					<td class="FieldLabel">
						<?php $tmpTplFile = $tpl->GetTemplate();
			$tmpTplData = $tpl->TemplateData;
			$tpl->ParseTemplate("Not_Required");
			$tpl->SetTemplate($tmpTplFile);
			$tpl->TemplateData = $tmpTplData; ?>
						<?php print GetLang('PHPVersion'); ?>:
					</td>
					<td>
						<?php if(isset($GLOBALS['PHPVersion'])) print $GLOBALS['PHPVersion']; ?>
					</td>
				</tr>
				<tr>
					<td class="FieldLabel">
						<?php $tmpTplFile = $tpl->GetTemplate();
			$tmpTplData = $tpl->TemplateData;
			$tpl->ParseTemplate("Not_Required");
			$tpl->SetTemplate($tmpTplFile);
			$tpl->TemplateData = $tmpTplData; ?>
						<?php print GetLang('SafeModeEnabled'); ?>:
					</td>
					<td>
						<?php if(isset($GLOBALS['SafeModeEnabled'])) print $GLOBALS['SafeModeEnabled']; ?>
					</td>
				</tr>
				<tr>
					<td class="FieldLabel">
						<?php $tmpTplFile = $tpl->GetTemplate();
			$tmpTplData = $tpl->TemplateData;
			$tpl->ParseTemplate("Not_Required");
			$tpl->SetTemplate($tmpTplFile);
			$tpl->TemplateData = $tmpTplData; ?>
						<?php print GetLang('ImapSupportFound'); ?>:
					</td>
					<td>
						<?php if(isset($GLOBALS['ImapSupportFound'])) print $GLOBALS['ImapSupportFound']; ?>
					</td>
				</tr>
				<tr>
					<td class="FieldLabel">
						<?php $tmpTplFile = $tpl->GetTemplate();
			$tmpTplData = $tpl->TemplateData;
			$tpl->ParseTemplate("Not_Required");
			$tpl->SetTemplate($tmpTplFile);
			$tpl->TemplateData = $tmpTplData; ?>
						<?php print GetLang('CurlSupportFound'); ?>:
					</td>
					<td>
						<?php if(isset($GLOBALS['CurlSupportFound'])) print $GLOBALS['CurlSupportFound']; ?>
					</td>
				</tr>
				<tr>
					<td class="FieldLabel">
						<?php $tmpTplFile = $tpl->GetTemplate();
			$tmpTplData = $tpl->TemplateData;
			$tpl->ParseTemplate("Not_Required");
			$tpl->SetTemplate($tmpTplFile);
			$tpl->TemplateData = $tmpTplData; ?>
						<?php print GetLang('GDVersion'); ?>:
					</td>
					<td>
						<?php if(isset($GLOBALS['GDVersion'])) print $GLOBALS['GDVersion']; ?>
					</td>
				</tr>
				<tr>
					<td class="FieldLabel">
						<?php $tmpTplFile = $tpl->GetTemplate();
			$tmpTplData = $tpl->TemplateData;
			$tpl->ParseTemplate("Not_Required");
			$tpl->SetTemplate($tmpTplFile);
			$tpl->TemplateData = $tmpTplData; ?>
						<?php print GetLang('ModSecurity'); ?>:
					</td>
					<td>
						<?php if(isset($GLOBALS['ModSecurity'])) print $GLOBALS['ModSecurity']; ?>
					</td>
				</tr>
				<tr>
					<td class="FieldLabel">
						<?php $tmpTplFile = $tpl->GetTemplate();
			$tmpTplData = $tpl->TemplateData;
			$tpl->ParseTemplate("Not_Required");
			$tpl->SetTemplate($tmpTplFile);
			$tpl->TemplateData = $tmpTplData; ?>
						<?php print GetLang('ServerSoftware'); ?>:
					</td>
					<td>
						<?php if(isset($GLOBALS['ServerSoftware'])) print $GLOBALS['ServerSoftware']; ?>
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
			</table>
		</td>
	</tr>
</table>
