<?php
// (c) Copyright 2002-2010 by authors of the Tiki Wiki/CMS/Groupware Project
// 
// All Rights Reserved. See copyright.txt for details and a complete list of authors.
// Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See license.txt for details.
// $Id$

function prefs_fgal_list() {
	return array(
		'fgal_podcast_dir' => array(
			'name' => tra('Podcast directory'),
			'type' => 'text',
			'help' => 'File+Gallery+Config',
			'size' => 50,
			'hint' => tra('The server must be able to read/write the directory.').' '.tra('Required for podcasts.'),
			'perspective' => false,
		),
		'fgal_use_dir' => array(
			'name' => tra('Path'),
			'type' => 'text',
			'help' => 'File+Gallery',
			'perspective' => false,
		),
		'fgal_batch_dir' => array(
			'name' => tra('Path'),
			'type' => 'text',
			'help' => 'File+Gallery+config',
			'size' => 50,
			'hint' => tra('If you enable Directory Batch Loading, you need to setup a web-readable directory (outside of your web space is better). Then setup a way to upload files in that dir, either by scp, ftp, or other protocols').' '.tra('The server must be able to read the directory.').' '. tra('The directory can be outside the web space.'),
			'perspective' => false,
		),
		'fgal_prevent_negative_score' => array(
			'name' => tra('Prevent download if score becomes negative'),
			'type' => 'text',
			'help' => 'File+Gallery+Config',
			'size' => 50,
		),
		'fgal_limit_hits_per_file' => array(
			'name' => tra('Allow download limit per file'),
			'type' => 'flag',
			'help' => 'File+Gallery+Config',
		),
		'fgal_prevent_negative_score' => array(
			'name' => tra('Prevent download if score becomes negative'),
			'type' => 'flag',
			'help' => 'File+Gallery+Config',
		),
		'fgal_allow_duplicates' => array(
			'name' => tra('Allow same file to be uploaded more than once'),
			'type' => 'list',
			'help' => 'File+Gallery+Config',
			'perspective' => false,
			'options' => array(
							  'n' => tra('Never'),
							  'y' => tra('Yes, even in the same gallery'),
							  'different_galleries' => tra('Only in different galleries')
			),
		),
		'fgal_match_regex' => array(
			'name' => tra('Must match'),
			'type' => 'text',
			'size' => 50,
		),
		'fgal_nmatch_regex' => array(
			'name' => tra('Cannot match'),
			'type' => 'text',
			'size' => 50,
		),
		'fgal_quota' => array (
			'name' => tra('Quota for all the files and archives'),
			'shorthint' => tra('Mb').' '.tra('(0 for unlimited)'),
			'type' => 'text',
			'size' => 7,
		),
		'fgal_quota_per_fgal' => array (
			'name' => tra('Quota can be defined for each file gallery'),
			'type' => 'flag',
		),
		'fgal_quota_default' => array (
			'name' => tra('Default quota for each new gallery'),
			'shorthint' => tra('Mb').' '.tra('(0 for unlimited)'),
			'type' => 'text',
			'size' => 7,
		),
		'fgal_quota_show' => array (
			'name' => tra('Show quota bar in the list page'),
			'type' => 'flag',
		),
		'fgal_use_db' => array(
			'name' => tra('Storage'),
			'type' => 'list',
			'perspective' => false,
			'options' => array(
				'y' => tra('Store in database'),
				'n' => tra('Store in directory'),
			),
		),
		'fgal_use_dir' => array(
			'name' => tra('Path'),
			'type' => 'text',
			'size' => 50,
			'perspective' => false,
		),
		'fgal_search_in_content' => array(
			'name' => tra('Include the search box on the current gallery files just after the find div'),
			'type' => 'flag',
		),
		'fgal_search' => array(
			'name' => tra('Include a search box on file galleries'),
			'type' => 'flag',
		),
		'fgal_delete_after' => array(
			'name' => tra('Automatic deletion of old files'),
			'description' => tra('The user will have an option when uploading a file to specify the time after which the file is deleted'),
			'type' => 'flag',
			'help' => 'File+Gallery+Config',
		),
		'fgal_delete_after_email' => array(
			'name' => tra('Deletion emails notification'),
			'description' => tra('These emails will receive a copy of each deleted file. Emails are separated with comma'),
			'type' => 'text',
		),
		'fgal_keep_fileId' => array(
			'name' => tra('Keep always the same fileId when replacing a file with archive'),
			'description' => tra('Keep always the same fileId when replacing a file with archive'),
			'type' => 'flag',
		),
	);
}
