<?php

namespace Tests;

use Illuminate\Support\Facades\Route;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use TenantCloud\APIVersioning\Constraint\BadConstraintException;
use TenantCloud\APIVersioning\RouteVersionMixin;
use TenantCloud\APIVersioning\Version\LatestVersion;
use TenantCloud\APIVersioning\Version\SemanticVersion;
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

		self::assertTrue($route->isVersionRegister(new SemanticVersion('1.0')));
		self::assertFalse($route->isVersionRegister(new SemanticVersion('2.0')));
	}

	public function testHasRegisteredRule(): void
	{
		$route = Route::get('/v1/mock/test', [MockResourceController::class, 'index'])
			->versioned('==1.0', [MockResourceController::class, 'index']);

		self::assertTrue($route->hasRegisteredVersion());

		$route = Route::get('/v1/mock/test1', [MockResourceController::class, 'index']);

		self::assertFalse($route->hasRegisteredVersion());
	}

	public function testGetVersionClassAndMethodNoSuggestedConstraint(): void
	{
		$route = Route::get('/v1/mock/test', [MockResourceController::class, 'index'])
			->versioned('==1.0', [MockResourceController::class, 'index']);

		$this->expectException(BadRequestHttpException::class);
		$route->getVersionClassAndMethod(new SemanticVersion('2.0'));
	}

	public function testGetVersionClassAndMethodNoSuggestedConstraintForLatestVersion(): void
	{
		$route = Route::get('/v1/mock/test', [MockResourceController::class, 'index'])
			->versioned('==1.0', [MockResourceController::class, 'index']);

		$this->expectException(BadRequestHttpException::class);
		$route->getVersionClassAndMethod(new LatestVersion());
	}

	public function testBadConstraints(): void
	{
		$route = Route::get('/v1/mock/test', [MockResourceController::class, 'index'])
			->versioned('test', [MockResourceController::class, 'index']);

		$this->expectException(BadConstraintException::class);
		$route->getVersionClassAndMethod(new LatestVersion());
	}

	public function testExistedConstraintForExistedVersion(): void
	{
		$route = Route::get('/v1/mock/test', [MockResourceController::class, 'index'])
			->versioned('==1.0', [MockResourceController::class, 'index']);

		self::assertEquals('index', $route->getVersionClassAndMethod(new SemanticVersion('1.0'))[1]);
	}

	public function testExistedConstraintForLatestVersion(): void
	{
		$route = Route::get('/v1/mock/test', [MockResourceController::class, 'index'])
			->versioned('>=1.0', [MockResourceController::class, 'index']);

		self::assertEquals('index', $route->getVersionClassAndMethod(new LatestVersion())[1]);
	}
}
