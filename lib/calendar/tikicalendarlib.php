<?php

class TikiCalendarLib extends TikiLib {

	function TikiCalendarLib($db) {
		$this->TikiLib($db);
	}

	function list_tiki_items($tikiobj, $user, $tstart, $tstop, $offset, $maxRecords, $sort_mode, $find) {
		global $user;
		$ret = array();
		if ( ! is_array($tikiobj) ) return $ret;
		$tikiobj =& array_unique($tikiobj);
		if ( in_array('wiki', $tikiobj) ) {
			$tikiobj[] = 'wiki page';
			$tikiobj[] = 'wiki comment';
		}

		foreach ( $tikiobj as $type ) {
			if ( $type != '' && $type != 'wiki' ) {
				$objectType = ( $type == 'wiki comment' ) ? 'wiki page' : $type;
				$result = $this->get_object_cal_infos($type, array($tstart, $tstop, $objectType));

				if ( is_object($result) ) while ($res = $result->fetchRow()) {
					if ( $res['start'] > 0 ) {
						$res['show_description'] = 'y';
						$res['visible'] = 'y';
						$res['type'] = $type;
						$dstart = TikiLib::make_time(
							0,0,0,
							TikiLib::date_format('%m', $res['start']),
							TikiLib::date_format('%d', $res['start']),
							TikiLib::date_format('%Y', $res['start'])
						);
						$res['time'] = TikiLib::date_format('%H%M', $res['start']);
						$res['when'] = TikiLib::date_format('%H:%M', $res['start']);
						$when = '<b>'.$res['when'].'</b>';
						$url_vars = array($res['id'], $res['id2']);
						switch ( $res['type'] ) {
							case 'art': $res['description'] = $this->parse_data($res['description']); break;
							case 'blog': $res['name'] = $res['parent'].' :: '.$res['name']; break;
							case 'dir': $res['description'] = addslashes($res['dir_url']).'<br />'.$res['description']; break;
							case 'forum': if ( $res['fid'] > 0 ) $url_vars = array($res['fid'], $res['id2'], 'threadId'.$res['id']); break;
							case 'gal': $res['description'] = tra('New Image Uploaded by').' %s'; break;
							case 'nl':
								$res['description'] = tra('New Subscriptions');
								$res['head'] = ' ... '.$res['head'];
								break;
							case 'track':
								$res['description'] = tra('New Item in Tracker');
								$res['parent'] = tra('tracker');
								break;
							case 'wiki page': $res['parent'] = 'wiki'; break;
						} 
						$res['url'] = $this->get_object_url($res['type'], $url_vars);
						if ( $res['user'] != '' ) {
							include_once('lib/smarty_tiki/modifier.username.php');
							$res['user'] = smarty_modifier_username($res['user']);
							if ( ! strpos($res['description'], '%s') ) {
								$br = ( $res['description'] == '' ) ? '' : '<br />';
								$res['description'] = '<i>'.tra('by').' %s</i>'.$br.$res['description'];
							}
							$res['description'] = sprintf($res['description'], $res['user']);
						}
						$res['description'] = str_replace(array('"',"\n|\r"), array("'",''), $res['description']);
						if ( $res['name'] == '' ) $res['name'] = $res['id'];
						
						$res['where'] = str_replace("\n|\r", '', addslashes($res['parent']));
						if ( ( ! isset($where) || $where == '' ) && $res['parent'] != '' ) {
							$where = ' '.tra('in').' <b>'.$res['where'].'</b>';
						}
						if ( $res['head'] == '' ) $res['head'] = $when.$where;
						
						$ret[$dstart][] = $res;
						
						unset($where);
						unset($when);
					}
				}
			}
		}
		return $ret;
	}

