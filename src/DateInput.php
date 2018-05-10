<?php

declare(strict_types = 1);

namespace TomasKarlik\DateInput;

use DateTime;
use InvalidArgumentException;
use Nette\Forms\Controls\BaseControl;
use Nette\Forms\Form;
use Nette\Forms\IControl;


final class DateInput extends BaseControl
{

	/**
	 * @var DateTime|NULL
	 */
	private $date = NULL;

	/**
	 * @var string $format
	 */
	private $format;


	public function __construct(
		?string $label = NULL,
		string $format = 'j.n.Y',
		int $maxlength = 10,
		string $type = 'text'
	) {
		parent::__construct($label);
		$this->format = $format;

		$this->setOption('maxlength', $maxlength);
		$this->setOption('type', $type);
		$this->control->data('dateinput-format', $format);
	}


	/**
	 * {@inheritdoc}
	 */
	public function loadHttpData(): void
	{
		$this->setValue($this->getHttpData(Form::DATA_LINE));
	}


	/**
	 * {@inheritdoc}
	 */
	public function setValue($value = NULL): DateInput
	{
		if ($value instanceof DateTime || $value === NULL) {
			$this->date = $value;
			$this->value = $value ? $value->format($this->format) : NULL;

		} elseif (is_string($value)) {
			if (($date = DateTime::createFromFormat('!' . $this->format, $value)) !== FALSE) {
				$this->date = $date;
				$this->value = $date->format($this->format);

			} else {
				$this->date = NULL;
				$this->value = (string) $value;
			}

		} else {
			throw new InvalidArgumentException(sprintf(
				'Value must be string, DateTime or NULL, %s given in field "%s".',
				gettype($value),
				$this->name
			));
		}

		return $this;
	}


	/**
	 * {@inheritdoc}
	 */	
	public function getValue(): ?DateTime
	{
		return $this->date !== NULL ? $this->date : NULL;
	}

	
	public function getRawValue(): ?string
	{
		return $this->value;
	}


	/**
	 * {@inheritdoc}
	 */
	public function getControl()
	{
		return parent::getControl()->addAttributes([
			'value' => (string) $this->value
		]);
	}


	/**
	 * {@inheritdoc}
	 */
	public function isFilled(): bool
	{
		return ! empty($this->value);
	}


	/**
	 * {@inheritdoc}
	 */
	public function addRule($operation, $message = NULL, $arg = NULL)
	{
		if ($operation === Form::VALID) {
			$operation = DateInput::class . '::isValid';
		} 

		return parent::addRule($operation, $message, $arg);
	}


	public static function isValid(IControl $control): bool
	{
		if ( ! $control instanceof self) {
			throw new InvalidArgumentException(sprintf('Can\'t validate control "%s".', get_class($control)));
		}

		return empty($control->value) || $control->getValue() !== NULL;
	}

}
