<?php
// (c) Copyright 2002-2016 by authors of the Tiki Wiki CMS Groupware Project
//
// All Rights Reserved. See copyright.txt for details and a complete list of authors.
// Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See license.txt for details.
// $Id$

use Tiki\TikiDb\SanitizeEncoding;

class TikiDb_Table
{
	/**
	 * @var TikiDb
	 */
	protected $db;
	protected $tableName;
	protected $autoIncrement;
	protected $errorMode = TikiDb::ERR_DIRECT;

	function __construct($db, $tableName, $autoIncrement = true)
	{
		$this->db = $db;
		$this->tableName = $tableName;
		$this->autoIncrement = $autoIncrement;

		SanitizeEncoding::detectCharset($db, $tableName);
	}

	function useExceptions()
	{
		$this->errorMode = TikiDb::ERR_EXCEPTION;
	}

	/**
	 * Inserts a row in the table by building the SQL query from an array of values.
	 * The target table is defined by the instance. Argument names are not validated
	 * against the schema. This is only a helper method to improve code readability.
	 *
	 * @param $values array Key-value pairs to insert.
	 * @param $ignore boolean Insert as ignore statement
	 */
	function insert(array $values, $ignore = false)
	{
		$bindvars = [];
		$query = $this->buildInsert($values, $ignore, $bindvars);

		$this->db->queryException($query, $bindvars);

		if ($this->autoIncrement) {
			return $this->db->lastInsertId();
		}
	}

	function insertOrUpdate(array $data, array $keys)
	{
		$insertData = array_merge($data, $keys);

		$bindvars = [];
		$query = $this->buildInsert($insertData, false, $bindvars);
		$query .= ' ON DUPLICATE KEY UPDATE ';
		$query .= $this->buildUpdateList($data, $bindvars);

		$this->db->queryException($query, $bindvars);

		if ($this->autoIncrement) {
			return $this->db->lastInsertId();
		}
	}

	/**
	 * Deletes a single record from the table matching the provided conditions.
	 * Conditions use exact matching. Multiple conditions will result in AND matching.
	 */
	function delete(array $conditions)
	{
		$bindvars = [];
		$query = $this->buildDelete($conditions, $bindvars) . ' LIMIT 1';

		return $this->db->queryException($query, $bindvars);
	}

	/**
	 * Builds and performs and SQL update query on the table defined by the instance.
	 * This query will update a single record.
	 */
	function update(array $values, array $conditions)
	{
		return $this->updateMultiple($values, $conditions, 1);
	}

	function updateMultiple(array $values, array $conditions, $limit = null)
	{
		$bindvars = [];
		$query = $this->buildUpdate($values, $conditions, $bindvars);

		if (! is_null($limit)) {
			$query .= ' LIMIT ' . intval($limit);
		}

		return $this->db->queryException($query, $bindvars);
	}


	/**
	 * Deletes a multiple records from the table matching the provided conditions.
	 * Conditions use exact matching. Multiple conditions will result in AND matching.
	 *
	 * The method works just like delete, except that it does not have the one record
	 * limitation.
	 */
	function deleteMultiple(array $conditions)
	{
		$bindvars = [];
		$query = $this->buildDelete($conditions, $bindvars);

		return $this->db->queryException($query, $bindvars);
	}

	function fetchOne($field, array $conditions, $orderClause = null)
	{
		if ($result = $this->fetchRow([$field], $conditions, $orderClause)) {
			return reset($result);
		}

		return false;
	}

	function fetchCount(array $conditions)
	{
		return $this->fetchOne($this->count(), $conditions);
	}

	function fetchFullRow(array $conditions, $orderClause = null)
	{
		return $this->fetchRow($this->all(), $conditions, $orderClause);
	}

	function fetchRow(array $fields, array $conditions, $orderClause = null)
	{
		$result = $this->fetchAll($fields, $conditions, 1, 0, $orderClause);

		return reset($result);
	}

