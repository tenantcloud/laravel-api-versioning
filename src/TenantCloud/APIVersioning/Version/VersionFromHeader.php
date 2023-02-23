<?php

namespace TenantCloud\APIVersioning\Version;

use Illuminate\Http\Request;
use TenantCloud\APIVersioning\Version;
use Tests\VersionFromHeaderTest;

/**
 * @see VersionFromHeaderTest
 */
class VersionFromHeader implements VersionParser
{
	public function __construct(private Request $request)
	{
	}

	public function getVersion(): string
	{
		$acceptHeader = $this->request->header('Version', Version::LATEST);

		if ($acceptHeader === Version::LATEST) {
			return Version::currentLatestVersion();
		}

		if (!preg_match('/v?([0-9\.]+)/i', $acceptHeader, $version)) {
			return Version::getLowestVersion();
		}

		return $version[1];
	}
}
