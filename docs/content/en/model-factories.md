---
title: 'Model Factories'
description: 'Model factories fluent interface'
position: 5
category: 'Structure'
---

## Form

### How to create a new form:

You can start to build a `Form` using:

```php
    Form::build('New Form')->save();
```

The slug for this form will be `new-form`. Sort will be the next one to the last in the `forms` table.

### Updating a form:

You can update an existent form using `updateForm`:

```php
    Form::updateForm($newForm)
        ->title('Second Form')
        ->save();
```

Other things you can customize are:

### sort

You can customize the position of the form:

```php
    Form::build('New Form title')
        ->sort(2)
        ->save();
```

### slug

You can define a slug for the form, by default it is built based in the title.

```php
    Form::build('New Form title')
        ->slug('new-form-slug')
        ->save();
```

### menuTitle

You can define a menu title text for the form (null by default):

```php
    Form::build('New Form title')
        ->menuTitle('new form menu title')
        ->save();
```

### description

You can define a description text for the form (null by default):

```php
    Form::build('New Form title')
        ->description('new form description')
        ->save();
```

### All together

```php
    Form::build('New Form title')
        ->sort(2)
        ->slug('new-form-slug')
        ->menuTitle('new form menu title')
        ->description('new form description')
        ->save();
```

## FormStep

### How to create a new form step:

You can start to build a `FormStep` using:

```php
    FormStep::build($form, 'First Step Title')->save();
```

You need a form `$form` created to add a form step.

The slug for this form step will be `first-step-title`. Sort will be the next one to the last in the `form_steps` table.

### Updating a form step:

You can update an existent form step using `updateFormStep`:

```php
    Form::updateFormStep($firstStep)
        ->form($anotherForm)
        ->save();
```

Other things you can customize are:

### sort

You can customize the position of the form step:

```php
    FormStep::build($form, 'New Step title')
        ->sort(2)
        ->save();
```

### slug

You can define a slug for the form step, by default it is built based in the title.

```php
    FormStep::build($form, 'New Step title')
        ->slug('new-step-slug')
        ->save();
```

### menuTitle

You can define the menu title text of the form step (null by default):

```php
    FormStep::build($form, 'New Step title')
        ->menuTitle('new step menu title')
        ->save();
```

### description

You can define the description of the form step (null by default)

```php
    FormStep::build($form, 'New Step title')
        ->description('new step description')
        ->save();
```

### isPersonalData

You can decide to encrypt the answers defining the step as a step that contains personal data, it is not encrypted by default:

```php
    FormStep::build($form, 'New Step title')
        ->isPersonalData(1)
        ->save();
```

### All together

```php
    FormStep::build($form, 'New Step title')
        ->sort(2)
        ->slug('new-step-slug')
        ->menuTitle('new step menu title')
        ->description('new step description')
        ->isPersonalData(1)
        ->save();
```

## Question

### How to create a new question:

You can start to build a `Question` using:

```php
    Question::build($formStep, 'A Question')->save();
```

You need a form step `$formStep` created to add a question.

The slug for this question will be `a-question`. Sort will be the next one to the last in the `questions` table (for this step).

### Updating a form question:

You can update an existent form step using `updateFormStep`:

```php
    Question::updateQuestion($question)
      ->title('Second Question')
      ->save();
```

Other things you can customize are:

### sort

You can customize the position of the question:

```php
    Question::build($formStep, 'New question title')
        ->sort(2)
        ->save();
```

### slug

You can customize the slug of the question:

```php
    Question::build($formStep, 'New question title')
        ->slug('new-question-slug')
        ->save();
```

### dependsOn

You should show that question to the frontend only when parent question is answered with a particular value:

```php
    Question::build($formStep, 'New question title')
        ->dependsOn($parentQuestion)
        ->save();
```

`$parentQuestion` is the question that makes this question appear.

### shownWhen

Add the values for the parent question who make the son question to appear:

```php
    Question::build($formStep, 'New question title')
        ->dependsOn($parentQuestion)
        ->shownWhen([10, 30])
        ->save();
```

Another example could be:

```php
    Question::build($formStep, 'What hobbies do you have')
        ->dependsOn($doYouHaveHobbiesQuestion)
        ->shownWhen([true])
        ->save();
```

### description

You can add a description for a question. Like a little text that could appear near the title.

```php
    Question::build($formStep, 'New question title')
        ->description('new question description')
        ->save();
```

### groupBy

You could use that in the frontend to group different questions of the same step. I encourage you to use a constant in order to avoid mistakes.

```php
    Question::build($formStep, 'New question title')
        ->groupBy('A group by text')
        ->save();
```

### groupByDescription

You could use that in the frontend to get the description of the group questions. I encourage you to use a constant is order to avoid mistakes.

```php
    Question::build($formStep, 'New question title')
        ->groupBy('A group by text')
        ->groupByDescription('A description for a group by text')
        ->save();
```

### labelPosition

You can tell to the frontend where do you want to put the label of the question using this property.

```php
    Question::build($formStep, 'New question title')
        ->labelPosition('left')
        ->save();
```

### helpTitle

If you want to add a help tooltip this can be the text:

```php
    Question::build($formStep, 'New question title')
        ->helpTitle('A help title')
        ->save();
```

### helpBody

Guess that your help tooltip has title and body. You can use that to transmit the body:

```php
    Question::build($formStep, 'New question title')
        ->helpTitle('A help title')
        ->helpBody('A help body')
        ->save();
```

### postInputText

You can use this property to set a text what will appear next to the input like the currency symbol.

```php
    Question::build($formStep, 'New question title')
        ->postInputText('A post input text')
        ->save();
```

### errorMessage

You can customize the error message from the backend when the validation fails, this text will be send to the frontend.

```php
    Question::build($formStep, 'New question title')
        ->errorMessage('A custom error message')
        ->save();
```

### defaultValue

You can define a default value for a question. This will be populated when the form steps are added to the model using `HasWebForms` trait.

```php
    Question::build($formStep, 'New question title')
        ->errorMessage('A custom error message')
        ->save();
```

### min

You can define a min value for a number question. This will be used in the validation

```php
    Question::build($formStep, 'New question title')
        ->min(10)
        ->save();
```

### max

You can define a max value for a number question. This will be used in the validation

```php
    Question::build($formStep, 'New question title')
        ->max(30)
        ->save();
```
### type

You can choose the question's type between `age`, `boolean`, `date`, `email`, `integer`, `money`, `options`, `percent`, `phone`, `text` and `year-month`. It will be a `text` question by default.

```php
    Question::build($formStep, 'New question title')
        ->type('integer')
        ->save();
```

### options

If it's an `options` question you can add the options for the question using this method:

```php
    Question::build($formStep, 'New question title')
        ->type('options')
        ->options([10, 20, 30])
        ->save();
```

### required

You can tell to the frontend that this question is required using this method. This will be used to mark the step as complete too.

```php
    Question::build($formStep, 'New question title')
        ->required(1)
        ->save();
```

### All together

```php
    Question::build($formStep, 'New question title')
        ->sort(2)
        ->slug('new-question-slug')
        ->dependsOn($parentQuestion)
        ->description('new question description')
        ->groupBy('A group by text')
        ->groupByDescription('A description for a group by text')
        ->labelPosition('left')
        ->helpTitle('A help title')
        ->helpBody('A help body')
        ->postInputText('A post input text')
        ->errorMessage('A custom error message')
        ->defaultValue('A default value')
        ->min(10)
        ->max(30)
        ->shownWhen([10, 30])
        ->type('options')
        ->options([10, 20, 30])
        ->required(1)
        ->save();
```
