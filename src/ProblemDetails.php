<?php declare(strict_types = 1);

namespace Shredio\Problem;

use Shredio\Problem\Detail\ProblemDetail;
use Stringable;

final class ProblemDetails
{

	/** @var list<ProblemDetail> */
	private array $details = [];

	/**
	 * @param iterable<ProblemDetail> $details
	 */
	public function __construct(iterable $details = [])
	{
		foreach ($details as $detail) {
			if ($detail->isValid()) {
				$this->details[] = $detail;
			}
		}
	}

	/**
	 * @return list<ProblemDetail>
	 */
	public function getDetails(): array
	{
		return $this->details;
	}

	public function add(ProblemDetail $detail): ProblemDetails
	{
		if ($detail->isValid()) {
			$this->details[] = $detail;
		}

		return $this;
	}

	public function merge(ProblemDetails $problemDetails): ProblemDetails
	{
		$self = new self();
		$self->details = array_merge($this->details, $problemDetails->details);

		return $self;
	}

	/**
	 * @param bool $sanitize Indicates whether the result should be sanitized before being returned.
	 * @param (callable(Stringable): string)|null $stringify Optional callback to stringify Stringable objects in the details.
	 * @return mixed[]
	 */
	public function toArray(bool $sanitize = true, ?callable $stringify = null): array
	{
		$details = [];

		foreach ($this->details as $detail) {
			if ($sanitize && $detail->isSensitive()) {
				continue;
			}

			$details[] = $detail->toArray($sanitize, $stringify);
		}

		return $details;
	}

}
