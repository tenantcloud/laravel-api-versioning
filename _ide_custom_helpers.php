<?php

namespace Illuminate\Routing {

	use Illuminate\Support\Traits\Macroable;
	use TenantCloud\APIVersioning\RouteVersionMixin;
	use TenantCloud\APIVersioning\Version\Version;

	/**
	 * @see \Illuminate\Routing\Route
	 * @see RouteVersionMixin
	 * @method self versioned(string $rule, array|string|callable|null $action = null)
	 * @method array{object, string} getVersionClassAndMethod(Version $version)
	 * @method bool hasMatchedConstraint(Version $version)
	 * @method bool hasRegisteredVersions()
	 * @method array parametersWithoutNulls()
	 * @method null|string getName()
	 * @property array $action
	 */
	class Route
	{
		use Macroable;

		/**
		 * @var array
		 */
		public $action;
	}
}
