<?php
// (c) Copyright 2002-2010 by authors of the Tiki Wiki/CMS/Groupware Project
// 
// All Rights Reserved. See copyright.txt for details and a complete list of authors.
// Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See license.txt for details.
// $Id$

function prefs_style_list() {
	global $tikilib, $prefs;

	$style_options = array();
	$list = $tikilib->list_style_options($prefs['site_style']);
	if (!empty($list)) {
		foreach ($list as $opt) {
			$style_options[$opt] = $opt;
		}
	}

	$all = glob( 'styles/*/options/*.css' );
	foreach( $all as $location ) {
		$option = basename( $location );

		if( ! isset( $style_options[$option] ) ) {
			$style_options[$option] = "X - $option";
		}
	}

	return array(
		'style_option' => array(
			'name' => tra('Theme options'),
			'type' => 'list',
			'help' => 'Theme options',
			'description' => tra('Style options'),
			'options' => $style_options,
		),
	);	
}
