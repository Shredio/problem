<?php declare(strict_types = 1);

namespace Shredio\Problem\Detail;

use Stringable;
use Symfony\Contracts\Translation\TranslatableInterface;

interface ProblemDetail
{

	public function getType(): string;

	public function isValid(): bool;

	/**
	 * Indicates whether the entire object should be skipped in the production environment.
	 */
	public function isSensitive(): bool;

	/**
	 * @param bool $sanitize Indicates whether the result should be sanitized before being returned.
	 * @param (callable(Stringable|TranslatableInterface): string)|null $stringify Optional callback to stringify Stringable and translatable messages in the details.
	 * @return mixed[]
	 */
	public function toArray(bool $sanitize = true, ?callable $stringify = null): array;

}
