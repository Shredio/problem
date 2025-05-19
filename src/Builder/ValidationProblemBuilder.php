<?php declare(strict_types = 1);

namespace Shredio\Problem\Builder;

use Shredio\Problem\Detail\Enum\ValidationSeverity;
use Shredio\Problem\Detail\ValidationProblemDetail;
use Shredio\Problem\Problem;
use Shredio\Problem\ProblemDetails;
use Shredio\Problem\Violation\FieldViolation;

final class ValidationProblemBuilder
{

	/** @var array<value-of<ValidationSeverity>, list<FieldViolation>> */
	private array $violations = [];

	private ?bool $fatal = null;

	private int $code = 422;

	private string $message = 'Validation failed.';

	/**
	 * @param list<string> $messages
	 */
	public function addViolation(
		string $field,
		array $messages,
		ValidationSeverity $severity = ValidationSeverity::Error,
	): self
	{
		$this->violations[$severity->value][] = new FieldViolation(
			field: $field,
			messages: $messages,
		);

		return $this;
	}

	public function isEmpty(): bool
	{
		return !$this->violations;
	}

	public function setFatal(bool $fatal): self
	{
		$this->fatal = $fatal;

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

	public function build(): Problem
	{
		return new Problem($this->code, $this->message, $this->createDetails(), $this->isFatal());
	}

	public function buildNullable(): ?Problem
	{
		return $this->violations ? $this->build() : null;
	}

	private function isFatal(): bool
	{
		if ($this->fatal !== null) {
			return $this->fatal;
		}

		return isset($this->violations[ValidationSeverity::Error->value]);
	}

	private function createDetails(): ProblemDetails
	{
		$details = new ProblemDetails();

		foreach ($this->violations as $severity => $violations) {
			$details->add(new ValidationProblemDetail($violations, ValidationSeverity::from($severity)));
		}

		return $details;
	}

}
