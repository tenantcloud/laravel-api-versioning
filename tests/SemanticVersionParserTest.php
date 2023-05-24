<?php

namespace Tests;

use TenantCloud\APIVersioning\Version\BadVersionException;
use TenantCloud\APIVersioning\Version\LatestVersion;
use TenantCloud\APIVersioning\Version\SemanticVersion;
use TenantCloud\APIVersioning\Version\SemanticVersionParser;

/**
 * @see SemanticVersionParser
 */
class SemanticVersionParserTest extends TestCase
{
	public function testParseLatestVersion(): void
	{
		self::assertInstanceOf(LatestVersion::class, (new SemanticVersionParser())->parse('latest'));
		self::assertInstanceOf(LatestVersion::class, (new SemanticVersionParser())->parse('LATEST'));
	}

	public function testWrongVersionPattern(): void
	{
		$this->expectException(BadVersionException::class);
		(new SemanticVersionParser())->parse('test');
	}

	public function testCorrectSemanticVersion(): void
	{
		$version = (new SemanticVersionParser())->parse('2.0');
		self::assertEquals(new SemanticVersion('2.0'), $version);
		self::assertEquals('2.0', (string) $version);
	}
}
