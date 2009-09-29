<?php
// (c) Copyright 2002-2009 by authors of the Tiki Wiki/CMS/Groupware Project
// 
// All Rights Reserved. See copyright.txt for details and a complete list of authors.
// Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See license.txt for details.
// $Id: /cvsroot/tikiwiki/tiki/tiki-objectpermissions.php,v 1.25.2.2 2008-03-11 15:17:54 nyloth Exp $
include_once ("tiki-setup.php");
if (!empty($_REQUEST['objectType']) && $_REQUEST['objectType'] != 'global') {
	if (!isset($_REQUEST['objectName']) || empty($_REQUEST['objectId'])) {
		$smarty->assign('msg', tra("Not enough information to display this page"));
		$smarty->display("error.tpl");
		die;
	}
}
if (empty($_REQUEST['objectType'])) {
	 $_REQUEST['objectType'] = 'global';
	 $_REQUEST['objectName'] = '';
	 $_REQUEST['objectId'] = '';
}


$auto_query_args = array(
	'referer',
	'reloff',
	'objectName',
	'objectType',
	'permType',
	'objectId',
	'filegals_manager',
	'show_disabled_features',
);
$perm = 'tiki_p_assign_perm_' . str_replace(' ', '_', $_REQUEST['objectType']);
if ($_REQUEST['objectType'] == 'wiki page') {
	if ($tiki_p_admin_wiki == 'y') {
		$special_perm = 'y';
	} else {
		$info = $tikilib->get_page_info($_REQUEST['objectName']);
		$tikilib->get_perm_object($_REQUEST['objectId'], $_REQUEST['objectType'], $info);
	}
} else if ($_REQUEST['objectType'] == 'global') {
	if ($tiki_p_admin != 'y') {						// is there a better perm for this?
		$smarty->assign('errortype', 401);
		$smarty->assign('msg', tra("Permission denied you cannot assign permissions for this object"));
		$smarty->display("error.tpl");
		die;
	}
} else {
	$tikilib->get_perm_object($_REQUEST['objectId'], $_REQUEST['objectType']);
	if ($_REQUEST['objectType'] == 'tracker') {
		global $trklib;
		include ('lib/trackers/trackerlib.php');
		if ($groupCreatorFieldId = $trklib->get_field_id_from_type($_REQUEST['objectId'], 'g', '1%')) {
			$smarty->assign('group_tracker', 'y');
		}
	}
}
if (!($tiki_p_admin_objects == 'y' || (isset($$perm) && $$perm == 'y') || (isset($special_perm) && $special_perm == 'y'))) {
	$smarty->assign('errortype', 401);
	$smarty->assign('msg', tra("Permission denied you cannot assign permissions for this object"));
	$smarty->display("error.tpl");
	die;
}

if (!isset($_REQUEST["referer"])) {
	if (isset($_SERVER['HTTP_REFERER']) && strpos($_SERVER['HTTP_REFERER'], 'tiki-objectpermissions.php') === false) {
		$_REQUEST["referer"] = $_SERVER['HTTP_REFERER'];
	} else {
		unset($_REQUEST["referer"]);
	}
}
if (isset($_REQUEST["referer"])) {
	$smarty->assign('referer', $_REQUEST["referer"]);
} else {
	$smarty->assign('referer', '');
}
$_REQUEST["objectId"] = urldecode($_REQUEST["objectId"]);
$_REQUEST["objectType"] = urldecode($_REQUEST["objectType"]);
$_REQUEST["permType"] = !empty($_REQUEST['permType']) ? urldecode($_REQUEST["permType"]) : 'all';
$smarty->assign('objectName', $_REQUEST["objectName"]);
$smarty->assign('objectId', $_REQUEST["objectId"]);
$smarty->assign('objectType', $_REQUEST["objectType"]);
$smarty->assign('permType', $_REQUEST["permType"]);

if( $_REQUEST['objectType'] == 'wiki' ) {
	$_REQUEST['objectType'] = 'wiki page';
}

require_once 'lib/core/lib/Perms/Applier.php';
require_once 'lib/core/lib/Perms/Reflection/Factory.php';

$objectFactory = Perms_Reflection_Factory::getDefaultFactory();
$currentObject = $objectFactory->get( $_REQUEST['objectType'], $_REQUEST['objectId'] );

$permissionApplier = new Perms_Applier;
$permissionApplier->addObject( $currentObject );

