<?php declare(strict_types = 1);

namespace Shredio\Problem\Helper;

use Shredio\Problem\Message\VerboseMessage;
use Stringable;

final readonly class ProblemHelper
{

	/**
	 * @param list<string|Stringable|VerboseMessage> $messages
	 * @param (callable(Stringable): string)|null $stringify
	 * @return list<string>
	 */
	public static function stringifyMessages(array $messages, bool $sanitize, ?callable $stringify): array
	{
		return array_map(
			static function (string|Stringable|VerboseMessage $message) use ($stringify, $sanitize): string {
				if ($message instanceof VerboseMessage) {
					$message = $sanitize ? $message->message : $message->debugMessage;
				}

				if ($message instanceof Stringable) {
					return $stringify ? $stringify($message) : (string) $message;
				}

				return $message;
			},
			$messages,
		);
	}

}
