<?php

// $Header: /cvsroot/tikiwiki/tiki/tiki-rollback.php,v 1.7 2003-10-08 03:53:08 dheltzel Exp $

// Copyright (c) 2002-2003, Luis Argerich, Garland Foster, Eduardo Polidor, et. al.
// All Rights Reserved. See copyright.txt for details and a complete list of authors.
// Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See license.txt for details.

// Initialization
require_once ('tiki-setup.php');

include_once ('lib/wiki/histlib.php');

if ($feature_wiki != 'y') {
	$smarty->assign('msg', tra("This feature is disabled").": feature_wiki");

	$smarty->display("styles/$style_base/error.tpl");
	die;
}

// Get the page from the request var or default it to HomePage
if (!isset($_REQUEST["page"])) {
	$smarty->assign('msg', tra("No page indicated"));

	$smarty->display("styles/$style_base/error.tpl");
	die;
} else {
	$page = $_REQUEST["page"];

	$smarty->assign_by_ref('page', $_REQUEST["page"]);
}

if (!isset($_REQUEST["version"])) {
	$smarty->assign('msg', tra("No version indicated"));

	$smarty->display("styles/$style_base/error.tpl");
	die;
} else {
	$version = $_REQUEST["version"];

	$smarty->assign_by_ref('version', $_REQUEST["version"]);
}

if (!$histlib->version_exists($page, $version)) {
	$smarty->assign('msg', tra("Unexistant version"));

	$smarty->display("styles/$style_base/error.tpl");
	die;
}

include_once ("tiki-pagesetup.php");

// Now check permissions to access this page
if ($tiki_p_rollback != 'y') {
	$smarty->assign('msg', tra("Permission denied you cannot rollback this page"));

	$smarty->display("styles/$style_base/error.tpl");
	die;
}

$version = $histlib->get_version($page, $version);
$version["data"] = $tikilib->parse_data($version["data"]);
$smarty->assign_by_ref('preview', $version);

// If the page doesn't exist then display an error
if (!$tikilib->page_exists($page)) {
	$smarty->assign('msg', tra("Page cannot be found"));

	$smarty->display("styles/$style_base/error.tpl");
	die;
}

if (isset($_REQUEST["rollback"])) {
	$histlib->use_version($_REQUEST["page"], $_REQUEST["version"]);

	header ("location: tiki-index.php");
	die;
}

$smarty->assign('mid', 'tiki-rollback.tpl');
$smarty->assign('show_page_bar', 'y');
$smarty->display("styles/$style_base/tiki.tpl");

?>
