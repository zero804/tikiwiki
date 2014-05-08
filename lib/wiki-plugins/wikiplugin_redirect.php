<?php
// (c) Copyright 2002-2013 by authors of the Tiki Wiki CMS Groupware Project
//
// All Rights Reserved. See copyright.txt for details and a complete list of authors.
// Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See license.txt for details.
// $Id$

function wikiplugin_redirect_info()
{
	return array(
		'name' => tra('Redirect'),
		'documentation' => 'PluginRedirect',
		'description' => tra('Redirect to another page'),
		'prefs' => array( 'wikiplugin_redirect' ),
		'validate' => 'arguments',
		'icon' => 'img/icons/arrow_right.png',
		'tags' => array( 'basic' ),
		'params' => array(
			'page' => array(
				'required' => false,
				'name' => tra('Page Name'),
				'description' => tra('Wiki page name to redirect to.'),
				'filter' => 'pagename',
				'default' => '',
				'profile_reference' => 'wiki_page',
			),
			'url' => array(
				'required' => false,
				'name' => tra('URL'),
				'description' => tra('Complete URL, internal or external.'),
				'filter' => 'url',
				'default' => '',
			),
			'perspective' => array(
				'required' => false,
				'name' => tra('Perspective'),
				'description' => tra('The ID of a perspective to switch to (requires feature_perspective).'),
				'filter' => 'int',
				'default' => '',
				'profile_reference' => 'perspective',
			),
		),
	);
}

function wikiplugin_redirect($data, $params)
{
	global $tikilib, $just_saved;
	extract($params, EXTR_SKIP);
	$areturn = '';

	if (!isset($page)) {
		$areturn = "REDIRECT plugin: No page specified!<br />";
	}
	if (!isset($url)) {
		$areturn .= "REDIRECT plugin: No url specified!<br />";
	}
	if (isset($page)) {
		$location = $page;
	} else if (isset($url)) {
		$location = $url;
	} else if (isset($perspective)) {
		$location = tra('perspective ') . $perspective;
	} else {
		$location = tra('nowhere');
		$areturn .= "REDIRECT plugin: No perspective specified!";
	}

	if ($just_saved) {
		$areturn = sprintf(tra("REDIRECT plugin: The redirection to '%s' is disabled just after saving the page."), $location);
	} else if (TikiLib::lib('parser')->option['indexing']) {
		return;
	} else if (TikiLib::lib('parser')->option['preview_mode']) {
		$areturn = sprintf(tra("REDIRECT plugin: The redirection to '%s' is disabled in preview mode. "), $location);
	} else if ((isset($_REQUEST['redirectpage']))) {
		$areturn = tra("REDIRECT plugin: redirect loop detected!");
	} else if (isset(TikiLib::lib('parser')->option['print']) && TikiLib::lib('parser')->option['print'] == 'y') {
		$info = $tikilib->get_page_info($location);
		return $tikilib->parse_data($info['data'], TikiLib::lib('parser')->option);
	} else {

		if (isset($perspective)) {
			global $access, $perspectivelib, $base_host;
			require_once 'lib/perspectivelib.php';
			$access->check_feature('feature_perspective');

			if ($_SESSION['current_perspective'] !== $perspective) {
		
				if ( $perspectivelib->perspective_exists($perspective) ) {
					$_SESSION['current_perspective'] = $perspective;
				}
				if (empty($page) && empty($url)) {
					$url =  $base_host . $_SERVER['REQUEST_URI'];
				}
			}
			$areturn = '';	// errors set above not relevant if using perspective
		}

		// Make it possible to edit the plugin in wysiwyg
		// Do not redirect if the page is being edited
		$isEditMode = (strpos($_SERVER['SCRIPT_NAME'], 'tiki-editpage.php') !== false) || (isset($_REQUEST['controller']) && $_REQUEST['controller'] == 'edit');
		if ($isEditMode == false) {

			/* SEO: Redirect with HTTP status 301 - Moved Permanently than default 302 - Found */
			if (isset($page)) {
				TikiLib::lib('access')->redirect("tiki-index.php?page=$page&redirectpage=".$_REQUEST['page'], '', 301);
			}
			if (isset($url)) {

				global $base_url, $url_path;		// try to detect redirect loop to server root
				if (
					$url == $base_url ||			// whole site url
					$url . '/' == $base_url ||		// optional trailing /
					$url == $url_path ||			// just the path?
					$url . '/' == $url_path ||
					preg_match('/[\.]?\/$/', $url)	// either ./ or / current dir or root
				) {

					$hp = TikiLib::lib('wiki')->get_default_wiki_page();

					if ($_REQUEST['page'] === $hp && !isset($_GET['page']) && !isset($_POST['page'])) {
						return '';						// don't redirect if we've already been redirected to the "home page"
					}
				}
				TikiLib::lib('access')->redirect($url);
			}
		}
	}
	return $areturn;
}
