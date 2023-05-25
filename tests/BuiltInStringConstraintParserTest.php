<?php

namespace Tests;

use TenantCloud\APIVersioning\Constraint\BadConstraintException;
use TenantCloud\APIVersioning\Constraint\BuiltInStringConstraintParser;
use TenantCloud\APIVersioning\Constraint\Operator;
use TenantCloud\APIVersioning\Version\SemanticVersionParser;
use TenantCloud\APIVersioning\Version\VersionParser;
use ValueError;

/**
 * @see BuiltInStringConstraintParser
 */
class BuiltInStringConstraintParserTest extends TestCase
{
	public function testWrongFormat(): void
	{
		$this->expectException(BadConstraintException::class);
		(new BuiltInStringConstraintParser(app(VersionParser::class)))->parse('test');
	}

	public function testWrongOperator(): void
	{
		$this->expectException(ValueError::class);
		(new BuiltInStringConstraintParser(new SemanticVersionParser()))->parse('>>1.0');
	}

	public function testSuccessVersion(): void
	{
		$constrain = (new BuiltInStringConstraintParser(app(VersionParser::class)))->parse('==1.0');

		self::assertEquals('1.0', (string) $constrain->version);
		self::assertEquals(Operator::EQUAL->value, $constrain->operator->value);
	}
}
