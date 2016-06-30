<?php
// (c) Copyright 2002-2013 by authors of the Tiki Wiki CMS Groupware Project
//
// All Rights Reserved. See copyright.txt for details and a complete list of authors.
// Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See license.txt for details.
// $Id$
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 *
 * smarty_block_title : add a title to a template.
 *
 * params:
 *    help: name of the doc page on doc.tiki.org
 *    admpage: admin panel name
 *    url: link on the title
 *
 * usage: {title help='Example' admpage='example'}{tr}Example{/tr}{/title}
 */

//this script may only be included - so its better to die if called directly.
if (strpos($_SERVER["SCRIPT_NAME"], basename(__FILE__)) !== false) {
  header("location: index.php");
  exit;
}

function smarty_block_title($params, $content, $template, &$repeat)
{
	global $prefs, $tiki_p_view_templates, $tiki_p_edit_templates, $tiki_p_admin;

	if ( $repeat || empty($content) ) return;

	$template->loadPlugin('smarty_function_icon');
	$template->loadPlugin('smarty_modifier_sefurl');

	if ( ! isset($params['help']) ) $params['help'] = '';
	if ( ! isset($params['admpage']) ) $params['admpage'] = '';

	// Set the variable for the HTML title tag
	$template->smarty->assign('headtitle', $content);

	$class = 'pagetitle';
	$current = current_object();

	if ( ! isset($params['url']) ) {
		$params['url'] = smarty_modifier_sefurl($current['object'], $current['type']);
	}

	$params['url'] = str_replace('"', '', $params['url']);

	$metadata = '';
	$coordinates = TikiLib::lib('geo')->get_coordinates($current['type'], $current['object']);
	if ($coordinates) {
		$class = ' geolocated primary';
		$metadata = " data-geo-lat=\"{$coordinates['lat']}\" data-geo-lon=\"{$coordinates['lon']}\"";
		
		if (isset($coordinates['zoom'])) {
			$metadata .= " data-geo-zoom=\"{$coordinates['zoom']}\"";
		}
	}

	$html = '<h1>';
	$html .= '<a class="' . $class . '"' . $metadata . ' href="' . $params['url'] . '">' . htmlspecialchars($content) . "</a>\n";

	if ($template->getTemplateVars('print_page') != 'y') {
		if ( $prefs['feature_help'] == 'y' && $prefs['helpurl'] != '' && $params['help'] != '' ) {
			if ($prefs['mobile_mode']== 'y') {
				$html .= '<a data-role="button" data-inline="true" href="' ;
			} else {
				$html .= '<a href="' ;
			}
			$html .= $prefs['helpurl'] . rawurlencode($params['help']) . '" class="titletips" title="' . tra('Help page:') . ' ' . htmlspecialchars($content) . '" target="tikihelp">'
			. smarty_function_icon(array('_id' => 'help'), $template)
			. "</a>\n";
		}

		if ($prefs['feature_edit_templates'] == 'y' && $tiki_p_edit_templates == 'y' && ($tpl = $template->getTemplateVars('mid'))) {
			if ($prefs['mobile_mode']== 'y') {
				$html .= '<a data-role="button" data-inline="true" href="tiki-edit_templates.php?template=' ;
			} else {
				$html .= '<a href="tiki-edit_templates.php?template=' ;
			}
			$html .= $tpl . '" class="titletips" title="' . tra('View or edit tpl:') . ' ' . htmlspecialchars($content) . '">'
			. smarty_function_icon(array('_id' => 'shape_square_edit', 'alt' => tra('Edit Template')), $template)
			. "</a>\n";
		} elseif ($prefs['feature_view_tpl'] == 'y' &&  $tiki_p_view_templates == 'y' && ($tpl = $template->getTemplateVars('mid'))) {
			if ($prefs['mobile_mode']== 'y') {
				$html .= '<a data-role="button" data-inline="true" href="tiki-edit_templates.php?template=' ;
			} else {
				$html .= '<a href="tiki-edit_templates.php?template=' ;
			}
			$html .= $tpl . '" class="titletips" title="' . tra('View tpl:') . ' ' . htmlspecialchars($content) . '">'
			. smarty_function_icon(array('_id' => 'shape_square', 'alt' => tra('View Template')), $template)
			. "</a>\n";
			
		}

		if ( $tiki_p_admin == 'y' && $params['admpage'] != '' ) {
			if ($prefs['mobile_mode']== 'y') {
				$html .= '<a data-role="button" data-inline="true" class="titletips" href="tiki-admin.php?page=' ;
			} else {
				$html .= '<a class="titletips" href="tiki-admin.php?page=' ;
			}
			$html .= $params['admpage'] . '" title="' . tra('Admin page:') . ' ' . htmlspecialchars($content) . '">'
			. smarty_function_icon(array('_id' => 'wrench', 'alt' => tra('Admin Feature')), $template)
			. "</a>\n";
		}
	}

	$html .= '</h1>';

	return $html;
}
