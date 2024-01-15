<?php

namespace TenantCloud\APIVersioning\Testing;

use Illuminate\Foundation\Testing\Concerns\MakesHttpRequests as LaravelMakesHttpRequests;
use TenantCloud\APIVersioning\Version\RequestHeaderVersionParser;
use Tests\MakesVersionedHttpRequestsTest;
use Webmozart\Assert\Assert;

/**
 * @see MakesVersionedHttpRequestsTest
 */
trait MakesVersionedHttpRequests
{
	use LaravelMakesHttpRequests;

	private ?string $apiVersion = null;

	public function withApiVersion(string $apiVersion): self
	{
		$this->withHeader(RequestHeaderVersionParser::HEADER, $apiVersion);

		Assert::notNull($this->app);

		$this->app->terminating(function () {
			unset($this->defaultHeaders[RequestHeaderVersionParser::HEADER]);
		});

		return $this;
	}
}
