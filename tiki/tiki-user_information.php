<?php

// $Header: /cvsroot/tikiwiki/tiki/tiki-user_information.php,v 1.27 2005-01-22 22:54:56 mose Exp $

// Copyright (c) 2002-2005, Luis Argerich, Garland Foster, Eduardo Polidor, et. al.
// All Rights Reserved. See copyright.txt for details and a complete list of authors.
// Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See license.txt for details.

// Initialization
require_once ('tiki-setup.php');

include_once ('lib/messu/messulib.php');
include_once ('lib/userprefs/scrambleEmail.php');

// admin can alway view user information.
// others can only view user information if they have the correct permission.
if ($user != "admin" && !($tiki_p_view_user_information == 'y')) {
  $smarty->assign('msg', tra("You don't have permission to view user information (tiki_p_view_user_information)"));
  $smarty->display("error.tpl");
  die;
}

if (isset($_REQUEST['view_user'])) {
	$userwatch = $_REQUEST['view_user'];
} else {
	if ($user) {
		$userwatch = $user;
	} else {
		$smarty->assign('msg', tra("You are not logged in and no user indicated"));

		$smarty->display("error.tpl");
		die;
	}
}

$smarty->assign('userwatch', $userwatch);

if ($feature_friends == 'y') {
     $smarty->assign('friend', $tikilib->verify_friendship($userwatch, $user));
}

if (!$userlib->user_exists($userwatch)) {
	$smarty->assign('msg', tra("Unknown user"));

	$smarty->display("error.tpl");
	die;
}

if ($tiki_p_admin != 'y') {
	$user_information = $tikilib->get_user_preference($userwatch, 'user_information', 'public');

	if ($user_information == 'private') {
		$smarty->assign('msg', tra("The user has chosen to make his information private"));
		$smarty->display("error.tpl");
		die;
	}
}

$smarty->assign('mid', 'tiki-user_information.tpl');

if ($user) {
	$smarty->assign('sent', 0);

	if (isset($_REQUEST['send'])) {
		check_ticket('user-information');
		$smarty->assign('sent', 1);

		$message = '';

		// Validation:
		// must have a subject or body non-empty (or both)
		if (empty($_REQUEST['subject']) && empty($_REQUEST['body'])) {
			$smarty->assign('message', tra('ERROR: Either the subject or body must be non-empty'));

			$smarty->display("tiki.tpl");
			die;
		}

		$message = tra('Message sent to'). ':' . $userwatch . '<br />';
		$messulib->post_message($userwatch, $user, $_REQUEST['to'],
			'', $_REQUEST['subject'], $_REQUEST['body'], $_REQUEST['priority']);

		$smarty->assign('message', $message);
	}
}
if ($feature_score == 'y' and isset($user) and $user != $userwatch) {
	$tikilib->score_event($user, 'profile_see');
	$tikilib->score_event($userwatch, 'profile_is_seen');
}
global $site_style;

$smarty->assign('priority',3);
$allowMsgs = $tikilib->get_user_preference($userwatch,'allowMsgs','y');
$smarty->assign('allowMsgs',$allowMsgs);
$user_style = $tikilib->get_user_preference($userwatch,'theme',$site_style);
$user_language = $tikilib->get_language($userwatch);
$smarty->assign_by_ref('user_language',$user_language);
$smarty->assign_by_ref('user_style',$user_style);
$realName = $tikilib->get_user_preference($userwatch,'realName','');
$country = $tikilib->get_user_preference($userwatch,'country','Other');
$smarty->assign('country',$country);
$anonpref = $tikilib->get_preference('userbreadCrumb',4);
$userbreadCrumb = $tikilib->get_user_preference($userwatch,'userbreadCrumb',$anonpref);
$smarty->assign_by_ref('realName',$realName);
$smarty->assign_by_ref('userbreadCrumb',$userbreadCrumb);
$homePage = $tikilib->get_user_preference($userwatch,'homePage','');

$smarty->assign_by_ref('homePage',$homePage);

$avatar = $tikilib->get_user_avatar($userwatch);
$smarty->assign('avatar', $avatar);

$user_information = $tikilib->get_user_preference($userwatch, 'user_information', 'public');
$smarty->assign('user_information', $user_information);

$timezone_options = $tikilib->get_timezone_list(true);
$smarty->assign_by_ref('timezone_options', $timezone_options);
$server_time = new Date();
$display_timezone = $tikilib->get_user_preference($userwatch, 'display_timezone', $server_time->tz->getID());
$smarty->assign_by_ref('display_timezone', $display_timezone);

$userinfo = $userlib->get_user_info($userwatch);
$email_isPublic = $tikilib->get_user_preference($userwatch, 'email is public', 'n');
if ($email_isPublic != 'n') {
	$userinfo['email'] = scrambleEmail($userinfo['email'], $email_isPublic);
}
$smarty->assign_by_ref('userinfo', $userinfo);
$smarty->assign_by_ref('email_isPublic',$email_isPublic);
$userPage = $feature_wiki_userpage_prefix.$userinfo['login'];
$exist = $tikilib->page_exists($userPage);
$smarty->assign("userPage_exists", $exist);

ask_ticket('user-information');

$smarty->display("tiki.tpl");

?>
