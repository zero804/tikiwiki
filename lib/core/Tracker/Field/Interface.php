<?php
// (c) Copyright by authors of the Tiki Wiki CMS Groupware Project
//
// All Rights Reserved. See copyright.txt for details and a complete list of authors.
// Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See license.txt for details.
// $Id$

interface Tracker_Field_Interface
{
	public static function getTypes();

	/**
	 * Optional method for implementations supporting multiple implementations or needing custom construction.
	 *
	 * public static function build($type, $trackerDefinition, $fieldInfo, $itemData);
	 */

	/**
	 * return the values of a field (not necessarily the html that will be displayed) for input or output
	 * The values come from either the requestData if defined, the database if defined or the default
	 * @param array something like $_REQUEST
	 * @return
	 */
	function getFieldData(array $requestData = []);

	/**
	 * return the html of the input form for a field
	 *  either call renderTemplate if using a tpl or use php code
	 * @param
	 * @return string html
	*/
	function renderInput($context = []);

	/**
	 * return the html for the output of a field
	 *  with the link, prepend, append....
	 *  Use renderInnerOutput
	 * @param
	 * @return string html
	*/
	function renderOutput($context = []);

	/**
	 * Generate the plain text comparison to include in the watch email.
	 */
	function watchCompare($old, $new);

	//function handleSave($value, $oldValue);

	//function isValid($ins_fields_data);

	/**
	 * Augmentable fields allow adding a value to the set of pre-existing values.
	 */
	function addValue($value);

	/**
	 * Augmentable fields allow removing a value from the set of pre-existing values.
	 */
	function removeValue($value);
}
