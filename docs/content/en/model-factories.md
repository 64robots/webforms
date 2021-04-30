---
title: 'Model Factories'
description: 'Model factories fluent interface'
position: 5
category: 'Structure'
---

## FormSection

### How to create a new form section:

You can start to build a `FormSection` using:

```php
    FormSection::build('New Section')->save();
```

The slug for this form section will be `new-section`. Sort will be the next one to the last in the `form_sections` table.

### Updating a form section:

You can update an existent form section using `updateFormSection`:

```php
    FormSection::updateFormSection($newSection)
        ->title('Second Section')
        ->save();
```

Other things you can customize are:

### sort

You can customize the position of the section:

```php
    FormSection::build('New Section title')
        ->sort(2)
        ->save();
```

### slug

You can define a slug for the form section, by default it is built based in the title.

```php
    FormSection::build('New Section title')
        ->slug('new-section-slug')
        ->save();
```

### menuTitle

You can define a menu title text for the form section(null by default):

```php
    FormSection::build('New Section title')
        ->menuTitle('new section menu title')
        ->save();
```

### description

You can define a description text for the form section (null by default):

```php
    FormSection::build('New Section title')
        ->description('new section description')
        ->save();
```

### All together

```php
    FormSection::build('New Section title')
        ->sort(2)
        ->slug('new-section-slug')
        ->menuTitle('new section menu title')
        ->description('new section description')
        ->save();
```

## FormStep

### How to create a new form step:

You can start to build a `FormStep` using:

```php
    FormStep::build($formSection, 'First Step Title')->save();
```

You need a form section `$formSection` created to add a form step.

The slug for this form step will be `first-step-title`. Sort will be the next one to the last in the `form_steps` table.

### Updating a form step:

You can update an existent form step using `updateFormStep`:

```php
    FormSection::updateFormStep($firstStep)
        ->formSection($anotherFormSection)
        ->save();
```

Other things you can customize are:

### sort

You can customize the position of the form step:

```php
    FormStep::build($formSection, 'New Step title')
        ->sort(2)
        ->save();
```

### slug

You can define a slug for the form step, by default it is built based in the title.

```php
    FormStep::build($formSection, 'New Step title')
        ->slug('new-step-slug')
        ->save();
```

### menuTitle

You can define the menu title text of the form step (null by default):

```php
    FormStep::build($formSection, 'New Step title')
        ->menuTitle('new step menu title')
        ->save();
```

### description

You can define the description of the form step (null by default)

```php
    FormStep::build($formSection, 'New Step title')
        ->description('new step description')
        ->save();
```

### isPersonalData

You can decide to encrypt the answers defining the step as a step that contains personal data, it is not encrypted by default:

```php
    FormStep::build($formSection, 'New Step title')
        ->isPersonalData(1)
        ->save();
```

### All together

```php
    FormStep::build($formSection, 'New Step title')
        ->sort(2)
        ->slug('new-step-slug')
        ->menuTitle('new step menu title')
        ->description('new step description')
        ->isPersonalData(1)
        ->save();
```

## Question
