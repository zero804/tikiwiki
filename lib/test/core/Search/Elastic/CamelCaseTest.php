<?php
// (c) Copyright by authors of the Tiki Wiki CMS Groupware Project
//
// All Rights Reserved. See copyright.txt for details and a complete list of authors.
// Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See license.txt for details.
// $Id$

class Search_Elastic_CamelCaseTest extends PHPUnit\Framework\TestCase
{
	private $connection;

	protected function setUp() : void
	{
		$elasticSearchHost = empty(getenv('ELASTICSEARCH_HOST')) ? 'localhost' : getenv('ELASTICSEARCH_HOST');
		$connection = new Search_Elastic_Connection('http://' . $elasticSearchHost . ':9200');

		$status = $connection->getStatus();
		if (! $status->ok) {
			$this->markTestSkipped('Elasticsearch needs to be available on ' . $elasticSearchHost . ':9200 for the test to run.');
		}

		$this->connection = $connection;
	}

	function testCamelCaseEnabled()
	{
		$index = new Search_Elastic_Index($this->connection, 'test_index');
		$index->setCamelCaseEnabled(true);
		$index->destroy();
		$typeFactory = $index->getTypeFactory();
		$index->addDocument([
			'object_type' => $typeFactory->identifier('wiki page'),
			'object_id' => $typeFactory->identifier('CamelCase Words'),
			'title' => $typeFactory->plaintext('CamelCase Words'),
		]);

		$query = new Search_Query;
		$query->filterContent('Camel AND Words', 'title');
		$this->assertGreaterThan(0, count($query->search($index)));
	}

	function testCamelCaseEnabledWithStemming()
	{
		$index = new Search_Elastic_Index($this->connection, 'test_index');
		$index->setCamelCaseEnabled(true);
		$index->destroy();
		$typeFactory = $index->getTypeFactory();
		$index->addDocument([
			'object_type' => $typeFactory->identifier('wiki page'),
			'object_id' => $typeFactory->identifier('CamelCase Words'),
			'title' => $typeFactory->plaintext('CamelCase Words'),
		]);

		$query = new Search_Query;
		$query->filterContent('Camels AND Word', 'title');
		$this->assertGreaterThan(0, count($query->search($index)));
	}

	function testCamelCaseNotEnabled()
	{
		$index = new Search_Elastic_Index($this->connection, 'test_index');
		$index->destroy();
		$typeFactory = $index->getTypeFactory();
		$index->addDocument([
			'object_type' => $typeFactory->identifier('wiki page'),
			'object_id' => $typeFactory->identifier('CamelCase Words'),
			'title' => $typeFactory->plaintext('CamelCase Words'),
		]);

		$query = new Search_Query;
		$query->filterContent('Camel AND Word', 'title');
		$this->assertCount(0, $index);
	}
}
