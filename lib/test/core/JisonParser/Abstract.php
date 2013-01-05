<?php
// (c) Copyright 2002-2013 by authors of the Tiki Wiki CMS Groupware Project
//
// All Rights Reserved. See copyright.txt for details and a complete list of authors.
// Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See license.txt for details.
// $Id$

class JisonParser_Abstract extends TikiTestCase
{
	public $verbose = false;
	public $called;
	public $parser;
	public $syntaxSets = array();

	function setUp()
	{
		global $prefs;
		$prefs['feature_jison_wiki_parser'] = 'y';
		$this->parser = new JisonParser_Wiki_Handler();
		$this->parser->setOption(array('skipPageCache' => true));
		$this->called = 0;
		$this->provider();

		WikiPlugin_Negotiator_Wiki::$standardRelativePath = "../wiki-plugins/wikiplugin_";
		WikiPlugin_Negotiator_Wiki::$zendRelativePath = "../core/WikiPlugin/";
		WikiPlugin_Negotiator_Wiki::$checkZendPaths = false;

		$this->removeFakePage();
		$this->createFakePage();
	}

	public function removeFakePage()
	{
		global $tikilib;
		$tikilib->query('DELETE FROM `tiki_pages` WHERE `pageName` = ?', array("FakePage"));
		$tikilib->query('DELETE FROM `tiki_pages` WHERE `pageName` = ?', array("[FakePage]"));
	}

	public function createFakePage()
	{
		global $wikilib;
		$_SERVER["SERVER_NAME"] = 'localhost';
		$_SERVER["REQUEST_URI"] = 'localhost';
		$wikilib->create_page("FakePage",0,"",0,"Fake Tiki Page");
		$wikilib->create_page("[FakePage]",0,"",0,"Fake Tiki Page");
	}

	public function testOutput()
	{
		foreach ($this->syntaxSets as $syntaxName => $syntax) {
			if (isset($syntax[0])) {
				$parsed = $this->parser->parse($syntax[0]);
			} else {
				$customHandled = $this->$syntaxName();
				$parsed = $customHandled['parsed'];
				$syntax = $customHandled['syntax'];
			}

			if ($this->verbose == true) {
				echo $syntaxName . ":\n";
				echo '"' . $parsed . '"' . "\n\n\n\n";
			}

			$this->called++;

			$this->assertEquals($syntax[1], $parsed, $syntaxName, $syntax[0], $this->parser->list);
		}
	}

	static function assertEquals($expected, $actual, $syntaxName, $syntax)
	{
		if ($expected != $actual) {
			echo "Failure on: " . $syntaxName . "\n";
			echo 'Syntax: "' . $syntax . '"' . "\n";
			echo 'Output: ' . $actual . "\n";
		}

		parent::assertEquals($expected, $actual);
	}

	public function tryRemoveIdsFromHtmlList(&$parsed)
	{
		$parsed = preg_replace('/id="id[0-9]+"/', 'id=""', $parsed);
	}

	public function tryRemoveFingerprintId($type, &$parsed)
	{
		$parsed = preg_replace('/id="' . $type . '(.)+?"/', 'id=""', $parsed);
	}
}