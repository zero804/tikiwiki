<?php

// $Id: /cvsroot/tikiwiki/tiki/tiki-admingroups.php,v 1.62.2.10 2008-03-14 19:51:58 sylvieg Exp $

// Copyright (c) 2002-2007, Luis Argerich, Garland Foster, Eduardo Polidor, et. al.
// All Rights Reserved. See copyright.txt for details and a complete list of authors.
// Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See license.txt for details.

// Initialization
require_once ('tiki-setup.php');

if ($tiki_p_admin != 'y') {
	$smarty->assign('errortype', 401);
	$smarty->assign('msg', tra("You don't have permission to use this feature"));
	$smarty->display("error.tpl");
	die;
}

$cookietab = "1";
list($trackers,$ag_utracker,$ag_ufield,$ag_gtracker,$ag_gfield,$ag_rufields) = array(array(),0,0,0,0,'');

if (isset($prefs['groupTracker']) and $prefs['groupTracker'] == 'y') {
	$trackerlist = $tikilib->list_trackers(0, -1, 'name_asc', '');
	$trackers = $trackerlist['list'];
	if (isset($_REQUEST["groupstracker"]) and isset($trackers[$_REQUEST["groupstracker"]])) {
		$ag_gtracker = $_REQUEST["groupstracker"];
		if (isset($_REQUEST["groupfield"]) and $_REQUEST["groupfield"]) {
			$ag_gfield = $_REQUEST["groupfield"];
		}
	}
}

if (isset($prefs['userTracker']) and $prefs['userTracker'] == 'y') {
	if (!isset($trackerlist)) $trackerlist = $tikilib->list_trackers(0, -1, 'name_asc', '');
	$trackers = $trackerlist['list'];
	if (isset($_REQUEST["userstracker"]) and isset($trackers[$_REQUEST["userstracker"]])) {
		$ag_utracker = $_REQUEST["userstracker"];
		if (isset($_REQUEST["usersfield"]) and $_REQUEST["usersfield"]) {
			$ag_ufield = $_REQUEST["usersfield"];
		}
		if (!empty($_REQUEST['registrationUsersFieldIds'])) {
			$ag_rufields = $_REQUEST['registrationUsersFieldIds'];
		}
	}
}
$smarty->assign('trackers', $trackers);

$ag_home = '';
$ag_defcat = 0;
$ag_theme = '';
if (isset($_REQUEST["home"])) $ag_home = $_REQUEST["home"];
if (!empty($_REQUEST["defcat"])) $ag_defcat = $_REQUEST["defcat"];
if (isset($_REQUEST["theme"])) $ag_theme = $_REQUEST["theme"];

// Process the form to add a group
if (isset($_REQUEST["newgroup"]) and $_REQUEST["name"]) {
	check_ticket('admin-groups');
	// Check if the user already exists
	if ($userlib->group_exists($_REQUEST["name"])) {
		$smarty->assign('msg', tra("Group already exists"));
		$smarty->display("error.tpl");
		die;
	} else {
		$_REQUEST['userChoice'] = (isset($_REQUEST['userChoice']) && $_REQUEST['userChoice'] == 'on')? 'y': '';
		$userlib->add_group($_REQUEST["name"],$_REQUEST["desc"],$ag_home,$ag_utracker,$ag_gtracker, '', $_REQUEST['userChoice'],$ag_defcat,$ag_theme);
		if (isset($_REQUEST["include_groups"])) {
			foreach ($_REQUEST["include_groups"] as $include) {
				if ($_REQUEST["name"] != $include) {
					$userlib->group_inclusion($_REQUEST["name"], $include);
				}
			}
		}
	}
	$_REQUEST["group"] = $_REQUEST["name"];
	$logslib->add_log('admingroups','created group '.$_REQUEST["group"]);
}

