<?php

namespace TenantCloud\APIVersioning\Version;

use Illuminate\Http\Request;

interface RequestVersionParser
{
	public function parse(Request $request): ?string;
}
