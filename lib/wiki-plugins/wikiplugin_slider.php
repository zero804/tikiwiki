<?php
// (c) Copyright 2002-2013 by authors of the Tiki Wiki CMS Groupware Project
//
// All Rights Reserved. See copyright.txt for details and a complete list of authors.
// Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See license.txt for details.
// $Id$

function wikiplugin_slider_info()
{
	return array(
		'name' => tra('Slider'),
		'documentation' => 'PluginSlider',
		'description' => tra('Arrange content in a sliding area'),
		'prefs' => array( 'wikiplugin_slider' ),
		'body' => tra('Content separated by /////'),
		'icon' => 'img/icons/cool.gif',
		'tags' => array( 'basic' ),
		'params' => array(
			'titles' => array(
				'required' => false,
				'name' => tra('Slider Titles'),
				'description' => tra('Pipe separated list of slider titles. Ex: slider 1|slider 2|slider 3'),
				'default' => '',
			),
			'width' => array(
				'required' => false,
				'name' => tra('Width'),
				'description' => tra('Width in pixels or percentage. Default value is page width. e.g. "200px" or "100%"'),
				'filter' => 'striptags',
				'accepted' => 'Number of pixels followed by \'px\' or percent followed by % (e.g. "200px" or "100%").',
				'default' => 'Slider width',
			),
			'height' => array(
				'required' => false,
				'name' => tra('Height'),
				'description' => tra('Height in pixels or percentage. Default value is complete slider height. if expand parameter set to y, then don\'t use percent only use pixels '),
				'filter' => 'striptags',
				'accepted' => 'Number of pixels followed by \'px\' or percent followed by % (e.g. "200px" or "100%").',
				'default' => 'Slider height',
			),
			'theme' => array(
				'required' => false,
				'name' => tra('Theme'),
				'description' => tra('The theme to use in slider.'),
				'filter' => 'striptags',
				'accepted' => 'Name of the theme you want to use',
				'default' => 'default',
				'options' => array(
					array('text' => 'default', 'value' => ''),
					array('text' => 'construction', 'value' => 'construction'),
					array('text' => 'cs-portfolio', 'value' => 'cs-portfolio'),
					array('text' => 'default1', 'value' => 'default1'),
					array('text' => 'default2', 'value' => 'default2'),
					array('text' => 'metallic', 'value' => 'metallic'),
					array('text' => 'mini-dark', 'value' => 'mini-dark'),
					array('text' => 'mini-light', 'value' => 'mini-light'),
					array('text' => 'minimalist-round', 'value' => 'minimalist-round'),
					array('text' => 'minimalist-square', 'value' => 'minimalist-square'),
					array('text' => 'office', 'value' => 'office'),
					array('text' => 'polished', 'value' => 'polished'),
					array('text' => 'ribbon', 'value' => 'ribbon'),
					array('text' => 'shiny', 'value' => 'shiny'),
					array('text' => 'simple', 'value' => 'simple'),
					array('text' => 'tabs-dark', 'value' => 'tabs-dark'),
					array('text' => 'tabs-light', 'value' => 'tabs-light')
				)
			),
			'expand' => array(
				'required' => false,
				'name' => tra('Expand'),
				'description' => tra('if y, the entire slider will expand to fit the parent element and height parameter should not be empty'),
				'filter' => 'alpha',
				'accepted' => 'y or n',
				'default' => 'n',
				'options' => array(
					array('text' => '', 'value' => ''),
					array('text' => tra('Yes'), 'value' => 'y'),
					array('text' => tra('No'), 'value' => 'n')
				)
			),
			'resizecontents' => array(
				'required' => false,
				'name' => tra('Resize Contents'),
				'description' => tra('if y, solitary images/objects in the panel will expand to fit the viewport'),
				'filter' => 'alpha',
				'accepted' => 'y or n',
				'default' => 'y',
				'options' => array(
					array('text' => '', 'value' => ''),
					array('text' => tra('Yes'), 'value' => 'y'),
					array('text' => tra('No'), 'value' => 'n')
				)
			),
			'showmultiple' => array(
				'required' => false,
				'name' => tra('Show Multiple'),
				'description' => tra('Set this value to a number and it will show that many slides at once'),
				'filter' => 'striptags',
				'accepted' => 'a number',
				'default' => '1'
			),
			'buildarrows' => array(
				'required' => false,
				'name' => tra('Build Arrows'),
				'description' => tra('if y, builds the forwards and backwards buttons'),
				'filter' => 'alpha',
				'accepted' => 'y or n',
				'default' => 'y',
				'options' => array(
					array('text' => '', 'value' => ''),
					array('text' => tra('Yes'), 'value' => 'y'),
					array('text' => tra('No'), 'value' => 'n')
				)
			),
			'buildnavigation' => array(
				'required' => false,
				'name' => tra('Build Navigation'),
				'description' => tra('if y, builds a list of anchor links to link to each panel'),
				'filter' => 'alpha',
				'accepted' => 'y or n',
				'default' => 'y',
				'options' => array(
					array('text' => '', 'value' => ''),
					array('text' => tra('Yes'), 'value' => 'y'),
					array('text' => tra('No'), 'value' => 'n')
				)
			),
			'buildstartstop' => array(
				'required' => false,
				'name' => tra('Build Start Stop'),
				'description' => tra('if y, builds the start/stop button and adds slideshow functionality'),
				'filter' => 'alpha',
				'accepted' => 'y or n',
				'default' => 'y',
				'options' => array(
					array('text' => '', 'value' => ''),
					array('text' => tra('Yes'), 'value' => 'y'),
					array('text' => tra('No'), 'value' => 'n')
				)
			),
			'togglearrows' => array(
				'required' => false,
				'name' => tra('Toggle Arrows'),
				'description' => tra('if y, side navigation arrows will slide out on hovering & hide @ other times'),
				'filter' => 'alpha',
				'accepted' => 'y or n',
				'default' => 'n',
				'options' => array(
					array('text' => '', 'value' => ''),
					array('text' => tra('Yes'), 'value' => 'y'),
					array('text' => tra('No'), 'value' => 'n')
				)
			),
			'togglecontrols' => array(
				'required' => false,
				'name' => tra('Toggle Controls'),
				'description' => tra('if y, slide in controls (navigation + play/stop button) on hover and slide change, hide @ other times'),
				'filter' => 'alpha',
				'accepted' => 'y or n',
				'default' => 'n',
				'options' => array(
					array('text' => '', 'value' => ''),
					array('text' => tra('Yes'), 'value' => 'y'),
					array('text' => tra('No'), 'value' => 'n')
				)
			),
			'enablearrows' => array(
				'required' => false,
				'name' => tra('Enable Arrows'),
				'description' => tra('if n, arrows will be visible, but not clickable.'),
				'filter' => 'alpha',
				'accepted' => 'y or n',
				'default' => 'y',
				'options' => array(
					array('text' => '', 'value' => ''),
					array('text' => tra('Yes'), 'value' => 'y'),
					array('text' => tra('No'), 'value' => 'n')
				)
			),
			'enablenavigation' => array(
				'required' => false,
				'name' => tra('Enable Navigation'),
				'description' => tra('if n, navigation links will still be visible, but not clickable.'),
				'filter' => 'alpha',
				'accepted' => 'y or n',
				'default' => 'y',
				'options' => array(
					array('text' => '', 'value' => ''),
					array('text' => tra('Yes'), 'value' => 'y'),
					array('text' => tra('No'), 'value' => 'n')
				)
			),
			'enablestartstop' => array(
				'required' => false,
				'name' => tra('Enable Start Stop'),
				'description' => tra('if n, the play/stop button will still be visible, but not clickable. Previously "enablePlay"'),
				'filter' => 'alpha',
				'accepted' => 'y or n',
				'default' => 'y',
				'options' => array(
					array('text' => '', 'value' => ''),
					array('text' => tra('Yes'), 'value' => 'y'),
					array('text' => tra('No'), 'value' => 'n')
				)
			),
			'enablekeyboard' => array(
				'required' => false,
				'name' => tra('Enable Keyboard'),
				'description' => tra('if n, keyboard arrow keys will not work for this slider.'),
				'filter' => 'alpha',
				'accepted' => 'y or n',
				'default' => 'y',
				'options' => array(
					array('text' => '', 'value' => ''),
					array('text' => tra('Yes'), 'value' => 'y'),
					array('text' => tra('No'), 'value' => 'n')
				)
			),
			'autoplay' => array(
				'required' => false,
				'name' => tra('Auto Play'),
				'description' => tra('if y, the slideshow will start running; replaces "startStopped" option'),
				'filter' => 'alpha',
				'accepted' => 'y or n',
				'default' => 'f',
				'options' => array(
					array('text' => '', 'value' => ''),
					array('text' => tra('Yes'), 'value' => 'y'),
					array('text' => tra('No'), 'value' => 'n')
				)
			),
			'autoplaylocked' => array(
				'required' => false,
				'name' => tra('Auto Play Locked'),
				'description' => tra('if y, user changing slides will not stop the slideshow'),
				'filter' => 'alpha',
				'accepted' => 'y or n',
				'default' => 'n',
				'options' => array(
					array('text' => '', 'value' => ''),
					array('text' => tra('Yes'), 'value' => 'y'),
					array('text' => tra('No'), 'value' => 'n')
				)
			),
			'autoplaydelayed' => array(
				'required' => false,
				'name' => tra('Auto Play Delayed'),
				'description' => tra('if y, starting a slideshow will delay advancing slides; if n, the slider will immediately advance to the next slide when slideshow starts'),
				'filter' => 'alpha',
				'accepted' => 'y or n',
				'default' => 'n',
				'options' => array(
					array('text' => '', 'value' => ''),
					array('text' => tra('Yes'), 'value' => 'y'),
					array('text' => tra('No'), 'value' => 'n')
				)
			),
			'pauseonhover' => array(
				'required' => false,
				'name' => tra('Pause On Hover'),
				'description' => tra('if y & the slideshow is active, the slideshow will pause on hover'),
				'filter' => 'alpha',
				'accepted' => 'y or n',
				'default' => 'y',
				'options' => array(
					array('text' => '', 'value' => ''),
					array('text' => tra('Yes'), 'value' => 'y'),
					array('text' => tra('No'), 'value' => 'n')
				)
			),
			'stopatend' => array(
				'required' => false,
				'name' => tra('Stop At End'),
				'description' => tra('if y & the slideshow is active, the slideshow will stop on the last page. This also stops the rewind effect when infiniteSlides is false.'),
				'filter' => 'alpha',
				'accepted' => 'y or n',
				'default' => 'n',
				'options' => array(
					array('text' => '', 'value' => ''),
					array('text' => tra('Yes'), 'value' => 'y'),
					array('text' => tra('No'), 'value' => 'n')
				)
			),
			'delay' => array(
				'required' => false,
				'name' => tra('Delay between slides'),
				'description' => tra('Time in milliseconds between slideshow transitions (in AutoPlay mode).'),
				'filter' => 'striptags',
				'accepted' => 'a number',
				'default' => '3000',
			),
			'playrtl' => array(
				'required' => false,
				'name' => tra('Play Right To Left'),
				'description' => tra('if y, the slideshow will move right-to-left'),
				'filter' => 'alpha',
				'accepted' => 'y or n',
				'default' => 'n',
				'options' => array(
					array('text' => '', 'value' => ''),
					array('text' => tra('Yes'), 'value' => 'y'),
					array('text' => tra('No'), 'value' => 'n')
				)
			),
			'resumeonvideoend' => array(
				'required' => false,
				'name' => tra('Resume On Video End'),
				'description' => tra('if y & the slideshow is active & a supported video is playing, it will pause the autoplay until the video is complete'),
				'filter' => 'alpha',
				'accepted' => 'y or n',
				'default' => 'y',
				'options' => array(
					array('text' => '', 'value' => ''),
					array('text' => tra('Yes'), 'value' => 'y'),
					array('text' => tra('No'), 'value' => 'n')
				)
			),
			'animationtime' => array(
				'required' => false,
				'name' => tra('Animation Time'),
				'description' => tra('Milliseconds between slides'),
				'filter' => 'striptags',
				'accepted' => 'a number',
				'default' => '600',
			),
			'hashtags' => array(
				'required' => false,
				'name' => tra('Display panel hashtag'),
				'description' => tra('if y, each panel has a hashtag that will appear in the page URL, allowing you to link to a specific panel.'),
				'filter' => 'alpha',
				'accepted' => 'y or n',
				'default' => 'y',
				'options' => array(
					array('text' => '', 'value' => ''),
					array('text' => tra('Yes'), 'value' => 'y'),
					array('text' => tra('No'), 'value' => 'n')
				)
			),

		),
	);
}

