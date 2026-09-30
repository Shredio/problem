<?php declare(strict_types = 1);

namespace Tests\Unit;

use Locale;
use Shredio\Problem\Builder\ValidationProblemBuilder;
use Shredio\Problem\Helper\ProblemHelper;
use Shredio\Problem\Message\VerboseMessage;
use Shredio\Problem\Violation\FieldViolation;
use Shredio\Problem\Violation\GlobalViolation;
use Stringable;
use Symfony\Component\Translation\TranslatableMessage;
use Symfony\Contracts\Translation\TranslatableInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
use Tests\TestCase;

final class TranslationTest extends TestCase
{

	public function testTranslatableMessage(): void
	{
		$builder = new ValidationProblemBuilder();
		$builder->addFieldViolation(['email'], [new TranslatableMessage('validation.email.invalid')]);
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
							'field' => ['email'],
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
		$builder->addFieldViolation(['email'], [new TranslatableMessage('validation.email.invalid')]);
		$problem = $builder->build();

		$stringify = static function (Stringable|TranslatableInterface $message): string {
			return 'Translated: ' . ProblemHelper::stringifyMessage($message);
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
							'field' => ['email'],
							'messages' => [
								'Translated: validation.email.invalid',
							],
						],
					],
				],
			],
		], $problem->toArray(stringify: $stringify));
	}


	public function testTranslatableMessageParametersAreSubstitutedWithoutStringify(): void
	{
		$violation = new FieldViolation(['code'], [new TranslatableMessage('Coupon {code} is not valid.', ['{code}' => 'ABC'], 'account')]);

		$this->assertSame(['field' => ['code'], 'messages' => ['Coupon ABC is not valid.']], $violation->toArray());
		$this->assertSame('Field "code": Coupon ABC is not valid.', $violation->debugString());
	}

	public function testAnyTranslatableIsRenderedWithoutStringify(): void
	{
		$violation = new GlobalViolation([$this->createTranslatable('Upload failed.')]);

		$this->assertSame(['messages' => ['Upload failed.']], $violation->toArray());
		$this->assertSame('Upload failed.', $violation->debugString());
	}

	public function testStringifyReceivesAnyTranslatable(): void
	{
		$translatable = $this->createTranslatable('Upload failed.');
		$violation = new GlobalViolation(['plain', $translatable]);

		$stringify = static function (Stringable|TranslatableInterface $message) use ($translatable): string {
			return $message === $translatable ? 'Nahrání selhalo.' : 'unexpected';
		};

		$this->assertSame(['messages' => ['plain', 'Nahrání selhalo.']], $violation->toArray(stringify: $stringify));
	}

	public function testVerboseMessageWithTranslatableMessages(): void
	{
		$message = new VerboseMessage(
			new TranslatableMessage('Account {id} not found.', ['{id}' => '12'], 'account'),
			new TranslatableMessage('Account {id} not found in database {database}.', ['{id}' => '12', '{database}' => 'root'], 'account'),
		);
		$violation = new GlobalViolation([$message]);

		$this->assertSame('Account 12 not found.', (string) $message);
		$this->assertSame(['messages' => ['Account 12 not found.']], $violation->toArray());
		$this->assertSame(['messages' => ['Account 12 not found in database root.']], $violation->toArray(sanitize: false));
	}

	public function testPluralMessageIsRenderedByEnglishRulesWhateverTheDefaultLocale(): void
	{
		$violation = new GlobalViolation([
			new TranslatableMessage(
				'It should have {{ limit }} character or less.|It should have {{ limit }} characters or less.',
				['{{ limit }}' => 30, '%count%' => 30],
				'validators',
			),
		]);
		$defaultLocale = Locale::getDefault();
		Locale::setDefault('cs');

		try {
			$this->assertSame(['messages' => ['It should have 30 characters or less.']], $violation->toArray());
		} finally {
			Locale::setDefault($defaultLocale);
		}
	}

	private function createTranslatable(string $message): TranslatableInterface
	{
		return new readonly class ($message) implements TranslatableInterface {

			public function __construct(
				private string $message,
			)
			{
			}

			public function trans(TranslatorInterface $translator, ?string $locale = null): string
			{
				return $translator->trans($this->message, locale: $locale);
			}

		};
	}

}
