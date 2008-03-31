<?php

// $Header: /cvsroot/tikiwiki/tiki/lib/mypage/smarty_mypage.php,v 1.2 2007-10-12 07:55:42 nyloth Exp $

// Copyright (c) 2002-2007, Luis Argerich, Garland Foster, Eduardo Polidor, et. al.
// All Rights Reserved. See copyright.txt for details and a complete list of authors.
// Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See license.txt for details.

// uncomment and adapt the following line if you use smarty external to tiki
// define('SMARTY_DIR', 'lib/smarty/');

require_once ( 'lib/smarty/libs/Smarty.class.php');

class Smarty_MyPage extends Smarty {
	
	function Smarty_MyPage($template_dir='templates') {
	    global $tikidomain;

		$tikidomain_path= $tikidomain ? $tikidomain.'/' : '';

		$this->template_dir = $template_dir;
		$this->compile_dir = "templates_c/$tikidomain_path";
		$this->config_dir = 'configs/';
		$this->cache_dir = "templates_c/$tikidomain_path";
		$this->caching = 0;
		$this->assign('app_name', 'Tikiwiki');
		$this->plugins_dir = array(	// the directory order must be like this to overload a plugin
			dirname(dirname(SMARTY_DIR)).'/smarty_tiki',
			SMARTY_DIR.'plugins'
		);
		// In general, it's better that use_sub_dirs = false
		// If ever you are on a very large/complex/multilingual site and your
		// templates_c directory is > 10 000 files, (you can check at tiki-admin_system.php)
		// you can change to true and maybe you will get better performance.
		// http://smarty.php.net/manual/en/variable.use.sub.dirs.php

		$this->use_sub_dirs = false;

		$this->load_filter('pre', 'tr');

	}

	function _smarty_include($params) {
		global $style_base, $tikidomain;

		if (/*isset($style_base)*/0) {
			if ($tikidomain and file_exists("templates/$tikidomain/styles/$style_base/".$params['smarty_include_tpl_file'])) {
				$params['smarty_include_tpl_file'] = "$tikidomain/styles/$style_base/".$params['smarty_include_tpl_file'];
			} elseif ($tikidomain and file_exists("templates/$tikidomain/".$params['smarty_include_tpl_file'])) {
				$params['smarty_include_tpl_file'] = "$tikidomain/".$params['smarty_include_tpl_file'];
			} elseif (file_exists("templates/styles/$style_base/".$params['smarty_include_tpl_file'])) {
				$params['smarty_include_tpl_file'] = "styles/$style_base/".$params['smarty_include_tpl_file'];
			}
		}
		return parent::_smarty_include($params);
	}

