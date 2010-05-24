<?php
// (c) Copyright 2002-2010 by authors of the Tiki Wiki CMS Groupware Project
// 
// All Rights Reserved. See copyright.txt for details and a complete list of authors.
// Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See license.txt for details.
// $Id$

//this script may only be included - so its better to die if called directly.
if (strpos($_SERVER["SCRIPT_NAME"],basename(__FILE__)) !== false) {
  header("location: index.php");
  exit;
}

function module_forums_last_posts_info() {
	return array(
		'name' => tra('Last forum posts'),
		'description' => tra('Displays the latest forum posts.'),
		'prefs' => array( 'feature_forums' ),
		'params' => array(
			'topics' => array(
				'name' => tra('Topics only'),
				'description' => tra('If set to "y", only displays topics.') . " " . tr('Not set by default.')
							  ),
			'forumId' => array(
				'name' => tra('List of forum IDs'),
				'description' => tra('Post only from this forum'),
				'separator' => ':'
			)
		),
		'common_params' => array('nonums', 'rows')
	);
}

function module_forums_last_posts( $mod_reference, $module_params ) {
	global $smarty;
	global $ranklib; include_once ('lib/rankings/ranklib.php');
	$default = array('forumId'=>'', 'topics' => false);
	$module_params = array_merge($default, $module_params);
	if (!empty($module_params['forumId'])) {
		$module_params['forumId'] = explode(':', $module_params['forumId']);
	}
	$ranking = $ranklib->forums_ranking_last_posts($mod_reference['rows'], $module_params['topics'], $module_params['forumId']);
	
	$replyprefix = tra("Re:");
	
	$smarty->assign('modForumsLastPosts', $ranking["data"]);
}
