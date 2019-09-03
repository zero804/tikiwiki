<?php
// (c) Copyright by authors of the Tiki Wiki CMS Groupware Project
//
// All Rights Reserved. See copyright.txt for details and a complete list of authors.
// Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See license.txt for details.
// $Id$

class Search_Elastic_FacetBuilder
{
	private $count;
	private $mainKey;

	function __construct($count = 10, $useAggregations = false)
	{
		$this->count = $count;
		$this->mainKey = $useAggregations ? 'aggregations' : 'facets';
	}

	function build(array $facets)
	{
		if (empty($facets)) {
			return [];
		}

		$out = [];
		foreach ($facets as $facet) {
			$out[$facet->getName()] = $this->buildFacet($facet);
		}

		return [
			$this->mainKey => $out,
		];
	}

	private function buildFacet(Search_Query_Facet_Interface $facet)
	{
		return ['terms' => [
			'field' => $facet->getField(),
			'size' => $facet->getCount() ?: $this->count,
		]];
	}
}
