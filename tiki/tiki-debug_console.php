<?php
//
// $Header: /cvsroot/tikiwiki/tiki/tiki-debug_console.php,v 1.2 2003-08-01 10:30:45 redflo Exp $
//

global $debugger;
require_once('lib/debug/debugger.php');

global $smarty;

// Get current URL
$smarty->assign('console_father', $_SERVER["REQUEST_URI"]);


// Set default value
$smarty->assign('result_type', NO_RESULT);

// Exec user command in internal debugger
if (isset($_REQUEST["command"]))
{
  // Exec command in debugger
  $command_result = $debugger->execute($_REQUEST["command"]);

  $smarty->assign('command', $_REQUEST["command"]);
  $smarty->assign('result_type', $debugger->result_type());

  // If result need temlate then we have $command_result array...
  if ($debugger->result_type() == TPL_RESULT)
  {
    $smarty->assign('result_tpl', $debugger->result_tpl());
    $smarty->assign_by_ref('command_result', $command_result);
  }
  else $smarty->assign('command_result', $command_result);
} else {
  $smarty->assign('command', "");
}

// Draw tabs to array. Note that it MUST be AFTER exec command.
// Bcouse 'exec' can change state of smth so tabs content should be changed...
$tabs_list = $debugger->background_tabs_draw();
// Add results tab which is always exists...
$tabs_list["console"] = $smarty->fetch("debug/tiki-debug_console_tab.tpl");
ksort($tabs_list);
$tabs = array(); 
// TODO: Use stupid dbl loop to generate links code and divs,
//       but it is quite suitable for
foreach ($tabs_list as $tname => $tcode)
{
  // Generate href code for current button
  $href = 'javascript:';
  foreach ($tabs_list as $tn => $t)
    $href .= (($tn == $tname) ? 'show' : 'hide')."('".md5($tn)."');";
  //
  $tabs[] = array("button_caption" => $tname, 
                  "tab_id"         => md5($tname),
                  "button_href"    => $href, 
                  "tab_code"       => $tcode);
}
$smarty->assign_by_ref('tabs', $tabs);

?>
