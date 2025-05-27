<?php declare(strict_types = 1);

namespace Shredio\Problem\Violation;

interface Violation
{

	/**
	 * @return mixed[]
	 */
	public function toArray(): array;

}
