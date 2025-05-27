<?php declare(strict_types = 1);

namespace Shredio\Problem\Detail;

use Shredio\Problem\Violation\Violation;

final readonly class BadRequestProblemDetail implements ProblemDetail
{

	public const string Type = 'BadRequest';

	/**
	 * @param list<Violation> $violations
	 */
	public function __construct(
		private array $violations,
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
	public function toArray(bool $sanitize = true): array
	{
		return [
			'@type' => $this->getType(),
			'violations' => array_map(
				fn (Violation $violation): array => $violation->toArray(),
				$this->violations,
			),
		];
	}

}
