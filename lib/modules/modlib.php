<?php
// (c) Copyright 2002-2010 by authors of the Tiki Wiki/CMS/Groupware Project
// 
// All Rights Reserved. See copyright.txt for details and a complete list of authors.
// Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See license.txt for details.
// $Id$

//this script may only be included - so its better to die if called directly.
if (strpos($_SERVER["SCRIPT_NAME"],basename(__FILE__)) !== false) {
  header("location: index.php");
  exit;
}

global $usermoduleslib; include_once('lib/usermodules/usermoduleslib.php');

class ModLib extends TikiLib
{

	public $pref_errors = array();
	
	function replace_user_module($name, $title, $data, $parse=NULL) {
		if ((!empty($name)) && (!empty($data))) {
			$query = "delete from `tiki_user_modules` where `name`=?";
			$result = $this->query($query,array($name),-1,-1,false);
			$query = "insert into `tiki_user_modules`(`name`,`title`,`data`, `parse`) values(?,?,?,?)";

			$result = $this->query($query,array($name,$title,$data,$parse));

			global $cachelib; require_once("lib/cache/cachelib.php");
			$cachelib->invalidate("user_modules_$name");

			return true;
		}
	}

	function assign_module($moduleId=0, $name, $title, $position, $order, $cache_time = 0, $rows = 10, $groups = null, $params = null,$type = null) {
		//check for valid values
		$cache_time = is_numeric($cache_time) ? $cache_time : 0;
		$rows = is_numeric($rows) ? $rows : 10;

		if( is_array( $params ) ) {
			$params = $this->serializeParameters( $name, $params );
		}

		if ($moduleId) {
			$query = "update `tiki_modules` set `name`=?,`title`=?,`position`=?,`ord`=?,`cache_time`=?,`rows`=?,`groups`=?,`params`=?,`type`=? where `moduleId`=?";
			$result = $this->query($query,array($name,$title,$position,(int) $order,(int) $cache_time,(int) $rows,$groups,$params,$type, $moduleId));
		} else {
			$query = "delete from `tiki_modules` where `name`=? and `position`=? and `ord`=? and `params`=?";
			$this->query($query, array($name, $position, (int)$order, $params));
			$query = "insert into `tiki_modules`(`name`,`title`,`position`,`ord`,`cache_time`,`rows`,`groups`,`params`,`type`) values(?,?,?,?,?,?,?,?,?)";
			$result = $this->query($query,array($name,$title,$position,(int) $order,(int) $cache_time,(int) $rows,$groups,$params,$type));
			if ($type == "D" || $type == "P") {
				$query = 'select `moduleId` from `tiki_modules` where `name`=? and `title`=? and `position`=? and `ord`=? and `cache_time`=? and `rows`=? and `groups`=? and `params`=? and `type`=?';
				$moduleId = $this->getOne($query, array($name,$title,$position,(int) $order,(int) $cache_time,(int) $rows,$groups,$params,$type));
			}
		}
		if ($type == "D" || $type == "P") {
			global $usermoduleslib;
			$usermoduleslib->add_module_users($moduleId, $name,$title,$position,$order,$cache_time,$rows,$groups,$params,$type);
		}
		return true;
	}

	/* Returns the requested module assignation. A module assignation is represented by an array similar to a tiki_modules record. The groups field is unserialized in the module_groups key, a spaces-separated list of groups. */
	function get_assigned_module($moduleId) {
		$query = "select * from `tiki_modules` where `moduleId`=?";
		$result = $this->query($query,array($moduleId));
		$res = $result->fetchRow();

		if ($res["groups"]) {
			$grps = unserialize($res["groups"]);

			$res["module_groups"] = '';

			foreach ($grps as $grp) {
				$res["module_groups"] .= " $grp ";
			}
		}

		return $res;
	}

	function unassign_module($moduleId) {
		$query = "delete from `tiki_modules` where `moduleId`=?";
		$result = $this->query($query,array($moduleId));
		$query = "delete from `tiki_user_assigned_modules` where `moduleId`=?";
		$result = $this->query($query,array($moduleId));
		return true;
	}

