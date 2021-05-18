---
title: 'Admin Endpoints'
description: 'Endpoints of the package to create/update/delete forms, form steps, questions'
position: 7
category: 'Structure'
---

For this section we will guess that you have the following in an admin routes file:

```php
Route::webformsAdmin('webforms-admin');
```

## Form

### POST

You can create a new form making a `POST` request to:

`/webforms-admin/forms`

with the following payload:

```json
{
    "sort": 2,
    "slug": "new-form",
    "menu_title": "New form title for the menu",
    "title": "New form title",
    "description": "An awesome new form"
}
```

The only required field is `title`.

The behavior will be the same described in <nuxt-link to="/model-factories#form">Model Factories</nuxt-link>.

The response will be the new form resource:

```json
{
    "data": {
        "id": 1,
        "sort": 2,
        "slug": "new-form",
        "menu_title": "New form title for the menu",
        "title": "New form title",
        "description": "An awesome new form",
        "completed": false
    }
}
```

### PUT

You can update a form making a `PUT` request to:

`/webforms-admin/forms/{formId}`

with the following payload:

```json
{
    "sort": 1,
    "slug": "edited-new-form",
    "menu_title": "Edited new form title for the menu",
    "title": "Edited new form title",
    "description":"Editing an awesome new form"
}
```

The behavior will be the same described in <nuxt-link to="/model-factories#form">Model Factories</nuxt-link>.

The response will be the updated form resource:

```json
{
    "data": {
        "id": 1,
        "sort": 1,
        "slug": "edited-new-form",
        "menu_title": "Edited new form title for the menu",
        "title": "Edited new form title",
        "description": "Editing an awesome new form",
        "completed": false
    }
}
```

#### DELETE

You can delete a form making a `DELETE` request to:

`/webforms-admin/forms/{formId}`

The response will be the form resource just deleted:

```json
{
    "data": {
        "id": 1,
        "sort": 1,
        "slug": "a-form",
        "menu_title": "First form",
        "title": "A Form",
        "description": "This is the first form",
        "completed": false
    }
}
```

## Form Step

### POST

You can create a new form step making a `POST` request to:

`/webforms-admin/form-steps`

with the following payload:

```json
{
    "form_id": 1,
    "sort": 1,
    "slug": "new-form-step",
    "menu_title": "New form step title for the menu",
    "title": "New form step title",
    "description": "An awesome new form step",
    "is_personal_data": 1
}
```

The only required fields are `form_id` and `title`.

The behavior will be the same described in <nuxt-link to="/model-factories#formstep">Model Factories</nuxt-link>.

The response will be the new form step resource:

```json
{
    "data": {
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
        "slug": "new-form-step",
        "menu_title": "New form step title for the menu",
        "title": "New form step title",
        "description": "An awesome new form step",
        "completed": false
    }
}
```

### PUT

You can update a form step making a `PUT` request to:

`/webforms-admin/form-steps/{formStepId}`

with the following payload:

```json
{
    "form_id": 1,
    "sort": 1,
    "slug": "edited-new-form-step",
    "menu_title": "Edited new form step title for the menu",
    "title": "Edited new form step title",
    "description": "Editing an awesome new form step",
    "is_personal_data": 1
}
```

The behavior will be the same described in <nuxt-link to="/model-factories#formstep">Model Factories</nuxt-link>.

The response will be the updated form step resource:

```json
{
    "data": {
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
        "slug": "edited-new-form-step",
        "menu_title": "Edited new form step title for the menu",
        "title": "Edited new form step title",
        "description": "Editing an awesome new form step",
        "completed": false
    }
}
```

#### DELETE

You can delete a form step making a `DELETE` request to:

`/webforms-admin/form-steps/{formStepId}`

The response will be the form step resource just deleted:

```json
{
    "data": {
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
        "slug": "new-form-step",
        "menu_title": "New form step title for the menu",
        "title": "New form step title",
        "description": "An awesome new form step",
        "completed": false
    }
}
```

## Question

### POST

You can create a new question making a `POST` request to:

`/webforms-admin/questions`

with the following payload:

```json
{
    
}
```

### PUT

You can update a question making a `PUT` request to:

`/webforms-admin/questions/{questionId}`

with the following payload:

```json
{
    
}
```

#### DELETE

You can delete a question making a `DELETE` request to:

`/webforms-admin/questions/{questionId}`
