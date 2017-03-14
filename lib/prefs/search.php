<?php
// (c) Copyright 2002-2016 by authors of the Tiki Wiki CMS Groupware Project
// 
// All Rights Reserved. See copyright.txt for details and a complete list of authors.
// Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See license.txt for details.
// $Id$

function prefs_search_list()
{
	global $prefs;
	return array (
		'search_parsed_snippet' => array(
			'name' => tra('Parse the results'),
			'hint' => tra('May impact performance'),
			'type' => 'flag',
			'default' => 'y',
		),
		'search_default_where' => array(
			'name' => tra('Default where'),
			'description' => tra('When object filter is not on, limit to search one type of object'),
			'type' => 'multicheckbox',
			'options' => isset($prefs['feature_search_fulltext']) && $prefs['feature_search_fulltext'] === 'y' ?
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
			'default' => array(),
		),
		'search_default_interface_language' => array(
			'name' => tra('Restrict search language by default'),
			'description' => tra('If enabled, only search content that is in the interface language, otherwise show the language menu.'),
			'type' => 'flag',
			'default' => 'n',
		),
		'search_autocomplete' => array(
			'name' => tra('Autocomplete page names'),
			'type' => 'flag',
			'dependencies' => array('feature_jquery_autocomplete', 'javascript_enabled'),
			'warning' => tra('deprecated'),
			'default' => 'n',
		),
		'search_show_category_filter' => array(
			'name' => tra('Category filter'),
			'type' => 'flag',
			'default' => 'n',
			'dependencies' => array(
				'feature_categories',
			),
			'tags' => array('basic'),
		),
		'search_show_tag_filter' => array(
			'name' => tra('Tag filter'),
			'type' => 'flag',
			'default' => 'n',
			'dependencies' => array(
				'feature_freetags',
			),
			'tags' => array('basic'),
		),
		'search_show_sort_order' => array(
			'name' => tra('Sort order'),
			'type' => 'flag',
			'default' => 'n',
			'tags' => array('basic'),
		),
		'search_use_facets' => array(
			'name' => tra('Use facets for default search interface'),
			'description' => tra('Facets are dynamic filters generated by the search engine to refine the search results. The feature may not be supported for all search engines.'),
			'type' => 'flag',
			'default' => 'n',
		),
		'search_facet_default_amount' => array(
			'name' => tra('Facet result count'),
			'description' => tra('DDefault number of facet results to obtain.'),
			'type' => 'text',
			'size' => 8,
			'filter' => 'digits',
			'default' => '10',
		),
		'search_error_missing_field' => array(
			'name' => tra('Show error on missing field'),
			'description' => tra('When using LIST plugin to specify certain fields, especially tracker fields, this check helps ensure their names were entered correctly.'),
			'type' => 'flag',
			'default' => 'y',
		),
	);
}
