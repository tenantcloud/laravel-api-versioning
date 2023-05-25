<?php

namespace TenantCloud\APIVersioning\Constraint;

enum Operator: string
{
	case LESS = '<';
	case LT = 'lt';
	case LESS_OR_EQUAL = '<=';
	case LE = 'le';
	case GREATER = '>';
	case GT = 'gt';
	case GREATER_OR_EQUAL = '>=';
	case GE = 'ge';
	case EQUAL = '==';
	case EQUAL_TO = '=';
	case EQ = 'eq';
	case NOT_EQUAL = '!=';
	case NOT_EQUAL_TO = '<>';
	case NE = 'ne';
}
