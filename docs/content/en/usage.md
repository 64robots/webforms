---
title: Usage
description: 'How to use'
position: 2
category: 'Introduction'
---

## 1. Route Macro

Add that to your routes file:

```php
Route::webforms('webforms');
```

This make possible that you can use your own middleware maybe for make the forms only available to a group of the users. You can choice the root prefix for this group of routes changing the value of the parameter.

If you want routes to create FormSections, FormSteps and Questions, add that under the appropriate middleware in your routes file:

```php
Route::webformsAdmin('webforms-admin');
```

## 2. `HasWebForms` trait

Add `HasWebForms` trait in your user entity.

We have a relationship between `FormStep` and `User`. The table name is `form_step_user`. A user only can see the steps that has assigned. `form_step_user` has a `completed` field. Answer model marks a step as completed when all the required question are filled. You don't need to worry about that.

The trait add to the users the following methods:

- `formSteps`: Relationship to `FormStep`. You can use that to retrieve the associated form step to a user.

- `addFormSteps`: This method add all the form steps to the user. A user only can see the associated `FormSteps`. If you pass only a form step then you will add only that form step to the user. This method adds the default answers to the user too. If you don't provide any form steps then all the present form steps will be added to the user.

- `markFormStepAsUncompleted`: It's done by the code automatically, but you can mark a FormStep as uncompleted using this method.

- `markFormStepAsCompleted`: It's done by the code automatically, but you can mark a FormStep as Completed using this method.

- `answers`: Relationship to `Answer`. You can use that to retrieve the associated answer to a user.

- `addDefaultAnswers`: It's done by the code automatically, but you can add a fictional answer to a question with an associated default value using this method.

## 3. Create Structure

Create `FormSections`, `FormSteps` and `Questions` using <nuxt-link to="/model-factories">Model Factories</nuxt-link> or the <nuxt-link to="/admin-endpoints">Admin Endpoints</nuxt-link>

## 4. Config

You can alter some values in the config file.

```php
php artisan vendor:publish --provider="R64\Webforms\WebformsServiceProvider" --tag="config"
```

This command adds a config file with the following values:

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

- `date_format` is used to validate and cast a `date` question type.

- `year_month_format` is used to validate and cast a `year-month` question type.

- `user_model` is used to declare the location of the user model in your app.

- `fields_to_be_confirmed` mark that kind of question's answer with confirmed as false waiting for another process like a confirmation email, sms code or something like that.

## Events

`AnswerCreated` is launched when the user creates an answer. If you need to make things in base to that then you can use a listener. The package launches the same event when the user updates an answer. The payload of the event is the user and the answer.
