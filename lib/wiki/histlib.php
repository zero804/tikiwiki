<?php

//this script may only be included - so its better to die if called directly.
if (strpos($_SERVER["SCRIPT_NAME"],basename(__FILE__)) !== false) {
  header("location: index.php");
  exit;
}

class HistLib extends TikiLib {
	function HistLib($db) {
		$this->TikiLib($db);
	}

	// Removes a specific version of a page
	function remove_version($page, $version, $comment = '', $historyId = '') {
		global $prefs;
		if ($prefs['feature_contribution'] == 'y') {
			global $contributionlib; include_once('lib/contribution/contributionlib.php');
			if ($historyId == '') {
				$query = 'select `historyId` from `tiki_history` where `pageName`=? and `version`=?';
				$historyId = $this->getOne($query, array($page, $version));
			}
			$contributionlib->remove_history($historyId);
		}
		$query = "delete from `tiki_history` where `pageName`=? and `version`=?";
		$result = $this->query($query,array($page,$version));
		global $logslib; include_once('lib/logs/logslib.php');
		$logslib->add_action("Removed version", $page, 'wiki page', "version=$version");
		//get_strings tra("Removed version $version")
		return true;
	}

	function use_version($page, $version, $comment = '') {
		$this->invalidate_cache($page);
		
		// Store the current page in tiki_history before rolling back
		if (strtolower($page) != 'sandbox') {
			$info = $this->get_page_info($page);
			$old_version = $this->get_page_latest_version($page) + 1;
		    $lastModif = $info["lastModif"];
		    $user = $info["user"];
		    $ip = $info["ip"];
		    $comment = $info["comment"];
		    $data = $info["data"];
		    $description = $info["description"];
			$query = "insert into `tiki_history`(`pageName`, `version`, `lastModif`, `user`, `ip`, `comment`, `data`, `description`)
		    			values(?,?,?,?,?,?,?,?)";
		    $this->query($query,array($page,(int) $old_version,(int) $lastModif,$user,$ip,$comment,$data,$description));
		}
		
		$query = "select * from `tiki_history` where `pageName`=? and `version`=?";
		$result = $this->query($query,array($page,$version));

		if (!$result->numRows())
			return false;

		$res = $result->fetchRow();
		
		global $prefs;
		if ($prefs['feature_wikiapproval'] == 'y') {
			// for approval and staging feature to work properly, one has to use real commit time of rollbacks
			//TODO: make this feature to set rollback time as current time as more general optional feature
			$res["lastModif"] = time();
			$res["comment"] = $res["comment"] . " [" . tra("rollback version ") . $version . "]"; 		
		}
		$query
			= "update `tiki_pages` set `data`=?,`lastModif`=?,`user`=?,`comment`=?,`version`=`version`+1,`ip`=? where `pageName`=?";
		$result = $this->query($query,array($res["data"],$res["lastModif"],$res["user"],$res["comment"],$res["ip"],$page));
		$query = "delete from `tiki_links` where `fromPage` = ?";
		$result = $this->query($query,array($page));
		$this->clear_links($page);
		$pages = $this->get_pages($res["data"]);

		foreach ($pages as $a_page) {
			$this->replace_link($page, $a_page);
		}

		//$query="delete from `tiki_history` where `pageName`='$page' and version='$version'";
		//$result=$this->query($query);
		//
		global $prefs;
		if ($prefs['feature_actionlog'] == 'y') {
			global $logslib; include_once('lib/logs/logslib.php');
			$logslib->add_action("Rollback", $page, 'wiki page', "version=$version");
		}
		//get_strings tra("Changed actual version to $version");
		return true;
	}

	function get_user_versions($user) {
		$query
			= "select `pageName`,`version`, `lastModif`, `user`, `ip`, `comment` from `tiki_history` where `user`=? order by `lastModif` desc";

		$result = $this->query($query,array($user));
		$ret = array();

		while ($res = $result->fetchRow()) {
			$aux = array();

			$aux["pageName"] = $res["pageName"];
			$aux["version"] = $res["version"];
			$aux["lastModif"] = $res["lastModif"];
			$aux["ip"] = $res["ip"];
			$aux["comment"] = $res["comment"];
			$ret[] = $aux;
		}

		return $ret;
	}

	// Returns information about a specific version of a page
	function get_version($page, $version) {

		$query = "select * from `tiki_history` where `pageName`=? and `version`=?";
		$result = $this->query($query,array($page,$version));
		$res = $result->fetchRow();
		return $res;
	}

