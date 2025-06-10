<?php declare(strict_types = 1);

namespace Shredio\Problem\Helper;

use Stringable;

final readonly class ProblemHelper
{

	/**
	 * @param list<string|Stringable> $messages
	 * @param (callable(Stringable): string)|null $stringify
	 * @return list<string>
	 */
	public static function stringifyStringables(array $messages, ?callable $stringify): array
	{
		return array_map(
			static function (string|Stringable $message) use ($stringify): string {
				if ($message instanceof Stringable) {
					return $stringify ? $stringify($message) : (string) $message;
				}

				return $message;
			},
			$messages,
		);
	}

}
