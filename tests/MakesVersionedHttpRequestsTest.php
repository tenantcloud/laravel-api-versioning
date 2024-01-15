<?php

namespace Tests;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use TenantCloud\APIVersioning\Testing\MakesVersionedHttpRequests;

/**
 * @see MakesVersionedHttpRequests
 */
class MakesVersionedHttpRequestsTest extends TestCase
{
	use MakesVersionedHttpRequests;

	public function testSendsVersionHeader(): void
	{
		Route::get('test', fn (Request $request) => $request->header('Version'));

		$this->withApiVersion('asd')
			->getJson('test')
			->assertContent('asd');

		$this->getJson('test')
			->assertContent('');
	}
}