	function get_rows($name) {
		$query = "select `rows` from `tiki_modules` where `name`=?";

		$rows = $this->getOne($query,array($name));

		if ($rows == 0)
			$rows = 10;

		return $rows;
	}

	function module_up($moduleId) {
		$query = "update `tiki_modules` set `ord`=`ord`-1 where `moduleId`=?";
		$result = $this->query($query,array($moduleId));
		return true;
	}

	function module_down($moduleId) {
		$query = "update `tiki_modules` set `ord`=`ord`+1 where `moduleId`=?";
		$result = $this->query($query,array($moduleId));
		return true;
	}
	
	function module_left($moduleId) {
		$query = "update `tiki_modules` set `position`='l' where `moduleId`=?";
		$result = $this->query($query,array($moduleId));
		return true;
	}
	
	function module_right($moduleId) {
		$query = "update `tiki_modules` set `position`='r' where `moduleId`=?";
		$result = $this->query($query,array($moduleId));
		return true;
	}

	function get_all_modules() {
		$user_modules = $this->list_user_modules();

		$all_modules = array();

		foreach ($user_modules["data"] as $um) {
			$all_modules[] = $um["name"];
		}

		// Now add all the system modules
		$h = opendir("templates/modules");

		while (($file = readdir($h)) !== false) {
			if (substr($file, 0, 4) == 'mod-' && preg_match ("/\.tpl$/", $file)) {
				if (!strstr($file, "nocache")) {
					$name = substr($file, 4, strlen($file) - 8);

					$all_modules[] = $name;
				}
			}
		}

		closedir ($h);
		return $all_modules;
	}

	function remove_user_module($name) {

		$this->unassign_module($name);
		$query = " delete from `tiki_user_modules` where `name`=?";
		$result = $this->query($query,array($name));

		global $cachelib; require_once("lib/cache/cachelib.php");
		$cachelib->invalidate('user_modules');

		return true;
	}

	function list_user_modules($sort_mode='name_asc') {
		$query = "select * from `tiki_user_modules` order by ".$this->convertSortMode($sort_mode);

		$result = $this->query($query,array());
		$query_cant = "select count(*) from `tiki_user_modules`";
		$cant = $this->getOne($query_cant,array());
		$ret = array();

		while ($res = $result->fetchRow()) {
			$ret[] = $res;
		}

		$retval = array();
		$retval["data"] = $ret;
		$retval["cant"] = $cant;
		return $retval;
	}

	function clear_cache() {
		global $tikidomain;
		$dircache = "modules/cache";
		if ($tikidomain) { $dircache.= "/$tikidomain"; }
		$h = opendir($dircache);
		while (($file = readdir($h)) !== false) {
			if (substr($file, 0, 3) == 'mod') {
				$file = "$dircache/$file";
				unlink ($file);
			}
		}
		closedir($h);
	}
	/* @param module_info = info of a module
	 * @param user_groups = list of groups of a user
	 * @param user = the user
	 */
	function check_groups($module_info, $user, $user_groups) {
		global $prefs, $tiki_p_admin;
		if( empty( $user ) ) {
			$user_groups = array( 'Anonymous' );
		}
		$pass = 'y';
		if ($tiki_p_admin == 'y' && $prefs['modhideanonadmin'] == 'y' && $module_info['groups'] == serialize(array('Anonymous'))) {
			$pass = 'n';
		} elseif ($tiki_p_admin != 'y' && $prefs['modallgroups'] != 'y') {
			if ($module_info['groups']) {
				$module_groups = unserialize($module_info['groups']);
			} else {
				$module_groups = array();
			}
			$pass = 'n';
			if ($prefs['modseparateanon'] !== 'y') {
				foreach ($module_groups as $mod_group) {
					if (in_array($mod_group, $user_groups)) {
						$pass = 'y';
						break; 
					}
				}
			} else {
				if(!$user) { 
					if (in_array('Anonymous', $module_groups)) {
						$pass = 'y';
					}
				} else { 
					foreach ($module_groups as $mod_group) {
						if ($mod_group === 'Anonymous') { 
							continue; 
						}
						if (in_array($mod_group, $user_groups)) {
							$pass = 'y';
							break;
						}
					}
				}
			}
		}
		return $pass;
	}

