<?php
// (c) Copyright 2002-2010 by authors of the Tiki Wiki/CMS/Groupware Project
// 
// All Rights Reserved. See copyright.txt for details and a complete list of authors.
// Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See license.txt for details.
// $Id$


function wikiplugin_attributes_info() {
	return array(
		'name' => tra('Attributes'),
		'documentation' => tra('PluginAttributes'),			
		'description' => tra('Allows for generic attributes to be assigned to the current object. Attributes are provided as parameters. For tiki.geo.lat, {attributes tiki_geo_lat=...}. Removing the plugin or values in it will not remove the attributes.'),
		'prefs' => array( 'wikiplugin_attributes' ),
		'extraparams' => true,
		'defaultfilter' => 'text',
		'params' => array(
		),
	);
}

function wikiplugin_attributes_save( $context, $data, $params ) {
	global $attributelib; require_once 'lib/attributes/attributelib.php';

	foreach( $params as $key => $value ) {
		$key = str_replace( '_', '.', $key );
		$attributelib->set_attribute( $context['type'], $context['object'], $key, $value );
	}
}

function wikiplugin_attributes($data, $params) {
	return '';
}

