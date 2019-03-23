<?php
// (c) Copyright by authors of the Tiki Wiki CMS Groupware Project
//
// All Rights Reserved. See copyright.txt for details and a complete list of authors.
// Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See license.txt for details.
// $Id$

if (strpos($_SERVER["SCRIPT_NAME"], basename(__FILE__)) !== false) {
	header("location: index.php");
	exit;
}

/**
 * @param $installer
 */
function upgrade_20190217_change_tiki_queue_tiki($installer)
{
	$query = <<<SQL
		ALTER TABLE `tiki_queue` CHANGE `handler` `handler` varchar(64) NULL;
SQL;

	$installer->query($query);
}