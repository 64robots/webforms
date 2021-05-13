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
    
}
```

### PUT

You can update a form section making a `PUT` request to:

`/webforms-admin/form-sections/{formSectionId}`

with the following payload:

```json
{
    
}
```

#### DELETE

You can delete a form section making a `DELETE` request to:

`/webforms-admin/form-sections/{formSectionId}`

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
