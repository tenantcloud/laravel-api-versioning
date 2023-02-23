<?php

namespace TenantCloud\APIVersioning\Version;

use Tests\VersionHelperTest;

/**
 * @see VersionHelperTest
 */
class VersionHelper
{
	public const REGEX_VERSION_RULE = '([<>=]*)([\d.]*)';

	public function compareVersions(string $requestVersion, array $availableVersions): bool
	{
		foreach ($availableVersions as $rawVersionRule) {
			$versionRule = $this->parseRawVersion($rawVersionRule);

			if (version_compare($requestVersion, $versionRule['version'], $versionRule['operator'])) {
				return true;
			}
		}

		return false;
	}

	/**
	 * If we have version rule like '<=3.0' and '<=2.0' and got version from client '1.0'
	 * we should suggest only one controller function for this request.
	 * So we suggest which controller should run.
	 * If we got exactly version (for ex. rule is ==2.0) and we got version from client 2.0
	 * we suggest this controller as well
	 *
	 * Examples :
	 * suggestedVersionRule('3.0', ['==1.0', '<=2.0', '<=3.0'])) => '<=3.0' (got max available version rule)
	 * suggestedVersionRule('2.0', ['==1.0', '==2.0', '<=3.0'])) => '==2.0' (got exactly version rule)
	 * suggestedVersionRule('3.0', ['==1.0', '==2.0', '<3.0'])) => '' (no rule find. Got empty string)
	 */
	public function suggestedVersionRule(string $requestVersion, array $availableVersionRules): string
	{
		$result = [];

		foreach ($availableVersionRules as $rawVersionRule) {
			$versionRule = $this->parseRawVersion($rawVersionRule);

			if (
				$requestVersion === $versionRule['version'] &&
				$versionRule['operator'] === '=='
			) {
				return $rawVersionRule;
			}

			if (version_compare($requestVersion, $versionRule['version'], $versionRule['operator'])) {
				$result[$versionRule['version']] = $rawVersionRule;
			}
		}

		return count($result) ? $result[max(array_keys($result))] : '';
	}

	public function parseRawVersion(string $rawVersionRule): array
	{
		preg_match('/' . self::REGEX_VERSION_RULE . '/', $rawVersionRule, $result);

		if ($rawVersionRule !== $result[0]) {
			throw new BadVersionException("Version {$rawVersionRule} didn't match " . self::REGEX_VERSION_RULE);
		}

		return [
			'operator' => $result[1],
			'version'  => $result[2],
		];
	}
}
