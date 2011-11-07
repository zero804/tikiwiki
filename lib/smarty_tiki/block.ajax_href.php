<?php
// (c) Copyright 2002-2010 by authors of the Tiki Wiki CMS Groupware Project
// 
// All Rights Reserved. See copyright.txt for details and a complete list of authors.
// Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See license.txt for details.
// $Id$

//this script may only be included - so its better to die if called directly.
if (strpos($_SERVER["SCRIPT_NAME"],basename(__FILE__)) !== false) {
  header("location: index.php");
  exit;
}

/*
 * smarty_block_ajax_href creates the href for a link in Smarty accoring to AJAX prefs
 * 
 * Params:
 * 
 * 	template	-	template to load (e.g. tiki-admin.tpl)
 * 	htmlelement	-	destination div (usually) to load request into
 * 	function	-	xajax registered function to call - default: loadComponent
 * 	scrollTo	-	x,y coords to scroll to on click (e.g. "0,0")
 * 	_onclick	-	extra JS to run first onclick
 */


function smarty_block_ajax_href($params, $content, &$smarty, $repeat) {
    global $prefs, $user, $info;
    if ( $repeat ) return;

	if ( !empty($params['_onclick']) ) {
		$onclick = $params['_onclick'];
		if (substr($onclick, -1) != ';') {
			$onclick .= ';';
		}
	} else {
		$onclick = '';
    }
    $url = $content;
    $template = $params['template'];
	if ( !empty($params['htmlelement']) ) {
		$htmlelement = $params['htmlelement'];
	} else {
		$htmlelement = 'role_main';
	}
	$def_func = (isset($params['scrollTo']) ? 'window.scrollTo('.$params['scrollTo'].');' : '') . 'loadComponent';
    $func = isset($params['function']) ? $params['function']: $def_func;	// preserve previous behaviour
    $last_user = htmlspecialchars($user);

    																		// temporary switch to not do ajax for ckeditor button - not reliable in tiki 6
// Actually in tiki 6 even when not ckeditor if you edit while in tiki-index vs editpage the plugin help breaks as well, you get blank help
    if ( $prefs['ajax_xajax'] !== 'y' || $prefs['javascript_enabled'] == 'n' || $template === 'tiki-editpage.tpl' ) {
		return " href=\"" . $url . "\" ";
    } else {
		$max_tikitabs = 50; // Same value as in header.tpl, <body> tag onload's param
		if (empty($params['_anchor'])) {
			$anchor = "#main";
		} else {
			$anchor = '#'.$params['_anchor'];
		}
		return " href=\"$anchor\" onclick=\"$onclick $func('" . $url . "','$template','$htmlelement',$max_tikitabs,'$last_user'); return false\" ";
    }
}
