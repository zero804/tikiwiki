<?php
// (c) Copyright by authors of the Tiki Wiki CMS Groupware Project
//
// All Rights Reserved. See copyright.txt for details and a complete list of authors.
// Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See license.txt for details.
// $Id$

class Search_Query_FacetWikiBuilder
{
	private $facets = [];

	function apply(WikiParser_PluginMatcher $matches)
	{
		$argumentParser = new WikiParser_PluginArgumentParser;

		foreach ($matches as $match) {
			if ($match->getName() === 'facet') {
				$arguments = $argumentParser->parse($match->getArguments());
				$operator = isset($arguments['operator']) ? $arguments['operator'] : 'or';
				$count = isset($arguments['count']) ? $arguments['count'] : null;

				if (isset($arguments['name'])) {
					$this->facets[] = [
						'name' => $arguments['name'],
						'operator' => $operator,
						'count' => $count,
						'order' => isset($arguments['order']) ? $arguments['order'] : null,
						'min' => isset($arguments['min']) ? $arguments['min'] : null,
					];
				}
			}
		}
	}

	function build(Search_Query $query, Search_FacetProvider $provider)
	{
		foreach ($this->facets as $facet) {
			if ($real = $provider->getFacet($facet['name'])) {
				$real->setOperator($facet['operator']);

				if ($facet['count']) {
					$real->setCount($facet['count']);
				}

				if ($facet['order']) {
					$real->setOrder($facet['order']);
				}

				if ($facet['min'] !== null) {
					$real->setMinDocCount($facet['min']);
				}

				$query->requestFacet($real);
			}
		}
	}
}
