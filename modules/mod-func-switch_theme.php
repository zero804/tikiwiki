<?php

//this script may only be included - so its better to die if called directly.
if (strpos($_SERVER["SCRIPT_NAME"],basename(__FILE__)) !== false) {
  header("location: index.php");
  exit;
}

function module_switch_theme_info() {
	return array(
		'name' => tra('Switch theme'),
		'description' => tra('Enables to quickly change the theme.'),
		'prefs' => array( 'change_theme' ),
		'params' => array()
	);
}

function module_switch_theme( $mod_reference, $module_params ) {
	global $prefs, $user, $tikilib, $smarty;
	
	if ( isset($_COOKIE['tiki-theme']) && !($prefs['feature_userPreferences'] == 'y' && $user && $prefs['change_theme'] == 'y') ){
		$style = $_COOKIE['tiki-theme'];
	}
	if ( isset($_COOKIE['tiki-theme-option']) && !($prefs['feature_userPreferences'] == 'y' && $user && $prefs['change_theme'] == 'y') ){
		$style_option = $_COOKIE['tiki-theme-option'];
	}
	
	$smarty->assign('styleslist',$tikilib->list_styles());
	$smarty->assign( "style_options", $tikilib->list_style_options());
	$smarty->clear_assign('tpl_module_title'); // TPL sets dynamic default title
}