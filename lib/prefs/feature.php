<?php
// (c) Copyright 2002-2010 by authors of the Tiki Wiki/CMS/Groupware Project
// 
// All Rights Reserved. See copyright.txt for details and a complete list of authors.
// Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See license.txt for details.
// $Id$

function prefs_feature_list() {

	global $prefs;
	
	$catree = array();

	if ($prefs['feature_categories'] == 'y') {
		global $categlib;

		include_once ('lib/categories/categlib.php');
		$all_categs = $categlib->get_all_categories();

		$catree['-1'] = tra('None');
		$catree['0'] = tra('All');

		foreach ($all_categs as $categ) {
			$catree[$categ['categId']] = $categ['categpath'];
		}
	}

	return array(
		'feature_blog_mandatory_category' => array(
			'name' => tra('Force and limit categorization to within subtree of'),
			'type' => 'list',
			'options' => $catree,
			'dependencies' => array(
				'feature_categories',
			),
		),
		'feature_wiki' => array(
			'name' => tra('Wiki'),
			'description' => tra('Collaboratively authored documents with history of changes.'),
			'type' => 'flag',
			'help' => 'Wiki',
		),
		'feature_blogs' => array(
			'name' => tra('Blog'),
			'description' => tra('Online diaries or journals.'),
			'type' => 'flag',
			'help' => 'Blogs',
		),
		'feature_galleries' => array(
			'name' => tra('Image Gallery'),
			'description' => tra('Collections of graphic images for viewing or downloading (photo album)'),
			'warning' => tra('You can use file galleries instead.'),
			'type' => 'flag',
			'help' => 'Image+Gallery',
		),
		'feature_machine_translation' => array(
			'name' => tra('Machine Translation (by Google Translate)'),
			'description' => tra('Uses Google Translate to translate the content of wiki pages to other languages.'),
			'help' => 'Machine+Translation',
			'warning' => tra('Experimental. This feature is still under development.'),
			'type' => 'flag',
		),	
		'feature_trackers' => array(
			'name' => tra('Trackers'),
			'description' => tra('Database & form generator'),
			'help' => 'Trackers',
			'type' => 'flag',
			'keywords' => 'CRUD',
		),
		'feature_forums' => array(
			'name' => tra('Forums'),
			'description' => tra('Online discussions on a variety of topics. Threaded or flat.'),
			'help' => 'Forums',
			'type' => 'flag',
		),
		'feature_file_galleries' => array(
			'name' => tra('File Gallery'),
			'description' => tra('Computer files, videos or software for downloading. With check-in & check-out (lock)'),
			'help' => 'File+Gallery',
			'type' => 'flag',
		),
		'feature_articles' => array(
			'name' => tra('Articles'),
			'description' => tra('Articles can be used for date-specific news and announcements. You can configure articles to automatically publish and expire at specific times or to require that submissions be approved before becoming "live."'),
			'help' => 'Article',
			'type' => 'flag',
		),
		'feature_polls' => array(
			'name' => tra('Polls'),
			'description' => tra('Brief list of votable options; appears in module (left or right column)'),
			'help' => 'Poll',
			'type' => 'flag',
		),
		'feature_newsletters' => array(
			'name' => tra('Newsletters'),
			'description' => tra('Content mailed to registered users.'),
			'help' => 'Newsletters',
			'type' => 'flag',
		),
		'feature_calendar' => array(
			'name' => tra('Calendar'),
			'description' => tra('Events calendar with public, private and group channels.'),
			'help' => 'Calendar',
			'type' => 'flag',
		),
		'feature_banners' => array(
			'name' => tra('Banners'),
			'description' => tra('Insert, track, and manage advertising banners.'),
			'help' => 'Banners',
			'type' => 'flag',
		),
		'feature_categories' => array(
			'name' => tra('Category'),
			'description' => tra('Global category system. Items of different types (wiki pages, articles, tracker items, etc) can be added to one or many categories. Categories can have permissions.'),
			'help' => 'Category',
			'type' => 'flag',
		),
		'feature_score' => array(
			'name' => tra('Score'),
			'description' => tra('Score is a game to motivate participants to increase their contribution by comparing to other users.'),
			'help' => 'Score',
			'type' => 'flag',
		),
		'feature_search' => array(
			'name' => tra('Tiki-indexed Search'),
			'description' => tra('Enables searching for content on the website, using Tiki-managed index.'),
			'hint' => tra('Unless you have a reason to, you should use MySQL Full-Text Search feature instead.'),
			'help' => 'Search',
			'type' => 'flag',
		),
		'feature_freetags' => array(
			'name' => tra('Freetags'),
			'description' => tra('Allows to set tags on pages and various objects within the website and generate tag cloud navigation patterns.'),
			'help' => 'Tags',
			'type' => 'flag',
		),
		'feature_actionlog' => array(
			'name' => tra('Action Log'),
			'description' => tra('Allows to keep track of what users are doing and produce reports on a per-user or per-category basis.'),
			'help' => 'Action+Log',
			'type' => 'flag',
		),
		'feature_contribution' => array(
			'name' => tra('Contribution'),
			'description' => tra('Allows users to specify the type of contribution they are making while editing objects. The contributions are then displayed as color-coded in history and other reports.'),
			'help' => 'Contribution',
			'type' => 'flag',
		),
		'feature_multilingual' => array(
			'name' => tra('Multilingual'),
			'description' => tra('Enables internationalization features and multilingual support for then entire site.'),
			'help' => 'Internationalization',
			'type' => 'flag',
		),
		'feature_faqs' => array(
			'name' => tra('FAQ'),
			'description' => tra('Frequently asked questions and answers'),
			'warning' => tra('You can use wiki pages instead.'),
			'help' => 'FAQ',
			'type' => 'flag',
		),
		'feature_surveys' => array(
			'name' => tra('Surveys'),
			'description' => tra('Questionnaire with multiple choice or open ended question'),
			'help' => 'Surveys',
			'type' => 'flag',
		),
		'feature_directory' => array(
			'name' => tra('Directory'),
			'description' => tra('User-submitted Web links'),
			'help' => 'Directory',
			'type' => 'flag',
		),
		'feature_quizzes' => array(
			'name' => tra('Quizzes'),
			'description' => tra('Timed questionnaire with recorded scores.'),
			'help' => 'Quizzes',
			'type' => 'flag',
		),
		'feature_featuredLinks' => array(
			'name' => tra('Featured links'),
			'description' => tra('Simple menu system which can optionally add an external web page in an iframe'),
			'help' => 'Featured+links',
			'type' => 'flag',
		),
		'feature_copyright' => array(
			'name' => tra('Copyright'),
			'description' => tra('The Copyright Management System (or ©MS) is a way of licensing your content'),
			'help' => 'Copyright',
			'type' => 'flag',
		),
		'feature_shoutbox' => array(
			'name' => tra('Shoutbox'),
			'description' => tra('Quick comment (graffiti) box. Like a group chat, but not in real time.'),
			'help' => 'Shoutbox',
			'type' => 'flag',
		),
		'feature_maps' => array(
			'name' => tra('Maps'),
			'description' => tra('Navigable, interactive maps with user-selectable layers'),
			'help' => 'Maps',
			'warning' => tra('Requires mapserver'),
			'type' => 'flag',
		),
		'feature_gmap' => array(
			'name' => tra('Google Maps'),
			'description' => tra('Interactive use of Google Maps'),
			'help' => 'GMap',
			'type' => 'flag',
		),
		'feature_live_support' => array(
			'name' => tra('Live support system'),
			'description' => tra('One-on-one chatting with customer'),
			'help' => 'Live+Support',
			'type' => 'flag',
		),
		'feature_tell_a_friend' => array(
			'name' => tra('Tell a Friend'),
			'description' => tra('Add a link "Email this page" in all the pages'),
			'help' => 'Tell+a+Friend',
			'type' => 'flag',
		),
		'feature_share' => array(
			'name' => tra('Share'),
			'description' => tra('Add a "Share" link in all pages to send it via e-mail, Twitter, Facebook, message or forums'),
			'help' => 'Share',
			'type' => 'flag',
		),
		'feature_html_pages' => array(
			'name' => tra('HTML pages'),
			'description' => tra('Static and dynamic HTML content'),
			'help' => 'HTML+Pages',
			'warning' => tra('HTML can be used in wiki pages. This is a separate feature.'),
			'type' => 'flag',
		),
		'feature_contact' => array(
			'name' => tra('Contact Us'),
			'description' => tra('Basic form from visitor to admin'),
			'help' => 'Contact+us',
			'type' => 'flag',
		),
		'feature_minichat' => array(
			'name' => tra('Minichat'),
			'description' => tra('Real-time group text chatting'),
			'help' => 'Minichat',
			'type' => 'flag',
		),
		'feature_comments_moderation' => array(
			'name' => tra('Comments Moderation'),
			'description' => tra('An admin must validate a comment before it is visible'),
			'help' => 'Comments',
			'type' => 'flag',
		),
		'feature_comments_locking' => array(
			'name' => tra('Comments Locking'),
			'description' => tra('Comments can be closed (no new comments)'),
			'help' => 'Comments',
			'type' => 'flag',
		),
		'feature_comments_post_as_anonymous' => array(
			'name' => tra('Allow posting of comments as Anonymous'),
			'description' => tra('Permit anonymous visitors to add a comment without needing to create an account'),
			'help' => 'Comments',
			'type' => 'flag',
		),
		'feature_wiki_description' => array(
			'name' => tra('Display page description'),
			'description' => tra('Display the page description below the heading when viewing the page.'),
			'type' => 'flag',
		),
		'feature_page_title' => array(
			'name' => tra('Display page name as page title'),
			'description' => tra('Display the page name at the top of each page as page title. If not enabled, the page content should be structured to contain a header.'),
			'type' => 'flag',
		),
		'feature_wiki_pageid' => array(
			'name' => tra('Display page ID'),
			'description' => tra('Display the internal page ID when viewing the page.'),
			'type' => 'flag',
		),
		'feature_wiki_icache' => array(
			'name' => tra('Individual wiki cache'),
			'description' => tra('Allow users to change the duration of the cache on a per-page basis.'),
			'type' => 'flag',
		),
		'feature_jscalendar' => array(
			'name' => tra('JS Calendar'),
			'description' => tra('JavaScript popup date selector.'),
			'help' => 'JS+Calendar',
			'type' => 'flag',
		),
		'feature_phplayers' => array(
			'name' => tra('PHPLayers'),
			'description' => tra('PhpLayers Dynamic menus.'),
			'help' => 'http://themes.tiki.org/PhpLayersMenu',
			'type' => 'flag',
			'warning' => tra('Will eventually be removed from Tiki. Use CSS menus instead.'),
		),
		'feature_htmlpurifier_output' => array(
			'name' => tra('Output should be HTML Purified'),
			'description' => tra('This enables HTML Purifier on outputs to filter potential remaining security problems like XSS.'),
			'help' => 'Purifier',
			'warning' => tra('Experimental. This feature is still under development.'),
			'type' => 'flag',
			'perspective' => false,
		),
		'feature_fullscreen' => array(
			'name' => tra('Full Screen'),
			'description' => tra('Allow users to activate fullscreen mode.'),
			'help' => 'Fullscreen',
			'type' => 'flag',
		),
		'feature_cssmenus' => array(
			'name' => tra('Css Menus'),
			'description' => tra('Css Menus (suckerfish).'),
			'help' => 'Menus',
			'type' => 'flag',
		),
		'feature_shadowbox' => array(
			'name' => tra('Shadowbox / ColorBox'),
			'description' => tra('"Displaying content with Eye Candy". <br />Uses jQuery plugin "ColorBox". e.g. <code>{img fileId="42" thumb="y" alt="" rel="box[g]"}</code>'),
			'help' => 'Shadowbox',
			'type' => 'flag',
		),
		'feature_quick_object_perms' => array(
			'name' => tra('Quick Permission Assignment'),
			'description' => tra('Quickperms allow to define classes of privileges and grant them to roles on objects.'),
			'help' => 'Quickperms',
			'type' => 'flag',
		),
		'feature_purifier' => array(
			'name' => tra('HTML Purifier'),
			'description' => tra("HTML Purifier is a standards-compliant HTML filter library written in PHP and integrated in Tiki. HTML Purifier will not only remove all malicious code (better known as XSS) with a thoroughly audited, secure yet permissive whitelist, it will also make sure your documents are standards compliant, something only achievable with a comprehensive knowledge of W3C's specifications."),
			'hint' => tra('If you are trying to use HTML in your pages and it gets stripped out, you should make sure your HTML is valid or de-activate this feature.'),
			'help' => 'Purifier',
			'type' => 'flag',
			'perspective' => false,
		),
		'feature_ajax' => array(
			'name' => tra('Ajax'),
			'description' => tra('Ajax'),
			'help' => 'Ajax',
			'type' => 'flag',
		),
		'feature_mobile' => array(
			'name' => tra('Mobile'),
			'description' => tra('Outputs a WAP and VoiceXML version.'),
			'help' => 'http://mobile.tiki.org',
			'type' => 'flag',
		),
		'feature_morcego' => array(
			'name' => tra('Morcego 3D browser'),
			'description' => tra('Visualize relationships between wiki pages, in a 3D applet'),
			'help' => 'Wiki+3D',
			'type' => 'flag',
		),
		'feature_webmail' => array(
			'name' => tra('Webmail'),
			'description' => tra('Webmail'),
			'help' => 'Webmail',
			'type' => 'flag',
		),
		'feature_intertiki' => array(
			'name' => tra('Intertiki'),
			'description' => tra('Allows several Tiki sites (slaves) to get authentication from a master Tiki site'),
			'help' => 'Intertiki',
			'perspective' => false,
			'type' => 'flag',
		),
		'feature_mailin' => array(
			'name' => tra('Mail-in'),
			'description' => tra('Populate wiki pages and articles by email'),
			'help' => 'Mail-in',
			'type' => 'flag',
		),
		'feature_wiki_mindmap' => array(
			'name' => tra('Mindmap'),
			'description' => tra('Mindmap'),
			'help' => 'MindMap',
			'type' => 'flag',
		),
		'feature_print_indexed' => array(
			'name' => tra('Print Indexed'),
			'description' => tra('Print Indexed'),
			'help' => 'Print+Indexed',
			'type' => 'flag',
		),
		'feature_sheet' => array(
			'name' => tra('SpreadSheet'),
			'description' => tra('Datasheets with calculations and charts'),
			'help' => 'SpreadSheet',
			'type' => 'flag',
			'keywords' => 'sheet calculation calculations stats stat graph graphs',
		),
		'feature_wysiwyg' => array(
			'name' => tra('Wysiwyg editor'),
			'description' => tra('WYSIWYG is an acronym for What You See Is What You Get. Uses CKEditor.'),
			'help' => 'Wysiwyg',
			'type' => 'flag',
		),
		'feature_wiki_save_draft' => array(
			'name' => tra('Save draft'),
			'warning' => tra('Experimental (Requires AJAX)'),
			'dependencies' => array(
				'feature_ajax',
			),
			'type' => 'flag',
			'dependencies' => array(
				'feature_ajax',
				'ajax_xajax',
      ),
		),	
		'feature_kaltura' => array(
			'name' => tra('Kaltura'),
			'description' => tra('Collaborative video editing'),
			'help' => 'Kaltura',
			'type' => 'flag',
		),
		'feature_friends' => array(
			'name' => tra('Friendship Network'),
			'description' => tra('Users can identify other users as their friends'),
			'warning' => tra('Neglected feature'),
			'help' => 'Friendship',
			'type' => 'flag',
		),	
		'feature_banning' => array(
			'name' => tra('Banning system'),
			'description' => tra('Banning system'),
			'help' => 'Banning',
			'type' => 'flag',
			'description' => tra('Deny access to specific users based on username, IP, and date/time range.')
		),
		'feature_stats' => array(
			'name' => tra('Stats'),
			'description' => tra('Record basic statistics about major Tiki features (number of wiki pages, size of file galleries, etc.)'),
			'help' => 'Stats',
			'type' => 'flag',
		),
		'feature_action_calendar' => array(
			'name' => tra('Action calendar'),
			'description' => tra('Action calendar'),
			'help' => 'Action+Calendar',
			'type' => 'flag',
		),
		'feature_referer_stats' => array(
			'name' => tra('Referer Stats'),
			'description' => tra('Record domain name of sites that send visitors to this Tiki.'),
			'help' => 'Stats',
			'type' => 'flag',
		),
		'feature_redirect_on_error' => array(
			'name' => tra('Redirect On Error'),
			'description' => tra('On error, goto the HomePage as configured in Admin->General.'),
			'help' => 'Redirect+On+Error',
			'type' => 'flag',
		),
		'feature_comm' => array(
			'name' => tra('Communications (send/receive objects)'),
			'description' => tra('Send/receive wiki pages and articles between Tiki-powered sites'),
			'help' => 'Communication+Center',
			'type' => 'flag',
		),
		'feature_custom_home' => array(
			'name' => tra('Custom Home'),
			'description' => tra('Custom Home'),
			'help' => 'Custom+Home',
			'type' => 'flag',
		),
		'feature_mytiki' => array(
			'name' => tra("Display 'MyTiki' in the application menu"),
			'description' => tra("Display 'MyTiki' in the application menu"),
			'help' => 'MyTiki',
			'type' => 'flag',
		),
		'feature_minical' => array(
			'name' => tra('Mini Calendar'),
			'description' => tra('Mini Calendar'),
			'help' => 'Calendar',
			'type' => 'flag',
		),
		'feature_userPreferences' => array(
			'name' => tra('User Preferences Screen'),
			'description' => tra('User Preferences Screen'),
			'help' => 'User+Preferences',
			'type' => 'flag',
		),
		'feature_notepad' => array(
			'name' => tra('User Notepad'),
			'description' => tra('User Notepad'),
			'help' => 'Notepad',
			'type' => 'flag',
		),
		'feature_user_bookmarks' => array(
			'name' => tra('User Bookmarks'),
			'description' => tra('User Bookmarks'),
			'help' => 'Bookmarks',
			'type' => 'flag',
		),
		'feature_contacts' => array(
			'name' => tra('User Contacts'),
			'description' => tra('User Contacts'),
			'help' => 'Contacts',
			'type' => 'flag',
		),
		'feature_user_watches' => array(
			'name' => tra('User Watches'),
			'description' => tra('User Watches'),
			'help' => 'User+Watches',
			'type' => 'flag',
		),
		'feature_group_watches' => array(
			'name' => tra('Group Watches'),
			'description' => tra('Group Watches'),
			'help' => 'Group+Watches',
			'type' => 'flag',
		),
		'feature_daily_report_watches' => array(
			'name' => tra('Daily Reports for User Watches'),
			'description' => tra('Daily Reports for User Watches'),
			'help' => 'Daily+Reports',
			'type' => 'flag',
		),
		'feature_user_watches_translations' => array(
			'name' => tra('User Watches Translations'),
			'description' => tra('User Watches Translations'),
			'help' => 'User+Watches',
			'type' => 'flag',
		),
		'feature_user_watches_languages' => array(
			'name' => tra('User Watches Languages'),
			'description' => tra('Watch language-specific changes within a category.'),
			'type' => 'flag',
		),
		'feature_usermenu' => array(
			'name' => tra('User Menu'),
			'description' => tra('User Menu'),
			'help' => 'User+Menu',
			'type' => 'flag',
		),
		'feature_tasks' => array(
			'name' => tra('User Tasks'),
			'description' => tra('User Tasks'),
			'help' => 'Task',
			'type' => 'flag',
		),
		'feature_messages' => array(
			'name' => tra('User Messages'),
			'description' => tra('User Messages'),
			'help' => 'Inter-user+Messages',
			'type' => 'flag',
		),
		'feature_userfiles' => array(
			'name' => tra('User Files'),
			'description' => tra('User Files'),
			'help' => 'User+Files',
			'type' => 'flag',
		),
		'feature_userlevels' => array(
			'name' => tra('User Levels'),
			'description' => tra('User Levels'),
			'help' => 'User+Levels',
			'type' => 'flag',
		),
		'feature_groupalert' => array(
			'name' => tra('Group Alert'),
			'description' => tra('Group Alert'),
			'help' => 'Group+Alert',
			'type' => 'flag',
		),
		'feature_integrator' => array(
			'name' => tra('Integrator'),
			'description' => tra('Integrator'),
			'help' => 'Integrator',
			'type' => 'flag',
		),
		'feature_xmlrpc' => array(
			'name' => tra('XMLRPC API'),
			'description' => tra('XMLRPC API'),
			'help' => 'Xmlrpc',
			'type' => 'flag',
		),
		'feature_debug_console' => array(
			'name' => tra('Debugger Console'),
			'description' => tra('Debugger Console'),
			'help' => 'Debugger+Console',
			'type' => 'flag',
			'perspective' => false,
		),
		'feature_tikitests' => array(
			'name' => tra('TikiTests'),
			'description' => tra('Permits recording and playback of functional tests'),
			'help' => 'TikiTests',
			'type' => 'flag',
		),
		'feature_version_checks' => array(
			'name' => tra('Check for updates automatically'),
			'description' => tra('Tiki will check for updates when you access the main Administration page'),
			'type' => 'flag',
			'perspective' => false,
		),
		'feature_pear_date' => array(
			'name' => tra('Use PEAR::Date library'),
			'description' => tra('Use PEAR::Date library'),
			'type' => 'flag',
			'perspective' => false,
		),
		'feature_ticketlib' => array(
			'name' => tra('Require confirmation if possible CSRF detected'),
			'description' => tra('Require confirmation if possible CSRF detected'),
			'type' => 'flag',
			'perspective' => false,
		),
		'feature_ticketlib2' => array(
			'name' => tra('Protect against CSRF with a ticket'),
			'description' => tra('Protect against CSRF with a ticket'),
			'type' => 'flag',
			'perspective' => false,
		),
		'feature_detect_language' => array(
			'name' => tra('Detect browser language'),
			'description' => tra('Lookup the user\'s preferred language through browser preferences.'),
			'type' => 'flag',
		),
		'feature_best_language' => array(
			'name' => tra('Show pages in user\'s preferred language'),
			'description' => tra('When accessing a page which has an equivalent in the user\'s preferred language, favor the translated page.'),
			'type' => 'flag',
			'dependencies' => array(
				'feature_userPreferences',
			),
		),
		'feature_sync_language' => array(
			'name' => tra('Changing the page language also changes the site language'),
			'type' => 'flag',
		),
		'feature_translation' => array(
			'name' => tra('Translation assistant'),
			'description' => tra('Track translation operations between pages.'),
			'help' => 'Translating+Tiki+content',
			'type' => 'flag',
		),
		'feature_urgent_translation' => array(
			'name' => tra('Urgent translation notifications'),
			'description' => tra('Allow to flag changes as urgent, leading translations to be marked with a notice visible to all users.'),
			'type' => 'flag',
		),
		'feature_urgent_translation_master_only' => array(
			'name' => tra('Only allow urgent translation from site language'),
			'description' => tra('Use the site language as a master language and prevent translations from sending critical updates.'),
			'type' => 'flag',
		),
		'feature_translation_incomplete_notice' => array(
			'name' => tra('Incomplete translation notice'),
			'description' => tra('When a page is translated to a new language, a notice will be automatically be inserted into the page to indicate that the translation is not yet complete.'),
			'type' => 'flag',
		),
		'feature_multilingual_structures' => array(
			'name' => tra('Multilingual structures'),
			'description' => tra('Structures to lookup equivalent pages in other languages. May cause performance problems on larger structures.'),
			'type' => 'flag',
			'dependencies' => array(
				'feature_wiki_structure',
				'feature_multilingual',
			),
		),
		'feature_multilingual_one_page' => array(
			'name' => tra('Display all languages in a single page'),
			'description' => tra('List all languages as a language option in the page language drop list to see all languages at once.'),
			'type' => 'flag',
		),
		'feature_obzip' => array(
			'name' => tra('GZip output'),
			'description' => tra('Compress your pages on-the-fly, if the requesting browser supports this'),
			'help' => 'Compression',
			'type' => 'flag',
			'perspective' => false,
		),
		'feature_help' => array(
			'name' => tra('Help System'),
			'description' => tra('Help System'),
			'help' => 'Documentation',
			'type' => 'flag',
		),
		'feature_display_my_to_others' => array(
			'name' => tra("Show user's contribution on the user information page"),
			'description' => tra("Show user's contribution on the user information page"),
			'help' => 'User+Preferences',
			'type' => 'flag',
		),
		'feature_babelfish' => array(
			'name' => tra('Translation URLs'),
			'description' => tra('Show clickable URLs to translate the page to another language using Babel Fish website.'),
			'type' => 'flag',
		),
		'feature_babelfish_logo' => array(
			'name' => tra('Translation icons'),
			'description' => tra('Show clickable icons to translate the page to another language using Babelfish website.'),
			'type' => 'flag',
		),
		'feature_smileys' => array(
			'name' => tra('Smileys'),
			'description' => tra('Also known as emoticons'),
			'help' => 'Smileys',
			'type' => 'flag',
		),
		'feature_dynamic_content' => array(
			'name' => tra('Dynamic Content System'),
			'description' => tra('Bloc of content which can be reused and programmed (timed)'),
			'help' => 'Dynamic+Content',
			'type' => 'flag',
		),
		'feature_filegals_manager' => array(
			'name' => tra('Use File Galleries to store pictures'),
			'type' => 'flag',
			'description' => tra('If disabled, pictures will be stored in ../img/wiki_up/.. instead.'),
		),
		'feature_wiki_ext_icon' => array(
			'name' => tra('External link icon'),
			'type' => 'flag',
			'description' => tra('External links will be identifed with an icon. Use the ../img/icons/external_link.gif image to customize the icon.')
		),
		'feature_wiki_ext_rel_nofollow' => array(
			'name' => tra('Add "rel=nofollow" on external links'),
			'description' => tra("nofollow is used to instruct some search engines that links should not influence search engines. It can reduce search engine spam and prevent 'spamdexing'"),
			'type' => 'flag',
			'keywords' => 'no follow spam',
		),
		'feature_semantic' => array(
			'name' => tra('Semantic links'),
			'description' => tra('Going beyond Backlinks, allows to define some semantic relationships between wiki pages'),
			'help' => 'Semantic',
			'type' => 'flag',
			'dependencies' => array(
				'feature_backlinks',
			),
		),
		'feature_webservices' => array(
			'name' => tra('Web Services'),
			'description' => tra('Can consume webservices in JSON or YAML'),
			'help' => 'WebServices',
			'type' => 'flag',
		),
		'feature_menusfolderstyle' => array(
			'name' => tra('Display menus as folders'),
			'type' => 'flag',
		),
		'feature_breadcrumbs' => array(
			'name' => tra('Breadcrumbs'),
			'description' => tra('Attempts to show you where you are'),
			'help' => 'Breadcrumbs',
			'warning' => tra('Neglected feature'),
			'type' => 'flag',
		),	
		'feature_antibot' => array(
			'name' => tra('Anonymous editors must enter anti-bot code (CAPTCHA)'),
			'help' => 'Spam+protection',
			'type' => 'flag',
		),	
		'feature_wiki_protect_email' => array(
			'name' => tra('Protect email against spam'),
			'help' => 'Spam+protection',
			'type' => 'flag',
		),	
		'feature_sitead' => array(
			'name' => tra('Activate'),
			'type' => 'flag',
		),	
		'feature_poll_anonymous' => array(
			'name' => tra('Anonymous voting'),
			'type' => 'flag',
		),	
		'feature_poll_revote' => array(
			'name' => tra('Allow re-voting'),
			'type' => 'flag',
		),	
		'feature_poll_comments' => array(
			'name' => tra('Comments for polls'),
			'type' => 'flag',
		),	
		'feature_faq_comments' => array(
			'name' => tra('Comments for FAQs'),
			'type' => 'flag',
		),	
		'feature_sefurl' => array(
			'name' => tra('Search engine friendly url'),
			'description' => tra('If you are using Apache, you can rename _htaccess to .htaccess to get Short URLs'),
			'help' => 'Clean+URLs',
			'perspective' => false,
			'type' => 'flag',
			'keywords' => 'sefurl sefurls seo rewrite rules short urls',
			'dependencies' => array(
				'wiki_badchar_prevent',
			),
		),
		'feature_sefurl_filter' => array(
			'name' => tra('Search engine friendly url Postfilter'),
			'help' => 'Rewrite+Rules',
			'type' => 'flag',
			'perspective' => false,
		),	
		'feature_sefurl_title_article' => array(
			'name' => tra('Display article title in the sefurl'),
			'type' => 'flag',
			'perspective' => false,
		),	
		'feature_sefurl_title_blog' => array(
			'name' => tra('Display blog title in the sefurl'),
			'type' => 'flag',
			'perspective' => false,
		),
		'feature_sefurl_tracker_prefixalias' => array(
			'name' => tra('Redirect tiki-view_tracker.php?itemId=yyy to Prefixyyy page'),
			'description' => tra('This redirection uses the wiki prefix alias feature'),
			'help' => 'Page+Alias',
			'perspective' => false,
			'type' => 'flag',
			'require' => array('feature_sefurl', 'wiki_prefixalias_tokens'),
		),	
		'feature_modulecontrols' => array(
			'name' => tra('Show module controls'),
			'help' => 'Module+Control',
			'type' => 'flag',
		),	
		'feature_perspective' => array(
			'name' => tra('Perspectives'),
			'description' => tra('Permits to override preferences.'),
			'help' => 'Perspectives',
			'type' => 'flag',
			'perspective' => false,
		),
		'feature_wiki_replace' => array(
			'name' => tra('Search and replace'),
			'description' => tra('Permits find and replace of content in the edit box'),
			'help' => 'Regex+search+and+replace',
			'type' => 'flag',
		),
		'feature_submissions' => array(
			'name' => tra('Submissions'),
			'help' => 'Articles',
			'type' => 'flag',
		),
		'feature_cms_rankings' => array(
			'name' => tra('Rankings'),
			'type' => 'flag',
		),
		'feature_article_comments' => array(
			'name' => tra('Comments for articles'),
			'type' => 'flag',
		),
		'feature_cms_templates' => array(
			'name' => tra('Content Templates'),
			'type' => 'flag',
			'help' => 'Content+Template',
			'description' => tra('Pre-defined content for a wiki page.'),
		),
		'feature_cms_print' => array(
			'name' => tra('Print'),
			'type' => 'flag',
		),
		'feature_cms_emails' => array(
			'name' => tra('Specify notification emails when creating articles'),
			'type' => 'flag',
		),
		'feature_categorypath' => array(
			'name' => tra('Category Path'),
			'description' => tra('Show category tree, above wiki pages'),
			'type' => 'flag',
		),
		'feature_categoryobjects' => array(
			'name' => tra('Show category objects'),
			'description' => tra('Show objects sharing the same category, below wiki pages'),
			'type' => 'flag',
		),
		'feature_category_use_phplayers' => array(
			'name' => tra('Use PHPLayers for category browser'),
			'type' => 'flag',
			'dependencies' => array(
				'feature_phplayers',
			),
		),
		'feature_search_show_forbidden_cat' => array(
			'name' => tra('Ignore category viewing restrictions'),
			'hint' => tra('Will improve performance, but may show forbidden results'),
			'type' => 'flag',
			'help' => 'WYSIWYCA+Search',
		),
		'feature_listPages' => array(
			'name' => tra('List pages'),
			'type' => 'flag',
			'hint' => 'tiki-listpages.php',
		),
		'feature_lastChanges' => array(
			'name' => tra('Last changes'),
			'type' => 'flag',
			'hint' => 'tiki-lastchanges.php',
		),
		'feature_listorphanPages' => array(
			'name' => tra('Orphan pages'),
			'type' => 'flag',
			'hint' => 'tiki-orphan_pages.php',
		),
		'feature_search_fulltext' => array(
			'name' => tra('MySQL Full-Text Search'),
			'description' => tra('This search uses the MySQL Full-Text Search feature. The indexation is continuously updated.'),
			'type' => 'flag',
			'help' => 'Search',
		),
		'feature_referer_highlight' => array(
			'name' => tra('Referer Search Highlighting'),
			'type' => 'flag',
			'help' => 'Referer+Search+Highlighting',
		),
		'feature_search_stats' => array(
			'name' => tra('Search stats'),
			'type' => 'flag',
			'help' => 'Search+Stats',
		),
		'feature_search_show_forbidden_obj' => array(
			'name' => tra('Ignore individual object permissions'),
			'type' => 'flag',
			'perspective' => false,
		),
		'feature_search_show_object_filter' => array(
			'name' => tra('Object filter'),
			'type' => 'flag',
		),
		'feature_search_show_search_box' => array(
			'name' => tra('Search box'),
			'type' => 'flag',
		),
		'feature_search_show_visit_count' => array(
			'name' => tra('Visits'),
			'type' => 'flag',
		),
		'feature_search_show_pertinence' => array(
			'name' => tra('Pertinence'),
			'type' => 'flag',
		),
		'feature_search_show_object_type' => array(
			'name' => tra('Object type'),
			'type' => 'flag',
		),
		'feature_search_show_last_modification' => array(
			'name' => tra('Last modified date'),
			'type' => 'flag',
		),
		'feature_blog_rankings' => array(
			'name' => tra('Rankings'),
			'type' => 'flag',
		),
		'feature_blog_heading' => array(
			'name' => tra('Custom blog headings'),
			'type' => 'flag',
		),
		'feature_blogposts_comments' => array(
			'name' => tra('Comments on blog posts'),
			'type' => 'flag',
		),
		'feature_blog_sharethis' => array(
			'name' => tra('ShareThis buttons'),
			'type' => 'flag',
			'hint' => tra('Insert a ShareThis button from www.sharethis.com.'),
		),
		'feature_file_galleries_rankings' =>array(
			'name' => tra('Rankings'),
			'type' => 'flag',
			'help' => 'File+Gallery+Config',
		),
		'feature_file_galleries_comments' =>array(
			'name' => tra('File Gallery Comments'),
			'type' => 'flag',
			'help' => 'File+Gallery+Config',
		),
		'feature_file_galleries_author' => array(
			'name' => tra("Require file author's name for anonymous uploads"),
			'type' => 'flag',
			'help' => 'File+Gallery+Config',
		),
		'feature_file_galleries_batch' => array(
			'name' => tra('Batch uploading'),
			'type' => 'flag',
			'help' => 'File+Gallery+Config',
		),
		'feature_forum_rankings' => array(
			'name' => tra('Rankings'),
			'type' => 'flag',
		),
		'feature_forum_parse' => array(
			'name' => tra('Accept wiki syntax'),
			'type' => 'flag',
			'help' => 'Wiki+Syntax',
		),
		'feature_forum_topics_archiving' => array(
			'name' => tra('Topic archiving'),
			'type' => 'flag',
		),
		'feature_forum_quickjump' => array(
			'name' => tra('Quick jumps'),
			'type' => 'flag',
		),
		'feature_forum_replyempty' => array(
			'name' => tra('Replies are empty'),
			'type' => 'flag',
			'hint' => tra('If disabled, replies will quote the original post'),
		),
		'feature_forums_allow_thread_titles' => array(
			'name' => tra('First post of a thread can have an empty body'),
			'type' => 'flag',
			'hint' => tra('Will be a thread title'),
		),
		'feature_forums_name_search' => array(
			'name' => tra('Forum name search'),
			'type' => 'flag',
			'hint' => tra('When listing forums'),
		),
		'feature_forums_search' => array(
			'name' => tra('Forum content search'),
			'type' => 'flag',
			'hint' => tra('When listing forums'),
		),
		'feature_forum_content_search' => array(
			'name' => tra('Topic content search'),
			'type' => 'flag',
			'dependencies' => array(
				'feature_search',
			),
		),
		'feature_forum_local_tiki_search' => array(
			'name' => tra('Tiki-indexed search.'),
			'type' => 'flag',
		),
		'feature_forum_local_search' => array(
			'name' => tra('Use database (full-text) search.'),
			'type' => 'flag',
		),
		'feature_clear_passwords' => array(
			'name' => tra('Store password as plain text'),
			'type' => 'flag',
			'perspective' => false,
		),

		'feature_crypt_passwords' => array(
			'name' => tra('Encryption method'),
			'type' => 'list',
			'options' => array(
				'crypt-md5' => 'crypt-md5',
				'crypt-des' => 'crypt-des',
				'tikihash' => tra('tikihash (old)'),
			),
			'perspective' => false,
		),
		'feature_bot_bar_power_by_tw' => array(
			'name' => tra("Add a Powered by Tiki link on your site's footer"),
			'type' => 'flag',
			'dependencies' => array(
				'feature_bot_bar',
			),			
		),
		'feature_editcss' => array(
			'name' => tra('Edit CSS'),
			'type' => 'flag',
			'help' => 'Edit+CSS',
			'perspective' => false,
		),
		'feature_theme_control' => array(
			'name' => tra('Theme Control'),
			'description' => tra('Assign different themes to different sections, categories, and objects'),
			'type' => 'flag',
		),
		'feature_view_tpl' => array(
			'name' => tra('Tiki Template Viewing'),
			'type' => 'flag',
			'help' => 'View+Templates',
			'perspective' => false,
		),
		'feature_edit_templates' => array(
			'name' => tra('Edit Templates'),
			'type' => 'flag',
			'help' => 'Edit+Templates',
			'perspective' => false,
		),
		'feature_custom_doctype' => array(
			'name' => tra('Custom Doctype'),
			'description' => tra('Use custom !DOCTYPE'),
			'type' => 'flag',
			'help' => 'Custom+Doctype',
			'perspective' => true,
		),
		'feature_custom_doctype_content' => array(
			'name' => tra('Custom Doctype Content'),
			'hint' => tra('Example:') . "<!DOCTYPE html 
	PUBLIC \"-//W3C//DTD XHTML 1.0 Strict//EN\"
	\"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd\">",
			'description' => tra('Use to generate the Tiki layout with custom &lt;!DOCTYPE&gt; specified.'),
			'type' => 'textarea',
			'size' => '3',
			'filter' => 'rawhtml_unsafe',
		),
		'feature_custom_html_head_content' => array(
			'name' => tra('Custom HTML <head> Content'),
			'hint' => tra('Example:') . " {if \$page eq 'Slideshow'}{literal}<style type=\"text/css\">.slideshow { height: 232px; width: 232px; }</style>{/literal}{/if}",
			'description' => tra('Use to include custom &lt;meta&gt; or &lt;link&gt; tags.'),
			'type' => 'textarea',
			'size' => '6',
			'filter' => 'rawhtml_unsafe',
		),
		'feature_sitemycode' => array(
			'name' => tra('Custom Site Header'),
			'type' => 'flag',
		),
		'feature_sitelogo' => array(
			'name' => tra('Site Logo and Title'),
			'type' => 'flag',
		),
		'feature_sitesearch' => array(
			'name' => tra('Search Bar'),
			'type' => 'flag',
			'dependencies' => array(
				'feature_search_fulltext',
			),
		),
		'feature_site_login' => array(
			'name' => tra('Login Bar'),
			'type' => 'flag',
		),
		'feature_topbar_custom_code' => array(
			'name' => tra('Custom code'),
			'type' => 'textarea',
			'size' => '6',
			'filter' => 'rawhtml_unsafe',
		),
		'feature_topbar_version' => array(
			'name' => tra('Display current Tiki version'),
			'type' => 'flag',
			'dependencies' => array(
				'feature_bot_bar_power_by_tw',
			),
		),
		'feature_sitemenu' => array(
			'name' => tra('Site menu bar'),
			'type' => 'flag',
		),
		'feature_sitemenu_custom_code' => array (
			'name' => tra('Site menu custom code'),
			'type' => 'textarea',
			'size' => '4',
			'hint' => tra('Example:') . " {if isset(\$objectCategoryIds) and in_array(2, \$objectCategoryIds)}
     {menu id=43 css=y type=horiz}{else}{menu id=44 css=y type=horiz}{/if}",
		),
		'feature_secondary_sitemenu_custom_code' => array (
			'name' => tra('Secondary site menu custom code'),
			'type' => 'textarea',
			'size' => '2',
		),
		'feature_topbar_id_menu' => array(
			'name' => tra('Menu ID'),
			'hint' => tra('Needs either CSS Menus or PHPLayers'),
			'type' => 'text',
			'size' => '5',
			'dependencies' => array(
				'feature_cssmenus',
				'feature_phplayers',
			),
		),
		'feature_top_bar' => array(
			'name' => tra('Top Bar'),
			'type' => 'flag',
		),
		'feature_custom_center_column_header' => array(
			'name' => tra('Custom Center Column Header'),
			'hint' => tra('Example:') . " {if \$page eq 'Travel'}{banner zone=5}{/if}",
			'type' => 'textarea',
			'size' => '6',
		),
		'feature_left_column' => array(
			'name' => tra('Left column'),
			'type' => 'list',
			'help' => 'Users+Flip+Columns',
			'hint' => tra('Controls visibility of the left column of modules'),
			'keywords' => tra('side bar'),
			'options' => array(
				'y' => tra('Only if module'),
				'fixed' => tra('Always'),
				'user' => tra('User Decides'),
				'n' => tra('Never'),
			),
		),
		'feature_right_column' => array(
			'name' => tra('Right Column'),
			'type' => 'list',
			'help' => 'Users+Flip+Columns',
			'hint' => tra('Controls visibility of the right column of modules'),
			'keywords' => tra('side bar'),
			'options' => array(
				'y' => tra('Only if module'),
				'fixed' => tra('Always'),
				'user' => tra('User Decides'),
				'n' => tra('Never'),
			),
		),
		'feature_siteloclabel' => array(
			'name' => tra('Prefix breadcrumbs with "Location : "'),
			'type' => 'flag',
		),
		'feature_siteloc' => array(
			'name' => tra('Site location bar'),
			'type' => 'list',
			'options' => array(
				'y' => tra('Top of page'),
				'page' => tra('Top of center column'),
				'n' => tra('None'),
			),
		),
		'feature_sitetitle' => array(
			'name' => tra('Larger font for'),
			'type' => 'list',
			'options' => array(
				'y' => tra('Entire location'),
				'title' => tra('Page name'),
				'n' => tra('None'),
			),
		),
		'feature_sitedesc' => array(
			'name' => tra('Use page description'),
			'type' => 'list',
			'options' => array(
				'y' => tra('Top of page'),
				'page' => tra('Top of center column'),
				'n' => tra('None'),
			),
			'dependencies' => array(
				'feature_wiki_description',
			),
		),
		'feature_bot_logo' => array(
			'name' => tra('Custom Site Footer'),
			'type' => 'flag',
		),
		'feature_endbody_code' => array(
			'name' => tra('Custom End of <body> Code'),
			'hint' => tra('Example:') . ' ' . "{wiki}{literal}{GOOGLEANALYTICS(account=xxxx) /}{/literal}{/wiki}",
			'type' => 'textarea',
			'size' => '6',
			'filter' => 'rawhtml_unsafe',
		),
		'feature_bot_bar' => array(
			'name' => tra('Bottom bar'),
			'type' => 'flag',
		),
		'feature_bot_bar_icons' => array(
			'name' => tra('Bottom bar icons'),
			'type' => 'flag',
		),
		'feature_bot_bar_debug' => array(
			'name' => tra('Bottom bar debug'),
			'type' => 'flag',
			'description' => tra('Indicate various debug-related information in the footer of the site (Execution time, Memory usage, etc.)'),
		),
		'feature_bot_bar_rss' => array(
			'name' => tra('Bottom bar (RSS)'),
			'type' => 'flag',
		),
		'feature_site_report' => array(
			'name' => tra('Webmaster Report'),
			'type' => 'flag',
		),
		'feature_site_report_email' => array(
			'name' => tra('Webmaster Email'),
			'hint' => tra('Leave blank to use the default sender email'),
			'type' => 'text',
			'size' => '20',
			'dependencies' => array(
				'sender_email',
			),
		),
		'feature_site_send_link' => array(
			'name' => tra('Email this page'),
			'description' => tra('Add a link at the bottom if set, otherwise add a link at the top'),
			'type' => 'flag',
			'dependencies' => array(
				'feature_tell_a_friend',
			),
		),
		'feature_layoutshadows' => array(
			'name' => tra('Shadow layer'),
			'hint' => tra('Additional layers for shadows, rounded corners or other decorative styling'),
			'type' => 'flag',
		),
		'feature_jquery_tooltips' => array(
			'name' => tra('Tooltips'),
			'type' => 'flag',
			'description' => tra('Make tooltips such as this appear in a custom style. Use CSS to change their appearance.'),
			'help' => 'JQuery#Tooltips',
		),
		'feature_jquery_autocomplete' => array(
			'name' => tra('Autocomplete'),
			'type' => 'flag',
			'description' => tra('Provides various drop-down menus on many text input boxes for page names, user names, groups, tags etc.'),
			'help' => 'JQuery#Autocomplete',
		),
		'feature_jquery_superfish' => array(
			'name' => tra('Superfish'),
			'type' => 'flag',
			'description' => tra('Adds animation effects to CSS "Suckerfish" menus.'),
			'help' => 'JQuery#Superfish',
		),
		'feature_jquery_reflection' => array(
			'name' => tra('Reflection'),
			'type' => 'flag',
			'description' => tra('Allows images to have a reflection effect below them. See the admin icons above for an example.'),
			'help' => 'JQuery#Reflection',
		),
		'feature_jquery_ui' => array(
			'name' => tra('JQuery UI'),
			'type' => 'flag',
			'description' => tra('Include jQuery UI library. Enables many user interface features.'),
			'help' => 'JQuery#UI',
		),
		'feature_jquery_ui_theme' => array(
			'name' => tra('JQuery UI Theme'),
			'help' => 'JQuery#UI',
			'type' => 'list',
			'description' => tra('jQuery UI Theme. Used in spreadsheet, for example.'),
			'options' => array(
				'black-tie' => 'black-tie',
				'blitzer' => 'blitzer',
				'cupertino' => 'cupertino',
				'dark-hive' => 'dark-hive',
				'dot-luv' => 'dot-luv',
				'eggplant' => 'eggplant',
				'excite-bike' => 'excite-bike',
				'flick' => 'flick',
				'hot-sneaks' => 'hot-sneaks',
				'humanity' => 'humanity',
				'le-frog' => 'le-frog',
				'mint-choc' => 'mint-choc',
				'overcast' => 'overcast',
				'pepper-grinder' => 'pepper-grinder',
				'redmond' => 'redmond',
				'smoothness' => 'smoothness',
				'south-street' => 'south-street',
				'start' => 'start',
				'sunny' => 'sunny',
				'swanky-purse' => 'swanky-purse',
				'trontastic' => 'trontastic',
				'ui-darkness' => 'ui-darkness',
				'ui-lightness' => 'ui-lightness',
				'vader' => 'vader',
			), 
		),
		'feature_jquery_validation' => array(
			'name' => tra('Validation'),
			'type' => 'flag',
			'description' => tra('Provides various validation possibilities like in Trackers.'),
			'help' => 'JQuery#Validation',
		),
		'feature_jquery_jqs5' => array(
			'name' => tra('JQuery JQS5'),
			'type' => 'flag',
			'help' => 'JQuery#JQS5',
			'description' => tra('jQuery Simple Standards-Based Slide Show System'),
		),
		'feature_jquery_carousel' => array(
			'name' => tra('JQuery Infinite Carousel'),
			'type' => 'flag',
			'help' => 'JQuery#Carousel',
			'description' => tra('Image "carousel" plugin (coming soon)'),
		),
		'feature_jquery_tablesorter' => array(
			'name' => tra('JQuery Sortable Tables'),
			'type' => 'flag',
			'help' => 'JQuery#TableSorter',
			'description' => tra('Sort in fancytable plugin'),
		),
		'feature_jquery_media' => array(
			'name' => tra('JQuery Media'),
			'type' => 'flag',
			'help' => 'JQuery#Media',
			'description' => tra('Media player'),
		),
		'feature_tabs' => array(
			'name' => tra('Use Tabs'),
			'type' => 'flag',
		),
		'feature_iepngfix' => array(
			'name' => tra('Correct PNG images alpha transparency in IE6 (experimental)'),
			'type' => 'flag',
		),
		'feature_wiki_1like_redirection' => array(
			'name' => tra("Redirect to similar wiki page"),
			'type' => 'flag',
			'description' => tra("If a requested page doesn't exist, redirect to a similarly named page"),
			'help' => 'Redirect+to+similar+wiki+page',
		),
		'feature_wiki_templates' => array(
			'name' => tra('Content templates'),
			'type' => 'flag',
			'help' => 'Content+Template',
		),
		'feature_warn_on_edit' => array(
			'name' => tra('Warn on edit conflict'),
			'type' => 'flag',
			'description' => tra('Tiki will warn users who attempt to edit a page that another user is currenly editing.'),
		),
		'feature_wiki_undo' => array(
			'name' => tra('Undo'),
			'type' => 'flag',
		),
		'feature_wiki_footnotes' => array(
			'name' => tra('Footnotes'),
			'type' => 'flag',
			'description' => tra('Create private notes for a page that are visible only by the author.'),
		),
		'feature_wiki_allowhtml' => array(
			'name' => tra('Allow HTML'),
			'type' => 'flag',
		),
		'feature_actionlog_bytes' => array(
			'name' => tra('Log bytes changes (+/-) in action logs'),
			'type' => 'flag',
			'hint' => tra('May impact performance'),
		),
		'feature_sandbox' => array(
			'name' => tra('Sandbox'),
			'type' => 'flag',
			'description' => tra('A special wiki page for testing. Users can edit, but not save the Sandbox.'),
		),
		'feature_wiki_comments' => array(
			'name' => tra('Comments below wiki pages'),
			'type' => 'flag',
			'help' => 'Comments',
			'description' => tra('Allow users (with permission) to post threaded comments to a page.'),
		),
		'feature_wiki_pictures' => array(
			'name' => tra('Pictures'),
			'type' => 'flag',
			'help' => 'Wiki-Syntax Images',
			'description' => tra('Allow users to upload images (pictures) to a page.'),
		),
		'feature_wiki_export' => array(
			'name' => tra('Export'),
			'type' => 'flag',
		),
		'feature_wikiwords' => array(
			'name' => tra('WikiWords'),
			'type' => 'flag',
			'description' => tra('Automatically convert words with UpPeR and LoWeR-case letters into wiki links.'),
		),
		'feature_wiki_plurals' => array(
			'name' => tra('Link plural WikiWords to their singular forms'),
			'type' => 'flag',
		),
		'feature_wikiwords_usedash' => array(
			'name' => tra('Accept dashes and underscores in WikiWords'),
			'type' => 'flag',
		),
		'feature_history' => array(
			'name' => tra('History'),
			'type' => 'flag',
			'help' => 'History',
		),
		'feature_wiki_history_ip' => array(
			'name' => tra('Display IP address'),
			'type' => 'flag',
		),
		'feature_wiki_history_full' => array(
			'name' => tra('History all instead of only page data, description, and change comment'),
			'type' => 'flag',
		),
		'feature_page_contribution' => array(
			'name' => tra('View page contributions by author'),
			'type' => 'flag',
			'dependencies' => array (
				'feature_history'
			),
			'description' => tra('Visualize the contributions of different authors made to a wiki page'),
		),
		'feature_wiki_discuss' => array(
			'name' => tra('Discuss pages on forums'),
			'type' => 'flag',
			'dependencies' => array(
				'feature_forums'
			),
			'description' => tra('Include a link on each page to a forum topic.'),
		),
		'feature_source' => array(
			'name' => tra('View source'),
			'type' => 'flag',
		),
		'feature_wiki_ratings' => array(
			'name' => tra('Rating'),
			'type' => 'flag',
			'help' => 'Rating',
			'dependencies' => array(
				'feature_polls'
			),
		),
		'feature_backlinks' => array(
			'name' => tra('Backlinks'),
			'type' => 'flag',
			'help' => 'Backlinks',
		),
		'feature_likePages' => array(
			'name' => tra('Similar (like pages)'),
			'type' => 'flag',
		),
		'feature_wiki_rankings' => array(
			'name' => tra('Rankings'),
			'type' => 'flag',
		),
		'feature_wiki_structure' => array(
			'name' => tra('Structures'),
			'type' => 'flag',
			'help' => 'Structure',
		),
		'feature_wiki_open_as_structure' => array(
			'name' => tra('Open page as structure'),
			'type' => 'flag',
		),
		'feature_wiki_make_structure' => array(
			'name' => tra('Make structure from page'),
			'type' => 'flag',
		),
		'feature_wiki_categorize_structure' => array(
			'name' => tra('Categorize structure pages together'),
			'type' => 'flag',
		),
		'feature_create_webhelp' => array(
			'name' => tra('Create webhelp from structure'),
			'type' => 'flag',
		),
		'feature_wiki_import_html' => array(
			'name' => tra('Import HTML'),
			'type' => 'flag',
		),
		'feature_wiki_import_page' => array(
			'name' => tra('Import pages'),
			'type' => 'flag',
		),
		'feature_wiki_userpage' => array(
			'name' => tra("User's page"),
			'type' => 'flag',
		),
		'feature_wiki_userpage_prefix' => array(
			'name' => tra('UserPage prefix'),
			'type' => 'text',
			'size' => '40',
		),
		'feature_wiki_usrlock' => array(
			'name' => tra('Users can lock pages'),
			'type' => 'flag',
		),
		'feature_wiki_multiprint' => array(
			'name' => tra('MultiPrint'),
			'type' => 'flag',
		),
	
		'feature_wiki_print' => array(
			'name' => tra('Print'),
			'type' => 'flag',
		),
		'feature_wikiapproval' => array(
			'name' => tra('Use wiki page staging and approval'),
			'type' => 'flag',
			'help' => 'Wiki+Page+Staging+and+Approval',
			'perspective' => false,
			'warning' => tra('This feature is experimental'),
			'description' => tra('Allows wiki pages to be staged (drafted) before they are approved (published)'),
		),
		'feature_listorphanStructure' => array(
			'name' => tra('Pages not in structure'),
			'type' => 'flag',
		),
		'feature_wiki_attachments' => array(
			'name' => tra('Attachments'),
			'type' => 'flag',
			'help' => 'Attachments',
			'description' => tra('Allow users to upload (attach) files to a page.'),
		),
		'feature_dump' => array(
			'name' => tra('Dumps'),
			'type' => 'flag',
		),
		'feature_wiki_mandatory_category' => array(
			'name' => tra('Force and limit categorization to within subtree of'),
			'type' => 'list',
			'options' => $catree,
			'dependencies' => array(
				'feature_categories',
			),
		),
		'feature_wiki_show_hide_before' => array(
			'name' => tra('Display show/hide icon displayed before headings'),
			'type' => 'flag',
		),
		'feature_metrics_dashboard' => array(
			'name' => tra('Metrics Dashboard'),
			'description' => tra('Generate automated statistics from configured database queries.'),
			'type' => 'flag',
			'dependencies' => array(
				'feature_jquery_ui',
			),
		),
		'feature_wiki_argvariable' => array(
			'name' => tra('Wiki argument variables'),
			'description' => tra('Allow to write request variables inside wiki content using {{paramname}} or {{paramname|default}} - special case {{page}} {{user}}'),
			'type' => 'flag',
			'help' => 'Advanced+Wiki+Syntax+usage+examples'
		),
		'feature_challenge' => array(
			'name' => tra('Use challenge/response authentication'),
			'type' => 'flag',
			'hint' => tra('Confirm that the Admin account has a valid email address or you will not be permitted to login'),
		),
		'feature_show_stay_in_ssl_mode' => array(
			'name' => tra('Users can choose to stay in SSL mode after an HTTPS login'),
			'type' => 'flag',
		),
		'feature_switch_ssl_mode' => array(
			'name' => tra('Users can switch between secured or standard mode at login'),
			'type' => 'flag',
		),
		'feature_wiki_paragraph_formatting' => array(
			'name' => tra('Wiki paragraph formatting'),
			'description' => tra('Because the Wiki paragraph formatting feature is on, all groups of non-blank lines are collected into paragraphs.  Lines can be of any length, and will be wrapped together with the next line.  Paragraphs are separated by blank lines.').' '.tra('Because the Wiki paragraph formatting feature is off, each line will be presented as you write it.  This means that if you want paragraphs to be wrapped properly, a paragraph should be all together on one line.'),
			'type' => 'flag',
		),
		'feature_wiki_paragraph_formatting_add_br' => array(
			'name' => tra('...but still create line breaks within paragraphs'),
			'type' => 'flag',
		),
		'feature_wiki_monosp' => array(
			'name' => tra('Automonospaced text'),
			'type' => 'flag',
		),
		'feature_wiki_tables' => array(
			'name' => tra('Tables syntax'),
			'type' => 'list',
			'options' => array(
				'old' => tra('|| for rows'),
				'new' => tra('<return> for rows'),
			),
		),
		'feature_autolinks' => array(
			'name' => tra('AutoLinks'),
			'type' => 'flag',
			'help' => 'AutoLinks',
			'description' => tra('Tiki will automatically convert http:// and email addresses into links.'),
		),
		'feature_hotwords' => array(
			'name' => tra('Hotwords'),
			'type' => 'flag',
			'help' => 'Hotwords',
		),
		'feature_hotwords_nw' => array(
			'name' => tra('Open Hotwords in new window'),
			'type' => 'flag',
		),
		'feature_use_quoteplugin' => array(
			'name' => tra('Use Quote plugin rather than ">" for quoting'),
			'type' => 'flag',
			'help' => 'PluginQuote',
			'dependencies' => array(
				'wikiplugin_quote',
			),
		),
		'feature_use_three_colon_centertag' => array(
			'name' => tra('Use three colons instead of two to center text. Avoids conflict with C++ resolution scope operator.'),
			'type' => 'flag',
		),
		'feature_community_gender' => array(
			'name' => tra('Users can choose to show their gender'),
			'type' => 'flag',
			'help' => 'User+Preferences',
			'dependencies' => array(
				'feature_userPreferences',
			),
		),
		'feature_community_mouseover' => array(
			'name' => tra("Show user's information on mouseover"),
			'type' => 'flag',
			'help' => 'User+Preferences',
			'hint' => tra("Requires user's information to be public"),
		),
		'feature_community_mouseover_name' => array(
			'name' => tra('Real name'),
			'type' => 'flag',
		),
		'feature_community_mouseover_gender' => array(
			'name' => tra('Gender'),
			'type' => 'flag',
		),
		'feature_community_mouseover_picture' => array(
			'name' => tra('Picture (avatar)'),
			'type' => 'flag',
		),
		'feature_community_mouseover_friends' => array(
			'name' => tra('Number of friends'),
			'type' => 'flag',
			'help' => 'Friendship+Network',
			'dependencies' => array(
				'feature_friends',
			),
		),
		'feature_community_mouseover_score' => array(
			'name' => tra('Score'),
			'type' => 'flag',
			'help' => 'Score',
		),
		'feature_community_mouseover_country' => array(
			'name' => tra('Country'),
			'type' => 'flag',
		),
		'feature_community_mouseover_email' => array(
			'name' => tra('E-mail'),
			'type' => 'flag',
		),
		'feature_community_mouseover_lastlogin' => array(
			'name' => tra('Last login'),
			'type' => 'flag',
		),
		'feature_community_mouseover_distance' => array(
			'name' => tra('Distance'),
			'type' => 'flag',
		),
		'feature_community_list_name' => array(
			'name' => tra('Name'),
			'type' => 'flag',
			'dependencies' => array(
				'feature_friends',
			),
		),
		'feature_community_list_score' => array(
			'name' => tra('Score'),
			'type' => 'flag',
			'help' => 'Score',
			'dependencies' => array(
				'feature_friends',
			),
		),
		'feature_community_list_country' => array(
			'name' => tra('Country'),
			'type' => 'flag',
			'dependencies' => array(
				'feature_friends',
			),
		),
		'feature_community_list_distance' => array(
			'name' => tra('Distance'),
			'type' => 'flag',
			'dependencies' => array(
				'feature_friends',
			),
		),
		'feature_cal_manual_time' => array(
			'name' => tra('Manual selection of time/date'),
			'type' => 'flag',
		),
		'feature_webdav' => array(
			'name' => tra('WebDAV access to Tiki'),
			'description' => tra('Allow to access File Galleries with WebDAV'),
			'hint' => tra('use tiki-webdav.php as the URI of the WebDAV repository'),
			'help' => 'WebDAV',
			'type' => 'flag',
			'dependencies' => array(
				'feature_file_galleries',
			),
		),
		'feature_fixed_width' => array(
			'name' => tra('Fixed width'),
			'type' => 'flag',
			'description' => tra('Constrains the site display to 990px wide.'),
			'warning' => tra('You can modify at styles/layout/fixed_width.css'),
		),
		'feature_socialnetworks' => array(
			'name' => tra('Social networks'),
			'description' => tra('Integration with different social networks like Twitter or Facebook'),
			'help' => 'Social+Networks',
			'type' => 'flag',
			'keywords' => 'social networks',
		),
		'feature_group_transition' => array(
			'name' => tra('Group Transition'),
			'description' => tra('Enables transitions for users between different groups. Transitions will create a user approval workflow.'),
			'type' => 'flag',
			'help' => 'Group+Transitions',
		),
		'feature_category_transition' => array(
			'name' => tra('Category Transition'),
			'description' => tra('Enables transitions on objects between different categories. Transitions will create a document workflow.'),
			'type' => 'flag',
			'help' => 'Category+Transitions',
		),
		'feature_watershed' => array(
			'name' => tra('Ustream Watershed'),
			'description' => tra('Integration to Ustream Watershed live video streaming.'),
			'type' => 'flag',
			'help' => 'Ustream+Watershed',
		),
		'feature_credits' => array(
			'name' => tra('Tiki User Credits'),
			'description' => tra('Tiki User Credits'),
			'type' => 'flag',
			'help' => 'Tiki+User+Credits',
		),
		'feature_invit' => array(
			'name' => tra('Invite users'),
			'description' => tra('Allow users to invite new users by mail to register on this tiki'),
			'type' => 'flag',
		),
        'feature_loadbalancer' => array(
            'name' => tra('Load Balancer'),
            'description' => tra('Enable this only if the server is behind a load balancer (or reverse proxy), this allow tiki to log the IP of the user, instead of the IP of the proxy server'),
            'type' => 'flag',
        ),
	);
}
