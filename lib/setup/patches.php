<?php

// $Id$
// Copyright (c) 2002-2007, Luis Argerich, Garland Foster, Eduardo Polidor, et. al.
// All Rights Reserved. See copyright.txt for details and a complete list of authors.
// Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See license.txt for
// details.

//this script may only be included - so its better to die if called directly.
if (strpos($_SERVER['SCRIPT_NAME'],'tiki-setup.php')!=FALSE) {
  header('location: index.php');
  exit;
}

// patch for Case-sensitivity perm issue
if ( $prefs['case_patched'] == 'n' ) {
	include_once 'db/case_patch.php';
	$tikilib->set_preference('case_patched','y');
}

// UPGRADE temporary for wysiwyg prefs. TODO REMOVE from release
if ($prefs['feature_wysiwyg'] == 'no' or $prefs['feature_wysiwyg'] == 'optional' or $prefs['feature_wysiwyg'] == 'default') {
	$par = $prefs['wiki_wikisyntax_in_html'];
	$def = $prefs['wysiwyg_default'];
	if ($prefs['feature_wysiwyg'] == 'optional') {
		$tikilib->set_preference('feature_wysiwyg','y');
		$tikilib->set_preference('wysiwyg_optional','y');
		if ($def == 'y') {
			$tikilib->set_preference('wysiwyg_default','y');
		}
	} elseif ($prefs['feature_wysiwyg'] == 'default') {
		$tikilib->set_preference('feature_wysiwyg','y');
		$tikilib->set_preference('wysiwyg_optional','n');
		$tikilib->set_preference('wysiwyg_default','y');
	} else {
		$tikilib->set_preference('feature_wysiwyg','n');
	}
	if ($par == 'full') {
		$tikilib->set_preference('wysiwyg_wiki_parsed','y');
		$tikilib->set_preference('wysiwyg_wiki_semi_parsed','n');
	} elseif ($par == 'partial') {
		$tikilib->set_preference('wysiwyg_wiki_parsed','y');
		$tikilib->set_preference('wysiwyg_wiki_semi_parsed','y');
	} elseif ($par == 'none') {
		$tikilib->set_preference('wysiwyg_wiki_parsed','n');
		$tikilib->set_preference('wysiwyg_wiki_semi_parsed','n');
	}
}
