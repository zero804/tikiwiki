<?php

//this script may only be included - so its better to die if called directly.
if (strpos($_SERVER["SCRIPT_NAME"],basename(__FILE__)) !== false) {
  die("This script cannot be called directly");
}

$nvi_info = $tikilib->get_news_from_last_visit($user);

$smarty->assign('nvi_info', $nvi_info);

?>
