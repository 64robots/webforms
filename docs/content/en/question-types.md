---
title: 'Question Types'
description: 'Question Types'
position: 4
category: 'Structure'
---

## How it works

Right now we have several Question Types.  All of them are defined in `src/QuestionTypes`. Every Question type file has the following structure:

Constant `TYPE` define the type as an internal slug. Available ones right now are:

- age
- boolean
- date
- email
- integer
- money
- options
- percent
- phone
- text
- year-month

A `__construct` method who accepts a question. This question is needed in some fields to validate the values of the possible answer using properties like `min`, `max` or `options`.

The `getValidationRules()` public function is used to calculate the validation rule of the question in order to validate the answer to this kind of question. Right now it needs to be a string.

The `cast()` public function is used to convert the value (a string) in something more enrich like a `Carbon` instance, an `int`, a `boolean`.

The `castToFront()` public function is used to convert the cast value before it will be delivered to the front. 

## Adding a new one

At this moment you only can add a new one through a pull request. We are working in add the ability of defining your own question types (or override ours) in the config file.
