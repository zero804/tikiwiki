<?php
// (c) Copyright 2002-2013 by authors of the Tiki Wiki CMS Groupware Project
// 
// All Rights Reserved. See copyright.txt for details and a complete list of authors.
// Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See license.txt for details.
// $Id$

class Services_Exception_Denied extends Services_Exception
{
	function __construct($message = null)
	{
		if (is_null($message)) {
			$message = tr('Permission denied');
		}

		parent::__construct($message, 403);
	}
}

