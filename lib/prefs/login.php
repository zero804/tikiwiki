<?php
// (c) Copyright 2002-2015 by authors of the Tiki Wiki CMS Groupware Project
// 
// All Rights Reserved. See copyright.txt for details and a complete list of authors.
// Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See license.txt for details.
// $Id$

function prefs_login_list() 
{
	return array(
		'login_is_email' => array(
			'name' => tra('Use email as username'),
			'description' => tra('Instead of creating new usernames, use the user\'s email address for authentication.'),
			'type' => 'flag',
			'default' => 'n',
		),
		'login_is_email_obscure' => array(
			'name' => tra('Obscure email when using email as username if possible (coverage will not be complete)'),
			'description' => tra('This will attempt as much as possible to hide the email, showing the realname or the truncated email instead.'),
			'type' => 'flag',
			'dependencies' => array(
				'login_is_email',
			),
			'default' => 'n',
		),
		'login_http_basic' => array(
			'name' => tr('HTTP Basic Authentication'),
			'description' => tr('Check credentials from HTTP Basic Authentication, useful to allow webservices to use credentials.'),
			'type' => 'list',
			'filter' => 'alpha',
			'default' => 'n',
			'options' => array(
				'n' => tr('Disable'),
				'ssl' => tr('SSL Only (Recommended)'),
				'always' => tr('Always'),
			),
		),
		'login_multiple_forbidden' => array(
			'name' => tr('Prevent multiple logins from same user'),
			'description' => tr('User can not login simultaneously from multiple browsers. Admin account is still allowed.'),
			'type' => 'flag',
			'default' => 'n',
			'tags' => array('advanced'),			
		),
		'login_autologin' => array(
			'name' => tr('Enable autologin from remote Tiki'),
			'description' => tr('Used with autologin_remotetiki in the redirect plugin'),
			'type' => 'flag',
			'default' => 'n',
			'tags' => array('advanced'),
			'dependencies' => array(
				'login_autologin_user',
				'login_autologin_group',
				'auth_token_access',
			),
		),
		'login_autologin_user' => array(
			'name' => tr('System username to use to initiate autologin from remote Tiki'),
			'description' => tr('Specified user must exist and be configured in Settings...Tools...DSN/Content Authentication on remote Tiki. Used with autologin_remotetiki in the redirect plugin.'),
			'type' => 'text',
			'default' => '',
			'tags' => array('advanced'),
		),
		'login_autologin_group' => array(
			'name' => tr('System groupname to use for auto login token'),
			'description' => tr('For security, please create a group that has no users and no permissions and specify its name here.'),
			'type' => 'text',
			'default' => '',
			'tags' => array('advanced'),
		),
		'login_autologin_createnew' => array(
			'name' => tr('Create user account if autologin user does not exist'),
			'description' => tr('Create a new user account if the user that is trying to autologin does not exist on this Tiki.'),
			'type' => 'flag',
			'default' => 'y',
			'tags' => array('advanced'),
		),
		'login_autologin_allowedgroups' => array(
			'name' => tr('Allowed groups from remote Tiki to autologin.'),
			'description' => tr('Comma separated list of groups to allow autologin from remote Tiki. If empty, will allow everyone.'),
			'type' => 'text',
			'default' => '',
			'tags' => array('advanced'),
		),
		'login_autologin_syncgroups' => array(
			'name' => tr('Sync these groups from remote Tiki on autologin.'),
			'description' => tr('Comma separated list of groups to sync from remote Tiki on autologin. Group membership will be added or removed accordingly.'),
			'type' => 'text',
			'default' => '',
			'tags' => array('advanced'),
		),
		'login_autologin_logoutremote' => array(
			'name' => tr('Automatically logout remote Tiki after logout.'),
			'description' => tr('When the user logs out of this Tiki, redirect the user to logout of the other Tiki as well.'),
			'type' => 'flag',
			'default' => 'y',
			'tags' => array('advanced'),
		),
	);
}

