<?php declare(strict_types = 1);

namespace Shredio\Problem\Violation;

final readonly class FieldViolation
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

}
