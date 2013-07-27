<?php
// (c) Copyright 2002-2013 by authors of the Tiki Wiki CMS Groupware Project
//
// All Rights Reserved. See copyright.txt for details and a complete list of authors.
// Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See license.txt for details.
// $Id$

class Search_MySql_Index implements Search_Index_Interface
{
	private $db;
	private $table;
	private $builder;

	private $providedMappings = array();

	function __construct(TikiDb $db, $index)
	{
		$this->db = $db;
		$this->table = new Search_MySql_Table($db, $index);
		$this->builder = new Search_MySql_QueryBuilder($db);
	}

	function destroy()
	{
		$this->table->drop();
		return true;
	}

	function exists()
	{
		return $this->table->exists();
	}

	function addDocument(array $data)
	{
		foreach ($data as $key => $value) {
			$this->handleField($key, $value);
		}

		$data = array_map(
			function ($entry) {
				return $entry->getValue();
			}, $data
		);

		$this->table->insert($data);
	}

	private function handleField($name, $value)
	{
		if ($value instanceof Search_Type_Whole) {
			$this->table->ensureHasField($name, 'VARCHAR(200)');
		} elseif ($value instanceof Search_Type_PlainShortText) {
			$this->table->ensureHasField($name, 'VARCHAR(300)');
		} elseif ($value instanceof Search_Type_PlainText) {
			$this->table->ensureHasField($name, 'TEXT');
		} elseif ($value instanceof Search_Type_WikiText) {
			$this->table->ensureHasField($name, 'TEXT');
		} elseif ($value instanceof Search_Type_MultivalueText) {
			$this->table->ensureHasField($name, 'TEXT');
		} elseif ($value instanceof Search_Type_Timestamp) {
			$this->table->ensureHasField($name, 'DATETIME');
		} else {
			throw new Exception('Unsupported type: ' . get_class($value));
		}
	}

	function endUpdate()
	{
		$this->table->flush();
	}

	function optimize()
	{
		$this->table->flush();
	}

	function invalidateMultiple(array $objectList)
	{
		foreach ($objectList as $object) {
			$this->table->deleteMultiple($object);
		}
	}

	function find(Search_Query_Interface $query, $resultStart, $resultCount)
	{
		$this->table->flush();

		try {
			$words = $this->getWords($query->getExpr());

			$condition = $this->builder->build($query->getExpr());
			$conditions = empty($condition) ? array() : array(
				$this->table->expr($condition),
			);

			$scoreField = null;
			$indexes = $this->builder->getRequiredIndexes();
			foreach ($indexes as $index) {
				$this->table->ensureHasIndex($index['field'], $index['type']);

				if (! $scoreField && $index['type'] == 'fulltext') {
					$scoreField = $index['field'];
				}
			}

			$order = $this->getOrderClause($query, (bool) $scoreField);

			$selectFields = $this->table->all();

			if ($scoreField) {
				$str = $this->db->qstr(implode(' ', $words));
				$selectFields['score'] = $this->table->expr("MATCH(`$scoreField`) AGAINST ($str)");
			}
			$count = $this->table->fetchCount($conditions);
			$entries = $this->table->fetchAll($selectFields, $conditions, $resultCount, $resultStart, $order);

			$resultSet = new Search_ResultSet($entries, $count, $resultStart, $resultCount);
			$resultSet->setHighlightHelper(new Search_MySql_HighlightHelper($words));

			return $resultSet;
		} catch (Search_MySql_QueryException $e) {
			$resultSet = new Search_ResultSet(array(), 0, $resultStart, $resultCount);
			return $resultSet;
		}
	}

	private function getOrderClause($query, $useScore)
	{
		$order = $query->getSortOrder();

		if ($order->getField() == Search_Query_Order::FIELD_SCORE) {
			if ($useScore) {
				return array('score' => 'DESC');
			} else {
				return; // No specific order
			}
		}

		if ($order->getMode() == Search_Query_Order::MODE_NUMERIC) {
			return $this->table->expr("CAST(`{$order->getField()}` as SIGNED) {$order->getOrder()}");
		} else {
			return $this->table->expr("`{$order->getField()}` {$order->getOrder()}");
		}
	}

	private function getWords($expr)
	{
		$words = array();
		$factory = new Search_Type_Factory_Direct;
		$expr->walk(function ($node) use (& $words, $factory) {
			if ($node instanceof Search_Expr_Token) {
				$word = $node->getValue($factory)->getValue();
				if (is_string($word)) {
					$words[] = $word;
				}
			}
		});

		return $words;
	}

	function getTypeFactory()
	{
		return new Search_MySql_TypeFactory;
	}
}

