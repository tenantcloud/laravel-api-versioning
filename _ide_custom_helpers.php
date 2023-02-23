<?php

namespace Illuminate\Routing {

	use TenantCloud\APIVersioning\RouteVersionMixin;

	/**
	 * @see \Illuminate\Routing\Route
	 * @see RouteVersionMixin
	 * @method self versioned(string $version, array|string|callable|null $action = null)
	 * @method mixed getVersionController(string $version)
	 * @method mixed getVersionMethod(string $version)
	 * @method bool isVersionRegister(string $version)
	 * @method bool hasRegisteredVersion()
	 */
	class Route
	{
	}
}
