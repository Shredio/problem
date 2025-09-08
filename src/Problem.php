<?php declare(strict_types = 1);

namespace Shredio\Problem;

use JsonSerializable;
use Psr\Log\LoggerInterface;
use Stringable;

final readonly class Problem implements JsonSerializable
{

	public function __construct(
		public int $code,
		public string $message,
		public ?ProblemDetails $details = null,
		public bool $fatal = true,
	)
	{
	}

	/**
	 * @param bool $sanitize Indicates whether the result should be sanitized before being returned.
	 * @param (callable(Stringable): string)|null $stringify Optional callback to stringify Stringable objects in the details.
	 * @return mixed[]
	 */
	public function toArray(bool $sanitize = true, ?callable $stringify = null): array
	{
		if (!$this->details) {
			$details = [];
		} else {
			$details = $this->details->toArray($sanitize, $stringify);
		}

		return [
			'code' => $this->code,
			'message' => $this->message,
			'fatal' => $this->fatal,
			'details' => $details,
		];
	}

	/**
	 * @param 'emergency'|'alert'|'critical'|'error'|'warning'|'notice'|'info'|'debug' $fatalSeverity
	 * @param 'emergency'|'alert'|'critical'|'error'|'warning'|'notice'|'info'|'debug' $severity
	 * @param mixed[] $context
	 */
	public function toLogger(
		LoggerInterface $logger,
		string $message,
		string $fatalSeverity = 'error',
		string $severity = 'warning',
		array $context = [],
	): void
	{
		$callback = [$logger, $this->fatal ? $fatalSeverity : $severity];
		$problem = $this->toArray(false);
		unset($problem['fatal']);
		$context['problem'] = $problem;

		$callback($message, $context);
	}

	/**
	 * @return mixed[]
	 */
	public function jsonSerialize(): array
	{
		return $this->toArray();
	}

}
