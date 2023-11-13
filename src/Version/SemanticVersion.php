<?php

namespace TenantCloud\APIVersioning\Version;

class SemanticVersion implements Version
{
	public function __construct(private readonly string $version) {}

	public function __toString(): string
	{
		return $this->version;
	}
}
