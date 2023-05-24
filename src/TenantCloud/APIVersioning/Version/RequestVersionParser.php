<?php

namespace TenantCloud\APIVersioning\Version;

use Illuminate\Http\Request;

interface RequestVersionParser
{
	public function getVersionString(Request $request): ?string;
}
