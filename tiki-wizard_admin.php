<?php
/**
 * @package tikiwiki
 */
// (c) Copyright 2002-2013 by authors of the Tiki Wiki CMS Groupware Project
// 
// All Rights Reserved. See copyright.txt for details and a complete list of authors.
// Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See license.txt for details.
// $Id$

require 'tiki-setup.php';

require_once('lib/headerlib.php');
$headerlib->add_cssfile('css/admin.css');
$headerlib->add_cssfile('css/wizards.css');

// Hide the display of the preference dependencies in the wizard
$headerlib->add_css('.pref_dependency{display:none !important;}');

$accesslib = TikiLib::lib('access');
$accesslib->check_permission('tiki_p_admin');

// Create the template instances
$pages = array();

/////////////////////////////////////
// BEGIN Wizard page section
/////////////////////////////////////

// Always show the first page
require_once('lib/wizard/pages/admin_wizard.php'); 
$pages[] = new AdminWizard();

// If $useDefaultPrefs is set, the profiles "wizard" should be run. Otherwise the standard 
$useDefaultPrefs = isset($_REQUEST['use-default-prefs']) ? true : false;
if ($useDefaultPrefs) {
	
	// Store the default prefs selection in the wizard bar
	$smarty->assign('useDefaultPrefs', $useDefaultPrefs);

    require_once('lib/wizard/pages/profiles_featured_site_confs.php');
    $pages[] = new ProfilesWizardFeaturedSiteConfs();

    require_once('lib/wizard/pages/profiles_useful_micro_confs.php');
    $pages[] = new ProfilesWizardUsefulMicroConfs();

    require_once('lib/wizard/pages/profiles_useful_changes_in_display.php');
    $pages[] = new ProfilesWizardUsefulChangesInDisplay();

    require_once('lib/wizard/pages/profiles_useful_new_tech_confs.php');
    $pages[] = new ProfilesWizardUsefulNewTechConfs();

    require_once('lib/wizard/pages/profiles_useful_admin_confs.php');
    $pages[] = new ProfilesWizardUsefulAdminConfs();

    require_once('lib/wizard/pages/profiles_demo_common_confs.php');
    $pages[] = new ProfilesWizardDemoCommonConfs();

    require_once('lib/wizard/pages/profiles_demo_interesting_use_cases.php');
    $pages[] = new ProfilesWizardDemoInterestingUseCases();

    require_once('lib/wizard/pages/profiles_demo_more_advanced_confs.php');
    $pages[] = new ProfilesWizardDemoMoreAdvancedConfs();

    require_once('lib/wizard/pages/profiles_demo_highly_specialized_confs.php');
    $pages[] = new ProfilesWizardHighlySpecializedConfs();

    require_once('lib/wizard/pages/profiles_completed.php');
    $pages[] = new AdminWizardProfilesCompleted();

} else {
	require_once('lib/wizard/pages/admin_language.php');
	$pages[] = new AdminWizardLanguage();

    require_once('lib/wizard/pages/admin_date_time.php');
    $pages[] = new AdminWizardDateTime();

    require_once('lib/wizard/pages/admin_login.php');
	$pages[] = new AdminWizardLogin();

    require_once('lib/wizard/pages/admin_look_and_feel.php');
    $pages[] = new AdminWizardLookAndFeel();

    require_once('lib/wizard/pages/admin_editor_type.php');
	$pages[] = new AdminWizardEditorType();

	require_once('lib/wizard/pages/admin_wysiwyg.php'); 
	$pages[] = new AdminWizardWysiwyg();

	require_once('lib/wizard/pages/admin_text_area.php'); 
	$pages[] = new AdminWizardTextArea();

	require_once('lib/wizard/pages/admin_wiki.php'); 
	$pages[] = new AdminWizardWiki();

	require_once('lib/wizard/pages/admin_auto_toc.php'); 
	$pages[] = new AdminWizardAutoTOC();

	require_once('lib/wizard/pages/admin_category.php'); 
	$pages[] = new AdminWizardCategory();

	require_once('lib/wizard/pages/admin_structures.php'); 
	$pages[] = new AdminWizardStructures();

	require_once('lib/wizard/pages/admin_jcapture.php'); 
	$pages[] = new AdminWizardJCapture();

	require_once('lib/wizard/pages/admin_files.php'); 
	$pages[] = new AdminWizardFiles();

	require_once('lib/wizard/pages/admin_files_storage.php'); 
	$pages[] = new AdminWizardFileStorage();

	require_once('lib/wizard/pages/admin_features.php'); 
	$pages[] = new AdminWizardFeatures();

	require_once('lib/wizard/pages/admin_search.php');
	$pages[] = new AdminWizardSearch();

	require_once('lib/wizard/pages/admin_community.php');
	$pages[] = new AdminWizardCommunity();

	require_once('lib/wizard/pages/admin_advanced.php');
	$pages[] = new AdminWizardAdvanced();

	require_once('lib/wizard/pages/admin_namespace.php'); 
	$pages[] = new AdminWizardNamespace();

	require_once('lib/wizard/pages/admin_wizard_completed.php'); 
	$pages[] = new AdminWizardCompleted();
	
}

/////////////////////////////////////
// END Wizard page section
/////////////////////////////////////


// Step the wizard pages
$wizardlib = TikiLib::lib('wizard');

// Show pages
$wizardlib->showPages($pages, true);

// Set the display flag
$showOnLogin = $wizardlib->get_preference('wizard_admin_hide_on_login') !== 'y';
$smarty->assign('showOnLogin', $showOnLogin);


// Build the TOC
$toc = '<ul class="wizard_toc">';
$stepNr = 0;
$reqStepNr = $wizardlib->wizard_stepNr;
$homepageUrl = $_REQUEST['url'];
foreach ($pages as $page) {
	global $base_url;
	$cssClasses = '';

	// Start the admin wizard
	$url = $base_url.'tiki-wizard_admin.php?&amp;stepNr=' . $stepNr . '&amp;url=' . rawurlencode($homepageUrl);
	if ($useDefaultPrefs) {
		$url .= '&amp;use-default-prefs=1';
	}
	$cnt = 	$stepNr+1;
	if ($cnt <= 9) {
		$cnt = '&nbsp;&nbsp;'.$cnt;
	}
	$toc .= '<li><a ';
	$cssClasses .= 'adminWizardTOCItem ';
	if ($stepNr == $reqStepNr) {
		$cssClasses .= 'highlight ';
	}
	if (!$page->isVisible()) {
		$cssClasses .= 'disabledTOCSelection ';
	}
	$css = '';
	if (strlen($cssClasses) > 0) {
		$css = 'class="'.$cssClasses.'" ';
	}
	$toc .= $css;
	$toc .= 'href="'.$url.'">'.$page->pageTitle().'</a></li>';
	$stepNr++;
}
$toc .= '</ul>';
	// Hide the left and right sidebars when the admin wizard is run
	$headerlib = TikiLib::lib('header');
	$headerlib->add_js(
<<<JS
	hideCol('col2','left', 'col1');
	hideCol('col3','right', 'col1');
JS
);

if ($reqStepNr > 0) {
	$smarty->assign('wizard_toc', $toc);
}


// disallow robots to index page:
$smarty->assign('metatag_robots', 'NOINDEX, NOFOLLOW');

$smarty->assign('mid', 'tiki-wizard_admin.tpl');
$smarty->display("tiki.tpl");
