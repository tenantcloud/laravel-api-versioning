<?php

namespace TenantCloud\APIVersioning\Version;

use Illuminate\Http\Request;
use Tests\VersionFromHeaderTest;

/**
 * @see VersionFromHeaderTest
 */
class RequestHeaderVersionParser implements RequestVersionParser
{
	public const HEADER = 'Version';

	public function parse(Request $request): ?string
	{
		return $request->header(self::HEADER);
	}
}
