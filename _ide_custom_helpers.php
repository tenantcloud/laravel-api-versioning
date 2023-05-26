<?php

namespace Illuminate\Routing {

	use TenantCloud\APIVersioning\RouteVersionMixin;
	use TenantCloud\APIVersioning\Version\Version;

	/**
	 * @see \Illuminate\Routing\Route
	 * @see RouteVersionMixin
	 * @method self versioned(string $rule, array|string|callable|null $action = null)
	 * @method array<mixed> getVersionClassAndMethod(Version $version)
	 * @method bool hasMatchedConstraint(Version $version)
	 * @method bool hasRegisteredVersions()
	 */
	class Route
	{
	}
}
