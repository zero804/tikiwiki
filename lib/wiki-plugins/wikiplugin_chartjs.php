<?php
// (c) Copyright 2002-2015 by authors of the Tiki Wiki CMS Groupware Project
//
// All Rights Reserved. See copyright.txt for details and a complete list of authors.
// Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See license.txt for details.
// $Id$

function wikiplugin_chartjs_info()
{
	return array(
		'name' => tr('Chart JS'),
		'documentation' => 'PluginChartJS',
		'description' => tra('Create a JS Chart'),
		'prefs' => array('wikiplugin_chartjs'),
		'body' => tr('JSON encoded array for data and options.'),
		'tags' => array('advanced'),
		'introduced' => 16,
		'params' => array(
			'id' => array(
				'name' => tra('Chart Id'),
				'description' => tr('A custom ID for the chart.'),
				'filter' => 'text',
				'default' => 'tikiChart1, tikiChart2 etc',
				'since' => '16.0',
			),
			'type' => array(
				'name' => tra('Chart Type'),
				'description' => tr('The type of chart. Currently works with pie, bar and doughnut'),
				'filter' => 'text',
				'default' => 'pie',
				'since' => '16.0',
			),
			'height' => array(
				'name' => tra('Chart Height'),
				'description' => tr('The height of the chart in px'),
				'filter' => 'text',
				'default' => '200',
				'since' => '16.0',
			),
			'width' => array(
				'name' => tra('Chart Width'),
				'description' => tr('The width of the chart in px'),
				'filter' => 'text',
				'default' => '200',
				'since' => '16.0',
			),
			'values' => array(
				'name' => tra('Chart data values'),
				'required' => true,
				'description' => tr('Colon-separated values for the chart'),
				'filter' => 'text',
				'since' => '16.0',
			),
			'data_labels' => array(
				'name' => tra('Chart data labels'),
				'description' => tr('Colon-separated labels for the datasets in the chart. Max 10, if left empty'),
				'filter' => 'text',
				'default' => 'A:B:C:D:E:F:G:H:I:J',
				'since' => '16.0',
			),
			'data_colors' => array(
				'name' => tra('Chart colors'),
				'description' => tr('Colon-separated colors for the datasets in the chart. Max 10, if left empty'),
				'filter' => 'text',
				'default' => 'red:blue:green:purple:grey:orange:yellow:black:brown:cyan',
				'since' => '16.0',
			),
			'data_highlights' => array(
				'name' => tra('Chart highlight'),
				'description' => tr('Colon-separated color of chart section when highlighted'),
				'filter' => 'text',
				'default' => '',
				'since' => '16.0',
			),
			'debug' => array(
				'name' => tra('Debug Mode'),
				'description' => tr('Uses the non-minified version of the chart.js library for easier debugging.'),
				'filter' => 'digits',
				'default' => 0,
				'advanced' => true,
				'since' => '18.3',
			),
		),
		'iconname' => 'pie-chart',
	);
}

function wikiplugin_chartjs($data, $params)
{
	static $instance = 0;
	$instance++;

	if (empty($params['id'])) {
		$params['id'] = "tikiChart$instance";
	}

	//set defaults
	$plugininfo = wikiplugin_chartjs_info();
	$defaults = array();
	foreach ($plugininfo['params'] as $key => $param) {
		$defaults[$key] = $param['default'];
	}
	$params = array_merge($defaults, $params);

	if (empty($params['data_highlights'])) {
		$params['data_highlights'] = $params['data_colors'];
	}

	$values = explode(':', $params['values']);
	$data_labels = explode(':', $params['data_labels']);
	$data_colors = explode(':', $params['data_colors']);
	$data_highlights = explode(':', $params['data_highlights']);

	if (empty($values)) {
		return tr('Values must be set for chart');
	}
	if (empty(trim($data))) {
		$data = [
			'labels'   => array_slice($data_labels, 0, count($values)),
			'datasets' => [
				[
					'data'                 => $values,
					'backgroundColor'      => array_slice($data_colors, 0, count($values)),
					'hoverBackgroundColor' => array_slice($data_highlights, 0, count($values)),
				],
			],
		];
		$options = [];
	} else {
		$data = json_decode($data, true);
		if (isset($data['options']) && isset($data['data'])) {
			$options = $data['options'];
			$data = $data['data'];
		}
	}

	$min = $params['debug'] ? '': 'min.';

	TikiLib::lib('header')->add_jsfile("vendor_bundled/vendor/chartjs/Chart.js/Chart.{$min}js")
		->add_jq_onready('
setTimeout(function () {
	var chartjs_' . $params['id'] . ' = new Chart("' . $params['id'] . '", {
		type: "' . $params['type'] . '",
		data: ' . json_encode($data) . ',
		options: ' . json_encode($options) . '
	})
},
500);');

	return '<div class="tiki-chartjs" style="width:' . $params['width'] . 'px;height:' . $params['height'] . 'px">' .
				'<canvas id="' . $params['id'] . '" width="' . $params['width'] . '" height="' . $params['height'] . '">' .
			'</canvas></div>';
}
