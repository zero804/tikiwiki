<?php
// $Header: /cvsroot/tikiwiki/tiki/tiki-forums_rss.php,v 1.19 2005-01-22 22:54:54 mose Exp $

// Copyright (c) 2002-2005, Luis Argerich, Garland Foster, Eduardo Polidor, et. al.
// All Rights Reserved. See copyright.txt for details and a complete list of authors.
// Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See license.txt for details.

require_once ('tiki-setup.php');
require_once ('lib/tikilib.php');

if ($rss_forums != 'y') {
        $errmsg=tra("rss feed disabled");
        require_once ('tiki-rss_error.php');
}

if($tiki_p_admin_forum != 'y' && $tiki_p_forum_read != 'y') {
        $errmsg=tra("Permission denied you cannot view this section");
        require_once ('tiki-rss_error.php');
}

$feed = "forums";
$title = (!empty($title_rss_forums)) ? $title_rss_forums :  tra("Tiki RSS feed for forums");
$desc = (!empty($desc_rss_forums)) ? $desc_rss_forums : tra("Last topics in forums.");
$now = date("U");
$id = "forumId";
$descId = "data";
$dateId = "commentDate";
$titleId = "title";
$readrepl = "tiki-view_forum_thread.php?$id=";
$uniqueid = $feed;

require ("tiki-rss_readcache.php");

if ($output == "EMPTY") {
  $changes = $tikilib -> list_all_forum_topics(0, $max_rss_forums, $dateId.'_desc', '');
  $output = "";
}

require ("tiki-rss.php");

?>
