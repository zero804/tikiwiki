<?php
// (c) Copyright 2002-2013 by authors of the Tiki Wiki CMS Groupware Project
// 
// All Rights Reserved. See copyright.txt for details and a complete list of authors.
// Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See license.txt for details.
// $Id$

/*
 * The following section ensures that when a KILL signal is received, PHP will
 * exit cleanly and trigger the shutdown functions.
 */
declare(ticks = 1); // how often to check for signals

if (function_exists('pcntl_signal')) {
	$exit = function () {
		exit;
	};

	pcntl_signal(SIGTERM, $exit);
	pcntl_signal(SIGHUP,  $exit);
	pcntl_signal(SIGINT, $exit);
}


if ( isset($_SERVER['REQUEST_METHOD']) ) die;

if ( !isset( $_SERVER['argv'][1] ) || !in_array($_SERVER['argv'][1], array('rebuild','process','optimize')))
	die( 'Usage: php lib/search/shell.php command [option]
	Where command [option] can be:
		rebuild [log]
		process [integer (default 10)]
		optimize
	Returns an error code (1) if search is already being rebuilt
	N.B. Needs to be run as the "apache" user, e.g. > "sudo -u www-data php lib/search/shell.php process 20"
' );

if ( ! file_exists('db/local.php') )
	die( "Tiki is not installed yet.\n" );

require_once('tiki-setup.php');

include_once 'lib/core/Zend/Log/Writer/Syslog.php';
$log_level = Zend_Log::INFO;
$writer = new Zend_Log_Writer_Stream('php://output');
$writer->addFilter((int) $log_level);
$logger = new Zend_Log($writer);

$logger->debug('Running search shell utility');

global $unifiedsearchlib;
require_once 'lib/search/searchlib-unified.php';

if ($unifiedsearchlib->rebuildInProgress()) {
	$logger->err('Rebuild in progress - exiting.');
	exit(1);
}

if ( $_SERVER['argv'][1] === 'process' ) {

	$queueCount = $unifiedsearchlib->getQueueCount();

	if ($queueCount > 0) {

		$toProcess = (isset($_SERVER['argv'][2]) && is_numeric($_SERVER['argv'][2])) ? $_SERVER['argv'][2] : 10;
		$toProcess = min($queueCount, $toProcess);

		try {
			$logger->debug('Started processing queue...');
			ob_flush();
			$unifiedsearchlib->processUpdateQueue($toProcess);
			$logger->info('Processed queue');
			ob_flush();

		} catch (Zend_Search_Lucene_Exception $e) {

			$msg = tr('Search index could not be updated: %0', $e->getMessage());

			$errlib = TikiLib::lib('errorreport');
			$errlib->report($msg);
		}

		$msgs = TikiLib::lib('errorreport')->get_errors();
		if (count($msgs)) {
			$logger->err("Problem processing $toProcess items.\n");
			$logger->err(implode("\n", str_replace('<br />', "\n", $msgs)));
			ob_flush();
		} else {
			$logger->info("Processed $toProcess items, {$unifiedsearchlib->getQueueCount()} remaining.\n");
			ob_flush();
		}
	}

} else if ( $_SERVER['argv'][1] === 'rebuild' ) {

	$loggit = (isset($_SERVER['argv'][2]) && $_SERVER['argv'][2] === 'log');

	$logger->debug('Started rebuilding index...');
	ob_flush();
	$unifiedsearchlib->rebuild($loggit);
	$logger->info('Rebuilding index done');
	ob_flush();

} else if ( $_SERVER['argv'][1] === 'optimize' ) {

	$logger->info('Started optimizing index...');
	ob_flush();
	$stat = $unifiedsearchlib->getIndex()->optimize();
	$logger->info("Optimizing index done\n");
	ob_flush();

}
