<?php
// (c) Copyright 2002-2016 by authors of the Tiki Wiki CMS Groupware Project
//
// All Rights Reserved. See copyright.txt for details and a complete list of authors.
// Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See license.txt for details.
// $Id$

class Tracker_Field_CalendarItem extends Tracker_Field_JsCalendar
{
	/** @var AttributeLib $attributeLib */
	private $attributeLib;

	/** @var CalendarLib $calendarLib */
	private $calendarLib;

	public static function getTypes()
	{
		$def = [
			'CAL' => [
				'name' => tr('Date and Time (Calendar Item)'),
				'description' => tr('Associate calendar items with tracker items.'),
				'prefs' => ['trackerfield_calendaritem'],
				'tags' => ['advanced', 'experimental'],
				'warning' => tra('Experimental: (work in progress, use with care)'),
				'default' => 'n',
				'supported_changes' => ['f', 'j', 'CAL'],
				'params' => [
					'calendarId' => [
						'name' => tr('Calendar Id'),
						'description' => tr('Calendar to use for associated events'),
						'filter' => 'int',
						'profile_reference' => 'calendar',

					],
					'showEventIdInput' => [
						'name' => tr('Show Event Id'),
						'description' => tr('Show an input for the event id when editing the field, allow lost events to be reattached.'),
						'filter' => 'int',
						'options' => [
							0 => tr('No'),
							1 => tr('Yes'),
						],
					],
				],
			],
		];

		$parentDef = parent::getTypes();

		$def['CAL']['params'] = array_merge($def['CAL']['params'], $parentDef['j']['params']);

		return $def;
	}

	/**
	 * Tracker_Field_CalendarItem constructor.
	 * @param array $fieldInfo
	 * @param array $itemData
	 * @param Tracker_Definition $trackerDefinition
	 */
	function __construct($fieldInfo, $itemData, $trackerDefinition)
	{

		$this->attributeLib = TikiLib::lib('attribute');
		$this->calendarLib = TikiLib::lib('calendar');

		if ($fieldInfo['options_map']['calendarId']) {
			TikiLib::lib('relation')->add_relation(
				'tiki.calendar.attach',
				'tracker',
				$trackerDefinition->getConfiguration('trackerId'),
				'calendar',
				$fieldInfo['options_map']['calendarId'],
				true
			);
		}

		parent::__construct($fieldInfo, $itemData, $trackerDefinition);
	}

	function handleSave($value, $oldValue)
	{
		$calendarId = $this->getOption('calendarId');

		$event = [];

		if ($this->getOption('showEventIdInput')) {
			$setCalitemId = isset($_POST['calitemId_' . $this->getFieldId()]) ? $_POST['calitemId_' . $this->getFieldId()] : 0;
			if ($setCalitemId) {
				$event = $this->calendarLib->get_item($setCalitemId);
				if ($event) {
					$value = $event['start'];
				}
			} else if ($setCalitemId === '') {		// event detached
				$this->removeCalendarItemId();
				return [
					'value' => $value,
				];
			}
		}

		if ($calendarId && $value) {
			global $user;

			/** @var TrackerLib $trklib */
			$trklib = TikiLib::lib('trk');

			$itemId = $this->getItemId();

			if ($itemId) {
				$trackerId = $this->getConfiguration('trackerId');
				if ($event['calitemId']) {
					$calitemId = $event['calitemId'];
					$name = $event['name'];
				} else {
					$calitemId = $this->getCalendarItemId();
					$event = $this->calendarLib->get_item($setCalitemId);

					if ($event) {
						$name = $event['name'];
					} else {
						$name = $trklib->get_isMain_value($trackerId, $itemId);	// use the item title for new events
					}
				}

				$data = [
					'calendarId' => $calendarId,
					'start'      => $value,
					//		'end'
					//		'locationId',
					//		'categoryId',
					//		'nlId',
					//		'priority',
					//		'status',
					//		'url',
					//		'lang'
					'name'       => $name,
					//		'description',
					//		'user',
					//		'created',
					//		'lastmodif',
					//		'allday',
					//		'recurrenceId',
					//		'changed'
				];

				if (! $this->calendarLib->get_calendarid($calitemId)) {
					$new = true;
					$calitemId = 0;
					$data['end'] = $value + 3600;
				} else {
					$new = false;
				}
				// save the event whether new or not as start time or the title/name might have changed

				$calitemId = $this->calendarLib->set_item($user, $calitemId, $data);

				if ($new || ($calitemId != $this->getCalendarItemId())) {    // added a new one or changed event id?
					$this->setCalendarItemId($calitemId);
				}
			}
			//$itemInfo = $calendarlib->get_item($calitemId);
		} else if (! $value && $oldValue && $itemId = $this->getItemId()) {
			// delete an item?
			$calitemId = $this->getCalendarItemId();
			if ($calitemId) {
				$this->calendarLib->drop_item($GLOBALS['user'], $calitemId);
				// also remove attribute
				$this->removeCalendarItemId();
			}
		}

		return [
			'value' => $value,
		];
	}

