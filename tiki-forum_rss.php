<?php
// (c) Copyright 2002-2010 by authors of the Tiki Wiki/CMS/Groupware Project
// 
// All Rights Reserved. See copyright.txt for details and a complete list of authors.
// Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See license.txt for details.
// $Id$

require_once('tiki-setup.php');
require_once('lib/tikilib.php');
require_once ('lib/rss/rsslib.php');

if ($prefs['feed_forum'] != 'y') {
        $errmsg=tra("rss feed disabled");
        require_once ('tiki-rss_error.php');
}

if(!isset($_REQUEST["forumId"])) {
        $errmsg=tra("No forumId specified");
        require_once ('tiki-rss_error.php');
}

$tikilib->get_perm_object( $_REQUEST['forumId'], 'forum' );

if ($tiki_p_forum_read != 'y') {
	$smarty->assign('errortype', 401);
	$errmsg=tra("Permission denied. You cannot view this section");
	require_once ('tiki-rss_error.php');
}

require_once('lib/commentslib.php');
if (!isset($commentslib)) {
	$commentslib = new Comments($dbTiki);
}

$feed = "forum";
$id = "forumId";
$uniqueid = "$feed.$id=".$_REQUEST["$id"];
$output = $rsslib->get_from_cache($uniqueid);

if ($output["data"]=="EMPTY") {
	$tmp = $commentslib->get_forum($_REQUEST["forumId"]);
	$title = tra("Tiki RSS feed for forum: ").$tmp["name"];
	$desc = $tmp["description"];
	$param = "threadId";
	$descId = "data";
	$dateId = "commentDate";
	$authorId = "userName";
	$titleId = "title";
	$readrepl = "tiki-view_forum_thread.php?$id=%s&comments_parentId=%s";

        $tmp = $prefs['feed_'.$feed.'_title'];
        if ($tmp<>'') $title = $tmp;
        $tmp = $prefs['feed_'.$feed.'_desc'];
        if ($desc<>'') $desc = $tmp;

	$changes = $tikilib->list_forum_topics($_REQUEST["$id"],0, $prefs['feed_forum_max'], $dateId.'_desc', '');
	$output = $rsslib->generate_feed($feed, $uniqueid, '', $changes, $readrepl, $param, $id, $title, $titleId, $desc, $descId, $dateId, $authorId);
}
header("Content-type: ".$output["content-type"]);
print $output["data"];
