<?php

// Copyright (c) 2002-2009, Luis Argerich, Garland Foster, Eduardo Polidor, et. al.
// All Rights Reserved. See copyright.txt for details and a complete list of authors.
// Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See license.txt for
// details.

//this script may only be included - so its better to die if called directly.
if ( basename($_SERVER['SCRIPT_NAME']) == basename(__FILE__) ) {
  header("location: index.php");
  exit;
}

// The following constant is at least used in the release.php script and in the error handling functions
define( 'THIRD_PARTY_LIBS_PATTERN', '#lib/(pear|ajax|adodb)#' );

// add a line like the following in db/local.php to use an external smarty installation: $smarty_path='/usr/share/php/smarty/'
define('TIKI_SMARTY_DIR', 'lib/smarty_tiki/');
if ( isset($smarty_path) && $smarty_path != '' && file_exists($smarty_path.'Smarty.class.php') ) define('SMARTY_DIR', $smarty_path);
else define('SMARTY_DIR', 'lib/smarty/libs/');
