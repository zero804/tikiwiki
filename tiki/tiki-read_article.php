<?php

// $Header: /cvsroot/tikiwiki/tiki/tiki-read_article.php,v 1.13 2003-08-07 04:33:57 rossta Exp $

// Copyright (c) 2002-2003, Luis Argerich, Garland Foster, Eduardo Polidor, et. al.
// All Rights Reserved. See copyright.txt for details and a complete list of authors.
// Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See license.txt for details.

// Initialization
require_once ('tiki-setup.php');

include_once ('lib/articles/artlib.php');

if ($feature_articles != 'y') {
	$smarty->assign('msg', tra("This feature is disabled"));

	$smarty->display("styles/$style_base/error.tpl");
	die;
}

if ($tiki_p_read_article != 'y') {
	$smarty->assign('msg', tra("Permission denied you cannot view this section"));

	$smarty->display("styles/$style_base/error.tpl");
	die;
}

if (!isset($_REQUEST["articleId"])) {
	$smarty->assign('msg', tra("No article indicated"));

	$smarty->display("styles/$style_base/error.tpl");
	die;
}

if (isset($_REQUEST["articleId"])) {
	$artlib->add_article_hit($_REQUEST["articleId"]);

	$smarty->assign('articleId', $_REQUEST["articleId"]);
	$article_data = $tikilib->get_article($_REQUEST["articleId"]);

	if (!$article_data) {
		$smarty->assign('msg', tra("Article not found"));

		$smarty->display("styles/$style_base/error.tpl");
		die;
	}

	if ($userlib->object_has_one_permission($article_data["topicId"], 'topic')) {
		if (!$userlib->object_has_permission($user, $article_data["topicId"], 'topic', 'tiki_p_topic_read')) {
			$smarty->assign('msg', tra("Permision denied"));

			$smarty->display("styles/$style_base/error.tpl");
			die;
		}
	}

	if (($article_data["publishDate"] > date("U")) && ($tiki_p_admin != 'y')) {
		$smarty->assign('msg', tra("Article is not published yet"));

		$smarty->display("styles/$style_base/error.tpl");
		die;
	}

	$smarty->assign('title', $article_data["title"]);
	$smarty->assign('authorName', $article_data["authorName"]);
	$smarty->assign('topicId', $article_data["topicId"]);
	$smarty->assign('type', $article_data["type"]);
	$smarty->assign('rating', $article_data["rating"]);
	$smarty->assign('entrating', $article_data["entrating"]);
	$smarty->assign('useImage', $article_data["useImage"]);
	$smarty->assign('isfloat', $article_data["isfloat"]);
	$smarty->assign('image_name', $article_data["image_name"]);
	$smarty->assign('image_type', $article_data["image_type"]);
	$smarty->assign('image_size', $article_data["image_size"]);
	$smarty->assign('image_x', $article_data["image_x"]);
	$smarty->assign('image_y', $article_data["image_y"]);
	$smarty->assign('image_data', urlencode($article_data["image_data"]));
	$smarty->assign('reads', $article_data["reads"]);
	$smarty->assign('size', $article_data["size"]);

	if (strlen($article_data["image_data"]) > 0) {
		$smarty->assign('hasImage', 'y');

		$hasImage = 'y';
	}

	$smarty->assign('heading', $article_data["heading"]);

	if (!isset($_REQUEST['page']))
		$_REQUEST['page'] = 1;

	$pages = $artlib->get_number_of_pages($article_data["body"]);
	$article_data["body"] = $artlib->get_page($article_data["body"], $_REQUEST['page']);
	$smarty->assign('pages', $pages);

	if ($pages > $_REQUEST['page']) {
		$smarty->assign('next_page', $_REQUEST['page'] + 1);
	} else {
		$smarty->assign('next_page', $_REQUEST['page']);
	}

	if ($_REQUEST['page'] > 1) {
		$smarty->assign('prev_page', $_REQUEST['page'] - 1);
	} else {
		$smarty->assign('prev_page', 1);
	}

	$smarty->assign('first_page', 1);
	$smarty->assign('last_page', $pages);
	$smarty->assign('page', $_REQUEST['page']);

	$smarty->assign('body', $article_data["body"]);
	$smarty->assign('publishDate', $article_data["publishDate"]);
	$smarty->assign('edit_data', 'y');

	$body = $article_data["body"];
	$heading = $article_data["heading"];
	$smarty->assign('parsed_body', $tikilib->parse_data($body));
	$smarty->assign('parsed_heading', $tikilib->parse_data($heading));
}

if ($feature_article_comments == 'y') {
	$comments_per_page = $article_comments_per_page;

	$comments_default_ordering = $article_comments_default_ordering;
	$comments_vars = array('articleId');
	$comments_prefix_var = 'article';
	$comments_object_var = 'articleId';
	include_once ("comments.php");
}

$section = 'cms';
include_once ('tiki-section_options.php');

if ($feature_theme_control == 'y') {
	$cat_type = 'article';

	$cat_objid = $_REQUEST["articleId"];
	include ('tiki-tc.php');
}

// Display the Index Template
$smarty->assign('mid', 'tiki-read_article.tpl');
$smarty->assign('show_page_bar', 'n');
$smarty->display("styles/$style_base/tiki.tpl");

?>