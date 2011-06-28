<?php
// (c) Copyright 2002-2011 by authors of the Tiki Wiki CMS Groupware Project
// 
// All Rights Reserved. See copyright.txt for details and a complete list of authors.
// Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See license.txt for details.
// $Id$

/**
 * @group unit
 */
class Search_Index_LuceneStemmingTest extends PHPUnit_Framework_TestCase
{
	private $dir;
	private $index;

	function setUp()
	{
		$this->dir = dirname(__FILE__) . '/test_index';
		$this->tearDown();

		$index = new Search_Index_Lucene($this->dir, 'en');
		$typeFactory = $index->getTypeFactory();
		$index->addDocument(array(
			'object_type' => $typeFactory->identifier('wikipage?!'),
			'object_id' => $typeFactory->identifier('Comité Wiki'),
			'description' => $typeFactory->plaintext('a description for the pages éducation Case'),
			'contents' => $typeFactory->plaintext('a description for the pages éducation Case'),
		));

		$this->index = $index;
	}

	function tearDown()
	{
		$dir = escapeshellarg($this->dir);
		`rm -Rf $dir`;
	}

	function testSearchWithAdditionalS()
	{
		$query = new Search_Query('descriptions');

		$this->assertGreaterThan(0, count($query->search($this->index)));
	}

	function testSearchWithMissingS()
	{
		$query = new Search_Query('page');

		$this->assertGreaterThan(0, count($query->search($this->index)));
	}

	function testSearchAccents()
	{
		$query = new Search_Query('education');

		$this->assertGreaterThan(0, count($query->search($this->index)));
	}

	function testSearchExtraAccents()
	{
		$query = new Search_Query('pagé');

		$this->assertGreaterThan(0, count($query->search($this->index)));
	}

	function testCaseSensitivity()
	{
		$query = new Search_Query('casE');

		$this->assertGreaterThan(0, count($query->search($this->index)));
	}

	function testFilterIdentifierExactly()
	{
		$query = new Search_Query;
		$query->filterType('wikipage?!');

		$this->assertGreaterThan(0, count($query->search($this->index)));
	}

	function testSearchObject()
	{
		$query = new Search_Query;
		$query->addObject('wikipage?!', 'Comité Wiki');

		$this->assertGreaterThan(0, count($query->search($this->index)));
	}
}

