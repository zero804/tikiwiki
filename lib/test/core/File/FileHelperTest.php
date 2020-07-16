<?php

namespace Tiki\Test\File;

use org\bovigo\vfs\vfsStream;
use org\bovigo\vfs\vfsStreamWrapper;
use Tiki\File\FileHelper;

class FileHelperTest extends \PHPUnit_Framework_TestCase
{

	public static function setUpBeforeClass()
	{
		vfsStreamWrapper::register();
	}

	/**
	 * @covers \Tiki\File\FileHelper::appendCSV
	 */
	public function testAppendCSV()
	{
		$dir = vfsStream::setup(uniqid('', true));
		$path = $dir->url() . '/file-test.csv';

		$this->assertFileNotExists($path);

		$header = ['id', 'name'];
		$rows = [
			[1, 'John Doe'],
		];

		FileHelper::appendCSV($path, $header, $rows);

		$this->assertFileExists($path);
		$fileContents = file_get_contents($path);

		$expectedContent = <<<TXT
id,name
1,"John Doe"
TXT;

		$this->assertEquals($expectedContent, trim($fileContents));

		// Append a new file
		$rows = [
			[2, 'Mary Jane'],
		];

		FileHelper::appendCSV($path, $header, $rows);
		$fileContents = file_get_contents($path);

		$expectedContent .= <<<TXT
\n2,"Mary Jane"
TXT;

		$this->assertEquals($expectedContent, trim($fileContents));
	}
}