if ($_REQUEST['objectType'] == 'wiki page') {
	global $structlib;
	include_once ('lib/structures/structlib.php');
	$pageInfoTree = $structlib->s_get_structure_pages($structlib->get_struct_ref_id($_REQUEST['objectId']));
	if (count($pageInfoTree) > 1) {
		$smarty->assign('inStructure', 'y');
	}

	// If assign to structure is requested, add subelements to the applier
	if (!empty($_REQUEST['assignstructure']) && $_REQUEST['assignstructure'] == 'on' && !empty($pageInfoTree)) {
		foreach( $pageInfoTree as $subPage ) {
			$sub = $objectFactory->get( $_REQUEST['objectType'], $subPage['pageName'] );
			$permissionApplier->addObject( $sub );
		}
	}
}

//Quickperms {{{
//Test to map permissions of ile galleries into read write admin admin levels.
if( $prefs['feature_quick_object_perms'] == 'y' ) {
	require_once 'lib/core/lib/Perms/Reflection/Quick.php';

	$perms = quickperms_get_data();
	$smarty->assign('quickperms', $perms);
	$quickperms = new Perms_Reflection_Quick;

	foreach( $perms as $type => $data ) {
		$quickperms->configure( $type, $data['data'] );
	}

	if (isset($_REQUEST['assign']) && isset($_REQUEST['quick_perms'])) {
		check_ticket('object-perms');
	
		$groups = $userlib->get_groups(0, -1, 'groupName_asc', '', '', 'n');
	
		$userInput = array();
		foreach($groups['data'] as $group) {
			if(isset($_REQUEST["perm_".$group['groupName']])) {
				$group = $group['groupName'];
				$permission = $_REQUEST["perm_".$group];

				$userInput[$group] = $permission;
			}
		}

		$current = $currentObject->getDirectPermissions();
		$newPermissions = $quickperms->getPermissions( $current, $userInput );
		$permissionApplier->apply( $newPermissions );
	}
}
//Quickperm END }}}


// Process the form to assign a new permission to this object
if (isset($_REQUEST['assign']) && !isset($_REQUEST['quick_perms'])) {
	check_ticket('object-perms');
	foreach($_REQUEST['perm'] as $group => $perms) {
		foreach($perms as $perm) {
			if ($tiki_p_admin_objects != 'y' && !$userlib->user_has_permission($user, $perm)) {
				$smarty->assign('errortype', 401);
				$smarty->assign('msg', tra('Permission denied'));
				$smarty->display('error.tpl');
				die;
			}
		}
	}
	
	$newPermissions = get_assign_permissions();
	$permissionApplier->apply( $newPermissions );
	$smarty->assign('groupName', $_REQUEST["group"]);
}
// Process the form to remove a permission from the page
if (isset($_REQUEST["action"])) {
	check_ticket('object-perms');
	if ($_REQUEST["action"] == 'remove') {
		remove_perm($_REQUEST["group"], $_REQUEST["objectId"], $_REQUEST["objectType"], $_REQUEST["perm"]);
	}
}
if (isset($_REQUEST['delsel_x']) && isset($_REQUEST['checked'])) {
	check_ticket('object-perms');
	foreach($_REQUEST['checked'] as $perm) {
		if (preg_match('/([^ ]*) (.*)/', $perm, $matches)) {
			if (!empty($_REQUEST['removestructure']) && $_REQUEST['removestructure'] == 'on' && !empty($pageInfoTree)) {
				foreach($pageInfoTree as $subPage) {
					remove_perm($matches[2], $subPage['pageName'], $_REQUEST['objectType'], $matches[1]);
				}
			} else {
				remove_perm($matches[2], $_REQUEST['objectId'], $_REQUEST['objectType'], $matches[1]);
			}
		}
	}
}

// Prepare display

$currentObject = $objectFactory->get( $_REQUEST['objectType'], $_REQUEST['objectId'] );
$displayedPermissions = $currentObject->getDirectPermissions();

// Now we have to get the individual page permissions if any
if ($_REQUEST['objectType'] == 'global') {
	$page_perms = array();
} else {
	$page_perms = $userlib->get_object_permissions($_REQUEST["objectId"], $_REQUEST["objectType"]);
}

// Get a list of groups
$groups = $userlib->get_groups(0, -1, 'id_asc', '', '', 'n');

//Quickperm
if( $prefs['feature_quick_object_perms'] == 'y' ) {
	$groupNames = array();
	foreach($groups['data'] as $key=>$group) {
		$groupNames[] = $group['groupName'];
	}

	$map = $quickperms->getAppliedPermissions( $displayedPermissions, $groupNames );
		
	foreach($groups['data'] as $key=>$group) {
		$groups['data'][$key]['groupSumm'] = $map[ $group['groupName'] ];
	}
}
//Quickperm END

