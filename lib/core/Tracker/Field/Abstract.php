<?php
// (c) Copyright 2002-2011 by authors of the Tiki Wiki CMS Groupware Project
// 
// All Rights Reserved. See copyright.txt for details and a complete list of authors.
// Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See license.txt for details.
// $Id$

abstract class Tracker_Field_Abstract implements Tracker_Field_Interface, Tracker_Field_Indexable
{
	private $definition;
	private $itemData;
	private $trackerDefinition;

	function __construct($fieldInfo, $itemData, $trackerDefinition)
	{
		$this->definition = $fieldInfo;
		$this->itemData = $itemData;
		$this->trackerDefinition = $trackerDefinition;
	}

	public function renderInput($context = array())
	{
		return 'Not implemented';
	}

	public function renderOutput($context = array())
	{
		if ($this->isLink($context)) {
			$itemId = $this->getItemId();
			$query = array_merge($_GET, array(
				'itemId' => $itemId,
				'show' => 'view',
			));

			$arguments = array(
				'class' => 'tablename',
				'href' => 'tiki-view_tracker_item.php?' . http_build_query($query, '', '&'),
			);

			$geolocation = TikiLib::lib('geo')->get_coordinates('trackeritem', $itemId);

			if ($geolocation) {
				$arguments['class'] .= ' geolocated';
				$arguments['data-geo-lat'] = $geolocation['lat'];
				$arguments['data-geo-lon'] = $geolocation['lon'];
			}
			
			if (!empty($context['url']) && strpos($context['url'], 'itemId') !== false) {
				$context['url'] = preg_replace('/([&|\?])itemId=?[^&]*/', '\\1itemId=' . $itemId, $context['url']);
				$arguments['href'] = $context['url'];
			}

			$pre = '<a';
			foreach ($arguments as $key => $value) {
				$pre .= ' ' . $key . '="' . htmlentities($value, ENT_QUOTES, 'UTF-8') . '"';
			}

			if (isset($context['showpopup']) && $context['showpopup'] == 'y') {
				$popup = $this->renderPopup();

				if ($popup) {
					$pre .= " $popup";
				}
			}

			$pre .= '>';
			$post = '</a>';

			return $pre . $this->renderInnerOutput($context) . $post;
		} else {
			return $this->renderInnerOutput($context);
		}
	}

	function watchCompare($old, $new)
	{
		$name = $this->getConfiguration('name');
		$is_visible = $this->getConfiguration('isHidden', 'n') == 'n';

		if (! $is_visible) {
			return;
		}

		if ($old) {
			// split old value by lines
			$lines = explode("\n", $old);
			// mark every old value line with standard email reply character
			$old_value_lines = '';
			foreach ($lines as $line) {
				$old_value_lines .= '> '.$line;
			}
			return "[-[$name]-]:\n--[Old]--:\n$old_value_lines\n\n*-[New]-*:\n$new";
		} else {
			return "[-[$name]-]:\n$new";
		}
	}

	private function isLink($context = array())
	{
		$type = $this->getConfiguration('type');
		if ($type == 'x') {
			return false;
		}

		if ($this->getConfiguration('showlinks', 'y') == 'n') {
			return false;
		}

		if (isset($context['showlinks']) && $context['showlinks'] == 'n') {
			return false;
		}

		if (isset($context['list_mode']) && $context['list_mode'] == 'csv') {
			return false;
		}

		$itemId = $this->getItemId();
		if (empty($itemId)) {
			return false;
		}
		$itemObject = Tracker_Item::fromInfo($this->itemData);

		$status = $this->getData('status');

		if ($this->getConfiguration('isMain', 'n') == 'y' 
			&& ($itemObject->canViewField($this->definition['fieldId'])	|| $itemObject->getPerm('comment_tracker_items'))
			// TODO : Re-introduce conditions, required information not available at this time.
			// or ($tracker_info.writerCanModify eq 'y' and $user and $my eq $user)
			// or ($tracker_info.writerGroupCanModify eq 'y' and $group and $ours eq $group))
			) {
			return (bool) $this->getItemId();
		}

		return false;
	}

