<?php
// (c) Copyright by authors of the Tiki Wiki CMS Groupware Project
//
// All Rights Reserved. See copyright.txt for details and a complete list of authors.
// Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See license.txt for details.
// $Id$

/**
 * Class Services_Edit_ListConverter
 *
 * This service converts legacy plugins in a wiki page to use the list plugin
 *
 * Currently only trackerlist is supported
 */
class Services_Edit_ListConverter
{
	private $sourcePlugin = '';
	private $utilities;

	private $columns;
	private $formats;
	private $missed;

	private $titleFound;

	private $columnOptions;

	public function __construct($sourcePlugin)
	{
		$this->utilities = new Services_Tracker_Utilities;

		$this->sourcePlugin = $sourcePlugin;
		$this->formats = [];
		$this->columns = [];
		$this->missed = [];
		$this->columnOptions = [
			'label'    => true,            // show labels
			'sort'     => false,            // allow sorting
			'links'    => false,           // show item links
			'edit'     => false,            // all editable
			'editable' => [],           // editable field id's
		];

		$this->titleFound = false;
	}

	/**
	 * Process params from source plugin (trackerlist so far) and return body content of list plugin replacement
	 * Work in progress, not all params converted so far
	 *
	 * @param array $params plugin trackerlist parameters to be converted
	 * @param string $content plugin trackerlist body
	 *
	 * @return string           body content for new list plugin
	 * @throws Services_Exception
	 * @throws Services_Exception_Disabled
	 */
	public function convert($params, $content)
	{
		$result = '';

		// ignore empty ones
		$params = array_filter($params);

		if (! empty($params['trackerId'])) {
			$trackerId = $params['trackerId'];
		} else {
			throw new Services_Exception_MissingValue('trackerId');
		}

		$definition = Tracker_Definition::get($trackerId);
		if (! $definition) {
			throw new Services_Exception_NotFound(tr('Tracker %0 not found'));
		}

		$filters = [
			['type' => 'trackeritem'],
			['field' => 'tracker_id', 'content' => $trackerId],
		];

		$fields = [];
		$sortMode = [];
		$pagination = [];
		$tableSorter = ['sortable' => 'n'];
		$filterFields = [];
		$filterValues = [];
		$filterExact = [];

		$showLastModif = false;
		$showCreated = false;
		$showStatus = false;

		unset($params['trackerId']);    // got it before

		foreach ($params as $param => $value) {
			if ($value === 'Y' || $value === 'N') {
				$value = strtolower($value);    // some people seem to use capitals for y/n
			}
			switch ($param) {
				case 'fields':
					$fields = $this->utilities->getFieldsFromIds($definition, explode(':', $value));
					break;
				case 'showlinks':
					$this->columnOptions['links'] = $value === 'y';
					break;
				case 'status':
					$filters[] = [
						'field' => 'tracker_status',
						'content' => implode(' OR ', str_split($value)),
					];
					break;
				// *********************** filtering *************************
				case 'filterfield':
					$filterFields = explode(':', $value);
					break;
				case 'filtervalue':
					$filterValues = explode(':', $value);
					break;
				case 'exactvalue':
					$filterExact = explode(':', $value);
					break;
				// *********************** editable *************************
				case 'editableall':
					$this->columnOptions['edit'] = $value === 'y';
					break;
				case 'editable':
					$this->columnOptions['editable'] = explode(':', $value);
					break;
				// *********************** sorting *************************
				case 'sort':
					$this->columnOptions['sort'] = $value === 'y';
					break;
				case 'sort_mode':
					if (strpos($value, 'lastModif_') === 0) {
						$sortMode = ['mode' => 'modification_date_' . str_replace('lastModif_', '', 'n' . $value)];
					} elseif (strpos($value, 'created_') === 0) {
						$sortMode = ['mode' => 'creation_date_' . str_replace('created_', '', 'n' . $value)];
					} else {
						$sortMode = ['mode' => $value];    // e.g. f_xxx_desc
					}
					break;
				// ***********************  dates  *************************
				case 'showlastmodif':
					$showLastModif = $value === 'y';
					break;
				case 'showcreated':
					$showCreated = $value === 'y';
					break;
				// *********************** status display **********************
				case 'showstatus':
					$showStatus = $value === 'y';
					break;
				// *********************** pagination *************************
				case 'max':
					$pagination = ['max' => $value];
					break;
				// *********************** tablesorter *************************
				case 'sortable':
					$tableSorter['sortable'] = $value;
					break;
				case 'server':
					$tableSorter['server'] = $value;
					break;
				case 'tsfilters':
					if ($params['showstatus'] === 'y') {    // when showing the status trackerfilter adds an extra column for it
						$value = preg_replace('/^.*?\|/', '', $value);
					}
					$tableSorter['tsfilters'] = $value;
					break;
				case 'tsfilteroptions':
					$tableSorter['tsfilteroptions'] = $value;
					break;
				case 'tsortcolumns':
					if ($params['showstatus'] === 'y') {
						$value = preg_replace('/^.*?\|/', '', $value);
					}
					$tableSorter['tsortcolumns'] = $value;
					break;
				case 'tscolselect':
					$tableSorter['tscolselect'] = $value;
					break;
				case 'tstotals':
					if ($params['showstatus'] === 'y') {
						$value = preg_replace('/^.*?\|/', '', $value);
					}
					$tableSorter['tstotals'] = $value;
					break;
				case 'tstotaloptions':
					$tableSorter['tstotaloptions'] = $value;
					break;
				case 'tspaginate':
					$tableSorter['tspaginate'] = $value;
					break;
				case 'sortList':
					$tableSorter['sortList'] = $value;
					break;
				default:
					$this->missed[$param] = $value;
			}
		}
		if (! $fields) {
			$fields = $definition->getFields();
		}

		foreach ($fields as $field) {
			$this->processFieldAsColumn($field);
			if ($sortMode) {
				if (preg_match('/f_' . $field['fieldId'] . '_(.*)/', $sortMode['mode'], $match)) {
					$sortMode['mode'] = 'tracker_field_' . $field['permName'] . '_' . $match[1];
				}
			}
			if ($filterFields) {
				for ($i = 0; $i < count($filterFields); $i++) {
					if ($filterFields[$i] == $field['fieldId']) {
						if (isset($filterValues[$i])) {
							$filters[] = [
								'field' => 'tracker_field_' . $field['permName'],
								'content' => $filterValues[$i],
							];
						} elseif (isset($filterExact[$i])) {
							$exactValue = $filterExact[$i];
							if (preg_match('/^(not)?categories\((\d+)\)$/', $exactValue, $matches)) {
								$filters[] = [
									'deepcategories' => strtoupper($matches[1]) . ' ' . $matches[2],
								];
							} elseif (preg_match('/^(not)?preference\((.*)\)$/', $exactValue, $matches)) {
								$prefValue = TikiLib::lib('tiki')->get_preference($matches[2]);
								$filters[] = [
									'field' => 'tracker_field_' . $field['permName'],
									'exact' => strtoupper($matches[1]) . ' ' . $prefValue,
								];
							} elseif (preg_match('/^(not)?field\((\d+?),\s*(\d+)\)$/', $exactValue, $matches)) {
								$prefValue = TikiLib::lib('trk')->get_field_value($matches[2], $matches[3]);
								$filters[] = [
									'field' => 'tracker_field_' . $field['permName'],
									'exact' => strtoupper($matches[1]) . ' ' . $prefValue,
								];
							} elseif (preg_match('/^not\((.*)\)$/', $exactValue, $matches)) {
								$filters[] = [
									'field' => 'tracker_field_' . $field['permName'],
									'exact' => 'NOT ' . $exactValue,
								];
								// still TODO:
								// less(equal)(date)
								// greater(equal)(date)
							} elseif (strtolower($exactValue) === 'not()') {
								$filters[] = [
									'field' => 'tracker_field_' . $field['permName'],
									'exact' => 'NOT ""',
								];
							} else {
								$filters[] = [
									'field' => 'tracker_field_' . $field['permName'],
									'exact' => $exactValue,
								];
							}
						}
					}
				}
			}
		}

		if ($this->columnOptions['links'] && ! $this->titleFound) {    // object link not listed in fields
			foreach ($definition->getFields() as $field) {
				if ($field['isMain'] === 'y') {
					$this->processFieldAsColumn($field, true);
				}
			}
		}

		if ($showStatus) {
			$this->processFieldAsColumn(
				[
					'name'     => 'Status',
					'permName' => 'tracker_status',
					'type'     => 't',
				], true
			);
		}

		if ($showLastModif) {
			$this->processFieldAsColumn([
				'name' => 'LastModif',
				'permName' => 'modification_date',
				'type' => 'f',        // pretend this is a date field
				'datetime' => 'dt',
			]);
		}

		if ($showCreated) {
			$this->processFieldAsColumn([
				'name' => 'Created',
				'permName' => 'creation_date',
				'type' => 'f',
				'datetime' => 'dt',
			]);
		}

		$result .= $this->arrayToInlinePluginString('filter', $filters);

		if ($sortMode) {
			$result .= $this->arrayToInlinePluginString('sort', [$sortMode]);
		}
		if ($pagination) {
			$result .= $this->arrayToInlinePluginString('pagination', [$pagination]);
		}

		$result .= "{OUTPUT(template=\"table\")}\n";
		$result .= $this->arrayToInlinePluginString('column', $this->columns);

		if ($tableSorter['sortable'] === 'y') {
			$result .= $this->arrayToInlinePluginString('tablesorter', [$tableSorter]);
		}

		$result .= "{OUTPUT}\n";

		$result .= $this->arrayToBlockPluginString('format', $this->formats);

		return $result;
	}

