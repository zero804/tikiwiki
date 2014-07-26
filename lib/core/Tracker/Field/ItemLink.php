<?php
// (c) Copyright 2002-2013 by authors of the Tiki Wiki CMS Groupware Project
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
class Tracker_Field_ItemLink extends Tracker_Field_Abstract implements Tracker_Field_Synchronizable
{
	const CASCADE_NONE = 0;
	const CASCADE_CATEG = 1;
	const CASCADE_STATUS = 2;
	const CASCADE_DELETE = 4;

	public static function getTypes()
	{
		return array(
			'r' => array(
				'name' => tr('Item Link'),
				'description' => tr('Link to an other item, similar to a foreign key.'),
				'help' => 'Items List and Item Link Tracker Fields',
				'prefs' => array('trackerfield_itemlink'),
				'tags' => array('advanced'),
				'default' => 'y',
				'params' => array(
					'trackerId' => array(
						'name' => tr('Tracker ID'),
						'description' => tr('Tracker to link to'),
						'filter' => 'int',
						'legacy_index' => 0,
						'profile_reference' => 'tracker',
					),
					'fieldId' => array(
						'name' => tr('Field ID'),
						'description' => tr('Default field to display'),
						'filter' => 'int',
						'legacy_index' => 1,
						'profile_reference' => 'tracker_field',
					),
					'linkToItem' => array(
						'name' => tr('Display'),
						'description' => tr('How the link to the item should be rendered'),
						'filter' => 'int',
						'options' => array(
							0 => tr('Value'),
							1 => tr('Link'),
						),
						'legacy_index' => 2,
					),
					'displayFieldsList' => array(
						'name' => tr('Multiple Fields'),
						'description' => tr('Display the values from multiple fields instead of a single one, separated by |'),
						'separator' => '|',
						'filter' => 'int',
						'legacy_index' => 3,
						'profile_reference' => 'tracker_field',
					),
					'status' => array(
						'name' => tr('Status Filter'),
						'description' => tr('Limit the available items to a selected set'),
						'filter' => 'alpha',
						'options' => array(
							'opc' => tr('all'),
							'o' => tr('open'),
							'p' => tr('pending'),
							'c' => tr('closed'),
							'op' => tr('open, pending'),
							'pc' => tr('pending, closed'),
						),
						'legacy_index' => 4,
					),
					'linkPage' => array(
						'name' => tr('Link Page'),
						'description' => tr('Link to a wiki page instead of directly to the item'),
						'filter' => 'pagename',
						'legacy_index' => 5,
						'profile_reference' => 'wiki_page',
					),
					'addItems' => array(
						'name' => tr('Add Items'),
						'description' => tr('Display text to allow new items to be added - e.g. "Add item..." (requires jQuery-UI)'),
						'filter' => 'text',
						'legacy_index' => 6,
					),
					'addItemsWikiTpl' => array(
						'name' => tr('Add Item Template Page'),
						'description' => tr('Wiki page to use as a Pretty Tracker template'),
						'filter' => 'pagename',
						'legacy_index' => 7,
						'profile_reference' => 'wiki_page',
					),
					'preSelectFieldHere' => array(
						'name' => tr('Preselect item based on value in this field'),
						'description' => tr('Preselect item based on value in specified field ID of item being edited'),
						'filter' => 'int',
						'legacy_index' => 8,
					),
					'preSelectFieldThere' => array(
						'name' => tr('Preselect based on value in this remote field'),
						'description' => tr('Match preselect item with this field ID in tracker that is being linked to'),
						'filter' => 'int',
						'legacy_index' => 9,
						'profile_reference' => 'tracker_field',
					),
					'preSelectFieldMethod' => array(
						'name' => tr('Preselection matching method'),
						'description' => tr('Method to use to match fields for preselection purposes'),
						'filter' => 'alpha',
						'options' => array(
							'exact' => tr('Exact Match'),
							'partial' => tr('Field here is part of field there'),
							'domain' => tr('Match domain, used for URL fields'),
							'crossSelect' => tr('Cross select. Load all matching items in the remote tracker'),
						),
						'legacy_index' => 10,
					),
					'displayOneItem' => array(
						'name' => tr('One item per value'),
						'description' => tr('Display only one random item per label'),
						'filter' => 'alpha',
						'options' => array(
							'multi' => tr('Displays all the items for a same label with a notation value (itemId)'),
							'one' => tr('Only one random item for each label'),
						),
						'legacy_index' => 11,
					),
					'selectMultipleValues' => array(
						'name' => tr('Select multiple values'),
						'description' => tr('Allow the user to select multiple values'),
						'filter' => 'int',
						'options' => array(
							0 => tr('No'),
							1 => tr('Yes'),
						),
						'legacy_index' => 12,
					),
					'indexRemote' => array(
						'name' => tr('Index remote fields'),
						'description' => tr('Index one or multiple fields from the master tracker along with the child, separated by |'),
						'separator' => '|',
						'filter' => 'int',
						'legacy_index' => 13,
						'profile_reference' => 'tracker_field',
					),
					'cascade' => array(
						'name' => tr('Cascade actions'),
						'description' => tr("Elements to cascade when the master is updated or deleted. Categories may conflict if multiple item links are used to different items attempting to manage the same categories. Same for status."),
						'filter' => 'int',
						'options' => array(
							self::CASCADE_NONE => tr('No'),
							self::CASCADE_CATEG => tr('Categories'),
							self::CASCADE_STATUS => tr('Status'),
							self::CASCADE_DELETE => tr('Delete'),
							(self::CASCADE_CATEG | self::CASCADE_STATUS) => tr('Categories and status'),
							(self::CASCADE_CATEG | self::CASCADE_DELETE) => tr('Categories and delete'),
							(self::CASCADE_DELETE | self::CASCADE_STATUS) => tr('Delete and status'),
							(self::CASCADE_CATEG | self::CASCADE_STATUS | self::CASCADE_DELETE) => tr('All'),
						),
						'legacy_index' => 14,
					),
				),
			),
		);
	}

