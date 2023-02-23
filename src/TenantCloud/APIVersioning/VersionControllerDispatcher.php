<?php

namespace TenantCloud\APIVersioning;

use Illuminate\Routing\ControllerDispatcher;
use Illuminate\Routing\Route;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use TenantCloud\APIVersioning\Version\VersionParser;

class VersionControllerDispatcher extends ControllerDispatcher
{
	/**
	 * Dispatch a request to a given controller and method.
	 *
	 * @param $defaultController
	 * @param $defaultMethod
	 *
	 * @return mixed
	 */
	public function dispatch(Route $route, $defaultController, $defaultMethod)
	{
		[$controller, $method] = $this->resolveVersionClassAndMethod($route, $defaultController, $defaultMethod);
		$parameters = $this->resolveClassMethodDependencies(
			$route->parametersWithoutNulls(),
			$controller,
			$method
		);

		if (method_exists($controller, 'callAction')) {
			return $controller->callAction($method, $parameters);
		}

		return $controller->{$method}(...array_values($parameters));
	}

	/**
	 * @param $controller
	 * @param $method
	 */
	public function resolveVersionClassAndMethod(Route $route, $controller, $method): array
	{
		$version = app(VersionParser::class)->getVersion();

		if (!$route->hasRegisteredVersion()) {
			return [$controller, $method];
		}

		if (!$route->isVersionRegister($version)) {
			throw new BadRequestHttpException();
		}

		return [$route->getVersionController($version), $route->getVersionMethod($version)];
	}
}