	function get_object_cal_infos($type, $bindvars = null) {
		switch ( $type ) {
			case 'art': $query = 'select `articleId` as `id`, `title` as `name`, `heading` as `description`, `authorName` as `user`, `topicName` as `parent`, `publishDate` as `start` from `tiki_articles` where (`publishDate`>? and `publishDate`<?)'; break;
			case 'blog': $query = 'select p.`created` as `start`, p.`user` as `user`, p.`title` as `name`, b.`title` as `parent`, b.`blogId` as `id` from `tiki_blog_posts` as p left join `tiki_blogs` as b on p.`blogId`=b.`blogId` where (p.`created`>? and p.`created`<?)'; break;
			case 'chart': $query = 'select `chartId` as `id`, `created` as `start`, `title` as `name`, `description` from `tiki_charts` where (`created`>? and `created`<?)'; break;
			case 'dir': $query = 'select `siteId` as `id`, `created` as `start`, `name`, `description`, `url` as `dir_url` from `tiki_directory_sites` where (`created`>? and `created`<?)'; break;
			case 'faq': $query = 'select `faqId` as `id`, `created` as `start`, `title` as `name`, `description` from `tiki_faqs` where (`created`>? and `created`<?)'; break;
			case 'fgal': $query = 'select f.`created` as `start`, f.`user` as `user`, f.`name` as `name`, f.`description` as `description`, g.`galleryId` as `id`, g.`name` as `parent` from `tiki_files` as f left join `tiki_file_galleries` as g on f.`galleryId`=g.`galleryId` where (f.`created`>? and f.`created`<?)'; break;
			case 'forum': $query = 'select c.`commentDate` as `start`, c.`threadId` as `id`, c.`userName` as `user`, c.`title` as `name`, f.`name` as `parent`, f.`forumId` as `fid`, c.`parentId` as `id2` from `tiki_comments` as c, `tiki_forums` as f where c.`object`=f.`forumId` and (c.`commentDate`>? and c.`commentDate`<?) and c.`objectType` = ?'; break;
			case 'gal': $query = 'select i.`imageId` as `id`, i.`created` as `start`, i.`user` as `user`, i.`name` as `name`, g.`name` as `parent`, g.`galleryId` as `id2` from `tiki_images` as i left join `tiki_galleries` as g on g.`galleryId`=i.`galleryId` where (i.`created`>? and i.`created`<?)'; break;
			case 'nl': $query = "select count(s.`email`) as `head`, max(s.`subscribed`) as `start`, s.`nlId` as `id`, n.`name` as `name` from `tiki_newsletter_subscriptions` as s left join `tiki_newsletters` as n on n.`nlId`=s.`nlId`  where (`subscribed`>? and `subscribed`<?) group by s.`nlId`, FROM_UNIXTIME(s.`subscribed`,'%d')"; break;
			case 'quiz': $query = 'select `quizId` as `id`, `created` as `start`, `name`, `description` from `tiki_quizzes` where (`created`>? and `created`<?)'; break;
			case 'surv': $query = 'select `surveyId` as `id`, `created` as `start`, `name`, `description` from `tiki_surveys` where (`created`>? and `created`<?)'; break;
			case 'track': $query = 'select i.`itemId` as `id`, i.`created` as `start`, t.`name` as `name`, t.`trackerId` as `id2` from `tiki_tracker_items` as i left join `tiki_trackers` as t on t.`trackerId`=i.`trackerId` where (i.`created`>? and i.`created`<?)'; break;
			case 'wiki comment': $query = 'select c.`commentDate` as `start`, c.`userName` as `user`, c.`title` as `name`, c.`object` as `parent`, c.`object` as `id` from `tiki_comments` as c where (c.`commentDate`>? and c.`commentDate`<?) and c.`objectType` = ?'; break;
			case 'wiki page': $query = 'select `lastModif` as `start`, `user`, `object` as `id`, `action` from `tiki_actionlog` where (`lastModif`>? and `lastModif`<?) and `objectType`=?'; break;

		}
		if ( $query != '' ) {
			if ( is_array($bindvars) && ($nb_vars = substr_count($query,'?')) > 0 ) {
				return $this->query($query, array_slice($bindvars,0,$nb_vars));
			} else return $this->query($query);
		}
	}

	function get_object_url($type, $bindvars = null) {
		switch ( $type ) {
			case 'art': $url = 'tiki-read_article.php?articleId=%s'; break;
			case 'blog': $url = 'tiki-view_blog.php?blogId=%s'; break;
			case 'chart': $url = 'tiki-view_chart.php?chartId=%s'; break;
			case 'dir': $url = 'tiki-directory_redirect.php?siteId=%s'; break;
			case 'faq': $url = 'tiki-view_faq.php?faqId=%s'; break;
			case 'fgal': $url = 'tiki-list_file_gallery.php?galleryId=%s'; break;
			case 'forum': $url = 'tiki-view_forum_thread.php?forumId=%s&amp;comments_parentId=%s#%s'; break;
			case 'gal': $url = 'tiki-browse_image.php?imageId=%s&amp;galleryId=%s'; break;
			case 'nl': $url = 'tiki-newsletters.php?nlId=%s'; break;
			case 'quiz': $url = 'tiki-take_quiz.php?quizId=%s'; break;
			case 'surv': $url = 'tiki-take_survey.php?surveyId=%s'; break;
			case 'track': $url = 'tiki-view_tracker_item.php?itemId=%s&amp;trackerId=%s&amp;offset=0&amp;sort_mode=created_desc'; break;
			case 'wiki comment': $url = 'tiki-index.php?page=%s&amp;comzone=show#comments'; break;
			case 'wiki page': $url = 'tiki-index.php?page=%s'; break;
		}
		if ( $url != '' ) {
			if ( is_array($bindvars) && ($nb_vars = substr_count($url,'%s')) > 0 ) {
				return vsprintf($url, array_map('urlencode',array_slice($bindvars,0,$nb_vars)));
			} else return $url;
		}
	}

