<?php declare(strict_types = 1);

namespace Tests\Unit;

use Shredio\Problem\Detail\ProblemDetail;
use Shredio\Problem\ProblemDetails;
use Tests\TestCase;

final class TestProblemDetails extends TestCase
{

	public function testSensitiveData(): void
	{
		$details = new ProblemDetails([
			new class implements ProblemDetail {

				public function getType(): string
				{
					return 'SensitiveData';
				}

				public function isValid(): bool
				{
					return true;
				}

				public function isSensitive(): bool
				{
					return true;
				}

				public function toArray(bool $sanitize = true): array
				{
					return [
						'@type' => 'SensitiveData',
						'data' => 'secret',
					];
				}
			}
		]);

		$this->assertSame([], $details->toArray());
	}

	public function testSanitize(): void
	{
		$details = new ProblemDetails([
			new class implements ProblemDetail {

				public function getType(): string
				{
					return 'SensitiveData';
				}

				public function isValid(): bool
				{
					return true;
				}

				public function isSensitive(): bool
				{
					return false;
				}

				public function toArray(bool $sanitize = true): array
				{
					return [
						'@type' => 'SensitiveData',
						'data' => $sanitize ? null : 'secret',
					];
				}
			}
		]);

		$this->assertSame([[
			'@type' => 'SensitiveData',
			'data' => null,
		]], $details->toArray());
	}

	public function testValid(): void
	{
		$details = new ProblemDetails([
			new class implements ProblemDetail {

				public function getType(): string
				{
					return 'SensitiveData';
				}

				public function isValid(): bool
				{
					return false;
				}

				public function isSensitive(): bool
				{
					return false;
				}

				public function toArray(bool $sanitize = true): array
				{
					return [
						'@type' => 'SensitiveData',
						'data' => $sanitize ? null : 'secret',
					];
				}
			}
		]);

		$this->assertCount(0, $details->getDetails());
		$this->assertSame([], $details->toArray());
	}

}
