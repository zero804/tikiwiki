<?php
// $Id$
// Copyright (c) 2002-2007, Luis Argerich, Garland Foster, Eduardo Polidor, et. al.
// All Rights Reserved. See copyright.txt for details and a complete list of authors.
// Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See license.txt for details.
// Initialization

$inputConfiguration = array(
	array( 'staticKeyFilters' => array(
		'page' => 'pagename',
		'watch' => 'digits',
	) ),
	array( 'staticKeyUnset' => array(
		'edit',
	) ),
);

$section = "wiki page";
$section_class = "tiki_wiki_page manage";	// This will be body class instead of $section
require_once ('tiki-setup.php');
include_once ('lib/wiki/wikilib.php');
include_once ('lib/structures/structlib.php');
include_once ('lib/notifications/notificationlib.php');
if ($prefs['feature_ajax'] == 'y') {
	require_once ("lib/ajax/ajaxlib.php");
	require_once ("lib/wiki/wiki-ajax.php");
}
require_once ("lib/wiki/editlib.php");

// Define all templates files that may be used with the 'zoom' feature
$zoom_templates = array('wiki_edit');

if ($prefs['feature_wiki'] != 'y') {
	$smarty->assign('msg', tra('This feature is disabled').': feature_wiki');
	$smarty->display('error.tpl');
	die;
}

$smarty->assign( 'translation_mode', ($editlib->isNewTranslationMode() || $editlib->isUpdateTranslationMode()) ?'y':'n' );

// If page is blank (from quickedit module or wherever) tell user -- instead of editing the default page
// Dont get the page from default HomePage if not set (surely this would always be an error?)
if (empty($_REQUEST["page"])) { 
	$smarty->assign('msg', tra("You must specify a page name, it will be created if it doesn't exist."));
	$smarty->display("error.tpl");
	die;
}

if ($prefs['feature_wikiapproval'] == 'y' && substr($_REQUEST['page'], 0, strlen($prefs['wikiapproval_prefix'])) != $prefs['wikiapproval_prefix'] && !empty($prefs['wikiapproval_master_group']) && !in_array($prefs['wikiapproval_master_group'], $tikilib->get_user_groups($user))) {
	$_REQUEST['page'] = $prefs['wikiapproval_prefix'] . $_REQUEST['page'];
}

$page = $_REQUEST["page"];
$info = $tikilib->get_page_info($page);

$editlib->make_sure_page_to_be_created_is_not_an_alias($page, $info);

// wysiwyg decision
include 'lib/setup/editmode.php';

$auto_query_args = array('wysiwyg','page_id','page', 'lang', 'hdr');

$smarty->assign_by_ref('page', $_REQUEST["page"]);
// Permissions
$tikilib->get_perm_object($page, 'wiki page', $info, true);
if ($tiki_p_edit != 'y') {
	if (empty($user)) {
		global $cachelib; include_once('lib/cache/cachelib.php');
		$cacheName = $tikilib->get_ip_address().$tikilib->now;
		$cachelib->cacheItem($cacheName, http_build_query($_REQUEST, '', '&'), 'edit');
		$smarty->assign('urllogin', "tiki-editpage.php?cache=$cacheName");
	}
	$smarty->assign('errortype', 401);
	$smarty->assign('msg', tra("Permission denied you cannot edit this page"));
	$smarty->display("error.tpl");
	die;
}
// Anti-bot feature: if enabled, anon user must type in a code displayed in an image
if (isset($_REQUEST['save']) && (!$user || $user == 'anonymous') && $prefs['feature_antibot'] == 'y') {
	if((!isset($_SESSION['random_number']) || $_SESSION['random_number'] != $_REQUEST['antibotcode'])) {
		$smarty->assign('msg',tra("You have mistyped the anti-bot verification code; please try again."));
		$smarty->display("error.tpl");
		die;
	}
}

$page_ref_id = '';
if (isset($_REQUEST["page_ref_id"])) {
	$page_ref_id = $_REQUEST["page_ref_id"];
}

$smarty->assign('page_ref_id',$page_ref_id);
//Is new page to be inserted into structure?
if (isset($_REQUEST["current_page_id"])) {
	if (empty($_REQUEST['page'])) {
		$smarty->assign('msg', tra("You must specify a page name, it will be created if it doesn't exist."));
		$smarty->display("error.tpl");
		die;
	}

	if ($tikilib->page_exists($_REQUEST['page'])) {
		$smarty->assign('msg', $_REQUEST['page'] . " " . tra("page not added (Exists)"));
		$smarty->display("error.tpl");
		die;
	}

	$structure_info = $structlib->s_get_structure_info($_REQUEST['current_page_id']);
	if ($tiki_p_edit_structures != 'y' || !$tikilib->user_has_perm_on_object($user,$structure_info["pageName"],'wiki page','tiki_p_edit')) {
		$smarty->assign('errortype', 401);
		$smarty->assign('msg', tra("Permission denied you cannot edit this page"));
		$smarty->display("error.tpl");
		die;
	}

	$smarty->assign('current_page_id',$_REQUEST["current_page_id"]);
	if (isset($_REQUEST["add_child"])) {
		$smarty->assign('add_child', "true");
	}
} else {
	$smarty->assign('current_page_id',0);
	$smarty->assign('add_child', false);
}

function compare_import_versions($a1, $a2) {
	return $a1["version"] - $a2["version"];
}

if (isset($_REQUEST['cancel_edit'])) {
	if ($prefs['feature_wikiapproval'] == 'y' && substr($page, 0, strlen($prefs['wikiapproval_prefix'])) == $prefs['wikiapproval_prefix'] && !$tikilib->page_exists($page)) {
		$approvedPageName = substr($page, strlen($prefs['wikiapproval_prefix']));
		$page = $approvedPageName;
	}

	$tikilib->semaphore_unset($page, $_SESSION["edit_lock_$page"]);
	$url = "location:".$wikilib->sefurl($page);
	if (!empty($_REQUEST['page_ref_id'])) {
		$url .= '&page_ref_id='.$_REQUEST['page_ref_id'];
	}	

	if ($prefs['feature_best_language'] == 'y') {
		$url .= '&bl=n';
	}

	header($url);
	die;
}
if (isset($_REQUEST['minor'])) {
	$_REQUEST['isminor'] = 'on';
	$_REQUEST['save'] = true;
}

if( $user && $prefs['feature_user_watches'] == 'y' ) {
	$isFormSubmit = isset($jitRequest['edit']);
	if( $tikilib->page_exists($page) ) {
		$currentlyWatching = (bool) $tikilib->user_watches( $user, 'wiki_page_changed', $page, 'wiki page' );
	} else {
		// New pages get default watch checked for authors
		$currentlyWatching = ($prefs['wiki_watch_author'] == 'y');
	}

	$requestedWatch = isset($_REQUEST['watch']) && $isFormSubmit;
	$smarty->assign( 'show_watch', 'y' );
	$smarty->assign( 'watch_checked', ( ($currentlyWatching && !$isFormSubmit) || $requestedWatch) ? 'y' : 'n' );
} else {
	$currentlyWatching = false;
	$requestedWatch = false;
	$smarty->assign( 'show_watch_controls', 'n' );
}

if (isset($_REQUEST['partial_save'])) {
	$_REQUEST['save'] = true;
}

if (isset($_REQUEST['hdr'])) {
	$smarty->assign('hdr', $_REQUEST['hdr']);
}

if (isset($_REQUEST['pos'])) {
	$smarty->assign('pos', $_REQUEST['pos']);
}

if (isset($_REQUEST['cell'])) {
	$smarty->assign('cell', $_REQUEST['cell']);
}

