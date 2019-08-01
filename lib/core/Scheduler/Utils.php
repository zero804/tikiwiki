<?php

class Scheduler_Utils
{

	/**
	 * Checks if a cron should run at a time.
	 *
	 * @param string|\DateTime $time Relative calculation date
	 * @param $cron string A cron time expression (ex.: 0 0 * * *)
	 * @return bool true if should run, false otherwise.
	 * @throws \Scheduler\Exception\CrontimeFormatException
	 */
	public static function is_time_cron($time, $cron)
	{
		if (! self::validate_cron_time_format($cron)) {
			throw new Scheduler\Exception\CrontimeFormatException(tra('Invalid cron time format'));
		}

		$cronEx = Cron\CronExpression::factory($cron);
		return $cronEx->isDue($time);
	}

	/**
	 * Validate a cron time string
	 *
	 * @param $cron string A cron time expression (ex.: 0 0 * * *)
	 * @return bool true if valid, false otherwise
	 */
	public static function validate_cron_time_format($cron)
	{
		return Cron\CronExpression::isValidExpression($cron);
	}

	/**
	 * Get previous run date.
	 *
	 * @param $cron string A cron time expression (ex.: 0 0 * * *)
	 * @return number timestamp in seconds.
	 * @throws \Scheduler\Exception\CrontimeFormatException
	 */
	public static function get_previous_run_date($cron)
	{
		if (! self::validate_cron_time_format($cron)) {
			throw new Scheduler\Exception\CrontimeFormatException(tra('Invalid cron time format'));
		}
		$cron = Cron\CronExpression::factory($cron);
		return $cron->getPreviousRunDate()->getTimestamp();
	}
}
