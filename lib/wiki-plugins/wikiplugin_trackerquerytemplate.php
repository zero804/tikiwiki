<?php
// (c) Copyright 2002-2013 by authors of the Tiki Wiki CMS Groupware Project
//
// All Rights Reserved. See copyright.txt for details and a complete list of authors.
// Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See license.txt for details.
// $Id$

function wikiplugin_trackerquerytemplate_info()
{
	return array(
		'name' => tra('Tracker Query Template'),
		'documentation' => 'PluginTrackerQueryTemplate',
		'description' => tra('Tracker Query and form generation. Supports nesting. When using byname="y" (default), variables are accessed "$field name$" (rendered) and "$~field name$ (unrendered). When using byname="n", variables are {$f_id} (rendered) and {$~f_id} (unrendered)'),
		'prefs' => array('feature_trackers','wikiplugin_trackerquerytemplate'),
		'body' => tra('Wiki Syntax, with variables from tracker query.'),
		'filter' => 'striptags',
		'icon' => 'img/icons/image_edit.png',
		'tags' => array( 'basic' ),
		'params' => array(
			'tracker' => array(
				'required' => true,
				'name' => tra('Tracker'),
				'description' => tra('Name of the tracker you want to query.'),
				'default' => ''
			),
			'debug' => array(
				'required' => false,
				'name' => tra('Debug'),
				'description' => tra('Turn tracker query debug on or off, default = "".'),
				'default' => '',
				'options' => array(
					array('text' => '', 'value' => ''),
					array('text' => tra('y'), 'value' => 'y'),
					array('text' => tra('n'), 'value' => 'n'),
				)
			),
			'byname' => array(
				'required' => false,
				'name' => tra('Tracker'),
				'description' => tra('Make tracker be accessed by name or ids, default is name, or "y".'),
				'default' => 'y',
				'options' => array(
					array('text' => '', 'value' => ''),
					array('text' => tra('y'), 'value' => 'y'),
					array('text' => tra('n'), 'value' => 'n'),
				)
			),
			'render' => array(
				'required' => false,
				'name' => tra('Render'),
				'description' => tra('Makes the field render as needed for trackers, default = "y".'),
				'default' => 'y',
				'options' => array(
					array('text' => '', 'value' => ''),
					array('text' => tra('y'), 'value' => 'y'),
					array('text' => tra('n'), 'value' => 'n'),
				)
			),
			'itemid' => array(
				'required' => false,
				'name' => tra('Tracker Item Id'),
				'description' => tra('Item id of tracker item'),
				'default' => '',
			),
			'likefilters' => array(
				'required' => false,
				'name' => tra('Like Filters'),
				'description' => tra('Filters for tracker query.'),
				'default' => ''
			),
			'andfilters' => array(
				'required' => false,
				'name' => tra('And Filters'),
				'description' => tra('Filters for tracker query.'),
				'default' => ''
			),
			'orfilters' => array(
				'required' => false,
				'name' => tra('Or Filters'),
				'description' => tra('Filters for tracker query.'),
				'default' => ''
			),
			'getlast' => array(
				'required' => false,
				'name' => tra('Get Last'),
				'description' => tra('Gets only the last item from the tracker.'),
				'default' => ''
			),
		)
	);
}

