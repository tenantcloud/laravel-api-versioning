<?php

namespace TenantCloud\APIVersioning\Constraint;

enum Operator: string
{
	case LESS = '<';
	case LESS_OR_EQUAL = '<=';
	case GREATER = '>';
	case GREATER_OR_EQUAL = '>=';
	case EQUAL = '==';
	case EQUAL_TO = '=';
	case NOT_EQUAL = '!=';
}
