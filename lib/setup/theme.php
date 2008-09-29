<?php

// $Id$
// Copyright (c) 2002-2007, Luis Argerich, Garland Foster, Eduardo Polidor, et. al.
// All Rights Reserved. See copyright.txt for details and a complete list of authors.
// Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See license.txt for
// details.

//this script may only be included - so its better to die if called directly.
$access->check_script($_SERVER["SCRIPT_NAME"],basename(__FILE__));

if ( isset($_SESSION['try_style']) ) {
	$prefs['style'] = $_SESSION['try_style'];
} elseif ( $prefs['change_theme'] != 'y' ) {
	// Use the site value instead of the user value if the user is not allowed to change the theme
	$prefs['style'] = $prefs['site_style'];
}

if ( ! is_file('styles/'.$prefs['style']) and ! is_file('styles/'.$tikidomain.'/'.$prefs['style']) ) {
	$prefs['style'] = 'tikineat.css';
}

$prefs['style'] = $userlib->get_user_group_theme($user);
		
include_once("csslib.php");
if ($prefs['transition_style_ver'] && $prefs['transition_style_ver'] == 'css_specified_only') {
	$transition_style = $csslib->transition_css('styles/'.$prefs['style'], '');
} elseif ($prefs['transition_style_ver'] && $prefs['transition_style_ver'] != 'none') {
	$transition_style = $csslib->transition_css('styles/'.$prefs['style'], $prefs['transition_style_ver']);
} else {
	$transition_style = '';
}

if ( $transition_style != '' ) $headerlib->add_cssfile('styles/transitions/'.$transition_style,50);

if ( $tikidomain and is_file('styles/'.$tikidomain.'/'.$prefs['style']) ) {
	$headerlib->add_cssfile('styles/'.$tikidomain.'/'.$prefs['style'], 51);
} else {
	$headerlib->add_cssfile('styles/'.$prefs['style'], 51);
}

$stlstl = split("-|\.", $prefs['style']);
$style_base = $stlstl[0];

// Allow to have an ie6.css file for the theme's specific hacks for IE 6
$style_ie6_css = '';
if ( $tikidomain and is_file('styles/'.$tikidomain.'/'.$style_base.'/ie6.css') ) {
	$style_ie6_css = 'styles/'.$tikidomain.'/'.$style_base.'/ie6.css';
} elseif ( is_file('styles/'.$style_base.'/ie6.css') ) {
	$style_ie6_css = 'styles/'.$style_base.'/ie6.css';
}
