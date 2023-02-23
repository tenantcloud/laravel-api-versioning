<?php

namespace TenantCloud\APIVersioning;

use Illuminate\Routing\Route;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use TenantCloud\APIVersioning\Version\VersionHelper;
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
	/**
	 * Fluent variant for "as" option of a resource.
	 */
	public function versioned(): callable
	{
		/*
		 * @param string $version
		 * @param  array|string|callable|null  $action
		 */
		return function (string $version, $action = null) {
			$this->action['versions'][$version] = Arr::except($this->parseAction($action), ['prefix']);

			return $this;
		};
	}

	public function getVersionController(): callable
	{
		return function (string $version) {
			$suggestedVersionRule = app(VersionHelper::class)->suggestedVersionRule($version, array_keys($this->action['versions']));

			$versionData = Arr::get($this->action['versions'], $suggestedVersionRule);

			$class = Str::parseCallback($versionData['uses'])[0];

			return $this->container->make(ltrim($class, '\\'));
		};
	}

	public function getVersionMethod(): callable
	{
		return function (string $version) {
			$suggestedVersionRule = app(VersionHelper::class)->suggestedVersionRule($version, array_keys($this->action['versions']));

			$versionData = Arr::get($this->action['versions'], $suggestedVersionRule);

			return Str::parseCallback($versionData['uses'])[1];
		};
	}

	public function isVersionRegister(): callable
	{
		return function (string $version) {
			if (!Arr::has($this->action, 'versions')) {
				return false;
			}

			return app(VersionHelper::class)->compareVersions($version, array_keys($this->action['versions']));
		};
	}

	public function hasRegisteredVersion(): callable
	{
		return fn () => Arr::has($this->action, 'versions');
	}
}
