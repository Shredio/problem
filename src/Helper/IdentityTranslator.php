<?php declare(strict_types = 1);

namespace Shredio\Problem\Helper;

use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Contracts\Translation\TranslatorTrait;

/**
 * Translates nothing: returns the message itself with its parameters substituted.
 *
 * @internal
 */
final class IdentityTranslator implements TranslatorInterface
{

	use TranslatorTrait;

}
