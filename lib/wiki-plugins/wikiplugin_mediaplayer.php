<?php
// (c) Copyright 2002-2015 by authors of the Tiki Wiki CMS Groupware Project
// 
// All Rights Reserved. See copyright.txt for details and a complete list of authors.
// Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See license.txt for details.
// $Id$

function wikiplugin_mediaplayer_info()
{
	return array(
		'name' => tra('Media Player'),
		'documentation' => 'PluginMediaplayer',
		'description' => tra('Add a media player to a page'),
		'extraparams' =>true,
		'prefs' => array( 'wikiplugin_mediaplayer' ),
		'icon' => 'img/icons/mime/avi.png',
		'tags' => array( 'basic' ),
		'params' => array(
			'fullscreen' => array(
				'required' => false,
				'name' => tra('Allow Fullscreen'),
				'description' => tra('Allow fullscreen mode.').tra(' true|false'),
				'filter' => 'alpha',
				'options' => array(
					array(
						'text' => '',
						'value' => '',
					),
					array(
						'text' => tra('Yes'),
						'value' => 'true',
					),
					array(
						'text' => tra('No'),
						'value' => 'false'
					)
				)
			),
			'mp3' => array(
				'required' => false,
				'name'=> tra('MP3 URL'),
				'description' => tra("Complete URL to the MP3 to include. http://example.org/example.mp3 for an external file or the following for a local file: tiki-download_file.php?fileId=2 (No need for http:// in this case)"),
				'filter' => 'url',
			),
			'flv' => array(
				'required' => false,
				'name'=> tra('FLV URL'),
				'description' => tra("Complete URL to the FLV to include. http://example.org/example.flv for an external file or the following for a local file: http:tiki-download_file.php?fileId=2 (the missing // is intentional as this is a valid internal link)"),
				'filter' => 'url'
			),

			// The following param needs an URL with an extension (ex.: example.wmv works but not tiki-download_file.php?fileId=4&display)
			'src' => array(
				'required' => false,
				'name'=> tra('URL'),
				'description' => tra("Complete URL to the media to include, which has the appropriate extension. If your URL doesn't have an extension, use the File type parameter below."). ' ' .'File extensions: asx, asf, avi, mov, mpg, mpeg, mp4, qt, ra, smil, swf, wmv, 3g2, 3gp, aif, aac, au, gsm, mid, midi, mov, m4a, snd, ra, ram, rm, wav, wma, bmp, html, pdf, psd, qif, qtif, qti, tif, tiff, xaml',
				'filter' => 'url',
				'default' => '',
			),

			// The type parameter is verified for Quicktime, Windows Media Player, Real Player, iframe (PDF), but doesn't work for flv param of the plugin
			'type' => array(
				'required' => false,
				'name'=> tra('File type'),
				'description' => tra('File type for source URL, e.g. mp4. Specify one of the supported file types when the URL of the file is missing the file extension. This is the case for File Gallery files which have a URL such as tiki-download_file.php?fileId=4&display or display4 if you have Clean URLs enabled.'),
				'filter' => 'url',
				'default' => '',
			),
			'width' => array(
				'required' => false,
				'name'=> tra('Width'),
				'description' => tra('Player width in px or %'),
				'default' => '',
				),
			'height' => array(
				'required' => false,
				'name'=> tra('Height'),
					'description' => tra('Player height in px or %'),
				'default' => '',
				),
			'style' => array(
				'required' => false,
				'name' => tra('Style'),
				'description' => tra('One of:').' mini|normal|maxi|multi|native',
				'filter' => 'alpha',
				'options' => array(
					array(
						'text' => '', 'value' => ''
					),
					array(
						'text' => 'Mini', 'value' => 'mini'
					),
					array(
						'text' => 'Normal', 'value' => 'normal'
					),
					array(
						'text' => 'Maxi', 'value' => 'maxi'
					),
					array(
						'text' => 'Multi', 'value' => 'multi'
					),
					array(
						'text' => 'Native (HTML5)', 'value' => 'native'
					)
				)
			),
			'mediatype' => array(
				'required' => false,
				'name' => tra('Media Type (for HTML5 style only)'),
				'description' => tra('One of:').' audio|video',
				'filter' => 'alpha',
				'options' => array(
					array(
						'text' => '', 'value' => ''
					),
					array(
						'text' => tra('Audio'), 'value' => 'audio'
					),
					array(
						'text' => tra('Video'), 'value' => 'video'
					)
				)
			),
			'wmode' => array(
				'required' => false,
				'name' => tra('Flash Window Mode'),
				'description' => tra('Sets the Window Mode property of the Flash movie. Transparent lets what\'s behind the movie show through and allows the movie to be covered Opaque hides what\'s behind the movie and Window plays the movie in its own window. Default value: ').'transparent',
				'filter' => 'alpha',
				'options' => array(
					array(
						'text' => '',
						'value' => '',
					),
					array(
						'text' => tra('Transparent'),
						'value' => 'transparent',
					),
					array(
						'text' => tra('Opaque'),
						'value' => 'opaque',
					),
					array(
						'text' => tra('Window'),
						'value' => 'window',
					)
				)
			),
		),
	);
}
function wikiplugin_mediaplayer($data, $params)
{
	global $prefs;
	$access = TikiLib::lib('access');
	static $iMEDIAPLAYER = 0;
	$id = 'mediaplayer'.++$iMEDIAPLAYER;

	if (empty($params['mp3']) && empty($params['flv']) && empty($params['src'])) {
		return;
	}
	if (!empty($params['src']) && $params['style'] != 'native') {
		$access->check_feature('feature_jquery_media');
	}
	$defaults_mp3 = array(
		'width' => 200,
		'height' => 20,
		'player' => 'player_mp3.swf',
		'where' => 'vendor/player/mp3/template_default/',
	);
	$defaults_flv = array(
		'width' => 320,
		'height' => 240,
		'player' => 'player_flv.swf',
		'where' => 'vendor/player/flv/template_default/'
	);
	$defaults_html5 = array(
		'width' => '',
		'height' => '',
	);
	$defaults = array(
		'width' => 320,
		'height' => 240,
	);
	if (!empty($params['flv'])) {
		$params = array_merge($defaults_flv, $params);
	} elseif (!empty($params['mp3'])) {
		$params = array_merge($defaults_mp3, $params);
	} elseif (!empty($params['style']) && $params['style'] == 'native') {
		$params = array_merge($defaults_html5, $params);
	} else {
		$params = array_merge($defaults, $params);
	}
	if (!empty($params['src']) && $params['style'] != 'native') {
		$headerlib = TikiLib::lib('header');
		$js = "\n var media_$id = $('#$id').media( {";
		foreach ($params as $param => $value) {
			if ($param == 'src') {
				continue;
			}
			
			if (is_numeric($value) == false &&
				strtolower($value) != 'true' && 
				strtolower($value) != 'false') {
				$value = "\"" . $value . "\"";
			}
			
			$js .= "$param: $value,";
		}
		// Force scaling (keeping the aspect ratio) of the QuickTime player
		//	Tried with .mp4. Not sure how this will work with other formats, not using QuickTime.
		// See: http://jquery.malsup.com/media/#players for default players for different formats. arildb
		$js .= " params: { 
				scale: 'aspect'
				} 
			} );";
			
		$headerlib->add_jq_onready($js);
		return "<a href=\"".$params['src']."\" id=\"$id\"></a>";
	}
	
	// Check the style of the player
	$styles = array('normal', 'mini', 'maxi', 'multi', 'native');
	if (empty($params['style']) || $params['style'] == 'normal' || !in_array($params['style'], $styles)) {
		$player = $params['player'];
 	} elseif ($params['style'] == 'native') {
		$player = '';
	} else {
		$params['where'] = str_replace('_default', '_'.$params['style'], $params['where']);
		$player = str_replace('.swf', '_'.$params['style'].'.swf', $params['player']);
	}
	
	// check if native native HTML5 video object is requested

	if ($params['style'] == 'native') {
		if ($params['mediatype'] == 'audio') {
			$mediatype = 'audio';
		} else {
			$mediatype = 'video';
		}
		$code = '<' . $mediatype;
		if (!empty( $params['height'] )) { 
			$code .= ' height="' . $params['height'] . '"';
		}
		if (!empty( $params['width'] )) { 
			$code .= ' width="' . $params['width'] . '"';
		}
		$code .= ' style="max-width: 100%" controls>';
		$code .= '	<source src="' . $params['src'] . '" type=\'' . $params['type'] . '\'>'; // type can be e.g. 'video/webm; codecs="vp8, vorbis"'
		$code .= '</' . $mediatype . '>';
	} else {

	// else use flash

	$code = '<object type="application/x-shockwave-flash" data="'.$params['where'].$player.'" width="'.$params['width'].'" height="'.$params['height'].'">';
	$code .= '<param name="movie" value="'.$params['where'].$player.'" />';
	if (!empty($params['fullscreen'])) {
		$code .= '<param name="allowFullscreen" value="'.$params['fullscreen'].'" />';
	}
	if (empty($params['wmode'])) {
		$wmode = 'transparent';
	} else {
		$wmode = $params['wmode'];
	}
	$code .= '<param name="wmode" value="'.$wmode.'" />';
	$code .= '<param name="FlashVars" value="';
	if (empty($params['flv']) && !empty($params['mp3'])) 
		$code .= 'mp3='.$params['mp3'];
	
	// Disabled due to MSIE issue still experienced with version 9: http://flv-player.net/help/#faq2
	//unset($params['width']); unset($params['height']);
	unset($params['where']); unset($params['player']);unset($params['mp3']); unset($params['style']); unset($params['fullscreen']); unset($params['wmode']);
	
	foreach ($params as $key=>$value) {
		$code .= '&amp;'.$key.'='.$value;
	}
	$code .= '" />';
	$code .= '</object>';

	} // end of else use flash

	return "~np~$code~/np~";
}
