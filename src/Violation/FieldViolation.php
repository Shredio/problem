<?php declare(strict_types = 1);

namespace Shredio\Problem\Violation;

use Shredio\Problem\Helper\ProblemHelper;
use Stringable;

final readonly class FieldViolation implements Violation
{

	/**
	 * @param list<string|Stringable> $messages
	 */
	public function __construct(
		public string $field,
		public array $messages,
	)
	{
	}

	/**
	 * @return mixed[]
	 */
	public function toArray(?callable $stringify = null): array
	{
		return [
			'field' => $this->field,
			'messages' => ProblemHelper::stringifyStringables($this->messages, $stringify),
		];
	}

	public function __toString(): string
	{
		return sprintf('%s: %s', $this->field, implode(' ', $this->messages));
	}

}
