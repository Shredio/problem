<?php declare(strict_types = 1);

namespace Shredio\Problem\Exception;

use Shredio\Problem\Violation\Violation;
use Throwable;

interface ViolationAwareException extends Throwable
{

	/**
	 * @return list<Violation>
	 */
	public function getViolations(?string $fieldName = null): array;

}
