<?php
// (c) Copyright 2002-2010 by authors of the Tiki Wiki/CMS/Groupware Project
// 
// All Rights Reserved. See copyright.txt for details and a complete list of authors.
// Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See license.txt for details.
// $Id$

function prefs_user_list() {
	return array(
		'user_show_realnames' => array(
			'name' => tra('Show user\'s real name instead of login (when possible)'),
			'description' => tra('Show user\'s real name instead of login (when possible)'),
			'help' => 'User+Preferences',
			'type' => 'flag',
		),
		'user_tracker_infos' => array(
			'name' => tra('Display UserTracker information on the user information page'),
			'description' => tra('Display UserTracker information on the user information page'),
			'help' => 'User+Tracker',
			'hint' => tra('Use the format: trackerId, fieldId1, fieldId2, ...'),
			'type' => 'text',
			'size' => '50',
			'dependencies' => array(
				'userTracker',
			),
		),
		'user_assigned_modules' => array(
			'name' => tra('Users can configure modules'),
			'help' => 'Users+Configure+Modules',
			'type' => 'flag',
		),	
		'user_flip_modules' => array(
			'name' => tra('Users can shade modules'),
			'help' => 'Users+Shade+Modules',
			'type' => 'list',
			'description' => tra('Allows users to hide/show modules.'),
			'options' => array(
				'y' => tra('Always'),
				'module' => tra('Module decides'),
				'n' => tra('Never'),
			),
		),
		'user_store_file_gallery_picture' => array(
			'name' => tra('Store full-size copy of avatar in file gallery'),
			'help' => 'User+Preferences',
			'type' => 'flag',
		),
		'user_picture_gallery_id' => array(
			'name' => tra('File gallery to store full-size copy of avatar in'),
			'description' => tra('Enter the gallery id here. Please create a dedicated gallery that is admin-only for security, or make sure gallery permissions are set so that only admins can edit.'),
			'help' => 'User+Preferences',
			'type' => 'text',
			'filter' => 'digits',
			'size' => '3',
		),
		'user_who_viewed_my_stuff' => array(
			'name' => tra('Display who viewed my stuff on the user information page'),
			'description' => tra('You will need to activate tracking of views for various items in the action log for this to work'),
			'type' => 'flag',
			'dependencies' => array(
				'feature_actionlog',
			),
		),
		'user_who_viewed_my_stuff_days' => array(
			'name' => tra('Number of days to consider who viewed my stuff'),
			'description' => tra('Number of days before current time to consider when showing who viewed my stuff'),
			'type' => 'text',
			'filter' => 'digit',
			'size' => '4',
		),
		'user_who_viewed_my_stuff_show_others' => array(
			'name' => tra('Show to others who viewed my stuff on the user information page'),
			'description' => tra('Show to others who viewed my stuff on the user information page. Admins can always see this information.'),
			'type' => 'flag',
			'dependencies' => array(
				'user_who_viewed_my_stuff',
			),
		),
		'user_list_order' => array(
			'name' => tra('Sort Order'),
			'type' => 'list',
			'options' => UserListOrder(),
		),
		'user_selector_threshold' => array(
			'name' => tra('Maximum number of users to show in drop down lists'),
			'description' => tra('Prevents out of memory and performance issues when user list is very large by using a jQuery autocomplete text input box.'),
			'type' => 'text',
			'size' => '5',
			'dependencies' => array('feature_jquery_autocomplete'),
		)
	);
}

/**
 * UserListOrder computes the value list for user_list_order preference
 * 
 * @access public
 * @return array : list of values
 */
function UserListOrder()
{
	global $prefs;
	$options = array();

	if ($prefs['feature_community_list_score'] == 'y') {
		$options['score_asc'] = tra('Score ascending');
		$options['score_desc'] = tra('Score descending');
	}
	
	if ($prefs['feature_community_list_name'] == 'y') {
		$options['pref:realname_asc'] = tra('Name ascending');
		$options['pref:realname_desc'] = tra('Name descending');
	}

	$options['login_asc'] = tra('Login ascending');
	$options['login_desc'] = tra('Login descending');

	return $options;
}
