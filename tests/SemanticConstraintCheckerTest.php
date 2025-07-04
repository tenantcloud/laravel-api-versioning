<?php

namespace Tests;

use PHPUnit\Framework\Attributes\DataProvider;
use TenantCloud\APIVersioning\Constraint\BadConstraintException;
use TenantCloud\APIVersioning\Constraint\Constraint;
use TenantCloud\APIVersioning\Constraint\Operator;
use TenantCloud\APIVersioning\Constraint\SemanticConstraintChecker;
use TenantCloud\APIVersioning\Version\LatestVersion;
use TenantCloud\APIVersioning\Version\SemanticVersion;
use TenantCloud\APIVersioning\Version\Version;
use Throwable;
use ValueError;

/**
 * @see SemanticConstraintChecker
 */
class SemanticConstraintCheckerTest extends TestCase
{
	public function testCompareVersionsWrongConstraint(): void
	{
		$this->expectException(BadConstraintException::class);
		app(SemanticConstraintChecker::class)
			->compareVersions(
				new SemanticVersion('1.0'),
				['test']
			);
	}

	/**
	 * @param list<string> $constraints
	 */
	#[DataProvider('compareVersionsProvider')]
	public function testCompareVersions(Version $version, array $constraints, bool $expectedResult): void
	{
		self::assertEquals($expectedResult, app(SemanticConstraintChecker::class)->compareVersions($version, $constraints));
	}

	public static function compareVersionsProvider(): iterable
	{
		yield 'less' => [
			new SemanticVersion('3.0'),
			['==1.0', '==2.0', '<=2.0'],
			false,
		];

		yield 'greater' => [
			new SemanticVersion('3.0'),
			['==1.0', '==2.0', '>=2.0'],
			true,
		];

		yield 'equal' => [
			new SemanticVersion('3.0'),
			['==1.0', '==3.0', '>=2.0'],
			true,
		];

		yield 'none' => [
			new SemanticVersion('3.0'),
			[],
			false,
		];

		yield 'latest' => [
			new LatestVersion(),
			['==1.0', '==3.0', '>=2.0'],
			true,
		];

		yield 'latest_equals' => [
			new LatestVersion(),
			['==1.0', '==3.0', '==2.0'],
			false,
		];
	}

	/**
	 * @param list<string> $constraints
	 */
	#[DataProvider('matchesProvider')]
	public function testMatches(Version $version, array $constraints, ?Constraint $expectedConstraint): void
	{
		self::assertEquals($expectedConstraint, app(SemanticConstraintChecker::class)->matches($version, $constraints));
	}

	public static function matchesProvider(): iterable
	{
		yield 'less' => [
			new SemanticVersion('3.0'),
			['==1.0', '==2.0', '<=2.0'],
			null,
		];

		yield 'greater' => [
			new SemanticVersion('3.0'),
			['==1.0', '==2.0', '>=2.0'],
			new Constraint(Operator::GREATER_OR_EQUAL, new SemanticVersion('2.0')),
		];

		yield 'equal' => [
			new SemanticVersion('3.0'),
			['==1.0', '==3.0', '>=2.0'],
			new Constraint(Operator::EQUAL, new SemanticVersion('3.0')),
		];

		yield 'none' => [
			new SemanticVersion('3.0'),
			[],
			null,
		];

		yield 'latest' => [
			new LatestVersion(),
			['==1.0', '==3.0', '>=2.0'],
			new Constraint(Operator::GREATER_OR_EQUAL, new SemanticVersion('2.0')),
		];

		yield 'latest_equals' => [
			new LatestVersion(),
			['==1.0', '==3.0', '==2.0'],
			null,
		];
	}

	/**
	 * @param list<string>            $wrongConstraints
	 * @param class-string<Throwable> $exception
	 */
	#[DataProvider('matchesWrongConstraintProvider')]
	public function testMatchesWrongConstraint(array $wrongConstraints, string $exception): void
	{
		$this->expectException($exception);
		app(SemanticConstraintChecker::class)->matches(new SemanticVersion('3.0'), $wrongConstraints);
	}

	public static function matchesWrongConstraintProvider(): iterable
	{
		yield 'text' => [
			['aaa'],
			BadConstraintException::class,
		];

		yield 'text_with_correct_constraint' => [
			['==3.0', 'aaa'],
			BadConstraintException::class,
		];

		yield 'wrong_version' => [
			['==aaa'],
			BadConstraintException::class,
		];

		yield 'wrong_version_with_correct_constraint' => [
			['==aaa', '==3.0'],
			BadConstraintException::class,
		];

		yield 'wrong_operator' => [
			['>>3.0'],
			ValueError::class,
		];

		yield 'wrong_operator_with_correct_constraint' => [
			['==3.0', '>>3.0'],
			ValueError::class,
		];
	}
}
