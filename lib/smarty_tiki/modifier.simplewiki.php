<?php
// (c) Copyright 2002-2016 by authors of the Tiki Wiki CMS Groupware Project
//
// All Rights Reserved. See copyright.txt for details and a complete list of authors.
// Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See license.txt for details.
// $Id$

//this script may only be included - so its better to die if called directly.
if (strpos($_SERVER["SCRIPT_NAME"], basename(__FILE__)) !== false) {
	header("location: index.php");
	exit;
}

/*
 * Smarty plugin
 * -------------------------------------------------------------
 * Type:     modifier
 * Name:     simplewiki
 * Purpose:  Simpler Tiki syntax parse
 * -------------------------------------------------------------
 */
function smarty_modifier_simplewiki($string)
{
	$parserlib = TikiLib::lib('parser');

	$string = htmlentities($string, ENT_QUOTES, 'UTF-8'); // Surely this should not be done here, if it is necessary. Chealer 2017-12-29
	return $parserlib->parse_data_simple($string);
}
