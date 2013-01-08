<?php
/**
 * This file will handle "unsubcribe" action that is instigated by subscribers.
 *
 *
 * @todo refactor
 */

// Include common procedure.
defined('IEM_UNSUBSCRIBE_HACK') or define('IEM_UNSUBSCRIBE_HACK', true);
require_once dirname(__FILE__) . '/unsubscribe_common.php';

defined('SENDSTUDIO_USEMULTIPLEUNSUBSCRIBE') or define('SENDSTUDIO_USEMULTIPLEUNSUBSCRIBE', '0');

if (!SENDSTUDIO_USEMULTIPLEUNSUBSCRIBE) {
	require_once dirname(__FILE__) . '/unsubscribe_confirmed.php';
	exit();
}






$primary_listid = 0;
if (isset($foundparts['l'])) {
	$primary_listid = $foundparts['l'];
}

if (isset($foundparts['a'])) {
	$statstype = 'auto';
	$statid = $foundparts['a'];
} elseif (isset($foundparts['n'])) {
	$statstype = 'newsletter';
	$statid = $foundparts['n'];
}

if ($statstype) {
	$validLists = $subscriberapi->GetSubscribersListByStatOwner($statid, $subscriber_id, $statstype);
} else {
	// default
	$validLists[] = array('listid' => $primary_listid, 'subscriberid' => $subscriber_id);
}

$displayList = array();
foreach ($validLists as $eachList) {
	$listapi->Load($eachList['listid']);
	$subscriberlistinfo = $subscriberapi->LoadSubscriberList($eachList['subscriberid'], $eachList['listid']);
	$displayList[] = array('listid' => $eachList['listid'], 'name' => $listapi->Get('name'), 'cc' => $subscriberlistinfo['confirmcode'], 'subscriberid' => $eachList['subscriberid']);
}

if (!sizeof($displayList)) {
	$GLOBALS['DisplayMessage'] = GetLang('DefaultUnsubscribeMessage');
	$sendstudio_functions->ParseTemplate('Default_Form_Message');
	exit();
}

$GLOBALS['Message'] = '<div style="padding:10px;">'.GetLang('Unsubscribe_Form_Note').'</div>';

$tpl = GetTemplateSystem();
$tpl->Assign('page', $_GET);
$tpl->Assign('list', $displayList);
$tpl->Assign('primary_listid', $primary_listid);
echo $tpl->ParseTemplate('unsubscribe_form', true);
