<?php
// (c) Copyright 2002-2013 by authors of the Tiki Wiki CMS Groupware Project
// 
// All Rights Reserved. See copyright.txt for details and a complete list of authors.
// Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See license.txt for details.
// $Id$

require_once('lib/wizard/wizard.php');
include_once ('lib/userprefs/userprefslib.php');

/**
 * Set up the wysiwyg editor, including inline editing
 */
class UserWizardPreferencesNotifications extends Wizard 
{
	function isEditable ()
	{
		return true;
	}

	function onSetupPage ($homepageUrl) 
	{
		global	$smarty;

		// Run the parent first
		parent::onSetupPage($homepageUrl);
		
		// Show if option is selected
		if ($prefs['feature_user_watches'] === 'y') {
			$showPage = true;
		}

		// Assign the page template
		$wizardTemplate = 'wizard/user_preferences_notifications.tpl';
		$smarty->assign('wizardBody', $wizardTemplate);
		
		return true;		
	}

	function onContinue ($homepageUrl) 
	{
		global $tikilib;
		
		// Run the parent first
		parent::onContinue($homepageUrl);
		
		if (isset($_REQUEST['user_calendar_watch_editor']) && $_REQUEST['user_calendar_watch_editor'] == true) {
			$tikilib->set_user_preference($user, 'user_calendar_watch_editor', 'y');
		} else {
			$tikilib->set_user_preference($user, 'user_calendar_watch_editor', 'n');
		}
		
		if (isset($_REQUEST['user_article_watch_editor']) && $_REQUEST['user_article_watch_editor'] == true) {
			$tikilib->set_user_preference($user, 'user_article_watch_editor', 'y');
		} else {
			$tikilib->set_user_preference($user, 'user_article_watch_editor', 'n');
		}
		
		if (isset($_REQUEST['user_wiki_watch_editor']) && $_REQUEST['user_wiki_watch_editor'] == true) {
			$tikilib->set_user_preference($user, 'user_wiki_watch_editor', 'y');
		} else {
			$tikilib->set_user_preference($user, 'user_wiki_watch_editor', 'n');
		}
		
		if (isset($_REQUEST['user_blog_watch_editor']) && $_REQUEST['user_blog_watch_editor'] == true) {
			$tikilib->set_user_preference($user, 'user_blog_watch_editor', 'y');
		} else {
			$tikilib->set_user_preference($user, 'user_blog_watch_editor', 'n');
		}
		
		if (isset($_REQUEST['user_tracker_watch_editor']) && $_REQUEST['user_tracker_watch_editor'] == true) {
			$tikilib->set_user_preference($user, 'user_tracker_watch_editor', 'y');
		} else {
			$tikilib->set_user_preference($user, 'user_tracker_watch_editor', 'n');
		}
		
		if (isset($_REQUEST['user_comment_watch_editor']) && $_REQUEST['user_comment_watch_editor'] == true) {
			$tikilib->set_user_preference($user, 'user_comment_watch_editor', 'y');
		} else {
			$tikilib->set_user_preference($user, 'user_comment_watch_editor', 'n');
		}

	}
}