	function get_modules_for_user( $user, array $module_zones ) {
		$list = $this->get_raw_module_list_for_user( $user, $module_zones );

		foreach( $list as & $partial ) {
			$partial = array_map( array( $this, 'augment_module_parameters' ), $partial );
			$partial = array_values( array_filter( $partial, array( $this, 'filter_active_module' ) ) );
		}

		return $list;
	}

	function augment_module_parameters( $module ) {
		global $prefs;

		TikiLib::parse_str( $module['params'], $module_params );
		$default_params = array(
			'decorations' => 'y',
			'overflow' => 'n',
			'nobox' => 'n',
			'notitle' => 'n',
			'error' => '',
			'flip' => ( $prefs['user_flip_modules'] == 'module' ) ? 'n' : $prefs['user_flip_modules'],
		);

		if (!is_array($module_params)) {
			$module_params = array();
		}

		$module_params = array_merge( $default_params, $module_params );

		$module_params['module_position'] = $module['position'];
		$module_params['module_ord'] = $module['ord'];

		if( isset( $module_params['section'] ) && $module_params['section'] == 'wiki' ) {
			$module_params['section'] = 'wiki page';
		}

		$module['params'] = $module_params;

		return $module;
	}

	function filter_active_module( $module ) {
		global $section, $page, $prefs, $user, $user_groups, $tikilib;

		// Validate preferences
		$module_info = $this->get_module_info( $module['name'] );
		foreach( $module_info['prefs'] as $p ) {
			if( $prefs[$p] != 'y' ) {
				$this->add_pref_error($module['name'], $p);
				return false;
			}
		}

		$params = $module['params'];

		if( $prefs['feature_perspective'] == 'y' ) {
			global $perspectivelib; require_once 'lib/perspectivelib.php';
			if( isset( $params['perspective'] ) && ! in_array( $perspectivelib->get_current_perspective( $prefs ), (array) $params['perspective'] ) ) {
				return false;
			}
		}

		if( isset( $params["lang"] ) && ! in_array( $prefs['language'], (array) $params["lang"]) ) {
			return false;
		}

		if( isset( $params['section'] ) && ( !isset($section)  || !in_array($section, (array) $params['section']))) {
			return false;
		}

		if( isset( $params['nopage'] ) && isset( $page ) && isset( $section ) && $section == 'wiki page' ) {
			if( in_array( $page, (array) $params['nopage'] ) ) {
				return false;
			}
		}

		if( isset( $params['page'] ) ) {
			if( ! isset($section) || $section != 'wiki page' || ! isset( $page ) ) { // must be in a page
				return false;
			} elseif( ! in_array( $page, (array) $params['page'] ) ) {
				return false;
			}
		}

		if( isset( $params['theme'] ) ) {
			global $tc_theme;

			if( $params['theme']{0} != '!' ) { // usual behavior
				if( isset($tc_theme) && $tc_theme > '' && $params['theme'] != $tc_theme ) {
					return false;
				} elseif( $params['theme'] != $prefs['style'] && ( !isset($tc_theme) || $tc_theme == '' ) ) {
					return false;
				}
			} else { // negation behavior
				$excluded_theme = substr($params['theme'],1);
				if( isset($tc_theme) && $tc_theme > '' && $excluded_theme == $tc_theme ) {
					return false;
				} elseif( $excluded_theme == $prefs['style'] && ( ! isset( $tc_theme ) || $tc_theme == '' ) ) {
					return false;
				}
			}
		}

		if( 'y' != $this->check_groups( $module, $user, $user_groups ) ) {
			return false;
		}

		if( isset( $params['creator'] ) && $section == 'wiki page' && isset( $page ) ) {
			if( ! $page_info = $tikilib->get_page_info( $page ) ) {
				return false;
			} elseif( $params['creator'] == 'y' && $page_info['creator'] != $user) {
				return false;
			} elseif( $params['creator'] == 'n' && $page_info['creator'] == $user ) {
				return false;
			}
		}

		if( isset( $params['contributor'] ) && $section == 'wiki page' && isset( $page ) ) {
			global $wikilib; include_once('lib/wiki/wikilib.php');
			if( ! $page_info = $tikilib->get_page_info( $page ) ) {
				return false;
			} else {
				$contributors = $wikilib->get_contributors($page);
				$contributors[] = $page_info['creator'];
				$in = in_array($user, $contributors);

				if( $params['contributor'] == 'y' && ! $in ) {
					return false;
				} elseif( $params['contributor'] == 'n' && $in ) {
					return false;
				}
			}
		}
		
		if ($module['name'] == 'login_box' && basename($_SERVER['SCRIPT_NAME']) == 'tiki-login_scr.php') {
			return false;
		}

		return true;
	}

