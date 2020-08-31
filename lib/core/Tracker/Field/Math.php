<?php
// (c) Copyright by authors of the Tiki Wiki CMS Groupware Project
//
// All Rights Reserved. See copyright.txt for details and a complete list of authors.
// Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See license.txt for details.
// $Id$

/**
 * Handler to perform a calculation for the tracker entry.
 *
 * Letter key: ~GF~
 *
 */
class Tracker_Field_Math extends Tracker_Field_Abstract implements Tracker_Field_Synchronizable, Tracker_Field_Indexable, Tracker_Field_Exportable
{
	private static $runner;

	public static function getTypes()
	{
		return [
			'math' => [
				'name' => tr('Mathematical Calculation'),
				'description' => tr('Perform a calculation upon saving the item based on other fields within the same item.'),
				'help' => 'Mathematical Calculation Field',
				'prefs' => ['trackerfield_math'],
				'tags' => ['advanced'],
				'default' => 'n',
				'params' => [
					'calculation' => [
						'name' => tr('Calculation'),
						'type' => 'textarea',
						'description' => tr('Calculation in the Rating Language'),
						'filter' => 'text',
						'legacy_index' => 0,
					],
					'recalculate' => [
						'name' => tr('Re-calculation event'),
						'type' => 'list',
						'description' => tr('Set this to "Indexing" to update the value during reindexing as well as when saving. Selection of indexing is useful for dynamic score fields that will not be displayed.'),
						'filter' => 'word',
						'options' => [
							'save' => tr('Save'),
							'index' => tr('Indexing'),
						],
					],
				],
			],
		];
	}

	function getFieldData(array $requestData = [])
	{
		if (isset($requestData[$this->getInsertId()])) {
			$value = $requestData[$this->getInsertId()];
		} else {
			$value = $this->getValue();
		}

		return [
			'value' => $value,
		];
	}

	function renderInput($context = [])
	{
		return tr('Value will be re-calculated on save. Current value: %0', $this->getValue());
	}

	function renderOutput($context = [])
	{
		return $this->getValue();
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
		return $info;
	}

	function getDocumentPart(Search_Type_Factory_Interface $typeFactory)
	{
		$value = $this->getValue();

		if ('index' == $this->getOption('recalculate')) {
			try {
				$runner = $this->getFormulaRunner();
				$data = ['itemId' => $this->getItemId()];

				foreach ($runner->inspect() as $fieldName) {
					if (is_string($fieldName) || is_numeric($fieldName)) {
						$data[$fieldName] = $this->getItemField($fieldName);
					}
				}

				$this->prepareFieldValues($data);
				// get it again as runner is a static property and could have overridden formula by preparing other field values
				$runner = $this->getFormulaRunner();
				$runner->setVariables($data);

				$value = (string)$runner->evaluate();
			} catch (Math_Formula_Exception $e) {
				$value = $e->getMessage();
			}

			if ($value !== $this->getValue()) {
				$trklib = TikiLib::lib('trk');
				$trklib->modify_field($this->getItemId(), $this->getConfiguration('fieldId'), $value);
			}
		}

		$baseKey = $this->getBaseKey();
		return [
			$baseKey => $typeFactory->sortable($value),
		];
	}

	function getProvidedFields()
	{
		$baseKey = $this->getBaseKey();
		return [$baseKey];
	}

	function getGlobalFields()
	{
		return [];
	}

	/**
	 * Recalculate formula after saving all other fields in the tracker item
	 * @param array $data - field values to save - passed by reference as
	 * prepareFieldValues might add ItemsList field reference values here
	 * and make them available for other Math fields in the same item, thus
	 * greatly speeding up the process.
	 */
	function handleFinalSave(array &$data)
	{
		try {
			$this->prepareFieldValues($data);
			if (! isset($data['itemId'])) {
				$data['itemId'] = $this->getItemId();
			}
			$runner = $this->getFormulaRunner();
			$runner->setVariables($data);

			return (string)$runner->evaluate();
		} catch (Math_Formula_Exception $e) {
			return $e->getMessage();
		}
	}

	function getTabularSchema()
	{
		$schema = new Tracker\Tabular\Schema($this->getTrackerDefinition());

		$permName = $this->getConfiguration('permName');
		$schema->addNew($permName, 'default')
			->setLabel($this->getConfiguration('name'))
			->setRenderTransform(function ($value) {
				return $value;
			})
			;

		return $schema;
	}

	/**
	 * Helper method to prepare field values for item fields that do not store their
	 * info in database - e.g. ItemsList.
	 * @param array data to be modified
	 */
	private function prepareFieldValues(&$data)
	{
		$fieldData = ['itemId' => $this->getItemId()];
		foreach ($data as $permName => $value) {
			$field = $this->getTrackerDefinition()->getFieldFromPermName($permName);
			if ($field) {
				$fieldData[$field['fieldId']] = $value;
			}
		}
		foreach ($data as $permName => $value) {
			if (! empty($value)) {
				continue;
			}
			$field = $this->getTrackerDefinition()->getFieldFromPermName($permName);
			if (! $field || $field['type'] != 'l') {
				continue;
			}
			$handler = TikiLib::lib('trk')->get_field_handler($field, $fieldData);
			$data[$permName] = $handler->getItemValues();
		}
	}

	private function getFormulaRunner()
	{
		static $cache = [];
		$fieldId = $this->getConfiguration('fieldId');
		if (! isset($cache[$fieldId])) {
			$cache[$fieldId] = $this->getOption('calculation');
		}

		$runner = self::getRunner();

		$cache[$fieldId] = $runner->setFormula($cache[$fieldId]);

		return $runner;
	}

	public static function getRunner()
	{
		if (! self::$runner) {
			self::$runner = new Math_Formula_Runner(
				[
					'Math_Formula_Function_' => '',
					'Tiki_Formula_Function_' => '',
				]
			);
		}

		return self::$runner;
	}

	public static function resetRunner()
	{
		self::$runner = null;
	}
}
