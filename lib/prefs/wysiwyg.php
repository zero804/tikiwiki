<?php
// (c) Copyright 2002-2010 by authors of the Tiki Wiki/CMS/Groupware Project
// 
// All Rights Reserved. See copyright.txt for details and a complete list of authors.
// Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See license.txt for details.
// $Id$

function prefs_wysiwyg_list() {
	return array(
		'wysiwyg_optional' => array(
			'name' => tra('Wysiwyg Editor is optional'),
			'type' => 'flag',
			'dependencies' => array(
				'feature_wysiwyg',
			),
		),
		'wysiwyg_default' => array(
			'name' => tra('... and is displayed by default'),
			'type' => 'flag',
			'dependencies' => array(
				'feature_optional',
			),
		),
		'wysiwyg_memo' => array(
			'name' => tra('Reopen with the same editor'),
			'type' => 'flag',
		),
		'wysiwyg_wiki_parsed' => array(
			'name' => tra('Content is parsed like wiki page'),
			'type' => 'flag',
		),
		'wysiwyg_wiki_semi_parsed' => array(
			'name' => tra('Content is partially parsed'),
			'type' => 'flag',
		),
		'wysiwyg_toolbar_skin' => array(
			'name' => tra('Toolbar skin'),
			'type' => 'list',
			'options' => array(
				'default' => tra('Default'),
				'office2003' => tra('Office 2003'),
				'silver' => tra('Silver'),
			),
		),
		'wysiwyg_ckeditor' => array(
			'name' => tra('Use CKEditor'),
			'description' => tra('Experimental, new in Tiki 5: Use New CKEditor instead of previous FCKEditor'),
			'type' => 'flag',
		),
	);
}
