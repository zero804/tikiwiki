<?php
// Initialization
require_once('tiki-setup.php');

if($feature_blogs != 'y') {
  $smarty->assign('msg',tra("This feature is disabled"));
  $smarty->display('error.tpl');
  die;  
}


if($tiki_p_read_blog != 'y') {
  $smarty->assign('msg',tra("Permission denied you cant view this section"));
  $smarty->display('error.tpl');
  die;  
}



/*
if($feature_listPages != 'y') {
  $smarty->assign('msg',tra("This feature is disabled"));
  $smarty->display('error.tpl');
  die;  
}
*/

/*
// Now check permissions to access this page
if($tiki_p_view != 'y') {
  $smarty->assign('msg',tra("Permission denied you cannot view pages"));
  $smarty->display('error.tpl');
  die;  
}
*/

if(isset($_REQUEST["remove"])) {
  // Check if it is the owner
  $data = $tikilib->get_blog($_REQUEST["remove"]);
  if($data["user"]!=$user) {
    if($tiki_p_blog_admin != 'y') {
      $smarty->assign('msg',tra("Permission denied you cannot remove this blog"));
      $smarty->display('error.tpl');
      die;  
    }
  }
  $tikilib->remove_blog($_REQUEST["remove"]);  
}

// This script can receive the thresold
// for the information as the number of
// days to get in the log 1,3,4,etc
// it will default to 1 recovering information for today
if(!isset($_REQUEST["sort_mode"])) {
  $sort_mode = 'created_desc'; 
} else {
  $sort_mode = $_REQUEST["sort_mode"];
} 


$smarty->assign_by_ref('sort_mode',$sort_mode);

// If offset is set use it if not then use offset =0
// use the maxRecords php variable to set the limit
// if sortMode is not set then use lastModif_desc
if(!isset($_REQUEST["offset"])) {
  $offset = 0;
} else {
  $offset = $_REQUEST["offset"]; 
}
$smarty->assign_by_ref('offset',$offset);

if(isset($_REQUEST["find"])) {
  $find = $_REQUEST["find"];  
} else {
  $find = ''; 
}

// Get a list of last changes to the Wiki database
$listpages = $tikilib->list_blogs($offset,$maxRecords,$sort_mode,$find);
for($i=0;$i<count($listpages["data"]);$i++) {
  if($userlib->object_has_one_permission($listpages["data"][$i]["blogId"],'blog')) {
    $listpages["data"][$i]["individual"]='y';
    
    if($userlib->object_has_permission($user,$listpages["data"][$i]["blogId"],'blog','tiki_p_read_blog')) {
      $listpages["data"][$i]["individual_tiki_p_read_blog"]='y';
    } else {
      $listpages["data"][$i]["individual_tiki_p_read_blog"]='n';
    }
    if($userlib->object_has_permission($user,$listpages["data"][$i]["blogId"],'blog','tiki_p_blog_post')) {
      $listpages["data"][$i]["individual_tiki_p_blog_post"]='y';
    } else {
      $listpages["data"][$i]["individual_tiki_p_blog_post"]='n';
    }
    if($userlib->object_has_permission($user,$listpages["data"][$i]["blogId"],'blog','tiki_p_create_blogs')) {
      $listpages["data"][$i]["individual_tiki_p_create_blogs"]='y';
    } else {
      $listpages["data"][$i]["individual_tiki_p_create_blogs"]='n';
    }
    if($tiki_p_admin=='y' || $userlib->object_has_permission($user,$listpages["data"][$i]["blogId"],'file gallery','tiki_p_blog_admin')) {
      $listpages["data"][$i]["individual_tiki_p_create_blogs"]='y';
      $listpages["data"][$i]["individual_tiki_p_blog_post"]='y';
      $listpages["data"][$i]["individual_tiki_p_read_blog"]='y';
    } 
    
  } else {
    $listpages["data"][$i]["individual"]='n';
  }
}




// If there're more records then assign next_offset
$cant_pages = ceil($listpages["cant"] / $maxRecords);
$smarty->assign_by_ref('cant_pages',$cant_pages);
$smarty->assign('actual_page',1+($offset/$maxRecords));

if($listpages["cant"] > ($offset + $maxRecords)) {
  $smarty->assign('next_offset',$offset + $maxRecords);
} else {
  $smarty->assign('next_offset',-1); 
}
// If offset is > 0 then prev_offset
if($offset>0) {
  $smarty->assign('prev_offset',$offset - $maxRecords);  
} else {
  $smarty->assign('prev_offset',-1); 
}

$smarty->assign_by_ref('listpages',$listpages["data"]);
//print_r($listpages["data"]);

// Display the template
$smarty->assign('mid','tiki-list_blogs.tpl');
$smarty->display('tiki.tpl');
?>