	function getTikiItems($with_infos = true) {
		global $prefs;
		global $tiki_p_view, $tiki_p_view_image_gallery, $tiki_p_read_article, $tiki_p_read_blog, $tiki_p_forum_read, $tiki_p_view_directory, $tiki_p_view_file_gallery, $tiki_p_view_faqs, $tiki_p_take_quiz, $tiki_p_view_trackers, $tiki_p_take_survey, $tiki_p_subscribe_newsletters, $tiki_p_view_chart;

		$return = array(
			"wiki" => array( "label" => tra("Wiki"), "feature" => ''.$prefs['feature_wiki'], "right" => "$tiki_p_view"),
			"gal" => array( "label" => tra("Image Gallery"), "feature" => ''.$prefs['feature_galleries'], "right" => "$tiki_p_view_image_gallery"),
			"art" => array( "label" => tra("Articles"), "feature" => ''.$prefs['feature_articles'], "right" => "$tiki_p_read_article"),
			"blog" => array( "label" => tra("Blogs"), "feature" => ''.$prefs['feature_blogs'], "right" => "$tiki_p_read_blog"),
			"forum" => array( "label" => tra("Forums"), "feature" => ''.$prefs['feature_forums'], "right" => "$tiki_p_forum_read"),
			"dir" => array( "label" => tra("Directory"), "feature" => ''.$prefs['feature_directory'], "right" => "$tiki_p_view_directory"),
			"fgal" => array( "label" => tra("File Gallery"), "feature" => ''.$prefs['feature_file_galleries'], "right" => "$tiki_p_view_file_gallery"),
			"faq" => array( "label" => tra("FAQs"), "feature" => ''.$prefs['feature_faqs'], "right" => $tiki_p_view_faqs),
			"quiz" => array( "label" => tra("Quizzes"), "feature" => ''.$prefs['feature_quizzes'], "right" => $tiki_p_take_quiz),
			"track" => array( "label" => tra("Trackers"), "feature" => ''.$prefs['feature_trackers'], "right" => "$tiki_p_view_trackers"),
			"surv" => array( "label" => tra("Survey"), "feature" => ''.$prefs['feature_surveys'], "right" => "$tiki_p_take_survey"),
			"nl" => array( "label" => tra("Newsletter"), "feature" => ''.$prefs['feature_newsletters'], "right" => "$tiki_p_subscribe_newsletters"),
			"chart" => array( "label" => tra("Charts"), "feature" => ''.$prefs['feature_charts'], "right" => "$tiki_p_view_chart")
		);
		return ( $with_infos ? $return : array_keys($return) );
	}

