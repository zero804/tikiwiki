<?php
// (c) Copyright 2002-2013 by authors of the Tiki Wiki CMS Groupware Project
// 
// All Rights Reserved. See copyright.txt for details and a complete list of authors.
// Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See license.txt for details.
// $Id$

include "tiki-setup.php";
$access->check_feature('feature_gmap');
$smarty->assign('mid', 'tiki-gmap_usermap.tpl');
$smarty->display('tiki.tpl');
