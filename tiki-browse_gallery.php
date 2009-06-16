<?php

/* $Id: $ */

// Copyright (c) 2002-2007, Luis Argerich, Garland Foster, Eduardo Polidor, et. al.
// All Rights Reserved. See copyright.txt for details and a complete list of authors.
// Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See license.txt for details.

// Initialization
$section = 'galleries';
require_once ('tiki-setup.php');

include_once ("lib/imagegals/imagegallib.php");
include_once ('lib/stats/statslib.php');

if ($prefs['feature_categories'] == 'y') {
	global $categlib;
	if (!is_object($categlib)) {
		include_once('lib/categories/categlib.php');
	}
}

if ($prefs['feature_galleries'] != 'y') {
	$smarty->assign('msg', tra("This feature is disabled").": feature_galleries");

	$smarty->display("error.tpl");
	die;
}

if ($_REQUEST["galleryId"] == 0 && $tiki_p_admin_galleries != 'y') {
	$smarty->assign('errortype', 401);
	$smarty->assign('msg', tra("Permission denied you cannot access this gallery"));

	$smarty->display("error.tpl");
	die;
}

if (!isset($_REQUEST["galleryId"])) {
	$smarty->assign('msg', tra("No gallery indicated"));

	$smarty->display("error.tpl");
	die;
}

$smarty->assign('individual', 'n');

if ($userlib->object_has_one_permission($_REQUEST["galleryId"], 'image gallery')) {
	$smarty->assign('individual', 'y');

	if ($tiki_p_admin != 'y') {
		// Now get all the permissions that are set for this type of permissions 'image gallery'
		$perms = $userlib->get_permissions(0, -1, 'permName_desc', '', 'image galleries');

		foreach ($perms["data"] as $perm) {
			$permName = $perm["permName"];

			if ($userlib->object_has_permission($user, $_REQUEST["galleryId"], 'image gallery', $permName)) {
				$$permName = 'y';

				$smarty->assign("$permName", 'y');
			} else {
				$$permName = 'n';

				$smarty->assign("$permName", 'n');
			}
		}
	}
} elseif ($tiki_p_admin != 'y' && $prefs['feature_categories'] == 'y') {
	$perms_array = $categlib->get_object_categories_perms($user, 'image gallery', $_REQUEST['galleryId']);
   	if ($perms_array) {
   		$is_categorized = TRUE;
    	foreach ($perms_array as $perm => $value) {
    		$$perm = $value;
    	}
   	} else {
   		$is_categorized = FALSE;
   	}
	if ($is_categorized && isset($tiki_p_view_categorized) && $tiki_p_view_categorized != 'y') {
		$smarty->assign('errortype', 401);
		$smarty->assign('msg',tra("Permission denied you cannot view this page"));
		$smarty->display("error.tpl");
		die;
	}
}

if ($tiki_p_admin_galleries == 'y') {
	$tiki_p_view_image_gallery = 'y';

	$smarty->assign("tiki_p_view_image_gallery", 'y');
	$tiki_p_upload_images = 'y';
	$smarty->assign("tiki_p_upload_images", 'y');
	$tiki_p_create_galleries = 'y';
	$smarty->assign("tiki_p_create_galleries", 'y');
}

if ($tiki_p_view_image_gallery != 'y') {
	$smarty->assign('errortype', 401);
	$smarty->assign('msg', tra("Permission denied you can not view this section"));

	$smarty->display("error.tpl");
	die;
}

$auto_query_args = array('offset','galleryId', 'sort_mode', 'find');

if (!isset($_REQUEST["galleryId"])) {
	$_REQUEST["galleryId"] = 0;
}

