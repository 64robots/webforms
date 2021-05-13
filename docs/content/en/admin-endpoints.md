---
title: 'Admin Endpoints'
description: 'Endpoints of the package to create/update/delete form sections, form steps, questions'
position: 7
category: 'Structure'
---

For this section we will guess that you have the following in an admin routes file:

```php
Route::webformsAdmin('webforms-admin');
```

## Form Section

### POST

You can create a new form section making a `POST` request to:

`/webforms-admin/form-sections`

with the following payload:

```json
{
    "sort": 2,
    "slug": "new-form-section",
    "menu_title": "New form section title for the menu",
    "title": "New form section title",
    "description": "An awesome new form section"
}
```

The behavior will be the same described in <nuxt-link to="/model-factories#formsection">Model Factories</nuxt-link>.

The response will be the new form section resource:

```json
{
    "data": {
        "id": 1,
        "sort": 2,
        "slug": "new-form-section",
        "menu_title": "New form section title for the menu",
        "title": "New form section title",
        "description": "An awesome new form section",
        "completed": false
    }
}
```

### PUT

You can update a form section making a `PUT` request to:

`/webforms-admin/form-sections/{formSectionId}`

with the following payload:

```json
{
    "sort": 1,
    "slug": "edited-new-form-section",
    "menu_title": "Edited new form section title for the menu",
    "title": "Edited new form section title",
    "description":"Editing an awesome new form section"
}
```

The behavior will be the same described in <nuxt-link to="/model-factories#formsection">Model Factories</nuxt-link>.

The response will be the updated form section resource:

```json
{
    "data": {
        "id": 1,
        "sort": 1,
        "slug": "edited-new-form-section",
        "menu_title": "Edited new form section title for the menu",
        "title": "Edited new form section title",
        "description": "Editing an awesome new form section",
        "completed": false
    }
}
```

#### DELETE

You can delete a form section making a `DELETE` request to:

`/webforms-admin/form-sections/{formSectionId}`

The response will be the form section resource just deleted:

```json
{
    "data": {
        "id": 1,
        "sort": 1,
        "slug": "a-form-section",
        "menu_title": "First section",
        "title": "A Form Section",
        "description": "This is the first form section",
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
    
}
```

### PUT

You can update a form step making a `PUT` request to:

`/webforms-admin/form-steps/{formStepId}`

with the following payload:

```json
{
    
}
```

#### DELETE

You can delete a form step making a `DELETE` request to:

`/webforms-admin/form-steps/{formStepId}`

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
