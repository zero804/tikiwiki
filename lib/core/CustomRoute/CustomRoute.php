<?php
// (c) Copyright 2002-2016 by authors of the Tiki Wiki CMS Groupware Project
//
// All Rights Reserved. See copyright.txt for details and a complete list of authors.
// Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See license.txt for details.
// $Id$

namespace Tiki\CustomRoute;

use TikiLib;

/**
 * Custom Route
 */
class CustomRoute
{
	/**
	 * Checks if the $path matches a custom route
	 *
	 * @param string $path Web URI to match
	 * @return Item|bool
	 */
	public static function matchRoute($path)
	{
		$routeLib = TikiLib::lib('custom_route');
		$routes = $routeLib->getRouteByType(null, ['type' => 'asc']);

		foreach ($routes as $row) {
			$route = new Item($row['type'], $row['from'], $row['redirect'], $row['description'], $row['active'], $row['short_url'], $row['id']);
			if ($match = $route->matchRoute($path)) {
				return $route;
			}
		}

		return false;
	}

	/**
	 * Perform the redirect based on $route and $path
	 * The user will be redirected either to the page (using http redirect) or to a error (404)
	 *
	 * @param Item $route
	 * @param string $path
	 */
	public static function executeRoute($route, $path)
	{
		$access = TikiLib::lib('access');
		if ($redirect = $route->getRedirectPath($path)) {
			$access->redirect($redirect);
		} else {
			$access->display_error($path, tra("Page cannot be found"), '404');
		}
	}

	/**
	 * Generate a hash to use in the shorturls to redirect to the selected page.
	 * @param int $size The hash size
	 * @return bool|string
	 * @throws \Exception
	 */
	public static function generateShortUrlHash($size = 6)
	{

		$routeLib = TikiLib::lib('custom_route');

		$chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
		$validHash = false;
		$hash = '';

		while (! $validHash) {
			$hash = substr(str_shuffle($chars), 0, $size);
			$validHash = ! $routeLib->checkRouteExists($hash);
		}

		return $hash;
	}

	/**
	 * Check if the given object already has a short_url generated
	 *
	 * @param $type
	 * @param $objectId
	 * @return null|String
	 * @throws \Exception
	 */
	public static function getShortUrl($type, $objectId)
	{

		$routeLib = TikiLib::lib('custom_route');

		$conditions = [
			'type' => Item::TYPE_OBJECT,
			'redirect' => json_encode(['type' => $type, 'object' => $objectId]),
			'active' => 1,
			'short_url' => 1,
		];

		$route = $routeLib->findRoute($conditions);

		return empty($route) ? null : $route['from'];
	}
}
