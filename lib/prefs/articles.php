<?php
// (c) Copyright 2002-2016 by authors of the Tiki Wiki CMS Groupware Project
// 
// All Rights Reserved. See copyright.txt for details and a complete list of authors.
// Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See license.txt for details.
// $Id$

function prefs_articles_list()
{
	return array(
		'articles_feature_copyrights' => array(
			'name' => tra('Articles'),
            'description' => tra('Enables adding of copyright information for articles'),
			'type' => 'flag',
			'dependencies' => array(
				'feature_articles',
			),
			'default' => 'n',
		),
	);
}
