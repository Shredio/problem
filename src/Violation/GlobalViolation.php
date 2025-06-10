<?php declare(strict_types = 1);

namespace Shredio\Problem\Violation;

use Shredio\Problem\Helper\ProblemHelper;
use Stringable;

final readonly class GlobalViolation implements Violation
{

	/**
	 * @param list<string|Stringable> $messages
	 */
	public function __construct(
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
			'messages' => ProblemHelper::stringifyStringables($this->messages, $stringify),
		];
	}

	public function __toString(): string
	{
		return implode(' ', $this->messages);
	}

}
