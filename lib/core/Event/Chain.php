<?php
// (c) Copyright 2002-2013 by authors of the Tiki Wiki CMS Groupware Project
// 
// All Rights Reserved. See copyright.txt for details and a complete list of authors.
// Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See license.txt for details.
// $Id$

class Event_Chain
{
	private $event;
	private $manager;

	function __construct(Event_Manager $manager, $eventName)
	{
		$this->event = $eventName;
		$this->manager = $manager;
	}

	function __invoke($arguments, $priority)
	{
		$this->manager->internalTrigger($this->event, $arguments, $priority);
	}

	function getEventName()
	{
		return $this->event;
	}
}

