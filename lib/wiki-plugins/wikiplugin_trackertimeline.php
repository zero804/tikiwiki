<?php
// (c) Copyright 2002-2011 by authors of the Tiki Wiki CMS Groupware Project
// 
// All Rights Reserved. See copyright.txt for details and a complete list of authors.
// Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See license.txt for details.
// $Id$

function wikiplugin_trackertimeline_info() {
	return array(
		'name' => tra( 'Tracker Timeline' ),
		'documentation' => 'PluginTrackerTimeline',
		'description' => tra('Show a timeline view of a tracker'),
		'prefs' => array( 'wikiplugin_trackertimeline', 'feature_trackers' ),
		'icon' => 'pics/icons/timeline_marker.png',
		'params' => array(
			'tracker' => array(
				'required' => true,
				'name' => tra('Tracker ID'),
				'description' => tra('Numeric value representing the tracker ID'),
				'filter' => 'digits',
				'default' => '',
			),
			'title' => array(
				'required' => true,
				'name' => tra('Title Field'),
				'description' => tra('Tracker Field ID containing the item title.'),
				'filter' => 'digits',
				'default' => '',
			),
			'summary' => array(
				'required' => true,
				'name' => tra('Summary Field'),
				'description' => tra('Tracker Field ID containing the summary of the item. The summary will be displayed on the timeline when the item is focused.'),
				'filter' => 'digits',
				'default' => '',
			),
			'start' => array(
				'required' => true,
				'name' => tra('Start Date'),
				'description' => tra('Tracker Field ID containing the element start date. The field must be a datetime/jscalendar field.'),
				'filter' => 'digits',
				'default' => ''
			),
			'end' => array(
				'required' => true,
				'name' => tra('End Date'),
				'description' => tra('Tracker Field ID containing the element end date. The field must be a datetime/jscalendar field.'),
				'filter' => 'digits',
				'default' => ''
			),
			'group' => array(
				'required' => true,
				'name' => tra('Element Group'),
				'description' => tra('Tracker Field ID containing the element\'s group. Elements of a same group are displayed on the same row.'),
				'filter' => 'digits',
				'default' => '',
			),
			'lower' => array(
				'required' => true,
				'name' => tra('Lower Bound'),
				'description' => tra('Date from which element should be displayed. Date must be provided in YYYY-MM-DD HH:mm:ss format.'),
				'filter' => 'striptags',
				'default' => '',
				'accepted' => 'Date in YYYY-MM-DD HH:mm:ss format',
			),
			'upper' => array(
				'required' => true,
				'name' => tra('Upper Bound'),
				'description' => tra('Date until which element should be displayed. Date must be provided in YYYY-MM-DD HH:mm:ss format.'),
				'filter' => 'striptags',
				'default' => '',
				'accepted' => 'Date in YYYY-MM-DD HH:mm:ss format',
			),
			'scale1' => array(
				'required' => false,
				'name' => tra('Primary Scale Unit'),
				'description' => tra('Unit of time to use for the primary scale (default to hour - * denotes SIMILE only)'),
				'filter' => 'alpha',
				'default' => 'hour',
				'options' => array(
					array('text' => '', 'value' => ''), 
					array('text' => tra('Hour'), 'value' => 'hour'), 
					array('text' => tra('Day'), 'value' => 'day'), 
					array('text' => tra('Week'), 'value' => 'week'), 
					array('text' => tra('Month'), 'value' => 'month'), 
					array('text' => tra('Year'), 'value' => 'year'),
					array('text' => tra('Decade *'), 'value' => 'decade'),
					array('text' => tra('Century *'), 'value' => 'century'),
				)
			),
			'scale2' => array(
				'required' => false,
				'name' => tra('Secondary Scale Unit'),
				'description' => tra('Unit of time to use for the secondary scale (default to empty - * denotes SIMILE only)'),
				'filter' => 'alpha',
				'default' => '',
				'options' => array(
					array('text' => '', 'value' => ''), 
					array('text' => tra('Hour'), 'value' => 'hour'), 
					array('text' => tra('Day'), 'value' => 'day'), 
					array('text' => tra('Week'), 'value' => 'week'), 
					array('text' => tra('Month'), 'value' => 'month'), 
					array('text' => tra('Year'), 'value' => 'year'),
					array('text' => tra('Decade *'), 'value' => 'decade'),
					array('text' => tra('Century *'), 'value' => 'century'),
				)
			),
			'link_group' => array(
				'required' => false,
				'name' => tra('Link Group Name'),
				'description' => tra('Convert the group name to a link'),
				'filter' => 'alpha',
				'default' => '',
				'options' => array(
					array('text' => '', 'value' => ''), 
					array('text' => tra('Yes'), 'value' => 'y'), 
					array('text' => tra('No'), 'value' => 'n')
				)
			),
			'link_page' => array(
				'required' => false,
				'name' => tra('Page Link Field'),
				'description' => tra('Tracker Field ID containing the page name for item details.'),
				'filter' => 'digits',
				'default' => '',
			),
			'simile_timeline' => array(
				'required' => false,
				'name' => tra('SIMILE Timeline'),
				'description' => tra('Use the SIMILE Timeline Widget.'),
				'filter' => 'alpha',
				'default' => 'n',
				'options' => array(
					array('text' => tra('Yes'), 'value' => 'y'),
					array('text' => tra('No'), 'value' => 'n'),
				),
			),
			'image_field' => array(
				'required' => false,
				'name' => tra('Image Field'),
				'description' => tra('Tracker Field ID containing in image.'),
				'filter' => 'digits',
				'default' => '',
			),
		)
	);
}

