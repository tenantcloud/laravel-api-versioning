<?php

namespace TenantCloud\APIVersioning\Version;

use Illuminate\Http\Request;
use Tests\VersionFromHeaderTest;

/**
 * @see VersionFromHeaderTest
 */
class RequestHeaderVersionParser implements RequestVersionParser
{
	public function parse(Request $request): ?string
	{
		return $request->header('Version');
	}
}
