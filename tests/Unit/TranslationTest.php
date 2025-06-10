<?php declare(strict_types = 1);

namespace Tests\Unit;

use Shredio\Problem\Builder\ValidationProblemBuilder;
use Stringable;
use Symfony\Component\Translation\TranslatableMessage;
use Tests\TestCase;

final class TranslationTest extends TestCase
{

	public function testTranslatableMessage(): void
	{
		$builder = new ValidationProblemBuilder();
		$builder->addFieldViolation('email', [new TranslatableMessage('validation.email.invalid')]);
		$problem = $builder->build();

		$this->assertSame([
			'code' => 422,
			'message' => 'Validation failed.',
			'fatal' => true,
			'details' => [
				[
					'@type' => 'Validation',
					'severity' => 'error',
					'violations' => [
						[
							'field' => 'email',
							'messages' => [
								'validation.email.invalid',
							],
						],
					],
				],
			],
		], $problem->toArray());
	}

	public function testStringify(): void
	{
		$builder = new ValidationProblemBuilder();
		$builder->addFieldViolation('email', [new TranslatableMessage('validation.email.invalid')]);
		$problem = $builder->build();

		$stringify = static function (Stringable $message): string {
			return 'Translated: ' . $message;
		};

		$this->assertSame([
			'code' => 422,
			'message' => 'Validation failed.',
			'fatal' => true,
			'details' => [
				[
					'@type' => 'Validation',
					'severity' => 'error',
					'violations' => [
						[
							'field' => 'email',
							'messages' => [
								'Translated: validation.email.invalid',
							],
						],
					],
				],
			],
		], $problem->toArray(stringify: $stringify));
	}

}
