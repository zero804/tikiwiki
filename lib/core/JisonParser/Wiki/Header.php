<?php
// (c) Copyright 2002-2013 by authors of the Tiki Wiki CMS Groupware Project
//
// All Rights Reserved. See copyright.txt for details and a complete list of authors.
// Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See license.txt for details.
// $Id$

class JisonParser_Wiki_Header
{
	public $stack = array();
	public $count = 0;
	public $idCount = array();
	public $headerIds = array();

	public function stack($level, $content)
	{
		JisonParser_Wiki_Handler::deleteEntities($content);
		$id = implode('_', JisonParser_Phraser_Handler::sanitizeToWords($content));

		if (isset($this->idCount[$id])) {
			$this->idCount[$id]++;
			$id .= $this->idCount[$id];
		} else {
			$this->idCount[$id] = 0;
		}

		if ($level == 1) {
			$this->stack[] = array('content' => $content, 'id' => $id, 'children' => array());
		} else {
			$this->addToStack($this->stack, 1, $level, $content, $id);
		}

		return $id;
	}

	private function addToStack(&$stack, $currentLevel, $neededLevel, $content, $id)
	{
		if ($currentLevel < $neededLevel && $currentLevel < 7) {
			if (!isset($stack)) {
				$stack = array();
				$key = 0;
			} else {
				end($stack);
				$key = key($stack);
			}

			$key = max(0, $key);

			$this->addToStack($stack[$key]['children'], $currentLevel + 1, $neededLevel, $content, $id);
		} else {
			$stack[] = array('content' => $content, 'id' => $id);
		}
	}

	public function toHtmlList($class = '')
	{
		return $this->toHtmlListChildren($this->stack, $class);
	}

	private function toHtmlListChildren(&$stack, $class = '')
	{
		$result = '';

		foreach ($stack as &$header) {
			if (empty($header['content']) == false) {
				$result .= '<li><a class="link" href="#' . $header['id'] . '">' . $header['content'] . '</a></li>';

				if (empty($header['children']) == false) {
					$result .= $this->toHtmlListChildren($header['children']);
				}

			} elseif (empty($header['children']) == false) {

				$result .= $this->toHtmlListChildren($header['children']);
			}
		}

		return '<ul class = "'. $class .'">' . $result . '</ul>';
	}

	function button($wiki_edit_icons_toggle)
	{
		global $smarty;

		if ($wiki_edit_icons_toggle == 'y' && !isset($_COOKIE['wiki_plugin_edit_view'])) {
			$iconDisplayStyle = ' style="display:none;"';
		} else {
			$iconDisplayStyle = '';
		}
		$button = '<div class="icon_edit_section"' . $iconDisplayStyle . '><a href="tiki-editpage.php?';

		if (!empty($_REQUEST['page'])) {
			$button .= 'page='.urlencode($_REQUEST['page']).'&amp;';
		}

		$this->count++;
		include_once('lib/smarty_tiki/function.icon.php');
		$button .= 'hdr=' . $this->count . '">'.smarty_function_icon(array('_id'=>'page_edit_section', 'alt'=>tra('Edit Section')), $smarty).'</a></div>';

		return $button;
	}
}
