<?php declare(strict_types = 1);

namespace Shredio\Problem\Detail;

interface ProblemDetail
{

	public function getType(): string;

	public function isValid(): bool;

	/**
	 * Indicates whether the entire object should be skipped in the production environment.
	 */
	public function isSensitive(): bool;

	/**
	 * @param bool $sanitize Indicates whether the result should be sanitized before being returned.
	 * @return mixed[]
	 */
	public function toArray(bool $sanitize = true): array;

}
