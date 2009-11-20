<?php
// (c) Copyright 2002-2009 by authors of the Tiki Wiki/CMS/Groupware Project
// 
// All Rights Reserved. See copyright.txt for details and a complete list of authors.
// Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See license.txt for details.
// $Id: /cvsroot/tikiwiki/tiki/tiki-poll_results.php,v 1.21.2.4 2007-12-06 14:14:11 nkoth Exp $
$section = 'poll';
require_once ('tiki-setup.php');
if ($prefs['feature_polls'] != 'y') {
	$smarty->assign('msg', tra('This feature is disabled') . ': feature_polls');
	$smarty->display('error.tpl');
	die;
}
// Now check permissions to access this page
if ($tiki_p_view_poll_results != 'y') {
	$smarty->assign('errortype', 401);
	$smarty->assign('msg', tra('Permission denied. You cannot view this page.'));
	$smarty->display('error.tpl');
	die;
}
global $pollib;
include_once ('lib/polls/polllib.php');
$auto_query_args = array('offset', 'pollId', 'maxRecords', 'scoresort_desc', 'scoresort_asc', 'sort_mode', 'list', 'vote_from_date', 'vote_to_date', 'which_date', 'from_Day', 'from_Month', 'from_Year', 'to_Day', 'to_Month', 'to_Year');
$smarty->assign('auto_args', implode(',', $auto_query_args));
if (!empty($_REQUEST['maxRecords'])) {
	$_REQUEST['maxRecords'] = $_REQUEST['maxRecords'];
	$smarty->assign('maxRecords', $_REQUEST['maxRecords']);
} else {
	$_REQUEST['maxRecords'] = - 1;
}
if (!isset($_REQUEST['find'])) {
	$_REQUEST['find'] = '';
}
$smarty->assign_by_ref('find', $_REQUEST['find']);
$now = $vote_from_date = $vote_to_date = $tikilib->now;
if (isset($_REQUEST['which_date'])) {
	$which_date = $_REQUEST['which_date'];
	if ($which_date == 'between') {
		if (!empty($_REQUEST['vote_from_date'])) {
			$vote_from_date = $_REQUEST['vote_from_date'];
		} else {
			$vote_from_date = TikiLib::make_time(0, 0, 0, $_REQUEST['from_Month'], $_REQUEST['from_Day'], $_REQUEST['from_Year']);
		}
		if (!empty($_REQUEST['vote_to_date'])) {
			$vote_to_date = $_REQUEST['vote_to_date'];
		} else {
			$vote_to_date = TikiLib::make_time(23, 59, 59, $_REQUEST['to_Month'], $_REQUEST['to_Day'], $_REQUEST['to_Year']);
		}
	}
	$smarty->assign_by_ref('which_date', $which_date);
} else {
	$which_date = '';
}
$pollIds = array();
if (!empty($_REQUEST['pollId'])) {
	$pollIds[] = $_REQUEST['pollId'];
	$smarty->assign_by_ref('pollId', $_REQUEST['pollId']);
} else {
	$polls = $polllib->list_active_polls(0, $_REQUEST['maxRecords'], 'votes_desc', $_REQUEST['find']);
	foreach($polls['data'] as $pId) {
		$pollIds[] = $pId['pollId'];
	}
}
$poll_info_arr = array();
$start_year = date('Y', $now);
foreach($pollIds as $pK => $pId) { // iterate each poll
	$poll_info = $polllib->get_poll($pId);
	$start_year = min($start_year, date('Y', $poll_info['publishDate']));
	if ($which_date == 'all') {
		$vote_from_date = $vote_to_date = 0;
	} elseif ($which_date == 'between') {
		$poll_info['from'] = $vote_from_date;
		$poll_info['to'] = $vote_to_date;
	} elseif ($poll_info['voteConsiderationSpan'] > 0) {
		$poll_info['from'] = $vote_from_date = $now - $poll_info['voteConsiderationSpan'] * 24 * 3600;
		$vote_to_date = $now;
	} else {
		$vote_from_date = $vote_to_date = 0;
	}
	$options = $polllib->list_poll_options($pId, $vote_from_date, $vote_to_date);
	//echo '<pre>'; print_r($options); echo '</pre>';
	$poll_info_arr[$pK] = $poll_info;
	if ($vote_from_date != 0) {
		$poll_info_arr[$pK]['votes'] = 0;
		foreach($options as $option) {
			$poll_info_arr[$pK]['votes']+= $option['votes'];
		}
	}
	$temp_max = count($options);
	$total = 0;
	$isNum = true; // try to find if it is a numeric poll with a title like +1, -2, 1 point...
	foreach($options as $i => $option) {
		if ($option['votes'] == 0) {
			$percent = 0;
		} else {
			$percent = number_format($option['votes'] * 100 / $poll_info_arr[$pK]['votes'], 2);
			$options[$i]['percent'] = $percent;
			if ($isNum) {
				if (preg_match('/^([+-]?[0-9]+).*/', $option['title'], $matches)) {
					$total+= $option['votes'] * $matches[1];
				} else {
					$isNum = false; // it is not a nunmeric poll
					
				}
			}
		}
		$width = $percent * 200 / 100;
		$options[$i]['width'] = $percent;
	}
	$poll_info_arr[$pK]['options'] = $options;
	$poll_info_arr[$pK]['total'] = $total;
} // end iterate each poll
//echo '<pre>'; print_r($poll_info_arr); echo '</pre>';
function scoresort($a, $b) {
	if (isset($_REQUEST['scoresort_asc'])) {
		$i = $_REQUEST['scoresort_asc'];
	} else {
		$i = $_REQUEST['scoresort_desc'];
	}
	// must first sort based on missing, otherwise missing index will occur when trying to read more info.
	if (count($a['options']) <= $i && count($b['options']) <= $i) {
		return 0;
	} elseif (count($a['options']) <= $i) {
		return -1;
	} elseif (count($b['options']) <= $i) {
		return 1;
	}
	if ($a['options'][$i]['title'] == $poll_info_arr['options'][$i]['title'] && $b['options'][$i]['title'] != $poll_info_arr['options'][$i]['title']) {
		return 1;
	}
	if ($a['options'][$i]['title'] != $poll_info_arr['options'][$i]['title'] && $b['options'][$i]['title'] == $poll_info_arr['options'][$i]['title']) {
		return -1;
	}
	if ($a['options'][$i]['width'] == $b['options'][$i]['width']) {
		return 0;
	}
	if (isset($_REQUEST['scoresort_asc'])) {
		return ($a['options'][$i]['width'] < $b['options'][$i]['width']) ? -1 : 1;
	} else {
		return ($a['options'][$i]['width'] > $b['options'][$i]['width']) ? -1 : 1;
	}
}
if (isset($_REQUEST['scoresort_desc'])) {
	$smarty->assign('scoresort_desc', $_REQUEST['scoresort_desc']);
} elseif (isset($_REQUEST['scoresort_asc'])) {
	$smarty->assign('scoresort_asc', $_REQUEST['scoresort_asc']);
}
if (isset($_REQUEST['scoresort']) || isset($_REQUEST['scoresort_desc'])) {
	$t_arr = $poll_info_arr;
	$sort_ok = usort($t_arr, 'scoresort');
	if ($sort_ok) $poll_info_arr = $t_arr;
}
if ($tiki_p_view_poll_voters == 'y' && !empty($_REQUEST['list']) && isset($_REQUEST['pollId'])) {
	$smarty->assign_by_ref('list', $_REQUEST['list']);
	if (empty($_REQUEST['sort_mode'])) {
		$_REQUEST['sort_mode'] = 'user_asc';
	}
	$smarty->assign_by_ref('sort_mode', $_REQUEST['sort_mode']);
	if (!isset($_REQUEST['offset'])) {
		$_REQUEST['offset'] = 0;
	}
	$smarty->assign_by_ref('offset', $_REQUEST['offset']);
	$list_votes = $tikilib->list_votes('poll' . $_REQUEST['pollId'], $_REQUEST['offset'], $prefs['maxRecords'], $_REQUEST['sort_mode'], $_REQUEST['find'], 'tiki_poll_options', 'title', $vote_from_date, $vote_to_date);
	$smarty->assign_by_ref('list_votes', $list_votes['data']);
	$smarty->assign_by_ref('cant_pages', $list_votes['cant']);
}
// Poll comments
if ($prefs['feature_poll_comments'] == 'y' && isset($_REQUEST['pollId'])) {
	$comments_per_page = $prefs['poll_comments_per_page'];
	$thread_sort_mode = $prefs['poll_comments_default_ordering'];
	$comments_vars = array('pollId');
	$comments_prefix_var = 'poll:';
	$comments_object_var = 'pollId';
	include_once ('comments.php');
}
$smarty->assign_by_ref('poll_info_arr', $poll_info_arr);
$smarty->assign_by_ref('start_year', $start_year);
$smarty->assign_by_ref('vote_from_date', $vote_from_date);
$smarty->assign_by_ref('vote_to_date', $vote_to_date);
// the following 4 lines preserved to preserve environment for old templates
$smarty->assign_by_ref('poll_info', $poll_info);
$smarty->assign('title', $poll_info['title']);
$smarty->assign_by_ref('options', $options);
ask_ticket('poll-results');
// Display the template
$smarty->assign('mid', 'tiki-poll_results.tpl');
$smarty->display('tiki.tpl');
