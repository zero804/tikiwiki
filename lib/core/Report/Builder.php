<?php
// (c) Copyright by authors of the Tiki Wiki CMS Groupware Project
//
// All Rights Reserved. See copyright.txt for details and a complete list of authors.
// Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See license.txt for details.
// $Id$

class Report_Builder
{
	var $type = '';
	var $id = null;
	var $input = [];
	var $name = '';
	var $description = '';
	var $values = [];

	static function load($type)
	{
		$me = new self();
		$me->type = ucwords($type);

		$class = "Report_Definition_{$me->type}";
		if (class_exists($class) == true) {
			$definition = new $class;
			$me->input = $definition->input();
		}
		return $me;
	}

	static function open($data)
	{
		$me = new self();
		$data = $me->fromWikiSyntax($data);
		return (json_encode($data));
	}

	static function listDefinitions()
	{
		$files = [];

		foreach (scandir('lib/core/Report/Definition') as $fileName) {
			if (preg_match('/[.]php/', $fileName) && $fileName != "index.php") {
				$files[] = str_replace('.php', '', $fileName);
			}
		}

		return $files;
	}

	function setValues($values = [])
	{
		$this->values = $values;
		return $this;
	}

	static function fromWikiSyntax($data = "")
	{
		if (empty($data)) {
			throw new Exception("Failed to get body", 1);
		}
		$parsedValues = [];

		foreach (explode("\n", $data) as $values) {
			$values = trim($values);
			if (! empty($values)) {
				$value = explode(":", $values);
				$parsedValues[trim($value[0])] = trim($value[1]);
			}
		}

		return TikiFilter_PrepareInput::delimiter('_')->prepare($parsedValues);
	}

	static function loadFromWikiSyntax($data = "")
	{
		$values = Report_Builder::fromWikiSyntax($data);
		$me = Report_Builder::load($values['type']);
		return $me->setValues($values);
	}

	function setValuesFromRequest($values)
	{
		$parsedValues = [];
		foreach ($values as $value) {
			$value = (array)$value; //was having trouble with downloading csv

			if (preg_match('/\[\]/', $value['name'])) {
				$value['name'] = str_replace('[]', '', $value['name']);
				$parsedValues[$value['name']][] = [
					"value" => trim($value['value'])
				];
			} else {
				$parsedValues[$value['name']] = [
					"value" => trim($value['value'])
				];
			}
		}

		return $this->setValues(TikiFilter_PrepareInput::delimiter('_')->prepare($parsedValues));
	}

	function outputArray()
	{
		$class = "Report_Definition_{$this->type}";
		if (class_exists($class) == true) {
			$definition = new $class;
			return $definition->output($this->values);
		}
		return [];
	}

	function outputSheet($name = "")
	{
		$sheetlib = TikiLib::lib("sheet");

		if (empty($name)) {
			$name = $this->type;
		}

		$handler = new TikiSheetSimpleArrayHandler(
			[
				"values" => $this->outputArray(),
				"name" => $name
			]
		);

		$grid = new TikiSheet();
		$grid->import($handler);

		return $grid->getTableHtml();
	}

	function outputCSV($auto = false)
	{
		$output = '';

		$header = false;

		foreach ($this->outputArray() as $row) {
			if ($header == false) {
				$header = true;
				$headerNames = [];
				foreach ($row as $headerName => $col) {
					$headerNames[] = tr(ucwords($headerName));
				}

				$output .= '"' . implode('","', $headerNames) . '"' . "\n";
			}
			$output .= '"' . implode('","', $row) . '"' . "\n";
		}

		if ($auto == true) {
			header("Content-type: application/csv");
			header("Content-Disposition: attachment; filename=export.csv");
			header("Pragma: no-cache");
			header("Expires: 0");
			echo $output;
			return '';
		}

		return $output;
	}

	function outputChart()
	{
		$output = $this->outputArray();
	}

	function outputWikiData()
	{
		$result = "type : " . $this->type . "\n";
		foreach (TikiFilter_PrepareInput::delimiter('_')->flatten($this->values) as $key => $value) {
			if (! empty($value)) {
				$result .= $key . ' : ' . $value . "\n";
			}
		}
		return $result;
	}
}
