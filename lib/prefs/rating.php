<?php
// (c) Copyright 2002-2013 by authors of the Tiki Wiki CMS Groupware Project
// 
// All Rights Reserved. See copyright.txt for details and a complete list of authors.
// Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See license.txt for details.
// $Id$

function prefs_rating_list()
{
	return array(
		'rating_advanced' => array(
			'name' => tra('Advanced Rating'),
			'description' => tra('Rating system allowing for options and calculation method to be configured.'),
			'type' => 'flag',
			'help' => 'Advanced+Rating',
			'default' => 'n',
			'admin' => 'rating',
			'tags' => array('advanced'),
		),
		'rating_recalculation' => array(
			'name' => tra('Rating recalculation mode'),
			'description' => tra('Determines when and how rating aggregates are recalculated. Depending on the site load, some options may be preferred to others. On large volume sites, it should be done as a cron job. On vote recalculation may be inaccurate if rating calculation depends time.'),
			'type' => 'list',
			'options' => array(
				'vote' => tra('Recalculate on vote'),
				'randomload' => tra('Randomly recalculate oldest ratings (on load)'),
				'randomvote' => tra('Randomly recalculate oldest ratings (on vote)'),
				'period' => tra('Cron job (must be set manually)'),
				'indexing' => tr('Before any attempt to re-index the object'),
			),
			'default' => 'vote',
		),
		'rating_recalculation_odd' => array(
			'name' => tra('Recalculation odds (1 in X)'),
			'description' => tra('Dice roll performed on every request. When successful, will recalculate a certain number of votes.'),
			'type' => 'text',
			'size' => 5,
			'filter' => 'digits',
			'default' => '100',
		),
		'rating_recalculation_count' => array(
			'name' => tra('Recalculation count'),
			'description' => tra('How many ratings should be recalculated when odds strike.'),
			'type' => 'text',
			'size' => 5,
			'filter' => 'digits',
			'default' => '100',
		),
		'rating_smileys' => array(
			'name' => tr('Smiley Ratings'),
			'description' => tr('Displays a smiley face for simple ratings system. Only works with 0-11, depending on what you have set. An easier user-interface to use, like emoticons.'),
			'type' => 'flag',
			'options' => array(
				'' => tr('Disabled'),
				'y' => tr('Enabled'),
			),
			'default' => ''
		),
	);
}

