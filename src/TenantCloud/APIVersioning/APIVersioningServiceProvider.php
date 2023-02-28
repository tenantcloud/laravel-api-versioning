<?php

namespace TenantCloud\APIVersioning;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Http\Request;
use Illuminate\Routing\Contracts\ControllerDispatcher;
use Illuminate\Routing\Route;
use Illuminate\Support\ServiceProvider;
use TenantCloud\APIVersioning\Version\VersionFromHeader;
use TenantCloud\APIVersioning\Version\VersionParser;

class APIVersioningServiceProvider extends ServiceProvider
{
	public function boot(): void
	{
		// Versioned API mixin
		Route::mixin(new RouteVersionMixin());
	}

	public function register(): void
	{
		parent::register();

		$this->mergeConfigFrom(__DIR__ . '/config/api-versioning.php', 'api-versioning');

		// Bind laravel controller dispatcher for our custom versioned dispatcher.
		$this->app->bind(
			ControllerDispatcher::class,
			VersionControllerDispatcher::class
		);

		$this->app->singleton(
			VersionParser::class,
			fn (Application $app) => new VersionFromHeader($app->factory(Request::class))
		);
	}
}
