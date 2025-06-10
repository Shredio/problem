<?php declare(strict_types = 1);

namespace Tests\Unit;

use Shredio\Problem\Message\VerboseMessage;
use Shredio\Problem\Violation\FieldViolation;
use Tests\TestCase;

final class ViolationTest extends TestCase
{

	public function testVerboseMessage(): void
	{
		$violation = new FieldViolation('field', [new VerboseMessage('User not found', 'User with ID 123 not found')]);

		$this->assertSame([
			'field' => 'field',
			'messages' => [
				'User not found',
			],
		], $violation->toArray());
		$this->assertSame([
			'field' => 'field',
			'messages' => [
				'User with ID 123 not found',
			],
		], $violation->toArray(false));
	}

}
