<?php

declare(strict_types = 1);

namespace TomasKarlik\DateInput;

use Nette\DI\CompilerExtension;
use Nette\Forms\Container;
use Nette\Forms\Validator;
use Nette\PhpGenerator\ClassType;
use Nette\Utils\ObjectMixin;


final class DateInputExtension extends CompilerExtension
{

	public function afterCompile(ClassType $class): void
	{
		$initializeMethod = $class->getMethod('initialize');
		$initializeMethod->addBody(__CLASS__ . '::registerControl();');
	}


	public static function registerControl(): void
	{
		ObjectMixin::setExtensionMethod(Container::class, 'addDate', function (
			Container $container,
			string $name,
			?string $label = NULL,
			string $format = 'j.n.Y',
			int $maxlength = 10,
			string $type = 'text'
		) {
			return $container[$name] = new DateInput($label, $format, $maxlength, $type);
		});

		Validator::$messages[DateInput::class . '::isValid'] = 'Please enter a valid date.';
	}

}