if ($_REQUEST["galleryId"] != 0) {
	// To browse the gallery the user has to be admin, the owner or the gallery has to be public
	$gal_info = $imagegallib->get_gallery($_REQUEST["galleryId"]);
} else {
	$gal_info["galleryId"] = 0;

	$gal_info["user"] = 'admin';
	$gal_info["name"] = tra('System');
	$gal_info["public"] = 'y';
	$gal_info["description"] = tra('System Gallery');
	$gal_info['showname'] ='y';
	$gal_info['showimageid'] ='n';
	$gal_info['showcategories'] ='n';
	$gal_info['showdescription'] ='n';
	$gal_info['showcreated'] ='n';
	$gal_info['showuser'] ='n';
	$gal_info['showhits'] ='y';
	$gal_info['showxysize'] ='y';
	$gal_info['showfilesize'] ='n';
	$gal_info['showfilename'] ='n';
	$gal_info['defaultscale'] ='o';

}

$smarty->assign_by_ref('owner', $gal_info["user"]);
$smarty->assign_by_ref('public', $gal_info["public"]);
$smarty->assign_by_ref('galleryId', $_REQUEST["galleryId"]);
$smarty->assign_by_ref('showname', $gal_info['showname']);
$smarty->assign_by_ref('showimageid', $gal_info['showimageid']);
$smarty->assign_by_ref('showcategories', $gal_info['showcategories']);
$smarty->assign_by_ref('showdescription', $gal_info['showdescription']);
$smarty->assign_by_ref('showcreated', $gal_info['showcreated']);
$smarty->assign_by_ref('showuser', $gal_info['showuser']);
$smarty->assign_by_ref('showhits', $gal_info['showhits']);
$smarty->assign_by_ref('showxysize', $gal_info['showxysize']);
$smarty->assign_by_ref('showfilesize', $gal_info['showfilesize']);
$smarty->assign_by_ref('showfilename', $gal_info['showfilename']);

if ($prefs['preset_galleries_info'] == 'y' && $prefs['scaleSizeGalleries'] > 0) {
     $gal_info['defaultscale'] = $prefs['scaleSizeGalleries'];
}
$smarty->assign_by_ref('defaultscale', $gal_info['defaultscale']);

$imagegallib->add_gallery_hit($_REQUEST["galleryId"]);

if (isset($_REQUEST["remove"])) {
	check_ticket('browse-gallery');
	// To remove an image the user must be the owner or admin
	if (($tiki_p_admin_galleries != 'y') && (!$user || $user != $gal_info["user"])) {
		$smarty->assign('errortype', 401);
		$smarty->assign('msg', tra("Permission denied you cannot remove images from this gallery"));

		$smarty->display("error.tpl");
		die;
	}

  $area = 'delgalimage';
  if ($prefs['feature_ticketlib2'] != 'y' or (isset($_POST['daconfirm']) and isset($_SESSION["ticket_$area"]))) {
    key_check($area);
		$imagegallib->remove_image($_REQUEST["remove"]);
	} else {
		key_get($area);
	}
}

if (isset($_REQUEST["rebuild"])) {
	check_ticket('browse-gallery');
	// To rebuild thumbnails the user must be the owner or admin
	if (($tiki_p_admin_galleries != 'y') && (!$user || $user != $gal_info["user"])) {
		$smarty->assign('errortype', 401);
		$smarty->assign('msg', tra("Permission denied you cannot rebuild thumbnails in this gallery"));

		$smarty->display("error.tpl");
		die;
	}
	$smarty->assign('advice', 'You must clear your browser cache.');//get_strings tra('You must clear your browser cache.');

	$imagegallib->rebuild_thumbnails($_REQUEST["rebuild"]);
}

if (isset($_REQUEST["rotateright"])) {
	check_ticket('browse-gallery');
	// To rotate an image the user must be the owner or admin
	if (($tiki_p_admin_galleries != 'y') && (!$user || $user != $gal_info["user"])) {
		$smarty->assign('errortype', 401);
		$smarty->assign('msg', tra("Permission denied you cannot rotate images in this gallery"));

		$smarty->display("error.tpl");
		die;
	}

	$imagegallib->rotate_right_image($_REQUEST["rotateright"]);
}

