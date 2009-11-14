<?php
// (c) Copyright 2002-2009 by authors of the Tiki Wiki/CMS/Groupware Project
// 
// All Rights Reserved. See copyright.txt for details and a complete list of authors.
// Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See license.txt for details.
require_once ('tiki-setup.php');

if ($prefs['feature_kaltura'] != 'y') {
	$smarty->assign('msg', tra("This feature is disabled").": feature_kaltura");
	$smarty->display("error.tpl");
	die;
}

include_once ("lib/videogals/KalturaClient_v3.php");

if ($tiki_p_upload_videos != 'y' && $tiki_p_admin_kaltura != 'y' && $tiki_p_admin != 'y') {
	$smarty->assign('errortype', 401);
	$smarty->assign('msg', tra("Permission denied: You cannot upload videos"));
	$smarty->display('error.tpl');
	die;
}

$secret = $prefs['secret'];
$admin_secret = $prefs['adminSecret'];
$partner_id = $prefs['partnerId'];
$SESSION_ADMIN = 2;
$SESSION_USER = 0;

if (empty($partner_id) || !is_numeric($partner_id) || empty($secret) || empty($admin_secret)) {
	$smarty->assign('msg', tra("You need to set your Kaltura account details: ") . '<a href="tiki-admin.php?page=kaltura">' . tra('here') . '</a>');
	$smarty->display('error.tpl');
	die;
}

$kconf = new KalturaConfiguration($partner_id);
$kclient = new KalturaClient($kconf);
$ksession = $kclient->session->start($secret,$user,$SESSION_USER);

if(!isset($ksession)) {
	$smarty->assign('msg', tra("Could not establish Kaltura session. Try again"));
	$smarty->display('error.tpl');
	die;
}
$kclient->setKs($ksession);

$cwflashVars = array();
$cwflashVars["uid"]               = $user;
$cwflashVars["partnerId"]         = $partner_id;
$cwflashVars["ks"]                  = $ksession;
$cwflashVars["afterAddEntry"]     = "afterAddEntry";
$cwflashVars["close"]       = "onContributionWizardClose";
$cwflashVars["showCloseButton"]   = false;
$cwflashVars["Permissions"]       = 1; 

$smarty->assign_by_ref('cwflashVars',json_encode($cwflashVars));

$count = 0;
if($_REQUEST['kcw']){
	$count = count($_REQUEST['entryId']);
	$smarty->assign_by_ref('count',$count);
}
// Display the template
	$smarty->assign('mid','tiki-kaltura_upload.tpl');
	$smarty->display("tiki.tpl");
