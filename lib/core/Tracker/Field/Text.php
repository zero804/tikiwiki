<?php
// (c) Copyright 2002-2013 by authors of the Tiki Wiki CMS Groupware Project
// 
// All Rights Reserved. See copyright.txt for details and a complete list of authors.
// Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See license.txt for details.
// $Id$

/**
 * Handler class for Text
 * 
 * Letter key: ~t~
 *
 */
class Tracker_Field_Text extends Tracker_Field_Abstract implements Tracker_Field_Synchronizable
{
	public static function getTypes()
	{
		return array(
			't' => array(
				'name' => tr('Text Field'),
				'description' => tr('Single-line text input.'),
				'help' => 'Text Tracker Field',
				'prefs' => array('trackerfield_text'),
				'tags' => array('basic'),
				'default' => 'y',
				'params' => array(
					'samerow' => array(
						'name' => tr('Same Row'),
						'description' => tr('Display the field name and input on the same row.'),
						'deprecated' => false,
						'filter' => 'int',
						'default' => 1,
						'options' => array(
							0 => tr('No'),
							1 => tr('Yes'),
						),
						'legacy_index' => 0,
					),
					'size' => array(
						'name' => tr('Display Size'),
						'description' => tr('Visible size of the field in characters.'),
						'filter' => 'int',
						'legacy_index' => 1,
					),
					'prepend' => array(
						'name' => tr('Prepend'),
						'description' => tr('Text to prepend when displaying the value.'),
						'filter' => 'text',
						'legacy_index' => 2,
					),
					'append' => array(
						'name' => tr('Append'),
						'description' => tr('Text to append when displaying the value.'),
						'filter' => 'text',
						'legacy_index' => 3,
					),
					'max' => array(
						'name' => tra('Maximum Length'),
						'description' => tra('Maximum amount of characters to store.'),
						'filter' => 'int',
						'legacy_index' => 4,
					),
					'autocomplete' => array(
						'name' => tra('Autocomplete'),
						'description' => tra('Enable autocompletion while typing in the field.'),
						'filter' => 'alpha',
						'options' => array(
							'n' => tr('No'),
							'y' => tr('Yes'),
						),
						'legacy_index' => 5,
					),
					'exact' => array(
						'name' => tr('Index exact value'),
						'description' => tr('In addition to indexing the content of the field, also index it as an identifier in tracker_field_{perm name}_exact. This option is not available for multilingual fields. Mostly for identifiers like product codes or ISBN numbers.'),
						'filter' => 'alpha',
						'options' => array(
							'n' => tr('No'),
							'y' => tr('Yes'),
						),
						'legacy_index' => 6,
					),
				),
			),
		);
	}

	function getFieldData(array $requestData = array())
	{
		$data = $this->processMultilingual($requestData, $this->getInsertId());

		return $data;
	}

	function renderInput($context = array())
	{
		return $this->renderTemplate('trackerinput/text.tpl', $context);
	}

	function renderInnerOutput($context = array())
	{
		$pre = '';
		$post = '';

		if ($this->getConfiguration('type') == 't') {
			if ($this->getOption('prepend')) {
				$pre = '<span class="formunit">' . $this->getOption('prepend') . '</span>';
			}

			if ($this->getOption('append')) {
				$post = '<span class="formunit">' . $this->getOption('append') . '</span>';
			}
		}

		return $pre . parent::renderInnerOutput($context) . $post;
	}

	function renderOutput($context = array())
	{
		if (isset($context['history']) && $context['history'] == 'y' && is_array($this->getConfiguration('value'))) {
			return $this->renderTemplate('trackeroutput/text_history.tpl');
		} else {
			return parent::renderOutput($context);
		}
	}

