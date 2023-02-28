<?php

namespace Tests;

use Illuminate\Http\Request;
use TenantCloud\APIVersioning\Version;
use TenantCloud\APIVersioning\Version\VersionFromHeader;

/**
 * @see VersionFromHeader
 */
class VersionFromHeaderTest extends TestCase
{
	public function testWithNoVersion(): void
	{
		$request = new Request();

		$version = (new VersionFromHeader(fn () => $request))->getVersion();

		self::assertEquals(Version::currentLatestVersion(), $version);
	}

	public function testWithLatestVersion(): void
	{
		$request = new Request();
		$request->headers->set('Version', 'latest');

		$version = (new VersionFromHeader(fn () => $request))->getVersion();

		self::assertEquals(Version::currentLatestVersion(), $version);
	}

	public function testIncorrectVersion(): void
	{
		$request = new Request();
		$request->headers->set('Version', 'test');

		$version = (new VersionFromHeader(fn () => $request))->getVersion();

		self::assertEquals(Version::getLowestVersion(), $version);
	}

	public function testCorrectVersion(): void
	{
		$request = new Request();
		$request->headers->set('Version', '3.0');

		$version = (new VersionFromHeader(fn () => $request))->getVersion();

		self::assertEquals('3.0', $version);
	}
}
