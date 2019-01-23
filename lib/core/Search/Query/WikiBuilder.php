<?php
// (c) Copyright by authors of the Tiki Wiki CMS Groupware Project
//
// All Rights Reserved. See copyright.txt for details and a complete list of authors.
// Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See license.txt for details.
// $Id$

class Search_Query_WikiBuilder
{
	private $query;
	private $input;
	private $paginationArguments;
	private $aggregate = false;
	private $boost = 1;

	function __construct(Search_Query $query, $input = null)
	{
		global $prefs;
		if (! empty($prefs['maxRecords'])) {
			$max = $prefs['maxRecords'];
		} else {
			$max = 50;
		}

		$this->query = $query;
		$this->input = ( $input ?: new JitFilter(@$_REQUEST) );
		$this->paginationArguments = [
			'offset_arg' => 'offset',
			'sort_arg' => 'sort_mode',
			'max' => $max,
		];
	}

	/**
	 * Only boost max page on aggregate when the calling code
	 * handles the resultset properly.
	 */
	function enableAggregate()
	{
		$this->aggregate = true;
	}

	function apply(WikiParser_PluginMatcher $matches)
	{
		$argumentParser = new WikiParser_PluginArgumentParser;

		foreach ($matches as $match) {
			$name = $match->getName();
			$arguments = $argumentParser->parse($match->getArguments());

			$this->addQueryArgument($name, $arguments);
		}

		$this->applyPagination();
	}

	function applyPagination()
	{
		$offsetArg = $this->paginationArguments['offset_arg'];
		$maxRecords = $this->paginationArguments['max'];
		if (isset($_REQUEST[$offsetArg])) {
			$this->query->setRange($_REQUEST[$offsetArg], $maxRecords * $this->boost);
		} else {
			$this->query->setRange(0, $maxRecords * $this->boost);
		}
	}

	function addQueryArgument($name, $arguments)
	{
		foreach ($arguments as $key => $value) {
			$function = "wpquery_{$name}_{$key}";

			if (method_exists($this, $function)) {
				call_user_func([$this, $function], $this->query, $value, $arguments);
			}
		}
	}

	function getPaginationArguments()
	{
		return $this->paginationArguments;
	}

	function wpquery_list_max($query, $value)
	{
		$this->paginationArguments['max'] = max(1, (int) $value);
	}

	function wpquery_filter_editable($query, $editableType, array $arguments)
	{
		$fields = $this->get_fields_from_arguments($arguments);
		foreach ($fields as $fieldName) {
			$fieldName = str_replace('tracker_field_', '', $fieldName);
			$filter = Tracker\Filter\Collection::getFilter($fieldName, $editableType);
			$filter->applyInput($this->input);
			$filter->applyCondition($query);
		}
	}

	/**
	 * Handle return only the list of results defined by the user
	 *
	 * @param $query
	 * @param $value
	 * @param array $arguments
	 * @return void
	 */
	protected function wpquery_sort_return_only($query, $value, $arguments)
	{
		$returnOnlyResultList = [];
		if (! empty($arguments['return_only'])) {
			$returnOnlyResultList = array_map('trim', explode(',', $arguments['return_only']));
		}
		if (! empty($returnOnlyResultList)) {
			$query->setReturnOnlyResultList($returnOnlyResultList);
		}
	}

	function wpquery_filter_type($query, $value)
	{
		$value = explode(',', $value);
		$query->filterType($value);
	}

	function wpquery_filter_nottype($query, $value)
	{
		$value = explode(',', $value);
		$value = array_map(
			function ($v) {
				return "NOT \"$v\"";
			},
			$value
		);
		$query->filterContent(implode(' AND ', $value), 'object_type');
	}

	function wpquery_filter_categories($query, $value)
	{
		$query->filterCategory($value);
	}

	function wpquery_filter_contributors($query, $value)
	{
		$query->filterContributors($value);
	}

	function wpquery_filter_deepcategories($query, $value)
	{
		$query->filterCategory($value, true);
	}

	function wpquery_filter_multivalue($query, $value, array $arguments)
	{
		if (isset($arguments['field'])) {
			$fields = explode(',', $arguments['field']);
		} else {
			$fields = 'nomatch';
		}

		$query->filterMultivalue($value, $fields);
	}

	function wpquery_filter_content($query, $value, array $arguments)
	{
		$fields = $this->get_fields_from_arguments($arguments);
		$query->filterContent($value, $fields);
	}

