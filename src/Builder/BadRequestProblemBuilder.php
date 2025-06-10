<?php declare(strict_types = 1);

namespace Shredio\Problem\Builder;

use Shredio\Problem\Detail\BadRequestProblemDetail;
use Shredio\Problem\Problem;
use Shredio\Problem\ProblemDetails;
use Shredio\Problem\Violation\FieldViolation;
use Shredio\Problem\Violation\GlobalViolation;
use Shredio\Problem\Violation\Violation;
use Stringable;

final class BadRequestProblemBuilder
{

	/** @var list<Violation> */
	private array $violations = [];

	private int $code = 400;

	private string $message = 'Invalid request.';

	/**
	 * Adds a list of violations to the builder.
	 *
	 * @param iterable<Violation> $violations
	 */
	public function addList(iterable $violations): self
	{
		foreach ($violations as $violation) {
			$this->violations[] = $violation;
		}

		return $this;
	}

	/**
	 * @param list<string|Stringable> $messages
	 */
	public function addFieldViolation(
		string $field,
		array $messages,
	): self
	{
		$this->violations[] = new FieldViolation(
			field: $field,
			messages: $messages,
		);

		return $this;
	}

	/**
	 * @param list<string|Stringable> $messages
	 */
	public function addViolation(
		array $messages,
	): self
	{
		$this->violations[] = new GlobalViolation($messages);

		return $this;
	}

	public function setCode(int $code): self
	{
		$this->code = $code;

		return $this;
	}

	public function setMessage(string $message): self
	{
		$this->message = $message;

		return $this;
	}

	public function isEmpty(): bool
	{
		return !$this->violations;
	}

	public function build(): Problem
	{
		return new Problem($this->code, $this->message, $this->createDetails());
	}

	public function buildNullable(): ?Problem
	{
		return $this->violations ? $this->build() : null;
	}

	private function createDetails(): ProblemDetails
	{
		return new ProblemDetails([new BadRequestProblemDetail($this->violations)]);
	}

}
