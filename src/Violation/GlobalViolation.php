<?php declare(strict_types = 1);

namespace Shredio\Problem\Violation;

use Shredio\Problem\Helper\ProblemHelper;
use Shredio\Problem\Message\VerboseMessage;
use Stringable;

final readonly class GlobalViolation implements Violation
{

	/**
	 * @param list<string|Stringable|VerboseMessage> $messages
	 */
	public function __construct(
		public array $messages,
	)
	{
	}

	/**
	 * @return mixed[]
	 */
	public function toArray(bool $sanitize = true, ?callable $stringify = null): array
	{
		return [
			'messages' => ProblemHelper::stringifyMessages($this->messages, $sanitize, $stringify),
		];
	}

}
