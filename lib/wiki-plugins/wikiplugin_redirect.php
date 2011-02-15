<?php
// (c) Copyright 2002-2010 by authors of the Tiki Wiki CMS Groupware Project
//
// All Rights Reserved. See copyright.txt for details and a complete list of authors.
// Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See license.txt for details.
// $Id$

// \brief Wiki plugin to redirect to another page.
// @author damian aka damosoft 30 March 2004

function wikiplugin_redirect_help() {
        return tra("Redirects you to another wiki page").":<br />~np~{REDIRECT(page=pagename [,url=http://foobar])/}~/np~";
}

function wikiplugin_redirect_info() {
	return array(
		'name' => tra('Redirect'),
		'documentation' => tra('PluginRedirect'),			
		'description' => tra('Redirect the user to a wiki page or generic URL.'),
		'prefs' => array( 'wikiplugin_redirect' ),
		'validate' => 'arguments',
		'params' => array(
			'page' => array(
				'required' => false,
				'name' => tra('Page Name'),
				'description' => tra('Wiki page name to redirect to.'),
				'default' => '',
			),
			'url' => array(
				'required' => false,
				'name' => tra('URL'),
				'description' => tra('Complete URL, internal or external.'),
				'default' => '',
			),
		),
	);
}

function wikiplugin_redirect($data, $params, $offset, $options) {
	global $tikilib, $just_saved;
	extract ($params,EXTR_SKIP);
	$areturn = '';

	if (!isset($page)) {$areturn = "REDIRECT plugin: No page specified!";}
	if (!isset($url)) {$areturn += "REDIRECT plugin: No url specified!";}
	$location = isset($page) ? $page : $url;
	if ($just_saved) {
		$areturn = sprintf(tra("REDIRECT plugin: The redirection to '%s' is disabled just after saving the page."), $location);
	} else if ($options['preview_mode']) {
		$areturn = sprintf(tra("REDIRECT plugin: The redirection to '%s' is disabled in preview mode. "), $location);
	} else if ((isset($_REQUEST['redirectpage']))) {
		$areturn = tra("REDIRECT plugin: redirect loop detected!");
	} else if (isset($options['print']) && $options['print'] == 'y') {
		$info = $tikilib->get_page_info( $location );
		return $tikilib->parse_data($info['data'], $options);
	} else {
		/* SEO: Redirect with HTTP status 301 - Moved Permanently than default 302 - Found */
		if (isset($page)) {
			header("Location: tiki-index.php?page=$page&redirectpage=".$_REQUEST['page'], true, 301);
			exit;
		}
		if (isset($url)) {
			header("Location: $url");
			exit;
		}
	}

	return $areturn;
}
