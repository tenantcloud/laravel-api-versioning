<?php

namespace Tests;

use Illuminate\Http\Request;
use TenantCloud\APIVersioning\Version\RequestHeaderVersionParser;
use TenantCloud\APIVersioning\Version\RequestVersionParser;

/**
 * @see RequestHeaderVersionParser
 */
class VersionFromHeaderTest extends TestCase
{
	public function testWithLatestVersion(): void
	{
		$request = new Request();
		$request->headers->set('Version', 'latest');

		$version = app(RequestVersionParser::class)->getVersionString($request);

		self::assertEquals('latest', $version);
	}

	public function testCorrectVersion(): void
	{
		$request = new Request();
		$request->headers->set('Version', '3.0');

		$version = app(RequestVersionParser::class)->getVersionString($request);

		self::assertEquals('3.0', $version);
	}
}
