<?php declare(strict_types = 1);

namespace Shredio\Problem\Builder;

use Shredio\Problem\Detail\BadRequestProblemDetail;
use Shredio\Problem\Problem;
use Shredio\Problem\ProblemDetails;
use Shredio\Problem\Violation\FieldViolation;

final class BadRequestProblemBuilder
{

	/** @var list<FieldViolation> */
	private array $violations = [];

	private int $code = 400;

	private string $message = 'Invalid request.';

	/**
	 * @param list<string> $messages
	 */
	public function addViolation(
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