if (isset($_REQUEST["rotateleft"])) {
	check_ticket('browse-gallery');
	// To rotate an image the user must be the owner or admin
	if (($tiki_p_admin_galleries != 'y') && (!$user || $user != $gal_info["user"])) {
		$smarty->assign('errortype', 401);
		$smarty->assign('msg', tra("Permission denied you cannot rotate images in this gallery"));

		$smarty->display("error.tpl");
		die;
	}

	$imagegallib->rotate_left_image($_REQUEST["rotateleft"]);
}
// Watches
if($prefs['feature_user_watches'] == 'y') {
    if($user && isset($_REQUEST['watch_event'])) {
		check_ticket('browse-gallery');
		if($_REQUEST['watch_action']=='add') {
	    	$tikilib->add_user_watch($user,$_REQUEST['watch_event'],$_REQUEST['watch_object'],'image gallery',$gal_info['name'],'tiki-browse_gallery.php?galleryId='.$_REQUEST['galleryId']);
		} else {
	    	$tikilib->remove_user_watch($user,$_REQUEST['watch_event'],$_REQUEST['watch_object'], 'image gallery');
		}
    }
    $smarty->assign('user_watching_gal','n');
    if($user && $tikilib->user_watches($user,'image_gallery_changed',$_REQUEST['galleryId'], 'image gallery')) {
	$smarty->assign('user_watching_gal','y');
    }

    // Check, if the user is watching this image gallery by a category.    
	if ($prefs['feature_categories'] == 'y') { 		  
	    $watching_categories_temp=$categlib->get_watching_categories($_REQUEST['galleryId'],'image gallery',$user);	    
	    $smarty->assign('category_watched','n');
	 	if (count($watching_categories_temp) > 0) {
	 		$smarty->assign('category_watched','y');
	 		$watching_categories=array();	 			 	
	 		foreach ($watching_categories_temp as $wct ) {								 	
	 			$watching_categories[]=array("categId"=>$wct,"name"=>$categlib->get_category_name($wct));
	 		}		 		 	
	 		$smarty->assign('watching_categories', $watching_categories);
	 	}    
	}
}

$smarty->assign('system', 'n');

if ($_REQUEST["galleryId"] == 0) {
	$info["thumbSizeX"] = 100;

	$info["thumbSizeY"] = 100;
	$info["galleryId"] = 0;
	$info["user"] = 'admin';
	$info["name"] = tra('System');
	$info["public"] = 'y';
	$info["description"] = tra('System Gallery');
	$info['sortorder'] = 'created';
	$info['sortdirection'] = 'desc';
	$info['galleryimage'] = 'last';
	$smarty->assign('system', 'y');
} else {
	$info = $imagegallib->get_gallery($_REQUEST["galleryId"]);

	$nextscaleinfo = $imagegallib->get_gallery_next_scale($_REQUEST["galleryId"]);
}

if (empty($info['maxRows']) || $info['maxRows'] < 0)
	$info['maxRows'] = 10;

if (empty($info['rowImages']) || $info['rowImages'] < 0)
	$info['rowImages'] = 5;

if (!isset($nextscaleinfo['scale'])) {
	$nextscaleinfo['scale'] = 0;

	$nextscaleinfo['scale'] = 0;
}

//print $info["rowImages"] ;
$maxImages = $info["maxRows"] * $info["rowImages"];
$smarty->assign_by_ref('maxImages', $maxImages);
$smarty->assign_by_ref('rowImages', $info["rowImages"]);
$smarty->assign('rowImages2', $info["rowImages"] - 1);
$smarty->assign_by_ref('thx', $info["thumbSizeX"]);
$smarty->assign_by_ref('thy', $info["thumbSizeY"]);
$smarty->assign_by_ref('name', $info["name"]);
$smarty->assign('title', $info["name"]);
$smarty->assign_by_ref('description', $info["description"]);
$smarty->assign_by_ref('nextscale', $nextscaleinfo['scale']);

