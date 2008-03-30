<?php

// $Id: /cvsroot/tikiwiki/tiki/tiki-login_scr.php,v 1.16.2.2 2008-03-11 20:06:32 nkoth Exp $

// Copyright (c) 2002-2007, Luis Argerich, Garland Foster, Eduardo Polidor, et. al.
// All Rights Reserved. See copyright.txt for details and a complete list of authors.
// Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See license.txt for details.

# $Id: /cvsroot/tikiwiki/tiki/tiki-login_scr.php,v 1.16.2.2 2008-03-11 20:06:32 nkoth Exp $
include_once ("tiki-setup.php");

if ( isset($_REQUEST['user']) ) {
	if ( $_REQUEST['user'] == 'admin' ) $smarty->assign('showloginboxes', 'y');
	else $smarty->assign('loginuser', $_REQUEST['user']);
}
if ($prefs['useGroupHome'] != 'y' && !isset($_SESSION['loginfrom'])) {
	$_SESSION['loginfrom'] = ( isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : $prefs['tikiIndex'] );
 }

// disallow robots to index page:
$smarty->assign('metatag_robots', 'NOINDEX, NOFOLLOW');

$smarty->assign('mid', 'tiki-login.tpl');
$smarty->display("tiki.tpl");

?>
