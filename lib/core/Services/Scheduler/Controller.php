<?php
// (c) Copyright 2002-2016 by authors of the Tiki Wiki CMS Groupware Project
//
// All Rights Reserved. See copyright.txt for details and a complete list of authors.
// Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See license.txt for details.
// $Id$

class Services_Scheduler_Controller
{

	/**
	 * @var SchedulersLib
	 */
	private $lib;

	/**
	 * @var TikiAccessLib
	 */
	private $access;

	public function setUp()
	{
		$this->lib = TikiLib::lib('scheduler');
		$this->access = TikiLib::lib('access');
	}


	/**
	 * Admin user "perform with checked" action to remove selected users
	 *
	 * @param $input JitFilter
	 * @return array
	 * @throws Services_Exception
	 * @throws Services_Exception_BadRequest
	 * @throws Services_Exception_Denied
	 * @throws Services_Exception_NotFound
	 */
	public function action_remove($input)
	{
		Services_Exception_Denied::checkGlobal('admin_users');

		$schedulerId = $input->schedulerId->int();
		$confirm = $input->confirm->int();

		$scheduler = $this->lib->get_scheduler($schedulerId);

		if (! $scheduler) {
			throw new Services_Exception_NotFound;
		}

		if ($confirm) {
			$this->lib->remove_scheduler($schedulerId);

			return [
				'schedulerId' => 0,
			];
		}

		return [
			'schedulerId' => $schedulerId,
			'name' => $scheduler['name'],
		];
	}

	/**
	 * Execute one scheduler from the admin interface
	 *
	 * @param $input
	 * @return array
	 * @throws Services_Exception_Denied
	 * @throws Services_Exception_NotFound
	 */
	public function action_run($input)
	{
		Services_Exception_Denied::checkGlobal('admin_users');

		$schedulerId = $input->schedulerId->int();

		$scheduler = $this->lib->get_scheduler($schedulerId);

		if (! $scheduler) {
			throw new Services_Exception_NotFound;
		}

		$logger = new Tiki_Log('Webcron', \Psr\Log\LogLevel::ERROR);
		$schedulerTask = Scheduler_Item::fromArray($scheduler, $logger);

		$message = tr('Execution output:') . '<br><br>';
		$result = $schedulerTask->execute(true);

		if ($result['status'] == 'failed') {
			$message .= tr('Scheduler %0 - FAILED', $schedulerTask->name) . '<br>' . $result['message'];
		} else {
			$message .= tr('Scheduler %0 - OK', $schedulerTask->name) . '<br>';
			$message .= $result['message'];
		}

		return [
			'title' => tr('Running %0', $schedulerTask->name),
			'schedulerId' => $schedulerId,
			'name' => $scheduler['name'],
			'message' => $message,
		];
	}

	/**
	 * Reset a running scheduler (if it's stucked for a unknown reason)
	 *
	 * @param $input
	 * @return array
	 * @throws Services_Exception_Denied
	 */
	public function action_reset($input)
	{
		global $user;

		Services_Exception_Denied::checkGlobal('admin_users');

		$schedulerId = $input->schedulerId->int();
		$confirm = $input->confirm->int();

		$scheduler = $this->lib->get_scheduler($schedulerId);

		if (! $scheduler) {
			throw new Services_Exception_NotFound;
		}

		if ($confirm) {
			$logger = new Tiki_Log('Webcron', \Psr\Log\LogLevel::ERROR);
			$item = Scheduler_Item::fromArray($scheduler, $logger);
			$item->heal('Reset by ' . $user, false, true);

			return [
				'schedulerId' => 0
			];
		}

		return [
			'title' => tr('Reset scheduler run?'),
			'schedulerId' => $schedulerId,
		];
	}
}
