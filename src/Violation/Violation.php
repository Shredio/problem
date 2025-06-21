<?php declare(strict_types = 1);

namespace Shredio\Problem\Violation;

use Stringable;

interface Violation
{

	/**
	 * @param (callable(Stringable): string)|null $stringify Optional callback to stringify Stringable objects in the details.
	 * @return mixed[]
	 */
	public function toArray(bool $sanitize = true, ?callable $stringify = null): array;

	public function debugString(): string;

}
