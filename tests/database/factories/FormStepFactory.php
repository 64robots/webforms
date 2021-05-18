<?php

use \Faker\Generator;
use R64\Webforms\Models\Form;
use R64\Webforms\Models\FormStep;

/* @var Illuminate\Database\Eloquent\Factory $factory */
$factory->define(FormStep::class, function (Generator $faker) {
    return [
        'form_id' => factory(Form::class),
        'title' => $faker->sentence,
        'slug' => $faker->unique()->slug,
    ];
});
