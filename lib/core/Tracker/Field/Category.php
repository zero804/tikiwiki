<?php
// (c) Copyright 2002-2011 by authors of the Tiki Wiki CMS Groupware Project
// 
// All Rights Reserved. See copyright.txt for details and a complete list of authors.
// Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See license.txt for details.
// $Id$

/**
 * Handler class for Category
 * 
 * Letter key: ~e~
 *
 */
class Tracker_Field_Category extends Tracker_Field_Abstract
{
	function getFieldData(array $requestData = array())
	{
		$key = 'ins_' . $this->getConfiguration('fieldId');
		$parentId = $this->getOption(0);

		if (isset($requestData[$key]) && is_array($requestData[$key])) {
			$selected = $requestData[$key];
		} elseif (!empty($requestData)) {
			$selected = array();		
		} else {
			$selected = $this->getCategories();
		}

		$categories = $this->getApplicableCategories();

		$data = array(
			'value' => '',
			'selected_categories' => array_intersect($selected, $this->getIds($categories)),
			$parentId => $categories,	// TODO kil?
			'list' => $categories,
			'cat' => array(),
			'categs' => array(),
		);

		foreach($data[$parentId] as $category) {
			$id = $category['categId'];
			if (in_array($id, $selected)) {
				$data['cat'][$id] = 'y';
				$data['categs'][] = $category;
			}
		}

		return $data;
	}

	function renderInput($context = array())
	{
		return $this->renderTemplate('trackerinput/category.tpl', $context);
	}

	function renderInnerOutput($context = array())
	{
		$selected_categories = $this->getConfiguration('selected_categories');
		$categories = $this->getConfiguration('list');
		$ret = '';
		foreach ($selected_categories as $categId) {
			if (!empty($ret))
				$ret .= '<br />';
			foreach ($categories as $category) {
				if ($category['categId'] == $categId) {
					$ret .= $category['name'];
					break;
				}
			}
		}
		return $ret;
	}

	private function getIds($categories)
	{
		$validIds = array();
		foreach ($categories as $c) {
			$validIds[] = $c['categId'];
		}

		return $validIds;
	}

	private function getApplicableCategories()
	{
		$parentId = $this->getOption(0);
		$descends = $this->getOption(3) == 1;

		return TikiLib::lib('categ')->get_viewable_child_categories($parentId, $descends);
	}

	private function getCategories()
	{
		return TikiLib::lib('categ')->get_object_categories('trackeritem', $this->getItemId());
	}
}

