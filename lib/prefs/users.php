<?php
// (c) Copyright 2002-2016 by authors of the Tiki Wiki CMS Groupware Project
// 
// All Rights Reserved. See copyright.txt for details and a complete list of authors.
// Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See license.txt for details.
// $Id$

function prefs_users_list()
{	
	return array(
		'users_serve_avatar_static' => array(
			'name' => tra('Serve profile pictures statically'),
			'description' => tra('When enabled, feature checks and permission checks will be skipped.'),
			'type' => 'flag',
			'perspective' => false,
			'default' => 'y',
		),
		'users_prefs_display_timezone' => array(
			'name' => tra('Displayed time zone'),
			'descriprion' => tra('Use time zone set from user preferences, or the automatically detected time zone for anonymous (if browser allows). Site default is used as fallback.'),
			'type' => 'radio',
			'options' => array(
				'Site' => tra('Always the site default time zone.'),
				'Local' => tra('Use time zone set from user preferences, or the automatically detected time zone for anonymous (if browser allows). Site default is used as fallback.'),
			),
			'default' => 'Local',
			'tags' => array('basic'),
		),
		'users_prefs_userbreadCrumb' => array(
			'name' => tra('Number of visited pages to remember'),
			'type' => 'list',
			'units' => tra('visited pages'),
			'options' => array(
				'1' => tra('1'),
				'2' => tra('2'),
				'3' => tra('3'),
				'4' => tra('4'),
				'5' => tra('5'),
				'10' => tra('10'),
			),
			'default' => '4',
		),
		'users_prefs_user_information' => array(
			'name' => tra('User information'),
			'type' => 'list',
			'description' => 'Specify if users’ information is Public or Private.',
			'options' => array(
				'private' => tra('Private'),
				'public' => tra('Public'),
			),
			'default' => 'private',
			'tags' => array('basic'),
		),
		'users_prefs_display_12hr_clock' => array(
			'name' => tra('Use 12-hour clock for time selectors'),
			'type' => 'flag',
			'description' => tra('Use the 12-hour clock (with AM and PM) in some edit screens to set the time for publishing new or edited blog posts, articles, etc.'),
			'default' => 'n',
			'tags' => array('basic'),
		),
		'users_prefs_diff_versions' => array(
			'name' => tra('Use interface that shows differences in any versions'),
			'type' => 'flag',
			'dependencies' => array(
				'feature_wiki',
			),
			'default' => 'n',
		),
		'users_prefs_show_mouseover_user_info' => array(
			'name' => tra("Pre-set show user's info on mouseover"),
			'description' => tra("Set new users to show their user's info on mouseover"),
			'type' => 'flag',
			'dependencies' => array(
				'feature_community_mouseover',
			),
			'default' => 'n',
		),
		'users_prefs_tasks_maxRecords' => array(
			'name' => tra('Tasks per page'),
			'type' => 'list',
			'units' => tra('tasks'),
			'options' => array(
				'2' => tra('2'),
				'5' => tra('5'),
				'10' => tra('10'),
				'20' => tra('20'),
				'30' => tra('30'),
				'40' => tra('40'),
				'50' => tra('50'),
			),
			'dependencies' => array(
				'feature_tasks',
			),
			'help' => 'Tasks',
			'default' => '10',
		),
		'users_prefs_mess_maxRecords' => array(
			'name' => tra('Messages per page'),
			'type' => 'list',
			'units' => tra('messages'),
			'dependencies' => array(
				'feature_messages',
			),
			'options' => array(
				'2' => tra('2'),
				'5' => tra('5'),
				'10' => tra('10'),
				'20' => tra('20'),
				'30' => tra('30'),
				'40' => tra('40'),
				'50' => tra('50'),
			),
			'default' => '10',
		),
		'users_prefs_allowMsgs' => array(
			'name' => tra('Allow messages from other users'),
			'type' => 'flag',
			'dependencies' => array(
				'feature_messages',
			),
			'default' => 'y',
		),
		'users_prefs_mess_sendReadStatus' => array(
			'name' => tra('Notify sender when reading mail'),
			'type' => 'flag',
			'dependencies' => array(
				'feature_messages',
			),
			'default' => 'n',
		),
		'users_prefs_minPrio' => array(
			'name' => tra('Send me an email for messages with priority equal to or greater than'),
			'type' => 'list',
			'units' => tra('priority'),
			'dependencies' => array(
				'feature_messages',
			),
			'options' => array(
				'1' => tra('1'),
				'2' => tra('2'),
				'3' => tra('3'),
				'4' => tra('4'),
				'5' => tra('5'),
				'6' => tra('None'),
			),
			'default' => '3',
		),
		'users_prefs_mess_archiveAfter' => array(
			'name' => tra('Auto-archive read messages after'),
			'type' => 'list',
			'description' => 'Number of days after which Tiki will archive users’ read messages.',
			'dependencies' => array(
				'feature_messages',
			),
			'options' => array(
				'0' => tra('Never'),
				'1' => tra('1'),
				'2' => tra('2'),
				'5' => tra('5'),
				'10' => tra('10'),
				'20' => tra('20'),
				'30' => tra('30'),
				'40' => tra('40'),
				'50' => tra('50'),
				'60' => tra('60'),
			),
			'units' => tra('days'),
			'default' => '0',
		),
		'users_prefs_mytiki_pages' => array(
			'name' => tra('My pages'),
			'type' => 'flag',
			'description' => tr('List all wiki pages edited by the user.'),
			'dependencies' => array(
				'feature_wiki',
			),
			'default' => 'y',
			'tags' => array('basic'),
		),
		'users_prefs_mytiki_blogs' => array(
			'name' => tra('My blogs'),
			'type' => 'flag',
			'description' => tr('List all blogs and blog posts edited by the user.'),
			'dependencies' => array(
				'feature_blogs',
			),
			'default' => 'y',
			'tags' => array('basic'),
		),
		'users_prefs_mytiki_gals' => array(
			'name' => tra('My galleries'),
			'type' => 'flag',
			'description' => tr('List all galleries edited by the user.'),
			'dependencies' => array(
				'feature_galleries',
			),
			'default' => 'y',
			'tags' => array('basic'),
		),
		'users_prefs_mytiki_msgs' => array(
			'name' => tra('My messages'),
			'type' => 'flag',
			'description' => tr('List all messages and replies by the user.'),
			'dependencies' => array(
				'feature_messages',
			),
			'default' => 'y',
			'tags' => array('basic'),
		),
		'users_prefs_mytiki_tasks' => array(
			'name' => tra('My tasks'),
			'type' => 'flag',
			'description' => tr('List all tasks by the user.'),
			'dependencies' => array(
				'feature_tasks',
			),
			'default' => 'y',
			'tags' => array('basic'),
		),
		'users_prefs_mytiki_forum_topics' => array(
			'name' => tra('My forum topics'),
			'type' => 'flag',
			'description' => tr('List all forum topics by the user.'),
			'dependencies' => array(
				'feature_forums',
			),
			'default' => 'y',
			'tags' => array('basic'),
		),
		'users_prefs_mytiki_forum_replies' => array(
			'name' => tra('My forum replies'),
			'type' => 'flag',
			'description' => tr('List all forum replies by the user.'),
			'dependencies' => array(
				'feature_forums',
			),
			'default' => 'y',
			'tags' => array('basic'),
		),
		'users_prefs_mytiki_items' => array(
			'name' => tra('My items'),
			'type' => 'flag',
			'description' => tr('List all tracker items by the user.'),
			'dependencies' => array(
				'feature_trackers',
			),
			'default' => 'y',
			'tags' => array('basic'),
		),
		'users_prefs_mailCharset' => array(
			'name' => tra('Character set for mail'),
			'type' => 'list',
			'options' => array(
				'' => 'default',
				'utf-8' => 'utf-8',
				'iso-8859-1' => 'iso-8859-1',
			),
			'default' => 'utf-8',
		),
		'users_prefs_remember_closed_rboxes' => array(
			'name' => tra('Keep closed remarksbox hidden'),
			'description' => tra("Remember which remarksbox (alert box) users have closed and don't show them again."),
			'type' => 'flag',
			'default' => 'n',
		),
		'users_prefs_xmpp_password' => array(
			'name' => tra('XMPP account password'),
			'description' => tra('XMPP account password'),
			'keywords' => 'xmpp converse conversejs chat',
			'type' => 'text',
			'default' => '',
		),
	);
}
