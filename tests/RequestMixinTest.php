<?php

namespace Tests;

use Illuminate\Http\Request;
use PHPUnit\Framework\Attributes\DataProvider;
use TenantCloud\APIVersioning\RequestMixin;
use TenantCloud\APIVersioning\Version\RequestHeaderVersionParser;

/**
 * @see RequestMixin
 */
class RequestMixinTest extends TestCase
{
	#[DataProvider('versionMatchesProvider')]
	public function testVersionMatches(?string $expected, string|array $constraints): void
	{
		$request = Request::create('');
		$request->headers->set(RequestHeaderVersionParser::HEADER, '2.3');

		self::assertSame($expected, $request->versionMatches($constraints)?->__toString());
	}

	public static function versionMatchesProvider(): iterable
	{
		yield ['=2.3', '=2.3'];

		yield ['>=2.3', '>=2.3'];

		yield ['>=2.3', ['>2.4', '>=2.3']];

		yield [null, '=2.4'];

		yield [null, '>=2.4'];
	}
}
