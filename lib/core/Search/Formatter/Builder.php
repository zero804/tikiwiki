<?php
// (c) Copyright 2002-2013 by authors of the Tiki Wiki CMS Groupware Project
// 
// All Rights Reserved. See copyright.txt for details and a complete list of authors.
// Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See license.txt for details.
// $Id$

class Search_Formatter_Builder
{
	private $parser;
	private $paginationArguments;

	private $formatterPlugin;
	private $subFormatters = array();
	private $alternateOutput;

	function __construct()
	{
		$this->parser = new WikiParser_PluginArgumentParser;
		$this->paginationArguments = array(
			'offset_arg' => 'offset',
			'max' => 50,
		);
	}

	function setPaginationArguments($arguments)
	{
		$this->paginationArguments = $arguments;
	}

	function setFormatterPlugin(Search_Formatter_Plugin_Interface $plugin)
	{
		$this->formatterPlugin = $plugin;
	}

	function apply($matches)
	{
		foreach ($matches as $match) {
			$name = $match->getName();

			if ($name == 'output') {
				$this->handleOutput($match);
			}

			if ($name == 'format') {
				$this->handleFormat($match);
			}

			if ($name == 'alternate') {
				$this->handleAlternate($match);
			}
		}
	}

	function getFormatter()
	{
		$plugin = $this->formatterPlugin;
		if (! $plugin) {
			$plugin = new Search_Formatter_Plugin_WikiTemplate("* {display name=title format=objectlink}\n");
		}

		$formatter = new Search_Formatter($plugin);

		if ($this->alternateOutput > '') {
			$formatter->setAlternateOutput($this->alternateOutput);
		} else {
			$formatter->setAlternateOutput('^' . tra('No results for query.') . '^');
		}

		foreach ($this->subFormatters as $name => $plugin) {
			$formatter->addSubFormatter($name, $plugin);
		}

		return $formatter;
	}

	private function handleFormat($match)
	{
		$arguments = $this->parser->parse($match->getArguments());

		if (isset($arguments['name'])) {
			$plugin = new Search_Formatter_Plugin_WikiTemplate($match->getBody());
			$plugin->setRaw(! empty($arguments['mode']) && $arguments['mode'] == 'raw');
			$this->subFormatters[$arguments['name']] = $plugin;
		}
	}

	private function handleAlternate($match)
	{
		$this->alternateOutput = $match->getBody();
	}

	private function handleOutput($output)
	{
		$arguments = $this->parser->parse($output->getArguments());

		if (isset($arguments['template'])) {
			if ($arguments['template'] == 'table') {
				$arguments['template'] = dirname(__FILE__) . '/../../../../templates/table.tpl';
				$arguments['pagination'] = true;
			} else if (!file_exists($arguments['template'])) {
				TikiLib::lib('errorreport')->report(tr('Missing template "%0"', $arguments['template']));
				return '';
			}
			$abuilder = new Search_Formatter_ArrayBuilder;
			$outputData = $abuilder->getData($output->getBody());
			foreach ($this->paginationArguments as $k => $v) {
				$outputData[$k] = $this->paginationArguments[$k];
			}
			$templateData = file_get_contents($arguments['template']);

			$plugin = new Search_Formatter_Plugin_SmartyTemplate($arguments['template']);
			$plugin->setData($outputData);
			$plugin->setFields($this->findFields($outputData, $templateData));
		} elseif (isset($arguments['wiki']) && TikiLib::lib('tiki')->page_exists($arguments['wiki'])) {	
			$wikitpl = "tplwiki:" . $arguments['wiki'];
			$wikicontent = TikiLib::lib('smarty')->fetch($wikitpl);
			$plugin = new Search_Formatter_Plugin_WikiTemplate($wikicontent);
		} else {
			$plugin = new Search_Formatter_Plugin_WikiTemplate($output->getBody());
		}

		if (isset($arguments['pagination'])) {

			$plugin = new Search_Formatter_AppendPagination($plugin, $this->paginationArguments);
		}

		$this->formatterPlugin = $plugin;
	}

	private function findFields($outputData, $templateData)
	{
		$outputData = TikiLib::array_flat($outputData);

		// Heuristic based: only lowercase letters, digits and underscore
		$fields = array();
		foreach ($outputData as $candidate) {
			if (preg_match("/^[a-z0-9_]+$/", $candidate) || substr($candidate, 0, strlen('tracker_field_')) === 'tracker_field_') {
				$fields[] = $candidate;
			}
		}

		preg_match_all('/\$(result|row|res)\.([a-z0-9_]+)[\|\}\w]+/', $templateData, $matches);
		$fields = array_merge($fields, $matches[2]);	

		$fields = array_flip(array_unique($fields));

		return $fields;
	}
}

