<?php

// $Id: $

// Copyright (c) 2002-2008, Luis Argerich, Garland Foster, Eduardo Polidor, et. al.
// All Rights Reserved. See copyright.txt for details and a complete list of authors.
// Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See license.txt for details.

//this script may only be included - so its better to die if called directly.
if (strpos($_SERVER['SCRIPT_NAME'],basename(__FILE__))!=FALSE) {
  header('location: index.php');
  exit;
}

if ( ! ($prefs['feature_calendar'] == 'y' || $prefs['feature_action_calendar'] == 'y')) {
  if (isset($_SERVER['SCRIPT_NAME'])) {
    if ($_SERVER['SCRIPT_NAME'] == "tiki-calendar.php")
      $smarty->assign('msg', tra("This feature is disabled") . ": feature_calendar");
    elseif ($_SERVER['SCRIPT_NAME'] == "tiki-action_calendar.php")
      $smarty->assign('msg', tra("This feature is disabled") . ": feature_action_calendar");
    else
      $smarty->assign('msg', tra("This feature is disabled"));
  }
  $smarty->display("error.tpl");
  die;
}

$trunc = "20"; // put in a pref, number of chars displayed in cal cells

if (isset($_REQUEST["todate"]) && $_REQUEST['todate']) {
	$_SESSION['CalendarFocusDate'] = $_REQUEST['todate'];
 } elseif (!isset($_REQUEST['todate']) && isset($_SESSION['CalendarFocusDate']) && $_SESSION['CalendarFocusDate']) {
	$_REQUEST["todate"] = $_SESSION['CalendarFocusDate'];
} else {
	$focusdate = $tikilib->now;
	$_SESSION['CalendarFocusDate'] = $focusdate;
	$_REQUEST["todate"] = $_SESSION['CalendarFocusDate'];
}

$focusdate = $_REQUEST['todate'];
list($focus_day, $focus_month, $focus_year) = array(
	TikiLib::date_format("%d", $focusdate),
	TikiLib::date_format("%m", $focusdate),
	TikiLib::date_format("%Y", $focusdate)
);
$focuscell = TikiLib::make_time(0,0,0,$focus_month,$focus_day,$focus_year);
$smarty->assign('focusdate', $focusdate);
$smarty->assign('focuscell', $focuscell);

if ( isset($calendarViewMode) && $calendarViewMode != '' ) {
	$smarty->assign('viewmode', $calendarViewMode);
} else {
	if (!isset($_SESSION['CalendarViewMode']) or !$_SESSION['CalendarViewMode']) {
		$_SESSION['CalendarViewMode'] = $prefs['calendar_view_mode'];
	}

	if (isset($_REQUEST["viewmode"]) and $_REQUEST["viewmode"]) {
		$_SESSION['CalendarViewMode'] = $_REQUEST["viewmode"];
	}

	if (!isset($_SESSION['CalendarViewMode']) or !$_SESSION['CalendarViewMode']) {
		$_SESSION['CalendarViewMode'] = 'month';
	}
	$smarty->assign('viewmode', $_SESSION['CalendarViewMode']);
	$calendarViewMode = $_SESSION['CalendarViewMode'];
}

if (isset($_REQUEST["viewlist"])) {
	$viewlist = $_REQUEST["viewlist"];
	$_SESSION['CalendarViewList'] = $viewlist;
} elseif (!empty($_SESSION['CalendarViewList'])) {
	$viewlist = $_SESSION['CalendarViewList'];
} else {
	$viewlist = "";
}
$smarty->assign_by_ref('viewlist', $_SESSION['CalendarViewList']);

if (isset($_REQUEST["gbi"])) {
	$group_by_item = $_REQUEST["gbi"];
	$_SESSION['CalendarGroupByItem'] = $group_by_item;
} else {
	$group_by_item = "";
}
$smarty->assign_by_ref('group_by_item', $_SESSION['CalendarGroupByItem']);

$calendarViewGroups = $_SESSION['CalendarViewGroups'];
$calendarViewList = $_SESSION['CalendarViewList'];
$calendarGroupByItem = $_SESSION['CalendarGroupByItem'];

global $calendar_firstDayofWeek;
if ($calendar_firstDayofWeek == 'user') {
  $firstDayofWeek = (int)tra('First day of week: Sunday (its ID is 0) - translators you need to localize this string!');
  if ( $firstDayofWeek < 1 || $firstDayofWeek > 6 ) {
    $firstDayofWeek = 0;
  } 
} else {
  $firstDayofWeek = $calendar_firstDayofWeek;
} 
$smarty->assign('firstDayofWeek', $firstDayofWeek);

