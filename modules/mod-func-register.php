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

function module_register_info() {
	return array(
		'name' => tra('New user registration'),
		'description' => tra('New user registrationmore tiki-re'),
		'params' => array(),
	);
}

function module_register( $mod_reference, $module_params ) {
	global $prefs, $smarty, $tikilib, $userlib;
	$module = true;
	include_once('tiki-register.php');
}
