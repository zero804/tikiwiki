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

if ($prefs['feature_kaltura'] === 'y') {
	require_once 'lib/videogals/kalturalib.php';
	if (is_object($kalturaadminlib) && !empty($kalturaadminlib->session)) {
		// contribution wizard
		$kcwDefault = $kalturaadminlib->updateStandardTikiKcw();
		if ($kcwDefault) {
			$kcwText = "<div class='adminoptionbox'>KCW Configuration ID: $kcwDefault (automatically configured)</div>";
		} else {
			$kcwText = "<div class='adminoptionbox'>Unable to retrieve configuration from Kaltura. Please reload page after setting up the Kaltura Partner Settings section</div>";
		}
		// TODO make way to override this for certain sites...
		if ($kcwDefault != $prefs['kaltura_kcwUIConf']) {
			$tikilib->set_preference('kaltura_kcwUIConf', $kcwDefault);
		}
	} else {
		$kcwText = "<div class='adminoptionbox error'>Unable to retrieve configuration from Kaltura. Please reload page after setting up the Kaltura Partner Settings section</div>";
		$kplayerlist = "<div class='adminoptionbox error'>Unable to retrieve list of valid player IDs. Please reload page after setting up the Kaltura Partner Settings section</div>";
	}
} else {
	$kcwText = "<div class='adminoptionbox error'>Kaltura feature disabled</div>";
	$kplayerlist = "<div class='adminoptionbox error'>Kaltura feature disabled</div>";
}
$smarty->assign('kcwText', $kcwText);
$smarty->assign('kplayerlist', $kplayerlist); 