$smarty->assign_by_ref('groups', $groups["data"]);

// get groupNames etc

$permGroups = array();
$groupNames = array();
$groupIndices = array();
$groupInheritance = array();
$groupIndex = 6;	// yuk!
foreach($groups['data'] as $row) {
	$groupNames[] = $row['groupName'];
	$permGroups[] = 'perm['.$row['groupName'].']';
	if ($row['groupName'] != 'Anonymous' && $row['groupName'] != 'Admins') {
		$groupInheritance[] = array_merge(array('Anonymous'), $userlib->get_included_groups($row['groupName']));
	} else {
		$groupInheritance[] = '';
	}
	$groupIndices[] = $groupIndex;
	$groupIndex++;
}

$smarty->assign('permGroups', implode(',', $permGroups));
$smarty->assign('permGroupCols', $groupIndices);
$smarty->assign('groupNames', implode(',', $groupNames));
//$smarty->assign('groupInheritance', $groupInheritance);


// Get the big list of permissions
if (isset($_REQUEST['show_disabled_features']) && $_REQUEST['show_disabled_features'] == 'on') {
	$show_disabled_features = true;
} else {
	$show_disabled_features = false;
}
$smarty->assign('show_disabled_features', $show_disabled_features);
$perms = $userlib->get_permissions(0, -1, 'permName_asc', '', $_REQUEST["permType"], $groupNames, !$show_disabled_features);
$perms = $perms['data'];
foreach ($perms as &$perm) {
	$perm['label'] = $perm['permDesc'] . ' (' . $perm['permName'] . ')';
}

if ($tiki_p_admin_objects != 'y') {
	$userPerms = array();
	foreach($perms as $perm) {
		if ($userlib->user_has_permission($user, $perm['permName'])) {
			$userPerms[] = $perm;
		}
	}
	$perms = $userPerms;
}

foreach($page_perms as $i => $pp) {
	foreach($perms as $p) {
		if ($pp['permName'] == $p['permName']) {
			$page_perms[$i]['permDesc'] = $p['permDesc'];
			break;
		}
	}
}
$smarty->assign_by_ref('page_perms', $page_perms);
if ($prefs['feature_categories'] == 'y') {
	global $categlib;
	include_once ('lib/categories/categlib.php');
	// Get the permissions of the categories that this object belongs to,
	$categ_perms = array();
	$parents = $categlib->get_object_categories($_REQUEST['objectType'], $_REQUEST['objectId']);
	$perms_categ = $userlib->get_permissions(0, -1, 'permName_asc', '', 'category', $groupNames);
	foreach($parents as $categId) {
		if ($userlib->object_has_one_permission($categId, 'category')) {
			$categ_perm = $userlib->get_object_permissions($categId, 'category');
			$categ_perm[0]['catpath'] = $categlib->get_category_name($categId);
			$categ_perms[] = $categ_perm;
		} else {
			$categpath = $categlib->get_category_path($categId);
			$arraysize = count($categpath);
			$x = 0;
			for ($i = $arraysize - 2; $i >= 0; $i--) {
				if ($userlib->object_has_one_permission($categpath[$i]['categId'], 'category')) {
					$categ_perms[] = $userlib->get_object_permissions($categpath[$i]['categId'], 'category');
					$categ_perms[$x][0]['catpath'] = $categlib->get_category_name($categpath[$i]['categId']);
					$x++;
					break 1;
				}
			}
		}
	}
	foreach($categ_perms as $i => $p) {
		foreach($p as $j => $pp) {
			foreach($perms_categ['data'] as $ppp) {
				if ($ppp['permName'] == $pp['permName']) {
					$categ_perms[$i][$j]['permDesc'] = $ppp['permDesc'];
					break;
				}
			}
		}
	}
	$smarty->assign_by_ref('categ_perms', $categ_perms);
}

// object perms override globals (TODO - indicate globals somehow on the object perms list)
if ($_REQUEST["objectType"] != 'global') {
	foreach ($perms as &$perm) {
		for( $i = 0; $i < count($groupNames); $i++) {
			if ($perm[$groupNames[$i] . '_hasPerm'] == 'y') {
				$perm[$groupNames[$i] . '_hasPerm'] = 'n';
				$perm[$groupIndices[$i]] = 'n';
			}
		}
	}
}