	// Returns all the versions for this page
	// without the data itself
	function get_page_history($page, $fetchdata=true) {
		global $prefs;

		$query = "select * from `tiki_history` where `pageName`=? order by `version` desc";
		$result = $this->query($query,array($page));
		$ret = array();

		while ($res = $result->fetchRow()) {
			$aux = array();

			$aux["version"] = $res["version"];
			$aux["lastModif"] = $res["lastModif"];
			$aux["user"] = $res["user"];
			$aux["ip"] = $res["ip"];
			if ($fetchdata==true) $aux["data"] = $res["data"];
			$aux["pageName"] = $res["pageName"];
			$aux["description"] = $res["description"];
			$aux["comment"] = $res["comment"];
			//$aux["percent"] = levenshtein($res["data"],$actual);
			if ($prefs['feature_contribution'] == 'y') {
				global $contributionlib; include_once('lib/contribution/contributionlib.php');
				$aux['contributions'] = $contributionlib->get_assigned_contributions($res['historyId'], 'history');
				global $logslib; include_once('lib/logs/logslib.php');
				$aux['contributors'] = $logslib->get_wiki_contributors($aux);
			}
			$ret[] = $aux;
		}

		return $ret;
	}

	// Returns one version of the page from the history
	// without the data itself (version = 0 now returns data from current version)
	function get_page_from_history($page,$version,$fetchdata=false) {

		if ($fetchdata==true) {
			if ($version > 0)
				$query = "select `pageName`, `description`, `version`, `lastModif`, `user`, `ip`, `data`, `comment` from `tiki_history` where `pageName`=? and `version`=?";				
			else
				$query = "select `pageName`, `description`, `version`, `lastModif`, `user`, `ip`, `data`, `comment` from `tiki_pages` where `pageName`=?";
		} else {
			if ($version > 0)
				$query = "select `pageName`, `description`, `version`, `lastModif`, `user`, `ip`, `comment` from `tiki_history` where `pageName`=? and `version`=?";
			else
				$query = "select `pageName`, `description`, `version`, `lastModif`, `user`, `ip`, `comment` from `tiki_pages` where `pageName`=?";
		}
		if ($version > 0)
			$result = $this->query($query,array($page,$version));
		else
			$result = $this->query($query,array($page));
			
		$ret = array();

		while ($res = $result->fetchRow()) {
			$aux = array();

			$aux["version"] = $res["version"];
			$aux["lastModif"] = $res["lastModif"];
			$aux["user"] = $res["user"];
			$aux["ip"] = $res["ip"];
			if ($fetchdata==true) $aux["data"] = $res["data"];
			$aux["pageName"] = $res["pageName"];
			$aux["description"] = $res["description"];
			$aux["comment"] = $res["comment"];
			//$aux["percent"] = levenshtein($res["data"],$actual);
			$ret[] = $aux;
		}

		return empty($ret)?$ret: $ret[0];
	}
	
	// note that this function returns the latest version in the
	// history db table, which is one less than the current version 
	function get_page_latest_version($page, $sort_mode='version_desc') {

		$query = "select `version` from `tiki_history` where `pageName`=? order by ".$this->convert_sortmode($sort_mode);
		$result = $this->query($query,array($page),1);
		$ret = array();
		
		if ($res = $result->fetchRow()) {
			$ret = $res['version'];
		} else {
			$ret = FALSE;
		}

		return $ret;
	}

	function version_exists($pageName, $version) {

		$query = "select `pageName` from `tiki_history` where `pageName` = ? and `version`=?";
		$result = $this->query($query,array($pageName,$version));
		return $result->numRows();
	}

	// This function get the last changes from pages from the last $days days
	// if days is 0 this gets all the registers
	// function parameters modified by ramiro_v on 11/03/2002
	function get_last_changes($days, $offset = 0, $limit = -1, $sort_mode = 'lastModif_desc', $findwhat = '') {
	        global $user;

		$where = "where (th.`version` != 0 or tp.`version` != 0) ";
		$bindvars = array();
		if ($findwhat) {
			$findstr='%' . $findwhat . '%';
			$where.= " and ta.`object` like ? or ta.`user` like ? or ta.`comment` like ?";
			$bindvars = array($findstr,$findstr,$findstr);
		}

		if ($days) {
			$toTime = $this->make_time(23, 59, 59, $this->date_format("%m"), $this->date_format("%d"), $this->date_format("%Y"));
			$fromTime = $toTime - (24 * 60 * 60 * $days);
			$where .= " and ta.`lastModif`>=? and ta.`lastModif`<=? ";
			$bindvars[] = $fromTime;
			$bindvars[] = $toTime;
		}

		$query = "select ta.`action`, ta.`lastModif`, ta.`user`, ta.`ip`, ta.`object`,th.`comment`, th.`version` as version, tp.`version` as versionlast from `tiki_actionlog` ta 
			left join `tiki_history` th on  ta.`object`=th.`pageName` and ta.`lastModif`=th.`lastModif` and ta.`objectType`='wiki page'
			left join `tiki_pages` tp on ta.`object`=tp.`pageName` and ta.`lastModif`=tp.`lastModif` " . $where . " order by ta.".$this->convert_sortmode($sort_mode);
		$query_cant = "select count(*) from `tiki_actionlog` ta 
			left join `tiki_history` th on  ta.`object`=th.`pageName` and ta.`lastModif`=th.`lastModif` 
			left join `tiki_pages` tp on ta.`object`=tp.`pageName` and ta.`lastModif`=tp.`lastModif` " . $where;

		$result = $this->query($query,$bindvars,$limit,$offset);
		$cant = $this->getOne($query_cant,$bindvars);
		$ret = array();
		$retval = array();
		while ($res = $result->fetchRow()) {
		   //WYSIWYCA hack: the $limit will not be respected
		   if($this->user_has_perm_on_object($user,$res['object'],'wiki page','tiki_p_view')) {
			$res['pageName'] = $res['object'];
			$ret[] = $res;
		   }
		}
		$retval["data"] = $ret;
		$retval["cant"] = $cant;
		return $retval;
	}
	function get_nb_history($page) {
		$query_cant = "select count(*) from `tiki_history` where `pageName` = ?";
		$cant = $this->getOne($query_cant, array($page));
		return $cant;
	}
	
