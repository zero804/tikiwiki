<?php
// (c) Copyright 2002-2013 by authors of the Tiki Wiki CMS Groupware Project
// 
// All Rights Reserved. See copyright.txt for details and a complete list of authors.
// Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See license.txt for details.
// $Id$

ini_set('include_path', '/home/multitiki:/home/multitiki/lib/pear:/home/multitiki/lib/adodb');

include_once("lib/init/initlib.php");

$file_local_php = "/home/multitiki/db/tikiwiki.org/local.php";
$file_local_php_multi = "";
$tikidomain = "tikiwiki.org";
$tikidomain2 = "cc.tikiwiki.org";

require_once("db/tiki-db.php");
require_once("lib/tikilib.php");
require_once("lib/userslib.php");

$tikilib = new TikiLib;
$userlib = new UsersLib;

$users = $userlib->get_group_users('CC');

foreach ($users as $user) {
	$res = $userlib->get_user_info($user);
	echo "replace into users_users set userId='".$res['userId']."', pass_due='". (time()+(60*60*24*999)) ."', email='".$res['email']."', hash='".$res['hash']."', login='".$res['login']."';\n";
	echo "insert ignore into users_usergroups set userId='".$res['userId']."', groupName='Registered';\n";
}
