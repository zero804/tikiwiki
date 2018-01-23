<?php
// (c) Copyright 2002-2016 by authors of the Tiki Wiki CMS Groupware Project
//
// All Rights Reserved. See copyright.txt for details and a complete list of authors.
// Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See license.txt for details.
// $Id$

class FilegalsTest extends TikiTestCase
{
	function testPNGIsNotSVG()
	{
		$fgallib = TikiLib::lib('filegal');
		$path = 'lib/test/filegals/testdata.png';
		$data = file_get_contents($path);
		$this->assertFalse($fgallib->fileContentIsSVG($data));
		$this->assertFalse($fgallib->fileIsSVG($path));
	}

	function testSVGDetect()
	{
		$fgallib = TikiLib::lib('filegal');
		$path = 'lib/test/filegals/testdata.svg';
		$data = file_get_contents($path);
		$this->assertTrue($fgallib->fileContentIsSVG($data));
		$this->assertTrue($fgallib->fileIsSVG($path));
	}

	function testCompressedPNGIsNotSVG()
	{
		$fgallib = TikiLib::lib('filegal');
		$path = 'lib/test/filegals/testdata.png.gz';
		$data = file_get_contents($path);
		$this->assertFalse($fgallib->fileContentIsSVG($data));
		$this->assertFalse($fgallib->fileIsSVG($path));
	}

	function testSVGDetectGzipped()
	{
		$fgallib = TikiLib::lib('filegal');
		$path = 'lib/test/filegals/testdata.svgz';
		$data = file_get_contents($path);
		$this->assertTrue($fgallib->fileContentIsSVG($data));
		$this->assertTrue($fgallib->fileIsSVG($path));
	}

	function testSVGWithPNGExtensionIsDetectedAsSVG()
	{
		$fgallib = TikiLib::lib('filegal');
		$path = 'lib/test/filegals/svg_content.png';
		$this->assertTrue($fgallib->fileIsSVG($path));
	}
}