// modification
if (isset($_REQUEST["save"]) and isset($_REQUEST["olgroup"]) and !empty($_REQUEST["name"])) {
	check_ticket('admin-groups');
	if (isset($_REQUEST['userChoice']) && $_REQUEST['userChoice'] == 'on') {
		$_REQUEST['userChoice'] = 'y';
	} else {
		$_REQUEST['userChoice'] = '';
	}
	$userlib->change_group($_REQUEST['olgroup'],$_REQUEST['name'],$_REQUEST['desc'],$ag_home,$ag_utracker,$ag_gtracker,$ag_ufield,$ag_gfield, $ag_rufields, $_REQUEST['userChoice'],$ag_defcat,$ag_theme);
	$userlib->remove_all_inclusions($_REQUEST["name"]);
	if (isset($_REQUEST["include_groups"]) and is_array($_REQUEST["include_groups"])) {		
		foreach ($_REQUEST["include_groups"] as $include) {
			if ($include && $_REQUEST["name"] != $include) {
				$userlib->group_inclusion($_REQUEST["name"], $include);
			}
		}
	}
	$_REQUEST["group"] = $_REQUEST["name"];
	$logslib->add_log('admingroups','modified group '.$_REQUEST["olgroup"].' to '.$_REQUEST["group"]);
}

// Process a form to remove a group
if (isset($_REQUEST["action"])) {
	if ($_REQUEST["action"] == 'delete') {		
		$area = 'delgroup';
		if ($prefs['feature_ticketlib2'] != 'y' or (isset($_POST['daconfirm']) and isset($_SESSION["ticket_$area"]))) {
			key_check($area);
			$userlib->remove_group($_REQUEST["group"]);
			$logslib->add_log('admingroups','removed group '.$_REQUEST["group"]);
			unset($_REQUEST['group']);
		} else {
			key_get($area, tra('Remove group: ').$_REQUEST['group']);
		}
	}
	if ($_REQUEST["action"] == 'remove') {
		$area = 'delgroupperm';
		if ($prefs['feature_ticketlib2'] != 'y' or (isset($_POST['daconfirm']) and isset($_SESSION["ticket_$area"]))) {
			key_check($area);
			$userlib->remove_permission_from_group($_REQUEST["permission"], $_REQUEST["group"]);
			$logslib->add_log('admingroups','removed permission '.$_REQUEST["permission"].' from group '.$_REQUEST["group"]);
    } else {
			key_get($area, sprintf(tra('Remove permission: %s on %s'), $_REQUEST['permission'], $_REQUEST['group']));
    }
	}
}

if (isset($_REQUEST['clean'])) {
	global $cachelib;require_once("lib/cache/cachelib.php");
	check_ticket('admin-groups');
	$cachelib->invalidate('grouplist');
}
if (!isset($_REQUEST["numrows"])) {
	$numrows = $maxRecords;
} else {
	$numrows = $_REQUEST["numrows"];
}
$smarty->assign_by_ref('numrows', $numrows);

if (!isset($_REQUEST["sort_mode"])) {
	$sort_mode = 'groupName_asc';
} else {
	$sort_mode = $_REQUEST["sort_mode"];
}
$smarty->assign_by_ref('sort_mode', $sort_mode);

if (!isset($_REQUEST["offset"])) {
	$offset = 0;
} else {
	$offset = $_REQUEST["offset"];
}
$smarty->assign_by_ref('offset', $offset);

if (isset($_REQUEST["initial"])) {
	$initial = $_REQUEST["initial"];
} else {
	$initial = '';
}
$smarty->assign('initial', $initial);
$smarty->assign('initials', split(' ','a b c d e f g h i j k l m n o p q r s t u v w x y z'));

if (isset($_REQUEST["find"])) {
	$find = $_REQUEST["find"];
} else {
	$find = '';
}
$smarty->assign('find', $find);


$users = $userlib->get_groups($offset, $numrows, $sort_mode, $find, $initial);

$inc = array();
list($groupname,$groupdesc,$grouphome,$userstrackerid,$usersfieldid,$grouptrackerid,$groupfieldid,$defcatfieldid,$themefieldid,$groupperms,$trackerinfo,$memberslist,$userChoice,$groupdefcat,$grouptheme) = array('','','','','','','','','','','','','','','');

