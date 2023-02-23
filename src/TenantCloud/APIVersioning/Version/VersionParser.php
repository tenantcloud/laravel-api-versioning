<?php

namespace TenantCloud\APIVersioning\Version;

use Illuminate\Http\Request;

interface VersionParser
{
	public function __construct(Request $request);

	public function getVersion(): string;
}
