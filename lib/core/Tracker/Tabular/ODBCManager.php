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
		$this->handlingErrors(function() use (&$result) {
			$conn = $this->getConnection();
			$columns = odbc_columns($conn, '', '', $this->config['table'], '%');
			while ($row = odbc_fetch_array($columns)) {
				$result[] = $row;
			}
		});
		return $result;
	}

	public function iterate($fields) {
		$select = implode(', ', $fields);
		$sql = "SELECT {$select} FROM {$this->config['table']}";
		$this->handlingErrors(function() use ($sql) {
			$conn = $this->getConnection();
			$rs = odbc_exec($conn, $sql);
			while ($row = odbc_fetch_array($rs)) {
				yield $row;
			}
		});
	}

	public function replace($pk, $row) {
		$this->handlingErrors(function() use ($pk, $row) {
			$conn = $this->getConnection();
			$id = $row[$pk];
			$sql = "SELECT {$pk} FROM {$this->config['table']} WHERE {$pk} = ?";
			$rs = odbc_prepare($conn, $sql);
			odbc_execute($rs, [$id]);
			if (odbc_num_rows($rs) > 0) {
				$sql = "UPDATE {$this->config['table']} SET ".implode(', ', array_map(function($k) { return "{$k} = ?"; }, array_keys($row)))." WHERE {$pk} = ?";
				$rs = odbc_prepare($conn, $sql);
				$params = array_values($row);
				$params[] = $id;
				odbc_execute($rs, $params);
				if (odbc_error()) {
					throw new \Exception(tr("Error updating remote item: %0", odbc_errormsg()));
				}
			} else {
				$sql = "INSERT INTO {$this->config['table']}(".implode(', ', array_keys($row)).") VALUES (".implode(", ", array_fill(0, count(array_keys($row)), '?')).")";
				$rs = odbc_prepare($conn, $sql);
				odbc_execute($rs, array_values($row));
				if (odbc_error()) {
					throw new \Exception(tr("Error inserting remote item: %0", odbc_errormsg()));
				}
			}
		});
	}

	private function getConnection() {
		return odbc_connect($this->config['dsn'], $this->config['user'], $this->config['password']);
	}

	private function handlingErrors($cb) {
		$orig = set_error_handler(function($errno, $errstr, $errfile, $errline) {
			$this->errors[] = $errstr;
		});
		$cb();
		set_error_handler($orig);
		if ($this->errors) {
			throw new \Exception($this->errors[0]);
		}
	}
}
