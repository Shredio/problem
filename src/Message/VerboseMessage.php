<?php declare(strict_types = 1);

namespace Shredio\Problem\Message;

use Stringable;

final readonly class VerboseMessage implements Stringable
{

	public function __construct(
		public string|Stringable $message,
		public string|Stringable $debugMessage,
	)
	{
	}

	public function __toString(): string
	{
		return (string) $this->message;
	}

}
