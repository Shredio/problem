<?php declare(strict_types = 1);

namespace Shredio\Problem\Message;

use Shredio\Problem\Helper\ProblemHelper;
use Stringable;
use Symfony\Contracts\Translation\TranslatableInterface;

final readonly class VerboseMessage implements Stringable
{

	public function __construct(
		public string|Stringable|TranslatableInterface $message,
		public string|Stringable|TranslatableInterface $debugMessage,
	)
	{
	}

	public function __toString(): string
	{
		return ProblemHelper::stringifyMessage($this->message);
	}

}