	private function renderPopup()
	{
		$fields = $this->trackerDefinition->getPopupFields();

		if (empty($fields)) {
			return null;
		}

		$factory = $this->trackerDefinition->getFieldFactory();

		$popupFields = array();

		foreach ($fields as $id) {
			$field = $this->trackerDefinition->getField($id);
			
			if (!isset($this->itemData[$field['fieldId']])) {
				foreach($this->itemData['field_values'] as $fieldVal) {
					if ($fieldVal['fieldId'] == $id) {
						if (isset($fieldVal['value'])) {
							$this->itemData[$field['fieldId']] = $fieldVal['value'];
						}
					}
				}
			}
			$handler = $factory->getHandler($field, $this->itemData);

			if ($handler) {
				$field = array_merge($field, $handler->getFieldData());
				$popupFields[] = $field;
			}
		}

		$smarty = TikiLib::lib('smarty');
		$smarty->assign('popupFields', $popupFields);
		$smarty->assign('popupItem', $this->itemData);
		return trim($smarty->fetch('trackeroutput/popup.tpl'));
	}

	/**
	 * return the html for the output of a field without link, preprend...
	 * @param
	 * @return html
	 */
	protected function renderInnerOutput($context = array())
	{
		if (isset($context['list_mode']) && $context['list_mode'] === 'csv') {
			$val = $this->getConfiguration('value');
			$default = array('CR'=>'%%%', 'delimitorL'=>'"', 'delimitorR'=>'"');
			$context = array_merge($default, $context);
			$val = str_replace(array("\r\n", "\n", '<br />', $context['delimitorL'], $context['delimitorR']), array($context['CR'], $context['CR'], $context['CR'], $context['delimitorL'].$context['delimitorL'], $context['delimitorR'].$context['delimitorR']), $val);
			return $val;
		} else {
			return $this->getConfiguration('pvalue', $this->getConfiguration('value'));
		}
	}

	protected function getInsertId()
	{
		return 'ins_' . $this->definition['fieldId'];
	}

	protected function getFilterId()
	{
		return 'filter_' . $this->definition['fieldId'];
	}

	protected function getConfiguration($key, $default = false)
	{
		return isset($this->definition[$key]) ? $this->definition[$key] : $default;
	}

	protected function getValue($default = '')
	{
		$key = $this->getConfiguration('fieldId');
		
		if (isset($this->itemData[$key])) {
			$value =$this->itemData[$key];
		} else {
			$value = isset($this->itemData[$key]) ? $this->itemData[$key] : null;
		}

		return $value === null ? $default : $value;
	}

	protected function getItemId()
	{
		return $this->getData('itemId');
	}

	protected function getData($key, $default = false)
	{
		return isset($this->itemData[$key]) ? $this->itemData[$key] : $default;
	}

	/**
	 * Returns an option from the options array based on the numeric position.
	 */
	protected function getOption($number, $default = false)
	{
		if (! is_numeric($number)) {
			$type = $this->getConfiguration('type');
			$class = get_class($this);

			$info = call_user_func(array($class, 'getTypes'));
			$params = array_keys($info[$type]['params']);

			$number = array_search($number, $params);

			if ($number === false) {
				return $default;
			}
		}

		return isset($this->definition['options_array'][(int) $number]) ?
			$this->definition['options_array'][(int) $number] :
			$default;
	}

	protected function getTrackerDefinition()
	{
		return $this->trackerDefinition;
	}

	protected function getItemData()
	{
		return $this->itemData;
	}

	protected function renderTemplate($file, $context = array(), $data = array())
	{
		$smarty = TikiLib::lib('smarty');
		$smarty->assign('field', $this->definition);
		$smarty->assign('context', $context);
		$smarty->assign('item', $this->getItemData());
		$smarty->assign('data', $data);

		return $smarty->fetch($file, $file);
	}

	function getDocumentPart($baseKey, Search_Type_Factory_Interface $typeFactory)
	{
		return array(
			$baseKey => $typeFactory->sortable($this->getValue()),
		);
	}

	function getProvidedFields($baseKey)
	{
		return array($baseKey);
	}

	function getGlobalFields($baseKey)
	{
		return array($baseKey => true);
	}
}


