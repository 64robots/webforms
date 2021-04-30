---
title: 'Model Factories'
description: 'Model factories fluent interface'
position: 5
category: 'Structure'
---

## FormSection

### How to create a new one:

You can start to build a `FormSection` using:

```php
    FormSection::build('New Section')->save();
```

The slug for this question will be `new-section`. Sort will be the next one to the last in the `form_sections` table.

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

You can define a slug for the question, by default it is build based in the name.

```php
    FormSection::build('New Section title')
        ->slug('new-section-slug')
        ->save();
```

### menuTitle

You can define a menu title text (null by default):

```php
    FormSection::build('New Section title')
        ->menuTitle('new section menu title')
        ->save();
```

### description

You can define a description text for the section (null by default):

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

## Question
