<?php

namespace TenantCloud\APIVersioning;

final class Version
{
	public const LATEST = 'latest';
	public const V1_0 = '1.0';

	/**
	 * We need current latest version for version comparison when we get self::LATEST version from client.
	 * For ex. route has <=2.0 rule. So if we get latest version from client we should check this rule as well.
	 */
	public static function currentLatestVersion(): string
	{
		return self::V1_0;
	}

	public static function getLowestVersion(): string
	{
		return self::V1_0;
	}
}
