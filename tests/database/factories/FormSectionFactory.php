<?php

use \Faker\Generator;
use R64\Webforms\Models\FormSection;

/* @var Illuminate\Database\Eloquent\Factory $factory */
$factory->define(FormSection::class, function (Generator $faker) {
    return [
        'title' => $faker->sentence,
        'slug' => $faker->unique()->slug,
    ];
});
