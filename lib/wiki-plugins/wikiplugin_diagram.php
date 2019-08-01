<?php
// (c) Copyright by authors of the Tiki Wiki CMS Groupware Project
//
// All Rights Reserved. See copyright.txt for details and a complete list of authors.
// Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See license.txt for details.
// $Id$

use Tiki\File\DiagramHelper;
use Tiki\Package\VendorHelper;

function wikiplugin_diagram_info()
{
	$info = [
		'name' => tr('Diagram'),
		'documentation' => 'PluginDiagram',
		'description' => tr('Display mxGraph/Draw.io diagrams'),
		'prefs' => ['wikiplugin_diagram'],
		'iconname' => 'sitemap',
		'tags' => ['basic'],
		'introduced' => 19,
		'packages_required' => ['xorti/mxgraph-editor' => VendorHelper::getAvailableVendorPath('mxgraph', 'xorti/mxgraph-editor/mxClient.js')],
		'params' => [
			'fileId' => [
				'required' => false,
				'name' => tr('fileId'),
				'description' => tr('Id of the file in the file gallery. A xml file containing the graph model. Leave empty for more options.'),
				'since' => '19.0',
				'filter' => 'int',
			],
			'annotate' => [
				'required' => false,
				'name' => tr('annotate'),
				'description' => tr('Id of the file in the file gallery. A image file to include in the diagram.'),
				'since' => '20.0',
				'filter' => 'int',
			],
		],
	];

	return $info;
}

/**
 * @param $data
 * @param $params
 * @return string
 * @throws Exception
 */
