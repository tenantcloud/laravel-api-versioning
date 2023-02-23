<?php

namespace TenantCloud\APIVersioning;

use Illuminate\Config\Repository;

final class Version
{
	public const LATEST = 'latest';
	public const LOWEST = '1.0';

	/**
	 * We need current latest version for version comparison when we get self::LATEST version from client.
	 * For ex. route has <=2.0 rule. So if we get latest version from client we should check this rule as well.
	 */
	public static function currentLatestVersion(): string
	{
		return resolve(Repository::class)->get('api-versioning.latest_version');
	}

	public static function getLowestVersion(): string
	{
		return resolve(Repository::class)->get('api-versioning.lowest_version');
	}
}