	function wpquery_filter_exact($query, $value, array $arguments)
	{
		$fields = $this->get_fields_from_arguments($arguments);
		$query->filterIdentifier($value, $fields);
	}

	function wpquery_filter_language($query, $value)
	{
		$query->filterLanguage($value);
	}

	function wpquery_filter_relation($query, $value, $arguments)
	{
		if (! isset($arguments['qualifier'], $arguments['objecttype'])) {
			Feedback::error(tr('Missing objectype or qualifier for relation filter.'));
		}

		/* custom mani for OR operation in relation filter */
		$qualifiers = explode(' OR ', $arguments['qualifier']);
		if (count($qualifiers) > 1) {
			$token = '';
			foreach ($qualifiers as $key => $qualifier) {
				$token .= (string) new Search_Query_Relation($qualifier, $arguments['objecttype'], $value);
				if (count($qualifiers) != ($key + 1)) {
					$token .= " OR ";
				}
			}
		} else {
			$token = (string) new Search_Query_Relation($arguments['qualifier'], $arguments['objecttype'], $value);
		}
		$query->filterRelation($token);
	}

	function wpquery_filter_favorite($query, $value)
	{
		$this->wpquery_filter_relation($query, $value, ['qualifier' => 'tiki.user.favorite.invert', 'objecttype' => 'user']);
	}

	function wpquery_filter_range($query, $value, array $arguments)
	{
		if (isset($arguments['from']) && ! is_numeric($arguments['from'])) {
			$time = strtotime($arguments['from']);
			if (! $time) {
				Feedback::error(tr('Range filter "from" parameter not valid: "%0"', $arguments['from']));
			} else {
				$arguments['from'] = $time;
			}
		}
		if (isset($arguments['to']) && ! is_numeric($arguments['to'])) {
			$time = strtotime($arguments['to']);
			if (! $time) {
				Feedback::error(tr('Range filter "to" parameter not valid: "%0"', $arguments['to']));
			} else {
				$arguments['to'] = $time;
			}
		}
		if (isset($arguments['gap']) && ! is_numeric($arguments['gap'])) {
			$time = strtotime($arguments['gap']);
			if (! $time) {
				Feedback::error(tr('Range filter "gap" parameter not valid: "%0"', $arguments['gap']));
			} else {
				$arguments['gap'] = $time - time();
			}
		}
		if (! isset($arguments['from']) && isset($arguments['to'], $arguments['gap'])) {
			$arguments['from'] = $arguments['to'] - $arguments['gap'];
		}
		if (! isset($arguments['to']) && isset($arguments['from'], $arguments['gap'])) {
			$arguments['to'] = $arguments['from'] + $arguments['gap'];
		}
		if (! isset($arguments['from'], $arguments['to'])) {
			Feedback::error(tr('The range filter is missing \"from\" or \"to\".'));
		}
		$query->filterRange($arguments['from'], $arguments['to'], $value);
	}

	function wpquery_filter_textrange($query, $value, array $arguments)
	{
		if (! isset($arguments['from'], $arguments['to'])) {
			Feedback::error(tr('The range filter is missing \"from\" or \"to\".'));
		}
		$query->filterTextRange($arguments['from'], $arguments['to'], $value);
	}

	function wpquery_filter_personalize($query, $type, array $arguments)
	{
		global $user;
		$targetUser = $user;

		if (! $targetUser) {
			$targetUser = "1"; // Invalid user name, make sure nothing matches
		}

		$subquery = $query->getSubQuery('personalize');

		$types = array_filter(array_map('trim', explode(',', $type)));

		if (in_array('self', $types)) {
			$subquery->filterContributors($targetUser);
			$subquery->filterContent($targetUser, 'user');
		}

		if (in_array('groups', $types)) {
			$part = new Search_Expr_Or(
				array_map(
					function ($group) {
						return new Search_Expr_Token($group, 'multivalue', 'user_groups');
					},
					Perms::get()->getGroups()
				)
			);
			$subquery->getExpr()->addPart(
				new Search_Expr_And(
					[
						$part,
						new Search_Expr_Not(
							new Search_Expr_Token($targetUser, 'identifier', 'user')
						),
					]
				)
			);
		}

		if (in_array('addongroups', $types)) {
			$api = new TikiAddons_Api_Group;
			$cats = $api->getOrganicGroupCatsForUser($targetUser);
			if (empty($cats)) {
				$subquery->filterCategory('impossible');
			} else {
				$subquery->filterCategory(implode(' ', $cats));
			}
		}

		if (in_array('follow', $types)) {
			$subquery->filterMultivalue($targetUser, 'user_followers');
		}

		$userId = TikiLib::lib('tiki')->get_user_id($targetUser);
		if (in_array('stream_critical', $types)) {
			$subquery->filterMultivalue("critical$userId", 'stream');
		}
		if (in_array('stream_high', $types)) {
			$subquery->filterMultivalue("high$userId", 'stream');
		}
		if (in_array('stream_low', $types)) {
			$subquery->filterMultivalue("low$userId", 'stream');
		}
	}

