<?php
// (c) Copyright 2002-2016 by authors of the Tiki Wiki CMS Groupware Project
// 
// All Rights Reserved. See copyright.txt for details and a complete list of authors.
// Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See license.txt for details.
// $Id$

function prefs_socialnetworks_list()
{
	return array(
		'socialnetworks_twitter_consumer_key' => array(
			'name' => tra('Consumer key'),
			'description' => tra('Consumer key generated by registering this Tiki site as an application at Twitter'),
			'type' => 'text',
			'keywords' => 'social networks',
			'size' => 40,
			'default' =>'',
		),
		'socialnetworks_twitter_consumer_secret' => array(
			'name' => tra('Consumer secret'),
			'description' => tra('Consumer secret generated by registering your site as an application at Twitter'),
			'keywords' => 'social networks',
			'type' => 'text',
			'size' => 60,
			'default' => '',
		),
		'socialnetworks_twitter_site_name' => array(
			'name' => tra('Site Name'),
			'description' => tra('Default twitter:site to be used on every page of your tiki'),
			'keywords' => 'social networks',
			'type' => 'text',
			'size' => 60,
			'default' => '',
		),
		'socialnetworks_twitter_site_image' => array(
			'name' => tra('Site Image'),
			'description' => tra('Default twitter:image to be used on every page of your tiki, must be url'),
			'keywords' => 'social networks',
			'type' => 'text',
			'size' => 60,
			'default' => '',
		),
		'socialnetworks_facebook_application_secr' => array(
			'name' => tra('Application secret'),
			'description' => tra('Application secret generated by registering this Tiki site as an application at Facebook'),
			'keywords' => 'social networks',
			'type' => 'text',
			'size' => 60,
			'default' => '',
		),
		'socialnetworks_facebook_application_id' => array(
			'name' => tra('Application ID'),
			'description' => tra('Application ID generated by registering this Tiki site as an application at Facebook'),
			'keywords' => 'social networks',
			'type' => 'text',
			'size' => 60,
			'default' => '',
		),
		'socialnetworks_facebook_site_name' => array(
			'name' => tra('Site Name'),
			'description' => tra('Default og:sitename to be used on every page of your tiki'),
			'keywords' => 'social networks',
			'type' => 'text',
			'size' => 60,
			'default' => '',
		),
		'socialnetworks_facebook_site_image' => array(
			'name' => tra('Site Image'),
			'description' => tra('Default og:image for sections without images (not articles, blogs, etc.), must be url and minimum image size is 200 x 200 pixels'),
			'keywords' => 'social networks',
			'type' => 'text',
			'size' => 60,
			'default' => '',
		),
		'socialnetworks_facebook_login' => array(
			'name' => tra('Login using Facebook'),
			'description' => tra('Allow users to log in using Facebook'),
			'keywords' => 'social networks',
			'type' => 'flag',
			'default' => 'n',
		),
		'socialnetworks_facebook_autocreateuser' => array(
			'name' => tra('Auto-create Tiki user'),
			'description' => tra('Automatically create a Tiki user by the username of fb_xxxxxxxx for users logging in using Facebook if they do not yet have a Tiki account. If not, they will be asked to link or register a Tiki account'),
			'keywords' => 'social networks',
			'type' => 'flag',
			'dependencies' => array(
				'socialnetworks_facebook_login',
			),
			'default' => 'n',
		),
		'socialnetworks_facebook_firstloginpopup' => array(
			'name' => tra('Require Facebook users to enter local account info on creation'),
			'description' => tra('Require Facebook users to enter local account info, specifically email and local log-in name'),
			'keywords' => 'social networks',
			'type' => 'flag',
			'dependencies' => array(
				'socialnetworks_facebook_login',
				'socialnetworks_facebook_autocreateuser',
			),
			'default' => 'n',
		),
		'socialnetworks_facebook_offline_access' => array(
			'name' => tra('Tiki can access Facebook at any time'),
			'description' => tra('Even when the user is not logged in to Facebook, Tiki can access it.'),
			'keywords' => 'social networks',
			'type' => 'flag',
			'default' => 'n',
		),
		'socialnetworks_facebook_publish_stream' => array(
			'name' => tra('Tiki can post to the Facebook Wall'),
			'description' => tra('Tiki may post status messages, notes, photos, and videos to the Facebook Wall'),
			'keywords' => 'social networks',
			'type' => 'flag',
			'default' => 'n',
		),
		'socialnetworks_facebook_manage_events' => array(
			'name' => tra('Tiki can manage events'),
			'description' => tra('Tiki may create and RSVP to Facebook events'),
			'keywords' => 'social networks',
			'type' => 'flag',
			'default' => 'n',
		),
		'socialnetworks_facebook_manage_pages' => array(
			'name' => tra('Tiki can manage pages'),
			'description' => tra('Tiki can manage user pages'),
			'keywords' => 'social networks',
			'type' => 'flag',
			'default' => 'n',
		),
		'socialnetworks_facebook_sms' => array(
			'name' => tra('Tiki can SMS'),
			'description' => tra('Tiki can SMS via Facebook'),
			'keywords' => 'social networks',
			'type' => 'flag',
			'default' => 'n',
		),
		'socialnetworks_facebook_email' => array(
			'name' => tra('Set user email from Facebook on creation'),
			'description' => tra("Tiki will set the user's email from Facebook on creation"),
			'keywords' => 'social networks',
			'dependencies' => array(
				'socialnetworks_facebook_autocreateuser',
			),
			'type' => 'flag',
			'default' => 'n',
		),
		'socialnetworks_facebook_names' => array(
			'name' => tra('First and last name tracker field IDs to set on creation'),
			'description' => tra("Comma-separated. First name field followed by last name field. eg '2,3'"),
			'keywords' => 'social networks',
			'type' => 'text',
			'default' => 'n',
			'dependencies' => array(
				'userTracker',
				'socialnetworks_facebook_autocreateuser',
			),
		),
		'socialnetworks_bitly_login' => array(
			'name' => tra('bit.ly Login'),
			'description' => tra('Site-wide log-in name (username) for bit.ly'),
			'keywords' => 'social networks',
			'type' => 'text',
			'size' => 60,
			'default' => '',
		),
		'socialnetworks_bitly_key' => array(
			'name' => tra('bit.ly Key'),
			'description' => tra('Site-wide API key for bit.ly'),
			'keywords' => 'social networks',
			'type' => 'text',
			'size' => 60,
			'default' => '',
		),
		'socialnetworks_bitly_sitewide' => array(
			'name' => tra('Use site-wide account'),
			'description' => tra('When set to "yes", only the site-wide account will be used for all users'),
			'keywords' => 'social networks',
			'type' => 'flag',
			'default' => 'n',
		),
		'socialnetworks_linkedin_client_id' => array(
			'name' => tra('Client ID'),
			'description' => tra('Client ID generated by registering your site as an application at LinkedIn'),
			'keywords' => 'social networks',
			'type' => 'text',
			'size' => 60,
			'default' => '',
		),
		'socialnetworks_linkedin_client_secr' => array(
			'name' => tra('Client Secret'),
			'description' => tra('Client Secret generated by registering your site as an application at LinkedIn'),
			'keywords' => 'social networks',
			'type' => 'text',
			'size' => 60,
			'default' => '',
		),
		'socialnetworks_linkedin_login' => array(
			'name' => tra('Login using LinkedIn'),
			'description' => tra('Allow users to login using LinkedIn'),
			'keywords' => 'social networks',
			'type' => 'flag',
			'default' => 'n',
		),
		'socialnetworks_linkedin_autocreateuser' => array(
			'name' => tra('Auto-create Tiki user from LinkedIn'),
			'description' => tra('Automatically create a Tiki user by the username of li_xxxxxxxx for users logging in using LinkedIn if they do not yet have a Tiki account. If not, they will be asked to link or register a Tiki account'),
			'keywords' => 'social networks',
			'type' => 'flag',
			'dependencies' => array(
				'socialnetworks_linkedin_login',
			),
			'default' => 'n',
		),
		'socialnetworks_linkedin_email' => array(
			'name' => tra('Set user email from LinkedIn on creation'),
			'description' => tra("Tiki will set the user's email from LinkedIn on creation"),
			'keywords' => 'social networks',
			'type' => 'flag',
			'dependencies' => array(
				'socialnetworks_linkedin_autocreateuser',
			),
			'default' => 'n',
		),
		'socialnetworks_linkedin_names' => array(
			'name' => tra('First and last name tracker field IDs to set on creation'),
			'description' => tra("Comma-separated. First name field followed by last name field. eg '2,3'"),
			'keywords' => 'social networks',
			'type' => 'text',
			'default' => '',
			'dependencies' => array(
				'userTracker',
				'socialnetworks_linkedin_autocreateuser',
			),
		),
	);
}
