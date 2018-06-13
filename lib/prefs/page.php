<?php
// (c) Copyright 2002-2016 by authors of the Tiki Wiki CMS Groupware Project
//
// All Rights Reserved. See copyright.txt for details and a complete list of authors.
// Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See license.txt for details.
// $Id$

function prefs_page_list()
{
	return [
		'page_bar_position' => [
			'name' => tra('Wiki buttons'),
			'description' => tra('The location of the row of Edit, Source, Rename, History, More buttons, etc.'),
			'type' => 'list',
			'options' => [
				'top' => tra('Top '),
				'bottom' => tra('Bottom'),
				'none' => tra('Neither'),
			],
			'default' => 'bottom',
		],
		'page_n_times_in_a_structure' => [
			'name' => tra('Pages can reoccur in structure'),
			'description' => tra('A page can be listed multiple times in a structure.'),
			'type' => 'flag',
			'default' => 'n',
		],
		'page_content_fetch' => [
			'name' => tra('Fetch page content from incoming feeds'),
			'description' => tra('Page content from the source will be fetched before sending the content to the generators'),
			'dependencies' => ['page_content_fetch_readability'],
			'type' => 'flag',
			'default' => 'n',
		],
		'page_content_fetch_readability' => [
			'name' => tra('Path to PHP-Readability library'),
			'description' => tra('Enter path to PHP-Readability library php file here.'),
			'type' => 'text',
			'size' => 20,
			'detail' => tr('Not included with Tiki due to licensing reasons.'),
			'default' => '',
		],
	];
}
