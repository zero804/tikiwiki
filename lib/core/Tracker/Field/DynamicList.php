<?php
// (c) Copyright 2002-2013 by authors of the Tiki Wiki CMS Groupware Project
//
// All Rights Reserved. See copyright.txt for details and a complete list of authors.
// Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See license.txt for details.
// $Id$

/**
 * Handler class for DynamicList
 *
 * Letter key: ~w~
 *
 */
// TODO: validate parameters (several required)
class Tracker_Field_DynamicList extends Tracker_Field_Abstract
{
	public static function getTypes()
	{
		return array(
			'w' => array(
				'name' => tra('Dynamic Items List'),
				'description' => tra('Dynamically updates a selection list based on linked data from another tracker.'),
				'help' => 'Dynamic items list',
				'prefs' => array('trackerfield_dynamiclist'),
				'tags' => array('advanced'),
				'default' => 'n',
				'params' => array(
					'trackerId' => array(
						'name' => tr('Tracker ID'),
						'description' => tr('Tracker to link with'),
						'filter' => 'int',
						'legacy_index' => 0,
						'profile_reference' => 'tracker',
					),
					'filterFieldIdThere' => array(
						'name' => tr('Field ID (Other tracker)'),
						'description' => tr('Field ID to link with in the other tracker'),
						'filter' => 'int',
						'legacy_index' => 1,
						'profile_reference' => 'tracker_field',
					),
					'filterFieldIdHere' => array(
						'name' => tr('Field ID (This tracker)'),
						'description' => tr('Field ID to link with in the current tracker'),
						'filter' => 'int',
						'legacy_index' => 2,
						'profile_reference' => 'tracker_field',
					),
					'listFieldIdThere' => array(
						'name' => tr('Listed Field'),
						'description' => tr('Field ID to be displayed in the drop list.'),
						'filter' => 'int',
						'legacy_index' => 3,
						'profile_reference' => 'tracker_field',
					),
					'statusThere' => array(
						'name' => tr('Status Filter'),
						'description' => tr('Restrict listed items to specific statuses.'),
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
				),
			),
		);
	}

	function getFieldData(array $requestData = array())
	{
		$ins_id = $this->getInsertId();

		return array(
			'value' => (isset($requestData[$ins_id]))
				? $requestData[$ins_id]
				: $this->getValue(),
		);
	}

	function renderInput($context = array())
	{
		// REFACTOR: can't use list-tracker_field_values_ajax.php yet as it doesn't seem to filter

		TikiLib::lib('header')->add_jq_onready(
			'
$("select[name=ins_' . $this->getOption('filterFieldIdHere') . ']").change(function(e, val) {
	$.getJSON(
		"tiki-tracker_http_request.php",
		{
			trackerIdList: ' . $this->getOption('trackerId') . ',
			fieldlist: ' . $this->getOption('listFieldIdThere') . ',
			filterfield: ' . $this->getOption('filterFieldIdThere') . ',
			status: "' . $this->getOption('statusThere') . '",
			mandatory: "' . $this->getConfiguration('isMandatory') . '",
			item: $(this).val() // We need the field value for the fieldId filterfield for the item $(this).val
		},
		function(data, status) {
			$ddl = $("select[name=' . $this->getInsertId() . ']");
			$ddl.empty();
			if (data) {
				$.each( data, function (i,v) {
					$ddl.append(
						$("<option/>")
							.attr("value", v)
							.text(v)
					);
				});
				if (val) {
					$ddl.val(val);
				}
			}
		}
	);
}).trigger("change", ["' . $this->getConfiguration('value') . '"]);
		'
		);

		return '<select name="' . $this->getInsertId() . '"></select>';

	}
}

