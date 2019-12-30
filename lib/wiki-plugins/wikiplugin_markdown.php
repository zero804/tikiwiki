<?php
// (c) Copyright by authors of the Tiki Wiki CMS Groupware Project
//
// All Rights Reserved. See copyright.txt for details and a complete list of authors.
// Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See license.txt for details.
// $Id$

function wikiplugin_markdown_info() {
	return [
		'name' => tra('Markdown'),
		'documentation' => 'PluginMarkdown',
		'description' => tra('Parse the body of the plugin using a Markdown parser.'),
		'prefs' => ['wikiplugin_markdown'],
		'body' => tra('Markdown syntax to be parsed'),
		'iconname' => 'code',
		'introduced' => 20,
		'filter' => 'rawhtml_unsafe',
		'format' => 'html',
		'tags' => [ 'advanced' ],
		'params' => [
			// TODO: add some useful params here
		],
	];
}

//use League\CommonMark\CommonMarkConverter;
use League\CommonMark\Converter;
use League\CommonMark\DocParser;
use League\CommonMark\Environment;
use League\CommonMark\HtmlRenderer;
use Webuni\CommonMark\AttributesExtension\AttributesExtension;

function wikiplugin_markdown($data, $params) {

	global $prefs;

	extract($params, EXTR_SKIP);

	$md = trim($data);

	$md = str_replace('&lt;x&gt;', '', $md);
	$md = str_replace('<x>', '', $md);

    $environment = Environment::createCommonMarkEnvironment();
	$environment->addExtension(new AttributesExtension());
    $environment->setConfig(['html_input' => 'escape', 'allow_unsafe_links' => false]);
	
	$converter = new Converter(new DocParser($environment), new HtmlRenderer($environment));

	$md = $converter->convertToHtml($md);
	
	// $converter = new CommonMarkConverter(['html_input' => 'escape', 'allow_unsafe_links' => false]);
	// $md = $converter->convertToHtml($md);

	# TODO: "if param wiki then" $md = TikiLib::lib('parser')->parse_data($md, ['is_html' => true, 'parse_wiki' => true]);
	return $md;
}

