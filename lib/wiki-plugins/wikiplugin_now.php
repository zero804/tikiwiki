<?php
// (c) Copyright 2002-2016 by authors of the Tiki Wiki CMS Groupware Project
//
// All Rights Reserved. See copyright.txt for details and a complete list of authors.
// Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See license.txt for details.
// $Id$

function wikiplugin_now_info()
{
	return [
		'name' => tra('Now'),
		'documentation' => 'PluginNow',
		'description' => tra('Show current date and time'),
		'prefs' => ['wikiplugin_now'],
		'body' => tra('text'),
		'iconname' => 'history',
		'introduced' => 9,
		'tags' => [ 'basic' ],
		'params' => [
			'format' => [
				'required' => false,
				'name' => tra('Format'),
				'description' => tr(
					'Time format using the PHP format described here: %0',
					'http://www.php.net/manual/en/function.strftime.php'
				),
				'since' => '9.0',
				'default' => tr('Based site long date and time setting'),
				'filter' => 'text',
			],
			'when' => [
				'required' => false,
				'name' => tra('Date to display'),
				'description' => tr(
					'Date time as specified in text using strtotime, i.e. "next month" - documentation here: %0',
					'https://secure.php.net/manual/en/function.strtotime.php'
				),
				'since' => '18.2',
				'default' => tr(''),
				'filter' => 'text',
			],
		],
	];
}

function wikiplugin_now($data, $params)
{
	global $prefs;
	$when = ! empty($params['when']) ? $params['when'] : false;
	$default = TikiLib::date_format($prefs['long_date_format'] . ' ' . $prefs['long_time_format'], $when);
	if (! empty($params['format'])) {
		$ret = TikiLib::date_format($params['format'], $when);
		//see if the user format setting results in a valid date, return default format if not
		try {
			$dateObj = new DateTime($ret);
		} catch (Exception $e) {
			return $default;
		}
		return $ret;
	} else {
		return $default;
	}
}
