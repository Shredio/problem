<?php declare(strict_types = 1);

namespace Shredio\Problem\Detail\Enum;

enum ValidationSeverity: string
{

	case Error = 'error';
	case Warning = 'warning';

}
