<?php

require_once 'DeclFilter/Rule.php';
require_once 'TikiFilter.php';

class DeclFilter_StaticKeyFilterRule extends DeclFilter_FilterRule
{
	private $rules;

	function __construct( $rules )
	{
		$this->rules = $rules;
	}

	function match( $key )
	{
		return array_key_exists( $key, $this->rules );
	}

	function getFilter( $key )
	{
		return TikiFilter::get( $this->rules[$key] );
	}
}

?>