	private function get_raw_module_list_for_user( $user, array $module_zones ) {
		global $prefs, $tiki_p_configure_modules, $usermoduleslib;

		$out = array_fill_keys( array_values($module_zones), array() );

		if( $prefs['user_assigned_modules'] == 'y' 
			&& $tiki_p_configure_modules == 'y' 
			&& $user 
			&& $usermoduleslib->user_has_assigned_modules($user) ) {

			foreach( $module_zones as $zone => $zone_name ) {
				$out[$zone_name] = $usermoduleslib->get_assigned_modules_user( $user, $zone );
			}
		} else {
			$modules_by_position = $this->get_assigned_modules( null, 'y' );
			foreach( $module_zones as $zone => $zone_name ) {
				if( isset($modules_by_position[$zone]) ) {
					$out[$zone_name] = $modules_by_position[$zone];
				}
			}
		}

		return $out;
	}

	function get_module_info( $module ) {
		if( is_array( $module ) ) {
			$moduleName = $module['name'];
		} else {
			$moduleName = $module;
		}

		$phpfuncfile = 'modules/mod-func-' . $moduleName . '.php';
		$info_func = "module_{$moduleName}_info";
		$info = array();

		if( file_exists( $phpfuncfile ) ) {
			include_once $phpfuncfile;

			if( function_exists( $info_func ) ) {
				$info = $info_func();
			}

			$info['type'] = 'function';
		}

		$defaults = array(
			'name' => $moduleName,
			'description' => tra('Description not available'),
			'type' => 'include',
			'cachekeygen' => array( $this, 'createDefaultCacheKey' ),
			'prefs' => array(),
			'params' => array(),
			'common_params' => array()
		);

		$info = array_merge( $defaults, $info );

		$info['params'] = array_merge( $info['params'], array(
			'title' => array(
				'name' => tra('Module Title'),
				'description' => tra('Title to display at the top of the box.'),
				'filter' => 'striptags',
			),
			'nobox' => array(
				'name' => tra('No box'),
				'description' => 'y|n '.tra('Show only the content'),
			),
			'decorations' => array(
				'name' => tra('Decorations'),
				'description' => 'y|n '. tra('Show module decorations'),
			),
			'notitle' => array(
				'name' => tra('No title'),
				'description' => 'y|n '.tra('Show module title'),
				'filter' => 'alpha',
			),
			'perspective' => array(
				'name' => tra('Perspective'),
				'description' => tra('Only display the module if in one of the listed perspective IDs. Semi-colon separated.'),
				'separator' => ';',
				'filter' => 'digits',
			),
			'lang' => array(
				'name' => tra('Language'),
				'description' => tra('Module only applicable for the specified languages. Languages are defined as two character language codes. Multiple values can be separated by semi-colons.'),
				'separator' => ';',
				'filter' => 'lang',
			),
			'section' => array(
				'name' => tra('Section'),
				'description' => tra('Module only applicable for the specified sections. Multiple values can be separated by semi-colons.'),
				'separator' => ';',
				'filter' => 'striptags',
			),
			'page' => array(
				'name' => tra('Page filter'),
				'description' => tra('Module only applicable on the specified page names. Multiple values can be separated by semi-colons.'),
				'separator' => ';',
				'filter' => 'pagename',
			),
			'nopage' => array(
				'name' => tra('No Page'),
				'description' => tra('Module not applicable on the specified page names. Multiple values can be separated by semi-colons.'),
				'separator' => ';',
				'filter' => 'pagename',
			),
			'theme' => array(
				'name' => tra('Theme'),
				'description' => tra('Module enabled or disabled depending on the theme file name (e.g. "thenews.css"). Specified themes can be either included or excluded. Theme names prefixed by \"!\" are in the exclusion list. Multiple values can be separated by semi-colons.'),
				'separator' => ';',
				'filter' => 'themename',
			),
			'creator' => array(
				'name' => tra('Creator'),
				'description' => tra('Module only available based on the relationship of the user with the wiki page. Either only creators (y) or only non-creators (n) will see the module.'),
				'filter' => 'alpha',
			),
			'contributor' => array(
				'name' => tra('Contributor'),
				'description' => tra('Module only available based on the relationship of the user with the wiki page. Either only contributors (y) or only non-contributors (n) will see the module.'),
				'filter' => 'alpha',
			),
			'flip' => array(
				'name' => tra('Flip'),
				'description' => tra('Users can shade module.'),
				'filter' => 'alpha',
			),
		) );

		// Parameters common to several modules, but not all
		$common_params = array(
			'nonums' => array(
				'name' => tra('No numbers'),
				'description' => tra('If set to "y", the module will not number list items.'),
			),
			'rows' => array(
				'name' => tra('Rows'),
				'description' => tra('Number of rows, or items, to display.') . ' ' . tra('Default: 10.'),
			)
		);

		if ($info['type'] == 'function')
			foreach($info['common_params'] as $common_param)
				$info['params'][$common_param] = $common_params[$common_param];

		// Parameters are not required, unless specified.
		foreach ($info["params"] as &$param)
			if (!isset($param["required"]))
				$param["required"] = false;

		return $info;
	}

