<?php
// (c) Copyright 2002-2011 by authors of the Tiki Wiki CMS Groupware Project
// 
// All Rights Reserved. See copyright.txt for details and a complete list of authors.
// Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See license.txt for details.
// $Id$

/**
 * Handler class for ItemLink
 * 
 * Letter key: ~r~
 *
 */
class Tracker_Field_ItemLink extends Tracker_Field_Abstract
{
	function getFieldData(array $requestData = array())
	{
		$data = $this->getLinkData($requestData, $this->getInsertId());

		$value = $this->getValue();
		if ($value && $this->getOption(2)) {
			$data["linkId"] = TikiLib::lib('trk')->get_item_id($this->getOption(0), $this->getOption(1), $value);
		}

		return $data;
	}

	function renderInput($context = array())
	{
		return $this->renderTemplate('trackerinput/itemlink.tpl', array());
	}

	function renderOutput($context = array())
	{
		$item = $this->getConfiguration('linkId');
		$label = $this->getConfiguration('value');
		require_once TikiLib::lib('smarty')->_get_plugin_filepath('function', 'object_link');

		if ($item) {
			return smarty_function_object_link(array(
				'type' => 'trackeritem',
				'id' => $item,
				'title' => $label,
			));
		}
	}

	private function getLinkData($requestData, $string_id)
	{
		$data = array(
			'value' => isset($requestData[$string_id]) ? $requestData[$string_id] : $this->getValue(),
		);

		if (!$this->getOption(3)) {	//no displayedFieldsList
			$data['list'] = array_unique(
				TikiLib::lib('trk')->get_all_items(
					$this->getOption(0),
					$this->getOption(1),
					$this->getOption(4, 'poc'),
					false
				)
			);
		} else {
			$data['list'] = TikiLib::lib('trk')->get_all_items(
				$this->getOption(0),
				$this->getOption(1),
				$this->getOption(4, 'poc'),
				false
			);
			$data['listdisplay'] = array_unique(
				TikiLib::lib('trk')->concat_all_items_from_fieldslist(
					$this->getOption(0),
					$this->getOption(3),
					$this->getOption(4, 'poc')
				)
			);
		}

		return $data;
	}
}

