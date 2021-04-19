---
title: 'Models'
description: 'Models'
position: 3
category: 'Structure'
---

## General structure

FormSection -> FormStep -> Question -> Answer.

A `FormSection` is like a big form with form steps. A form section has many form steps.

A `FormStep` belongs to a form section. A form step has many questions.

A `Question` belongs to a form step. A question has answers.

An `Answer` belongs to a user. An answer also belongs to a question.

## FormSection

Attributes:

- `name`.

- `description`.

- `menu_title` you can use it in a menu.

- `slug` is a unique string.

- `sort` is a numerical value. Can be used to sort these form sections.

## FormStep

Attributes:

- `form_section_id` is the id of the `FormSection` it belongs to.

- `title`.

- `description`.

- `is_personal_data` is a boolean about encrypt the data in the database. If it's true the values will be stored as encrypted values.

- `menu_title` you can use it in a menu.

- `slug` is a unique string.

- `sort` is a numerical value. Can be used to sort these form steps.

## Question

Attributes:

- `form_step_id` is the id of the `FormStep` it belongs to.

- `depends_on` you can have a use case where a question A (this question) is only shown when other question B has a certain value or values. This attribute is the id of the question B.

- `shown_when` have the values when that field must be shown. It is a json value like `'shown_when' => json_encode([1, 2], true)` or `'shown_when' => json_encode([true], true)`.

- `slug` is a unique string.

- `sort` is a numerical value. Can be used to sort these questions.

- `group_by` is used if you want to put some questions together under the same title in a step. It's like a "section" in a step. It's likely a constant string.

- `group_by_description` is used if you have some description for the group of questions. It's likely a constant string.

- `label_position` is can be used to move the label to the `left`, `right` or `top`.

- `help_title` is used to show some extra info about this question to the user.

- `help_body` is the body of the help that you'll show to the user.

- `type` is the type of the question. You can select, at this moment, these types (all that you can find in the class QuestionTypes): `date`, `year-month`, `integer`, `money`, `age`, `percent`, `boolean`, `options`, `text`, `phone`, `email`.

- `post_input_text` is a text that will be shown after the input field like `/month`, `/year`...

- `title` is the text of the question.
  
- `description` is the description of the question.

- `error_message` is the text that we'll send to the front. If it's null then you will get a default one.

- `default_value` is used when the question has a default_value that is needed to populate before user answers this question.

- `min` is used when an integer field is validated.

- `max` is used when an integer field is being validated

- `options` is used when the question is of type options. It's a json field. Then you should add, if you are working with the DB facade, as `json_encode(['slug' => 'title', 'slug2' => 'title2'], true)`;

- `required` is used when you want to mark the question as required.

## Answer

Attributes:

- `user_id` is the id of the `User` it belongs to.

- `question_id` is the id of the `Question` it belongs to.

- `text` is the value of the answer. It's cast to the correct type based in the question type.

- `confirmed` is a boolean that you can use if you want to confirm an answer in other way like an email, cellphone. Fields that need to be confirmed are defined in the config key `fields_to_be_confirmed` settled as `email`, and `phone` by default.

- `is_real` is used to mark an answer as created from the `default_value` defined in the question. Then it's not an answer provided by the user.

- `is_current` when a user answers a question the package stores the answer and mark it with `is_current` as true. The package marks the previous ones with `is_current` as false.
