<?php

namespace TenantCloud\APIVersioning\Constraint;

use TenantCloud\APIVersioning\Version\LatestVersion;
use TenantCloud\APIVersioning\Version\SemanticVersion;
use TenantCloud\APIVersioning\Version\Version;
use Tests\SemanticConstraintCheckerTest;

/**
 * @see SemanticConstraintCheckerTest
 */
class SemanticConstraintChecker implements ConstraintChecker
{
	public const LATEST_VERSION_PRESENTATION = '1000000.0';

	public function __construct(private readonly StringConstraintParser $constraintParser)
	{
	}

	/**
	 * If we have version rule like '<=3.0' and '<=2.0' and got version from client '1.0'
	 * we should suggest only one controller function for this request.
	 * So we suggest which controller should run.
	 * If we got exactly version (for ex. rule is ==2.0) and we got version from client 2.0
	 * we suggest this controller as well
	 *
	 * Examples :
	 * matches('3.0', ['==1.0', '<=2.0', '<=3.0'])) => '<=3.0' (got max available version rule)
	 * matches('2.0', ['==1.0', '==2.0', '<=3.0'])) => '==2.0' (got exactly version rule)
	 * matches('3.0', ['==1.0', '==2.0', '<3.0'])) => null (no rule find. Got empty string)
	 */
	public function matches(Version $version, array $constraints): ?Constraint
	{
		$result = [];

		// If latest version - create the highest semantic version object.
		if ($version instanceof LatestVersion) {
			$version = new SemanticVersion(self::LATEST_VERSION_PRESENTATION);
		}

		foreach ($constraints as $rawVersionRule) {
			$versionRule = $this->constraintParser->parse($rawVersionRule);

			if (
				(string) $version === (string) $versionRule->version &&
				in_array($versionRule->operator, [Operator::EQUAL->value, Operator::EQUAL_TO->value], true)
			) {
				return $versionRule;
			}

			if (version_compare((string) $version, (string) $versionRule->version, $versionRule->operator->value)) {
				$result[(string) $versionRule->version] = $versionRule;
			}
		}

		return count($result) ? $result[max(array_keys($result))] : null;
	}

	public function compareVersions(Version $version, array $constraints): bool
	{
		// If latest version - create the highest semantic version object.
		if ($version instanceof LatestVersion) {
			$version = new SemanticVersion(self::LATEST_VERSION_PRESENTATION);
		}

		foreach ($constraints as $rawVersionRule) {
			$constraint = $this->constraintParser->parse($rawVersionRule);

			if (version_compare($version, (string) $constraint->version, $constraint->operator->value)) {
				return true;
			}
		}

		return false;
	}
}