// We set empty wiki page name as default here if not set (before including Tiki modules)
if ($prefs['feature_warn_on_edit'] == 'y') {
	$editpageconflict = 'n';
	$beingEdited = 'n';
	$semUser = '';
	$u = $user? $user: 'anonymous';
	if (!empty($page) && ($page != 'sandbox' || $page == 'sandbox' && $tiki_p_admin == 'y')) {
		if (!isset($_REQUEST['save'])) {
			if ($tikilib->semaphore_is_set($page, $prefs['warn_on_edit_time'] * 60) && $tikilib->get_semaphore_user($page) != $u) {
				$editpageconflict = 'y';
			} elseif ($tiki_p_edit == 'y') {
				$_SESSION["edit_lock_$page"] = $tikilib->semaphore_set($page);
			}
			$semUser = $tikilib->get_semaphore_user($page);
			$beingedited = 'y';
		} else {
			if (!empty($_SESSION["edit_lock_$page"])) {
				$tikilib->semaphore_unset($page, $_SESSION["edit_lock_$page"]);
			}
		}
	}
	if ($editpageconflict == 'y' && !isset($_REQUEST["conflictoverride"]) ) {
		include_once('lib/smarty_tiki/modifier.userlink.php');
		$msg = tra("This page is being edited by ") .
			smarty_modifier_userlink($semUser) . ". " . 
			tra("Please check with the user before editing the page,
			otherwise the changes will be stored as two separate versions in the history and
			you will have to manually merge them later. ") ;
		$msg .= '<br /><br /><a href="tiki-editpage.php?page=';
		$msg .= urlencode($page);
		$msg .= '&conflictoverride=y">' . tra('Override lock and carry on with edit') . '</a>';
		$smarty->assign('msg',$msg);
		$smarty->assign('errortitle',tra('Page is currently being edited'));
		$smarty->display("error.tpl");
		die;
	}
}
$category_needed = false;
$contribution_needed = false;
if (isset($_REQUEST['lock_it']) && $_REQUEST['lock_it'] =='on') {
	$lock_it = 'y';
} else {
	$lock_it = 'n';
}
if (isset($_REQUEST['comments_enabled']) && $_REQUEST['comments_enabled'] =='on') {
	$comments_enabled = 'y';
} else {
	$comments_enabled = 'n';
}
$hash = array();
$hash['lock_it'] = $lock_it;
$hash['comments_enabled'] = $comments_enabled;
if (!empty($_REQUEST['contributions'])) {
	$hash['contributions'] = $_REQUEST['contributions'];
}
if (!empty($_REQUEST['contributors'])) {
	$hash['contributors'] = $_REQUEST['contributors'];
}
if (isset($_FILES['userfile1']) && is_uploaded_file($_FILES['userfile1']['tmp_name'])) {
	check_ticket('edit-page');
	require ("lib/mail/mimelib.php");
	$fp = fopen($_FILES['userfile1']['tmp_name'], "rb");
	$data = '';
	while (!feof($fp)) {
		$data .= fread($fp, 8192 * 16);
	}
	fclose ($fp);
	$name = $_FILES['userfile1']['name'];
	$output = mime::decode($data);
	$parts = array();
	parse_output($output, $parts, 0);
	$last_part = '';
	$last_part_ver = 0;
	usort($parts, 'compare_import_versions');
	foreach ($parts as $part) {
		if ($part["version"] > $last_part_ver) {
			$last_part_ver = $part["version"];
			$last_part = $part["body"];
		}
		if (isset($part["pagename"])) {
			$pagename = urldecode($part["pagename"]);
			$version = urldecode($part["version"]);
			$author = urldecode($part["author"]);
			$lastmodified = $part["lastmodified"];
			if (isset($part["description"])) {
				$description = $part["description"];
			} else {
				$description = '';
			}
			$pageLang = isset($part["lang"])? $part["lang"]: "";
			$authorid = urldecode($part["author_id"]);
			if (isset($part["hits"]))
				$hits = urldecode($part["hits"]);
			else
				$hits = 0;
			$ex = substr($part["body"], 0, 25);
			//print(strlen($part["body"]));
			$msg = '';
			if (isset($_REQUEST['save']) && $prefs['feature_contribution'] == 'y' && $prefs['feature_contribution_mandatory'] == 'y' && (empty($_REQUEST['contributions']) || count($_REQUEST['contributions']) <= 0)) {
				$contribution_needed = true;
				$smarty->assign('contribution_needed', 'y');
			} else {
				$contribution_needed = false;
			}
			if (isset($_REQUEST['save']) && $prefs['feature_categories'] == 'y' && $prefs['feature_wiki_mandatory_category'] >=0 && (empty($_REQUEST['cat_categories']) || count($_REQUEST['cat_categories']) <= 0)) {
				$category_needed = true;
				$smarty->assign('category_needed', 'y');
			} else {
				$category_needed = false;
			}
			if (isset($_REQUEST["save"]) && !$category_needed && !$contribution_needed) {
				if (strtolower($pagename) != 'sandbox' || $tiki_p_admin == 'y') {
					$description = TikiFilter::get('striptags')->filter($description);
					if ($tikilib->page_exists($pagename)) {
						if ($prefs['feature_multilingual'] == 'y') {
							$info = $tikilib->get_page_info($pagename);
							if ($info['lang'] != $pageLang) {
								include_once("lib/multilingual/multilinguallib.php");
								if ($multilinguallib->updatePageLang('wiki page', $info['page_id'], $pageLang, true)){
									$pageLang = $info['lang'];
									$smarty->assign('msg', tra("The language can't be changed as its set of translations has already this language"));
									$smarty->display("error.tpl");
									die;
								}
							}
						}

						$tikilib->update_page($pagename, $part["body"], tra('page imported'), $author, $authorid, $description, 0, $pageLang, false, $hash);
					} else {
						$tikilib->create_page($pagename, $hits, $part["body"], $lastmodified, tra('created from import'), $author, $authorid, $description, $pageLang, false, $hash);
					}

					// Handle the translation bits after actual creation/update
					// This path is never used by minor updates
					if ($prefs['feature_multilingual'] == 'y') {
						include_once("lib/multilingual/multilinguallib.php");
						unset( $tikilib->cache_page_info );

						if ($prefs['feature_wikiapproval'] == 'y' && substr($page, 0, strlen($prefs['wikiapproval_prefix'])) == $prefs['wikiapproval_prefix']) {
							$oldpage = substr($page, strlen($prefs['wikiapproval_prefix']));
							$oldpageid = $tikilib->get_page_id_from_name($oldpage);
							$oldtrads = $multilinguallib->getTrads('wiki page', $oldpageid);
							foreach ($oldtrads as $ot) {
								$oldtradname = $prefs['wikiapproval_prefix'] . $tikilib->get_page_name_from_id($ot["objId"]);
								if ($ot["lang"] != $pageLang && $tikilib->page_exists($oldtradname)) {
									$multilinguallib->insertTranslation('wiki page', $tikilib->get_page_id_from_name($page), $pageLang, $tikilib->get_page_id_from_name($oldtradname), $ot["lang"]);
									break;									
								}							
							}
						}

						if( $editlib->isNewTranslationMode() ) {
							$sourceInfo = $tikilib->get_page_info( $_REQUEST['translationOf'] );
							$targetInfo = $tikilib->get_page_info( $pagename );

							if( !isset($_REQUEST['partial_save']) ) {
								$multilinguallib->propagateTranslationBits( 
										'wiki page',
										$sourceInfo['page_id'],
										$targetInfo['page_id'],
										$sourceInfo['version'],
										$targetInfo['version'] );
							}

						} elseif( $editlib->isUpdateTranslationMode() ) {
							$targetInfo = $tikilib->get_page_info( $pagename );

							if( !isset($_REQUEST['partial_save']) ) {
								$multilinguallib->propagateTranslationBits( 
										'wiki page',
										$_REQUEST['source_page'],
										$targetInfo['page_id'],
										(int) $_REQUEST['newver'],
										$targetInfo['version'] );
							}

						} else {
							$info = $tikilib->get_page_info( $pagename );
							$flags = array();
							if( isset( $_REQUEST['translation_critical'] ) ) {
								$flags[] = 'critical';
							}
							$multilinguallib->createTranslationBit( 'wiki page', $info['page_id'], $info['version'], $flags );
						}
					}
				}
			} else {
				$_REQUEST["edit"] = $last_part;
			}
		}
	}

	// If the watch state is not the same
	if( $requestedWatch !== $currentlyWatching ) {
		if( $requestedWatch ) {
			$tikilib->add_user_watch( $user, 'wiki_page_changed', $page, 'wiki page', $page, $wikilib->sefurl($page) );
		} else {
			$tikilib->remove_user_watch( $user, 'wiki_page_changed', $page, 'wiki page' );
		}
	}

	if (isset($_REQUEST["save"])) {
		unset ($_REQUEST["save"]);
		if ($page_ref_id) {
			$url = "tiki-index.php?page_ref_id=$page_ref_id";
		} else {
			$url = $wiki->sefurl($page);
		}
		if ($prefs['feature_best_language'] == 'y') {
			$url .= '&bl=n';
		}
		header("location: $url");
		die;
	}
}

$smarty->assign('category_needed',$category_needed);
$smarty->assign('contribution_needed',$contribution_needed);
$wiki_up = "img/wiki_up";
if ($tikidomain) { $wiki_up.= "/$tikidomain"; }
// Upload pictures here
if (($prefs['feature_wiki_pictures'] == 'y') && (isset($tiki_p_upload_picture)) && ($tiki_p_upload_picture == 'y')) {
	$i = 1;
	while ( isset($_FILES['picfile'.$i]) ) {
		if ( is_uploaded_file($_FILES['picfile'.$i]['tmp_name']) ) {
			$picname = $_FILES['picfile'.$i]['name'];
			if ( preg_match('/\.(gif|png|jpe?g)$/i',$picname) ) {
				if (@getimagesize($_FILES['picfile'.$i]['tmp_name'])) {
					move_uploaded_file($_FILES['picfile'.$i]['tmp_name'], "$wiki_up/$picname");
					chmod("$wiki_up/$picname", 0644); // seems necessary on some system (see move_uploaded_file doc on php.net)
				}
			}
		}
		$i++;
	}
}
if ($prefs['feature_wiki_attachments'] == 'y' && isset($_REQUEST["attach"]) && ($tiki_p_wiki_attach_files == 'y' || $tiki_p_wiki_admin_attachments == 'y')) {
	if (isset($_FILES['userfile2']) && is_uploaded_file($_FILES['userfile2']['tmp_name'])) {
		$ret = $tikilib->attach_file($_FILES['userfile2']['name'], $_FILES['userfile2']['tmp_name'], $prefs['w_use_db'] == 'y'? 'db': 'dir');
		if ($ret['ok']) {
			$wikilib->wiki_attach_file($page, $_FILES['userfile2']['name'], $_FILES['userfile2']['type'], $_FILES['userfile2']['size'], ($prefs['w_use_db'] == 'dir')?'': $ret['data'], $_REQUEST["attach_comment"], $user, $ret['fhash']);
		} else {
				$smarty->assign('msg', $ret['error']);
				$smarty->display("error.tpl");
				die();
		}
	}
}


// Suck another page and append to the end of current
$suck_url = isset($_REQUEST["suck_url"]) ? $_REQUEST["suck_url"] : '';
$parsehtml = isset ($_REQUEST["parsehtml"]) ? ($_REQUEST["parsehtml"] == 'on' ? 'y' : 'n') : ($info['is_html'] ? 'n' : 'y');
$smarty->assign('parsehtml', $parsehtml);
if (isset($_REQUEST['do_suck']) && strlen($suck_url) > 0)
{
	// \note by zaufi
	//   This is ugly implementation of wiki HTML import.
	//   I think it should be plugable import/export converters with ability
	//   to choose from edit form what converter to use for operation.
	//   In case of import converter, it can try to guess what source
	//   file is (using mime type from remote server response).
	//   Of couse converters may have itsown configuration panel what should be
	//   pluged into wiki page edit form too... (like HTML importer may have
	//   flags 'strip HTML tags' and 'try to convert HTML to wiki' :)
	//   At least one export filter for wiki already coded :) -- PDF exporter...
	$sdta = $tikilib->httprequest($suck_url);
	if (isset($php_errormsg) && strlen($php_errormsg))
	{
		$smarty->assign('msg', tra("Can't import remote HTML page"));
		$smarty->display("error.tpl");
		die;
	}
	// Need to parse HTML?
	if ($parsehtml == 'y') {
		$sdta = $editlib->parse_html($sdta);
	}
	$_REQUEST['edit'] = $jitRequest['edit'] . $sdta;
}
// if "UserPage" complete with the user name
if ($prefs['feature_wiki_userpage'] == 'y' && $tiki_p_admin != 'y' && $page == $prefs['feature_wiki_userpage_prefix']) {
	$page .= $user;
	$_REQUEST['page'] = $page;
}

if (strtolower($_REQUEST["page"]) == 'sandbox' && $prefs['feature_sandbox'] != 'y') {
	$smarty->assign('msg', tra("The SandBox is disabled"));
	$smarty->display("error.tpl");
	die;
}

if (!isset($_REQUEST["comment"])) {
	$_REQUEST["comment"] = '';
}

// Get page data
if(isset($info['wiki_cache'])) {
	$prefs['wiki_cache'] = $info['wiki_cache'];
	$smarty->assign('wiki_cache',$prefs['wiki_cache']);
}

if ($info["flag"] == 'L' && !$wikilib->is_editable($page, $user, $info)) {
	$smarty->assign('msg', tra("Cannot edit page because it is locked"));
	$smarty->display("error.tpl");
	die;
}

$smarty->assign('editable','y');
$smarty->assign('show_page','n');
$smarty->assign('comments_show','n');

$smarty->assign_by_ref('data', $info);
$smarty->assign('footnote', '');
$smarty->assign('has_footnote', 'n');
if ($prefs['feature_wiki_footnotes'] == 'y') {
	if ($user) {
		$x = $wikilib->get_footnote($user, $page);
		$footnote = $wikilib->get_footnote($user, $page);
		$smarty->assign('footnote', $footnote);
		if ($footnote)
			$smarty->assign('has_footnote', 'y');
		$smarty->assign('parsed_footnote', $tikilib->parse_data($footnote));
		if (isset($_REQUEST['footnote'])) {
			check_ticket('edit-page');
			$smarty->assign('parsed_footnote', $tikilib->parse_data($_REQUEST['footnote']));
			$smarty->assign('footnote', $_REQUEST['footnote']);
			$smarty->assign('has_footnote', 'y');
			if (empty($_REQUEST['footnote'])) {
				$wikilib->remove_footnote($user, $page);
			} else {
				$wikilib->replace_footnote($user, $page, $_REQUEST['footnote']);
			}
		}
	}
}
if (isset($_REQUEST["templateId"]) && $_REQUEST["templateId"] > 0 && !isset($_REQUEST['preview']) && !isset($_REQUEST['save'])) {
	$template_data = $tikilib->get_template($_REQUEST["templateId"]);
	$_REQUEST["edit"] = $template_data["content"]."\n".$_REQUEST["edit"];
	$smarty->assign("templateId", $_REQUEST["templateId"]);
}

if (isset($_REQUEST["categId"]) && $_REQUEST["categId"] > 0) {
	$categs = split("\+",$_REQUEST["categId"]);
	$smarty->assign('categIds',$categs);
	$smarty->assign('categIdstr',$_REQUEST["categId"]);
} else {
	$smarty->assign('categIds',array());
	$smarty->assign('categIdstr',0);
}

if (isset($_REQUEST["ratingId"]) && $_REQUEST["ratingId"] > 0) {
	$smarty->assign("poll_template",$_REQUEST["ratingId"]);
} else {
	$smarty->assign("poll_template",0);
}

if(isset($_REQUEST["edit"])) {
	$edit_data = $_REQUEST["edit"];
} else {
	if (isset($info['draft'])) {
		$edit_data = $info['draft']['data'];
	} elseif (isset($info["data"])) {
		if ((isset($_REQUEST['hdr']) || (!empty($_REQUEST['pos']) && isset($_REQUEST['cell']))) && $prefs['wiki_edit_section'] == 'y') {
			if (isset($_REQUEST['hdr'])) {
				if ($_REQUEST['hdr'] == 0) {
					list($real_start, $real_len) = $tikilib->get_wiki_section($info['data'], 1);
					$real_len = $real_start;
					$real_start = 0;
				} else {
					list($real_start, $real_len) = $tikilib->get_wiki_section($info['data'], $_REQUEST['hdr']);
				}
			} else {
				include_once('lib/wiki-plugins/wikiplugin_split.php');
				list($real_start, $real_len) = wikiplugin_split_cell($info['data'], $_REQUEST['pos'], $_REQUEST['cell']);
			}
			$edit_data = substr($info['data'], $real_start, $real_len);
		} else {
			$edit_data = $info['data'];
		}
	} elseif ($prefs['feature_wikiapproval'] == 'y' && substr($page, 0, strlen($prefs['wikiapproval_prefix'])) == $prefs['wikiapproval_prefix'] && !$tikilib->page_exists($page)) {
	// Handle first creation of staging copy 
	$oldpage = substr($page, strlen($prefs['wikiapproval_prefix']));	
	// Get page data
		if ($tikilib->page_exists($oldpage)) {
			$oldinfo = $tikilib->get_page_info($oldpage);
			$edit_data = $oldinfo["data"];
			$edit_lang = $oldinfo["lang"];
		} else {
			$edit_data = '';
		}
	} else {
		$edit_data = '';
	}
}

$likepages = '';
$smarty->assign_by_ref('likepages', $likepages);
if ($prefs['feature_likePages'] == 'y' and $edit_data == '' && !$tikilib->page_exists($page)) {
	$likepages = $wikilib->get_like_pages($page);
}
	
if (isset($prefs['wiki_feature_copyrights']) && $prefs['wiki_feature_copyrights'] == 'y') {
	if (isset($_REQUEST['copyrightTitle'])) {
		$smarty->assign('copyrightTitle', $_REQUEST["copyrightTitle"]);
	}
	if (isset($_REQUEST['copyrightYear'])) {
		$smarty->assign('copyrightYear', $_REQUEST["copyrightYear"]);
	}
	if (isset($_REQUEST['copyrightAuthors'])) {
		$smarty->assign('copyrightAuthors', $_REQUEST["copyrightAuthors"]);
	}
}

if (isset($_REQUEST["comment"])) {
	$smarty->assign_by_ref('commentdata', $_REQUEST["comment"]);
} elseif (isset($info['draft'])) {
	$smarty->assign_by_ref('commentdata',$info['draft']['data']);
} else {
	$smarty->assign('commentdata', '');
}
if (isset($info["description"])) {
	if (isset($info['draft'])) {
		$info['description'] = $info['draft']['description'];
	}
	$smarty->assign('description', $info["description"]);
	$description = $info["description"];
} else {
	$smarty->assign('description', '');
	$description = '';
}
if(isset($_REQUEST["description"])) {
	$smarty->assign_by_ref('description',$_REQUEST["description"]);
	$description = $_REQUEST["description"];
}

$wiki_authors_style = '';
if ( $prefs['wiki_authors_style_by_page'] == 'y' ) {
	if ( isset($_REQUEST['wiki_authors_style']) && $tiki_p_admin_wiki == 'y' ) {
		$wiki_authors_style = $_REQUEST['wiki_authors_style'];
	} elseif ( isset($info['wiki_authors_style']) ) {
		$wiki_authors_style = $info['wiki_authors_style'];
	}
	$smarty->assign('wiki_authors_style', $wiki_authors_style);
}

if($is_html) {
	$smarty->assign('allowhtml','y');
} else {
	$edit_data = str_replace( '<x>', '', $edit_data );
	$smarty->assign('allowhtml','n');
}
if (empty($_REQUEST['lock_it']) && !empty($info['flag']) && $info['flag'] == 'L') {
	$lock_it = 'y';
}
$smarty->assign_by_ref('lock_it', $lock_it);
if ($prefs['wiki_comments_allow_per_page'] != 'n') {
	if (!isset($_REQUEST['save']) && !isset($_REQUEST['preview'])) {
		if (!empty($info) && !empty($info['comments_enabled'])) {
			$comments_enabled =  $info['comments_enabled'];
		} else {
			if ($prefs['wiki_comments_allow_per_page'] == 'y') {
				$comments_enabled = 'y';
			} else {
				$comments_enabled = 'n';
			}
		}
	}
	$smarty->assign_by_ref('comments_enabled', $comments_enabled);
}
if (isset($_REQUEST["lang"])) {
	if ($prefs['feature_multilingual'] == 'y' && isset($info["lang"]) && $info['lang'] != $_REQUEST["lang"]) {
		include_once("lib/multilingual/multilinguallib.php");
		if ($multilinguallib->updatePageLang('wiki page', $info['page_id'], $_REQUEST["lang"], true)) {
			$pageLang = $info['lang'];
			$smarty->assign('msg', tra("The language can't be changed as its set of translations has already this language"));
			$smarty->display("error.tpl");
			die;
		}
	}
	$pageLang = $_REQUEST["lang"];
} elseif (isset($info["lang"])) {
	$pageLang = $info["lang"];
} elseif (isset($edit_lang)) {
	$pageLang = $edit_lang;
} else {
	$pageLang = "";
}

$smarty->assign('lang', $pageLang);
if( isset( $_REQUEST['translation_critical'] ) ) {
	$smarty->assign( 'translation_critical', 1 );
} else {
	$smarty->assign( 'translation_critical', 0 );
}

// Screencasts {{{
if (($prefs['feature_wiki_screencasts'] == 'y') && (isset($tiki_p_upload_screencast)) && ($tiki_p_upload_screencast == 'y')) {
	if ( !isset($headerlib) || !is_object($headerlib) ) {
		include_once("lib/headerlib.php");
	}
	$headerlib->add_jsfile('lib/wikiplugin_screencast.js');

	require_once("lib/screencasts/screencastlib.php");

	if ( !isset($cachelib) || !is_object($cachelib) )
		require_once("lib/cache/cachelib");

	// Get a page hash identical to what images are assigned
	$pageHash = md5( $pageLang . '/' . ( (strpos($page,$prefs['wikiapproval_prefix'])===0) ? substr($page,1) : $page) );
	$hashedFileName = join('-', array($pageHash, time(), rand(1,1000)));

	$screencastErrors = array();

	if ( isset($_FILES['flash_screencast']) ) {
		$cachelib->invalidate($pageHash);

		for ( $i = 0; $i <= count($_FILES['flash_screencast']['name']); $i++ ) {

			if ( $_FILES['flash_screencast']['size'][$i] > $prefs['feature_wiki_screencasts_max_size'] ||
					$_FILES['flash_screencast']['error'][$i] == 1 || $_FILES['flash_screencast']['error'][$i] == 2 ) {

				$screencastErrors[] = tra("The file you selected is too large to upload") . ' (' . htmlentities($_FILES['flash_screencast']['name'][$i], ENT_QUOTES) . ')';
				continue;
			}

			if ( is_uploaded_file($_FILES['flash_screencast']['tmp_name'][$i]) ) {
				if ( preg_match("/\.((swf)|(flv))$/", $_FILES['flash_screencast']['name'][$i], $ext) ) {
					if ( !$screencastlib->add($_FILES['flash_screencast']['tmp_name'][$i], $hashedFileName . "-" . $i . "." . $ext[1] ) ) {
						$screencastErrors[] = tra("An unexpected error occurred while uploading your flash screencast!");
					}
				} else {
					$screencastErrors[] = tra("Incorrect file extension was used for your flash screencast, expecting .swf or .flv");     
				}

				if ( isset($_FILES['ogg_screencast']) && $_FILES['ogg_screencast']['name'][$i]) {
					if ( $_FILES['ogg_screencast']['size'][$i] >= $prefs['feature_wiki_screencasts_max_size'] ||
						$_FILES['ogg_screencast']['error'][$i] == 1 || $_FILES['ogg_screencast']['error'][$i] == 2 ) {

						$screencastErrors[] = tra("The file you selected is too large to upload") . ' (' . htmlentities($_FILES['ogg_screencast']['name'][$i], ENT_QUOTES) . ')';
							continue;
					}

					if ( is_uploaded_file($_FILES['ogg_screencast']['tmp_name'][$i]) ) { 
						if ( preg_match("/\.(ogg)$/", $_FILES['ogg_screencast']['name'][$i], $ext) ) {
							if ( !$screencastlib->add($_FILES['ogg_screencast']['tmp_name'][$i], $hashedFileName . "-" . $i . "." .  $ext[1])) {
								$screencastErrors[] = tra("An unexpected error occurred while uploading your Ogg screencast!");
							}
						} else {
							$screencastErrors[] = tra("Incorrect file extension was used for your 0gg screencast, expecting .ogg");
						}
					}
				}
			}
		}
	}

	if ( $cachelib->isCached($pageHash) ) {
		$screencasts_uploaded = unserialize($cachelib->getCached($pageHash));
	} else {
		$screencasts_uploaded = $screencastlib->find($pageHash, true);
		$cachelib->cacheItem($pageHash, serialize($screencasts_uploaded));
	}

	$smarty->assign('screencasts_uploaded', $screencasts_uploaded);

	if ( count($screencastErrors) > 0 ) {
		$smarty->assign('screencasts_errors', array_unique($screencastErrors));
	}
} // }}}

// Parse (or not) $edit_data into $parsed
// Handles switching editor modes
if (isset($_REQUEST['mode_normal']) && $_REQUEST['mode_normal']=='y') {
	// Parsing page data as first time seeing html page in normal editor
	$smarty->assign('msg', "Parsing html to wiki");
	$parsed = $editlib->parseToWiki($edit_data);
	$is_html = false;
	$info['is_html'] = false;
	$info['wysiwyg'] = false;
	$smarty->assign('allowhtml','n');
	
} elseif (isset($_REQUEST['mode_wysiwyg']) && $_REQUEST['mode_wysiwyg']=='y') {
	// Parsing page data as first time seeing wiki page in wysiwyg editor
	$smarty->assign('msg', "Parsing wiki to html");
	$secedit = $prefs['wiki_edit_section'];
	$prefs['wiki_edit_section'] = 'n';		// get rid of the section edit icons
	$exticons = $prefs['feature_wiki_ext_icon'];
	$prefs['feature_wiki_ext_icon'] = 'n';		// and the external link icons
	$editplugin = $prefs['wiki_edit_plugin'];
	$prefs['wiki_edit_plugin'] = 'n';		// and the external link icons
	$parsed = $editlib->parseToWysiwyg($edit_data);
	$smarty->assign('pagedata', $parsed);
	$prefs['wiki_edit_section'] = $secedit;
	$prefs['feature_wiki_ext_icon'] = $exticons;
	$prefs['wiki_edit_plugin'] = $editplugin;
	$is_html = true;
	$info['is_html'] = true;
	$info['wysiwyg'] = true;
	$smarty->assign('allowhtml','y');
}
if (empty($parsed)) {
	if ( ! isset($_REQUEST['edit']) && ! $is_html ) {
		// When we get data from database (i.e. we are not in preview mode) and if we don't allow HTML,
		//   then we need to convert database's HTML entities into their "normal chars" equivalents
		$parsed = TikiLib::htmldecode($edit_data);
	} else {
		$parsed = $edit_data;
	}
}
$smarty->assign('pagedata', $parsed);

// apply the optional post edit filters before preview
if(isset($_REQUEST["preview"]) || ($prefs['wiki_spellcheck'] == 'y' && isset($_REQUEST["spellcheck"]) && $_REQUEST["spellcheck"] == 'on')) {
	$parsed = $tikilib->apply_postedit_handlers($parsed);
	$parsed = $tikilib->parse_data($parsed, array('is_html' => $is_html, 'preview_mode'=>true));
} else {
	$parsed = "";
}

/* SPELLCHECKING INITIAL ATTEMPT */
//This nice function does all the job!
if ($prefs['wiki_spellcheck'] == 'y') {
	if (isset($_REQUEST["spellcheck"]) && $_REQUEST["spellcheck"] == 'on') {
		$parsed = $tikilib->spellcheckreplace($edit_data, $parsed, $prefs['language'], 'editwiki');
		$smarty->assign('spellcheck', 'y');
	} else {
		$smarty->assign('spellcheck', 'n');
	}
}

$smarty->assign_by_ref('parsed', $parsed);
$smarty->assign('preview',0);
// If we are in preview mode then preview it!
if(isset($_REQUEST["preview"])) {
	$smarty->assign('preview',1);
}

function parse_output(&$obj, &$parts,$i) {
	if(!empty($obj['parts'])) {
		foreach( $obj['parts'] as $index => $part ) {
			parse_output($part, $parts,$index);
		}
	}elseif( $obj['type'] == 'application/x-tikiwiki' ) {
		$aux["body"] = $obj['body'];
		$ccc=$obj['header']["content-type"];
		$items = split(';',$ccc);
		foreach($items as $item) {
			$portions = split('=',$item);
			if(isset($portions[0])&&isset($portions[1])) {
				$aux[trim($portions[0])]=trim($portions[1]);
			}
		}
		$parts[]=$aux;
	}
}
// Pro
// Check if the page has changed
$pageAlias = '';
$cat_type='wiki page';
$cat_objid = $_REQUEST["page"];
if (isset($_REQUEST['save']) && $prefs['feature_contribution'] == 'y' && $prefs['feature_contribution_mandatory'] == 'y' && (empty($_REQUEST['contributions']) || count($_REQUEST['contributions']) <= 0)) {
	$contribution_needed = true;
	$smarty->assign('contribution_needed', 'y');
} else {
	$contribution_needed = false;
}
if (isset($_REQUEST['save']) && $prefs['feature_categories'] == 'y' && $prefs['feature_wiki_mandatory_category'] >=0 && (empty($_REQUEST['cat_categories']) || count($_REQUEST['cat_categories']) <= 0)) {
	$category_needed = true;
	$smarty->assign('category_needed', 'y');
} else {
	$category_needed = false;
}	
if (isset($_REQUEST["save"]) && (strtolower($_REQUEST['page']) != 'sandbox' || $tiki_p_admin == 'y') && !$category_needed && !$contribution_needed) {
	check_ticket('edit-page');
	// Check if all Request values are delivered, and if not, set them
	// to avoid error messages. This can happen if some features are
	// disabled
	if(!isset($_REQUEST["description"])) $_REQUEST["description"]='';
	if(!isset($_REQUEST["wiki_authors_style"])) $_REQUEST["wiki_authors_style"]='';
	if(!isset($_REQUEST["comment"])) $_REQUEST["comment"]='';
	if(!isset($_REQUEST["lang"])) $_REQUEST["lang"]='';
	if(!isset($_REQUEST['wysiwyg'])) $_REQUEST['wysiwyg'] = '';
	if(isset($_REQUEST['wiki_cache'])) {
		$wikilib->set_page_cache($_REQUEST['page'],$_REQUEST['wiki_cache']);
	}
	include_once("lib/imagegals/imagegallib.php");
	$cat_desc = ($prefs['feature_wiki_description'] == 'y') ? substr($_REQUEST["description"],0,200) : '';
	$cat_name = $_REQUEST["page"];
	$cat_href="tiki-index.php?page=".urlencode($cat_objid);
	$cat_lang = $_REQUEST['lang'];
	$cat_object_exists = $tikilib->page_exists( $_REQUEST['page'] );
	include_once("categorize.php");
	include_once("poll_categorize.php");
	include_once("freetag_apply.php");
	$page = $_REQUEST["page"];
	if($is_html) {
		$edit = $_REQUEST["edit"];
	} else {
		$edit = htmlspecialchars($_REQUEST['edit']);
	}
	// add permisions here otherwise return error!
	if(
		isset($prefs['wiki_feature_copyrights']) 
		&& $prefs['wiki_feature_copyrights'] == 'y'
		&& isset($_REQUEST['copyrightTitle'])
		&& isset($_REQUEST['copyrightYear'])
		&& isset($_REQUEST['copyrightAuthors'])
		&& !empty($_REQUEST['copyrightYear'])
		&& !empty($_REQUEST['copyrightTitle']) 
	){

		include_once("lib/copyrights/copyrightslib.php");
		$copyrightslib = new CopyrightsLib;
		$copyrightYear = $_REQUEST['copyrightYear'];
		$copyrightTitle = $_REQUEST['copyrightTitle'];
		$copyrightAuthors = $_REQUEST['copyrightAuthors'];
		$copyrightslib->add_copyright($page,$copyrightTitle,$copyrightYear,$copyrightAuthors,$user);
	}

	// Parse $edit and eliminate image references to external URIs (make them internal)
	$edit = $imagegallib->capture_images($edit);
	// apply the optional page edit filters before data storage
	$edit = $tikilib->apply_postedit_handlers($edit);
	$exist = $tikilib->page_exists($_REQUEST['page']);
	if (!$exist && $prefs['feature_wikiapproval'] == 'y' && $prefs['wikiapproval_delete_staging'] == 'y' && substr($_REQUEST['page'], 0, strlen($prefs['wikiapproval_prefix'])) == $prefs['wikiapproval_prefix']) { //needs to create the first history = initial page for history
		$approvedPageName = substr($_REQUEST['page'], strlen($prefs['wikiapproval_prefix']));
		if ($tikilib->page_exists($approvedPageName)) {
			$wikilib->duplicate_page($approvedPageName, $_REQUEST['page']);
			$exist = true;
		}
	}
	// If page exists
	if(!$exist) {
		// Extract links and update the page
		$links = $tikilib->get_links($_REQUEST["edit"]);
		/*
		   $notcachedlinks = $tikilib->get_links_nocache($_REQUEST["edit"]);
		   $cachedlinks = array_diff($links, $notcachedlinks);
		   $tikilib->cache_links($cachedlinks);
		 */
		$tikilib->create_page($_REQUEST["page"], 0, $edit, $tikilib->now, $_REQUEST["comment"],$user,$tikilib->get_ip_address(),$description, $pageLang, $is_html, $hash, $_REQUEST['wysiwyg'], $wiki_authors_style);

		$info_new = $tikilib->get_page_info($page);

		if( $editlib->isNewTranslationMode() && ! empty( $pageLang ) )
		{
			include_once("lib/multilingual/multilinguallib.php");
			$infoSource = $tikilib->get_page_info($_REQUEST['translationOf']);
			$infoCurrent = $tikilib->get_page_info($_REQUEST['page']);
			if ($multilinguallib->insertTranslation('wiki page', $infoSource['page_id'], $infoSource['lang'], $infoCurrent['page_id'], $pageLang)){
				$pageLang = $info['lang'];
				$smarty->assign('msg', tra("The language can't be changed as its set of translations has already this language"));
				$smarty->display("error.tpl");
				die;
			}
		}
		if ($prefs['feature_multilingual'] == 'y') {
			include_once("lib/multilingual/multilinguallib.php");

			if ($prefs['feature_wikiapproval'] == 'y' && substr($page, 0, strlen($prefs['wikiapproval_prefix'])) == $prefs['wikiapproval_prefix']) {
				$oldpage = substr($page, strlen($prefs['wikiapproval_prefix']));
				$oldpageid = $tikilib->get_page_id_from_name($oldpage);
				$oldtrads = $multilinguallib->getTrads('wiki page', $oldpageid);
				foreach ($oldtrads as $ot) {
					$oldtradname = $prefs['wikiapproval_prefix'] . $tikilib->get_page_name_from_id($ot["objId"]);
					if ($ot["lang"] != $pageLang && $tikilib->page_exists($oldtradname)) {
						$multilinguallib->insertTranslation('wiki page', $tikilib->get_page_id_from_name($page), $pageLang, $tikilib->get_page_id_from_name($oldtradname), $ot["lang"]);
						break;									
					}							
				}
			}

			unset( $tikilib->cache_page_info );
			if( $editlib->isNewTranslationMode() ) {
				$sourceInfo = $tikilib->get_page_info( $_REQUEST['translationOf'] );
				$targetInfo = $tikilib->get_page_info( $_REQUEST['page'] );

				if( !isset($_REQUEST['partial_save']) ) {
					$multilinguallib->propagateTranslationBits( 
							'wiki page',
							$sourceInfo['page_id'],
							$targetInfo['page_id'],
							$sourceInfo['version'],
							$targetInfo['version'] );
				}

			} else {
				$info = $tikilib->get_page_info( $_REQUEST['page'] );
				$multilinguallib->createTranslationBit( 'wiki page', $info['page_id'], 1 );
			}
		}
	} else {
		$links = $tikilib->get_links($edit);
		/*
		   $tikilib->cache_links($links);
		 */
		$minor=(isset($_REQUEST['isminor'])&&$_REQUEST['isminor']=='on') ? 1 : 0;

		if ((isset($_REQUEST['hdr']) || (!empty($_REQUEST['pos']) && isset($_REQUEST['cell']))) && $prefs['wiki_edit_section'] == 'y') {
			if (isset($_REQUEST['hdr'])) {
				if ($_REQUEST['hdr'] == 0) {
					list($real_start, $real_len) = $tikilib->get_wiki_section($info['data'], 1);
					$real_len = $real_start;
					$real_start = 0;
				} else {
					list($real_start, $real_len) = $tikilib->get_wiki_section($info['data'], $_REQUEST['hdr']);
				}
			} else {
				include_once('lib/wiki-plugins/wikiplugin_split.php');
				list($real_start, $real_len) = wikiplugin_split_cell($info['data'], $_REQUEST['pos'], $_REQUEST['cell']);
			}
			if ($edit[strlen($edit) - 1] != "\n")
				$edit .= "\r\n";
			$edit = substr($info['data'], 0, $real_start).$edit.substr($info['data'], $real_start + $real_len);
		}
		if (isset($_REQUEST['wysiwyg']) && $_REQUEST['wysiwyg'] == 'y' && $prefs['wysiwyg_wiki_parsed'] == 'y') {//take away the <p> that fck introduces around wiki heading ! to have maketoc/edit section working
			$edit = preg_replace('/<p>!(.*)<\/p>/u', "!$1\n", $edit);
		}
		$tikilib->update_page($_REQUEST["page"],$edit,$_REQUEST["comment"],$user,$tikilib->get_ip_address(),$description,$minor,$pageLang, $is_html, $hash, null, $_REQUEST['wysiwyg'], $wiki_authors_style);
		$info_new = $tikilib->get_page_info($page);

		// Handle translation bits
		if ($prefs['feature_multilingual'] == 'y' && !$minor) {
			include_once("lib/multilingual/multilinguallib.php");
			unset( $tikilib->cache_page_info );

			if( $editlib->isUpdateTranslationMode() ) {
				$sourceInfo = $tikilib->get_page_info( $_REQUEST['source_page'] );
				$targetInfo = $tikilib->get_page_info( $_REQUEST['page'] );

				if( !isset($_REQUEST['partial_save']) ) {
					$multilinguallib->propagateTranslationBits( 
							'wiki page',
							$sourceInfo['page_id'],
							$targetInfo['page_id'],
							(int) $_REQUEST['newver'],
							$targetInfo['version'] );
				}

			} else {
				$info = $tikilib->get_page_info( $_REQUEST['page'] );
				$flags = array();
				if( isset( $_REQUEST['translation_critical'] ) ) {
					$flags[] = 'critical';
				}
				$multilinguallib->createTranslationBit( 'wiki page', $info['page_id'], $info['version'], $flags );
			}
		}
	}
	//Page may have been inserted from a structure page view
	if (isset($_REQUEST['current_page_id']) ) {
		$page_info = $structlib->s_get_page_info($_REQUEST['current_page_id']);
		$pageAlias = $page_info['page_alias'];
		if (isset($_REQUEST["add_child"]) ) {
			//Insert page after last child of current page
			$subpages = $structlib->s_get_pages($_REQUEST["current_page_id"]);
			$max = count($subpages);
			$last_child_ref_id = null;
			if ($max != 0) {
				$last_child = $subpages[$max - 1];
				$last_child_ref_id = $last_child["page_ref_id"];
			}
			$page_ref_id = $structlib->s_create_page($_REQUEST['current_page_id'], $last_child_ref_id, $_REQUEST["page"], '', $page_info['structure_id']);
		} else {
			//Insert page after current page
			$page_ref_id = $structlib->s_create_page($page_info["parent_id"], $_REQUEST['current_page_id'], $_REQUEST["page"], '', $page_info['structure_id']);
		}
		//Criss Holman added the if containing this code of which I don't know the use, but a check before the permissions copy
		//is definitely needed in case someone has tiki_p_edit/tiki_p_admin_wiki in a page belonging to a structure. chealer
		if ($tikilib->user_has_perm_on_object($user, $_REQUEST["page"],'wiki page', 'tiki_p_admin_wiki', 'tiki_p_admin_categories'))
			$userlib->copy_object_permissions($page_info["pageName"], $_REQUEST["page"],'wiki page');
	} 

	// If the watch state is not the same
	if( $requestedWatch !== $currentlyWatching ) {
		if( $requestedWatch ) {
			$tikilib->add_user_watch( $user, 'wiki_page_changed', $page, 'wiki page', $page, $wikilib->sefurl($page) );
		} else {
			$tikilib->remove_user_watch( $user, 'wiki_page_changed', $page, 'wiki page' );
		}
	}

	if ($page_ref_id) {
		$url = "tiki-index.php?page_ref_id=$page_ref_id";
	} else {
		$url = $wikilib->sefurl($page);
	}
	if ($prefs['feature_best_language'] == 'y') {
		$url .= '&bl=n';
	}
	$_SESSION['saved_msg'] = $_REQUEST["page"];

	if (!empty($_REQUEST['hdr'])) {
		$tmp = $tikilib->parse_data($edit);			// fills $anch[] so page refreshes at the section being edited
		$url .= "#".$anch[$_REQUEST['hdr']-1]['id'];
	}
	header("location: $url");
	die;
} //save
$smarty->assign('pageAlias',$pageAlias);
if ($prefs['feature_wiki_templates'] == 'y' && $tiki_p_use_content_templates == 'y') {
	$templates = $tikilib->list_templates('wiki', 0, -1, 'name_asc', '');
	$smarty->assign_by_ref('templates', $templates["data"]);
}
if ($prefs['feature_polls'] =='y' and $prefs['feature_wiki_ratings'] == 'y' && $tiki_p_wiki_admin_ratings == 'y') {
	function pollnameclean($s) { global $page; if (isset($s['title'])) $s['title'] = substr($s['title'],strlen($page)+2); return $s; }
	if (!isset($polllib) or !is_object($polllib)) include("lib/polls/polllib_shared.php");
	if (!isset($categlib) or !is_object($categlib)) include("lib/categories/categlib.php");
	if (isset($_REQUEST['removepoll'])) {
		$catObjectId = $categlib->is_categorized($cat_type,$cat_objid);
		$polllib->remove_object_poll($cat_type,$cat_objid);
	}
	$polls_templates = $polllib->get_polls('t');
	$smarty->assign('polls_templates',$polls_templates['data']);
	$poll_rated = $polllib->get_rating($cat_type,$cat_objid);
	if (isset($poll_rated['title'])) {
		$poll_rated = array_map('pollnameclean',$poll_rated);
	}
	$smarty->assign('poll_rated',$poll_rated);
	if (isset($_REQUEST['poll_title'])) {
		$smarty->assign('poll_title',$_REQUEST['poll_title']);
	}
	if (isset($_REQUEST['poll_template'])) {
		$smarty->assign('poll_template',$_REQUEST['poll_template']);
	}
	$listpolls = $polllib->get_polls('o',"$page: ");
	$smarty->assign('listpolls',$listpolls['data']);
}

if ($prefs['feature_multilingual'] == 'y') {
	$languages = array();
	$languages = $tikilib->list_languages();
	$smarty->assign_by_ref('languages', $languages);

	if( $editlib->isNewTranslationMode() ) {
		$smarty->assign( 'translationOf', $_REQUEST['translationOf'] );

		if( $tikilib->page_exists( $page ) ) {
			// Display an error if the page already exists
			$smarty->assign('msg',tra("Page already exists. Go back and choose a different name."));
			$smarty->display("error.tpl");
			die;
		}

		include_once("lib/multilingual/multilinguallib.php");
		$sourceInfo = $tikilib->get_page_info( $_REQUEST['translationOf'] );
		if( $multilinguallib->getTranslation('wiki page', $sourceInfo['page_id'], $_REQUEST['lang'] ) ) {
			// Display an error if the page already exists
			$smarty->assign('msg',tra("The translation set already contains a page in this language."));
			$smarty->display("error.tpl");
			die;
		}
	}

	if( $editlib->isUpdateTranslationMode() ) {
		include_once('lib/wiki/histlib.php');
		histlib_helper_setup_diff( $_REQUEST['source_page'], $_REQUEST['oldver'], $_REQUEST['newver'] );
		$smarty->assign( 'diff_oldver', (int) $_REQUEST['oldver'] );
		$smarty->assign( 'diff_newver', (int) $_REQUEST['newver'] );
		$smarty->assign( 'source_page', $_REQUEST['source_page'] );
		/* 
		   Use Full Screen mode when translating an update, because 
		   user needs to see both diffs that have happened in the source language
		   and the edit form for the  target language. This requires a lot of real-estate
		   
		   AD (2009-11-09): For now, keep that line commented because the 
		   side-by-side source and target layout in wiki-edit is a bit 
		   screwed up. Will reactivate as soon as I get the CSS right for
		   that.
		 */
//		$_REQUEST['zoom'] = 'wiki_edit';
		$smarty->assign('update_translation', 'y');
	}
}
$cat_type = 'wiki page';
$cat_objid = $_REQUEST["page"];
$cat_lang = $pageLang;
$cat_object_exists = $tikilib->page_exists( $_REQUEST['page'] );
$smarty->assign('section',$section);
include_once ('tiki-section_options.php');
if ($prefs['feature_freetags'] == 'y') {
	include_once ('freetag_list.php');
	// if given in the request, set the freetag list (used for preview mode, when coming back from zoom mode, ...)
	if ( isset($_REQUEST['freetag_string']) ) {
		$smarty->assign('taglist', $_REQUEST['freetag_string']);
	} elseif( $editlib->isNewTranslationMode() ) {
		$tags = $freetaglib->get_all_tags_on_object_for_language($_REQUEST['translationOf'], 'wiki page', $pageLang);
		$smarty->assign( 'taglist', implode( ' ', $tags ) );
	}
}
if ($prefs['feature_categories'] == 'y') {
	include_once ("categorize_list.php");
	
	if (isset($_REQUEST["current_page_id"]) && $prefs['feature_wiki_categorize_structure'] == 'y' && $categlib->is_categorized('wiki page', $structure_info["pageName"])) {
		$categIds = $categlib->get_object_categories('wiki page', $structure_info["pageName"]);
		$smarty->assign('categIds',$categIds);
	}
	if (isset($_SERVER['HTTP_REFERER']) && strstr($_SERVER['HTTP_REFERER'], 'tiki-index.php') && !$tikilib->page_exists($_REQUEST["page"])) { // default the categs the page you come from for a new page
		if (preg_match('/page=([^\&]+)/', $_SERVER['HTTP_REFERER'], $ms))
			$p = $ms[1];
		else
			$p = $wikilib->get_default_wiki_page();
		$cs = $categlib->get_object_categories('wiki page', $p);
		for ($i = count($categories) - 1; $i >= 0; --$i) {
			if (in_array($categories[$i]['categId'], $cs))
				$categories[$i]['incat'] = 'y';
		}
	}
}

$plugins = $wikilib->list_plugins(true, 'editwiki');

$smarty->assign_by_ref('plugins', $plugins);
$smarty->assign('showstructs', array());
if ($structlib->page_is_in_structure($_REQUEST["page"])) {
	$structs = $structlib->get_page_structures($_REQUEST["page"]);
	$smarty->assign('showstructs', $structs);
}
// Flag for 'page bar' that currently 'Edit' mode active
// so no need to show comments & attachments, but need
// to show 'wiki quick help'
$smarty->assign('edit_page', 'y');
$smarty->assign('categ_checked', 'n');
// Set variables so the preview page will keep the newly inputted category information
if (isset($_REQUEST['cat_categorize'])) {
	if ($_REQUEST['cat_categorize'] == 'on') {
		$smarty->assign('categ_checked', 'y');
	}
}
if ($prefs['wiki_feature_copyrights'] == 'y' && $tiki_p_edit_copyrights == 'y') {
	include_once ('lib/copyrights/copyrightslib.php');
	$copyrightslib = new CopyrightsLib;
	$copyrights = $copyrightslib->list_copyrights($_REQUEST["page"]);
	if ($copyrights['cant'])
	$smarty->assign_by_ref('copyrights', $copyrights['data']);
}
$defaultRows = $prefs['default_rows_textarea_wiki'];
include_once ('lib/toolbars/toolbarslib.php');
if (!$user or $user == 'anonymous') {
	$smarty->assign('anon_user', 'y');
}
if ($prefs['feature_contribution'] == 'y') {
	include_once('contribution.php');
}
if ($prefs['feature_wikiapproval'] == 'y') {
	if (substr($page, 0, strlen($prefs['wikiapproval_prefix'])) == $prefs['wikiapproval_prefix']) {
		$approvedPageName = substr($page, strlen($prefs['wikiapproval_prefix']));	
		$smarty->assign('beingStaged', 'y');
		$smarty->assign('approvedPageName', $approvedPageName);
		$approvedPageExists = $tikilib->page_exists($approvedPageName);
		$smarty->assign('approvedPageExists', $approvedPageExists);
	} elseif ($prefs['wikiapproval_approved_category'] > 0 && in_array($prefs['wikiapproval_approved_category'], $cats)) {		
		$stagingPageName = $prefs['wikiapproval_prefix'] . $page;
		if ($prefs['wikiapproval_block_editapproved'] == 'y') {
			header("location: tiki-editpage.php?page=$stagingPageName");
		}
		$smarty->assign('needsStaging', 'y');
		$smarty->assign('stagingPageName', $stagingPageName);		
	}
	if ($prefs['wikiapproval_outofsync_category'] > 0 && in_array($prefs['wikiapproval_outofsync_category'], $cats)) {
		$smarty->assign('outOfSync', 'y');
		if (!isset($_REQUEST['preview'])) {
			$smarty->assign('preview',1);
			$parsed = $tikilib->parse_data($edit_data, array('is_html' => $is_html));
			$smarty->assign('parsed', $parsed);
			$smarty->assign('staging_preview', 'y');
		}
		if (isset($approvedPageName)) {
			include_once('lib/wiki/histlib.php');
			$approvedPageInfo = $histlib->get_page_from_history($approvedPageName, 0);
			if ($info['lastModif'] > $approvedPageInfo['lastModif']) {
				$lastSyncVersion = $histlib->get_version_by_time($page, $approvedPageInfo['lastModif']);
				// get very first version if unable to get last sync version.
				if ($lastSyncVersion == 0) $lastSyncVersion = $histlib->get_version_by_time($page, 0, 'after');
				// if really not possible, just give up.
				if ($lastSyncVersion > 0) $smarty->assign('lastSyncVersion', $lastSyncVersion );
			}
		}		
	}
}

if( $prefs['feature_multilingual'] == 'y' ) {
	global $multilinguallib;
	include_once('lib/multilingual/multilinguallib.php');
	$trads = $multilinguallib->getTranslations('wiki page', $info['page_id'], $page, $info['lang']);
	$smarty->assign('trads', $trads);
}

// Get edit session timeout in seconds
$smarty->assign('edittimeout', ini_get('session.gc_maxlifetime'));

// setup tab showing flags (only avoiding empty tabs for now - regroup better of less than X features later)
// tools tab
if (($prefs['feature_wiki_templates'] == 'y' && $tiki_p_use_content_templates == 'y') ||
	($prefs['feature_wiki_usrlock'] == 'y' && ($tiki_p_lock == 'y' || $tiki_p_admin_wiki == 'y')) ||
	($prefs['feature_wiki_replace'] == 'y' && $wysiwyg != 'y') ||
	$prefs['wiki_spellcheck'] == 'y' ||
	($prefs['feature_wiki_allowhtml'] == 'y' && $tiki_p_use_HTML == 'y' && $wysiwyg != 'y') ||
	$prefs['feature_wiki_import_html'] == 'y' ||
	$prefs['wiki_comments_allow_per_page'] != 'n' ||
	($tiki_p_admin_wiki == 'y' && $prefs['feature_wiki_import_page'] == 'y') ||
	($wysiwyg != 'y' && ($prefs['feature_wiki_attachments'] == 'y' && ($tiki_p_wiki_attach_files == 'y' && $tiki_p_wiki_admin_attachments == 'y')) ||
						($prefs['feature_wiki_screencasts'] == 'y' && $tiki_p_upload_screencast == 'y'))) {
	$smarty->assign('showToolsTab', 'y');
}
if (strtolower($page) != 'sandbox' &&
			($prefs['wiki_feature_copyrights']  == 'y' ||
			($prefs['feature_freetags'] == 'y' && $tiki_p_freetags_tag == 'y') ||
			$prefs['feature_wiki_icache'] == 'y' ||
			$prefs['feature_contribution'] == 'y' ||
			$prefs['feature_wiki_structure'] == 'y' ||
			$prefs['wiki_feature_copyrights']  == 'y' ||
			($tiki_p_admin_wiki == 'y' && $prefs['wiki_authors_style_by_page'] == 'y')) ||
		($prefs['feature_wiki_description'] == 'y' || $prefs['metatag_pagedesc'] == 'y') ||
		$prefs['feature_wiki_footnotes'] == 'y' ||
		($prefs['feature_wiki_ratings'] == 'y' && $tiki_p_wiki_admin_ratings =='y') ||
		$prefs['feature_multilingual'] == 'y') {
	$smarty->assign('showPropertiesTab', 'y');
}

ask_ticket('edit-page');
// disallow robots to index page:
$smarty->assign('metatag_robots', 'NOINDEX, NOFOLLOW');
// Display the Index Template
$smarty->assign('mid', 'tiki-editpage.tpl');
$smarty->assign('showtags', 'n');
$smarty->assign('qtnum', '1');
$smarty->assign('qtcycle', '');
$smarty->display("tiki.tpl");