if (!empty($_REQUEST["group"])) {
	$re = $userlib->get_group_info($_REQUEST["group"]);

	if (isset($re["groupName"]))
		$groupname = $re["groupName"];

	if (isset($re["groupDesc"]))
		$groupdesc = $re["groupDesc"];

	if(isset($re["groupHome"]))
		$grouphome = $re["groupHome"];

	if(isset($re["groupDefCat"]))
		$groupdefcat = $re["groupDefCat"];
  	 
	if(isset($re["groupHome"]))
		$grouptheme = $re["groupTheme"];

	if(isset($re['userChoice']))
		$userChoice = $re['userChoice'];

	if ($prefs['userTracker'] == 'y') {
		if (isset($re["usersTrackerId"]) and $re["usersTrackerId"]) {
			include_once('lib/trackers/trackerlib.php');
			$userstrackerid = $re["usersTrackerId"];
			$smarty->assign('userstrackerid',$userstrackerid);
			$usersFields = $trklib->list_tracker_fields($userstrackerid, 0, -1, 'position_asc', '');
			$smarty->assign_by_ref('usersFields', $usersFields['data']);
			if (isset($re["usersFieldId"]) and $re["usersFieldId"]) {
				$usersfieldid = $re["usersFieldId"];
				$smarty->assign('usersfieldid',$usersfieldid);
			}
		}
		if (isset($re['registrationUsersFieldIds']))
			$smarty->assign('registrationUsersFieldIds', $re['registrationUsersFieldIds']);
	}

	if ($prefs['groupTracker'] == 'y') {	
		$groupFields = array();
		if (isset($re["groupTrackerId"]) and $re["groupTrackerId"]) {
			include_once('lib/trackers/trackerlib.php');
			$grouptrackerid = $re["groupTrackerId"];
			$smarty->assign('grouptrackerid',$grouptrackerid);
			$groupFields = $trklib->list_tracker_fields($grouptrackerid, 0, -1, 'position_asc', '');
			$smarty->assign_by_ref('groupFields', $groupFields['data']);
			if (isset($re["groupFieldId"]) and $re["groupFieldId"]) {
				$groupfieldid = $re["groupFieldId"];
				$smarty->assign('groupfieldid',$groupfieldid);
				$groupitemid = $trklib->get_item_id($grouptrackerid,$groupfieldid,$groupname);
				$smarty->assign('groupitemid',$groupitemid);
			}
		}
	}

	$groupperms = $re["perms"];
	
	//$allgroups = $userlib->list_all_groups();
	$allgroups = $userlib->list_can_include_groups($re["groupName"]);
	$rs = $userlib->get_included_groups($_REQUEST['group'], false);

	foreach ($allgroups as $rr) {
		$inc["$rr"] = "n";
		if (in_array($rr, $rs)) {
			$inc["$rr"] = "y";
			$smarty->assign('hasOneIncludedGroup', "y");
		}
	}
	$memberslist = $userlib->get_group_users($_REQUEST['group']);
	$cookietab = "2";
} else {
	$allgroups = $userlib->list_all_groups();
	foreach ($allgroups as $rr) {
		$inc["$rr"] = "n";
	}
	$cookietab = "1";
	$_REQUEST["group"] = 0;
}
if (isset($_REQUEST['add'])) {
	$cookietab = "2";
}

