<?php
// (c) Copyright by authors of the Tiki Wiki CMS Groupware Project
//
// All Rights Reserved. See copyright.txt for details and a complete list of authors.
// Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See license.txt for details.
// $Id$

namespace Tracker\Tabular\Writer;

class ODBCWriter
{
	private $odbc_manager;

	function __construct($config)
	{
		$this->odbc_manager = new \Tracker\Tabular\ODBCManager($config);
	}

	function write(\Tracker\Tabular\Source\SourceInterface $source)
	{
		$schema = $source->getSchema();
		$schema->validate();

		$columns = $schema->getColumns();
		foreach ($source->getEntries() as $entry) {
			$row = [];
			$pk = null;
			$id = null;
			foreach ($columns as $column) {
				$row[$column->getRemoteField()] = $entry->render($column);
				if ($column->isPrimaryKey()) {
					$pk = $column->getRemoteField();
					$id = $row[$pk];
				}
			}
			$this->odbc_manager->replace($pk, $id, $row);
		}
	}

	function sync(\Tracker\Tabular\Schema $schema, array $entry, array $full_entry)
	{
		$schema->validate();
		$columns = $schema->getColumns();

		$row = [];
		$pk = null;
		$id = null;
		$mapping = [];
		foreach ($columns as $column) {
			if (! isset($entry[$column->getField()]) && ! $column->isPrimaryKey()) {
				continue;
			}
			$mapping[$column->getRemoteField()] = $column->getField();
			$row[$column->getRemoteField()] = $full_entry[$column->getField()];
			if ($column->isPrimaryKey()) {
				$pk = $column->getRemoteField();
				$id = $row[$pk];
				if ($schema->isPrimaryKeyAutoIncrement()) {
					unset($row[$pk]);
				}
			}
		}
		$result = $this->odbc_manager->replace($pk, $id, $row);
		$mapped = [];
		foreach ($result as $remoteField => $value) {
			if (isset($mapping[$remoteField])) {
				$mapped[$mapping[$remoteField]] = $value;
			}
		}
		return $mapped;
	}

	function delete($pk, $id) {
		return $this->odbc_manager->delete($pk, $id);
	}
}