	function createDefaultCacheKey( $mod_reference ) {
		global $prefs;
		return $mod_reference['moduleId'] . '-' . $mod_reference['name'] . '-'. $prefs['language'] . '-' . serialize($mod_reference['params']);
	}

	function execute_module( $mod_reference ) {
		$module_params = $mod_reference['params'];

		if ( empty($mod_reference['rows']) ) {
			$mod_reference['rows'] = 10;
		}
		$module_rows = $mod_reference["rows"];

		$info = $this->get_module_info( $mod_reference );
		$cachefile = $this->get_cache_file( $mod_reference, $info );

		global $smarty, $tikilib, $user;

		if( ! $cachefile || $this->require_cache_build( $mod_reference, $cachefile ) ) {
			if ( $info['type'] == "function") // Use the module name as default module title. This can be overriden later. A module can opt-out of this in favor of a dynamic default title set in the TPL using clear_assign in the main module function. It can also be overwritten in the main module function.
				$smarty->assign('tpl_module_title', tra( $info['name'] ) );

			$smarty->assign('nonums', isset( $module_params['nonums'] ) ? $module_params['nonums'] : "n" );

			if( $info['type'] == 'include' ) {
				$phpfile = 'modules/mod-' . $mod_reference['name'] . '.php';

				if( file_exists( $phpfile ) ) {
					include $phpfile;
				}
			} elseif( $info['type'] == 'function' ) {
				$function = 'module_' . $mod_reference['name'];

				if( function_exists( $function ) ) {
					$function( $mod_reference, $module_params );
				}
			}

			global $prefs;
			$ck = getCookie('mod-'.$mod_reference['name'].$mod_reference['position'].$mod_reference['ord'], 'menu', 'o');
			$smarty->assign('module_display', ($prefs['javascript_enabled'] == 'n' || $ck == 'o'));
			
			$smarty->assign_by_ref('module_rows',$mod_reference['rows']);
			$smarty->assign_by_ref('module_params', $module_params); // module code can unassign this if it wants to hide params
			$smarty->assign('module_ord', $mod_reference['ord']);
			$smarty->assign('module_position', $mod_reference['position']);
			$smarty->assign('moduleId', $mod_reference['moduleId']);
			if( isset( $module_params['title'] ) )
				$smarty->assign('tpl_module_title', tra( $module_params['title'] ) );

			$template = 'modules/mod-' . $mod_reference['name'] . '.tpl';

			if (file_exists('templates/'.$template)) {
				$data = $smarty->fetch($template);
			} else {
				$data = $this->get_user_module_content( $mod_reference['name'] );
			}
			$smarty->clear_assign('module_params'); // ensure params not available outside current module
			$smarty->clear_assign('tpl_module_title');

			if (!empty($cachefile)) {
				file_put_contents( $cachefile, $data );
			}
		} else {
			$data = file_get_contents( $cachefile );
		}

		return $data;
	}

