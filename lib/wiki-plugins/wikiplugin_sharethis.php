<?php
// (c) Copyright 2002-2010 by authors of the Tiki Wiki/CMS/Groupware Project
// 
// All Rights Reserved. See copyright.txt for details and a complete list of authors.
// Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See license.txt for details.
// $Id$

/* Insert the bookmark button from ShareThis (www.sharethis.com). ShareThis account is not necessary.
// Developed by Andrew Hafferman for Tiki CMS
//
// 2008-11-25 SEWilco
//   Convert comments to WikiSyntax comments.
// 2009-07-11 lindon
//   Update for changes in ShareThis and fix bugs
*/
function wikiplugin_sharethis_help() {
	return tra('Insert a ShareThis button from www.sharethis.com').":<br />~np~{SHARETHIS(sendsvcs=> , postfirst=> ,  rotateimage=> y|n, buttontext=> , headertitle=> , headerbg=> , headertxtcolor=> , linkfg=> , popup=> true|false, embed=> true|false)}{SHARETHIS} ~/np~ <br /> ";
}
function wikiplugin_sharethis_info() {
	return array(
		'name' => tra('sharethis'),
		'documentation' => 'PluginSharethis',
		'description' => tra('Display a social networking tool.'),
		'prefs' => array( 'wikiplugin_sharethis' ),
		'params' => array(
			'sendsvcs' => array(
				'required' => false,
				'name' => tra('Send services'),
				'description' => tra('By default, email, aim and sms are available. Input one or two of the services separated by a | to limit the choice of send services.'),
			),
			'style' => array(
				'required' => false,
				'name' => tra('Button style'),
				'description' => tra('Horizontal, vertical or rotate.'),
				'options' => array(
					array('text' => tra('None'), 'value' => ''), 
					array('text' => tra('Horizontal'), 'value' => 'horizontal'), 
					array('text' => tra('Vertical'), 'value' => 'vertical'), 
					array('text' => tra('Rotate'), 'value' => 'rotate'), 
					),
			),
			'rotateimage' => array(
				'required' => false,
				'name' => tra('Rotate image'),
				'description' => tra('A value of y will cause the button icon to rotate every 3 seconds between a few icons, cycling through twice before stopping.'),
			),
			'multiple' => array(
				'required' => false,
				'name' => tra('Multiple icons'),
				'description' => tra('Enter list: email | facebook | twitter | sharethis, depending on which icons you\'d like.'),
			),
			'postfirst' => array(
				'required' => false,
				'name' => tra('First post services shown'),
				'description' => tra('Input a list of post services (like facebook, myspace, digg, etc.) separated by a | to customize the services that are shown in the opening panel of the widget.'),
			),
			'buttontext' => array(
				'required' => false,
				'name' => tra('Button text'),
				'description' => tra('Custom link text for the button.'),
			),
			'headertitle' => array(
				'required' => false,
				'name' => tra('Header title'),
				'description' => tra('Optional header title text for the widget.'),
			),
			'headerbg' => array(
				'required' => false,
				'name' => tra('Header background'),
				'description' => tra('HTML color code (not color name) for the background color for the header if an optional header title is used.'),
			),
			'headertxtcolor' => array(
				'required' => false,
				'name' => tra('Header text color'),
				'description' => tra('HTML color code (not color name) for the header text if an optional header title is used.'),
			),
			'linkfg' => array(
				'required' => false,
				'name' => tra('Link text color for services'),
				'description' => tra('HTML color code (not color name) for the link text for all send and post services shown in the widget'),
			),
			'popup' => array(
				'required' => false,
				'name' => tra('Pop-up'),
				'description' => tra('A value of true will cause the widget to show in a pop-up window.'),
			),
			'embed' => array(
				'required' => false,
				'name' => tra('Embedded elements'),
				'description' => tra('A value of true will allow embedded elements (like flash) to be seen while iframe is loading.'),
			),		)
	);
}
function wikiplugin_sharethis($data, $params) {

	extract ($params,EXTR_SKIP);
	$sharethis_options = array();
	$sep = '&amp;';
	$comma = '%2C';
	$lb = '%23';
	$sp = '%20';

	// The following is the array that holds the default options for the plugin.
	$sharethis_options['type'] = 'website';
	$sharethis_options['sendsvcs'] = '';
	$sharethis_options['style'] = '';
	$sharethis_options['buttontext'] = '';
	$sharethis_options['postfirst'] = '';
	$sharethis_options['headertitle'] = '';
	$sharethis_options['headerbg'] = '';
	$sharethis_options['headertxtcolor'] = '';
	$sharethis_options['linkfg'] = '';
	$sharethis_options['popup'] = '';
	$sharethis_options['embed'] = '';

	// load setting options from $params

	// set post services that appear upon widget opening
	if(!empty($postfirst))
	{
		$sharethis_options['postfirst'] = str_replace('|',$comma,$postfirst);
	}
	// limit send services that will appear
	if(!empty($sendsvcs))
	{
		$sharethis_options['sendsvcs'] = str_replace('|',$comma,$sendsvcs);
	}
	// set icon style
	if(!empty($rotateimage) || !empty($style)) {
		if ($rotateimage == 'y' || $style == 'rotate') {
			$sharethis_options['style'] = 'rotate';
		} elseif ($style == 'horizontal') {
			$sharethis_options['style'] = 'horizontal';
		} elseif ($style == 'vertical') {
			$sharethis_options['style'] = 'vertical';
		}
	}
	if (!empty($multiple)) {
		$iconcode = '<style type="text/css">
					body {font-family:helvetica,sans-serif;font-size:12px;}
					a.stbar.chicklet img {border:0;height:16px;width:16px;margin-right:3px;vertical-align:middle;}
					a.stbar.chicklet {height:16px;line-height:16px;}
					</style>';
		$icons = explode('|',$multiple);
		foreach ($icons as $icon) {
			$iconcode .= '<a id="ck_' . $icon . '" class="stbar chicklet" href="javascript:void(0);">' 
							. '<img src="http://w.sharethis.com/chicklets/' . $icon . '.gif" style="margin-right:3px;" />';
			if ($icon == 'sharethis') {
				$iconcode .= 'ShareThis';
			}
			$iconcode .= '</a>'; 
		}
		$iconcode .= '<script type="text/javascript">
						var shared_object = SHARETHIS.addEntry({
						title: document.title,
						url: document.location.href
					});
					
					shared_object.attachButton(document.getElementById("ck_sharethis"));
					shared_object.attachChicklet("email", document.getElementById("ck_email"));
					shared_object.attachChicklet("facebook", document.getElementById("ck_facebook"));
					shared_object.attachChicklet("twitter", document.getElementById("ck_twitter"));
					</script>';
	}
	
	// set button text
	if(!empty($buttontext))
	{
		$sharethis_options['buttontext'] = $buttontext;
	}
	// set header title text. If header title is set by user, then set background color and text color
	if(!empty($headertitle))
	{
		$sharethis_options['headertitle'] = str_replace(' ',$sp,$headertitle);
			if(!empty($headerbg)) {
			$sharethis_options['headerbg'] = $headerbg;
			}
			if(!empty($headertxtcolor)) {
			$sharethis_options['headertxtcolor'] = $headertxtcolor;
			}
		} else {
			$sharethis_options['headerbg'] = '';
			$sharethis_options['headertxtcolor'] = '';
		}
	// set link text color for services shown in popup
	if(!empty($linkfg))
	{
		$sharethis_options['linkfg'] = $linkfg;
	}
	// set popup
	if(!empty($popup))
	{
		$sharethis_options['popup'] = $popup;
	}
	// set embed
	if(!empty($embed))
	{
		$sharethis_options['embed'] = $embed;
	}

	// put all the options together

	$sharethiscode = "~hc~ ))ShareThis(( Bookmark Button BEGIN ~/hc~";
	$sharethiscode .= '<script type="text/javascript" src="http://w.sharethis.com/button/sharethis.js#';
	$sharethiscode .= "type=".$sharethis_options['type'];
	
	if(!empty($sharethis_options['buttontext'])) $sharethiscode .= $sep."buttonText=".$sharethis_options['buttontext'];
	if(!empty($sharethis_options['popup'])) $sharethiscode .= $sep."popup=".$sharethis_options['popup'];
	if(!empty($sharethis_options['embed'])) $sharethiscode .= $sep."embeds=".$sharethis_options['embed'];
	if(!empty($sharethis_options['style'])) $sharethiscode .= $sep."style=".$sharethis_options['style'];
	if(!empty($sharethis_options['sendsvcs'])) $sharethiscode .= $sep."send_services=".$sharethis_options['sendsvcs'];
	if(!empty($sharethis_options['postfirst'])) $sharethiscode .= $sep."post_services=".$sharethis_options['postfirst'];
	if(!empty($sharethis_options['headertxtcolor'])) $sharethiscode .= $sep."headerfg=".$lb.$sharethis_options['headertxtcolor'];	
	if(!empty($sharethis_options['headerbg'])) $sharethiscode .= $sep."headerbg=".$lb.$sharethis_options['headerbg'];	
	if(!empty($sharethis_options['linkfg'])) $sharethiscode .= $sep."linkfg=".$lb.$sharethis_options['linkfg'];
	if(!empty($sharethis_options['headertitle'])) $sharethiscode .= $sep."headerTitle=".$sharethis_options['headertitle'];
	if(!empty($iconcode)) $sharethiscode .= ';button=false';
	$sharethiscode .= "\"></script>\n";
	if(!empty($iconcode)) $sharethiscode .= $iconcode;	
	$sharethiscode .= "~hc~ ))ShareThis(( Bookmark Button END ~/hc~";

$result = $sharethiscode;

return $result;

}