	function fetchColumn($field, array $conditions, $numrows = -1, $offset = -1, $order = null)
	{
		if (is_string($order)) {
			$order = [$field => $order];
		}

		$result = $this->fetchAll([$field], $conditions, $numrows, $offset, $order);

		$output = [];

		foreach ($result as $row) {
			$output[] = reset($row);
		}

		return $output;
	}

	function fetchMap($keyField, $valueField, array $conditions, $numrows = -1, $offset = -1, $order = null)
	{
		$result = $this->fetchAll([$keyField, $valueField], $conditions, $numrows, $offset, $order);

		$map = [];

		foreach ($result as $row) {
			$key = $row[$keyField];
			$value = $row[$valueField];

			$map[ $key ] = $value;
		}

		return $map;
	}

	function fetchAll(array $fields = [], array $conditions = [], $numrows = -1, $offset = -1, $orderClause = null)
	{
		$bindvars = [];

		$fieldDescription = '';

		foreach ($fields as $k => $f) {
			if ($f instanceof TikiDB_Expr) {
				$fieldDescription .= $f->getQueryPart(null);
				$bindvars = array_merge($bindvars, $f->getValues());
			} else {
				$fieldDescription .= $this->escapeIdentifier($f);
			}

			if (is_string($k)) {
				$fieldDescription .= ' AS ' . $this->escapeIdentifier($k);
			}

			$fieldDescription .= ', ';
		}

		$query = 'SELECT ';
		$query .= (! empty($fieldDescription)) ? rtrim($fieldDescription, ', ') : '*';
		$query .= ' FROM ' . $this->escapeIdentifier($this->tableName);
		$query .= $this->buildConditions($conditions, $bindvars);
		$query .= $this->buildOrderClause($orderClause);

		return $this->db->fetchAll($query, $bindvars, $numrows, $offset, $this->errorMode);
	}

	function expr($string, $arguments = [])
	{
		return new TikiDb_Expr($string, $arguments);
	}

	function all()
	{
		return [$this->expr('*')];
	}

	function count()
	{
		return $this->expr('COUNT(*)');
	}

	function sum($field)
	{
		return $this->expr("SUM(`$field`)");
	}

	function max($field)
	{
		return $this->expr("MAX(`$field`)");
	}

	function min($field)
	{
		return $this->expr("MIN(`$field`)");
	}

	function increment($count)
	{
		return $this->expr('$$ + ?', [$count]);
	}

	function decrement($count)
	{
		return $this->expr('$$ - ?', [$count]);
	}

	function greaterThan($value)
	{
		return $this->expr('$$ > ?', [$value]);
	}

	function lesserThan($value)
	{
		return $this->expr('$$ < ?', [$value]);
	}

	function not($value)
	{
		if (empty($value)) {
			return $this->expr('($$ <> ? AND $$ IS NOT NULL)', [$value]);
		} else {
			return $this->expr('$$ <> ?', [$value]);
		}
	}

	function like($value)
	{
		return $this->expr('$$ LIKE ?', [$value]);
	}

	function unlike($value)
	{
		return $this->expr('$$ NOT LIKE ?', [$value]);
	}

	function exactly($value)
	{
		return $this->expr('BINARY $$ = ?', [$value]);
	}

	function in(array $values, $caseSensitive = false)
	{
		if (empty($values)) {
			return $this->expr('1=0', []);
		} else {
			return $this->expr(($caseSensitive ? 'BINARY ' : '') . '$$ IN(' . rtrim(str_repeat('?, ', count($values)), ', ') . ')', $values);
		}
	}

	function notIn(array $values, $caseSensitive = false)
	{
		if (empty($values)) {
			return $this->expr('1=0', []);
		} else {
			return $this->expr(($caseSensitive ? 'BINARY ' : '') . '$$ NOT IN(' . rtrim(str_repeat('?, ', count($values)), ', ') . ')', $values);
		}
	}

