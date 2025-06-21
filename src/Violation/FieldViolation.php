<?php declare(strict_types = 1);

namespace Shredio\Problem\Violation;

use Shredio\Problem\Helper\ProblemHelper;
use Shredio\Problem\Message\VerboseMessage;
use Stringable;

final readonly class FieldViolation implements Violation
{

	/**
	 * @param list<string|Stringable|VerboseMessage> $messages
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
	public function toArray(bool $sanitize = true, ?callable $stringify = null): array
	{
		return [
			'field' => $this->field,
			'messages' => ProblemHelper::stringifyMessages($this->messages, $sanitize, $stringify),
		];
	}

	public function debugString(): string
	{
		$str = '';
		$messages = ProblemHelper::stringifyMessages($this->messages, sanitize: false);

		foreach ($messages as $message) {
			$str .= sprintf(
				'Field "%s": %s%s',
				$this->field,
				$message,
				PHP_EOL
			);
		}

		return $str;
	}

}
