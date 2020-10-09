<?php

use \Faker\Generator;
use R64\Webforms\Models\FormStep;
use R64\Webforms\Models\Question;
use R64\Webforms\QuestionTypes\AgeType;
use R64\Webforms\QuestionTypes\BooleanType;
use R64\Webforms\QuestionTypes\DateType;
use R64\Webforms\QuestionTypes\EmailType;
use R64\Webforms\QuestionTypes\IntegerType;
use R64\Webforms\QuestionTypes\MoneyType;
use R64\Webforms\QuestionTypes\OptionsType;
use R64\Webforms\QuestionTypes\PercentType;
use R64\Webforms\QuestionTypes\PhoneType;
use R64\Webforms\QuestionTypes\TextType;
use R64\Webforms\QuestionTypes\YearMonthType;

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
        'type' => DateType::TYPE,
    ];
});

// state: year-month
$factory->state(Question::class, 'year-month', function (Generator $faker) {
    return [
        'type' => YearMonthType::TYPE,
    ];
});

// state: integer
$factory->state(Question::class, 'integer', function (Generator $faker) {
    return [
        'type' => IntegerType::TYPE,
    ];
});

// state: money
$factory->state(Question::class, 'money', function (Generator $faker) {
    return [
        'type' => MoneyType::TYPE,
    ];
});

// state: age
$factory->state(Question::class, 'age', function (Generator $faker) {
    return [
        'type' => AgeType::TYPE,
        'min' => '18',
        'max' => '70',
    ];
});

// state: percent
$factory->state(Question::class, 'percent', function (Generator $faker) {
    return [
        'type' => PercentType::TYPE,
        'min' => '0',
        'max' => '100',
    ];
});

// state: boolean
$factory->state(Question::class, 'boolean', function (Generator $faker) {
    return [
        'type' => BooleanType::TYPE,
    ];
});

// state: options
$factory->state(Question::class, 'options', function (Generator $faker) {
    return [
        'type' => OptionsType::TYPE,
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
        'type' => TextType::TYPE,
    ];
});

// state: phone
$factory->state(Question::class, 'phone', function (Generator $faker) {
    return [
        'type' => PhoneType::TYPE,
        'min' => '13',
    ];
});

// state: email
$factory->state(Question::class, 'email', function (Generator $faker) {
    return [
        'type' => EmailType::TYPE,
    ];
});

// state: personal_data
$factory->state(Question::class, 'personal_data', function (Generator $faker) {
    return [
        'form_step_id' => factory(FormStep::class)->create(['is_personal_data' => true])->id,
        'type' => TextType::TYPE,
    ];
});

// state: not_personal_data
$factory->state(Question::class, 'not_personal_data', function (Generator $faker) {
    return [
        'form_step_id' => factory(FormStep::class)->create(['is_personal_data' => false])->id,
        'type' => TextType::TYPE,
    ];
});
