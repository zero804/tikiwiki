<?php

require_once('tiki-setup.php');
include_once('lib/trackers/trackerlib.php');

if ($prefs['feature_trackers'] != 'y') {
  $smarty->assign('msg', tra("This feature is disabled").": feature_trackers");
  $smarty->display("error.tpl");
  die;
}

if (!isset($_REQUEST["trackerId"])) {
  $smarty->assign('msg', tra("No tracker indicated"));
  $smarty->display("error.tpl");
  die;
}

if ($tiki_p_admin_trackers != 'y') {
	$smarty->assign('errortype', 401);
    $smarty->assign('msg',tra("Permission denied you cannot view this page"));
	$smarty->display("error.tpl");
	die;
}

if (isset($_FILES['importfile']) && is_uploaded_file($_FILES['importfile']['tmp_name'])) {
	$replace = false;
	$total = 'Incorrect file';
	$fp = @ fopen($_FILES['importfile']['tmp_name'], "rb");
	if ($fp) {
		$total = $trklib->import_csv($_REQUEST["trackerId"],$fp, true, isset($_REQUEST['dateFormat'])? $_REQUEST['dateFormat']: '', isset($_REQUEST['encoding'])? $_REQUEST['encoding']: 'UTF8');
	}
	fclose($fp);
	if (!is_numeric($total)) {
		$smarty->assign('msg', $total);
		$smarty->display('error.tpl');
		die;
	}
}
header('Location: tiki-view_tracker.php?trackerId='.$_REQUEST["trackerId"]);
die;

?>
