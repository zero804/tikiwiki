<?php
// (c) Copyright 2002-2011 by authors of the Tiki Wiki CMS Groupware Project
// 
// All Rights Reserved. See copyright.txt for details and a complete list of authors.
// Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See license.txt for details.
// $Id$

/**
 * Handler class for url fields:
 * 
 * - url key ~L~
 */
class Tracker_Field_Url extends Tracker_Field_Abstract
{
	function getFieldData(array $requestData = array())
	{
		$ins_id = $this->getInsertId();

		return array(
			'value' => (isset($requestData[$ins_id]))
				? $requestData[$ins_id]
				: $this->getValue(),
		);
	}
	
	function renderOutput($context = array())
	{
		$smarty = TikiLib::lib('smarty');

		$url = $this->getConfiguration('value');
		require_once $smarty->_get_plugin_filepath('function', 'object_link');

		if ($url) {
			return smarty_function_object_link(array(
				'type' => 'external',
				'id' => $url,
			), $smarty);
		}
	}

	function renderInput($context = array())
	{
		return $this->renderTemplate("trackerinput/url.tpl", $context);
	}
}

