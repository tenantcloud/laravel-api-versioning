<?php

namespace TenantCloud\APIVersioning\Version;

class LatestVersion implements Version
{
	public function __toString(): string
	{
		return 'latest';
	}
}
