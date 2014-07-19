<?php
// (c) Copyright 2002-2013 by authors of the Tiki Wiki CMS Groupware Project
//
// All Rights Reserved. See copyright.txt for details and a complete list of authors.
// Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See license.txt for details.
// $Id$

class Services_Wiki_Controller
{
	function setUp()
	{
		Services_Exception_Disabled::check('feature_wiki');
	}

	function action_save_structure($input)
	{
		Services_Exception_Disabled::check('feature_wiki_structure');

		$data = json_decode($input->data->text());
		if ($data) {
			global $structlib; include_once('lib/structures/structlib.php');
			$structlib->reorder_structure($data);
			$params = json_decode($input->params->text());

			$_GET = array();		// self_link and query objects used by get_toc adds all this request data to the action links
			$_POST = array();

			$html = $structlib->get_toc(
				$params->page_ref_id,
				$params->order,
				$params->showdesc,
				$params->numbering,
				$params->numberPrefix,
				$params->type,
				$params->page,
				$params->maxdepth,
				$params->structurePageName
			);
		}
		return array('html' => $html);
	}

	function action_get_page($input)
	{
		$wikilib = TikiLib::lib('wiki');
		$page = $input->page->text();
		$info = $wikilib->get_page_info($page);
		if (!$info) {
			throw new Services_Exception_NotFound(tr('Page "%0" not found', $page));
		}
		$canBeRefreshed = false;
		$data = $wikilib->get_parse($page, $canBeRefreshed);
		return array('data' => $data);
	}
}

