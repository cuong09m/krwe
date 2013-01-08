<?php

/**
 * Anything in this file has a mention or link to http://www.interspire.com.
 * This makes it easy to remove all references to us.
 *
 * @package    SendStudio
 * @subpackage Language
 */

/**
 * Please backup before you start.
 */

defined('LNG_ApplicationTitle') or define('LNG_ApplicationTitle', 'Interspire Email Marketer');
define('LNG_HLP_UseSMTP', 'Choose this option to specify the details of your own external SMTP server, which will be used to send your email campaigns and autoresponders.');
define('LNG_UseSMTPCOM', 'SMTP.com Sending');
define('LNG_HLP_UseSMTPCOM', 'Choose this option if you have an SMTP.com account or would like to sign up for one. SMTP.com have partnered with Interspire to offer our customers a way to send large amounts of emails through their own SMTP server.');
define('LNG_SMTPCOM_UseSMTPOption', 'Sign up for an SMTP.com account');
define('LNG_SMTPCOM_UseSMTPOptionSeeBelow', ' (see below)');
define('LNG_SMTPCOM_Header', 'What is SMTP.com?');
define('LNG_SMTPCOM_Explain', '
	<a href="http://anonym.to/?http://interspire.smtp.com/" target="_blank" border="0"><img src="images/smtp_com_logo.gif" border="0" /></a>

	<div class="toolTipBox" style="width:95%; padding:10px">SMTP.com provides a way to send
	emails without relying on your web hosting provider. They take care of email sending
	and handle large amounts of emails reliably, so if you\'re on a shared server or need to
	send a large amount of emails, SMTP.com can provide you with a custom SMTP server. They
	take care of dealing with blacklisting and all other server administration tasks, so you
	can focus on creating and sending your email campaigns and autoresponders, without
	worrying about deliverability issues.</div>

	<div>
		To get started with SMTP.com, just follow the steps below.

		<ol style="line-height:1.5">
			<li><a href="http://anonym.to/?http://interspire.smtp.com/" target="_blank">Sign up for an SMTP.com account here</a> (Interspire customers receive a discount on the normal prices)</li>
			<li>Once you\'ve received your SMTP server details, select the "Let me specify my own SMTP server details" option above.</li>
			<li>Enter the details of your new SMTP.com account and click save.</li>
			<li>All email campaigns and autoresponders will now be sent using your new SMTP.com mail server.</li>
		</ol>
	</div>
');
define('LNG_NoMoreLists_LK', '');
define('LNG_Subject_Guide_Link', '<a class="FieldLabel" style="color: gray;" href="#" onClick="LaunchHelp(\'%%WHITELABEL_INFOTIPS%%\',\'800\'); return false;"><u>Follow this guide for tips on improving your subject lines.</u></a>');
define('LNG_CronNotSetup', 'You have enabled cron support, but the system has not yet detected a cron job running. <a href="#" onclick="LaunchHelp(\'%%WHITELABEL_INFOTIPS%%\',\'819\'); return false;">Learn how to fix it</a> and this message will go away.');
define('LNG_Menu_Support', 'Support');
define('LNG_Menu_Support_Description', 'Support');
define('LNG_Menu_Support_KnowledgeBase', 'Knowledge Base');
define('LNG_Menu_Support_KnowledgeBase_Description', 'Get help on common questions and read "How to" guides.');
define('LNG_Menu_Support_Forum', 'Community Forum');
define('LNG_Menu_Support_Forum_Description', 'Discuss '.LNG_ApplicationTitle.' with customers and partners.');
define('LNG_Menu_Support_SupportTicket', 'Support Ticket');
define('LNG_Menu_Support_SupportTicket_Description', 'Post a support ticket through the Interspire client area.');
define('LNG_SubscriberActivity_Last7Days', 'Contact Activity for the Last 7 Days');
define('LNG_VersionIsOutOfDate', '&nbsp;You are running version %s. A new version (%s) is available for download.');
define('LNG_VersionNumber', '&nbsp;You are currently running the latest version (%s).');
define('LNG_GettingStarted_Guide','<a href="#" onclick="LaunchGettingStarted(\'LaunchHelp(\\\'%%WHITELABEL_INFOTIPS%%\\\',\\\'812\\\')\'); return false;">Read the getting started guide</a> to learn more about '.LNG_ApplicationTitle);
define('LNG_LatestNews','Latest News');
define('LNG_Index_LatestNewsURL', 'http://anonym.to/?http://www.interspire.com/content/blogrss.php');
define('LNG_PopularHelpArticles', 'Popular Help Articles');
define('LNG_Index_PopularArticlesURL', 'http://anonym.to/?http://www.viewkb.com/rss.php?c=138&t=popular');
define('LNG_UpgradeNoticeInfo', '
	<p>You\'re currently running the Starter edition of ' . LNG_ApplicationTitle . '.
	Upgrade today to send more emails and access more features including:</p>

	<ul>
		<li>Send thousands or millions of emails</li>
		<li>Create and send automatic emails</li>
		<li>Segment and filter your contact lists</li>
		<li>Track campaigns with Google Analytics support</li>
		<li>Export your contact lists</li>
		<li>Schedule emails to be sent at a later date</li>
		<li>Import contacts from your existing system</li>
	</ul>
');
define('LNG_ImportantInformation', 'Important Information');
define('LNG_ImportantInformation_Start', 'Upgrade and Send More Emails Today!');
define('LNG_Limit_Over', 'You are over the maximum number of contacts you are allowed to have. You have <i>%s</i> in total and your limit is <i>%s</i>. You will only be able to send to a maximum of %s at a time.');
define('LNG_Limit_Reached', 'You have reached the maximum number of contacts you are allowed to have. You have <i>%s</i> contacts and your limit is <i>%s</i> in total.');
define('LNG_Limit_Close', 'You are reaching the total number of contacts for which you are licensed. You have <i>%s</i> contacts and your limit is <i>%s</i> in total.');
define('LNG_SendSize_Many_Max', 'Your license allows you to send a maximum of %s emails at once. You are trying to send %s emails, so only the first %s emails will be sent.');
define('LNG_SendSize_Many_Max_Alert', '--- Important: Please Read ---\n\nYour license allows you to send a maximum of %s emails at once. You are trying to send %s emails, so only the first %s emails will be sent.\n\nTo send more emails, please upgrade. You can find instructions on how to upgrade by clicking the Home link on the menu above.');
define('LNG_Send_NoCronEnabled_Explain_Admin', 'This email campaign will be sent immediately using the popup sending method. To learn how to setup scheduled sending, read <a href="#" onClick="LaunchHelp(\'%%WHITELABEL_INFOTIPS%%\',\'841\'); return false;">this article</a>.');
define('LNG_SendSize_Many', 'This email campaign will be sent to approximately %s contacts.');
define('LNG_UpgradeMeLK', '');
define('LNG_ApplicationTitleEdition', ' (%s Edition)');
define('LNG_SendingSystem', LNG_ApplicationTitle);
define('LNG_UrlPF_Intro', '');
define('LNG_UrlPF_ExtraIntro', '');
define('LNG_UrlPF_Intro_Done', '');
define('LNG_UrlP', '');
define('LNG_UrlPF_Heading', '');



/**
**************************
* Changed/added in 5.0.6
**************************
*/
defined('LNG_Default_Global_HTML_Footer')
or define('LNG_Default_Global_HTML_Footer', '');
defined('LNG_Default_Global_Text_Footer') or define('LNG_Default_Global_Text_Footer', "");



/**
 **************************
 * Changed/added in 5.5.0
 **************************
 */
define('LNG_Menu_Forms','Forms');
define('LNG_Form_Branding', '');



/**
 **************************
 * Changed/added in 5.6.0
 **************************
 */
define('LNG_Spam_Guide_Intro', '<strong>Please note:</strong> The results above should be used as a guide only and do not guarantee that your email will be delivered. Your email will be checked against a list of known spam keywords. The more keywords that are found, the higher your rating will be and the less likely it is that your contacts will receive your email. <a href="#" onClick="LaunchHelp(\'%%WHITELABEL_INFOTIPS%%\',\'802\'); return false;">Learn More.</a>');
define('LNG_Home_Video_Link','http://anonym.to/?http://static.interspire.com/iem-quick-tour/index.html');
define('LNG_Home_Getting_Starting_Link','http://anonym.to/?http://www.interspire.com/emailmarketer/pdf/InterspireEmailMarketerUserGuide.pdf');



/**
 **************************
 * Changed/added in 5.7.0
 **************************
 */
defined('LNG_Copyright') or define('LNG_Copyright', '');
defined('APPLICATION_LOGO_IMAGE') or define('APPLICATION_LOGO_IMAGE', 'images/logo.jpg');
defined('APPLICATION_FAVICON') or define('APPLICATION_FAVICON', 'images/favicon.ico');
defined('SHOW_SMTP_COM_OPTION') or define('SHOW_SMTP_COM_OPTION', true);
defined('SHOW_HELP_LINKS') or define('SHOW_HELP_LINKS', true);
defined('LNG_AccountUpgradeMessage') or define('LNG_AccountUpgradeMessage', 'You are on day %%trial_days_current%% of your free trial. You have %%trial_days_left%% days remaining.');
defined('SHOW_INTRO_VIDEO') or define('SHOW_INTRO_VIDEO', false);
defined('LNG_FreeTrial_Expiry_Login') or define('LNG_FreeTrial_Expiry_Login', 'Your free trial account has expired. Please contact your admin');
define('LNG_Limit_Info', 'You have <i>%s</i> contacts and your limit is <i>%s</i> in total.');



/**
 **************************
 * Changed/added in 5.7.2
 **************************
 */
define('APPLICATION_SHOW_WHITELABEL_MENU', true);
