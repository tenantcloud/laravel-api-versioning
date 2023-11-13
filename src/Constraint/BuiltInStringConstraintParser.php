<?php

namespace TenantCloud\APIVersioning\Constraint;

use TenantCloud\APIVersioning\Version\VersionParser;
use Tests\BuiltInStringConstraintParserTest;

/**
 * @see BuiltInStringConstraintParserTest
 */
class BuiltInStringConstraintParser implements StringConstraintParser
{
	public const REGEX_VERSION_RULE = '([<>=]*)([\d.]*)';

	public function __construct(
		private readonly VersionParser $versionParser,
	) {}

	public function parse(string $constraint): Constraint
	{
		preg_match('/' . self::REGEX_VERSION_RULE . '/', $constraint, $result);

		if ($constraint !== $result[0]) {
			throw new BadConstraintException("Constraint rule {$constraint} didn't match " . self::REGEX_VERSION_RULE);
		}

		return new Constraint(
			operator: Operator::from($result[1]),
			version: $this->versionParser->parse($result[2]),
		);
	}
}
