<?php

namespace TenantCloud\APIVersioning;

use Illuminate\Routing\Route;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use TenantCloud\APIVersioning\Constraint\ConstraintChecker;
use TenantCloud\APIVersioning\Version\Version;
use Tests\RouteVersionMixinTest;

/**
 * @method array parseAction($action)
 *
 * @see Route
 * @see RouteVersionMixinTest
 * @mixin Route
 */
class RouteVersionMixin
{
	public function __construct(public readonly ConstraintChecker $checker)
	{
	}

	/**
	 * Fluent variant for "as" option of a resource.
	 */
	public function versioned(): callable
	{
		/*
		 * @param string $version
		 * @param  array|string|callable|null  $action
		 */
		return function (string $rule, $action = null) {
			$this->action['versions'][$rule] = Arr::except($this->parseAction($action), ['prefix']);

			return $this;
		};
	}

	public function getVersionClassAndMethod(): callable
	{
		$that = $this;

		return function (Version $version) use ($that) {
			$suggestedConstraint = $that->checker->matches($version, array_keys($this->action['versions']));

			if ($suggestedConstraint === null) {
				throw new BadRequestHttpException();
			}

			$versionData = Arr::get($this->action['versions'], (string) $suggestedConstraint);

			return [
				$this->container->make(ltrim(Str::parseCallback($versionData['uses'])[0], '\\')),
				Str::parseCallback($versionData['uses'])[1],
			];
		};
	}

	public function isVersionRegister(): callable
	{
		$that = $this;

		return function (Version $version) use ($that) {
			if (!Arr::has($this->action, 'versions')) {
				return false;
			}

			return $that->checker->compareVersions($version, array_keys($this->action['versions']));
		};
	}

	public function hasRegisteredVersion(): callable
	{
		return fn () => Arr::has($this->action, 'versions');
	}
}
