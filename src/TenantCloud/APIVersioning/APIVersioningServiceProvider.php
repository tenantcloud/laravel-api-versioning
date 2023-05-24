<?php

namespace TenantCloud\APIVersioning;

use Illuminate\Http\Request;
use Illuminate\Routing\Contracts\ControllerDispatcher;
use Illuminate\Routing\Route;
use Illuminate\Support\ServiceProvider;
use TenantCloud\APIVersioning\Constrain\BuiltInStringConstraintParser;
use TenantCloud\APIVersioning\Constrain\ConstraintChecker;
use TenantCloud\APIVersioning\Constrain\SemanticConstraintChecker;
use TenantCloud\APIVersioning\Constrain\StringConstraintParser;
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
		// Versioned API mixin
		$mixin = new RouteVersionMixin($this->app->make(ConstraintChecker::class));

		Route::macro('versioned', $mixin->versioned());
		Route::macro('getVersionClassAndMethod', $mixin->getVersionClassAndMethod());
		Route::macro('isVersionRegister', $mixin->isVersionRegister());
		Route::macro('hasRegisteredVersion', $mixin->hasRegisteredVersion());
	}
}