$strRef = tra("%H:%M %Z");
if (strstr($strRef, "%h") || strstr($strRef, "%g")) {
	$timeFormat12_24 = "12";
} else {
	$timeFormat12_24 = "24";
}
$smarty->assign('timeFormat12_24', $timeFormat12_24);
$smarty->assign('short_format_day', tra('%m/%d'));

$focus_day_limited = min($focus_day, 28); // To make "previous month" work if the current focus is on, for example, the last day of march.

$focus_prevday = TikiLib::make_time(0, 0, 0, $focus_month, $focus_day - 1, $focus_year);
$focus_prevweek = TikiLib::make_time(0, 0, 0, $focus_month, $focus_day - 7, $focus_year);
$focus_prevmonth = TikiLib::make_time(0, 0, 0,
	(($focus_month == 1) ? 12 : $focus_month - 1), // TikiLib::make_time() used with timezones doesn't support month = 0
	$focus_day_limited,
	(($focus_month == 1) ? $focus_year - 1 : $focus_year)
);
$focus_prevquarter = TikiLib::make_time(0, 0, 0,
	(($focus_month == 3) ? 12 : $focus_month - 3),
	$focus_day_limited,
	(($focus_month == 3) ? $focus_year - 1 : $focus_year)
);
$focus_prevsemester = TikiLib::make_time(0, 0, 0,
	(($focus_month == 6) ? 12 : $focus_month - 6),
	$focus_day_limited,
	(($focus_month == 6) ? $focus_year - 1 : $focus_year)
);
$focus_prevyear = TikiLib::make_time(0, 0, 0, $focus_month, $focus_day, $focus_year - 1);

$focus_nextday = TikiLib::make_time(0, 0, 0, $focus_month, $focus_day + 1, $focus_year);
$focus_nextweek = TikiLib::make_time(0, 0, 0, $focus_month, $focus_day + 7, $focus_year);
$focus_nextmonth = TikiLib::make_time(0, 0, 0, $focus_month + 1, $focus_day_limited, $focus_year);
$focus_nextquarter = TikiLib::make_time(0, 0, 0, $focus_month + 3, $focus_day_limited, $focus_year);
$focus_nextsemester = TikiLib::make_time(0, 0, 0, $focus_month + 6, $focus_day_limited, $focus_year);
$focus_nextyear = TikiLib::make_time(0, 0, 0, $focus_month, $focus_day, $focus_year + 1);

$smarty->assign('daybefore', $focus_prevday);
$smarty->assign('weekbefore', $focus_prevweek);
$smarty->assign('monthbefore', $focus_prevmonth);
$smarty->assign('quarterbefore', $focus_prevquarter);
$smarty->assign('semesterbefore', $focus_prevsemester);
$smarty->assign('yearbefore', $focus_prevyear);
$smarty->assign('dayafter', $focus_nextday);
$smarty->assign('weekafter', $focus_nextweek);
$smarty->assign('monthafter', $focus_nextmonth);
$smarty->assign('quarterafter', $focus_nextquarter);
$smarty->assign('semesterafter', $focus_nextsemester);
$smarty->assign('yearafter', $focus_nextyear);

$smarty->assign('focusday', $focus_day);
$smarty->assign('focusmonth', $focus_month);
$smarty->assign('focusdate', $focusdate);
$smarty->assign('focuscell', $focuscell);
$smarty->assign('now', $tikilib->now);
$smarty->assign('nowUser', $tikilib->now);

$weekdays = range(0, 6);
$hours = range(0, 23);

$d = 60 * 60 * 24;
$currentweek = TikiLib::date_format("%U", $focusdate);
$wd = TikiLib::date_format('%w', $focusdate);

//prepare for select first day of week (Hausi)
if ($firstDayofWeek == 1) {
	$wd--;
	if($wd == -1) {
		$wd = 6;
	}
}

if (isset($request_day)) $focus_day = $request_day;
if (isset($request_month)) $focus_month = $request_month;
if (isset($request_year)) $focus_year = $request_year;

$smarty->assign('viewmonth', $focus_month);
$smarty->assign('viewday', $focus_day);
$smarty->assign('viewyear', $focus_year);

// calculate timespan for sql query
if ($calendarViewMode == 'month' || $calendarViewMode == 'quarter' || $calendarViewMode == 'semester') {
	$daystart = TikiLib::make_time(0,0,0, $focus_month, 1, $focus_year);
} elseif ($calendarViewMode == 'year') {
	$daystart = TikiLib::make_time(0,0,0, 1, 1, $focus_year);
} else {
	$daystart = TikiLib::make_time(0,0,0, $focus_month, $focus_day, $focus_year);
}
$viewstart = $daystart; // viewstart is the beginning of the display, daystart is the beginning of the selected period
	
