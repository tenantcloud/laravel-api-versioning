<?php

namespace TenantCloud\APIVersioning\Constraint;

use TenantCloud\APIVersioning\Version\Version;

class Constraint
{
	public function __construct(
		public readonly Operator $operator,
		public readonly Version $version,
	) {
	}

	public function __toString(): string
	{
		return $this->operator->value . $this->version;
	}
}
