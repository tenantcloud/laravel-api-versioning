<?php

namespace TenantCloud\APIVersioning\Version;

interface VersionParser
{
	public function getVersion(): string;
}
