<?php
// (c) Copyright 2002-2014 by authors of the Tiki Wiki CMS Groupware Project
//
// All Rights Reserved. See copyright.txt for details and a complete list of authors.
// Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See license.txt for details.
// $Id: ProfileForgetCommand.php 45724 2013-04-26 17:33:23Z changi67 $

namespace Tiki\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

use TikiDb;

class ProfileBaselineCommand extends Command
{
	protected function configure()
	{
		$this
			->setName('profile:baseline')
			->setDescription('Generate the SQL patch to assign profile symbols for an existing installation.')
			->addArgument(
				'repository',
				InputArgument::OPTIONAL,
				'Repository',
				'file://profiles'
			)
			->addArgument(
				'profile',
				InputArgument::OPTIONAL,
				'Repository',
				'Baseline'
			)
			->addOption(
				'categories',
				null,
				InputOption::VALUE_NONE,
				'Include categories'
			)
			->addOption(
				'file-galleries',
				null,
				InputOption::VALUE_NONE,
				'Include file galleries'
			)
			->addOption(
				'trackers',
				null,
				InputOption::VALUE_NONE,
				'Include trackers'
			)
			->addOption(
				'tracker-fields',
				null,
				InputOption::VALUE_NONE,
				'Include tracker fields'
			)
			;
	}

	protected function execute(InputInterface $input, OutputInterface $output)
	{
		$profile = $input->getArgument('profile');
		$repository = $input->getArgument('repository');

		$db = TikiDb::get();
		$writer = function ($type, $id, $name) use ($output, $db, $profile, $repository) {
			$name = $db->qstr($this->generateSymbol($type, $name));

			$profile = $db->qstr($profile);
			$repository = $db->qstr($repository);
			$type = $db->qstr($type);
			$id = $db->qstr($id);

			$output->writeln("REPLACE INTO `tiki_profile_symbols` (`domain`, `profile`, `object`, `type`, `value`, `named`) VALUES ($repository, $profile, $name, $type, $id, 'y');");
		};

		$output->writeln("-- This file was automatically generated by the profile baseline generator");
		$output->writeln("-- Date: " . date('Y-m-d H:i:s'));

		if ($input->getOption('categories')) {
			$output->writeln("");
			$output->writeln("-- Categories");
			$table = $db->table('tiki_categories');
			foreach ($table->fetchMap('categId', 'name', []) as $id => $name) {
				$writer('category', $id, $name);
			}
		}

		if ($input->getOption('file-galleries')) {
			$output->writeln("");
			$output->writeln("-- File Galleries");
			$table = $db->table('tiki_file_galleries');
			foreach ($table->fetchMap('galleryId', 'name', []) as $id => $name) {
				$writer('file_gallery', $id, $name);
			}
		}

		if ($input->getOption('trackers')) {
			$output->writeln("");
			$output->writeln("-- Trackers");
			$table = $db->table('tiki_trackers');
			foreach ($table->fetchMap('trackerId', 'name', []) as $id => $name) {
				$writer('tracker', $id, $name);
			}
		}

		if ($input->getOption('tracker-fields')) {
			$output->writeln("");
			$output->writeln("-- Tracker Fields");
			$table = $db->table('tiki_tracker_fields');
			foreach ($table->fetchMap('fieldId', 'name', []) as $id => $name) {
				$writer('tracker_field', $id, $name);
			}
		}

		$output->writeln("");
		$output->writeln("-- Dump completed");
	}

	private function generateSymbol($type, $name)
	{
		static $memory = [];

		$basename = preg_replace('/\W+/', '_', strtolower($name));
		$candidate = $type . '_' . $basename;

		if (! isset($memory[$candidate])) {
			$memory[$candidate] = 0;
			return $candidate;
		} else {
			return $candidate . '_' . ++$memory[$candidate];
		}
	}
}
