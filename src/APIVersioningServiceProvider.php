<?php

namespace TenantCloud\APIVersioning;

use Illuminate\Http\Request;
use Illuminate\Routing\Contracts\ControllerDispatcher;
use Illuminate\Routing\Route;
use Illuminate\Support\ServiceProvider;
use TenantCloud\APIVersioning\Constraint\BuiltInStringConstraintParser;
use TenantCloud\APIVersioning\Constraint\ConstraintChecker;
use TenantCloud\APIVersioning\Constraint\SemanticConstraintChecker;
use TenantCloud\APIVersioning\Constraint\StringConstraintParser;
use TenantCloud\APIVersioning\Version\RequestHeaderVersionParser;
use TenantCloud\APIVersioning\Version\RequestVersionParser;
use TenantCloud\APIVersioning\Version\SemanticVersionParser;
use TenantCloud\APIVersioning\Version\VersionParser;

class APIVersioningServiceProvider extends ServiceProvider
{
	public function register(): void
	{
		parent::register();

		// Bind laravel controller dispatcher for our custom versioned dispatcher.
		$this->app->bind(
			ControllerDispatcher::class,
			VersionControllerDispatcher::class
		);

		// Bind parser from request header as default.
		$this->app->singleton(
			RequestVersionParser::class,
			RequestHeaderVersionParser::class
		);

		$this->app->singleton(
			ConstraintChecker::class,
			SemanticConstraintChecker::class
		);

		$this->app->singleton(
			VersionParser::class,
			SemanticVersionParser::class
		);

		$this->app->singleton(
			StringConstraintParser::class,
			BuiltInStringConstraintParser::class
		);
	}

	public function boot(): void
	{
		$routeVersionMixin = $this->app->make(RouteVersionMixin::class);

		Route::macro('versioned', $routeVersionMixin->versioned());
		Route::macro('getVersionClassAndMethod', $routeVersionMixin->getVersionClassAndMethod());
		Route::macro('hasMatchedConstraint', $routeVersionMixin->hasMatchedConstraint());
		Route::macro('hasRegisteredVersions', $routeVersionMixin->hasRegisteredVersions());

		$requestMixin = $this->app->make(RequestMixin::class);

		Request::macro('versionMatches', $requestMixin->versionMatches());
	}
}
