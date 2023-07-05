<?php

namespace TenantCloud\APIVersioning\Version;

interface VersionParser
{
	public function parse(?string $version): Version;
}
