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
			foreach ($columns as $column) {
				$row[$column->getRemoteField()] = $entry->render($column);
				if ($column->isPrimaryKey()) {
					$pk = $column->getRemoteField();
				}
			}
			$this->odbc_manager->replace($pk, $row);
		}
	}
}
