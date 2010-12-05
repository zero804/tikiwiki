<?php
// (c) Copyright 2002-2010 by authors of the Tiki Wiki/CMS/Groupware Project
// 
// All Rights Reserved. See copyright.txt for details and a complete list of authors.
// Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See license.txt for details.
// $Id$

/* by robferguson
 *
 * FOOTNOTEAREA plugin. Inserts a section for collected footnotes created with the FOOTNOTES plugin .
 * 
 * Syntax:
 * 
 * {FOOTNOTEAREA()/}
 */
function wikiplugin_footnotearea_help() {
	return tra("Inserts a section for collected footnotes in the wiki page").":<br />~np~{FOOTNOTEAREA()/}~/np~";
}

function wikiplugin_footnotearea_info() {
	return array(
		'name' => tra( 'Footnotearea' ),
		'documentation' => tra('PluginFootnoteArea'),
		'description' => tra('Inserts a section for collected footnotes in the wiki page.'),
		'prefs' => array('wikiplugin_footnotearea'),
		'params' => array(),
	);
}

function wikiplugin_footnotearea($data, $params) {

	$html = '<div class="footnotearea">';
	$html .= '<hr />';

	foreach($GLOBALS["footnotesData"] as $key => $value){
		$noteId = $key + 1;
		$html .= '<div class="onefootnote" id="footnote' . $noteId . '">';
		$html .= '<a href="#ref_footnote' . $noteId . '">'. $noteId . '.</a> ';
		$html .= $value;
		$html .= '</div>';
	}
	$html .= '</div>';
	
	return $html;
}
