# DateInput

Install
-------
Download package
```console
composer require tomaskarlik/dateinput
````

Register extension in ```config.neon```
```neon
dateinput: TomasKarlik\DateInput\DateInputExtension
```

Usage
```php
$form->addDate('dateTo', 'Datum do:')
	->setRequired('Zadejte datum do!')
	->addRule(Form::VALID, 'Zadejte platné datum!')
	->setAttribute('class', 'datepicker');

$form->addDate('date', 'Date:', 'Y-m-d')
	->setRequired(FALSE)
	->addRule(Form::VALID)
	->setDefaultValue(new DateTime);
```
