<?php
// (c) Copyright by authors of the Tiki Wiki CMS Groupware Project
//
// All Rights Reserved. See copyright.txt for details and a complete list of authors.
// Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See license.txt for details.
// $Id$

namespace Tracker\Tabular\Source;

class ODBCSource implements SourceInterface
{
	private $schema;
	private $odbc_manager;

	function __construct(\Tracker\Tabular\Schema $schema, array $odbc_config)
	{
		$this->schema = $schema;
		$this->odbc_manager = new \Tracker\Tabular\ODBCManager($odbc_config);
	}

	function getEntries()
	{
		$fields = [];
		foreach ($this->schema->getColumns() as $column) {
			$fields[] = $column->getRemoteField();
		}
		foreach ($this->odbc_manager->iterate($fields) as $row) {
			yield new ODBCSourceEntry($row);
		}
	}

	function getSchema()
	{
		return $this->schema;
	}

	function getRemoteSchema() {
		$result = [];
		$schema = $this->odbc_manager->getSchema();
		foreach ($schema as $row) {
			$result[] = [
				'name' => $row['COLUMN_NAME'],
				'type' => $row['TYPE_NAME'],
				'size' => $row['COLUMN_SIZE'],
				'remarks' => $row['REMARKS'],
			];
		}
		return $result;
	}
}
