<?php
// (c) Copyright 2002-2016 by authors of the Tiki Wiki CMS Groupware Project
//
// All Rights Reserved. See copyright.txt for details and a complete list of authors.
// Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See license.txt for details.
// $Id$

class Search_Indexer
{
	private $searchIndex;
	private $contentSources = [];
	private $globalSources = [];
	private $addonSources = [];

	private $cacheGlobals = null;
	private $cacheTypes = [];

	private $contentFilters = [];

	public $log = null;

	public function __construct(Search_Index_Interface $searchIndex, $logWriter = null)
	{
		if (! $logWriter instanceof \Zend\Log\Writer\AbstractWriter) {
			$logWriter = new Zend\Log\Writer\Noop();
		} else {
			// writing logs
			set_error_handler(function ($errno, $errstr, $errfile = '', $errline = 0) {

				$error = '';

				switch ($errno) {
					case E_PARSE:
					case E_ERROR:
					case E_CORE_ERROR:
					case E_COMPILE_ERROR:
					case E_USER_ERROR:
						$error = 'FATAL';
						break;
					case E_WARNING:
					case E_USER_WARNING:
					case E_COMPILE_WARNING:
					case E_RECOVERABLE_ERROR:
						$error = 'WARNING';
						break;
					case E_NOTICE:
					case E_USER_NOTICE:
						$error = 'NOTICE';
						break;
					case E_STRICT:
						$error = 'STRICT';
						break;
					case E_DEPRECATED:
					case E_USER_DEPRECATED:
						$error = 'DEPRECATED';
						break;
					default :
						break;
				}

				$this->log->err($error . ': ' . $errstr, [
					'code' => $errno,
					'file' => $errfile,
					'line' => $errline,
				]);

				return true;
			});

			// set smarty error muting again after declaring our handler becasue smarty is set up in tiki-setup.php
			// before we get here so smarty then doesn't know about us
			Smarty_Tiki::muteExpectedErrors();
		}

		$logWriter->setFormatter(new Zend\Log\Formatter\Simple());
		$this->log = new Zend\Log\Logger();
		$this->log->addWriter($logWriter);

		$this->searchIndex = $searchIndex;

		$api = new TikiAddons_Api_Search();
		$this->addonSources = $api->getAddonSources();
	}

	public function addContentSource($objectType, Search_ContentSource_Interface $contentSource)
	{
		$this->contentSources[$objectType] = $contentSource;
	}

	public function addGlobalSource(Search_GlobalSource_Interface $globalSource)
	{
		if (is_a($globalSource, "Search_GlobalSource_RelationSource")) {
			$globalSource->setContentSources($this->contentSources);
		}
		$this->globalSources[] = $globalSource;
	}

	public function clearSources()
	{
		$this->contentSources = [];
		$this->globalSources = [];
	}

	public function addContentFilter(Zend\Filter\FilterInterface $filter)
	{
		$this->contentFilters[] = $filter;
	}

	/**
	 * Rebuild the entire index.
	 * @return array
	 */
	public function rebuild()
	{
		$this->log('Starting rebuild');
		$stat = array_fill_keys(array_keys($this->contentSources), 0);

		foreach ($this->contentSources as $objectType => $contentSource) {
			foreach ($contentSource->getDocuments() as $objectId) {
				$stat[$objectType] += $this->addDocument($objectType, $objectId);
			}
		}

		$this->log('Starting optimization');
		$this->searchIndex->optimize();
		$this->log('Finished optimization');
		$this->log('Finished rebuild');
		return $stat;
	}

	public function update(array $objectList)
	{

		foreach (array_unique($objectList, SORT_REGULAR) as $object) {
			$this->searchIndex->invalidateMultiple([$object]);
			$this->addDocument($object['object_type'], $object['object_id']);
		}

		$this->searchIndex->endUpdate();
	}

	private function addDocument($objectType, $objectId)
	{
		$this->log("addDocument $objectType $objectId");

		$data = $this->getDocuments($objectType, $objectId);
		foreach ($data as $entry) {
			try {
				$this->searchIndex->addDocument($entry);
			} catch (Exception $e) {
				$msg = tr(
					'Indexing failed while processing "%0" (type %1) with the error "%2"',
					$objectId,
					$objectType,
					$e->getMessage()
				);
				Feedback::error($msg, 'session');
				$this->log->err($msg);
			}
		}

		return count($data);
	}


