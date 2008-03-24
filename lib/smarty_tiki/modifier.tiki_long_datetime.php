<?php

//this script may only be included - so its better to die if called directly.
if (strpos($_SERVER["SCRIPT_NAME"],basename(__FILE__)) !== false) {
  header("location: index.php");
  exit;
}

require_once $smarty->_get_plugin_filepath('modifier','tiki_date_format');
function smarty_modifier_tiki_long_datetime($string) {
	global $prefs;
	return smarty_modifier_tiki_date_format($string, $prefs['long_date_format'].' '.$prefs['long_time_format']);
}

?>
