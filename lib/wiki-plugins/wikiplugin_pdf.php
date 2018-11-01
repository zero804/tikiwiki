<?php
// (c) Copyright by authors of the Tiki Wiki CMS Groupware Project
//
// All Rights Reserved. See copyright.txt for details and a complete list of authors.
// Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See license.txt for details.

function wikiplugin_pdf_info()
{
	//including prefs to set global print settings as default value of parameters
	global $prefs;
	 return [
				'name' => 'PluginPDF',
				'documentation' => 'PluginPDF',
				'description' => tra('For customized PDF generation, to override global PDF settings.'),
				'tags' => [ 'basic' ],
				'prefs' => [ 'wikiplugin_pdf' ],
				'format' => 'html',
				'iconname' => 'pdf',
				'introduced' => 17,
				'params' => [
					'printfriendly' => [
						'name' => tra('Print Friendly PDF'),
						'description' => tra('Print friendly option will change theme background color to white and text /headings color to black. If set to \'n\', theme colors will be retained in pdf'),
						'type' => 'list',
						'default' => '',
						'options' => [
							['text' => 'Default','value' => ''],
							['text' => 'Yes','value' => 'y'],
							['text' => 'No','value' => 'n'],
						],
					],
					'orientation' => [
						'name' => tra('PDF Orientation'),
						'description' => tra('Landscape or portrait'),
						'type' => 'list',
						'default' => $prefs['print_pdf_mpdf_orientation'],
						'options' => [
							['text' => 'Default','value' => ''],
							['text' => 'Portrait','value' => 'P'],
							['text' => 'Landscape','value' => 'L'],
						],

					],
					'pagesize' => [
					'name' => tra('PDF page size'),
					'description' => tra('ISO Standard sizes: A0, A1, A2, A3, A4, A5 or North American paper sizes: Letter, Legal, Tabloid/Ledger (for ledger, select landscape orientation)'),
					'type' => 'list',
					'options' => [
						['text' => 'Default','value' => ''],
						['text' => 'Letter','value' => 'Letter'],
						['text' => 'Legal','value' => 'Legal'],
						['text' => 'Tabloid/Ledger','value' => 'Tabloid/Ledger'],
						['text' => 'A0','value' => 'A0'],
						['text' => 'A1','value' => 'A1'],
						['text' => 'A2','value' => 'A2'],
						['text' => 'A3','value' => 'A3'],
						['text' => 'A4','value' => 'A4'],
						['text' => 'A5','value' => 'A5'],
						['text' => 'A6','value' => 'A6']
						]
					],
					'toc' => [
						'name' => tra('Generate table of contents'),
						'description' => tra('Set if table of contents will be autogenerated before PDF content'),
						'type' => 'list',
						'default' => $prefs['print_pdf_mpdf_toc'],
						'options' => [
							['text' => 'Default','value' => ''],
							['text' => 'On','value' => 'y'],
							['text' => 'Off','value' => 'n'],
						],
					],
					'toclinks' => [
						'name' => tra('Link TOC with content'),
						'description' => tra('Link TOC headings with content on PDF document'),
						'type' => 'list',
						'default' => 'n',
						'options' => [
							['text' => 'Default','value' => ''],
							['text' => 'Yes','value' => 'y'],
							['text' => 'No','value' => 'n'],
						],
					],
					'tocheading' => [
						'name' => tra('TOC heading'),
						'description' => tra('Heading to be appeared before table of content is printed'),
						'tags' => ['advanced'],
						'type' => 'text',
						'default' => $prefs['print_pdf_mpdf_tocheading'],
						'shorthint' => 'For example:Table of contents'
					],

					'toclevels' => [
						'name' => tra('TOC levels'),
						'description' => tra('Will be autopicked from content of document, for example:<code>H1|H2|H3</code>'),
						'tags' => ['advanced'],
						'type' => 'text',
						'default' => "H1|H2|H3",
						'shorthint' => ''
					],
					'pagetitle' => [
						'name' => tra('Show Page title'),
						'description' => tra('Print wiki page title with pdf'),
						'tags' => ['advanced'],
						'type' => 'list',
						'default' => '',
						'options' => [
							['text' => 'Default','value' => ''],
							['text' => 'Yes','value' => 'y'],
							['text' => 'No','value' => 'n']
						]
					],
					'header' => [
						'name' => tra('PDF header text'),
						'description' => tra('Format: <code>Left text| Center Text | Right Text</code>. Possible values,<code>custom text</code>, {PAGENO},{PAGETITLE},{DATE j-m-Y}, Page {PAGENO} of {NB} or "off" to turn off header'),
						'tags' => ['basic'],
						'type' => 'text',
						'default' => $prefs['print_pdf_mpdf_header'],
						'shorthint' => 'Left text |Center Text| Right Text'
					],
					'footer' => [
						'name' => tra('PDF footer text'),
						'description' => tra('Possible values, custom text, {PAGENO}, {DATE j-m-Y},  Page {PAGENO} of {NB}. For example:<code>{PAGETITLE}|{DATE j-m-Y}|{PAGENO}</code> or "off" to turn off footer'),
						'type' => 'text',
						'default' => $prefs['print_pdf_mpdf_footer'],
					],
					'margin_left' => [
						'name' => tra('Left margin'),
						'description' => tra('Numeric value.For example 10'),
						'type' => 'text',
						'default' => $prefs['print_pdf_mpdf_margin_left'],
						'size' => '2',
						'filter' => 'digits',
					],
					'margin_right' => [
						'name' => tra('Right margin'),
						'description' => tra('Numeric value, no need to add px. For example 10'),
						'type' => 'text',
						'default' => $prefs['print_pdf_mpdf_margin_right'],
						'size' => '2',
						'filter' => 'digits',
					],
					'margin_top' => [
						'name' => tra('Top margin'),
						'description' => tra('Numeric value, no need to add px. For example 10'),
						'type' => 'text',
						'default' => $prefs['print_pdf_mpdf_margin_top'],
						'size' => '2',
						'filter' => 'digits',
					],
					'margin_bottom' => [
						'name' => tra('Bottom margin'),
						'description' => tra('Numeric value, no need to add px. For example 10'),
						'type' => 'text',
						'default' => $prefs['print_pdf_mpdf_margin_bottom'],
						'size' => '2',
						'filter' => 'digits',
					],
					'margin_header' => [
						'name' => tra('Header margin from top of document'),
						'description' => tra('Only applicable if header is set. Numeric value only, no need to add px.Warning: Header can overlap text if top margin is not set properly'),
						'type' => 'text',
						'default' => $prefs['print_pdf_mpdf_margin_header'],
						'size' => '2',
						'filter' => 'digits',

					],
					'margin_footer' => [
						'name' => tra('Footer margin from bottom of document'),
						'description' => tra('Only applicable if footer is set.Numeric value only, no need to add px. Warning: Footer can overlap text if bottom margin is not set properly'),
						'type' => 'text',
						'default' => $prefs['print_pdf_mpdf_margin_footer'],
						'size' => '2',
						'filter' => 'digits',
					],
					'hyperlinks' => [
						'name' => tra('Hyperlink behaviour in PDF'),
						'description' => tra(''),
						'tags' => ['advanced'],
						'type' => 'list',
						'default' => '',
						'options' => [
							['text' => 'Default','value' => ''],
							['text' => 'Off (Links will be removed)','value' => 'off'],
							['text' => 'Add as footnote (Links will be listed at end of document)','value' => 'footnote'],
						]
					],
					'autobookmarks' => [
						'name' => tra('Generate PDF Bookmarks'),
						'description' => tra('Values H1-H6,separated by | <code>For example H1|H2|H3</code>'),
						'tags' => ['advanced'],
						'type' => 'text',
						'default' => 'h1|h2|h3',

					],
					'columns' => [
						'name' => tra('Number of columns'),
						'description' => tra(''),
						'tags' => ['advanced'],
						'type' => 'list',
						'default' => '',
						'options' => [
							['text' => 'Default - 1 Column','value' => ''],
							['text' => '2 Columns','value' => '2'],
							['text' => '3 Columns','3'],
							['text' => '4 Columns','4'],
						]
					],
					'password' => [
						'name' => tra('PDF password for viewing'),
						'description' => tra('Secure confidential PDF with password, leave blank if password protected is not needed'),
						'type' => 'password',
						'default' => $prefs['print_pdf_mpdf_password'],
					],
					'watermark' => [
						'name' => tra('Watermark text'),
						'description' => tra('Watermark text value, for example: Confidential, Draft etc. '),
						'type' => 'text',
						'default' => '',
					],
					'watermark_image' => [
						'name' => tra('Watermark image, enter full URL'),
						'description' => tra('Full URL of watermark image'),
						'type' => 'text',
						'default' => '',
					],
					'background' => [
						'name' => tra('Page background color'),
						'description' => tra('Enter a valid CSS color code.'),
						'type' => 'text',
						'default' => '',
					],
					'background_image' => [
						'name' => tra('Page background image'),
						'description' => tra('Enter the full URL.'),
						'type' => 'text',
						'default' => '',
					],
					'coverpage_text_settings' => [
						'name' => tra('CoverPage text settings'),
						'description' => tra('<code>Heading|Subheading|Text Alignment|Background color|Text color|Page border|Border color</code>. Enter settings separated by <code>|</code>,sequence is important,leave blank for default. For example <code>{PAGETITLE}|Tikiwiki|Center|#fff|#000|1|#ccc</code>'),
						'type' => 'text',
						'default' => '',
					],
					'coverpage_image_settings' => [
						'name' => tra('Coverpage image URL'),
						'description' => tra('Enter the full URL.'),
						'type' => 'text',
						'default' => '',
					],

				],
		];
}

function wikiplugin_pdf($data, $params)
{
	//included globals to check mpdf selection as pdf generation engine
	global $prefs;

	//checking if mdpf is default PDF generation engine, since plugin is only set for mpdf.
	if ($prefs['print_pdf_from_url'] != 'mpdf') {
		return WikiParser_PluginOutput::internalError(tr('For pluginPDF, please select mpdf as default PDF engine from Print Settings.'));
	}
	$paramList = '';
	//creating string of data paramaters set by user
	foreach ($params as $paramName => $param) {
		$paramList .= $paramName . "='" . $param . "' ";
	}
	return "<pdfsettings " . $paramList . "></pdfsettings>";
}