// blend the perms from object onto the big perm list

foreach ($page_perms as $page_perm) {
	foreach ($perms as &$perm) {
		if ($perm['permName'] == $page_perm['permName']) {
			break;
		}
	}
	for( $i = 0; $i < count($groupNames); $i++) {
		if ($page_perm['groupName'] == $groupNames[$i]) {
			$perm[$groupNames[$i] . '_hasPerm'] = 'y';
			$perm[$groupIndices[$i]] = 'y';
		}
	}
}

$smarty->assign_by_ref('perms', $perms);

$js = '';

//for( $i = 0; $i < count($groupNames); $i++) {
//
//	$groupName = addslashes($groupNames[$i]);
//	$beneficiaries = '';
//	for( $j = 0; $j < count($groupInheritance); $j++) {
//		if (is_array($groupInheritance[$j]) && in_array($groupName, $groupInheritance[$j])) {
//			$beneficiaries .= !empty($beneficiaries) ? ',' : '';
//			$beneficiaries .='input[name="perm['. addslashes($groupNames[$j]).'][]"]';
//		}
//	}
//
//	
//	$js .= <<< JS
//\$jq('input[name="perm[$groupName][]"]').each( function() { 		// each one of this group
//
//	if (\$jq(this).attr('checked')) {
//		\$jq('input[value="'+\$jq(this).val()+'"]').					// other checkboxes of same value (perm)
//			filter('$beneficiaries').									// which inherit from this
//			attr('checked',\$jq(this).attr('checked')).					// check and disable
//			attr('disabled',\$jq(this).attr('checked') ? 'disabled' : '');
//	}
//		
//	\$jq(this).click( function() {									// bind click event
//	
//		if (\$jq(this).attr('checked')) {
//			\$jq('input[value="'+\$jq(this).val()+'"]').			// same...
//				filter('$beneficiaries').
//				attr('checked','checked').							// check?
//				attr('disabled','disabled');						// disable
//		} else {
//			\$jq('input[value="'+\$jq(this).val()+'"]').			// same...
//				filter('$beneficiaries').
//				attr('checked','').									// check?
//				attr('disabled','');								// disable
//}
//	});
//});
//
//JS;
//}	// end of for $groupNames loop

$maxGroupsToShow = 6;	// maybe a pref one day?
if (count($groupNames) >= $maxGroupsToShow) {
	$hideGroups = implode(',',array_keys(array_fill($maxGroupsToShow+1, count($groupNames)-$maxGroupsToShow, 1)));
	if (!isset($_COOKIE['columnManagerCtreetable_1'])) {
		$smarty->assign('groupsHidden', 'y');
	}
} else {
	$hideGroups = '';
}
$js .= "\$jq('#treetable_1').columnManager(".
	"{ listTargetID:'column_switches', onClass: 'advon', offClass: 'advoff', saveState: true, ".
	"hideInList: [".(count($groupNames) + 1)."], colsHidden: [".$hideGroups."]});\n";

$headerlib->add_jq_onready($js);

ask_ticket('object-perms');
// Display the template
$smarty->assign('mid', 'tiki-objectpermissions.tpl');
if (isset($_REQUEST['filegals_manager']) && $_REQUEST['filegals_manager'] != '') {
	$smarty->assign('filegals_manager', $_REQUEST['filegals_manager']);
	$smarty->display("tiki-print.tpl");
} else {
	$smarty->display("tiki.tpl");
}

function assign_perm($group, $objectId, $objectType, $perm) {
	global $userlib;
	if ($objectType == 'global') {
		$userlib->assign_permission_to_group($perm, $group);
	} else {
		$userlib->assign_object_permission($group, $objectId, $objectType, $perm);				
	}
}

function remove_perm($group, $objectId, $objectType, $perm) {
	global $userlib;
	if ($objectType == 'global') {
		$userlib->remove_permission_from_group($perm, $group);
	} else {
		$userlib->remove_object_permission($group, $objectId, $objectType, $perm);
	}
}

function get_assign_permissions() {
	$set = new Perms_Reflection_PermissionSet;

	if( isset( $_REQUEST['perm'] ) ) {
		foreach( $_REQUEST['perm'] as $group => $perms ) {
			foreach( $perms as $perm ) {
				$set->add( $group, $perm );
			}
		}
	}

	return $set;
}

function quickperms_get_data() {
	if($_REQUEST["permType"]=="file galleries") {
		return quickperms_get_filegal();
	} else {
		return quickperms_get_generic();
	}
}

