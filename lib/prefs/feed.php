<?php
// (c) Copyright 2002-2014 by authors of the Tiki Wiki CMS Groupware Project
// 
// All Rights Reserved. See copyright.txt for details and a complete list of authors.
// Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See license.txt for details.
// $Id$

function prefs_feed_list()
{
	return array(
		'feed_default_version' => array(
			'name' => tra('Default feed format'),
            'description' => tra(''),
			'type' => 'list',
			'options' => array(
				'5' => tra('ATOM 1.0'),
				'2' => tra('RSS 2.0'),
			),
			'default' => '5',
			'shorthint' => '[http://atomenabled.org/developers/syndication/atom-format-spec.php|Atom 1.0]'
						.' - '
						.'[http://cyber.law.harvard.edu/rss/rss.html|RSS 2.0]',
		),

		// atom specific preferences
		'feed_atom_author_name' => array(
			'name' => tra('Feed author name'),
            'description' => tra(''),
			'type' => 'text',
			'size' => '40',
			'hint' => tra('This field is mandatory unless both feed author email and homepage are empty.'),
			'default' => '',
		),
		'feed_atom_author_email' => array(
			'name' => tra('Feed author email'),
            'description' => tra(''),
			'type' => 'text',
			'size' => '40',
			'default' => '',
		),
		'feed_atom_author_url' => array(
			'name' => tra('Feed author homepage'),
            'description' => tra(''),
			'type' => 'text',
			'size' => '40',
			'default' => '',
		),

		// rss specific preferences
		'feed_rss_editor_email' => array(
			'name' => tra('Feed editor email'),
			'description' => tra('Email address for person responsible for editorial content.'),
			'type' => 'text',
			'size' => '40',
			'default' => '',
		),
		'feed_rss_webmaster_email' => array(
			'name' => tra('Feed webmaster email'),
			'description' => tra('Email address for person responsible for technical issues relating to channel.'),
			'type' => 'text',
			'size' => '40',
			'default' => '',
		),

		'feed_img' => array(
			'name' => tra('Feed Image path'),
			'description' => tra('Specifies a GIF, JPEG or PNG image that can be displayed with the feed.'),
			'type' => 'text',
			'size' => '40',
			'default' => 'img/tiki/Tiki_WCG.png',
		),
		'feed_language' => array(
			'name' => tra('Feed Language'),
            'description' => tra('the default language for this feed'),
			'type' => 'text',
			'size' => '10',
			'default' =>  'en-us',
			'tags' => array('basic'),
		),
		'feed_basic_auth' => array(
			'name' => tra('RSS basic Authentication'),
			'description' => tra('Propose basic HTTP authentication if the user has no permission to see the feed'),
			'type' => 'flag',
			'default' => 'n',
		),
		'feed_cache_time' => array(
			'name' => tra('Feed Caching time'),
            'description' => tra('number of seconds to cache the feed for'),
			'type' => 'text',
			'size' => '5',
			'filter' => 'digits',
			'shorthint' => tra('seconds'),
			'hint' => tra('Use 0 for no caching'),
			'default' => '300', // 5 minutes
			'detail' => tra('Feed caching is done for anonymous users only.'),
		),
		'feed_articles' => array(
			'name' => tra('RSS for articles'),
			'description' => tra('RSS feeds for articles'),
			'type' => 'flag',
			'dependencies' => array(
				'feature_articles',
			),
			'default' => 'n',
			'tags' => array('basic'),
		),
		'feed_blogs' => array(
			'name' => tra('RSS for blogs'),
			'description' => tra('RSS feeds for blogs'),
			'type' => 'flag',
			'dependencies' => array(
				'feature_blogs',
			),
			'default' => 'n',
			'tags' => array('basic'),
		),
		'feed_blog' => array(
			'name' => tra('RSS for individual blogs'),
			'description' => tra('RSS feed for individual blogs'),
			'type' => 'flag',
			'dependencies' => array(
				'feature_blogs',
			),
			'default' => 'n',
			'tags' => array('basic'),
		),
		'feed_image_galleries' => array(
			'name' => tra('RSS for image galleries'),
			'description' => tra('RSS feed for image galleries'),
			'type' => 'flag',
			'dependencies' => array(
				'feature_galleries',
			),
			'default' => 'n',
		),
		'feed_image_gallery' => array(
			'name' => tra('RSS for individual image galleries'),
			'description' => tra('RSS feed for individual image galleries'),
			'type' => 'flag',
			'dependencies' => array(
				'feature_galleries',
			),
			'default' => 'n',
		),
		'feed_file_galleries' => array(
			'name' => tra('RSS for file galleries'),
			'description' => tra('RSS feed for file galleries'),
			'type' => 'flag',
			'dependencies' => array(
				'feature_file_galleries',
			),
			'default' => 'n',
		),
		'feed_file_gallery' => array(
			'name' => tra('RSS for individual file galleries'),
			'description' => tra('RSS feed for individual file galleries'),
			'type' => 'flag',
			'dependencies' => array(
				'feature_file_galleries',
			),
			'default' => 'n',
		),
		'feed_wiki' => array(
			'name' => tra('RSS for wiki pages'),
			'description' => tra('RSS feed for wiki'),
			'type' => 'flag',
			'dependencies' => array(
				'feature_wiki',
			),
			'default' => 'n',
		),
		'feed_forums' => array(
			'name' => tra('RSS for forums'),
			'description' => tra('RSS feed for forums'),
			'type' => 'flag',
			'dependencies' => array(
				'feature_forums',
			),
			'default' => 'n',
			'tags' => array('basic'),
		),
		'feed_forum' => array(
			'name' => tra('RSS for individual forums'),
			'description' => tra('RSS feed for individual forums'),
			'type' => 'flag',
			'dependencies' => array(
				'feature_forums',
			),
			'default' => 'n',
			'tags' => array('basic'),
		),
		'feed_tracker' => array(
			'name' => tra('RSS per tracker'),
			'description' => tra('RSS feed per tracker'),
			'type' => 'flag',
			'dependencies' => array(
				'feature_trackers',
			),
			'default' => 'n',
		),
		'feed_calendar' => array(
			'name' => tra('RSS for calendar events'),
			'description' => tra('RSS feed for calendar events'),
			'type' => 'flag',
			'dependencies' => array(
				'feature_calendar',
			),
			'default' => 'n',
			'tags' => array('basic'),
		),
		'feed_directories' => array(
			'name' => tra('RSS for directories'),
			'description' => tra('RSS feed for directories'),
			'type' => 'flag',
			'dependencies' => array(
				'feature_directory',
			),
			'default' => 'n',
		),
		'feed_shoutbox' => array(
			'name' => tra('RSS for shoutbox'),
			'description' => tra('RSS feed for shoutbox'),
			'type' => 'flag',
			'dependencies' => array(
				'feature_shoutbox',
			),
			'default' => '',
		),
		'feed_articles_max' => array(
			'name' => tra('Maximum number of items to display'),
            'description' => tra(''),
			'type' => 'text',
			'size' => 5,
			'filter' => 'digits',
			'default' => 10,
		),
		'feed_blogs_max' => array(
			'name' => tra('Maximum number of items to display'),
            'description' => tra(''),
			'type' => 'text',
			'size' => 5,
			'filter' => 'digits',
			'default' => 10,
		),
		'feed_blog_max' => array(
			'name' => tra('Maximum number of items to display'),
            'description' => tra(''),
			'type' => 'text',
			'size' => 5,
			'filter' => 'digits',
			'default' => 10,
		),
		'feed_image_galleries_max' => array(
			'name' => tra('Maximum number of items to display'),
            'description' => tra(''),
			'type' => 'text',
			'size' => 5,
			'filter' => 'digits',
			'default' => 10,
		),
		'feed_image_gallery_max' => array(
			'name' => tra('Maximum number of items to display'),
            'description' => tra(''),
			'type' => 'text',
			'size' => 5,
			'filter' => 'digits',
			'default' => 10,
		),
		'feed_file_galleries_max' => array(
			'name' => tra('Maximum number of items to display'),
            'description' => tra(''),
			'type' => 'text',
			'size' => 5,
			'filter' => 'digits',
			'default' => 10,
		),
		'feed_file_gallery_max' => array(
			'name' => tra('Maximum number of items to display'),
            'description' => tra(''),
			'type' => 'text',
			'size' => 5,
			'filter' => 'digits',
			'default' => 10,
		),
		'feed_wiki_max' => array(
			'name' => tra('Maximum number of items to display'),
            'description' => tra(''),
			'type' => 'text',
			'size' => 5,
			'filter' => 'digits',
			'default' => 10,
		),
		'feed_forums_max' => array(
			'name' => tra('Maximum number of items to display'),
            'description' => tra(''),
			'type' => 'text',
			'size' => 5,
			'filter' => 'digits',
			'default' => 10,
		),
		'feed_forum_max' => array(
			'name' => tra('Maximum number of items to display'),
            'description' => tra(''),
			'type' => 'text',
			'size' => 5,
			'filter' => 'digits',
			'default' => 10,
		),
		'feed_tracker_max' => array(
			'name' => tra('Maximum number of items to display'),
            'description' => tra(''),
			'type' => 'text',
			'size' => 5,
			'filter' => 'digits',
			'default' => 10,
		),
		'feed_calendar_max' => array(
			'name' => tra('Maximum number of items to display'),
            'description' => tra(''),
			'type' => 'text',
			'size' => 5,
			'filter' => 'digits',
			'default' => 10,
		),
		'feed_directories_max' => array(
			'name' => tra('Maximum number of items to display'),
            'description' => tra(''),
			'type' => 'text',
			'size' => 5,
			'filter' => 'digits',
			'default' => 10,
		),
		'feed_shoutbox_max' => array(
			'name' => tra('Maximum number of items to display'),
            'description' => tra(''),
			'type' => 'text',
			'size' => 5,
			'filter' => 'digits',
			'default' => '',
		),
		'feed_articles_showAuthor' => array(
			'name' => tra('Show Author'),
            'description' => tra(''),
			'type' => 'flag',
			'default' => 'n',
		),
		'feed_blogs_showAuthor' => array(
			'name' => tra('Show Author'),
            'description' => tra(''),
			'type' => 'flag',
			'default' => 'n',
		),
		'feed_blog_showAuthor' => array(
			'name' => tra('Show Author'),
			'type' => 'flag',
			'default' => 'n',
		),
		'feed_image_galleries_showAuthor' => array(
			'name' => tra('Show Author'),
            'description' => tra(''),
			'type' => 'flag',
			'default' => 'n',
		),
		'feed_image_gallery_showAuthor' => array(
			'name' => tra('Show Author'),
            'description' => tra(''),
			'type' => 'flag',
			'default' => 'n',
		),
		'feed_file_galleries_showAuthor' => array(
			'name' => tra('Show Author'),
            'description' => tra(''),
			'type' => 'flag',
			'default' => 'n',
		),
		'feed_file_gallery_showAuthor' => array(
			'name' => tra('Show Author'),
            'description' => tra(''),
			'type' => 'flag',
			'default' => 'n',
		),
		'feed_wiki_showAuthor' => array(
			'name' => tra('Show Author'),
            'description' => tra(''),
			'type' => 'flag',
			'default' => 'n',		
		),
		'feed_forums_showAuthor' => array(
			'name' => tra('Show Author'),
            'description' => tra(''),
			'type' => 'flag',
			'default' => 'n',
		),
		'feed_forum_showAuthor' => array(
			'name' => tra('Show Author'),
            'description' => tra(''),
			'type' => 'flag',
			'default' => 'n',
		),
		'feed_tracker_showAuthor' => array(
			'name' => tra('Show Author'),
            'description' => tra(''),
			'type' => 'flag',
			'default' => 'n',
		),
		'feed_calendar_showAuthor' => array(
			'name' => tra('Show Author'),
            'description' => tra(''),
			'type' => 'flag',
			'default' => 'n',
		),
		'feed_directories_showAuthor' => array(
			'name' => tra('Show Author'),
            'description' => tra(''),
			'type' => 'flag',
			'default' => 'n',
		),
		'feed_shoutbox_showAuthor' => array(
			'name' => tra('Show Author'),
            'description' => tra(''),
			'type' => 'flag',
			'default' => 'n',
		),
		'feed_articles_homepage' => array(
			'name' => tra('Homepage URL'),
            'description' => tra(''),
			'type' => 'text',
			'size' => '60',
			'default' => '',
		),
		'feed_blogs_homepage' => array(
			'name' => tra('Homepage URL'),
            'description' => tra(''),
			'type' => 'text',
			'size' => '60',
			'default' => '',
		),
		'feed_blog_homepage' => array(
			'name' => tra('Homepage URL'),
            'description' => tra(''),
			'type' => 'text',
			'size' => '60',
			'default' => '',
		),
		'feed_image_galleries_homepage' => array(
			'name' => tra('Homepage URL'),
            'description' => tra(''),
			'type' => 'text',
			'size' => '60',
			'default' => '',
		),
		'feed_image_gallery_homepage' => array(
			'name' => tra('Homepage URL'),
            'description' => tra(''),
			'type' => 'text',
			'size' => '60',
			'default' => '',
		),
		'feed_file_galleries_homepage' => array(
			'name' => tra('Homepage URL'),
            'description' => tra(''),
			'type' => 'text',
			'size' => '60',
			'default' => '',
		),
		'feed_file_gallery_homepage' => array(
			'name' => tra('Homepage URL'),
            'description' => tra(''),
			'type' => 'text',
			'size' => '60',
			'default' => '',
		),
		'feed_wiki_homepage' => array(
			'name' => tra('Homepage URL'),
            'description' => tra(''),
			'type' => 'text',
			'size' => '60',
			'default' => '',
		),
		'feed_forum_homepage' => array(
			'name' => tra('Homepage URL'),
            'description' => tra(''),
			'type' => 'text',
			'size' => '60',
			'default' => '',
		),
		'feed_tracker_homepage' => array(
			'name' => tra('Homepage URL'),
            'description' => tra(''),
			'type' => 'text',
			'size' => '60',
			'default' => '',
		),
		'feed_calendar_homepage' => array(
			'name' => tra('Homepage URL'),
            'description' => tra(''),
			'type' => 'text',
			'size' => '60',
			'default' => '',
		),
		'feed_directories_homepage' => array(
			'name' => tra('Homepage URL'),
            'description' => tra(''),
			'type' => 'text',
			'size' => '60',
			'default' => '',
		),
		'feed_shoutbox_homepage' => array(
			'name' => tra('Homepage URL'),
            'description' => tra(''),
			'type' => 'text',
			'size' => '60',
			'default' => '',
		),
		'feed_articles_title' => array(
			'name' => tra('Title'),
            'description' => tra(''),
			'type' => 'text',
			'size' => '80',
			'default' => tra('Tiki RSS feed for articles'),
		),
		'feed_blogs_title' => array(
			'name' => tra('Title'),
            'description' => tra(''),
			'type' => 'text',
			'size' => '80',
			'default' => tra('Tiki RSS feed for weblogs'),
		),
		'feed_blog_title' => array(
			'name' => tra('Title'),
            'description' => tra('Title to be prepended to the blog title for all blogs. If this field is empty only the blog title will be used.'),
			'type' => 'text',
			'size' => '80',
			'default' => '',
		),
		'feed_image_galleries_title' => array(
			'name' => tra('Title'),
            'description' => tra(''),
			'type' => 'text',
			'size' => '80',
			'default' => tra('Tiki RSS feed for image galleries'),
		),
		'feed_image_gallery_title' => array(
			'name' => tra('Title'),
            'description' => tra(''),
			'type' => 'text',
			'size' => '80',
			'default' => tra('Tiki RSS feed for the image gallery: '),
		),
		'feed_file_galleries_title' => array(
			'name' => tra('Title'),
            'description' => tra(''),
			'type' => 'text',
			'size' => '80',
			'default' => tra('Tiki RSS feed for file galleries'),
		),
		'feed_file_gallery_title' => array(
			'name' => tra('Title'),
            'description' => tra(''),
			'type' => 'text',
			'size' => '80',
			'default' => tra('Tiki RSS feed for the file gallery: '),
		),
		'feed_wiki_title' => array(
			'name' => tra('Title'),
            'description' => tra(''),
			'type' => 'text',
			'size' => '80',
			'default' => tra('Tiki RSS feed for the wiki pages'),
		),
		'feed_forums_title' => array(
			'name' => tra('Title'),
            'description' => tra(''),
			'type' => 'text',
			'size' => '80',
			'default' => tra('Tiki RSS feed for forums'),
		),
		'feed_forum_title' => array(
			'name' => tra('Title'),
            'description' => tra(''),
			'type' => 'text',
			'size' => '80',
			'default' => tra('Tiki RSS feed for forum: '),
		),
		'feed_tracker_title' => array(
			'name' => tra('Title'),
            'description' => tra(''),
			'type' => 'text',
			'size' => '80',
			'default' => tra('Tiki RSS feed for individual trackers: '),
		),
		'feed_calendar_title' => array(
			'name' => tra('Title'),
            'description' => tra(''),
			'type' => 'text',
			'size' => '80',
			'default' => tra('Tiki RSS feed for calendars'),
		),
		'feed_directories_title' => array(
			'name' => tra('Title'),
            'description' => tra(''),
			'type' => 'text',
			'size' => '80',
			'default' => tra('Tiki RSS feed for directory sites'),
		),
		'feed_shoutbox_title' => array(
			'name' => tra('Title'),
            'description' => tra(''),
			'type' => 'text',
			'size' => '80',
			'default' => tra('Tiki RSS feed for shoutbox messages'),
		),
		'feed_articles_desc' => array(
			'name' => tra('Article RSS Description'),
			'description' => tra('Description to be published as part of the RSS feed for articles.'),
			'type' => 'textarea',
			'size' => 2,
			'default' => tra('Last articles.'),
		),
		'feed_blogs_desc' => array(
			'name' => tra('Blogs RSS Description'),
			'description' => tra('Description to be published as part of the RSS feed for blogs.'),
			'type' => 'textarea',
			'size' => 2,
			'default' => tra('Last posts to weblogs.'),
		),
		'feed_blog_desc' => array(
			'name' => tra('Blog RSS Description'),
			'description' => tra('Description to be prepended to the blog description and published as part of the RSS feed for individual blogs. If this field is empty only the blog description will be used.'),
			'type' => 'textarea',
			'size' => 2,
			'default' => '',
		),
		'feed_image_galleries_desc' => array(
			'name' => tra('Image galleries RSS Description'),
			'description' => tra('Description to be published as part of the RSS feed for image galleries.'),
			'type' => 'textarea',
			'size' => 2,
			'default' => tra('Last images uploaded to the image galleries.'),
		),
		'feed_image_gallery_desc' => array(
			'name' => tra('Individual image galleries RSS Description'),
			'description' => tra('Description to be published as part of the RSS feed for individual image galleries.'),
			'type' => 'textarea',
			'size' => 2,
			'default' => tra('Last images uploaded to this image gallery. '),
		),
		'feed_file_galleries_desc' => array(
			'name' => tra('File galleries RSS Description'),
			'description' => tra('Description to be published as part of the RSS feed for file galleries.'),
			'type' => 'textarea',
			'size' => 2,
			'default' => tra('Last files uploaded to the file galleries.'),
		),
		'feed_file_gallery_desc' => array(
			'name' => tra('Individual file galleries RSS Description'),
			'description' => tra('Description to be published as part of the RSS feed for individual file galleries.'),
			'type' => 'textarea',
			'size' => 2,
			'default' => tra('Last files uploaded to this file gallery. '),
		),
		'feed_wiki_desc' => array(
			'name' => tra('Wiki pages RSS Description'),
			'description' => tra('Description to be published as part of the RSS feed for wiki pages.'),
			'type' => 'textarea',
			'size' => 2,
			'default' => tra('Last modifications to the Wiki.'),
		),
		'feed_forums_desc' => array(
			'name' => tra('Forums RSS Description'),
			'description' => tra('Description to be published as part of the RSS feed for forums.'),
			'type' => 'textarea',
			'size' => 2,
			'default' => tra('Last topics in forums.'),
		),
		'feed_forum_desc' => array(
			'name' => tra('Individual forums RSS Description'),
			'description' => tra('Description to be published as part of the RSS feed for individual forums.'),
			'type' => 'textarea',
			'size' => 2,
			'default' => tra('Last posts to this forum. '),
		),
		'feed_tracker_desc' => array(
			'name' => tra('Individual trackers RSS Description'),
			'description' => tra('Description to be published as part of the RSS feed for individual trackers.'),
			'type' => 'textarea',
			'size' => 2,
			'default' => tra('Last items published on this tracker.'),
		),
		'feed_calendar_desc' => array(
			'name' => tra('Calendar events RSS Description'),
			'description' => tra('Description to be published as part of the RSS feed for calendar events.'),
			'type' => 'textarea',
			'size' => 2,
			'default' => tra('Upcoming Events.'),
		),
		'feed_directories_desc' => array(
			'name' => tra('Directories RSS Description'),
			'description' => tra('Description to be published as part of the RSS feed for directories.'),
			'type' => 'textarea',
			'size' => 2,
			'default' => tra('Last sites.'),
		),
		'feed_shoutbox_desc' => array(
			'name' => tra('Shoutbox RSS Description'),
			'description' => tra('Description to be published as part of the RSS feed for shoutbox messages.'),
			'type' => 'textarea',
			'size' => 2,
			'default' => tra('Last shoutbox messages.'),
		),
		'feed_tracker_labels' => array(
			'name' => tra('Tracker labels'),
			'description' => tra('Include tracker field labels in the RSS output'),
			'type' => 'flag',
			'dependencies' => array(
				'feature_trackers',
			),
			'default' => 'y',
		),
	);
}