	protected function processMultilingual($requestData, $id_string) 
	{
		global $prefs, $jitRequest;
		$language = $prefs['language'];
		$multilingual = $this->getConfiguration('isMultilingual') == 'y';

		if (!isset($requestData[$id_string])) { // although we're using jitRequest test for $requestData here as it gets unset once processed
			$value = $this->getValue();
			if ($multilingual) {
				$newValue = @json_decode($value, true);

				if ($newValue !== false && ! is_null($newValue)) {
					$value = $newValue;
				}
			}
		} else {
			$value = $jitRequest->$id_string->wikicontent();
		}

		if (is_array($value)) {
			$thisVal = $value[$language];
		} else {
			$thisVal = $value;
		}

		$data = array(
			'value' => $value,
			'pvalue' => trim($this->attemptParse($thisVal), "\n"),
			'lingualvalue' => array(),
			'lingualpvalue' => array(),
		);

		if ($multilingual) {
			// When multilingual is turned on after data exists, this may well be a string
			// rather than an array. Assume it's empty, $thisVal will replace all values.
			if (! is_array($value)) {
				$value = array();
			}
			foreach ($prefs['available_languages'] as $num => $lang) { // TODO add a limit on number of langs - 40+ makes this blow up
				if (!isset($value[$lang])) {
					$value[$lang] = $thisVal;
				}

				$data['lingualvalue'][$num] = array(
					'id' => str_replace(array('[', ']'), array('_', ''), $this->getInsertId()) . '_' . $lang,
					'lang' => $lang,
					'value' => $value[$lang],
				);
				$data['lingualpvalue'][$num] = array(
					'lang' => $lang,
					'value' => $this->attemptParse($value[$lang]),
				);
			}
		}

		unset($data['raw']);

		return $data;
	}

	protected function attemptParse($text)
	{
		return $text;
	}

	function handleSave($value, $oldValue)
	{
		if (is_array($value)) {
			return array(
				'value' => json_encode(array_map(array($this, 'filterValue'), $value)),
			);
		} else {
			return array(
				'value' => $this->filterValue($value),
			);
		}
	}

	function filterValue($value)
	{
		$length = $this->getOption('max');

		if ($length) {
			$f_len = function_exists('mb_strlen') ? 'mb_strlen' : 'strlen';
			$f_substr = function_exists('mb_substr') ? 'mb_substr' : 'substr';

			if ($f_len($value) > $length) {
				return $f_substr($value, 0, $length);
			}
		}

		return $value;
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
		$fieldType = $this->getIndexableType();
		$baseKey = $this->getBaseKey();

		if ($this->getConfiguration('isMultilingual') == 'y') {
			if (!empty($value)) {
				$decoded = json_decode($value, true);
				$value = implode("\n", $decoded);
			} else {
				$decoded = array();
			}

			$data = array($baseKey => $typeFactory->$fieldType($value));
			foreach ($decoded as $lang => $content) {
				$data[$baseKey . '_' . $lang] = $typeFactory->$fieldType($content);
			}

			return $data;
		} else {
			$data = array(
				$baseKey => $typeFactory->$fieldType($value),
			);

			if ($this->getOption('exact') == 'y') {
				$data[$baseKey . '_exact'] = $typeFactory->identifier($value);
			}

			return $data;
		}
	}

	function getProvidedFields()
	{
		global $prefs;
		$baseKey = $this->getBaseKey();

		$data = array($baseKey);

		if ($this->getConfiguration('isMultilingual') == 'y') {
			foreach ($prefs['available_languages'] as $lang) {
				$data[] = $baseKey . '_' . $lang;
			}
		} elseif ($this->getOption('exact') == 'y') {
			$data[] = $baseKey . '_exact';
		}

		return $data;
	}

	function getGlobalFields()
	{
		global $prefs;
		$baseKey = $this->getBaseKey();

		$data = array($baseKey => true);

		if ($this->getConfiguration('isMultilingual') == 'y') {
			foreach ($prefs['available_languages'] as $lang) {
				$data[$baseKey . '_' . $lang] = true;
			}
		}

		return $data;
	}

	protected function getIndexableType()
	{
		return 'sortable';
	}

	function isValid()
	{
		global $prefs;

		$value = $this->getValue();

		$validation = $this->getConfiguration('validation');
		$param = $this->getConfiguration('validationParam');
		$message = $this->getConfiguration('validationMessage');

		if (! $validation) {
			return true;
		}

		if ($prefs['feature_jquery_validation'] === 'y' && $validation === 'regex' &&
				(strpos($param, '\\') !== false || strpos($param, '\"') !== false)) {	// work around for legacy patterns pre r52254

			$param = stripslashes($param);
		}

		$validators = TikiLib::lib('validators');
		$validators->setInput($value);
		$ret = $validators->validateInput($validation, $param);
		return $ret;
	}
}

