<?php

//this script may only be included - so its better to die if called directly.
if (strpos($_SERVER["SCRIPT_NAME"],basename(__FILE__)) !== false) {
  header("location: index.php");
  exit;
}

$users_rank = $tikilib->rank_users($module_rows);

$smarty->assign('users_rank', $users_rank);

?>
