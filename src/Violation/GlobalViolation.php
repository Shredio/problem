<?php declare(strict_types = 1);

namespace Shredio\Problem\Violation;

final readonly class GlobalViolation implements Violation
{

	/**
	 * @param list<string> $messages
	 */
	public function __construct(
		public array $messages,
	)
	{
	}

	/**
	 * @return mixed[]
	 */
	public function toArray(): array
	{
		return [
			'messages' => $this->messages,
		];
	}

	public function __toString(): string
	{
		return implode(' ', $this->messages);
	}

}
