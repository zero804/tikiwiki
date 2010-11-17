<?php
// (c) Copyright 2002-2010 by authors of the Tiki Wiki/CMS/Groupware Project
// 
// All Rights Reserved. See copyright.txt for details and a complete list of authors.
// Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See license.txt for details.
// $Id$

require_once('tikiimporter_wiki.php');
require_once('Text/Wiki/Mediawiki.php');

/**
 * Parses a MediaWiki-style XML dump to import it into TikiWiki.
 * Requires PHP5 DOM extension.
 * Based on the work done on http://dev.tiki.org/MediaWiki+to+TikiWiki+converter  
 *
 * @package    tikiimporter
 */
class TikiImporter_Wiki_Mediawiki extends TikiImporter_Wiki
{
    public $softwareName = 'Mediawiki';
    
    /**
     * The DOM representation of the Mediawiki XML dump
     * @var DOMDocument object
     */
    public $dom = '';

    /**
     * Array of the valid mime types for the
     * input file
     */
    public $validTypes = array('application/xml', 'text/xml');

    /**
     * @see lib/importer/TikiImporter#importOptions
     */
    static public $importOptions = array(
        array('name' => 'importAttachments', 'type' => 'checkbox', 'label' => 'Import images and attachments (see documentation for more information)'),
    );    

    /**
     * The directory used to save the attachments.
     * It is defined on $this->import()
     */
    var $attachmentsDestDir = '';

    /**
     * Text_Wiki object to handle Mediawiki
     * syntax parsing
     */
    var $parser = '';

    /**
     * Start the importing process by loading the XML file.
     * 
     * @see lib/importer/TikiImporter_Wiki#import()
     *
     * @param string $filePath path to the XML file
     * @return void 
     * @throws UnexpectedValueException if invalid file mime type
     */
    function import($filePath)
    {
        if (isset($_FILES['importFile']) && !in_array($_FILES['importFile']['type'], $this->validTypes)) {
            throw new UnexpectedValueException(tra('Invalid file mime type'));
        }

        if (!empty($_POST['importAttachments']) && $_POST['importAttachments'] == 'on') {
            $this->checkRequirementsForAttachments();
        }

        $this->saveAndDisplayLog("Loading and validating the XML file\n");

        $this->dom = new DOMDocument;
        $this->dom->load($filePath);

        $this->configureParser();

        if (!empty($_POST['importAttachments']) && $_POST['importAttachments'] == 'on') {
            $this->downloadAttachments();
        }

        parent::import();
    }

    /**
     * Create a Text_Wiki object to handle the parsing
     * of Mediawiki syntax and define some configuration
     * option
     */
    function configureParser()
    {
        $this->parser = Text_Wiki::factory('Mediawiki');

        // do not replace space by underscore in wikilinks
        $this->parser->setParseConf('Wikilink', 'spaceUnderscore', false);

        // define possible localized namespace for image and files in the wikilink syntax
        $namespaces = $this->dom->getElementsByTagName('namespace');
        $prefix = array('Image', 'image');
        if ($namespaces->length > 0) {
            foreach ($namespaces as $namespace) {
                if ($namespace->getAttribute('key') == '-2' || $namespace->getAttribute('key') == '6') {
                    $prefix[] = $namespace->nodeValue;
                }
            }
        }
        $this->parser->setParseConf('Image', 'prefix', $prefix);
    }

    /**
     * At present this method only validates the Mediawiki XML
     * against its DTD (Document Type Definition). Mediawiki XML
     * versions 0.3 and 0.4 are supported.
     * 
     * Note: we use schemaValidate() instead of validate() because
     * for some unknown reason the former method is unable to automatically
     * retrieve Mediawiki XML DTD and dies with "no DTD found" error.
     * 
     * @see lib/importer/TikiImporter#validateInput()
     *
     * @throws DOMException if XML file does not validate against schema
     */
    function validateInput()
    {
    	$xmlVersion = $this->dom->getElementsByTagName('mediawiki')->item(0)->getAttribute('version');

    	switch ($xmlVersion) {
    		case '0.3':
    		case '0.4':
    			$xmlDtdFile = dirname(__FILE__) . "/mediawiki_dump_v$xmlVersion.xsd";
    			break;
    		default:
    			throw new DOMException(tra("Mediawiki XML file version $xmlVersion is not supported."));
    			break;
    	}
    	
        if (!@$this->dom->schemaValidate($xmlDtdFile)) {
            throw new DOMException(tra('XML file does not validate against the Mediawiki XML schema'));
        }
    }

