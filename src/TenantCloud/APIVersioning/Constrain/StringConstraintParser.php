<?php

namespace TenantCloud\APIVersioning\Constrain;

interface StringConstraintParser
{
	public function parse(string $constraint): Constraint;
}
