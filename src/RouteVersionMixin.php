<?php

namespace TenantCloud\APIVersioning;

use Illuminate\Routing\Route;
use Illuminate\Routing\Router;
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
 *
 * @mixin Route
 */
class RouteVersionMixin
{
	public function __construct(
		public readonly ConstraintChecker $checker
	) {}

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
			if ($action === null) {
				$this->action['versions'][$rule] = Arr::only($this->action, ['uses', 'controller']);
			} else {
				/* @phpstan-ignore-next-line property.protected */
				if ((fn () => /** @var Router $this */ $this->actionReferencesController($action))->call($this->router)) {
					/** @phpstan-ignore-next-line property.protected */
					$action = (fn () => $this->convertToControllerAction($action))->call($this->router);
				}
				$this->action['versions'][$rule] = Arr::except($this->parseAction($action), ['prefix']);
			}

			return $this;
		};
	}

	public function getVersionClassAndMethod(): callable
	{
		$that = $this;

		return function (Version $version) use ($that) {
			/** @var string[] $constraints */
			$constraints = array_keys($this->action['versions']);

			$suggestedConstraint = $that->checker->matches($version, $constraints);

			if ($suggestedConstraint === null) {
				throw new BadRequestHttpException("{$version} is not supported.");
			}

			/** @var array{'uses': string} $versionData */
			$versionData = Arr::get($this->action['versions'], (string) $suggestedConstraint);

			return [
				$this->container->make(ltrim((string) Str::parseCallback($versionData['uses'])[0], '\\')),
				Str::parseCallback($versionData['uses'])[1],
			];
		};
	}

	public function hasMatchedConstraint(): callable
	{
		$that = $this;

		return function (Version $version) use ($that) {
			if (!Arr::has($this->action, 'versions')) {
				return false;
			}

			/** @var string[] $constraints */
			$constraints = array_keys($this->action['versions']);

			return $that->checker->compareVersions($version, $constraints);
		};
	}

	public function hasRegisteredVersions(): callable
	{
		return fn () => Arr::has($this->action, 'versions');
	}
}
