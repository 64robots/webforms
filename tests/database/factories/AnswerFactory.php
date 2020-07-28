<?php

use \Faker\Generator;
use R64\Webforms\Models\Answer;
use R64\Webforms\Models\Question;

/* @var Illuminate\Database\Eloquent\Factory $factory */
$factory->define(Answer::class, function (Generator $faker) {
    return [
        'user_id' => factory(config('webforms.user_model')),
        'question_id' => factory(Question::class),
        'revision' => 1,
        'text' => $faker->sentence,
    ];
});

// state: date
$factory->state(Answer::class, 'date', function (Generator $faker) {
    return [
        'question_id' => factory(Question::class)->state('date'),
        'text' => '2000-01-01',
    ];
});

// state: year-month
$factory->state(Answer::class, 'year-month', function (Generator $faker) {
    return [
        'question_id' => factory(Question::class)->state('year-month'),
        'text' => '2000-01',
    ];
});

// state: integer
$factory->state(Answer::class, 'integer', function (Generator $faker) {
    return [
        'question_id' => factory(Question::class)->state('integer'),
        'text' => '123',
    ];
});

// state: money
$factory->state(Answer::class, 'money', function (Generator $faker) {
    return [
        'question_id' => factory(Question::class)->state('money'),
        'text' => '12000',
    ];
});

// state: age
$factory->state(Answer::class, 'age', function (Generator $faker) {
    return [
        'question_id' => factory(Question::class)->state('age'),
        'text' => '50',
    ];
});

// state: percent
$factory->state(Answer::class, 'percent', function (Generator $faker) {
    return [
        'question_id' => factory(Question::class)->state('percent'),
        'text' => '50',
    ];
});

// state: boolean
$factory->state(Answer::class, 'boolean', function (Generator $faker) {
    return [
        'question_id' => factory(Question::class)->state('boolean'),
        'text' => '123',
    ];
});

// state: options
$factory->state(Answer::class, 'options', function (Generator $faker) {
    return [
        'question_id' => factory(Question::class)->state('options'),
        'text' => '123',
    ];
});

// state: text
$factory->state(Answer::class, 'text', function (Generator $faker) {
    return [
        'question_id' => factory(Question::class)->state('text'),
        'text' => '123',
    ];
});

// state: phone
$factory->state(Answer::class, 'phone', function (Generator $faker) {
    return [
        'question_id' => factory(Question::class)->state('phone'),
        'text' => '+55-555-5555555',
    ];
});

// state: email
$factory->state(Answer::class, 'email', function (Generator $faker) {
    return [
        'question_id' => factory(Question::class)->state('email'),
        'text' => 'email@example.com',
    ];
});

// state: personal_data
$factory->state(Answer::class, 'personal_data', function (Generator $faker) {
    return [
        'question_id' => factory(Question::class)->state('personal_data'),
        'text' => 'encrypted answer',
    ];
});

// state: not_personal_data
$factory->state(Answer::class, 'not_personal_data', function (Generator $faker) {
    return [
        'question_id' => factory(Question::class)->state('not_personal_data'),
        'text' => 'not encrypted answer',
    ];
});
