<?php
// (c) Copyright 2002-2010 by authors of the Tiki Wiki/CMS/Groupware Project
// 
// All Rights Reserved. See copyright.txt for details and a complete list of authors.
// Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See license.txt for details.
// $Id: function.user_selector.php 28003 2010-07-15 15:52:29Z jonnybradley $

/* {user_selector
 *     user = $user
 *     group = 'all'
 *     name = 'user'
 *     id = user_selector_XX
 *     size = ''
 *     editable = $tiki_p_admin
 *  }
 * 
 * Display a drop down menu of all users or
 * an input box with autocomplete if there are more users
 * than $prefs['user_selector_threshold']
 */
function smarty_function_user_selector($params, &$smarty) {
	global $prefs, $user, $userlib, $headerlib, $tikilib, $tiki_p_admin;
	require_once 'lib/userslib.php';
	
	static $iUserSelector = 0;
	$iUserSelector++;
	
	$defaults = array( 'user' => $user, 'group' => 'all', name => 'user', id => 'user_selector_' . $iUserSelector, 'editable' => $tiki_p_admin == 'y');
	$params = array_merge($defaults, $params);
	if (isset($params['size'])) {
		$sz = ' size="' . $params['size'] . '"';
	} else {
		$sz = '';
	}
	if (!$params['editable']) {
		$ed = ' disabled="disabled"';
	} else {
		$ed = '';
	}
	if ($params['group'] == 'all') {
		$usrs = $tikilib->list_users(0, -1, 'login_asc');
		$users = array();
		foreach ($usrs['data'] as $usr) {
			$users[] = $usr['login'];
		}
	} else {
		$users = $userlib->get_group_users($params['group']);
	}
	$ret = '';
	
	if ($prefs['feature_jquery_autocomplete'] == 'y' && count($users) > $prefs['user_selector_threshold']) {
		$ret .= '<input id="' . $params['id'] . '" type="text" name="' . $params['name'] . '" value="' . $params['user'] . '"' . $sz . $ed . ' />';
		$headerlib->add_jq_onready('$jq("#' . $params['id'] . '").tiki("autocomplete", "username", {mustMatch: true});');
	} else {
		$ret .= '<select name="' . $params['name'] . '" id="' . $params['id'] . '"' . $sz . $ed . '>';
		foreach($users as $usr) {
			$ret .= '<option value="' . $usr . '"' . ($usr == $params['user'] ? ' selected="selected"' : '') . ' >' . $usr .'</option>';
		}
		$ret .= '</select>';
	}
	return $ret;
		
}
