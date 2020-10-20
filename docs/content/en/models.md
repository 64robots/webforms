---
title: 'Models'
description: 'Models'
position: 3
category: 'Structure'
---

## General structure

FormSection -> FormStep -> Question.

A FormSection is like a great form with FormSteps, then, a FormSection has many Form Steps.

A FormStep belongs to a FormSection. A FormStep has many Questions.

A Question belongs to a FormStep. A Question has Answers.

An answer belongs to a User, and a Question.

## FormSection

Attributes:

- `name`.

- `description`.

- `menu_title` you can use it in a menu.

- `slug` is a unique string.

- `sort` is a numerical value for sort this sections.

## FormStep

Attributes:

- `form_section_id` is the id of the FormSection it belongs to.
- `title`.

- `description`.

- `is_personal_data` is a boolean about encrypt the data or not in the database. If it's true the values will be stored as encrypted values.

- `menu_title` you can use it in a menu.

- `slug` is a unique string.

- `sort` is a numerical value for sort this steps.

## Question

Attributes:

- `form_step_id` is the id of the FormStep it belongs to.

- `depends_on` you can have a use case where a question A (this question) is only showed when other question B has a certain value or values. This attribute is the id of the question B.

- `showed_when` have the values when that field must be showed. It is a json value like `'showed_when' => json_encode([1, 2], true)` or `'showed_when' => json_encode([true], true)`.

- `slug` is a unique string.

- `sort` is a numerical value for sort this steps.

- `group_by` is used if you want to put some questions together under the same title in a step. It's like a "section" in a step. It's likely a constant string.

- `group_by_description` is used if you have some description for the group of questions. It's likely a constant string.

- `label_position` is used to move the label to the `left`, `right` or `top`.

- `help_title` is used to show some extra info about this question to the user.

- `help_body` is the body of the help that you show to the user.

- `type` is the type of the question. You can select, at this moment, these types (all that you can found in the class QuestionTypes): `date`, `year-month`, `integer`, `money`, `age`, `percent`, `boolean`, `options`, `text`, `phone`, `email`.

- `post_input_text` is a text that will be shown after the input field like `/month`, `/year`...

- `title` is the text of the question.
- `description` if you need some description to this question.

- `error_message` is the text that we will send to the front. If it's null you will get a default one.

- `default_value` is used when the question has a default_value that is needed to populate before user answers that.

- `min` is used for integer fields to mark a min for the validation.

- `max` is used for integer fields to mark a max for the validation.

- `options` is used when the question is of type options. It's a json field. Then you should add, if you are working with the DB facade, as `json_encode(['slug' => 'title', 'slug2' => 'title2'], true)`;

- `required` is used when you want to mark the question as required.

## Answer

Attributes:

- `user_id` is the id of the User it belongs to.

- `question_id` is the id of the User it belongs to.

- `text` is the value of the answer. It's casted to the correct type based in the question type.

- `confirmed` is a boolean that you can use if you want to confirm an answer in other way like an email, cellphone. Fields that need to be confirmed are defined in the config key 'fields_to_be_confirmed' settled as 'email', and 'phone' by default.

- `is_real` is used to mark an answer as created from `default_value` in question not from the user.

- `is_current` when a user answers a question the package stores the answer and mark it with is_current as true. The package marks the previous ones with is_current as false.
