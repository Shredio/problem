<?php declare(strict_types = 1);

namespace Shredio\Problem;

use JsonSerializable;

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
	 * @return mixed[]
	 */
	public function toArray(bool $sanitize = true): array
	{
		if (!$this->details) {
			$details = [];
		} else {
			$details = $this->details->toArray($sanitize);
		}

		return [
			'code' => $this->code,
			'message' => $this->message,
			'fatal' => $this->fatal,
			'details' => $details,
		];
	}

	/**
	 * @return mixed[]
	 */
	public function jsonSerialize(): array
	{
		return $this->toArray();
	}

}
