<?php
// (c) Copyright 2002-2010 by authors of the Tiki Wiki/CMS/Groupware Project
// 
// All Rights Reserved. See copyright.txt for details and a complete list of authors.
// Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See license.txt for details.
// $Id$

// Translate only if feature_multilingual is on

//this script may only be included - so its better to die if called directly.
if (strpos($_SERVER["SCRIPT_NAME"],basename(__FILE__)) !== false) {
	header("location: index.php");
	exit;
}

function smarty_modifier_sefurl($source, $type='wiki', $with_next = '', $all_langs='' ) {
	global $prefs, $tikilib, $wikilib, $smarty;
	include_once('lib/wiki/wikilib.php');

	switch($type){
	case 'wiki page':
		$type = 'wiki';
	case 'wiki':
		return $wikilib->sefurl($source, $with_next, $all_langs);
	case 'blog':
		$href = 'tiki-view_blog.php?blogId='.$source;
		break;
	case 'blogpost':
		$href = 'tiki-view_blog_post.php?postId='.$source;
		break;
	case 'gallery':
		$href = 'tiki-browse_gallery.php?galleryId='. $source;
		break;
	case 'videogallery':
		$href = 'tiki-browse_video_gallery.php?galleryId='. $source;
		break;
	case 'article':
		$href = 'tiki-read_article.php?articleId='. $source;
		break;
	case 'file':
		$href = 'tiki-download_file.php?fileId='. $source;
		break;
	case 'thumbnail':
		$href = 'tiki-download_file.php?fileId='. $source.'&amp;thumbnail';
		break;
	case 'display':
		$href = 'tiki-download_file.php?fileId='. $source.'&amp;display';
		break;
	case 'preview':
		$href = 'tiki-download_file.php?fileId='. $source.'&amp;preview';
		break;
	case 'tracker item':
		$type = 'trackeritem';
	case 'trackeritem':
		$replacementpage = '';
		if ($prefs["feature_sefurl_tracker_prefixalias"] == 'y') {
			$replacementpage = $tikilib->get_trackeritem_pagealias($source);
		}
		if ($replacementpage) {
			return $wikilib->sefurl($replacementpage, $with_next, $all_langs);
		} else {
			$href = 'tiki-view_tracker_item.php?itemId='. $source;
		}
		break;
	case 'tracker':
		$href = 'tiki-view_tracker.php?trackerId='.$source;
		break;
	case 'filegallery':
	case 'file gallery':
		$href = 'tiki-list_file_gallery.php?galleryId='.$source;
		break;
	case 'image':
		$href = 'tiki-browse_image.php?imageId='.$source;
		break;
	default:
		$href = $source;
		break;
	}
	if ($with_next) {
		$href .= '&amp;';
	}
	if ($prefs['feature_sefurl'] == 'y') {
		include_once('tiki-sefurl.php');
		return filter_out_sefurl($href, $smarty, $type, '', $with_next);
	} else {
		return $href;
	}
}
