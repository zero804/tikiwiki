<?php
// (c) Copyright by authors of the Tiki Wiki CMS Groupware Project
//
// All Rights Reserved. See copyright.txt for details and a complete list of authors.
// Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See license.txt for details.
// $Id$

//this script may only be included - so its better to die if called directly.
if (strpos($_SERVER["SCRIPT_NAME"], basename(__FILE__)) !== false) {
	header("location: index.php");
	exit;
}

TikiLib::lib('smarty')->assign('sodium_available', extension_loaded('sodium'));
TikiLib::lib('smarty')->assign('openssl_available', extension_loaded('openssl'));

if ($prefs['feature_user_encryption'] == 'y') {
	$cryptlib = TikiLib::lib('crypt');
	$smarty->assign('show_user_encyption_stats', 'y');
	$smarty->assign('user_encryption_stat_mcrypt', $cryptlib->getUserCryptDataStats('mcrypt'));
	$smarty->assign('user_encryption_stat_openssl', $cryptlib->getUserCryptDataStats('openssl'));
	$smarty->assign('user_encryption_stat_sodium', $cryptlib->getUserCryptDataStats('sodium'));
}

if (extension_loaded('openssl')) {
  $servicelib = TikiLib::lib('service');
  if (! empty($_REQUEST['encryption_key'])) {
    $result = $servicelib->internal('encryption', 'get_key', ['keyId' => $_REQUEST['encryption_key']]);
    $encryption_key = $result['key'];
  } else {
    $encryption_key = ['algo' => 'aes-256-ctr', 'shares' => 1];
  }
  if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (! empty($_REQUEST['key_delete'])) {
      $servicelib->internal('encryption', 'delete_key', ['keyId' => $_REQUEST['key_delete']]);
    } elseif (! empty($_REQUEST['name'])) {
      try {
        $result = $servicelib->internal('encryption', 'save_key', new JitFilter($_REQUEST));
        $smarty->assign('encryption_shares', $result['shares']);
      } catch (Services_Exception $e) {
        $smarty->assign('encryption_error', $e->getMessage());
        $encryption_key = $_REQUEST;
        $_REQUEST['encryption_key'] = $_REQUEST['keyId'];
      }
      $_POST['redirect'] = 0;
    }
  }
  $encryption_keys = $servicelib->internal('encryption', 'get_keys');
  $smarty->assign('encryption_enabled', 'y');
  $smarty->assign('encryption_algos', openssl_get_cipher_methods());
  $smarty->assign('encryption_key', $encryption_key);
  $smarty->assign('encryption_keys', $encryption_keys);
}
