<?php 
// (c) Copyright 2002-2013 by authors of the Tiki Wiki CMS Groupware Project
// 
// All Rights Reserved. See copyright.txt for details and a complete list of authors.
// Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See license.txt for details.
// $Id$

function validator_password($input, $parameter = '', $message = '')
{
	global $userlib;
	$errors = $userlib->check_password_policy($input);
	if (!$errors) {
		return true;
	} else {
		return $errors;
	}
}



