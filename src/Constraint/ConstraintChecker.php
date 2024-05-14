<?php

namespace TenantCloud\APIVersioning\Constraint;

use TenantCloud\APIVersioning\Version\Version;

interface ConstraintChecker
{
	/**
	 * @param list<string> $constraints
	 */
	public function matches(Version $version, array $constraints): ?Constraint;

	/**
	 * @param list<string> $constraints
	 */
	public function compareVersions(Version $version, array $constraints): bool;
}
