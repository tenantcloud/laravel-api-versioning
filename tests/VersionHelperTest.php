<?php

namespace Tests;

use Illuminate\Support\Arr;
use TenantCloud\APIVersioning\Version\BadVersionException;
use TenantCloud\APIVersioning\Version\VersionHelper;

/**
 * @see VersionHelper
 */
class VersionHelperTest extends TestCase
{
	protected array $operators = [
		'==',
		'>=',
		'<=',
		'>',
		'<',
	];

	protected VersionHelper $helper;

	protected function setUp(): void
	{
		parent::setUp();

		$this->helper = app(VersionHelper::class);
	}

	public function testCorrectRawVersion(): void
	{
		$version = '1.0';
		$operator = Arr::random($this->operators);

		$parsedVersion = $this->helper->parseRawVersion($operator . $version);

		self::assertIsArray($parsedVersion);
		self::assertEquals($version, $parsedVersion['version']);
		self::assertEquals($operator, $parsedVersion['operator']);
	}

	public function testInCorrectRawVersion(): void
	{
		$version = '1.0';
		$operator = 'aaa';

		$this->expectException(BadVersionException::class);
		$this->helper->parseRawVersion($operator . $version);

		$version = 'sttwqt';
		$operator = '==';

		$this->expectException(BadVersionException::class);
		$this->helper->parseRawVersion($operator . $version);
	}

	public function testCompareVersion(): void
	{
		self::assertFalse($this->helper->compareVersions('3.0', ['==1.0', '==2.0', '<=2.0']));
		self::assertTrue($this->helper->compareVersions('3.0', ['==1.0', '==2.0', '>=2.0']));
		self::assertTrue($this->helper->compareVersions('3.0', ['==1.0', '==3.0', '>=2.0']));
		self::assertFalse($this->helper->compareVersions('3.0', []));
	}

	public function testSuggestedVersionRule(): void
	{
		self::assertEquals('', $this->helper->suggestedVersionRule('3.0', []));
		self::assertEquals('', $this->helper->suggestedVersionRule('3.0', ['==1.0']));
		self::assertEquals('', $this->helper->suggestedVersionRule('3.0', ['==1.0', '<=2.0', '<3.0']));
		self::assertEquals('<=3.0', $this->helper->suggestedVersionRule('3.0', ['==1.0', '<=2.0', '<=3.0']));
		self::assertEquals('<=3.0', $this->helper->suggestedVersionRule('2.0', ['==1.0', '<=2.0', '<=3.0']));
		self::assertEquals('==2.0', $this->helper->suggestedVersionRule('2.0', ['==1.0', '==2.0', '<=3.0']));
	}
}
