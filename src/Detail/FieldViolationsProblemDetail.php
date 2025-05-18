<?php declare(strict_types = 1);

namespace Shredio\Problem\Detail;

use Shredio\Problem\Detail\Enum\FieldViolationsSeverity;
use Shredio\Problem\Violation\FieldViolation;

final readonly class FieldViolationsProblemDetail implements ProblemDetail
{

	/**
	 * @param list<FieldViolation> $fieldViolations
	 */
	public function __construct(
		private array $fieldViolations,
		private FieldViolationsSeverity $severity = FieldViolationsSeverity::Error,
	)
	{
	}

	public function getType(): string
	{
		return 'FieldViolations';
	}

	public function isValid(): bool
	{
		return (bool) $this->fieldViolations;
	}

	public function isSensitive(): bool
	{
		return false;
	}

	/**
	 * @return mixed[]
	 */
	public function toArray(bool $sanitize = true): array
	{
		return [
			'@type' => $this->getType(),
			'severity' => $this->severity->value,
			'fieldViolations' => array_map(
				fn (FieldViolation $violation): array => $violation->toArray(),
				$this->fieldViolations,
			),
		];
	}

}
