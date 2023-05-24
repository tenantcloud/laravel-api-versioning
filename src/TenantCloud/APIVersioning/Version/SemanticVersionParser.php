<?php

namespace TenantCloud\APIVersioning\Version;

use Illuminate\Support\Str;
use Tests\SemanticVersionParserTest;

/**
 * @see SemanticVersionParserTest
 */
class SemanticVersionParser implements VersionParser
{
	public const LATEST = 'latest';
	public const REGEX_VERSION_RULE = '/v?([0-9\.]+)/i';

	public function parse(?string $version): Version
	{
		if ($version === null || Str::lower($version) === self::LATEST) {
			return new LatestVersion();
		}

		if (!preg_match(self::REGEX_VERSION_RULE, $version, $matches)) {
			throw new BadVersionException("Version {$version} didn't match " . self::REGEX_VERSION_RULE);
		}

		return new SemanticVersion($matches[1]);
	}
}
