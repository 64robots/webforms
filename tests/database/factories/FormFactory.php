<?php

use \Faker\Generator;
use R64\Webforms\Models\Form;

/* @var Illuminate\Database\Eloquent\Factory $factory */
$factory->define(Form::class, function (Generator $faker) {
    return [
        'title' => $faker->sentence,
        'slug' => $faker->unique()->slug,
    ];
});
