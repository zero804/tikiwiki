<?php
// (c) Copyright 2002-2016 by authors of the Tiki Wiki CMS Groupware Project
//
// All Rights Reserved. See copyright.txt for details and a complete list of authors.
// Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See license.txt for details.
// $Id$

function prefs_h5p_list()
{
	return [
		'h5p_enabled' => [
			'name' => tra('H5P support'),
			'description' => tra('Handle H5P package files on upload.'),
			'dependencies' => [
				'feature_file_galleries',
			],
			'type' => 'flag',
			'default' => 'n',
			'filter' => 'alpha',
			'hint' => tr('Enable H5P content'),
		],
		'h5p_whitelist' => [
			'name' => tr('Whitelist'),
			'description' => tr('Allowed filetypes'),
			'dependencies' => [
				'h5p_enabled',
			],
			'type' => 'text',
			'filter' => 'text',
			'default' => H5PCore::$defaultContentWhitelist,
		],
		'h5p_track_user' => [
			'name' => tra('H5P Tracker User'),
			'description' => tra('TODO'),
			'dependencies' => [
				'h5p_enabled',
			],
			'type' => 'flag',
			'filter' => 'alpha',
			'default' => 'n',
		],
		'h5p_dev_mode' => [
			'name' => tra('H5P Developer Mode'),
			'description' => tra('Use "patched" libraries?'),
			'dependencies' => [
				'h5p_enabled',
			],
			'type' => 'flag',
			'filter' => 'alpha',
			'default' => 'n',
		],
		'h5p_filegal_id' => [
			'name' => tr('Default Gallery'),
			'description' => tr('File gallery to create new H5P content in by default.'),
			'dependencies' => [
				'h5p_enabled',
			],
			'type' => 'text',
			'filter' => 'int',
			'profile_reference' => 'file_gallery',
			'default' => 1,
		],
	];
}

