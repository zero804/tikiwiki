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
 * In r69140 the file was added without "_tiki", this allows the script to be run also when the script without _tiki was run already
 *
 * @param Installer $installer
 */
function upgrade_20190218_add_back_article_hash_column_tiki($installer)
{
	if (empty($installer->query("SHOW COLUMNS FROM `tiki_articles` LIKE 'hash';")->result)) {
		$installer->query("ALTER TABLE `tiki_articles` ADD COLUMN `hash` VARCHAR(32) DEFAULT NULL AFTER `body`;");
	}
}
