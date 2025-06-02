<?php declare(strict_types = 1);

namespace Shredio\Problem\Violation;

final readonly class FieldViolation implements Violation
{

	/**
	 * @param list<string> $messages
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
	public function toArray(): array
	{
		return [
			'field' => $this->field,
			'messages' => $this->messages,
		];
	}

	public function __toString(): string
	{
		return sprintf('%s: %s', $this->field, implode(' ', $this->messages));
	}

}