	function findIn($value, array $fields)
	{
		$expr = $this->like("%$value%");

		return $this->any(array_fill_keys($fields, $expr));
	}

	function concatFields(array $fields)
	{
		$fields = array_map([$this, 'escapeIdentifier'], $fields);
		$fields = implode(', ', $fields);

		$expr = '';
		if ($fields) {
			$expr = "CONCAT($fields)";
		}

		return $this->expr($expr);
	}

	function any(array $conditions)
	{
		$binds = [];
		$parts = [];

		foreach ($conditions as $field => $expr) {
			$parts[] = $expr->getQueryPart($this->escapeIdentifier($field));
			$binds = array_merge($binds, $expr->getValues());
		}

		return $this->expr('(' . implode(' OR ', $parts) . ')', $binds);
	}

	function sortMode($sortMode)
	{
		return $this->expr($this->db->convertSortMode($sortMode));
	}

	private function buildDelete(array $conditions, & $bindvars)
	{
		$query = "DELETE FROM {$this->escapeIdentifier($this->tableName)}";
		$query .= $this->buildConditions($conditions, $bindvars);

		return $query;
	}

	private function buildConditions(array $conditions, & $bindvars)
	{
		$query = " WHERE 1=1";

		foreach ($conditions as $key => $value) {
			$field = $this->escapeIdentifier($key);
			if ($value instanceof TikiDb_Expr) {
				$query .= " AND {$value->getQueryPart($field)}";
				$bindvars = array_merge($bindvars, $value->getValues());
			} elseif (empty($value)) {
				$query .= " AND ($field = ? OR $field IS NULL)";
				$bindvars[] = $value;
			} else {
				$query .= " AND $field = ?";
				$bindvars[] = $value;
			}
		}

		return $query;
	}

	private function buildOrderClause($orderClause)
	{
		if ($orderClause instanceof TikiDb_Expr) {
			return ' ORDER BY ' . $orderClause->getQueryPart(null);
		} elseif (is_array($orderClause) && ! empty($orderClause)) {
			$part = ' ORDER BY ';

			foreach ($orderClause as $key => $direction) {
				$part .= "`$key` $direction, ";
			}

			return rtrim($part, ', ');
		}
	}

	private function buildUpdate(array $values, array $conditions, & $bindvars)
	{
		$query = "UPDATE {$this->escapeIdentifier($this->tableName)} SET ";

		$query .= $this->buildUpdateList($values, $bindvars);
		$query .= $this->buildConditions($conditions, $bindvars);

		return $query;
	}

	private function buildUpdateList($values, & $bindvars)
	{
		$query = '';

		foreach ($values as $key => $value) {
			$field = $this->escapeIdentifier($key);
			if ($value instanceof TikiDb_Expr) {
				$query .= "$field = {$value->getQueryPart($field)}, ";
				$bindvars = array_merge($bindvars, $value->getValues());
				$bindvars = SanitizeEncoding::filter($bindvars);
			} else {
				$query .= "$field = ?, ";
				$bindvars[] = SanitizeEncoding::filter($value);
			}
		}

		return rtrim($query, ' ,');
	}

	private function buildInsert($values, $ignore, & $bindvars)
	{
		$fieldDefinition = implode(', ', array_map([$this, 'escapeIdentifier'], array_keys($values)));
		$fieldPlaceholders = rtrim(str_repeat('?, ', count($values)), ' ,');

		if ($ignore) {
			$ignore = ' IGNORE';
		}

		$bindvars = array_merge($bindvars, SanitizeEncoding::filter(array_values($values)));
		return "INSERT$ignore INTO {$this->escapeIdentifier($this->tableName)} ($fieldDefinition) VALUES ($fieldPlaceholders)";
	}

	protected function escapeIdentifier($identifier)
	{
		return "`$identifier`";
	}
}
