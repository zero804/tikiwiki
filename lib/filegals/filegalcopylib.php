<?php

// (c) Copyright 2002-2017 by authors of the Tiki Wiki CMS Groupware Project
//
// All Rights Reserved. See copyright.txt for details and a complete list of authors.
// Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See license.txt for details.
// $Id$

//this script may only be included - so its better to die if called directly.
if (strpos($_SERVER["SCRIPT_NAME"], basename(__FILE__)) !== false) {
	header("location: index.php");
	exit;
}

/**
 * Class FileGalCopyLib
 *
 * Container for functions involved in files:copy and files:move console commands
 *
 */
class FileGalCopyLib extends FileGalLib
{

	/**
	 * Processes a list of files to be copied/moved to a directory in the filesystem
	 *
	 * @param array $files
	 * @param string $destinationPath
	 * @param string $sourcePath
	 * @param bool $move
	 * @return array					feedback messages
	 */

	function processCopy($files, $destinationPath, $sourcePath = '', $move = false)
	{

		$feedback = [];

		// cycle through all files to copy
		foreach ($files as $file) {
			$result = $this->copyFile($file, $destinationPath, $sourcePath, $move);
			if (isset($result['error'])) {
				if ($move) {
					$feedback[] = '<span class="text-danger">' . tr('Move was not successful for "%0"', $file['filename']) . '<br>(' . $result['error'] . ')</span>';
				} else {
					$feedback[] = '<span class="text-danger">' . tr('Copy was not successful for "%0"', $file['filename']) . '<br>(' . $result['error'] . ')</span>';
				}
			} else {
				if ($move) {
					$feedback[] = tra('Move was successful') . ': ' . $file['filename'];
				} else {
					$feedback[] = tra('Copy was successful') . ': ' . $file['filename'];
				}
			}
		}
		return $feedback;
	}

	/**
	 *	Takes a file from a file gallery and copies/moves it to a local path
	 *
	 * @param array $file
	 * @param string $destinationPath
	 * @param string $sourcePath
	 * @param bool $move
	 * @return array					[fileName[,error]]
	 */
	function copyFile($file, $destinationPath, $sourcePath = '', $move = false)
	{

		$fileId = $file['fileId'];
		$filePath = $file['path'];
		$fileName = $file['filename'];

		if (! empty($filePath)) { // i.e., fgal_use_db !== 'y'
			if ($sourcePath == '') {
				return ['error' => tra('Source path empty')];
			}
			if (! copy($sourcePath . $filePath, $destinationPath . $fileName)) {
				if (! is_writable($destinationPath)) {
					return ['error' => tra('Cannot write to this path: ') . $destinationPath];
				} else {
					return ['error' => tra('Cannot read this file: ') . $sourcePath . $filePath];
				}
			}
		} else {
			$filesTable = $this->table('tiki_files');
			$fileData = $filesTable->fetchOne('data', ['fileId' => (int)$fileId]);
			if (file_put_contents($destinationPath . $fileName, $fileData) === false) {
				if (! is_writable($destinationPath)) {
					return ['error' => tra('Cannot write to this path: ') . $destinationPath];
				} else {
					return ['error' => tra('Cannot get filedata from db')];
				}
			}
		}

		if ($move) {
			// This is a hack to bypass inconsistency in filegallib that would cause a Notice
			// message to the user.
			// remove_file() needs $file['data'], despite it being an optional field.
			// In the end, no Handlers in FileGallery implement any usage of $file['data']
			$file['data'] = null;

			if ($this->remove_file($file, '', true) === false) {
				return ['error' => tra('Cannot remove file from gallery')];
			}
		}

		return [
			'fileName' => $fileName,
		];
	}
}
