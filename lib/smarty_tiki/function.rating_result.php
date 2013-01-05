<?php
// (c) Copyright 2002-2013 by authors of the Tiki Wiki CMS Groupware Project
//
// All Rights Reserved. See copyright.txt for details and a complete list of authors.
// Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See license.txt for details.
// $Id$

function smarty_function_rating_result( $params, $smarty )
{
	global $prefs, $ratinglib;
	require_once 'lib/rating/ratinglib.php';
	$votings = $ratinglib->votings($params['id'], $params['type']);
	$smiles = $ratinglib->get_options_smiles($params['type'], $params['id'], true);
	$tableBody = "";

	foreach ($votings as $vote => $voting) {
		$tableBody .= '<td style="width:' . $voting['percent'] . '%; text-align: center;">
			<div class="ui-widget-content">' .
				($prefs['rating_smileys'] == 'y' ? '<img src="' . $smiles[$vote]['img'] . '"/> ' : '<b>' . $vote . '</b> ') .
				'( ' . $voting['votes'] . ' / ' . $voting['percent'] . '% )' .
				($prefs['rating_smileys'] == 'y' ? '<div style="background-color: ' . $smiles[$vote]['color'] . ';">&nbsp;</div>' : '').
			'</div>
		</td>';
	}

	return "<table class='ratingDeliberationResultTable' style='width:100%;'><tr>" . $tableBody . "</tr></table>";
}