function wikiplugin_slider($data, $params)
{
	global $tikilib, $headerlib;
	extract($params, EXTR_SKIP);

	$headerlib->add_jsfile('vendor/jquery/plugins/anythingslider/js/swfobject.js');
	$headerlib->add_jsfile('vendor/jquery/plugins/anythingslider/js/jquery.anythingslider.js');
	$headerlib->add_jsfile('vendor/jquery/plugins/anythingslider/js/jquery.anythingslider.fx.js');
	$headerlib->add_jsfile('vendor/jquery/plugins/anythingslider/js/jquery.anythingslider.video.js');
	$headerlib->add_cssfile('vendor/jquery/plugins/anythingslider/css/anythingslider.css');
	$headerlib->add_cssfile('vendor/jquery/plugins/anythingslider/css/theme-construction.css');
	$headerlib->add_cssfile('vendor/jquery/plugins/anythingslider/css/theme-cs-portfolio.css');
	$headerlib->add_cssfile('vendor/jquery/plugins/anythingslider/css/theme-metallic.css');
	$headerlib->add_cssfile('vendor/jquery/plugins/anythingslider/css/theme-minimalist-round.css');
	$headerlib->add_cssfile('vendor/jquery/plugins/anythingslider/css/theme-minimalist-square.css');
	$headerlib->add_cssfile('vendor_extra/anythingslider-themes/css/theme-default1.css');
	$headerlib->add_cssfile('vendor_extra/anythingslider-themes/css/theme-default2.css');
	$headerlib->add_cssfile('vendor_extra/anythingslider-themes/css/theme-mini-dark.css');
	$headerlib->add_cssfile('vendor_extra/anythingslider-themes/css/theme-mini-light.css');
	$headerlib->add_cssfile('vendor_extra/anythingslider-themes/css/theme-office.css');
	$headerlib->add_cssfile('vendor_extra/anythingslider-themes/css/theme-polished.css');
	$headerlib->add_cssfile('vendor_extra/anythingslider-themes/css/theme-ribbon.css');
	$headerlib->add_cssfile('vendor_extra/anythingslider-themes/css/theme-shiny.css');
	$headerlib->add_cssfile('vendor_extra/anythingslider-themes/css/theme-simple.css');
	$headerlib->add_cssfile('vendor_extra/anythingslider-themes/css/theme-tabs-dark.css');
	$headerlib->add_cssfile('vendor_extra/anythingslider-themes/css/theme-tabs-light.css');
	
	if (isset($theme) && !empty($theme)) {
		switch (strtolower($theme)) {
			case 'construction':
			case 'cs-portfolio':
			case 'default1':
			case 'default2':
			case 'metallic':
			case 'mini-dark':
			case 'mini-light':
			case 'minimalist-round':
			case 'minimalist-square':
			case 'office':
			case 'polished':
			case 'ribbon':
			case 'shiny':
			case 'simple':
			case 'tabs-dark':
			case 'tabs-light':
				$theme = $theme;
    			break;
			default:
				$theme = 'default';
		}
	} else {
		$theme = 'default';
	}

	$animationtime = (int) $animationtime;
	$animationtime = (empty($animationtime) === false ? $animationtime : 600);
	$delay = (int) $delay;
	$delay = (empty($delay) === false ? $delay : 3000);
	$showmultiple = (int) $showmultiple;
	$showmultiple = (empty($showmultiple) === false ? $showmultiple : 1);

	$headerlib->add_jq_onready(
		"function formatText(i, p) {
			var possibleText = $('.tiki-slider-title').eq(i - 1).text();
			return (possibleText ? possibleText : 'slide_' + i);
		}

		$('.tiki-slider').anythingSlider({
			theme               : '$theme',
			expand              : ".makeBool($expand, false).",
			resizeContents      : ".makeBool($resizecontents, true).",
			showMultiple        : $showmultiple,
			easing              : 'swing',

			buildArrows         : ".makeBool($buildarrows, true).",
			buildNavigation     : ".makeBool($buildnavigation, true).",
			buildStartStop      : ".makeBool($buildstartstop, true).",

			toggleArrows        : ".makeBool($togglearrows, false).",
			toggleControls      : ".makeBool($togglecontrols, false).",

			startText           : 'Start',
			stopText            : 'Stop',
			forwardText         : '&raquo;',
			backText            : '&laquo;',
			tooltipClass        : 'tooltip',

			// Function
			enableArrows        : ".makeBool($enablearrows, true).",
			enableNavigation    : ".makeBool($enablenavigation, true).",
			enableStartStop     : ".makeBool($enablestartstop, true).",
			enableKeyboard      : ".makeBool($enablekeyboard, true).",

			// Navigation
			startPanel          : 1,
			changeBy            : 1,
			hashTags            : ".makeBool($hashtags, true).",

			// Slideshow options
			autoPlay            : ".makeBool($autoplay, false).",
			autoPlayLocked      : ".makeBool($autoplaylocked, false).",
			autoPlayDelayed     : ".makeBool($autoplaydelayed, false).",
			pauseOnHover        : ".makeBool($pauseonhover, true).",
			stopAtEnd           : ".makeBool($stopatend, false).",
			playRtl             : ".makeBool($playrtl, false).",

			// Times
			delay               : $delay,
			resumeDelay         : 15000,
			animationTime       : $animationtime,

			// Video
			resumeOnVideoEnd    : ".makeBool($resumeonvideoend, true).",
			addWmodeToObject    : 'opaque',

			navigationFormatter: formatText
		});"
	);

	if (!empty($titles)) {
		$titles = $tikilib->parse_data($titles, array('suppress_icons' => true));
		$titles = explode('|', $titles);
	}

	$sliderData = array();
	if (!empty($data)) {
		$data = $tikilib->parse_data($data, array('suppress_icons' => true));
		$data = preg_replace('/<p>\/\/\/\/\/\s*<\/p>/', '/////', $data);	// remove surrounding <p> tags on slide boundaries
		$sliderData = explode('/////', $data);
	}

	$ret = '';
	foreach ($sliderData as $i => $slide) {
		$ret .= "<div>
			".(isset($titles[$i]) ? "<span class='tiki-slider-title' style='display: none;'>".$titles[$i]."</span>" : "")."
			$slide
		</div>";
	}

	if($expand == 'y') {
		/** if expand eq 'y', "100%" height not working **/
		/** Temp fix: if $height is empty**/
		$height = (empty($height) === false ? $height : '300px');
		$result = "<div style='width: $width; height: $height;'><div class='tiki-slider'>$ret</div></div>";
	} else {
		$result = "<div class='tiki-slider' style='width: $width; height: $height;'>$ret</div>";
	}
	
	return <<<EOF
	~np~$result~/np~
EOF;
}