	function getFieldData(array $requestData = array())
	{
		$string_id = $this->getInsertId();
		$data = array(
			'value' => isset($requestData[$string_id]) ? $requestData[$string_id] : $this->getValue(),
		);

		if ($this->getOption('selectMultipleValues') && ! is_array($data['value'])) {
			$data['value'] = explode(',', $data['value']);
		}

		return $data;
	}

	function renderInput($context = array())
	{
		$data = array(
			'list' => $this->getItemList(),
		);

		if ($this->getOption('addItems') && empty($context['in_ajax_form'])) {

			$context['in_ajax_form'] = true;

			require_once 'lib/wiki-plugins/wikiplugin_tracker.php';

			$params = array(
				'trackerId' => $this->getOption('trackerId'),
				'ignoreRequestItemId' => 'y',
				'_ajax_form_ins_id' => $this->getInsertId(),
			);

			if ($this->getOption('addItemsWikiTpl')) {
				$params['wiki'] = $this->getOption('addItemsWikiTpl');
			}
			$form = wikiplugin_tracker('', $params);

			$form = preg_replace(array('/<!--.*?-->/', '/\s+/', '/^~np~/', '/~\/np~/'), array('', ' ', '', ''), $form);	// remove comments etc

			if ($displayFieldsList = $this->getDisplayFieldsListArray()) {
				$displayFieldId = $displayFieldsList[0];
				if (is_string($displayFieldId) && strpos($displayFieldId, '|') !== false) {
					$displayFieldId = substr($displayFieldId, 0, strpos($displayFieldId, '|'));
				}
			} else {
				$displayFieldId = $this->getOption('fieldId');
			}

			TikiLib::lib('header')->add_jq_onready(
				'$("select[name=' . $this->getInsertId() . ']").change(function(e, val) {
	var $select = $(this);
	if ($select.val() == -1) {
		var $d = $("<div id=\'add_dialog_' . $this->getInsertId() . '\' style=\'display:none\'/>")
			.html(' . json_encode($form) . ')
			.appendTo(document.body);

		var w = $d.width() * 1.4;
		var h = $d.height() * 2.0;
		if ($(document.body).width() < w) {
			w = $(document.body).width() * 0.8;
		}
		if ($(document.body).height() < h) {
			h = $(document.body).height() * 0.8;
		}

		$d.dialog({
				width: w,
				height: h,
				title: '.json_encode($this->getOption('addItems')).',
				modal: true,
				buttons: {
					"Add": function() {
						var $f = $("form", this).append($("<input type=\'hidden\' name=\'ajax_add\' value=\'1\' />"));
						if (typeof $f.valid === "function" && $f.valid()) {
							ajaxLoadingShow($f);
							$.post( $f.attr("action"), $f.serialize(), function(data, status) {
								if (data && data.data) {
									$.each(data.data, function (i, a) {
										if ( a && a.fieldId == '. intval($displayFieldId) .' ) {
											if ("string" !== typeof a.value && a.pvalue) {
												a.value = a.pvalue;
											}

											var $o = $("<option/>")
												.val(data.itemId)
												.text(a.value);
											$select
												.append($o)
												.val(data.itemId);
										}
									});
								}
								ajaxLoadingHide();
								$d.dialog( "close" );
								$select.trigger("chosen:updated");
								return;
							}, "json");
						}
					},
					Cancel: function() {
						$select.val("");
						$( this ).dialog( "close" );
					}
				},
				create: function(event, ui) {
					 ajaxTrackerFormInit_' . $this->getInsertId() . '();
				}
			}).find(".input_submit_container").remove();
	}
});
'
);

		}

		$data['selectMultipleValues'] = (bool) $this->getOption('selectMultipleValues');

		// 'crossSelect' overrides the preselection reference, which is enabled, when a cross reference Item Link <-> Item Link
		//	When selecting a value another item link can provide the relation, then the cross link can point to several records having the same linked value.
		//	Example Contact and Report links to a Company. Report also links to Contact. When selecting Contact, Only Contacts in the same company as the Report is linked to, should be made visible.
		//	When 'crossSelect' is enabled
		//		1) The dropdown list is no longer disabled (else disabled)
		//		2) All rows in the remote tracker matching the criterea are displayed in the dropdown list (else only 1 row is displayed)
		$method = $this->getOption('preSelectFieldMethod');
		if ($method == 'crossSelect') {
			$data['crossSelect'] = 'y';
		} else {
			$data['crossSelect'] = 'n';
		}

		// Prepare for 'crossSelect'
		$linkValue = false;		// Value which links the tracker items
		if ($data['crossSelect'] === 'y') {
			// Check if itemId is set / used.
			// If not, it must be set here
			$itemData = $this->getItemData();
			if (empty($itemData['itemId'])) {
				if (!empty($_REQUEST['itemId'])) {
					$linkValue = $_REQUEST['itemId'];
				}
			} else {
				$linkValue = $itemData['itemId'];
			}
		}

		if ($preselection = $this->getPreselection($linkValue)) {
			$data['preselection'] = $preselection;
		} else {
			$data['preselection'] = '';
		}

		$data['filter'] = $this->buildFilter();

		if ($data['crossSelect'] === 'y') {
			$fullList = $data['list'];
			if (!empty($preselection) && is_array($preselection)) {
				$data['remoteData'] = array_intersect_key($fullList, array_flip($preselection));
			} else {
				$data['remoteData'] = $fullList;
			}
		}
		return $this->renderTemplate('trackerinput/itemlink.tpl', $context, $data);
	}

	private function buildFilter()
	{
		return array(
			'tracker_id' => $this->getOption('trackerId'),
		);
	}

	function renderOutput($context = array())
	{
		$smarty = TikiLib::lib('smarty');

		$item = $this->getValue();

		if (! is_array($item)) {
			// single value item field
			$items = array($item);
		} else {
			// item field has multiple values
			$items = $item;
		}

		$labels = array();
		foreach ($items as $i) {
			$labels[] = $this->getItemLabel($i, $context);
		}
		$label = implode(', ', $labels);

		if ($item && !is_array($item) && $context['list_mode'] !== 'csv' && $this->getOption('fieldId')) {
			$smarty->loadPlugin('smarty_function_object_link');

			if ( $this->getOption('linkPage') ) {
				$link = smarty_function_object_link(
					array(
						'type' => 'wiki page',
						'id' => $this->getOption('linkPage') . '&itemId=' . $item,	// add itemId param TODO properly
						'title' => $label,
					),
					$smarty
				);
				// decode & and = chars
				return str_replace(array('%26','%3D'), array('&','='), $link);
			} else if ($this->getOption('linkToItem')) {
				return smarty_function_object_link(array('type' => 'trackeritem',	'id' => $item,	'title' => $label), $smarty);
			} else {
				return $label;
			}
		} elseif ($context['list_mode'] == 'csv' && $item) {
			if ($label) {
				return $label;
			} else {
				return $item;
			}
		} elseif ($label) {
			return $label;
		}
	}

	function getDocumentPart(Search_Type_Factory_Interface $typeFactory)
	{
		$item = $this->getValue();
		$label = $this->getItemLabel($item);
		$baseKey = $this->getBaseKey();

		$out = array(
			$baseKey => $typeFactory->identifier($item),
			"{$baseKey}_text" => $typeFactory->sortable($label),
		);

		$indexRemote = array_filter($this->getOption('indexRemote'));

		if (count($indexRemote) && is_numeric($item)) {
			$utilities = new Services_Tracker_Utilities;
			$trackerId = $this->getOption('trackerId');
			$itemData = $utilities->getItem($trackerId, $item);

			$definition = Tracker_Definition::get($trackerId);
			foreach ($indexRemote as $fieldId) {
				$field = $definition->getField($fieldId);
				$permName = $field['permName'];

				$out["{$baseKey}_{$permName}"] = $typeFactory->sortable($itemData['fields'][$permName]);
			}
		}

		return $out;
	}

	function getProvidedFields()
	{
		$baseKey = $this->getBaseKey();
		$fields = array($baseKey, "{$baseKey}_text");

		$trackerId = $this->getOption('trackerId');
		$indexRemote = array_filter($this->getOption('indexRemote'));

		if (count($indexRemote)) {
			if ($definition = Tracker_Definition::get($trackerId)) {
				foreach ($indexRemote as $fieldId) {
					$field = $definition->getField($fieldId);
					$permName = $field['permName'];

					$fields[] = "{$baseKey}_{$permName}";
				}
			}
		}

		return $fields;
	}

	function getGlobalFields()
	{
		return array();
	}

	function getItemLabel($itemId, $context = array('list_mode' => ''))
	{
		$trklib = TikiLib::lib('trk');
		$item = $trklib->get_tracker_item($itemId);

		if (! $item) {
			return '';
		}

		$trackerId = (int) $this->getOption('trackerId');
		$status = $this->getOption('status', 'opc');

		$parts = array();

		if ($fields = $this->getDisplayFieldsListArray()) {
			foreach ($fields as $fieldId) {
				if (isset($item[$fieldId])) {
					$parts[] = $fieldId;
				}
			}
		} else {
			$fieldId = $this->getOption('fieldId');

			if (isset($item[$fieldId])) {
				$parts[] = $fieldId;
			}
		}


		if (count($parts)) {
			return $trklib->concat_item_from_fieldslist($trackerId, $itemId, $parts, $status, ' ', $context['list_mode'], $this->getOption('linkToItem'));
		} else {
			return TikiLib::lib('object')->get_title('trackeritem', $itemId);
		}
	}

	private function getItemList()
	{
		if ($displayFieldsList = $this->getDisplayFieldsListArray()) {
			$list = TikiLib::lib('trk')->concat_all_items_from_fieldslist(
				$this->getOption('trackerId'),
				$displayFieldsList,
				$this->getOption('status', 'opc'),
                ' ',
				true
			);
		} else {
			$list = TikiLib::lib('trk')->get_all_items(
				$this->getOption('trackerId'),
				$this->getOption('fieldId'),
				$this->getOption('status', 'opc'),
				false
			);
		}

		$list = $this->handleDuplicates($list);

		if ($this->getOption('addItems')) {
			$list['-1'] = $this->getOption('addItems');
		}

		return $list;
	}

	private function handleDuplicates($list)
	{
		if ($this->getOption('displayOneItem') != 'multi') {
			return array_unique($list);
		} elseif (array_unique($list) != $list) {
			$newlist = array();
			foreach ($list as $itemId => $label) {
				if (in_array($label, $newlist)) {
					$label = $label . " ($itemId)";
				}
				$newlist[$itemId] = $label;
			}

			return $newlist;
		} else {
			return $list;
		}
	}

	function importRemote($value)
	{
		return $value;
	}

	function exportRemote($value)
	{
		return $value;
	}

	function importRemoteField(array $info, array $syncInfo)
	{
		$sourceOptions = explode(',', $info['options']);
		$trackerId = isset($sourceOptions[0]) ? (int) $sourceOptions[0] : 0;
		$fieldId = isset($sourceOptions[1]) ? (int) $sourceOptions[1] : 0;
		$status = isset($sourceOptions[4]) ? (int) $sourceOptions[4] : 'opc';

		$info['type'] = 'd';
		$info['options'] = $this->getRemoteItemLinks($syncInfo, $trackerId, $fieldId, $status);

		return $info;
	}

	private function getRemoteItemLinks($syncInfo, $trackerId, $fieldId, $status)
	{
		$controller = new Services_RemoteController($syncInfo['provider'], 'tracker');
		$items = $controller->getResultLoader('list_items', array('trackerId' => $trackerId, 'status' => $status));
		$result = $controller->edit_field(array('trackerId' => $trackerId, 'fieldId' => $fieldId));

		$permName = $result['field']['permName'];
		if (empty($permName)) {
			return '';
		}

		$parts = array();
		foreach ($items as $item) {
			$parts[] = $item['itemId'] . '=' . $item['fields'][$permName];
		}

		return implode(',', $parts);
	}

	private function getPreselection($linkValue = false)
	{
		$trklib = TikiLib::lib('trk');

		$localField = $this->getOption('preSelectFieldHere');
		$remoteField = $this->getOption('preSelectFieldThere');
		$method = $this->getOption('preSelectFieldMethod');
		$localTrackerId = $this->getConfiguration('trackerId');
		$remoteTrackerId = $this->getOption('trackerId');

		$localValue = $trklib->get_item_value($localTrackerId, $this->getItemId(), $localField);

		if ($method == 'domain') {
			if (! preg_match('@^(?:http://)?([^/]+)@i', $localValue, $matches)) {
				return '';
			}
			$host = $matches[1];
			preg_match('/[^.]+\.[^.]+$/', $host, $matches);
			$domain = $matches[0];
			if (strlen($domain) > 6) {
				// avoid com.sg or similar country subdomains
				$localValue = $domain;
			} else {
				$localValue = $host;
			}
		}

		if ($method == 'domain' || $method == 'partial') {
			$partial = true;
		} else {
			$partial = false;
		}

		// If $linkValue is specified, it means get_all_item_id should be called,
		//	which can match a set of linked values. Not just 1
		if (!empty($linkValue)) {
			// get_all_item_id always collects all matching links. $partial is ignored
			//	Use the local value in the search, when it's available
			$value = empty($localValue) ? $linkValue : $localValue;
			$data = $trklib->get_all_item_id($remoteTrackerId, $remoteField, $value);
		} else {
			$data = $trklib->get_item_id($remoteTrackerId, $remoteField, $localValue, $partial);
		}
		return $data;
	}

	function handleSave($value, $oldValue)
	{
		// if selectMultipleValues is enabled, convert the array
		// of options to string before saving the field value in the db
		if ($this->getOption('selectMultipleValues')) {
			$value = implode(',', $value);
		} else {
			$value = (int) $value;
		}

		return array(
			'value' => $value,
		);
	}

	function itemsRequireRefresh($trackerId, $modifiedFields)
	{
		if ($this->getOption('trackerId') != $trackerId) {
			return false;
		}

		$usedFields = array_merge(
			array($this->getOption('fieldId')),
			$this->getOption('indexRemote', array()),
			$this->getDisplayFieldsListArray()
		);

		$intersect = array_intersect($usedFields, $modifiedFields);

		return count($intersect) > 0;
	}

	function cascadeCategories($trackerId)
	{
		return $this->cascade($trackerId, self::CASCADE_CATEG);
	}

	function cascadeStatus($trackerId)
	{
		return $this->cascade($trackerId, self::CASCADE_STATUS);
	}

	function cascadeDelete($trackerId)
	{
		return $this->cascade($trackerId, self::CASCADE_DELETE);
	}

	private function cascade($trackerId, $flag)
	{
		if ($this->getOption('trackerId') != $trackerId) {
			return false;
		}

		return ($this->getOption('cascade') & $flag) > 0;
	}

	function watchCompare($old, $new)
	{
		$o = $this->getItemLabel($old);
		$n = $this->getItemLabel($new);

		return parent::watchCompare($o, $n);	// then compare as text
	}

	/**
	 * @return mixed
	 */
	private function getDisplayFieldsListArray()
	{
		$option = $this->getOption('displayFieldsList');
		if (!empty($option) && (!is_array($option) || !empty($option[0]))) {
			return $option;
		} else {
			return array();
		}
	}

}

