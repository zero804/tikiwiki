<?php
// (c) Copyright by authors of the Tiki Wiki CMS Groupware Project
//
// All Rights Reserved. See copyright.txt for details and a complete list of authors.
// Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See license.txt for details.
// $Id$

function prefs_mobile_list()
{

	return [

		'mobile_feature' => [
			'name' => tra('Mobile access'),
			'description' => tra('Mobile feature pre-Tiki 13; as of Tiki 14 all it does is to allow automatic switching of the perspective according to the mobile_perspectives preference.'),
			'help' => 'Mobile',
			'warning' => tra('This feature will be removed after Tiki18 and before Tiki19 (It is no longer under development following the integration of the Bootstrap CSS framework)'),
			'type' => 'flag',
			'tags' => ['deprecated'],
			'dependencies' => [
				'feature_perspective',
			],
			'default' => 'n',
		],
		'mobile_perspectives' => [
			'name' => tra('Mobile Perspectives'),
			'help' => 'Mobile',
			'type' => 'text',
			'separator' => ',',
			'filter' => 'int',
			'tags' => ['experimental'],
			'dependencies' => [
				'mobile_feature',
			],
			'default' => [],
			'profile_reference' => 'perspective',
		],
	];
}
