<?php
// (c) Copyright 2002-2011 by authors of the Tiki Wiki CMS Groupware Project
// 
// All Rights Reserved. See copyright.txt for details and a complete list of authors.
// Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See license.txt for details.
// $Id$

//this script may only be included - so its better to die if called directly.
if (strpos($_SERVER["SCRIPT_NAME"],basename(__FILE__)) !== false) {
  header("location: index.php");
  exit;         
}  

function smarty_function_trackeroutput( $params, $smarty ) {
	$trklib = TikiLib::lib('trk');

	$field = $params['field'];
	$item = isset($params['item']) ? $params['item'] : array();
	
	$item[$field['fieldId']] = $field['value'];
	$handler = $trklib->get_field_handler($field, $item);

	if (isset($params['process']) && $params['process'] == 'y') {
		$field = array_merge($field, $handler->getFieldData($field));
		$handler = $trklib->get_field_handler($field, $item);
	}

	if ($handler) {
		$context = $params;
		unset($context['item']);
		unset($context['field']);
		if (!isset($context['list_mode'])) {
			$context['list_mode'] = 'n';
		} 
		$r = $handler->renderOutput($context);
		return $r;
	}
}

