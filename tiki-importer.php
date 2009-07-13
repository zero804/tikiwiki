<?php

require_once('tiki-setup.php');
require_once('lib/importer/tikiimporter.php');
require_once('lib/importer/tikiimporter_wiki.php');

if ($tiki_p_admin_importer != 'y') {
    $smarty->assign('msg', tra("Permission denied you cannot view this section"));
    $smarty->display("error.tpl");
    die;
}

if (!empty($_POST['importerClassName'])) {
    $importerClassName = $_POST['importerClassName'];
    require_once('lib/importer/' . $importerClassName . '.php');
    $importer = new $importerClassName();
    $smarty->assign('softwareName', $importer->softwareName);
}

if (!empty($_FILES['importFile'])) {
    // third step: start the importing process
    try {
        $importFeedback = $importer->import($_FILES['importFile']['tmp_name']); 
    } catch(Exception $e) {
        $smarty->assign('msg', $e->getMessage());
        $smarty->display('error.tpl');
        die;
    }
    $smarty->assign('importFeedback', $importFeedback);
} else if (!empty($_POST['importerClassName'])) {
    // second step: display import options for the software previously chosen
    if (!file_exists('lib/importer/' . $importerClassName . '.php')) {
        $smarty->assign('msg', tra("Invalid software name"));
        $smarty->display("error.tpl");
        die;
    }
    
    $importerOptions = $importer->getOptions();

    $smarty->assign('importerOptions', $importerOptions);
    $smarty->assign('softwareSpecificOptions', true);
    $smarty->assign('importerClassName', $importerClassName);
} else {
    // first step: display the list of available software importers

    // $availableSoftwares is an array thtat control the list of available software importers.
    // The array key is the name of the importer class and the value is the name of the software
    $availableSoftwares = array('tikiimporter_wiki_mediawiki' => 'Mediawiki');
    $smarty->assign('availableSoftwares', $availableSoftwares);
    $smarty->assign('chooseSoftware', true);
}

$smarty->assign('mid', 'tiki-importer.tpl');
$smarty->display('tiki.tpl');

?>
