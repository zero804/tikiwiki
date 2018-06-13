<?php
// (c) Copyright 2002-2016 by authors of the Tiki Wiki CMS Groupware Project
//
// All Rights Reserved. See copyright.txt for details and a complete list of authors.
// Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See license.txt for details.
// $Id$

function prefs_mobile_list()
{

	return [

		'mobile_feature' => [
			'name' => tra('Mobile access'),
			'description' => tra('Allow automatic switching of the perspective according to mobile_perspectives preference (behavior since Tiki 14).'),
			'help' => 'Mobile',
			'warning' => tra('Deprecated. This feature is no longer under development following the integration of the Bootstrap CSS framework.'),
			'type' => 'flag',
			'tags' => ['experimental'],
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