if (!empty($_REQUEST['group']) && isset($_REQUEST['export'])) {
	$users = $userlib->get_users(0,-1,'login_asc', '', '', false, $_REQUEST['group']);
	$smarty->assign_by_ref('users', $users['data']);
	$listfields = array();
	if (isset($_REQUEST['username'])) {
		$listfields[] = 'user';
	}
	if (isset($_REQUEST['email'])) {
		$listfields[] = 'email';
	}
	$smarty->assign_by_ref('listfields', $listfields);
	$data = $smarty->fetch('tiki-export_users.tpl');
	if (!empty($_REQUEST['encoding']) && $_REQUEST['encoding'] == 'ISO-8859-1') {
		$data = utf8_decode($data);
	} else {
		$_REQUEST['encoding'] = "UTF-8";
	}
	header("Content-type: text/comma-separated-values; charset:".$_REQUEST['encoding']);
	header("Content-Disposition: attachment; filename=".tra('users')."_".$_REQUEST['group'].".csv");
	header("Expires: 0");
	header("Cache-Control: must-revalidate, post-check=0,pre-check=0");
	header("Pragma: public");
	echo $data;
	die;
}

if (!empty($_REQUEST['group']) && isset($_REQUEST['import'])) {
	$fname = $_FILES['csvlist']['tmp_name'];
	$fhandle = fopen($fname, 'r');
	$fields = fgetcsv($fhandle, 1000);
	if (!$fields[0]) {
		$smarty->assign('msg', tra('The file is not a CSV file or has not a correct syntax'));
		$smarty->display('error.tpl');
		die;
	}
	if ($fields[0]!='user') {
		$smarty->assign('msg', tra('The file does not have the required header:').' user');
		$smarty->display('error.tpl');
		die;	
	}
	$data = @fgetcsv($fhandle, 1000);	
	while (!feof($fhandle)) {
		if (function_exists("mb_detect_encoding") && mb_detect_encoding($data[0], "ASCII, UTF-8, ISO-8859-1") ==  "ISO-8859-1") {
				$data[0] = utf8_encode($data[0]);
			}
		if (!$userlib->user_exists($data[0])) {
			$errors[] = tra("User doesn't exist").': '.$data[0];
		} else {
			$userlib->assign_user_to_group($data[0], $_REQUEST['group']);
		}
		$data = fgetcsv($fhandle, 1000);
	}
	if (!empty($errors)) {
		$smarty->assign_by_ref('errors', $errors);
	}
	$cookietab = 4;
}

if ($prefs['feature_categories'] == 'y') {
	global $categlib; include_once ('lib/categories/categlib.php');
	$categories = $categlib->get_all_categories_respect_perms($user, 'tiki_p_view_categories');
	$smarty->assign_by_ref('categories', $categories);
}
  	 
$av_themes = $tikilib->list_styles();
$smarty->assign_by_ref('av_themes', $av_themes);

$smarty->assign('memberslist',$memberslist);

$smarty->assign('inc', $inc);
$smarty->assign('group', $_REQUEST["group"]);
$smarty->assign('groupname', $groupname);
$smarty->assign('groupdesc', $groupdesc);
$smarty->assign('grouphome',$grouphome);
$smarty->assign('groupdefcat', $groupdefcat);
$smarty->assign('grouptheme', $grouptheme);
$smarty->assign('groupperms', $groupperms);
$smarty->assign_by_ref('userChoice', $userChoice);

$cant_pages = ceil($users["cant"] / $numrows);
$smarty->assign_by_ref('cant_pages', $cant_pages);
$smarty->assign('actual_page', 1 + ($offset / $numrows));

if ($users["cant"] > ($offset + $numrows)) {
	$smarty->assign('next_offset', $offset + $numrows);
} else {
	$smarty->assign('next_offset', -1);
}
if ($offset > 0) {
	$smarty->assign('prev_offset', $offset - $numrows);
} else {
	$smarty->assign('prev_offset', -1);
}

setcookie('tab',$cookietab);
$smarty->assign('cookietab',$cookietab);

ask_ticket('admin-groups');

$smarty->assign('uses_tabs', 'y');

// Assign the list of groups
$smarty->assign_by_ref('users', $users["data"]);

// disallow robots to index page:
$smarty->assign('metatag_robots', 'NOINDEX, NOFOLLOW');

// Display the template for group administration
$smarty->assign('mid', 'tiki-admingroups.tpl');
$smarty->display("tiki.tpl");

?>
