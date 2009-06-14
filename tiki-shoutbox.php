<?php

// $Id$

// Copyright (c) 2002-2007, Luis Argerich, Garland Foster, Eduardo Polidor, et. al.
// All Rights Reserved. See copyright.txt for details and a complete list of authors.
// Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See license.txt for details.

// Initialization
require_once ('tiki-setup.php');
include_once ('lib/shoutbox/shoutboxlib.php');

if ($prefs['feature_shoutbox'] != 'y') {
	$smarty->assign('msg', tra("This feature is disabled").": feature_shoutbox");
	$smarty->display("error.tpl");
	die;
}

if ($tiki_p_view_shoutbox != 'y') {
	$smarty->assign('errortype', 401);
	$smarty->assign('msg', tra("You do not have permission to use this feature"));
	$smarty->display("error.tpl");
	die;
}

if (!isset($_REQUEST["msgId"])) {
	$_REQUEST["msgId"] = 0;
}

$smarty->assign('msgId', $_REQUEST["msgId"]);
if ($_REQUEST["msgId"]) {
	$info = $shoutboxlib->get_shoutbox($_REQUEST["msgId"]);
	$owner=$info["user"];
	if ($tiki_p_admin_shoutbox != 'y' &&  $owner != $user) {
		$smarty->assign('msg', tra("You do not have permission to edit messages $owner"));
		$smarty->display("error.tpl");
		die;
	}
} else {
	$info = array();
	$info["message"] = '';
	$info["user"] = $user;
	$owner=$info["user"];
}

$smarty->assign('message', $info["message"]);

if ($tiki_p_admin_shoutbox == 'y' || $user == $owner ) {
	if (isset($_REQUEST["remove"])) {
		$area = 'delshoutboxitem';
		if ($prefs['feature_ticketlib2'] != 'y' or (isset($_POST['daconfirm']) and isset($_SESSION["ticket_$area"]))) {
			key_check($area);
			$shoutboxlib->remove_shoutbox($_REQUEST["remove"]);
		} else {
			key_get($area);
		}
	} elseif (isset($_REQUEST["shoutbox_admin"])) {
		$prefs['shoutbox_autolink'] = (isset($_REQUEST["shoutbox_autolink"])) ? 'y' : 'n';
		$tikilib->set_preference('shoutbox_autolink',$prefs['shoutbox_autolink']);
	}
}

if ($tiki_p_post_shoutbox == 'y') {
	if (isset($_REQUEST["save"]) && !empty($_REQUEST['message'])) {
		check_ticket('shoutbox');
		if (($prefs['feature_antibot'] == 'y' && empty($user)) && (!isset($_SESSION['random_number']) || $_SESSION['random_number'] != $_REQUEST['antibotcode'])) {
			$smarty->assign('msg',tra("You have mistyped the anti-bot verification code; please try again."));
			if (!empty($_REQUEST['message'])) $smarty->assign_by_ref('message', $_REQUEST['message']);
		} else {
			$shoutboxlib->replace_shoutbox($_REQUEST['msgId'], $owner, $_REQUEST['message']);
			$smarty->assign('msgId', '0');
			$smarty->assign('message', '');
		}
	}
}

if (!isset($_REQUEST["sort_mode"])) {
	$sort_mode = 'timestamp_desc';
} else {
	$sort_mode = $_REQUEST["sort_mode"];
}

if (!isset($_REQUEST["offset"])) {
	$offset = 0;
} else {
	$offset = $_REQUEST["offset"];
}

$smarty->assign_by_ref('offset', $offset);

if (isset($_REQUEST["find"])) {
	$find = $_REQUEST["find"];
} else {
	$find = '';
}

/* additions for ajax (formerly shoutjax) */

function processShout($formValues, $destDiv = 'mod-shoutbox') {
	global $shoutboxlib, $user, $smarty, $prefs, $ajaxlib, $tiki_p_admin_shoutbox;
	
	if (array_key_exists('shout_msg',$formValues) && strlen($formValues['shout_msg']) > 2) {
		if (empty($user) && $prefs['feature_antibot'] == 'y' && (!isset($_SESSION['random_number']) || $_SESSION['random_number'] != $formValues['antibotcode'])) {
			$smarty->assign('shout_error', tra('You have mistyped the anti-bot verification code; please try again.'));
			$smarty->assign_by_ref('shout_msg', $formValues['shout_msg']);
		} else {
			$shoutboxlib->replace_shoutbox(0, $user, $formValues['shout_msg']);
		}
	} else if (array_key_exists('shout_remove',$formValues) && $formValues['shout_remove'] > 0) {
		$info = $shoutboxlib->get_shoutbox($formValues['shout_remove']);
		if ($tiki_p_admin_shoutbox == 'y'  || $info['user'] == $user ) {
			$shoutboxlib->remove_shoutbox($formValues['shout_remove']);
		}
	}

	$ajaxlib->registerTemplate('mod-shoutbox.tpl');
	
	include('lib/wiki-plugins/wikiplugin_module.php');
	$data = wikiplugin_module('', Array('module'=>'shoutbox','max'=>10,'np'=>0,'nobox'=>'y','notitle'=>'y'));
	$objResponse = new xajaxResponse();
	$objResponse->assign($destDiv,"innerHTML",$data);
	return $objResponse;
}

if ($prefs['feature_ajax'] == 'y') {
	global $ajaxlib;
	include_once('lib/ajax/ajaxlib.php');
	$ajaxlib->registerFunction('processShout');
	$ajaxlib->registerTemplate('mod-shoutbox.tpl');
	$ajaxlib->processRequests();


}
/* end additions for ajax */

$smarty->assign('find', $find);

$smarty->assign_by_ref('sort_mode', $sort_mode);
$channels = $shoutboxlib->list_shoutbox($offset, $maxRecords, $sort_mode, $find);

$smarty->assign_by_ref('cant_pages', $channels["cant"]);

$smarty->assign_by_ref('channels', $channels["data"]);

ask_ticket('shoutbox');

// Display the template
$smarty->assign('mid', 'tiki-shoutbox.tpl');
$smarty->display("tiki.tpl");

?>