	function get_user_module_content( $name ) {
		global $tikilib, $smarty;

		$smarty->assign('module_type','module');
		$info = $tikilib->get_user_module( $name );
		if (!empty($info)) {
			// test if we have a menu
			if (strpos($info['data'],'{menu ') === 0 and strpos($info['data'],"css=y")) {
				$smarty->assign('module_type','cssmenu');
			}

			if (isset($info['parse']) && $info['parse'] == 'y') {
				$info['data'] = $tikilib->parse_data($info['data']);
				$info['title'] = $tikilib->parse_data($info['title'], array('noparseplugins' => true));
			}

			$smarty->assign('user_title', tra($info['title']));
			$smarty->assign_by_ref('user_data', $info['data']);
			$smarty->assign_by_ref('user_module_name', $info['name']);

			return $smarty->fetch('modules/user_module.tpl');
		}
	}

	function get_cache_file( $mod_reference, $info ) {
		global $tikidomain, $user;
		$nocache = 'templates/modules/mod-' . $mod_reference["name"] . '.tpl.nocache';

		// Uncacheable
		if( ! empty( $user ) || $mod_reference['cache_time'] <= 0 || file_exists( $nocache ) ) {
			return null;
		}

		$cb = $info['cachekeygen'];

		$cachefile = 'modules/cache/';
		if ($tikidomain) {
			$cachefile.= "$tikidomain/";
		}

		$cachefile .= 'mod-' . md5( call_user_func( $cb, $mod_reference ) );

		return $cachefile;
	}

	// Returns whether $cachefile needs to be [re]built
	function require_cache_build( $mod_reference, $cachefile ) {
		global $tikilib;
		return ! file_exists( $cachefile )
			|| ( $tikilib->now - filemtime($cachefile) ) >= $mod_reference['cache_time'];
	}

	function dispatchValues( $input, & $params ) {
		if( is_string( $input ) ) {
			TikiLib::parse_str( $input, $module_params );
		} else {
			$module_params = $input;
		}

		foreach( $params as $name => & $inner ) {
			if( isset( $module_params[$name] ) ) {
				if( isset( $inner['separator'] ) ) {
					$inner['value'] = implode( $inner['separator'], (array) $module_params[$name] );
				} else {
					$inner['value'] = $module_params[$name];
				}
			} else {
				$inner['value'] = null;
			}
		}
	}

	function serializeParameters( $name, $params ) {
		$info = $this->get_module_info( $name );
		$expanded = array();

		foreach( $info['params'] as $name => $def ) {
			if( isset( $def['filter'] ) ) {
				$filter = TikiFilter::get( $def['filter'] );
			} else {
				$filter = null;
			}

			if( isset( $params[$name] ) && ! empty( $params[$name] ) ) {
				if( isset( $def['separator'] ) && strpos($params[$name], $def['separator']) !== false ) {
					$parts = explode( $def['separator'], $params[$name] );
					
					if( $filter ) {
						foreach( $parts as & $single ) {
							$single = $filter->filter( $single );
							$single = trim( $single );
						}
					}
				} else {
					$parts = $params[$name];
					if( $filter ) {
						$parts = $filter->filter( $parts );
					}
				}

				$expanded[$name] = $parts;
			}
		}
		if (empty($expanded)) {
			return '';// http_build_query return NULL or '' depending on system
		}

		return http_build_query( $expanded, '', '&' );
	}
	
	function add_pref_error($module_name, $preference_name) {
		$this->pref_errors[] = array('mod_name' => $module_name, 'pref_name' => $preference_name);
	}
}
$modlib = new ModLib;
