<?php declare(strict_types = 1);

namespace Shredio\Problem\Detail;

use Shredio\Problem\Detail\Enum\ValidationSeverity;
use Shredio\Problem\Violation\Violation;

final readonly class ValidationProblemDetail implements ProblemDetail
{

	public const string Type = 'Validation';

	/**
	 * @param list<Violation> $violations
	 */
	public function __construct(
		private array $violations,
		private ValidationSeverity $severity = ValidationSeverity::Error,
	)
	{
	}

	public function getType(): string
	{
		return self::Type;
	}

	public function isValid(): bool
	{
		return (bool) $this->violations;
	}

	public function isSensitive(): bool
	{
		return false;
	}

	/**
	 * @return mixed[]
	 */
	public function toArray(bool $sanitize = true, ?callable $stringify = null): array
	{
		return [
			'@type' => $this->getType(),
			'severity' => $this->severity->value,
			'violations' => array_map(
				fn (Violation $violation): array => $violation->toArray($stringify),
				$this->violations,
			),
		];
	}

}
