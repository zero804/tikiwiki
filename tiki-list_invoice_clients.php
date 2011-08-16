<?php
// (c) Copyright 2002-2011 by authors of the Tiki Wiki CMS Groupware Project
// 
// All Rights Reserved. See copyright.txt for details and a complete list of authors.
// Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See license.txt for details.
// $Id$

require_once('tiki-setup.php');
$trklib = TikiLib::lib('trk');
$trkqrylib = TikiLib::lib('trkqry');

$access->check_feature('feature_invoice');
$access->check_permission('tiki_p_admin');

//check if profile is created
if ($trklib->get_tracker_by_name("Invoice Items") < 1) {
	$smarty->assign('msg', tra('You need to apply the "Invoice" profile'));
	$smarty->display("error.tpl");
	die;
}

$headerlib->add_jq_onready("
	$('.ClientName').each(function(i) {
		$(this)
			.click(function() {
				$('.ClientDetails').eq(i).toggle('fast');
			})
			.css('cursor', 'pointer');
	});
");
print_r($trkqrylib->tracker_query_by_names("Invoice Clients"));
$smarty->assign("Clients", $trkqrylib->tracker_query_by_names("Invoice Clients"));
$smarty->assign("Settings", end($trkqrylib->tracker_query_by_names("Invoice Settings")));

// Display the template
$smarty->assign('mid', 'tiki-list_invoice_clients.tpl');
$smarty->display("tiki.tpl");