function quickperms_get_filegal() {
	return array(
		'admin' => array(
			'name' => 'admin',
			'data' => array(
				'tiki_p_admin_file_galleries' => 'tiki_p_admin_file_galleries',
				'tiki_p_assign_perm_file_gallery' => 'tiki_p_assign_perm_file_gallery',
				'tiki_p_batch_upload_files' => 'tiki_p_batch_upload_files',
				'tiki_p_batch_upload_file_dir' => 'tiki_p_batch_upload_file_dir',
				'tiki_p_create_file_galleries' => 'tiki_p_create_file_galleries',
				'tiki_p_download_files' => 'tiki_p_download_files',
				'tiki_p_edit_gallery_file' => 'tiki_p_edit_gallery_file',
				'tiki_p_list_file_galleries' => 'tiki_p_list_file_galleries',
				'tiki_p_upload_files' => 'tiki_p_upload_files',
				'tiki_p_view_fgal_explorer' => 'tiki_p_view_fgal_explorer',
				'tiki_p_view_fgal_path' => 'tiki_p_view_fgal_path',
				'tiki_p_view_file_gallery' => 'tiki_p_view_file_gallery',
			),
		),
		'write' => array(
			'name' => 'write',
			'data' => array(
				'tiki_p_batch_upload_files' => 'tiki_p_batch_upload_files',
				'tiki_p_batch_upload_file_dir' => 'tiki_p_batch_upload_file_dir',
				'tiki_p_create_file_galleries' => 'tiki_p_create_file_galleries',
				'tiki_p_download_files' => 'tiki_p_download_files',
				'tiki_p_edit_gallery_file' => 'tiki_p_edit_gallery_file',
				'tiki_p_list_file_galleries' => 'tiki_p_list_file_galleries',
				'tiki_p_upload_files' => 'tiki_p_upload_files',
				'tiki_p_view_fgal_explorer' => 'tiki_p_view_fgal_explorer',
				'tiki_p_view_fgal_path' => 'tiki_p_view_fgal_path',
				'tiki_p_view_file_gallery' => 'tiki_p_view_file_gallery',
			),
		),
		'read' => array(
			'name' => 'read',
			'data' => array(
				'tiki_p_download_files' => 'tiki_p_download_files',
				'tiki_p_list_file_galleries' => 'tiki_p_list_file_galleries',
				'tiki_p_view_fgal_explorer' => 'tiki_p_view_fgal_explorer',
				'tiki_p_view_fgal_path' => 'tiki_p_view_fgal_path',
				'tiki_p_view_file_gallery' => 'tiki_p_view_file_gallery',
			),
		),
		'none' => array(
			'name' => 'none',
			'data' => array(
			),
		),
	);
}

function quickperms_get_generic() {
	global $userlib;

	$databaseperms = $userlib->get_permissions(0, -1, 'permName_asc', '', $_REQUEST["permType"], '', true);
	foreach($databaseperms['data'] as $perm) {
		if ($perm['level']=='basic')
			$quickperms_['basic'][$perm['permName']] = $perm['permName'];
		elseif ($perm['level']=='registered')
			$quickperms_['registered'][$perm['permName']] = $perm['permName'];
		elseif ($perm['level']=='editors')
			$quickperms_['editors'][$perm['permName']] = $perm['permName'];
		elseif ($perm['level']=='admin')
			$quickperms_['admin'][$perm['permName']] = $perm['permName'];
	}

	if(!isset($quickperms_['basic']))
		$quickperms_['basic'] = array();
	if(!isset($quickperms_['registered']))
		$quickperms_['registered'] = array();
	if(!isset($quickperms_['editors']))
		$quickperms_['editors'] = array();
	if(!isset($quickperms_['admin']))
	$quickperms_['admin'] = array();

	$perms = array();
	$perms['basic']['name'] = "basic";
	$perms['basic']['data'] = array_merge($quickperms_['basic']);
	$perms['registered']['name'] = "registered";
	$perms['registered']['data'] = array_merge($quickperms_['basic'], $quickperms_['registered']);
	$perms['editors']['name'] = "editors";
	$perms['editors']['data'] = array_merge($quickperms_['basic'], $quickperms_['registered'], $quickperms_['editors']);
	$perms['admin']['name'] = "admin";
	$perms['admin']['data'] = array_merge($quickperms_['basic'], $quickperms_['registered'], $quickperms_['editors'], $quickperms_['admin']);
	$perms['none']['name'] = "none";
	$perms['none']['data'] = array();

	return $perms;
}

