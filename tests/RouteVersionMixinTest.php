<?php

namespace Tests;

use Illuminate\Support\Facades\Route;
use TenantCloud\APIVersioning\RouteVersionMixin;
use Tests\Mocks\MockResourceController;

/**
 * @see RouteVersionMixin
 */
class RouteVersionMixinTest extends TestCase
{
	public function testRegisterVersionRule(): void
	{
		$route = Route::get('/v1/mock/test', [MockResourceController::class, 'index'])
			->versioned('1.0', [MockResourceController::class, 'index']);

		self::assertIsArray($route->action['versions']['1.0']);
	}

	public function testRegisterMultipleVersions(): void
	{
		$route = Route::get('/v1/mock/test', [MockResourceController::class, 'index'])
			->versioned('==1.0', [MockResourceController::class, 'index'])
			->versioned('==2.0', [MockResourceController::class, 'index']);

		self::assertIsArray($route->action['versions']['==1.0']);
		self::assertIsArray($route->action['versions']['==2.0']);
	}

	public function testMultipleRegisterSameVersion(): void
	{
		$route = Route::get('/v1/mock/test', [MockResourceController::class, 'index'])
			->versioned('==1.0', [MockResourceController::class, 'index'])
			->versioned('==1.0', [MockResourceController::class, 'index']);

		self::assertIsArray($route->action['versions']['==1.0']);
	}

	public function testRegisteredVersionCheck(): void
	{
		$route = Route::get('/v1/mock/test', [MockResourceController::class, 'index'])
			->versioned('==1.0', [MockResourceController::class, 'index']);

		self::assertTrue($route->isVersionRegister('1.0'));
		self::assertFalse($route->isVersionRegister('2.0'));
	}

	public function testHasRegisteredRule(): void
	{
		$route = Route::get('/v1/mock/test', [MockResourceController::class, 'index'])
			->versioned('==1.0', [MockResourceController::class, 'index']);

		self::assertTrue($route->hasRegisteredVersion());

		$route = Route::get('/v1/mock/test1', [MockResourceController::class, 'index']);

		self::assertFalse($route->hasRegisteredVersion());
	}
}