	function wpquery_filter_distance($query, $value, array $arguments)
	{
		if (! isset($arguments['distance'], $arguments['lat'], $arguments['lon'])) {
			Feedback::error(tr('The distance filter is missing \"distance\", \"lat\" or \"lon\".'));
		}
		$query->filterDistance($value, $arguments['lat'], $arguments['lon']);
	}

	function wpquery_sort_mode($query, $value, array $arguments)
	{
		if ($value == 'randommode') {
			if (! empty($arguments['modes'])) {
				$modes = explode(',', $arguments['modes']);
				$value = trim($modes[array_rand($modes)]);
				// append a direction if not already supplied
				$last = substr($value, strrpos($value, '_'));
				$directions = ['_asc', '_desc', '_nasc', '_ndesc'];
				if (! in_array($last, $directions)) {
					$direction = $directions[array_rand($directions)];
					if (stripos($value, 'date')) {
						$value .= $direction;
					} else {
						$value .= str_replace('n', '', $direction);
					}
				}
			} else {
				return;
			}
		} elseif ($value === 'distance') {
			if (isset($arguments['lat'], $arguments['lon'])) {
				$arguments = array_merge([	// defaults
					'order' => 'asc',
					'unit' => 'km',
					'distance_type' => 'sloppy_arc',
				], $arguments);

				$value = new Search_Query_Order('geo_point', 'distance', $arguments['order'], $arguments);
			} else {
				Feedback::error(tr('Distance sort: Missing lat or lon arguments'));
				return;
			}
		} elseif ($value === 'script') {
			if (isset($arguments['source'])) {

				$arguments['order'] = isset($arguments['order']) ? $arguments['order'] : 'asc';
				$arguments['lang']  = isset($arguments['lang'])  ? $arguments['lang']  : 'painless';
				$arguments['type']  = isset($arguments['type'])  ? $arguments['type']  : 'number';

				unset($arguments['mode']);

				// using a dummy field for now
				$value = new Search_Query_Order('_script', 'script', $arguments['order'], $arguments);
			} else {
				Feedback::error(tr('Script sort: Missing source argument'));
				return;
			}

		}
		$query->setOrder($value);
	}

	function wpquery_pagination_onclick($query, $value)
	{
		$this->paginationArguments['_onclick'] = $value;
	}

	function wpquery_pagination_offset_jsvar($query, $value)
	{
		$this->paginationArguments['offset_jsvar'] = $value;
	}

	function wpquery_pagination_offset_arg($query, $value)
	{
		$this->paginationArguments['offset_arg'] = $value;
	}

	function wpquery_pagination_sort_jsvar($query, $value)
	{
		$this->paginationArguments['sort_jsvar'] = $value;
	}

	function wpquery_pagination_sort_arg($query, $value)
	{
		$this->paginationArguments['sort_arg'] = $value;
	}

	function wpquery_pagination_max($query, $value)
	{
		$this->paginationArguments['max'] = (int) $value;
	}

	function wpquery_group_boost($query, $value)
	{
		if ($this->aggregate) {
			$this->boost *= max(1, intval($value));
		}
	}

	function isNextPossible()
	{
		return $this->boost == 1;
	}

