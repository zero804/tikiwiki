<?php
// (c) Copyright 2002-2013 by authors of the Tiki Wiki CMS Groupware Project
// 
// All Rights Reserved. See copyright.txt for details and a complete list of authors.
// Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See license.txt for details.
// $Id$

function wikiplugin_content_info()
{
	return array(
		'name' => tra('Dynamic Content'),
		'documentation' => 'PluginContent',
		'description' => tra('Display content from dynamic content repository'),
		'prefs' => array( 'feature_dynamic_content', 'wikiplugin_content'),
		'filter' => 'text',
		'icon' => 'img/icons/database_table.png',
		'tags' => array( 'basic' ),		
		'params' => array(
			'id' => array(
				'required' => false,
				'name' => tra('Content ID'),
				'description' => tra('Dynamic content ID. The value can be obtained in the listing.'),
				'filter' => 'digits',
				'default' => '',
			),
			'label' => array(
				'required' => false,
				'name' => tra('Content Label'),
				'description' => tra('Label of the dynamic content to display.'),
				'filter' => 'description',
				'default' => '',
			),
		),
	);
}

function wikiplugin_content( $data, $params )
{

	global $dcslib; require_once 'lib/dcs/dcslib.php';

	$lang = null;
	if ( isset( TikiLib::lib('parser')->option['language'] ) ) {
		$lang = TikiLib::lib('parser')->option['language'];
	}

	if ( isset($params['id']) &&  $params['id'] ) {
		return $dcslib->get_actual_content((int) $params['id'], $lang);
	} elseif ( isset($params['label']) && $params['label'] ) {
		return $dcslib->get_actual_content_by_label($params['label'], $lang);
	}
}
