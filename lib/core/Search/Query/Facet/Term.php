<?php
// (c) Copyright 2002-2016 by authors of the Tiki Wiki CMS Groupware Project
//
// All Rights Reserved. See copyright.txt for details and a complete list of authors.
// Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See license.txt for details.
// $Id$

class Search_Query_Facet_Term implements Search_Query_Facet_Interface
{
	private $field;
	private $renderCallback;
	private $operator = 'or';
	private $count;
	private $label;
	private $order;
	private $min_doc_count;

	static function fromField($field)
	{
		return new self($field);
	}

	function __construct($field)
	{
		$this->field = $field;
		$this->label = ucfirst($field);
	}

	function getName()
	{
		return $this->field;
	}

	function getField()
	{
		return $this->field;
	}

	function getCount()
	{
		return $this->count;
	}

	function setCount($count)
	{
		$this->count = $count;
		return $this;
	}

	function getLabel()
	{
		return $this->label;
	}

	function setLabel($label)
	{
		$this->label = $label;
		return $this;
	}

	function setRenderCallback($callback)
	{
		$this->renderCallback = $callback;
		return $this;
	}

	function setRenderMap(array $map)
	{
		return $this->setRenderCallback(
			function ($value) use ($map) {
				if (isset($map[$value])) {
					return $map[$value];
				} else {
					return $value;
				}
			}
		);
	}

	function render($value)
	{
		if ($cb = $this->renderCallback) {
			return call_user_func($cb, $value);
		}

		return $value;
	}

	function setOperator($operator)
	{
		$this->operator = in_array($operator, ['and', 'or']) ? $operator : 'or';
		return $this;
	}

	function getOperator()
	{
		return $this->operator;
	}

	/**
	 * @return array [field => order]
	 */
	public function getOrder()
	{
		$order = null;

		if ($this->order) {
			$searchQueryOrder = \Search_Query_Order::parse($this->order);
			return [$searchQueryOrder->getField() => $searchQueryOrder->getOrder()];
		}

		return $order;
	}

	/**
	 * @param string $order
	 *
	 * @return $this
	 */
	public function setOrder($order)
	{
		$this->order = $order;
		return $this;
	}

	/**
	 * @return int
	 */
	public function getMinDocCount()
	{
		return $this->min_doc_count;
	}

	/**
	 * @param int $min
	 *
	 * @return Search_Query_Facet_Term
	 */
	public function setMinDocCount($min)
	{
		$this->min_doc_count = $min;
		return $this;
	}

}
