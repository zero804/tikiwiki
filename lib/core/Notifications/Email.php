<?php
// (c) Copyright by authors of the Tiki Wiki CMS Groupware Project
//
// All Rights Reserved. See copyright.txt for details and a complete list of authors.
// Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See license.txt for details.
// $Id$

namespace Tiki\Notifications;

require_once 'lib/notifications/notificationemaillib.php';

class Email
{
	/**
	 * Send email notification to tiki admins regarding scheduler run status (stalled/healed)
	 *
	 * @param string 			$subjectTpl	Email subject template file path
	 * @param string 			$txtTpl		Email body template file path
	 * @param \Scheduler_Item	$scheduler	The scheduler that if being notified about.
	 *
	 * @return int The number of sent emails
	 */
	public static function sendSchedulerNotification($subjectTpl, $txtTpl, $scheduler)
	{
		global $prefs;

		$smarty = \TikiLib::lib('smarty');
		$smarty->assign('schedulerName', $scheduler->name);
		$smarty->assign('stalledTimeout', $prefs['scheduler_stalled_timeout']);

		// Need to fetch users with email address listed
		$userlib = \TikiLib::lib('user');
		$adminUsers = $userlib->get_group_users('Admins', 0, -1, '*');

		return sendEmailNotification($adminUsers, null, $subjectTpl, null, $txtTpl);
	}
}
