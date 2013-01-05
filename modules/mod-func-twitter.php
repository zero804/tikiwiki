<?php
// (c) Copyright 2002-2013 by authors of the Tiki Wiki CMS Groupware Project
//
// All Rights Reserved. See copyright.txt for details and a complete list of authors.
// Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See license.txt for details.
// $Id$

//this script may only be included - so its better to die if called directly.
if (strpos($_SERVER["SCRIPT_NAME"], basename(__FILE__)) !== false) {
  header("location: index.php");
  exit;
}

// Module special params:
// - user: Tiki username to show Twitter timeline of

function module_twitter_info()
{
	return array(
		'name' => tra('Tweets'),
		'description' => tra('Shows the tweets from the Twitter timeline of a user'),
		'params' => array(
			'user' => array(
				'name' => 'user',
				'description' => tra('Tiki user to show Twitter timeline of.'),
				'required' => true
			),
			'timelinetype' => array(
				'name' => 'Timeline type',
				'description' => tra('Show public|friends timeline. '),
				'default' => 'public',
			),
			'showuser' => array(
				'name' => 'showuser',
				'description' => tra('Show username in timeline. y|n'),
				'default' => 'n',
			),
		),
		'common_params' => array('nonums', 'rows'),
	);
}

function module_twitter( $mod_reference, $module_params )
{
	global $tikilib, $smarty, $prefs;
	global $socialnetworkslib; require_once ('lib/socialnetworkslib.php');
	if ( !empty($module_params['user']) ) {
		$user = $module_params['user'];

		$token=$tikilib->get_user_preference($user, 'twitter_token', '');
		$smarty->assign('twitter', ($token!=''));

		$response=$socialnetworkslib->getTwitterTimeline($user, $mod_reference['params']['timelinetype']);
		if ($response == -1) {
			$timeline[0]['text'] = tra('user not registered with twitter').": $user";
		}
		for ($i = 0, $count_response_status = count($response->status); $i < $count_response_status; $i++) {

			$timeline[$i]['text']=$response->status[$i]->text;
			$timeline[$i]['id']=$response->status[$i]->id;
			$timeline[$i]['created_at']=$response->status[$i]->created_at;
			$timeline[$i]['screen_name']=$response->status[$i]->user->screen_name;
		}
	} else {
		$i=0;
		$timeline[$i]['text'] = tra('No username given');
		$timeline[$i]['created_at'] = '';
		$timeline[$i]['screen_name'] = '';
	}

	$timeline=array_splice($timeline, 0, $mod_reference['rows']?$mod_reference['rows']:10);

	$smarty->assign('timeline', $timeline);
}
