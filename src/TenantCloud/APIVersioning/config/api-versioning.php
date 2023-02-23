<?php

use TenantCloud\APIVersioning\Version;

return [
	'latest_version' => env('LATEST_API_VERSIONING', Version::LATEST),
	'lowest_version' => env('LOWEST_API_VERSIONING', Version::LOWEST),
];