    /**
     * Check for all the requirements to import attachments
     * and also set the $this->attachmentsDestDir.
     * If one of them is not satisfied the script will die.
     *
     * @returns void
     */
    function checkRequirementsForAttachments()
    {
        global $tikidomain;

        $this->attachmentsDestDir = dirname(__FILE__) . '/../../img/wiki_up/';
        if ($tikidomain)
            $this->attachmentsDestDir .= $tikidomain;

        if (ini_get('allow_url_fopen') === false) {
            $this->saveAndDisplayLog("ABORTING: you need to enable the PHP setting 'allow_url_fopen' to be able to import attachments. Fix the problem or try to import without the attachments.\n");
            die;
        }

        if (!file_exists($this->attachmentsDestDir)) {
            $this->saveAndDisplayLog("ABORTING: destination directory for attachments ($this->attachmentsDestDir) does no exist. Fix the problem or try to import without the attachments.\n");
            die;
        } elseif (!is_writable($this->attachmentsDestDir)) {
            $this->saveAndDisplayLog("ABORTING: destination directory for attachments ($this->attachmentsDestDir) is not writable. Fix the problem or try to import without attachments.\n");
            die;
        }
    }

    /**
     * Foreach page check if it is a wiki page or a wiki page
     * attachment and call the proper method, respectively 
     * $this->extractInfo() and $this->handleFileUpload()
     *
     * In the case of a wiki page append the returned value of
     * $this->extractInfo() to $parsedData array
     * 
     * @return array $parsedData
     */
    function parseData()
    {
        $parsedData = array();
        $pages = $this->dom->getElementsByTagName('page');

        $this->saveAndDisplayLog("\nStarting to parse pages:\n");

        foreach ($pages as $page) {
            $isAttachment = $page->getElementsByTagName('upload');
            // is a wiki page and not an attachment
            if ($isAttachment->length == 0) {
                try {
                    $parsedData[] = $this->extractInfo($page);
                } catch (ImporterParserException $e) {
                    $this->saveAndDisplayLog($e->getMessage(), true);
                }
            }
        }

        return $parsedData;
    }

    /**
     * Searches for the last version of each attachments in the XML file
     * and try to download it to the img/wiki_up/ directory
     *
     * Note: it is not possible to generate the Mediawiki
     * XML file with the <upload> tag through the web interface
     * (Special:Export). This is only possible through the Mediawiki
     * script maintanance/dumpBackup.php with the experimental option
     * --uploads
     *
     * @return void
     */
    function downloadAttachments() {
        $pages = $this->dom->getElementsByTagName('page');

        if ($this->dom->getElementsByTagName('upload')->length == 0) {
            $this->saveAndDisplayLog("\n\nNo attachments found to import! Make sure you have created your XML file with the dumpDump.php script and with the option --uploads. This is the only way to import attachment.\n", true);
            return;
        }

        $this->saveAndDisplayLog("\n\nStarting to import attachments:\n");

        foreach ($pages as $page) {
            $attachments = $page->getElementsByTagName('upload');

            if ($attachments->length > 0) {
                $i = $attachments->length - 1;
                $lastVersion = $attachments->item($i);

                $fileName = $lastVersion->getElementsByTagName('filename')->item(0)->nodeValue;
                $fileUrl = $lastVersion->getElementsByTagName('src')->item(0)->nodeValue;

                if (file_exists($this->attachmentsDestDir . $fileName)) {
                    $this->saveAndDisplayLog("NOT importing file $fileName as there is already a file with the same name in the destination directory ($this->attachmentsDestDir)\n", true);
                    continue;
                }

                if (@fopen($fileUrl, 'r')) {
                    $attachmentContent = @file_get_contents($fileUrl);
                    $newFile = fopen($this->attachmentsDestDir . $fileName, 'w');
                    fwrite($newFile, $attachmentContent);
                    $this->saveAndDisplayLog("File $fileName successfully imported!\n");
                } else {
                    $this->saveAndDisplayLog("Unable to download file $fileName. File not found.\n", true);
                }
            }
        }
    }

