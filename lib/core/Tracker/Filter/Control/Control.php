<?php
// (c) Copyright 2002-2014 by authors of the Tiki Wiki CMS Groupware Project
// 
// All Rights Reserved. See copyright.txt for details and a complete list of authors.
// Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See license.txt for details.
// $Id$

namespace Tracker\Filter\Control;

interface Control
{
	/**
	 * Collect the input values for the controlled field.
	 */
	function applyInput(\JitFilter $input);

	/**
	 * Provide the portion of the query arguments relating to this field.
	 * Will be used to generate links.
	 *
	 * Provided as a map to be handled by http_build_query()
	 */
	function getQueryArguments();

	/**
	 * Provide the ID of the primary field to be referenced by the label.
	 */
	function getId();
	
	/**
	 * Render the field within a form.
	 */
	function __toString();
}
