<?php
// (c) Copyright 2002-2010 by authors of the Tiki Wiki CMS Groupware Project
// 
// All Rights Reserved. See copyright.txt for details and a complete list of authors.
// Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See license.txt for details.
// $Id$

//this script may only be included - so its better to die if called directly.
if (strpos($_SERVER["SCRIPT_NAME"],basename(__FILE__)) !== false) {
  header("location: index.php");
  exit;         
}  

function smarty_function_object_link( $params, $smarty ) {

	if( ! isset( $params['type'], $params['id'] ) ) {
		return tra('No object information provided.');
	}

	$type = $params['type'];
	$object = $params['id'];
	$title = isset( $params['title'] ) ? $params['title'] : null;

	switch( $type ) {
	case 'wiki page':
	case 'wikipage':
	case 'wiki':
		$function = 'smarty_function_object_link_wiki';
		break;
	case 'user':
		$function = 'smarty_function_object_link_user';
		break;
	case 'external':
		$function = 'smarty_function_object_link_external';
		break;
	case 'relation_source':
		$function = 'smarty_function_object_link_relation_source';
		break;
	case 'relation_target':
		$function = 'smarty_function_object_link_relation_target';
		break;
	default:
		return tr('No rules to display object %1 of type %0.', $type, $object );
	}

	return $function( $object, $title );
}

function smarty_function_object_link_wiki( $page, $title = null ) {
	require_once 'lib/smarty_tiki/modifier.sefurl.php';
	require_once 'lib/smarty_tiki/modifier.escape.php';

	$escapedPage = smarty_modifier_escape( $title ? $title : $page );
	$escapedHref = smarty_modifier_escape( smarty_modifier_sefurl( $page, 'wiki' ) );

	return '<a href="' . $escapedHref . '">' . $escapedPage . '</a>';
}

function smarty_function_object_link_user( $user, $title = null ) {
	require_once 'lib/smarty_tiki/modifier.userlink.php';

	return smarty_modifier_userlink( $user, 'link', 'not_set', $title ? $title : '' );
}

function smarty_function_object_link_external( $link, $title = null ) {
	global $cachelib; require_once 'lib/cache/cachelib.php';
	global $tikilib;

	if( ! $title ) {
		if( ! $title = $cachelib->getCached( $link, 'object_link_ext_title' ) ) {
			$body = $tikilib->httprequest( $link );
			if( preg_match( '|<title>(.+)</title>|', $body, $parts ) ) {
				$title = TikiFilter::get('text')->filter($parts[1]);
			} else {
				$title = $link;
			}

			$cachelib->cacheItem( $link, $title, 'object_link_ext_title' );
		}
	}

	require_once 'lib/smarty_tiki/modifier.escape.php';
	$escapedHref = smarty_modifier_escape( $link );
	$escapedTitle = smarty_modifier_escape( $title );
	$data = '<a href="' . $escapedHref . '">' . $escapedTitle . '</a>';

	return $data;
}

function smarty_function_object_link_relation_source( $relationId, $title = null ) {
	return smarty_function_object_link_relation_end( 'source', $relationId, $title );
}

function smarty_function_object_link_relation_target( $relationId, $title = null ) {
	return smarty_function_object_link_relation_end( 'target', $relationId, $title );
}

function smarty_function_object_link_relation_end( $end, $relationId, $title = null ) {
	global $relationlib; require_once 'lib/attributes/relationlib.php';
	global $attributelib; require_once 'lib/attributes/attributelib.php';
	global $cachelib; require_once 'lib/cache/cachelib.php';

	$cacheKey = "$relationId:$end:$title";

	if( ! $out = $cachelib->getCached( $cacheKey, 'relation_link' ) ) {
		$relation = $relationlib->get_relation( $relationId );

		if( $relation ) {
			if( ! $title ) {
				$attributes = $attributelib->get_attributes( 'relation', $relationId );
				$key = 'tiki.relation.' . $end;

				if( isset( $attributes[$key] ) && ! empty( $attributes[$key] ) ) {
					$title = $attributes[$key];
				}
			}

			$type = $relation[ $end . '_type' ];
			$object = $relation[ $end . '_itemId' ];

			global $smarty;
			$out = smarty_function_object_link( array(
				'type' => $type,
				'id' => $object,
				'title' => $title,
			), $smarty );

			$cachelib->cacheItem( $cacheKey, $out, 'relation_link' );
		} else {
			$out = tra('Relation not found.');
		}
	}

	return $out;
}

