<?php
// (c) Copyright 2002-2016 by authors of the Tiki Wiki CMS Groupware Project
//
// All Rights Reserved. See copyright.txt for details and a complete list of authors.
// Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See license.txt for details.
// $Id$

namespace Tiki\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\FormatterHelper;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class IndexRebuildCommand extends Command
{
	protected function configure()
	{
		$this
			->setName('index:rebuild')
			->setDescription('Fully rebuild the unified search index')
			->addOption(
				'force',
				null,
				InputOption::VALUE_NONE,
				'Destroy failed indexes prior to rebuild'
			)
			->addOption(
				'log',
				null,
				InputOption::VALUE_NONE,
				'Generate a log of the indexed documents, useful to track down failures or memory issues'
			)
			->addOption(
				'cron',
				null,
				InputOption::VALUE_NONE,
				'Only output error messages'
			);
	}

	protected function execute(InputInterface $input, OutputInterface $output)
	{
		global $num_queries;
		global $prefs;

		$force = $input->getOption('force');
		if ($input->getOption('log')) {
			$log = 2;
		} else {
			$log = 0;
		}
		$cron = $input->getOption('cron');

		$unifiedsearchlib = \TikiLib::lib('unifiedsearch');

		if ($force && $unifiedsearchlib->rebuildInProgress()) {
			if (! $cron) {
				$output->writeln('<info>Removing leftovers...</info>');
			}
			$unifiedsearchlib->stopRebuild();
		}

		if (! $cron) {
			$message = '[' . \TikiLib::lib('tiki')->get_short_datetime(0) . '] Started rebuilding index...';
			if ($log) {
				$message .= ' logging to file: ' . $unifiedsearchlib->getLogFilename($log);
			}
			$output->writeln($message);
		}

		if (! $cron) {
			list($engine, $version) = $unifiedsearchlib->getEngineAndVersion();
			if (! empty($engine)) {
				$engineMessage = 'Unified search engine: ' . $engine;
				if (! empty($version)) {
					$engineMessage .= ', version ' . $version;
				}
				$output->writeln($engineMessage);
			}
		}

		$timer = new \timer();
		$timer->start();

		$memory_peak_usage_before = memory_get_peak_usage();

		$num_queries_before = $num_queries;

		// Apply 'Search index rebuild memory limit' setting if available
		if (! empty($prefs['allocate_memory_unified_rebuild'])) {
			$memory_limiter = new \Tiki_MemoryLimit($prefs['allocate_memory_unified_rebuild']);
		}

		$result = $unifiedsearchlib->rebuild($log);

		// Also rebuild admin index
		\TikiLib::lib('prefs')->rebuildIndex();

		// Back up original memory limit if possible
		if (isset($memory_limiter)) {
			unset($memory_limiter);
		}

		$errors = \Feedback::get();
		if (is_array($errors)) {
			foreach ($errors as $type => $message) {
				if (is_array($message)) {
					if (is_array($message[0]) && ! empty($message[0]['mes'])) {
						$type = $message[0]['type'];
						$message = $type . ': ' . str_replace('<br />', "\n", $message[0]['mes'][0]);
					} elseif (! empty($message['mes'])) {
						$message = $type . ': ' . str_replace('<br />', "\n", $message['mes']);
					}
					if ($type === 'success' || $type === 'note') {
						$type = 'info';
					} else if ($type === 'warning') {
						$type = 'comment';
					}
					if (! $cron || $type === 'error') {
						$output->writeln("<$type>$message</$type>");
					}
				} else {
					$output->writeln("<error>$message</error>");
				}
			}
		}

		$queries_after = $num_queries;

		if ($result) {
			if (! $cron) {
				$output->writeln("Indexed");
				foreach ($result as $key => $val) {
					$output->writeln("  $key: $val");
				}
				$output->writeln('Rebuilding index done');
				$output->writeln('Execution time: ' . FormatterHelper::formatTime($timer->stop()));
				$output->writeln('Current Memory usage: ' . FormatterHelper::formatMemory(memory_get_usage()));
				$output->writeln('Memory peak usage before indexing: ' . FormatterHelper::formatMemory($memory_peak_usage_before));
				$output->writeln('Memory peak usage after indexing: ' . FormatterHelper::formatMemory(memory_get_peak_usage()));
				$output->writeln('Number of queries: ' . ($queries_after - $num_queries_before));
			}
			return(0);
		} else {
			$output->writeln("\n<error>Search index rebuild failed. Last messages shown above.</error>");
			\TikiLib::lib('logs')->add_action('rebuild indexes', 'Search index rebuild failed.', 'system');
			return(1);
		}
	}
}