	function getCalendar($tikiItems, &$viewstart, &$viewend, $group_by = '') {
		global $user, $prefs, $smarty;

		// Global vars used by tiki-calendar_setup.php (this has to be changed)
		global $tikilib, $calendarViewMode, $request_day, $request_month, $request_year, $dayend, $myurl;
		include('tiki-calendar_setup.php');

		$listtikievents = $this->list_tiki_items($tikiItems, $user, $viewstart, $viewend, 0, 50, 'name_desc', '');
	
		$mloop = TikiLib::date_format('%m', $viewstart);
		$dloop = TikiLib::date_format('%d', $viewstart);
		$yloop = TikiLib::date_format('%Y', $viewstart);
	
		// note that number of weeks starts at ZERO (i.e., zero = 1 week to display).
		for ($i = 0; $i <= $numberofweeks; $i++) {
			$wee = TikiLib::date_format('%U', $viewstart + ($i * weekInSeconds) + $d);
			$weeks[] = $wee;
	
			// $startOfWeek is a unix timestamp
			$startOfWeek = $viewstart + $i * weekInSeconds;
	
			foreach ( $weekdays as $w ) {
				$leday = array();
				if ( $group_by == 'day' ) {
					$key = 0;
				}
				if ( $calendarViewMode == 'day' ) {
					$dday = $daystart;
				} else {
					$dday = TikiLib::make_time(0,0,0, $mloop, $dloop++, $yloop);
				}
				$cell[$i][$w]['day'] = $dday;
	
				if ( $calendarViewMode == 'day' or ( $dday >= $daystart && $dday <= $dayend ) ) {
					$cell[$i][$w]['focus'] = true;
				} else {
					$cell[$i][$w]['focus'] = false;
				}
				if ( isset($listtikievents["$dday"]) ) {
					$e = -1;
	
					foreach ( $listtikievents["$dday"] as $lte ) {
						$lte['desc_name'] = $lte['name'];
						if ( $calendarGroupByItem != 'n' ) {
							if ( $group_by != 'day' ) $key = $lte['id'].'|'.$lte['type'];
							if ( ! isset($leday[$key]) ) {
								$leday[$key] = $lte;
								if ( $group_by == 'day' ) {
									$leday[$key]['description'] = array($lte['where'] => array($lte['name']));
									$leday[$key]['head'] = TikiLib::date_format($prefs['short_date_format'], $cell[$i][$w]['day']);
								} else {
									$leday[$key]['description'] = ' - <b>'.$lte['when'].'</b> : '.tra($lte['action']).' '.$lte['description'];
									$leday[$key]['head'] = $lte['name'].', <i>'.tra('in').' '.$lte['where'].'</i>';
								}
								$leday[$key]['desc_name'] = '';
							} else {
								$leday_item =& $leday[$key];
								$leday_item['user'] .= ', '.$lte['user'];
	
								if ( ! is_integer($leday_item['action']) ) {
									$leday_item['action'] = 1;
								}
								$leday_item['action']++;
	
								if ( $group_by == 'day' ) {
									$leday_item['name'] .= '<br />'.$lte['name'];
									$leday_item['desc_name'] = $leday_item['action'].' '.tra('Actions').' '.tra('in').': ';
									$leday_item['description'][$lte['where']][] = $lte['name'];
								} else {
									$leday_item['name'] = $lte['name'].' (x<b>'.$leday_item['action'].'</b>)';
									$leday_item['desc_name'] = $leday_item['action'].' '.tra('Actions');
									if ( $lte['show_description'] == 'y' && ! empty($lte['description']) ) {
										$leday_item['description'] .= ",\n<br /> - <b>".$lte['when'].'</b> : '.tra($lte['action']).' '.$lte['description'];
										$leday_item['show_description'] = 'y';
									}
								}
							}
						} else {
							$e++;
							$key = "{$lte['time']}$e";
							$leday[$key] = $lte;
							$lte['desc_name'] .= tra($lte['action']);
						}
					}
	
					foreach ( $leday as $key => $lte ) {
						if ( $group_by == 'day' ) {
							$desc = '';
							foreach ( $lte['description'] as $desc_where => $desc_items ) {
								$desc_items = array_unique($desc_items);
								foreach ( $desc_items as $desc_item ) {
									if ( $desc != '' ) $desc .= '<br />';
									$desc .= '- '.$desc_item.' <i>['.$desc_where.']</i>';
								}
							}
							$lte['description'] = $desc;
						}
	
						$smarty->assign_by_ref('cellhead', $lte["head"]);
						$smarty->assign_by_ref('cellprio', $lte["prio"]);
						$smarty->assign_by_ref('cellcalname', $lte["calname"]);
						$smarty->assign('celllocation', "");
						$smarty->assign('cellcategory', "");
						$smarty->assign_by_ref('cellname', $lte["desc_name"]);
						$smarty->assign('cellid', "");
						$smarty->assign_by_ref('celldescription', $lte["description"]);
						$smarty->assign('show_description', $lte["show_description"]);
	
						if ( ! isset($leday[$key]["over"]) ) {
							$leday[$key]["over"] = '';
						} else {
							$leday[$key]["over"] .= "<br />\n";
						}
						$leday[$key]["over"] .= $smarty->fetch("tiki-calendar_box.tpl");
					}
				}
	
				if ( is_array($leday) ) {
					ksort ($leday);
					$cell[$i][$w]['items'] = array_values($leday);
				}
			}
		}

		if ( $_SESSION['CalendarViewList'] == 'list' ) {
			if ( is_array($listtikievents) ) {
				foreach ( $listtikievents as $le ) {
					if ( is_array($le) ) {
						foreach ( $le as $e ) {
							$listevents[] = $e;
						}
					}
				}
			}
		}

		return array(
			'cell' => $cell,
			'listevents' => $listevents,
			'weeks' => $weeks,
			'weekdays' => $weekdays,
			'daysnames' => $daysnames,
			'trunc' => $trunc
		);
	}

}
global $dbTiki;
$tikicalendarlib = new TikiCalendarLib($dbTiki);
define('weekInSeconds', 604800);

?>
