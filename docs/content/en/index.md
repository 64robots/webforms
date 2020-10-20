---
title: 'Webforms'
description: 'Overview of the package'
position: 1
category: 'Introduction'
---

Package to create questions and answers for a multistep-form made with :heart: from [64 Robots](https://64robots.com)

## Installation

You can install the package via composer:

```bash
composer require 64robots/webforms
```

You can publish and run the migrations with:

```bash
php artisan vendor:publish --provider="R64\Webforms\WebformsServiceProvider" --tag="migrations"
php artisan migrate
```

You can publish the config file with:
```bash
php artisan vendor:publish --provider="R64\Webforms\WebformsServiceProvider" --tag="config"
```

This is the contents of the published config file:

```php
return [
    'date_format' => 'Y-m-d',
    'year_month_format' => 'Y-m',
    'fields_to_be_confirmed' => [
        QuestionTypes::EMAIL_TYPE,
        QuestionTypes::PHONE_TYPE,
    ],
    'user_model' => 'App\User',
];
```

## Usage

1. Add that to your routes file:

```php
Route::webforms('webforms');
```

2. Add `HasWebForms` trait in your user entity.

3. Create Seeders for FormSection, FormSteps and Question.

More details in the [Usage Docs](docs/usage.md).

## Testing

``` bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security

If you discover any security related issues, please email mmanzano@gmail.com instead of using the issue tracker.

## Credits

- [64 Robots](https://github.com/64Robots)
- [All Contributors](https://github.com/64Robots/webforms/contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

## Acknowledgments

Thanks to [Spatie](https://spatie.be/) for the [Package Skeleton Laravel](https://github.com/spatie/package-skeleton-laravel).