// Can we rotate images
if ($imagegallib->canrotate) {
	$smarty->assign('imagerotate', true);
} else {
	$smarty->assign('imagerotate', false);
}

if (!isset($_REQUEST["sort_mode"])) {
	if(isset($info['sortorder'])) {
		// default sortorder from gallery settings
		$sort_mode = $info['sortorder'].'_'.$info['sortdirection'];
	} else {
		$sort_mode = 'created_desc';
	}
} else {
	$sort_mode = $_REQUEST["sort_mode"];
}

$smarty->assign_by_ref('sort_mode', $sort_mode);

// If offset is set use it if not then use offset =0
// use the maxRecords php variable to set the limit
// if sortMode is not set then use lastModif_desc
if (!isset($_REQUEST["offset"])) {
	$offset = 0;
} else {
	$offset = $_REQUEST["offset"];
}

$smarty->assign_by_ref('offset', $offset);

if (isset($_REQUEST["find"])) {
	$find = $_REQUEST["find"];
} else {
	$find = '';
}
$smarty->assign_by_ref('find', $find);

// get subgalleries first
$subgals = $imagegallib->get_subgalleries($offset, $maxImages, $sort_mode, '', $_REQUEST["galleryId"]);
$remainingImages = $maxImages-count($subgals['data']);
$newoffset = $offset -$subgals['cant'];
$images = $imagegallib->get_images($newoffset, $remainingImages, $sort_mode, $find, $_REQUEST["galleryId"]);
//get categories for each images
global $objectlib;
if ($prefs['feature_categories'] == 'y') {
	$type='image';
	$arr=array();
	for($i=0;$i<=count($images['data'])-1;$i++){
		$img_id=$images['data'][$i]['imageId'];
		$arr= $categlib->get_object_categories($type, $img_id);
		//adding categories to the object
		for ($k=0; $k<=count($arr)-1; $k++){
			$images['data'][$i]['categories'][$k]=$categlib->get_category_name($arr[$k]);
		}
	}
}
$smarty->assign('num_objects',count($subgals['data'])+count($images['data']));
$smarty->assign('num_subgals',count($subgals['data']));
$smarty->assign('num_images',count($images['data']));

$smarty->assign_by_ref('images', $images["data"]);
$smarty->assign('cant', $subgals['cant']+ $images['cant']);
$smarty->assign_by_ref('subgals', $subgals['data']);

// Mouseover data
if ( $prefs['gal_image_mouseover'] != 'n' ) {
	foreach ( $images['data'] as $k => $v ) {
		$smarty->assign_by_ref('file_info', $v);
		$over_info[$k] = $smarty->fetch("tiki-file_info_box.tpl");
	}
	$smarty->assign_by_ref('over_info', $over_info);
}

if ($prefs['feature_image_galleries_comments'] == 'y') {
	$comments_per_page = $prefs['image_galleries_comments_per_page'];

	$thread_sort_mode = $prefs['image_galleries_comments_default_order'];
	$comments_vars = array(
		'galleryId',
		'offset',
		'sort_mode'
	);

	$comments_prefix_var = 'image gallery:';
	$comments_object_var = 'galleryId';
	include_once ("comments.php");
}

include_once ('tiki-section_options.php');

if ($prefs['feature_theme_control'] == 'y') {
	$cat_type = 'image gallery';

	$cat_objid = $_REQUEST["galleryId"];
	include ('tiki-tc.php');
}
ask_ticket('browse-gallery');
//add a hit
$statslib->stats_hit($gal_info["name"],"image gallery",$_REQUEST["galleryId"]);
if ($prefs['feature_actionlog'] == 'y') {
	include_once('lib/logs/logslib.php');
	$logslib->add_action('Viewed', $_REQUEST['galleryId'], 'image gallery');
}

// Display the template

$smarty->assign('mid', 'tiki-browse_gallery.tpl');
$smarty->display("tiki.tpl");

?>