function wikiplugin_diagram($data, $params)
{

	global $tikilib, $user, $page, $wikiplugin_included_page, $tiki_p_upload_files;

	$filegallib = TikiLib::lib('filegal');
	$vendorPath = VendorHelper::getAvailableVendorPath('mxgraph', 'xorti/mxgraph-editor/mxClient.min.js', false);

	if (! $vendorPath) {
		Feedback::error(tr('To view diagrams Tiki needs the xorti/mxgraph-editor package. If you do not have permission to install this package, ask the site administrator.'));
		return;
	}

	$headerlib = $tikilib::lib('header');
	$headerlib->add_js_config("var mxGraphVendorPath = '{$vendorPath}';");
	$headerlib->add_jsfile('lib/jquery_tiki/tiki-mxgraph.js', true);
	$headerlib->add_jsfile($vendorPath . '/xorti/mxgraph-editor/drawio/webapp/js/app.min.js');

	$headerlib->add_css('.diagram hr {margin-top:0.5em;margin-bottom:0.5em}');

	$fileId = isset($params['fileId']) ? intval($params['fileId']) : 0;
	$annotate = isset($params['annotate']) ? intval($params['annotate']) : 0;

	if ($fileId) {
		$file = \Tiki\FileGallery\File::id($fileId);
		$data = $file->getContents();

		if ($data === false) {
			Feedback::error(tr("Tiki wasn't able to find the file with id %0.", $fileId));
			return;
		}
	}

	$data = DiagramHelper::parseData($data);
	static $diagramIndex = 0;
	++$diagramIndex;

	if (function_exists('simplexml_load_string')) {
		$doc = simplexml_load_string($data);
		if ($doc !== false && ($doc->getName() != 'mxGraphModel' && $doc->getName() != 'mxfile')) {
			Feedback::error(tr("Tiki wasn't able to parse the Diagram. Please check the diagram XML data and structure."));
			return;
		} elseif (empty($data) || $doc === false) {
			if ($tiki_p_upload_files != 'y') {
				return;
			}

			$label = tra('Create New Diagram');
			$page = htmlentities($page);
			$in = tr(" in ");

			$gals = $filegallib->list_file_galleries(0, -1, 'name_desc', $user);

			$galHtml = "<option value='0'>" . tr('Page (inline)') . "</option>";
			usort($gals['data'], function ($a, $b) {
				return strcmp(strtolower($a['name']), strtolower($b['name']));
			});
			foreach ($gals['data'] as $gal) {
				if ($gal['name'] != "Wiki Attachments" && $gal['name'] != "Users File Galleries") {
					$galHtml .= "<option value='" . $gal['id'] . "'>" . $gal['name'] . "</option>";
				}
			}

			if ($annotate && $infoImg = loadImageAnnotate($annotate)) {
				$data = <<<XML
<mxGraphModel grid="1" gridSize="10" guides="1" tooltips="1" connect="1" arrows="1" fold="1" page="1" pageScale="1" pageWidth="850" pageHeight="1100" background="#ffffff">
  <root>
	<mxCell id="0"/>
	<mxCell id="1" parent="0"/>
	<mxCell id="2" value="" style="shape=image;imageAspect=0;aspect=fixed;verticalLabelPosition=bottom;verticalAlign=top;image={$infoImg['url']};imageBackground=none;movable=0;resizable=0;rotatable=0;deletable=0;editable=0;connectable=0;" parent="1" vertex="1">
	  <mxGeometry width="{$infoImg['imageSize'][0]}" height="{$infoImg['imageSize'][1]}" as="geometry"/>
	</mxCell>
  </root>
</mxGraphModel>
XML;
				$data = DiagramHelper::parseData($data);
				$data = base64_encode($data);
			}

			return <<<EOF
		~np~
		<form id="newDiagram$diagramIndex" method="post" action="tiki-editdiagram.php">
			<p>
				<input type="submit" class="btn btn-primary btn-sm" name="label" value="$label" class="newSvgButton" />$in
				<select name="galleryId">
					$galHtml
				</select>
				<input type="hidden" name="newDiagram" value="1"/>
				<input type="hidden" name="page" value="$page"/>
				<input type="hidden" name="xml" value="$data"/>
				<input type="hidden" name="index" value="$diagramIndex"/>
			</p>
		</form>
		~/np~
EOF;
		}
	}

	//checking if user can see edit button
	if (! empty($wikiplugin_included_page)) {
		$sourcepage = $wikiplugin_included_page;
	} else {
		$sourcepage = $page;
	}

	//checking if user has edit permissions on the wiki page/file using the current permission library to obey global/categ/object perms
	if ($fileId) {
		$objectperms = Perms::get(['type' => 'file', 'object' => $fileId]);
	} else {
		$objectperms = Perms::get([ 'type' => 'wiki page', 'object' => $sourcepage ]);
	}

	if ($objectperms->edit) {
		$allowEdit = true;
	} else {
		$allowEdit = false;
	}

	$smarty = TikiLib::lib('smarty');
	$smarty->assign('index', $diagramIndex);
	$smarty->assign('data', $data);
	$smarty->assign('graph_data_base64', base64_encode($data));
	$smarty->assign('sourcepage', $sourcepage);
	$smarty->assign('allow_edit', $allowEdit);
	$smarty->assign('file_id', $fileId);
	$smarty->assign('file_name', $file->name);
	$smarty->assign('mxgraph_prefix', $vendorPath);

	return '~np~' . $smarty->fetch('wiki-plugins/wikiplugin_diagram.tpl') . '~/np~';
}

/**
 * Get info of the image to annotate on
 *
 * @param number $annotate
 * @return array | false
 * @throws SmartyException
 */
function loadImageAnnotate($annotate)
{
	global $user;

	$userLib = TikiLib::lib('user');
	$file = \Tiki\FileGallery\File::id($annotate);

	$smarty = TikiLib::lib('smarty');
	$smarty->loadPlugin('smarty_modifier_sefurl');
	$url = smarty_modifier_sefurl($annotate, 'display');

	if (! $file->exists() || ! $userLib->user_has_perm_on_object($user, $file->fileId, 'file', 'tiki_p_download_files')) {
		Feedback::error(tr("Tiki wasn't able to find the file with id %0.", $annotate));
		return false;
	}

	if (! preg_match('/^image\//', $file->filetype)) {
		Feedback::error(tr("Selected file to annotate must be an image."));
		return false;
	}

	if ($file->getWrapper()->isFileLocal()) {
		$imageSize = getimagesize($file->getWrapper()->getReadableFile());
	} else {
		$data = $file->getContents();
		$imageSize = getimagesize('data://text/plain;base64,' . base64_encode($data));
	}

	if (empty($imageSize)) {
		Feedback::error(tr("Can not retrieve size from file %0", $annotate));
		return false;
	}

	return [
		'url' => $url,
		'imageSize' => $imageSize
	];
}
