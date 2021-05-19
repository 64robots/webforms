---
title: 'User Endpoints'
description: 'Endpoints of the package to obtain structure and answer the questions'
position: 6
category: 'Structure'
---

For this section we will guess that you have the following in an auth routes file:

```php
Route::webforms('webforms');
```

## Form

### GET

You can obtain the forms making a `GET` request to the endpoint:

`/webforms/forms`

It will return a list of sorted forms. You will only obtain the forms where the user have steps added in a response like:

```json
{
    "data": [
        {
            "id": 1,
            "sort": 1,
            "slug": "a-form",
            "menu_title": "First form",
            "title": "A Form",
            "description": "This is the first form",
            "completed": false
        }
    ]
}
```

## Form Step

### GET

You can obtain the form steps for a user making a `GET` request to the endpoint:

`/webforms/form-steps`

It will return a list of sorted form steps in a response like:

```json
{
    "data": [
        {
            "id": 1,
            "form": {
                "id": 1,
                "sort": 1,
                "slug": "a-form",
                "menu_title": "First form",
                "title": "A Form",
                "description": "This is the first form",
                "completed": false
            },
            "sort": 1,
            "slug": "a-form-step",
            "menu_title": "First Form Step",
            "title": "A Form Step",
            "description": "This is the first form step",
            "completed": false
        }
    ]
}
```

You can filter it down by form using `form` query parameter like:

`/webforms/form-steps?form=1`

It only will show form that user can see.

### PUT

A question with a default value creates a fictional answer. If you want to persist those answers as real ones you need to make a `PUT` request to this endpoint:

`/webforms/form-steps/{formStepId}`

It will return the same as the `GET` request shown above.

Alternatively you can make `$formStep->markFictionalAnswersAsRealFor($user)`.

## Question

### GET

You can obtain the questions for a user making a `GET` request to the endpoint:

`/webforms/questions`

If you want only questions for a singular form step you can pass it as a query parameter:

`/webforms/questions?form_step=1`

It will return a list of sorted questions in a response like:

```json
{
    "data": [
        {
            "id": 2,
            "form_step": {
                "id": 1,
                "sort": 1,
                "slug": "a-form-step",
                "menu_title": "First Form Step",
                "title": "A Form Step",
                "description": "This is the first form step",
                "completed": false
            },
            "sort": 2,
            "depends_on": 1,
            "shown_when": [true],
            "required": true,
            "slug": "first-question",
            "group_by": "Personal Info",
            "group_by_description": "We need some personal information from you",
            "label_position": "left",
            "help_title": "Your month of birthday",
            "help_body": "The month you were born",
            "type": "options",
            "post_input_text": ":season:",
            "title": "What month do you birth",
            "description": "Please, provide the month of your birthday",
            "error_message": "This is an incorrect month",
            "default_value": "june",
            "min": null,
            "max": null,
            "options": [
                {
                    "label": "January",
                    "value": "january"
                },
                {
                    "label": "June",
                    "value": "june"
                },
                {
                    "label": "December",
                    "value": "december"
                }
            ],
            "answer": {
                "id": 123,
                "user_id": 10,
                "question_id": 2,
                "text": "june",
                "confirmed": true
            }
        }
    ]
}
```

## Answer

### POST

You can answer/update an answer to a question by a user making a `POST` request to the endpoint:

`/webforms/answers`

With the following payload:

```json
{
    "question_id": 2,
    "text": "june"
}
```

The response will be the question with the answer:

```json
{
    "data": {
        "id": 2,
        "form_step": {
            "id": 1,
            "sort": 1,
            "slug": "a-form-step",
            "menu_title": "First Form Step",
            "title": "A Form Step",
            "description": "This is the first form step",
            "completed": false
        },
        "sort": 2,
        "depends_on": 1,
        "shown_when": [true],
        "required": true,
        "slug": "first-question",
        "group_by": "Personal Info",
        "group_by_description": "We need some personal information from you",
        "label_position": "left",
        "help_title": "Your month of birthday",
        "help_body": "The month you were born",
        "type": "options",
        "post_input_text": ":season:",
        "title": "What month do you birth",
        "description": "Please, provide the month of your birthday",
        "error_message": "This is an incorrect month",
        "default_value": "june",
        "min": null,
        "max": null,
        "options": [
            {
                "label": "January",
                "value": "january"
            },
            {
                "label": "June",
                "value": "june"
            },
            {
                "label": "December",
                "value": "december"
            }
        ],
        "answer": {
            "id": 123,
            "user_id": 10,
            "question_id": 2,
            "text": "june",
            "confirmed": true
        }
    }
}
```