	function applyTablesorter(WikiParser_PluginMatcher $matches, $hasactions = false)
	{
		$ret = ['max' => false, 'tsOn' => false];
		$parser = new WikiParser_PluginArgumentParser;
		$args = [];
		$tsc = [];
		$tsf = [];
		$tsenabled = Table_Check::isEnabled();

		foreach ($matches as $match) {
			$name = $match->getName();
			if ($name == 'tablesorter') {
				$tsargs = $parser->parse($match->getArguments());
				$ajax = ! empty($tsargs['server']) && $tsargs['server'] === 'y';
				$ret['tsOn'] = Table_Check::isEnabled($ajax);
				if (! $ret['tsOn']) {
					Feedback::error(tra('List plugin: Feature "jQuery Sortable Tables" (tablesorter) is not enabled'));
					return $ret;
				}
				if (isset($tsargs['tsortcolumns'])) {
					$tsc = Table_Check::parseParam($tsargs['tsortcolumns']);
				}
				if (isset($tsargs['tspaginate'])) {
					$tsp = Table_Check::parseParam($tsargs['tspaginate']);
					if (isset($tsp[0]['max']) && $ajax) {
						$ret['max'] = (int) $tsp[0]['max'];
					}
				}
				if (isset($tsargs['tsfilters'])) {
					$tsf = Table_Check::parseParam($tsargs['tsfilters']);
				}
			} elseif ($name == 'column') {
				$args[] = $parser->parse($match->getArguments());
			} elseif ($name == 'format' && $tsenabled) {
				// if fields have been "formatted" then get the original field name to filter on
				$formatArgs = $parser->parse($match->getArguments());

				// use first display subplugin or first one that ends in _text
				$displayArgsToUse = [];
				$subPlugins = WikiParser_PluginMatcher::match($match->getBody());
				foreach ($subPlugins as $subPlugin) {
					if ($subPlugin->getName() === 'display') {
						$displayArgs = $parser->parse($subPlugin->getArguments());
						if (empty($displayArgsToUse) || substr($displayArgs['name'], -5) === '_text') {
							$displayArgsToUse = $displayArgs;
						}
						if (! empty($displayArgsToUse['name']) && substr($displayArgsToUse['name'], -5) === '_text') {
							break;
						}
					}
				}
				if (! empty($displayArgsToUse)) {
					foreach ($args as & $arg) {
						if ($arg['field'] === $formatArgs['name']) {
							$arg['field'] = $displayArgsToUse['name'];
							if (isset($displayArgsToUse['format']) && $displayArgsToUse['format'] === 'trackerrender') {
								// this works for many field types, ItemLink, Drowdown, CountrySelector etc but not all (categories notably)
								$arg['field'] .= '_text';
							}
							break;
						}
					}
				}
			}
		}

		if (Table_Check::isSort()) {
			foreach ($_REQUEST['sort'] as $key => $dir) {
				if ($hasactions) {
					$type = $tsc[$key]['type'];
					$field = @$args[$key - 1]['field'];
				} else {
					$type = $tsc[$key]['type'];
					$field = $args[$key]['field'];
				}
				if (! $field) {
					continue;
				}
				$n = '';
				switch ($type) {
					case 'digit':
					case 'currency':
					case 'percent':
					case 'time':
					case strpos($type, 'date') !== false:
						$n = 'n';
						break;
				}
				$this->query->setOrder($field . '_' . $n . Table_Check::$dir[$dir]);
			}
		}

		if (Table_Check::isFilter()) {
			foreach ($_REQUEST['filter'] as $key => $filter) {
				if ($hasactions) {
					$type = $tsc[$key]['type'];
					$field = @$args[$key - 1]['field'];
				} else {
					$type = $tsc[$key]['type'];
					$field = $args[$key]['field'];
				}
				if (! $field) {
					continue;
				}
				switch ($type) {
					case 'digit':
					case strpos($type, 'date') !== false:
						$from = 0;
						$to = 0;
						$timestamps = explode(' - ', $filter);
						if (count($timestamps) === 2) {
							$from = $timestamps[0] / 1000;
							$to = $timestamps[1] / 1000;
						} elseif (strpos($filter, '>=') === 0) {
							$from = substr($filter, 2) / 1000;
							$to = 'now';
						} elseif (strpos($filter, '<=') === 0) {
							$from = '1970-01-01';
							$to = substr($filter, 2) / 1000;
						}
						if ($from && $to) {
							$this->query->filterRange($from, $to, $field);
							break;
						}	// else fall through to default
					default:
						if (! empty($tsf[$key]['initial'])) {
							$this->query->filterInitial($filter, $field);
						} else {
							$this->query->filterContent($filter, $field);
						}
						break;
				}
			}
		}

		return $ret;
	}

	private function get_fields_from_arguments($arguments)
	{
		if (isset($arguments['field'])) {
			$fields = explode(',', $arguments['field']);
		} else {
			$fields = TikiLib::lib('tiki')->get_preference('unified_default_content', ['contents'], true);
		}
		return $fields;
	}
}
