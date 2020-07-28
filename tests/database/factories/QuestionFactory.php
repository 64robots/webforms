<?php

use \Faker\Generator;
use R64\Webforms\Models\FormStep;
use R64\Webforms\Models\Question;
use R64\Webforms\Models\QuestionTypes;

/* @var Illuminate\Database\Eloquent\Factory $factory */
$factory->define(Question::class, function (Generator $faker) {
    return [
        'form_step_id' => factory(FormStep::class),
        'sort' => $faker->unique()->numberBetween(1, 200),
        'slug' => $faker->unique()->slug,
        'type' => 'text',
        'title' => $faker->sentence,
    ];
});

// state: date
$factory->state(Question::class, 'date', function (Generator $faker) {
    return [
        'type' => QuestionTypes::DATE_TYPE,
    ];
});

// state: year-month
$factory->state(Question::class, 'year-month', function (Generator $faker) {
    return [
        'type' => QuestionTypes::YEAR_MONTH_TYPE,
    ];
});

// state: integer
$factory->state(Question::class, 'integer', function (Generator $faker) {
    return [
        'type' => QuestionTypes::INTEGER_TYPE,
    ];
});

// state: money
$factory->state(Question::class, 'money', function (Generator $faker) {
    return [
        'type' => QuestionTypes::MONEY_TYPE,
    ];
});

// state: age
$factory->state(Question::class, 'age', function (Generator $faker) {
    return [
        'type' => QuestionTypes::AGE_TYPE,
    ];
});

// state: percent
$factory->state(Question::class, 'percent', function (Generator $faker) {
    return [
        'type' => QuestionTypes::PERCENT_TYPE,
    ];
});

// state: boolean
$factory->state(Question::class, 'boolean', function (Generator $faker) {
    return [
        'type' => QuestionTypes::BOOLEAN_TYPE,
    ];
});

// state: options
$factory->state(Question::class, 'options', function (Generator $faker) {
    return [
        'type' => QuestionTypes::OPTIONS_TYPE,
        'options' => [
            'one' => 'One',
            'two' => 'Two',
            'three' => 'Three',
        ],
    ];
});

// state: text
$factory->state(Question::class, 'text', function (Generator $faker) {
    return [
        'type' => QuestionTypes::TEXT_TYPE,
    ];
});

// state: phone
$factory->state(Question::class, 'phone', function (Generator $faker) {
    return [
        'type' => QuestionTypes::PHONE_TYPE,
    ];
});

// state: email
$factory->state(Question::class, 'email', function (Generator $faker) {
    return [
        'type' => QuestionTypes::EMAIL_TYPE,
    ];
});

// state: personal_data
$factory->state(Question::class, 'personal_data', function (Generator $faker) {
    return [
        'form_step_id' => factory(FormStep::class)->create(['is_personal_data' => true])->id,
        'type' => QuestionTypes::TEXT_TYPE,
    ];
});

// state: not_personal_data
$factory->state(Question::class, 'not_personal_data', function (Generator $faker) {
    return [
        'form_step_id' => factory(FormStep::class)->create(['is_personal_data' => false])->id,
        'type' => QuestionTypes::TEXT_TYPE,
    ];
});
