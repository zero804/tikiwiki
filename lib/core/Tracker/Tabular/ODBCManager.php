<?php
// (c) Copyright by authors of the Tiki Wiki CMS Groupware Project
//
// All Rights Reserved. See copyright.txt for details and a complete list of authors.
// Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See license.txt for details.
// $Id$

namespace Tracker\Tabular;

class ODBCManager
{
	private $config;
	private $errors;

	function __construct($config)
	{
		$this->config = $config;
	}

	public function getSchema() {
		$result = [];
		$this->handleErrors();
		$conn = $this->getConnection();
		$columns = odbc_columns($conn, $this->getCatalog(), '%', $this->config['table'], '%');
		while ($row = odbc_fetch_array($columns)) {
			$result[] = $row;
		}
		$this->stopErrorHandler();
		return $result;
	}

	public function iterate($fields) {
		$this->handleErrors();
		$select = implode('", "', $fields);
		$sql = "SELECT \"{$select}\" FROM {$this->config['table']}";
		$conn = $this->getConnection();
		$rs = odbc_exec($conn, $sql);
		while ($row = odbc_fetch_array($rs)) {
			yield $row;
		}
		$this->stopErrorHandler();
	}

	public function replace($pk, $row) {
		$this->handleErrors();
		$conn = $this->getConnection();
		$id = $row[$pk];
		$sql = "SELECT \"{$pk}\" FROM {$this->config['table']} WHERE {$pk} = ?";
		$rs = odbc_prepare($conn, $sql);
		odbc_execute($rs, [$id]);
		if (odbc_num_rows($rs) > 0) {
			$sql = "UPDATE {$this->config['table']} SET ".implode(', ', array_map(function($k) { return "\"{$k}\" = ?"; }, array_keys($row)))." WHERE \"{$pk}\" = ?";
			$rs = odbc_prepare($conn, $sql);
			$params = array_map(function($v){ return empty($v) ? null : $v; }, array_values($row));
			$params[] = $id;
			odbc_execute($rs, $params);
			if (odbc_error()) {
				throw new \Exception(tr("Error updating remote item: %0", odbc_errormsg()));
			}
		} else {
			$sql = "INSERT INTO {$this->config['table']}(\"".implode('", "', array_keys($row))."\") VALUES (".implode(", ", array_fill(0, count(array_keys($row)), '?')).")";
			$rs = odbc_prepare($conn, $sql);
			odbc_execute($rs, array_values($row));
			if (odbc_error()) {
				throw new \Exception(tr("Error inserting remote item: %0", odbc_errormsg()));
			}
		}
		$this->stopErrorHandler();
	}

	private function getConnection() {
		return odbc_connect($this->config['dsn'], $this->config['user'], $this->config['password']);
	}

	private function getCatalog() {
		if (preg_match('/Database=(.*);/i', $this->config['dsn'], $m)) {
			return $m[1];
		} else {
			return '';
		}
	}

	private function handleErrors() {
		$this->orig_handler = set_error_handler(function($errno, $errstr, $errfile, $errline) {
			$this->errors[] = $errstr;
		});
	}

	private function stopErrorHandler() {
		set_error_handler($this->orig_handler);
		if ($this->errors) {
			throw new \Exception($this->errors[0]);
		}
	}
}
