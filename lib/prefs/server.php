<?php
// (c) Copyright 2002-2010 by authors of the Tiki Wiki/CMS/Groupware Project
// 
// All Rights Reserved. See copyright.txt for details and a complete list of authors.
// Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See license.txt for details.
// $Id$

function prefs_server_list() {
	global $prefs, $tikilib;
	
	// Skipping the getTimeZoneList() from tikidate which just emulates the pear date format
	// Generating it is extremely costly in terms of memory.
	if( class_exists( 'DateTimeZone' ) ) {
		$timezones = DateTimeZone::listIdentifiers();
	} elseif ( class_exists('DateTime')) {
		$timezones = array_keys( DateTime::getTimeZoneList() );
	} else {
		$timezones = TikiDate::getTimeZoneList();
		$timezones = array_keys($timezones);
	}

	sort( $timezones );

	if ($prefs['server_timezone'] == 'GMT' && !in_array('GMT', $timezones) && in_array('UTC', $timezones)) {
		$tikilib->set_preference( 'server_timezone', 'UTC' );
	}
	
	return array(
		'server_timezone' => array(
			'name' => tra('Timezone'),
			'description' => tra('Indicates the default time zone to use for the server.'),
			'type' => 'list',
			'options' => array_combine( $timezones, $timezones ),
		),
	);
}

