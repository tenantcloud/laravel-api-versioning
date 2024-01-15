<?php

namespace TenantCloud\APIVersioning;

use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use TenantCloud\APIVersioning\Constraint\Constraint;
use TenantCloud\APIVersioning\Constraint\ConstraintChecker;
use TenantCloud\APIVersioning\Version\RequestVersionParser;
use TenantCloud\APIVersioning\Version\VersionParser;
use Tests\RequestMixinTest;

/**
 * @mixin Request
 *
 * @see RequestMixinTest
 */
class RequestMixin
{
	public function __construct(
		public readonly RequestVersionParser $requestVersionParser,
		public readonly VersionParser $versionParser,
		public readonly ConstraintChecker $constraintChecker,
	) {}

	public function versionMatches(): callable
	{
		$that = $this;

		return function (string|array $constraints) use ($that): ?Constraint {
			/** @var Request $this */
			$versionString = $that->requestVersionParser->parse($this);

			$version = $that->versionParser->parse($versionString);

			return $that->constraintChecker->matches($version, Arr::wrap($constraints));
		};
	}
}
