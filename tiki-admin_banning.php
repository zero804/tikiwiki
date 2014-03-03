<?php
// (c) Copyright 2002-2010 by authors of the Tiki Wiki/CMS/Groupware Project
// 
// All Rights Reserved. See copyright.txt for details and a complete list of authors.
// Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See license.txt for details.
// $Id$

require_once ('tiki-setup.php');
include_once ('lib/ban/banlib.php');
$access->check_feature('feature_banning');
$access->check_permission('tiki_p_admin_banning');

if (isset($_REQUEST['banId'])) {
	$info = $banlib->get_rule($_REQUEST['banId']);
} else {
	$_REQUEST['banId'] = 0;
	$info['sections'] = array();
	$info['title'] = '';
	$info['mode'] = 'user';
	$info['ip1'] = 255;
	$info['ip2'] = 255;
	$info['ip3'] = 255;
	$info['ip4'] = 255;
	$info['use_dates'] = 'n';
	$info['date_from'] = $tikilib->now;
	$info['date_to'] = $tikilib->now + 7 * 24 * 3600 * 100;
	$info['message'] = '';
}

if (!empty($_REQUEST['mass_ban_ip'])) {
	check_ticket('admin-banning');
	include_once ('lib/comments/commentslib.php');
	$commentslib = new Comments;
	$smarty->assign('mass_ban_ip', $_REQUEST['mass_ban_ip']);
	$info['mode'] = 'mass_ban_ip';
	$info['title'] = tr('Multiple IP Banning');
	$info['message'] = tr('Access from your localization was forbidden due to excessive spamming.');
	$info['date_to'] = $tikilib->now + 365 * 24 * 3600;
	$banId_list = explode('|',$_REQUEST['mass_ban_ip']);
	if ( !empty($_REQUEST['mass_remove']) ) {
		$access->check_authenticity(tra('Delete comments then set banning rules'));
	}
	foreach($banId_list as $id) {
		$ban_comment=$commentslib->get_comment($id);
		$ban_comments_list[$ban_comment['user_ip']][$id]['userName'] = $ban_comment['userName'];
		$ban_comments_list[$ban_comment['user_ip']][$id]['title'] = $ban_comment['title'];
		if ( !empty($_REQUEST['mass_remove']) ) {
			$commentslib->remove_comment($id);
		}
	}
	$smarty->assign_by_ref('ban_comments_list', $ban_comments_list);
}

$smarty->assign('banId', $_REQUEST['banId']);
$smarty->assign_by_ref('info', $info);
if (isset($_REQUEST['remove'])) {
	$access->check_authenticity();
	$banlib->remove_rule($_REQUEST['remove']);
}
if (isset($_REQUEST['del']) && isset($_REQUEST['delsec'])) {
	check_ticket('admin-banning');
	foreach(array_keys($_REQUEST['delsec']) as $sec) {
		$banlib->remove_rule($sec);
	}
}
if (isset($_REQUEST['save'])) {
	check_ticket('admin-banning');
	$_REQUEST['use_dates'] = isset($_REQUEST['use_dates']) ? 'y' : 'n';
	$_REQUEST['date_from'] = $tikilib->make_time(0, 0, 0, $_REQUEST['date_fromMonth'], $_REQUEST['date_fromDay'], $_REQUEST['date_fromYear']);
	$_REQUEST['date_to'] = $tikilib->make_time(0, 0, 0, $_REQUEST['date_toMonth'], $_REQUEST['date_toDay'], $_REQUEST['date_toYear']);
	$sections = array_keys($_REQUEST['section']);
	if ($_REQUEST['mode'] == 'mass_ban_ip') {
		foreach ($_REQUEST['multi_banned_ip'] as $ip => $value) {
			list($ip1,$ip2,$ip3,$ip4) = explode('.',$ip);
			$banlib->replace_rule($_REQUEST['banId'], 'ip', $_REQUEST['title'], $ip1, $ip2, $ip3, $ip4, $_REQUEST['userreg'], $_REQUEST['date_from'], $_REQUEST['date_to'], $_REQUEST['use_dates'], $_REQUEST['message'], $sections);
		}
	}else{
		$banlib->replace_rule($_REQUEST['banId'], $_REQUEST['mode'], $_REQUEST['title'], $_REQUEST['ip1'], $_REQUEST['ip2'], $_REQUEST['ip3'], $_REQUEST['ip4'], $_REQUEST['userreg'], $_REQUEST['date_from'], $_REQUEST['date_to'], $_REQUEST['use_dates'], $_REQUEST['message'], $sections);
	}
	$info['sections'] = array();
	$info['title'] = '';
	$info['mode'] = 'user';
	$info['ip1'] = 255;
	$info['ip2'] = 255;
	$info['ip3'] = 255;
	$info['ip4'] = 255;
	$info['use_dates'] = 'n';
	$info['date_from'] = $tikilib->now;
	$info['date_to'] = $tikilib->now + 7 * 24 * 3600;
	$info['message'] = '';
	$smarty->assign_by_ref('info', $info);
}

if (!isset($_REQUEST["sort_mode"])) {
	$sort_mode = 'created_desc';
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
$smarty->assign('find', $find);
$smarty->assign_by_ref('sort_mode', $sort_mode);
$items = $banlib->list_rules($offset, $maxRecords, $sort_mode, $find);
$smarty->assign('cant', $items['cant']);
$smarty->assign_by_ref('cant_pages', $items["cant"]);
$smarty->assign_by_ref('items', $items["data"]);
$smarty->assign('sections', $sections_enabled);
ask_ticket('admin-banning');
// disallow robots to index page:
$smarty->assign('metatag_robots', 'NOINDEX, NOFOLLOW');
$smarty->assign('mid', 'tiki-admin_banning.tpl');
$smarty->display("tiki.tpl");