function wikiplugin_trackertimeline( $data, $params ) {
	global $trklib, $smarty, $tikilib;
	require_once 'lib/trackers/trackerlib.php';

	if( ! isset( $params['tracker'] ) )
		return "^" . tr("Missing parameter: %0", 'tracker') . "^";

	$default = array('scale1' => 'hour', 'simile_timeline' => 'n');
	$params = array_merge($default, $params);
	$formats = array('hour'=>'H:i', 'day'=>'jS', 'week' => 'jS', 'month'=>'m', 'year'=>'y');

	$start = strtotime( $params['lower'] );
	$end = strtotime( $params['upper'] );
	$size = $end - $start;

	if( $size <= 0 )
		return "^" . tr("Start date after end date.") . "^";

	$fieldIds = array(
		$params['title'] => 'title',
		$params['summary'] => 'summary',
		$params['start'] => 'start',
		$params['end'] => 'end',
		$params['group'] => 'group',
	);

	if( isset($params['link_page']) ) {
		$fieldIds[ $params['link_page'] ] = 'link';
	}

	if( !empty($params['image_field']) ) {
		$fieldIds[ $params['image_field'] ] = 'image';
	}

	$fields = array();
	foreach( $fieldIds as $id => $label )
		$fields[$id] = $trklib->get_tracker_field( $id );

	$items = $trklib->list_items( $params['tracker'], 0, -1, '', $fields );

	$data = array();
	foreach( $items['data'] as $item ) {
		// Collect data
		$detail = array( 'item' => $item['itemId'] );
		foreach( $item['field_values'] as $field ) {
			$detail[ $fieldIds[$field['fieldId']] ] = $field['value'];
		}

		// Filter elements
		if( $detail['start'] >= $detail['end'] )
			continue;
		if( $detail['end'] <= $start || $detail['start'] > $end )
			continue;

		$detail['lstart'] = max( $start, $detail['start'] );
		$detail['lend'] = min( $end, $detail['end'] );
		$detail['lsize'] = round( ( $detail['lend'] - $detail['lstart'] ) / $size * 80 );

		$detail['fstart'] = date( $formats[$params['scale1']], $detail['start'] );
		$detail['fend'] = date( $formats[$params['scale1']], $detail['end'] );
		$detail['psummary'] = $tikilib->parse_data( $detail['summary'] );

		$detail['encoded'] = json_encode( $detail );

		// Add to data list
		if( ! array_key_exists( $detail['group'], $data ) )
			$data[$detail['group']] = array();
		$data[ $detail['group'] ][] = $detail;
	}

	$new = array();
	foreach( $data as $group => &$list ) {
		wp_ttl_organize( $group, $start, $size, $list, $new );
	}
	$data = array_merge( $data, $new );
	ksort($data);

	if ($params['simile_timeline'] !== 'y') {
		$smarty->assign( 'wp_ttl_data', $data );
		$layouts = array();
		if( isset( $params['scale2'] ) && $layout = wp_ttl_genlayout( $start, $end, $size, $params['scale2'] ) ) {
			$layouts[] = $layout;
		}
		$layouts[] = wp_ttl_genlayout( $start, $end, $size, isset($params['scale1']) ? $params['scale1'] : 'hour' );
		$smarty->assign( 'layouts', $layouts );
		$smarty->assign( 'link_group_names', isset($params['link_group']) && $params['link_group'] == 'y' );
		return $smarty->fetch('wiki-plugins/wikiplugin_trackertimeline.tpl');

	} else {	// SIMILE Timeline Widget setup

		global $headerlib;

		// the simile api has to be included in the head to work it seems (tiki js files live at end of body now)
		$headerlib->add_js('
(function() {
var head = document.getElementsByTagName("head")[0];
var script = document.createElement("script");
script.type = "text/javascript";
script.language = "JavaScript";
script.src = "http://static.simile.mit.edu/timeline/api-2.3.0/timeline-api.js?bundle=true";
head.appendChild(script);
})();
');

		// prepare the data for SIMILE widget - to be included in the page for now (ajax feed to come)
		$ttl_data = array();
		$events = array();
		foreach( $data as $group => $list ) {	// ignoring group for now
			foreach( $list as $item) {
				$event = array(
					'title' => $item['title'],
					'start' => date('r', $item['start']),
					'description' => $item['summary'],
				);
				if (!empty( $item['end'])) {
					$event['end'] = date('r', $item['end']);
					$event['isDuration'] = true;
				}
				if (!empty( $item['link'])) {
					$event['link'] = $item['link'];
				}
				if (!empty( $item['image'])) {
					$event['image'] = $item['image'];
				}
				$events[] = $event;
			}
			$ttl_data = array(
				'dateTimeFormat' => '',	// iso8601
//				'wikiURL' => '',
//				'wikiSection' => '',
				'events' => $events,
			);
		}
		$js = 'ajaxLoadingShow("ttl_timeline");';
		$js .= 'var ttl_eventData = ' . json_encode($ttl_data) . ";\n";

		$js .= '
var ttlTimelineReady = false, ttlInitCount = 0, ttlTimeline, ttlInit = function() {
	// wait for Timeline to be loaded
	if (ttlInitCount < 12 && (typeof window.Timeline === "undefined" ||
			typeof window.Timeline.createBandInfo === "undefined" ||
			typeof window.Timeline.DateTime === "undefined" ||
			typeof window.Timeline.GregorianDateLabeller === "undefined" ||
			typeof window.Timeline.GregorianDateLabeller.getMonthName === "undefined" )) {

		if (ttlInitCount > 10) {	// at least 5 secs - reload
			location.replace(location.href);
		}
		window.setTimeout( function() { ttlInit(); }, 500);
		ttlInitCount++;
		return;
	}

	if (!ttlTimelineReady) {	// just seems to need a little bit longer...
		ttlTimelineReady = true;
		window.setTimeout( function() { ttlInit(); }, 500);
		return;
	}
	
	var ttl_eventSource = new Timeline.DefaultEventSource();
	ttl_eventSource.loadJSON(ttl_eventData, ".");	// The data
	
	var bandInfos = [
		window.Timeline.createBandInfo({
			width:          "' . (empty($params['scale2']) ? '100%' : '70%' ) . '",
			intervalUnit:   window.Timeline.DateTime.' . (strtoupper($params['scale1'])) . ',
			eventSource:	ttl_eventSource,
			intervalPixels: 100
		})';
		if (!empty($params['scale2'])) {
			$js .= ',
		window.Timeline.createBandInfo({
			width:          "30%",
			intervalUnit:   window.Timeline.DateTime.' . (strtoupper($params['scale2'])) . ',
			eventSource:	ttl_eventSource,
			intervalPixels: 200,
			layout:			"overview"
		})';
		}
		$js .= '];';
		if (!empty($params['scale2'])) {
			$js .= '
	bandInfos[1].syncWith = 0;
	bandInfos[1].highlight = true;
	//bandInfos[1].eventPainter.setLayout(bandInfos[0].eventPainter.getLayout());
';
		}
		$js .= '
	ttlTimeline = window.Timeline.create(document.getElementById("ttl_timeline"), bandInfos);
	ajaxLoadingHide();
	ttlTimeline.layout(); // display the Timeline

}	// end ttlInit
ttlInit();

var ttlResizeTimerID = null;
$(window).resize( function () {
	if (ttlTimeline && ttlResizeTimerID == null) {
		ttlResizeTimerID = window.setTimeout(function() {
			resizeTimerID = null;
			ttlTimeline.layout();
		}, 500);
	}
});';

		$headerlib->add_jq_onready( $js, 10);
		$out = '<div id="ttl_timeline" style="height: 150px; border: 1px solid #aaa"></div>';
		return $out;
	}
}

function wp_ttl_organize( $name, $base, $size, &$list, &$new ) {
	usort( $list, 'wp_ttl_sort_cb' );

	$first = $list;
	$list = array();
	$remaining = array();

	$pos = $base;
	foreach( $first as $item ) {
		if( $item['lstart'] < $pos ) {
			$remaining[] = $item;
			continue;
		}

		$item['lpad'] = round( ( $item['lstart'] - $pos ) / $size * 80 );
		$pos = $item['lend'];

		$list[] = $item;
	}

	if( count( $remaining ) ) {
		wp_ttl_organize( "$name ", $base, $size, $remaining, $new );
		$new["$name "] = $remaining;
	}
}

function wp_ttl_sort_cb( $a, $b ) {
	if( $a['start'] == $b['start'] )
		return 0;
	if( $a['start'] < $b['start'] )
		return -1;
	if( $a['start'] > $b['start'] )
		return 1;
}

function wp_ttl_genlayout( $start, $end, $full, $type ) {
	switch( $type ) {
	case 'empty': 
	case '':
		return;
	case 'hour': 
		$size = 3600;
		$pos = $start - ( $start + $size ) % $size;
		break;
	case 'day': 
		$size = 86400;

		if( date( 'H:i:s', $start ) == '00:00:00' ) {
			$pos = $start;
		} else {
			$pos = strtotime( date( 'Y-m-d 00:00:00', $start + $size ) );
		}
		break;
	case 'week':
		$size = 604800;

		if( date( 'H:i:sw', $start ) == '00:00:000' ) {
			$pos = $start;
		} else {
			$pos = strtotime( date( 'Y-m-d 00:00:00', $start + $size ) );
		}

		$pos += 86400 * ( 6 - date('w', $start) );

		break;
	case 'month':
		if( date( 'd H:i:s', $start ) == '01 00:00:00' ) {
			$pos = $start;
		} else {
			$pos = strtotime( date( 'Y-m-01 00:00:00', strtotime( 'next month', $start ) ) );
		}

		$size = date( 't', $pos ) * 86400;

		break;
	case 'year':
		if( date( 'm-d H:i:s', $start ) == '01-01 00:00:00' ) {
			$pos = $start;
		} else {
			$pos = strtotime( date( 'Y-01-01 00:00:00', strtotime( 'next year', $start ) ) );
		}

		$size = date( 'L', $pos ) * 86400 + 86400*365;
		break;
	}

	$layout = array(
		'size' => round( $size / $full * 80 ),
		'blocks' => array(
		),
	);

	$layout['pad'] = round( ($pos - $start) / $full * 80 );

	for( $i = $pos; $end > $i + $size; $i += $size ) {
		switch( $type ) {
		case 'hour': $layout['blocks'][] = date( 'H:i', $i ); break;
		case 'day': $layout['blocks'][] = date( 'j', $i ); break;
		case 'week': $layout['blocks'][] = date( 'j', $i ); break;
		case 'month': $layout['blocks'][] = date( 'M', $i ); break;
		case 'year': $layout['blocks'][] = date( 'Y', $i ); break;
		}

		switch( $type ) {
		case 'month':
			$size = date( 't', $i ) * 86400;
			break;
		case 'year':
			$size = date( 'L', $i ) * 86400 + 86400*365;
			break;
		}
	}

	return $layout;
}
