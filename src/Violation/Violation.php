<?php declare(strict_types = 1);

namespace Shredio\Problem\Violation;

use Stringable;
use Symfony\Contracts\Translation\TranslatableInterface;

interface Violation
{

	/**
	 * @param (callable(Stringable|TranslatableInterface): string)|null $stringify Optional callback to stringify Stringable and translatable messages in the details.
	 * @return mixed[]
	 */
	public function toArray(bool $sanitize = true, ?callable $stringify = null): array;

	public function debugString(string $separator = "\n"): string;

}
