<?php declare(strict_types = 1);

namespace Shredio\Problem\Violation;

use Stringable;

interface Violation extends Stringable
{

	/**
	 * @return mixed[]
	 */
	public function toArray(): array;

}
