<?php declare(strict_types = 1);

namespace Tests\Unit;

use Shredio\Problem\Detail\ValidationProblemDetail;
use Shredio\Problem\Violation\FieldViolation;
use Tests\TestCase;

final class DetailsTest extends TestCase
{

	public function testFieldViolations(): void
	{
		$detail = new ValidationProblemDetail([
			new FieldViolation('name', ['Name is required']),
			new FieldViolation('email', ['Email is invalid']),
		]);

		$this->assertSame([
			'@type' => ValidationProblemDetail::Type,
			'severity' => 'error',
			'violations' => [
				[
					'field' => 'name',
					'messages' => ['Name is required'],
				],
				[
					'field' => 'email',
					'messages' => ['Email is invalid'],
				],
			],
		], $detail->toArray());
	}

}
