<?php declare(strict_types = 1);

namespace Tests\Unit;

use Shredio\Problem\Detail\FieldViolationsProblemDetail;
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
			'BAD_REQUEST',
		);

		$this->assertSame([
			'code' => 400,
			'message' => 'The request was invalid',
			'status' => 'BAD_REQUEST',
			'fatal' => true,
			'details' => [],
		], $problem->toArray());
	}

	public function testDetails(): void
	{
		$problem = new Problem(
			400,
			'The request was invalid',
			'BAD_REQUEST',
			new ProblemDetails([
				new FieldViolationsProblemDetail([
					new FieldViolation('name', ['Name is required']),
				]),
			]),
		);

		$this->assertSame([
			'code' => 400,
			'message' => 'The request was invalid',
			'status' => 'BAD_REQUEST',
			'fatal' => true,
			'details' => [[
				'@type' => 'FieldViolations',
				'severity' => 'error',
				'fieldViolations' => [
					[
						'field' => 'name',
						'messages' => ['Name is required'],
					],
				],
			]],
		], $problem->toArray());
	}

}