	// This function gets the version number of the version before or after the time specified
	// (note that current version is not included in search)
	function get_version_by_time($page, $unixtimestamp, $before_or_after = 'before', $include_minor = true) {
		$query = "select `version`, `version_minor`, `lastModif` from `tiki_history` where `pageName`=? order by `version` desc";
		$result = $this->query($query,array($page));
		$ret = array();
		$version = 0;
		while ($res = $result->fetchRow()) {
			$aux = array();
			$aux["version"] = $res["version"];
			$aux["version_minor"] = $res["version_minor"];
			$aux["lastModif"] = $res["lastModif"];
			$ret[] = $aux;
		}
		foreach ($ret as $ver) {
			if ($ver["lastModif"] <= $unixtimestamp && ($include_minor || $ver["version_minor"] == 0)) {
				if ($before_or_after == 'before') { 
					$version = (int) $ver["version"];
					break;
				} elseif ($before_or_after == 'after') {
					break;
				}
			}
			if ($before_or_after == 'after' && ($include_minor || $ver["version_minor"] == 0)) {
				$version = (int) $ver["version"];				
			}		
		}
		return max(0, $version);		
	}
}

global $dbTiki;
$histlib = new HistLib($dbTiki);

function histlib_helper_setup_diff( $page, $oldver, $newver )
{
	global $smarty, $histlib, $tikilib;
	
	$info = $tikilib->get_page_info( $page );

	if ($oldver == 0 || $oldver == $info["version"]) {
		$old = & $info;
		$smarty->assign_by_ref('old', $info);
	} else {
		// fetch the required page from history, including its content
		while( $oldver > 0 && ! ($exists = $histlib->version_exists($page, $oldver) ) )
			--$oldver;

		if ( $exists ) {
			$old = $histlib->get_page_from_history($page,$oldver,true);
			$smarty->assign_by_ref('old', $old);
		}
	}
	if ($newver == 0 || $newver >= $info["version"]) {
		$new =& $info;
		$smarty->assign_by_ref('new', $info);
	} else {
		// fetch the required page from history, including its content
		while( $newver > 0 && ! ($exists = $histlib->version_exists($page, $newver) ) )
			--$newver;

		if ( $exists ) {
			$new = $histlib->get_page_from_history($page,$newver,true);
			$smarty->assign_by_ref('new', $new);
		}
	}

	if (!isset($_REQUEST["diff_style"]) || $_REQUEST["diff_style"] == "old") {
		$_REQUEST["diff_style"] = 'unidiff';
	}
	$smarty->assign('diff_style', $_REQUEST["diff_style"]);
	if ($_REQUEST["diff_style"] == "sideview") {
		$old["data"] = $tikilib->parse_data($old["data"]);
		$new["data"] = $tikilib->parse_data($new["data"]);
	} else {
		require_once('lib/diff/difflib.php');
		if ($info['is_html'] == 1 and $_REQUEST["diff_style"] != "htmldiff") {
			$search[] = "~</(table|td|th|div|p)>~";
			$replace[] = "\n";
			$search[] = "~<(hr|br) />~";
			$replace[] = "\n";
			$old['data'] = strip_tags(preg_replace($search,$replace,$old['data']),'<h1><h2><h3><h4><b><i><u><span>');
			$new['data'] = strip_tags(preg_replace($search,$replace,$new['data']),'<h1><h2><h3><h4><b><i><u><span>');
		}
		if ($_REQUEST["diff_style"] == "htmldiff") {
			$old["data"] = $tikilib->parse_data($old["data"],$info['is_html'] == 1 );
			$new["data"] = $tikilib->parse_data($new["data"],$info['is_html'] == 1 );
		}
		$html = diff2($old["data"], $new["data"], $_REQUEST["diff_style"]);
		$smarty->assign_by_ref('diffdata', $html);
	}
}

?>
