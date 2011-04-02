<?php
// (c) Copyright 2002-2011 by authors of the Tiki Wiki CMS Groupware Project
// 
// All Rights Reserved. See copyright.txt for details and a complete list of authors.
// Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See license.txt for details.
// $Id$

/**
 * Handler class for Image
 * 
 * Letter key: ~i~
 *
 */
class Tracker_field_Image extends Tracker_Field_File
{
	function getFieldData(array $requestData = array())
	{
		global $prefs, $smarty;
		
		$ins_id = $this->getInsertId();

		if (!empty($prefs['fgal_match_regex'])) {
			if (!preg_match('/' . $prefs['fgal_match_regex'] . '/', $_FILES[$ins_id]['name'], $reqs)) {
				$smarty->assign('msg', tra('Invalid imagename (using filters for filenames)'));
				$smarty->display("error.tpl");
				die;
			}
		}
		if (!empty($prefs['fgal_nmatch_regex'])) {
			if (preg_match('/' . $prefs['fgal_nmatch_regex'] . '/', $_FILES[$ins_id]['name'], $reqs)) {
				$smarty->assign('msg', tra('Invalid imagename (using filters for filenames)'));
				$smarty->display("error.tpl");
				die;
			}
		}
		if (!empty($requestData)) {
			return parent::getFieldData($requestData);
		} else {
			return array( 'value' => $this->getValue() );
		}
	}

	function renderInnerOutput( $context )
	{
		global $prefs;
		$smarty = TikiLib::lib('smarty');

		$val = $this->getConfiguration('value');
		$list_mode = $context['list_mode'];
		if ($list_mode == 'csv') {
			return $val; // return the filename
		}
		$pre = '';
		if ( !empty($val) && file_exists($val) ) {
			$params['file'] = $val;
			$shadowtype = $this->getOption(5);
			if ($prefs['feature_shadowbox'] == 'y' && !empty($shadowtype)) {
				switch ($shadowtype) {
				case 'item':
					$rel = '['.$this->getItemId().']';
					break;
				case 'individual':
					$rel = '';
					break;
				default:
					$rel = '['.$this->getConfiguration('fieldId').']';
					break;
				}
				$pre = "<a href=\"$val\" rel=\"shadowbox$rel;type=img\">";
			}
			if ( $this->getOption(0) || $this->getOption(1) || $this->getOption(2) || $this->getOption(3)) {
				$image_size_info = getimagesize($val);
			}
			if ($list_mode != 'n') {
				if ($this->getOption(0) || $this->getOption(1)) {
					list( $params['width'], $params['height']) = $this->get_resize_dimensions( $image_size_info[0], $image_size_info[1],
																			$this->getOption(0), $this->getOption(1));
				}
			} else {
				if ($this->getOption(2) || $this->getOption(3)) {
					list( $params['width'], $params['height']) = $this->get_resize_dimensions( $image_size_info[0], $image_size_info[1],
																			$this->getOption(2), $this->getOption(3));
				}
			}
		} else {
			$params['file'] = 'img/icons/na_pict.gif';
			$params['alt'] = 'n/a';
		}
		require_once $smarty->_get_plugin_filepath('function', 'html_image');
		$ret = smarty_function_html_image($params, $smarty);
		if (!empty($pre))
			$ret = $pre.$ret.'</a>';
		return $ret;
	}

	function renderInput($context = array())
	{
		return $this->renderTemplate('trackerinput/image.tpl', $context);
	}

	/**
	 * Calculate the size of a resized image
	 * 
	 * TODO move to a lib (Images depends on Imagick or GD which this doesn't need)
	 * 
	 * @param int $image_width (existing image width)
	 * @param int $image_height	(existing image height)
	 * @param int $max_width (max width to scale to)
	 * @param int $max_height (optional max height)
	 * 
	 * @return array(int $resized_width, int $resized_height)
	 */
	private function get_resize_dimensions( $image_width, $image_height, $max_width = null, $max_height = null) {
		if ( !$max_height || ($max_width && $image_width > $image_height && $image_height < $max_height)) {
			$ratio = $max_width / $image_width;
		} else {
			$ratio = $max_height / $image_height;
		}
		return array(round($image_width * $ratio), round($image_height * $ratio));
	}
}

