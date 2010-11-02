<?php

class Search_ContentSource_WikiSource implements Search_ContentSource_Interface
{
	function getDocuments()
	{
		$db = TikiDb::get();
		return array_values($db->fetchMap('SELECT page_id, pageName FROM tiki_pages'));
	}

	function getDocument($objectId, Search_Type_Factory_Interface $typeFactory)
	{
		global $tikilib;

		$info = $tikilib->get_page_info($objectId);

		$data = array(
			'title' => $typeFactory->sortable($info['pageName']),
			'language' => $typeFactory->identifier(empty($info['lang']) ? 'unknown' : $info['lang']),
			'modification_date' => $typeFactory->timestamp($info['lastModif']),

			'wiki_content' => $typeFactory->wikitext($info['data']),
			'wiki_description' => $typeFactory->plaintext($info['description']),
		);

		return $data;
	}
}

