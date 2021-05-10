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

## Form Section

### GET

You can obtain the form sections making a `GET` request to the endpoint:

`/webforms/form-sections`

It will return a list of sorted form sections in a response like:

```json
{
    "data":  [
        {
            "id":  1,
            "sort": 1,
            "slug": "a-form-section",                
            "menu_title":  "First section",
            "title":  "A Form Section",
            "description": "This is the first form section",
            "completed":  false
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
    "data":  [
        {
            "id": 1,
            "form_section": {
                "id":  1,
                "sort": 1,
                "slug": "a-form-section",
                "menu_title":  "First section",
                "title":  "A Form Section",
                "description": "This is the first form section",
                "completed":  false
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

### PUT

A question with a default value creates a fictional answer. If you want to persist those answers as real ones you need to make a `PUT` request to this endpoint:

`/webforms/form-steps`

It will return the same as the `GET` request shown above.

Alternatively you can make `$formStep->markFictionalAnswersAsRealFor($user)`.

## Question

## Answer

