<?php declare(strict_types = 1);

namespace Shredio\Problem\Helper;

use DateTimeInterface;
use Shredio\Problem\Message\VerboseMessage;
use Stringable;
use Symfony\Component\Translation\TranslatableMessage;

final readonly class ProblemHelper
{

	private const int JsonEncodeOptions = JSON_PRESERVE_ZERO_FRACTION | JSON_UNESCAPED_SLASHES | JSON_THROW_ON_ERROR;
	private const int MaxStringLength = 40;

	public static function describeValue(mixed $value): string
	{
		if ($value === null || is_bool($value) || is_int($value)) {
			return json_encode($value, self::JsonEncodeOptions);
		}

		if (is_float($value)) {
			return is_finite($value)
				? json_encode($value, self::JsonEncodeOptions)
				: (string) $value;
		}

		if (is_string($value)) {
			$printable = false;
			$truncated = false;

			if (extension_loaded('mbstring')) {
				if (preg_match('#^[^\p{C}]*+$#u', $value) === 1) {
					$printable = true;
					$truncated = mb_strlen($value, 'UTF-8') > self::MaxStringLength;
					$value = $truncated ? mb_substr($value, 0, self::MaxStringLength, 'UTF-8') : $value;
				}
			} else {
				if (preg_match('#^[\x20-\x7F]*+$#', $value) === 1) {
					$printable = true;
					$truncated = strlen($value) > self::MaxStringLength;
					$value = $truncated ? substr($value, 0, self::MaxStringLength) : $value;
				}
			}

			if ($printable) {
				return json_encode($value, self::JsonEncodeOptions) . ($truncated ? ' (truncated)' : '');
			}
		}

		if ($value instanceof DateTimeInterface) {
			if ($value->format('H:i:s') === '00:00:00') {
				return $value->format('Y-m-d (e)');
			}

			return $value->format(DateTimeInterface::RFC3339);
		}

		return get_debug_type($value);
	}

	/**
	 * @param non-empty-list<string|int|float> $items
	 */
	public static function humanImplode(array $items, string $conjunction = 'and', string $surroundWith = ''): string
	{
		$count = count($items);
		if ($count === 1) {
			return $surroundWith . $items[0] . $surroundWith;
		}

		$isConjunctionEmpty = $conjunction === '';

		$str = $surroundWith;
		$str .= implode($surroundWith . ', ' . $surroundWith, $isConjunctionEmpty ? $items : array_slice($items, 0, -1));
		$str .= $surroundWith;

		if ($isConjunctionEmpty) {
			return $str;
		}
		return $str . ' ' . $conjunction . ' ' . $surroundWith . $items[$count - 1] . $surroundWith;
	}

	/**
	 * @param list<string|Stringable|VerboseMessage> $messages
	 * @param (callable(Stringable): string)|null $stringify
	 * @return list<string>
	 */
	public static function stringifyMessages(array $messages, bool $sanitize, ?callable $stringify = null): array
	{
		return array_map(
			static function (string|Stringable|VerboseMessage $message) use ($stringify, $sanitize): string {
				if ($message instanceof VerboseMessage) {
					$message = $sanitize ? $message->message : $message->debugMessage;
				}

				if ($message instanceof Stringable) {
					if ($stringify !== null) {
						return $stringify($message);
					}
					if ($message instanceof TranslatableMessage) {
						return strtr($message->getMessage(), $message->getParameters());
					}

					return (string) $message;
				}

				return $message;
			},
			$messages,
		);
	}

}
