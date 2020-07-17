<?php

use \Faker\Generator;
use R64\Webforms\Models\Section;

/* @var Illuminate\Database\Eloquent\Factory $factory */
$factory->define(Section::class, function (Generator $faker) {
    return [
        'title' => $faker->sentence,
        'slug' => $faker->unique()->slug,
    ];
});