if ($calendarViewMode == 'month' ||
	 $calendarViewMode == 'quarter' ||
	 $calendarViewMode == 'semester' ||
	 $calendarViewMode == 'year' ) {
   $TmpWeekday = TikiLib::date_format("%w",$viewstart);
//prepare for select first day of week (Hausi)
   if($firstDayofWeek == 1){
	$TmpWeekday--;
	if($TmpWeekday == -1) {
		$TmpWeekday=6;
	}
   }

   // move viewstart back to Sunday....
   if ($viewlist != 'list') {
	   $viewstart -= $TmpWeekday * $d;
   }
   // this is the last day of $focus_month
   if ($calendarViewMode == 'month') {
     $viewend = TikiLib::make_time(0,0,0,$focus_month + 1, 1, $focus_year);
   } elseif ($calendarViewMode == 'quarter') {
     $viewend = TikiLib::make_time(0,0,0,$focus_month + 3, 1, $focus_year);
   } elseif ($calendarViewMode == 'semester') {
     $viewend = TikiLib::make_time(0,0,0,$focus_month + 6, 1, $focus_year);
   } elseif ($calendarViewMode == 'year') {
     $viewend = TikiLib::make_time(0,0,0,1, 1, $focus_year+1);
   } else {
     $viewend = TikiLib::make_time(0,0,0,$focus_month + 1, 0, $focus_year);
   }
   $viewend -= 1;
   $dayend=$viewend;
   $TmpWeekday = TikiLib::date_format("%w", $viewend);
   if ($viewlist != 'list') {
	   $viewend += (6 - $TmpWeekday) * $d;
   }
   // ISO weeks --- kinda mangled because ours begin on Sunday...
   $firstweek = TikiLib::date_format("%U", $viewstart + $d);
   $lastweek = TikiLib::date_format("%U", $viewend);
   if ($lastweek <= $firstweek) {
	   $startyear = TikiLib::date_format("%Y",$daystart-1);
	   $weeksinyear = TikiLib::date_format("%U",TikiLib::make_time(0,0,0,12,31,$startyear));
	   if ($weeksinyear == 1){
		   $weeksinyear = TikiLib::date_format("%U",TikiLib::make_time(0,0,0,12,28,$startyear));
	   }
	   $lastweek += $weeksinyear;
   }

   $numberofweeks = $lastweek - $firstweek;
} elseif ($calendarViewMode == 'week') {
   $firstweek = $currentweek;
   $lastweek = $currentweek;
   // then back up to the preceding Sunday;
   $viewstart -= $wd * $d;
   $daystart = $viewstart;
   // then go to the end of the week for $viewend
   $viewend = $viewstart + (7 * $d) - 1;
   $dayend=$viewend;
   $numberofweeks = 0;
} else {
   $firstweek = $currentweek;
   $lastweek = $currentweek;
   $viewend = $viewstart + ($d - 1);
   $dayend = $daystart;
   $weekdays = array(TikiLib::date_format('%w',$focusdate));
   $numberofweeks = 0;
}
// untested (by me, anyway!) function grabbed from the php.net site:
// [2004/01/05:rpg]
if ( ! function_exists('m_weeks') ) {
	function m_weeks($y, $m){
		// monthday array
		$monthdays = array(1=>31, 3=>31, 4=>30, 5=>31, 6=>30,7=>31,
				8=>31, 9=>30, 10=>31, 11=>30, 12=>31);
		// weekdays remaining in a week starting on 7 - Sunday...(could be changed)
		$weekdays = array(7=>7, 1=>6, 2=>5, 3=>4, 4=>3, 5=>2, 6=>1);
		$date = TikiLib::make_time( 0, 0, 0, $m, 1, $y);
		$leap = (TikiLib::date_format("%y", $date) % 4 == 0) ? true : false;
		// if it is a leap year set February to 29 days, otherwise 28
		$monthdays[2] = ($leap ? 29 : 28);
		// get the weekday of the first day of the month
		$wn = TikiLib::date_format("%w",$date);
		$days = $monthdays[$m] - $weekdays[$wn];
		return (ceil($days/7) + 1);
	}
}

$smarty->assign('viewstart', $viewstart);
$smarty->assign('viewend', $viewend);
$smarty->assign('numberofweeks', $numberofweeks);
$smarty->assign('daystart', $daystart);
$smarty->assign('dayend', $dayend);

$daysnames = array();
if ($firstDayofWeek == 0) {
	$daysnames[] = tra("Sunday");
}

array_push($daysnames, 
	tra("Monday"),
	tra("Tuesday"),
	tra("Wednesday"),
	tra("Thursday"),
	tra("Friday"),
	tra("Saturday")
);
if ($firstDayofWeek != 0) {
	$daysnames[] = tra("Sunday");
}
$weeks = array();
$cell = array();

if (empty($myurl))
	$myurl = 'tiki-calendar.php';
$jscal_url = "$myurl?todate=%s";
$smarty->assign('jscal_url', $jscal_url);

?>