    /**
     * Parse an DOM representation of a Mediawiki page and return all the values
     * that will be imported (page name, page content for all revisions). The 
     * property TikiImporter_Wiki::revisionsNumber define how many wiki page
     * revisions are parsed.
     * 
     * Note: the names of the keys are changed to reflected the names used by
     * Tiki builtin function (i.e. 'title' is changed to 'name' as used in 
     * TikiLib::create_page() which will be called by TikiImporter_Wiki::insertPage())
     * 
     * @param DOMElement $page
     * @return array $data information for one wiki page 
     * @throws ImporterParserException if fail to parse all revisions of a page
     */
    function extractInfo(DOMElement $page)
    {
        $data = array();
        $data['revisions'] = array();

        $totalRevisions = $page->getElementsByTagName('revision')->length;
        if ($this->revisionsNumber != 0 && $totalRevisions > $this->revisionsNumber) {
            $j = true;
        }

        $i = 0;
        foreach ($page->childNodes as $node) {
            if ($node instanceof DOMElement) {
                switch ($node->tagName)
                {
                case 'id':
                    break;
                case 'title':
                    $data['name'] = (string) $node->textContent;
                    break;
                case 'revision':
                    $i++;
                    if (!isset($j) || ($i > ($totalRevisions - $this->revisionsNumber))) {
                        try {
                            $data['revisions'][] = $this->extractRevision($node);
                        } catch (ImporterParserException $e) {
                            $this->saveAndDisplayLog('Error while parsing revision ' . $i . ' of the page "' . $data['name'] . '". Or there is a problem on the page syntax or on the Text_Wiki parser (the parser used by the importer).' . "\n", true);
                        }
                    }
                    break;
                default:
                    print "Unknown tag : {$node->tagName}\n";
                }
            }
        }

        if (count($data['revisions']) > 0) {
            $msg = 'Page "' . $data['name'] . '" successfully parsed with ' . count($data['revisions']) . " revisions (from a total of $totalRevisions revisions).\n";
            $this->saveAndDisplayLog($msg);
            return $data;
        } else {
            throw new ImporterParserException('Page "' . $data['name'] . '" is NOT going to be imported. It was not possible to parse any of the page revisions.' . "\n", true);
        }
    }

    /**
     * Parse an DOM representation of a Mediawiki page revisions and return all the values
     * that will be imported (page content converted to Tiki syntax, lastModif, minor, user and ip address)
     *
     * Note: the names of the keys are changed to reflected the names used by
     * Tiki builtin function (i.e. 'text' is changed to 'data' as used in TikiLib::create_page())
     * 
     * @param DOMElement $page
     * @return array $data information for one wiki page revision
     * @throws ImporterParserException if unable to parse revision content
     */
    function extractRevision(DOMElement $revision)
    {
        $data = array();
        $data['minor'] = false;
        $data['comment'] = '';

        foreach ($revision->childNodes as $node) {
            if ($node instanceof DOMElement) {
                switch ($node->tagName)
                {
                case 'id':
                    break;
                case 'comment':
                    $data['comment'] = $node->textContent;
                    break;
                case 'text':
                    $text = $this->convertMarkup($node->textContent);
                    if ( $text instanceof PEAR_Error ) {
                        throw new ImporterParserException($text->message);
                    } else {
                        $data['data'] = $text;
                    }
                    break;
                case 'timestamp':
                    $data['lastModif'] = strtotime($node->textContent);
                    break;
                case 'minor':
                    $data['minor'] = true;
                    break;
                case 'contributor':
                    $data = array_merge($data, $this->extractContributor($node));
                    break;
                }
            }
        }

        return $data;
    }

    /**
     * Parse an DOM representation of a Mediawiki page revision contributor and return
     * the username and ip address
     * 
     * @param DOMElement $contributor
     * @return array $data
     */
    function extractContributor(DOMElement $contributor)
    {
        $data = array();
        
        foreach ($contributor->childNodes as $node) {
            if ($node instanceof DOMElement) {
                switch ($node->tagName) {
                case 'id':
                    break;
                case 'ip':
                    $data[$node->tagName] = (string) $node->textContent;
                    break;
                case 'username':
                    $data['user'] = (string) $node->textContent;
                    break;
                default:
                    print "Unknown tag in contributor: {$node->tagName}\n";
                }
            }
        }

        if (!isset($data['user']))
            $data['user'] = 'anonymous';

        if (!isset($data['ip']))
            $data['ip'] = '0.0.0.0';

        return $data;
    }
    
    /**
     * Utility for converting MediaWiki markup to TikiWiki markup
     * Uses Text_Wiki PEAR library for heavy lifting
     *  
     * @param string $mediawikiText 
     * @return string $tikiText
     */
    function convertMarkup($mediawikiText) {
        if (!empty($mediawikiText)) {
            $tikiText = $this->parser->transform($mediawikiText, 'Tiki');
            return $tikiText;
        }
    }
}

class ImporterParserException extends Exception
{
}
