<?php

namespace TenantCloud\APIVersioning;

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
		// Bind laravel controller dispatcher for our custom versioned dispatcher.
		$this->app->bind(
			ControllerDispatcher::class,
			VersionControllerDispatcher::class
		);

		$this->app->bind(VersionParser::class, fn () => new VersionFromHeader(request()));
	}
}