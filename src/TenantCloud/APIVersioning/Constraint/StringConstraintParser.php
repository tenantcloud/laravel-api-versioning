<?php

namespace TenantCloud\APIVersioning\Constraint;

interface StringConstraintParser
{
	public function parse(string $constraint): Constraint;
}
