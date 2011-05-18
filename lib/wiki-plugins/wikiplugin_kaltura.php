<?php
// (c) Copyright 2002-2011 by authors of the Tiki Wiki CMS Groupware Project
// 
// All Rights Reserved. See copyright.txt for details and a complete list of authors.
// Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See license.txt for details.
// $Id$

function wikiplugin_kaltura_info() {
	return array(
		'name' => tra('Kaltura Video'),
		'documentation' => 'PluginKaltura',
		'description' => tra('Display a video created through the Kaltura feature'),
		'prefs' => array('wikiplugin_kaltura', 'feature_kaltura'),
		'extraparams' => true,
		'icon' => 'pics/icons/film_edit.png',
		'params' => array(
			'id' => array(
				'required' => true,
				'name' => tra('Kaltura Entry ID'),
				'description' => tra('Kaltura entry ID of the video to be displayed'),
			),
		),
	);
}

function wikiplugin_kaltura($data, $params) {
	global $prefs;
     extract ($params, EXTR_SKIP);
     
     $code ='<object name="kaltura_player" id="kaltura_player" type="application/x-shockwave-flash" allowScriptAccess="always" allowNetworking="all" allowFullScreen="true" height="365" width="400" data="'.$prefs['kServiceUrl'].'index.php/kwidget/wid/'.$prefs['kdpWidget'].'/uiconf_id/'.$prefs['kdpUIConf'].'/entry_id/'.$id.'">
			 <param name="allowScriptAccess" value="always" />
			 <param name="allowNetworking" value="all" />
			 <param name="allowFullScreen" value="true" />
			 <param name="movie" value="'.$prefs['kServiceUrl'].'index.php/kwidget/wid/'.$prefs['kdpWidget'].'/uiconf_id/'.$prefs['kdpUIConf'].'/entry_id/'.$id.'"/>
			 <param name="flashVars" value="entry_id='.$id.'"/>
			 <param name="wmode" value="opaque"/>
			 </object>';
     return '~np~'.$code.'~/np~';
}