	function fetch($_smarty_tpl_file, $_smarty_cache_id = null, $_smarty_compile_id = null, $_smarty_display = false) {
		global $prefs, $style_base, $tikidomain;

		if (/*isset($style_base)*/0) {
			if ($tikidomain and file_exists("templates/$tikidomain/styles/$style_base/$_smarty_tpl_file")) {
				$_smarty_tpl_file = "$tikidomain/styles/$style_base/$_smarty_tpl_file";
			} elseif ($tikidomain and file_exists("templates/$tikidomain/$_smarty_tpl_file")) {
				$_smarty_tpl_file = "$tikidomain/$_smarty_tpl_file";
			} elseif (file_exists("templates/styles/$style_base/$_smarty_tpl_file")) {
				$_smarty_tpl_file = "styles/$style_base/$_smarty_tpl_file";
			}
		}
		$_smarty_cache_id = $prefs['language'] . $this->template_dir . $_smarty_cache_id;
		$_smarty_compile_id = $prefs['language'] . $this->template_dir . $_smarty_compile_id;
		return parent::fetch($_smarty_tpl_file, $_smarty_cache_id, $_smarty_compile_id, $_smarty_display);
	}
	/* fetch in a specific language  without theme consideration */
	function fetchLang($lg, $_smarty_tpl_file, $_smarty_cache_id = null, $_smarty_compile_id = null, $_smarty_display = false)  {
		global $prefs, $lang, $style_base, $tikidomain;
		
		if (/*isset($prefs['style']) && isset($style_base)*/0) {
			if ($tikidomain and file_exists("templates/$tikidomain/styles/$style_base/$_smarty_tpl_file")) {
				$_smarty_tpl_file = "$tikidomain/styles/$style_base/$_smarty_tpl_file";
			} elseif ($tikidomain and file_exists("templates/$tikidomain/$_smarty_tpl_file")) {
				$_smarty_tpl_file = "$tikidomain/$_smarty_tpl_file";
			} elseif (file_exists("templates/styles/$style_base/$_smarty_tpl_file")) {
				$_smarty_tpl_file = "styles/$style_base/$_smarty_tpl_file";
			}
		}

		$_smarty_cache_id = $lg . $this->template_dir . $_smarty_cache_id;
		$_smarty_compile_id = $lg . $this->template_dir . $_smarty_compile_id;
		$this->_compile_id = $lg . $_smarty_compile_id; // not pretty but I don't know how to change id for get_compile_path
		$isCompiled = $this->_is_compiled($_smarty_tpl_file, $this->_get_compile_path($_smarty_tpl_file));
		if (!$isCompiled) {
			$lgSave = $prefs['language'];
			$prefs['language'] = $lg;
			include('lang/'.$prefs['language'].'/language.php');
				// the language file needs to be included again:
				// the file could have been included before: prefilter.tr using include_once will not reload the file
				// but the $lang can be from another language
		}
		$res = parent::fetch($_smarty_tpl_file, $_smarty_cache_id, $_smarty_compile_id, $_smarty_display);
		if (!$isCompiled) {
			$prefs['language'] = $lgSave;
			include ('lang/'.$prefs['language'].'/language.php');
		}

		return ereg_replace("^[ \t]*", '', $res);
	}
	function is_cached($_smarty_tpl_file, $_smarty_cache_id = null, $_smarty_compile_id = null) {
		global $prefs, $style_base, $tikidomain;

		if (/*isset($prefs['style']) && isset($style_base)*/0) {
			if ($tikidomain and file_exists("templates/$tikidomain/styles/$style_base/$_smarty_tpl_file")) {
				$_smarty_tpl_file = "$tikidomain/styles/$style_base/$_smarty_tpl_file";
			} elseif ($tikidomain and file_exists("templates/$tikidomain/$_smarty_tpl_file")) {
				$_smarty_tpl_file = "$tikidomain/$_smarty_tpl_file";
			} elseif (file_exists("templates/styles/$style_base/$_smarty_tpl_file")) {
				$_smarty_tpl_file = "styles/$style_base/$_smarty_tpl_file";
			}
		}
		$_smarty_cache_id = $prefs['language'] . $this->template_dir . $_smarty_cache_id;
		$_smarty_compile_id = $prefs['language'] . $this->template_dir . $_smarty_compile_id;
		return parent::is_cached($_smarty_tpl_file, $_smarty_cache_id, $_smarty_compile_id);
	}
	function clear_cache($_smarty_tpl_file = null, $_smarty_cache_id = null, $_smarty_compile_id = null, $_smarty_exp_time=null) {
		global $prefs, $style_base, $tikidomain;

		if (/*isset($prefs['style']) && isset($style_base) && isset($_smarty_tpl_file)*/0) {
			if ($tikidomain and file_exists("templates/$tikidomain/styles/$style_base/$_smarty_tpl_file")) {
				$_smarty_tpl_file = "$tikidomain/styles/$style_base/$_smarty_tpl_file";
			} elseif ($tikidomain and file_exists("templates/$tikidomain/$_smarty_tpl_file")) {
				$_smarty_tpl_file = "$tikidomain/$_smarty_tpl_file";
			} elseif (file_exists("templates/styles/$style_base/$_smarty_tpl_file")) {
				$_smarty_tpl_file = "styles/$style_base/$_smarty_tpl_file";
			}
		}
		$_smarty_cache_id = $prefs['language'] . $this->template_dir . $_smarty_cache_id;
		$_smarty_compile_id = $prefs['language'] . $this->template_dir . $_smarty_compile_id;
		return parent::clear_cache($_smarty_tpl_file, $_smarty_cache_id, $_smarty_compile_id, $_smarty_exp_time);
	}
	function display($resource_name, $cache_id=null, $compile_id = null) {
		if (($tpl = $this->get_template_vars('mid')) && $resource_name == 'tiki.tpl' || $resource_name == 'tiki-print.tpl' || $resource_name == 'tiki_full.tpl') {
			$data = $this->fetch($tpl, $cache_id, $compile_id);//must get the mid because the modules can overwrite smarty variables
			$this->assign('mid_data', $data);
			include_once('tiki-modules.php');
		} elseif ($resource_name == 'confirm.tpl' || $resource_name == 'error.tpl' || $resource_name == 'information.tpl' || $resource_name == 'error_ticket.tpl' || $resource_name == 'error_simple.tpl') {
			include_once('tiki-modules.php');
		}
		return parent::display($resource_name, $cache_id, $compile_id);
	}
	// Returns the file name associated to the template name
	function get_filename($template) {
		global $tikidomain, $style_base;
		if (/*!empty($tikidomain) && is_file($this->template_dir.'/'.$tikidomain.'/styles/'.$style_base.'/'.$template)*/0) {
    			$file = "/$tikidomain/styles/$style_base/";
  		} elseif (!empty($tikidomain) && is_file($this->template_dir.'/'.$tikidomain.'/'.$template)) {
    			$file = "/$tikidomain/";
  		} elseif (/*is_file($this->template_dir.'/styles/'.$style_base.'/'.$template)*/0) {
			$file = "/styles/$style_base/";
  		} else {
    			$file = '';
  		}
		return $this->template_dir.$file.$template;
	}
}
?>