	public function getErrorsComment()
	{
		$errors = tr("The following parameters could not be converted to plugin list at this stage:\n");

		foreach ($this->missed as $name => $value) {
			$errors .= "Param $name=$value not converted\n";
		}

		return "~tc~$errors~/tc~\n";
	}

	private function arrayToInlinePluginString($type, $params)
	{
		$result = '';
		foreach ($params as $param) {
			$result .= "{{$type} ";

			foreach ($param as $name => $value) {
				$result .= "$name=\"$value\" ";
			}
			$result = rtrim($result) . "}\n";
		}
		return $result;
	}

	private function arrayToBlockPluginString($type, $params)
	{
		$result = '';
		$type = strtoupper($type);
		foreach ($params as $name => $contents) {
			$result .= "{{$type}(name=\"$name\")}$contents{{$type}}\n";
		}
		return $result;
	}

	/**
	 * @param array $field field definition array
	 * @param bool $first add this column at the beginning
	 */
	private function processFieldAsColumn($field, $first = false)
	{
		global $prefs;

		$permName = $field['permName'];
		$rawMode = false;

		$display = [
			'default' => '',
		];

		if (! empty($field['fieldId'])) {
			$fullPermName = 'tracker_field_' . $permName;
		} else {
			$fullPermName = $permName;    // if not an actual tracker field, e.g. mod or create date
		}
		$display['name'] = $fullPermName;

		if ($permName === 'tracker_status') {
			$display['format'] = 'trackerrender';
			$rawMode = true;
		}
		if ($this->columnOptions['links'] && $field['isMain'] === 'y') {
			$display['format'] = 'objectlink';
			$this->titleFound = true;
			$rawMode = true;
		}
		if ($this->columnOptions['edit'] || in_array($field['fieldId'], $this->columnOptions['editable'])) {
			$display['editable'] = 'inline';
			$rawMode = true;
		}
		if (in_array($field['type'], ['f', 'j'])) {    // or just use trackerrender?
			if ($field['options_map']['datetime'] === 'dt') {
				if ($prefs['jquery_timeago'] === 'y') {
					$display['format'] = 'timeago';
					$rawMode = true;
				} else {
					$display['format'] = 'datetime';
				}
			} else {
				$display['format'] = 'date';
			}
		}
		if (in_array($field['type'], ['a', 'e', 'FG', 'G', 'icon', 'L', 'p', 'r', 'u', 'w', 'y']) || ! empty($display['editable'])) {
			$display['format'] = 'trackerrender';
			$rawMode = true;
		}
		$displays = rtrim($this->arrayToInlinePluginString('display', [$display]));
		if ($this->columnOptions['first']) {
			$arr = array_reverse($this->formats, true);
			$arr[$permName] = $displays;
			$this->formats = array_reverse($arr, true);
		} else {
			$this->formats[$permName] = $displays;
		}

		$column = ['field' => $permName];

		if ($this->columnOptions['label']) {
			$column['label'] = $field['name'];
		}
		if ($this->columnOptions['sort']) {
			$column['sort'] = $fullPermName;
		}
		if ($rawMode) {
			$column['mode'] = 'raw';
		}
		if ($first) {
			$arr = array_reverse($this->columns, true);
			$arr[$permName] = $column;
			$this->columns = array_reverse($arr, true);
		} else {
			$this->columns[$permName] = $column;
		}
	}
}
