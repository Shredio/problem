<?php declare(strict_types = 1);

namespace Tests\Unit;

use Shredio\Problem\Detail\ValidationProblemDetail;
use Shredio\Problem\Problem;
use Shredio\Problem\ProblemDetails;
use Shredio\Problem\Violation\FieldViolation;
use Tests\TestCase;

final class ProblemTest extends TestCase
{

	public function testEmptyProblem(): void
	{
		$problem = new Problem(
			400,
			'The request was invalid',
		);

		$this->assertSame([
			'code' => 400,
			'message' => 'The request was invalid',
			'fatal' => true,
			'details' => [],
		], $problem->toArray());
	}

	public function testDetails(): void
	{
		$problem = new Problem(
			400,
			'The request was invalid',
			new ProblemDetails([
				new ValidationProblemDetail([
					new FieldViolation('name', ['Name is required']),
				]),
			]),
		);

		$this->assertSame([
			'code' => 400,
			'message' => 'The request was invalid',
			'fatal' => true,
			'details' => [[
				'@type' => ValidationProblemDetail::Type,
				'severity' => 'error',
				'violations' => [
					[
						'field' => 'name',
						'messages' => ['Name is required'],
					],
				],
			]],
		], $problem->toArray());
	}

}
