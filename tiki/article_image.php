<?php

// $Header: /cvsroot/tikiwiki/tiki/article_image.php,v 1.11 2005-01-01 00:16:15 damosoft Exp $

// Copyright (c) 2002-2005, Luis Argerich, Garland Foster, Eduardo Polidor, et. al.
// All Rights Reserved. See copyright.txt for details and a complete list of authors.
// Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See license.txt for details.

# $Header: /cvsroot/tikiwiki/tiki/article_image.php,v 1.11 2005-01-01 00:16:15 damosoft Exp $

// application to display an image from the database with 
// option to resize the image dynamically creating a thumbnail on the fly.
if (!isset($_REQUEST["id"])) {
	die;
}

include_once ('lib/init/initlib.php');
include_once ('db/tiki-db.php');

$topiccachefile = "temp";
if ($tikidomain) { $topiccachefile.= "/$tikidomain"; }
$topiccachefile.= "/article.".$_REQUEST["id"];

if (is_file($topiccachefile) and (!isset($_REQUEST["reload"]))) {
	$size = getimagesize($topiccachefile);
//	header ("Content-type: ".$size['mime']);
	readfile($topiccachefile);
	die();
} else {
	include_once ('lib/tikilib.php');
	$tikilib = new Tikilib($dbTiki);
	$data = $tikilib->get_article_image($_REQUEST["id"]);
	$type = $data["image_type"];
	$data = $data["image_data"];
	if ($data["image_data"]) {
		$fp = fopen($topiccachefile,"wb");
		fputs($fp,$data);
		fclose($fp);
	}
}

header ("Content-type: $type");
if (is_file($topiccachefile)) {
	readfile($topiccachefile);
} else {
	echo $data;
}

?>