function wikiplugin_trackerquerytemplate($data, $params)
{
	global $tikilib;

	$handler = new dataToFieldHandler();

	$params = array_merge(
		array(
			'tracker'=>'',
			'debug'=>'',
			'byname'=>'y',
			'render'=>'y',
			'likefilters'=>'',
			'andfilters'=>'',
			'getlast'=>''
		),
		$params
	);

	foreach ($params as &$param) {//We parse the variables
		$param = $handler->parse($param);
	}


	$query = Tracker_Query::tracker($params['tracker'])->excludeDetails();

	$pattern = 'id';

	if (!empty($params['byname']) && $params['byname'] == 'y') {
		$query->byName();
		$pattern = 'name';
	}

	if (!empty($params['render']) && $params['render'] == 'n') {
		$query->render(false);
	}

	if (!empty($params['itemid']) || isset($_REQUEST['itemId'])) {
		if (isset($_REQUEST['itemId'])) { //itemId overrides parameters
			$query->itemId($_REQUEST['itemId']);
			unset($_REQUEST['itemId']); //we unset because nested plugins may need to have itemId set
		} else {
			$query->itemId($params['itemid']);
		}
	}

	if (!empty($params['likefilters'])) {
		$likefilters = explode(';', $params['likefilters']);
		foreach ($likefilters as $likefilter) {
			$filter = explode(':', $likefilter);
			$query->filterFieldByValueLike($filter[0], $filter[1]);
		}
	}

	if (!empty($params['andfilters'])) {
		$andfilters = explode(';', $params['andfilters']);
		foreach ($andfilters as $andfilter) {
			$filter = explode(':', $andfilter);
			$query->filterFieldByValue($filter[0], $filter[1]);
		}
	}

	if (!empty($params['orfilters'])) {
		$orfilters = explode(';', $params['orfilters']);
		foreach ($orfilters as $orfilter) {
			$filter = explode(':', $orfilter);
			$query->filterFieldByValueOr($filter[0], $filter[1]);
		}
	}

	if (!empty($params['debug']) && $params['debug'] == 'y') {
		$query->debug();
	}

	if (!empty($params['getlast']) && $params['getlast'] == 'y') {
		$items = $query->getLast();
	} else {
		$items = $query->query();
	}

	$newData = '';

	foreach ($items as $itemId => $fields) {
		$trackerId = $query->trackerId();
		$handler->set($pattern, $fields, $query->itemsRaw[$itemId], $itemId, $trackerId);

		$newData .= $handler->parse($data);
		$newData = "~np~" . $tikilib->parse_data($newData, array('is_html'=>true)) . "~/np~";
		$handler->pop();
	}

	return $newData;
}

class dataToFieldHandler
{
	public $pattern;
	private $trackerId;
	private $itemId;
	private $fields;
	private $fieldsRaw;
	public static $itemStack = array();

	function __construct() //initially set it to the last called item from trackers if it exists
	{
		$last = end(self::$itemStack);

		if (!empty($last)) {
			$this->set($last['pattern'], $last['fields'], $last['fieldsRaw'], $last['itemId'], $last['trackerId']);
		}
	}

	function set($pattern, $fields, $fieldsRaw, $itemId, $trackerId)
	{
		$this->pattern = $pattern;
		$this->trackerId = $trackerId;
		$this->itemId = $itemId;
		$this->fields = $fields;
		$this->fieldsRaw = $fieldsRaw;

		self::$itemStack[] = array(
			"pattern" => $this->pattern,
			"fields" => $this->fields,
			"fieldsRaw" => $this->fieldsRaw,
			"itemId" => $this->itemId,
			"trackerId" => $this->trackerId,
		);
	}

	function pop()
	{
		array_pop(self::$itemStack);
	}

	function parse($data)
	{
		global $tikilib;

		$checkedData = trim($data);
		if (empty($checkedData) || empty(self::$itemStack)) {
			return $data;
		}

		$data = str_replace('$trackerId$', $this->trackerId, $data);
		$data = str_replace('$itemId$', $this->itemId, $data);

		if ($this->pattern == 'name') {
			foreach ($this->fields as $key => $field) {
				$data = str_replace('$' . $key . '$', $field, $data);
				$data = str_replace('$~' . $key . '$', $this->fieldsRaw[$key], $data);
			}
		} else {
			preg_match_all('/[\{][$](.)+?[\}]/', $data, $matches);
			if (!empty($matches[0])) {
				foreach ($matches[0] as $match) {
					$data = str_replace($match, "(" . substr($match, 1, -1) . ")", $data);
				}
			}

			foreach ($this->fields as $key => $field) {
				$data = str_replace('($f_' . $key . ')', $field, $data);
				$data = str_replace('($~f_' . $key . ')', $this->fieldsRaw[$key], $data);
				$data = str_replace('{$f_' . $key . '}', $field, $data);
				$data = str_replace('{$~f_' . $key . '}', $this->fieldsRaw[$key], $data);
			}
		}

		if (strpos($data, '$created$')) {
			$data = str_replace('$created$', $tikilib->get_short_date($tikilib->getOne("SELECT created FROM tiki_tracker_items WHERE itemId = ?", array($this->itemId))), $data);
		}

		return $data;
	}
}
