<?php
// (c) Copyright 2002-2011 by authors of the Tiki Wiki CMS Groupware Project
// 
// All Rights Reserved. See copyright.txt for details and a complete list of authors.
// Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See license.txt for details.
// $Id$

function prefs_search_list() {
	global $prefs;
	return array (
		'search_parsed_snippet' => array(
			'name' => tra('Parse the results'),
			'hint' => tra('May impact performance'),
			'type' => 'flag',
		),
		'search_default_where' => array(
			'name' => tra('Default where'),
			'description' => tra('When object filter is not on, limit to search one type of object'),
			'type' => 'list',
			'options' => $prefs['feature_search_fulltext'] === 'y' ?
					array(
						'' => tra('Entire site'),
						'wikis' => tra('Wiki Pages'),
						'trackers' => tra('Trackers'),
					) : array(
						'' => tra('Entire site'),
						'wiki page' => tra('Wiki Pages'),
						'blog post' => tra('Blog Posts'),
						'article' => tra('Articles'),
						'file' => tra('Files'),
						'forum post' => tra('Forums'),
						'trackeritem' => tra('Tracker Items'),
						'sheet' => tra('Spreadsheets'),
					),
		),
		'search_default_interface_language' => array(
			'name' => tra('Restrict search language by default'),
			'description' => tra('If enabled, only search content in the interface language, by default.'),
			'type' => 'flag',
		),
		'search_autocomplete' => array(
			'name' => tra('Autocomplete on page names'),
			'type' => 'flag',
			'dependencies' => array('feature_jquery_autocomplete', 'javascript_enabled'),
		),
	);
}