	/**
	 * Return all supported content types and their fields
	 *
	 * @return array
	 */
	public function getAvailableFields()
	{
		$output = [
			'global' => [],
			'object_types' => [],
		];
		/**
		 * @var  string $objectType
		 * @var  Search_ContentSource_Interface $contentSource
		 */
		foreach ($this->contentSources as $objectType => $contentSource) {
			$output['object_types'][$objectType] = $contentSource->getProvidedFields();
			$output['global'] = array_unique(
				array_merge(
					$output['global'],
					array_keys(
						array_filter($contentSource->getGlobalFields())
					)
				)
			);
		}

		return $output;
	}

	public function getDocuments($objectType, $objectId)
	{
		$out = [];

		$typeFactory = $this->searchIndex->getTypeFactory();

		if (isset($this->contentSources[$objectType])) {
			$globalFields = $this->getGlobalFields($objectType);

			$contentSource = $this->contentSources[$objectType];

			if (false !== $data = $contentSource->getDocument($objectId, $typeFactory)) {
				if ($data === null) {
					Feedback::error(tr(
						'Object %0 type %1 returned null from getDocument function',
						$objectId,
						$objectType
					), 'session');
					$data = [];
				}
				if (! is_int(key($data))) {
					$data = [$data];
				}

				foreach ($data as $entry) {
					$out[] = $this->augmentDocument($objectType, $objectId, $entry, $typeFactory, $globalFields);
				}
			}
		}

		return $out;
	}

	private function augmentDocument($objectType, $objectId, $data, $typeFactory, $globalFields)
	{
		$initialData = $data;

		foreach ($this->globalSources as $globalSource) {
			$local = $globalSource->getData($objectType, $objectId, $typeFactory, $initialData);

			if (false !== $local) {
				$data = array_merge($data, $local);
			}
		}
		foreach ($this->addonSources as $addonSource) {
			if ($addonSource->toIndex($objectType, $objectId, $initialData)) {
				$local = $addonSource->getData($objectType, $objectId, $typeFactory, $initialData);

				if (false !== $local) {
					$data = array_merge($data, $local);
				}
			}
		}

		$base = [
			'object_type' => $typeFactory->identifier($objectType),
			'object_id' => $typeFactory->identifier($objectId),
			'contents' => $typeFactory->plaintext($this->getGlobalContent($data, $globalFields)),
		];

		$data = array_merge(array_filter($data), $base);
		$data = $this->applyFilters($data);

		$data = $this->removeTemporaryKeys($data);

		return $data;
	}

	private function applyFilters($data)
	{
		$keys = array_keys($data);

		foreach ($keys as $key) {
			$value = $data[$key];

			if (is_callable([$value, 'filter'])) {
				$data[$key] = $value->filter($this->contentFilters);
			}
		}

		return $data;
	}

	private function removeTemporaryKeys($data)
	{
		$keys = array_keys($data);
		$toRemove = array_filter(
			$keys,
			function ($key) {
				return $key{0} === '_';
			}
		);

		foreach ($keys as $key) {
			if ($key{0} === '_') {
				unset($data[$key]);
			}
		}

		return $data;
	}

	private function getGlobalContent(array & $data, $globalFields)
	{
		$content = '';

		foreach ($globalFields as $name => $preserve) {
			if (isset($data[$name])) {
				$v = $data[$name]->getValue();
				if (is_string($v)) {
					$content .= $v . ' ';

					if (! $preserve) {
						$data[$name] = false;
					}
				}
			}
		}

		return $content;
	}

	private function getGlobalFields($objectType)
	{
		if (is_null($this->cacheGlobals)) {
			$this->cacheGlobals = [];
			foreach ($this->globalSources as $source) {
				$this->cacheGlobals = array_merge($this->cacheGlobals, $source->getGlobalFields());
			}
		}

		if (! isset($this->cacheTypes[$objectType])) {
			$this->cacheTypes[$objectType] = array_merge($this->cacheGlobals, $this->contentSources[$objectType]->getGlobalFields());
		}

		return $this->cacheTypes[$objectType];
	}

	private function log($message)
	{
		$this->log->info($message, ['memoryUsage' => \Symfony\Component\Console\Helper\Helper::formatMemory(memory_get_usage())]);
	}
}
