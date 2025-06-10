<?php declare(strict_types = 1);

namespace Shredio\Problem\Violation;

use Stringable;

interface Violation extends Stringable
{

	/**
	 * @param (callable(Stringable): string)|null $stringify Optional callback to stringify Stringable objects in the details.
	 * @return mixed[]
	 */
	public function toArray(?callable $stringify = null): array;

}