	function getDocumentPart(Search_Type_Factory_Interface $typeFactory)
	{
		$data = parent::getDocumentPart($typeFactory);

		$baseKey = $this->getBaseKey();
		$calitemId = $this->getCalendarItemId();
		$recurrenceId = null;

		if ($calitemId) {
			$calItem = TikiLib::lib('calendar')->get_item($calitemId);
			if ($calItem) {
				$recurrenceId = $calItem['recurrenceId'];
			} else {
				Feedback::error(tr('CalendarItem Tracker Field %0 item not found %1', $this->getFieldId(), $calitemId), 'session');
				$calitemId = null;
			}
		}

		$data = array_merge(
			$data,
			[
				"{$baseKey}_calitemid"    => $typeFactory->numeric($calitemId),
				"{$baseKey}_recurrenceId" => $typeFactory->numeric($recurrenceId),
			]
		);

		return $data;
	}

	function getProvidedFields()
	{
		$data = parent::getProvidedFields();

		$baseKey = $this->getBaseKey();

		$data = array_merge(
			$data,
			[
				"{$baseKey}_calitemid",
				"{$baseKey}_recurrenceId",
			]
		);

		return $data;
	}

	function getFieldData(array $requestData = [])
	{
		$data = parent::getFieldData($requestData);

		if (! empty($data['value']) && ! $this->getCalendarItemId()) {
			// corresponding calendar irtem missing, so create a new one
			$this->handleSave($data['value'], '');
		}

		return $data;
	}

	/**
	 * @param array $context
	 * @return string
	 * @throws Exception
	 */
	function renderInput($context = [])
	{
		global $tikiroot;

		/** @var Smarty_Tiki $smarty */
		$smarty = TikiLib::lib('smarty');

		$smarty->assign('datePickerHtml', parent::renderInput($context));

		$event = $this->calendarLib->get_item($this->getCalendarItemId());
		$perms = Perms::get([ 'type' => 'calendar', 'object' => $event['calendarId']]);

		if ($perms->change_events) {
			if ($event) {
				$editUrl = 'tiki-calendar_edit_item.php?fullcalendar=y&isModal=1&trackerItemId=' . $this->getItemId() . '&calitemId=' . $event['calitemId'];
			} else {
				$editUrl = 'tiki-calendar_edit_item.php?fullcalendar=y&isModal=1&trackerItemId=' . $this->getItemId() . '&calendarId=' . $this->getOption('calendarId');
			}
			$headerlib = TikiLib::lib('header');

			$headerlib->add_js_config('window.CKEDITOR_BASEPATH = "' . $tikiroot . 'vendor_bundled/vendor/ckeditor/ckeditor/";')
				->add_jsfile('vendor_bundled/vendor/ckeditor/ckeditor/ckeditor.js', true)
				->add_js('window.dialogData = [];', 1);
			// ->add_js('window.CKEDITOR.config._TikiRoot = "' . $tikiroot . '";', 1);

		} else {
			$editUrl = '';
		}

		return $this->renderTemplate('trackerinput/calendaritem.tpl', $context, ['editUrl' => $editUrl, 'event' => $event]);
	}

	function isValid($ins_fields_data)
	{
		return parent::isValid($ins_fields_data);
	}

	/**
	 * @return bool|string
	 */
	private function getCalendarItemId()
	{
		$calitemId = $this->attributeLib->get_attribute('trackeritem', $this->getItemId(), 'tiki.calendar.item');
		return $calitemId;
	}

	/**
	 * @param $itemId
	 */
	private function removeCalendarItemId()
	{
		$this->attributeLib->set_attribute('trackeritem', $this->getItemId(), 'tiki.calendar.item', '');
	}

	/**
	 * @param $calitemId
	 */
	private function setCalendarItemId($calitemId)
	{
		$this->attributeLib->set_attribute(
			'trackeritem', $this->getItemId(), 'tiki.calendar.item',
			$calitemId
		);
	}
}
