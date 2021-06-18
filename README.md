
# Backend for 64 Robots webforms

[![Latest Version on Packagist](https://img.shields.io/packagist/v/64robots/webforms.svg?style=flat-square)](https://packagist.org/packages/64robots/webforms)
[![MIT Licensed](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)
![GitHub Tests Action Status](https://img.shields.io/github/workflow/status/64robots/webforms/run-tests?label=tests)
[![Total Downloads](https://img.shields.io/packagist/dt/64robots/webforms.svg?style=flat-square)](https://packagist.org/packages/64robots/webforms)

Package to rapidly create custom forms. This package provides you an easy way to start the backend for an SPA Form. You could create forms, form steps and questions. Your users could respond to these forms. Made by [64 Robots](https://64robots.com).

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

This is the contents of the published `webforms.php` config file:

```php
use R64\Webforms\QuestionTypes\EmailType;
use R64\Webforms\QuestionTypes\PhoneType;

return [
    'date_format' => 'Y-m-d',
    'year_month_format' => 'Y-m',
    'fields_to_be_confirmed' => [
        EmailType::TYPE,
        PhoneType::TYPE,
    ],
    'user_model' => 'App\User',
];
```

## Usage

1 - Add Routes

At the moment, the package doesn't work with anonymous users. Please, add that to your routes file under an auth middleware:

```php
Route::webforms('webforms');
```

If you want routes to create Forms, FormSteps and Questions, add that under the appropriate middleware in your routes file:

```php
Route::webformsAdmin('webforms-admin');
```

2 - Add `HasWebForms` trait in your user entity.

3 - Create Seeders for Form, FormSteps and Question.

## Example

Let's start a new form to collect info about coffee in your app:

We will add in `routes/api.php`:

```php
Route::webforms('webforms');
```

We will also add in `routes/api_admin.php`:

```php
Route::webformsAdmin('webforms-admin');
```

The next step is to add a new Form. Just create a new Seeder.

```
php artisan make:seeder CoffeeSeeder
```

Now in the Seeder file `database/seeders/CoffeeSeeder` we'll include the creation of the form, form steps and questions.

Let's start with the form creation:

```php
use R64\Webforms\Models\Form;

$coffeeForm = Form::build('Coffee Form')
    ->save();
```

Once we have the `form` we'll need to add, at least, a form step:

```php
use R64\Webforms\Models\FormStep;

$coffeeStep = FormStep::build($coffeeForm, 'Coffee')
    ->save();
```

Then we can add `questions` to this step:

```php
use R64\Webforms\Models\Question;
use R64\Webforms\QuestionTypes\OptionsType;

$whatKindOfCoffeeDoYouLikeQuestion = Question::build($coffeeStep, 'What kind of coffee do you like?')
    ->type(OptionsType::TYPE)
    ->options([
        'black' => 'Black', 
        'latte' => 'Latte',
        'capuccino' => 'Cappucino',
        'americano' => 'Americano',
        'red-eye' => 'Red Eye',
        'flat-white' => 'Flat White',
     ])
    ->save();

$whatTypeOfBeansDoYouLikeQuestion = Question::build($coffeeStep, 'What type of coffee beans do you like the most?')
    ->type(OptionsType::TYPE)
    ->options([
        'arabica' => 'Arabica',
        'robusta' => 'Robusta',
    ])
    ->save();
```

Add now a new step to collect some personal info. We'll encrypt that info in the database:

```php
use R64\Webforms\Models\FormStep;

$personalInfoStep = FormStep::build($coffeeForm, 'Personal info')
    ->isPersonalData(1)
    ->save();
```

Then add the questions:

```php
use R64\Webforms\Models\Question;
use R64\Webforms\QuestionTypes\IntegerType;

$firstNameQuestion = Question::build($personalInfoStep, 'First Name')
    ->save();

$lastNameQuestion = Question::build($personalInfoStep, 'Last Name')
    ->save();

$ageQuestion = Question::build($personalInfoStep, 'Birth Year')
    ->type(IntegerType::TYPE)
    ->save();
```

Once we have that, we can add the questions steps to users:

```php
User::all()->each->addFormSteps([$coffeeStep, $personalInfoStep]);
```

If we want to add all the formSteps to the users we can also use:

```php
User::all()->each->addFormSteps();
```

When the users ask for the forms they will get only the forms they had steps on it. We need to do an authenticated request to:

`/webforms/forms`

We'll get something like:

```json
{
    "data": [
        {
            "id": 1,
            "sort": 1,
            "slug": "coffee-form",
            "menu_title": null,
            "title": "Coffee Form",
            "description": null,
            "completed": false
        }
    ]
}
```

Let's say the form is the one with id 1. Then we can make another one to:

`/webforms/form-steps?form=1`

We'll obtain all the forms steps info:

```json
{
    "data": [
        {
            "id": 1,
            "form": {
                "id": 1,
                "sort": 1,
                "slug": "coffee-form",
                "menu_title": "",
                "title": "Coffee Form",
                "description": "",
                "completed": false
            },
            "sort": 1,
            "slug": "coffee",
            "menu_title": null,
            "title": "Coffee",
            "description": "",
            "completed": false
        },
        {
            "id": 2,
            "form": {
                "id": 1,
                "sort": 1,
                "slug": "coffee-form",
                "menu_title": null,
                "title": "Coffee Form",
                "description": null,
                "completed": false
            },
            "sort": 2,
            "slug": "personal-info",
            "menu_title": null,
            "title": "Personal info",
            "description": null,
            "completed": false
        }
    ]
}
```

For each form step we need to ask for the questions using:

`/webforms/questions?form_step=1`

```json
{
    "data": [
        {
            "id": 1,
            "form_step": {
                "id": 1,
                "sort": 1,
                "slug": "coffee",
                "menu_title": null,
                "title": "Coffee",
                "description": "",
                "completed": false
            },
            "sort": 1,
            "depends_on": null,
            "shown_when": null,
            "required": false,
            "slug": "what-kind-of-coffee-do-you-like",
            "group_by": null,
            "group_by_description": null,
            "label_position": "left",
            "help_title": null,
            "help_body": null,
            "type": "options",
            "post_input_text": null,
            "title": "What kind of coffee do you like?",
            "description": null,
            "error_message": null,
            "default_value": null,
            "min": null,
            "max": null,
            "options": [
                {
                    "label": "Black",
                    "value": "black"
                },
                {
                    "label": "Latte",
                    "value": "latte"
                },
                {
                    "label": "Cappucino",
                    "value": "capuccino"
                },
                {
                    "label": "Americano",
                    "value": "americano"
                },
                {
                    "label": "Red Eye",
                    "value": "red-eye"
                },
                {
                    "label": "Flat White",
                    "value": "flat-white"
                }
            ],
            "answer": {}
        },
        {
            "id": 2,
            "form_step": {
                "id": 1,
                "sort": 1,
                "slug": "coffee",
                "menu_title": null,
                "title": "Coffee",
                "description": "",
                "completed": false
            },
            "sort": 2,
            "depends_on": null,
            "shown_when": null,
            "required": false,
            "slug": "what-type-of-coffee-beans-do-you-like-the-most",
            "group_by": null,
            "group_by_description": null,
            "label_position": "left",
            "help_title": null,
            "help_body": null,
            "type": "options",
            "post_input_text": null,
            "title": "What type of coffee beans do you like the most?",
            "description": null,
            "error_message": null,
            "default_value": null,
            "min": null,
            "max": null,
            "options": [
                {
                    "label": "Arabica",
                    "value": "arabica"
                },
                {
                    "label": "Robusta",
                    "value": "robusta"
                }
            ],
            "answer": {}
        }
    ]
}
```

We need to do the same with the personal info step:

`/webforms/questions?form_step=2`

```json
{
    "data": [
        {
            "id": 3,
            "form_step": {
                "id": 2,
                "sort": 2,
                "slug": "personal-info",
                "menu_title": null,
                "title": "Personal info",
                "description": "",
                "completed": false
            },
            "sort": 3,
            "depends_on": null,
            "shown_when": null,
            "required": false,
            "slug": "first-name",
            "group_by": null,
            "group_by_description": null,
            "label_position": "left",
            "help_title": null,
            "help_body": null,
            "type": "text",
            "post_input_text": null,
            "title": "First Name",
            "description": null,
            "error_message": null,
            "default_value": null,
            "min": null,
            "max": null,
            "options": null,
            "answer": {}
        },
        {
            "id": 4,
            "form_step": {
                "id": 2,
                "sort": 2,
                "slug": "personal-info",
                "menu_title": null,
                "title": "Personal info",
                "description": "",
                "completed": false
            },
            "sort": 4,
            "depends_on": null,
            "shown_when": null,
            "required": false,
            "slug": "last-name",
            "group_by": null,
            "group_by_description": null,
            "label_position": "left",
            "help_title": null,
            "help_body": null,
            "type": "text",
            "post_input_text": null,
            "title": "Last Name",
            "description": null,
            "error_message": null,
            "default_value": null,
            "min": null,
            "max": null,
            "options": null,
            "answer": {}
        },
        {
            "id": 5,
            "form_step": {
                "id": 1,
                "sort": 1,
                "slug": "coffee",
                "menu_title": null,
                "title": "Coffee",
                "description": "",
                "completed": false
            },
            "sort": 4,
            "depends_on": null,
            "shown_when": null,
            "required": false,
            "slug": "birth-year",
            "group_by": null,
            "group_by_description": null,
            "label_position": "left",
            "help_title": null,
            "help_body": null,
            "type": "integer",
            "post_input_text": null,
            "title": "Birth Year",
            "description": null,
            "error_message": null,
            "default_value": null,
            "min": null,
            "max": null,
            "options": null,
            "answer": {}
        }
    ]
}
```

When a user needs to send an answer to a question, we will need to make a POST request to:

`/webforms/answers`

With the following payload:

```json
{
    "question_id": 2,
    "text": "arabica"
}
```

We will receive the question but now with an answer on it:

```json
{
    "data": {
        "id": 2,
        "form_step": {
            "id": 1,
            "sort": 1,
            "slug": "coffee",
            "menu_title": null,
            "title": "Coffee",
            "description": "",
            "completed": false
        },
        "sort": 2,
        "depends_on": null,
        "shown_when": null,
        "required": false,
        "slug": "what-type-of-coffee-beans-do-you-like-the-most",
        "group_by": null,
        "group_by_description": null,
        "label_position": "left",
        "help_title": null,
        "help_body": null,
        "type": "options",
        "post_input_text": null,
        "title": "What type of coffee beans do you like the most?",
        "description": null,
        "error_message": null,
        "default_value": null,
        "min": null,
        "max": null,
        "options": [
            {
                "label": "Arabica",
                "value": "arabica"
            },
            {
                "label": "Robusta",
                "value": "robusta"
            }
        ],
        "answer": {
            "id": 123,
            "user_id": 10,
            "question_id": 2,
            "text": "arabica",
            "confirmed": true
        }
    }
}
```

## Testing

Copy `phpunit.xml.dist` to `phpunit.xml`

```bash
cp phpunit.xml.dist phpunit.xml
```

Adapt or change the values in the next portion of code to your preferences:

```xml
    <php>
        <env name="DB_CONNECTION" value="mysql"/>
        <env name="DB_USERNAME" value="root"/>
        <env name="DB_PASSWORD" value=""/>
        <env name="DB_DATABASE" value="r64_webforms"/>
        <env name="DB_HOST" value="127.0.0.1"/>
        <env name="DB_PORT" value="3306"/>
    </php>
```

Create the database, in this case `r64_webforms`.

Execute:

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
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

## Acknowledgments

Thanks to [Spatie](https://spatie.be/) for the [Package Skeleton Laravel](https://github.com/spatie/package-skeleton-laravel).
