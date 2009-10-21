<?php

// $Id$
// Copyright (c) 2002-2007, Luis Argerich, Garland Foster, Eduardo Polidor, et. al.
// All Rights Reserved. See copyright.txt for details and a complete list of authors.
// Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See license.txt for
// details.

//this script may only be included - so its better to die if called directly.
$access->check_script($_SERVER["SCRIPT_NAME"],basename(__FILE__));

// Javascript auto-detection
//   (to be able to generate non-javascript code if there is no javascript, when noscript tag is not useful enough)
//   It uses cookies instead of session vars to keep the correct value after a session timeout

if ( isset($_COOKIE['javascript_enabled']) ) {
	// Update the pref with the cookie value
	$prefs['javascript_enabled'] = $_COOKIE['javascript_enabled'];
} else {
	// Set the cookie to 'n', through PHP / HTTP headers
	$prefs['javascript_enabled'] = 'n';
}

if ( $prefs['javascript_enabled'] != 'y' ) {
	// Set the cookie to 'y', through javascript (will override the above cookie set to 'n' and sent by PHP / HTTP headers) - duration: approx. 1 year
	$headerlib->add_js("var jsedate = new Date();\njsedate.setTime(" . ( 1000 * ( $tikilib->now + 365 * 24 * 3600 ) ) . ");\nsetCookieBrowser('javascript_enabled', 'y', null, jsedate);");

	$prefs['feature_tabs'] = 'n';
	$prefs['feature_jquery'] = 'n';
	$prefs['feature_shadowbox'] = 'n';
	$prefs['feature_wysiwyg'] = 'n';
	$prefs['feature_ajax'] = 'n';
	
} else {	// we have JavaScript

	$prefs['feature_jquery'] = 'y';	// just in case
	
	/** Use custom.js in styles or options dir if there **/
	$custom_js = $tikilib->get_style_path($prefs['style'], $prefs['style_option'], 'custom.js');
	if (!empty($custom_js)) {
		$headerlib->add_jsfile($custom_js, 50);
	}
	
	if (strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE 6') !== false) {
		
		$smarty->assign('ie6', true);
		
		if ($prefs['feature_iepngfix'] == 'y') {
			/**
			 * \brief another attempt for PNG alpha transparency fix which seems to work best for IE6 and can be applied even on background positioned images
			 * 
			 * is applied explicitly on defined CSS selectors or HTMLDomElement
			 * 
			 */
			if (($fixoncss = $prefs['iepngfix_selectors']) == '') {
				$fixoncss = '#sitelogo a img';
			}
			if (($fixondom = $prefs['iepngfix_elements']) != '') {
				$fixondom = "DD_belatedPNG.fixPng($fixondom); // list of HTMLDomElements to fix separated by commas (default is none)";
			}
			if ($prefs['feature_use_minified_scripts'] != 'n') {
				$scriptpath = 'lib/iepngfix/DD_belatedPNG.js';
			} else {
				$scriptpath = 'lib/iepngfix/DD_belatedPNG-min.js';
			}
			$headerlib->add_jsfile ($scriptpath, 200);
			$headerlib->add_js (<<<JS
DD_belatedPNG.fix('$fixoncss'); // list of CSS selectors to fix separated by commas (default is set to fix sitelogo)
$fixondom
JS
			);
			// css rules for colorbox - were causing large delay on page rendering in IE7 & 8
			if ($prefs['feature_shadowbox'] == 'y') {
				$headerlib->add_css('
.cboxIE #cboxTopLeft{background:transparent; filter: progid:DXImageTransform.Microsoft.AlphaImageLoader(src=images/internet_explorer/borderTopLeft.png, sizingMethod="scale");}
.cboxIE #cboxTopCenter{background:transparent; filter: progid:DXImageTransform.Microsoft.AlphaImageLoader(src=images/internet_explorer/borderTopCenter.png, sizingMethod="scale");}
.cboxIE #cboxTopRight{background:transparent; filter: progid:DXImageTransform.Microsoft.AlphaImageLoader(src=images/internet_explorer/borderTopRight.png, sizingMethod="scale");}
.cboxIE #cboxBottomLeft{background:transparent; filter: progid:DXImageTransform.Microsoft.AlphaImageLoader(src=images/internet_explorer/borderBottomLeft.png, sizingMethod="scale");}
.cboxIE #cboxBottomCenter{background:transparent; filter: progid:DXImageTransform.Microsoft.AlphaImageLoader(src=images/internet_explorer/borderBottomCenter.png, sizingMethod="scale");}
.cboxIE #cboxBottomRight{background:transparent; filter: progid:DXImageTransform.Microsoft.AlphaImageLoader(src=images/internet_explorer/borderBottomRight.png, sizingMethod="scale");}
.cboxIE #cboxMiddleLeft{background:transparent; filter: progid:DXImageTransform.Microsoft.AlphaImageLoader(src=images/internet_explorer/borderMiddleLeft.png, sizingMethod="scale");}
.cboxIE #cboxMiddleRight{background:transparent; filter: progid:DXImageTransform.Microsoft.AlphaImageLoader(src=images/internet_explorer/borderMiddleRight.png, sizingMethod="scale");}
				');
			}
		}
	}
}

if ($prefs['feature_ajax'] != 'y') {
	$prefs['feature_ajax_autosave'] = 'n';
}
