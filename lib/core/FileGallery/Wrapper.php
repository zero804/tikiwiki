<?php
// (c) Copyright 2002-2013 by authors of the Tiki Wiki CMS Groupware Project
//
// All Rights Reserved. See copyright.txt for details and a complete list of authors.
// Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See license.txt for details.
// $Id$

class FileGallery_Wrapper
{
	private $data;
	private $path;
	private $galleryId;

	private $temporaryFile = false;
	private $loadedData = false;

	function __construct($data, $path, $galleryId)
	{
		$this->data = $data;
		$this->path = $path;
		$this->galleryId = $galleryId;
	}

	function __destruct()
	{
		if (false !== $this->temporaryFile) {
			unlink($this->temporaryFile);
		}
	}

	function getReadableFile()
	{
		if (empty($this->path)) {
			if (false !== $this->temporaryFile) {
				return $this->temporaryFile;
			}

			$sIniUploadTmpDir = ini_get('upload_tmp_dir');
			if (!empty($sIniUploadTmpDir)) {
				$sTmpDir = ini_get('upload_tmp_dir');
			} else {
				$sTmpDir = '/tmp';
			}

			$this->temporaryFile = $tmpfname = tempnam($sTmpDir, 'wiki_');
			@file_put_contents($tmpfname, $this->data);
			return $tmpfname;
		} else {
			$savedir = $this->getGallerySaveDir();

			return $savedir . $this->path;
		}
	}

	function getContents()
	{
		if (! empty($this->path)) {
			$savedir = $this->getGallerySaveDir();

			$tmpfname = $savedir . $this->path;

			return file_get_contents($tmpfname);
		} else {
			return $this->data;
		}
	}

	private function getGallerySaveDir()
	{
		$filegallib = TikiLib::lib('filegal');
		return $filegallib->get_gallery_save_dir($this->galleryId);
	}
}

